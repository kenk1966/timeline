<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

<!--<link rel="stylesheet" type="text/css" href="css/ms-dropdown.css">-->
<link rel="stylesheet" type="text/css" href="css/admin.css">

</head>

<body onLoad="redirTimer()">

<div id="container">
  <div id="formBackground">
  <img src="art/Logo-DunkinDonuts.png" class="DDlogo"/>
      <h1>DELETE JOBS</h1>
         
          <div>
          
			<?php
			if (isset($_GET['filename']) && !empty($_GET['filename'])) {
				
				//unlink("sheets/".$_GET['filename'].".txt");
				rename("sheets/".$_GET['filename'].".txt", "archive/".$_GET['filename'].".txt");
				echo "You have archived ".$_GET['filename'].".txt";
				unset($_GET['filename']);
			
				//exit();
			}
			else {
	 
				echo "";
			}
			?>
          
           <br />
        </div>

      </div>
    </div>
    
</body>
<script>
var redirTime = "500";
var redirURL = "timelineZ.php"; 
function redirTimer() {
self.setTimeout("parent.location.href = redirURL;",redirTime);}
</script>

</html>
