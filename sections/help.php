<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

                        
                        
                        
			<div class='help_section'>
                            <p><b>First Run From Fresh Install:</b><br />
                                The first time you install and run the DFD Cryptocoin Values app, it may be sluggish as it creates the temporary cache files for the first time. After the first page load with coin values and exchange data displaying, it should run much faster.</p>
                        </div>
                        
			<div class='help_section'>
                            <p><b>Setting Up Price Alerts:</b><br />
                                You can setup price change alerts to be sent to your email, mobile phone, and amazon alexa devices. See the required settings in config.php, and instructions on cron job setup in the <a href='README.txt' target='_blank'>README.txt file</a>. Once setup, there is no need to keep your computer turned on. The alerts run automatically from your web server.</p>
                        </div>
                        
			<div class='help_section'>
                            <p><b>Monitoring Coins You Don't Hold:</b><br />
                                In the "Update Coin Amounts" section, for every coin you don't hold <i>but wish to monitor it's real-time value</i>, just set the amount to 0.00000001. This avoids skewing your "Total Bitcoin Value" and "Total USD Value" amounts, but allows you to track these coin value(s).</p>
                        </div>
                        
			<div class='help_section'>
                            <p><b>Messed Up Values After Upgrading:</b><br />
                                If the config file settings are re-configured or re-ordered, reload / refresh the page before updating any coin values, or the submission form may not be configured properly and may not submit or display data correctly. Also, you may need to uncheck "Save coin values as cookie data" on the Program Settings page temporarily to clear out old cookie data that may conflict with the new configuration...then you can re-enable cookies again afterwards.</p>
                        </div>
                        
                        
			<div class='help_section'>
                            <p><b>Coinmarketcap.com Data Not Available For An Asset</b><br />
                                Either the asset has not been added to <a href='https://coinmarketcap.com' target='_blank'>coinmarketcap.com</a> yet, you forgot to add the URL slug in it's config section, or you need to increase the number of rankings to fetch in config.php in the settings section (<?=$marketcap_ranks_max?> rankings is the current setting).</p>
                        </div>
                        
                        
			<div class='help_section'>
                            <p><b>Installing On Your Website, and Adding Your Own Coins:</b><br />
                                If you install this application on your own server, you can add / delete / edit the coin list very easily. Instructions can be found in the <a href='README.txt' target='_blank'>README.txt file</a>.</p>
                        </div>
                        
                        
			<div class='help_section'>
                            <p><b>Feature Requests and Reporting Issues:</b><br />
                                Have a feature you'd like to see added, or an issue to report? You can do that <a href='https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues' target='_blank'>in the github issue reporting area for this application</a>.</p>
                        </div>