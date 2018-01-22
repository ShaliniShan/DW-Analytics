<?php
    session_start();
	require_once  $_SERVER['DOCUMENT_ROOT'].'/pages/modules/parseAPI.php';
	//echo "<br> krsna inside getBeacons<br>";
	$parseAPI = new ParseAPI();
	if ($_SESSION['userRole'] == 'o') {
		$topNotifications = $parseAPI->buildTopNotifications($_POST['id']);
	} else if ($_SESSION['userRole'] == 'e') {
		$topNotifications = $parseAPI->buildTopExhibitorNotifications($_POST['id'], $_SESSION['userID']);
	}
	
	if ($topNotifications) {
		echo $topNotifications;
	}
	if ($_SESSION['userRole'] == 'o') {
	$topExhInteractions = $parseAPI->buildTopExhibitorInteractions($_POST['id']);
	$topContent = $parseAPI->buildTopContentLibrary($_POST['id']);
	}
			//if (!$topNotifications && !$topExhInteractions && !$topContent) {
			//echo 0;
			//}
	
	
	if ($topExhInteractions) {
		echo $topExhInteractions;
	}
	if ($topContent) {
		echo $topContent;
	}
	//echo $parseAPI->buildTopNotifications($_POST['id']);
	//echo $parseAPI->buildTopExhibitorInteractions($_POST['id']);
	//echo $parseAPI->buildTopContentLibrary($_POST['id']);

?>