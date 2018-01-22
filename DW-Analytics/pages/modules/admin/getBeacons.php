<?php 
    session_start();
	require_once  $_SERVER['DOCUMENT_ROOT'].'/pages/modules/parseAPI.php';
	//echo "<br> krsna inside getBeacons<br>";
	$parseAPI = new ParseAPI();
	echo $parseAPI->buildTopInteractions($_POST['id']);
	echo $parseAPI->buildTopSpeakers($_POST['id']);
	echo $parseAPI->buildTopSessionAttendance($_POST['id']);
	//echo $adminAPi->deleteRow($_POST['id'], $_POST['class']);
?>
 