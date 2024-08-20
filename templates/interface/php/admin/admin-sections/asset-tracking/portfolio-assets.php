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
	
<div id='update_markets_ajax' style='margin: 1em;'>
	
	
    <button class='force_button_style input_margins' onclick='
     	
     ct_ajax_load("type=add_markets&step=1", "#update_markets_ajax", "add / remove asset markets", false, true); // Secured
     	
     '> Add / Remove Asset Markets </button>



</div>    
	
<?php
}
?>	