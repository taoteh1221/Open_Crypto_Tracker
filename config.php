<?php
// DON'T LEAVE ANY WHITESPACE ABOVE THE OPENING PHP TAG!

/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */

// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
header('HTTP/1.0 403 Forbidden', TRUE, 403);
exit;
}


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PRIMARY CONFIGURATIONS -START- ////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG

// SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// See TROUBLESHOOTING.txt for tips / troubleshooting FAQs.

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC !!!!WILL BREAK THE APP!!!!, BE CAREFUL EDITING THIS CONFIG FILE!


////////////////////////////////////////
// !START! GENERAL CONFIGURATION
////////////////////////////////////////


// Your local time offset IN HOURS COMPARED TO UTC TIME (#CAN BE DECIMAL# TO SUPPORT 30 / 45 MINUTE TIME ZONES). Can be negative or positive.
// (Used for user experience 'pretty' timestamping in interface logic ONLY, WILL NOT change or screw up UTC log times etc if you change this)
$ct_conf['gen']['loc_time_offset'] = -5; // example: -5 or 5, -5.5 or 5.75


// Configure which interface theme you want as the default theme (also can be manually switched later, on the settings page in the interface)
$ct_conf['gen']['default_theme'] = 'dark'; // 'dark' or 'light'


// ENABLING CHARTS REQUIRES A CRON JOB / TASK SCHEDULER SETUP (see README.txt for setup information)
// Enables a charts tab / page, and caches real-time updated spot price / 24 hour trade volume chart data on your device's storage drive
// Disabling will disable EVERYTHING related to the price charts (price charts tab / page, and price chart data caching)
$ct_conf['gen']['asset_charts_toggle'] = 'on'; // 'on' / 'off'


// Default BITCOIN market currencies (80+ currencies supported)
// (set for default Bitcoin market, and charts / price alert primary-currency-equivalent value determination [example: usd value of btc/ltc market, etc])
// aed / ars / aud / bam / bdt / bob / brl / bwp / byn / cad / chf / clp / cny / cop / crc / czk / dai 
// dkk / dop / egp / eth / eur / gbp / gel / ghs / gtq / hkd / huf / idr / ils / inr / irr / jmd / jod 
// jpy / kes / krw / kwd / kzt / lkr / mad / mur / mwk / mxn / myr / ngn / nis / nok / nzd / pab / pen 
// php / pkr / pln / pyg / qar / ron / rsd / rub / rwf / sar / sek / sgd / thb / try / tusd / twd / tzs 
// uah / ugx / usd / usdc / usdt / uyu / ves / vnd / xaf / xof / zar / zmw
// SEE THE $ct_conf['assets']['BTC'] CONFIGURATION NEAR THE BOTTOM OF THIS CONFIG FILE, FOR THE PROPER (CORRESPONDING)
// MARKET PAIR VALUE NEEDED FOR YOUR CHOSEN 'BTC' EXCHANGE (set in $ct_conf['gen']['btc_prim_exchange'] directly below)
$ct_conf['gen']['btc_prim_currency_pair'] = 'usd'; // PUT INSIDE SINGLE QUOTES ('selection')


// Default BITCOIN market exchanges (30+ bitcoin exchanges supported)
// (set for default Bitcoin market, and charts / price alert primary-currency-equivalent value determination [example: usd value of btc/ltc market, etc])
// binance / binance_us / bit2c / bitbns / bitfinex / bitflyer / bitmex / bitpanda / bitso / bitstamp 
// bittrex / bittrex_global / btcmarkets / btcturk / buyucoin / cex / coinbase / coindcx / coingecko_sgd 
// coingecko_twd / coingecko_usd / coinspot / gemini / hitbtc / huobi / korbit / kraken / kucoin / liquid 
// localbitcoins / loopring_amm / luno / okcoin / okex / southxchange / unocoin / upbit / wazirx
// SEE THE $ct_conf['assets']['BTC'] CONFIGURATION NEAR THE BOTTOM OF THIS CONFIG FILE, FOR THE PROPER (CORRESPONDING)
// 'BTC' EXCHANGE VALUE NEEDED FOR YOUR CHOSEN MARKET PAIR (set in $ct_conf['gen']['btc_prim_currency_pair'] directly above)
// SEE THE $ct_conf['dev']['limited_apis'] SETTING FURTHER DOWN IN THE DEVELOPER SECTION, FOR EXCHANGES !NOT RECOMMENDED FOR USAGE HERE!
$ct_conf['gen']['btc_prim_exchange'] = 'kraken';  // PUT INSIDE SINGLE QUOTES ('selection')


// Default marketcap data source: 'coingecko', or 'coinmarketcap'
// (COINMARKETCAP REQUIRES A #FREE# API KEY, SEE $ct_conf['ext_api']['coinmarketcap_key'] BELOW in the APIs section)
$ct_conf['gen']['prim_mcap_site'] = 'coingecko'; 


// Maximum decimal places for [primary currency] values, of coins worth under 1.00 in unit value [usd/gbp/eur/jpy/brl/rub/etc],
// for prettier / less-cluttered interface. IF YOU ADJUST $ct_conf['gen']['btc_prim_currency_pair'] ABOVE, 
// YOU MAY NEED TO ADJUST THIS ACCORDINGLY FOR !PRETTY / FUNCTIONAL! CHARTS / ALERTS FOR YOUR CHOSEN PRIMARY CURRENCY
// ALSO KEEP THIS NUMBER AS LOW AS IS FEASIBLE, TO SAVE ON CHART DATA STORAGE SPACE / MAINTAIN QUICK CHART LOAD TIMES
$ct_conf['gen']['prim_currency_dec_max'] = 5; // Whole numbers only (represents number of decimals maximum to use)


// PRICE PERCENTAGE to round off INTERFACE-DISPLAYED price IN DECIMALS (DYNAMIC / RELATIVE to price amount)
// (FINE-GRAINED CONTROL OVER INTERFACE PRICE ROUNDING #AMOUNT OF DECIMALS SHOWN#)
// (interface examples: one = 1000, tenth = 1000, hundredth = 1000.9, thousandth = 1000.09)
// (interface examples: one = 100, tenth = 100.9, hundredth = 100.09, thousandth = 100.009)
// (interface examples: one = 10.9, tenth = 10.09, hundredth = 10.009, thousandth = 10.0009)
// #FIAT# CURRENCY VALUES UNDER 100 #ARE ALWAYS FORCED TO 2 DECIMALS MINUMUM#
// #FIAT# CURRENCY VALUES UNDER 1 #ARE ALWAYS FORCED TO 'prim_currency_dec_max' DECIMALS MAXIMUM#
// THIS SETTING ONLY AFFECTS INTERFACE / COMMS PRICE DISPLAY ROUNDING, IT DOES #NOT# AFFECT BACKGROUND CALCULATIONS
$ct_conf['gen']['price_round_percent'] = 'thousandth'; // (OF A PERCENT) 'one', 'tenth', 'hundredth', 'thousandth'
////
// FORCE a FIXED MINIMUM amount of decimals on interface price, CALCULATED OFF ABOVE price_round_percent SETTING
// (ALWAYS SAME AMOUNT OF DECIMALS, #EVEN IF IT INCLUDES TRAILING ZEROS#) 
$ct_conf['gen']['price_round_fixed_decimals'] = 'off'; // 'off', 'on'


// Number of decimals for price chart CRYPTO 24 hour volumes (NOT USED FOR FIAT VOLUMES, 4 decimals example: 24 hr vol = 91.3874 BTC)
// KEEP THIS NUMBER AS LOW AS IS FEASIBLE, TO SAVE ON CHART DATA STORAGE SPACE / MAINTAIN QUICK CHART LOAD TIMES
$ct_conf['gen']['chart_crypto_vol_dec'] = 4;  // (default = 4)
////
// Every X days backup chart data. 0 disables backups. Email to / from !MUST BE SET! (a download link is emailed to you of the chart data archive)
$ct_conf['gen']['charts_backup_freq'] = 1; 


////////////////////////////////////////
// !END! GENERAL CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! COMMUNICATIONS CONFIGURATION
////////////////////////////////////////


// Allow or disallow sending out ANY communications (email / text / telegram / alexa / etc), so no comms are sent to you unless allowed here
// (PAUSES ALL COMMS IF SET TO 'off')
$ct_conf['comms']['allow_comms'] = 'on'; // 'on' / 'off' (Default = 'on' [comms are sent out normally])


// Enable / disable upgrade checks / alerts (DEFAULT: ALL USER-ACTIVATED COMM CHANNELS)
// (Checks latest release version via github.com API endpoint value "tag_name" 
// @ https://api.github.com/repos/taoteh1221/Open_Crypto_Tracker/releases/latest)
// Choosing 'all' will send to all properly-configured communication channels (and automatically skip any not properly setup)
$ct_conf['comms']['upgrade_alert'] = 'all'; // 'off' (disabled) / 'all' / 'ui' (web interface) / 'email' / 'text' / 'notifyme' / 'telegram'
////
// Wait X days between upgrade reminders
$ct_conf['comms']['upgrade_alert_reminder'] = 7; // (only used if upgrade check is enabled above)


// Every X days email a list of #NEW# RSS feed posts. 
// 0 to disable. Email to / from !MUST BE SET IN COMMS CHANNELS SETUP!
$ct_conf['comms']['news_feed_email_freq'] = 1; // (default = 1)
////
// MAXIMUM #NEW# RSS feed entries to include (per-feed) in news feed EMAIL (less then 'news_feed_email_freq' days old)
$ct_conf['comms']['news_feed_email_entries_show'] = 15; // (default = 15)


// PRICE ALERTS SETUP REQUIRES A CRON JOB / SCHEDULED TASK RUNNING ON YOUR WEB SERVER (see README.txt for setup information) 
///
// Enable / disable price alerts (DEFAULT: ALL USER-ACTIVATED COMM CHANNELS)
// Choosing 'all' will send to all properly-configured communication channels, (and automatically skip any not properly setup)
$ct_conf['comms']['price_alert'] = 'all'; // 'off' (disabled) / 'all' / 'email' / 'text' / 'notifyme' / 'telegram'
////
// Price percent change to send alerts for (WITHOUT percent sign: 15.75 = 15.75%). Sends alerts when percent change reached (up or down)
$ct_conf['comms']['price_alert_thres'] = 8.75; // CAN BE 0 TO DISABLE PRICE ALERTS
////
// Re-allow SAME asset price alert(s) messages after X HOURS (per alert config)
// Set higher if sent to email junk folder / other comms APIs are blocking or throttling your alert messeges 
$ct_conf['comms']['price_alert_freq_max'] = 1; // Can be 0, to have no limits
////
// Block an asset price alert if price retrieved, BUT failed retrieving pair volume (not even a zero was retrieved, nothing)
// Good for BLOCKING QUESTIONABLE EXCHANGES from bugging you with price alerts, especially when used in combination with the minimum volume filter
// (EXCHANGES WITH NO TRADE VOLUME API ARE EXCLUDED [VOLUME IS SET TO ZERO BEFORE THIS FILTER RUNS])
$ct_conf['comms']['price_alert_block_vol_error'] = 'off'; // 'on' / 'off' 
////
// Minimum 24 hour trade volume filter. Only allows sending price alerts if minimum 24 hour trade volume reached
// CAN BE 0 TO DISABLE MINIMUM VOLUME FILTERING, NO DECIMALS OR SEPARATORS, NUMBERS ONLY, WITHOUT the [primary currency] prefix symbol
// THIS FILTER WILL AUTO-DISABLE IF THERE IS ANY ERROR RETRIEVING VOLUME DATA ON A CERTAIN MARKET (NOT EVEN A ZERO IS RECEIVED ON VOLUME API)
// !!WARNING!!: IF AN EXCHANGE DOES #NOT# PROVIDE TRADE VOLUME API DATA FOR MARKETS, SETTING THIS ABOVE 0 WILL 
// #DISABLE ANY CONFIGURED PRICE ALERTS# FOR MARKETS ON THAT EXCHANGE, SO USE WITH CARE!
$ct_conf['comms']['price_alert_min_vol'] = 0; // (default = 0)


// Email logs every X days. 
// 0 to disable. Email to / from !MUST BE SET IN COMMS CHANNELS SETUP!, MAY NOT SEND IN TIMELY FASHION WITHOUT A CRON JOB / SCHEDULED TASK
$ct_conf['comms']['logs_email'] = 3; // (default = 3)


// Alerts for failed proxy data connections (#ONLY USED# IF proxies are enabled further down in PROXY CONFIGURATION). 
// Choosing 'all' will send to all properly-configured communication channels (and automatically skip any not properly setup)
$ct_conf['comms']['proxy_alert'] = 'email'; // 'off' (disabled) / 'all' / 'email' / 'text' / 'notifyme' / 'telegram'
////
$ct_conf['comms']['proxy_alert_freq_max'] = 1; // Re-allow same proxy alert(s) after X HOURS (per ip/port pair, can be 0)
////
// Which runtime mode should allow proxy alerts? Options: 'cron', 'ui', 'all' 
$ct_conf['comms']['proxy_alert_runtime'] = 'cron'; // (default = 'cron')
////
// Include or ignore proxy alerts if proxy checkup went OK? (after flagged, started working again when checked)
$ct_conf['comms']['proxy_alert_checkup_ok'] = 'include'; // 'include' / 'ignore' 


// ~~~~~~~~~~~~~~~  C O M M   C H A N N E L S   S E T U P   (email, text, alexa, telegram, etc)  ~~~~~~~~~~~~~~~

// Use SMTP authentication TO SEND EMAIL, if your IP has no reverse lookup that matches the email domain name (on your home network etc)
// #REQUIRED WHEN INSTALLED ON A HOME NETWORK#, OR ALL YOUR EMAIL ALERTS WILL BE BLACKHOLED / SEEN AS SPAM EMAIL BY EMAIL SERVERS!
// If SMTP credentials / configuration is filled in, BUT not setup properly, APP EMAILING WILL FAIL!
// !!USE A THROWAWAY ACCOUNT ONLY!! If this web server is hacked, HACKER WOULD THEN HAVE ACCESS YOUR EMAIL LOGIN FROM THIS FILE!!
// CAN BE BLANK (PHP's built-in mail function will be automatically used to send email instead)
$ct_conf['comms']['smtp_login'] = ''; // This format MUST be used: 'username||password'
////
// SMTP Server examples (protocol auto-detected / used based off port number): 
// 'example.com:25' (non-encrypted), 'example.com:465' (ssl-encrypted), 'example.com:587' (tls-encrypted)
$ct_conf['comms']['smtp_server'] = ''; // CAN BE BLANK. This format MUST be used: 'domain_or_ip:port_number' 


// IF SMTP EMAIL SENDING --NOT-- USED, FROM email should be a REAL address on the server domain, or risk having email sent to junk folder
// IF SMTP EMAIL SENDING --IS-- USED, FROM EMAIL MUST MATCH EMAIL ADDRESS associated with SMTP login (SMTP Email configuration is above this setting)
$ct_conf['comms']['from_email'] = ''; // #SHOULD BE SET# to avoid email going to spam / junk
////
$ct_conf['comms']['to_email'] = ''; // #MUST BE SET# for price alerts and other email features


// For alert texts to mobile phone numbers. 
// Attempts to email the text if a SUPPORTED MOBILE TEXTING NETWORK name is set, AND no textbelt / textlocal config is setup.
// SMTP-authenticated email sending MAY GET THROUGH TEXTING SERVICE CONTENT FILTERS #BETTER# THAN USING PHP'S BUILT-IN EMAILING FUNCTION
// SEE FURTHER DOWN IN THIS CONFIG FILE, FOR A LIST OF SUPPORTED MOBILE TEXTING NETWORK PROVIDER NAMES 
// IN THE EMAIL-TO-MOBILE-TEXT CONFIG SECTION (the "network name keys" in the $ct_conf['mob_net_txt_gateways'] variables array)
// CAN BE BLANK. Country code format MAY NEED TO BE USED (depending on your mobile network)
// skip_network_name SHOULD BE USED IF USING textbelt / textlocal BELOW
// 'phone_number||network_name_key' (examples: '12223334444||virgin_us' / '12223334444||skip_network_name')
$ct_conf['comms']['to_mobile_text'] = '';


// Do NOT use textbelt AND textlocal together. Leave one setting blank, #OR IT WILL DISABLE# USING BOTH.
// LEAVE textbelt AND textlocal BOTH BLANK to use a mobile text gateway set ABOVE

// CAN BE BLANK. For asset price alert textbelt notifications. Setup: https://textbelt.com/
// SET $ct_conf['comms']['to_mobile_text'] ABOVE IN THE SERVICE PROVIDER AREA TO: skip_network_name
$ct_conf['comms']['textbelt_apikey'] = '';


// CAN BE BLANK. For asset price alert textlocal notifications. Setup: https://www.textlocal.com/integrations/api/
// SET $ct_conf['comms']['to_mobile_text'] ABOVE IN THE SERVICE PROVIDER AREA TO: skip_network_name
$ct_conf['comms']['textlocal_account'] = ''; // This format MUST be used: 'username||hash_code'


// For notifyme / alexa notifications (sending Alexa devices notifications for free). 
// CAN BE BLANK. Setup: http://www.thomptronics.com/notify-me
// (NOTE: THIS APP'S BUILT-IN QUEUE SYSTEM THROTTLES / SENDS OUT ONLY 5 ALERTS EVERY 5 MINUTES MAXIMUM FOR NOTIFYME ALERTS,
// TO STAY WITHIN NOTIFYME API MESSAGE LIMITS, SO YOU WILL ALWAYS #STILL GET ALL YOUR QUEUED NOTIFYME ALERTS#, JUST SLIGHTLY DELAYED)
$ct_conf['comms']['notifyme_accesscode'] = '';


// Sending alerts to your own telegram bot chatroom. 
// (USEFUL IF YOU HAVE ISSUES SETTING UP MOBILE TEXT ALERTS, INCLUDING EMOJI / UNICODE CHARACTER ENCODING)
// Setup: https://core.telegram.org/bots , OR JUST SEARCH / VISIT "BotFather" in the telegram app
// YOU MUST SETUP A TELEGRAM USERNAME #FIRST / BEFORE SETTING UP THE BOT#, IF YOU HAVEN'T ALREADY (IN THE TELEGRAM APP SETTINGS)
// SET UP YOUR BOT WITH "BotFather", AND SAVE YOUR BOT NAME / USERNAME / ACCESS TOKEN / BOT'S CHATROOM IN TELEGRAM APP
// VISIT THE BOT'S CHATROOM IN TELEGRAM APP, #SEND THE MESSAGE "/start" TO THIS CHATROOM# (THIS WILL CREATE USER CHAT DATA THIS APP NEEDS)
// THE USER CHAT DATA #IS REQUIRED# FOR THIS APP TO DETERMINE / SECURELY SAVE YOUR TELEGRAM USER'S CHAT ID WITH THE BOT YOU CREATED
// #DO NOT DELETE THE BOT CHATROOM IN THE TELEGRAM APP, OR YOU WILL STOP RECEIVING MESSAGES FROM THE BOT!#
$ct_conf['comms']['telegram_your_username'] = ''; // Your telegram username (REQUIRED, setup in telegram app settings)
////
$ct_conf['comms']['telegram_bot_username'] = '';  // Your bot's username
////
$ct_conf['comms']['telegram_bot_name'] = ''; // Your bot's human-readable name (example: 'My Alerts Bot')
////
$ct_conf['comms']['telegram_bot_token'] = '';  // Your bot's access token


////////////////////////////////////////
// !END! COMMUNICATIONS CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! EXTERNAL API CONFIGURATION
////////////////////////////////////////


// API key for etherscan.io (required unfortunately, but a FREE level is available): https://etherscan.io/apis
$ct_conf['ext_api']['etherscan_key'] = '';


// API key for coinmarketcap.com Pro API (required unfortunately, but a FREE level is available): https://coinmarketcap.com/api
$ct_conf['ext_api']['coinmarketcap_key'] = '';


// API key for Alpha Vantage (global stock APIs as well as foreign exchange rates (forex) and cryptocurrency data feeds)
// (required unfortunately, but a FREE level is available [paid premium also available]): https://www.alphavantage.co/support/#api-key
$ct_conf['ext_api']['alphavantage_key'] = '';
////
// The below settings will automatically limit your API requests to NEVER go over your DAILY Alpha Vantage API requests limit
// (CONTACT ALPHA VANTAGE SUPPORT, IF YOU ARE UNAWARE OF WHAT YOUR MINUTE / DAILY LIMITS ARE [IF you have a PAID PREMIUM plan])
// The requests-per-MINUTE limit on your Alpha Vantage API key (varies depending on you free / paid member level)
$ct_conf['ext_api']['alphavantage_per_minute_limit'] = 5; // (default = 5 [FOR FREE SERVICE])
// The requests-per-DAY limit on your Alpha Vantage API key (varies depending on you free / paid member level)
$ct_conf['ext_api']['alphavantage_per_day_limit'] = 500; // (default = 500 [FOR FREE SERVICE])


////////////////////////////////////////
// !END! EXTERNAL API CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! SECURITY CONFIGURATION
////////////////////////////////////////


// Interface login protection (htaccess user/password required to view this portfolio app's web interface)
// Username MUST BE at least 4 characters, beginning with ONLY LOWERCASE letters (may contain numbers AFTER first letter), NO SPACES
// Password MUST BE EXACTLY 8 characters, AND contain one number, one UPPER AND LOWER CASE letter, and one symbol, NO SPACES
// (ENABLES / UPDATES automatically, when a valid username / password are filled in or updated here)
// (DISABLES automatically, when username / password are blank '' OR invalid) 
// (!ONLY #UPDATES OR DISABLES# AUTOMATICALLY #AFTER# LOGGING IN ONCE WITH YOUR #OLD LOGIN# [or if a cron job / scheduled task runs with the new config]!)
// DOES #NOT# WORK ON #DESKTOP EDITIONS# (ONLY WORKS ON #SERVER EDITION#)
// #IF THIS SETTING GIVES YOU ISSUES# ON YOUR SYSTEM, BLANK IT OUT TO '', AND DELETE '.htaccess' IN THE MAIN DIRECTORY OF 
// THIS APP (TO RESTORE PAGE ACCESS), AND PLEASE REPORT IT HERE: https://github.com/taoteh1221/Open_Crypto_Tracker/issues
$ct_conf['sec']['interface_login'] = ''; // Leave blank to disable requiring an interface login. This format MUST be used: 'username||password'


// Password protection / encryption security for backup archives (REQUIRED for app config backup archives, #NOT# USED FOR CHART BACKUPS)
$ct_conf['sec']['backup_arch_pass'] = ''; // LEAVE BLANK TO DISABLE


// Enable / disable admin login alerts (DEFAULT: ALL USER-ACTIVATED COMM CHANNELS)
// Choosing 'all' will send to all properly-configured communication channels, (and automatically skip any not properly setup)
$ct_conf['sec']['login_alert'] = 'all'; // 'off' (disabled) / 'all' / 'email' / 'text' / 'notifyme' / 'telegram'
							
							
// HOURS until admin login cookie expires (requiring you to login again)
// The lower number the better for higher security, epecially if the app server temporary session data 
// doesn't auto-clear often (that also logs you off automatically, REGARDLESS of this setting's value)
$ct_conf['sec']['admin_cookie_expire'] = 6; // (default = 6, MAX ALLOWED IS 6)


// 'on' verifies ALL SMTP server certificates for secure SMTP connections, 'off' verifies NOTHING 
// Set to 'off' if the SMTP server has an invalid certificate setup (which stops email sending, but you still want to send email through that server)
$ct_conf['sec']['smtp_strict_ssl'] = 'off'; // (DEFAULT IS 'off', TO ASSURE SMTP EMAIL SENDING STILL WORKS THROUGH MISCONFIGURED SMTP SERVERS)


// 'on' verifies ALL REMOTE API server certificates for secure API connections, 'off' verifies NOTHING 
// Set to 'off' if some exchange's API servers have invalid certificates (which stops price data retrieval...but you still want to get price data from them)
$ct_conf['sec']['remote_api_strict_ssl'] = 'off'; // (default = 'off')


// Set CORS 'Access-Control-Allow-Origin' (controls what web domains can load this app's admin / user pages, AJAX scripts, etc)
// Set to 'any' if this web server's domain can vary / redirect (eg: some INITIAL visits are 'www.mywebsite.com', AND some are 'mywebsite.com')
// Set to 'strict' if this web server's domain CANNOT VARY / REDIRECT (it's always 'mywebsite.com', EVERY VISIT #WITHOUT EXCEPTIONS#)
// 'strict' mode blocks all CSRF / XSS attacks on resources using this setting, ALTHOUGH NOT REALLY NEEDED AS SERVER EDITIONS USE STRICT / SECURE COOKIES
// #CHANGE WITH CAUTION#, AS 'strict' #CAN BREAK CHARTS / LOGS / NEWS FEEDS / ADMIN SECTIONS / ETC FROM LOADING# ON SOME SETUPS!
$ct_conf['sec']['access_control_origin'] = 'any'; // 'any' / 'strict' (default = 'any')
		

// CONTRAST of CAPTCHA IMAGE text against background (on login pages)
// 0 for neutral contrast, positive for more contrast, negative for less contrast (MAXIMUM OF +-35)
$ct_conf['sec']['captcha_text_contrast'] = -8; // example: -5 or 5 (default = -8)
////
// MAX OFF-ANGLE DEGREES (tilted backward / forward) of CAPTCHA IMAGE text characters (MAXIMUM OF 35)
$ct_conf['sec']['captcha_text_angle'] = 35; // (default = 35)
////
$ct_conf['sec']['captcha_text_size'] = 50; // Text size (default = 50)
////
$ct_conf['sec']['captcha_chars_length'] = 7; // Number of characters in captcha image (default = 7)
////
// Configuration for advanced CAPTCHA image settings on all admin login / reset pages
$ct_conf['sec']['captcha_image_width'] = 525; // Image width (default = 525)
////
$ct_conf['sec']['captcha_image_height'] = 135; // Image height (default = 135)
////
$ct_conf['sec']['captcha_text_margin'] = 10; // MINIMUM margin of text from edge of image (approximate / average) (default = 10)
////		
// Only allow the MOST READABLE characters for use in captcha image 
// (DON'T SET TOO LOW, OR BOTS CAN GUESS THE CAPTCHA CODE EASIER)
$ct_conf['sec']['captcha_permitted_chars'] = 'ABCDEFHJKMNPRSTUVWXYZ23456789'; // (default = 'ABCDEFHJKMNPRSTUVWXYZ23456789')


// Cache directories / files and .htaccess / index.php files permissions (CHANGE WITH #EXTREME# CARE, to adjust security for your PARTICULAR setup)
// THESE PERMISSIONS ARE !ALREADY! CALLED THROUGH THE octdec() FUNCTION *WITHIN THE APP WHEN USED*
////
// Cache directories permissions
$ct_conf['sec']['chmod_cache_dir'] = '0770'; // (default = '0770' [owner/group read/write/exec])
////
// Cache files permissions
$ct_conf['sec']['chmod_cache_file'] = '0660'; // (default = '0660' [owner/group read/write])
////
// .htaccess / index.php index security files permissions
$ct_conf['sec']['chmod_index_sec'] = '0660'; // (default = '0660' [owner/group read/write])


////////////////////////////////////////
// !END! SECURITY CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! PROXY CONFIGURATION
////////////////////////////////////////


// If using proxies, add the ip address / port number here for each one, like examples below (without the double slashes in front enables the code)
// CAN BE BLANK. Adding proxies here will automatically choose one randomly for each API request
$ct_conf['proxy']['proxy_list'] = array(
					// 'ipaddress1:portnumber1',
					// 'ipaddress2:portnumber2',
					);
////
// If using proxies and login is required
// Adding a user / pass here will automatically send login details for proxy connections
// CAN BE BLANK. IF using ip address authentication instead, MUST BE LEFT BLANK
$ct_conf['proxy']['proxy_login'] = ''; // Use format: 'username||password'


////////////////////////////////////////
// !END! PROXY CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! CHART AND PRICE ALERT MARKETS
////////////////////////////////////////


// CHARTS / PRICE ALERTS SETUP REQUIRES A CRON JOB OR SCHEDULED TASK RUNNING ON YOUR WEB SERVER (see README.txt for setup information) 
////
// Markets you want charts or asset price change alerts for (see the COMMUNICATIONS section for price alerts threshold settings) 
// NOTE: This list must only contain assets / exchanges / trading pairs included in the primary portfolio assets list configuration further down in this config file
// TO ADD MULTIPLE CHARTS / ALERTS FOR SAME ASSET (FOR DIFFERENT EXCHANGES / TRADE PAIRS), FORMAT LIKE SO: symbol, symbol-1, symbol-2, symbol-3, etc.
// TO DISABLE A CHART / ALERT = none, TO ENABLE CHART AND ALERT = both, TO ENABLE CHART ONLY = chart, TO ENABLE ALERT ONLY = alert
$ct_conf['charts_alerts']['tracked_mrkts'] = array(


					// SYMBOL
				// 'symbol' => 'exchange||trade_pair||alert',
				// 'symbol-2' => 'exchange2||trade_pair2||chart',
				// 'symbol-3' => 'exchange3||trade_pair3||both',
				// 'symbol-4' => 'exchange4||trade_pair4||none',
				
				
					// OTHERSYMBOL
				// 'othersymbol' => 'exchange||trade_pair||none',
				// 'othersymbol-2' => 'exchange2||trade_pair2||both',
				// 'othersymbol-3' => 'exchange3||trade_pair3||chart',
				// 'othersymbol-4' => 'exchange4||trade_pair4||alert',
					
					
					// BTC
					'btc' => 'coinbase||usd||chart',
					'btc-2' => 'binance||usdt||chart',
					'btc-3' => 'bitstamp||usd||none',
					'btc-4' => 'kraken||usd||chart',
					'btc-5' => 'gemini||usd||none',
					'btc-6' => 'bitfinex||usd||none',
					'btc-7' => 'binance_us||usd||none',
					'btc-8' => 'kraken||eur||chart',
					'btc-9' => 'coinbase||eur||none',
					'btc-10' => 'coinbase||gbp||none',
					'btc-11' => 'kraken||cad||none',
					'btc-12' => 'btcmarkets||aud||none',
					'btc-13' => 'bitbns||inr||none',
					'btc-14' => 'localbitcoins||inr||none',
					'btc-15' => 'localbitcoins||cny||none',
					'btc-16' => 'bitflyer||jpy||chart',
					'btc-17' => 'liquid||hkd||none',
					'btc-18' => 'localbitcoins||chf||none',
					'btc-19' => 'upbit||krw||none',
					'btc-20' => 'bitso||mxn||none',
					'btc-21' => 'localbitcoins||nzd||none',
					'btc-22' => 'localbitcoins||rub||none',
					'btc-24' => 'btcturk||try||none',
					'btc-25' => 'coingecko_twd||twd||none',
					'btc-26' => 'luno||zar||none',
					'btc-27' => 'kraken||dai||none',
					'btc-28' => 'bitmex||usd||both',
					
					
					// ETH
					'eth' => 'coinbase||btc||none',
					'eth-2' => 'bittrex||btc||none',
					'eth-3' => 'kraken||btc||chart',
					'eth-4' => 'binance||usdt||chart',
					'eth-5' => 'binance_us||btc||none',
					'eth-6' => 'coinbase||usd||chart',
					'eth-7' => 'kraken||usd||none',
					'eth-8' => 'bitstamp||usd||none',
					'eth-9' => 'gemini||usd||none',
					'eth-10' => 'coinbase||gbp||none',
					'eth-11' => 'coinbase||eur||chart',
					'eth-12' => 'bittrex||usdt||none',
					'eth-13' => 'bitbns||inr||none',
					'eth-14' => 'bitmex||usd||both',
					
					
					// SOL
					'sol' => 'binance||btc||none',
					'sol-2' => 'coinbase||usd||both',
					'sol-3' => 'coinbase||btc||chart',
					'sol-4' => 'binance||eth||chart',
					
					
					// COINSTOCK (Coinbase stock)
					'coinstock' => 'alphavantage_stock||usd||both',
					
					
					// AMZNSTOCK (Amazon stock)
					'amznstock' => 'alphavantage_stock||usd||both',
					
					
					// GOOGLSTOCK (Google stock)
					'googlstock' => 'alphavantage_stock||usd||both',
					
					
					// GPVSTOCK (GreenPower Motor Company stock)
					'gpvstock' => 'alphavantage_stock||cad||both',
					
					
					// DTGSTOCK (Daimler Truck Holding stock)
					'dtgstock' => 'alphavantage_stock||eur||both',
					
					
					// APT
					'apt' => 'coinbase||usd||both',
					'apt-2' => 'kraken||eur||chart',
					'apt-3' => 'binance||btc||chart',
					'apt-4' => 'gateio||eth||chart',
					
					
					// UNI
					'uni' => 'binance||btc||both',
					'uni-3' => 'binance||usdt||none',
					'uni-4' => 'coinbase||usd||none',
					
					
					// MKR
					'mkr' => 'okex||btc||none',
					'mkr-2' => 'kucoin||btc||none',
					'mkr-3' => 'coinbase||btc||both',
					
					
					// DAI
					'dai' => 'coinbase||usd||both',
					'dai-2' => 'kraken||usd||none',
					'dai-3' => 'bittrex||btc||none',
					
					
					// USDC
					'usdc' => 'kraken||usd||both',
					'usdc-2' => 'binance_us||usd||none',
					
					
					// MSOL
					'msol' => 'coingecko_usd||usd||chart',
					
					
					// MANA
					'mana' => 'bittrex||btc||chart',
					'mana-2' => 'binance||btc||both',
					'mana-3' => 'kucoin||btc||none',
					'mana-4' => 'ethfinex||btc||none',
					'mana-5' => 'binance||eth||none',
					
					
					// ATLAS
					'atlas' => 'gateio||usdt||chart',
					'atlas-2' => 'coingecko_btc||btc||chart',
					'atlas-3' => 'kraken||usd||both',
					
					
					// POLIS
					'polis' => 'coingecko_btc||btc||chart',
					'polis-2' => 'kraken||usd||both',
					
					
					// RAY
					'ray' => 'binance||usdt||both',
					'ray-2' => 'coingecko_btc||btc||chart',
					
					
					// SLRS
					'slrs' => 'gateio||usdt||both',
					'slrs-2' => 'gateio||eth||chart',
					
					
					// BIT
					'bit' => 'gateio||usdt||both',
					'bit-2' => 'coingecko_btc||btc||chart',
					
					
					// HNT
					'hnt-2' => 'binance_us||usd||both',
					'hnt-3' => 'gateio||eth||none',
					
					
					// RNDR
					'rndr' => 'huobi||btc||both',
					'rndr-2' => 'gateio||usdt||none',
					
					
					// ZBC
					'zbc' => 'coingecko_btc||btc||chart',
					'zbc-2' => 'coingecko_eth||eth||chart',
					'zbc-3' => 'gateio||usdt||both',
					
					
					// GRAPE
					'grape' => 'coingecko_usd||usd||both',
					'grape-2' => 'coingecko_btc||btc||chart',
					'grape-3' => 'coingecko_eth||eth||chart',
					'grape-4' => 'coingecko_eur||eur||none',
					'grape-5' => 'coingecko_gbp||gbp||none',
					
					
					// SLC
					'slc' => 'coingecko_btc||btc||chart',
					'slc-2' => 'gateio||usdt||both',
					'slc-3' => 'coingecko_eth||eth||chart',
					
					
					// HIVE
					'hive' => 'bittrex||btc||both',
					
					
					);
					
// END $ct_conf['charts_alerts']['tracked_mrkts']


////////////////////////////////////////
// !END! CHART AND PRICE ALERT MARKETS
////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
// !START! POWER USER CONFIGURATION (ADJUST WITH CARE, OR YOU CAN BREAK THE APP!)
/////////////////////////////////////////////////////////////////////////////


// Activate any built-in included plugins / custom plugins you've created (that run from the /plugins/ directory)
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt for creating your own custom plugins
// ADD ANY NEW PLUGIN HERE BY USING THE FOLDER NAME THE NEW PLUGIN IS LOCATED IN
// !!NEVER ADD A PLUGIN SOMEBODY ELSE WROTE, UNLESS YOU OR SOMEONE YOU TRUST 
// HAVE REVIEWED THE CODE AND ARE ABSOLUTELY SURE IT IS NOT MALICIOUS!!
// PLUGINS *MAY REQUIRE* A CRON JOB / SCHEDULED TASK RUNNING ON YOUR WEB SERVER (if built for cron jobs...see README.txt for setup information)
// PLUGIN CONFIGS are in the /plugins/ directory associated with that plugin
// CHANGE 'off' to 'on' FOR THE PLUGIN YOU WANT ACTIVATED 
$ct_conf['power']['activate_plugins'] = array(
            								  //'plugin-folder-name' => 'on', // (disabled example...your LOWERCASE plugin folder name in the folder: /plugins/)
            								  'debt-tracker' => 'on',  // Track how much you pay in TOTAL interest MONTHLY on ALL your debt (credit cards, auto / personal / mortgage loan, etc)
            								  'recurring-reminder' => 'off',  // Recurring Reminder plugin (alert yourself every X days to do something)
            								  'price-target-alert' => 'off',  // Price target alert plugin (alert yourself when an asset's price target is reached)
            								  'address-balance-tracker' => 'off',  // Alerts for BTC / ETH / [SOL|SPL Token] / HNT address balance changes (when coins are sent / recieved)
            								  );


// NEWS FEED SETTINGS (ATOM / RSS formats supported)
// RSS feed entries to show (per-feed) on News page (without needing to click the "show more / less" link)
$ct_conf['power']['news_feed_entries_show'] = 15; // (default = 15)
////
// RSS feed entries under X DAYS old are marked as 'new' on the news page
$ct_conf['power']['news_feed_entries_new'] = 1; // (default = 1)
							
							
// MINUTES to wait until running consecutive desktop edition emulated cron jobs
// (HOW OFTEN BACKGROUND TASKS ARE RUN...#NOT# USED IN SERVER EDITION)
// SET TO ZERO DISABLES EMULATED CRON JOBS ON #DESKTOP EDITIONS#
// DON'T SET TOO LOW, OR EXCHANGE PRICE DATA MAY BE BLOCKED / THROTTLED TEMPORARILY ON OCASSION!
// IF USING ADD-WIN10-SCHEDULER-JOB.bat, #THIS SETTING NEEDS TO BE DISABLED# OR THE SCHEDULED TASK WILL #NOT# BE ALLOWED TO RUN!
// IF YOU CHANGE THIS SETTING, YOU *MUST* RESTART / RELOAD THE APP *AFTERWARDS*!
$ct_conf['power']['desktop_cron_interval'] = 20; // (default = 20, 0 disables this feature)
							
							
// SECONDS to wait for response from REMOTE API endpoints (exchange data, etc). 
// Set too low you won't get ALL data (partial or zero bytes), set too high the interface can take a long time loading if an API server hangs up
// RECOMMENDED MINIMUM OF 60 FOR INSTALLS BEHIND #LOW BANDWIDTH# NETWORKS 
// (which may need an even higher timeout above 60 if data still isn't FULLY received from all APIs)
// YOU WILL GET ALERTS IN THE ERROR LOGS IF YOU NEED TO ADJUST THIS
$ct_conf['power']['remote_api_timeout'] = 30; // (default = 30)


// MINUTES to cache real-time exchange price data...can be zero to skip cache, but set to at least 1 minute TO AVOID YOUR IP ADDRESS GETTING BLOCKED
// SOME APIS PREFER THIS SET TO AT LEAST A FEW MINUTES, SO IT'S RECOMMENDED TO KEEP FAIRLY HIGH
$ct_conf['power']['last_trade_cache_time'] = 4; // (default = 4)


// MINUTES to cache blockchain stats (for mining calculators). Set high initially, it can be strict
$ct_conf['power']['chainstats_cache_time'] = 60;  // (default = 60)


// MINUTES to cache marketcap rankings...START HIGH and test lower, it can be STRICT
// (coingecko #ABSOLUTELY HATES# DATA CENTER IPS [DEDICATED / VPS SERVERS], BUT GOES EASY ON RESIDENTIAL IPS)
$ct_conf['power']['mcap_cache_time'] = 60;  // (default = 60)
////
// Number of marketcap rankings to request from API.
// 500 rankings is a safe maximum to START WITH, to avoid getting your API requests THROTTLED / BLOCKED
// (coingecko #ABSOLUTELY HATES# DATA CENTER IPS [DEDICATED / VPS SERVERS], BUT GOES EASY ON RESIDENTIAL IPS)
$ct_conf['power']['mcap_ranks_max'] = 500; // (default = 500)


// Maximum margin leverage available in the user interface ('Update' page, etc)
$ct_conf['power']['margin_lvrg_max'] = 150; 


// Days TO WAIT UNTIL DELETING OLD backup archives (chart data archives, etc)
$ct_conf['power']['backup_arch_del_old'] = 14; 


// Keep logs X DAYS before purging (fully deletes logs every X days). Start low (especially when using proxies)
$ct_conf['power']['logs_purge'] = 7; // (default = 7)


// (Light) time period charts (load just as quickly for any time period, 7 day / 30 day / 365 day / etc)
// Structure of light charts #IN DAYS# (X days time period charts)
// Interface will auto-detect and display days IN THE INTERFACE as: 365 = 1Y, 180 = 6M, 30 = 1M, 7 = 1W, etc
// (JUST MAKE SURE YOU USE 365 / 30 / 7 *MULTIPLIED BY THE NUMBER OF* YEARS / MONTHS / WEEKS FOR PROPER AUTO-DETECTION/CONVERSION)
// (LOWER TIME PERIODS [UNDER 180 DAYS] #SHOULD BE KEPT SOMEWHAT MINIMAL#, TO REDUCE RUNTIME LOAD / DISK WRITES DURING CRON JOBS)
$ct_conf['power']['light_chart_day_intervals'] = array(14, 30, 90, 180, 365, 730, 1460);
// (default = 14, 30, 90, 180, 365, 730, 1460)
////
// The maximum number of data points allowed in each light chart 
// (saves on disk storage / speeds up chart loading times SIGNIFICANTLY #WITH A NUMBER OF 750 OR LESS#)
$ct_conf['power']['light_chart_data_points_max'] = 750; // (default = 750), ADJUST WITH CARE!!!


// Default settings for Asset Performance chart height / menu size (in the 'View More Stats' modal window, linked at bottom of Portfolio page)
// CHART HEIGHT MIN/MAX = 400/900 (increments of 100), MENU SIZE MIN/MAX (increments of 1) = 7/16
$ct_conf['power']['asset_perf_chart_defaults'] = '800||10'; // 'chart_height||menu_size' (default = '800||10')


// Default settings for Marketcap Comparison chart height / menu size (in the 'View More Stats' modal window, linked at bottom of Portfolio page)
// CHART HEIGHT MIN/MAX = 400/900 (increments of 100), MENU SIZE MIN/MAX (increments of 1) = 7/16
$ct_conf['power']['asset_mcap_chart_defaults'] = '600||10'; // 'chart_height||menu_size' (default = '600||10')


// Highest allowed sensor value to scale vertical axis for, in the FIRST system information chart  (out of two)
// (higher sensor data is moved into the second chart, to keep ranges easily readable between both charts...only used IF CRON JOB IS SETUP)
$ct_conf['power']['sys_stats_first_chart_max_scale'] = 3.25; // (default = 3.25) 
////
// Highest allowed sensor value to scale vertical axis for, in the SECOND system information chart (out of two)
// (to prevent anomaly results from scaling vertical axis TOO HIGH to read LESSER-VALUE sensor data...only used IF CRON JOB IS SETUP)
$ct_conf['power']['sys_stats_second_chart_max_scale'] = 325; // (default = 325) 

																		
// Fixed time interval RESET of cached comparison asset prices every X days (since last price reset / alert), compared with the current latest spot prices
// Helpful if you only want price alerts for a certain time window. Resets also send alerts that reset occurred, with summary of price changes since last reset
// Can be 0 to disable fixed time interval resetting (IN WHICH CASE RESETS WILL ONLY OCCUR DYNAMICALLY when the next price alert is triggered / sent out)
$ct_conf['power']['price_alert_fixed_reset'] = 0; // (default = 0)
////
// Whale alert (adds "WHALE ALERT" to beginning of alexa / email / telegram alert text, and spouting whale emoji to email / text / telegram)
// Format: 'max_days_to_24hr_avg_over||min_price_percent_change_24hr_avg||min_vol_percent_increase_24hr_avg||min_vol_currency_increase_24hr_avg'
// ("min_price_percent_change_24hr_avg" should be the same value or higher as $ct_conf['comms']['price_alert_thres'] to work properly)
// Leave BLANK '' TO DISABLE. DECIMALS ARE SUPPORTED, USE NUMBERS ONLY (NO CURRENCY SYMBOLS / COMMAS, ETC)
$ct_conf['power']['price_alert_whale_thres'] = '1.25||9.75||12.75||25000'; // (default: '1.25||9.75||12.75||25000')	
			
			
// Configuration for system resource warning thresholds (logs to error log, and sends comms alerts to any activated comms)
// (WHEN THE SYSTEM RESOURCES REACH THESE VALUES [and it's been hours_between_alerts since last alert],
// THE WARNINGS ARE TRIGGERED TO BE LOGGED / SENT TO ADMIN COMMS)
// !!LEAVE YOURSELF SOME #EXTRA ROOM# ON THESE VALUES, TO BE ALERTED #BEFORE# YOUR SYSTEM WOULD RISK CRASHING!!
////
// If SYSTEM UPTIME has only been up X DAYS (or less), trigger warning
$ct_conf['power']['system_uptime_warning'] = '0||36'; // 'days_uptime||hours_between_alerts' (default = '0||36')
////
// SYSTEM LOAD warning default is 2x number of cores your app server has (1 core system = load level 2.00 would trigger an alert)
// SYSTEM LOAD THRESHOLD MULTIPLIER allows you to adjust when warning is triggered (0.5 is half default, 2.00 is 2x default, etc)
$ct_conf['power']['system_load_warning'] = '1.00||4';  // 'threshold_multiplier||hours_between_alerts' (default = '1.00||4')
////
// If system TEMPERATURE is X degrees celcius (or more), trigger warning
$ct_conf['power']['system_temp_warning'] = '70||1'; // 'temp_celcius||hours_between_alerts' (default = '70||1')
////
// If USED MEMORY PERCENTAGE is X (or more), trigger warning
$ct_conf['power']['memory_used_percent_warning'] = '90||4'; // 'memory_used_percent||hours_between_alerts' (default = '90||4')
////
// If FREE STORAGE space is X MEGABYTES (or less), trigger warning
$ct_conf['power']['free_partition_space_warning'] = '1000||24'; // 'free_space_megabytes||hours_between_alerts' (default = '1000||24')
////
// If PORTFOLIO CACHE SIZE is X MEGABYTES (or more), trigger warning
$ct_conf['power']['portfolio_cache_warning'] = '2000||72'; // 'portfolio_cache_megabytes||hours_between_alerts' (default = '2000||72')
////
// If ALL COOKIES TOTAL DATA SIZE is X BYTES (or more), trigger warning
// Because the header data MAY be approaching the server limit (WHICH CAN CRASH THIS APP!!)
// STANDARD SERVER HEADER SIZE LIMITS (IN BYTES)...Apache: 8000, NGINX: 4000 - 8000, IIS: 8000 - 16000, Tomcat: 8000 - 48000
$ct_conf['power']['cookies_size_warning'] = '7000||6'; // 'cookies_size_bytes||hours_between_alerts' (default = '7000||6')


// PRICE CHARTS colors (https://www.w3schools.com/colors/colors_picker.asp)
////
// Charts border color
$ct_conf['power']['charts_border'] = '#808080'; // (default: '#808080')
////
// Charts background color
$ct_conf['power']['charts_background'] = '#515050';   // (default: '#515050')
////
// Charts line color
$ct_conf['power']['charts_line'] = '#444444';   // (default: '#444444')
////
// Charts text color
$ct_conf['power']['charts_text'] = '#e8e8e8';   // (default: '#e8e8e8')
////
// Charts link color
$ct_conf['power']['charts_link'] = '#939393';   // (default: '#939393')
////
// Charts price (base) gradient color
$ct_conf['power']['charts_price_gradient'] = '#000000';  // (default: '#000000')
////
// Charts tooltip background color
$ct_conf['power']['charts_tooltip_background'] = '#bbbbbb'; // (default: '#bbbbbb')
////
// Charts tooltip text color
$ct_conf['power']['charts_tooltip_text'] = '#222222'; // (default: '#222222')


// Local / internal REST API rate limit (maximum of once every X SECONDS, per ip address) for accepting remote requests
// Can be 0 to disable rate limiting (unlimited)
$ct_conf['power']['local_api_rate_limit'] = 1; // (default = 1)
////
// Local / internal REST API market limit (maximum number of MARKETS requested per call)
$ct_conf['power']['local_api_mrkt_limit'] = 35; // (default = 35)
////
// Local / internal REST API cache time (MINUTES that previous requests are cached for)
$ct_conf['power']['local_api_cache_time'] = 1; // (default = 1)
							

// Auto-activate support for ALTCOIN PAIRED MARKETS (like glm/eth or mkr/eth, etc...markets where the base pair is an altcoin)
// EACH ALTCOIN LISTED HERE !MUST HAVE! AN EXISTING 'btc' MARKET (within 'pair') in it's 
// $ct_conf['assets'] listing (further down in this config file) TO PROPERLY AUTO-ACTIVATE
// THIS ALSO ADDS THESE ASSETS AS OPTIONS IN THE "Show Crypto Value Of ENTIRE Portfolio In" SETTING, ON THE SETTINGS PAGE,
// AND IN THE "Show Secondary Trade / Holdings Value" SETTING, ON THE SETTINGS PAGE TOO
// !!!!!TRY TO #NOT# ADD STABLECOINS HERE!!!!!, FIRST TRY $ct_conf['power']['btc_currency_mrkts'] INSTEAD (TO AUTO-CLIP UN-NEEDED DECIMAL POINTS) 
// !!!!!BTC IS ALREADY ADDED *AUTOMATICALLY*, NO NEED TO ADD IT HERE!!!!!
$ct_conf['power']['crypto_pair'] = array(
						//'lowercase_altcoin_ticker' => 'UNICODE_SYMBOL', // Add whitespace after the symbol, if you prefer that
						// Native chains...
						'eth' => 'Ξ ',
						'sol' => '◎ ',
						'apt' => 'Ⓐ ',
						// ERC-20 tokens on Ethereum / SPL tokens on Solana, etc etc...
						'uni' => '🦄 ',
						'mkr' => '𐌼 ',
						'ray' => 'Ｒ ',
						'hnt' => 'Ȟ ',
						//...
							);



// Preferred ALTCOIN PAIRED MARKETS market(s) for getting a certain crypto's value
// EACH ALTCOIN LISTED HERE MUST EXIST IN $ct_conf['power']['crypto_pair'] ABOVE,
// AND !MUST HAVE! AN EXISTING 'btc' MARKET (within 'pair') in it's 
// $ct_conf['assets'] listing (further down in this config file),
// AND #THE EXCHANGE NAME MUST BE IN THAT 'btc' LIST#
// #USE LIBERALLY#, AS YOU WANT THE BEST PRICE DISCOVERY FOR THIS CRYPTO'S VALUE
$ct_conf['power']['crypto_pair_pref_mrkts'] = array(
						    //'lowercase_btc_mrkt_or_stablecoin_pair' => 'PREFERRED_MRKT',
							'eth' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'sol' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'apt' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'uni' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'mkr' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'ray' => 'coingecko_btc',  // coingecko global average price IN BTC
							'hnt' => 'coingecko_btc',  // coingecko global average price IN BTC
							);



// Auto-activate support for PRIMARY CURRENCY MARKETS (to use as your preferred local currency in the app)
// EACH CURRENCY LISTED HERE !MUST HAVE! AN EXISTING BITCOIN ASSET MARKET (within 'pair') in 
// Bitcoin's $ct_conf['assets'] listing (further down in this config file) TO PROPERLY AUTO-ACTIVATE
// #CAN# BE A CRYPTO / HAVE A DUPLICATE IN $ct_conf['power']['crypto_pair'], 
// !AS LONG AS THERE IS A PAIR CONFIGURED WITHIN THE BITCOIN ASSET SETUP!
$ct_conf['power']['btc_currency_mrkts'] = array(
						//'lowercase_btc_mrkt_or_stablecoin_pair' => 'CURRENCY_SYMBOL',
						'aed' => 'د.إ',
						'ars' => 'ARS$',
						'aud' => 'A$',
						'bam' => 'KM ',
						'bdt' => '৳',
						'bob' => 'Bs ',
						'brl' => 'R$',
						'bwp' => 'P ',
						'byn' => 'Br ',
						'cad' => 'C$',
						'chf' => 'CHf ',
						'clp' => 'CLP$',
						'cny' => 'C¥',
						'cop' => 'Col$',
						'crc' => '₡',
						'czk' => 'Kč ',
						'dai' => '◈ ',
						'dkk' => 'Kr. ',
						'dop' => 'RD$',
						'egp' => 'ج.م',
						'eth' => 'Ξ ',
						'eur' => '€',
						'gbp' => '£',
						'gel' => 'ლ',
						'ghs' => 'GH₵',
						'gtq' => 'Q ',
						'hkd' => 'HK$',
						'huf' => 'Ft ',
						'idr' => 'Rp ',
						'ils' => '₪',
						'inr' => '₹',
						'irr' => '﷼',
						'jmd' => 'JA$',
						'jod' => 'د.ا',
						'jpy' => 'J¥',
						'kes' => 'Ksh ',
						'krw' => '₩',
						'kwd' => 'د.ك',
						'kzt' => '₸',
						'lkr' => 'රු, ரூ',
						'mad' => 'د.م.',
						'mur' => '₨ ',
						'mwk' => 'MK ',
						'mxn' => 'Mex$',
						'myr' => 'RM ',
						'ngn' => '₦',
						'nis' => '₪',
						'nok' => 'kr ',
						'nzd' => 'NZ$',
						'pab' => 'B/. ',
						'pen' => 'S/ ',
						'php' => '₱',
						'pkr' => '₨ ',
						'pln' => 'zł ',
						'pyg' => '₲',
						'qar' => 'ر.ق',
						'ron' => 'lei ',
						'rsd' => 'din ',
						'rub' => '₽',
						'rwf' => 'R₣ ',
						'sar' => '﷼',
						'sek' => 'kr ',
						'sgd' => 'S$',
						'thb' => '฿',
						'try' => '₺',
						'tusd' => 'Ⓢ ',
						'twd' => 'NT$',
						'tzs' => 'TSh ',
						'uah' => '₴',
						'ugx' => 'USh ',
						'usd' => '$',
						'usdc' => 'Ⓢ ',
						'usdt' => '₮ ',
						'uyu' => '$U ',
						'vnd' => '₫',
						'ves' => 'Bs ',
						'xaf' => 'FCFA ',
						'xof' => 'CFA ',
						'zar' => 'R ',
						'zmw' => 'ZK ',
							);



// Preferred BITCOIN market(s) for getting a certain currency's value
// (when other exchanges for this currency have poor api / volume / price discovery / etc)
// EACH CURRENCY LISTED HERE MUST EXIST IN $ct_conf['power']['btc_currency_mrkts'] ABOVE
// #USE LIBERALLY#, AS YOU WANT THE BEST PRICE DISCOVERY FOR THIS CURRENCY'S VALUE
$ct_conf['power']['btc_pref_currency_mrkts'] = array(
						//'lowercase_btc_mrkt_or_stablecoin_pair' => 'PREFERRED_MRKT',
							'aud' => 'kraken',  // WAY BETTER api than ALL alternatives
							'chf' => 'kraken',  // WAY MORE reputable than ALL alternatives
							'dai' => 'kraken',  // WAY MORE reputable than ALL alternatives
							'eur' => 'kraken',  // WAY BETTER api than ALL alternatives
							'gbp' => 'kraken',  // WAY BETTER api than ALL alternatives
							'inr' => 'localbitcoins',  // WAY MORE volume / price discovery than ALL alternatives
							'jpy' => 'kraken',  // WAY MORE reputable than ALL alternatives
							'rub' => 'localbitcoins',  // WAY MORE volume / price discovery than ALL alternatives
							'usd' => 'kraken',  // WAY BETTER api than ALL alternatives
							);



// Static values in ETH for Ethereum subtokens, like during crowdsale periods etc (before exchange listings)
$ct_conf['power']['eth_erc20_icos'] = array(
                        'ETHSUBTOKENNAME' => '0.15',
                        'GOLEM' => '0.001',
                        'ARAGON' => '0.01',
                        'DECENTRALAND' => '0.00008',
                        );
						


// HIVE INTEREST CALCULATOR SETTINGS
// Weeks to power down all HIVE Power holdings
$ct_conf['power']['hive_powerdown_time'] = 13; 
////
// HIVE Power yearly interest rate AS OF 11/29/2022 (0.975%, decreasing every year by roughly 0.075% until it hits a minimum of 0.075% and stays there)
// (DO NOT INCLUDE PERCENT SIGN), see above for manual yearly adjustment
$ct_conf['power']['hivepower_yearly_interest'] = 0.975; // (Default = 0.975 as of 11/29/22)



// Mining calculator configs for different crypto networks (SEMI-AUTOMATICALLY adds mining calculators to the Mining page)
// FOR #DYNAMIC# CHAIN STATS (height / difficuly / rewards / etc), API CALL FUNCTIONS NEED TO BE CUSTOM-WRITTEN FOR ANY #CUSTOM# ASSETS ADDED HERE,
// AND CALLED WITHIN THE 'Update dynamic mining data' SECTION OF THE FILE: /app-lib/php/inline/config/config-auto-adjust.php
// 'mining_time_formula' ALSO NEEDS TO BE DYNAMICALLY ADDED IN THAT SAME SECTION, #OR YOUR CUSTOM CALCULATOR WILL NOT WORK AT ALL#
// ('PLACEHOLDER' values are dynamically populated during app runtime)
$ct_conf['power']['mining_calculators'] = array(
					
					
			// POW CALCULATORS
			'pow' => array(
					
					
					// BTC
					'btc' => array(
									'name' => 'Bitcoin', // Coin name
									'symbol' => 'btc', // Coin symbol (lowercase)
									'exchange_name' => 'binance', // Exchange name (for price data, lowercase)
									'exchange_mrkt' => 'BTCUSDT', // Market pair name (for price data)
									'measure_semantic' => 'difficulty',  // (difficulty, nethashrate, etc)
									'block_reward' => 6.25, // Mining block reward (OPTIONAL, can be made dynamic with code, like below)
									// EVERYTHING BELOW #MUST BE DYNAMICALLY# UPDATED IN:
									// app-lib/php/inline/calculators/mining/pow/dynamic-settings.php (so we can run a cached config)
									'mining_time_formula' => 'PLACEHOLDER', // Mining time formula calculation (REQUIRED)
									'height' => 'PLACEHOLDER', // Block height (OPTIONAL)
									'difficulty' => 'PLACEHOLDER', // Mining network difficulty (OPTIONAL)
									'other_network_data' => '', // Leave blank to skip (OPTIONAL)
									),
					
					
			), // POW END
					
					
			// POS CALCULATORS (#NOT FUNCTIONAL YET#)
			'pos' => array(
			
			// CALCULATORS HERE
			
			), // POS END
					
			
); // MINING CALCULATORS END
			


// RSS news feeds available on the News page
$ct_conf['power']['news_feed'] = array(
    
    
    					/////////////////////////////////////////////////////
    					// STANDARD RSS #AND# ATOM FORMAT ARE SUPPORTED
    					/////////////////////////////////////////////////////
        
        
        				array(
            			"title" => "Blog - Aptos (Layer 1 High-Speed Smart Contract Network)",
            			"url" => "https://medium.com/feed/aptoslabs"
        						),

        
        				array(
            			"title" => "Blog - BitcoinCore.org",
            			"url" => "https://bitcoincore.org/en/rss.xml"
        						),
        
        
        				array(
            			"title" => "Blog - BitDAO (decentralized hedge fund on Ethereum)",
            			"url" => "https://medium.com/feed/bitdao"
        						),
        
        
        				array(
            			"title" => "Blog - Bittrex",
            			"url" => "https://bittrex.com/discover/category/blog/feed"
        						),
        
        
        				array(
            			"title" => "Blog - Bitmex",
            			"url" => "https://blog.bitmex.com/feed/?lang=en_us"
        						),
        
        
        				array(
            			"title" => "Blog - Coinbase",
            			"url" => "https://medium.com/feed/the-coinbase-blog"
        						),
        
        
        				array(
            			"title" => "Blog - Ethereum.org (community-driven on-chain smart contracts)",
            			"url" => "https://blog.ethereum.org/feed.xml"
        						),
        
        
        				array(
            			"title" => "Blog - Ethereum EIPs (Last Call Review)",
            			"url" => "https://eips.ethereum.org/last-call.xml"
        						),
        
        
        				array(
            			"title" => "Blog - Ethereum Solidity (smart contract programming language)",
            			"url" => "https://solidity.ethereum.org/feed.xml"
        						),
        
        
        				array(
            			"title" => "Blog - Grape Protocol (tokenized community access on Solana)",
            			"url" => "https://medium.com/feed/great-ape"
        						),
        
        
        				array(
            			"title" => "Blog - Helium Network (community-driven global LoRaWAN network)",
            			"url" => "https://blog.helium.com/feed"
        						),
    
    
        				array(
            			"title" => "Blog - Kraken",
            			"url" => "https://blog.kraken.com/feed/"
        						),
        
        
        				array(
            			"title" => "Blog - Marinade Finance (mSOL liquid staking on Solana)",
            			"url" => "https://medium.com/feed/marinade-finance"
        						),
        
        
        				array(
            			"title" => "Blog - Misten Labs (creators of the 'Sui' high-speed smart contract network)",
            			"url" => "https://medium.com/feed/mysten-labs"
        						),
    
    
        				array(
            			"title" => "Blog - OkCoin",
            			"url" => "https://blog.okcoin.com/feed/"
        						),
        
        
        				array(
            			"title" => "Blog - Portals (NFT-based metaverse on Solana)",
            			"url" => "https://medium.com/@portals_/feed"
        						),
        
        
        				array(
            			"title" => "Blog - Raydium (Solana-based on-chain order book AMM)",
            			"url" => "https://raydium.medium.com/feed"
        						),
        
        
        				array(
            			"title" => "Blog - RNDR Network (Blockchain-Distributed GPU Rendering)",
            			"url" => "https://medium.com/feed/render-token"
        						),
        
        
        				array(
            			"title" => "Blog - Solice (Virtual World / Metaverse on Solana)",
            			"url" => "https://medium.com/feed/@solice_io"
        						),
        
        
        				array(
            			"title" => "Blog - Star Atlas (NFT-based Space Shooter Metaverse on Solana)",
            			"url" => "https://medium.com/feed/star-atlas"
        						),
        
        
        				array(
            			"title" => "Blog - ZkSync (Ethereum Layer 2 Network)",
            			"url" => "https://medium.com/feed/matter-labs"
        						),
        
        
        				array(
            			"title" => "News - Bitcoin Magazine",
            			"url" => "https://bitcoinmagazine.com/feed"
        						),
        
        
        				array(
            			"title" => "News - CoinTelegraph",
            			"url" => "https://cointelegraph.com/feed"
        						),
        
        
        				array(
            			"title" => "News - Decrypt",
            			"url" => "https://decrypt.co/feed"
        						),
    
    
        				array(
            			"title" => "News - The Block",
            			"url" => "https://www.theblockcrypto.com/rss.xml"
        						),
    
    
        				array(
            			"title" => "Newsletter - Alpha Please",
            			"url" => "https://alphapls.substack.com/feed"
        						),
    					
    					
        				array(
            			"title" => "Newsletter - Bitcoin Optech",
            			"url" => "https://bitcoinops.org/feed.xml"
        						),
    
    
        				array(
            			"title" => "Newsletter - Lightning Labs (Bitcoin Layer 2 Network)",
            			"url" => "https://lightninglabs.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - Our Network",
            			"url" => "https://ournetwork.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - Page One",
            			"url" => "https://page1.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - The Daily Degen",
            			"url" => "https://thedailydegen.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - Week In Ethereum",
            			"url" => "https://weekinethereumnews.com/feed/"
        						),
        
        
        				array(
            			"title" => "Podcast - Bankless",
            			"url" => "http://podcast.banklesshq.com/rss"
        						),
        
        
        				array(
            			"title" => "Podcast - Citadel Dispatch",
            			"url" => "https://anchor.fm/s/45563e80/podcast/rss"
        						),
    					
    					
        				array(
            			"title" => "Podcast - Stephan Livera",
            			"url" => "https://anchor.fm/s/7d083a4/podcast/rss"
        						),

    					
        				array(
            			"title" => "Podcast - Tales From The Crypt",
            			"url" => "http://talesfromthecrypt.libsyn.com/rss"
        						),
    
    
        				array(
            			"title" => "Podcast - The Solana Podcast",
            			"url" => "https://feeds.simplecast.com/W1NI2v3Z"
        						),

    					
        				array(
            			"title" => "Podcast - The Scoop",
            			"url" => "http://feeds.megaphone.fm/theblock-thescoop"
        						),

    					
        				array(
            			"title" => "Podcast - Unchained",
            			"url" => "https://unchained.libsyn.com/unchained"
        						),
    
    
        				array(
            			"title" => "Podcast - What Bitcoin Did",
            			"url" => "https://www.whatbitcoindid.com/podcast?format=RSS"
        						),
    
    
        				array(
            			"title" => "Podcast - Zero Knowledge",
            			"url" => "https://feeds.fireside.fm/zeroknowledge/rss"
        						),
    
    
        				array(
            			"title" => "Reddit - Bitcoin (hot)",
            			"url" => "https://www.reddit.com/r/Bitcoin/hot/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - Ethereum (hot)",
            			"url" => "https://www.reddit.com/r/Ethereum/hot/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - EthFinance (hot)",
            			"url" => "https://www.reddit.com/r/EthFinance/hot/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - Helium Network (hot)",
            			"url" => "https://www.reddit.com/r/heliumnetwork/hot/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - Solana (hot)",
            			"url" => "https://www.reddit.com/r/solana/hot/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - ZKsync (hot)",
            			"url" => "https://www.reddit.com/r/zksync/hot/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "StackExchange - Bitcoin (hot)",
            			"url" => "https://bitcoin.stackexchange.com/feeds/hot"
        						),
    
    
        				array(
            			"title" => "StackExchange - Ethereum (hot)",
            			"url" => "https://ethereum.stackexchange.com/feeds/hot"
        						),
    
    
        				array(
            			"title" => "StackExchange - Solana (hot)",
            			"url" => "https://solana.stackexchange.com/feeds/hot"
        						),
    
    
        				array(
            			"title" => "Stocks - CNBC: US Top News and Analysis",
            			"url" => "https://search.cnbc.com/rs/search/combinedcms/view.xml?partnerId=wrss01&id=100003114"
        						),
    
    
        				array(
            			"title" => "Stocks - AlphaStreet",
            			"url" => "https://news.alphastreet.com/feed/"
        						),
    
    
        				array(
            			"title" => "Stocks - Grit Capital",
            			"url" => "https://gritcapital.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Stocks - Investing.com: News",
            			"url" => "https://www.investing.com/rss/news.rss"
        						),
    
    
        				array(
            			"title" => "Youtube - Anthony Pompliano",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCevXpeL8cNyAnww-NqJ4m2w"
        						),
    
    
        				array(
            			"title" => "Youtube - BTC Sessions",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UChzLnWVsl3puKQwc5PoO6Zg"
        						),
    
    
        				array(
            			"title" => "Youtube - CryptoWendyO",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCla2jS8BrfLJj7kbKyy5_ew"
        						),
    
    
        				array(
            			"title" => "Youtube - DataDash",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCCatR7nWbYrkVXdxXb4cGXw"
        						),
    
    
        				array(
            			"title" => "Youtube - Epicenter Podcast",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCh-0T48JrvvmKDX41aWB_Vg"
        						),
    
    
        				array(
            			"title" => "Youtube - Ethereum Foundation",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCNOfzGXD_C9YMYmnefmPH0g"
        						),
    
    
        				array(
            			"title" => "Youtube - Grape Network",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UC58byjI3u8KaehHumP3AXrQ"
        						),
    
    
        				array(
            			"title" => "Youtube - Helium Network",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCEdh5moyCkiIrfdkZOnG5ZQ"
        						),
    
    
        				array(
            			"title" => "Youtube - Kripto Emre (turkish)",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UC87A7vsRlyZ68gtu-z1Q3ow"
        						),
    
    
        				array(
            			"title" => "Youtube - Kripto Sözlük (turkish)",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UC5rV0QEGbv0Y-umDwshs_HA"
        						),
    
    
        				array(
            			"title" => "Youtube - Solana Labs",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UC9AdQPUe4BdVJ8M9X7wxHUA"
        						),
    
    
        				array(
            			"title" => "Youtube - The Daily Gwei",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCvCp6vKY5jDr87htKH6hgDA"
        						),
        
        
    				);
				

////////////////////////////////////////
// !END! POWER USER CONFIGURATION
////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
// !START! DEVELOPER-ONLY CONFIGURATION, !CHANGE WITH #EXTREME# CARE, OR YOU CAN BREAK THE APP!
/////////////////////////////////////////////////////////////////////////////


// Enable / disable PHP error reporting (to error logs on the web server)
// https://www.php.net/manual/en/function.error-reporting.php
$ct_conf['dev']['php_error_reporting'] = 0; // 0 == off / -1 == on


// $ct_conf['dev']['debug_mode'] enabled runs unit tests during ui runtimes (during webpage load),
// errors detected are error-logged and printed as alerts in header alert bell area
// It also logs ui / cron runtime telemetry to /cache/logs/debug.log, AND /cache/logs/debug/
////////////////////////////////////////////////////////////////////////////////////////////
////
// ### GENERAL ###
////
// 'off' (disables), 
// 'all' (all debugging), 
////
// ### TELEMETRY ###
////
// 'all_telemetry' (ALL in-app telemetry), 
// 'light_chart_telemetry' (light chart caching),
// 'memory_usage_telemetry' (PHP system memory usage),
// 'ext_data_live_telemetry' (external API requests from server),
// 'ext_data_cache_telemetry' (external API requests from cache),
// 'smtp_telemetry' (smtp server responses to: /cache/logs/smtp_debug.log),
// 'api_comms_telemetry' (API comms responses to: /cache/logs/debug/external_data/last-response-[service].log),
////
// ### CHECKS ###
////
// 'markets' (asset market checks),
// 'texts' (mobile texting gateway checks), 
// 'alerts_charts' (price chart / price alert checks),
////
// ### SUMMARIES ###
////
// 'stats' (hardware / software / runtime summary),
// 'markets_conf' (outputs a markets configuration summary),
////
////////////////////////////////////////////////////////////////////////////////////////////
// UNIT TESTS ('CHECKS' SECTION) WILL ONLY RUN DURING WEB PAGE INTERFACE RUNTIMES
// PHP MAX EXECUTION TIME *SHOULD* AUTO-SET TO 1320 SECONDS (22 MINUTES) IN *ANY* DEBUG MODE (EXCEPT 'off')
// IF YOU GET AN ERROR 500, TRY RUNNING ONE DEBUG MODE AT A TIME, TO AVOID GOING OVER THE PHP EXECUTION TIME LIMIT
// DON'T LEAVE DEBUGGING ENABLED AFTER USING IT, THE /cache/logs/debug.log AND /cache/logs/debug/
// LOG FILES !CAN GROW VERY QUICKLY IN SIZE! EVEN AFTER JUST A FEW RUNTIMES!
$ct_conf['dev']['debug_mode'] = 'off'; 


// Level of detail / verbosity in log files. 'normal' logs minimal details (basic information), 
// 'verbose' logs maximum details (additional information IF AVAILABLE, for heavy debugging / tracing / etc)
// IF DEBUGGING IS ENABLED ABOVE, LOGS ARE AUTOMATICALLY VERBOSE #WITHOUT THE NEED TO ADJUST THIS SETTING#
$ct_conf['dev']['log_verb'] = 'normal'; // 'normal' / 'verbose'


// Maximum number of BATCHED coingecko marketcap data results to fetch, per API call (during multiple / paginated calls) 
// (coingecko #ABSOLUTELY HATES# DATA CENTER IPS [DEDICATED / VPS SERVERS], BUT GOES EASY ON RESIDENTIAL IPS)
$ct_conf['dev']['coingecko_api_batched_max'] = 100; // (default = 100), ADJUST WITH CARE!!!


// Maximum number of BATCHED news feed fetches / re-caches per ajax OR cron runtime 
// (#TO HELP PREVENT RUNTIME CRASHES# ON LOW POWER DEVICES OR HIGH TRAFFIC INSTALLS, USE A LOW NUMBER OF 20 OR LESS)
$ct_conf['dev']['news_feed_batched_max'] = 25; // (default = 25), ADJUST WITH CARE!!!
////
// Minutes to cache RSS feeds for News page
// Randomly cache each RSS feed between the minimum and maximum MINUTES set here (so they don't refresh all at once, for faster runtimes)
// THE WIDER THE GAP BETWEEN THE NUMBERS, MORE SPLIT UP / FASTER THE FEEDS WILL LOAD IN THE INTERFACE #CONSISTANTLY#
$ct_conf['dev']['news_feed_cache_min_max'] = '60,120'; // 'min,max' (default = '60,120'), ADJUST WITH CARE!!!
////
// Maximum number of news feeds allowed to be pre-cached during background tasks (to avoid overloading low power devices)
$ct_conf['dev']['news_feed_precache_hard_limit'] = 45; // (default = 45), ADJUST WITH CARE!!!


// Randomly rebuild the 'ALL' chart between the minimum and maximum HOURS set here  (so they don't refresh all at once, for faster runtimes)
// LARGER AVERAGE TIME SPREAD IS EASIER ON LOW POWER DEVICES (TO ONLY UPDATE A FEW AT A TIME), FOR A MORE CONSISTANT CRON JOB RUNTIME SPEED!!
$ct_conf['dev']['all_chart_rebuild_min_max'] = '4,8'; // 'min,max' (default = '4,8'), ADJUST WITH CARE!!!
////
// Maximum number of light chart NEW BUILDS allowed during background tasks, PER CPU CORE (only reset / new, NOT the 'all' chart REbuilds)
// (THIS IS MULTIPLIED BY THE NUMBER OF CPU CORES [if detected], avoids overloading low power devices / still builds fast on multi-core)
$ct_conf['dev']['light_chart_first_build_hard_limit'] = 25; // (default = 25), ADJUST WITH CARE!!!


// If you want to override the default CURL user agent string (sent with API requests, etc)
// Adding a string here automatically enables that as the custom curl user agent
// LEAVING BLANK '' USES THE DEFAULT CURL USER AGENT LOGIC BUILT-IN TO THIS APP (WHICH INCLUDES ONLY BASIC SYSTEM CONFIGURATION STATS)
$ct_conf['dev']['override_curl_user_agent'] = ''; 


// Default charset used
$ct_conf['dev']['charset_default'] = 'UTF-8'; 
////
// Unicode charset used (if needed)
// UCS-2 is outdated as it only covers 65536 characters of Unicode
// UTF-16BE / UTF-16LE / UTF-16 / UCS-2BE can represent ALL Unicode characters
$ct_conf['dev']['charset_unicode'] = 'UTF-16'; 
			
									
// !!!!! BE #VERY CAREFUL# LOWERING MAXIMUM EXECUTION TIMES BELOW, #OR YOU MAY CRASH THE RUNNING PROCESSES EARLY, 
// OR CAUSE MEMORY LEAKS THAT ALSO CRASH YOUR !ENTIRE SYSTEM!#
// (ALL maximum execution times are automatically 900 seconds [15 minutes] IN DEBUG MODE)
////
// Maximum execution time for interface runtime in seconds (how long it's allowed to run before automatically killing the process)
$ct_conf['dev']['ui_max_exec_time'] = 250; // (default = 250)
////
// Maximum execution time for ajax runtime in seconds (how long it's allowed to run before automatically killing the process)
$ct_conf['dev']['ajax_max_exec_time'] = 250; // (default = 250)
////
// Maximum execution time for cron job runtime in seconds (how long it's allowed to run before automatically killing the process)
$ct_conf['dev']['cron_max_exec_time'] = 1320; // (default = 1320)
////
// Maximum execution time for internal API runtime in seconds (how long it's allowed to run before automatically killing the process)
$ct_conf['dev']['int_api_max_exec_time'] = 120; // (default = 120)
////
// Maximum execution time for webhook runtime in seconds (how long it's allowed to run before automatically killing the process)
$ct_conf['dev']['webhook_max_exec_time'] = 120; // (default = 120)
     
     
// Servers with STRICT CONSECUTIVE CONNECT limits (we add 0.11 seconds to the wait between consecutive connections)
$ct_conf['dev']['strict_cosecutive_connect_servers'] = array(
                                      						'test654321.com',
                                      						);
     
     
// Servers which are known to block API access by location / jurasdiction
// (we alert end-users in error logs, when a corrisponding API server connection fails [one-time notice per-runtime])
$ct_conf['dev']['location_blocked_servers'] = array(
                                      				'binance.com',
                                      				);
     
     
// Servers requiring TRACKED THROTTLE-LIMITING, due to limited-allowed minute / hour / daily requests
// (are processed by ct_cache->api_throttling(), to avoid using up daily request limits)
$ct_conf['dev']['tracked_throttle_limited_servers'] = array(
                                      						'alphavantage.co',
                                      						);
    
     
// RSS feed services that are a bit funky with allowed user agents, so we need to let them know this is a real feed parser (not just a spammy bot)
// (user agent string is EXPLICITLY SET AS A CUSTOM FEED PARSER)
$ct_conf['dev']['strict_news_feed_servers'] = array(
                                                  'medium.com',
                                                  'reddit.com',
                                                  'whatbitcoindid.com',
                                                  'simplecast.com',
                                                  );
							

// TLD-only (Top Level Domain only, NO SUBDOMAINS) for each API service that UN-EFFICIENTLY requires multiple calls (for each market / data set)
// Used to throttle these market calls a tiny bit (0.15 seconds), so we don't get easily blocked / throttled by external APIs etc
// (ANY EXCHANGES LISTED HERE ARE !NOT! RECOMMENDED TO BE USED AS THE PRIMARY CURRENCY MARKET IN THIS APP,
// AS ON OCCASION THEY CAN BE !UNRELIABLE! IF HIT WITH TOO MANY SEPARATE API CALLS FOR MULTIPLE COINS / ASSETS)
// !MUST BE LOWERCASE!
// #DON'T ADD ANY WEIRD TLD HERE LIKE 'xxxxx.co.il'#, AS DETECTING TLD DOMAINS WITH MORE THAN ONE PERIOD IN THEM ISN'T SUPPORTED
// WE DON'T WANT THE REQUIRED EXTRA LOGIC TO PARSE THESE DOUBLE-PERIOD TLDs BOGGING DOWN / CLUTTERING APP CODE, FOR JUST ONE TINY FEATURE
$ct_conf['dev']['limited_apis'] = array(
                						'alphavantage.co',
                						'bitforex.com',
                						'bitflyer.com',
                						'bitmex.com',
                						'bitso.com',
                						'bitstamp.net',
                						'blockchain.info',
                						'btcmarkets.net',
                						'coinbase.com',
                						// (coingecko #ABSOLUTELY HATES# DATA CENTER IPS [DEDICATED / VPS SERVERS], BUT GOES EASY ON RESIDENTIAL IPS)
                						'coingecko.com',
                						'etherscan.io',
                						'gemini.com',
                						'jup.ag',
							            );
				

////////////////////////////////////////
// !END! DEVELOPER-ONLY CONFIGURATION
////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PRIMARY CONFIGURATIONS -END- //////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// EMAIL-TO-MOBILE-TEXT CONFIGURATION -START- ////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


/*

Below are the mobile networks supported by Open Crypto Tracker's email-to-mobile-text functionality. 

Using your corresponding "Network Name Key" (case-sensitive) listed below, 
add that EXACT name in this config file further above within the $ct_conf['comms']['to_mobile_text'] setting as the text network name variable,
to enable email-to-text alerts to your network's mobile phone number.

PLEASE REPORT ANY MISSING / INCORRECT / NON-FUNCTIONAL GATEWAYS HERE, AND I WILL FIX THEM:
https://github.com/taoteh1221/Open_Crypto_Tracker/issues
(or you can add / update it yourself right in this configuration, if you know the correct gateway domain name)

*/


// All supported mobile network email-to-text gateway (domain name) configurations
// Network name keys MUST BE LOWERCASE (for reliability / consistency, 
// as these name keys are always called from (forced) lowercase name key lookups)

// DUPLICATE NETWORK NAME KEYS --WILL CANCEL EACH OTHER OUT--, !!USE A UNIQUE NAME FOR EACH KEY!!

// WHEN ADDING YOUR OWN GATEWAYS, ONLY INCLUDE THE DOMAIN (THIS APP WILL AUTOMATICALLY FORMAT TO your_phone_number@your_gateway)


$ct_conf['mob_net_txt_gateways'] = array(
                        
                        
                        // [EXAMPLE]
                        // 'network_name_key' => 'network_gateway1.com',
                        // 'unique_network_name_key' => 'network_gateway2.com',
                        
                        
                        // [NO NETWORK] (when using textbelt / textlocal API instead)
                        'skip_network_name' => NULL,
                        
                        
                        // [INTERNATIONAL]
                        'esendex' => 'echoemail.net',
                        'global_star' => 'msg.globalstarusa.com',
                        
                        
                        // [MISCELLANEOUS COUNTRIES]
                        'movistar_ar' => 'sms.movistar.net.ar', // Argentina
                        'setar' => 'mas.aw',                    // Aruba
                        'mobiltel' => 'sms.mtel.net',           // Bulgaria
                        'china_mobile' => '139.com',            // China
                        'ice' => 'sms.ice.cr',                  // Costa Rica
                        'tmobile_hr' => 'sms.t-mobile.hr',      // Croatia
                        'digicel' => 'digitextdm.com',          // Dominica
                        'tellus_talk' => 'esms.nu',             // Europe
                        'guyana_tt' => 'sms.cellinkgy.com',     // Guyana
                        'csl' => 'mgw.mmsc1.hkcsl.com',         // Hong Kong
                        'vodafone_it' => 'sms.vodafone.it',     // Italy
                        'tele2_lv' => 'sms.tele2.lv',           // Latvia
                        'emtel' => 'emtelworld.net',            // Mauritius
                        'telcel' => 'itelcel.com',              // Mexico
                        'tmobile_nl' => 'gin.nl',               // Netherlands
                        'mas_movil' => 'cwmovil.com',           // Panama
                        'claro_pr' => 'vtexto.com',             // Puerto Rico
                        'beeline' => 'sms.beemail.ru',          // Russia
                        'm1' => 'm1.com.sg',                    // Singapore
                        'mobitel' => 'sms.mobitel.lk',          // Sri Lanka
                        'tele2_se' => 'sms.tele2.se',           // Sweden
                        'sunrise_ch' => 'gsm.sunrise.ch',       // Switzerland
                        'movistar_uy' => 'sms.movistar.com.uy', // Uruguay
                        
                        
                        // [AUSTRALIA]
                        'sms_broadcast' => 'send.smsbroadcast.com.au',
                        'sms_central' => 'sms.smscentral.com.au',
                        'sms_pup' => 'smspup.com',
                        'tmobile_au' => 'optusmobile.com.au',
                        'telstra' => 'sms.tim.telstra.com',
                        'ut_box' => 'sms.utbox.net',
                        
                        
                        // [AUSTRIA]
                        'firmen_sms' => 'subdomain.firmensms.at',
                        'tmobile_at' => 'sms.t-mobile.at',
                        
                        
                        // [CANADA]
                        'bell' => 'txt.bell.ca',
                        'bell_mts' => 'text.mts.net',
                        'koodo' => 'msg.telus.com',
                        'pc_telecom' => 'mobiletxt.ca',
                        'rogers_ca' => 'pcs.rogers.com',
                        'sasktel' => 'pcs.sasktelmobility.com',
                        'telus' => 'mms.telusmobility.com',
                        'virgin_ca' => 'vmobile.ca',
                        'wind' => 'txt.windmobile.ca',
                        
                        
                        // [COLUMBIA]
                        'claro_co' => 'iclaro.com.co',
                        'movistar_co' => 'movistar.com.co',
                        
                        
                        // [FRANCE]
                        'bouygues' => 'mms.bouyguestelecom.fr',
                        'orange_fr' => 'orange.fr',
                        'sfr' => 'sfr.fr',
                        
                        
                        // [GERMANY]
                        'o2' => 'o2online.de',
                        'tmobile_de' => 't-mobile-sms.de',
                        'vodafone_de' => 'vodafone-sms.de',
                        
                        
                        // [ICELAND]
                        'vodafone_is' => 'sms.is',
                        'box_is' => 'box.is',
                        
                        
                        // [INDIA]
                        'aircel' => 'aircel.co.in',
                        'airtel' => 'airtelmail.com',
                        'airtel_kerala' => 'airtelkerala.com',
                        
                        
                        // [NEW ZEALAND]
                        'telecom' => 'etxt.co.nz',
                        'vodafone_nz' => 'mtxt.co.nz',
                        
                        
                        // [NORWAY]
                        'sendega' => 'sendega.com',
                        'teletopia' => 'sms.teletopiasms.no',
                        
                        
                        // [POLAND]
                        'orange_pl' => 'orange.pl',
                        'plus' => 'text.plusgsm.pl',
                        'polkomtel' => 'text.plusgsm.pl',
                        
                        
                        // [SOUTH AFRICA]
                        'mtn' => 'sms.co.za',
                        'vodacom' => 'voda.co.za',
                        
                        
                        // [SPAIN]
                        'esendex' => 'esendex.net',
                        'movistar_es' => 'movistar.net',
                        'vodafone_es' => 'vodafone.es',
                        
                        
                        // [UNITED KINGDOM]
                        'media_burst' => 'sms.mediaburst.co.uk',
                        'txt_local' => 'txtlocal.co.uk',
                        'virgin_uk' => 'vxtras.com',
                        'vodafone_uk' => 'vodafone.net',
                        
                        
                        // [UNITED STATES]
                        'att' => 'txt.att.net',
                        'bluegrass' => 'mms.myblueworks.com',
                        'boost' => 'myboostmobile.com',
                        'cellcom' => 'cellcom.quiktxt.com',
                        'chariton_valley' => 'sms.cvalley.net',
                        'cricket' => 'mms.cricketwireless.net',
                        'cspire' => 'cspire1.com',
                        'gci' => 'mobile.gci.net',
                        'googlefi' => 'msg.fi.google.com',
                        'nextech' => 'sms.ntwls.net',
                        'pioneer' => 'zsend.com',
                        'rogers_us' => 'pcs.rogers.com',
                        'simple_mobile' => 'smtext.com',
                        'southern_linc' => 'page.southernlinc.com',
                        'south_central_comm' => 'rinasms.com',
                        'sprint' => 'messaging.sprintpcs.com',
                        'tmobile_us' => 'tmomail.net',
                        'telus' => 'mms.telusmobility.com',
                        'trac_fone' => 'mmst5.tracfone.com',
                        'union' => 'union-tel.com',
                        'us_cellular' => 'email.uscc.net',
                        'verizon' => 'vtext.com',
                        'viaero' => 'mmsviaero.com',
                        'virgin_us' => 'vmobl.com',
                        'west_central' => 'sms.wcc.net',
                        'xit' => 'sms.xit.net',
                        

); // mob_net_txt_gateways END



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// EMAIL-TO-MOBILE-TEXT CONFIGURATION -END- //////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PORTFOLIO ASSETS CONFIGURATION -START- ////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG

// SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// See TROUBLESHOOTING.txt for tips / troubleshooting FAQs.

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC !!!!WILL BREAK THE APP!!!!, BE CAREFUL EDITING THIS CONFIG FILE!


$ct_conf['assets'] = array(

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MISCASSETS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    // (!!!!DO NOT DELETE, MISCASSETS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'MISCASSETS' => array(), 
                    // Asset END

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ETHNFTS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    // (!!!!DO NOT DELETE, ETHNFTS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'ETHNFTS' => array(), 
                    // Asset END

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SOLNFTS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    // (!!!!DO NOT DELETE, SOLNFTS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'SOLNFTS' => array(), 
                    // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // BTC 
                    // (!!!!*BTC #MUST# BE THE VERY FIRST* IN THIS CRYPTO ASSET MARKETS LIST!!!!)
                    // (!!!!DO NOT DELETE, BTC IS *REQUIRED* TO RUN THIS APP!!!!)
                    'BTC' => array(
                        
                        'name' => 'Bitcoin',
                        'mcap_slug' => 'bitcoin',
                        'pair' => array(
                        
                        
                        			'aed' => array(
                                          'localbitcoins' => 'AED',
                                                    ),

                        
                        			'ars' => array(
                                          'localbitcoins' => 'ARS',
                                                    ),

                        
                        		    'aud' => array(
                                    	  'kraken' => 'XBTAUD',
                                    	  'btcmarkets' => 'BTC/AUD',
                                          'localbitcoins' => 'AUD',
                                          'coinspot' => 'btc',
                                                    ),

                                                    
                                    'bam' => array(
                                          'localbitcoins' => 'BAM',
                                                    ),

                                                    
                                    'bdt' => array(
                                          'localbitcoins' => 'BDT',
                                                    ),

                                                    
                                    'bob' => array(
                                          'localbitcoins' => 'BOB',
                                                    ),

                                                    
                                    'brl' => array(
                                          'localbitcoins' => 'BRL',
                                                    ),

                                                    
                                    'bwp' => array(
                                          'localbitcoins' => 'BWP',
                                                    ),

                                                    
                                    'byn' => array(
                                          'localbitcoins' => 'BYN',
                                                    ),

                                                    
                                    'cad' => array(
                                          'kraken' => 'XXBTZCAD',
                                          'localbitcoins' => 'CAD',
                                                    ),

                                                    
                                    'chf' => array(
                                          'kraken' => 'XBTCHF',
                                          'localbitcoins' => 'CHF',
                                                    ),

                                                    
                                    'clp' => array(
                                          'localbitcoins' => 'CLP',
                                                    ),

                                                    
                                    'cny' => array(
                                          'localbitcoins' => 'CNY',
                                                    ),

                                                    
                                    'cop' => array(
                                          'localbitcoins' => 'COP',
                                                    ),

                                                    
                                    'crc' => array(
                                          'localbitcoins' => 'CRC',
                                                    ),

                                                    
                                    'czk' => array(
                                          'localbitcoins' => 'CZK',
                                                    ),

                                                    
                                    'dai' => array(
                                        	'binance' => 'BTCDAI',
                                        	'hitbtc' => 'BTCDAI',
                                    	 	'kraken' => 'XBTDAI',
                                        	'okex' => 'BTC-DAI',
                                        	'kucoin' => 'BTC-DAI',
                                                    ),

                                                    
                                    'dkk' => array(
                                          'localbitcoins' => 'DKK',
                                                    ),

                                                    
                                    'dop' => array(
                                          'localbitcoins' => 'DOP',
                                                    ),

                                                    
                                    'egp' => array(
                                          'localbitcoins' => 'EGP',
                                                    ),

                                                    
                                    'eth' => array(
                                          'localbitcoins' => 'ETH',
                                          'loopring_amm' => 'AMM-WBTC-ETH',
                                                    ),

                                                    
                                    'eur' => array(
                                          'coinbase' => 'BTC-EUR',
                                          'kraken' => 'XXBTZEUR',
                                          'bitstamp' => 'btceur',
                                          'bittrex_global' => 'BTC-EUR',
                                          'bitpanda' => 'BTC_EUR',
                                          'bitflyer' => 'BTC_EUR',
                                          'cex' => 'BTC:EUR',
                                          'localbitcoins' => 'EUR',
                                          'luno' => 'XBTEUR',
                                                    ),

                                                    
                                    'gbp' => array(
                                          'coinbase' => 'BTC-GBP',
                                          'kraken' => 'XXBTZGBP',
                                          'bitfinex' => 'tBTCGBP',
                                          'cex' => 'BTC:GBP',
                                          'localbitcoins' => 'GBP',
                                                    ),

                                                    
                                    'gel' => array(
                                          'localbitcoins' => 'GEL',
                                                    ),

                                                    
                                    'ghs' => array(
                                          'localbitcoins' => 'GHS',
                                                    ),

                                                    
                                    'gtq' => array(
                                          'localbitcoins' => 'GTQ',
                                                    ),

                                                    
                                    'hkd' => array(
                                          'liquid' => 'BTCHKD',
                                          'localbitcoins' => 'HKD',
                                                    ),

                                                    
                                    'huf' => array(
                                          'localbitcoins' => 'HUF',
                                                    ),

                                                    
                                    'idr' => array(
                                          'localbitcoins' => 'IDR',
                                                    ),

                                                    
                                    'ils' => array(
                                          'localbitcoins' => 'ILS',
                                                    ),

                                                    
                                    'inr' => array(
                                          'localbitcoins' => 'INR',
                                          'bitbns' => 'BTC',
                                          'buyucoin' => 'INR-BTC',
                                          'wazirx' => 'btcinr',
                                          'coindcx' => 'BTCINR',
                                          'unocoin' => 'BTC',
                                                    ),

                                                    
                                    'irr' => array(
                                          'localbitcoins' => 'IRR',
                                                    ),

                                                    
                                    'jmd' => array(
                                          'localbitcoins' => 'JMD',
                                                    ),

                                                    
                                    'jod' => array(
                                          'localbitcoins' => 'JOD',
                                                    ),

                                                    
                                    'jpy' => array(
                                          'kraken' => 'XXBTZJPY',
                                          'bitflyer' => 'BTC_JPY',
                                          'localbitcoins' => 'JPY',
                                                    ),

                                                    
                                    'kes' => array(
                                          'localbitcoins' => 'KES',
                                                    ),

                                                    
                                    'krw' => array(
                                          'localbitcoins' => 'KRW',
                                          'upbit' => 'KRW-BTC',
                                          'korbit' => 'btc_krw',
                                                    ),

                                                    
                                    'kwd' => array(
                                          'localbitcoins' => 'KWD',
                                                    ),

                                                    
                                    'kzt' => array(
                                          'localbitcoins' => 'KZT',
                                                    ),

                                                    
                                    'lkr' => array(
                                          'localbitcoins' => 'LKR',
                                                    ),

                                                    
                                    'mad' => array(
                                          'localbitcoins' => 'MAD',
                                                    ),

                                                    
                                    'mur' => array(
                                          'localbitcoins' => 'MUR',
                                                    ),

                                                    
                                    'mwk' => array(
                                          'localbitcoins' => 'MWK',
                                                    ),

                                                    
                                    'mxn' => array(
                                          'bitso' => 'btc_mxn',
                                          'localbitcoins' => 'MXN',
                                                    ),

                                                    
                                    'myr' => array(
                                          'localbitcoins' => 'MYR',
                                                    ),

                                                    
                                    'ngn' => array(
                                          'localbitcoins' => 'NGN',
                                                    ),

                                                    
                                    'nis' => array(
                                          'bit2c' => 'BtcNis',
                                                    ),

                                                    
                                    'nok' => array(
                                          'localbitcoins' => 'NOK',
                                                    ),

                                                    
                                    'nzd' => array(
                                          'localbitcoins' => 'NZD',
                                                    ),

                                                    
                                    'pab' => array(
                                          'localbitcoins' => 'PAB',
                                                    ),

                                                    
                                    'pen' => array(
                                          'localbitcoins' => 'PEN',
                                                    ),

                                                    
                                    'php' => array(
                                          'localbitcoins' => 'PHP',
                                                    ),

                                                    
                                    'pkr' => array(
                                          'localbitcoins' => 'PKR',
                                                    ),

                                                    
                                    'pln' => array(
                                          'localbitcoins' => 'PLN',
                                                    ),

                                                    
                                    'pyg' => array(
                                          'localbitcoins' => 'PYG',
                                                    ),

                                                    
                                    'qar' => array(
                                          'localbitcoins' => 'QAR',
                                                    ),

                                                    
                                    'ron' => array(
                                          'localbitcoins' => 'RON',
                                                    ),

                                                    
                                    'rsd' => array(
                                          'localbitcoins' => 'RSD',
                                                    ),

                                                    
                                    'rub' => array(
                                          'localbitcoins' => 'RUB',
                                                    ),

                                                    
                                    'rwf' => array(
                                          'localbitcoins' => 'RWF',
                                                    ),

                                                    
                                    'sar' => array(
                                          'localbitcoins' => 'SAR',
                                                    ),

                                                    
                                    'sek' => array(
                                          'localbitcoins' => 'SEK',
                                                    ),

                                                    
                                    'sgd' => array(
                                          'localbitcoins' => 'SGD',
                                          'coingecko_sgd' => 'bitcoin',
                                                    ),

                                                    
                                    'thb' => array(
                                          'localbitcoins' => 'THB',
                                                    ),

                                                    
                                    'try' => array(
                                          'btcturk' => 'BTCTRY',
                                          'localbitcoins' => 'TRY',
                                                    ),

                                                    
                                    'twd' => array(
                                          'localbitcoins' => 'TWD',
                                          'coingecko_twd' => 'bitcoin',
                                                    ),

                                                    
                                    'tzs' => array(
                                          'localbitcoins' => 'TZS',
                                                    ),

                                                    
                                    'uah' => array(
                                          'localbitcoins' => 'UAH',
                                                    ),

                                                    
                                    'ugx' => array(
                                          'localbitcoins' => 'UGX',
                                                    ),

                                                    
                                    'usd' => array(
                                          'coingecko_usd' => 'bitcoin',
                                          'coinbase' => 'BTC-USD',
                                          'binance_us' => 'BTCUSD',
                                          'bitstamp' => 'btcusd',
                                          'kraken' => 'XXBTZUSD',
                                          'gemini' => 'btcusd',
                                          'bitmex' => 'XBTUSD',
                                          'bitmex_u20' => 'XBTU20',
                                          'bitmex_z20' => 'XBTZ20',
                                          'bittrex' => 'BTC-USD',
                                          'localbitcoins' => 'USD',
                                          'bitfinex' => 'tBTCUSD',
                                          'bitflyer' => 'BTC_USD',
                                          'hitbtc' => 'BTCUSD',
                                          'okcoin' => 'BTC-USD',
                                          'cex' => 'BTC:USD',
                                                    ),

                                                    
                                    'usdc' => array(
                                          'kraken' => 'XBTUSDC',
                                          'southxchange' => 'BTC/USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                          'binance' => 'BTCUSDT',
                                    	  'kraken' => 'XBTUSDT',
                                          'bittrex' => 'BTC-USDT',
                                          'btcturk' => 'BTCUSDT',
                                          'huobi' => 'btcusdt',
                                          'okex' => 'BTC-USDT',
                                          'bitbns' => 'BTCUSDT',
                                          'wazirx' => 'btcusdt',
                                          'southxchange' => 'BTC/USDT',
                                                    ),

                                                    
                                    'uyu' => array(
                                          'localbitcoins' => 'UYU',
                                                    ),

                                                    
                                    'ves' => array(
                                          'localbitcoins' => 'VES',
                                                    ),

                                                    
                                    'vnd' => array(
                                          'localbitcoins' => 'VND',
                                                    ),

                                                    
                                    'xaf' => array(
                                          'localbitcoins' => 'XAF',
                                                    ),

                                                    
                                    'xof' => array(
                                          'localbitcoins' => 'XOF',
                                                    ),

                                                    
                                    'zar' => array(
                                          'localbitcoins' => 'ZAR',
                                          'luno' => 'XBTZAR',
                                                    ),

                                                    
                                    'zmw' => array(
                                          'localbitcoins' => 'ZMW',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END (!!!!*BTC MUST BE THE VERY FIRST* IN THIS ASSET LIST, DO NOT DELETE, BTC IS *REQUIRED* TO RUN THIS APP!!!!)
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ETH
                    'ETH' => array(
                        
                        'name' => 'Ethereum',
                        'mcap_slug' => 'ethereum',
                        'pair' => array(

                        
                        			'aud' => array(
                                    	  'kraken' => 'ETHAUD',
                                    	  'btcmarkets' => 'ETH/AUD',
                                          'coinspot' => 'eth',
                                                    ),

                                                    
                                    'btc' => array(
                                          'binance' => 'ETHBTC',
                                          'coinbase' => 'ETH-BTC',
                                          'binance_us' => 'ETHBTC',
                                          'bittrex' => 'ETH-BTC',
                                          'bitstamp' => 'ethbtc',
                                          'gemini' => 'ethbtc',
                                          'kraken' => 'XETHXXBT',
                                          'bitfinex' => 'tETHBTC',
                                          'bitmex_u20' => 'ETHU20',
                                          'hitbtc' => 'ETHBTC',
                                          'upbit' => 'BTC-ETH',
                                          'bitflyer' => 'ETH_BTC',
                                          'kucoin' => 'ETH-BTC',
                                          'okex' => 'ETH-BTC',
                                          'poloniex' => 'BTC_ETH',
                                          'bitso' => 'eth_btc',
                                          'zebpay' => 'ETH-BTC',
                                          'luno' => 'ETHXBT',
                                          'wazirx' => 'ethbtc',
                                                    ),

                                                    
                                    'cad' => array(
                                          'kraken' => 'XETHZCAD',
                                                    ),

                                                    
                                    'chf' => array(
                                          'kraken' => 'ETHCHF',
                                                    ),

                                                    
                                    'dai' => array(
                                          'binance' => 'ETHDAI',
                                          'coinbase' => 'ETH-DAI',
                                          'kraken' => 'ETHDAI',
                                          'kucoin' => 'ETH-DAI',
                                          'hitbtc' => 'ETHDAI',
                                          'loopring_amm' => 'AMM-ETH-DAI',
                                                    ),

                                                    
                                    'eur' => array(
                                          'coinbase' => 'ETH-EUR',
                                          'kraken' => 'XETHZEUR',
                                          'bitstamp' => 'etheur',
                                          'bittrex_global' => 'ETH-EUR',
                                          'cex' => 'ETH:EUR',
                                                    ),

                                                    
                                    'gbp' => array(
                                          'coinbase' => 'ETH-GBP',
                                          'kraken' => 'XETHZGBP',
                                          'cex' => 'BTC:GBP',
                                                    ),

                                                    
                                    'hkd' => array(
                                          'liquid' => 'ETHHKD',
                                                    ),

                                                    
                                    'inr' => array(
                                          'bitbns' => 'ETH',
                                          'buyucoin' => 'INR-ETH',
                                          'wazirx' => 'ethinr',
                                                    ),

                                                    
                                    'jpy' => array(
                                          'kraken' => 'XETHZJPY',
                                          'bitflyer' => 'ETH_JPY',
                                                    ),

                                                    
                                    'krw' => array(
                                          'upbit' => 'KRW-ETH',
                                          'korbit' => 'eth_krw',
                                                    ),

                                                    
                                    'mxn' => array(
                                          'bitso' => 'eth_mxn',
                                                    ),

                                                    
                                    'nis' => array(
                                          'bit2c' => 'EthNis',
                                                    ),

                                                    
                                    'sgd' => array(
                                          'coingecko_sgd' => 'ethereum',
                                                    ),

                                                    
                                    'try' => array(
                                          'btcturk' => 'ETHTRY',
                                                    ),

                                                    
                                    'usd' => array(
                                          'coingecko_usd' => 'ethereum',
                                          'coinbase' => 'ETH-USD',
                                          'kraken' => 'XETHZUSD',
                                          'bitstamp' => 'ethusd',
                                          'gemini' => 'ethusd',
                                          'bitfinex' => 'tETHUSD',
                                          'bitmex' => 'ETHUSD',
                                          'bitmex_u20' => 'ETHUSDU20',
                                          'bittrex' => 'ETH-USD',
                                          'okcoin' => 'ETH-USD',
                                          'cex' => 'ETH:USD',
                                                    ),

                                                    
                                    'usdt' => array(
                                          'binance' => 'ETHUSDT',
                                          'kraken' => 'ETHUSDT',
                                          'btcturk' => 'ETHUSDT',
                                          'huobi' => 'ethusdt',
                                          'binance_us' => 'ETHUSDT',
                                          'bittrex' => 'ETH-USDT',
                                          'hitbtc' => 'ETHUSD',
                                          'upbit' => 'USDT-ETH',
                                       	  'kucoin' => 'ETH-USDT',
                                          'okex' => 'ETH-USDT',
                                          'loopring_amm' => 'AMM-ETH-USDT',
                                          'poloniex' => 'USDT_ETH',
                                          'bitbns' => 'ETHUSDT',
                                          'wazirx' => 'ethusdt',
                                                    ),

                                                    
                                    'usdc' => array(
                                          'kraken' => 'ETHUSDC',
                                          'kucoin' => 'ETH-USDC',
                                          'loopring_amm' => 'AMM-ETH-USDC',
                                          'poloniex' => 'USDC_ETH',
                                                    ),

                                                    
                                    'zar' => array(
                                          'luno' => 'ETHZAR',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SOL
                    'SOL' => array(
                        
                        'name' => 'Solana',
                        'mcap_slug' => 'solana',
                        'pair' => array(

                                                    
                                    'aud' => array(
                                        'binance' => 'SOLAUD',
                                                    ),

                                                    
                                    'brl' => array(
                                        'binance' => 'SOLBRL',
                                                    ),

                        
                                    'btc' => array(
                                    	'coinbase' => 'SOL-BTC',
                                        'bittrex' => 'SOL-BTC',
                                        'binance' => 'SOLBTC',
                                        'huobi' => 'solbtc',
                                        'okex' => 'SOL-BTC',
                                    	'crypto.com' => 'SOL_BTC',
                                        'hitbtc' => 'SOLBTC',
                                        'hotbit' => 'SOL_BTC',
                                        'coinex' => 'SOLBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'okex' => 'SOL-ETH',
                                        'binance' => 'SOLETH',
                                        'huobi' => 'soleth',
                                        'hitbtc' => 'SOLETH',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'coinbase' => 'SOL-EUR',
                                         'binance' => 'SOLEUR',
                                    	 'kraken' => 'SOLEUR',
                                                    ),

                                                    
                                    'gbp' => array(
                                    	 'kraken' => 'SOLGBP',
                                                    ),

                                                    
                                    'rub' => array(
                                         'binance' => 'SOLRUB',
                                                    ),

                                                    
                                    'try' => array(
                                         'binance' => 'SOLTRY',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'coinbase' => 'SOL-USD',
                                         'bittrex' => 'SOL-USD',
                                    	 'kraken' => 'SOLUSD',
                                    	 'binance_us' => 'SOLUSD',
                                    	 'bitfinex' => 'tSOLUSD',
                                         'gateio' => 'SOL_USD',
                                         'hitbtc' => 'SOLUSD',
                                         'cex' => 'SOL:USD',
                                                    ),

                                                    
                                    'usdt' => array(
                                    	'coinbase' => 'SOL-USDT',
                                        'bittrex' => 'SOL-USDT',
                                        'binance' => 'SOLUSDT',
                                        'okex' => 'SOL-USDT',
                                        'huobi' => 'solusdt',
                                    	'binance_us' => 'SOLUSDT',
                                    	'crypto.com' => 'SOL_USDT',
                                        'kucoin' => 'SOL-USDT',
                                        'coinex' => 'SOLUSDT',
                                        'hotbit' => 'SOL_USDT',
                                        'gateio' => 'SOL_USDT',
                                        'bitmart' => 'SOL_USDT',
                                        'wazirx' => 'solusdt',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // COINSTOCK
                    'COINSTOCK' => array(
                        
                        'name' => 'Coinbase Global Inc',
                        'mcap_slug' => 'COIN:NASDAQ',
                        'pair' => array(

                        
                                    'usd' => array(
                                        'alphavantage_stock' => 'COIN',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // AMZNSTOCK
                    'AMZNSTOCK' => array(
                        
                        'name' => 'Amazon Inc',
                        'mcap_slug' => 'AMZN:NASDAQ',
                        'pair' => array(

                        
                                    'usd' => array(
                                        'alphavantage_stock' => 'AMZN',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // GOOGLSTOCK
                    'GOOGLSTOCK' => array(
                        
                        'name' => 'Alphabet Inc Class A',
                        'mcap_slug' => 'GOOGL:NASDAQ',
                        'pair' => array(

                        
                                    'usd' => array(
                                        'alphavantage_stock' => 'GOOGL',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // GPVSTOCK
                    'GPVSTOCK' => array(
                        
                        'name' => 'GreenPower Motor Company Inc',
                        'mcap_slug' => 'GPV:CVE',
                        'pair' => array(

                        
                                    'cad' => array(
                                        'alphavantage_stock' => 'GPV.TRV',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // DTGSTOCK
                    'DTGSTOCK' => array(
                        
                        'name' => 'Daimler Truck Holding AG',
                        'mcap_slug' => 'DTG:ETR',
                        'pair' => array(

                        
                                    'eur' => array(
                                        'alphavantage_stock' => 'DTG.DEX',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // APT
                    'APT' => array(
                        
                        'name' => 'Aptos',
                        'mcap_slug' => 'aptos',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'binance' => 'APTBTC',
                                        'gateio' => 'APT_BTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'gateio' => 'APT_ETH',
                                                    ),

                                                    
                                    'eur' => array(
                                         'binance' => 'APTEUR',
                                    	 'kraken' => 'APTEUR',
                                                    ),

                                                    
                                    'try' => array(
                                         'binance' => 'APTTRY',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'coinbase' => 'APT-USD',
                                    	 'kraken' => 'APTUSD',
                                    	 'bitfinex' => 'tAPTUSD',
                                                    ),

                                                    
                                    'usdc' => array(
                                        'okex' => 'APT-USDC',
                                        'huobi' => 'aptusdc',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'binance' => 'APTUSDT',
                                        'okex' => 'APT-USDT',
                                        'huobi' => 'aptusdt',
                                        'kucoin' => 'APT-USDT',
                                        'bybit' => 'APTUSDT',
                                        'gateio' => 'APT_USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // UNI
                    'UNI' => array(
                        
                        'name' => 'Uniswap',
                        'mcap_slug' => 'uniswap',
                        'pair' => array(
                                                    
                                                    
                                    'btc' => array(
                                        'binance' => 'UNIBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'loopring_amm' => 'AMM-UNI-ETH',
                                                    ),


                                    'inr' => array(
                                        'bitbns' => 'UNI',
                                        'wazirx' => 'uniinr',
                                        'zebpay' => 'UNI-INR',
                                                    ),

                                                    
                                    'usd' => array(
                                        'coinbase' => 'UNI-USD',
                                        'binance_us' => 'UNIUSD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'binance' => 'UNIUSDT',
                                        'binance_us' => 'UNIUSDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MKR
                    'MKR' => array(
                        
                        'name' => 'Maker',
                        'mcap_slug' => 'maker',
                        'pair' => array(

                        
                                    'btc' => array(
                                          'binance' => 'MKRBTC',
                                          'coinbase' => 'MKR-BTC',
                                          'bittrex' => 'MKR-BTC',
                                       	  'kucoin' => 'MKR-BTC',
                                          'okex' => 'MKR-BTC',
                                          'hitbtc' => 'MKRBTC',
                                          'coinex' => 'MKRBTC',
                                                    ),

                                                    
                                	'dai' => array(
                                        	'kucoin' => 'MKR-DAI',
                                        	'hitbtc' => 'MKRDAI',
                                                    ),

                                                    
                                	'eth' => array(
                                            'bittrex' => 'MKR-ETH',
                                        	'kucoin' => 'MKR-ETH',
                                            'okex' => 'MKR-ETH',
                                        	'hitbtc' => 'MKRETH',
                                            'gateio' => 'MKR_ETH',
                                                    ),

                                                    
                                	'krw' => array(
                                        	'korbit' => 'mkr_krw',
                                                    ),

                                                    
                                    'usd' => array(
                                          'coinbase' => 'MKR-USD',
                                          'binance_us' => 'MKRUSD',
                                          			),

                                                    
                                    'usdt' => array(
                                          'binance' => 'MKRUSDT',
                                          'bittrex' => 'MKR-USDT',
                                          'okex' => 'MKR-USDT',
                                          'bitfinex' => 'tMKRUSD',
                                          'hitbtc' => 'MKRUSD',
                                          'gateio' => 'MKR_USDT',
                                          'coinex' => 'MKRUSDT',
                                          			),

                                          			
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // DAI
                    'DAI' => array(
                        
                        'name' => 'Dai',
                        'mcap_slug' => 'dai',
                        'pair' => array(

                        
                                    'btc' => array(
                                        'bittrex' => 'DAI-BTC',
                                        'upbit' => 'BTC-DAI',
                                        'bitfinex' => 'tDAIBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                         'bittrex' => 'DAI-ETH',
                                    	 'bitfinex' => 'tDAIETH',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'DAIEUR',
                                                    ),

                                                    
                                    'krw' => array(
                                         'korbit' => 'dai_krw',
                                                    ),

                                                    
                                    'usd' => array(
                                    	'coinbase' => 'DAI-USD',
                                    	'kraken' => 'DAIUSD',
                                        'binance_us' => 'DAIUSD',
                                    	'bitfinex' => 'tDAIUSD',
                                        'bittrex' => 'DAI-USD',
                                        'gemini' => 'daiusd',
                                                    ),

                                                    
                                    'usdc' => array(
                                        'hitbtc' => 'DAIUSDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                    	'kraken' => 'DAIUSDT',
                                        'bittrex' => 'DAI-USDT',
                                        'okex' => 'DAI-USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // USDC
                    'USDC' => array(
                        
                        'name' => 'USD Coin',
                        'mcap_slug' => 'usd-coin',
                        'pair' => array(

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'USDCEUR',
                                                    ),

                                                    
                                    'gbp' => array(
                                    	 'kraken' => 'USDCGBP',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'kraken' => 'USDCUSD',
                                    	 'binance_us' => 'USDCUSD',
                                                    ),

                                                    
                                    'usdt' => array(
                                    	'kraken' => 'USDCUSDT',
                                        'huobi' => 'usdcusdt',
                                        'kucoin' => 'USDC-USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MSOL
                    'MSOL' => array(
                        
                        'name' => 'Marinade Solana',
                        'mcap_slug' => 'marinade-staked-sol',
                        'pair' => array(

                                                    
                                    'eth' => array(
                                        'gateio' => 'MSOL_ETH',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'coingecko_usd' => 'msol',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'gateio' => 'MSOL_USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MANA
                    'MANA' => array(
                        
                        'name' => 'Decentraland',
                        'mcap_slug' => 'decentraland',
                        'pair' => array(

                        
                                    'btc' => array(
                                          'binance' => 'MANABTC',
                                          'bittrex' => 'MANA-BTC',
                                          'ethfinex' => 'tMNABTC',
                                          'kucoin' => 'MANA-BTC',
                                          'upbit' => 'BTC-MANA',
                                          'okex' => 'MANA-BTC',
                                          'bitso' => 'mana_btc',
                                          'poloniex' => 'BTC_MANA',
                                                    ),

                                                    
                                    'eth' => array(
                                          'binance' => 'MANAETH',
                                          'bittrex' => 'MANA-ETH',
                                          'hitbtc' => 'MANAETH',
                                          'kucoin' => 'MANA-ETH',
                                          'okex' => 'MANA-ETH',
                                                    ),

                                                    
                                    'krw' => array(
                                          'upbit' => 'KRW-MANA',
                                                    ),

                                                    
                                    'mxn' => array(
                                          'bitso' => 'mana_mxn',
                                                    ),

                                                    
                                    'usd' => array(
                                          'coinbase' => 'MANA-USD',
                                                    ),

                                                    
                                    'usdt' => array(
                                          'hitbtc' => 'MANAUSD',
                                          'okex' => 'MANA-USDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ATLAS
                    'ATLAS' => array(
                        
                        'name' => 'Star Atlas',
                        'mcap_slug' => 'star-atlas',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'coingecko_btc' => 'star-atlas',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'ATLASEUR',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'kraken' => 'ATLASUSD',
                                    	 'bitfinex' => 'tATLAS:USD',
                                    	 'coingecko_usd' => 'star-atlas',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'gateio' => 'ATLAS_USDT',
                                        'coinex' => 'ATLASUSDT',
                                        'hotbit' => 'ATLAS_USDT',
                                        'bitmart' => 'ATLAS_USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // POLIS
                    'POLIS' => array(
                        
                        'name' => 'Star Atlas DAO',
                        'mcap_slug' => 'star-atlas-dao',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'coingecko_btc' => 'star-atlas-dao',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'POLISEUR',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'kraken' => 'POLISUSD',
                                    	 'bitfinex' => 'tPOLIS:USD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'gateio' => 'POLIS_USDT',
                                        'coinex' => 'POLISUSDT',
                                        'hotbit' => 'POLIS_USDT',
                                        'bitmart' => 'ATLAS_USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // RAY
                    'RAY' => array(
                        
                        'name' => 'Raydium',
                        'mcap_slug' => 'raydium',
                        'pair' => array(

                        
                                    'btc' => array(
                                        'coingecko_btc' => 'raydium',
                                                    ),

                                                    
                                    'eth' => array(
                                        'gateio' => 'RAY_ETH',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'RAYEUR',
                                                    ),

                                                    
                                    'usd' => array(
                                    	'kraken' => 'RAYUSD',
                                        'gateio' => 'RAY_USD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'binance' => 'RAYUSDT',
                                        'coinex' => 'RAYUSDT',
                                        'gateio' => 'RAY_USDT',
                                        'wazirx' => 'rayusdt',
                                        'bitmart' => 'RAY_USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SLRS
                    'SLRS' => array(
                        
                        'name' => 'Solrise Finance',
                        'mcap_slug' => 'solrise-finance',
                        'pair' => array(

                        
                                    'eth' => array(
                                        'gateio' => 'SLRS_ETH',
                                                    ),

                                                    
                                    'usd' => array(
                                        'coingecko_usd' => 'solrise-finance',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'gateio' => 'SLRS_USDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // BIT
                    'BIT' => array(
                        
                        'name' => 'BitDAO',
                        'mcap_slug' => 'bitdao',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'coingecko_btc' => 'bitdao',
                                                    ),

                                                    
                                    'usd' => array(
                                        'bybit' => 'BITUSD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'bybit' => 'BITUSDT',
                                        'gateio' => 'BIT_USDT',
                                        'bitmart' => 'BIT_USDT',
                                        'hotbit' => 'BIT_USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // HNT
                    'HNT' => array(
                        
                        'name' => 'Helium',
                        'mcap_slug' => 'helium',
                        'pair' => array(

                        
                                    'btc' => array(
                                        'coingecko_btc' => 'helium',
                                        'hotbit' => 'HNT_BTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'gateio' => 'HNT_ETH',
                                                    ),

                                                    
                                    'inr' => array(
                                        'wazirx' => 'hntinr',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'binance_us' => 'HNTUSD',
                                                    ),

                                                    
                                    'usdt' => array(
                                    	'binance_us' => 'HNTUSDT',
                                        'hotbit' => 'HNT_USDT',
                                        'gateio' => 'HNT_USDT',
                                        'wazirx' => 'hntusdt',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // RNDR
                    'RNDR' => array(
                        
                        'name' => 'Render Token',
                        'mcap_slug' => 'render-token',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'huobi' => 'rndrbtc',
                                        'kucoin' => 'RNDR-BTC',
                                        'hitbtc' => 'RNDRBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'huobi' => 'rndreth',
                                        'gateio' => 'RNDR_ETH',
                                                    ),

                                                    
                                    'usd' => array(
                                        'hitbtc' => 'RNDRUSD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'huobi' => 'rndrusdt',
                                        'gateio' => 'RNDR_USDT',
                                        'kucoin' => 'RNDR-USDT',
                                        'hotbit' => 'RNDR_USDT',
                                        'coinex' => 'RNDRUSDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ZBC
                    'ZBC' => array(
                        
                        'name' => 'Zebec Protocol',
                        'mcap_slug' => 'zebec-protocol',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'coingecko_btc' => 'zebec-protocol',
                                                    ),

                                                    
                                    'eth' => array(
                                        'coingecko_eth' => 'zebec-protocol',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'huobi' => 'zbcusdt',
                                        'gateio' => 'ZBC_USDT',
                                        'bitmart' => 'ZBC_USDT',
                                        'coinex' => 'ZBCUSDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // GRAPE
                    'GRAPE' => array(
                        
                        'name' => 'Grape Protocol',
                        'mcap_slug' => 'grape-protocol',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                          'coingecko_btc' => 'grape-2',
                                                    ),

                        
                                    'eth' => array(
                                          'coingecko_eth' => 'grape-2',
                                                    ),

                                                    
                                    'eur' => array(
                                          'coingecko_eur' => 'grape-2',
                                                    ),

                                                    
                                    'gbp' => array(
                                          'coingecko_gbp' => 'grape-2',
                                                    ),

                                                    
                                    'usd' => array(
                                          'coingecko_usd' => 'grape-2',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SLC
                    'SLC' => array(
                        
                        'name' => 'Solice',
                        'mcap_slug' => 'solice',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'coingecko_btc' => 'solice',
                                                    ),

                                                    
                                    'eth' => array(
                                        'coingecko_eth' => 'solice',
                                        'gateio' => 'SLC_ETH',
                                                    ),

                                                    
                                    'usd' => array(
                                        'coingecko_usd' => 'solice',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'huobi' => 'slcusdt',
                                        'gateio' => 'SLC_USDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // HIVE
                    'HIVE' => array(
                        
                        'name' => 'Hive',
                        'mcap_slug' => 'hive',
                        'pair' => array(

                        
                                    'btc' => array(
                                        'binance' => 'HIVEBTC',
                                        'bittrex' => 'HIVE-BTC',
                                        'hotbit' => 'HIVE_BTC',
                                                    ),

                        
                                    'usdt' => array(
                                        'huobi' => 'hiveusdt',
                                        'hotbit' => 'HIVE_USDT',
                                        'wazirx' => 'hiveusdt',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                
                    ////////////////////////////////////////////////////////////////////
                
                
); // All assets END



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PORTFOLIO ASSETS CONFIGURATION -END- //////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
?>