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
				<legend class='subsection_legend'> <b>External Tools</b> </legend>
    			<ul>
		    
        			<li class='links_list'><a href='https://timestamp.decred.org/' target='_blank'>Timestamp Proof-Of-Existence Of Files (FREE) With The Decred Blockchain</a></li>
        
        <li class='links_list'><a href='https://calendar.google.com/' target='_blank'>Google Calendar to Send Yourself Reminders For Important Crypto Times</a> ;-)</li>
				
				
    			</ul>
			</fieldset>