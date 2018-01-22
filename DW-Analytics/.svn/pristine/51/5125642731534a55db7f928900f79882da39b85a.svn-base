<?php 
	$type = $_GET['action'];
	$adminAPI->setEventId($_GET['event']);
	if(isset($_POST['submit_exhibitor'])) {
		/* This is will the id of the user */
		//$adminAPI->submitNewSpeaker($_SESSION['userID'], $_POST['event_title'], $_POST['event_description'], $_POST['event_location'], $_POST['event_address'], $_POST['event_city'], $_POST['event_state'], $_POST['event_zip'], $_POST['event_country'], $_POST['event_start'], $_POST['event_end'], $_FILES['event_logo'], $_POST['event_hashtags']);
		$adminAPI->submitNewExhibitor($_GET['event'], $_POST['exhibitor_name'], $_POST['exhibitor_profile'], $_POST['booth_number'], $_FILES['logo']);
	}
?>
<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-file"></i>  Add Exhibitor </h3>
					</div>
					<div class="panel-body">
					    <form id='add_exhibitor' class="col-md-12" action='/Organizer/Admin/new_sponsor' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_name">Exhibitor Name</label>
						              <input type='text' name='exhibitor_name' class="form-control input-lg" id='exhibitor_name' value='' placeholder="Exhibitor Name">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_profile">Exhibitor Profile</label>
						              <textarea name='exhibitor_profile' class="form-control input-lg" id='exhibitor_profile' value='' placeholder="Exhibitor Profile"></textarea>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="booth_number">Booth #</label>
						              <input type='text' name='booth_number' class="form-control input-lg" id='booth_number' value='' placeholder="Booth #">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="exhibitor_logo">Logo</label>
						              <input type='file' name='exhibitor_logo' class="file logo_ex form-control input-lg" id='exhibitor_logo' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_exhibitor" type='submit' onClick="" class="btn btn-custom btn-lg btn-block">Add</button>
						          </div>
						   </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
