<?php
 	if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
	/**
	  * @version BEEP-1.0
	  * @var Array['app_id'] YCzMrwgIZqFcpt9gc1erWrwTnO6yv3pmFUOJ6OT5
	  * @var Array['rest_key'] PixDMyMgm1lGsa3FDMefCLEfBq3J93hsg4QYq4Jy
	  * @var Array['master_key'] Ml8ZoLEUf4fq3CnLtY6Y3J6AugYYwUycxiFV4muQ
	  *	@version BEEP-DEV
	  * @var Array['app_id'] 2xdxNeM9YFoUIac6vo2t11duMvuqfbhN44jf0C2D
	  * @var Array['rest_key'] ZkkvuI2lZEn8YTUxTDMU7PKAuMD5Xx9hiaLNVWR6
	  * @var Array['master_key'] 6lfkVW3TPEyxTAIqM3XclOHrtWWQt7POphgy7yVc
	  */
	$conf['security']['app_id'] = "YCzMrwgIZqFcpt9gc1erWrwTnO6yv3pmFUOJ6OT5"; //production beep key
	//$conf['security']['app_id'] = "APP_ID_BEEP_1_0";
 	
	$conf['security']['rest_key'] = "PixDMyMgm1lGsa3FDMefCLEfBq3J93hsg4QYq4Jy";
	
	$conf['security']['master_key'] = "Ml8ZoLEUf4fq3CnLtY6Y3J6AugYYwUycxiFV4muQ"; //prod master key
	//$conf['security']['master_key'] = "MASTER_KEY_BEEP_1_0";
	//$company_name = "Digital Wavefront";
	//$formatted_company_name = "<strong>Digital Wavefront</strong><br>".
	$company_name = "Beantown Beacons";
	$formatted_company_name = "<strong>Beantown Beacons</strong><br>".
		   "BEEP - CMS & Attendee Analytics";
	$tcrmimg = '/assets/img';
	$logo = "$tcrmimg/beep-logo.png";
	$formatted_logo_top = "<img src='$logo' width='45'>";
	$loginHeader = "<p style='padding-top:15px;'><img src='$logo' width='200'></p>";
	$loginHeader .= "<h1>$formatted_company_name<br><small>Please Login</small></h1>";
	$app_version = "0.0.1";
	$company_number = "781-242-2423";
	$company_email = "hello&#64;beepetc.com";
