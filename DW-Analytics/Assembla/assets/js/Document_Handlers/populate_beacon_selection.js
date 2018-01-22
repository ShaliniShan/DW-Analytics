'use strict';
var beacon_options = (function () {
	function initAjax(eventName, $selected) {
		if($selected === 'Organizer' || $selected === 'Trigger') { $("#beacon_options_container").empty(); } 
		else {	
			$.post('/pages/modules/admin/populate_beacon_options.php', {sl :$selected, event: eventName}, function (data) {
				$("#beacon_options_container").empty();
				var $html = "<label for='beacon_options'>For</label><select data-live-search='true' name='beacon_options' class='form-control input-lg selectpicker' id='beacon_options'>";
				$("#beacon_options_container").html($html + data + '</select>');
				$('.selectpicker').selectpicker('refresh');
			});
		}
	} 
	function populateInputFields() {
		var input_val = $("#survey_questions").val();
		var html = '';
		$("#survey_questions_generate").empty();
		for (let i = 1; i <= input_val; i++) {
			html += "<label for='survey_question"+i+"_add'>Question"+i+":</label>"
					+"<input class='form-control input-lg' name='survey_question"+i+"_add' type='text' />";
		}
		$("#survey_questions_generate").html(html).show();
	}
	return {
		init : initAjax,
		populate : populateInputFields
	};
})();  