<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
    <title><?= $event->get("name"); ?> | BEEP</title>
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap/css/bootstrap.min.css">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="/assets/css/font-awesome/css/font-awesome.min.css">
     <!-- DATATABLES -->
    <link rel="stylesheet" href="/assets/css/dataTables.bootstrap.css">
    <!-- ADMINLTE CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/css/AdminLTE/AdminLTE.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/custom.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/agenda.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type = "text/css">
    /* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
/* --------------- Responsive Fixes --------------- */
        /* small device */
        @media (max-width: 767px) {
            .device-big {
            display: none;
            }
            .device-small {
            display: block;
            }
        }
        /* big device */
        @media only screen and (min-width : 768px) {
            .device-big, .tab-content > .tab-pane {
            display: block;
            }
            .device-small {
            display: none;
            }
            .tab-content > .tab-pane {
                display: block;
            }
        }
        /* --------------- / Responsive Fixes --------------- */
    
    </style>
</head>
<body class="layout-top-nav element-background">
	<header class="main-header">
	    <nav class="navbar navbar-inverse navbar-static-top">
	      <div class="container-fluid" style="padding-top: 0">
	        <div class="navbar-header">
	          <a href="./" class="navbar-brand"><img src="/assets/img/beep-logo.png" width="45" alt=""></a>
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
	            <i class="fa fa-bars"></i>
	          </button>
	        </div>
	        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
	          <ul class="nav navbar-nav">
	          <li > <p class="fa fa-phone" style = 'color:white; font-size:1em; margin :10px,0;'><?= $company_number; ?></p></li>
	          <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
	           <li><p class="fa fa-send" style = 'color:white; font-size:1em;' ><a  style = 'color:white; font-size:1em; font-family:Source Sans Pro;' href="mailto:<?= $company_email; ?>"><?= $company_email; ?> </a></p></li>
	           
	           </ul>
	          	           
	         
	        </div><!-- /.navbar-collapse -->
	      </div><!-- /.container-fluid -->
	    </nav>
	</header>
	<!-- Full Width Column -->
	<div class="content-wrapper">
	  <div class="ssp-title">
	    <div class="container-fluid" style="padding-top: 0">
	    <div style="text-align: center;" class="col-xs-4 col-lg-4">
                  <img class="event-logo" src="<?= $logo; ?>" width="40%" alt="">
              </div>
	      <h3><?= $event->get("name"); ?> Agenda<br><small>Powered by BEEP</small></h3>
	    </div>
	  </div>
	  <div class="container-fluid">
	     
	      <div class="row"> 
	       <!-- Nav tabs -->
      <ul class="nav nav-tabs device-small" role="tablist">
        <li role="presentation" class="active"><a href="#session" aria-controls="session" role="tab" data-toggle="tab">Sessions</a></li>
        <li role="presentation"><a href="#speaker" aria-controls="speaker" role="tab" data-toggle="tab">Speakers</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active col-lg-8 col-xs-12 col-sm-4" id="session">
        <!-- Full Width Column -->
	  <div class="container-fluid">
	     
	      <div class="row">
	      	
	      		<h2>Sessions: </h2>
	      	<?php foreach ($sessions as $session):?>
 				<p class="time_line"><div class="time_border"><?= $model->_setTimezone($session->get("startTime")->format('g:i A'), $timezone)->format('g:i A'); ?></div></p>
	      	
					<div class="container-fluid" style="position: center;">
				   <?php 
				   $src_url = $session->get("details") ?  '/assets/img/down-arrow.png'  :'' ;
				   if ($session->get("details")){
				echo "<a href='#demo' class='accordion' data-toggle='collapse' style= 'color :white;background-color:skyblue5;width:75%;font-size:medium;display:block;' >
					 <img class='session-description' src=' ".$src_url."'></img>".$session->get('title')."</a>";
				echo "<div id='desc' class='collapse'>";
												
				echo "<p style='text-overflow: ellipsis;font-size: 15px; margin-top: 20px;margin-left:30px;float:left;align:left;margin-right:10px; 'class='session_info'>". $session->get('details')."</p>
						</div></div>";
				   }
					else{
						echo "<a href='#demo' class='accordion' data-toggle='collapse' style= 'pointer-events:none;margin-right:10px; color :white;background-color:skyblue5;font-size:medium;width:75%;display:block;' >
					 <img class='session-description' src=' ".$src_url."'></img>".$session->get('title')."</a></div>";
				
						//echo "<a style= 'color :white;background-color:skyblue5;font-size:medium;' >
					 //<img class='session-description'  src='".$src_url."'></img>".$session->get('title'). "</a>";
				    
					   }?>
					
					<p class="time_line"><div class="time_border1" style="display:inline;float :left;" ><?=$model->_setTimezone($session->get("endTime")->format('g:i A'), $timezone)->format('g:i A'); ?></div></p>	
					<br><hr/>
	
	      		<?php endforeach; ?>
				</div>
        </div></div>
        <div role="tabpanel" class="tab-pane col-lg-4 col-xs-12 col-sm-4" id="speaker">
        <div class="container-fluid">
	     
	      <div class="row">
	  
        
	      		<h2>Speakers: </h2>
	      		<div id="parentmodal">
	      		<?php
	      		    $first_name = array();
	      		    foreach($speakers as $speaker_sort) {
	      		        array_push($first_name, $speaker_sort->get("firstName"). ' '. $speaker_sort->get("lastName"));	
	      		    } 
	      		    $first_name_lower = array_map('strtolower', $first_name);
	      		    array_multisort($first_name_lower, SORT_ASC, SORT_STRING, $speakers);
	      		?>
	      		<?php foreach ($speakers as $speaker): 
	      		$src_url = ($speaker->get("avatar") ? $speaker->get("avatar")->getURL() : '/assets/img/avatar2.png');    
				if ($speaker->get("about")) {
					echo "<p><img class='speaker-logo' src='". $src_url ."' width='45' align = 'left' alt=''><a href='#myModal' class = 'popupcl' style = 'a:active{'color: skyblue5;'}';> " . $speaker->get('firstName') . " ". $speaker->get('lastName')."</a><h5>".$speaker->get('title') . "<br>" . $speaker->get('company') . "</h5></p> <div id = 'myModal' class='modal'><div class='modal-content'><span class='close'> &times;</span><p> <div><img class='speaker-logo' src='". $src_url ."' width='45' align = 'left' alt=''> <div style='font-size: 1.2em'>".$speaker->get('firstName') . " ". $speaker->get('lastName')."</br><h5>" . $speaker->get('title') . "<br>" . $speaker->get('company') . "</div></h5></div><div style='font-size: 1em'> ".$speaker->get('about'). "</div></p></div></div><br>";
				//	echo "<img class='speaker-logo' src='". $src_url ."' width='45' alt=''><div class = 'popup' onclick='showBio()'>" . $speaker->get('firstName') . " ". $speaker->get('lastName') . "<span class='popuptext' id='myPopup'>" . $speaker->get('about') . "</span></div>";
			//echo  $speaker->get('title') . "<br>" . $speaker->get('company') . "<br>";
			//		echo <img class="speaker-logo" src="($speaker->get("avatar") ? $speaker->get("avatar")->getURL() : '/assets/img/avatar2.png')" width="45" alt=""><div class = "popup" onclick="showBio()">$speaker->get("firstName") . ' ' . $speaker->get('lastName')<span class="popuptext" id="myPopup"> $speaker->get("about")</span></div><h5>echo $speaker->get("title"); echo "<br>"; echo $speaker->get("company")</h5>
			 //   else if (($speaker->get("about")) === "undefined" ) 
				}
				else {
				    echo "<img class='speaker-logo' src='" . $src_url . "' width='45' align = 'left' alt=''> ". $speaker->get('firstName') . " ". $speaker->get('lastName')."<h5>" . $speaker->get('title') . "<br>" . $speaker->get('company') . "</h5><br>";
				    //echo "<h5>" . $speaker->get('title') . "<br>" . $speaker->get('company') . "</h5>"; 
				}?>		
	      		<?php endforeach; ?>
	      		</div>
	      	</div>
	      </div>
        
        
        
        </div> 
      </div>
	      	
	   <!--<div class="row ending-note">
	      	<div class="col-xs-12 col-lg-12">
	      		<p style="vertical-align: middle; margin-top: 20px;" class="lead">Event Powered by <a href="http://www.beepetc.com">BEEP</a></p>
	      	</div>
	      </div> -->
	   </div>
		<!-- Main Footer -->
		<footer class="main-footer">
		    <div class="container-fluid" style="padding-top: 0; font-size :large;">
		     <div class="col-lg-4">
		        Copyright &copy; 2017 <a href="http://www.beepetc.com/" target="_blank">Beantown Beacons, Inc.</a> </div>
		      <div class="col-lg-4">
		        <i class="fa fa-send"></i><a href="mailto:<?= $company_email; ?>"> <?= $company_email; ?></a>
		      </div>
		      <div class="col-lg-4 pull-right">
		        <i class="fa fa-phone"></i> <?= $company_number; ?>
		      </div>
		    </div>
		</footer>
	</div>
	<!-- JQUERY AND BOOTSTRAP JAVASCRIPT -->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/css/bootstrap/js/bootstrap.min.js"></script>
    <script>
    
    // Get the modal
    var modal = document.getElementsByClassName("modal");
	// Get the button that opens the modal
	
    var btn = document.getElementsByClassName("popupcl");

// Get the <span> element that closes the modal
    
    var span = document.getElementsByClassName("close");

    for(var a = 0, len = btn.length; a < len; a++)
    {
        (function(index){
        	// When the user clicks the button, open the modal 
            btn[a].onclick = function(){
                //alert(index);
                modal[index].style.display = "block";
            }
         // When the user clicks on <span> (x), close the modal
            span[a].onclick = function() {
                modal[index].style.display = "none";
            }

       /*     function modalClose() {
                alert("Krsna");
                modal[index].fadeOut(500);
            }

            document.addEventListener('keyup', function(e) {
                if (e.keyCode == 27) {
                    modalClose();
               
                }
            });

            modal[index].addEventListener('click', function(e) {
                modalClose();
            }, false);

            modal[index].children[0].addEventListener('click', function(e) {
                e.stopPropagation();
            }, false);
*/
         //TODO, this doesn't work, need to check later
         // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal[index]) {
                    modal[index].style.display = "none";
                }
            }
       })(a);
    } 

   /* $(document).keypress(function(e) {
        if (e.keyCode == 27) {
            window.close();
        }
    }); */
    
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].onclick = function(){
            this.classList.toggle("active");
            //this.nextElementSibling.classList.toggle("show");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
      }
    }
        
    </script>
</body>
</html>