<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Project Deletion</title>

 <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script src="js/jquery.dd.min.js"></script>

<script>
$(function() {
$(".calendar" ).datepicker();
$("select").msDropDown();
});

</script>


<link rel="stylesheet" type="text/css" href="css/ms-dropdown.css">
<link rel="stylesheet" type="text/css" href="css/admin.css">

<?php
	$file_list_raw=array();
	$file_counter=0;
	
	$project_data=array();
	$file_list=array();
	$dir = new DirectoryIterator(dirname(__FILE__)."/sheets");
	foreach ($dir as $fileinfo) {
		$file_list_raw[] = $fileinfo->getFilename();
	}
	
	for ($i = 0; $i < count($file_list_raw); ++$i) {
		
		$f = fopen("sheets/".$file_list_raw[$i], "r");
		
		while (!feof($f)) { 
		   $project_record = explode(",",fgets($f)); 
		}
		fclose($f);
		
		if (strlen($project_record[1]) > 1) {
			$project_data[] = $project_record;
			$file_list[] = $file_list_raw[$i];
		}
		
		$file_counter++;
	}
	sort($project_data);
	sort($file_list);
	?>
    
</head>

<body>

<div id="container">
  <div id="formBackground">
  <img src="art/Logo-DunkinDonuts.png" class="DDlogo"/>
      <h1>EDIT JOBS</h1>
          <div class="headline">PLEASE SELECT A JOB BELOW TO EDIT</div>
          
          <div>
          
          
			<?
            foreach($file_list as $item) {
				
                echo "<a href='project_info_entry.php?job=".substr($item, 0, -4)."' class='file_link'>".substr($item, 0, -4)."</a><br />";
            }
			
			?>
          
          
          </div>
       
       
      
          <div id="admin_bar">
          	<a href="project_info_entry.php"><div class="button_info_entry"></div></a>
            <a href="delete_job.php"><div class="button_delete_job"></div></a>
          
          </div>
       
        
      </div>
    </div>

</body>
</html>
