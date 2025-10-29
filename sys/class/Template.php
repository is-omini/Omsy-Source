<?php
/**
 * Template Class
 * 29/07/25 - Create file
 */
class Template
{
	// Defintion des extensions
	private $Mime = [
		"txt" => "text/plain",
		"htm" => "text/html",
		"html" => "text/html",
		"css" => "text/css",

		"js" => "application/javascript",
		"json" => "application/json",
		"xml" => "application/xml",

		"swf" => "application/x-shockwave-flash",
		"flv" => "video/x-flv",

		"php" => "application/x-httpd-php",

		"png" => "image/png",
		"jpe" => "image/jpeg",
		"jpeg" => "image/jpeg",
		"jpg" => "image/jpeg",

		"gif" => "image/gif",
		"bmp" => "image/bmp",

		"webp" => "image/webp",

		"ico" => "image/vnd.microsoft.icon",

		"tiff" => "image/tiff",
		"tif" => "image/tiff",

		"svg" => "image/svg+xml",

		"zip" => "application/zip",
		"rar" => "application/x-rar",
		"rar" => "application/x-rar-compressed",

		"exe" => "application/x-msdownload",
		"msi" => "application/x-msdownload",

		"cab" => "application/vnd.ms-cab-compressed",
		"mp3" => "audio/mpeg",

		"qt" => "video/quicktime",
		"mov" => "video/quicktime",
		"mp4" => "video/mp4",

		"pdf" => "application/pdf",

		"psd" => "image/vnd.adobe.photoshop",
		"ai" => "application/postscript",
		"eps" => "application/postscript",
		"ps" => "application/postscript",

		"doc" => "application/msword",
		"rtf" => "application/rtf",

		"xls" => "application/ms-excel",
		"ppt" => "application/ms-powerpoint",

		"odt" => "application/vnd.oasis.opendocument.text",
		"ods" => "application/vnd.oasis.opendocument.spreadsheet",
	];
	private $AcceptMime = array(
		'image' => true,
		'audio' => true,
		'video' => true,
		'text' => true,

		'application/javascript' => true,
		'application/json' => true
	);

	private $CMS;
	private $TemplateID, $StrPath, $ArrayPath, $Rewrites;

	function __construct($CMS) {
		$this->CMS = $CMS;

		$this->TemplateID = $CMS->Config->template->folder;

		$this->StrPath = substr($_SERVER['REQUEST_URI'], 1);
		$this->ArrayPath = explode('/', $this->StrPath);

		$this->Rewrites = json_decode(file_get_contents("./rewrite.json"));


		// Gestion du panel admin
		if($this->ArrayPath[0] == $CMS->PathDefintionString->RootAdministatorDashboard) $this->panelRoot();

		// Gestion de l'api interne
		else if($this->ArrayPath[0] == $CMS->PathDefintionString->RootApplicationProgrammingInterface) $this->apiRoot();

		else if($this->ArrayPath[0] == "plugin") $this->plugRoot();

		// Gestion des page web
		else $this->webRoot();
	}

	private function apiRoot() {
		$this->CMS->setAccess(1);

		if(file_exists("./share/".$this->StrPath.".php")) {
			$this->CMS->Security->Include(Path:"share/".$this->StrPath.".php");
		} else if (file_exists("./usr/plugins/".$this->ArrayPath[1]."/api/".$this->ArrayPath[2].".php")) {
			$this->CMS->Security->Include(Path:"./usr/plugins/".$this->ArrayPath[1]."/api/".$this->ArrayPath[2].".php");
		} else exit(CMS->OHeader->DynamicHeaderError('404'));
	}

	private function panelRoot() {
		$this->CMS->setAccess(3);

		if($this->CMS->SessionOmsy->GetRole() >= 2) {
			if($this->verifMime($this->StrPath)) {
				if ($this->TryLoadFile($this->StrPath)) exit();
			}

			if(file_exists($this->StrPath.'.php')) $this->CMS->Security->Include($this->StrPath.".php");
			else exit('Echec opening / '.$this->StrPath);
		}
		else exit('Echec opening / '.$this->StrPath);
	}

	private function plugRoot() {
		$this->CMS->setAccess(2);

		$arrayRoot = explode('/', $this->StrPath);
		unset($arrayRoot[0]);

		$pluginName = $arrayRoot[1];
		unset($arrayRoot[1]);

		$newRoot = implode('/', $arrayRoot);

		if($this->verifMime(__root__.'usr/plugins/'.$pluginName.'/'.$newRoot)) {
			if ($this->TryLoadFile(__root__.'usr/plugins/'.$pluginName.'/'.$newRoot)) exit();
		}

		if(file_exists('usr/plugins/'.$pluginName.'/'.$newRoot.'.php')) {
			$this->CMS->Security->Include('usr/plugins/'.$pluginName.'/'.$newRoot.'.php');
		}
		else exit('Echec opening / '.'usr/plugins/'.$pluginName.'/'.$newRoot.'.php');
	}

	private function webRoot() {
		$this->CMS->setAccess(0);

		$this->CMS->DataBase->execute('INSERT INTO omsy_viewpage(useragent, userip, page, date_time) VALUES(?,?,?,now())', [$_SERVER['HTTP_USER_AGENT'], $this->CMS->GFunction->getIp(), $this->StrPath]);
		
		//echo $this->StrPath;

		if($this->verifMime($this->StrPath)) {
			if ($this->TryLoadFile($this->StrPath)) exit();
		}

		$Permission = $this->LoadPerm("usr/template/".$this->TemplateID."/prm.json");
		if ($Permission === null) exit('Permission load error');

		if ($this->TryLoadFile("usr/template/".$this->TemplateID."/".$this->StrPath)) return;

		else {
			if(isset($Permission[$this->StrPath])) {
				if ($this->CMS->Permission->can(OmsyPRM->OPEN, $Permission[$this->StrPath])){
				//if (in_array($this->CMS->SessionOmsy->GetRole(), $Permission[$this->StrPath])){
					$this->CMS->Security->Include("usr/template/".$this->TemplateID."/".$this->StrPath.".php");
					return;
				}
			}

			foreach ($this->Rewrites as $value) {
				$regex = str_replace("/", "\\/", $value->path);
				preg_match("/^$regex$/", $this->StrPath, $matches);
				if (isset($matches[0]) !== false) {
					unset($matches[0]);
					$this->StrPath = substr($value->rewrite, 1);
					$this->CMS->Args = $matches;
					break;
				}
			}

			if(isset($Permission[$this->StrPath])) {
				if ($this->CMS->Permission->can(OmsyPRM->OPEN, $Permission[$this->StrPath])){
				//if (in_array($this->CMS->SessionOmsy->GetRole(), $Permission[$this->StrPath])){
					$this->CMS->Security->Include("usr/template/".$this->TemplateID."/".$this->StrPath.".php");
					return;
				} else exit(var_dump($this->CMS->Permission->can(OmsyPRM->OPEN, $this->CMS->SessionOmsy->GetRole())));// CMS->OHeader->DynamicHeaderError('403'));
			} else exit(CMS->OHeader->DynamicHeaderError('404'));
		}
	}

	private function LoadPerm($Path) : mixed {
		if (!file_exists($Path)) return null;
		return json_decode(file_get_contents($Path), true);
	}

	private function verifMime($pathFull) {
		foreach ($this->Mime as $key => $value) {
			if(str_contains($pathFull, ".$key")) {
				
				if(!isset($this->AcceptMime)) return true;
				$valueS = explode('/', $value);
				if(isset($this->AcceptMime[$valueS[0]])) return true;
				if(isset($this->AcceptMime[$valueS[1]])) return true;
				if(isset($this->AcceptMime[$value])) return true;
			}
		}
		return false;
	}

	private function TryLoadFile($Path){
		if (file_exists($Path)){
			$extention = pathinfo($Path, PATHINFO_EXTENSION);
			//var_dump($this->Mime[$extention]);
			header("Content-Type: ".$this->Mime[$extention]);
			header('Content-Length: ' . filesize($Path));
			readfile($Path);
			return true;
		}
		return false;
	}
}