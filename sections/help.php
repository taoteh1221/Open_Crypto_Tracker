<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

                        
                        
			<div class='help_section'>
                            <p><b>Feature Requests and Reporting Issues:</b><br />
                                Have a question, or feature you'd like to see added, or an issue to report? You can do that at the following URLs:<br /><br />
                                
                                <a href='https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues' target='_blank'>https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues</a><br /><br />
                                
                                <a href='https://dragonfrugal.com/contact/' target='_blank'>https://dragonfrugal.com/contact/</a><br /><br />
                                

											Web server setup / install is available for $30 hourly if needed. PM me on Twitter / Skype @ taoteh1221, or contact me using above contact links.
                                </p>
                        </div>
                        
			<div class='help_section'>
                            <p><b>Setting Up Email / Text / Alexa Exchange Price Alerts:</b><br />
                                You can setup exchange price alerts to be sent to email, mobile phone text, and amazon alexa notifications. You will be alerted when the USD price of an asset goes up or down a certain percent or more (whatever percent you choose in the settings), for specific exchange / base pairing combinations for that asset. You can even setup alerts for multiple exchanges / base pairings for the same asset. </p>
                             <p>See the required settings in config.php, and instructions on cron job setup in the <a href='README.txt' target='_blank'>README.txt file</a>. Once setup, there is no need to keep your computer turned on. The alerts run automatically from your web server. If you encounter errors or the alerts don't work during setup, check the error logs file at /cache/logs/errors.log for errors in your configuration setup. Basic checks are performed and errors are reported there, and on the Settings page.</p>
                        </div>
                        
			<div class='help_section'>
                            <p><b>Monitoring Coins You Don't Hold:</b><br />
                                In the "Update Coin Values" section, for every coin you don't hold <i>but wish to monitor it's real-time value</i>, just set the amount to 0.00000001. This avoids skewing your "Total Bitcoin Value" and "Total USD Value" amounts, but allows you to track these coin value(s).</p>
                        </div>
                        
                        
			<div class='help_section'>
                            <p><b>Installing On Your Website, and Adding Your Own Coins:</b><br />
                                If you install this application on your own server, you can add / delete / edit the coin list very easily. Instructions can be found in the <a href='README.txt' target='_blank'>README.txt file</a>.</p>
                        </div>
                        
			<div class='help_section'>
                            <p><b>SMTP Email Doesn't Work:</b><br />
                                If you have enabled SMTP emailing but it doesn't work, check the error logs file at /cache/logs/errors.log for error responses from the SMTP server connection attempt(s). Alternatively try disabling SMTP email by blanking out your username and password in the config.php file, and see if PHP's built-in mail function works (no setup required, other than SMTP settings must be blanked out).</p>
                        </div>
                        
			<div class='help_section'>
                            <p><b>Runs Sluggish / Throws Errors With Proxies:</b><br />
                                If page loads are sluggish or throw API connection errors without clearing up, and you have enabled proxy ip addresses, check the error logs file at /cache/logs/errors.log for error responses from the proxy server connection attempt(s). If there are no errors log entries related to the issue that help diagnose the problem, disable using proxies in config.php and try loading the web page again.</p>
                             <p>If it is a bad or misconfigured proxy setup causing the issue, and everything runs great after disabling proxies, you probably have either (a) a bad proxy or proxy configuration, or (b) an API server / endpoint address is not responding properly when routed through proxies (example: HTTP used instead of HTTPS can cause this error). <i>If you are absolutely sure your proxy setup is ok</i>, and that an API connection built-in to this app is the issue, please <a href='https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues' target='_blank'>report it</a>.</p>
                        </div>
                        
			<div class='help_section'>
                            <p><b>Messed Up Values After Upgrading:</b><br />
                                If the config file settings are re-configured or re-ordered, reload / refresh the page before updating any coin values, or the submission form may not be configured properly and may not submit or display data correctly. Also, you may need to uncheck "Use cookie data to save values between sessions" on the Program Settings page, to temporarily clear out old cookie data that may conflict with the new configuration...then you can re-enable cookies again afterwards.</p>
                        </div>
                        
                        
			<div class='help_section'>
                            <p><b>Coinmarketcap.com Data Not Available For An Asset</b><br />
                                Either the asset has not been added to <a href='https://coinmarketcap.com' target='_blank'>coinmarketcap.com</a> yet, you forgot to add the URL slug in it's config section, or you need to increase the number of rankings to fetch in config.php in the settings section (<?=$marketcap_ranks_max?> rankings is the current setting).</p>
                        </div>