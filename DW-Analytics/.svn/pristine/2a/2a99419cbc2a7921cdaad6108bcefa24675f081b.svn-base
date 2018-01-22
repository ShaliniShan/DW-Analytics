<?php
    session_start();
	require_once  $_SERVER['DOCUMENT_ROOT'].'/pages/modules/adminParse.php';
	//echo "<br> krsna inside getBeacons<br>";
	$adminAPI = new AdminParseAPI();
	$action1 = $_POST['action1'];
	//echo var_dump($action1);
	switch($action1) {
		case 'isEventCodeUnique':
			$eventCode = '';
			if (!empty($_POST['params'])) {
				$eventCode = $_POST['params'][0];
				echo $adminAPI->getEventByCode($eventCode);
				//echo var_dump($count);
			}
			break;
		case 'isBeaconAvailable':
			$available_beacon = $adminAPI->getAvailableBeacons();
			
			if (!$available_beacon) {
				echo 0;
			} else {
				echo 1;
			}
			break;
		case 'isSpeakerWithSession':
			if (!empty($_POST['params'])) {
				$speaker_id = $_POST['params'][0];
				$class = $_POST['params'][1];
			    echo $adminAPI->checkDeleteIntegrity($speaker_id, $class);	
			}
			break;
		case 'isBeaconWithNotification':
			if (!empty($_POST['params'])) {
				$beacon_id = $_POST['params'][0];
				$class = $_POST['params'][1];
			    echo $adminAPI->checkDeleteIntegrity($beacon_id, $class);	
			}
			break;
	}
?>
 