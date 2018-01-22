<?php $top_interactions = $parseAPI->buildTopInteractions($_SESSION['eventId']); 
		    if ($top_interactions) {?>
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top Total Interactions</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    		    <!--<?php $time_start = microtime(true);?>-->
				    		    <?= $top_interactions; ?>
				    		    <!--<?php $time_end = microtime(true); $time_perf = $time_end-$time_start;?>
				    		    <tr><td><?php echo $time_perf?></td></tr>-->  
				    		</tbody>
			    		</table>
			    		<a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Beacons">view all</a>
		            </div>    
		        </div>
		    </div>
		    <?php }
		    $top_speakers = $parseAPI->buildTopSpeakers($_SESSION['eventId']);
		    if ($top_speakers) {?>
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top Speaker Ratings</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    		    <!--<?php $time_start = microtime(true);?>-->
				    		    <?= $top_speakers; ?>
				    		    <!--<?php $time_end = microtime(true); $time_perf = $time_end-$time_start;?>
				    		    <tr><td><?php echo $time_perf?></td></tr>-->
				    		</tbody>
			    		</table>
			    		<a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Speakers">view all</a>
		            </div>    
		        </div>
		    </div>
		    <?php }
		    $top_sessions = $parseAPI->buildTopSessionAttendance($_SESSION['eventId']);
		    if ($top_sessions) {?>
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top Session Interactions</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    		    <!--<?php $time_start = microtime(true);?>-->
				                <?= $top_sessions; ?>    		    
				    		    <!--<?php $time_end = microtime(true); $time_perf = $time_end-$time_start;?>
				    		    <tr><td><?php echo $time_perf?></td></tr>-->
				    		</tbody>
			    		</table>
			    		<a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Sessions">view all</a>
		            </div>    
		        </div>
		    </div>
		    <?php }?>