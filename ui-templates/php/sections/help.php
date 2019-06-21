<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='force_1200px_wrapper'>
	
				<h4 style='display: inline;'>Help?</h4>
				
				<span id='reload_countdown8' class='red countdown_notice'></span>
			
	
	<div class="accordion" id="accordionHelp" style='margin: 20px;'> <!-- Accordion START -->
	
	
	
	<?php
	$accord_var = 1;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="true" aria-controls="collapse_<?=$accord_var?>">
	          
	          Feature Requests and Reporting Issues
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse show" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	      
	        Have a question, feature you'd like to see added, or an issue to report? You can do that at the following URLs:<br /><br />
	        
	        <a href='https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues' target='_blank'>https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues</a><br /><br />
	        
	        <a href='https://dragonfrugal.com/contact/' target='_blank'>https://dragonfrugal.com/contact/</a><br /><br />
	        
				Web server setup / install is available for $30 hourly if needed. PM me on Twitter / Skype @ taoteh1221, or contact me using above contact links.
												
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	  
	
	
	<?php
	$accord_var = 2;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Layout / Functions / Assets Not Running Properly, After Reconfiguring or Upgrading
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	      
	    If you recently upgraded to a newer version of this app, and layout or features don't work properly anymore, you may need to clear your browser cache and refresh the page afterwards. This will assure your browser is loading any newly-updated layout styling or javascript-based features.
	    <br /><br />
	        If the coins list settings are re-configured or re-ordered in config.php, reload / refresh the page before updating any coin values, or the submission form may not be configured properly and may not submit or display data correctly. Also, you may need to uncheck "Use cookie data to save values between sessions" on the Settings page, to temporarily clear out old cookie data that may conflict with the new configuration...then you can re-enable cookies again afterwards. 
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	
	
	<?php
	$accord_var = 3;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Setting Up Email / Text / Alexa Exchange Price Alerts
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	      
	        
	        You can setup asset price alerts to be sent to email, mobile phone text, and amazon alexa notifications. You will be alerted when the USD price of an asset goes up or down a certain percent or more (whatever percent you choose in the settings), for specific exchange / base pairing combinations for that asset. You can even setup alerts for multiple exchanges / base pairings for the same asset.
	    <br /><br />
	      Running asset price alerts requires setting up a cron job on your website server, <i>otherwise they will not work</i>. See the required settings in config.php, and instructions on cron job setup in the <a href='README.txt' target='_blank'>README.txt file</a>. Once setup, there is no need to keep your computer turned on. The alerts run automatically from your website server. If you encounter errors or the alerts don't work during setup, check the error logs file at /cache/logs/errors.log for errors in your configuration setup. Basic checks are performed and errors are reported there, and on the Settings page. 
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	
	
	<?php
	$accord_var = 4;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Monitoring Coins You Don't Hold
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	       
	        On the Update Assets page, for every coin you don't hold <i>but wish to monitor it's real-time value</i>, just set the amount to 0.00000001. This avoids skewing your total "Bitcoin Value" / "USD Value" summary amounts, but allows you to track these coin value(s). 
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	
	
	<?php
	$accord_var = 5;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Page Loads Slowly With Charts Enabled
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	       
	        If you have the Charts page enabled in config.php, and page load times are slow after activating your favorite charts, go to the Charts page and click the "Activate Charts" button. Uncheck all charts, and click "Update Activated Charts". If the page load times are faster afterwards, the issue may have been that you were loading too many charts at once. Try loading only a few charts instead, this may help page load times. 
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	
	
	<?php
	$accord_var = 6;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Coinmarketcap.com Data Not Available For An Asset
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	       
	        Either the asset has not been added to <a href='https://coinmarketcap.com' target='_blank'>coinmarketcap.com</a> yet, you forgot to add the URL slug in it's config section, or you need to increase the number of rankings to fetch in config.php in the settings section (<?=$marketcap_ranks_max?> rankings is the current setting). 
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	
	
	<?php
	$accord_var = 7;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Installing On Your Website, and Adding Your Own Coins
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	      
	        If you install this application on your website server, you can add / delete / edit the coin list very easily. Instructions can be found in the <a href='README.txt' target='_blank'>README.txt file</a>. 
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	
	
	<?php
	$accord_var = 8;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          SMTP Email Sending Doesn't Work
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	       
	        If you have enabled SMTP emailing (to send emails) but it doesn't work, check the error logs file at /cache/logs/errors.log for error responses from the SMTP server connection attempt(s). Alternatively try disabling SMTP email sending by blanking out your username and password in the config.php file, and see if PHP's built-in mail function sends emails OK (no setup required, other than SMTP settings must be blanked out). 
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	
	
	<?php
	$accord_var = 9;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Page Loads Slowly or Throws Errors With Proxies Enabled
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	         
	        If page loads are sluggish or throw API connection errors without clearing up, and you have enabled proxy ip addresses, check the error logs file at /cache/logs/errors.log for error responses from the proxy server connection attempt(s). If there are no errors log entries related to the issue that help diagnose the problem, disable using proxies in config.php and try loading the web page again.
	    <br /><br />
	      If it is a bad or misconfigured proxy setup causing the issue, and everything runs great after disabling proxies, you probably have either (a) a bad proxy or proxy configuration, or (b) an API server / endpoint address is not responding properly when routed through proxies (example: HTTP used instead of HTTPS can cause this error). <i>If you are absolutely sure your proxy setup is ok</i>, and that an API connection built-in to this app is the issue, please <a href='https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues' target='_blank'>report it</a>. 
	        
	        
	      </div>
	    </div>
	  </div>
	  
	
	<?php
	$accord_var = 10;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Backup Archives Don't Work
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	         
	        If backup archives (for chart data etc) don't work properly, check the error logs file at /cache/logs/errors.log for error responses related to (a) "cryptographically secure pseudo-random bytes could not be generated" (which means your system is not properly setup to generate secure random characters, which are used for backup storage privacy via a random filename suffix), or (b) "Backup zip archive creation failed with no_extension" (which means your system does not have the libzip module for the PHP command-line version on your server). If you have either of these issues, the problem is related to your server not being setup properly to support this functionality. It's not a bug in this application. Contact your hosting provider or system administrator to have them fix your server setup to support these features.
	    <br /><br />
	      If you have none of these error log messages, your issue may be file / folder permissions. Make sure the /backups/ directory access permissions are set to readable and writable. This assures the ZIP archive has permission to be created in this directory.
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	  
	  
	  
	</div> <!-- Accordion END -->
	
	
			    
			    
</div> <!-- force_1200px_wrapper END -->




		    