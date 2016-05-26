<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Create Text File</title>
<style>
body {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 11px;
	color: #fff;
	
}
a, a:hover, a:visited, a:active {
	text-align:center;
	color:#fff;
}
.bold {
	font-weight: bold;
}
td { text-align:left; }
h1 {
	color: #fff;
	font-size: 16px;
	font-weight: bold;
	
}
h3 {
	color: #fff;
	font-size: 14px;
	font-weight: bold;
}
label, input, select, textarea {
	margin: 8px;
}
#blueBox {
	display: inline-block;
	padding: 25px;
	box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.1);
	background: #69A1DA;
	width: 200px; 
	height:1000px;
	text-align: center;
}
#container {
	text-align: center;
	margin: 0px auto;
}
.grad { 
  background-color: #F07575; 
}
.nobr	{ white-space:nowrap; }
.asterisk {
	color: #f00;
}
.noPad {
	padding:0px;
	margin:0px;
	padding-right:3px;
}
</style>
</head>
<body onLoad="redirTimer()">
<div id="container">
  <div id="blueBox">
    <div style="background:#fff; text-align:center;"></div>
      <h1>Thank you for submitting your request!</h1>
      <a href="project_info_entry.html">Click to enter more project info</a>
      <div style="margin:20px; color:#000;">
      	
      
<?php
$items = '';
$htmlItems = '';

foreach($_POST as $name => $value){ 

	$items .= test_input($value).","; 
	$htmlItems .= test_input($value).","; 
}


$filename = str_replace(':', '', test_input($_POST['Job_Number']));

$filename = str_replace(',', '', $filename);
$filename = str_replace(' ', '_', $filename);
$filename .= '.txt';

$myfile = fopen('sheets/'.$filename, "w") or die("Unable to open file!");

$txt = $items;
fwrite($myfile, $txt);

fclose($myfile);


/** TIMED REDIRECT BACK TO FORM WHEN DONE **/

$redirect = 'timelineZ.php'; //'project_info_entry.php';



/****** FUNCTIONS *******/
/* Form Security -- strip tags etc. */
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
	</div>
  </div>
</div>
</body>
<SCRIPT LANGUAGE="JavaScript">
redirTime = "2550";
redirURL = "<?php echo $redirect ?>";
function redirTimer() {
/*self.setTimeout("self.location.href = redirURL;",redirTime);}*/
self.setTimeout("parent.location.href = redirURL;",redirTime);}
</script>
</html>

