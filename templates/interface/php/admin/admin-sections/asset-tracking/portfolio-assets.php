<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


<?php
if ( $ct['admin_area_sec_level'] == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {
?>
	
	<div id='add_markets_ajax' style='margin: 1em;'>
	
	
     	Search for a ticker, OR ticker/pair (eg: BTC, or SOL/ETH):<br /><br />
     	
     	<input type='text' id='add_markets_search' value='' size='15' />
     	
     	<button class='force_button_style' onclick='
     	
     	var add_markets_search = { "add_markets_search": $("#add_markets_search").val() };
     	
     	ct_ajax_load("type=add_markets&step=1", "#add_markets_ajax", "markets search results", add_markets_search, true); // Secured
     	
     	'> Search </button>

	
	</div>
    
	
<?php
}
?>	