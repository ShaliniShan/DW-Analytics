<?php
	require_once('pages/modules/authenticator.php');
	require_once('pages/modules/ACL.php');
	if (isset($_GET['page']) && ($_GET['page'] == 'admin') &&
	isset($_GET['event']) && ($_GET['event'] == 'activate')) {
        include_once 'pages/modules/admin/activate.php';
	} else {
		$auth = new Authenticator();
		$auth->SetRandomKey('DUu0Ar90MlTS2jw');
		$auth->SetSiteName('digitalwavefront.com');
		if(!$auth->CheckLogin()) {
			$auth->RedirectToURL("/Login/");
		}
		$acl = new ACL($_SESSION['userID']);
		if(!$acl->hasPermission($_SESSION['userRole']))
		$auth->RedirectToURL("login.php");
	}