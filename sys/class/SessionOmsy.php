<?php
/**
 * SessionOmsy Class
 * 29/07/25 - Create file
 */
class SessionOmsy
{
	private $CMS;
	private $ArrayPath, $StrPath;
	
	function __construct($CMS)
	{
		$this->CMS = $CMS;

		// CLé de sécurité
		$privatekey = $CMS->Config->server->privatekey;
	}

	/**
	 * Omsy 29.07.25
	 */

	public function GetRole() : int {
		return isset($_SESSION['Role'])?$_SESSION['Role']:0;
	}
	public function Get($key) : mixed {
		return isset($_SESSION[$key]) ? $_SESSION[$key]:null;
	}
	public function Destroy() : void {
		//session_destroy();
	}
}