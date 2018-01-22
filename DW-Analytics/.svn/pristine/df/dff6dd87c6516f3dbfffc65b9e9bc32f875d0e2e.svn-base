<?php 
    session_start();
	require_once  $_SERVER['DOCUMENT_ROOT'].'/pages/modules/parseAPI.php';
	//echo "<br> krsna inside getBeacons<br>";
	$parseAPI = new ParseAPI();
	$topInteractions = $parseAPI->buildTopInteractions($_POST['id']);
	$topSpeakers = $parseAPI->buildTopSpeakers($_POST['id']);
	$topSessions = $parseAPI->buildTopSessionAttendance($_POST['id']);
	
	//if (!$topInteractions && !$topSpeakers && !$topSessions) {
		//echo 0;
	//}
	
	if ($topInteractions) {
		echo $topInteractions;
	} 
	if ($topSpeakers) {
		echo $topSpeakers;
	}
	if ($topSessions) {
		echo $topSessions;
	}
	//echo $parseAPI->buildTopInteractions($_POST['id']);
	//echo $parseAPI->buildTopSpeakers($_POST['id']);
	//echo $parseAPI->buildTopSessionAttendance($_POST['id']);
?>
 