<?php 
	$type = $_GET['action'];
	$adminAPI->setEventId($_GET['event']);
	if(isset($_POST['submit_notification'])) {
		/* This is will the id of the user */
		//$adminAPI->submitNewSpeaker($_SESSION['userID'], $_POST['event_title'], $_POST['event_description'], $_POST['event_location'], $_POST['event_address'], $_POST['event_city'], $_POST['event_state'], $_POST['event_zip'], $_POST['event_country'], $_POST['event_start'], $_POST['event_end'], $_FILES['event_logo'], $_POST['event_hashtags']);
		$adminAPI->submitNewNotification($_GET['event'], $_POST['notification_title'], $_POST['notification_message'], $_FILES['pdf'], $_POST['url'], $_POST['trigger_notification_after'], $_POST['beacon']);
	}
?>
<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-file"></i>  Add Notification </h3>
					</div>
					<div class="panel-body">
					    <form id='add_exhibitor' class="col-md-12" action='/Organizer/Admin/new_content' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div class="form-group col-md-12">
						          	  <label for="notification_title">Notification Title</label>
						              <input type='text' name='notification_title' class="form-control input-lg" id='notification_title' value='' placeholder="Notification Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_message">Notification Message</label>
						              <textarea name='notification_message' class="form-control input-lg" id='notification_message' value='' placeholder="Notification Message"></textarea>
						          </div>
						          <div class="form-group col-md-8">
						          	  <label for="notification_pdf">PDF</label>
						              <input type='file' name='notification_pdf' class="file logo_ex form-control input-lg" id='notification_pdf' value='' >
						          </div>
						           <div class="form-group col-md-4">
						          	  <label for="url">URL</label>
						              <input type='text' name='url' class="form-control input-lg" id='url' value='' placeholder="URL">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="trigger_notification_after">Trigger Notification After</label>
						              <select data-live-search='true' onChange="beacon_options.init('<?= $_GET['event']; ?>', this.value);" name='trigger_notification_after' class="form-control input-lg selectpicker" id='trigger_notification_after'>
						              	<option selected disabled>Choose a option</option>
						              	<option value="1">1 minute</option>
						              	<option value="2">2 minutes</option>
						                <option value="3">3 minutes</option>
						                <option value="4">4 minutes</option>
						              	<option value="5">5 minutes</option>
						                <option value="6">6 minutes</option>
						                <option value="7">7 minute</option>
						              	<option value="8">8 minutes</option>
						                <option value="9">9 minutes</option>
						                <option value="10">10 minutes</option>
						              	<option value="15">15 minutes</option>
						                <option value="30">30 minutes</option>
                                        <option value="45">45 minute</option>
						              	<option value="60">60 minutes</option>
						                <option value="90">90 minutes</option>					              
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="notification_beacon">Beacon</label>
						              <select data-live-search='true' style="background-color: #5e6870" name='notification_beacon[]' class="form-control input-lg selectpicker" id='notification_beacon' value='' multiple title="Choose a Beacon">
						              	<?= $adminAPI->buildBeaconDropDown(); ?>
						              </select>
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_notification" type='submit' onClick="" class="btn btn-custom btn-lg btn-block">Add</button>
						          </div>
						   </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
