<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='max_1200px_wrapper'>

			
				
				<span class='red countdown_notice'></span>
			

			<p style='margin-top: 15px; margin-bottom: 15px;'><?=$pt_gen->start_page_html('mining')?></p>	
	
    		
   <ul style='margin-top: 25px; font-weight: bold;'>
	
	<li class='bitcoin' style='font-weight: bold;'>Chain data (block height, difficulty, etc) is cached for <?=$pt_conf['power']['chainstats_cache_time']?> minute(s).</li>	
   
   </ul>		
			
			
			<p style='margin-top: 25px;' class='red'>*Using these mining calculators <i><u>will set this page as the start page</u>, which you can reset afterwards at top left</i>. If you have portfolio data you don't want to lose, be sure you have enabled "Use cookies to save data" on the Settings page before using these mining calculators.</p>
			 
			 <?php
			 foreach( $pt_conf['power']['mining_calculators']['pow'] as $pow_coin_data ) {
			 ?>
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b><?=$pow_coin_data['name']?> Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/other/calculators/mining/pow/render.php"); ?>
				
				
			</fieldset>
			 <?php
			 }
			 ?>
			
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>HIVE Power Interest Rate / Power Down Weekly Payout Calculator</b> </legend>
				
				<?php require("app-lib/php/other/calculators/mining/pos/hive-power-interest-calculator.php"); ?>
				
			</fieldset>
		    
		    
</div> <!-- max_1200px_wrapper END -->


