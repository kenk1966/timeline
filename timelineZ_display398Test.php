<html>
<head>
<title>Timeline demo</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- above is for reporting charts, see if conflicts with anything -->


<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/custom_timeline/timeline.js"></script>
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="js/custom_timeline/custom-timeline.css">
<link rel="stylesheet" type="text/css" href="css/main.css">

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
    <script type="text/javascript">

$(function() {
	$(".calendar" ).datepicker();
});

//Add Business Days 'addBusDays()', addHours functionality added to Date Class Prototype    
Number.prototype.mod = function(n) {
return ((this%n)+n)%n;
}
Date.prototype.addBusDays = function(dd) {
var wks = Math.floor(dd/5);
var dys = dd.mod(5);
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
	
var timeline;
var timeline_options;
var custom_options;
var creative_num;
var project_data = <?= json_encode($project_data); ?>;
var File_List = <?= json_encode($file_list); ?>;
var Job_Number; 
var Group_Name;

var Start_Date=new Date(); 
var End_Date=new Date(); 
var AS_Opening=new Date(); 
var Creative_Time1=new Date(); 
var Creative_TimeDD1=new Date(); 
var AS_Revision=new Date(); 
var Creative_Time2=new Date(); 
var Creative_TimeDD2=new Date(); 
var AS_To_Client1=new Date(); 
var Client_Time1=new Date(); 
var Unknown_Time=new Date(); 
var Client_Revision=new Date(); 
var AS_To_Creative=new Date(); 
var Creative_Time3=new Date(); 
var Creative_TimeDD3=new Date(); 
var AS_To_Client2=new Date(); 
var Client_Time2=new Date(); 
var AS_To_Approve=new Date(); 
var AS_To_Release=new Date(); 
var Agency_Release=new Date();

var custom_timeline = null;

var bar_AS_Opening="<div class='AS_Opening_Class'>&nbsp;</div>";	
var bar_Creative_Time1="<div class='Creative_Time1_Class'>&nbsp;</div>";
var bar_AS_Revision="<div class='AS_Revision_Class'>&nbsp;</div>";
var bar_Creative_Time2="<div class='Creative_Time2_Class'>&nbsp;</div>"; 
var bar_AS_To_Client1="<div class='AS_To_Client1_Class'>&nbsp;</div>";
var bar_Client_Time1="<div class='Client_Time1_Class'>&nbsp;</div>"; 
var bar_Unknown_Time="<div class='Unknown_Time_Class'>&nbsp;</div>"; 
var bar_Client_Revision="<div class='Client_Revision_Class'>&nbsp;</div>"; 
var bar_AS_To_Creative="<div class='AS_To_Creative_Class'>&nbsp;</div>"; 
var bar_Creative_Time3="<div class='Creative_Time3_Class'>&nbsp;</div>";  
var bar_AS_To_Client2="<div class='AS_To_Client2_Class'>&nbsp;</div>"; 
var bar_Client_Time2="<div class='Client_Time2_Class'>&nbsp;</div>"; 
var bar_AS_To_Approve="<div class='AS_To_Approve_Class'>&nbsp;</div>"; 
var bar_AS_To_Release="<div class='AS_To_Release_Class'>&nbsp;</div>";
var bar_End_Date="<div class='End_Date_Class'>&nbsp;</div>";
var bar_Agency_Release="<div class='Agency_Release_Class'>&nbsp;</div>";
var bar_Creative_Person1="<div class='Creative_Person1_Class'>&nbsp;</div>";
var bar_Creative_Person2="<div class='Creative_Person2_Class'>&nbsp;</div>";
var bar_Creative_Person3="<div class='Creative_Person3_Class'>&nbsp;</div>";
var bar_Creative_Person_Error="<div class='Creative_Person_Error_Class'>&nbsp;</div>";
var bar_Weekend="<div class='Weekend_Class'>&nbsp;</div>";

var crt=new Date();
var crt_start = new Date();
var date_starts = [];
var date_ends = [];
var earliest_job = new Date();
var latest_job = new Date();
var display_group = getCookie("group_status");
var data;
var reports = {};
var Unknown_Remainder = [];
var temp_date = new Date();
var colorsArray = [];
var report_starts = new Date();
var report_ends = new Date();
var report_subtotal = 0;
var first_run_flag = true;

google.load("visualization", "1");
google.setOnLoadCallback(drawVisualization);

function drawVisualization() {
	
	data = new google.visualization.DataTable();
	data.addColumn('datetime', 'start');
	data.addColumn('datetime', 'end');
	data.addColumn('string', 'content');
	data.addColumn('string', 'group');
	
	var proj_len = project_data.length;
	for (var i = 0; i < proj_len; i++)
	{
	
	Project_Type=project_data[i][0];  
	Group_Name = project_data[i][2];
	//append Group Name to front of Job Number for list sorting visibility
	
	// check display_group flag
	if (display_group == "true") {
		Job_Number = Group_Name+" "+File_List[i].slice(0,-4);
	}
	else {
		Job_Number = File_List[i].slice(0,-4);
	}
	
	Start_Date=new Date(project_data[i][3].substring(6,10), project_data[i][3].substring(0,2)-1, project_data[i][3].substring(3,5)); 
	End_Date=new Date(project_data[i][4].substring(6,10), project_data[i][4].substring(0,2)-1, project_data[i][4].substring(3,5)); 
	
	date_starts[i]=new Date(Start_Date.getTime());
	date_ends[i]=new Date(End_Date.getTime());
	
	AS_Opening=Number(project_data[i][5]);	
	Creative_Time1=Number(project_data[i][6]);
	AS_Revision=Number(project_data[i][8]); 
	Creative_Time2=Number(project_data[i][9]); 
	AS_To_Client1=Number(project_data[i][11]); 
	Client_Time1=Number(project_data[i][12]); 
	Unknown_Time=Number(project_data[i][13]); 
	Client_Revision=Number(project_data[i][14]); 
	AS_To_Creative=Number(project_data[i][15]); 
	Creative_Time3=Number(project_data[i][16]);  
	AS_To_Client2=Number(project_data[i][18]); 
	Client_Time2=Number(project_data[i][19]); 
	AS_To_Approve=Number(project_data[i][20]); 
	AS_To_Release=Number(project_data[i][21]);
	 
	Agency_Release=0.25;
	
	bar_Creative_Time1 = WhichCreative(project_data[i][7]);
	bar_Creative_Time2 = WhichCreative(project_data[i][10]);		
	bar_Creative_Time3 = WhichCreative(project_data[i][17]);
	
	//reset current running time to start of each job#
	crt = Start_Date;
	crt=AddSegment(AS_Opening, bar_AS_Opening, Job_Number, crt);		
	crt=AddSegment(Creative_Time1, bar_Creative_Time1 , Job_Number, crt);		
	crt=AddSegment(AS_Revision, bar_AS_Revision , Job_Number, crt);
	crt=AddSegment(Creative_Time2, bar_Creative_Time2 , Job_Number, crt);
	crt=AddSegment(AS_To_Client1, bar_AS_To_Client1 , Job_Number, crt);
	crt=AddSegment(Client_Time1, bar_Client_Time1 , Job_Number, crt);
	crt=AddSegment(Unknown_Time, bar_Unknown_Time , Job_Number, crt);
	crt=AddSegment(Client_Revision, bar_Client_Revision , Job_Number, crt);
	crt=AddSegment(AS_To_Creative, bar_AS_To_Creative , Job_Number, crt);
	crt=AddSegment(Creative_Time3, bar_Creative_Time3 , Job_Number, crt);
	crt=AddSegment(AS_To_Client2, bar_AS_To_Client2 , Job_Number, crt);
	crt=AddSegment(Client_Time2, bar_Client_Time2 , Job_Number, crt);
	crt=AddSegment(AS_To_Approve, bar_AS_To_Approve , Job_Number, crt);
	crt=AddSegment(AS_To_Release, bar_AS_To_Release , Job_Number, crt);
	//below is a special case where End_Date is a date rather than time interval like most 1st params here
	

	temp_date = new Date(crt.getTime());; // get copy of Date before calculating 
	crt=AddSegmentEndDate(End_Date, bar_End_Date , Job_Number, crt);
	
	Unknown_Remainder[i] = Math.abs(crt.getTime() - temp_date.getTime())/ (1000 * 3600 * 24); 
	// subtract temp_date from crt to get unknown extra time at end (will include weekends, oh well)
	
	
	crt=AddSegment(Agency_Release, bar_Agency_Release , Job_Number, crt);
	ColorizeWeekends(date_starts, date_ends, Job_Number);
	}
	
	//TEMP STUFF BELOW
	var todaysDate = new Date(); // get today's date
	var finalDate = addDays(todaysDate, 10); // new date later
	
	custom_options = {
		start: todaysDate,
		end: finalDate,
		width:  "100%",
		height: "auto",
		layout: "box",
		editable: true,
		eventMargin: 10,  // minimal margin between events
		eventMarginAxis: 0, // minimal margin beteen events and the axis
		showMajorLabels: true, //false,
		axisOnTop: true,
		groupsChangeable : true,
		groupsOnRight: false,
		stackEvents: false,
		groupsOrder: true,
		zoomable: true,
		zoomMax: 1814400000  //about a 3 week initial zoom stretch for visible display
		
	};
	
	custom_timeline = new links.Timeline(document.getElementById('timeline_chart'), custom_options);
	custom_timeline.draw(data);
}

function WhichCreative(dropdown_data) {
	switch (dropdown_data) {
    case "1":
        return bar_Creative_Person1;
		break;
	case "2":
        return bar_Creative_Person2;
		break;
	case "3":
        return bar_Creative_Person3;
		break;
    default:
		return bar_Creative_Person_Error;
		break;
	} 
}
	
function AddSegment (segmentTime, whichBar, jobNum, crt) {
	crt_start = new Date(crt.getTime());
	//adds Business days
	var segment_integer = Math.floor(segmentTime);
	var segment_fraction = segmentTime - segment_integer;
	
	crt.addBusDays(segment_integer);
	crt = addDays(crt, segment_fraction);
	
	//*CHECK TO SEE IF AFTER ADDING FRACTION IT PUTS
	//*US INTO SATURDAY (CHECK IT) -- IF SO, ADD EXACTLY 48 HOURS TO IT TO OFFSET CORRECTLY
	
	if (crt.getDay() === 6) {
		crt = addDays(crt, 2); // if fraction makes date fall on saturday, push to same time Monday
	}
	
	data.addRow([crt_start, crt, whichBar, jobNum]);
	
	return crt;
}

function AddSegmentEndDate(End_Date, whichBar, jobNum, crt) {
	// add similar to above but take a bar_End_Date instead of a segmentTime in days
	crt_start = new Date(crt.getTime());	
	data.addRow([crt_start, End_Date, whichBar, jobNum]);
	return End_Date;
}

function getNextDayOfWeek(date, dayOfWeek) {
	var resultDate = new Date(date.getTime());
    resultDate.setDate(date.getDate() + (7 + dayOfWeek - date.getDay()) % 7);
    return resultDate;
}

function addDays(date, days) {
    var result = new Date(date);
	//below uses 'addHours' added to Date() prototype at top of js
	result.addHours(days*24);
    return result;
}

function ShortDateFormat(value)
{
   var y = String(value.getYear());
   var yr = y.slice(1);
   return value.getMonth()+1 + "/" + value.getDate() + "/" + yr;
}

function ColorizeWeekends(starts, ends, jobNum) {
	var start_wknd = new Date();
	var end_wknd = new Date();
	var d = new Date();
	var next_saturday_bod = new Date();
	var next_monday_bod = new Date();
	
	var orderedDates = starts.sort(function(a,b){
        return Date.parse(a) > Date.parse(b);
    });
	
	
	earliest_job = new Date(orderedDates[0].getTime());
	
	var orderedDates = ends.sort(function(a,b){
        return Date.parse(a) < Date.parse(b);
    });
	
	latest_job = new Date(orderedDates[0].getTime());
	
	next_saturday_bod = new Date(getNextDayOfWeek(earliest_job, 6).getTime());
	next_monday_bod = new Date(next_saturday_bod.getTime());
	next_monday_bod.setDate(next_saturday_bod.getDate()+2);
	
	for (var d = next_saturday_bod; d <= latest_job; d.setDate(d.getDate() + 7) ) {
		
		var end_wknd = new Date(d.getTime());
		
		end_wknd.setDate(end_wknd.getDate()+2);
	
		var start_wknd = new Date(d.getTime());
		var len = project_data.length;
		
		for (var i = 0; i < len; i++)
		{
			if (display_group == "true") {
				var job_row = project_data[i][2]+" "+File_List[i].slice(0,-4); //append group name in front
			}
			else {
				var job_row = File_List[i].slice(0,-4); //append group name in front
			}
			data.addRow([ start_wknd, end_wknd, bar_Weekend , job_row]);
		}
	}
}

function Toggle_Group_Flag() {
	display_group = getCookie("group_status");
	
	if (display_group == "true") {
		display_group = "false";
	}
	else 
	{
		display_group = "true";
	}
	setCookie("group_status", display_group, 3000);
	
	location.reload();
}

function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname+"="+cvalue+"; "+expires;
}

function getCookie(cname) {
	
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) {
	
            return c.substring(name.length, c.length);
        }
    }
	setCookie("group_status", "true", 3000);
    return true;
}


function ChooseReport(which_creative) {
	$( "#reports_area" ).removeClass( "hide" );
	$( "#report_title").text(which_creative);
	reports = {};
	reports.which = which_creative;
	
	GenerateReport();
}

function GenerateReport() {
	
	var proj_len = project_data.length;
	for (var i = 0; i < proj_len; i++)
	{
	Project_Type=project_data[i][0];  
	Group_Name = project_data[i][2];
	if (display_group == "true") {
		Job_Number = Group_Name+" "+File_List[i].slice(0,-4);
	}
	else {
		Job_Number = File_List[i].slice(0,-4);
	}
	///initialize object so all data filled out with zeros before starting
	reports[Job_Number]=[0,0,0,0,0,0];
	var time_account = 0;
	var time_creative1 = 0;
	var time_creative2 = 0;
	var time_creative3 = 0;
	var time_client = 0;
	var time_standby = 0;
	
	
	
	Start_Date=new Date(project_data[i][3].substring(6,10), project_data[i][3].substring(0,2)-1, project_data[i][3].substring(3,5)); 
	End_Date=new Date(project_data[i][4].substring(6,10), project_data[i][4].substring(0,2)-1, project_data[i][4].substring(3,5));
	
	date_starts[i]=new Date(Start_Date.getTime());
	date_ends[i]=new Date(End_Date.getTime());
	
	AS_Opening=Number(project_data[i][5]);	
	Creative_Time1=Number(project_data[i][6]);
	  
	AS_Revision=Number(project_data[i][8]); 
	Creative_Time2=Number(project_data[i][9]); 
	 
	AS_To_Client1=Number(project_data[i][11]); 
	Client_Time1=Number(project_data[i][12]); 
	Unknown_Time=Number(project_data[i][13]); 
	Client_Revision=Number(project_data[i][14]); 
	AS_To_Creative=Number(project_data[i][15]); 
	Creative_Time3=Number(project_data[i][16]);  
	
	AS_To_Client2=Number(project_data[i][18]); 
	Client_Time2=Number(project_data[i][19]); 
	AS_To_Approve=Number(project_data[i][20]); 
	AS_To_Release=Number(project_data[i][21]);
	 
	Agency_Release=0.25;
	
	bar_Creative_Time1 = WhichCreative(project_data[i][7]);
	bar_Creative_Time2 = WhichCreative(project_data[i][10]);		
	bar_Creative_Time3 = WhichCreative(project_data[i][17]);
	
	time_account += AS_Opening + AS_Revision + AS_To_Client1 + AS_To_Creative + AS_To_Client2 + AS_To_Approve + AS_To_Release;
	
	/*console.log (" AS_Opening: "+AS_Opening+" AS_Revision: "+AS_Revision+" AS_To_Client1: "+AS_To_Client1+" AS_To_Creative: "+AS_To_Creative+" AS_To_Client2: "+AS_To_Client2+" AS_To_Approve: "+AS_To_Approve+" AS_To_Release: "+AS_To_Release+"   TOTAL_account: "+time_account);*/
	
	switch (project_data[i][7]) {
    case "1":
        time_creative1 += Creative_Time1;
		break;
	case "2":
        time_creative2 += Creative_Time1;
		break;
	case "3":
        time_creative3 += Creative_Time1;
		break;
    default:
		time_creative1 += Creative_Time1;
		break;
	} 
	
	switch (project_data[i][10]) {
    case "1":
        time_creative1 += Creative_Time2;
		break;
	case "2":
        time_creative2 += Creative_Time2;
		break;
	case "3":
        time_creative3 += Creative_Time2;
		break;
    default:
		time_creative1 += Creative_Time2;
		break;
	} 
	
	switch (project_data[i][17]) {
    case "1":
        time_creative1 += Creative_Time3;
		break;
	case "2":
        time_creative2 += Creative_Time3;
		break;
	case "3":
        time_creative3 += Creative_Time3;
		break;
    default:
		time_creative1 += Creative_Time3;
		break;
	} 
	
	time_client += Client_Time1 + Client_Time2;
	time_standby += Unknown_Time + Unknown_Remainder[i];
	
	console.log ('Job_Number at time of reports creation = '+Job_Number);
	
	reports[Job_Number]=[time_account,time_creative1,time_creative2,time_creative3,time_client,time_standby];
	
	}
	DrawReportsChart();	
}


function CalcSegmentEndDate(End_Date, whichBar, jobNum, crt) {
	// add similar to above but take a bar_End_Date instead of a segmentTime in days
	crt_start = new Date(crt.getTime());	
	
	return End_Date;
}

function DrawReportsChart() {
	//google.charts.load('current', {'packages':['bar']});
	
	if (first_run_flag) {
	
	google.charts.load('current', {packages: ['corechart', 'bar']});
	first_run_flag = false;
	}
	
      google.charts.setOnLoadCallback(drawChart);
	  
}

function drawChart() {
		//var chart; //DELETE THIS?  TRYING TO CLEAR CHART FOR EACH TIME
		  
		data = new google.visualization.DataTable();
		data.addColumn('string', 'Job');
		
		switch (reports.which) {
				case "creative1":
					data.addColumn('number', 'Creative 1');
					colorsArray = ['#ed1c85'];
					break;
				case "creative2":
					data.addColumn('number', 'Creative 2');
					colorsArray = ['#5cc533'];
					break;
				case "creative3":
					data.addColumn('number', 'Creative 3');
					colorsArray = ['#00A7E9'];
					break;
				case "client":
					data.addColumn('number', 'Client');
					colorsArray = ['orange'];
					break;
				case "account":
					data.addColumn('number', 'Account Services');
					colorsArray = ['silver'];
					break;
				case "standby":
					data.addColumn('number', 'Stand By');
					colorsArray = ['black'];
					break;
				
				//below, default means any other individual bar display besides 'all'
				default:
					data.addColumn('number', 'Creative 1');
					data.addColumn('number', 'Creative 2');
					data.addColumn('number', 'Creative 3');
					data.addColumn('number', 'Client');
					data.addColumn('number', 'Account Services');
					data.addColumn('number', 'Stand By');
					colorsArray = ['#ed1c85', '#5cc533', '#00A7E9', 'orange', 'silver', 'black'];
					break;
			} 
		
        var options = {
		  
		  title: 'Reporting - '+ reports.which.toUpperCase() + ' Hours per Job',
		  chartArea: {width: '90%', height: '60%'},
		  width:1250,
		  vAxis: {format: 'decimal'},
		  legend: { position: 'none' },
		  hAxis: {showTextEvery: 1, slantedText: true, slantedTextAngle: 90/*, viewWindow:{max:103}*/},
		  colors: colorsArray
        };
		
		
		var proj_len = project_data.length;
		for (var i = 0; i < proj_len; i++)
		{ 
			Group_Name = project_data[i][2];
			if (display_group == "true") {
				Job_Number = Group_Name+" "+File_List[i].slice(0,-4);
				
			}
			else {
				Job_Number = File_List[i].slice(0,-4);
			}
		
		console.log ('Job_Number at time of reports chart draw = '+Job_Number);
		
			switch (reports.which) {
		
				case "creative1":
					data.addRow([Job_Number,8*reports[Job_Number][1]]);
					break;
				case "creative2":
					data.addRow([Job_Number,8*reports[Job_Number][2]]);
					break;
				case "creative3":
					data.addRow([Job_Number,8*reports[Job_Number][3]]);
					break;
				case "account":
					data.addRow([Job_Number,8*reports[Job_Number][0]]);
					break;
				case "client":
					data.addRow([Job_Number,8*reports[Job_Number][4]]);
					break;
				case "standby":
					data.addRow([Job_Number,8*reports[Job_Number][5]]);
					break;
				//below, default shows all data reporting bars
				default:
					data.addRow([Job_Number, 8*reports[Job_Number][1],8*reports[Job_Number][2],8*reports[Job_Number][3],8*reports[Job_Number][0],8*reports[Job_Number][4],8*reports[Job_Number][5]]);
					break;
			} 
	
		}
		
		var chart = new google.visualization.ColumnChart(document.getElementById('reports_column_chart'));
		$( "#report_chart" ).removeClass( "hide" );
		
		//test here of sorting columns
		data.sort([{column: 0}]);
		
        chart.draw(data, options);
}
</script>
</head>

<body>
<div id="wrapper">
<iframe id="form_panel" src="project_info_entry.php" class="iframe_class" scrolling="no"></iframe>


<div id="container">
  <div id="panelBox">
      <h1 style="display:inline; vertical-align:center; margin-right:30px;">DD Resources Timeline</h1>
      <a href="javascript:location.reload(true)" class="button_green">CLICK TO REDRAW CHARTS</a>
      <a href="javascript:Toggle_Group_Flag()" class="button_blue">CLICK TO TOGGLE GROUP DISPLAY</a>
      <div style="width:100%" id="trace_text"></div>

      <div id="timeline_legend">
      	<span onclick="ChooseReport('generic')" class="legend_click">CLICK HERE FOR COMPLETE REPORTS - OR BY CATEGORY:</span>
        <span onclick="ChooseReport('creative1')" class="legend_click"><div class="legend_box" style="background:#ed1c85"></div>CREATIVE 1</span>
        <span onclick="ChooseReport('creative2')" class="legend_click"><div class="legend_box" style="background:#5cc533"></div>CREATIVE 2</span>
        <span onclick="ChooseReport('creative3')" class="legend_click"><div class="legend_box" style="background:#00A7E9"></div>CREATIVE 3</span>
        <span onclick="ChooseReport('client')" class="legend_click"><div class="legend_box" style="background:orange"></div>CLIENT</span>
        <span onclick="ChooseReport('account')" class="legend_click"><div class="legend_box" style="background:silver"></div>ACCOUNT SERVICES</span>
        <span onclick="ChooseReport('standby')" class="legend_click"><div class="legend_box" style="background:black"></div>STAND BY</span>
        <span><div class="legend_box" style="background:purple"></div>AGENCY RELEASE DAY</span>
      </div>
      <div id="reports_area" class="hide">
          <h4>REPORTS &nbsp;<span id="report_title"></span></h4>
          <div id="report_dates">
             
          </div>
          <div id="report_chart" class="hide">
            <div id="report_content1"></div>
            <div id="reports_column_chart" style="width: 1100px; height: 500px;">COLUMN CHART HERE</div>
          </div>
      </div>
      <div id="timeline_chart"></div>
      
	</div>
  </div>
</div>

</body>
</html>
