<?php $info = $parseAPI->buildSpecificEventInfo($adminAPI->getCorrectGET($_GET['event'])); ?>
<div class="container-fluid">	
	<div class="pages pages_dashboard">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-danger">
				    <div class="panel-heading">
				      <h3 class="panel-title"><strong><i class="fa fa-file-text"></i> Event Information</strong></h3>
				    </div><!-- /.box-header -->
				    <div class="panel-body">
					    <div class="row">
				    		<div class="col-md-3">
							  <?php if($info['session'][0]->get('logo')) {?>
							  	<div class="pull-left"><img src="<?= $info['session'][0]->get('logo')->getURL(); ?>" width="100" alt="" style="margin-right:20px;padding-bottom:50px;"></div>
							  <?php } ?>
							  	<h4><strong><?= $info['session'][0]->get("name"); ?></strong> <br /></h4>
						  	</div>
						  	<div class="col-md-3">
						      	<h4><i class="fa fa-star"> </i> <strong>General Information:</strong></h4>
						  	    <p>
						          <strong>Location:</strong> <?= $info['session'][0]->get("location"); ?><br />
						          <strong>Address:</strong> <?= $info['session'][0]->get("address"); ?><br />
						          <strong>City:</strong> <?= $info['session'][0]->get('city'); ?></br />
						          <strong>Zip:</strong> <?= $info['session'][0]->get('zipCode'); ?></p>
						  	</div>
						  	<div id="" class="col-md-6"> <!-- Notes -->
						        <h4 style="text-align: center;"><strong><span class="fa fa-commenting"></span> Speakers:</strong></h4>
						        <p>
						        	<?php foreach($info['speaker'] as $key) { ?>
						        	<div class="col-sm-3">
						        	<img src="<?=$key[0]->get("avatar")->getURL(); ?>" width="70" alt="" style="border-radius: 35px;"><br><?= $key[0]->get("firstName") . ' ' . $key[0]->get('lastName'); ?></div>
						        	<?php } ?>
						        </p>
						      </div>
			    		</div>
		    		</div>
				</div>
			</div>
		</div>
	</div>
</div>
