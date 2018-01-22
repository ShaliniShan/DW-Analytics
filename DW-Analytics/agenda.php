<?php
	// ini_set("display_errors", true);
	require_once 'pages/modules/authenticator.php';
	$auth = new Authenticator();
	if($auth->isEventCode()) 
		require_once 'pages/modules/agenda/initial.php';
	else 
		$auth->RedirectToURL("/Login/");