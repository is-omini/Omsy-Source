<?php
class Cachor {
	private $CMS;

	function __construct($CMS) {
        $this->CMS = $CMS;
	}

	public function getCache($page) {
		$chechCache = CMS->DataBase->execute('SELECT * FROM cachor_page WHERE page = ?', [$page])->fetchAll();
		if(isset($chechCache[0])) $chechCache = $chechCache[0];
		else $chechCache = [];

		if(count($chechCache) > 0):
			$chechCache = json_decode(CMS->GFunction->htmlDecode($chechCache['content']), true);
			return $chechCache;
		endif;

		return null;
	}

	public function insertCache($page, $content) {
		CMS->DataBase->execute(
			'INSERT INTO cachor_page(page, content, reg) VALUES(?, ?, now())',
			[$page, $content]
		);
	}
}