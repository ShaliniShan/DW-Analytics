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
		<div class="row">
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top Interactions</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr><td><em>Krsna Interaction 1</em></td></tr>
				    			<tr><td><em>Krsna Interaction 2</em></td></tr>
				    			<tr><td><em>Krsna Interaction 3</em></td></tr>
				    		</tbody>
			    		</table>
		            </div>    
		        </div>
		    </div>
		    
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top Sessions</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr><td><em>Krsna Session 1</em></td></tr>
				    			<tr><td><em>Krsna Session 2</em></td></tr>
				    			<tr><td><em>Krsna Session 3</em></td></tr>
				    		</tbody>
			    		</table>
		            </div>    
		        </div>
		    </div>
		    
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top notifications</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr><td><em>Krsna Notification 1</em></td></tr>
				    			<tr><td><em>Krsna Notification 2</em></td></tr>
				    			<tr><td><em>Krsna Notification 3</em></td></tr>
				    		</tbody>
			    		</table>
		            </div>    
		        </div>
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top Contents</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr><td><em>Krsna Content 1</em></td></tr>
				    			<tr><td><em>Krsna Content 2</em></td></tr>
				    			<tr><td><em>Krsna Content 3</em></td></tr>
				    		</tbody>
			    		</table>
		            </div>    
		        </div>
		    </div>
		    
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top Questions</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr><td><em>Krsna Question 1</em></td></tr>
				    			<tr><td><em>Krsna Question 2</em></td></tr>
				    			<tr><td><em>Krsna Question 3</em></td></tr>
				    		</tbody>
			    		</table>
		            </div>    
		        </div>
		    </div>
		    
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top answers</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr><td><em>Krsna Answer 1</em></td></tr>
				    			<tr><td><em>Krsna Answer 2</em></td></tr>
				    			<tr><td><em>Krsna Answer 3</em></td></tr>
				    		</tbody>
			    		</table>
		            </div>    
		        </div>
		    </div>
		</div>
		
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
	</div>
</div>
