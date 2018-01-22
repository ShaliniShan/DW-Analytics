var registerModal = (function () {
	$('#register_submit').submit(function (event) {
		var $name = $("#register_name").val().trim(),
			$company = $("#register_company").val().trim(),
			$email = $("#register_email").val().trim(),
			$phone = $("#register_phone").val().trim(),
			$username = $("#register_username").val().trim(),
			$pass1 = $("#register_pass1").val().trim(),
			$pass2 = $("#register_pass2").val().trim(),
			$code = $("#register_code").val().trim(),
			$file = $("#register_logo").val().trim(),
			$error = '<div class="alert alert-danger alert-dismissable">',
			$formError = '';
		
		function checkInjection(checkValue) {
			var isPossibleOfInjection = false;
			var regex = /\W\B/g;
			var check = regex.exec(checkValue);
			isPossibleOfInjection = (!check ? false : true);
			return isPossibleOfInjection;
		} 

		if($name === '') {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Name cannot be empty<br/>";
		} else if(checkInjection($name)) {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Illegal characters in Name<br/>";
		}

		if($company === '') {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Company cannot be empty<br/>";
		} else if(checkInjection($company)) {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Illegal characters in Company<br/>";
		}

		if($email === '') {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Email cannot be empty<br/>";
		} 

		if($phone === '') {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Phone cannot be empty<br/>";
		}

		if($username === '') {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Username cannot be empty<br/>";
		} else if(checkInjection($username)) {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Illegal characters in Username<br/>";
		}

		if($pass1 === '' || $pass2 === '') {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Password cannot be empty<br/>";
		} else if ($pass1 !== $pass2) {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Passwords do not match<br/>";
		} else if(checkInjection($pass1) || checkInjection($pass2)) {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Illegal characters in Password<br/>";
		}

		if($code === '') {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Code cannot be empty<br/>";
		} else if(checkInjection($code)) {
				$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " Illegal characters in Code<br/>";
		}

		if($file === '') {
			$formError += "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>" +
                           "<span class='sr-only'>Error:</span>" +
                           " A file needs to be uploaded<br/>";
		}
		if($formError !== ""){
            event.preventDefault();
            $error += ("<button type='button' class='close' data-dismiss='alert'>x</button>" + $formError);
            $error += '</div>';
            $("#register_error").html($error);
        }
	});
}); 