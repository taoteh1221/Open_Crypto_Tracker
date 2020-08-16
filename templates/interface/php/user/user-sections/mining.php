<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='max_1200px_wrapper'>

			

			<h4 style='display: inline;'>Mining</h4>
				
				<span class='red countdown_notice'></span>
			

			<p style='margin-top: 15px; margin-bottom: 15px;'><?=start_page_html('mining')?></p>			
			
			<p class='bitcoin' style='font-weight: bold;'>Chain data (block height, difficulty, etc) is cached for <?=$app_config['power_user']['chainstats_cache_time']?> minute(s).</p>
			
			<p class='red'>Using these mining calculators <i><u>will set this page as the start page</u>, which you can reset afterwards at top left</i>. If you have portfolio data you don't want to lose, be sure you have enabled "Use cookies to save data" on the Settings page before using these mining calculators.</p>
			  
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Bitcoin Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/other/calculators/mining/bitcoin-mining-calculator.php"); ?>
				
				
			</fieldset>
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Ethereum Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/other/calculators/mining/ethereum-mining-calculator.php"); ?>
				
				
			</fieldset>
				
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Monero Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/other/calculators/mining/monero-mining-calculator.php"); ?>
				
				
			</fieldset>
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Decred Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/other/calculators/mining/decred-mining-calculator.php"); ?>
				
				
			</fieldset>
			
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>HIVE Power Interest Rate / Power Down Weekly Payout Calculator</b> </legend>
				
				<?php require("app-lib/php/other/calculators/mining/hive-power-interest-calculator.php"); ?>
				
			</fieldset>
		    
		    
</div> <!-- max_1200px_wrapper END -->


