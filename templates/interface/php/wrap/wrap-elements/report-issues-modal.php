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
	
	
	<div class='bitcoin_dotted blue' style='font-weight: bold;'>
	
	If your issue is NOT listed below in the Development Status section, please REPORT IT HERE:<br />
	
	<a href='https://github.com/taoteh1221/Open_Crypto_Tracker/issues' target='_blank'>https://github.com/taoteh1221/Open_Crypto_Tracker/issues</a>
	
	</div>
				
				
	<br />
		
		
	Every 90 minutes, this app checks for development status alerts (related to security / functionality / etc), at this location on github.com:<br />
	<a href='https://github.com/taoteh1221/Open_Crypto_Tracker/blob/main/.dev-status.json' target='_blank'>https://github.com/taoteh1221/Open_Crypto_Tracker/blob/main/.dev-status.json</a><br /><br />
	
	<?php
	if ( is_array($dev_status) && sizeof($dev_status) > 0 ) {
     
     //var_dump($dev_status);	     
	     
	     foreach ( $dev_status as $dev_alert ) {
	          
	          if ( $dev_alert['dummy_entry'] ) {
	          continue;
	          }
	          
	?>
	
	<fieldset class='subsection_fieldset <?=( $dev_alert['very_important'] ? 'red' : 'bitcoin' )?>'><legend class='subsection_legend <?=( $dev_alert['very_important'] ? 'red' : 'bitcoin' )?>'> <strong><?=date('Y-m-d', $dev_alert['timestamp'])?> (UTC)</strong> </legend>
	
	<?=$dev_alert['affected_desc']?><br /><br />
	
	Affected Version(s): v<?=$dev_alert['affected_version']?> <?=( $dev_alert['affected_earlier'] ? ' and earlier' : '' )?>
	
	</fieldset>
	
	<?php
	    }
	    
	}
	else {
	?>
	
	NO DATA AVAILABLE
	
	<?php
	}
	?>
	    
	
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