<?php 
	$type = $_GET['action'];
	
?>
<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-file"></i>  Add Speaker </h3>
					</div>
					<div class="panel-body">
					    <form id='add_speaker' class="col-md-12" action='/Organizer/Admin/new_session' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
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
						              <textarea name='speaker_bio' class="form-control input-lg" id='speaker_bio' value='' placeholder="Bio"></textarea>
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_li_profile">LinkedIn Profile</label>
						              <input type='text' name='speaker_li_profile' class="form-control input-lg" id='speaker_li_profile' value='' placeholder="LinkedIn Profile">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_tw_profile">Twitter Profile</label>
						              <input type='text' name='speaker_tw_profile' class="form-control input-lg" id='speaker_tw_profile' value='' placeholder="Twitter Profile">
						          </div>
						          <div class="form-group col-md-12">
						          	  <label for="speaker_image">Image</label>
						              <input type='file' name='speaker_image' class="file logo_ex form-control input-lg" id='speaker_image' value='' >
						          </div>
						          <div class="form-group col-md-12">
						              <button name="submit_speaker" type='submit' onClick="" class="btn btn-custom btn-lg btn-block">Add Speaker</button>
						          </div>
						   </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>