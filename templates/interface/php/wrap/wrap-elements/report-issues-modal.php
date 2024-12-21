<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

// Development status DATA SET from github file:
// https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/.dev-status.json
$dev_status = @$ct['api']->dev_status();

?>
	
<!-- START report issues modal -->
<div class='' id="show_report_issues">
	
		
		<h3 class='blue' style='display: inline;'>Report Issues / Check Development Status</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice_modal'></span>
	
	<br clear='all' />
	<br clear='all' />
	
	
	<div class='bitcoin_dotted bitcoin' style='font-weight: bold;'>
	
	If your issue is NOT listed below in the Development Status section, please REPORT IT HERE:<br />
	
	<a href='https://github.com/taoteh1221/Open_Crypto_Tracker/issues' target='_blank'>https://github.com/taoteh1221/Open_Crypto_Tracker/issues</a>
	
	</div>
				
				
	<br />
		
		
	    <fieldset class='subsection_fieldset red'><legend class='subsection_legend red'> <strong>Development Status:</strong> </legend>
	<?php
	if ( is_array($dev_status) && sizeof($dev_status) > 0 ) {
	?>
	
	
	<?php
	}
	else {
	?>
	
	NO DATA AVAILABLE
	
	<?php
	}
	?>
	
	    </fieldset>
	    
	
</div>
<!-- END report issues modal -->
	
	
	<script>
	
	modal_windows.push('.show_report_issues'); // Add to modal window tracking (for closing all dynamically on app reloads) 
	
	$('.show_report_issues').modaal({
	fullscreen: true,
	content_source: '#show_report_issues'
	});
	</script>
	
	

	</script>