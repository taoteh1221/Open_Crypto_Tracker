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
	
	<p> Coming Soon&trade; </p>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ***CAN STILL EDIT THIS SECTION*** BY SWITCHING TO HIGH SECURITY ADMIN MODE (in the Security section), and update it via the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor.
	
	</p>
	
	<?php
     
     // DEBUGGING...this can be a ticker by itself like 'sol', OR INCLUDE A PAIRING like 'sol/btc'
	//var_dump( $ct['api']->ticker_markets_search('sol/btc') );

	?>
    
	
<?php
}
?>	