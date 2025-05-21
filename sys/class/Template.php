<?php
class Template {
	private $Rewrites, $ArrayPath, $StrPath, $Template, $Mime;

	function __construct($CMS) {
		$this->ArrayPath = explode('/', substr($_SERVER['REQUEST_URI'], 1));
		$this->StrPath = implode('/', explode('/', substr($_SERVER['REQUEST_URI'], 1)));
		$this->Template = $CMS->Config->template->folder;
		$this->Rewrites = json_decode(file_get_contents("./rewrite.json"));
        $this->Mime = [
            "text/plain" => "txt",
            "text/html" => ["htm", "html"],
            "text/css" => "css",
            "application/javascript" => "js",
            "application/json" => "json",
            "application/xml" => "xml",
            "application/x-shockwave-flash" => "swf",
            "video/x-flv" => "flv",
            "image/png" => "png",
            "image/jpeg" => ["jpe", "jpeg", "jpg"],
            "image/gif" => "gif",
            "image/bmp" => "bmp",
            "image/vnd.microsoft.icon" => "ico",
            "image/tiff" => ["tiff", "tif"],
            "image/svg+xml" => "svg",
            "application/zip" => "zip",
            "application/x-rar" => "rar",
            "application/x-rar-compressed" => "rar",
            "application/x-msdownload" => ["exe", "msi"],
            "application/vnd.ms-cab-compressed" => "cab",
            "audio/mpeg" => "mp3",
            "video/quicktime" => ["qt", "mov"],
            "application/pdf" => "pdf",
            "image/vnd.adobe.photoshop" => "psd",
            "application/postscript" => ["ai", "eps", "ps"],
            "application/msword" => "doc",
            "application/rtf" => "rtf",
            "application/vnd.ms-excel" => "xls",
            "application/vnd.ms-powerpoint" => "ppt",
            "application/vnd.oasis.opendocument.text" => "odt",
            "application/vnd.oasis.opendocument.spreadsheet" => "ods"
        ];

		$CMS->setPage(end($this->ArrayPath));
        CMS->DataBase->execute('INSERT INTO omsy_viewpage(useragent, userip, page, date_time) VALUES(?,?,?,now())', [$_SERVER['HTTP_USER_AGENT'], CMS->GFunction->getIp(), $this->StrPath]);

		if($this->ArrayPath[0] == 'panel') {
			if($CMS->Session->GetRole() === 3 || $CMS->Session->GetRole() === 2) {
				if ($this->mimeEpxlore($this->StrPath)) {
					if ($this->TryLoadFile($this->StrPath)){
						exit();
					}
				}
				$CMS->Security->Include($this->StrPath.".php");
				return;
			}
		} else if($this->ArrayPath[0] == 'api') {
			if(file_exists("./share/".$this->StrPath.".php")) {
				$CMS->Security->Include(Path:"share/".$this->StrPath.".php");
			} else if (file_exists("./usr/plugins/".$this->ArrayPath[1]."/api/".$this->ArrayPath[2].".php")) {
				$CMS->Security->Include(Path:"./usr/plugins/".$this->ArrayPath[1]."/api/".$this->ArrayPath[2].".php");
			} else {
				exit(header('Location: ../../404'));
			}
		} else {

			$Permission = $this->LoadPerm("usr/template/".$this->Template."/Permission.json");
			if ($Permission === null) exit();

			if ($this->TryLoadFile("usr/template/".$this->Template."/".$this->StrPath)){
				return;
			}
			if ($this->TryLoadFile($this->StrPath)){
			} else {
				if(isset($Permission[$this->StrPath])) {
					if (in_array($CMS->Session->GetRole(), $Permission[$this->StrPath])){
						$CMS->Security->Include("usr/template/".$this->Template."/".$this->StrPath.".php");
						return;
					}
				}

				foreach ($this->Rewrites as $value) {
					$regex = str_replace("/", "\\/", $value->path);
					preg_match("/$regex/", $this->StrPath, $matches);
					if (isset($matches[0]) !== false) {
						unset($matches[0]);
						$this->StrPath = substr($value->rewrite, 1);
						$CMS->Args = $matches;
						break;
					}
				}

				if(isset($Permission[$this->StrPath])) {
					if (in_array($CMS->Session->GetRole(), $Permission[$this->StrPath])){
						$CMS->Security->Include("usr/template/".$this->Template."/".$this->StrPath.".php");
						return;
					} else {
						exit(header('Location: ../403'));
					}
				} else {
					exit(header('Location: ../404'));
				}
			}
		}

		
	}

	private function Load_Page_Template($CMS) {
		$Permission = $this->LoadPerm("usr/template/".$this->Template."/Permission.json");
		//if($this->mimeEpxlore($this->StrPath)) {
			if ($Permission === null) exit();

			$pathFull = "usr/template/".$this->Template."/".$this->StrPath;

			if ($this->TryLoadFile("usr/template/".$this->Template."/".$this->StrPath)){
				exit();
			} else if ($this->TryLoadFile($this->StrPath)){
				exit();
			} else {
				echo 'ddd';
				foreach ($this->Rewrites as $value) {
					$regex = str_replace("/", "\\/", $value->path);
					preg_match("/$regex/", $this->StrPath, $matches);
					if (isset($matches[0]) !== false) {
						unset($matches[0]);
						$this->StrPath = explode('/', substr($value->rewrite, 1));
						$CMS->Args = $matches;
						break;
					}
				}
			}
		//}
		if (isset($Permission[$this->StrPath])) {

		}
		var_dump($this->StrPath, 'ddd');
	}

	private function TryLoadFile($Path){
		if (file_exists($Path)){
			$extention = pathinfo($Path, PATHINFO_EXTENSION);
			header("Content-Type: ".array_search($extention, $this->Mime));
			header('Content-Length: ' . filesize($Path));
			readfile($Path);
			return true;
		}
		return false;
    }

	function rewrite($ArrayPath) {

	}

	private function LoadPerm($Path) : mixed {
		if (!file_exists($Path))
			return null;
		return json_decode(file_get_contents($Path), true);
    }

	function mimeEpxlore($pathFull) {
		if(
			str_contains($pathFull, '.css') ||
			str_contains($pathFull, '.js') ||
			str_contains($pathFull, '.jpg')  ||
			str_contains($pathFull, '.jpeg') ||
			str_contains($pathFull, '.png')  ||
			str_contains($pathFull, '.svg') ||
			str_contains($pathFull, '.ico') ||
			str_contains($pathFull, '.webp') ||
			str_contains($pathFull, '.mp4')
		) {
			return true;
		}

		return false;
	}

    public function getTitle() {
        return $this->templateConfig->name;
    }
}