<?php
  session_start();
	require_once  $_SERVER['DOCUMENT_ROOT'].'/pages/modules/parseAPI.php';
	//echo "<br> krsna inside getBeacons<br>";
	$parseAPI = new ParseAPI();
	
	/*if ($_SESSION['userRole'] == 'o') {
		$topInteractions = $parseAPI->buildTopInteractions($_POST['id']);
	} else if ($_SESSION['userRole'] == 'e') {
		$topInteractions = $parseAPI->buildTopExhibitorBeaconInteractions($_POST['id'], $_SESSION['userID']);
	}*/
	if ($_SESSION['userRole'] == 'o') {
	$topSpeakers = $parseAPI->buildTopSpeakersByAttendance($_POST['id']);
	//$topSpeakers = $parseAPI->buildTopSpeakers($_POST['id']);
	}if ($topSpeakers) {
		echo $topSpeakers;
	} 
	
	//echo $parseAPI->buildTopInteractions($_POST['id']);
	//echo $parseAPI->buildTopSpeakers($_POST['id']);
	//echo $parseAPI->buildTopSessionAttendance($_POST['id']);
?>
 