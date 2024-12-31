<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

?>
	
<!-- START report issues modal -->
<div class='' id="show_report_issues">
	
		
		<h3 class='blue' style='display: inline;'>Report Issues / Check Development Status</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice_modal'></span>
	
	<br clear='all' />
	<br clear='all' />
	
	
	<div class='blue_dotted blue' style='font-weight: bold;'>
	
	If your issue is NOT listed below in the Development Status section, please REPORT IT HERE:<br />
	
	<a href='https://github.com/taoteh1221/Open_Crypto_Tracker/issues' target='_blank'>https://github.com/taoteh1221/Open_Crypto_Tracker/issues</a>
	
	</div>
				
    		
   <ul style='margin-top: 25px; font-weight: bold;'>
	
	<li class='bitcoin' style='font-weight: bold;'>Every 90 minutes (in the interface, during user interaction / auto-reloads), this app checks for development status alerts (related to security / functionality / etc), at this location on Github.com:<br />
	<a href='https://github.com/taoteh1221/Open_Crypto_Tracker/blob/main/.dev-status.json' target='_blank'>https://github.com/taoteh1221/Open_Crypto_Tracker/blob/main/.dev-status.json</a></li>	
	
	<li class='bitcoin' style='font-weight: bold;'>ALL Github.com code (update) commits are SECURED with this GPG Key:<br />
	<a href='https://github.com/taoteh1221/Open_Crypto_Tracker/blob/main/taoteh1221-gpg-pub-key.asc' target='_blank'>https://github.com/taoteh1221/Open_Crypto_Tracker/blob/main/taoteh1221-gpg-pub-key.asc</a></li>	
	
	<li class='bitcoin' style='font-weight: bold;'>For security reasons, you MUST login to check YOUR app version number (found in your app install, at: "Admin Area => System Monitoring => System Stats").</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>RED highlighting indicates IMPORTANT notification(s).</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>Entries are sorted newest to oldest.</li>	
   
   </ul>
		
	
	<?php
	
	if ( $ct['dev']['status_data_found'] ) {
	     
	     foreach ( $ct['dev']['status'] as $dev_alert ) {
	          
	          if ( $dev_alert['dummy_entry'] ) {
	          continue;
	          }
	          
	?>
	
	<fieldset class='subsection_fieldset <?=( $dev_alert['very_important'] ? 'red' : 'bitcoin' )?>'><legend class='subsection_legend <?=( $dev_alert['very_important'] ? 'red' : 'bitcoin' )?>'> <strong><?=date('Y-m-d', $dev_alert['timestamp'])?></strong> </legend>
	
	<b><u><i>Affected Version(s):</i></u> &nbsp; v<?=$dev_alert['affected_version']?> <?=( $dev_alert['affected_earlier'] ? ' and earlier' : '' )?></b><br /><br />
	
	<?=$dev_alert['affected_desc']?>
	
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