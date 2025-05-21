<?php
class Security {
    private $Settings;
    function __construct($CMS) {
        define("CMS", $CMS);
        include("./sys/class/Request.php");
        $_GET = new Request($_GET);
        $_POST = new Request($_POST);
        //$_COOKIE = new Request($_COOKIE);
        //$_SESSION = new Request($_SESSION);
        $_REQUEST = new Request($_REQUEST);
        $_SERVER['REQUEST_URI'] = explode('?', substr($_SERVER['REQUEST_URI'], 0));
        $_SERVER['REQUEST_URI'] = str_replace([".php", ".html"], "", $_SERVER['REQUEST_URI'][0]);

        if (in_array($_SERVER['REQUEST_URI'], ["/"])){
            $_SERVER['REQUEST_URI'] = "/index";
        }
        if (preg_match('/(sys|usr)/', $_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI']  = "/404";
        }
        if (in_array($_SERVER['REQUEST_URI'], ["/panel", "/panel/"])) {
            $_SERVER['REQUEST_URI']  = "/panel/index";
        }
        if (in_array($_SERVER['REQUEST_URI'], ["/api", "/api/"])) {
            $_SERVER['REQUEST_URI']  = "/api/index";
        }
        
        $this->Settings = $CMS->Config->security;
    }
    public function Include($Path) : void {       
        if (!file_exists(__root__.'/'.$Path)){
            exit(header('Location: ../404'));
        }
        $inclue = false;
        foreach ((array)$this->Settings as $key => $value) {
            if ($value === "false"){
                $file = file_get_contents(__root__.'/'.$Path);
                if (strpos($file, "$key(") === false) {
                    $inclue = true;
                } else {
                    exit(htmlspecialchars("It appears that the $key function is disabled. Please edit the .config/Core.xml file by replacing '<$key>false</$key>' with '<$key>true</$key>'."));
                }
            } else {
                $inclue = true;
            }
        }
        if ($inclue)
            include($Path);
    }

}
