
<style type = "text/css">
.highlight {
    background: #00bcd4;
}
.disable-link {
    pointer-events: none;
    cursor: default;
}
</style>
<div class="container-fluid half-padding">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><em>Your Events</em> </h3>
						<a style="float:right; position: relative; bottom: 25.5px;" class="btn btn-custom" href="/Organizer/admin/new-event"><i class="fa fa-plus"></i> ,</a>
					</div>
					<div class="panel-body">
						<!-- <table id="eventList" class="table table_pick datatable display"> -->
				    		<table id="records-event" class="table table_pick datatable display">
				    		<thead>
				    			<tr>
				    				<?php $tabhead = '
					    				<th class="table-header">Title</th>
					    				<th class="table-header">Venue</th>
					    				<th class="table-header">City, State</th>
					    				<th class="table-header">Start Date</th>
					    				<th class="table-header">Edit</th>
					    				<th class="table-header">View</th>';
				    				 echo $tabhead; ?>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<?= $adminAPI->buildEventEditTable($_SESSION['userID']);?> 
				    		</tbody>
			    		</table>
					</div>
				</div>
			</div>
		</div>
		<div id="testing"></div>
		<div id="home_row1" class="row">
		</div>
		<div id="home_row2" class="row">
		</div>
		<div id="home_row3" class="row">
		</div>
	</div>
</div>
