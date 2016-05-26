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
	
	var running_total = new Date();

});

/*Number.prototype.mod = function(n) {
return ((this%n)+n)%n;
}*/
Date.prototype.addBusDays = function(dd) {
var wks = Math.floor(dd/5);
//var dys = dd.mod(5);
var dys = ((dd%5)+5)%5;
//above to get rid of mod fn
var dy = this.getDay();
if (dy === 6 && dys > -1) {
   if (dys === 0) {dys-=2; dy+=2;}
   dys++; dy -= 6;}
if (dy === 0 && dys < 1) {
   if (dys === 0) {dys+=2; dy-=2;}
   dys--; dy += 6;}
if (dy + dys > 5) dys += 2;
if (dy + dys < 1) dys -= 2;
this.setDate(this.getDate()+wks*7+dys);
}
Date.prototype.addHours = function(h) {    
   this.setTime(this.getTime() + (h*60*60*1000)); 
   return this;   
}

function validate()
{

	var temp_date = document.timeline_form.Start_Date.value; 
	// here use start date then add up days from all segments to it to see if exceeds end date
	running_total = ParseDateString(temp_date);
	
	running_total=CalcSegmentTime(document.timeline_form.AS_Opening.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.Creative_Time1.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.AS_Revision.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.Creative_Time2.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.AS_To_Client1.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.Client_Time1.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.Unknown_Time.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.Client_Revision.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.AS_To_Creative.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.Creative_Time3.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.AS_To_Client2.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.Client_Time2.value, running_total);
	running_total = CalcSegmentTime (document.timeline_form.AS_To_Approve.value, running_total);
	//this last step below happens after end date, so ignore
	//running_total = CalcSegmentTime (document.timeline_form.AS_To_Release.value, running_total);
	
	temp_date = ParseDateString (document.timeline_form.End_Date.value);
	if ( temp_date < running_total )
	 {
		alert( "Your end date is too early for the days assigned to this project. Please move end date forward.");
		document.timeline_form.End_Date.focus() ;
		return false;
	 }
	
	 return( true );
}

function ParseDateString(date) {
	
	var mon1   = parseInt(date.substring(0,2));
	var dt1  = parseInt(date.substring(3,5));
	var yr1   = parseInt(date.substring(6,10));
	var date1 = new Date(yr1, mon1-1, dt1);
	return date1;
}


function CalcSegmentTime (segmentTime, total) {
	
	
	var segment_integer = Math.floor(segmentTime);
	var segment_fraction = segmentTime - segment_integer;
	
	total.addBusDays(segment_integer);
	
	total = addDays(total, segment_fraction);
	
	if (total.getDay() === 6) {
		total = addDays(total, 2); // if fraction makes date fall on saturday, push to same time Monday
	}
	
	return total;
}

function addDays(date, days) {
    var result = new Date(date);
	//below uses 'addHours' added to Date() prototype at top of js
	result.addHours(days*24);
    return result;
}

</script>

<link rel="stylesheet" type="text/css" href="css/ms-dropdown.css">
<link rel="stylesheet" type="text/css" href="css/admin.css">

</head>

<body>

<form name="timeline_form" action="timelineZ_submit_form.php" method="post" id="timeline_form" onsubmit="return(validate());">
<input name="Request_Title" type="hidden" id="Request_Title" value="Project_Type_1.00" />
<!-- value of Standard Project above shows the project TYPE for forward compatibility, here 'standard' -->
<div id="container">
  <div id="formBackground">
  <img src="art/Logo-DunkinDonuts.png" class="DDlogo"/>
      <h1>PROJECT INFO</h1>
      <div class="headline">
      	<?php
			if (isset($_GET['job']) && !empty($_GET['job'])) {
				echo "EDITING JOB #: ".$_GET['job'];
				
				//use job number to load just that one sheet and loop through build array
				//then use array to prepop fields
				
				$f = fopen("sheets/".$_GET['job'].".txt", "r");
		
				while (!feof($f)) { 
				   $project_record = explode(",",fgets($f)); 
				}
				fclose($f);
				
				if (strlen($project_record[1]) > 1) {
					$prepopulated_data = $project_record;
					//data to prepop into form fields when loading preexisting sheet
				}
				    
			}else{  
				echo "ENTER NEW JOB INFORMATION:";
			}
		
		
		     
		?>
		</div>
          <div>
            <table border="0" cellpadding="0" cellspacing="0">
        
              <tr>
                <td width="40%"><label>Job&nbsp;Number</label></td>
                <td  width="60%"><input name="Job_Number" id="Job_Number" value="<?php echo($prepopulated_data[1]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>Group (if any)</label></td>
                <td><input name="Group" id="Group" value="<?php echo($prepopulated_data[2]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>Start Date</label></td>
                <td><input name="Start_Date" type="text" id="Start_Date" value="<?php echo($prepopulated_data[3]); ?>" class="calendar" /></td>
              </tr>
              <tr>
                <td><label>End Date</label></td>
                <td><input name="End_Date" type="text" id="End_Date" value="<?php echo($prepopulated_data[4]); ?>" class="calendar" /></td>
              </tr>
              <tr>
                <td><label>AS Opening</label></td>
                <td><input name="AS_Opening" id="AS_Opening" value="<?php echo($prepopulated_data[5]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>Creative Time</label></td>
                <td><input name="Creative_Time1" id="Creative_Time1" value="<?php echo($prepopulated_data[6]); ?>" type="text" class="thin_input">
					<select name="Creative_TimeDD1" id="Creative_TimeDD1"  class="thin_dd">
                        <option value="1" title="art/dd_red.png" <?php if ($prepopulated_data[7]=="1") echo('selected = "selected" '); ?> >1</option>
                        <option value="2" title="art/dd_green.png" <?php if ($prepopulated_data[7]=="2") echo('selected = "selected" '); ?> >2</option>
                        <option value="3" title="art/dd_blue.png" <?php if ($prepopulated_data[7]=="3") echo('selected = "selected" '); ?> >3</option>
                  	</select></td>
              </tr>
              <tr>
                <td><label>AS Revision</label></td>
                <td><input name="AS_Revision" id="AS_Revision" value="<?php echo($prepopulated_data[8]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>Creative Time</label></td>
                <td><input name="Creative_Time2" id="Creative_Time2" value="<?php echo($prepopulated_data[9]); ?>" type="text"  class="thin_input">
					<select name="Creative_TimeDD2" id="Creative_TimeDD2"  class="thin_dd">
                        <option value="1" title="art/dd_red.png" <?php if ($prepopulated_data[10]=="1") echo('selected = "selected" '); ?> >1</option>
                        <option value="2" title="art/dd_green.png" <?php if ($prepopulated_data[10]=="2") echo('selected = "selected" '); ?> >2</option>
                        <option value="3" title="art/dd_blue.png" <?php if ($prepopulated_data[10]=="3") echo('selected = "selected" '); ?> >3</option>
                  	</select></td>
              </tr>
              <tr>
                <td><label>AS To Client</label></td>
                <td><input name="AS_To_Client1" id="AS_To_Client1" value="<?php echo($prepopulated_data[11]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>Client Time</label></td>
                <td><input name="Client_Time1" id="Client_Time1" value="<?php echo($prepopulated_data[12]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>Unknown Time</label></td>
                <td><input name="Unknown_Time" id="Unknown_Time" value="<?php echo($prepopulated_data[13]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>Client Revision</label></td>
                <td><input name="Client_Revision" id="Client_Revision" value="<?php echo($prepopulated_data[14]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>AS To Creative</label></td>
                <td><input name="AS_To_Creative" id="AS_To_Creative" value="<?php echo($prepopulated_data[15]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>Creative Time</label></td>
                <td><input name="Creative_Time3" id="Creative_Time3" value="<?php echo($prepopulated_data[16]); ?>" type="text"  class="thin_input">
					<select name="Creative_TimeDD3" id="Creative_TimeDD3"  class="thin_dd">
                        <option value="1" title="art/dd_red.png" <?php if ($prepopulated_data[17]=="1") echo('selected = "selected" '); ?> >1</option>
                        <option value="2" title="art/dd_green.png" <?php if ($prepopulated_data[17]=="2") echo('selected = "selected" '); ?> >2</option>
                        <option value="3" title="art/dd_blue.png" <?php if ($prepopulated_data[17]=="3") echo('selected = "selected" '); ?> >3</option>
                  	</select></td>
              </tr>
              <tr>
                <td><label>AS To Client</label></td>
                <td><input name="AS_To_Client2" id="AS_To_Client2" value="<?php echo($prepopulated_data[18]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>Client Time</label></td>
                <td><input name="Client_Time2" id="Client_Time2" value="<?php echo($prepopulated_data[19]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>AS To Approve</label></td>
                <td><input name="AS_To_Approve" id="AS_To_Approve" value="<?php echo($prepopulated_data[20]); ?>" type="text"></td>
              </tr>
              <tr>
                <td><label>AS To Release</label></td>
                <td><input name="AS_To_Release" id="AS_To_Release" value="<?php echo($prepopulated_data[21]); ?>" type="text"></td>
              </tr>
            </table>
          </div>
       
        <div style="text-align:center; margin-top:10px;">
        <p class="submit_left">ACTIVATE PROJECT: </p>
          <input type="submit" name="submitButton" value="OK" onClick="" id="submitButton" title="Create" class="input_btn" style="" />
          <div id="admin_bar">
          	<a href="edit_job.php"><div class="button_edit_job"></div></a>
            <a href="delete_job.php"><div class="button_delete_job"></div></a>
          </div>
        </div>
      </div>
    </div>

</form>
</body>
</html>
