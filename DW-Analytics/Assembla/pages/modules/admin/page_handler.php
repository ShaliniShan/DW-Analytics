<?php
    $inIncluded = false;
    if (count($_GET) == 4) {
    	if (isset($_GET['page']) && ($_GET['page'] == 'admin') &&
    	     isset($_GET['action']) && ($_GET['action'] == 'edit') &&
    	     isset($_GET['event']) && isset($_GET['specific']) && ($_GET['specific'] == 'event')) {
    	     	$isIncluded = true;
    	     	$_SESSION['userAction'] = 'edit';
    	     	unset($_SESSION['newTabObjId']);
    	         include_once 'pages/modules/admin/edit_specific_details.php';
    	     }
    	     
    	     if (isset($_GET['specific'])) {
    	     	$specificArray = explode("-", $_GET['specific']);
    	     	if ($specificArray[0] == 'add') {
    	     		$event_obj = $adminAPI->getEventFromEventCode($_GET['event']);
    	     			
    	     		if ($event_obj) {
    	     			$_SESSION['newEventId'] = $event_obj->getObjectId();
    	     		}
    	     		$adminAPI->setEventId($_SESSION['newEventId']);
    	     		
    	     		$isIncluded = true;
    	     		include_once 'pages/modules/admin/event_list_add.php';
    	     	}
    	     }
    }
    if ($isIncluded == false) {
    	if (isset($_GET['page']) && ($_GET['page'] == 'admin') &&
    	isset($_GET['action']) && ($_GET['action'] == 'edit') &&
    	isset($_GET['event']) && isset($_GET['specific']) &&
    	isset($_GET['class'])) {
    		$isIncluded = true;
    		$_SESSION['userAction'] = 'edit';
    		unset($_SESSION['newTabObjId']);
    		include_once 'pages/modules/admin/edit_specific_details.php';
    	}
    	
    	if (isset($_GET['page']) && ($_GET['page'] == 'admin') &&
    	isset($_GET['event']) && ($_GET['event'] == 'edit') &&
    	isset($_GET['action'])) {
    		$isIncluded = true;
    		/*
    		 * edit event, _GET[action] will be event code
    		 */
    		$event_obj = $adminAPI->getEventFromEventCode($_GET['action']);
    			
    		if ($event_obj) {
    			$_SESSION['newEventId'] = $event_obj->getObjectId();
    		}
    		$adminAPI->setEventId($_SESSION['newEventId']);
    		include_once 'pages/modules/admin/event_list_add.php';
    	}
    }
    
	/* Displays correct page in Organizer.php */
	if(isset($_GET['page']) && ($isIncluded == false)) {
		//foreach ($_GET as $name => $value) {
			//echo $name . ' : ' . $value . '<br />';
		//}
		 if($_GET['page'] == 'admin' && isset($_GET['event']) && $_GET['event'] == 'new-event') {
			unset ($_SESSION['newEventId']);
			$_SESSION['newEventId'] = null;
			$_SESSION['userAction'] = 'add';
			include_once 'pages/modules/admin/add_event.php';
		} else if($_GET['page'] == 'Admin' && isset($_GET['action']) && $_GET['action'] == 'analytics') {
			include_once 'pages/modules/admin/analytics_graph.php';
		} else if($_GET['page'] == 'Admin' && isset($_GET['action']) && $_GET['action'] == 'attendee') {
			include_once 'pages/attendee.php';
		} else if($_GET['page'] == 'attendee' && isset($_GET['event']) && !isset($_GET['action'])) {
			include_once 'pages/specific/attendee_list/attendee_event.php';
		} else if($_GET['page'] == 'attendee' && isset($_GET['event']) && isset($_GET['action'])) {
			include_once 'pages/specific/attendee_list/attendee_content.php';
		} else if($_GET['page'] == 'attendees') {
			include_once 'pages/specific/attendees_list.php';
		} else if($_GET['action'] == 'edit') {
			include_once 'pages/modules/admin/edit_details.php';
		}
	} else {
		if (!$isIncluded) {
		    include_once 'pages/modules/admin/home.php';
		}
	}	
	//if ($inIncluded == false) {
		//include_once 'pages/modules/admin/event_test.php';
	//}
