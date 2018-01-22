<?php 
	$type = $_GET['action'];
	$adminAPI->setEventId($_GET['event']);
	if(isset($_POST['submit_sponsor'])) {
		/* This is will the id of the user */
		//$adminAPI->submitNewSpeaker($_SESSION['userID'], $_POST['event_title'], $_POST['event_description'], $_POST['event_location'], $_POST['event_address'], $_POST['event_city'], $_POST['event_state'], $_POST['event_zip'], $_POST['event_country'], $_POST['event_start'], $_POST['event_end'], $_FILES['event_logo'], $_POST['event_hashtags']);
		$adminAPI->submitNewSponsor($_GET['event'], $_POST['sponsor_name'], $_POST['sponsor_profile'], $_POST['level'], $_FILES['logo']);
	}
?>
<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-file"></i>  Add Sponsor </h3>
					</div>
					<div class="panel-body">
					    <form id='add_sponsor' class="col-md-12" action='/Organizer/Admin/new_beacon' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_name">Sponsor Name</label>
						              <input type='text' name='sponsor_name' class="form-control input-lg" id='sponsor_name' value='' placeholder="Sponsor Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_profile">Sponsor Profile</label>
						              <textarea name='sponsor_profile' class="form-control input-lg" id='sponsor_profile' value='' placeholder="Sponsor Profile"></textarea>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="level">Level</label>
						              <input type='text' name='level' class="form-control input-lg" id='level' value='' placeholder="Level">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="sponsor_logo">Logo</label>
						              <input type='file' name='sponsor_logo' class="file logo_ex form-control input-lg" id='sponsor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_sponsor" type='submit' onClick="" class="btn btn-custom btn-lg btn-block">Add</button>
						          </div>
						   </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
