<?php 
	$type = $_GET['action'];
	//$adminAPI->setEventId($_GET['event']);
	if(isset($_POST['submit_speaker'])) {
		/* This is will the id of the user */
		//$adminAPI->submitNewSpeaker($_SESSION['userID'], $_POST['event_title'], $_POST['event_description'], $_POST['event_location'], $_POST['event_address'], $_POST['event_city'], $_POST['event_state'], $_POST['event_zip'], $_POST['event_country'], $_POST['event_start'], $_POST['event_end'], $_FILES['event_logo'], $_POST['event_hashtags']);
		$adminAPI->submitNewSpeaker($_SESSION['newEventId'] , $_POST['speaker_name'], $_POST['speaker_company'], $_POST['speaker_title'], $_POST['speaker_bio'], $_POST['speaker_li_profile'], $_POST['speaker_tw_profile'], $_FILES['speaker_image']);
	}
	if(isset($_POST['submit_session'])) {
		/* This is will the id of the user */
		$adminAPI->submitNewSession($_GET['event'], $_POST['session_title'], $_POST['session_description'], $_POST['session_start'], $_POST['seesion_end'], $_POST['session_room'], $_POST['session_speaker']);
	}
?>
<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-file"></i>  Add Session </h3>
					</div>
					<div class="panel-body">
					    <form id='add_session' class="col-md-12" action='/Organizer/Admin/new_exhibitor' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
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
						          	  <label for="seesion_end">End Time</label>
						              <input type='text' name='seesion_end' class="date_pick form-control input-lg" id='seesion_end' value='' placeholder="End Time">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_room">Room</label>
						              <input type='text' name='session_room' class="form-control input-lg" id='session_room' value='' placeholder="Room">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="session_speaker">Speaker</label>
						              <select data-live-search='true' style="background-color: #5e6870" name='session_speaker[]' class="form-control input-lg selectpicker" id='session_speaker' value='' multiple title="Choose a Speaker">
						              	<?= $adminAPI->buildSpeakerSelection(); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_session" type='submit' onClick="" class="btn btn-custom btn-lg btn-block">Add Session</button>
						          </div>
						        </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>