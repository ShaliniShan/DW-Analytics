<?php
//url for edit event - Admin/Edit/BESE2017/Event
//foreach ($_GET as $name => $value) {
  //echo $name . ' : ' . $value . '<br />';
//}
$detailsInc = false;
if (count($_GET) == 4) {
	if (isset($_GET['specific'])) {
		$update = $_GET['specific'];
	}
	if (isset($_GET['event'])) {
		$eventCode = $_GET['event'];
		$event_obj = $adminAPI->getEventFromEventCode($eventCode);
		$_SESSION['newEventId'] = $event_obj->getObjectId();
		$objectId = $event_obj->getObjectId();
		$_SESSION['updateObjId'] = $objectId;
	}
	$detailsInc = true;
}
//url for edit speaker (other tabs) - Admin/Edit/BESE2017/edit-speaker/ab7392xy
if (count($_GET) == 5) {
	if (isset($_GET['specific'])) {
		$specificArray = explode("-", $_GET['specific']);
		$update = $specificArray[1];
	}
	if (isset($_GET['event'])) {
		$eventCode = $_GET['event'];
		$event_obj = $adminAPI->getEventFromEventCode($eventCode);
		$_SESSION['newEventId'] = $event_obj->getObjectId();
	}
	if (isset($_GET['class'])) {
		$objectId = $_GET['class'];
		$_SESSION['updateObjId'] = $objectId;
	}
	$detailsInc = true;
}

if (!$detailsInc) {
	$action_var = explode("_", $_GET['action']);
	$update = $action_var[0];
	$objectId = $action_var[1];
	$_SESSION['updateObjId'] = $objectId;
	$_SESSION['newEventId'] = $_GET['event'];
}
//$page_url = "/Organizer/Admin/Edit/".$_GET['action'];

$current_tab = 'event';
//For event edit, both $_SESSION[newEventId] and $_SESSION[updateObjId] will be same
if (!$detailsInc) {
    $event_obj = $adminAPI->getEventById($_SESSION['newEventId']);
}
if ($update == 'event') {
	//Since we already have event info
	//$event = $adminAPI->getEventInfoById($objectId);
	$current_tab = 'event';
	$display_title = 'Event';
}

if ($update == 'speaker') {
	$speaker = $adminAPI->getSpeakerInfoById($objectId);
	$current_tab = 'speaker';
	$display_title = 'Speaker';
	//$button_name = 'Update Speaker';
}
else if ($update == 'session') {
	$session = $adminAPI->getSessionInfoById($objectId);
	$current_tab = 'session';
	$display_title = 'Session';
	//echo var_dump($session);
}
else if ($update == 'content') {
	$content = $adminAPI->getContentInfoById($objectId);
	$current_tab = 'content';
	$display_title = 'content';
}
else if ($update == 'notification') {
	$notification = $adminAPI->getNotificationInfoById($objectId);
	$current_tab = 'notification';
	$display_title = 'Notification';
}
else if ($update == 'beacon') {
	$beacon = $adminAPI->getBeaconInfoById($objectId);
	$current_tab = 'beacon';
	$display_title = 'Beacon';
}

else if ($update == 'exhibitor') {
	$exhibitor = $adminAPI->getExhibitorInfoById($objectId);
	$current_tab = 'exhibitor';
	$display_title = 'Exhibitor';
	//echo var_dump($exhibitor);
}
else if ($update == 'sponsor') {
	$sponsor = $adminAPI->getSponsorInfoById($objectId);
	$current_tab = 'sponsor';
	$display_title = 'Sponsor';
}
 else if($update == 'survey') {
	$surveyQ = $adminAPI->getSurveyQuestionById($objectId);
	$current_tab = 'survey';
	$display_title = 'Survey';
}

 $goto_action_url = '/Organizer/admin/edit/' . $event_obj->get("eventCode");
 //$cancel_url = $goto_action_url . "_cancel_" . $current_tab;
 
 $_SESSION['prevURI'] = $_SERVER['REQUEST_URI'];
 //echo var_dump($_SESSION['userAction']);
?>

<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-file"></i>  Edit <?= $display_title?> </h3>
					</div>
					<div class="panel-body">
					    <?php if ($update == 'speaker') {?>  
						<form id='add_speaker' class="col-md-12" action=<?=$goto_action_url ?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_1"></div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_name">Name</label>
						              <input type='text' name='speaker_name' class="form-control input-lg" id='speaker_name' value="<?php echo $speaker->get("firstName") . ' ' . $speaker->get("lastName");?>" placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_company">Company</label>
						              <input type='text' name='speaker_company' class="form-control input-lg" id='speaker_company' value="<?php echo $speaker->get("company"); ?>" placeholder="Company">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_title">Title</label>
						              <input type='text' name='speaker_title' class="form-control input-lg" id='speaker_title' value="<?php echo $speaker->get("title"); ?>" placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_bio">Bio/Summary</label>
										 <textarea name='speaker_bio'  rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='speaker_bio' placeholder=" Bio"><?php echo ($speaker->get("about")?$speaker->get("about"):'')?></textarea>						          
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_li_profile">LinkedIn Profile</label>
						              <input type='text' name='speaker_li_profile' class="form-control input-lg" id='speaker_li_profile' value="<?php echo $speaker->get("linkedInURL");?>" placeholder="https://www.linkedin.com/in/ExampleName/">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_tw_profile">Twitter Profile</label>
						              <input type='text' name='speaker_tw_profile' class="form-control input-lg" id='speaker_tw_profile' value="<?php echo $adminAPI->getTwitterAccount($speaker->get("twitterURL")); ?>" placeholder="@ExampleName">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_image">Image</label>
						          	  <?php if ($speaker->get("avatar")) {?>
						          	      <img src="<?=$speaker->get("avatar")->getURL(); ?>" width="70" alt="" style="border-radius: 35px;"><br>
						          	  <?php }?>
						              <input type='file' name='speaker_image' class="file logo_ex form-control input-lg" id='speaker_image' value='' >
						          </div>
						          <div class="form-group col-md-6">
						              <button name="submit_speaker" id = "submitButton"  href="javascript:;" type='submit' onClick="return Error_checks.automaticCheck('new_speaker');" class="btn btn-custom btn-lg ">Update Speaker</button>
						             <a id="cancelButton" href=<?=$goto_action_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						          
						            </form>
						            <?php } else if ($update == 'event') { ?> 
						            <form name='add_event_name'  action=<?=$goto_action_url ?> id='add_event' class="col-md-12" method='POST' accept-charset='UTF-8' enctype="multipart/form-data" autocomplete="on">
						<div id="error_container"></div>
				          <div class="form-group col-md-8">
				          	  <label for="event_title">Title</label>
				              <input type='text' name='event_title' class="form-control input-lg" id='event_title' value="<?php echo $event_obj->get("name"); ?>" placeholder="Title">
				          </div>
				          <div class="form-group col-md-4">
						          	  <label for="event_timezone">Timezone</label>
						              <select data-live-search='true' style="background-color: #5e6870" name='event_timezone[]' class="form-control input-lg selectpicker" id='event_timezone' value='' title="Choose a Timezone">
						              	<?= $adminAPI->buildTimezoneSelection($event_obj->get("timeZone")); ?>
						              </select>
						   </div>
						   <div class="form-group col-md-6">
				          	  <label for="event_code">Event Code</label>
				              <input type='text' name='event_code' class="form-control input-lg" id='event_code' value="<?php echo $event_obj->get("eventCode"); ?>" placeholder="Event Code(must be unique)">
				          </div>
				          <div class="form-group col-md-6">
				          	  <label for="event_logo">Logo</label>
				          	    <?php if ($event_obj->get("logo")) {?>
						          	      <img src="<?=$event_obj->get("logo")->getURL(); ?>" width="70" alt="" style="border-radius: 35px;"><br>
						          	  <?php }?>
						     <input type='file' name='event_logo' class="file logo_ex form-control input-lg" id='event_logo' value = ''>
				          </div>
				          <div class="form-group col-md-12">
				          	  <label for="event_location">Location/Venue Name</label>
				              <input type='text' name='event_location' class="form-control input-lg" id='event_location' value="<?php echo $event_obj->get("location"); ?>" placeholder="Location">
				          </div>
				          <div class="form-group col-md-6">
				          	  <label for="event_address1">Address 1</label>
				              <input type='text' name='event_address1' class="form-control input-lg" id='event_address1' value="<?php echo $event_obj->get("address"); ?>" placeholder="Address 1">
				          </div>
				          <div class="form-group col-md-6">
				          	  <label for="event_address2">Address 2</label>
				              <input type='text' name='event_address2' class="form-control input-lg" id='event_address2' value="<?php echo $event_obj->get("address2"); ?>" placeholder="Address 2">
				          </div>
				          <div class="form-group col-md-4">
				          	  <label for="event_city">City</label>
				              <input type='text' name='event_city' class="form-control input-lg" id='event_city' value="<?php echo $event_obj->get("city"); ?>" placeholder="City">
				          </div>
				          <div class="form-group col-md-4">
				          	  <label for="event_state">State</label>
				              <input type='text' maxlength="2" name='event_state' class="form-control input-lg" id='event_state' value="<?php echo $event_obj->get("state"); ?>" placeholder="State">
				          </div>
				          <div class="form-group col-md-4">
				          	  <label for="event_zip">Zip Code</label>
				              <input type='text' maxlength="5" name='event_zip' class="form-control input-lg" id='event_zip' value="<?php echo $event_obj->get("zipCode"); ?>" placeholder="01730">
				          </div>
				          <div class="form-group col-md-3">
				          	  <label for="event_start">Start Time</label>
				              <input type='text' name='event_start' class="date_pick form-control input-lg" id='event_start' value="<?php echo $adminAPI->setTimeZone($event_obj->get("startDate")->format("Y-m-d H:i:s"), $event_obj->get("timeZone"))->format("Y-m-d H:i:s"); ?>" placeholder="Start Time">
				          </div>
				          <div class="form-group col-md-3">
				          	  <label for="event_end">End Time</label>
				              <input type='text' name='event_end' class="date_pick form-control input-lg" id='event_end' value="<?php echo $adminAPI->setTimeZone($event_obj->get("endDate")->format("Y-m-d H:i:s"), $event_obj->get("timeZone"))->format("Y-m-d H:i:s"); ?>" placeholder="End Time">
				          </div>
				          
				          <div class="form-group col-md-12">
				          	  <label for="event_hashtags">Twitter Hashtags</label>
				          	  <?php
				          	  for($i = 0; $i < count($event_obj->get("twTags")); $i++) {
				          	  	$twitter .= '#'. $event_obj->get("twTags")[$i] . ' ';
				          	  }
				          	  ?>
				              <input type='text' name='event_hashtags' class="form-control input-lg" id='event_hashtags' value ="<?php echo $twitter; ?>" placeholder="#tag1#tag2#tag3">
				          </div>
				          <div class="form-group col-md-12">
				              <input type="hidden" id="editEventId" name="editEvtObj" value=<?=$_SESSION['newEventId']?>>
				              <button name="submit_event" type='submit' onClick="return Error_checks.automaticCheck('new-event');" class="btn btn-custom btn-lg">Update Event</button>
				          	 <a id="cancelButton" href=<?=$goto_action_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>	
				          </div>
					    </form>
		
						            <?php } else if ($update == 'session') {?>  
							<form id='add_session' class="col-md-12" action=<?=$goto_action_url ?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_2"></div>
								<div class="form-group col-md-12">
						          	  <label for="session_title">Title</label>
						              <input type='text' name='session_title' class="form-control input-lg" id='session_title' value="<?php echo $session->get("title"); ?>" placeholder="Title">
						          </div>
						          			          
						          <div class="form-group col-md-12">
						          	  <label for="session_description">Description</label>
						              <textarea name='session_description' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;"  id='session_description'  placeholder="Description"><?php echo $session->get("details"); ?></textarea>
						          </div>
						          <div class="form-group col-md-3">
						          	  <label for="session_start">Start Time</label>
						              <input type='text' name='session_start' class="date_pick form-control input-lg" id='session_start' value="<?php echo $adminAPI->setTimeZone($session->get("startTime")->format("Y-m-d H:i:s"), $event_obj->get("timeZone"))->format("Y-m-d H:i:s"); ?>" placeholder="Start Time">
						          </div>
						          <div class="form-group col-md-3">
						          	  <label for="session_end">End Time</label>
						              <input type='text' name='session_end' class="date_pick form-control input-lg" id='session_end' value="<?php echo $adminAPI->setTimeZone($session->get("endTime")->format("Y-m-d H:i:s"), $event_obj->get("timeZone"))->format("Y-m-d H:i:s"); ?>" placeholder="End Time">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_speaker">Speakers</label>
						              <select data-live-search='true' value = '' style="background-color: #5e6870;" data-style="input-lg btn-lg" name='session_speaker[]' class=" form-control input-lg selectpicker" id='session_speaker'  value='' multiple title="Choose multiple Speakers if required" >	
						           	  	<?php $speakersInSession = $session->get("speakerIds");
						           	  	      //if ($speakersInSession) {
						           	  	      //foreach($speakersInSession as $sp) {
						           	  	          echo $adminAPI->buildSpeakerSelection($speakersInSession);	
						           	  	      //} 						    									    							    
						    		       //} else {
						    		 	    //echo $adminAPI->buildSpeakerSelection();
						    			   //}
						    			   ?>
						           	  
						              	             	
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						         
						              <button name="submit_session" id = "submitButton" href="javascript:;" type='submit' onClick="return Error_checks.automaticCheck('new_session');" class="btn btn-custom btn-lg">Update Session</button>
									<a id="cancelButton" href=<?=$goto_action_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>											          
						          </div>
						        </form>
						        <?php	} else if ($update == 'exhibitor') {?>  
						<form id='add_exhibitor' class="col-md-12" action=<?=$goto_action_url ?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_3"></div>
						         <div class="form-group col-md-12">
						          	  <label for="exhibitor_name">Name</label>
						              <input type='text' name='exhibitor_name' class="form-control input-lg" id='exhibitor_name' value="<?php echo $exhibitor->get("name"); ?>" placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_summary">Company Profile/Summary</label>
						              <textarea name='exhibitor_summary'  rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" id='exhibitor_summary' placeholder="Summary"><?php echo $exhibitor->get("description"); ?></textarea>
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_booth">Booth #</label>
						              <input type='text' name='exhibitor_booth' class="form-control input-lg" id='exhibitor_booth' value="<?php echo $exhibitor->get("booth"); ?>" placeholder="Booth #">
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_website">Website</label>
						              <input type='text' name='exhibitor_website' class="form-control input-lg" id='exhibitor_website' value="<?php echo $exhibitor->get("website"); ?>" placeholder="Website">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_logo">Logo</label>
						          	  <?php if ($exhibitor->get("logo")) {?>
						          	      <img src="<?=$exhibitor->get("logo")->getURL(); ?>" width="70" alt="" style="border-radius: 35px;"><br>
						          	  <?php }?>
						        						          
						              <input type='file' name='exhibitor_logo' class="file logo_ex form-control input-lg" id='exhibitor_logo' value='' >
						          </div>
						          
						          <div class="form-group col-md-12">
						              <button name="submit_exhibitor" id = "submitButton"  href="javascript:;" type='submit' onClick="return Error_checks.automaticCheck('new_exhibitor');" class="btn btn-custom btn-lg ">Update Exhibitor</button>
						          		<a id="cancelButton" href=<?=$goto_action_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
						          </div>
						        </form>
						        <?php  		
				                      	}else if ($update == 'sponsor') {?>
						         
						       <form id='add_sponsor' class="col-md-12" action=<?=$goto_action_url ?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_7"></div>
						          			<div class="form-group col-md-12">
						          	  <label for="sponsor_name">Sponsor Name</label>
						              <input type='text' name='sponsor_name' class="form-control input-lg" id='sponsor_name' value="<?php echo $sponsor->get("name");?>" placeholder="Sponsor Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_profile">Sponsor Profile</label>
						              <textarea name='sponsor_profile' id='sponsor_profile' rows='5' data-min-rows='5' class='form-control-class' style = "width:99.9%;overflow:hidden;font-family:Source Sans Pro;font-size:17px;line-height:170%;margin:0px 0px;padding:5px;height:100%;;margin:0px auto;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" placeholder="Sponsor Profile"><?php echo $sponsor->get("description"); ?></textarea>
						          </div>
						          
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_level">Level</label>
						              <input type='text' name='sponsor_level' class="form-control input-lg" id='sponsor_level' value="<?php echo $sponsor->get("level"); ?>" placeholder="Level">
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="sponsor_website">Website</label>
						              <input type='text' name='sponsor_website' class="form-control input-lg" id='sponsor_website' value="<?php echo $sponsor->get("website"); ?>" placeholder="Website">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_logo">Logo</label>
						          	  <?php if ($sponsor->get("logo")) {?>
						          	      <img src="<?=$sponsor->get("logo")->getURL(); ?>" width="70" alt="" style="border-radius: 35px;"><br>
						          	  <?php }?>
						              <input type='file' name='sponsor_logo' class="file logo_ex form-control input-lg" id='sponsor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_sponsor" type='submit' id = "submitButton"  href="javascript:;" onClick="return Error_checks.automaticCheck('new_sponsor');" class="btn btn-custom btn-lg ">Update Sponsor</button>
										<a id="cancelButton" href=<?=$goto_action_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>						          
						          </div>
						        </form>
						        <?php  		
				                      	}else if ($update == 'beacon') {			                       
				                  ?>
							 <form id='add_beacon' class="col-md-12" action=<?=$goto_action_url ?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_4"></div>
						       		<div class="form-group col-md-12">
						          	  <label for="beacon_name">Name</label>
						              <input type='text' name='beacon_name' class="form-control input-lg" id='beacon_name' value="<?php echo $beacon->get("name");?>" placeholder="Name">
						          </div>
						          <?php $beacon_type = $adminAPI->getBeaconTypeByBeacon($beacon) ?>
					
					
				
						          <div class="form-group col-md-12">
						          	  <label for="beacon_type">Beacon Type</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" value = '' name='beacon_type' class="form-control input-lg selectpicker" id='beacon_type'>
						      			<option selected disabled>Choose a Beacon</option>
						    <?php 
						    	$select = ($beacon_type == 'Organizer' )? "selected" : "";
						    	?>
						    	<option value='Organizer'  <?php echo $select ?> >Organizer</option>
						     <?php 
						    	$select = ($beacon_type == 'Exhibitor' )? "selected" : "";
						    	?>
						    	<option value="Exhibitor"  <?php echo $select ?>> Exhibitor</option>
						   <?php 
						    	$select = ($beacon_type == 'Session' )? "selected" : "";
						    	?>
						    	<option value="Session"  <?php echo $select ?>>Session</option>
			  		   
				           </select> 
						          </div>
						          <div class="beacon_container">
						          <div class="Session">
						          <div class="form-group col-md-12">
						          	  <label for="beacon_session">Session</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" value = '' multiple style="background-color: #5e6870" name='beacon_session' class="form-control input-lg selectpicker" id='beacon_session'   title="Choose multiple Sessions if required">
						         		<!--  	<option selected disabled>Choose multiple Sessions if required</option> -->
						         		
						              	<?= $adminAPI->buildSessionSelection($beacon->getObjectId()); ?>
						              </select>
						          </div>
						          </div>
						          <div class="Exhibitor">
						          <div class="form-group col-md-12">
						          	  <label for="beacon_exhibitor">Exhibitor</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" value = '' style="background-color: #5e6870" name='beacon_exhibitor' class="form-control input-lg selectpicker" id='beacon_exhibitor' >
						             <!-- <option selected disabled>Choose an Exhibitor</option> -->
						              	<?= $adminAPI->buildExhibitorSelection($beacon->get("companyId")); ?>
						              </select>
						          </div>
						          </div>
						          </div>
						          <div id="beacon_options_container" class="form-group col-md-12"></div>
						          <div class="form-group col-md-12">
						              <button name="submit_beacon" type='submit' id = "submitButton"  href="javascript:;" onClick="return Error_checks.automaticCheck('new_beacon');" class="btn btn-custom btn-lg">Update Beacon</button>
									<a id="cancelButton" href=<?=$goto_action_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>							          
						          </div>
						        </form>
						        <?php  		
				                      	}else if ($update == 'notification') {?>
						         
						       <form id='add_notification' class="col-md-12" action=<?=$goto_action_url ?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_5"></div>
						       		 <div class="form-group col-md-12">
						          	  <label for="notification_title">Title</label>
						              <input type='text' name='notification_title' class="form-control input-lg" id='notification_title' value="<?php echo $notification->get("title");?>" placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_message">Message</label>
						              <input type='text' name='notification_message' class="form-control input-lg" id='notification_message' value="<?php echo $notification->get("message");?>" placeholder="Message">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_pdf">PDF</label><br>
						          	   <?php 
						          	   $pdf = $notification->get("pdfFile") ? $notification->get("pdfFile")->getURL() : "";
						          	   //echo $pdf;
						          	   echo '<a href="' . $pdf . '" target="_blank">View PDF</a>';?>
						          	   	   
						          <input type='file' name='notification_pdf' class="file logo_ex form-control input-lg" id='notification_pdf' value=''>
						          </div>
						      	  <div class="form-group col-md-12">
						          	  <label for="notification_url">URL</label>
						              <input type='text' name='notification_url' class="form-control input-lg" id='notification_url' value="<?php echo $notification->get("url");?>" placeholder="URL">
						          </div>
						          <div class="form-group col-md-12">
						          	<label for="notification_trigger">Trigger After(xx minutes)</label>
						          	<select data-live-search='true' data-style="input-lg btn-lg" value = '' class="form-control input-lg selectpicker" name="notification_trigger" id="notification_trigger">
						          		<option selected disabled>Choose a Time</option>
						          		<?= $adminAPI->buildNotificationSelect($_SESSION['newEventId'],($notification->get("triggerTimeInSecs"))/60); ?>
						          	</select>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_beacon">Beacon</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" value = '' name='notification_beacon' class="form-control input-lg selectpicker" id='notification_beacon'>
						              	<option selected disabled>Choose a Beacon</option>
						              	<?= $adminAPI->buildBeaconDropDown($_SESSION['newEventId'], $notification->get("beaconId")); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_notification" type='submit' id = "submitButton"  href="javascript:;"  onClick="return Error_checks.automaticCheck('new_notification');" class="btn btn-custom btn-lg">Update Notification</button>
										<a id="cancelButton" href=<?=$goto_action_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>						          
						          </div>
						        </form>
						        <?php  		
				                      	} else if ($update == 'survey') {				            			                      
				                  ?>
				                  <form id='add_survey' class="col-md-12" action=<?=$goto_action_url ?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          
						          <div id="error_container_6"></div>
						          
						            
						        
						        
						         <div class="form-group col-md-12">
						          	  <label for="survey_question">Question</label>
						              <input type='text' name='survey_question' class="form-control input-lg" id='survey_question' value="<?php echo $surveyQ->get("question"); ?>" placeholder="Survey Question">
						          </div>
						           						
						          <div class="form-group col-md-12">
						              <button name="submit_survey" type='submit' onClick="return Error_checks.automaticCheck('new_survey');" class="btn btn-custom btn-lg">Update Question</button>
									<a id="cancelButton" href=<?=$goto_action_url ?>  onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>												          
						          </div>
						        </form>
						        <?php }else if ($update == 'content') {			                       
				                  ?>
							 <form id='add_content' class="col-md-12" action=<?=$goto_action_url ?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_8"></div>
						       	  <div class="form-group col-md-12">
						          	  <label for="content_title">Content Title</label>
						              <input type='text' name='content_title' class="form-control input-lg" id='content_title' value="<?php echo $content->get("title"); ?>" placeholder="Content Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="content_description">Content Description</label>
						              <input type='text' name='content_description' class="form-control input-lg" id='content_description' value="<?php echo $content->get("description"); ?>" placeholder="Content Description">
						          </div>
						          <div class="form-group col-md-6">
						          	  <label for="content_pdf">PDF</label>
						          	  	 <?php 
						          	   $pdf = $content->get("pdfFile") ? $content->get("pdfFile")->getURL() : "";
						          	   //echo $pdf;
						          	   echo '<a href="' . $pdf . '" target="_blank">View PDF</a>';?>
						         <input type='file' name='content_pdf' class="file logo_ex form-control input-lg" id='content_pdf' value = ''>
						          </div>
						      	  <div class="form-group col-md-6">
						          	  <label for="content_url">URL</label>
						              <input type='text' name='content_url' class="form-control input-lg" id='content_url' value="<?php echo $content->get("url"); ?>" placeholder="URL">
						          </div>
						            
						       		  
						       	<div class="form-group col-md-12">
						              <button name="submit_content" type='submit' id = "submitButton"  href="javascript:;" onClick="return Error_checks.automaticCheck('new_content');" class="btn btn-custom btn-lg">Update Content</button>
									<a id="cancelButton" href=<?=$goto_action_url ?> onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>						          
						          </div>
						        </form>
						        <?php  		
				                      	}?>
				              
						          
				                       
				                 
						          
						                   				  
						       
					    
					</div>
				</div>
			</div>
		</div>
	</div>
</div>