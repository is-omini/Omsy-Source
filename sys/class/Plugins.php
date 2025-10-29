<?php
/**
 * Plugins Class
 * 29/07/25 - Create file
 */
class Plugins
{
	private $CMS;

	private $Class = [];

	function __construct($CMS)
	{
		$this->CMS = $CMS;

		// Tranormation de la liste des plugins devant etre activer en tableau
		$plugins = explode(',', $CMS->Config->template->plugins);
		foreach ($plugins as $key => $value) {
			if(empty($value)) continue; // Es que c'est vide ?

			$value = trim($value); // Suprime la mise en forme..

			if (file_exists(".".$CMS->Path->UserPlugin . $value."/Main.php")){

				$CMS->Security->Include(".".$CMS->Path->UserPlugin . $value."/Main.php");
				$value = str_replace('-', '', $value); // Suprime les tiré au espace..
				if(!class_exists($value)) continue;
				
				$this->Class[$value] = new $value($CMS);
			}
			else echo "Impossible de charger le plugin : ".$value;
		}
	}

	public function __get($name) {
		return isset($this->Class[$name])?$this->Class[$name]:NULL;
	}
}