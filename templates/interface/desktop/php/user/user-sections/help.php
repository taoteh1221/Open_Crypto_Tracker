<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='max_1200px_wrapper'>
				
				<span class='red countdown_notice'></span>
			
	
	<p style='margin-top: 15px; margin-bottom: 15px;'><?=$ocpt_gen->start_page_html('help')?></p>		
			
	
	<div class="accordion" id="accordionHelp" style='margin: 20px; margin-top: 35px;'> <!-- Accordion START -->
	
	  
	
	<?php
	$accord_var = 1;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="true" aria-controls="collapse_<?=$accord_var?>">
	          
	          Feature Requests / Reporting Issues / Help
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse show" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	        
			  Web server setup / install is available for $30 hourly if needed (see 'Manual Install' section for managed hosting, or try the auto-install bash script for self-hosted). PM me on Twitter / Skype @ taoteh1221, or get a hold of me using the below-listed contact methods.<br /><br />
	      
	      
	        <span class='blue'>Have a question, feature you'd like to see added, or an issue to report? You can do that at the following URLs...</span><br /><br />
	        
	        <span class='bitcoin'>Issue Reporting (Features / Issues / Help):</span> <a href='https://github.com/taoteh1221/Open_Crypto_Portfolio_Tracker/issues' target='_blank'>https://github.com/taoteh1221/Open_Crypto_Portfolio_Tracker/issues</a><br /><br />
	        
	        <span class='bitcoin'>Discord:</span> <a href='https://discord.gg/WZVK2nm' target='_blank'>https://discord.gg/WZVK2nm</a><br /><br />
	        
	        <span class='bitcoin'>Telegram:</span> <a href='https://t.me/joinchat/Oo2XZRS2HsOXSMGejgSO0A' target='_blank'>https://t.me/joinchat/Oo2XZRS2HsOXSMGejgSO0A</a><br /><br />
	        
	        <span class='bitcoin'>Twitter:</span> <a href='https://twitter.com/taoteh1221' target='_blank'>https://twitter.com/taoteh1221</a><br /><br />
	        
	        <span class='bitcoin'>Private Contact:</span> <a href='https://dragonfrugal.com/contact' target='_blank'>https://dragonfrugal.com/contact</a>
												
<br /><br />

<span class='blue'>Donations support further development...</span> <br /><br />

<span class='bitcoin'>Bitcoin:</span>  3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW<br /><br />

<span class='bitcoin'>Ethereum:</span>  0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8<br /><br />

<span class='bitcoin'>Github Sponsors:</span>  <a href='https://github.com/sponsors/taoteh1221' target='_blank'>https://github.com/sponsors/taoteh1221</a><br /><br />

<span class='bitcoin'>Patreon:</span>   <a href='https://www.patreon.com/dragonfrugal' target='_blank'>https://www.patreon.com/dragonfrugal</a><br /><br />

<span class='bitcoin'>PayPal:</span>    <a href='https://www.paypal.me/dragonfrugal' target='_blank'>https://www.paypal.me/dragonfrugal</a><br /><br />
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	
	
	
	<?php
	$accord_var = 'raspi_install';
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Automatic Setup For Ubuntu or Raspberry Pi, On Home / Internal Network <span class='red' style="display: block;">(THE RECOMMENDED WAY TO PRIVATELY / CHEAPLY USE THIS APP)</span>
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	

	<div align='center'>
	
	<h5><span class='bitcoin'>Recommended MINIMUM system specs:</span> <span class='blue' style="display: block;">1 Gigahertz CPU / 512 Megabytes RAM / HIGH QUALITY 32 Gigabyte MicroSD card</span> <span style="display: block;">(running Nginx or Apache headless with PHP v7.2+)</span></h5>
	    <br />
	 
	 <a href='https://www.raspberrypi.org/products/raspberry-pi-zero-w/' target='_blank' title='Visit the Raspberry Pi Zero W product page.'><img class='image_border' src='templates/interface/media/images/pi-zero.jpg' width='550' alt='' /></a> 
	    <br /><br />
	 
	 <a href='https://www.raspberrypi.org/products/raspberry-pi-zero-w/' target='_blank' title='Visit the Raspberry Pi Zero W product page.'>Compatible With Raspberry Pi Zero W</a>
	 
    
   </div>
    
	    <br /><br />
	      
	       To install / upgrade everything automatically on <a href='https://ubuntu.com/#download' target='_blank' title='Download Ubuntu operating system for your PC.'>Ubuntu</a> or <a href='https://www.raspberrypi.org/products/' target='_blank' title='View Raspberry Pi hardware products.'>Raspberry Pi</a> (an affordable low power single board computer), copy / paste / run the command below in a terminal program (using the 'Terminal' app in the system menu, or over remote SSH), while logged in AS THE USER THAT WILL RUN THE APP (user must have sudo privileges):
	       

	    <br /><br />
<pre class='rounded' style='display: inline-block;<?=( $ocpt_gen->is_msie() == false ? ' padding-top: 1em !important;' : '' )?>'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block;'>wget --no-cache -O FOLIO-INSTALL.bash https://git.io/JqCvQ;chmod +x FOLIO-INSTALL.bash;sudo ./FOLIO-INSTALL.bash</code></pre>


	    <br /><br />

Follow the prompts. This automated script gives you the options to: install / uninstall a PHP web server automatically, download / install / configure / uninstall the latest version of the Open Crypto Portfolio Tracker app automatically, setup a cron job automatically (for price alerts / price charts), and setup SSH (to update / install web site files remotely to the web server via SFTP) automatically. 
	    <br /><br />

<span class='bitcoin'>When the auto-install is completed, it will display addresses / logins to access the app (write these down / save them for future use).</span>

	    <br /><br />
SEE <a href='https://github.com/taoteh1221/Open_Crypto_Portfolio_Tracker/tree/main/DOCUMENTATION-ETC/RASPBERRY-PI' target='_blank'>DOCUMENTATION-ETC/RASPBERRY-PI</a> for additional information on securing and setting up Raspberry Pi OS (disabling bluetooth, firewall setup, remote login, hostname, etc). 
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	
	
	<?php
	$accord_var = 'website_install';
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Installing On A Website / Manual Installation
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	      
	        
	        Just upload this app's files to your PHP-based web server (with an FTP client like <a href='https://filezilla-project.org/download.php?type=client' target='_blank'>FileZilla</a>) and you should be all set, unless your host is a strict setup related to file writing permissions, in which case the 'cache' directory permissions should be set to '777' chmod on unix / linux systems (or 'readable / writable' on windows systems). 
	    <br /><br />
	        
	        Your web host must have CURL modules activated on your HTTP server. Most web hosting companies provide this "out-of-the-box" already. This app will detect whether or not CURL is setup on your website server (and also alert you to any other missing required system components / configurations). 
	    <br /><br />

See "<span class='bitcoin'>Setting Up Price Charts And Email / Text / Telegram / Alexa / Google Home Price Alerts</span>"</a>, for how to setup a cron job for additional features. 
	        
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
	          
	          Setting Up Price Charts And Email / Text / Telegram / Alexa / Google Home Price Alerts
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	      You can setup price charts or price alerts in your app install. Price alerts can be sent to email, mobile phone text, Telegram, and Alexa / Google Home notifications. You will be alerted when the [configured default primary currency] price of an asset goes up or down a certain percent or more (whatever percent you choose in the settings), for specific exchange / base pairing combinations for that asset. You can even setup alerts and charts for multiple exchanges / base pairings for the same asset.
	    <br /><br />
	    
Running price charts or price alerts requires setting up a cron job on the Ubuntu / Raspberry Pi machine or website server (this is automated for Ubuntu / Raspberry Pi users who use the automated install script), otherwise charts / alerts will not work. Also see the related settings in Admin Config for charts / alerts. 
	    <br /><br />

Once a cron job is setup, there is no need to keep your PC / Laptop turned on. The price charts and price alerts run automatically from your app server. If you encounter errors or the charts / alerts don't work during setup, check the error logs file at /cache/logs/errors.log for errors in your configuration setup. Basic checks are performed and errors are reported there, and on the Settings page. 
	    <br /><br />

If you want to take advantage of these cron job based features and more (chart data backups, daily or weekly error log emails / etc), then the file cron.php (located in the primary directory of this app) must be setup as a cron job on your Ubuntu / Raspberry Pi / website server device. 
	    <br /><br />

As mentioned previously, if you run the automated setup / install script for Ubuntu or Raspberry Pi devices on home / internal networks, automatic cron job setup is offered as an option during this process. If you are using a full stack website host for hosting a TLD website domain name remotely, consult your web server host's documentation or help desk for their particular method of setting up a cron job. 
	    <br /><br />

Note that you should have the cron job run every 5, 10, 15, 20, or 30 minutes 24/7, based on how often you want chart data points / alerts / any other cron based features to run. Setting up the cron job to run every 20 minutes is the RECOMMENDED lowest time interval. IF SET BELOW 20 MINUTES, lite chart disk writes may be excessive for lower end hardware (Raspberry PI MicroSD cards etc). IF SET #VERY LOW# (5 / 10 minutes), the free exchange APIs may throttle / block your data requests temporarily on occasion for requesting data too frequently (negatively affecting your alerts / charts). 
	    <br /><br />

Here is an example cron job command line for reference below (NOT including any cron parameters your host interface may require), to setup as the "command" within a cron job. Replace system paths in the example with the correct ones for your server (TIP - A very common path to PHP on a server is /usr/bin/php):
	    <br /><br />
	    
<pre class='rounded' style='display: inline-block;<?=( $ocpt_gen->is_msie() == false ? ' padding-top: 1em !important;' : '' )?>'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block;'>/path/to/php -q /home/username/path/to/website/this_app/cron.php</code></pre>


	    <br /><br />

Here is another example of a COMPLETE cron command that can be added by creating the following file (you'll need sudo/root permissions): /etc/cron.d/cryptocoin on a linux-based machine with systemd (to run every 20 minutes 24/7)...play it safe and add a newline after it as well if you install examples like these:
	    <br /><br />

	    
<pre class='rounded' style='display: inline-block;<?=( $ocpt_gen->is_msie() == false ? ' padding-top: 1em !important;' : '' )?>'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block;'>*/20 * * * * WEBSITE_USERNAME_GOES_HERE /usr/bin/php -q /var/www/html/cron.php > /dev/null 2>&1</code></pre>


	    <br /><br />

If your system DOES NOT have the directory /etc/cron.d/ on it, then NEARLY the same format (minus the username) can be installed via the legacy 'crontab -e' command (YOU MUST BE logged in as the user you want running the cron job):
	    <br /><br />

	    
<pre class='rounded' style='display: inline-block;<?=( $ocpt_gen->is_msie() == false ? ' padding-top: 1em !important;' : '' )?>'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block;'>*/20 * * * * /usr/bin/php -q /var/www/html/cron.php > /dev/null 2>&1</code></pre>



	    <br /><br />
<span class='bitcoin'>Important Cron Job Notes:</span> 
	    <br />

MAKE SURE YOU ONLY USE EITHER /etc/cron.d/, or 'crontab -e', NOT BOTH...ANY OLD DUPLICATE CRONTAB ENTRIES WILL RUN YOUR CRON JOB TOO OFTEN. If everything is setup properly, and the cron job still does NOT run, your particular server may require the cron.php file permissions to be set as 'executable' ('755' chmod on unix / linux systems) to allow running it.
	        
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
	          
	          Adding Your Own Coins
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	      
	       <span class='red'>Important Note:</span> <span class='bitcoin'>In the upcoming v5 release (due out in 2021 or 2022), doing this manually in a text editor won't be necassary. You will be able to do it in the <span class='blue'>"Admin Config => Portfolio Assets"</span> interface much easier.</span>
	    <br /><br />

Below is an example for editing your assets / markets into the portfolio assets in the file config.php (located in the primary directory of this app), in the PORTFOLIO ASSETS section. It's very quick / easy to do (after you get the hang of it, lol). Also see the text file <a href='https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Portfolio_Tracker/main/DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt' target='_blank'>DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt</a>, for a pre-configured set of default settings and example assets / markets. 

	    <br /><br />

Contact any supported exchange's help desk if you are unaware of the correct formatting of the trading pair naming you are adding in the configuration file (examples: Kraken has arbitrary Xs inserted in SOME older pair names, HitBTC sometimes has tether pairing without the "T" in the symbol name).

	    <br /><br />


Support for over 80 trading pairs (country fiat currency or secondary crypto, contact me to request more): 
	    <br /><br />

<span class='blue'>AED / ARS / AUD / BAM / BDT / BOB / BRL / BTC / BWP / BYN / CAD / CHF / CLP / CNY / COP / CRC / CZK / DAI / DKK / DOP / EGP / ETH / EUR / GBP / GEL / GHS / GTQ / HKD / HUF / IDR / ILS / INR / IRR / JMD / JOD / JPY / KES / KRW / KWD / KZT / LKR / LRC / MAD / MKR / MUR / MWK / MXN / MYR / NGN / NIS / NOK / NZD / PAB / PEN / PHP / PKR / PLN / PYG / QAR / RON / RSD / RUB / RWF / SAR / SEK / SGD / THB / TRY / TUSD / TWD / TZS / UAH / UGX / UNI / USDC / USDT / UYU / VES / VND / XAF / XOF / ZAR / ZMW</span>

	    <br /><br />

Support for over 40 exchanges (contact me to request more): 
	    <br /><br />

<span class='blue'>binance / binance_us / bit2c / bitbns / bitfinex / bitflyer / bitmex / bitmex_u20 / bitmex_z20 / bitpanda / bitso / bitstamp / bittrex / bittrex_global / braziliex / btcmarkets / btcturk / buyucoin / cex / coinbase / coinex / cryptofresh / defipulse / ethfinex / gateio / gemini / hitbtc / hotbit / huobi / korbit / kraken / kucoin / liquid / localbitcoins / loopring / loopring_amm / luno / okcoin / okex / poloniex / southxchange / upbit / wazirx / zebpay</span>
	    <br /><br />


Nearly Unlimited Assets Supported (whatever assets exist on supported exchanges).
	    <br /><br />


Ethereum ICO subtoken support (pre-exchange listing) has been built in (values are static ICO values in ETH).
	    <br /><br />
 

<span class='bitcoin'>USAGE (ADDING / UPDATING COINS):</span>
	    <br /><br />
 
 
<pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;'>

            // UPPERCASE_COIN_ABRV_HERE
            'UPPERCASE_COIN_ABRV_HERE' => array(
                
                'name' => 'COIN_NAME_HERE',
                // Website slug (URL data) on coinmarketcap / coingecko, leave blank if not listed there
                'mcap_slug' => 'WEBSITE_SLUG_HERE', 
                'pairing' => array(
                            
                            // MARKET IDS ARE CASE-SENSITIVE!
                            
                            'lowercase_pairing_abrv' => array(
                                  'lowercase_exchange1' => 'MARKETIDHERE',
                                  'lowercase_exchange2' => 'ASSET/PAIRING',
                                  'lowercase_exchange3' => 'ASSET-PAIRING',
                                  'lowercase_exchange4' => 'ASSET_PAIRING',
                                  'lowercase_exchange5' => 'ASSETPAIRING',
                                  'defipulse' => 'ASSET/PAIRING', // DeFi Generic
                                            ),
                                            
                            'eth' => array(
                                  'lowercase_exchange1' => 'MARKETIDHERE',
                                  'lowercase_exchange2' => 'ASSET/ETH',
                                  'lowercase_exchange3' => 'ASSET-ETH',
                                  'lowercase_exchange4' => 'ASSET_ETH',
                                  'lowercase_exchange5' => 'ASSETETH',
                                  // ETH ICOs...MUST be defined in 'eth_erc20_icos', in Admin Config POWER USER section
                                  'ico_erc20_value' => 'ETHSUBTOKENNAME', 
                                  // INCLUDING #OPTIONAL# LIQUIDITY POOL ADDRESS, ASSURING #EXACT# MARKET DESIRED
                                  'defipulse' => 'ASSET/PAIRING||OPTIONAL_LIQUIDITY_POOL_ADDRESS', // DeFi Generic
                                            ),
                                            
                                  ) // market_pairing END
            	   
            ), // Asset END
</code></pre>
      
	    <br /><br />
 
    
 SEE <a href='https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Portfolio_Tracker/main/DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt' target='_blank'>DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt</a> FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)
	        
	        
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
	          
	          Layout / Functions / Assets Not Running Properly, After Reconfiguring Or Upgrading
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	      
	        If the portfolio assets settings are re-configured or re-ordered in Admin Config, reload / refresh the page before updating any coin values, or the submission form may not be configured properly and may not submit or display data correctly. Also, you may need to uncheck "Use cookies to save data" on the Settings page, to temporarily clear out old cookie data that may conflict with the new configuration...then you can re-enable cookies again afterwards. 
	    <br /><br />
	    If you recently upgraded to a newer version of this app, and layout or features don't work properly anymore, you may need to clear your browser cache (temporary files) and restart you browser / refresh the page afterwards. This will assure your browser is loading any newly-updated layout styling or javascript-based features.
	    <br /><br />
	    If your problems still persist even after clearing your browser cache (temporary files) and restarting your browser, your config.php setup may be corrupt IF YOU EDITED IT BY HAND. If you did edit it by hand, try backing up you old config.php file, and replacing it with the default config.php file included with the latest release. This will ensure your configuration setup is not corrupt from messed up file formatting.
	    <br /><br />
	    If none of the above solutions work, your last resort (before contacting me for support) is to wipe out all data in your cache directory folder within the app. THIS WILL ERASE YOUR CHART DATA, SO YOU MAY WANT TO BE SURE YOU HAVE A BACKUP FIRST. After your chart data is backed up, delete the folder named 'cache' in the main directory of this app. Reloading the app web page should re-create the cache folder, with new / clean cache files.
	    <br /><br />
	    If you are still having issues after trying everything, file an issue here at the github project account, and I will help you troubleshoot the problems: <a href='https://github.com/taoteh1221/Open_Crypto_Portfolio_Tracker/issues' target='_blank'>https://github.com/taoteh1221/Open_Crypto_Portfolio_Tracker/issues</a>
	        
	        
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
	          
	          Coinmarketcap.com / Coingecko.com Data Not Available For An Asset
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	       
	        Either the asset has not been added to <a href='https://coinmarketcap.com' target='_blank'>Coinmarketcap.com</a> or <a href='https://Coingecko.com' target='_blank'>Coingecko.com</a> yet, you forgot to add the URL slug in it's config section, or you need to increase the number of rankings to fetch in Admin Config in the POWER USER section (300 rankings is the safe maximum to avoid getting your API requests throttled / blocked). 
	        
	        
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
	          
	          SMTP Email Sending Doesn't Work
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	       
	        If you have enabled SMTP emailing (to send emails) but it doesn't work, check the error logs files at /cache/logs/errors.log and /cache/logs/smtp_errors.log for error responses from the SMTP server connection attempt(s). 
	    <br /><br />
	        
	        If you are sure your username / password / host:port setup are valid, try disabling SMTP email sending by blanking out your username / password / host:port (in the Admin Config COMMUNICATIONS section), and see if PHP's built-in mail function sends emails OK (no setup required, other than SMTP settings must be blanked out). 
	        
	    <br /><br />
<span class='bitcoin'>Important Note:</span> 
	    <br />

SMTP email sending is REQUIRED if you are running this app on a home network, or if reverse DNS hasn't been properly setup for the TLD domain hosted on this device (servers receiving email from this machine would likely blackhole it, or mark it as junk email).
	        
	        
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
	          
	          Page Loads Slowly Or Throws Errors With Proxies Enabled
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	         
	        If page loads are sluggish or throw API connection errors without clearing up, and you have enabled proxy ip addresses, check the error logs file at /cache/logs/errors.log for error responses from the proxy server connection attempt(s). If there are no errors log entries related to the issue that help diagnose the problem, disable using proxies (in the Admin Config PROXY section) and try loading the web page again.
	    <br /><br />
	      If it is a bad or misconfigured proxy setup causing the issue, and everything runs great after disabling proxies, you probably have either (a) a bad proxy or proxy configuration, or (b) an API server / endpoint address is not responding properly when routed through proxies (example: HTTP used instead of HTTPS can cause this error). <i>If you are absolutely sure your proxy setup is ok</i>, and that an API connection built-in to this app is the issue, please <a href='https://github.com/taoteh1221/Open_Crypto_Portfolio_Tracker/issues' target='_blank'>report it</a>. 
	        
	        
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
	          
	          Backup Archives Don't Work
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	         
	        This app will automatically detect and alert you if your system doesn't support zip file creating or secure random number generation, which are both used in creating the zip archive backups. So if you have issues with your backup archives working, it's most likely related to file / folder permissions. Make sure the /cache/secured/backups/ directory access permissions are set to readable and writable. This assures the ZIP archive has permission to be created in this directory.
	        
	        
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
	          
	          Write Errors In Error Log For Charts / Other Data
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	         
If you are getting a lot of messages in the error logs like "file_write_error: File write failed for file X", you may need to free up disk space quota on your device, OR change directory permissions on your /cache/ folder. Check to make sure you have not used up all your ALLOWED disk space quota, AND that your /cache/ folder permissions are readable / writable (777 on unix / linux systems).
<br /><br />
If you already have plenty of disk space quota freed up / your cache folder permissions are readable / writable, and you still have file write issues on linux-based operating systems, you MAY need to setup a higher "open files" limit for your website user account. If you have shell access you can login and run this command to check your current limits:
<br /><br />
<pre class='rounded' style='display: inline-block;<?=( $ocpt_gen->is_msie() == false ? ' padding-top: 1em !important;' : '' )?>'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block;'>ulimit -n</code></pre>

<br /><br />
If it's a low number like 1024, this MAY be the cause of your file write error issue (especially if you run multiple web apps that write a lot of data on the same account). If you are running a dedicated or VPS server, you can easily change this limit. 
<br /><br />
Running a google search for "set permanently ulimit -n linux", you'll find tons of articles on permanently upping your user's open files limit:
<br /><br />
<a href='https://www.google.com/search?q=set+permanently+ulimit+-n+linux' target='_blank'>https://www.google.com/search?q=set+permanently+ulimit+-n+linux</a>
	        
	        
	      </div>
	    </div>
	  </div>
	  
	
	<?php
	$accord_var = 11;
	?>
	
	  <div class="card z-depth-0 bordered">
	    <div class="card-header" id="heading_<?=$accord_var?>">
	      <h5 class="mb-0">
	        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_<?=$accord_var?>"
	          aria-expanded="false" aria-controls="collapse_<?=$accord_var?>">
	          
	          Partial API Data Failure, When Running Behind Slow Internet Connections
	          
	        </button>
	      </h5>
	    </div>
	    <div id="collapse_<?=$accord_var?>" class="collapse" aria-labelledby="heading_<?=$accord_var?>"
	      data-parent="#accordionHelp">
	      <div class="card-body">
	      
	         
	        If you installed this application on a device on your home network, or on any other network WITH A SLOW INTERNET CONNECTION, you may need to increase the default timeout for retrieving API data IF YOU RECEIVE PARTIAL OR NO API DATA IN THE APP FOR SOME API DATA SETS (the error logs will alert you if this is happening, so check there). 
<br /><br />
	        
	        To adjust the API timeout, go to the Admin Config POWER USER section. Adjust the 'remote_api_timeout' setting much higher, save the setup in the app, and run the app again to see if this fixes the issue. Adjust higher again if the issue still occurs frequently. DON'T SET 'remote_api_timeout' TOO HIGH though, or any unresponsive connections may cause the app to take a very long time to load / reload.
	        
	        
	      </div>
	    </div>
	  </div>
	  
	  
	  
	  
	  
	</div> <!-- Accordion END -->
	
	
			    
			    
</div> <!-- max_1200px_wrapper END -->




		    