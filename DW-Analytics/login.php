<?php 
  // ini_set("display_errors", true);
	 require_once("pages/modules/config.php");
	 include_once('pages/modules/authen-head.php');
  if(isset($_GET['logout'])) {
    if($_GET['logout'] == 1) {
      $auth->Logout();
      $error = '<div class="alert alert-success alert-dismissable" id="error">';
      $error .= "<button type='button' class='close' data-dismiss='alert'>x</button>";
      $error .= 'Logout Successful'.'</div>';
    }
  } 
	if(isset($_POST['submit'])) {
	   if($auth->Login()) {
	   	$auth->RedirectToURL("/index.php");
	   } else {
      $error = '<div class="alert alert-danger alert-dismissable" id="error">';
	    $error .= "<button type='button' class='close' data-dismiss='alert'>x</button>";
	    $error .= $auth->getErrorMessage() . '</div>';
	   }
	}
	if(isset($_POST['register_submit'])) {
		$error_submit = '<div class="alert alert-danger alert-dismissable" id="error">';
		$auth->submitNewUser($_POST['register_name'], $_POST['register_company'], $_POST['register_email'], $_POST['register_phone'], $_POST['register_username'], $_POST['register_pass1'], $_POST['register_pass2'], $_FILES['register_logo']);
	}
  $pageTitle = "Login | BEEP";
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
	<form id='loginForm' class="col-md-12 center" action='' name="login_form" method='post' accept-charset='UTF-8'>
		<div class="form-group has-feedback">
          <input type='text' name='username' class="form-control" id='username' placeholder="Username" />
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
      	</div>
      	<div class="form-group has-feedback">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password" />
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      	</div>
      	<div class="col-xs-4 pull-right">
          <button name="submit" id="submit" onClick="authenticator();" type="submit" class="btn btn-custom btn-block btn-flat">Sign In</button>
        </div><!-- /.col -->
        <div class="col-xs-8">
	        <!--  <div class="form-group">
	          <span><a data-toggle="modal" data-target="#register">Register</a></span>
	      	</div> -->
	        <div class="form-group">
	          <span><a data-toggle="modal" data-target="#forgot">Forgot Password?</a></span>
	      	</div>
      	</div>
	</form>
</div>
<!-- JQUERY AND BOOTSTRAP JAVASCRIPT -->
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/css/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/bootstrap_input.js"></script>
<script type="text/javascript" src="/assets/js/Document_Handlers/authenticator.js"></script>
<script type="text/javascript" src="/assets/js/Document_Handlers/register_handler.js"></script>
<!-- Help Modal -->
<div id="forgot" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Forgot Password?</h4>
      </div>
      <div class="modal-body">
        <form id='resetreq' class="col-md-12" action='' method='post' accept-charset='UTF-8'>
          <p>If you forgot your password, enter the email address associated with your account to reset it. A password reset link will be sent to that account.</p>
          <input type='hidden' name='submitted_pass' id='submitted_pass' value='1'/>
          <div class="form-group">
              <input type='text' name='email' class="form-control input-lg" id='email' value='' placeholder="Email Address" />
              <span id='resetreq_email_errorloc' class='error'></span>
          </div>
          <div class="form-group">
              <button name='email_submit' type='submit' class="btn btn-custom btn-lg btn-block">Reset Password</button>
          </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- Resister Modal -->
<div id="register" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Beep Analytics Registration</h4>
      </div>
      <div class="modal-body">
        <form id='register_submit_form' class="col-md-12" action='' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
          <p class="lead">Thank you for choosing BEEP to power your events!</p>
          <div id="register_error"></div>
          <div class="form-group col-md-12">
          	  <label for="register_name">Name</label>
              <input type='text' name='register_name' class="form-control input-lg" id='register_name' value='' placeholder="Name">
          </div>
          <div class="form-group col-md-12">
          	  <label for="register_company">Company</label>
              <input type='text' name='register_company' class="form-control input-lg" id='register_company' value='' placeholder="Company">
          </div>
          <div class="form-group col-md-6">
          	  <label for="register_email">Email</label>
              <input type='email' name='register_email' class="form-control input-lg" id='register_email' value='' placeholder="example@dw.com">
          </div>
          <div class="form-group col-md-6">
          	  <label for="register_phone">Phone</label>
              <input type='phone' name='register_phone' class="form-control input-lg" id='register_phone' value='' placeholder="999-111-2222">
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
              <button name="register_submit" type='submit' onClick="registerModal();" class="btn btn-custom btn-lg btn-block">Register</button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
 </div>
 <script>
  // with plugin options
  $("#register_logo").fileinput({'showUpload':false, 'previewFileType':'any', 'showPreview' : false});
</script>
</body>
</html>
