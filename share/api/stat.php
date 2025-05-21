<?php
$dateStart = date('Y-m-d', strtotime('-6 days'));

$getAllView = CMS->DataBase->execute(
    "SELECT * FROM omsy_viewpage
     WHERE date_time >= ?
     AND page NOT LIKE '%share%'
     AND page NOT LIKE '%api%'
     AND page NOT LIKE '%404%'
     AND page NOT LIKE '%403%'
     AND page NOT LIKE '%asset%'
     AND page NOT LIKE '%panel%'
     AND page NOT LIKE '%manifest.json%'
     AND page NOT LIKE '%robots.txt%'
     ORDER BY ID DESC",
     [$dateStart]
)->fetchAll();
$notSkipRequest = [];

$countRequest=0;

$buffIp = [];
function getFiles($string) {
	global $rewrites;

	$rewrites = json_decode(file_get_contents(__root__."rewrite.json"));
	$geturl = implode("/", $string);
	foreach ($rewrites as $value) {
		$regex = str_replace("/", "\\/", $value->path);
		preg_match("/$regex/", $geturl, $matches);
		if (isset($matches[0]) !== false){
			return true;
		}
	}

	return false;
}

$days=[];
$link = [];
foreach ($getAllView as $value) {
	$path = explode('/', $value['page']);
	
	//var_dump($value['page']);
	//echo '<br>';
	//echo '<br>';
	
	if(CMS->GFunction->mimeEpxlore($value['page'])) continue;

	$is = getFiles($path);
	if(!$is) continue;
	if(stripos($value['useragent'], 'bot') || stripos($value['useragent'], 'python')) continue;

	$Ymd = date_format(date_create($value['date_time']), 'Y-m-d');
	if(!isset($days[$Ymd])) $days[$Ymd] = ['number' => 0, 'link' => [], 'date' => $value['date_time']];
	$days[$Ymd]['number']++;

	if(!isset($days[$Ymd]['link'][$value['page']])) $days[$Ymd]['link'][$value['page']] = 0;
	$days[$Ymd]['link'][$value['page']]++;

	if(!isset($link[$value['page']])) $link[$value['page']] = 0;
	$link[$value['page']]++;
}
$buff = [
	'days' => array_reverse($days)
];
header('Content-Type: application/json');
echo json_encode($buff);