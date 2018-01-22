<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><em>Your Events</em> </h3>
						<a style="float:right; position: relative; bottom: 25.5px;" class="btn btn-custom" href="/Organizer/Admin/new_event"><i class="fa fa-plus"></i> Add Event</a>
					</div>
					<div class="panel-body">
						<table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				<?php $tabhead = '
					    				<th class="table-header">Title</th>
					    				<th class="table-header">Venue</th>
					    				<th class="table-header">City, State</th>
					    				<th class="table-header">Start Date</th>
					    				<th class="table-header">Edit</th>';
				    				 echo $tabhead; ?>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<?= $adminAPI->buildEventEditTable($_SESSION['userID']); ?>
				    		</tbody>
			    		</table>
					</div>
				</div>
			</div>
		</div>
		<div id="home_row1" class="row">
		    <?php include_once 'pages/modules/admin/home_row1.php'; ?>    
		</div>
		<div id="error"></div>
		<div id="home_row2" class="row">
		    <?php include_once 'pages/modules/admin/home_row2.php'; ?>
		</div>
		
		<!-- 
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><strong><em><?= $info['title']; ?></em></strong> <i class="fa fa-arrow-right"></i> All Beacon Interactions</h3>
					</div>
					<div class="panel-body">
						<div data-event-type="<?= $info['event_type'] ?>" data-file="<?= $info['event_code']; ?>" <?php if($hasInfo) { ?> data-href="/Organizer/Admin/analytics/<?= $_SESSION['eventId']; ?>/" <?php } ?> id="main-container" class="container">
							<div class="row"></div>
						</div>
						<button type="button" class="download_btn btn btn-lg btn-custom">Download CSV</button>
					</div>
				</div>
			</div>
		</div>
		-->
	</div>
</div>
