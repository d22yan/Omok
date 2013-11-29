<?php 

$link = mysql_connect('localhost','root',''); 
if (!$link) { 
	die('Could not connect to MySQL: ' . mysql_error()); 
} 
$db_selected = mysql_select_db('database', $link);
if (!$db_selected) {
    die ('Can\'t use database : ' . mysql_error());
}

?> 