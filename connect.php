<?php

if(defined('DB_CONNECTED')) return true;

$dbData = array(
	"host" => 'localhost',
	"user" => 'skdiggyshelper',
	"password" => (strpos($_SERVER["SERVER_NAME"], 'localhost') !== false ? NULL : '7FD58A34E5'),
	"name" => 'skdiggyshelper',
);

$connection = mysql_connect(
	$dbData["host"], 
	$dbData["user"], 
	$dbData["password"]
);

$selectedDb = mysql_select_db($dbData["name"]);

unset($dbData);

if((bool)$connection && $selectedDb) {
	mysql_query("SET NAMES utf8");
	define('DB_CONNECTED', true);
	return true;
}
return false;


?>