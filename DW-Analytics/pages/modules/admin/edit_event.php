<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<?php
	// echo var_dump($_GET['action']);
	$type = $_GET['action'];
	
	 
	//$update = $_GET['update'];
	//$objectId = $_GET['objid'];
	if (isset($_GET['action'])) {
		$regex = "/[a-zA-Z]+_[a-zA-Z0-9]+/";
		if (preg_match($regex, $_GET['action'])) {
			$action_var = explode("_", $_GET['action']);
			$update = $action_var[0];
			$objectId = $action_var[1];
			$_SESSION['updateObjId'] = $objectId;
		}
	}

	if (!$update) {
		$page_url = "/Organizer/Admin/Edit/".$type;
		$_SESSION['actionURL'] = $page_url;
	}

	$adminAPI->setEventId($_GET['event']);
	if(isset($_POST['submit_event'])) {
		$new_event_obj = $adminAPI->submitNewEvent($_SESSION['userID'], $_POST['event_title'],$_POST['event_timezone'],$_POST['event_code'],$_FILES['event_logo'], $_POST['event_location'], $_POST['event_address1'], $_POST['event_address2'], $_POST['event_city'], $_POST['event_state'], $_POST['event_zip'], $_POST['event_country'], $_POST['event_start'], $_POST['event_end'],  $_POST['event_hashtags']);
		//echo var_dump($new_event_obj->getObjectId());
		$_SESSION['newEventCode'] = $new_event_obj->get("eventCode");
		$_SESSION['newEventId'] = $new_event_obj->getObjectId();
	}else if(isset($_POST['submit_speaker'])) {
		$adminAPI->submitNewSpeaker($_SESSION['updateObjId'], $_SESSION['newEventId'], $_POST['speaker_name'], $_POST['speaker_company'], $_POST['speaker_title'], $_POST['speaker_bio'], $_POST['speaker_li_profile'], $_POST['speaker_tw_profile'], $_FILES['speaker_image']);
		$_SESSION['updateObjId'] = '';
		$_POST['submit_speaker'] = '';
		
		unset($_SESSION['updateObjId']);
		unset($_POST['submit_speaker']);
		$_SESSION['active_tab'] = 'speaker';
		
	echo "<div class='alert alert-success' id='success-alert'>";
   	echo "<button type='button' class='close' data-dismiss='alert'>x</button>";
   	echo "<strong>Success! </strong>";
    echo "Speaker has been added to your event.";
    echo "</div>";
	}else if(isset($_POST['submit_sponsor'])) {
		$adminAPI->submitNewSponsor($_SESSION['newEventId'], $_POST['sponsor_name'], $_POST['sponsor_profile'], $_POST['sponsor_level'],$_POST['sponsor_website'], $_FILES['sponsor_logo']);
		$_SESSION['active_tab'] = 'sponsor';
	} else if(isset($_POST['submit_session'])) {
		$adminAPI->submitNewSession($_SESSION['newEventId'], $_POST['session_title'], $_POST['session_description'], $_POST['session_start'], $_POST['seesion_end'], $_POST['session_speaker']);
		$_SESSION['active_tab'] = 'session';
		unset($_POST['submit_session']);
	} else if(isset($_POST['submit_exhibitor'])) {
		$adminAPI->submitNewExhibitor($_SESSION['newEventId'], $_POST['exhibitor_name'], $_POST['exhibitor_summary'], $_POST['exhibitor_booth'],$_POST['exhibitor_website'], $_FILES['exhibitor_logo']);
		$_SESSION['active_tab'] = 'exhibitor';
	} else if(isset($_POST['submit_beacon'])) {
		//$beacon_opts = $_POST['beacon_options'] ? $_POST['beacon_options'] : NULL;
		$beacon_type_val = ($_POST['beacon_type'] == 'Exhibitor') ? $_POST['beacon_exhibitor'] : $_POST['beacon_session'];  
		$adminAPI->submitNewBeacon($_SESSION['newEventId'], $_POST['beacon_name'], $_POST['beacon_type'], $beacon_type_val);
		$_SESSION['active_tab'] = 'beacon';
	} else if(isset($_POST['submit_notification'])) {
		$adminAPI->submitNewNotification($_SESSION['newEventId'], $_POST['notification_title'], $_POST['notification_message'], $_FILES['notification_pdf'], $_POST['notification_trigger'], $_POST['notification_url'], $_POST['notification_beacon']);
		$_SESSION['active_tab'] = 'notification';
	}else if(isset($_POST['submit_content'])) {
		$adminAPI->submitNewContent($_SESSION['newEventId'], $_POST['content_title'], $_POST['content_description'], $_FILES['content_pdf'], $_POST['content_url']);
		$_SESSION['active_tab'] = 'content';
	 } else if(isset($_POST['submit_survey'])) {
		$questions_array = array();
		for($i = 1; $i <= $_POST['survey_questions']; $i++) {
			array_push($questions_array, $_POST['survey_question'.$i.'_add']);
		}
		$adminAPI->submitNewSurvey($_SESSION['newEventId'], $_POST['survey_name'], $_POST['survey_session'], $questions_array);
		$_SESSION['active_tab'] = 'survey';
	}
 ?>
 
<div id="testing"></div>
<style type = "text/css">
 #submitButton, #cancelButton {
    display: inline-block;
}
</style>
<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-edit"></i> Edit Event - <?php $_SESSION['newEventCode'] ?></h3>
					</div>
					<div class="panel-body">
						<div class="submissions">
				          <ul class="nav nav-tabs" role="tablist">
				            <li class="<?php echo (($_SESSION['active_tab'] == 'speaker' || $_SESSION['active_tab'] == '')?'active':'')?>"><a class="center tab_color" href="#tab1" role="tab" data-toggle="tab">
				              <span class="fa fa-users"></span><br>Speak</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'session'?'active':'')?>"><a class="center tab_color" href="#tab2" role="presentation" data-toggle="tab">
				              <span class="fa fa-bars"></span><br>Sessions</a></li>
				           <li class="<?php echo ($_SESSION['active_tab'] == 'beacon'?'active':'')?>"><a class="center tab_color" href="#tab4" role="presentation" data-toggle="tab">
				              <i class="fa fa-bluetooth"></i><br>Beacons</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'notification'?'active':'')?>"><a class="center tab_color" href="#tab5" role="presentation" data-toggle="tab">
				              <i class="fa fa-clock-o"></i><br>Triggered Content</a></li>
				            <li class="<?php echo ($_SESSION['active_tab'] == 'survey'?'active':'')?>"><a class="center tab_color" href="#tab6" role="presentation" data-toggle="tab">
				              <i class="fa fa-question-circle-o"></i><br>Surveys</a></li>
				             <li class="<?php echo ($_SESSION['active_tab'] == 'exhibitor'?'active':'')?>"><a class="center tab_color" href="#tab3" role="presentation" data-toggle="tab">
				              <i class="fa fa-id-card-o"></i><br>Exhibitors</a></li>
							<li class="<?php echo ($_SESSION['active_tab'] == 'sponsor'?'active':'')?>"><a class="center tab_color" href="#tab7" role="presentation" data-toggle="tab">
				              <i class="fa fa-trophy"></i><br>Sponsors</a></li>			          
				            <li class="<?php echo ($_SESSION['active_tab'] == 'content'?'active':'')?>"><a class="center tab_color" href="#tab8" role="presentation" data-toggle="tab">
				              <i class="fa fa-files-o"></i><br>Content</a></li>
				          </ul>
				          <div class="tab-content">
				            <div role="tabpanel" class="tab-pane fade <?php echo (($_SESSION['active_tab'] == '' || $_SESSION['active_tab'] == 'speaker')?'in active':'')?>" id="tab1">
				              <div class="row">
				                
				                <form id='add_speaker' class="col-md-12" action=<?=$_SESSION['actionURL'] ?> method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						       		<div id="error_container_1"></div>
						       		<div id="added_speakers" class="form-group col-md-12">
						       		<?php 
						       		    if (!$update) {
						       		        echo $adminAPI->displayAddedSpeakers($_SESSION['newEventId'], $_GET['action']);
						       		    } 
						       		?>
						       		</div>
						       		<div id="speaker_update" class="form-group col-md-12">
						       		<?php
						       		   $speaker = '';
						       		   $button_name = 'Add Speaker';
						       		    if ($update == 'speaker') {
						       		        $speaker = $adminAPI->getSpeakerInfoById($objectId);
						       		        $button_name = 'Update Speaker';
						       		    } 
						       		?>
						       		</div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_name">Name</label>
						              <input type='text' name='speaker_name' class="form-control input-lg" id='speaker_name' value="<?php echo ($update == 'speaker')?$speaker->get("firstName"):''; ?>" placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_company">Company</label>
						              <input type='text' name='speaker_company' class="form-control input-lg" id='speaker_company' value="<?php echo ($update == 'speaker')?$speaker->get("company"):''; ?>" placeholder="Company">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_title">Title</label>
						              <input type='text' name='speaker_title' class="form-control input-lg" id='speaker_title' value="<?php echo ($update == 'speaker')?$speaker->get("title"):''; ?>" placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_bio">Bio/Summary</label>
						              <textarea name='speaker_bio' class="form-control input-lg" id='speaker_bio' value="<?php echo ($update == 'speaker')?$speaker->get("about"):''; ?>" placeholder="Bio"></textarea>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_li_profile">LinkedIn Profile</label>
						              <input type='text' name='speaker_li_profile' class="form-control input-lg" id='speaker_li_profile' value="<?php echo ($update == 'speaker')?$speaker->get("linkedInURL"):''; ?>" placeholder="https://www.linkedin.com/in/ExampleName/">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_tw_profile">Twitter Profile</label>
						              <input type='text' name='speaker_tw_profile' class="form-control input-lg" id='speaker_tw_profile' value="<?php echo ($update == 'speaker')?$speaker->get("twitterURL"):''; ?>" placeholder="@ExampleName">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_image">Image</label>
						              <input type='file' name='speaker_image' class="file logo_ex form-control input-lg" id='speaker_image' value="<?php echo ($update == 'speaker')?$speaker->get("avatar")->getURL():''; ?>" >
						          </div>
						          <div class="form-group col-md-6">
						              <button name="submit_speaker" id = "submitButton"  href="javascript:;" type='submit' onClick="return Error_checks.automaticCheck('new_speaker');" class="btn btn-custom btn-lg btn-block "><?=$button_name; ?></button>
						              <button id="cancelButton" onClick="javascript:history.go(-1);" class="btn btn-custom btn-lg btn-block">Cancel</button>
						          </div>
						          
						            </form>
						            </div>
				                   </div>
				               
				            
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'session'?'in active':'')?>" id="tab2">
				              <div class="row">
				                <form id='add_session' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_2"></div>
						          <div class="form-group col-md-12">
						          	  <label for="session_title">Title</label>
						              <input type='text' name='session_title' class="form-control input-lg" id='session_title' value='' placeholder="Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_description">Description</label>
						              <textarea name='session_description' class="form-control input-lg" id='session_description' value='' placeholder="Description"></textarea>
						          </div>
						          <div class="form-group col-md-3">
						          	  <label for="session_start">Start Time</label>
						              <input type='text' name='session_start' class="date_pick form-control input-lg" id='session_start' value='' placeholder="Start Time">
						          </div>
						          <div class="form-group col-md-3">
						          	  <label for="session_end">End Time</label>
						              <input type='text' name='session_end' class="date_pick form-control input-lg" id='session_end' value='' placeholder="End Time">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_speaker">Speakers</label>
						              <select data-live-search='true' style="background-color: #5e6870;" data-style="input-lg btn-lg" name='session_speaker[]' class=" form-control input-lg selectpicker" id='session_speaker' value='' multiple title="Choose multiple Speakers if required">
						              	<?= $adminAPI->buildSpeakerSelection(); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_session" type='submit' onClick="return Error_checks.automaticCheck('new_session');" class="btn btn-custom btn-lg btn-block">Add Session</button>
						          </div>
						        </form>
				              </div>
				            </div>
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'exhibitor'?'in active':'')?>" id="tab3">
				              <div class="row">
				                <form id='add_exhibitor' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div id="error_container_3"></div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_name">Name</label>
						              <input type='text' name='exhibitor_name' class="form-control input-lg" id='exhibitor_name' value='' placeholder="Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_summary">Company Profile/Summary</label>
						              <textarea name='exhibitor_summary' class="form-control input-lg" id='exhibitor_summary' value='' placeholder="Summary"></textarea>
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_booth">Booth #</label>
						              <input type='text' name='exhibitor_booth' class="form-control input-lg" id='exhibitor_booth' value='' placeholder="Booth #"></textarea>
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="exhibitor_website">Website</label>
						              <input type='text' name='exhibitor_website' class="form-control input-lg" id='exhibitor_website' value='' placeholder="Website"></textarea>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_logo">Logo</label>
						              <input type='file' name='exhibitor_logo' class="file logo_ex form-control input-lg" id='exhibitor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_exhibitor" type='submit' onClick="return Error_checks.automaticCheck('new_exhibitor');" class="btn btn-custom btn-lg btn-block">Add Exhibitor</button>
						          </div>
						        </form>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'beacon'?'in active':'')?>" id="tab4">
				              <div class="row">
				                <form id='add_beacon' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
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
						              <select data-live-search='true' data-style="input-lg btn-lg" style="background-color: #5e6870" name='beacon_session[]' class="form-control input-lg selectpicker" id='beacon_session' value='' title="Choose a Session">
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
						              <button name="submit_beacon" type='submit' onClick="return Error_checks.automaticCheck('new_beacon');" class="btn btn-custom btn-lg btn-block">Add Beacon</button>
						          </div>
						        </form>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'notification'?'in active':'')?>" id="tab5">
				              <div class="row">
				                <form id='add_notification' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
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
						              <button name="submit_notification" type='submit' onClick="return Error_checks.automaticCheck('new_notification');" class="btn btn-custom btn-lg btn-block">Add Notification</button>
						          </div>
						        </form>
				              </div>
				            </div>
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'survey'?'in active':'')?>" id="tab6">
				              <div class="row">
				                <form id='add_survey' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          
						          <div id="error_container_6"></div>
						          <div class="form-group col-md-12">
						          	  <label for="survey_name">Name</label>
						              <input type='text' name='survey_name' class="form-control input-lg" id='survey_name' value='' placeholder="Name">
						          </div>
						          <div class="form-group col-md-4">
						          	  <label for="survey_session">Session</label>
						              <select data-live-search='true' data-style="input-lg btn-lg" name='survey_session' class="form-control input-lg selectpicker" id='survey_session'>
						              	<option value="" selected disabled>Choose a Session</option>
						              	<?= $adminAPI->buildSessionSelection($_GET['event']); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-4">
						          	  <label for="survey_questions">How many Questions?</label>
						              <input type='number' name='survey_questions' class="form-control input-lg" id='survey_questions' min='0' max="20" value='3' placeholder="">
						              <button type="button" class='btn btn-custom' onClick="beacon_options.populate()">Generate Questions</button>
						          </div>
						          <div id="survey_questions_generate" class="form-group col-md-6"></div>
						          <div class="form-group col-md-12">
						              <button name="submit_survey" type='submit' onClick="return Error_checks.automaticCheck('new_survey');" class="btn btn-custom btn-lg btn-block">Add Survey</button>
						          </div>
						        </form>
				              </div>
				            </div>        
				          
				            <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'sponsor'?'in active':'')?>" id="tab7">
				              <div class="row">
				                <form id='add_sponsor' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          
						          <div id="error_container_7"></div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_name">Sponsor Name</label>
						              <input type='text' name='sponsor_name' class="form-control input-lg" id='sponsor_name' value='' placeholder="Sponsor Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_profile">Sponsor Profile</label>
						              <textarea name='sponsor_profile' class="form-control input-lg" id='sponsor_profile' value='' placeholder="Sponsor Profile"></textarea>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_level">Level</label>
						              <input type='text' name='sponsor_level' class="form-control input-lg" id='sponsor_level' value='' placeholder="Level">
						          </div>
						           <div class="form-group col-md-12">
						          	  <label for="sponsor_website">Website</label>
						              <input type='text' name='sponsor_website' class="form-control input-lg" id='sponsor_website' value='' placeholder="Website"></textarea>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_logo">Logo</label>
						              <input type='file' name='sponsor_logo' class="file logo_ex form-control input-lg" id='sponsor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_sponsor" type='submit' onClick="return Error_checks.automaticCheck('new_sponsor');" class="btn btn-custom btn-lg btn-block">Add Sponsor</button>
						          </div>
						        </form>
				              </div>
				            </div>
				             <div role="tabpanel" class="tab-pane fade <?php echo ($_SESSION['active_tab'] == 'content'?'in active':'')?>" id="tab8">
				              <div class="row">
				                <form id='add_content' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
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
						              <button name="submit_content" type='submit' onClick="return Error_checks.automaticCheck('new_content');" class="btn btn-custom btn-lg btn-block">Add Content</button>
						          </div>
						          <?php unset($_SESSION['active_tab']);?>
						        </form>
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
<script>
$(document).ready (function(){
    $("#success-alert").hide();
    $("#submitButton").click(function showAlert() {
        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
       $("#success-alert").slideUp(500);
        });   
    });
});

</script>