<?php
class omsyPage {
	public function setPage($path) {
		$page = CMS->DataBase->execute('SELECT * FROM omsypage_page WHERE page_slug=?', [$path])->fetchAll();
		if(is_array($page) && count($page) > 0) {
			$this->pageSql = $page[0];

			return true;
		}
		return false;
	}
	public function getContent() {
		return CMS->GFunction->htmlDecode($this->pageSql['page_content']);
	}

	public function getPageMenu() {
		$data = [];
		$notParent = CMS->DataBase->execute('SELECT * FROM omsypage_page WHERE page_parent=0')->fetchAll();

		foreach ($notParent as $key => $value) {
			if(!isset($notParent[$key]['kids'])) $notParent[$key]['kids'] = [];
			$parent = CMS->DataBase->execute('SELECT * FROM omsypage_page WHERE page_parent=?', [$value['ID']])->fetchAll();
			if(isset($parent[0])) $notParent[$key]['kids'] = $parent;
		}

		return $notParent;
	}
}