<?php
	$hasInfo = true; 
	if($_GET['event'] && $_GET['event'] != $_SESSION['eventId']) {
		$adminAPI->setEventId($_GET['event']);
	}
	
	$_SESSION['eventId'] = isset($_SESSION['eventId']) && $_SESSION['eventId'] != '' ? $_SESSION['eventId'] : $parseAPI->getLatestEvent($_SESSION['userID']);
	//echo $_GET['event'];
	//echo $_SESSION['eventId'];
	//$info = $parseAPI->buildEventInteraction($_SESSION['eventId']); 
	//if($info['beacon_graph'] == '[]' || empty($info)) {
		//$info = $parseAPI->buildDefaultGraph();
		//$hasInfo = false;
	//}
?>
<div class="dashboard" id="dashboardDiv">
	<?= include_once 'left_sidebar.php'; ?>
	<div class="main">
		<div class="main__scroll scrollbar-macosx">	
			<div class="main__cont">
				<?php include_once 'pages/modules/admin/page_handler.php'; ?>
			</div>
		</div>
	</div>
</div>