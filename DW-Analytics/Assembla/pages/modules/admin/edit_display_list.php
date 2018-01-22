<style type = "text/css">
.highlight {
    background: #00bcd4;
}
</style>
<div class="container-fluid half-padding">
	<div class="pages pages_speakers_list">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title"><em>Speakers List</em> </h3>
						<a style="float:right; position: relative; bottom: 25.5px;" class="btn btn-custom" href=<?=$_SESSION['actionURL'] ?>><i class="fa fa-plus"></i> Add </a>
					</div>
					<div class="panel-body">
						<table id="records-event" class="table table_pick datatable display table-hover">
				    		<thead>
				    			<tr>
				    				<?php $tabhead = '
					    				<th class="table-header">Name</th>
					    				<th class="table-header">Company</th>
					    				<th class="table-header">Title</th>
					    				<th class="table-header">TwitterURL</th>
					    				<th class="table-header">LinkedInURL</th>';
				    				 echo $tabhead; ?>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<?= $adminAPI->displayAddedSpeakers($_SESSION['newEventId'],$_GET['action']) ;?> 
				    		</tbody>
			    		</table>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
