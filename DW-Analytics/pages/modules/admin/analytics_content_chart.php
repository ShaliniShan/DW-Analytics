<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
	    <ol class="breadcrumb">
	    <?= $parseAPI->getEventName($_SESSION['eventId']); ?>
	        <li><a href="/Organizer/">Dashboard</a></li>
	        <?php switch($_GET['specific']) {
	        	case 'Content':
	        		$link = 'Content Library';
	        		break;
	        	case 'ContentPdfTracking':
	        		$content_link = '<li><a href="/Organizer/Admin/analytics/' . $_SESSION['eventId'] . '/Content">Content</a></li>';
	        		$link = 'Click (PDF)';
	        		break;
	        	case 'ContentUrlTracking':
	        		$content_link = '<li><a href="/Organizer/Admin/analytics/' . $_SESSION['eventId'] . '/Content">Content</a></li>';
	        		$link = 'Click (URL)';
	        		break;
	        	default:
	        		break;
	        }
	        if ($content_link) {
	        	echo $content_link;
	        }?>
	        <li class="active"><?php echo $link?></li>
	    </ol>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
				<!--  	<div class="panel-heading">					
						<h3 class="panel-title"><em><span id="content_header" title=<?= $parseAPI->getEventLocationDate($_SESSION['event']); ?>><?= $parseAPI->getEventName($_SESSION['event']); ?></span></em> <i class="fa fa-arrow-right"></i><?= $link; ?></h3>									
					</div> -->
					<div class="panel-body">				
						<table id="records" cellspacing="0" width="100%" class="table table-bordered table-striped table-hover dt-responsive nowrap">
					        <thead>
					            <tr>
					              <?= $parseAPI->getCorrectTableHead($_GET['specific']); ?>
					            </tr>
					        </thead>
					        <tbody>
					   			<?=  $parseAPI->buildContentAnalytics($_GET['event'], $_GET['specific']); 	?>	
					      </tbody>
					    </table>
					</div>
				</div>
			</div>
		</div>
	</div> 	
</div>