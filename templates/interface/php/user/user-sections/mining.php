<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>

	
<h2 class='bitcoin page_title'>Staking / Mining</h2>
	            

<div class='full_width_wrapper'>
			

			<p style='margin-top: 25px; margin-bottom: 15px;'><?=$ct['gen']->start_page_html('mining')?></p>	
	
    		
   <ul style='margin-top: 25px; font-weight: bold;'>
	
	<li class='bitcoin' style='font-weight: bold;'>Chain data (block height, difficulty, etc) is cached for <?=$ct['conf']['power']['blockchain_stats_cache_time']?> minute(s).</li>
	
	<li class='bitcoin' style='font-weight: bold;'>*CUSTOM* POW mining calculators can be added in the file "dynamic-config.php" (in the app's main directory).</li>	
   
   </ul>		
			
			
			<p style='margin-top: 25px;' class='red'>*Using these mining calculators <i><u>will set this page as the start page</u>, which you can reset afterwards at top left</i>. If you have portfolio data you don't want to lose, be sure you have enabled "Use cookies to save data" on the Settings page before using these mining calculators.</p>
			 
			 <?php
			 foreach( $ct['opt_conf']['mining_calculators']['pow'] as $pow_asset_data ) {
			 ?>
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b><?=$pow_asset_data['name']?> Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/inline/coin-mining-staking/pow/render.php"); ?>
				
				
			</fieldset>
			 <?php
			 }
			 ?>
			
		    
</div> <!-- full_width_wrapper END -->


