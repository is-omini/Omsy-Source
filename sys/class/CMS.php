<?php
/**
 * CMS Class
 * 28/07/25 - Create file
 * 	- 
 */
class CMS
{
	/**
	 * DynamicVar - Omsy 28.07.25
	 * 
	 */
	private $DynamicVar;
	public $Args;

	private $title;

	private $getAccess = 0;

	/*
	 * 0 : Webroot /usr/template/theme-user/
	 * 1: ApiRoot /share/api/
	 * 2 : PlugRoot /usr/plugins/plugin-user/
	 * 3 : adminRoot /panel/
	 */
	public function setAccess(int $str) { $this->getAccess = $str; }
	public function getAccess() { return $this->getAccess; }

	function __construct()
	{
		// Lecture de fichier de configuration du CMS
		$Config = simplexml_load_file('./Core.xml');
		if ($Config === false) {
			die('Erreur de chargement du fichier XML.');
		}

		// Definition du titre des pages
		//if(isset($Config->template->title)) $this->Tilte = $Config->template->title;
		//else $this->Tilte = '';

		// PageAccess
		// Permet de la gestion dynamique des "redirection" systeme
		$PathDefintionString = (object) array(
			"RootAdministatorDashboard" => "panel",
			"RootApplicationProgrammingInterface" => "api"
		);

		// Path - Omsy 28.07.25
		// Defintion des repertoire
		$Path = (object) array(
			"panel" => "/panel/",

			"Sys" => "/sys/",
			"SysClass" => "/sys/class/",
			"SysFunction" => "/sys/function/",
			"SysModule" => "/sys/module/",

			"User" => "/usr/",
			"UserPlugin" => "/usr/plugins/",
			"UserTemplate" => "/usr/template/",
		);

		$this->title = $Config->template->title;

		// Add $Config to DynamicVar
		$this->DynamicVar["Config"] = $Config;
		// Add $Path to DynamicVar
		$this->DynamicVar["Path"] = $Path;
		// Add $PathDefintionString to DynamicVar
		$this->DynamicVar["PathDefintionString"] = $PathDefintionString;

		/**
		 * Tempory Code - Omsy 28.07.25
		 * - Ajout dynamique des class systeme
		 * !! Trouvez une solution d'éviter les conflit d'importation d'ordre...
		 */
		$buffPrimClass = [];

		$primaryClassSys = array('FunctionOmsy', 'Security', 'Permission', 'SessionOmsy', 'Log', 'DataBase', 'Plugins', 'ModuleOmsy', 'Template');
		foreach ($primaryClassSys as $key => $value) {
			$pathIncludeClass = ".".$Path->SysClass."$value.php";
			if(!file_exists($pathIncludeClass)) continue;
			include($pathIncludeClass);
			if(!class_exists($value)) continue;
			$this->DynamicVar["$value"] = new $value($this);


			$buffPrimClass[] = $value;
		}

		foreach ($buffPrimClass as $key => $value) {
			if(!$this->DynamicVar[$value]) continue;
			if(method_exists($this->DynamicVar[$value], 'afterCMSLoad')) $this->DynamicVar[$value]->afterCMSLoad();
		}
	}

	public function __get($name) {
		$returnGet = null;
		if(isset($this->DynamicVar[$name])) $returnGet = $this->DynamicVar[$name];
		return $returnGet;
	}

	public function addFunction($functionName) {
		if(function_exists($functionName)) return;

		include_once ("./sys/function/$functionName.php");
		$this->DynamicVar[$functionName] = new $functionName($this);
	}

	public function addModule($moduleName) {
		if(class_exists($moduleName)) return;
		include_once ("./sys/module/$moduleName.php");
		$this->DynamicVar[$moduleName] = new $moduleName($this);
	}

	public function getTitle() { return $this->title; }
	public function setTitle($string) { $this->title = $string; }

	public function getTemplate() { return ''; }



	public function setTemplate($content){
		$getFolder = __root__ . 'usr/template/' . $content . '/template.json';
		if(!file_exists($getFolder)) return;

		$getConfig = json_decode(file_get_contents($getFolder));
		$getConfig->id = $content;

		$this->DynamicVar["Config"]->template->folder = $getConfig->id;
		$this->DynamicVar["Config"]->database->host = $getConfig->install->database->host;
		$this->DynamicVar["Config"]->database->dbname = $getConfig->install->database->dbname;
		$this->DynamicVar["Config"]->database->username = $getConfig->install->database->username;
		$this->DynamicVar["Config"]->database->password = $getConfig->install->database->password;

		if(isset($getConfig->install->metadate->title)) $this->DynamicVar["Config"]->template->title = $getConfig->install->metadate->title;

		file_put_contents(__root__ . 'rewrite.json', json_encode($getConfig->install->rewrite, JSON_PRETTY_PRINT));

		//var_dump($getConfig->install->database);
		$this->DynamicVar["Config"]->asXML('./Core.xml');
	}

	public function setPlguins($content){
		$buff = [];
		$config = [];
		foreach($content as $value) {
			$getFolder = __root__ . 'usr/plugins/' . $value;
			if(!file_exists($getFolder)) continue;
			$buff[] = $value;

			if(!file_exists($getFolder.'/template.json')) continue;

			$getConf = json_decode(file_get_contents($getFolder.'/template.json'));
			$img = null;
			if(isset($getConf->thumbnail)) $img = "<img src=\"$getConf->thumbnail\" height=\"22px\" width=\"22px\">";
			$config[] = [
				"id" => $value,
				"name" => $getConf->name,
				"icon" => $getConf->icon ?? null,
				"thumbnail" => $img,
			];
		}
		//file_put_contents(filename, data)
		file_put_contents(__root__.'/panel/interface/config/apps.json', json_encode($config, JSON_PRETTY_PRINT));

		$buff = implode(", ", $buff);
		$this->DynamicVar["Config"]->template->plugins = $buff;
		$this->DynamicVar["Config"]->asXML('./Core.xml');
	}
}