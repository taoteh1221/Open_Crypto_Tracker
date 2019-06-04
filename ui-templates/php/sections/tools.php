<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>
			<h3 style='display: inline;'>Other Tools</h3>

<p><?=start_page_html('tools')?></p>			

			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>QR Code Generator For Addresses</b> </legend>
		    
			<p style='color: red;'>Using this QR code generator <i><u>will set this page as the start page</u>, which you can reset afterwards at top left</i>. If you have portfolio data you don't want to lose, be sure you have enabled "Use cookie data to save values between sessions" on the Settings page before using this tool.</p>
			
				<?php require("app-lib/php/other/qr-code-generator/qr-code-generator.php"); ?>
				
				
			</fieldset>
			
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>Altcoin / BTC Trade Target Calculator</b> </legend>
    			
    			
    			<p><b>Token Amount:</b> <input type='text' id='to_trade_amount' name='to_trade_amount' value='0' size='20' /> </p>
    			
    			<p><b>BTC Trade Target:</b> <input type='text' id='sat_target' name='sat_target' value='0.00000001' minlength="10" maxlength="10" size="11" /> </p>
    			
    			<p>
    			
    				<b>Increase Satoshi Value:</b> <button class='force_button_style' onclick='
    				document.getElementById("sat_target").value = (0.00000001).toFixed(8);
    				satoshi_value("refresh");
    				'>Reset</button> 
    			
    				<button class='force_button_style' onclick='satoshi_value(0.00000001);'>+1</button> 
    				
    				<button class='force_button_style' onclick='satoshi_value(0.00000010);'>+10</button> 
    				
    				<button class='force_button_style' onclick='satoshi_value(0.00000100);'>+100</button> 
    				
    				<button class='force_button_style' onclick='satoshi_value(0.00001000);'>+1,000</button> 
    				
    				<button class='force_button_style' onclick='satoshi_value(0.00010000);'>+10,000</button> 
    				
    				<button class='force_button_style' onclick='satoshi_value(0.00100000);'>+100,000</button> 
    				
    				<button class='force_button_style' onclick='satoshi_value(0.01000000);'>+1,000,000</button> 
    				
    				<button class='force_button_style' onclick='satoshi_value(0.10000000);'>+10,000,000</button> 
    				
    			</p>
    			
    			<p style='font-weight: bold; color: green;'>Token Trade Target: $<span id='target_usd'>0.00</span> (<span id='target_btc'>0.00</span> BTC) </p>
    			
    			<p style='font-weight: bold; color: green;'>Total Trade Target: $<span id='target_total_usd'>0.00</span> (<span id='target_total_btc'>0.00</span> BTC) </p>
    			
    			<script>
    			
    			document.getElementById("to_trade_amount").addEventListener("input", function(){
  				satoshi_value("refresh");
				});
				
    			document.getElementById("sat_target").addEventListener("input", function(){
  				satoshi_value("refresh");
				});
    			
    			
    			</script>
    			
			</fieldset>
			
			
			<fieldset class='subsection_fieldset'>
				<legend class='subsection_legend'> <b>External Tools</b> </legend>
    			<ul>
		    
        			<li class='links_list'><a href='https://timestamp.decred.org/' target='_blank'>Timestamp Proof-Of-Existence Of Files (FREE) With The Decred Blockchain</a></li>
        
        <li class='links_list'><a href='https://calendar.google.com/' target='_blank'>Google Calendar to Send Yourself Reminders For Important Crypto Times</a> ;-)</li>
				
				
    			</ul>
			</fieldset>
			
			
			
			