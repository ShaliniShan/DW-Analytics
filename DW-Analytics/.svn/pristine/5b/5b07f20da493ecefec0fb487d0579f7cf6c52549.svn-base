<?php 
	include_once $_SERVER['DOCUMENT_ROOT'].'/pages/modules/config.php';
	require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
	use Parse\ParseClient;
	ParseClient::initialize($conf['security']['app_id'], $conf['security']['rest_key'], $conf['security']['master_key']);
	ParseClient::setServerURL('http://aws.beepetc.com:1337/parse');
