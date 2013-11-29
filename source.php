<?php
require_once('../include/ConnectToDatabase.php');
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

function sendMsg($msg) {
	echo "data: $msg" . PHP_EOL;
	echo PHP_EOL;
	ob_flush();
	flush();
}

function CountRows($statement) {
	if (empty($statement)) {
		return 0;
	}
	$result = mysql_query($statement);
	$row_count = mysql_fetch_row($result);
	return $row_count[0];
}

echo PHP_EOL; // need to pre flush eol;
ob_flush();
flush();
$statement = 'SELECT COUNT(*) FROM test';
$old_count = CountRows($statement);
while(true) {
	$new_count = CountRows($statement);
	if($old_count != $new_count) {
		$old_count = $new_count;
		sendMsg($old_count);
	}
	sleep(1);
}
?>