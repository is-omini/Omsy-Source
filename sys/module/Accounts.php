<?php
class Accounts {
	private $CMS;
	private $userID;
	private $getUsername = [];

	function __construct($CMS){
		$this->CMS = $CMS;
		$this->userID = $_SESSION['uniqid'] ?? null;

		$this->getUsername = $this->CMS->DataBase->execute('SELECT * FROM omsy_account WHERE uniqid = ?', [$this->userID])->fetchAll();
		if(isset($this->getUsername[0])) $this->getUsername = $this->getUsername[0];
	}

	function __get($var) {
		if(!isset($this->getUsername) || !is_array($this->getUsername)) {
			var_dump('recharged');

			$this->getUsername = $this->CMS->DataBase->execute('SELECT * FROM omsy_account WHERE uniqid = ?', [$this->userID])->fetchAll();

			if(!isset($this->getUsername[0])) return null;

			$this->getUsername = $this->getUsername[0];
		}

		if(isset($this->getUsername[$var])) return $this->getUsername[$var];
		return null;
	}

	function updatePassword($password, $user = null) {
		if(!isset($user)) $user = $this->uniqid;
		if(isset($user) && !$this->CMS->Permission->can(OmsyPRM->EDIT, '00000015')) return false;

		var_dump($password);

		$newPassword = $this->passwordHasher($password);

		CMS->DataBase->execute(
			'UPDATE omsy_account SET password = ? WHERE uniqid = ?',
			[$newPassword, $user]
		);
	}

	function getInfo($user) {
		$getUsername = CMS->DataBase->execute('SELECT * FROM omsy_account WHERE uniqid = ?', [$user])->fetchAll();
		if(!isset($getUsername[0])) return false;

		return $getUsername[0];
	}

	function passwordHasher($password) { return password_hash($password, PASSWORD_DEFAULT); }
	function passwordVerfiy($password, $hash) { return password_verify($password, $hash); }

	function getAll() {
		return $this->CMS->DataBase->execute('SELECT * FROM account')->fetchAll();
	}

	function create($data) {
		$reg = [
			'uniqid' => $data['uniqid'] ?? uniqid(),
			'username' => $data['username'] ?? '',
			'password' => $data['password'] ?? '',
			'role' => $data['role'] ?? 0
		];

		$reg['password'] = $this->passwordHasher($reg['password']);

		if(!$this->usernameVirify($reg['username'])) return ['err' => 'Username non valide veuillez utiliser des Nombre ou letre, . , - et _ .'];

		$getUsername = CMS->DataBase->execute('SELECT * FROM omsy_account WHERE username = ?', [$reg['username']])->fetchAll();

		if(isset($getUsername[0])) return ['err' => 'Nom d\'utilisateur déjà utiliser.'];

		$this->CMS->DataBase->execute(
			"INSERT INTO omsy_account (uniqid, username, password, role, register_date) VALUES(?, ?, ?, ?, Now())",
			[$reg['uniqid'], $reg['username'], $reg['password'], intval($reg['role'])]
		);

		$userToken = $this->CMS->GFunction->RandomString(25);
		if(isset($_SESSION['string_token'])) $userToken = htmlentities($_SESSION['string_token']);
		$this->CMS->DataBase->execute(
			'INSERT INTO omsy_account_login (token, user_id, reg_date) VALUES(?,?,Now())',
			[$userToken, $reg['uniqid']]
		);

		$_SESSION['string_token'] = $userToken;

		return ['account' => $reg, 'success' => true, 'err' => null];
	}

	function connect($data) {
		$reg = [
			'username' => $data['username'] ?? '',
			'password' => $data['password'] ?? ''
		];

		$reqUser = $this->CMS->DataBase->execute(
			'SELECT * FROM omsy_account WHERE username = ?',
			[$reg['username']]
		)->fetchAll();

		if(!$reqUser) return ['err' => 'L\'utilisateur n\'existe pas.'];

		$reqUser = $reqUser[0];

		if($this->passwordVerfiy($reg['password'], $reqUser['password'])) {
			$_SESSION = $reqUser;
			$_SESSION['Role'] = intval($reqUser['role']);

			$userToken = $this->CMS->GFunction->RandomString(25);
			if(isset($_SESSION['string_token'])) $userToken = htmlentities($_SESSION['string_token']);
			$this->CMS->DataBase->execute(
				'INSERT INTO omsy_account_login (token, user_id, reg_date) VALUES(?,?,Now())',
				[$userToken, $reqUser['uniqid']]
			);
			$_SESSION['string_token'] = $userToken;

			return ['account' => $reqUser, 'success' => true, 'err' => null];
		}
		return ['err' => 'Mot de passe incorrect'];
	}

	function usernameVirify($chaine) {
	    return (bool) preg_match('/^[A-Za-z0-9._-]+$/', $chaine);
	}
}