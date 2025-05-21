<?php
CMS->DataBase->execute("DELETE FROM omsy_session WHERE last_activity < NOW() - INTERVAL ? SECOND", [300]);
$existe = CMS->DataBase->execute("DELETE FROM omsy_session WHERE session_id = ?", [session_id()])->fetchAll();
if(isset($existe[0])) CMS->DataBase->execute("UPDATE omsy_session SET last_activity = Now() AND page = ? WHERE session_id = ?", [$_SERVER['HTTP_USER_AGENT'], session_id()]);
else {
	CMS->DataBase->execute(
		'INSERT INTO omsy_session (session_id, page, agent, last_activity) VALUES(?, ?, ?, NOW())',
		[session_id(), $_POST['page'], $_SERVER['HTTP_USER_AGENT']]
	);
}