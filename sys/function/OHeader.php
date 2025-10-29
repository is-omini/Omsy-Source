<?php
class OHeader {
	private $CMS;
	public $errorPrecision = null;

	function __construct($CMS){
        $this->CMS = $CMS;
    }

	private $headerError = [
		404 => [
			"code" => '404', 
			"msg" => 'Not Found'
		],
		400 => [
			"code" => '400', 
			"msg" => 'Bad Request'
		],
		401 => [
			"code" => '401', 
			"msg" => 'Unauthorized'
		],
		403 => [
			"code" => '403', 
			"msg" => 'Forbidden'
		],
		500 => [
			"code" => '500', 
			"msg" => 'Internal Server Error'
		],
		502 => [
			"code" => '502', 
			"msg" => 'Bad Gateway'
		],
		503 => [
			"code" => '503', 
			"msg" => 'Service Unavailable'
		]
	];

	public function DynamicHeaderError($errCode, $errorPrecision = null) {
		if(isset($errorPrecision) && !empty($errorPrecision)) $this->errorPrecision = $errorPrecision;

		header("HTTP/1.1 ".$this->headerError[intval($errCode)]['code'].' '.$this->headerError[intval($errCode)]['msg']);

		$html = '<body style="display: flex; align-items: center; justify-content: center; text-align: center; flex-direction: column; position: fixed; top: 0; left: 0; right: 0; bottom: 0; font-size: 32px;">';
			$html .= '<div><h1>'.$this->headerError[intval($errCode)]['code'].'</h1></div>';
			$html .= '<div><p>'.$this->headerError[intval($errCode)]['msg'].'</p></div>';
		$html .= '</body>';
		$errro404file = __root__."usr/template/".$this->CMS->Config->template->folder."/".$this->headerError[intval($errCode)]['code'].".php";
		if($this->CMS->getAccess() > 2) $errro404file = __root__."panel/".$this->headerError[intval($errCode)]['code'].".php";

		if(!$this->CMS->Security->onc($errro404file)) echo '';
	}
}