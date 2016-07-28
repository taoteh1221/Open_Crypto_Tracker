
			<div style='border: 2px dotted red; font-weight: bold; padding: 9px; color: red;'><a href='index.php' style='color: red;'>Click Here To Reset Default Tab / Calculators</a></div>
			
			<fieldset class='calculators'>
				<legend style='color: blue;'> <b>Ethereum Mining Calculator</b> </legend>
		    
				<?php require("app.lib/calculators/ethereum-mining-calculator.php"); ?>
				
				
			</fieldset>
			
			<fieldset class='calculators'>
				<legend style='color: blue;'> <b>STEEM Power Interest Rate / Power Down Weekly Payout Calculator</b> </legend>
				
				<?php require("app.lib/calculators/steem-power-interest.php"); ?>
				
			</fieldset>
		    