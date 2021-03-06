<?php
	include_once('pages/modules/admin/class.phpmailer.php');
	include_once("pages/modules/parseAPI.php");
	include_once("pages/modules/adminParse.php");
	$parseAPI = new ParseAPI();
	$adminAPI = new AdminParseAPI();
 ?>
<!DOCTYPE html>
<html lang="en">
<script src="https://use.fontawesome.com/51e0c88837.js"></script>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
	<title><?= $pageTitle; ?></title>
	<!-- BOOTSTRAP -->
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap/css/bootstrap.min.css">
	<!-- FONT AWESOME -->
    <!--  <link rel="stylesheet" href="/assets/css/font-awesome/css/font-awesome.min.css"> -->
     <!-- DATATABLES -->
    <link rel="stylesheet" href="/assets/css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">
	<!-- DATETIMEPICKER -->
	<link rel="stylesheet" type="text/css" href="/assets/css/jquery.datetimepicker.css">
	<!-- ADMINLTE CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/AdminLTE/AdminLTE.min.css">
	<?php if($acl->getUserPerm() == 1) {
		// ADMIN THEMES
		echo '<link rel="stylesheet" type="text/css" href="/assets/css/jquery.scrollbar.css">';
		echo '<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.css">';
		//echo '<link rel="stylesheet" type="text/css" href="/assets/css/timepicker.css">';
		echo '<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap_fileinput.css">';
		echo '<link rel="stylesheet" type="text/css" href="/assets/css/ion.rangeSlider.css">';
		echo '<link rel="stylesheet" type="text/css" href="/assets/css/ion.rangeSlider.skinFlat.css">';
		echo '<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap/css/bootstrap.switch.min.css">';
		echo '<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap/css/bootstrap.select.min.css">';
		echo '<link rel="stylesheet" type="text/css" href="/assets/css/dark.min.css">';
		echo '<link rel="stylesheet" type="text/css" href="/assets/css/barGraphAdmin.css">';
		echo '<link rel="stylesheet" type="text/css" href="/assets/css/admin.css">';
		} else {
			echo '<link rel="stylesheet" type="text/css" href="/assets/css/bargraph.css">';
			echo '<link rel="stylesheet" type="text/css" href="/assets/css/custom.css">';
		}
			?>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
