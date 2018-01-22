<?php $top_notifications = $parseAPI->buildTopNotifications($_SESSION['eventId']);
		    if ($top_notifications) {?>
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top Notifications</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    		    <!--<?php $time_start = microtime(true);?>-->
				    	        <?= $top_notifications; ?> 
				    		    <!--<?php $time_end = microtime(true); $time_perf = $time_end-$time_start;?>
				    		    <tr><td><?php echo $time_perf?></td></tr>-->
				    		</tbody>
			    		</table>
			    		<a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Notifications">view all</a>
		            </div>    
		        </div>
		    </div>
		    <?php }
		    $top_exhibitors = $parseAPI->buildTopExhibitorInteractions($_SESSION['eventId']); 
		    if ($top_exhibitors)  {?>
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top Exhibitor Interactions</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    		    <!--<?php $time_start = microtime(true);?>-->
				    		    <?= $top_exhibitors; ?>
				    		    <!--<?php $time_end = microtime(true); $time_perf = $time_end-$time_start;?>
				    		    <tr><td><?php echo $time_perf?></td></tr>-->
				    		</tbody>
			    		</table>
			    		<a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Exhibitors-Interactions">view all</a>
		            </div>    
		        </div>
		    </div>
		    <?php }
		    $top_content = $parseAPI->buildTopContentLibrary($_SESSION['eventId']);
		    if ($top_content) {?>
		    <div class="col-md-4">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h3 class="panel-title"><em>Your Top Content Library</em> </h3>    
		            </div>
		            <div class="panel-body">
		                <table id="records" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				
				    			</tr>
				    		</thead>
				    		<tbody>
				    		    <!--<?php $time_start = microtime(true);?>-->
				    		    <?= $top_content; ?>
				    		    <!--<?php $time_end = microtime(true); $time_perf = $time_end-$time_start;?>
				    		    <tr><td><?php echo $time_perf?></td></tr>-->
				    		</tbody>
			    		</table>
			    		<a href="/Organizer/Admin/analytics/<?php echo $_SESSION["eventId"]; ?>/Content">view all</a>
		            </div>    
		        </div>
		    </div>
		    <?php }?>
