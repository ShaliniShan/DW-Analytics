var Error_checks = (function () {
	"use strict";
	
	function getEventActiveTab() {
		var $active_tab = $('.nav-tabs .active').text().trim();
		var $formAction = document.add_button_form.action;
		if ($active_tab === 'Speakers') {
			document.getElementById('actab').value='speaker';
			document.add_button_form.action=$formAction + "/add-speaker";
			//$("#activeTab").val('speaker'); //This should have worked if id has been set
		}else if ($active_tab === 'Sessions') {
			document.getElementById('actab').value='session';
			document.add_button_form.action=$formAction + "/add-session";
		} else if ($active_tab === 'Beacons') {
			document.getElementById('actab').value='beacon';
			document.add_button_form.action=$formAction + "/add-beacon";
		} else if ($active_tab === 'Triggered Content') {
			document.getElementById('actab').value='notification';
			document.add_button_form.action=$formAction + "/add-notification";
		} else if ($active_tab === 'Survey Questions') {
			document.getElementById('actab').value='survey';
			document.add_button_form.action=$formAction + "/add-survey";
		} else if ($active_tab === 'Exhibitors') {
			document.getElementById('actab').value='exhibitor';
			document.add_button_form.action=$formAction + "/add-exhibitor";
		} else if ($active_tab === 'Sponsors') {
			document.getElementById('actab').value='sponsor';
			document.add_button_form.action=$formAction + "/add-sponsor";
		} else if ($active_tab === 'Content') {
			document.getElementById('actab').value='content';
			document.add_button_form.action=$formAction + "/add-event";
		} else if ($active_tab === 'Event Info') {
		    document.getElementById('actab').value='event';	
		}
		
		//alert(document.getElementById('actab').value)
	}
	function chooseCorrectErrorContent($class) {
		var retval;
		switch ($class) {
			case 'new-event':
				retval = generateAddEventCheck();
				break;
			case 'new_speaker':
				retval = generateAddSpeakerCheck();
				break;
			case 'new_session':
				retval = generateAddSessionCheck();
				break;
			case 'new_beacon':
				retval = generateAddBeaconCheck();
				break;
			case 'new_exhibitor':
				retval = generateAddExhibitorCheck();
				break;
			case 'new_notification':
				retval = generateAddNotificationCheck();
				break;
			case 'new_survey':
				retval = generateAddSurveyCheck();
				break;
			case 'new_sponsor':
				retval = generateAddSponsorCheck();
				break;
			case 'new_content':
				retval = generateAddContentCheck();
				break;
			
			default:
				console.log("Cannot Choose Correct Class");
				break;
		}
		return retval;
	}

	function checkInjection(checkValue) {
		var isPossibleOfInjection = false;
		var regex = /\W\B/g;
		var check = regex.exec(checkValue);
		isPossibleOfInjection = (!check ? false : true);
		return isPossibleOfInjection;
	}

	/* Generates Error Message for add event */
	function generateAddEventCheck() {
		//$("#submit_event").submit(function (event) {
		    //var $formtitle = document.getElementById('event_title').value;
			var $title = $("#event_title").val().trim(),
			    //$timezone = $("#event_timezone").val().trim(),
				$location = $("#event_location").val().trim(),
				$address1 = $("#event_address1").val().trim(),
				$address2 = $("#event_address2").val().trim(),
				$city = $("#event_city").val().trim(),
				$state = $("#event_state").val().trim(),
				$zip = $("#event_zip").val().trim(),
				$start = $("#event_start").val().trim(),
				$end = $("#event_end").val().trim(),
				$logo = $("#event_logo").val().trim(),
				$hash = $("#event_hashtags").val().trim(),
				$code = $("#event_code").val().trim(),
				$error = '<div class="alert alert-danger alert-dismissable">',
				$formError = "";
			
			var $formAction = document.add_event_name.action;
			
			if ($code === '') {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                "<span class='sr-only'>Error:</span>" +
                " Eventcode cannot be empty<br/>";
			} else {
				/*
				 * validate if code is unique
				 */
				//TODO, as of now, executing ajax call with async false because of
				//onclick attribute of submit button on add_event.php. If there are
				//issues, need to revisit to change the form validation using jquery
				//validation 
				$.ajax({
					type: 'POST',
					url: "/pages/modules/admin/validate_event.php",
					data: {action1: 'isEventCodeUnique', params: [$code]},
					async: false,
					success: function(data) {
						//alert(data);
						//data is the count of number of event rows found for given event code 
						if (data > 0) {
							$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
	                        "<span class='sr-only'>Error:</span>" +
	                        " Event code needs to be unique<br/>";	
						}
					},
					error: function(xhr, status, err) {
						alert(xhr.status);
						alert(xhr.responseText);
						if(status == 'timeout') {
							alert('Ajax took too long');
						}
					}
				});
				
				var $action = '/Organizer/admin/edit/' + $code;
				document.add_event_name.action=$action;
						
			}
			if($title === '') {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Title cannot be empty<br/>";
			} 
			if($location === '') {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Location cannot be empty<br/>";
			} 
			if($address1 === '') {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Address1 cannot be empty<br/>";
			}
			if($city === '') {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " City cannot be empty<br/>";
			} 
			if($state === '') {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " State cannot be empty<br/>";
			} 
			if($zip === '') {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Zip Code cannot be empty<br/>";
			} 
			if($start === '') {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Start Time cannot be empty<br/>";
			} else if(checkInjection($start)) {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Invalid Characters in Start Time<br/>";
			}
			if($end === '') {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " End Time cannot be empty<br/>";
			} else if(checkInjection($end)) {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Invalid Characters in End Time<br/>";
			}
			
			if($formError !== '') {
			   	//event.preventDefault();
				$error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
	            $error += '</div>';
				document.getElementById("error_container").innerHTML = $error;
				return false;
	            
	            //$("#error_container").html($error);
			}
			return true;
			
			
		//});
	}
	
	function generateAddSpeakerCheck() {
		var $name = $("#speaker_name").val().trim(),
		$company = $("#speaker_company").val().trim(),
		$title = $("#speaker_title").val().trim(),
		$error = '<div class="alert alert-danger alert-dismissable">',
		$formError = "";
	if($name === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Name cannot be empty<br/>";
	} 
	if($company === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Company cannot be empty<br/>";
	} 
	if($title === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Title cannot be empty<br/>";
	}
	if($formError !== '') {
	   	//event.preventDefault();
		$error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
        $error += '</div>';
		document.getElementById("error_container_1").innerHTML = $error;
		return false;
        
        //$("#error_container").html($error);
	}
	return true;
		
	}
	
	function generateAddSessionCheck() {
		var $title = $("#session_title").val().trim(),
		$start = $("#session_start").val().trim(),
		$end = $("#session_end").val().trim(),
		$error = '<div class="alert alert-danger alert-dismissable">',
		$formError = "";
	if($title === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Title cannot be empty<br/>";
	} 
	if($start === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Start Time cannot be empty<br/>";
	} else if(checkInjection($start)) {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Invalid Characters in Start Time<br/>";
	}
	if($end === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " End Time cannot be empty<br/>";
	} else if(checkInjection($end)) {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Invalid Characters in End Time<br/>";
	}
	if($formError !== '') {
	   	//event.preventDefault();
		$error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
        $error += '</div>';
		document.getElementById("error_container_2").innerHTML = $error;
		return false;
        
        //$("#error_container").html($error);
	}
	return true;	
	}
	function generateAddExhibitorCheck() {
		var $name = $("#exhibitor_name").val().trim(),
		$website = $("#exhibitor_website").val().trim(),
		$error = '<div class="alert alert-danger alert-dismissable">',
		$formError = "";
	if($name === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Name cannot be empty<br/>";
	} 
	if ($website !== ''){
		var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
		if (!re.test($website)) { 
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
	        "<span class='sr-only'>Error:</span>" +
	        " Invalid URL<br/>";
		   // return false;
		}
		}

	if($formError !== '') {
	   	//event.preventDefault();
		$error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
        $error += '</div>';
		document.getElementById("error_container_3").innerHTML = $error;
		return false;
        
        //$("#error_container").html($error);
	}
	return true;	
	}
	
	
	function generateAddBeaconCheck() {
		var $formError = "";
		var $name = $("#beacon_name").val().trim(),
		$error = '<div class="alert alert-danger alert-dismissable">';
		
		
	if($name === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Beacon Name cannot be empty<br/>";
	} 
		var exhibitor_dropdown = document.getElementById("beacon_exhibitor");
		var exhibitor_ddl_val = exhibitor_dropdown.options[exhibitor_dropdown.selectedIndex].value;
		//alert(exhibitor_ddl_val);
		
		var beaconType_dropdown = document.getElementById("beacon_type");
		var beaconType_ddl_val = beaconType_dropdown.options[beaconType_dropdown.selectedIndex].value;
		
		if (beaconType_ddl_val == 'Exhibitor' && exhibitor_ddl_val === ''){
			//Not selected from drop down
			$formError = "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
            "<span class='sr-only'>Error:</span>" +
            " Please select an exhibitor<br/>";	
		}
		
		/*
		 * validate if any beacons are available for assignment to this event 
		 * or not
		 */
		if ($formError === '') {
		$.ajax({
			type: 'POST',
			url: "/pages/modules/admin/validate_event.php",
			data: {action1: 'isBeaconAvailable'},
			async: false,
			success: function(data) {
				//alert(data);
				//data is the count of number of event rows found for given event code 
				if (data == 0) {
					$formError = "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                    "<span class='sr-only'>Error:</span>" +
                    " We seem to have run out of Beacons. Do not worry. It happens when we are enabling many events at the same time. Please contact the System Administrator to assign a new block'<br/>";	
				}
			},
			error: function(xhr, status, err) {
				alert(xhr.status);
				alert(xhr.responseText);
				if(status == 'timeout') {
					alert('Ajax took too long');
				}
			}
		});
		}
		if($formError !== '') {
			$error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
            $error += '</div>';
			document.getElementById("error_container_4").innerHTML = $error;
			return false;		
		}
		return true;
	}
   
	function generateAddNotificationCheck() {
		var $title = $("#notification_title").val().trim(),
		 $message = $("#notification_message").val().trim(),
		 //$beacon = $("#notification_beacon").val().trim(),
		$error = '<div class="alert alert-danger alert-dismissable">',
		$formError = "";
	if($title === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Title cannot be empty<br/>";
	} 
	if($message === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Message cannot be empty<br/>";
	}
/*	if($beacon === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Beacon cannot be empty<br/>";
	} else if(checkInjection($beacon)) {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Invalid Characters in Beacon<br/>";
	}*/
	if($formError !== '') {
	   	//event.preventDefault();
		$error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
        $error += '</div>';
		document.getElementById("error_container_5").innerHTML = $error;
		return false;
        
        //$("#error_container").html($error);
	}
	return true;	
	}
	
	function generateAddSurveyCheck() {
		var $name = $("#survey_name").val().trim(),
		$session = $("#survey_session").val().trim(),
		$error = '<div class="alert alert-danger alert-dismissable">',
		$formError = "";
	if($name === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Name cannot be empty<br/>";
	}
	if($session === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Session cannot be empty<br/>";
	} 
	if($formError !== '') {
	   	//event.preventDefault();
		$error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
        $error += '</div>';
		document.getElementById("error_container_6").innerHTML = $error;
		return false;
        
        //$("#error_container").html($error);
	}
	return true;	
	}
	
	
	
	function generateAddSponsorCheck() {
		var $name = $("#sponsor_name").val().trim(),
		$website = $("#sponsor_website").val().trim(),
		$error = '<div class="alert alert-danger alert-dismissable">',
		$formError = "";
	if($name === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Name cannot be empty<br/>";
	} 
	if ($website !== ''){
	var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
	if (!re.test($website)) { 
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
        "<span class='sr-only'>Error:</span>" +
        " Invalid URL<br/>";
	   // return false;
	}
	}
	if($formError !== '') {
	   	//event.preventDefault();
		$error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
        $error += '</div>';
		document.getElementById("error_container_7").innerHTML = $error;
		return false;
        
        //$("#error_container").html($error);
	}
	return true;	
	}
	
	function generateAddContentCheck() {
		var $title = $("#content_title").val().trim(),
		$desc = $("#content_description").val().trim(),
		$error = '<div class="alert alert-danger alert-dismissable">',
		$formError = "";
	if($title === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Title cannot be empty<br/>";
	} 
	if($desc === '') {
		$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                   "<span class='sr-only'>Error:</span>" +
                   " Description cannot be empty<br/>";
	} 
	
	if($formError !== '') {
	   	//event.preventDefault();
		$error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
        $error += '</div>';
		document.getElementById("error_container_8").innerHTML = $error;
		return false;
        
        //$("#error_container").html($error);
	}
	return true;	
	}
	
	return {
		automaticCheck : chooseCorrectErrorContent,
		getActiveTab   : getEventActiveTab
	};
})(); 