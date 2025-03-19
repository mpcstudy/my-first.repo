<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/session_include.php";
?>

<?

$func -> checkAdmin("/admin/index.php");


////////////////////////
// Get Visit Last 7 Days 
////////////////////////

$strVisitList = "";

for ($cnt =0; $cnt <30; $cnt++) {
	$setDate = "-".$cnt." day";
	$getDate = date("Ymd", strtotime($setDate)); 

	$query = "SELECT SUM(CNT_NUMBER) FROM tbl_counter where CNT_DATE LIKE '$getDate%'";
	$result = $jdb->fQuery($query, "query error");
//echo "[$query]";
	$todayCnt = $result[0];
	//echo "[$getDate][$todayCnt]";
	if ($cnt == 0) {
		$nowDayCnt = $todayCnt;
		if ($nowDayCnt == "") $nowDayCnt = 0;
	}
	if ($todayCnt =="") $todayCnt = 0;
	$todayWidth = ($todayCnt);
	$getDateStr = $func -> convertFormat ($getDate, 5);
	$resultSTR .= "['".$getDateStr."' ,".$todayCnt.", 'color: #d95f02'],";			
}

///////////////////
// Get Geo Visit
///////////////////

$qry_geo = "SELECT CNT_INFO_COUNTRY, count(CNT_INFO_COUNTRY) as CNT
					FROM tbl_count_details 
					where CNT_INFO_COUNTRY IS NOT NULL 
					group by CNT_INFO_COUNTRY
					ORDER BY CNT_INFO_COUNTRY ASC ";
$rt_geo = $jdb->nQuery($qry_geo, "list error");
//echo "[$qry_geo]";
$regeoSTR = "";

while($lt_geo=mysqli_fetch_array($rt_geo, MYSQLI_ASSOC)) {
		$regeoSTR .= "['".$lt_geo['CNT_INFO_COUNTRY']."' ,".$lt_geo['CNT']."],";	
}
	
///////////////////
// Get Total Visit
///////////////////

$query = "SELECT SUM(CNT_NUMBER) FROM tbl_counter ";
$result = $jdb->fQuery($query, "query error");

$getTotalVisit = $result[0];


?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    google.charts.load('current', {
      'packages':['geochart'],
    });
    google.charts.setOnLoadCallback(drawRegionsMap);

    function drawRegionsMap() {
      var data = google.visualization.arrayToDataTable([
        ['Country', 'Visitors'],

<?=$regeoSTR?>
        
/*
        ['Germany', 200],
        ['United States', 300],
        ['Brazil', 400],
        ['Canada', 500],
        ['France', 600],
        ['RU', 700]
*/
      ]);

      var options = {
      	colorAxis: {colors: ['green', 'blue']} // orange to blue
      	};

      var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

      chart.draw(data, options);
    }
      
      

	  google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
		  function drawChart() {
		    var data = google.visualization.arrayToDataTable([
			    ['Date', '# of Visits', { role: 'style' }],

<?=$resultSTR?>

/*
	    ['05/02', 12],
	    ['05/02', 18],
	    ['05/02', 12],
	    ['05/02', 20],
	    ['05/02', 12],
	    ['05/02', 18],
	    ['05/02', 12],
	    ['05/02', 20],
	    ['05/02', 12],
*/

			  ]);

		    var options = {
		      title: 'Last 30 Days',   
		      subtitle: '',
		      legend: { position: "none" },
		      hAxis: {title: 'Date', titleTextStyle: {color: 'black'}},
		      vAxis: {title: '# of Visits', titleTextStyle: {color: '#d95f02'}}
		    };

		    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
		    chart.draw(data, options);
		  }


			$(window).resize(function(){
			        drawChart();
			        drawRegionsMap();
			});


</script>


<style>
.chart {
	border: 0px solid #AAA;
  width: 100%; 
  min-height: 500px;
  font-size:20px;
}
.row {
  margin:0 !important;
}
</style>

<style type="text/css">
input[type=submit] {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0px;
  border:none;
  font-family: open-sans, sans-serif;
  color: #ffffff;
  font-size: 14px;
  background: #0F216C;
  padding: 8px 18px 8px 18px;
  text-decoration: none;
}

input[type=submit]:hover {
  background: #1734A8;
  color: #ffffff;
  text-decoration: none;
}

.update {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0px;
  font-family: open-sans, sans-serif;
  color: #ffffff;
  font-size: 14px;
  background: #0F216C;
  padding: 8px 18px 8px 18px;
  text-decoration: none;
}

.update:hover {
  background: #1734A8;
  color: #ffffff;
  text-decoration: none;
}

</style>

<p><img src="images/icon-list.png" align="absmiddle"><font class="title">Welcome MPCSolution Admin Page</font></p>


<div style="line-height:10px;">&nbsp;</div> 


<!-- Start of Question Information -->

<p><img src="images/icon-narr.png" align="absmiddle"><font class="stitle">WEBSITE VISIT - REGION </font></p>


<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-left-width:3px; border-right-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:solid;"> 
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Total</td>
	    <td width=85% colspan=3 ><div id="regions_div" ></div></td>		  	  		  	
	  </tr>	
</table>

<br />

<p><img src="images/icon-narr.png" align="absmiddle"><font class="stitle">WEBSITE VISIT COUNT</font></p>


<table width="95%" align="center"  border="0" cellpadding="0" cellspacing="1" style="border-top-width:3px; border-left-width:3px; border-right-width:3px; border-bottom-width:3px; border-color:#dddddd; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:solid;">
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Total Visits</td>
	    <td width=35%  class="tl"><?=number_format($getTotalVisit)?></td>		  	  		  	
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Today Visits</td>
	    <td width=35%  class="tl"><?=$nowDayCnt?></td>	
	  </tr>		  
	  <tr>
	    <td height="1" colspan="4" style="border-bottom-width:1px; border-bottom-color:#cccccc; border-bottom-style:dotted;"></td>
	  </tr>	  
	  <tr>
	    <td width=15% height="25" bgcolor="#E4E4E4" class="tl"><img src="images/icon-round.png" hspace="10" align="absmiddle">Last 30 Days</td>
	    <td width=85% colspan=3 ><div id="chart_div" class="chart"></div></td>		  	  		  	
	  </tr>	
</table>

<br />

<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width=150 height="<?=$TMP_LENGTH?>">&nbsp;</td>
  </tr>
</table>