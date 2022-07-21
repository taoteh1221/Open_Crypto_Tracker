<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// HTML field formatting CONFIGs for admin settings

$admin_ui_menus['dev']['dropdown'] = array(

                                           'error_reporting' => array(
                                                                     'PHP Error Reporting' => array(
                                                                                                   'Off' => 0,
                                                                                                   'On' => -1
                                                                                                   )
                                                                     ),
                                                                     
                                                                     
                                           );

// END of $admin_ui_menus['dev']['dropdown']
                                           

//var_dump($admin_ui_menus);

?>
	
	<div class='bitcoin align_center' style='margin-bottom: 20px;'>(advanced configuration, handle with care)</div>
				
				
	<p> Coming Soon&trade; </p>
				
	<p class='bitcoin'> Editing these settings is <i>currently only available manually</i>, by updating the file config.php (in this app's main directory: <?=$base_dir?>) with a text editor.</p>
				
	
		    