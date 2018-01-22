<?php 
	$type = $_GET['action'];
	$adminAPI->setEventId($_GET['event']);
	if(isset($_POST['submit_content'])) {
		/* This is will the id of the user */
		//$adminAPI->submitNewSpeaker($_SESSION['userID'], $_POST['event_title'], $_POST['event_description'], $_POST['event_location'], $_POST['event_address'], $_POST['event_city'], $_POST['event_state'], $_POST['event_zip'], $_POST['event_country'], $_POST['event_start'], $_POST['event_end'], $_FILES['event_logo'], $_POST['event_hashtags']);
		$adminAPI->submitNewNotification($_GET['event'], $_POST['content_title'], $_POST['content_description'], $_FILES['pdf'], $_POST['url']);
	}
?>
<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-file"></i>  Add Content </h3>
					</div>
					<div class="panel-body">
					    <form id='add_content' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
						          <div class="form-group col-md-12">
						          	  <label for="content_title">Content Title</label>
						              <input type='text' name='content_title' class="form-control input-lg" id='content_title' value='' placeholder="Content Title">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="content_description">Content Description</label>
						              <textarea name='content_description' class="form-control input-lg" id='content_description' value='' placeholder="Content Description"></textarea>
						          </div>
						          <div class="form-group col-md-8">
						          	  <label for="content_pdf">PDF</label>
						              <input type='file' name='content_pdf' class="file logo_ex form-control input-lg" id='content_pdf' value='' >
						          </div>
						           <div class="form-group col-md-4">
						          	  <label for="content_url">URL</label>
						              <input type='text' name='content_url' class="form-control input-lg" id='url' value='' placeholder="URL">
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
