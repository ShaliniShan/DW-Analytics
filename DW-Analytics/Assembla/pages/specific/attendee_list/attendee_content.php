<div class="container-fluid">	
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
				    <div class="panel-heading">
				      <h3 class="panel-title"><strong>Content Library</strong></h3>
				    </div><!-- /.box-header -->
				    <div class="panel-body">
				    	<table id="records" class="table table-bordered table-striped table-hover">
				    		<thead>
				    			<tr>
				    				<?php $tabhead = '
					    				<th>Title</th>
					    				<th>PDF</th>
					    				<th>URL</th>';
				    				 echo $tabhead; ?>
				    			</tr>
				    		</thead>
				    		<tbody>
			    				<?= $parseAPI->buildContentLibrary($_SESSION['userID'], $adminAPI->getCorrectGET($_GET['event'])); ?>	
				    		</tbody>
			    		</table>
		    		</div>
				</div>
			</div>
		</div>
	</div>
</div>
