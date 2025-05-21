<?php
$getAllSection = CMS->DataBase->execute('SELECT * FROM omsy_session')->fetchAll();

$buff = [];
foreach ($getAllSection as $k => $v) {
    if(stripos($v['agent'], 'bot') || stripos($v['agent'], 'python')) { unset($getAllSection[$k]); continue; }
    $v['last_activity'] = strtotime($v['last_activity']);
    $v['appareil'] = getDeviceType($v['agent']);
    $buff[]=$v;
}

header('Content-Type: application/json');
echo json_encode(['total_session' => count($buff), 'time' => time(), 'list' => $buff]);

function getDeviceType($userAgent = null) {
    // Utiliser le User-Agent fourni ou celui du navigateur actuel
    if ($userAgent === null) {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
    }

    // Détection mobile
    if (preg_match('/mobile|iphone|ipod|android.*mobile|blackberry|nokia|opera mini|windows phone/i', $userAgent)) {
        return 'Mobile';
    }

    // Détection tablette
    if (preg_match('/ipad|android(?!.*mobile)|tablet|kindle|silk/i', $userAgent)) {
        return 'Tablette';
    }

    // Détection TV connectée
    if (preg_match('/smart-tv|smarttv|hbbtv|appletv|googletv|netcast|viera|roku|dtv/i', $userAgent)) {
        return 'TV';
    }

    // Par défaut, considérer comme un ordinateur
    return 'Ordinateur';
}