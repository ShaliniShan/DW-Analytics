<?php 
  // ini_set("display_errors", true);
	 require_once("pages/modules/config.php");
	 include_once('pages/modules/authen-head.php');
  
	/*if(isset($_POST['submit'])) {
	   if($auth->Login()) {
	   	$auth->RedirectToURL("/index.php");
	   } else {
      $error = '<div class="alert alert-danger alert-dismissable" id="error">';
	    $error .= "<button type='button' class='close' data-dismiss='alert'>x</button>";
	    $error .= $auth->getErrorMessage() . '</div>';
	   }
	}*/
		if(isset($_POST['register_exh_usr_submit'])) {
			$error_submit = '<div class="alert alert-danger alert-dismissable" id="error">';
			echo var_dump($auth);
			$auth->submitNewExhibitorUser($_POST['register_name'], $_POST['register_company'], $_POST['register_username'], $_POST['register_pass1'], $_POST['register_pass2'], $_FILES['register_logo']);
		    echo var_dump($auth);
			$auth->RedirectToURL("/Login/");
		}
	 	
  $pageTitle = "Registration | BEEP";
?>
<!DOCTYPE html>
<html lang="en">
<script src="https://use.fontawesome.com/51e0c88837.js"></script>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="initial-scale=1.0">
  <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
  <title><?= $pageTitle; ?></title>
  <!-- BOOTSTRAP -->
  <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap/css/bootstrap.min.css">
  <!-- FONT AWESOME -->
  <!--  <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome/css/font-awesome.min.css">-->
  <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap_fileinput.css">
  <!-- ADMINLTE -->
  <link rel="stylesheet" type="text/css" href="/assets/css/AdminLTE/AdminLTE.min.css">
  <!-- CUSTOM CSS -->
  <link rel="stylesheet" type="text/css" href="/assets/css/login.css">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login_background">
<div class="container darktext">
	<div class="page-header" style="text-align:center;">
        <?= $loginHeader; ?>
  </div>
  <div id="error-container"><?= empty($error) ? '' : $error; ?></div>
	<form id='register_submit_form' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
          <p class="lead">Welcome! Please Sign up to access your account</p>
          <div id="register_error"></div>
          <div class="form-group col-md-12">
          	  <label for="register_name">Name</label>
              <input type='text' name='register_name' class="form-control input-lg" id='register_name' value='' placeholder="Name">
          </div>
          <div class="form-group col-md-12">
          	  <label for="register_company">Company</label>
              <input type='text' name='register_company' class="form-control input-lg" id='register_company' value='' placeholder="Company">
          </div>
          <div class="form-group col-md-12">
          	  <label for="register_username">Username</label>
              <input type='text' name='register_username' class="form-control input-lg" id='register_username' value='' placeholder="Username">
          </div>
          <div class="form-group col-md-12">
          	  <label for="register_pass1">Create Password</label>
              <input type='password' name='register_pass1' class="form-control input-lg" id='register_pass1' value='' placeholder="Password">
          </div>
          <div class="form-group col-md-12">
          	  <label for="register_pass2">Confirm Password</label>
              <input type='password' name='register_pass2' class="form-control input-lg" id='register_pass2' value='' placeholder="Confirm Password">
          </div>
          <div class="form-group col-md-12">
          	  <label for="register_logo">Logo</label>
              <input type='file' class="file" name='register_logo' class="form-control input-lg" id='register_logo'>
          </div>
          <div class="form-group col-md-12">
              <button name="register_exh_usr_submit" type='submit' onClick="registerExhUsrModal();" class="btn btn-custom btn-lg btn-block">Register</button>
          </div>
        </form>
</div>
<!-- JQUERY AND BOOTSTRAP JAVASCRIPT -->
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/css/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/bootstrap_input.js"></script>
<script type="text/javascript" src="/assets/js/Document_Handlers/authenticator.js"></script>
<script type="text/javascript" src="/assets/js/Document_Handlers/register_exh_usr_handler.js"></script>
 <script>
  // with plugin options
  $("#register_logo").fileinput({'showUpload':false, 'previewFileType':'any', 'showPreview' : false});
</script>
</body>
</html>

