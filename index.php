<?php
ini_set("session.cookie_httponly", True);
ini_set('memory_limit', '800M');

session_start();
date_default_timezone_set('Europe/Paris');
define("__root__", dirname(__FILE__)."/");


//$_SESSION['Role'] = 3;
include "./sys/class/CMS.php";
new CMS();