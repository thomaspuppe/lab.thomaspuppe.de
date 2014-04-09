<?php
// fake load time on localhost
if($_SERVER['SERVER_ADDR']==$_SERVER['REMOTE_ADDR']){
	sleep(1);
}

header('Content-Type: image/jpeg');
readfile($_GET['i']);
die();
?>
