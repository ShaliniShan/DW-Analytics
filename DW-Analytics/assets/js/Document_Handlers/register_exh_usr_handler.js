var registerExhUsrModal = (function validator() {
	$('#register_submit_form').submit(function (event) {
		var $username = $("#register_username").val().trim(),
			$pass1 = $("#register_pass1").val().trim(),
			$pass2 = $("#register_pass2").val().trim(),
			$error = '<div class="alert alert-danger alert-dismissable">',
			$formError = '';
		
		function checkInjection(checkValue) {
			var isPossibleOfInjection = false;
			var regex = /\W\B/g;
			var check = regex.exec(checkValue);
			isPossibleOfInjection = (!check ? false : true);
			return isPossibleOfInjection;
		} 
		
		function checkCredentials(checkVal) {
			var checkUsr = false;
			var regex = /^[a-zA-Z0-9]{6,}$/;
			var check = regex.exec(checkVal);
			checkUsr = (!check ? false : true);
			return checkUsr;
		}
		var isValidUsername = checkCredentials($username);
		var isValidPassword = checkCredentials($pass1);
		if($username === '') {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Username cannot be empty<br/>";
			
		} else if (!isValidUsername){
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
			"<span class='sr-only'>Error:</span>" +
			" Username should have a minimum of 6 characters<br/>";
		}

		if($pass1 === '' || $pass2 === '') {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Password cannot be empty<br/>";
		} else if ($pass1 !== $pass2) {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Passwords do not match<br/>";
		} else if(!isValidPassword){
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
			"<span class='sr-only'>Error:</span>" +
			" Password should have a minimum of 6 characters<br/>";
		}
		if($formError !== ""){
            event.preventDefault();
            $error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
            $error += '</div>';
            $("#register_error").html($error);
        }
	});
}); 