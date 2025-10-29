<?php
/**
 * Session Class
 * 28/07/25 - Create file
 */
class Session
{
	private $CMS;
	private $ArrayPath, $StrPath;

	function __construct($CMS)
	{
		$this->CMS = $CMS;

		// CLé de sécurité
		$privatekey = $this->CMS->Config->server->privatekey;


	}

	private function insertSession() {
		$this->ArrayPath = explode('/', substr($_SERVER['REQUEST_URI'], 1));
		$this->StrPath = implode('/', explode('/', substr($_SERVER['REQUEST_URI'], 1)));

		if(CMS->Template->mimeEpxlore($this->StrPath)) return;
		if($_SERVER['REQUEST_URI'] == '/404') return;

		$this->CMS->DataBase->execute("DELETE FROM omsy_session WHERE last_activity < NOW() - INTERVAL ? SECOND", [300]);
		$existe = $this->CMS->DataBase->execute("DELETE FROM omsy_session WHERE session_id = ?", [session_id()])->fetchAll();
		if(isset($existe[0])) $this->CMS->DataBase->execute("UPDATE omsy_session SET last_activity = Now() AND page = ? WHERE session_id = ?", [$_SERVER['HTTP_USER_AGENT'], session_id()]);
		else {
			$this->CMS->DataBase->execute(
				'INSERT INTO omsy_session (session_id, page, agent, last_activity) VALUES(?, ?, ?, NOW())',
				[session_id(), $_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING'], $_SERVER['HTTP_USER_AGENT']]
			);
		}
	}

	// After load CMS
	/**
	 * OTK - OpenToken
	 */
	public function afterCMSLoad() {
		$getId = explode('/', $_SERVER['REQUEST_URI']);
		if(!in_array($getId[1], ['panel', 'api'])) $this->insertSession();

		if(isset($_SESSION['string_token'])) {
			/// OpenToken -> TEMPS
			$otk = htmlentities($_SESSION['string_token']);
			$us = $this->CMS->DataBase->execute('SELECT * FROM account_login WHERE token = ?', [$otk])->fetchAll() ?? [];
			if(count($us) > 0) {
				$reqUser = $this->CMS->DataBase->execute(
					'SELECT * FROM account WHERE uniqid = ?',
					[$us[0]['user_id']]
				)->fetchAll()[0];

				$_SESSION = $reqUser;
				$_SESSION['Role'] = intval($reqUser['role']);
				$_SESSION['string_token'] = $otk;
			}
			/// OpenToken -> TEMPS
		}
	}

	public function getAllSessionAccount() {
		if(isset($_SESSION['string_token'])) {
			$otk = htmlentities($_SESSION['string_token']);
			$us = $this->CMS->DataBase->execute('SELECT * FROM account_login WHERE token = ?', [$otk])->fetchAll();

			$buff = [];
			foreach($us as $v) {
				$buff[] = $this->CMS->DataBase->execute(
					'SELECT * FROM account WHERE uniqid = ?',
					[$v['user_id']]
				)->fetchAll()[0];
			}
			return $buff;
		}

		return [];
	}

	/**
	 * Omsy 29.07.25
	 */

	public function GetRole() : int {
		echo 'calling';
		return isset($_SESSION['Role'])?$_SESSION['Role']:0;
	}
	public function Get($key) : mixed {
		return isset($_SESSION[$key]) ? $_SESSION[$key]:null;
	}
	public function Destroy() : void {
		//session_destroy();
	}
}