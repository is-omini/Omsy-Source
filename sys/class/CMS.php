<?php
class CMS {
    private $Class;
    private $Module;
    private $Function;

    public $Title, $metaDescription, $metaTags;

    public $Tilte;
    public $querySearch='';
    public $Page;
    public $Args;
    function __construct() {
        $Config = simplexml_load_file('./Core.xml');
        if ($Config === false) {
            die('Erreur de chargement du fichier XML.');
        }

        $this->Tilte = $Config->template->title;

        $this->Title = $Config->template->title;
        $this->metaDescription = $Config->template->meta->description;
        $this->metaTags = $Config->template->meta->tags;

        $this->Class["Path"] = (object)[
            "Share" => (object)[
                "upload" => "./share/upload/",
                "Bot" => "./share/bot/",
                "Api" => "./share/api/"
            ],
            "Usr" => (object)[
                "Plugins" => "./usr/plugins/",
                "Template" => "./usr/template/"
            ],
            "Panel" => (object)[
                "Class" => "./panel/class/",
                "Templates" => "./panel/templates/"
            ]
        ];

        $this->Class["Config"] = $Config;

        $for = ['FunctionOmsy', 'Security', 'Session', 'Log', 'DataBase', 'Plugins', 'ModuleOmsy', 'Template'];
        foreach ($for as $key => $value) {
            include("./sys/class/$value.php");
            $this->Class["$value"] = new $value($this);
        }
        foreach ($this->Class as $key => $value) {
            if(!$this->Class[$key]) continue;
            if(!class_exists($key)) continue;
            if(method_exists($this->Class[$key], 'afterCMSLoad')) $this->Class[$key]->afterCMSLoad();
        }
    }

    public function __get($name) {
        $r = NULL;
        if(isset($this->Class[$name])) $r = $this->Class[$name];
        else if(isset($this->Function[$name])) $r = $this->Function[$name];
        return $r;
    }

    public function addFunction($functionName) {
        if(function_exists($functionName)) return;

        include_once ("./sys/function/$functionName.php");
        $this->Function[$functionName] = new $functionName($this);
    }

    public function addModule($moduleName) {
        if(class_exists($moduleName)) return;
        include_once ("./sys/module/$moduleName.php");
        $this->Class[$moduleName] = new $moduleName($this);
    }

    public function setPage($page){
        $this->Page = $page;
    }
    public function getPage(){
        return $this->Page;
    }
    public function getTitle(){ return $this->Title; }
    public function getMetaDescription(){ return $this->metaDescription; }
    public function getMetaTags(){ return $this->metaTags; }

    public function getTemplate() {
        return (String) $this->Config->template->folder[0];
    }
    public function setTitle($tilte){
        $this->Config->template->title = $tilte;
        $this->Tilte = $tilte;
        //$this->Config->asXML('./Core.xml');
    }
    public function setTemplate($content){
        $getFolder = __root__ . 'usr/template/' . $content . '/template.json';
        if(!file_exists($getFolder)) return;

        $getConfig = json_decode(file_get_contents($getFolder));
        $getConfig->id = $content;

        $this->Config->template->folder = $getConfig->id;
        $this->Config->database->host = $getConfig->install->database->host;
        $this->Config->database->dbname = $getConfig->install->database->dbname;
        $this->Config->database->username = $getConfig->install->database->username;
        $this->Config->database->password = $getConfig->install->database->password;

        if(isset($getConfig->install->metadate->title)) $this->Config->template->title = $getConfig->install->metadate->title;

        file_put_contents(__root__ . 'rewrite.json', json_encode($getConfig->install->rewrite, JSON_PRETTY_PRINT));

        //var_dump($getConfig->install->database);
        $this->Config->asXML('./Core.xml');
    }

    public function setPlguins($content){
        $buff = [];
        $config = [];
        foreach($content as $value) {
            $getFolder = __root__ . 'usr/plugins/' . $value;
            if(!file_exists($getFolder)) continue;
            $buff[] = $value;

            if(!file_exists($getFolder.'/template.json')) continue;

            $getConf = json_decode(file_get_contents($getFolder.'/template.json'));
            $img = null;
            if(isset($getConf->thumbnail)) $img = "<img src=\"$getConf->thumbnail\" height=\"22px\" width=\"22px\">";
            $config[] = [
                "id" => $value,
                "name" => $getConf->name,
                "icon" => $getConf->icon ?? null,
                "thumbnail" => $img,
            ];
        }
        //file_put_contents(filename, data)
        file_put_contents(__root__.'/panel/interface/config/apps.json', json_encode($config, JSON_PRETTY_PRINT));

        $buff = implode(", ", $buff);
        $this->Config->template->plugins = $buff;
        $this->Config->asXML('./Core.xml');
    }

    public function getNameTemplate() {
        $templateConfig = json_decode(file_get_contents($this->Path->Usr->Template.$this->Config->template->folder. '/template.json'));
        return $templateConfig->name;
    }
}