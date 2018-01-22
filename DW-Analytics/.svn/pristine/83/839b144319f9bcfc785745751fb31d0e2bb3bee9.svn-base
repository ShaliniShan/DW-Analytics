<?php
    session_start();
	require_once  $_SERVER['DOCUMENT_ROOT'].'/pages/modules/parseAPI.php';
	//echo "<br> krsna inside getBeacons<br>";
	$parseAPI = new ParseAPI();
	$topNotifications = $parseAPI->buildTopNotifications($_POST['id']);
	$topExhInteractions = $parseAPI->buildTopExhibitorInteractions($_POST['id']);
	$topContent = $parseAPI->buildTopContentLibrary($_POST['id']);
	
	//if (!$topNotifications && !$topExhInteractions && !$topContent) {
		//echo 0;
	//}
	
	if ($topNotifications) {
		echo $topNotifications;
	}
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