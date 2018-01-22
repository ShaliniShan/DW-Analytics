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
	if(!$acl->hasPermission($_SESSION['userRole']))
		$auth->RedirectToURL("login.php");
