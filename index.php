<?php
/*
 *
 * Omsy CMS by Floagg EI - 2025
 *
 */

ini_set("session.cookie_httponly", True);
ini_set('session.cookie_secure', '1');

ini_set('memory_limit', '800M');

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
date_default_timezone_set('Europe/Paris');
define("__root__", dirname(__FILE__)."/");

include "./sys/class/CMS.php";
new CMS();