<?php
class Permission {
	private $userOpen = 0;
	private $userEdit = 0;
	private $userDelete = 0;

	private $userGroup = 0;

	private $CMS = 0;

	private $prm_def_code = [
		"OPEN" => 1,
		"EDIT" => 2,
		"DELETE" => 4,

		"PASSWORD_RESET" => 7
	];

	public function __get($name) {
		$returnGet = null;
		if(isset($this->prm_def_code[$name])) $returnGet = $this->prm_def_code[$name];
		return $returnGet;
	}

	function __construct($CMS) {
		$this->CMS = $CMS;
		$this->userGroup = intval($_SESSION['Role'] ?? 0);

		define('OmsyPRM', $this);
	}

	/*

	| open | edit | delete | pr | Valeur | Code |
	|------|------|--------|----|--------|------|
	| ❌   | ❌   | ❌     | ❌ | 0      | ---- |
	| ✅   | ❌   | ❌     | ❌ | 1      | o--- |
	| ❌   | ✅   | ❌     | ❌ | 2      | -e-- |
	| ✅   | ✅   | ❌     | ❌ | 3      | oe-- |
	| ❌   | ❌   | ✅     | ❌ | 4      | --d- |
	| ✅   | ❌   | ✅     | ❌ | 5      | o-d- |
	| ❌   | ✅   | ✅     | ❌ | 6      | -ed- |
	| ✅   | ✅   | ✅     | ❌ | 7      | oed- |
	| ❌   | ❌   | ❌     | ✅ | 8      | ---p |
	| ✅   | ✅   | ✅     | ✅ | 15     | oedp |
	*/

	public function human($prmT) {
		$symbol = [
			"----",
			"o---",
			"-e--",
			"oe--",
			"--d-",
			"o-d-",
			"-ed-",
			"oed-",
			"---p",
			"oedp",
		];

		$prm = str_split($prmT, 2);
		$prmtab=[];
		foreach ($prm as $value) {
			$bits = (int) $value;
			$prmtab[] = $symbol[$bits] ?? '----';
		}

		$encoded = implode(',', $prmtab);

		return [$encoded, $prmT];
	}

	function can($prmCode, $prmRule) {
		$prm = str_split($prmRule, 2);
		$groupMap = [
			0 => (int) $prm[0] ?? 0, // Inviter
			2 => (int) $prm[1] ?? 0, // Membre
			4 => (int) $prm[2] ?? 0, // Staff
			
			7 => (int) $prm[3] ?? 0, // Root
			8 => (int) $prm[3] ?? 0, // Root
		];
		$userPerm = isset($groupMap[$this->userGroup]) ? (int)$groupMap[$this->userGroup] : 0;

		return ($userPerm & $prmCode) !== 0;
	}
}