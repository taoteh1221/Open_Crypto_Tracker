<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


if ( $_GET['show_charts'] != '' ) {
$show_charts = explode(',', rtrim($_GET['show_charts'],',') );
}
?>

  
<input type='hidden' id='show_charts' value='<?=$_GET['show_charts']?>' />
		
		
<p>
	<select id='page_select' onchange='
	
		if ( this.value == "index.php?start_page=charts" ) {
		var request_start = "&";
		var anchor = "#charts";
		}
		else {
		var request_start = "?";
		var anchor = "";
		}

	var show_charts = document.getElementById("show_charts").value;
	
	if ( show_charts != "" ) {
	window.location.href = this.value + request_start + "show_charts=" + show_charts + anchor;
	}
	else {
	window.location.href = this.value + anchor;
	}
	
	'>
		<option value='index.php'> Show Portfolio Value Page First </option>
		<option value='index.php?start_page=charts' <?=( $_GET['start_page'] == 'charts' ? 'selected' : '' )?> > Show Charts Page First </option>
	</select>
</p>
			
			
<button class="show_chart_settings force_button_style">Show / Hide Charts</button>


<div id="show_chart_settings" style="display:none;">

	<h3>Show / Hide Charts</h3>
	
<?php
foreach ( $exchange_price_alerts as $key => $value ) {
?>
	<p><input type='checkbox' value='<?=$key?>' onchange='

	var show_charts = document.getElementById("show_charts").value;
	
		if ( this.checked == true ) {
		document.getElementById("show_charts").value = show_charts + this.value + ",";
		}
		else {
		document.getElementById("show_charts").value = show_charts.replace("<?=$key?>,", "");
		}
	
' <?=( in_array($key, $show_charts) ? 'checked' : '' )?> /> Show "<?=$key?>" chart</p>
<?php
}
?>

	<p><button class='force_button_style' onclick='javascript:window.location.href = "index.php<?=( $_GET['start_page'] == 'charts' ? '?start_page=charts&' : '?' )?>show_charts=" + document.getElementById("show_charts").value + "<?=( $_GET['start_page'] == 'charts' ? '#charts' : '' )?>";'>Update Shown Charts</button></p>

	<p style='color: red;'>*Charts are hidden by default to increase page loading speed. You can persist showing charts without enabling cookie data, but to fully persist it's recommended to enable "Use cookie data to save values between sessions" on the Program Settings page. Updating coin values or markets in your portfolio will reset to hiding all charts without cookie data enabled, but <i>if you enable cookie data before showing your charts, your charts will stay visible all the time</i>.</p>

</div>


<script>
$('.show_chart_settings').modaal({
	content_source: '#show_chart_settings'
});
</script>


<br />
  
  
<p><a style='color: red; font-weight: bold;' class='show' id='chartsnotice' href='#show_chartsnotice' title='Click to show charts notice.' onclick='return false;'><b>Charts Notice</b></a></p>
    
<div style='display: none;' class='show_chartsnotice' align='left'>
            	
     
	<p style='font-weight: bold; color: red;'>Charts are only available to show here for each price alert that is properly configured in config.php. Price alerts must be <a href='README.txt' target='_blank'>setup as a cron job on your web server</a>, or <i>the charts here will not work</i> (they will remain blank). The chart's tab, page, caching, and javascript can be disabled in config.php.
	
<br /><br />
	Charts are hidden by default to increase page loading speed. You can persist showing charts without enabling cookie data, but to fully persist it's recommended to enable "Use cookie data to save values between sessions" on the Program Settings page. Updating coin values or markets in your portfolio will reset to hiding all charts without cookie data enabled, but <i>if you enable cookie data before showing your charts, your charts will stay visible all the time</i>.</p>

            
</div>


<?php

// Render the charts
foreach ( $exchange_price_alerts as $key => $value ) {
	
	if ( in_array($key, $show_charts) ) {
?>

<div style='background-color: #515050; border: 1px solid #808080; border-radius: 5px;' id='<?=$key?>_chart'></div>
<script src='app-lib/js/chart.js.php?type=asset&asset_data=<?=urlencode($key)?>' async></script>
<br/><br/><br/>

<?php
	}
	
}
?>


