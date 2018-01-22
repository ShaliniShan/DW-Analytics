<?php 
	$type = $_GET['action'];
	$adminAPI->setEventId($_GET['event']);
	if(isset($_POST['submit_beacon'])) {
		/* This is will the id of the user */
		//$adminAPI->submitNewSpeaker($_SESSION['userID'], $_POST['event_title'], $_POST['event_description'], $_POST['event_location'], $_POST['event_address'], $_POST['event_city'], $_POST['event_state'], $_POST['event_zip'], $_POST['event_country'], $_POST['event_start'], $_POST['event_end'], $_FILES['event_logo'], $_POST['event_hashtags']);
		$adminAPI->submitNewBeacon($_GET['event'], $_POST['beacon_name'], $_POST['beacon_type']);
	}
	$categories[] = array("Organizer","Session","Exhibitor");
	if($categories == "Exhibitor")
	$subcats[] = $adminAPI->buildExhibitorAtEvent($event);
	 else if ($categories == "Session")
	$subcats = $adminAPI->buildSessionsAtEvent($event);
	
?>

 <script type='text/javascript'>
      <?php
        echo "var categories = $jsonCats; \n";
        echo "var subcats = $jsonSubCats; \n";
      ?>
      function loadCategories(){
        var select = document.getElementById("categoriesSelect");
        select.onchange = updateSubCats;
        for(var i = 0; i < categories.length; i++){
          select.options[i] = new Option(categories[i].val,categories[i].id);          
        }
      }
      function updateSubCats(){
        var catSelect = this;
        var catid = this.value;
        var subcatSelect = document.getElementById("subcatsSelect");
        subcatSelect.options.length = 0; //delete all options if any present
        for(var i = 0; i < subcats[catid].length; i++){
          subcatSelect.options[i] = new Option(subcats[catid][i].val,subcats[catid][i].id);
        }
      }
    </script>

<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-file"></i>  Add Beacon </h3>
					</div>
					<div class="panel-body">
					    <form id='add_beacon' class="col-md-12" action='/Organizer/Admin/new_notification' method='POST' accept-charset='UTF-8'  onload='loadCategories()' enctype="multipart/form-data">
						          <div class="form-group col-md-12">
						          	  <label for="beacon_name">Beacon Name</label>
						              <input type='text' name='beacon_name' class="form-control input-lg" id='beacon_name' value='' placeholder="Beacon Name">
						          </div>
						         
						          <div class="form-group col-md-12">
						          	  <label for="beacon_type">Beacon Type</label>
						              <select data-live-search='true' onChange="beacon_options.init('<?= $_GET['event']; ?>', this.value);" name='beacon_type' class="form-control input-lg selectpicker" id='categoriesSelect'>
						              	<option selected disabled>Choose a type</option>
						              	<option value="Organizer">Organizer</option>
						              	<option value="Exhibitor">Exhibitor</option>
						              	<option value="Session">Session</option>						             
						              </select>
						              
						             <?php if(isset($_POST['submit'])){
											$selected_val = $_POST['beacon_type'];
									if ($selected_val == 'Exhibitor')	 
						              <label for="exhibitors">Exhibitors</label>
						              <select data-live-search='true' style="background-color: #5e6870" name='exhibitors[]' class="form-control input-lg selectpicker" id='subCatsSelect' value='' multiple title="Choose a Exhibitor">
						              	<?= $adminAPI->buildExhibitorAtEvent($event); ?>					             
						              </select>
						             else if ($selected_val == 'Session')
						              <label for="sessions">Sessions</label>
						              <select data-live-search='true' style="background-color: #5e6870" name='sessions[]' class="form-control input-lg selectpicker" id='subCatsSelect' value='' multiple title="Choose a Session">
						              	<?= $adminAPI->buildSessionsAtEvent($event); ?>					             
						              </select>
						          </div>
						   
						          
						          <div class="form-group col-md-12">
						          	  <label for="beacon_sensitivity">Sensitivity/Trigger Radius</label>
						              <select data-live-search='true' onChange="beacon_options.init('<?= $_GET['event']; ?>', this.value);" name='beacon_sensitivity' class="form-control input-lg selectpicker" id='beacon_sensitivity'>
						              	<option selected disabled>Choose a type</option>
						              	<option value="5">5ft</option>
						              	<option value="10">10ft</option>
						                <option value="20">20ft</option>
						                <option value="50">50ft</option>
						              	<option value="100">100ft</option>
						                <option value="150">150ft</option>
						              </select>
						          </div>
						          
						          <div class="form-group col-md-12">
						              <button name="submit_beacon" type='submit' onClick="" class="btn btn-custom btn-lg btn-block">Add</button>
						          </div>
						   </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
