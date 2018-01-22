<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<?= $parseAPI->getEventName($_SESSION['eventId']); ?>
	    <ol class="breadcrumb">
	        
	        <li>
	            <?php if ($_SESSION['userRole'] == 'o') { ?>
	                <a href="/Organizer/">Dashboard</a>
	            <?php } else if ($_SESSION['userRole'] == 'e') { ?>
	                <a href="/Exhibitor/">Dashboard</a>
	            <?php }?>
	        </li>
	        <?php switch($_GET['specific']) {
	        	case 'Notifications':
	        		if ($_SESSION['userRole'] == 'o') {
	        		$link = 'Triggered Content';
	        		}else if ($_SESSION['userRole'] == 'e') {
	        		$link =	'Content';
	        		}
	        		break;
	        	case 'ReceivedNotification':
	        		if ($_SESSION['userRole'] == 'o') {
	                $triggered_link = '<li><a href="/Organizer/Admin/analytics/' . $_SESSION['eventId'] . '/Notifications">Content</a></li>';
	        		$link = 'Triggered';
	        		}else if ($_SESSION['userRole'] == 'e') {
	        		$triggered_link = '<li><a href="/Exhibitor/Admin/analytics/' . $_SESSION['eventId'] . '/Notifications">Content</a></li>';
	        		$link = 'Presented';
	        		}
	        		break;
	        	case 'SavedNotification':
	        		if ($_SESSION['userRole'] == 'o') {
	                $triggered_link = '<li><a href="/Organizer/Admin/analytics/' . $_SESSION['eventId'] . '/Notifications">Content</a></li>';
	        		}else if ($_SESSION['userRole'] == 'e') {
	        		$triggered_link = '<li><a href="/Exhibitor/Admin/analytics/' . $_SESSION['eventId'] . '/Notifications">Content</a></li>';	
	        		}
	        		$link = 'Saved';
	        		break;
	        	case 'ClickedPdfTracking':
	        		if ($_SESSION['userRole'] == 'o') {
	                 $triggered_link = '<li><a href="/Organizer/Admin/analytics/' . $_SESSION['eventId'] . '/Notifications">Content</a></li>';
	                 }else if ($_SESSION['userRole'] == 'e') {
	                 $triggered_link = '<li><a href="/Exhibitor/Admin/analytics/' . $_SESSION['eventId'] . '/Notifications">Content</a></li>';
	                 }
	                 $link = 'Click (PDF)';
	        		break;
	        	case 'ClickedUrlTracking':
	        		if ($_SESSION['userRole'] == 'o') {
	                 $triggered_link = '<li><a href="/Organizer/Admin/analytics/' . $_SESSION['eventId'] . '/Notifications">Content</a></li>';
	        		}else if ($_SESSION['userRole'] == 'e') {
	        		$triggered_link = '<li><a href="/Exhibitor/Admin/analytics/' . $_SESSION['eventId'] . '/Notifications">Content</a></li>';
	        		}
	        		$link = 'Click (URL)';
	        		break;
	        		
	        	default:
	        		break;
	        } if ($triggered_link) {
	        	echo $triggered_link;
	        }
	        ?>
	        <li class="active"><?php echo $link?></li>
	    </ol>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
				<!-- <div class="panel-heading">					
						<h3 class="panel-title"><em><span id="content_header" title=<?= $parseAPI->getEventLocationDate($_SESSION['eventId']); ?>><?= $parseAPI->getEventName($_SESSION['eventId']); ?></span></em> <i class="fa fa-arrow-right"></i><?= $link; ?></h3>									
					</div> -->
					<div class="panel-body">			
						<table id="records" cellspacing="0" width="100%" class="table table-bordered table-striped table-hover dt-responsive nowrap">
					        <thead>
					            <tr>
					              <?= $parseAPI->getCorrectTableHead($_GET['specific']); ?>
					            </tr>
					        </thead>
					        <tbody>
					   			<?=  $parseAPI->buildNotificationAnalytics($_GET['event'], $_GET['specific']); 	?>	
					      </tbody>
					    </table>
					</div>
				</div>
			</div>
		</div>
	</div> 	
</div>