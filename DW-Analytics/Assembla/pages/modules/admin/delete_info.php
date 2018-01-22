<?php 
	require_once  $_SERVER['DOCUMENT_ROOT'].'/pages/modules/adminParse.php';

	$adminAPi = new AdminParseAPI();
	echo $adminAPi->deleteRow($_POST['id'], $_POST['class']);

 ?>