<?php
class Cachor {
	// Temp ????
	private $CMS;

	function __construct($CMS) {
        $this->CMS = $CMS;
	}


	public function get_cache($page) {
		// Recuperration de la page dans le CacheSQL
		$chechCache = CMS->DataBase->execute('SELECT * FROM cachor_page WHERE page = ?', [$page])->fetchAll();
		if(isset($chechCache[0])) $chechCache = $chechCache[0];
		else $chechCache = [];

		// Renvoie du contenus si trouver
		if(count($chechCache) > 0):
			$chechCache = json_decode(CMS->GFunction->htmlDecode($chechCache['content']), true);
			return $chechCache;
		endif;

		return null;
	}

	public function insert_cache($page, $content) {
		CMS->DataBase->execute(
			'INSERT INTO cachor_page(page, content, reg) VALUES(?, ?, now())',
			[$page, $content]
		);
	}
}