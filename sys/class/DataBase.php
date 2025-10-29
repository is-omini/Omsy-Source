<?php
/**
 * DataBase Class
 * 29/07/25 - Create file
 * 	- 
 */
class DataBase
{
	private $CMS;

	// SQL Information
	private $PDO, $Result;
	public $Success;

	function __construct($CMS)
	{
		$this->CMS = $CMS;

		include(".".$CMS->Path->SysClass."PDO_htmlspecialchars.php");

		try {
			$this->PDO = new PDO(
				"mysql:host=".$CMS->Config->database->host.";dbname=".$CMS->Config->database->dbname, 
				$CMS->Config->database->username, 
				$CMS->Config->database->password,
				[
				    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
				]
			);
			$this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$this->PDO->setAttribute(PDO::ATTR_STATEMENT_CLASS, ['PDO_htmlspecialchars']);
		} catch (Exception $e) {
			//$Data->Call("Log")->Report($e);
		}
	}

	public function sqlPagex($tab, $sqlEnded = '', $max = 10) {
		$listChannels = CMS->DataBase->execute("SELECT * FROM $tab $sqlEnded")->fetchAll();

		if(isset($_GET['q_limit']) && intval($_GET['q_limit']) > 0) {
			$max = intval($_GET['q_limit']);
		}

		$countChannelVideo = count($listChannels);
		$pagesTotales = ceil($countChannelVideo/$max);
		if(isset($_GET['pp']) AND !empty($_GET['pp']) AND $_GET['pp'] > 0 AND $_GET['pp'] <= $pagesTotales) {
			$_GET['pp'] = intval($_GET['pp']);
			$pageCourante = $_GET['pp'];
		} else {
			$pageCourante = 1;
		}
		$depart = ($pageCourante-1)*$max;
		
		$listChannels = CMS->DataBase->execute("SELECT * FROM $tab $sqlEnded LIMIT $depart, $max")->fetchAll();
		

		return [
			'items' => $listChannels,
			'page' => $pageCourante,
			'max' => $max,
			'totalePage' => $pagesTotales,
			'totaleItems' => $countChannelVideo,
		];
	}

	public function execute($Request, $Params = null, $Protect = true) {
		try {
			$stmt = $this->PDO->prepare($Request);
			if ($Params === null) $this->Success = $stmt->execute();
			else $this->Success = $stmt->execute($Params);
			$this->Result = $stmt;
		}
		catch (Exception $e) { $this->Success = false; }

		return $this;
	}

	// Affichage des contenus
	// !! Ajouter des verification et des cas d'erreurs...
	public function fetchAll($index = null) {
		if ($this->Result === null) return NULL;
		if ($index !== null) return $this->Result->fetchAll()[$index];
		return $this->Result->fetchAll();
	}

	public function fetch($key = null) {
		if ($key !== null) return $this->Result->fetch()->$key;
		return $this->Result->fetch();
	}
}