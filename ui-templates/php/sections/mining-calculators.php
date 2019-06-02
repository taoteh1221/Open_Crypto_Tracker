<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

			<div style='border: 2px dotted red; font-weight: bold; padding: 9px; color: red;'><a href='index.php' style='color: red;'>Click Here To Reset Default Tab / Calculators</a></div>
			
			<p style='font-weight: bold; color: red;'>Chain data (block height, difficulty, etc) on this page is cached for <?=$chainstats_cache?> minute(s).</p>
			
			<p style='color: red;'>If you have portfolio data entered on the Update Values page you don't want to lose, be sure you have enabled "Use cookie data to save values between sessions" on the Settings page before using these mining calculators.</p>
			
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
		    