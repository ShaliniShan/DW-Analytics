<?php
	//echo $_GET['specific']== 'eventId';
    if ($_GET['specific'] == 'Content' || $_GET['specific'] == 'ContentPdfTracking'||$_GET['specific'] == 'ContentUrlTracking') 
    { //echo $info['title'];
      include_once 'analytics_content_chart.php';
    }
    elseif ($_GET['specific'] == 'Notifications' || $_GET['specific'] == 'ReceivedNotification' || $_GET['specific'] == 'SavedNotification' || $_GET['specific'] == 'ClickedPdfTracking' || $_GET['specific'] == 'ClickedUrlTracking')  
    {	
      // echo $info['title'];//echo $_GET['specific']; 
		include_once 'analytics_chart.php';
    }
	
	else{
		//echo $_GET['specific'];
		$info = $parseAPI->buildCorrectGraph($_GET['specific']);
		//echo var_dump($info);
		$default = false;
		if($info[0] == null || empty($info[0]) || $info[0]['beacon_graph'] == '[]') {
			$info = $parseAPI->buildDefaultGraph();
			$default = true;
		} else if($info[1] == null) {
			$info = $info[0];
		}
?>

<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
	<em><span id="content_header_actual"><?=$info['event_name'];?></span></em>
	<em><span id="content_header" style="display:none;"><?= $info['title']; ?></span></em>    
	    <ol class="breadcrumb">
	        <li><a href="/Organizer/">Dashboard</a></li>
	        <?php switch($_GET['specific']) {
	        	case 'Beacons':
	        		$link = 'Total Interactions';
	        		break;
	        	case 'Speakers':
	        		$link = 'Speaker Ratings';
	        		break;
	        	case 'Sessions':
	        		$link = 'Session Interactions';
	        		break;
	        	case 'Exhibitors-Interactions':
	        		$link = 'Exhibitor Interactions';
	        		break;
	        	default:
	        		$total_interaction_link = '<li><a href="/Organizer/Admin/analytics/' . $_SESSION['eventId'] . '/Beacons">Total Interactions</a></li>';
	        		//$link = 'Hourly Interactions';
	        		$link = $_GET['specific'].': Hourly Interactions';
	        		$graph_type = 'Hourly';
	        		break;
	        }
	        if ($total_interaction_link) {
	        	echo $total_interaction_link;
	        }?>
	        <li class="active"><?php echo $link?></li>
	    </ol>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
				 	<div class="panel-heading">
						<h3 class="panel-title"> </h3>
					</div> 
					<div class="panel-body <?php if($default) echo 'default_graph' ?>">
						<div data-event-type="<?= $info['event_type']; ?>" data-file-name="cms" data-file="<?= $info['event_code']; ?>" data-event="<?=$_GET['event'];?>" data-table="<?= $_GET['specific']; ?>"<?php if($_GET['specific'] == 'Beacons' || $parseAPI->isBeacon($_GET['specific']) || $_GET['specific'] == 'Exhibitors-Interactions'|| $_GET['specific'] == 'Notifications'|| $_GET['specific'] == 'Content') { ?> data-graph="<?= $_GET['event']; ?>" <?php } if($parseAPI->isBeacon($_GET['specific'])) { ?> id='main-container-sm' <?php } else { ?> id="main-container" <?php } if($_GET['specific'] == 'Beacons') { ?> data-href="/Organizer/Admin/analytics/<?= $_GET['event']?>/" <?php } ?> class="container">
							<div class="row"><div class="col-md-12"></div></div>
						</div>
						<?php if ($graph_type == 'Hourly') {
							//$graph_title = ' Hourly Interactions';
							$download_btn_val = $_GET['specific'].':';
						} ?>
						<button type="button" class="download_btn btn btn-lg btn-custom" value=<?=$download_btn_val?>>Download CSV</button>
					</div>
				</div>
			</div>
		</div>
		<div id="testing"></div>
		<div id="ajax_table" style="margin-bottom: 50px;">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-danger">
						<div class="panel-heading">
							<h3 class="panel-title"><strong><em> <?php if($_GET['specific'] == 'Exhibitors') echo 'Ratings'; ?></em></strong> </h3>
						</div>  
						
						<div class="panel-body">
						   <h3 id = "data-file-ext" class="panel-title" style="display:none;"><strong><em></em></strong></h3>
						   <table id="records" cellspacing="0" width="100%" class="table table-bordered table-striped table-hover dt-responsive nowrap">
					        <thead>
					          <tr>  
					            <?= $parseAPI->getCorrectTableHead($_GET['specific']); ?>
					          </tr>
					        </thead>
					        <tbody>

					        </tbody>
					       </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
