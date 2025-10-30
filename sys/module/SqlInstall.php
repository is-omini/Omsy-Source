<?php
/**
 * 
 */
class SqlInstall
{
	// SQL Attributes
	private $sqlAttr = [
		// Nullabilité
		"NOT NULL",
		"NULL",

		// Valeurs par défaut
		"DEFAULT\s+'[^']+'",           // DEFAULT 'texte'
		'DEFAULT\s+"[^"]+"',           // DEFAULT "texte"
		"DEFAULT\s+[A-Za-z0-9_]+",     // DEFAULT valeur brute (CURRENT_TIMESTAMP, 0, etc.)
		"DEFAULT",                     // DEFAULT vide (toléré, mais signalé)

		// Types de données courants
		"varchar\([0-9]+\)",
		"int\([0-9]+\)",
		"text",
		"datetime",
		"timestamp",

		// Attributs optionnels
		"AUTO_INCREMENT",
		"PRIMARY KEY",
	];

	function __construct($CMS)
	{
		// /www/sql_install.json
		//$getOmsyInstall = file_get_contents(__root__ . 'sql_install.json');
		//$getOmsyInstall = json_decode($getOmsyInstall, true);

		//header('Content-Type: text/plain');
		//die(var_dump($this->sql_verifie($getOmsyInstall)));
	}

	// verifier les données avant execution
	function sql_verifie($getOmsyInstall) {
		$buff = [];
		$buffErr = [];

		foreach ($getOmsyInstall as $key => $value) {
			if(!preg_match('/^[A-Za-z0-9_-]+$/', $value['.name'])) continue;
			$dbName = $value['.name'];
			$dbData = "ID int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY";
			unset($value['.name']);

			foreach ($value as $k => $v) {
				$dbData .= ", $k";

				foreach ($v as $attr) $dbData .= " ".$this->attr_match($attr);
				//echo $attr . ' → ' . ($this->attr_match($attr) ? 'OK' : 'ERR') . PHP_EOL;
			}
			$newSqlRequest = "CREATE TABLE {$dbName} ($dbData)";

			if($this->is_valid_sql_reate($newSqlRequest)) $buff[] = $newSqlRequest;
		}

		return $buff;
	}

	// Verifier si les attribut sont correct
	private function attr_match($attr) {
		foreach ($this->sqlAttr as $key => $value)
			if(preg_match('/^' . $value . '$/i', $attr)) return $attr;
		return false;
	}

	private function is_valid_sql_reate($query) {
		// Nettoie les espaces multiples
		$query = trim(preg_replace('/\s+/', ' ', $query));

		// Vérifie la structure de base
		$pattern = '/CREATE TABLE [`"]?[A-Za-z_][A-Za-z0-9_]*[`"]? \((?:[A-Za-z_][A-Za-z0-9_]*\s+[A-Za-z_0-9\(\)]+(?:\s+(?:NOT NULL|NULL|DEFAULT\s+[A-Za-z0-9\'"]+|AUTO_INCREMENT|PRIMARY KEY))*(?:,\s*)?)+\)$/i';

		// Vérifie les parenthèses et la structure
		if (!preg_match($pattern, $query)) return false;

		// Vérifie les parenthèses
		$open = substr_count($query, '(');
		$close = substr_count($query, ')');
		if ($open !== $close) return false;

		// Vérifie qu’il y a au moins une colonne
		if (!preg_match('/\([^)]+\)/', $query)) return false;

		return true;
	}
}