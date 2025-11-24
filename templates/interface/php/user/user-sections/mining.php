<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>

	
<h2 class='bitcoin page_title'>Staking / Mining</h2>
	            

<div class='full_width_wrapper'>
			

	<p style='margin-top: 0.5em; margin-bottom: 2em;'>
	
	<?=$ct['gen']->start_page_html('mining')?>
	
			&nbsp; &nbsp; <span class='blue' style='font-weight: bold;'>App Reload:</span> <select title='Auto-Refresh MAY NOT WORK properly on mobile devices (phone / laptop / tablet / etc), or inactive tabs.' class='browser-default custom-select select_auto_refresh' name='select_auto_refresh' onchange='
			 auto_reload(this);
			 '>
				<option value='0'> Manually </option>
				<option value='300'> 5 Minutes </option>
				<option value='600'> 10 Minutes </option>
				<option value='900'> 15 Minutes </option>
				<option value='1800'> 30 Minutes </option>
			</select> 
			
			&nbsp; <span class='reload_notice red'></span>		
		
		
	         </p>	
	
    		
   <ul style='margin-top: 25px; font-weight: bold;'>
	
	<li class='bitcoin' style='font-weight: bold;'>Chain data MAY be cached for a few minutes.</li>
	
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


