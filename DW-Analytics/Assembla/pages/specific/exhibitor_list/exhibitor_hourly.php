<?php $info = $parseAPI->buildAttendeeHourlyEx('YSKEVRQZTO', $_GET['eh']); ?>
<!--Full Width Column -->
<div class="content-wrapper">
  <div class="container">
<!-- 	  <section class="content-header">  
	    <h1>Hourly Interactions : <span id="content_header"><?= $_GET['eh']; ?></span></h1>
	  </section>  -->	
    <!-- Bar Graph -->
  <!-- <div id="main-container" class="container"><div class="row"></div></div> -->
 	<div data-table="?page=Exhibitor" class="container" id="main-container-sm"><div class="row"></div></div> 	
    <div style="width: 100%; height: 50px;"><div id="testing"></div></div>
	  <div id="ajax_table" style="margin-bottom: 50px;">
	   <table id="records" class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <?php $tabhead = '
              <th>Name</th>
              <th>Title</th>
              <th>Company</th>
              <th>TimeIn</th>';
             echo $tabhead; ?>
          </tr>
        </thead>
        <tbody>
          <!-- Function will get beacon through selection -->
        </tbody>
       </table>
      </div>
  </div>
</div> <!-- .content-wrapper end -->

