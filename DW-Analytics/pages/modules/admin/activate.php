<?php

use Parse\ParseObject;
    use Parse\ParseQuery;
    use Parse\ParseUser;
    use Parse\ParseFile;
    use Parse\ParseException;

echo "Haribol";
/*foreach ($_GET as $name => $value) {
    echo $name . ' : ' . $value . '<br />';
}*/
//$_GET['action'] contains verification hash stored in user table
//verfication hash is the exhibitor object id
require_once  $_SERVER['DOCUMENT_ROOT'].'/pages/modules/adminParse.php';

$adminAPI = new AdminParseAPI();
$user_found = $adminAPI->validateUserByHash($_GET['action']);
if ($user_found) {
	$_SESSION['reg_user_id'] = $user_found->getObjectId();
	//$_GET['action'] is Exhibitor Id is same as Verification Hash
	$exhibitor_object = $adminAPI->getExhibitorInfoById($_GET['action']);
	
	
	if ($user_found->get("isVerified") == True) {
		//user already validated (existing user) and have login information,
		//redirect to login screen	
		//Add userexhibitorrole record
			
		$adminAPI->saveUserExhRecordIfNotExist($user_found->getObjectId(), $_GET['action'], $exhibitor_object->get("eventId"));

		$auth = new Authenticator();
		$auth->SetRandomKey('DUu0Ar90MlTS2jw');
		$auth->SetSiteName('digitalwavefront.com');
		$auth->RedirectToURL("/Login/");

	} else {
		//Add userexhibitorrole record
		$adminAPI->saveUserExhRecordIfNotExist($user_found->getObjectId(), $_GET['action'], $exhibitor_object->get("eventId"));
		
		//user not validated (new user), show login screen and gather details
		include_once 'register.php';
		/*$auth = new Authenticator();
		$auth->SetRandomKey('DUu0Ar90MlTS2jw');
		$auth->SetSiteName('digitalwavefront.com');
		$auth->SetRegisterUserId($user_found->getObjectId());
		
		$auth->RedirectToURL("/Register/");	
		*/
	}
} else {
	echo "No user found";
}

?>