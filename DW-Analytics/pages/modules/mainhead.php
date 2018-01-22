<?php
	require_once('pages/modules/authenticator.php');
	require_once('pages/modules/ACL.php');
	$auth = new Authenticator();
	$auth->SetRandomKey('DUu0Ar90MlTS2jw');
	$auth->SetSiteName('digitalwavefront.com');
	if(!$auth->CheckLogin()) { 
		$auth->RedirectToURL("/Login/");
	}
	$acl = new ACL($_SESSION['userID']);
	echo var_dump($acl->getUserPerm());
	if($acl->getUserPerm() == 1 && $_SESSION['userRole'] == 'o') {
		$auth->RedirectToURL("/Organizer/");
	} else if ($acl->getUserPerm() == 1 && $_SESSION['userRole'] == 'e') {
		//user is an exhibitor
		$auth->RedirectToURL("/Exhibitor/");
		//$auth->RedirectToURL("/Exhibitor/");
		
	}
