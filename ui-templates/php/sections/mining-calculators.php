<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

			<h3 style='display: inline;'>Mining Calculators</h3>

			<p><?=start_page_html('mining_calculators')?></p>			
			
			<p style='font-weight: bold;'>Chain data (block height, difficulty, etc) on this page is cached for <?=$chainstats_cache?> minute(s).</p>
			
			<p style='color: red;'>Using these mining calculators <i><u>will set this page as the start page</u>, which you can reset afterwards at top left</i>. If you have portfolio data you don't want to lose, be sure you have enabled "Use cookie data to save values between sessions" on the Settings page before using these mining calculators.</p>
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Bitcoin Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/other/calculators/bitcoin-mining-calculator.php"); ?>
				
				
			</fieldset>
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Ethereum Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/other/calculators/ethereum-mining-calculator.php"); ?>
				
				
			</fieldset>
				
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Monero Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/other/calculators/monero-mining-calculator.php"); ?>
				
				
			</fieldset>
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Litecoin Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/other/calculators/litecoin-mining-calculator.php"); ?>
				
				
			</fieldset>
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Decred Mining Calculator</b> </legend>
		    
				<?php require("app-lib/php/other/calculators/decred-mining-calculator.php"); ?>
				
				
			</fieldset>
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>STEEM Power Interest Rate / Power Down Weekly Payout Calculator</b> </legend>
				
				<?php require("app-lib/php/other/calculators/steem-power-interest-calculator.php"); ?>
				
			</fieldset>
		    
		    

