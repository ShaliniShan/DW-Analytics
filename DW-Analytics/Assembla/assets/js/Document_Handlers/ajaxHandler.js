var populateTable = (function () {
	var actual_length = 0;

	/**
	 * Builds the Datatable based on the specific info clicked
	 * @param  {String} $event The eventId of the chosen graph
	 * @param  {String} $title The 
	 * @param  {[type]} $type  [description]
	 * @param  {[type]} $extra [description]
	 * @return {[type]}        [description]
	 */
	function buildTableOrg($event, $title, $type, $extra) {
		$.ajax({
			type: 'POST',
			url: "/pages/specific/organizer_list/organizer_table.php",
			data: {id : $event, tb : $title, tm : $type, ex : $extra },
			timeout: 10000,
			success: function (data) {
				$("#ajax_table").css("display", "block");
				var table = $("#records").dataTable();	
				
				
				table.fnClearTable();
				if (data == '' || data == null) { console.log('No Data returned'); return; }
				//alert(data);
				var regex = /<\/tr>/g;
				var lastIndexArray = [];
				var len = data.match(regex).length;
				publicAPI.getLength = $("#records")[0].tHead.childElementCount;
				publicAPI.getThird = $("#records")[0].clientWidth/3;
				for (var i = 0; i < len; i++) {
					var	sub = regex.exec(data);
					lastIndexArray.push(regex.lastIndex);
					if (lastIndexArray.length == 1)
						table.DataTable().row.add($(data.substring(0, lastIndexArray[i]))).draw();
					else if (lastIndexArray.length > 1) {
						table.DataTable().row.add($(data.substring(lastIndexArray[i-1], lastIndexArray[i]))).draw();
					} else {
						console.log("Cannot add row");
					}
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

	function deleteInfo($id, $table, $row) {
		$.post("/pages/modules/admin/delete_info.php", {id : $id, class : $table}, function (data) {
			$row.style.display = "none";
		});
	}
	
	function deleteIntegrityCheck($id, $table) {
		$checkResult = null;
		//table id for speaker
		if ($table == 'ba_tb_011') {
			$.ajax({
				type: 'POST',
				url: "/pages/modules/admin/validate_event.php",
				data: {action1: 'isSpeakerWithSession', params: [$id, $table]},
				async: false,
				success: function(data) {
					//data is the true/false if speaker is associated with session or not
					data.trim();
					if (parseInt(data) > 0) {
						$checkResult = "Cannot delete Speaker. There's an active Session associated with this Speaker.";	
					}
				},
				error: function(xhr, status, err) {
					alert(xhr.status);
					alert(xhr.responseText);
					$checkResult = "Error in validing speaker delete request";
					if(status == 'timeout') {
						alert('Ajax took too long');
					}
				}
			});
		}
		else if ($table == 'ba_tb_000') {
			$.ajax({
				type: 'POST',
				url: "/pages/modules/admin/validate_event.php",
				data: {action1: 'isBeaconWithNotification', params: [$id, $table]},
				async: false,
				success: function(data) {
					//alert(data);
					//data is the true/false if speaker is associated with session or not 
					data.trim();
					if (parseInt(data) > 0) {
						$checkResult = "Cannot delete Beacon. There's an active Notification associated with this Beacon.";	
					}
				},
				error: function(xhr, status, err) {
					alert(xhr.status);
					alert(xhr.responseText);
					$checkResult = "Error in validating beacon delete request";
					if(status == 'timeout') {
						alert('Ajax took too long');
					}
				}
			});
		}
		return $checkResult;
	}
	
	function getBeacons($event) {
		console.log("Krsna-Radha", $event);
		$.post("/pages/modules/admin/getBeacons.php", {id: $event}, function(data) {
			console.log(data.text);
			alert(data.text);
		 }, 'json');
		
		/*$.ajax({
			type: 'POST',
			url: "/pages/modules/admin/getBeacons.php",
			data: {id : $event},
			timeout: 10000,
			success: function (data) {
				console.log(data.text);
				alert(data.text);
			},
			error: function(request, status, err) {
				if(status == 'timeout') {
					alert('Ajax took too long');
				} else {
					console.log(err.getMessage());
				}
			},
			'json'
		});*/
	}
	/**
	 * @return Object      for the iife to call interanl methods
	 * @method createTable creates table based on ajax call
	 * @method deleteRow   removes specific row and ajax call deletes from backend
	 * @static getLength   returns the length of entries in the table
	 */
	var publicAPI = {
		createTable : buildTableOrg,
		deleteRow : deleteInfo,
		deleteCheckIntegrity : deleteIntegrityCheck,
		getLength : actual_length,
		getActivity : getBeacons
	};
	return publicAPI;
})();
