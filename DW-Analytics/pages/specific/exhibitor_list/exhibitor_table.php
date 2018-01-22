<?php
	//ini_set('display_errors', true);
	require_once  $_SERVER['DOCUMENT_ROOT'].'/pages/modules/parseAPI.php';
    $parseAPI = new ParseAPI();
    echo $parseAPI->buildAttendeeHourlyTableEx('YSKEVRQZTO',$_POST['eb'], $_POST['em']);
 ?>