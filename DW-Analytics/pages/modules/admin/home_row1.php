<?php 
    session_start();
	require_once  $_SERVER['DOCUMENT_ROOT'].'/pages/modules/parseAPI.php';
	//echo "<br> krsna inside getBeacons<br>";
	$parseAPI = new ParseAPI();
	
	if ($_SESSION['userRole'] == 'o') {
		$topInteractions = $parseAPI->buildTopInteractions($_POST['id']);
		$topSpeakers = $parseAPI->buildTopSpeakers($_POST['id']);
		$topSessions = $parseAPI->buildTopSessionAttendance($_POST['id']);
	} else if ($_SESSION['userRole'] == 'e') {
		$topInteractions = $parseAPI->buildTopExhibitorBeaconInteractions($_POST['id'], $_SESSION['userID']);
		$topSpeakers = $parseAPI->buildTopSpeakersByAttendance($_POST['id']);
		$topVisitors = $parseAPI->buildTopVisitorsByInteractions($_POST['id'], $_SESSION['userID']);
	}
	if ($topSpeakers) {
		echo $topSpeakers;
	} 
	if ($topInteractions) {
		echo $topInteractions;
	} 
	if ($topVisitors) {
		echo $topVisitors;
	} 
	
	if ($topSessions) {
		echo $topSessions;
	}
	
	


	
	
	
	//if (!$topInteractions && !$topSpeakers && !$topSessions) {
		//echo 0;
	//}
	
	
	//echo $parseAPI->buildTopInteractions($_POST['id']);
	//echo $parseAPI->buildTopSpeakers($_POST['id']);
	//echo $parseAPI->buildTopSessionAttendance($_POST['id']);
?>
 