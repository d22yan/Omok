<?php 

$hostname = 'localhost';
$username = 'root';
$password = 'Winter';
$database = 'dtest';

$mysql_connect = mysql_connect($hostname, $username, $password);
if (!$mysql_connect) { 
	die('Could not connect to MySQL: ' . mysql_error()); 
} 
$mysql_select_db = mysql_select_db($database, $mysql_connect);
if (!$mysql_select_db) {
    die ('Can\'t use database : ' . mysql_error());
}

?> 