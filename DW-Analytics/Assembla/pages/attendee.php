<div class="container-fluid">	
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
				    <div class="panel-heading">
				      <h3 class="panel-title"><strong>Attendee Event List</strong></h3>
				    </div><!-- /.box-header -->
				    <div class="panel-body">
				    	<table id="records" class="table_pick table table-bordered table-striped table-hover">
				    		<thead>
				    			<tr>
				    				<?php $tabhead = '
					    				<th>Event</th>
					    				<th>Date</th>
					    				<th>Venue</th>
					    				<th>Location</th>
					    				<th>Library</th>';
				    				 echo $tabhead; ?>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<?= $parseAPI->buildEventsTableAttendee($_SESSION['userID']); ?>
				    		</tbody>
			    		</table>
		    		</div>
				</div>
			</div>
		</div>
	</div>
</div>