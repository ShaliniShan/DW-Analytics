<?php
	session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/pages/modules/parseAPI.php';
    $extra = (isset($_POST['ex'])) ? $_POST['ex'] : NULL; 
    $parseAPI = new ParseAPI();
   	echo $parseAPI->HandleAJAX($_POST['id'], $_POST['tb'], $_POST['tm'], $extra);
 