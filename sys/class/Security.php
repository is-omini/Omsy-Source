<?php
/**
 * CMS Class
 * 28/07/25 - Create file
 */
class Security
{
	private $Settings;

	function __construct($CMS)
	{
		define('CMS', $CMS);

		//include(".".$CMS->Path->SysClass."/Request.php");

		//$_GET = new Request($_GET);
		//$_POST = new Request($_POST);
		//$_REQUEST = new Request($_REQUEST);

		// Mise en sercurité des information de l'URL
		// TMP: Ajout dans un tableau CMS->Args...
		$_SERVER['REQUEST_URI'] = explode('?', substr($_SERVER['REQUEST_URI'], 0));
		$_SERVER['REQUEST_URI'] = str_replace([".php", ".html"], "", $_SERVER['REQUEST_URI'][0]);

		if (in_array($_SERVER['REQUEST_URI'], ["/"])) $_SERVER['REQUEST_URI'] = "/index";
		if (preg_match('/(sys)/', $_SERVER['REQUEST_URI'])) $_SERVER['REQUEST_URI']  = "/404"; // TMP
		if (in_array($_SERVER['REQUEST_URI'], ["/panel", "/panel/"])) $_SERVER['REQUEST_URI']  = "/panel/index";
		if (in_array($_SERVER['REQUEST_URI'], ["/api", "/api/"])) $_SERVER['REQUEST_URI']  = "/api/index";

		$this->Settings = $CMS->Config->security;
	}

	public function onc($pth) {
		if (!file_exists($pth)) return false;

		$inclue = false;
		foreach ((array)$this->Settings as $key => $value) {
			if ($value === "false"){
				$file = file_get_contents($pth);
				if (strpos($file, "$key(") === false) {
					$inclue = true;
				} else {
					exit(htmlspecialchars("It appears that the $key function is disabled. Please edit the .config/Core.xml file by replacing '<$key>false</$key>' with '<$key>true</$key>'."));
				}
			} else {
				$inclue = true;
			}

			if ($inclue) {
				include_once($pth);
				return true;
			}
		}

		return false;
	}

	public function Include($Path) : void {
		if(!$this->onc($Path)) exit(CMS->OHeader->DynamicHeaderError(404));
	}
}