<?php
class GFunction {
    private $CMS;
    function __construct($CMS){
        $this->CMS = $CMS;
    }
    
    public function RandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    public function RandomCode($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)) )),1,$length);
    }

    public function slugify($text, string $divider = '-') {
      $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
      $text = preg_replace('~[^-\w]+~', '', $text);
      $text = trim($text, $divider);
      $text = preg_replace('~-+~', $divider, $text);
      $text = strtolower($text);
      if (empty($text)) {
        return 'n-a';
      }
      return $text;
    }

    public function web_substr($string, $int = 255, $end = "...") {
        if(strlen($string) >= $int)
            $string = substr($string, 0, $int).$end ;
        return $string;
    }

    public function htmlDecode($string) {
        $string = html_entity_decode(htmlspecialchars_decode($string));
        return html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    }

    public function isNotBot($str = null) {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if(isset($str)) $userAgent = $str;

        if(stripos($userAgent, 'bot') || stripos($userAgent, 'python')) return true;
        return false;
    }

    public function getIp(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function getAgent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    function shuffle_assoc(&$array) {
        $keys = array_keys($array);

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }

    public function convertirDate($datetimeStr) {
    if (!$datetimeStr) return "Date vide";

    // Création de l'objet DateTime avec fuseau Europe/Paris
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $datetimeStr, new DateTimeZone('UTC'));

    // Puis on la convertit en Europe/Paris
    $date->setTimezone(new DateTimeZone('Europe/Paris'));
    if (!$date) {
        return "Format de date invalide";
    }

    $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
    $diffInSeconds = $now->getTimestamp() - $date->getTimestamp();

    // Gérer le passé uniquement
    if ($diffInSeconds < 0) {
        return "Dans le futur";
    }

    if ($diffInSeconds < 60) {
        $s = $diffInSeconds;
        return "Il y a $s seconde" . ($s > 1 ? "s" : "");
    }

    $diffInMinutes = floor($diffInSeconds / 60);
    if ($diffInMinutes < 60) {
        return "Il y a $diffInMinutes minute" . ($diffInMinutes > 1 ? "s" : "");
    }

    $diffInHours = floor($diffInMinutes / 60);
    if ($diffInHours < 24) {
        return "Il y a $diffInHours heure" . ($diffInHours > 1 ? "s" : "");
    }

    $diffInDays = floor($diffInHours / 24);
    if ($diffInDays <= 7) {
        return "Il y a $diffInDays jour" . ($diffInDays > 1 ? "s" : "");
    }

    // Affichage complet pour dates anciennes
    $jours = [
        'Sunday' => 'Dimanche', 'Monday' => 'Lundi', 'Tuesday' => 'Mardi',
        'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi',
        'Saturday' => 'Samedi'
    ];
    $mois = [
        'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars',
        'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin',
        'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre',
        'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
    ];

    $jour = $jours[$date->format('l')] ?? $date->format('l');
    $moisNom = $mois[$date->format('F')] ?? $date->format('F');

    return "$jour, " . $date->format('j') . " $moisNom " . $date->format('Y');
}


    
    public function mimeEpxlore($pathFull) {
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
}
