<?php 
	$adminAPI->setEventId($_GET['event']);
	$parseAPI->eventId = $adminAPI->getEventId($_GET['event']);
	if(isset($_POST['update_event'])) {
		if(isset($_POST['update_title'])) {
			$adminAPI->updateSessionInfo($_GET['specific'], $_POST['update_title'], $_POST['update_room'], $_POST['update_speaker'], $_POST['update_startTime'], $_POST['update_endTime'], $_POST['update_details']);
		} else if(isset($_POST['speaker_firstName'])) {
			$questions_array = array();
			for($i = 1; $i <= count($_POST['question_amount']); $i++) {
				array_push($questions_array, $_POST['speaker_question'.$i]);
			}
			$adminAPI->updateSpeakerInfo($_GET['specific'], $_POST['speaker_firstName'], $_POST['speaker_lastName'], $_POST['speaker_company'], $_POST['speaker_linkedIn'], $_POST['speaker_twitter'], $_FILES['speaker_image'], $_POST['speaker_details'], $questions_array);
		} else if(isset($_POST['exhibitor_name'])) {
			$adminAPI->updateExhibitorInfo($_GET['specific'], $_POST['exhibitor_name'], $_POST['exhibitor_summary'], $_FILES['exhibitor_logo']);
		} else if(isset($_POST['not_title'])) {
			$adminAPI->updateNotificationInfo($_GET['specific'], $_POST['not_title'], $_POST['not_message'], $_FILES['not_pdf'], $_POST['not_url'], $_POST['not_time'], $_POST['not_beacon']);
		} else if(isset($_POST['survey_title'])) {
			$pos_array = array();
			$ques_array = array();
			for($i = 1; $i <= $_POST['number_of_ques']; $i++) { 
				if($i+1 > $_POST['number_of_ques']) {
					array_push($pos_array, $_POST[$i]);
				}	
				array_push($pos_array, $_POST[$i.'&']);
				array_push($ques_array, $_POST['survey_question'.$i]);
			}
			if(isset($_POST['survey_questions_add'])) {
				$added_questions_array = array();
				for ($i=1; $i <= $_POST['survey_questions_add'] ; $i++) { 
					array_push($added_questions_array, $_POST['survey_question'.$i.'_add']);
				}
			} else {
				$added_questions_array = NULL;
			}
			$adminAPI->updateSurveyInfo($_GET['specific'], $pos_array, $ques_array, $_POST['survey_title'], $_POST['survey_start'], $_POST['survey_session'], $_POST['survey_speaker'], $added_questions_array);
		} else if(isset($_POST['beacon_name'])) {
			$adminAPI->updateBeaconInfo($_GET['specific'], $_POST['beacon_name'], $_POST['beacon_company']);
		} else if(isset($_POST['event_name'])) {
			$adminAPI->updateEventInfo($_POST['event_name'], $_FILES['event_logo'], $_POST['event_startDate'], $_POST['event_endDate'], $_POST['event_address'], $_POST['event_city'], $_POST['event_location'], $_POST['event_triggerBeacons'], $_POST['event_twitterTags'], $_POST['event_code'], $_POST['event_orgCode']);
		}
	}
	if(isset($_POST['number_of_ques'])) {
		for($i = 1; $i <= $_POST['number_of_ques']; $i++) { 
			if(isset($_POST['delete_node'.$i])) {
				$adminAPI->deleteRow(array('specific' => $_GET['specific'], 'pos' => $i), $_GET['class']);
			}
		}
	}
 ?>
<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-plus-square-o"></i> Edit Event - <?= $_GET['event'] ?></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<form id='edit_event' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div id="error_container"></div>
								<?= $adminAPI->chooseCorrectEditFill($_GET['class'], $_GET['specific']); ?>
								<?php if($_GET['class'] == 'ba_tb_012' || $_GET['class'] == 'ba_tb_011') {?>
								<div style="clear:both;">
									<button id="button_add_show" onClick="javascript:$('#generate_questions').show();$(this).hide();$('#survey_questions_add').val(3);" type="button" class="btn btn-info">Add Questions?</button>
									<div id="generate_questions" style="display:none;" class="form-group col-md-4">
						          	  <label for="survey_questions_add">How many Questions?</label>
						              <input type='number' name='survey_questions_add' class="form-control input-lg" id='survey_questions_add' value='' placeholder="">
						              <button type="button" class='btn btn-custom' onClick="beacon_options.populate()">Add Questions</button>
						              <button type="button" class='btn btn-default' onClick="javascript:$('#button_add_show').show();$('#generate_questions').hide();$('#survey_questions_generate').hide();$('#survey_questions_add').val('');">Cancel</button>
						            </div>
							        <div id="survey_questions_generate" class="form-group col-md-6"></div>
							        <?php } ?>
									<div style="clear:both;" class="form-group col-md-6">
										<button type="submit" name="update_event" class="btn btn-custom">Update</button>
										<button onClick="javascript:history.go(-1);" class="btn btn-default">Cancel</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>