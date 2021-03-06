<<<<<<< .mine
<?php
$cancel = 0;

//foreach ($_GET as $name => $value) {
    //echo $name . ' : ' . $value . '<br />';
//}

//echo var_dump($_SERVER['REQUEST_URI']);

function checkIfSubmitClicked() {
	if (isset($_POST['submit_event']) ||
	    isset($_POST['submit_speaker']) ||
	    isset($_POST['submit_sponsor']) ||
	    isset($_POST['submit_session']) ||
	    isset($_POST['submit_exhibitor']) ||
	    isset($_POST['submit_beacon']) ||
	    isset($_POST['submit_notification']) ||
	    isset($_POST['submit_content']) ||
	    isset($_POST['submit_survey'])) {
	        return true;	
	    }
	    return false;
}

$_SESSION['curURI'] = $_SERVER['REQUEST_URI'];	

if (isset($_SESSION['prevURI'])) {
	if ($_SESSION['prevURI'] != $_SESSION['curURI']) {
	    if ($_SESSION['userAction'] == 'add' && !isset($_SESSION['newTabObjId'])) {
	    	/*
	    	 * it is cancel action by user
	    	 */
	    	if (!checkIfSubmitClicked()) {
	    		$cancel = 1;
	    		$_SESSION['userAction'] = 'none';
	    	}
	    }	
	}
	if ($_SESSION['userAction'] == 'edit' && !isset($_SESSION['newTabObjId'])) {
		/*
		 * cancel action from edit screen
		 */
		if (!checkIfSubmitClicked()) {
			$cancel = 1;
			$prevURI = $_SESSION['prevURI'];
			$subURIArray = explode("/", $prevURI);
			$tabArray = explode("-", $subURIArray[5]);
			$_SESSION['active_tab'] = $tabArray[1];
			$_SESSION['userAction'] = 'none';
		}
		
	}
} 
 
$event_obj = $adminAPI->getEventById($_SESSION['newEventId']);

$submit_details = 0;
if(isset($_POST['submit_event'])) {
	//echo var_dump($_SESSION['userAction']);
	//echo var_dump($_SESSION['newEventId']);
	if (($_SESSION['userAction'] == 'add' && $_SESSION['newEventId'] == null) ||
	    ($_SESSION['userAction'] == 'edit' && $_SESSION['updateObjId'] != null)) {
		$new_event_obj = $adminAPI->submitNewEvent(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['userID'], $_POST['event_title'],$_POST['event_timezone'],$_POST['event_code'],$_FILES['event_logo'], $_POST['event_location'], $_POST['event_address1'], $_POST['event_address2'], $_POST['event_city'], $_POST['event_state'], $_POST['event_zip'], $_POST['event_country'], $_POST['event_start'], $_POST['event_end'],  $_POST['event_hashtags']);
		//echo var_dump($new_event_obj->getObjectId());
		$_SESSION['newEventCode'] = $new_event_obj->get("eventCode");
		$_SESSION['newEventId'] = $new_event_obj->getObjectId();
		$adminAPI->setEventId($_SESSION['newEventId']);
		$_SESSION['active_tab'] = 'event';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	}
} else if(isset($_POST['submit_speaker'])) {
		$adminAPI->submitNewSpeaker(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL), $_SESSION['newEventId'], $_POST['speaker_name'], $_POST['speaker_company'], $_POST['speaker_title'], $_POST['speaker_bio'], $_POST['speaker_li_profile'], $_POST['speaker_tw_profile'], $_FILES['speaker_image']);
		$_POST['submit_speaker'] = '';
		unset($_POST['submit_speaker']);
		$_SESSION['active_tab'] = 'speaker';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	}else if(isset($_POST['submit_sponsor'])) {
		$adminAPI->submitNewSponsor(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['sponsor_name'], $_POST['sponsor_profile'], $_POST['sponsor_level'],$_POST['sponsor_website'], $_FILES['sponsor_logo']);
		$_SESSION['active_tab'] = 'sponsor';
		$_POST['submit_sponsor'] = '';
		unset($_POST['submit_sponsor']);
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_session'])) {
		$adminAPI->submitNewSession(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL), $_SESSION['newEventId'], $_POST['session_title'], $_POST['session_description'], $_POST['session_start'], $_POST['session_end'], $_POST['session_speaker']);
	     
			
		$_POST['submit_session'] = '';
		unset($_POST['submit_session']);
		$_SESSION['active_tab'] = 'session';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_exhibitor'])) {
		$adminAPI->submitNewExhibitor(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['exhibitor_name'], $_POST['exhibitor_email'], $_POST['exhibitor_summary'], $_POST['exhibitor_booth'],$_POST['exhibitor_website'], $_FILES['exhibitor_logo']);
		$_SESSION['active_tab'] = 'exhibitor';
		unset($_POST['submit_exhibitor']);
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_beacon'])) {
		//$beacon_opts = $_POST['beacon_options'] ? $_POST['beacon_options'] : NULL;
		$beacon_type_val = ($_POST['beacon_type'] == 'Exhibitor') ? $_POST['beacon_exhibitor'] : $_POST['beacon_session'];
		echo var_dump($_POST['beacon_type']);
		echo var_dump($_POST['beacon_exhibitor']);
		echo var_dump($_POST['beacon_session']);  
		//echo var_dump($beacon_type_val);
		$adminAPI->submitNewBeacon(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['beacon_name'], $_POST['beacon_type'], $beacon_type_val);
		$_SESSION['active_tab'] = 'beacon';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_notification'])) {
		$adminAPI->submitNewNotification(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['notification_title'], $_POST['notification_message'], $_FILES['notification_pdf'], $_POST['notification_trigger'], $_POST['notification_url'], $_POST['notification_beacon']);
		$_SESSION['active_tab'] = 'notification';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	}else if(isset($_POST['submit_content'])) {
		$adminAPI->submitNewContent(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['content_title'], $_POST['content_description'], $_FILES['content_pdf'], $_POST['content_url']);
		$_SESSION['active_tab'] = 'content';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	 } else if(isset($_POST['submit_survey'])) {
		$questions_array = array();
		for($i = 1; $i <= $_POST['survey_questions']; $i++) {
			array_push($questions_array, $_POST['survey_question'.$i.'_add']);
		}
		if ($_SESSION['updateObjId']) {
		    $adminAPI->submitNewSurvey($_SESSION['updateObjId'],$_SESSION['newEventId'], $_POST['survey_question']);
		    $_SESSION['updateObjId'] = null;	
		} else {
		    $adminAPI->submitNewSurvey(NULL,$_SESSION['newEventId'], $questions_array);	
		}
		//$adminAPI->submitNewSurvey(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $questions_array);
		$_SESSION['active_tab'] = 'survey';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
	}
	$event_no_content = 0;
	$speaker_rec = $session_rec = $exhibitor_rec = $beacon_rec = $content_rec = $not_rec = $survey_rec = $sponsor_rec = 0;
	
	$speaker_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Speaker');
	$session_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Session');
	$exhibitor_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Exhibitor');
	$beacon_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Beacon');
	$content_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Content');
	$not_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Notification');
	$survey_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Survey');
	$sponsor_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Sponsor');
	
	if (!$speaker_rec &&  
	     !$session_rec &&
	     !$exhibitor_rec &&
	     !$beacon_rec &&
	     !$content_rec &&
	     !$not_rec &&
	     !$survey_rec &&
	     !$sponsor_rec) {
	     	$event_no_content = 1;
	     }
	 
	 
	 if (isset($_POST['add_event_details'])) {
	     $event_no_content = 0;
     }
     
     $display_event_form = $display_speaker_form = $display_session_form = $display_exh_form = $display_beacon_form = 0;
     $display_not_form = $display_survey_form = $display_sponsor_form = $display_content_form = 0;
     if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'event')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	//$display_event_form = 1;
     
     }else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'speaker')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_speaker_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'session')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_session_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'exhibitor')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_exh_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'beacon')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_beacon_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'notification')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_not_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'survey')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_survey_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'sponsor')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_sponsor_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'content')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_content_form = 1;
     }
     
     $list_event_view = $list_speaker_view = $list_session_view = $list_exh_view = $list_beacon_view = 0;
     $list_not_view = $list_survey_view = $list_sponsor_view = $list_content_view = 0;
     
     if ($event_no_content == 0) {
     	 //if ($event_rec) {
           	//$display_event_form = 1;
         //}
        
         if ($speaker_rec) {
         	$list_speaker_view = 1;
         } else {
         	$display_speaker_form = 1;
         }
         if ($session_rec) {
         	$list_session_view = 1;
         } else {
         	$display_session_form = 1;
         }
         if ($exhibitor_rec) {
         	$list_exh_view = 1;
         } else {
         	$display_exh_form = 1;
         }
         if ($beacon_rec) {
         	$list_beacon_view = 1;
         } else {
         	$display_beacon_form = 1;
         }
         if ($not_rec) {
         	$list_not_view = 1;
         } else {
         	$display_not_form = 1;
         } 
         if ($survey_rec) {
         	$list_survey_view = 1;
         } else {
         	$display_survey_form = 1;
         } 
         if ($sponsor_rec) {
         	$list_sponsor_view = 1;
         } else {
         	$display_sponsor_form = 1;
         } 
         if ($content_rec) {
         	$list_content_view = 1;
         } else {
         	$display_content_form = 1;
         } 	
     }
     
     //No need to have list view, when displaying form
     if ($display_event_form) {
     	$list_event_view = 0;
     }
     if ($display_speaker_form) {
     	$list_speaker_view = 0;
     }
     if ($display_session_form) {
     	$list_session_view = 0;
     }
     if ($display_exh_form) {
     	$list_exh_view = 0;
     }
     if ($display_beacon_form) {
     	$list_beacon_view = 0;
     }
     if ($display_not_form) {
     	$list_not_view = 0;
     }
     if ($display_sponsor_form) {
     	$list_sponsor_view = 0;
     }
     if ($display_survey_form) {
     	$list_survey_view = 0;
     }
     if ($display_content_form)	 {
     	$list_content_view = 0;
     }
     
     $_SESSION['prevURI'] = $_SESSION['curURI'];
?>
<style type = "text/css">
#date_pick{ position: relative;
				z-index: 1000; 
}

 .btn-custom {
    color: #fff;
    background-color: #000bcd4;
  
}
div.container-fluid{
width:100%;																;
}

.button.nohover:hover	{
	 color: #fff;
    background-color: #000bcd4;
    cursor:default !important;
   }
   
   .editlistdashboard {
     border-top:5px solid transparent;
     height:100%;
     width:100%;
     white-space:nowrap;
     font-size:0
     }
</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
<script src = "https://code.jquery.com/jquery-1.12.4.js"></script>
<script src = "https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src = "https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>

 <div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
	 	<div class="row">  
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="btn-info btn-block btn-xs" 	 style="font-color:white;">
						<h3 class= ><i class="fa fa-edit"></i><em > Edit Event - <?php  
						
						$current_event = $adminAPI->getEventInfo($_SESSION['newEventId']);
				       
				       if ($current_event && $current_event->get('name')) {
					      echo $current_event->get('name');
				       }
						
						?></em></h3>
					</div>
					<div class="panel-body">
						<div class="submissions">
				          <ul class="nav nav-tabs" role="tablist">
				          	<li class="<?php echo (($_SESSION['active_tab'] == 'event' || $_SESSION['active_tab'] == '')?'active':'')?>"><a class="center tab_color" href="#tab0" role="tab" data-toggle="tab">
				              <span class="fa fa-info-circle"></span><br>Event Info</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'speaker'?'active':'')?>"><a class="center tab_color" href="#tab1" role="presentation" data-toggle="tab">
				              <span class="fa fa-users"></span><br>Speakers</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'session'?'active':'')?>"><a class="center tab_color" href="#tab2" role="presentation" data-toggle="tab">
				              <span class="fa fa-bars"></span><br>Sessions</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'beacon'?'active':'')?>"><a class="center tab_color" href="#tab4" role="presentation" data-toggle="tab">
				              <span class="fa fa-bluetooth"></span><br>Beacons</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'notification'?'active':'')?>"><a class="center tab_color" href="#tab5" role="presentation" data-toggle="tab">
				              <span class="fa fa-clock-o"></span><br>Triggered Content</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'survey'?'active':'')?>"><a class="center tab_color" href="#tab6" role="presentation" data-toggle="tab">
				              <span class="fa fa-question-circle"></span><br>Survey Questions</a></li>
							<li class="<?php echo ($_SESSION['active_tab'] == 'exhibitor'?'active':'')?>"><a class="center tab_color" href="#tab3" role="presentation" data-toggle="tab">
				              <span class="fa fa-id-card"></span><br>Exhibitors</a></li>
							<li class="<?php echo ($_SESSION['active_tab'] == 'sponsor'?'active':'')?>"><a class="center tab_color" href="#tab7" role="presentation" data-toggle="tab">
				              <span class="fa fa-trophy"></span><br>Sponsors</a></li>			          
				            <li class="<?php echo ($_SESSION['active_tab'] == 'content'?'active':'')?>"><a class="center tab_color" href="#tab8" role="presentation" data-toggle="tab">
				              <span class="fa fa-files-o"></span><br>Content</a></li>
				              <?php
				              
				              $eventCode = '';
				              if ($event_obj) {
				              	$eventCode = $event_obj->get("eventCode");
				              } else {
				              	if (count($_GET) == 3 && isset($_GET['action'])) {
				              		$eventCode = $_GET['action'];
				              	}
				              }
				             $add_page_url = "/Organizer/admin/edit/".$eventCode;
				              
				              if ($event_no_content == 1) {
				              	
				           ?>
				           
				             
						     <li role="presentation" style="float:right;"><form name='add_button_form' action=<?=$add_page_url ?> method='POST'>
						         <input type="hidden" id="actab" name="activeTab" value=<?php echo ($submit_details == 1)?'':(($cancel == 1)?'':$_SESSION['active_tab'])?>>
						         <button name="add_event_details" id = "addButton"  href="javascript:;" type='submit' onClick="return Error_checks.getActiveTab();" class="btn btn-custom btn-sm btn-block">Add</button>
						      </form></li>
						   
				           <?php
				              } 
				              
				              if ($event_no_content == 0) {
				              	
				              	if (($list_speaker_view == 1 && $display_speaker_form == 0) ||
				              	    ($list_session_view == 1 && $display_speaker_form == 0) ||
				              	    ($list_exh_view == 1 && $display_exh_form == 0) ||
				              	    ($list_beacon_view == 1 && $display_beacon_form == 0) ||
				              	    ($list_not_view == 1 && $display_not_form ==0) ||
				              	    ($list_survey_view == 1 && $display_survey_form == 0) ||
				              	    ($list_sponsor_view == 1 && $display_sponsor_form == 0) || 
				              	    ($list_content_view == 1 && $display_content_form == 0)) {
				              	    	
				            ?>
				            <li role="presentation" style="float:right;"><form name='add_button_form' action=<?=$add_page_url ?> method='POST'>
						         <input type="hidden" id="actab" name="activeTab" value=<?php echo ($submit_details == 1)?'':(($cancel == 1)?'':$_SESSION['active_tab'])?>>
						         <button name="add_event_details" id = "addButton"  href="javascript:;" type='submit' onClick="return Error_checks.getActiveTab();" class="btn btn-sm btn-custom btn-block">Add</button>
						      </form></li>
				            <?php  		
				              	}
				              }
				          ?>
				          </ul>
				          
				          <div class="tab-content">
				          
				            <div role="tabpanel" class="tab-pane fade <?php echo (($_SESSION['active_tab'] == '' || $_SESSION['active_tab'] == 'event')?'in active':'')?>" id="tab0">
				              <div class="row">
				                  <?php echo $adminAPI->buildEventEditDisplay($_SESSION['newEventId']);?>   				              				             
				           	  </div>
				           </div>
				            
				            <div role="tabpanel" class="tab-pane fade <?php echo (($_SESSION['active_tab'] == 'speaker')?'in active':'')?>" id="tab1">
				          	              <div class="row">
				                  <?php 
				                  if ($list_speaker_view) {
				                  	//Get the list view for speaker
				                  	echo $adminAPI->buildSpeakersAtEvent($_SESSION['newEventId'], ($display_form==1)?false:true);
				                  	//include_once 'pages/modules/admin/edit_display_list.php';
				                  	 
				                  } ?>
				                  
				               
				                  <?php 
				                  if ($display_speaker_form) {
				                  	  $_SESSION['userAction'] = 'add';
				                  	  unset($_SESSION['newTabObjId']);
				                   ?>
				                   <form id='add_speaker' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_1"></div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_name">Name</label>
						              <input type='text' name='speaker_name' class="form-control input-lg" id='speaker_name' value='' placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_company">Company</label>
						              <input type='text' name='speaker_company' class="form-control input-lg" id='speaker_company' value='' placeholder="Company">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_title">Title</label>
						              <input type='text' name='speaker_title' class="form-control input-lg" id='speaker_title' value='' placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_bio">Bio/Summary</label>
						          	 <textarea name='speaker_bio'  rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='speaker_bio' placeholder=" Bio"></textarea>	
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_li_profile">LinkedIn Profile</label>
						              <input type='text' name='speaker_li_profile' class="form-control input-lg" id='speaker_li_profile' value='' placeholder="https://www.linkedin.com/in/ExampleName/">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_tw_profile">Twitter Profile</label>
						              <input type='text' name='speaker_tw_profile' class="form-control input-lg" id='speaker_tw_profile' value='' placeholder="@ExampleName">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_image">Image</label>
						              <input type='file' name='speaker_image' class="file logo_ex form-control input-lg" id='speaker_image' value='' >
						          </div>
						          <div class="form-group col-md-6">
						              <button name="submit_speaker" id = "submitButton"  href="javascript:;" type='submit' onClick="return Error_checks.automaticCheck('new_speaker');" class="btn btn-custom btn-lg">Add Speaker</button>
						              <a id="cancelButton" onClick="" href=<?=$add_page_url ?> style= "a:active {font-color: red;font-style:underline}">Cancel</a>
						          </div>
						          
						            </form>
				                    <?php  		
				                      	}
				                  ?>
				                
				                
						            </div>
				                   </div>
				         
				           	 	            
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'session'?'in active':'')?>" id="tab2">
				              <div class="row">
				                  <?php
				                      
				                  if ($list_session_view) {
				                  	//Get the list view for speaker
				                  	echo $adminAPI->buildSessionsAtEvent($_SESSION['newEventId']);
				                  }
				                  if ($display_session_form) {
				                  	  $_SESSION['userAction'] = 'add';
				                  	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_session' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_2"></div>
						          <div class="form-group col-md-12">
						          	  <label for="session_title">Title</label>
						              <input type='text' name='session_title' class="form-control input-lg" id='session_title' value='' placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_description">Description</label>
						              <textarea name='session_description' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='session_description' value='' placeholder="Description"></textarea>
						          </div>
						          <div class="form-group col-md-3">
						          	  <label for="session_start">Start Time</label>
						              <input type='text' name='session_start' class="date_pick form-control input-md" id='session_start' value='' placeholder="Start Time">
						          </div>
						          <div class="form-group col-md-3">
						          	  <label for="session_end">End Time</label>
						              <input type='text' name='session_end' class="date_pick form-control input-md"  style = ".date_pick { z-index: 1000; }"  id='session_end' value='' placeholder="End Time">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_speaker">Speakers</label>
						              <select data-live-search='true' style="background-color: #5e6870;" data-style="input-lg btn-lg" name='session_speaker[]' class=" form-control input-lg selectpicker" id='session_speaker' value='' multiple >
						              	<?= $adminAPI->buildSpeakerSelection(); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_session" type='submit' onClick="return Error_checks.automaticCheck('new_session');" class="btn btn-custom btn-lg">Add Session</button>
						          		<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						        </form>
						        <?php  		
				                      	} 
				                  ?>
				              </div>
				            </div>
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'exhibitor'?'in active':'')?>" id="tab3">
				              <div class="row">
				                  <?php
				                      
				                  if ($list_exh_view) {
				                  	//Get the list view for exhibitor
				                  	echo $adminAPI->buildExhibitorAtEvent($_SESSION['newEventId']);
				                  }
				                  if ($display_exh_form) {
				                  	  $_SESSION['userAction'] = 'add';
				                  	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_exhibitor' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_3"></div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_name">Name</label>
						              <input type='text' name='exhibitor_name' class="form-control input-lg" id='exhibitor_name' value='' placeholder="Name">
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_email">Email</label>
						              <input type='text' name='exhibitor_email' class="form-control input-lg" id='exhibitor_website' value='' placeholder="Email">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_summary">Company Profile/Summary</label>
						              <textarea name='exhibitor_summary' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='exhibitor_summary' value='' placeholder="Summary"></textarea>
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_booth">Booth #</label>
						              <input type='text' name='exhibitor_booth' class="form-control input-lg" id='exhibitor_booth' value='' placeholder="Booth #">
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_website">Website</label>
						              <input type='text' name='exhibitor_website' class="form-control input-lg" id='exhibitor_website' value='' placeholder="Website">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_logo">Logo</label>
						              <input type='file' name='exhibitor_logo' class="file logo_ex form-control input-lg" id='exhibitor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_exhibitor" type='submit' onClick="return Error_checks.automaticCheck('new_exhibitor');" class="btn btn-custom btn-lg ">Add Exhibitor</button>
						             <a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						        </form>
						        <?php  		
				                      	}
				                  ?>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'beacon'?'in active':'')?>" id="tab4">
				              <div class="row">
				              <?php
				                     
				              if ($list_beacon_view) {
				              	//Get the list view for beacon
				              	echo $adminAPI->buildBeaconAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_beacon_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_beacon' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_4"></div>
						          <div class="form-group col-md-12">
						          	  <label for="beacon_name">Name</label>
						              <input type='text' name='beacon_name' class="form-control input-lg" id='beacon_name' value='' placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="beacon_type">Beacon Type</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" name='beacon_type' class="form-control input-lg selectpicker" id='beacon_type' title="Choose a type">
						      			<option value="Organizer">Organizer</option>
						              	<option value="Exhibitor">Exhibitor</option>
						              	<option value="Session">Session</option>
						              </select>
						          </div>
						          <div class="beacon_container">
						          <div class="Session">
						          <div class="form-group col-md-12">
						          	  <label for="beacon_session">Session</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" name='beacon_session[]' class="form-control input-lg selectpicker" id='beacon_session' value='' title="Choose multiple Sessions if required">
						              	<?= $adminAPI->buildSessionSelection(); ?>
						              </select>
						          </div>
						          </div>
						          <div class="Exhibitor">
						          <div class="form-group col-md-12">
						          	  <label for="beacon_exhibitor">Exhibitor</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" name='beacon_exhibitor[]' class="form-control input-lg selectpicker" id='beacon_exhibitor' value='' title="Choose a Exhibitor">
						              	<?= $adminAPI->buildExhibitorSelection(); ?>
						              </select>
						          </div>
						          </div>
						          </div>
						          <div id="beacon_options_container" class="form-group col-md-12"></div>
						          <div class="form-group col-md-12">
						              <button name="submit_beacon" type='submit' onClick="return Error_checks.automaticCheck('new_beacon');" class="btn btn-custom btn-lg">Add Beacon</button>
									<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>							          
						          </div>
						        </form>
						        <?php  		
				                      	}			                    
				                  ?>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'notification'?'in active':'')?>" id="tab5">
				              <div class="row">
				              <?php
				                     
				              if ($list_not_view) {
				              	//Get the list view for speaker
				              	echo $adminAPI->buildNotificationAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_not_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_notification' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_5"></div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_title">Title</label>
						              <input type='text' name='notification_title' class="form-control input-lg" id='notification_title' value='' placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_message">Message</label>
						              <input type='text' name='notification_message' class="form-control input-lg" id='notification_message' value='' placeholder="Message">
						          </div>
						          <div class="form-group col-md-6">
						          	  <label for="notification_pdf">PDF</label>
						              <input type='file' name='notification_pdf' class="file logo_ex form-control input-lg" id='notification_pdf'>
						          </div>
						      	  <div class="form-group col-md-6">
						          	  <label for="notification_url">URL</label>
						              <input type='text' name='notification_url' class="form-control input-lg" id='notification_url' value='' placeholder="URL">
						          </div>
						          <div class="form-group col-md-12">
						          	<label for="notification_trigger">Trigger After(xx minutes)</label>
						          	<select data-live-search='true' data-style="input-lg btn-lg" class="form-control input-lg selectpicker" name="notification_trigger" id="notification_trigger">
						          		<option selected disabled>Choose a Time</option>
						          		<?= $adminAPI->buildNotificationSelect($_SESSION['newEventId']); ?>
						          	</select>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_beacon">Beacon</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" name='notification_beacon' class="form-control input-lg selectpicker" id='notification_beacon'>
						              	<option selected disabled>Choose a Beacon</option>
						              	<?= $adminAPI->buildBeaconDropDown($_SESSION['newEventId']); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_notification" type='submit' onClick="return Error_checks.automaticCheck('new_notification');" class="btn btn-custom btn-lg">Add Notification</button>
										<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>						          
						          </div>
						        </form>
						        <?php  		
				                      	}				                      
				                  ?>
				              </div>
				            </div>
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'survey'?'in active':'')?>" id="tab6">
				              <div class="row">
				              <?php
				                      
				              if ($list_survey_view) {
				              	//Get the list view for survey
				              	echo $adminAPI->buildSurveyQuestionsAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_survey_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_survey' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          
						          <div id="error_container_6"></div>
						          <div class="form-group col-md-4">
						          	  <label for="survey_questions">How many questions would you like to add?</label>
						              <input type='number' name='survey_questions' class="form-control input-lg" id='survey_questions' min='0' max="20" value='3' placeholder="">
						              <button type="button" class='btn btn-custom' onClick="beacon_options.populate()">Generate Questions</button>
						          </div>
						          <div id="survey_questions_generate" class="form-group col-md-6"></div>
						          <div class="form-group col-md-12">
						              <button name="submit_survey" type='submit' onClick="return Error_checks.automaticCheck('new_survey');" class="btn btn-custom btn-lg">Add Survey</button>
									<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>												          
						          </div>
						        </form>
						        <?php  		
				                      	}
				                      
				                  ?>
				              </div>
				            </div>        
				          
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'sponsor'?'in active':'')?>" id="tab7">
				              <div class="row">
				              <?php
				                      
				              if ($list_sponsor_view) {
				              	//Get the list view for sponsor
				              	echo $adminAPI->buildSponsorsAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_sponsor_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_sponsor' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          
						          <div id="error_container_7"></div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_name">Sponsor Name</label>
						              <input type='text' name='sponsor_name' class="form-control input-lg" id='sponsor_name' value='' placeholder="Sponsor Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_profile">Sponsor Profile</label>
						              <textarea name='sponsor_profile' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='sponsor_profile' value='' placeholder="Sponsor Profile"></textarea>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_level">Level</label>
						              <input type='text' name='sponsor_level' class="form-control input-lg" id='sponsor_level' value='' placeholder="Level">
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="sponsor_website">Website</label>
						              <input type='text' name='sponsor_website' class="form-control input-lg" id='sponsor_website' value='' placeholder="Website">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_logo">Logo</label>
						              <input type='file' name='sponsor_logo' class="file logo_ex form-control input-lg" id='sponsor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_sponsor" type='submit' onClick="return Error_checks.automaticCheck('new_sponsor');" class="btn btn-custom btn-lg ">Add Sponsor</button>
										<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>						          
						          </div>
						        </form>
						        <?php  		
				                      	}			                       
				                  ?>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'content'?'in active':'')?>" id="tab8">
				              <div class="row">
				              <?php
				                      
				              if ($list_content_view) {
				              	//Get the list view for content
				              	echo $adminAPI->buildContentAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_content_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_content' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_8"></div>
						          <div class="form-group col-md-12">
						          	  <label for="content_title">Content Title</label>
						              <input type='text' name='content_title' class="form-control input-lg" id='content_title' value='' placeholder="Content Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="content_description">Content Description</label>
						              <input type='text' name='content_description' class="form-control input-lg" id='content_description' value='' placeholder="Content Description">
						          </div>
						          <div class="form-group col-md-6">
						          	  <label for="content_pdf">PDF</label>
						              <input type='file' name='content_pdf' class="file logo_ex form-control input-lg" id='content_pdf'>
						          </div>
						      	  <div class="form-group col-md-6">
						          	  <label for="content_url">URL</label>
						              <input type='text' name='content_url' class="form-control input-lg" id='content_url' value='' placeholder="URL">
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_content" type='submit' onClick="return Error_checks.automaticCheck('new_content');" class="btn btn-custom btn-lg">Add Content</button>
						              <a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						          
						        </form>
						         <?php  		
				                      	}				           
				                  ?>
				              </div>
				            </div>
				          </div>
				          
			          </div>
					</div>
				</div>
			</div>
 </div> 		
	</div>
</div>
<div id="deleteModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Wait!</h4>
      </div>
      <div class="modal-body">
        <div role="alert" class="alert alert-danger">
          <h4><i class="alert-ico fa fa-fw fa-ban"></i><strong>Confirm</strong></h4>Are you sure?
        </div>
      </div>
      <div class="modal-footer">
      	<button id="delete_node" type="button" data-dismiss="modal" class="btn btn-danger">Yes</button>
        <button type="button" data-dismiss="modal" class="btn btn-default">No</button>
      </div>
    </div>
  </div>
</div>  
  <div class="modal fade" id="deleteInfoModal">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Warning!</h4>
        </div>
        <div class="modal-body">
          <div role="alert" class="alert alert-danger" id="modalBodyAlertMsg">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
||||||| .r434
<?php
$cancel = 0;

//foreach ($_GET as $name => $value) {
    //echo $name . ' : ' . $value . '<br />';
//}

//echo var_dump($_SERVER['REQUEST_URI']);

function checkIfSubmitClicked() {
	if (isset($_POST['submit_event']) ||
	    isset($_POST['submit_speaker']) ||
	    isset($_POST['submit_sponsor']) ||
	    isset($_POST['submit_session']) ||
	    isset($_POST['submit_exhibitor']) ||
	    isset($_POST['submit_beacon']) ||
	    isset($_POST['submit_notification']) ||
	    isset($_POST['submit_content']) ||
	    isset($_POST['submit_survey'])) {
	        return true;	
	    }
	    return false;
}

$_SESSION['curURI'] = $_SERVER['REQUEST_URI'];	

if (isset($_SESSION['prevURI'])) {
	if ($_SESSION['prevURI'] != $_SESSION['curURI']) {
	    if ($_SESSION['userAction'] == 'add' && !isset($_SESSION['newTabObjId'])) {
	    	/*
	    	 * it is cancel action by user
	    	 */
	    	if (!checkIfSubmitClicked()) {
	    		$cancel = 1;
	    		$_SESSION['userAction'] = 'none';
	    	}
	    }	
	}
	if ($_SESSION['userAction'] == 'edit' && !isset($_SESSION['newTabObjId'])) {
		/*
		 * cancel action from edit screen
		 */
		if (!checkIfSubmitClicked()) {
			$cancel = 1;
			$prevURI = $_SESSION['prevURI'];
			$subURIArray = explode("/", $prevURI);
			$tabArray = explode("-", $subURIArray[5]);
			$_SESSION['active_tab'] = $tabArray[1];
			$_SESSION['userAction'] = 'none';
		}
		
	}
} 
 
$event_obj = $adminAPI->getEventById($_SESSION['newEventId']);

$submit_details = 0;
if(isset($_POST['submit_event'])) {
	//echo var_dump($_SESSION['userAction']);
	//echo var_dump($_SESSION['newEventId']);
	if (($_SESSION['userAction'] == 'add' && $_SESSION['newEventId'] == null) ||
	    ($_SESSION['userAction'] == 'edit' && $_SESSION['updateObjId'] != null)) {
		$new_event_obj = $adminAPI->submitNewEvent(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['userID'], $_POST['event_title'],$_POST['event_timezone'],$_POST['event_code'],$_FILES['event_logo'], $_POST['event_location'], $_POST['event_address1'], $_POST['event_address2'], $_POST['event_city'], $_POST['event_state'], $_POST['event_zip'], $_POST['event_country'], $_POST['event_start'], $_POST['event_end'],  $_POST['event_hashtags']);
		//echo var_dump($new_event_obj->getObjectId());
		$_SESSION['newEventCode'] = $new_event_obj->get("eventCode");
		$_SESSION['newEventId'] = $new_event_obj->getObjectId();
		$adminAPI->setEventId($_SESSION['newEventId']);
		$_SESSION['active_tab'] = 'event';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	}
} else if(isset($_POST['submit_speaker'])) {
		$adminAPI->submitNewSpeaker(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL), $_SESSION['newEventId'], $_POST['speaker_name'], $_POST['speaker_company'], $_POST['speaker_title'], $_POST['speaker_bio'], $_POST['speaker_li_profile'], $_POST['speaker_tw_profile'], $_FILES['speaker_image']);
		$_POST['submit_speaker'] = '';
		unset($_POST['submit_speaker']);
		$_SESSION['active_tab'] = 'speaker';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	}else if(isset($_POST['submit_sponsor'])) {
		$adminAPI->submitNewSponsor(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['sponsor_name'], $_POST['sponsor_profile'], $_POST['sponsor_level'],$_POST['sponsor_website'], $_FILES['sponsor_logo']);
		$_SESSION['active_tab'] = 'sponsor';
		$_POST['submit_sponsor'] = '';
		unset($_POST['submit_sponsor']);
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_session'])) {
		$adminAPI->submitNewSession(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL), $_SESSION['newEventId'], $_POST['session_title'], $_POST['session_description'], $_POST['session_start'], $_POST['session_end'], $_POST['session_speaker']);
	     
			
		$_POST['submit_session'] = '';
		unset($_POST['submit_session']);
		$_SESSION['active_tab'] = 'session';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_exhibitor'])) {
		$adminAPI->submitNewExhibitor(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['exhibitor_name'], $_POST['exhibitor_summary'], $_POST['exhibitor_booth'],$_POST['exhibitor_website'], $_FILES['exhibitor_logo']);
		$_SESSION['active_tab'] = 'exhibitor';
		unset($_POST['submit_exhibitor']);
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_beacon'])) {
		//$beacon_opts = $_POST['beacon_options'] ? $_POST['beacon_options'] : NULL;
		$beacon_type_val = ($_POST['beacon_type'] == 'Exhibitor') ? $_POST['beacon_exhibitor'] : $_POST['beacon_session'];  
		$adminAPI->submitNewBeacon(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['beacon_name'], $_POST['beacon_type'], $beacon_type_val);
		$_SESSION['active_tab'] = 'beacon';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_notification'])) {
		$adminAPI->submitNewNotification(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['notification_title'], $_POST['notification_message'], $_FILES['notification_pdf'], $_POST['notification_trigger'], $_POST['notification_url'], $_POST['notification_beacon']);
		$_SESSION['active_tab'] = 'notification';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	}else if(isset($_POST['submit_content'])) {
		$adminAPI->submitNewContent(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['content_title'], $_POST['content_description'], $_FILES['content_pdf'], $_POST['content_url']);
		$_SESSION['active_tab'] = 'content';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	 } else if(isset($_POST['submit_survey'])) {
		$questions_array = array();
		for($i = 1; $i <= $_POST['survey_questions']; $i++) {
			array_push($questions_array, $_POST['survey_question'.$i.'_add']);
		}
		if ($_SESSION['updateObjId']) {
		    $adminAPI->submitNewSurvey($_SESSION['updateObjId'],$_SESSION['newEventId'], $_POST['survey_question']);
		    $_SESSION['updateObjId'] = null;	
		} else {
		    $adminAPI->submitNewSurvey(NULL,$_SESSION['newEventId'], $questions_array);	
		}
		//$adminAPI->submitNewSurvey(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $questions_array);
		$_SESSION['active_tab'] = 'survey';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
	}
	$event_no_content = 0;
	$speaker_rec = $session_rec = $exhibitor_rec = $beacon_rec = $content_rec = $not_rec = $survey_rec = $sponsor_rec = 0;
	
	$speaker_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Speaker');
	$session_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Session');
	$exhibitor_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Exhibitor');
	$beacon_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Beacon');
	$content_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Content');
	$not_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Notification');
	$survey_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Survey');
	$sponsor_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Sponsor');
	
	if (!$speaker_rec &&  
	     !$session_rec &&
	     !$exhibitor_rec &&
	     !$beacon_rec &&
	     !$content_rec &&
	     !$not_rec &&
	     !$survey_rec &&
	     !$sponsor_rec) {
	     	$event_no_content = 1;
	     }
	 
	 
	 if (isset($_POST['add_event_details'])) {
	     $event_no_content = 0;
     }
     
     $display_event_form = $display_speaker_form = $display_session_form = $display_exh_form = $display_beacon_form = 0;
     $display_not_form = $display_survey_form = $display_sponsor_form = $display_content_form = 0;
     if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'event')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	//$display_event_form = 1;
     
     }else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'speaker')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_speaker_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'session')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_session_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'exhibitor')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_exh_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'beacon')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_beacon_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'notification')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_not_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'survey')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_survey_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'sponsor')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_sponsor_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'content')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_content_form = 1;
     }
     
     $list_event_view = $list_speaker_view = $list_session_view = $list_exh_view = $list_beacon_view = 0;
     $list_not_view = $list_survey_view = $list_sponsor_view = $list_content_view = 0;
     
     if ($event_no_content == 0) {
     	 //if ($event_rec) {
           	//$display_event_form = 1;
         //}
        
         if ($speaker_rec) {
         	$list_speaker_view = 1;
         } else {
         	$display_speaker_form = 1;
         }
         if ($session_rec) {
         	$list_session_view = 1;
         } else {
         	$display_session_form = 1;
         }
         if ($exhibitor_rec) {
         	$list_exh_view = 1;
         } else {
         	$display_exh_form = 1;
         }
         if ($beacon_rec) {
         	$list_beacon_view = 1;
         } else {
         	$display_beacon_form = 1;
         }
         if ($not_rec) {
         	$list_not_view = 1;
         } else {
         	$display_not_form = 1;
         } 
         if ($survey_rec) {
         	$list_survey_view = 1;
         } else {
         	$display_survey_form = 1;
         } 
         if ($sponsor_rec) {
         	$list_sponsor_view = 1;
         } else {
         	$display_sponsor_form = 1;
         } 
         if ($content_rec) {
         	$list_content_view = 1;
         } else {
         	$display_content_form = 1;
         } 	
     }
     
     //No need to have list view, when displaying form
     if ($display_event_form) {
     	$list_event_view = 0;
     }
     if ($display_speaker_form) {
     	$list_speaker_view = 0;
     }
     if ($display_session_form) {
     	$list_session_view = 0;
     }
     if ($display_exh_form) {
     	$list_exh_view = 0;
     }
     if ($display_beacon_form) {
     	$list_beacon_view = 0;
     }
     if ($display_not_form) {
     	$list_not_view = 0;
     }
     if ($display_sponsor_form) {
     	$list_sponsor_view = 0;
     }
     if ($display_survey_form) {
     	$list_survey_view = 0;
     }
     if ($display_content_form)	 {
     	$list_content_view = 0;
     }
     
     $_SESSION['prevURI'] = $_SESSION['curURI'];
?>
<style type = "text/css">
#date_pick{ position: relative;
				z-index: 1000; 
}

 .btn-custom {
    color: #fff;
    background-color: #000bcd4;
  
}
div.container-fluid{
width:100%;																;
}

.button.nohover:hover	{
	 color: #fff;
    background-color: #000bcd4;
    cursor:default !important;
   }
   
   .editlistdashboard {
     border-top:5px solid transparent;
     height:100%;
     width:100%;
     white-space:nowrap;
     font-size:0
     }
</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
<script src = "https://code.jquery.com/jquery-1.12.4.js"></script>
<script src = "https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src = "https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>

 <div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
	 	<div class="row">  
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="btn-info btn-block btn-xs" 	 style="font-color:white;">
						<h3 class= ><i class="fa fa-edit"></i><em > Edit Event - <?php  
						
						$current_event = $adminAPI->getEventInfo($_SESSION['newEventId']);
				       
				       if ($current_event && $current_event->get('name')) {
					      echo $current_event->get('name');
				       }
						
						?></em></h3>
					</div>
					<div class="panel-body">
						<div class="submissions">
				          <ul class="nav nav-tabs" role="tablist">
				          	<li class="<?php echo (($_SESSION['active_tab'] == 'event' || $_SESSION['active_tab'] == '')?'active':'')?>"><a class="center tab_color" href="#tab0" role="tab" data-toggle="tab">
				              <span class="fa fa-info-circle"></span><br>Event Info</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'speaker'?'active':'')?>"><a class="center tab_color" href="#tab1" role="presentation" data-toggle="tab">
				              <span class="fa fa-users"></span><br>Speakers</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'session'?'active':'')?>"><a class="center tab_color" href="#tab2" role="presentation" data-toggle="tab">
				              <span class="fa fa-bars"></span><br>Sessions</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'beacon'?'active':'')?>"><a class="center tab_color" href="#tab4" role="presentation" data-toggle="tab">
				              <span class="fa fa-bluetooth"></span><br>Beacons</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'notification'?'active':'')?>"><a class="center tab_color" href="#tab5" role="presentation" data-toggle="tab">
				              <span class="fa fa-clock-o"></span><br>Triggered Content</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'survey'?'active':'')?>"><a class="center tab_color" href="#tab6" role="presentation" data-toggle="tab">
				              <span class="fa fa-question-circle"></span><br>Survey Questions</a></li>
							<li class="<?php echo ($_SESSION['active_tab'] == 'exhibitor'?'active':'')?>"><a class="center tab_color" href="#tab3" role="presentation" data-toggle="tab">
				              <span class="fa fa-id-card"></span><br>Exhibitors</a></li>
							<li class="<?php echo ($_SESSION['active_tab'] == 'sponsor'?'active':'')?>"><a class="center tab_color" href="#tab7" role="presentation" data-toggle="tab">
				              <span class="fa fa-trophy"></span><br>Sponsors</a></li>			          
				            <li class="<?php echo ($_SESSION['active_tab'] == 'content'?'active':'')?>"><a class="center tab_color" href="#tab8" role="presentation" data-toggle="tab">
				              <span class="fa fa-files-o"></span><br>Content</a></li>
				              <?php
				              
				              $eventCode = '';
				              if ($event_obj) {
				              	$eventCode = $event_obj->get("eventCode");
				              } else {
				              	if (count($_GET) == 3 && isset($_GET['action'])) {
				              		$eventCode = $_GET['action'];
				              	}
				              }
				             $add_page_url = "/Organizer/admin/edit/".$eventCode;
				              
				              if ($event_no_content == 1) {
				              	
				           ?>
				           
				             
						     <li role="presentation" style="float:right;"><form name='add_button_form' action=<?=$add_page_url ?> method='POST'>
						         <input type="hidden" id="actab" name="activeTab" value=<?php echo ($submit_details == 1)?'':(($cancel == 1)?'':$_SESSION['active_tab'])?>>
						         <button name="add_event_details" id = "addButton"  href="javascript:;" type='submit' onClick="return Error_checks.getActiveTab();" class="btn btn-custom btn-sm btn-block">Add</button>
						      </form></li>
						   
				           <?php
				              } 
				              
				              if ($event_no_content == 0) {
				              	
				              	if (($list_speaker_view == 1 && $display_speaker_form == 0) ||
				              	    ($list_session_view == 1 && $display_speaker_form == 0) ||
				              	    ($list_exh_view == 1 && $display_exh_form == 0) ||
				              	    ($list_beacon_view == 1 && $display_beacon_form == 0) ||
				              	    ($list_not_view == 1 && $display_not_form ==0) ||
				              	    ($list_survey_view == 1 && $display_survey_form == 0) ||
				              	    ($list_sponsor_view == 1 && $display_sponsor_form == 0) || 
				              	    ($list_content_view == 1 && $display_content_form == 0)) {
				              	    	
				            ?>
				            <li role="presentation" style="float:right;"><form name='add_button_form' action=<?=$add_page_url ?> method='POST'>
						         <input type="hidden" id="actab" name="activeTab" value=<?php echo ($submit_details == 1)?'':(($cancel == 1)?'':$_SESSION['active_tab'])?>>
						         <button name="add_event_details" id = "addButton"  href="javascript:;" type='submit' onClick="return Error_checks.getActiveTab();" class="btn btn-sm btn-custom btn-block">Add</button>
						      </form></li>
				            <?php  		
				              	}
				              }
				          ?>
				          </ul>
				          
				          <div class="tab-content">
				          
				            <div role="tabpanel" class="tab-pane fade <?php echo (($_SESSION['active_tab'] == '' || $_SESSION['active_tab'] == 'event')?'in active':'')?>" id="tab0">
				              <div class="row">
				                  <?php echo $adminAPI->buildEventEditDisplay($_SESSION['newEventId']);?>   				              				             
				           	  </div>
				           </div>
				            
				            <div role="tabpanel" class="tab-pane fade <?php echo (($_SESSION['active_tab'] == 'speaker')?'in active':'')?>" id="tab1">
				          	              <div class="row">
				                  <?php 
				                  if ($list_speaker_view) {
				                  	//Get the list view for speaker
				                  	echo $adminAPI->buildSpeakersAtEvent($_SESSION['newEventId'], ($display_form==1)?false:true);
				                  	//include_once 'pages/modules/admin/edit_display_list.php';
				                  	 
				                  } ?>
				                  
				               
				                  <?php 
				                  if ($display_speaker_form) {
				                  	  $_SESSION['userAction'] = 'add';
				                  	  unset($_SESSION['newTabObjId']);
				                   ?>
				                   <form id='add_speaker' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_1"></div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_name">Name</label>
						              <input type='text' name='speaker_name' class="form-control input-lg" id='speaker_name' value='' placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_company">Company</label>
						              <input type='text' name='speaker_company' class="form-control input-lg" id='speaker_company' value='' placeholder="Company">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_title">Title</label>
						              <input type='text' name='speaker_title' class="form-control input-lg" id='speaker_title' value='' placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_bio">Bio/Summary</label>
						          	 <textarea name='speaker_bio'  rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='speaker_bio' placeholder=" Bio"></textarea>	
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_li_profile">LinkedIn Profile</label>
						              <input type='text' name='speaker_li_profile' class="form-control input-lg" id='speaker_li_profile' value='' placeholder="https://www.linkedin.com/in/ExampleName/">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_tw_profile">Twitter Profile</label>
						              <input type='text' name='speaker_tw_profile' class="form-control input-lg" id='speaker_tw_profile' value='' placeholder="@ExampleName">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_image">Image</label>
						              <input type='file' name='speaker_image' class="file logo_ex form-control input-lg" id='speaker_image' value='' >
						          </div>
						          <div class="form-group col-md-6">
						              <button name="submit_speaker" id = "submitButton"  href="javascript:;" type='submit' onClick="return Error_checks.automaticCheck('new_speaker');" class="btn btn-custom btn-lg">Add Speaker</button>
						              <a id="cancelButton" onClick="" href=<?=$add_page_url ?> style= "a:active {font-color: red;font-style:underline}">Cancel</a>
						          </div>
						          
						            </form>
				                    <?php  		
				                      	}
				                  ?>
				                
				                
						            </div>
				                   </div>
				         
				           	 	            
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'session'?'in active':'')?>" id="tab2">
				              <div class="row">
				                  <?php
				                      
				                  if ($list_session_view) {
				                  	//Get the list view for speaker
				                  	echo $adminAPI->buildSessionsAtEvent($_SESSION['newEventId']);
				                  }
				                  if ($display_session_form) {
				                  	  $_SESSION['userAction'] = 'add';
				                  	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_session' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_2"></div>
						          <div class="form-group col-md-12">
						          	  <label for="session_title">Title</label>
						              <input type='text' name='session_title' class="form-control input-lg" id='session_title' value='' placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_description">Description</label>
						              <textarea name='session_description' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='session_description' value='' placeholder="Description"></textarea>
						          </div>
						          <div class="form-group col-md-3">
						          	  <label for="session_start">Start Time</label>
						              <input type='text' name='session_start' class="date_pick form-control input-md" id='session_start' value='' placeholder="Start Time">
						          </div>
						          <div class="form-group col-md-3">
						          	  <label for="session_end">End Time</label>
						              <input type='text' name='session_end' class="date_pick form-control input-md"  style = ".date_pick { z-index: 1000; }"  id='session_end' value='' placeholder="End Time">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_speaker">Speakers</label>
						              <select data-live-search='true' style="background-color: #5e6870;" data-style="input-lg btn-lg" name='session_speaker[]' class=" form-control input-lg selectpicker" id='session_speaker' value='' multiple >
						              	<?= $adminAPI->buildSpeakerSelection(); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_session" type='submit' onClick="return Error_checks.automaticCheck('new_session');" class="btn btn-custom btn-lg">Add Session</button>
						          		<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						        </form>
						        <?php  		
				                      	} 
				                  ?>
				              </div>
				            </div>
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'exhibitor'?'in active':'')?>" id="tab3">
				              <div class="row">
				                  <?php
				                      
				                  if ($list_exh_view) {
				                  	//Get the list view for exhibitor
				                  	echo $adminAPI->buildExhibitorAtEvent($_SESSION['newEventId']);
				                  }
				                  if ($display_exh_form) {
				                  	  $_SESSION['userAction'] = 'add';
				                  	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_exhibitor' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_3"></div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_name">Name</label>
						              <input type='text' name='exhibitor_name' class="form-control input-lg" id='exhibitor_name' value='' placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_summary">Company Profile/Summary</label>
						              <textarea name='exhibitor_summary' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='exhibitor_summary' value='' placeholder="Summary"></textarea>
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_booth">Booth #</label>
						              <input type='text' name='exhibitor_booth' class="form-control input-lg" id='exhibitor_booth' value='' placeholder="Booth #">
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_website">Website</label>
						              <input type='text' name='exhibitor_website' class="form-control input-lg" id='exhibitor_website' value='' placeholder="Website">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_logo">Logo</label>
						              <input type='file' name='exhibitor_logo' class="file logo_ex form-control input-lg" id='exhibitor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_exhibitor" type='submit' onClick="return Error_checks.automaticCheck('new_exhibitor');" class="btn btn-custom btn-lg ">Add Exhibitor</button>
						             <a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						        </form>
						        <?php  		
				                      	}
				                  ?>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'beacon'?'in active':'')?>" id="tab4">
				              <div class="row">
				              <?php
				                     
				              if ($list_beacon_view) {
				              	//Get the list view for beacon
				              	echo $adminAPI->buildBeaconAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_beacon_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_beacon' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_4"></div>
						          <div class="form-group col-md-12">
						          	  <label for="beacon_name">Name</label>
						              <input type='text' name='beacon_name' class="form-control input-lg" id='beacon_name' value='' placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="beacon_type">Beacon Type</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" name='beacon_type' class="form-control input-lg selectpicker" id='beacon_type' title="Choose a type">
						      			<option value="Organizer">Organizer</option>
						              	<option value="Exhibitor">Exhibitor</option>
						              	<option value="Session">Session</option>
						              </select>
						          </div>
						          <div class="beacon_container">
						          <div class="Session">
						          <div class="form-group col-md-12">
						          	  <label for="beacon_session">Session</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" name='beacon_session[]' class="form-control input-lg selectpicker" id='beacon_session' value='' multiple title="Choose a Session">
						              	<?= $adminAPI->buildSessionSelection(); ?>
						              </select>
						          </div>
						          </div>
						          <div class="Exhibitor">
						          <div class="form-group col-md-12">
						          	  <label for="beacon_exhibitor">Exhibitor</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" name='beacon_exhibitor[]' class="form-control input-lg selectpicker" id='beacon_exhibitor' value='' title="Choose a Exhibitor">
						              	<?= $adminAPI->buildExhibitorSelection(); ?>
						              </select>
						          </div>
						          </div>
						          </div>
						          <div id="beacon_options_container" class="form-group col-md-12"></div>
						          <div class="form-group col-md-12">
						              <button name="submit_beacon" type='submit' onClick="return Error_checks.automaticCheck('new_beacon');" class="btn btn-custom btn-lg">Add Beacon</button>
									<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>							          
						          </div>
						        </form>
						        <?php  		
				                      	}			                    
				                  ?>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'notification'?'in active':'')?>" id="tab5">
				              <div class="row">
				              <?php
				                     
				              if ($list_not_view) {
				              	//Get the list view for speaker
				              	echo $adminAPI->buildNotificationAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_not_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_notification' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_5"></div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_title">Title</label>
						              <input type='text' name='notification_title' class="form-control input-lg" id='notification_title' value='' placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_message">Message</label>
						              <input type='text' name='notification_message' class="form-control input-lg" id='notification_message' value='' placeholder="Message">
						          </div>
						          <div class="form-group col-md-6">
						          	  <label for="notification_pdf">PDF</label>
						              <input type='file' name='notification_pdf' class="file logo_ex form-control input-lg" id='notification_pdf'>
						          </div>
						      	  <div class="form-group col-md-6">
						          	  <label for="notification_url">URL</label>
						              <input type='text' name='notification_url' class="form-control input-lg" id='notification_url' value='' placeholder="URL">
						          </div>
						          <div class="form-group col-md-12">
						          	<label for="notification_trigger">Trigger After(xx minutes)</label>
						          	<select data-live-search='true' data-style="input-lg btn-lg" class="form-control input-lg selectpicker" name="notification_trigger" id="notification_trigger">
						          		<option selected disabled>Choose a Time</option>
						          		<?= $adminAPI->buildNotificationSelect($_SESSION['newEventId']); ?>
						          	</select>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_beacon">Beacon</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" name='notification_beacon' class="form-control input-lg selectpicker" id='notification_beacon'>
						              	<option selected disabled>Choose a Beacon</option>
						              	<?= $adminAPI->buildBeaconDropDown($_SESSION['newEventId']); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_notification" type='submit' onClick="return Error_checks.automaticCheck('new_notification');" class="btn btn-custom btn-lg">Add Notification</button>
										<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>						          
						          </div>
						        </form>
						        <?php  		
				                      	}				                      
				                  ?>
				              </div>
				            </div>
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'survey'?'in active':'')?>" id="tab6">
				              <div class="row">
				              <?php
				                      
				              if ($list_survey_view) {
				              	//Get the list view for survey
				              	echo $adminAPI->buildSurveyQuestionsAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_survey_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_survey' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          
						          <div id="error_container_6"></div>
						          <div class="form-group col-md-4">
						          	  <label for="survey_questions">How many questions would you like to add?</label>
						              <input type='number' name='survey_questions' class="form-control input-lg" id='survey_questions' min='0' max="20" value='3' placeholder="">
						              <button type="button" class='btn btn-custom' onClick="beacon_options.populate()">Generate Questions</button>
						          </div>
						          <div id="survey_questions_generate" class="form-group col-md-6"></div>
						          <div class="form-group col-md-12">
						              <button name="submit_survey" type='submit' onClick="return Error_checks.automaticCheck('new_survey');" class="btn btn-custom btn-lg">Add Survey</button>
									<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>												          
						          </div>
						        </form>
						        <?php  		
				                      	}
				                      
				                  ?>
				              </div>
				            </div>        
				          
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'sponsor'?'in active':'')?>" id="tab7">
				              <div class="row">
				              <?php
				                      
				              if ($list_sponsor_view) {
				              	//Get the list view for sponsor
				              	echo $adminAPI->buildSponsorsAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_sponsor_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_sponsor' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          
						          <div id="error_container_7"></div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_name">Sponsor Name</label>
						              <input type='text' name='sponsor_name' class="form-control input-lg" id='sponsor_name' value='' placeholder="Sponsor Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_profile">Sponsor Profile</label>
						              <textarea name='sponsor_profile' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='sponsor_profile' value='' placeholder="Sponsor Profile"></textarea>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_level">Level</label>
						              <input type='text' name='sponsor_level' class="form-control input-lg" id='sponsor_level' value='' placeholder="Level">
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="sponsor_website">Website</label>
						              <input type='text' name='sponsor_website' class="form-control input-lg" id='sponsor_website' value='' placeholder="Website">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_logo">Logo</label>
						              <input type='file' name='sponsor_logo' class="file logo_ex form-control input-lg" id='sponsor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_sponsor" type='submit' onClick="return Error_checks.automaticCheck('new_sponsor');" class="btn btn-custom btn-lg ">Add Sponsor</button>
										<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>						          
						          </div>
						        </form>
						        <?php  		
				                      	}			                       
				                  ?>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'content'?'in active':'')?>" id="tab8">
				              <div class="row">
				              <?php
				                      
				              if ($list_content_view) {
				              	//Get the list view for content
				              	echo $adminAPI->buildContentAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_content_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_content' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_8"></div>
						          <div class="form-group col-md-12">
						          	  <label for="content_title">Content Title</label>
						              <input type='text' name='content_title' class="form-control input-lg" id='content_title' value='' placeholder="Content Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="content_description">Content Description</label>
						              <input type='text' name='content_description' class="form-control input-lg" id='content_description' value='' placeholder="Content Description">
						          </div>
						          <div class="form-group col-md-6">
						          	  <label for="content_pdf">PDF</label>
						              <input type='file' name='content_pdf' class="file logo_ex form-control input-lg" id='content_pdf'>
						          </div>
						      	  <div class="form-group col-md-6">
						          	  <label for="content_url">URL</label>
						              <input type='text' name='content_url' class="form-control input-lg" id='content_url' value='' placeholder="URL">
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_content" type='submit' onClick="return Error_checks.automaticCheck('new_content');" class="btn btn-custom btn-lg">Add Content</button>
						              <a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						          
						        </form>
						         <?php  		
				                      	}				           
				                  ?>
				              </div>
				            </div>
				          </div>
				          
			          </div>
					</div>
				</div>
			</div>
 </div> 		
	</div>
</div>
<div id="deleteModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Wait!</h4>
      </div>
      <div class="modal-body">
        <div role="alert" class="alert alert-danger">
          <h4><i class="alert-ico fa fa-fw fa-ban"></i><strong>Confirm</strong></h4>Are you sure?
        </div>
      </div>
      <div class="modal-footer">
      	<button id="delete_node" type="button" data-dismiss="modal" class="btn btn-danger">Yes</button>
        <button type="button" data-dismiss="modal" class="btn btn-default">No</button>
      </div>
    </div>
  </div>
</div>  
  <div class="modal fade" id="deleteInfoModal">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Warning!</h4>
        </div>
        <div class="modal-body">
          <div role="alert" class="alert alert-danger" id="modalBodyAlertMsg">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
=======
<?php
$cancel = 0;

//foreach ($_GET as $name => $value) {
    //echo $name . ' : ' . $value . '<br />';
//}

//echo var_dump($_SERVER['REQUEST_URI']);

function checkIfSubmitClicked() {
	if (isset($_POST['submit_event']) ||
	    isset($_POST['submit_speaker']) ||
	    isset($_POST['submit_sponsor']) ||
	    isset($_POST['submit_session']) ||
	    isset($_POST['submit_exhibitor']) ||
	    isset($_POST['submit_beacon']) ||
	    isset($_POST['submit_notification']) ||
	    isset($_POST['submit_content']) ||
	    isset($_POST['submit_survey'])) {
	        return true;	
	    }
	    return false;
}

$_SESSION['curURI'] = $_SERVER['REQUEST_URI'];	

if (isset($_SESSION['prevURI'])) {
	if ($_SESSION['prevURI'] != $_SESSION['curURI']) {
	    if ($_SESSION['userAction'] == 'add' && !isset($_SESSION['newTabObjId'])) {
	    	/*
	    	 * it is cancel action by user
	    	 */
	    	if (!checkIfSubmitClicked()) {
	    		$cancel = 1;
	    		$_SESSION['userAction'] = 'none';
	    	}
	    }	
	}
	if ($_SESSION['userAction'] == 'edit' && !isset($_SESSION['newTabObjId'])) {
		/*
		 * cancel action from edit screen
		 */
		if (!checkIfSubmitClicked()) {
			$cancel = 1;
			$prevURI = $_SESSION['prevURI'];
			$subURIArray = explode("/", $prevURI);
			$tabArray = explode("-", $subURIArray[5]);
			$_SESSION['active_tab'] = $tabArray[1];
			$_SESSION['userAction'] = 'none';
		}
		
	}
} 
 
$event_obj = $adminAPI->getEventById($_SESSION['newEventId']);

$submit_details = 0;
if(isset($_POST['submit_event'])) {
	//echo var_dump($_SESSION['userAction']);
	//echo var_dump($_SESSION['newEventId']);
	if (($_SESSION['userAction'] == 'add' && $_SESSION['newEventId'] == null) ||
	    ($_SESSION['userAction'] == 'edit' && $_SESSION['updateObjId'] != null)) {
		$new_event_obj = $adminAPI->submitNewEvent(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['userID'], $_POST['event_title'],$_POST['event_timezone'],$_POST['event_code'],$_FILES['event_logo'], $_POST['event_location'], $_POST['event_address1'], $_POST['event_address2'], $_POST['event_city'], $_POST['event_state'], $_POST['event_zip'], $_POST['event_country'], $_POST['event_start'], $_POST['event_end'],  $_POST['event_hashtags']);
		//echo var_dump($new_event_obj->getObjectId());
		$_SESSION['newEventCode'] = $new_event_obj->get("eventCode");
		$_SESSION['newEventId'] = $new_event_obj->getObjectId();
		$adminAPI->setEventId($_SESSION['newEventId']);
		$_SESSION['active_tab'] = 'event';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	}
} else if(isset($_POST['submit_speaker'])) {
		$adminAPI->submitNewSpeaker(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL), $_SESSION['newEventId'], $_POST['speaker_name'], $_POST['speaker_company'], $_POST['speaker_title'], $_POST['speaker_bio'], $_POST['speaker_li_profile'], $_POST['speaker_tw_profile'], $_FILES['speaker_image']);
		$_POST['submit_speaker'] = '';
		unset($_POST['submit_speaker']);
		$_SESSION['active_tab'] = 'speaker';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	}else if(isset($_POST['submit_sponsor'])) {
		$adminAPI->submitNewSponsor(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['sponsor_name'], $_POST['sponsor_profile'], $_POST['sponsor_level'],$_POST['sponsor_website'], $_FILES['sponsor_logo']);
		$_SESSION['active_tab'] = 'sponsor';
		$_POST['submit_sponsor'] = '';
		unset($_POST['submit_sponsor']);
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_session'])) {
		$adminAPI->submitNewSession(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL), $_SESSION['newEventId'], $_POST['session_title'], $_POST['session_description'], $_POST['session_start'], $_POST['session_end'], $_POST['session_speaker']);
	     
			
		$_POST['submit_session'] = '';
		unset($_POST['submit_session']);
		$_SESSION['active_tab'] = 'session';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_exhibitor'])) {
		$adminAPI->submitNewExhibitor(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['exhibitor_name'], $_POST['exhibitor_summary'], $_POST['exhibitor_booth'],$_POST['exhibitor_website'], $_FILES['exhibitor_logo']);
		$_SESSION['active_tab'] = 'exhibitor';
		unset($_POST['submit_exhibitor']);
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_beacon'])) {
		//$beacon_opts = $_POST['beacon_options'] ? $_POST['beacon_options'] : NULL;
		$beacon_type_val = ($_POST['beacon_type'] == 'Exhibitor') ? $_POST['beacon_exhibitor'] : $_POST['beacon_session'];  
		$adminAPI->submitNewBeacon(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['beacon_name'], $_POST['beacon_type'], $beacon_type_val);
		$_SESSION['active_tab'] = 'beacon';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	} else if(isset($_POST['submit_notification'])) {
		$adminAPI->submitNewNotification(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['notification_title'], $_POST['notification_message'], $_FILES['notification_pdf'], $_POST['notification_trigger'], $_POST['notification_url'], $_POST['notification_beacon']);
		$_SESSION['active_tab'] = 'notification';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	}else if(isset($_POST['submit_content'])) {
		$adminAPI->submitNewContent(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $_POST['content_title'], $_POST['content_description'], $_FILES['content_pdf'], $_POST['content_url']);
		$_SESSION['active_tab'] = 'content';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
		$_SESSION['updateObjId'] = null;
	 } else if(isset($_POST['submit_survey'])) {
		$questions_array = array();
		for($i = 1; $i <= $_POST['survey_questions']; $i++) {
			array_push($questions_array, $_POST['survey_question'.$i.'_add']);
		}
		if ($_SESSION['updateObjId']) {
		    $adminAPI->submitNewSurvey($_SESSION['updateObjId'],$_SESSION['newEventId'], $_POST['survey_question']);
		    $_SESSION['updateObjId'] = null;	
		} else {
		    $adminAPI->submitNewSurvey(NULL,$_SESSION['newEventId'], $questions_array);	
		}
		//$adminAPI->submitNewSurvey(($_SESSION['updateObjId']?$_SESSION['updateObjId']:NULL),$_SESSION['newEventId'], $questions_array);
		$_SESSION['active_tab'] = 'survey';
		$submit_details = 1;
		$_SESSION['userAction'] = 'none';
	}
	$event_no_content = 0;
	$speaker_rec = $session_rec = $exhibitor_rec = $beacon_rec = $content_rec = $not_rec = $survey_rec = $sponsor_rec = 0;
	
	$speaker_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Speaker');
	$session_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Session');
	$exhibitor_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Exhibitor');
	$beacon_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Beacon');
	$content_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Content');
	$not_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Notification');
	$survey_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Survey');
	$sponsor_rec = $adminAPI->hasEventInfo($_SESSION['newEventId'], 'Sponsor');
	
	if (!$speaker_rec &&  
	     !$session_rec &&
	     !$exhibitor_rec &&
	     !$beacon_rec &&
	     !$content_rec &&
	     !$not_rec &&
	     !$survey_rec &&
	     !$sponsor_rec) {
	     	$event_no_content = 1;
	     }
	 
	 
	 if (isset($_POST['add_event_details'])) {
	     $event_no_content = 0;
     }
     
     $display_event_form = $display_speaker_form = $display_session_form = $display_exh_form = $display_beacon_form = 0;
     $display_not_form = $display_survey_form = $display_sponsor_form = $display_content_form = 0;
     if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'event')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	//$display_event_form = 1;
     
     }else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'speaker')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_speaker_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'session')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_session_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'exhibitor')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_exh_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'beacon')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_beacon_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'notification')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_not_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'survey')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_survey_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'sponsor')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_sponsor_form = 1;
     } else if (isset($_POST['add_event_details']) && isset($_POST['activeTab']) && ($_POST['activeTab'] == 'content')) {
     	$_SESSION['active_tab'] = $_POST['activeTab'];
     	$display_content_form = 1;
     }
     
     $list_event_view = $list_speaker_view = $list_session_view = $list_exh_view = $list_beacon_view = 0;
     $list_not_view = $list_survey_view = $list_sponsor_view = $list_content_view = 0;
     
     if ($event_no_content == 0) {
     	 //if ($event_rec) {
           	//$display_event_form = 1;
         //}
        
         if ($speaker_rec) {
         	$list_speaker_view = 1;
         } else {
         	$display_speaker_form = 1;
         }
         if ($session_rec) {
         	$list_session_view = 1;
         } else {
         	$display_session_form = 1;
         }
         if ($exhibitor_rec) {
         	$list_exh_view = 1;
         } else {
         	$display_exh_form = 1;
         }
         if ($beacon_rec) {
         	$list_beacon_view = 1;
         } else {
         	$display_beacon_form = 1;
         }
         if ($not_rec) {
         	$list_not_view = 1;
         } else {
         	$display_not_form = 1;
         } 
         if ($survey_rec) {
         	$list_survey_view = 1;
         } else {
         	$display_survey_form = 1;
         } 
         if ($sponsor_rec) {
         	$list_sponsor_view = 1;
         } else {
         	$display_sponsor_form = 1;
         } 
         if ($content_rec) {
         	$list_content_view = 1;
         } else {
         	$display_content_form = 1;
         } 	
     }
     
     //No need to have list view, when displaying form
     if ($display_event_form) {
     	$list_event_view = 0;
     }
     if ($display_speaker_form) {
     	$list_speaker_view = 0;
     }
     if ($display_session_form) {
     	$list_session_view = 0;
     }
     if ($display_exh_form) {
     	$list_exh_view = 0;
     }
     if ($display_beacon_form) {
     	$list_beacon_view = 0;
     }
     if ($display_not_form) {
     	$list_not_view = 0;
     }
     if ($display_sponsor_form) {
     	$list_sponsor_view = 0;
     }
     if ($display_survey_form) {
     	$list_survey_view = 0;
     }
     if ($display_content_form)	 {
     	$list_content_view = 0;
     }
     
     $_SESSION['prevURI'] = $_SESSION['curURI'];
?>
<style type = "text/css">
#date_pick {
	position: relative;
	z-index: 1000;
}

.btn-custom {
	color: #fff;
	background-color: #000bcd4;
}

div.container-fluid {
	width: 100%;;
}

.button.nohover:hover {
	color: #fff;
	background-color: #000bcd4;
	cursor: default !important;
}

.editlistdashboard {
	border-top: 5px solid transparent;
	height: 100%;
	width: 100%;
	white-space: nowrap;
	font-size: 0
}

.dataTables_scroll {
	overflow: auto;
}

.table>tbody>tr.no-border>td,
.table>tbody>tr.no-border>th {
  border-top: none;
}
</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
<script src = "https://code.jquery.com/jquery-1.12.4.js"></script>
<script src = "https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src = "https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>

 <div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
	 	<div class="row">  
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="btn-info btn-block btn-xs" 	 style="font-color:white;">
						<h4 class= ><i class="fa fa-edit"></i><em > Edit Event - <?php  
						
						$current_event = $adminAPI->getEventInfo($_SESSION['newEventId']);
				       
				       if ($current_event && $current_event->get('name')) {
					      echo $current_event->get('name');
				       }
						
						?></em></h4>
					</div>
					<div class="panel-body">
						
				          <ul class="nav nav-tabs" role="tablist">
				          	<li class="<?php echo (($_SESSION['active_tab'] == 'event' || $_SESSION['active_tab'] == '')?'active':'')?>"><a class="center tab_color" href="#tab0" role="tab" data-toggle="tab">
				              <span class="fa fa-info-circle"></span><br>Event Info</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'speaker'?'active':'')?>"><a class="center tab_color" href="#tab1" role="presentation" data-toggle="tab">
				              <span class="fa fa-users"></span><br>Speakers</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'session'?'active':'')?>"><a class="center tab_color" href="#tab2" role="presentation" data-toggle="tab">
				              <span class="fa fa-bars"></span><br>Sessions</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'beacon'?'active':'')?>"><a class="center tab_color" href="#tab4" role="presentation" data-toggle="tab">
				              <span class="fa fa-bluetooth"></span><br>Beacons</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'notification'?'active':'')?>"><a class="center tab_color" href="#tab5" role="presentation" data-toggle="tab">
				              <span class="fa fa-clock-o"></span><br>Triggered Content</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'survey'?'active':'')?>"><a class="center tab_color" href="#tab6" role="presentation" data-toggle="tab">
				              <span class="fa fa-question-circle"></span><br>Survey Questions</a></li>
							<li class="<?php echo ($_SESSION['active_tab'] == 'exhibitor'?'active':'')?>"><a class="center tab_color" href="#tab3" role="presentation" data-toggle="tab">
				              <span class="fa fa-id-card"></span><br>Exhibitors</a></li>
							<li class="<?php echo ($_SESSION['active_tab'] == 'sponsor'?'active':'')?>"><a class="center tab_color" href="#tab7" role="presentation" data-toggle="tab">
				              <span class="fa fa-trophy"></span><br>Sponsors</a></li>			          
				            <li class="<?php echo ($_SESSION['active_tab'] == 'content'?'active':'')?>"><a class="center tab_color" href="#tab8" role="presentation" data-toggle="tab">
				              <span class="fa fa-files-o"></span><br>Content</a></li>
				              <?php
				              
				              $eventCode = '';
				              if ($event_obj) {
				              	$eventCode = $event_obj->get("eventCode");
				              } else {
				              	if (count($_GET) == 3 && isset($_GET['action'])) {
				              		$eventCode = $_GET['action'];
				              	}
				              }
				             $add_page_url = "/Organizer/admin/edit/".$eventCode;
				              
				              if ($event_no_content == 1) {
				              	
				           ?>
				           
				             
						     <li role="presentation" style="float:right;"><form name='add_button_form' action=<?=$add_page_url ?> method='POST'>
						         <input type="hidden" id="actab" name="activeTab" value=<?php echo ($submit_details == 1)?'':(($cancel == 1)?'':$_SESSION['active_tab'])?>>
						         <button name="add_event_details" id = "addButton"  href="javascript:;" type='submit' onClick="return Error_checks.getActiveTab();" class="btn btn-custom btn-sm btn-block">Add</button>
						      </form></li>
						   
				           <?php
				              } 
				              
				              if ($event_no_content == 0) {
				              	
				              	if (($list_speaker_view == 1 && $display_speaker_form == 0) ||
				              	    ($list_session_view == 1 && $display_speaker_form == 0) ||
				              	    ($list_exh_view == 1 && $display_exh_form == 0) ||
				              	    ($list_beacon_view == 1 && $display_beacon_form == 0) ||
				              	    ($list_not_view == 1 && $display_not_form ==0) ||
				              	    ($list_survey_view == 1 && $display_survey_form == 0) ||
				              	    ($list_sponsor_view == 1 && $display_sponsor_form == 0) || 
				              	    ($list_content_view == 1 && $display_content_form == 0)) {
				              	    	
				            ?>
				            <li role="presentation" style="float:right;"><form name='add_button_form' action=<?=$add_page_url ?> method='POST'>
						         <input type="hidden" id="actab" name="activeTab" value=<?php echo ($submit_details == 1)?'':(($cancel == 1)?'':$_SESSION['active_tab'])?>>
						         <button name="add_event_details" id = "addButton"  href="javascript:;" type='submit' onClick="return Error_checks.getActiveTab();" class="btn btn-sm btn-custom btn-block">Add</button>
						      </form></li>
				            <?php  		
				              	}
				              }
				          ?>
				          </ul>
				          
				          <div class="tab-content">
				          
				            <div role="tabpanel" class="tab-pane fade <?php echo (($_SESSION['active_tab'] == '' || $_SESSION['active_tab'] == 'event')?'in active':'')?>" id="tab0">
				              <div class="row">
				                  <?php echo $adminAPI->buildEventEditDisplay($_SESSION['newEventId']);?>   				              				             
				           	  </div>
				           </div>
				            
				            <div role="tabpanel" class="tab-pane fade <?php echo (($_SESSION['active_tab'] == 'speaker')?'in active':'')?>" id="tab1">
				          	              <div class="row">
				                  <?php 
				                  if ($list_speaker_view) {
				                  	//Get the list view for speaker
				                  	echo $adminAPI->buildSpeakersAtEvent($_SESSION['newEventId'], ($display_form==1)?false:true);
				                  	//include_once 'pages/modules/admin/edit_display_list.php';
				                  	 
				                  } ?>
				                  
				               
				                  <?php 
				                  if ($display_speaker_form) {
				                  	  $_SESSION['userAction'] = 'add';
				                  	  unset($_SESSION['newTabObjId']);
				                   ?>
				                   <form id='add_speaker' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_1"></div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_name">Name</label>
						              <input type='text' name='speaker_name' class="form-control input-lg" id='speaker_name' value='' placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_company">Company</label>
						              <input type='text' name='speaker_company' class="form-control input-lg" id='speaker_company' value='' placeholder="Company">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_title">Title</label>
						              <input type='text' name='speaker_title' class="form-control input-lg" id='speaker_title' value='' placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_bio">Bio/Summary</label>
						          	 <textarea name='speaker_bio'  rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='speaker_bio' placeholder=" Bio"></textarea>	
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_li_profile">LinkedIn Profile</label>
						              <input type='text' name='speaker_li_profile' class="form-control input-lg" id='speaker_li_profile' value='' placeholder="https://www.linkedin.com/in/ExampleName/">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_tw_profile">Twitter Profile</label>
						              <input type='text' name='speaker_tw_profile' class="form-control input-lg" id='speaker_tw_profile' value='' placeholder="@ExampleName">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_image">Image</label>
						              <input type='file' name='speaker_image' class="file logo_ex form-control input-lg" id='speaker_image' value='' >
						          </div>
						          <div class="form-group col-md-6">
						              <button name="submit_speaker" id = "submitButton"  href="javascript:;" type='submit' onClick="return Error_checks.automaticCheck('new_speaker');" class="btn btn-custom btn-lg">Add Speaker</button>
						              <a id="cancelButton" onClick="" href=<?=$add_page_url ?> style= "a:active {font-color: red;font-style:underline}">Cancel</a>
						          </div>
						          
						            </form>
				                    <?php  		
				                      	}
				                  ?>
				                
				                
						            </div>
				                   </div>
				         
				           	 	            
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'session'?'in active':'')?>" id="tab2">
				              <div class="row">
				                  <?php
				                      
				                  if ($list_session_view) {
				                  	//Get the list view for speaker
				                  	echo $adminAPI->buildSessionsAtEvent($_SESSION['newEventId']);
				                  }
				                  if ($display_session_form) {
				                  	  $_SESSION['userAction'] = 'add';
				                  	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_session' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_2"></div>
						          <div class="form-group col-md-12">
						          	  <label for="session_title">Title</label>
						              <input type='text' name='session_title' class="form-control input-lg" id='session_title' value='' placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_description">Description</label>
						              <textarea name='session_description' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='session_description' value='' placeholder="Description"></textarea>
						          </div>
						          <div class="form-group col-md-3">
						          	  <label for="session_start">Start Time</label>
						              <input type='text' name='session_start' class="date_pick form-control input-md" id='session_start' value='' placeholder="Start Time">
						          </div>
						          <div class="form-group col-md-3">
						          	  <label for="session_end">End Time</label>
						              <input type='text' name='session_end' class="date_pick form-control input-md"  style = ".date_pick { z-index: 1000; }"  id='session_end' value='' placeholder="End Time">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_speaker">Speakers</label>
						              <select data-live-search='true' style="background-color: #5e6870;" data-style="input-lg btn-lg" name='session_speaker[]' class=" form-control input-lg selectpicker" id='session_speaker' value='' multiple >
						              	<?= $adminAPI->buildSpeakerSelection(); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_session" type='submit' onClick="return Error_checks.automaticCheck('new_session');" class="btn btn-custom btn-lg">Add Session</button>
						          		<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						        </form>
						        <?php  		
				                      	} 
				                  ?>
				              </div>
				            </div>
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'exhibitor'?'in active':'')?>" id="tab3">
				              <div class="row">
				                  <?php
				                      
				                  if ($list_exh_view) {
				                  	//Get the list view for exhibitor
				                  	echo $adminAPI->buildExhibitorAtEvent($_SESSION['newEventId']);
				                  }
				                  if ($display_exh_form) {
				                  	  $_SESSION['userAction'] = 'add';
				                  	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_exhibitor' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_3"></div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_name">Name</label>
						              <input type='text' name='exhibitor_name' class="form-control input-lg" id='exhibitor_name' value='' placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_summary">Company Profile/Summary</label>
						              <textarea name='exhibitor_summary' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='exhibitor_summary' value='' placeholder="Summary"></textarea>
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_booth">Booth #</label>
						              <input type='text' name='exhibitor_booth' class="form-control input-lg" id='exhibitor_booth' value='' placeholder="Booth #">
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_website">Website</label>
						              <input type='text' name='exhibitor_website' class="form-control input-lg" id='exhibitor_website' value='' placeholder="Website">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_logo">Logo</label>
						              <input type='file' name='exhibitor_logo' class="file logo_ex form-control input-lg" id='exhibitor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_exhibitor" type='submit' onClick="return Error_checks.automaticCheck('new_exhibitor');" class="btn btn-custom btn-lg ">Add Exhibitor</button>
						             <a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						        </form>
						        <?php  		
				                      	}
				                  ?>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'beacon'?'in active':'')?>" id="tab4">
				              <div class="row">
				              <?php
				                     
				              if ($list_beacon_view) {
				              	//Get the list view for beacon
				              	echo $adminAPI->buildBeaconAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_beacon_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_beacon' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_4"></div>
						          <div class="form-group col-md-12">
						          	  <label for="beacon_name">Name</label>
						              <input type='text' name='beacon_name' class="form-control input-lg" id='beacon_name' value='' placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="beacon_type">Beacon Type</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" name='beacon_type' class="form-control input-lg selectpicker" id='beacon_type' title="Choose a type">
						      			<option value="Organizer">Organizer</option>
						              	<option value="Exhibitor">Exhibitor</option>
						              	<option value="Session">Session</option>
						              </select>
						          </div>
						          <div class="beacon_container">
						          <div class="Session">
						          <div class="form-group col-md-12">
						          	  <label for="beacon_session">Session</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" name='beacon_session[]' class="form-control input-lg selectpicker" id='beacon_session' value='' multiple title="Choose a Session">
						              	<?= $adminAPI->buildSessionSelection(); ?>
						              </select>
						          </div>
						          </div>
						          <div class="Exhibitor">
						          <div class="form-group col-md-12">
						          	  <label for="beacon_exhibitor">Exhibitor</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" name='beacon_exhibitor[]' class="form-control input-lg selectpicker" id='beacon_exhibitor' value='' title="Choose a Exhibitor">
						              	<?= $adminAPI->buildExhibitorSelection(); ?>
						              </select>
						          </div>
						          </div>
						          </div>
						          <div id="beacon_options_container" class="form-group col-md-12"></div>
						          <div class="form-group col-md-12">
						              <button name="submit_beacon" type='submit' onClick="return Error_checks.automaticCheck('new_beacon');" class="btn btn-custom btn-lg">Add Beacon</button>
									<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>							          
						          </div>
						        </form>
						        <?php  		
				                      	}			                    
				                  ?>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'notification'?'in active':'')?>" id="tab5">
				              <div class="row">
				              <?php
				                     
				              if ($list_not_view) {
				              	//Get the list view for speaker
				              	echo $adminAPI->buildNotificationAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_not_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_notification' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_5"></div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_title">Title</label>
						              <input type='text' name='notification_title' class="form-control input-lg" id='notification_title' value='' placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_message">Message</label>
						              <input type='text' name='notification_message' class="form-control input-lg" id='notification_message' value='' placeholder="Message">
						          </div>
						          <div class="form-group col-md-6">
						          	  <label for="notification_pdf">PDF</label>
						              <input type='file' name='notification_pdf' class="file logo_ex form-control input-lg" id='notification_pdf'>
						          </div>
						      	  <div class="form-group col-md-6">
						          	  <label for="notification_url">URL</label>
						              <input type='text' name='notification_url' class="form-control input-lg" id='notification_url' value='' placeholder="URL">
						          </div>
						          <div class="form-group col-md-12">
						          	<label for="notification_trigger">Trigger After(xx minutes)</label>
						          	<select data-live-search='true' data-style="input-lg btn-lg" class="form-control input-lg selectpicker" name="notification_trigger" id="notification_trigger">
						          		<option selected disabled>Choose a Time</option>
						          		<?= $adminAPI->buildNotificationSelect($_SESSION['newEventId']); ?>
						          	</select>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_beacon">Beacon</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" name='notification_beacon' class="form-control input-lg selectpicker" id='notification_beacon'>
						              	<option selected disabled>Choose a Beacon</option>
						              	<?= $adminAPI->buildBeaconDropDown($_SESSION['newEventId']); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_notification" type='submit' onClick="return Error_checks.automaticCheck('new_notification');" class="btn btn-custom btn-lg">Add Notification</button>
										<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>						          
						          </div>
						        </form>
						        <?php  		
				                      	}				                      
				                  ?>
				              </div>
				            </div>
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'survey'?'in active':'')?>" id="tab6">
				              <div class="row">
				              <?php
				                      
				              if ($list_survey_view) {
				              	//Get the list view for survey
				              	echo $adminAPI->buildSurveyQuestionsAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_survey_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_survey' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          
						          <div id="error_container_6"></div>
						          <div class="form-group col-md-4">
						          	  <label for="survey_questions">How many questions would you like to add?</label>
						              <input type='number' name='survey_questions' class="form-control input-lg" id='survey_questions' min='0' max="20" value='3' placeholder="">
						              <button type="button" class='btn btn-custom' onClick="beacon_options.populate()">Generate Questions</button>
						          </div>
						          <div id="survey_questions_generate" class="form-group col-md-6"></div>
						          <div class="form-group col-md-12">
						              <button name="submit_survey" type='submit' onClick="return Error_checks.automaticCheck('new_survey');" class="btn btn-custom btn-lg">Add Survey</button>
									<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>												          
						          </div>
						        </form>
						        <?php  		
				                      	}
				                      
				                  ?>
				              </div>
				            </div>        
				          
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'sponsor'?'in active':'')?>" id="tab7">
				              <div class="row">
				              <?php
				                      
				              if ($list_sponsor_view) {
				              	//Get the list view for sponsor
				              	echo $adminAPI->buildSponsorsAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_sponsor_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_sponsor' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          
						          <div id="error_container_7"></div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_name">Sponsor Name</label>
						              <input type='text' name='sponsor_name' class="form-control input-lg" id='sponsor_name' value='' placeholder="Sponsor Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_profile">Sponsor Profile</label>
						              <textarea name='sponsor_profile' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='sponsor_profile' value='' placeholder="Sponsor Profile"></textarea>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_level">Level</label>
						              <input type='text' name='sponsor_level' class="form-control input-lg" id='sponsor_level' value='' placeholder="Level">
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="sponsor_website">Website</label>
						              <input type='text' name='sponsor_website' class="form-control input-lg" id='sponsor_website' value='' placeholder="Website">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_logo">Logo</label>
						              <input type='file' name='sponsor_logo' class="file logo_ex form-control input-lg" id='sponsor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_sponsor" type='submit' onClick="return Error_checks.automaticCheck('new_sponsor');" class="btn btn-custom btn-lg ">Add Sponsor</button>
										<a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>						          
						          </div>
						        </form>
						        <?php  		
				                      	}			                       
				                  ?>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'content'?'in active':'')?>" id="tab8">
				              <div class="row">
				              <?php
				                      
				              if ($list_content_view) {
				              	//Get the list view for content
				              	echo $adminAPI->buildContentAtEvent($_SESSION['newEventId']);
				              }
				              if ($display_content_form) {
				              	  $_SESSION['userAction'] = 'add';
				              	  unset($_SESSION['newTabObjId']);
				                   ?>
				                <form id='add_content' class="col-md-12" action=<?=$add_page_url?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_8"></div>
						          <div class="form-group col-md-12">
						          	  <label for="content_title">Content Title</label>
						              <input type='text' name='content_title' class="form-control input-lg" id='content_title' value='' placeholder="Content Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="content_description">Content Description</label>
						              <input type='text' name='content_description' class="form-control input-lg" id='content_description' value='' placeholder="Content Description">
						          </div>
						          <div class="form-group col-md-6">
						          	  <label for="content_pdf">PDF</label>
						              <input type='file' name='content_pdf' class="file logo_ex form-control input-lg" id='content_pdf'>
						          </div>
						      	  <div class="form-group col-md-6">
						          	  <label for="content_url">URL</label>
						              <input type='text' name='content_url' class="form-control input-lg" id='content_url' value='' placeholder="URL">
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_content" type='submit' onClick="return Error_checks.automaticCheck('new_content');" class="btn btn-custom btn-lg">Add Content</button>
						              <a id="cancelButton" href=<?=$add_page_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						          
						        </form>
						         <?php  		
				                      	}				           
				                  ?>
				              </div>
				            </div>
				          </div>
				          
			          
					</div>
				</div>
			</div>
 </div> 		
	</div>
</div>
<div id="deleteModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Wait!</h4>
      </div>
      <div class="modal-body">
        <div role="alert" class="alert alert-danger">
          <h4><i class="alert-ico fa fa-fw fa-ban"></i><strong>Confirm</strong></h4>Are you sure?
        </div>
      </div>
      <div class="modal-footer">
      	<button id="delete_node" type="button" data-dismiss="modal" class="btn btn-danger">Yes</button>
        <button type="button" data-dismiss="modal" class="btn btn-default">No</button>
      </div>
    </div>
  </div>
</div>  
  <div class="modal fade" id="deleteInfoModal">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Warning!</h4>
        </div>
        <div class="modal-body">
          <div role="alert" class="alert alert-danger" id="modalBodyAlertMsg">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
>>>>>>> .r446
