<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


if ( $_GET['hide_charts'] != '' ) {
$hide_charts = explode(',', rtrim($_GET['hide_charts'],',') );
}
?>

  
<input type='hidden' id='hide_charts' value='<?=$_GET['hide_charts']?>' />
		
		
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

	var hide_charts = document.getElementById("hide_charts").value;
	
	if ( hide_charts != "" ) {
	window.location.href = this.value + request_start + "hide_charts=" + hide_charts + anchor;
	}
	else {
	window.location.href = this.value + anchor;
	}
	
	'>
		<option value='index.php'> Show Portfolio Value Page First </option>
		<option value='index.php?start_page=charts' <?=( $_GET['start_page'] == 'charts' ? 'selected' : '' )?> > Show Charts Page First </option>
	</select>
</p>
			
			
<button class="hide_chart_settings force_button_style">Hide / Unhide Charts</button>


<div id="hide_chart_settings" style="display:none;">

	<h3>Hide / Unhide Charts</h3>
	
<?php
foreach ( $exchange_price_alerts as $key => $value ) {
?>
	<p><input type='checkbox' value='<?=$key?>' onchange='

	var hide_charts = document.getElementById("hide_charts").value;
	
		if ( this.checked == true ) {
		document.getElementById("hide_charts").value = hide_charts + this.value + ",";
		}
		else {
		document.getElementById("hide_charts").value = hide_charts.replace("<?=$key?>,", "");
		}
	
' <?=( in_array($key, $hide_charts) ? 'checked' : '' )?> /> Hide "<?=$key?>" chart</p>
<?php
}
?>

	<p><button class='force_button_style' onclick='javascript:window.location.href = "index.php<?=( $_GET['start_page'] == 'charts' ? '?start_page=charts&' : '?' )?>hide_charts=" + document.getElementById("hide_charts").value + "<?=( $_GET['start_page'] == 'charts' ? '#charts' : '' )?>";'>Update Hidden Charts</button></p>

	<p style='color: red;'>*Although you can persist hiding charts pretty good without enabling cookie data, to fully persist hiding charts it's recommended to enable "Use cookie data to save values between sessions" on the Program Settings page. For instance, updating coin values or markets in your portfolio will reset back to showing all charts. But <i>if you enable cookie data before hiding your charts, your hidden charts will stay hidden</i>.</p>

</div>


<script>
$('.hide_chart_settings').modaal({
	content_source: '#hide_chart_settings'
});
</script>


<br />
  
  
<p><a style='color: red; font-weight: bold;' class='show' id='chartsnotice' href='#show_chartsnotice' title='Click to show charts notice.' onclick='return false;'><b>Charts Notice</b></a></p>
    
<div style='display: none;' class='show_chartsnotice' align='left'>
            	
     
	<p style='font-weight: bold; color: red;'>Charts are only generated here for each price alert that is properly configured in the configuration file (config.php). Price alerts must be <a href='README.txt' target='_blank'>setup as a cron job on your web server</a> or they will not work, <i>and the charts here will not work either</i> (they will remain blank). The charts page, chart caching, and chart javascript can be disabled in the configuration file (config.php).
	
<br /><br />
	Although you can persist hiding charts pretty good without enabling cookie data, to fully persist hiding charts it's recommended to enable "Use cookie data to save values between sessions" on the Program Settings page. For instance, updating coin values or markets in your portfolio will reset back to showing all charts. But <i>if you enable cookie data before hiding your charts, your hidden charts will stay hidden</i>.</p>

            
</div>


<?php

// Render the charts
foreach ( $exchange_price_alerts as $key => $value ) {
	
	if ( !$hide_charts || !in_array($key, $hide_charts) ) {
?>

<div style='background-color: #515050; border: 1px solid #808080; border-radius: 5px;' id='<?=$key?>_chart'></div>
<script src='app-lib/js/chart.js.php?type=asset&asset_data=<?=urlencode($key)?>' async></script>
<br/><br/><br/>

<?php
	}
	
}
?>


