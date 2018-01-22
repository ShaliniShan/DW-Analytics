<?php $adminAPI->setEventId($_SESSION['eventId']); ?>
<div class="container-fluid">
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
				    <div class="panel-heading">
				      <h3 class="panel-title"><strong><?php echo $adminAPI->event; ?> All Attendees</strong></h3>
				    </div><!-- /.box-header -->
				    <div class="panel-body">
				    	<table id="records" class="table_pick table table-bordered table-striped table-hover">
				    		<thead>
				    			<tr>
				    				<?php $tabhead = '
					    				<th>User</th>
					    				<th>Actual Attendee</th>';
				    				 echo $tabhead; ?>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<?= $parseAPI->buildCompleteAttendee($_SESSION['eventId']); ?>
				    		</tbody>
			    		</table>
		    		</div>
				</div>
			</div>
		</div>
	</div>
</div>
