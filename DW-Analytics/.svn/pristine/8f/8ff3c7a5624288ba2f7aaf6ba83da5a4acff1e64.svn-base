<?php 
	$type = $_GET['event'];
	$home_url = '/Organizer/admin';
    $cancel_url = $home_url."_cancel";
	
?>
<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-file"></i>  Add Event </h3>
					</div>
					<div class="panel-body">
					      
						<form name='add_event_name' id='add_event' class="col-md-12" action='/Organizer/admin/edit/' method='POST' accept-charset='UTF-8' enctype="multipart/form-data" autocomplete="on">
						<div id="error_container"></div>
				          <div class="form-group col-md-8">
				          	  <label for="event_title">Title</label>
				              <input type='text' name='event_title' class="form-control input-lg" id='event_title' value='' placeholder="Title">
				          </div>
				          <div class="form-group col-md-4">
						          	  <label for="event_timezone">Timezone</label>
						              <select data-live-search='true' style="background-color: #5e6870" name='event_timezone[]' class="form-control input-lg selectpicker" id='event_timezone' value='' title="Choose a Timezone">
						              	<?= $adminAPI->buildTimezoneSelection(); ?>
						              </select>
						   </div>
						   <div class="form-group col-md-6">
				          	  <label for="event_code">Event Code</label>
				              <input type='text' name='event_code' class="form-control input-lg" id='event_code' value='' placeholder="Event Code(must be unique)">
				          </div>
				          <div class="form-group col-md-6">
				          	  <label for="event_logo">Logo</label>
				              <input type='file' name='event_logo' class="file logo_ex form-control input-lg" id='event_logo'>
				          </div>
				          <div class="form-group col-md-12">
				          	  <label for="event_location">Location/Venue Name</label>
				              <input type='text' name='event_location' class="form-control input-lg" id='event_location' value='' placeholder="Location">
				          </div>
				          <div class="form-group col-md-6">
				          	  <label for="event_address1">Address 1</label>
				              <input type='text' name='event_address1' class="form-control input-lg" id='event_address1' value='' placeholder="Address 1">
				          </div>
				          <div class="form-group col-md-6">
				          	  <label for="event_address2">Address 2</label>
				              <input type='text' name='event_address2' class="form-control input-lg" id='event_address2' value='' placeholder="Address 2">
				          </div>
				          <div class="form-group col-md-4">
				          	  <label for="event_city">City</label>
				              <input type='text' name='event_city' class="form-control input-lg" id='event_city' value='' placeholder="City">
				          </div>
				          <div class="form-group col-md-4">
				          	  <label for="event_state">State</label>
				              <input type='text' maxlength="2" name='event_state' class="form-control input-lg" id='event_state' value='' placeholder="State">
				          </div>
				          <div class="form-group col-md-4">
				          	  <label for="event_zip">Zip Code</label>
				              <input type='text' maxlength="5" name='event_zip' class="form-control input-lg" id='event_zip' value='' placeholder="01730">
				          </div>
				          <div class="form-group col-md-3">
				          	  <label for="event_start">Start Time</label>
				              <input type='text' name='event_start' class="date_pick form-control input-lg" data-date-orientation='top auto' id='event_start' placeholder="Start Time">
				          </div>
				          <div class="form-group col-md-3">
				          
				          	  <label for="event_end">End Time</label>
				               <input type='text' name='event_end'  class='date_pick form-control input-lg' id='event_end'  placeholder='End Time'>  
				          </div>
				          
				          <div class="form-group col-md-12">
				          	  <label for="event_hashtags">Twitter Hashtags</label>
				              <input type='text' name='event_hashtags' class="form-control input-lg" id='event_hashtags' placeholder="#tag1 #tag2 #tag3">
				          </div>
				          <div class="form-group col-md-12">
				              <button name="submit_event" type='submit' onClick="return Error_checks.automaticCheck('<?=$type?>');" class="btn btn-custom btn-lg ">Add Event</button>
				         		<a id="cancelButton" href="javascript:history.go(-1)" onMouseOver="this.style.color='#0F0'" onMouseOut="this.style.color='#00F'">Cancel</a>
				          </div>
					    </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>