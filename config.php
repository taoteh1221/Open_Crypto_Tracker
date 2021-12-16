<?php

/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
///////////////////////////////////////////////////////////////////////////////////////////////

// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
header('HTTP/1.0 403 Forbidden', TRUE, 403);
exit;
}

$ct_conf = array(); // REQUIRED, DON'T DELETE BY ACCIDENT

// https://www.php.net/manual/en/function.error-reporting.php
$ct_conf['init']['error_reporting'] = 0; // 0 == off / -1 == on

error_reporting($ct_conf['init']['error_reporting']);

//apc_clear_cache(); apcu_clear_cache(); opcache_reset();  // DEBUGGING ONLY

///////////////////////////////////////////////////////////////////////////////////////////////
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// !!! WHEN RE-CONFIGURING APP, LEAVE THIS CODE ABOVE HERE, DON'T DELETE ABOVE THESE LINES !!!!



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PRIMARY CONFIGURATIONS -START- ////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG

// SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// See TROUBLESHOOTING.txt for tips / troubleshooting FAQs.

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC !!!!WILL BREAK THE APP!!!!, BE CAREFUL EDITING THIS CONFIG FILE!


////////////////////////////////////////
// !START! COMMUNICATIONS CONFIGURATION
////////////////////////////////////////


// Pause sending out all communications (email / text / telegram / alexa / etc), so they are NOT sent to you anymore UNTIL you un-pause them
$ct_conf['comms']['comms_pause'] = 'off'; // 'on' / 'off' (Default = 'off' [comms are sent out normally])


// Enable / disable daily upgrade checks / alerts (DEFAULT: ALL USER-ACTIVATED COMM CHANNELS)
// (Checks latest release version via github.com API endpoint value "tag_name" 
// @ https://api.github.com/repos/taoteh1221/Open_Crypto_Tracker/releases/latest)
// Choosing 'all' will send to all properly-configured communication channels, and automatically skip any not properly setup
$ct_conf['comms']['upgrade_alert'] = 'all'; // 'off' (disabled) / 'all' / 'ui' (web interface) / 'email' / 'text' / 'notifyme' / 'telegram'
////
// Wait X days between upgrade reminders
$ct_conf['comms']['upgrade_alert_reminder'] = 7; // (only used if upgrade check is enabled above)


// IF SMTP EMAIL SENDING --NOT-- USED, FROM email should be REAL address on the website domain, or risk having email sent to junk folder
// IF SMTP EMAIL SENDING --IS-- USED, FROM EMAIL MUST MATCH EMAIL ADDRESS associated with SMTP login (SMTP Email configuration is below this setting)
$ct_conf['comms']['from_email'] = ''; // #SHOULD BE SET# to avoid email going to spam / junk
////
$ct_conf['comms']['to_email'] = ''; // #MUST BE SET# for price alerts and other email features


// Use SMTP authentication TO SEND EMAIL, if your IP has no reverse lookup that matches the email domain name (on your home network etc)
// #REQUIRED WHEN INSTALLED ON A HOME NETWORK#, OR ALL YOUR EMAIL ALERTS WILL BE BLACKHOLED / SEEN AS SPAM EMAIL BY EMAIL SERVERS
// If SMTP credentials / configuration is filled in, BUT not setup properly, APP EMAILING WILL FAIL
// !!USE A THROWAWAY ACCOUNT ONLY!! If web server is hacked, HACKER WOULD THEN HAVE ACCESS YOUR EMAIL LOGIN FROM THIS FILE!!
// CAN BE BLANK (PHP's built-in mail function will be automatically used to send email instead)
$ct_conf['comms']['smtp_login'] = ''; //  CAN BE BLANK. This format MUST be used: 'username||password'
////
// SMTP Server examples (protocol auto-detected / used based off port number): 
// 'example.com:25' (non-encrypted), 'example.com:465' (ssl-encrypted), 'example.com:587' (tls-encrypted)
$ct_conf['comms']['smtp_server'] = ''; // CAN BE BLANK. This format MUST be used: 'domain_or_ip:port_number' 


// For alert texts to mobile phone numbers. 
// Attempts to email the text if a SUPPORTED MOBILE TEXTING NETWORK name is set, AND no textbelt / textlocal config is setup.
// SMTP-authenticated email sending MAY GET THROUGH TEXTING SERVICE CONTENT FILTERS #BETTER# THAN USING PHP'S BUILT-IN EMAILING FUNCTION
// SEE FURTHER DOWN IN THIS CONFIG FILE, FOR A LIST OF SUPPORTED MOBILE TEXTING NETWORK PROVIDER NAMES 
// IN THE EMAIL-TO-MOBILE-TEXT CONFIG SECTION (the "network name keys" in the $ct_conf['mob_net_txt_gateways'] variables array)
// CAN BE BLANK. Country code format MAY NEED TO BE USED (depending on your mobile network)
// skip_network_name SHOULD BE USED IF USING textbelt / textlocal BELOW
// 'phone_number||network_name_key' (examples: '12223334444||virgin_us' / '12223334444||skip_network_name')
$ct_conf['comms']['to_mobile_text'] = '';


// Do NOT use textbelt AND textlocal together. Leave one setting blank, OR IT WILL DISABLE USING BOTH.
// LEAVE textbelt AND textlocal BOTH BLANK to use a mobile text gateway set ABOVE

// CAN BE BLANK. For asset price alert textbelt notifications. Setup: https://textbelt.com/
$ct_conf['comms']['textbelt_apikey'] = '';


// CAN BE BLANK. For asset price alert textlocal notifications. Setup: https://www.textlocal.com/integrations/api/
$ct_conf['comms']['textlocal_account'] = ''; // This format MUST be used: 'username||hash_code'


// For notifyme / alexa notifications (sending Alexa devices notifications for free). 
// CAN BE BLANK. Setup: http://www.thomptronics.com/notify-me
// (NOTE: THIS APP'S BUILT-IN QUEUE SYSTEM THROTTLES / SENDS OUT 6 ALERTS EVERY 6 MINUTES MAXIMUM FOR NOTIFYME ALERTS,
// TO STAY WITHIN NOTIFYME API MESSAGE LIMITS, SO YOU ALWAYS STILL GET ALL YOUR ALERTS, JUST SLIGHTLY DELAYED)
$ct_conf['comms']['notifyme_accesscode'] = '';


// Sending alerts to your own telegram bot chatroom. 
// (USEFUL IF YOU HAVE ISSUES SETTING UP MOBILE TEXT ALERTS, INCLUDING EMOJI / UNICODE CHARACTER ENCODING)
// Setup: https://core.telegram.org/bots , OR JUST SEARCH / VISIT "BotFather" in the telegram app
// YOU MUST SETUP A TELEGRAM USERNAME #FIRST / BEFORE SETTING UP THE BOT#, IF YOU HAVEN'T ALREADY (IN THE TELEGRAM APP SETTINGS)
// SET UP YOUR BOT WITH "BotFather", AND SAVE YOUR BOT NAME / USERNAME / ACCESS TOKEN / BOT CHATROOM LINK
// VISIT THE BOT CHATROOM, #SEND THE MESSAGE "/start" TO THIS CHATROOM# (THIS WILL CREATE USER CHAT DATA THE APP NEEDS)
// THE USER CHAT DATA #IS REQUIRED# FOR THIS APP TO INITIALLY DETERMINE AND SECURELY SAVE YOU TELEGRAM USER'S CHAT ID
// #DO NOT DELETE THE BOT CHATROOM IN THE TELEGRAM APP, OR YOU WILL STOP RECEIVING MESSAGES FROM THE BOT#
$ct_conf['comms']['telegram_your_username'] = ''; // Your telegram username (REQUIRED, setup in telegram app settings)
////
$ct_conf['comms']['telegram_bot_username'] = '';  // Your bot's username
////
$ct_conf['comms']['telegram_bot_name'] = ''; // Your bot's human-readable name (example: 'My Alerts Bot')
////
$ct_conf['comms']['telegram_bot_token'] = '';  // Your bot's access token


// PRICE ALERTS SETUP REQUIRES A CRON JOB RUNNING ON YOUR WEB SERVER (see README.txt for cron job setup information) 
// Price alerts will send to all properly-configured communication channels, and automatically skip any not properly setup
// Price percent change to send alerts for (WITHOUT percent sign: 15.75 = 15.75%). Sends alerts when percent change reached (up or down)
$ct_conf['comms']['price_alert_thres'] = 9.25; // CAN BE 0 TO DISABLE PRICE ALERTS
////
// Re-allow SAME asset price alert(s) messages after X hours (per alert config, set higher if sent to junk folder / API blocking or throttling)
// Price alerts AUTOMATICALLY will send to all properly-configured communication channels, and automatically skip any not properly setup
$ct_conf['comms']['price_alert_freq_max'] = 2; // Can be 0, to have no limits
////
// Block an asset price alert if price retrieved, BUT failed retrieving pair volume (not even a zero was retrieved, nothing)
// Good for blocking questionable exchanges bugging you with price alerts, especially when used in combination with the minimum volume filter
$ct_conf['comms']['price_alert_block_vol_error'] = 'on'; // 'on' / 'off' 
////
// Minimum 24 hour volume filter. Only allows sending price alerts if minimum 24 hour volume reached
// CAN BE 0 TO DISABLE MINIMUM VOLUME FILTERING, NO DECIMALS OR SEPARATORS, NUMBERS ONLY, WITHOUT the [primary currency] prefix symbol: 4500 = $4,500 , 30000 = $30,000 , etc
// THIS FILTER WILL AUTO-DISABLE IF THERE IS ANY ERROR RETRIEVING DATA ON A CERTAIN MARKET (WHEN NOT EVEN A ZERO IS RECEIVED)
$ct_conf['comms']['price_alert_min_vol'] = 3500;


// Every X days email a list of #NEW# RSS feed posts. 
// 0 to disable. Email to / from !MUST BE SET!
$ct_conf['comms']['news_feed_email_freq'] = 2; // (default = 2)
////
// MAXIMUM #NEW# RSS feed entries to include (per-feed) in news feed email (less then 'news_feed_email_freq' days old)
$ct_conf['comms']['news_feed_email_entries_show'] = 15; // (default = 15)


// Email logs every X days. 
// 0 to disable. Email to / from !MUST BE SET!, MAY NOT SEND IN TIMELY FASHION WITHOUT A CRON JOB
$ct_conf['comms']['logs_email'] = 2; 


// Alerts for failed proxy data connections (#ONLY USED# IF proxies are enabled further down in PROXY CONFIGURATION). 
// Choosing 'all' will send to all properly-configured communication channels, and automatically skip any not properly setup
$ct_conf['comms']['proxy_alert'] = 'email'; // 'off' (disabled) / 'all' / 'email' / 'text' / 'notifyme' / 'telegram'
////
$ct_conf['comms']['proxy_alert_freq_max'] = 1; // Re-allow same proxy alert(s) after X hours (per ip/port pair, can be 0)
////
$ct_conf['comms']['proxy_alert_runtime'] = 'cron'; // Which runtime mode should allow proxy alerts? Options: 'cron', 'ui', 'all' 
////
// 'include' or 'ignore' proxy alerts, if proxy checkup went OK? (after flagged, started working again when checked)
$ct_conf['comms']['proxy_alert_checkup_ok'] = 'include'; 


////////////////////////////////////////
// !END! COMMUNICATIONS CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! GENERAL CONFIGURATION
////////////////////////////////////////


// Interface login protection (htaccess user/password required to view this portfolio app's web interface)
// Username MUST BE at least 4 characters, beginning with ONLY LOWERCASE letters (may contain numbers AFTER first letter), NO SPACES
// Password MUST BE EXACTLY 8 characters, AND contain one number, one UPPER AND LOWER CASE letter, and one symbol, NO SPACES
// (ENABLES / UPDATES automatically, when a valid username / password are filled in or updated here)
// (DISABLES automatically, when username / password are blank '' OR invalid) 
// (!ONLY #UPDATES OR DISABLES# AUTOMATICALLY #AFTER# LOGGING IN ONCE WITH YOUR #OLD LOGIN# [or if a cron job runs with the new config]!)
// #IF THIS SETTING GIVES YOU ISSUES# ON YOUR SYSTEM, BLANK IT OUT TO '', AND DELETE '.htaccess' IN THE MAIN DIRECTORY OF 
// THIS APP (TO RESTORE PAGE ACCESS), AND PLEASE REPORT IT HERE: https://github.com/taoteh1221/Open_Crypto_Tracker/issues
$ct_conf['gen']['interface_login'] = ''; // Leave blank to disable requiring an interface login. This format MUST be used: 'username||password'


// Password protection / encryption security for backup archives (REQUIRED for app config backup archives, NOT USED FOR CHART BACKUPS)
$ct_conf['gen']['backup_arch_pass'] = ''; // LEAVE BLANK TO DISABLE


// API key for etherscan.io (required unfortunately, but a FREE level is available): https://etherscan.io/apis
$ct_conf['gen']['etherscan_key'] = '';


// API key for defipulse.com API (required unfortunately, but a FREE level is available): https://data.defipulse.com/
$ct_conf['gen']['defipulse_key'] = '';


// API key for coinmarketcap.com Pro API (required unfortunately, but a FREE level is available): https://coinmarketcap.com/api
$ct_conf['gen']['cmc_key'] = '';


// Default marketcap data source: 'coingecko', or 'coinmarketcap' (COINMARKETCAP REQUIRES A #FREE# API KEY, see $ct_conf['gen']['cmc_key'] above)
$ct_conf['gen']['prim_mcap_site'] = 'coingecko'; 


// Default BITCOIN market currencies (80+ currencies supported)
// (set for default Bitcoin market, and charts / price alert primary-currency-equivalent value determination [example: usd value of btc/ltc market, etc])
// aed / ars / aud / bam / bdt / bob / brl / bwp / byn / cad / chf / clp / cny / cop / crc / czk 
// dai / dkk / dop / egp / eth / eur / gbp / gel / ghs / gtq / hkd / huf / idr / ils / inr / irr 
// jmd / jod / jpy / kes / krw / kwd / kzt / lkr / mad / mur / mwk / mxn / myr / ngn / nis / nok 
// nzd / pab / pen / php / pkr / pln / pyg / qar / ron / rsd / rub / rwf / sar / sek / sgd / thb 
// try / tusd / twd / tzs / uah / ugx / usdc / usdt / uyu / ves / vnd / xaf / xof / zar / zmw
// SEE THE $ct_conf['assets'] CONFIGURATION NEAR THE BOTTOM OF THIS CONFIG FILE, FOR THE PROPER (CORRESPONDING)
// CURRENCY PAIRING VALUE NEEDED FOR YOUR CHOSEN 'BTC' EXCHANGE (set in $ct_conf['gen']['btc_prim_exchange'] directly below)
$ct_conf['gen']['btc_prim_currency_pairing'] = 'usd'; // PUT INSIDE SINGLE QUOTES ('selection')


// Default BITCOIN market exchanges (30+ bitcoin exchanges supported)
// (set for default Bitcoin market, and charts / price alert primary-currency-equivalent value determination [example: usd value of btc/ltc market, etc])
// binance / binance_us / bit2c / bitbns / bitfinex / bitflyer / bitmex / bitpanda / bitso / bitstamp 
// bittrex / bittrex_global / btcmarkets / btcturk / buyucoin / cex / coinbase / coindcx / coinspot 
// defipulse / gemini / hitbtc / huobi / korbit / kraken / kucoin / liquid / localbitcoins / loopring_amm 
// luno / okcoin / okex / southxchange / unocoin / upbit / wazirx
// SEE THE $ct_conf['assets'] CONFIGURATION NEAR THE BOTTOM OF THIS CONFIG FILE, FOR THE PROPER (CORRESPONDING)
// MARKET PAIRING VALUE NEEDED FOR YOUR CHOSEN 'BTC' EXCHANGE (to populate $ct_conf['gen']['btc_prim_currency_pairing'] directly above with)
// SEE THE $ct_conf['dev']['limited_apis'] SETTING MUCH FURTHER DOWN, FOR EXCHANGES !NOT RECOMMENDED FOR USAGE HERE!
$ct_conf['gen']['btc_prim_exchange'] = 'kraken';  // PUT INSIDE SINGLE QUOTES ('selection')


// Maximum decimal places for [primary currency] values, of coins worth under 1 fiat value unit [usd/gbp/eur/jpy/brl/rub/etc],
// for prettier / less-cluttered interface. IF YOU ADJUST $ct_conf['gen']['btc_prim_currency_pairing'] ABOVE, 
// YOU MAY NEED TO ADJUST THIS ACCORDINGLY FOR !PRETTY / FUNCTIONAL! CHARTS / ALERTS FOR YOUR PRIMARY CURRENCY
// ALSO KEEP THIS NUMBER AS LOW AS IS FEASIBLE, TO SAVE ON CHART DATA STORAGE SPACE / MAINTAIN QUICK CHART LOAD TIMES
$ct_conf['gen']['prim_currency_dec_max'] = 5; // Whole numbers only (represents number of decimals maximum to use)


// Your local time offset IN HOURS, COMPARED TO UTC TIME. Can be negative or positive.
// (Used for user experience 'pretty' timestamping in interface logic ONLY, WILL NOT change or screw up UTC log times etc if you change this)
$ct_conf['gen']['loc_time_offset'] = -5; // example: -5 or 5, -5.5 or 5.75 (#CAN BE DECIMAL# TO SUPPORT 30 / 45 MINUTE TIME ZONES)


// Configure which interface theme you want as the default theme (also can be manually switched later, on the settings page in the interface)
$ct_conf['gen']['default_theme'] = 'dark'; // 'dark' or 'light'


// ENABLING CHARTS REQUIRES A CRON JOB SETUP (see README.txt for cron job setup information)
// Enables a charts tab / page, and caches real-time updated historical chart data on your device's storage drive
// Disabling will disable EVERYTHING related to the price charts (price charts tab / page, and price chart data caching)
$ct_conf['gen']['asset_charts_toggle'] = 'on'; // 'on' / 'off'


////////////////////////////////////////
// !END! GENERAL CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! PROXY CONFIGURATION
////////////////////////////////////////


// If using proxies and login is required
// Adding a user / pass here will automatically send login details for proxy connections
// CAN BE BLANK. IF using ip address authentication instead, MUST BE LEFT BLANK
$ct_conf['proxy']['proxy_login'] = ''; // Use format: 'username||password'
////
// If using proxies, add the ip address / port number here for each one, like examples below (without the double slashes in front enables the code)
// CAN BE BLANK. Adding proxies here will automatically choose one randomly for each API request
$ct_conf['proxy']['proxy_list'] = array(
					// 'ipaddress1:portnumber1',
					// 'ipaddress2:portnumber2',
					);


////////////////////////////////////////
// !END! PROXY CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! CHART AND PRICE ALERT CONFIGURATION
////////////////////////////////////////


// CHARTS / PRICE ALERTS SETUP REQUIRES A CRON JOB RUNNING ON YOUR WEB SERVER (see README.txt for cron job setup information) 

// Asset price alert configuration
// Only used if $ct_conf['charts_alerts']['tracked_markets'] is filled in properly below, AND a cron job is setup (see README.txt for cron job setup information) 
////
// Fixed time interval RESET of cached comparison asset prices every X days (since last price reset / alert) with the current latest spot prices
// Helpful if you only want price alerts for a certain time window. Resets also send alerts that reset occurred, with summary of price changes since last reset
// Can be 0 to disable fixed time interval resetting (IN WHICH CASE RESETS WILL ONLY OCCUR DYNAMICALLY when the next price alert is triggered / sent out)
$ct_conf['charts_alerts']['price_alert_fixed_reset'] = 0; // (default = 0)
////
// Whale alert (adds "WHALE ALERT" to beginning of alexa / email / telegram alert text, and spouting whale emoji to email / text / telegram)
// Format: 'max_days_to_24hr_avg_over||min_price_percent_change_24hr_avg||min_vol_percent_incr_24hr_avg||min_vol_currency_incr_24hr_avg'
// ("min_price_percent_change_24hr_avg" should be the same value or higher as $ct_conf['comms']['price_alert_thres'] to work properly)
// Leave BLANK '' TO DISABLE. DECIMALS ARE SUPPORTED, USE NUMBERS ONLY (NO CURRENCY SYMBOLS / COMMAS, ETC)
$ct_conf['charts_alerts']['price_alert_whale_thres'] = '1.65||8.85||9.1||16000'; // (default: '1.65||8.85||9.1||16000')
////
// Markets you want charts or asset price change alerts for (alerts sent when default [primary currency] 
// [$ct_conf['gen']['btc_prim_currency_pairing'] at top of this config] value change is equal to or above / below $ct_conf['comms']['price_alert_thres']) 
// NOTE: This list must only contain assets / exchanges / trading pairs included in the primary portfolio assets list configuration further down in this config file
// TO ADD MULTIPLE CHARTS / ALERTS FOR SAME ASSET (FOR DIFFERENT EXCHANGES / TRADE PAIRINGS), FORMAT LIKE SO: symbol, symbol-1, symbol-2, symbol-3, etc.
// TO DISABLE CHART AND ALERT = none, TO ENABLE CHART AND ALERT = both, TO ENABLE CHART ONLY = chart, TO ENABLE ALERT ONLY = alert
$ct_conf['charts_alerts']['tracked_markets'] = array(


					// SYMBOL
				// 'symbol' => 'exchange||trade_pairing||alert',
				// 'symbol-2' => 'exchange2||trade_pairing2||chart',
				// 'symbol-3' => 'exchange3||trade_pairing3||both',
				// 'symbol-4' => 'exchange4||trade_pairing4||none',
				
				
					// OTHERSYMBOL
				// 'othersymbol' => 'exchange||trade_pairing||none',
				// 'othersymbol-2' => 'exchange2||trade_pairing2||both',
				// 'othersymbol-3' => 'exchange3||trade_pairing3||chart',
				// 'othersymbol-4' => 'exchange4||trade_pairing4||alert',
					
					
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
					'btc-25' => 'localbitcoins||twd||none',
					'btc-26' => 'luno||zar||none',
					'btc-27' => 'kraken||dai||none',
					'btc-28' => 'bitmex||usd||both',
					'btc-29' => 'defipulse||eth||none',
					
					
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
					'eth-15' => 'defipulse||btc||none',
					
					
					// SOL
					'sol' => 'binance||btc||none',
					'sol-2' => 'coinbase||usd||chart',
					'sol-3' => 'ftx_us||btc||both',
					
					
					// UNI
					'uni' => 'binance||btc||both',
					'uni-2' => 'defipulse||eth||none',
					'uni-3' => 'binance||usdt||none',
					'uni-4' => 'coinbase||usd||none',
					
					
					// MKR
					'mkr' => 'okex||btc||none',
					'mkr-2' => 'kucoin||btc||none',
					'mkr-3' => 'coinbase||btc||both',
					'mkr-4' => 'defipulse||eth||none',
					
					
					// DAI
					'dai' => 'coinbase||usdc||both',
					'dai-2' => 'kraken||usd||none',
					'dai-3' => 'bittrex||btc||none',
					'dai-4' => 'defipulse||usdc||none',
					
					
					// USDC
					'usdc' => 'kraken||usd||both',
					'usdc-2' => 'binance_us||usd||none',
					
					
					// MANA
					'mana' => 'bittrex||btc||chart',
					'mana-2' => 'binance||btc||both',
					'mana-3' => 'kucoin||btc||none',
					'mana-4' => 'ethfinex||btc||none',
					'mana-5' => 'binance||eth||none',
					
					
					// ENJ
					'enj' => 'bittrex||btc||none',
					'enj-2' => 'binance||btc||both',
					'enj-3' => 'kucoin||btc||none',
					'enj-4' => 'bitfinex||usd||none',
					
					
					// RNDR
					'rndr' => 'huobi||btc||both',
					'rndr-2' => 'gateio||usdt||none',
					
					
					// LRC
					'lrc' => 'coinbase||usd||both',
					'lrc-2' => 'binance||btc||chart',
					'lrc-3' => 'binance||eth||none',
					'lrc-4' => 'defipulse||eth||none',
					
					
					// RAY
					'ray' => 'ftx||usd||both',
					'ray-2' => 'generic_btc||btc||chart',
					
					
					// SRM
					'srm' => 'ftx||usd||both',
					'srm-2' => 'binance||btc||chart',
					
					
					// SLRS
					'slrs' => 'ftx||usd||both',
					'slrs-2' => 'gateio||eth||chart',
					
					
					// IN
					'in' => 'generic_usd||usd||both',
					'in-2' => 'generic_btc||btc||chart',
					
					
					// HNT
					'hnt' => 'binance||btc||chart',
					'hnt-2' => 'binance_us||usd||both',
					'hnt-3' => 'gateio||eth||none',
					
					
					// HIVE
					'hive' => 'bittrex||btc||both',
					
					
					// MYST
					'myst' => 'hitbtc||btc||chart',
					'myst-2' => 'hitbtc||eth||none',
					'myst-3' => 'bittrex_global||btc||both',
					'myst-4' => 'defipulse||eth||none',
					
					
					// SAMO
					'samo' => 'okex||usdt||both',
					'samo-2' => 'gateio||eth||chart',
					
					
					// SG
					'sg' => 'bittrex_global||usdt||chart',
					'sg-2' => 'bitmart||usdt||none',
					'sg-3' => 'bitmart||btc||both',
					
					
					);
					
// END $ct_conf['charts_alerts']['tracked_markets']


////////////////////////////////////////
// !END! CHART AND PRICE ALERT CONFIGURATION
////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
// !START! POWER USER CONFIGURATION (ADJUST WITH CARE, OR YOU CAN BREAK THE APP!)
/////////////////////////////////////////////////////////////////////////////


// Activate any built-in included / custom plugins you've created (that run from the /plugins/ directory)
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt for creating your own custom plugins
// ADD ANY NEW PLUGIN HERE BY USING THE FOLDER NAME THE NEW PLUGIN IS LOCATED IN
// !!NEVER ADD A PLUGIN SOMEBODY ELSE WROTE, UNLESS YOU OR SOMEONE YOU TRUST 
// HAVE REVIEWED THE CODE AND ARE ABSOLUTELY SURE IT IS NOT MALICIOUS!!
// PLUGINS *MAY REQUIRE* A CRON JOB RUNNING ON YOUR WEB SERVER (if built for cron jobs...see README.txt for cron job setup information)
// PLUGIN CONFIGS are in the /plugins/ directory associated with that plugin
// CHANGE 'off' to 'on' FOR THE PLUGIN YOU WANT ACTIVATED 
$ct_conf['power']['activate_plugins'] = array(
									//'plugin-folder-name' => 'on', // (disabled example...your LOWERCASE plugin folder name in the folder: /plugins/)
									'recurring-reminder' => 'off',  // Recurring Reminder plugin (alert yourself every X days to do something)
									'price-target-alert' => 'off',  // Price target alert plugin (alert yourself when an asset's price target is reached)
									'address-balance-tracker' => 'off',  // Get alerts for BTC / ETH address balance changes (when new coins are sent / recieved)
									);


// Keep logs X days before purging (fully deletes logs every X days). Start low (especially when using proxies)
$ct_conf['power']['logs_purge'] = 10; // (default = 10)
							
							
// Seconds to wait for response from REMOTE API endpoints (exchange data, etc). 
// Set too low you won't get ALL data (partial or zero bytes), set too high the interface can take a long time loading if an API server hangs up
// RECOMMENDED MINIMUM OF 60 FOR INSTALLS BEHIND #LOW BANDWIDTH# NETWORKS 
// (which may need an even higher timeout above 60 if data still isn't FULLY received from all APIs)
$ct_conf['power']['remote_api_timeout'] = 35; // (default = 35)
							
							
// HOURS until admin login cookie expires (requiring you to login again)
// The lower number the better for higher security, epecially if the app server temporary session data 
// doesn't auto-clear too often (that also logs you off automatically, REGARDLESS of this setting's attribute)
$ct_conf['power']['admin_cookie_expire'] = 6; // (default = 6)


// MINUTES to cache real-time exchange price data...can be zero to skip cache, but set to at least 1 minute TO AVOID YOUR IP ADDRESS GETTING BLOCKED
// SOME APIS PREFER THIS SET TO AT LEAST A FEW MINUTES, SO HIGHLY RECOMMENDED TO KEEP FAIRLY HIGH
$ct_conf['power']['last_trade_cache_time'] = 4; // (default = 4)


// Minutes to cache blockchain stats (for mining calculators). Set high initially, it can be strict
$ct_conf['power']['chainstats_cache_time'] = 75;  // (default = 75)


// Minutes to cache marketcap rankings...start high and test lower, it can be strict
$ct_conf['power']['mcap_cache_time'] = 55;  // (default = 55)
////
// Number of marketcap rankings to request from API.
// 750 rankings is a safe maximum to start with, to avoid getting your API requests throttled / blocked
$ct_conf['power']['mcap_ranks_max'] = 750; // (default = 750)


// Maximum margin leverage available in the user interface ('Update' page, etc)
$ct_conf['power']['margin_leverage_max'] = 150; 


// Lite charts (load just as quickly for any time interval, 7 day / 30 day / 365 day / etc)
// Structure of lite charts #IN DAYS# (X days time period charts)
// Interface will auto-detect and display days as 365 = 1Y, 180 = 6M, 7 = 1W, etc
// (LOWER TIME PERIODS [UNDER 180 DAYS] #SHOULD BE KEPT SOMEWHAT MINIMAL#, TO REDUCE RUNTIME LOAD / DISK WRITES DURING CRON JOBS)
$ct_conf['power']['lite_chart_day_intervals'] = array(10, 30, 90, 180, 365, 730, 1460); // (default = 10, 30, 90, 180, 365, 730, 1460)
////
// The maximum number of data points allowed in each lite chart 
// (saves on disk storage / speeds up chart loading times SIGNIFICANTLY #WITH A NUMBER OF 400 OR LESS#)
$ct_conf['power']['lite_chart_data_points_max'] = 400; // (default = 400), ADJUST WITH CARE!!!


// Number of decimals for price chart CRYPTO 24 hour volumes (NOT USED FOR FIAT VOLUMES, 4 decimals example: 24 hr vol = 91.3874 BTC)
// KEEP THIS NUMBER AS LOW AS IS FEASIBLE, TO SAVE ON CHART DATA STORAGE SPACE / MAINTAIN QUICK CHART LOAD TIMES
$ct_conf['power']['chart_crypto_vol_dec'] = 4;  // (default = 4)
////
// Every X days backup chart data. 0 disables backups. Email to / from !MUST BE SET! (a download link is emailed to you of the chart data archive)
$ct_conf['power']['charts_backup_freq'] = 1; 


// Default settings for Asset Performance chart height / menu size (in the 'View More Stats' modal window, linked at bottom of Portfolio page)
// CHART HEIGHT MIN/MAX = 400/900 (increments of 100), MENU SIZE MIN/MAX (increments of 1) = 7/16
$ct_conf['power']['asset_performance_chart_defaults'] = '700||11'; // 'chart_height||menu_size' (default = '700||11')


// Default settings for Marketcap Comparison chart height / menu size (in the 'View More Stats' modal window, linked at bottom of Portfolio page)
// CHART HEIGHT MIN/MAX = 400/900 (increments of 100), MENU SIZE MIN/MAX (increments of 1) = 7/16
$ct_conf['power']['asset_mcap_chart_defaults'] = '700||11'; // 'chart_height||menu_size' (default = '700||11')


// Highest numeric value sensor data to include, in the FIRST system information chart (out of two)
// (higher sensor data is moved into the second chart, to keep ranges easily readable between both charts...only used IF CRON JOB IS SETUP)
$ct_conf['power']['system_stats_first_chart_highest_val'] = 1; // (default = 1) 
////
// Highest allowed sensor value to scale vertical axis for, in the SECOND system information chart (out of two)
// (to prevent anomaly results from scaling vertical axis too high to read LESSER-VALUE sensor data...only used IF CRON JOB IS SETUP)
$ct_conf['power']['system_stats_second_chart_max_scale'] = 150; // (default = 150) 


// MINUTES to cache real-time DeFi pool info (pool eth address / name / volume / etc)
// THIS SETTING DOES #NOT# AFFECT PRICE / TRADE VALUE REFRESHING, IT ONLY AFFECTS THE POOL'S TRADE VOLUME STATS / STORED ETH ADDRESS
// LOTS OF DATA, the higher number the better for fast page load times
$ct_conf['power']['defi_pools_info_cache_time'] = 25; // (default = 25)
////
// Maximum number of LARGEST LIQUIDITY OR 24 HOUR TRADE VOLUME DeFi pools to fetch
// INCREASE IF YOUR POOL DOESN'T GET DETECTED, BUT YOU KNOW THE POOL EXISTS, AS POOLS ARE SORTED BY LARGEST VOLUME OR LIQUIDITY
$ct_conf['power']['defi_liquidity_pools_max'] = 1000; // (default = 1000)
////
// Sort DeFi pools by USD liquidity or volume (largest first)
$ct_conf['power']['defi_liquidity_pools_sort_by'] = 'volume'; // 'volume' or 'liquidity' (default = 'volume')
////
// Maximum number of MOST RECENT trades to fetch per DeFi pool
// INCREASE IF TRADES FOR YOUR PAIRING DON'T GET DETECTED, AS TRADES ARE SORTED BY NEWEST FIRST
$ct_conf['power']['defi_pools_max_trades'] = 60; // (default = 60)
		

// CONTRAST of CAPTCHA IMAGE text against background (on login pages)
// 0 for neutral contrast, positive for more contrast, negative for less contrast (MAXIMUM OF +-35)
$ct_conf['power']['captcha_text_contrast'] = -5; // example: -5 or 5 (default = -5)
////
// MAX OFF-ANGLE DEGREES (tilted backward / forward) of CAPTCHA IMAGE text characters (MAXIMUM OF 35)
$ct_conf['power']['captcha_text_angle'] = 30; // (default = 30)


// Days until old backup archives should be deleted (chart data archives, etc)
$ct_conf['power']['backup_arch_del_old'] = 7; 

																					
// ASSET MARKETS chart colors (https://www.w3schools.com/colors/colors_picker.asp)
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
							
							

// Auto-activate support for ALTCOIN PAIRED MARKETS (like glm/eth or mkr/eth, etc...markets where the base pairing is an altcoin)
// EACH ALTCOIN LISTED HERE !MUST HAVE! AN EXISTING 'btc' MARKET (within 'pairing') in it's 
// $ct_conf['assets'] listing (further down in this config file) TO PROPERLY AUTO-ACTIVATE
// THIS ALSO ADDS THESE ASSETS AS OPTIONS IN THE "Show Crypto Value Of ENTIRE Portfolio In" SETTING, ON THE SETTINGS PAGE,
// AND IN THE "Show Secondary Trade / Holdings Value" SETTING, ON THE SETTINGS PAGE TOO
// !!!!!TRY TO #NOT# ADD STABLECOINS HERE!!!!!, FIRST TRY $ct_conf['power']['btc_currency_markets'] INSTEAD (TO AUTO-CLIP UN-NEEDED DECIMAL POINTS) 
// !!!!!BTC IS ALREADY ADDED AUTOMATICALLY, NO NEED TO ADD IT HERE!!!!!
$ct_conf['power']['crypto_pairing'] = array(
						//'lowercase_altcoin_ticker' => 'UNICODE_SYMBOL', // Add whitespace after the symbol, if you prefer that
						// Native chains...
						'eth' => 'Ξ ',
						'sol' => '◎ ',
						'hnt' => 'Ȟ ',
						// Liquidity pools / ERC-20 tokens on Ethereum / SPL tokens on Solana, etc etc...
						'uni' => '🦄 ',
						'mkr' => '𐌼 ',
						'lrc' => '➰ ',
						'in' => '🦉 ',
						//....
							);



// Preferred ALTCOIN PAIRED MARKETS market(s) for getting a certain crypto's value
// EACH ALTCOIN LISTED HERE MUST EXIST IN $ct_conf['power']['crypto_pairing'] ABOVE,
// AND !MUST HAVE! AN EXISTING 'btc' MARKET (within 'pairing') in it's 
// $ct_conf['assets'] listing (further down in this config file),
// AND #THE EXCHANGE NAME MUST BE IN THAT 'btc' LIST#
// #USE LIBERALLY#, AS YOU WANT THE BEST PRICE DISCOVERY FOR THIS CRYPTO'S VALUE
$ct_conf['power']['crypto_pairing_pref_markets'] = array(
						//'lowercase_btc_market_or_stablecoin_pairing' => 'PREFERRED_MARKET',
							'eth' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'hnt' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'sol' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'uni' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'mkr' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'lrc' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							);



// Auto-activate support for PRIMARY CURRENCY MARKETS (to use as your preferred local currency in the app)
// EACH CURRENCY LISTED HERE !MUST HAVE! AN EXISTING BITCOIN ASSET MARKET (within 'pairing') in 
// Bitcoin's $ct_conf['assets'] listing (further down in this config file) TO PROPERLY AUTO-ACTIVATE
// #CAN# BE A CRYPTO / HAVE A DUPLICATE IN $ct_conf['power']['crypto_pairing'], 
// !AS LONG AS THERE IS A PAIRING CONFIGURED WITHIN THE BITCOIN ASSET SETUP!
$ct_conf['power']['btc_currency_markets'] = array(
						//'lowercase_btc_market_or_stablecoin_pairing' => 'CURRENCY_SYMBOL',
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
// EACH CURRENCY LISTED HERE MUST EXIST IN $ct_conf['power']['btc_currency_markets'] ABOVE
// #USE CONSERVATIVELY#, AS YOU'LL BE RECOMMENDING IN THE INTERFACE TO END-USERS TO AVOID USING ANY OTHER MARKETS FOR THIS CURRENCY
$ct_conf['power']['btc_pref_currency_markets'] = array(
						//'lowercase_btc_market_or_stablecoin_pairing' => 'PREFERRED_MARKET',
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
// HIVE Power yearly interest rate START 11/29/2019 (1.2%, decreasing every year by roughly 0.075% until it hits a minimum of 0.075% and stays there)
// 1.2 (DO NOT INCLUDE PERCENT SIGN) the first year at 11/29/2019 refactored rates, see above for manual yearly adjustment
$ct_conf['power']['hivepower_yearly_interest'] = 1.2;



// Mining calculator configs for different crypto networks (SEMI-AUTOMATICALLY adds mining calculators to the Mining page)
// FOR #DYNAMIC# CHAIN STATS (height / difficuly / rewards / etc), API CALL FUNCTIONS NEED TO BE CUSTOM-WRITTEN FOR ANY #CUSTOM# ASSETS ADDED HERE,
// AND CALLED WITHIN THE 'Update dynamic mining data' SECTION OF THE FILE: /app-lib/php/other/app-config-managment.php
// 'mining_time_formula' ALSO NEEDS TO BE DYNAMICALLY ADDED IN THAT SAME SECTION, #OR YOUR CUSTOM CALCULATOR WILL NOT WORK AT ALL#
$ct_conf['power']['mining_calculators'] = array(
					
					
			// POW CALCULATORS
			'pow' => array(
					
					
					// BTC
					'btc' => array(
											'name' => 'Bitcoin', // Coin name
											'symbol' => 'btc', // Coin symbol (lowercase)
											'exchange_name' => 'binance', // Exchange name (for price data, lowercase)
											'exchange_market' => 'BTCUSDT', // Market pair name (for price data)
											'measure_semantic' => 'difficulty',  // (difficulty, nethashrate, etc)
											'block_reward' => 6.25, // Mining block reward (OPTIONAL, can be made dynamic with code, like below)
											// EVERYTHING BELOW #MUST BE# updated in /app-lib/php/other/app-config-managment.php, since we run a cached config)
											'mining_time_formula' => 'PLACEHOLDER', // Mining time formula calculation (REQUIRED)
											'height' => 'PLACEHOLDER', // Block height (OPTIONAL)
											'difficulty' => 'PLACEHOLDER', // Mining network difficulty (OPTIONAL)
											'other_network_data' => '', // Leave blank to skip (OPTIONAL)
										),
					
					
					// ETH
					'eth' => array(
											'name' => 'Ethereum', // Coin name
											'symbol' => 'eth', // Coin symbol (lowercase)
											'exchange_name' => 'binance', // Exchange name (for price data, lowercase)
											'exchange_market' => 'ETHBTC', // Market pair name (for price data)
											'measure_semantic' => 'difficulty',  // (difficulty, nethashrate, etc)
											'block_reward' => 2, // Mining block reward (OPTIONAL, can be made dynamic with code, like below)
											// EVERYTHING BELOW #MUST BE# updated in /app-lib/php/other/app-config-managment.php, since we run a cached config)
											'mining_time_formula' => 'PLACEHOLDER', // Mining time formula calculation (REQUIRED)
											'height' => 'PLACEHOLDER', // Block height (OPTIONAL)
											'difficulty' => 'PLACEHOLDER', // Mining network difficulty (OPTIONAL)
											'other_network_data' => 'PLACEHOLDER', // Leave blank to skip (OPTIONAL)
										),
					
					
			), // POW END
					
					
			// POS CALCULATORS (#NOT FUNCTIONAL YET#)
			'pos' => array(
			
			// CALCULATORS HERE
			
			), // POS END
					
			
); // MINING CALCULATORS END
			


// NEWS FEED (RSS) SETTINGS
// RSS feed entries to show (per-feed) on News page (without needing to click the "show more / less" link)
$ct_conf['power']['news_feed_entries_show'] = 5; // (default = 5)
////
// RSS feed entries under X days old are marked as 'new'
$ct_conf['power']['news_feed_entries_new'] = 2; // (default = 2)
////
// RSS news feeds available on the News page
$ct_conf['power']['news_feed'] = array(
    
    
    					/////////////////////////////////////////////////////
    					// STANDARD RSS #AND# ATOM FORMAT ARE SUPPORTED
    					/////////////////////////////////////////////////////

        
        				array(
            			"title" => "Blog - Binance Academy",
            			"url" => "https://api.binance.vision/api/feed"
        						),
        
        
        				array(
            			"title" => "Blog - BitcoinCore.org",
            			"url" => "https://bitcoincore.org/en/rss.xml"
        						),
        
        
        				array(
            			"title" => "Blog - BitcoinCore.org Meetings",
            			"url" => "https://bitcoincore.org/en/meetingrss.xml"
        						),
        
        
        				array(
            			"title" => "Blog - BitcoinCore.org Releases",
            			"url" => "https://bitcoincore.org/en/releasesrss.xml"
        						),
        
        
        				array(
            			"title" => "Blog - BitcoinCore.org Security",
            			"url" => "https://bitcoincore.org/en/announcements.xml"
        						),
        
        
        				array(
            			"title" => "Blog - Bitfinex",
            			"url" => "https://blog.bitfinex.com/feed/"
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
            			"title" => "Blog - Bonifida (Data, Analytics, GUIs for Serum / Solana)",
            			"url" => "https://bonfida.medium.com/feed"
        						),
        
        
        				array(
            			"title" => "Blog - Coinbase",
            			"url" => "https://medium.com/feed/the-coinbase-blog"
        						),
        
        
        				array(
            			"title" => "Blog - CoinGecko",
            			"url" => "https://blog.coingecko.com/feed/"
        						),
        
        
        				array(
            			"title" => "Blog - CoinMarketCap",
            			"url" => "https://blog.coinmarketcap.com/feed/"
        						),
        
        
        				array(
            			"title" => "Blog - ConsenSys",
            			"url" => "https://media.consensys.net/feed"
        						),
        
        
        				array(
            			"title" => "Blog - Decentralized Wireless Alliance (Helium Foundation)",
            			"url" => "https://dewialliance.medium.com/feed"
        						),
        
        
        				array(
            			"title" => "Blog - Enterprise Ethereum Alliance",
            			"url" => "https://entethalliance.org/feed/"
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
            			"title" => "Blog - Helium Network (community-driven global LoRaWAN network)",
            			"url" => "https://blog.helium.com/feed"
        						),
    
    
        				array(
            			"title" => "Blog - Kraken",
            			"url" => "https://blog.kraken.com/feed/"
        						),
        
        
        				array(
            			"title" => "Blog - LoopRing (Ethereum Layer 2 Network)",
            			"url" => "https://medium.com/feed/loopring-protocol"
        						),
    
    
        				array(
            			"title" => "Blog - OkCoin",
            			"url" => "https://blog.okcoin.com/feed/"
        						),
    
    
        				array(
            			"title" => "Blog - Open Node (Professional Bitcoin Ecommerce Merchant Services)",
            			"url" => "https://www.opennode.com/blog/feed/"
        						),
        
        
        				array(
            			"title" => "Blog - Portals (NFT-based metaverse on Solana)",
            			"url" => "https://medium.com/@portals_/feed"
        						),
        
        
        				array(
            			"title" => "Blog - Project Eluüne (NFT-based game on Solana)",
            			"url" => "https://medium.com/@Arrivant_/feed"
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
            			"title" => "Blog - Sol Invictus (Fork of OlympusDAO on Solana)",
            			"url" => "https://medium.com/@Sol-Invictus/feed"
        						),
        
        
        				array(
            			"title" => "Blog - Solana Labs (High-Speed Smart Contracts Network)",
            			"url" => "https://medium.com/feed/solana-labs"
        						),
        
        
        				array(
            			"title" => "Blog - ZkSync (Ethereum Layer 2 Network)",
            			"url" => "https://medium.com/feed/matter-labs"
        						),
        
        
        				array(
            			"title" => "News - Altcoin Buzz",
            			"url" => "https://www.altcoinbuzz.io/feed/"
        						),
        
        
        				array(
            			"title" => "News - AMB Crypto",
            			"url" => "https://investing-api-eng.ambcrypto.com/feed/merge_category"
        						),
        
        
        				array(
            			"title" => "News - Bitcoin Magazine",
            			"url" => "https://bitcoinmagazine.com/feed"
        						),
        
        
        				array(
            			"title" => "News - Bitcoinist",
            			"url" => "https://bitcoinist.com/feed/"
        						),
    					
    					
        				array(
            			"title" => "News - Box Mining",
            			"url" => "https://boxmining.com/feed/"
        						),
        
        
        				array(
            			"title" => "News - BTC Manager",
            			"url" => "https://btcmanager.com/rssfeed/"
        						),
        
        
        				array(
            			"title" => "News - CoinTelegraph",
            			"url" => "https://cointelegraph.com/feed"
        						),
        
        
        				array(
            			"title" => "News - Crypto Potato",
            			"url" => "https://cryptopotato.com/feed"
        						),
        
        
        				array(
            			"title" => "News - Crypto Mining Blog",
            			"url" => "https://cryptomining-blog.com/feed/"
        						),
        
        
        				array(
            			"title" => "News - Ethereum World News",
            			"url" => "https://en.ethereumworldnews.com/feed"
        						),
    
    
        				array(
            			"title" => "News - The Block",
            			"url" => "https://www.theblockcrypto.com/rss.xml"
        						),
    
    
        				array(
            			"title" => "News - The Merkle",
            			"url" => "https://themerkle.com/feed/"
        						),
    
    
        				array(
            			"title" => "News - Token Daily",
            			"url" => "https://www.tokendaily.co/rss"
        						),
    
    
        				array(
            			"title" => "News - What's New In Eth2",
            			"url" => "http://benjaminion.xyz/newineth2/rss_feed.xml"
        						),
    					
    					
        				array(
            			"title" => "Newsletter - Bitcoin Optech",
            			"url" => "https://bitcoinops.org/feed.xml"
        						),
    
    
        				array(
            			"title" => "Newsletter - EthHub",
            			"url" => "https://ethhub.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - Lightning Labs (Bitcoin Layer 2 Network)",
            			"url" => "https://lightninglabs.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - LoopRing (Ethereum Layer 2 Network)",
            			"url" => "https://loopring.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - Our Network",
            			"url" => "https://ournetwork.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - The Daily Gwei",
            			"url" => "https://thedailygwei.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - The Solana Grapevine",
            			"url" => "https://thesolanagrapevine.substack.com/feed"
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
            			"title" => "Podcast - Blockchain Insider",
            			"url" => "https://feeds.fireside.fm/bifeed/rss"
        						),
        
        
        				array(
            			"title" => "Podcast - Citadel Dispatch",
            			"url" => "https://anchor.fm/s/45563e80/podcast/rss"
        						),
        
        
        				array(
            			"title" => "Podcast - Citizen Bitcoin",
            			"url" => "https://feeds.simplecast.com/620_gQYv"
        						),
    
    
        				array(
            			"title" => "Podcast - Into the Ether",
            			"url" => "https://podcast.ethhub.io/rss"
        						),
    
    
        				array(
            			"title" => "Podcast - Let's Talk Bitcoin",
            			"url" => "https://letstalkbitcoin.com/rss/feed/blog"
        						),
        
        
        				array(
            			"title" => "Podcast - POV Crypto",
            			"url" => "http://povcryptopod.libsyn.com/rss"
        						),
        
        
        				array(
            			"title" => "Podcast - Proof of Talent",
            			"url" => "https://feedcdn21.podbean.com/cryptobob/feed.xml"
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
            			"title" => "Podcast - The Bitcoin Podcast Network",
            			"url" => "https://feeds.simplecast.com/xCQr3ykc"
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
            			"title" => "Reddit - Bitcoin (top)",
            			"url" => "https://www.reddit.com/r/Bitcoin/top/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - CryptoCurrency (top)",
            			"url" => "https://www.reddit.com/r/CryptoCurrency/top/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - CryptoMarkets (top)",
            			"url" => "https://www.reddit.com/r/CryptoMarkets/top/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - Ethereum (top)",
            			"url" => "https://www.reddit.com/r/Ethereum/top/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - EthFinance (top)",
            			"url" => "https://www.reddit.com/r/EthFinance/top/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - GPUMining (top)",
            			"url" => "https://www.reddit.com/r/gpumining/top/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - Helium Network (top)",
            			"url" => "https://www.reddit.com/r/heliumnetwork/top/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - Invictus DAO (top)",
            			"url" => "https://www.reddit.com/r/invictusdao/top/.rss?format=xml"
        						),
    
    
        				array(
            			"title" => "Reddit - Solana (top)",
            			"url" => "https://www.reddit.com/r/solana/top/.rss?format=xml"
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
            			"title" => "Youtube - Andreas Antonopoulos",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCJWCJCWOxBYSi5DhCieLOLQ"
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
            			"title" => "Youtube - Crypto Finally",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCzPaGwO9MY5_xUNuwHEzR4Q"
        						),
    
    
        				array(
            			"title" => "Youtube - Crypt0's News",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCdUSSt-IEUg2eq46rD7lu_g"
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
            			"title" => "Youtube - Helium Network",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCEdh5moyCkiIrfdkZOnG5ZQ"
        						),
    
    
        				array(
            			"title" => "Youtube - Ivan on Tech",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCrYmtJBtLdtm2ov84ulV-yg"
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
            			"title" => "Youtube - Naomi Brockwell",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCSuHzQ3GrHSzoBbwrIq3LLA"
        						),
    
    
        				array(
            			"title" => "Youtube - Solana Labs",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UC9AdQPUe4BdVJ8M9X7wxHUA"
        						),
    
    
        				array(
            			"title" => "Youtube - VoskCoin",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCT44w6854K62cSiwA1aiXfw"
        						),
        
        
    				);
				

////////////////////////////////////////
// !END! POWER USER CONFIGURATION
////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
// !START! DEVELOPER-ONLY CONFIGURATION, !CHANGE WITH #EXTREME# CARE, OR YOU CAN BREAK THE APP!
/////////////////////////////////////////////////////////////////////////////


// $ct_conf['dev']['debug'] enabled runs unit tests during ui runtimes (during webpage load),
// errors detected are error-logged and printed as alerts in footer
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
// 'lite_chart_telemetry' (lite chart caching),
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
// UNIT TESTS ('CHECKS' SECTION) WILL ONLY RUN DURING WEB PAGE LOAD. MAY REQUIRE SETTING MAXIMUM ALLOWED 
// PHP EXECUTION TIME TO 120 SECONDS TEMPORARILY, FOR ALL UNIT TESTS TO FULLY COMPLETE RUNNING, 
// IF YOU GET AN ERROR 500. OPTIONALLY, TRY RUNNING ONE TEST PER PAGE LOAD, TO AVOID THIS.
// DON'T LEAVE DEBUGGING ENABLED AFTER USING IT, THE /cache/logs/debug.log AND /cache/logs/debug/
// LOG FILES !CAN GROW VERY QUICKLY IN SIZE! EVEN AFTER JUST A FEW RUNTIMES
$ct_conf['dev']['debug'] = 'off'; 


// Level of detail / verbosity in log files. 'normal' logs minimal details (basic information), 
// 'verbose' logs maximum details (additional information IF AVAILABLE, for heavy debugging / tracing / etc)
// IF DEBUGGING IS ENABLED ABOVE, LOGS ARE AUTOMATICALLY VERBOSE #WITHOUT THE NEED TO ADJUST THIS SETTING#
$ct_conf['dev']['log_verb'] = 'normal'; // 'normal' / 'verbose'


// 'on' verifies ALL SMTP server certificates for secure SMTP connections, 'off' verifies NOTHING 
// Set to 'off' if the SMTP server has an invalid certificate setup (which stops email sending, but you still want to send email through that server)
$ct_conf['dev']['smtp_strict_ssl'] = 'off'; // (DEFAULT IS 'off', TO ASSURE SMTP EMAIL SENDING STILL WORKS THROUGH MISCONFIGURED SMTP SERVERS)


// 'on' verifies ALL REMOTE API server certificates for secure API connections, 'off' verifies NOTHING 
// Set to 'off' if some exchange's API servers have invalid certificates (which stops price data retrieval...but you still want to get price data from them)
$ct_conf['dev']['remote_api_strict_ssl'] = 'off'; // (default = 'off')


// Ignore warning to use PHP-FPM (#PHP-FPM HELPS PREVENT RUNTIME CRASHES# ON LOW POWER DEVICES OR HIGH TRAFFIC INSTALLS)
$ct_conf['dev']['ignore_php_fpm_warning'] = 'yes'; // (default = 'no', options are 'yes' / 'no')


// Maximum number of BATCHED coingecko marketcap data results to fetch, per API call (during multiple / paginated calls) 
$ct_conf['dev']['coingecko_api_batched_max'] = 100; // (default = 100), ADJUST WITH CARE!!!


// Maximum number of BATCHED news feed fetches / re-caches per ajax OR cron runtime 
// (#TO HELP PREVENT RUNTIME CRASHES# ON LOW POWER DEVICES OR HIGH TRAFFIC INSTALLS, WITH A LOW NUMBER OF 25 OR LESS)
$ct_conf['dev']['news_feed_batched_max'] = 25; // (default = 25), ADJUST WITH CARE!!!
////
// Minutes to cache RSS feeds for News page
// Randomly cache each RSS feed between the minimum and maximum MINUTES set here (so they don't refresh all at once, for faster runtimes)
// THE WIDER THE GAP BETWEEN THE NUMBERS, MORE SPLIT UP / FASTER THE FEEDS WILL LOAD IN THE INTERFACE
$ct_conf['dev']['news_feed_cache_min_max'] = '90,180'; // 'min,max' (default = '90,180'), ADJUST WITH CARE!!!


// Randomly rebuild the 'ALL' chart between the minimum and maximum HOURS set here  (so they don't refresh all at once, for faster runtimes)
$ct_conf['dev']['all_chart_rebuild_min_max'] = '4,12'; // 'min,max' (default = '4,12'), ADJUST WITH CARE!!!
			
			
// Configuration for advanced CAPTCHA image settings on all admin login / reset pages
$ct_conf['dev']['captcha_image_width'] = 430; // Image width (default = 430)
////
$ct_conf['dev']['captcha_image_height'] = 130; // Image height (default = 130)
////
$ct_conf['dev']['captcha_text_margin'] = 4; // MINIMUM margin of text from edge of image (approximate / average) (default = 4)
////
$ct_conf['dev']['captcha_text_size'] = 50; // Text size (default = 50)
////
$ct_conf['dev']['captcha_chars_length'] = 6; // Number of characters in captcha image (default = 6)
////
// ONLY MOST READABLE characters allowed for use in captcha image 
$ct_conf['dev']['captcha_permitted_chars'] = 'ACEFHMNPQRSTUVWXYZ2345679'; // (default = 'ACEFHMNPQRSTUVWXYZ2345679')


// Local / internal API rate limit (maximum of once every X seconds, per ip address) for accepting remote requests
// Can be 0 to disable rate limiting (unlimited)
$ct_conf['dev']['local_api_rate_limit'] = 5; // (default = 5)
////
// Local / internal API market limit (maximum number of markets requested per call)
$ct_conf['dev']['local_api_market_limit'] = 20; // (default = 20)
////
// Local / internal API cache time (minutes that previous requests are cached for)
$ct_conf['dev']['local_api_cache_time'] = 4; // (default = 4)


// If you want to override the default user agent string (sent with API requests, etc)
// Adding a string here automatically enables that as the custom user agent
// LEAVING BLANK '' USES THE DEFAULT USER AGENT LOGIC BUILT-IN TO THIS APP (INCLUDES BASIC SYSTEM CONFIGURATION STATS)
$ct_conf['dev']['override_user_agent'] = ''; 


// Default charset used
$ct_conf['dev']['charset_default'] = 'UTF-8'; 
////
// Unicode charset used (if needed)
// UCS-2 is outdated as it only covers 65536 characters of Unicode
// UTF-16BE / UTF-16LE / UTF-16 / UCS-2BE can represent ALL Unicode characters
$ct_conf['dev']['charset_unicode'] = 'UTF-16'; 


// Cache directories / files and .htaccess / index.php files permissions (CHANGE WITH #EXTREME# CARE, to adjust security for your PARTICULAR setup)
// THESE PERMISSIONS ARE !ALREADY! CALLED THROUGH THE octdec() FUNCTION WITHIN THE APP WHEN USED
// Cache directories permissions
$ct_conf['dev']['chmod_cache_dir'] = '0777'; // (default = '0777')
////
// Cache files permissions
$ct_conf['dev']['chmod_cache_file'] = '0666'; // (default = '0666')
////
// .htaccess / index.php index security files permissions
$ct_conf['dev']['chmod_index_sec'] = '0664'; // (default = '0664')
			
									
// !!!!! BE #VERY CAREFUL# LOWERING MAXIMUM EXECUTION TIMES BELOW, #OR YOU MAY CRASH THE RUNNING PROCESSES EARLY, 
// OR CAUSE MEMORY LEAKS THAT ALSO CRASH YOUR !ENTIRE SYSTEM!#
////
// Maximum execution time for interface runtime in seconds (how long it's allowed to run before automatically killing the process)
// (ALL execution times are automatically 600 IN DEBUG MODE)
$ct_conf['dev']['ui_max_exec_time'] = 120; // (default = 120)
////
// Maximum execution time for ajax runtime in seconds (how long it's allowed to run before automatically killing the process)
// (ALL execution times are automatically 600 IN DEBUG MODE)
$ct_conf['dev']['ajax_max_exec_time'] = 120; // (default = 120)
////
// Maximum execution time for cron job runtime in seconds (how long it's allowed to run before automatically killing the process)
// (ALL execution times are automatically 600 IN DEBUG MODE)
$ct_conf['dev']['cron_max_exec_time'] = 600; // (default = 600)
////
// Maximum execution time for internal API runtime in seconds (how long it's allowed to run before automatically killing the process)
// (ALL execution times are automatically 600 IN DEBUG MODE)
$ct_conf['dev']['int_api_max_exec_time'] = 90; // (default = 90)
////
// Maximum execution time for webhook runtime in seconds (how long it's allowed to run before automatically killing the process)
// (ALL execution times are automatically 600 IN DEBUG MODE)
$ct_conf['dev']['webhook_max_exec_time'] = 90; // (default = 90)
							

// TLD-only (Top Level Domain only, NO SUBDOMAINS) for each API service that UN-EFFICIENTLY requires multiple calls (for each market / data set)
// Used to throttle these market calls a tiny bit (0.15 seconds), so we don't get easily blocked / throttled by external APIs etc
// (ANY EXCHANGES LISTED HERE ARE !NOT! RECOMMENDED TO BE USED AS THE PRIMARY CURRENCY MARKET IN THIS APP,
// AS ON OCCASION THEY CAN BE !UNRELIABLE! IF HIT WITH TOO MANY SEPARATE API CALLS FOR MULTIPLE COINS / ASSETS)
// !MUST BE LOWERCASE!
$ct_conf['dev']['limited_apis'] = array(
						'bit2c.co.il',
						'bitforex.com',
						'bitflyer.com',
						'bitmex.com',
						'bitso.com',
						'bitstamp.net',
						'blockchain.info',
						'btcmarkets.net',
						'coinbase.com',
						'cryptofresh.com',
						'defipulse.com',
						'etherscan.io',
						'gemini.com',
							);


// TLD-extensions-only mapping (Top Level Domain extensions only, supported in the $ct_gen->get_tld_or_ip() function, which removes subdomains for tld checks)
// IF YOU ADD A NEW API, !MAKE SURE IT'S DOMAIN EXTENSION EXISTS HERE!
// (NO LEADING DOTS, !MUST BE LOWERCASE!)
$ct_conf['dev']['top_level_domain_map'] = array(
					'co',
					'co.il',
					'co.uk',
					'com', 
					'com.au',
					'fm',
					'info',
					'io',
					'market',
					'net',
					'net.au',
					'net.uk',
					'one',
					'org',
					'org.au',
					'org.uk',
					'pro',
					'us',
					'xyz',
					// internal / intranet / etc...
					'local', 
					'network', 
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

Below are the mobile networks supported by DFD Cryptocoin Value's email-to-mobile-text functionality. 

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

// SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// See TROUBLESHOOTING.txt for tips / troubleshooting FAQs.

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC !!!!WILL BREAK THE APP!!!!, BE CAREFUL EDITING THIS CONFIG FILE!

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
                        'beeline' => 'sms.beemail.ru',          // Russia
                        'china_mobile' => '139.com',            // China
                        'claro_pr' => 'vtexto.com',             // Puerto Rico
                        'csl' => 'mgw.mmsc1.hkcsl.com',         // Hong Kong
                        'digicel' => 'digitextdm.com',          // Dominica
                        'emtel' => 'emtelworld.net',            // Mauritius
                        'guyana_tt' => 'sms.cellinkgy.com',     // Guyana
                        'ice' => 'sms.ice.cr',                  // Costa Rica
                        'm1' => 'm1.com.sg',                    // Singapore
                        'mas_movil' => 'cwmovil.com',           // Panama
                        'mobiltel' => 'sms.mtel.net',           // Bulgaria
                        'mobitel' => 'sms.mobitel.lk',          // Sri Lanka
                        'movistar_ar' => 'sms.movistar.net.ar', // Argentina
                        'movistar_uy' => 'sms.movistar.com.uy', // Uruguay
                        'setar' => 'mas.aw',                    // Aruba
                        'sunrise_ch' => 'gsm.sunrise.ch',       // Switzerland
                        'tmobile_hr' => 'sms.t-mobile.hr',      // Croatia
                        'tele2_lv' => 'sms.tele2.lv',           // Latvia
                        'tele2_se' => 'sms.tele2.se',           // Sweden
                        'telcel' => 'itelcel.com',              // Mexico
                        'tmobile_nl' => 'gin.nl',               // Netherlands
                        'vodafone_it' => 'sms.vodafone.it',     // Italy
                        
                        
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
                        'fido' => 'sms.fido.ca',
                        'koodo' => 'msg.telus.com',
                        'lynx' => 'sms.lynxmobility.com',
                        'pc_telecom' => 'mobiletxt.ca',
                        'rogers_ca' => 'pcs.rogers.com',
                        'sasktel' => 'pcs.sasktelmobility.com',
                        'telus' => 'mms.telusmobility.com',
                        'virgin_ca' => 'vmobile.ca',
                        'wind' => 'txt.windmobile.ca',
                        
                        
                        // [COLUMBIA]
                        'claro_co' => 'iclaro.com.co',
                        'movistar_co' => 'movistar.com.co',
                        
                        
                        // [EUROPE]
                        'tellus_talk' => 'esms.nu',
                        
                        
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
                        'escotel' => 'escotelmobile.com',
                        
                        
                        // [NEW ZEALAND]
                        'telecom' => 'etxt.co.nz',
                        'vodafone_nz' => 'mtxt.co.nz',
                        
                        
                        // [NORWAY]
                        'sendega' => 'sendega.com',
                        'teletopia' => 'sms.teletopiasms.no',
                        
                        
                        // [SOUTH AFRICA]
                        'mtn' => 'sms.co.za',
                        'vodacom' => 'voda.co.za',
                        
                        
                        // [SPAIN]
                        'esendex' => 'esendex.net',
                        'movistar_es' => 'movistar.net',
                        'vodafone_es' => 'vodafone.es',
                        
                        
                        // [POLAND]
                        'orange_pl' => 'orange.pl',
                        'plus' => 'text.plusgsm.pl',
                        'polkomtel' => 'text.plusgsm.pl',
                        
                        
                        // [UNITED KINGDOM]
                        'media_burst' => 'sms.mediaburst.co.uk',
                        'txt_local' => 'txtlocal.co.uk',
                        'virgin_uk' => 'vxtras.com',
                        'vodafone_uk' => 'vodafone.net',
                        
                        
                        // [UNITED STATES]
                        'alaska_comm' => 'msg.acsalaska.com',
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
                    // (DO NOT DELETE, MISCASSETS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'MISCASSETS' => array(), 
                    // Asset END

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ETHNFTS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    // (DO NOT DELETE, ETHNFTS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'ETHNFTS' => array(), 
                    // Asset END

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SOLNFTS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    // (DO NOT DELETE, SOLNFTS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'SOLNFTS' => array(), 
                    // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // BTC (!!!!*BTC MUST BE THE VERY FIRST* IN THIS CRYPTO ASSET LIST, DO NOT DELETE, BTC IS *REQUIRED* TO RUN THIS APP!!!!)
                    'BTC' => array(
                        
                        'name' => 'Bitcoin',
                        'mcap_slug' => 'bitcoin',
                        'pairing' => array(
                        
                        
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
                                    	 	'defipulse' => 'WBTC/WETH',
                                                    ),

                                                    
                                    'eur' => array(
                                          'coinbase' => 'BTC-EUR',
                                          'kraken' => 'XXBTZEUR',
                                          'bitstamp' => 'btceur',
                                          'okcoin' => 'BTC-EUR',
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
                                          'cex' => 'BTC:RUB',
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
                                          'okcoin' => 'BTC-SGD',
                                                    ),

                                                    
                                    'thb' => array(
                                          'localbitcoins' => 'THB',
                                                    ),

                                                    
                                    'try' => array(
                                          'btcturk' => 'BTCTRY',
                                          'localbitcoins' => 'TRY',
                                                    ),

                                                    
                                    'tusd' => array(
                                          'binance' => 'BTCTUSD',
                                                    ),

                                                    
                                    'twd' => array(
                                          'localbitcoins' => 'TWD',
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
                                          'southxchange' => 'BTC/USD',
                                                    ),

                                                    
                                    'usdc' => array(
                                          'binance' => 'BTCUSDC',
                                          'kraken' => 'XBTUSDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                          'binance' => 'BTCUSDT',
                                    	 	'kraken' => 'XBTUSDT',
                                          'okcoin' => 'BTC-USDT',
                                        	'bittrex' => 'BTC-USDT',
                                          'btcturk' => 'BTCUSDT',
                                          'huobi' => 'btcusdt',
                                          'okex' => 'BTC-USDT',
                                          'bitbns' => 'BTCUSDT',
                                          'wazirx' => 'btcusdt',
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

                                                    
                        ) // pairing END
                        
                    ), // Asset END (!!!!*BTC MUST BE THE VERY FIRST* IN THIS ASSET LIST, DO NOT DELETE, BTC IS *REQUIRED* TO RUN THIS APP!!!!)
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ETH
                    'ETH' => array(
                        
                        'name' => 'Ethereum',
                        'mcap_slug' => 'ethereum',
                        'pairing' => array(

                        
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
                                          'cryptofresh' => 'OPEN.ETH',
                                          'bitso' => 'eth_btc',
                                          'zebpay' => 'ETH-BTC',
                                          'luno' => 'ETHXBT',
                                          'wazirx' => 'ethbtc',
                                    	  'defipulse' => 'WETH/WBTC',
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
                                    	 	'defipulse' => 'WETH/DAI',
                                                    ),

                                                    
                                    'eur' => array(
                                          'coinbase' => 'ETH-EUR',
                                          'kraken' => 'XETHZEUR',
                                          'bitstamp' => 'etheur',
                                          'okcoin' => 'ETH-EUR',
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
                                          'okcoin' => 'ETH-SGD',
                                                    ),

                                                    
                                    'tusd' => array(
                                          'binance' => 'ETHTUSD',
                                                    ),

                                                    
                                    'try' => array(
                                          'btcturk' => 'ETHTRY',
                                                    ),

                                                    
                                    'usd' => array(
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
                                          'binance' => 'ETHUSDC',
                                          'coinbase' => 'ETH-USDC',
                                          'kraken' => 'ETHUSDC',
                                          'kucoin' => 'ETH-USDC',
                                          'loopring_amm' => 'AMM-ETH-USDC',
                                          'poloniex' => 'USDC_ETH',
                                                    ),

                                                    
                                    'zar' => array(
                                          'luno' => 'ETHZAR',
                                                    ),

                                                    
                        ) // pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SOL
                    'SOL' => array(
                        
                        'name' => 'Solana',
                        'mcap_slug' => 'solana',
                        'pairing' => array(

                                                    
                                    'aud' => array(
                                        'binance' => 'SOLAUD',
                                                    ),

                                                    
                                    'brl' => array(
                                        'binance' => 'SOLBRL',
                                                    ),

                        
                                    'btc' => array(
                                    	'coinbase' => 'SOL-BTC',
                                        'binance' => 'SOLBTC',
                                        'huobi' => 'solbtc',
                                    	'ftx' => 'SOL/BTC',
                                    	'ftx_us' => 'SOL/BTC',
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
                                    	 'ftx' => 'SOL/USD',
                                    	 'ftx_us' => 'SOL/USD',
                                    	 'kraken' => 'SOLUSD',
                                    	 'binance_us' => 'SOLUSD',
                                    	 'bitfinex' => 'tSOLUSD',
                                         'okcoin' => 'SOL-USD',
                                         'gateio' => 'SOL_USD',
                                         'cex' => 'SOL:USD',
                                                    ),

                                                    
                                    'usdc' => array(
                                        'binance' => 'SOLUSDC',
                                    	'crypto.com' => 'SOL_USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                    	'coinbase' => 'SOL-USDT',
                                        'binance' => 'SOLUSDT',
                                    	'ftx' => 'SOL/USDT',
                                    	'ftx_us' => 'SOL/USDT',
                                        'okex' => 'SOL-USDT',
                                        'huobi' => 'solusdt',
                                    	'binance_us' => 'SOLUSDT',
                                    	'crypto.com' => 'SOL_USDT',
                                        'kucoin' => 'SOL-USDT',
                                        'hitbtc' => 'SOLUSDT',
                                        'coinex' => 'SOLUSDT',
                                        'hotbit' => 'SOL_USDT',
                                        'gateio' => 'SOL_USDT',
                                        'bitmart' => 'SOL_USDT',
                                        'wazirx' => 'solusdt',
                                                    ),

                                                    
                        ) // pairing END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // UNI
                    'UNI' => array(
                        
                        'name' => 'Uniswap',
                        'mcap_slug' => 'uniswap',
                        'pairing' => array(
                                                    
                                                    
                                    'btc' => array(
                                        'binance' => 'UNIBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'loopring_amm' => 'AMM-UNI-ETH',
                                    	 'defipulse' => 'UNI/WETH',
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

                                                    
                        ) // pairing END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MKR
                    'MKR' => array(
                        
                        'name' => 'Maker',
                        'mcap_slug' => 'maker',
                        'pairing' => array(

                        
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
                                        	'loopring_amm' => 'AMM-MKR-ETH',
                                        	'hitbtc' => 'MKRETH',
                                          'gateio' => 'MKR_ETH',
                                    	 	'defipulse' => 'MKR/WETH',
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

                                          			
                        ) // pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // DAI
                    'DAI' => array(
                        
                        'name' => 'Dai',
                        'mcap_slug' => 'dai',
                        'pairing' => array(

                        
                                    'btc' => array(
                                        'bittrex' => 'DAI-BTC',
                                        'upbit' => 'BTC-DAI',
                                        'bitfinex' => 'tDAIBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                         'bittrex' => 'DAI-ETH',
                                    	 'bitfinex' => 'tDAIETH',
                                    	 'defipulse' => 'DAI/WETH',
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
                                        'okcoin' => 'DAI-USD',
                                    	'bitfinex' => 'tDAIUSD',
                                        'bittrex' => 'DAI-USD',
                                        'gemini' => 'daiusd',
                                                    ),

                                                    
                                    'usdc' => array(
                                    	'coinbase' => 'DAI-USDC',
                                        'hitbtc' => 'DAIUSDC',
                                    	'defipulse' => 'DAI/USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                    	'kraken' => 'DAIUSDT',
                                        'bittrex' => 'DAI-USDT',
                                        'okex' => 'DAI-USDT',
                                        'loopring' => 'DAI-USDT',
                                    	'defipulse' => 'DAI/USDT',
                                                    ),

                                                    
                        ) // pairing END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // USDC
                    'USDC' => array(
                        
                        'name' => 'USD Coin',
                        'mcap_slug' => 'usd-coin',
                        'pairing' => array(

                                                    
                                    'eur' => array(
                                    	 'coinbase' => 'USDC-EUR',
                                    	 'kraken' => 'USDCEUR',
                                                    ),

                                                    
                                    'gbp' => array(
                                    	 'coinbase' => 'USDC-GBP',
                                    	 'kraken' => 'USDCGBP',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'kraken' => 'USDCUSD',
                                    	 'binance_us' => 'USDCUSD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'binance' => 'USDCUSDT',
                                    	'kraken' => 'USDCUSDT',
                                        'huobi' => 'usdcusdt',
                                        'kucoin' => 'USDC-USDT',
                                                    ),

                                                    
                        ) // pairing END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MANA
                    'MANA' => array(
                        
                        'name' => 'Decentraland',
                        'mcap_slug' => 'decentraland',
                        'pairing' => array(

                        
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
                                    	  'defipulse' => 'MANA/WETH||0x11b1f53204d03e5529f09eb3091939e4fd8c9cf3',
                                                    ),

                                                    
                                    'krw' => array(
                                        	'upbit' => 'KRW-MANA',
                                                    ),

                                                    
                                    'mxn' => array(
                                          'bitso' => 'mana_mxn',
                                                    ),

                                                    
                                    'usdc' => array(
                                          'coinbase' => 'MANA-USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                          'hitbtc' => 'MANAUSD',
                                          'okex' => 'MANA-USDT',
                                                    ),

                                                    
                        ) // pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ENJ
                    'ENJ' => array(
                        
                        'name' => 'Enjin Coin',
                        'mcap_slug' => 'enjin-coin',
                        'pairing' => array(

                        
                                    'btc' => array(
                                          'binance' => 'ENJBTC',
                                          'bittrex' => 'ENJ-BTC',
                                          'hitbtc' => 'ENJBTC',
                                          'kucoin' => 'ENJ-BTC',
                                          'coinex' => 'ENJBTC',
                                          'liquid' => 'ENJBTC',
                                          'upbit' => 'BTC-ENJ',
                                                    ),

                                                    
                                    'eth' => array(
                                          'binance' => 'ENJETH',
                                          'bittrex' => 'ENJ-ETH',
                                          'hitbtc' => 'ENJETH',
                                          'kucoin' => 'ENJ-ETH',
                                          'loopring_amm' => 'AMM-ENJ-ETH',
                                    	  'defipulse' => 'ENJ/WETH',
                                                    ),

                                                    
                                    'krw' => array(
                                        	'upbit' => 'KRW-ENJ',
                                                    ),

                                                    
                                    'usd' => array(
                                        	'binance_us' => 'ENJUSD',
                                            'bittrex' => 'ENJ-USD',
                                        	'bitfinex' => 'tENJUSD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        	'binance' => 'ENJUSDT',
                                            'bittrex' => 'ENJ-USDT',
                                            'hitbtc' => 'ENJUSD',
                                            'coinex' => 'ENJUSDT',
                                                    ),

                                                    
                        ) // pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // RNDR
                    'RNDR' => array(
                        
                        'name' => 'Render Token',
                        'mcap_slug' => 'render-token',
                        'pairing' => array(

                                                    
                                    'btc' => array(
                                        'huobi' => 'rndrbtc',
                                        'kucoin' => 'RNDR-BTC',
                                        'hitbtc' => 'RNDRBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'huobi' => 'rndreth',
                                        'gateio' => 'RNDR_ETH',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'huobi' => 'rndrusdt',
                                        'gateio' => 'RNDR_USDT',
                                        'kucoin' => 'RNDR-USDT',
                                        'hotbit' => 'RNDR_USDT',
                                        'coinex' => 'RNDRUSDT',
                                        'hitbtc' => 'RNDRUSDT',
                                                    ),

                                                    
                        ) // pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // LRC
                    'LRC' => array(
                        
                        'name' => 'Loopring',
                        'mcap_slug' => 'loopring',
                        'pairing' => array(

                        
                                    'btc' => array(
                                        'binance' => 'LRCBTC',
                                    	'coinbase' => 'LRC-BTC',
                                        'bittrex' => 'LRC-BTC',
                                        'okex' => 'LRC-BTC',
                                        'huobi' => 'lrcbtc',
                                        'upbit' => 'BTC-LRC',
                                        'loopring_amm' => 'AMM-LRC-WBTC',
                                        'hitbtc' => 'LRCBTC',
                                        'gateio' => 'LRC_BTC',
                                        'coinex' => 'LRCBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'binance' => 'LRCETH',
                                        'huobi' => 'lrceth',
                                        'loopring' => 'LRC-ETH',
                                        'hitbtc' => 'LRCETH',
                                        'gateio' => 'LRC_ETH',
                                    	'defipulse' => 'LRC/WETH||0x8878df9e1a7c87dcbf6d3999d997f262c05d8c70',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'coinbase' => 'LRC-USD',
                                         'bittrex' => 'LRC-USD',
                                    	 'bitfinex' => 'tLRCUSD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'binance' => 'LRCUSDT',
                                        'okex' => 'LRC-USDT',
                                        'huobi' => 'lrcusdt',
                                        'kucoin' => 'LRC-USDT',
                                        'loopring' => 'LRC-USDT',
                                        'gateio' => 'LRC_USDT',
                                        'coinex' => 'LRCUSDT',
                                        'wazirx' => 'lrcusdt',
                                                    ),

                                                    
                        ) // pairing END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // RAY
                    'RAY' => array(
                        
                        'name' => 'Raydium',
                        'mcap_slug' => 'raydium',
                        'pairing' => array(

                        
                                    'btc' => array(
                                        'generic_btc' => 'ray',
                                                    ),

                                                    
                                    'eth' => array(
                                        'gateio' => 'RAY_ETH',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'RAYEUR',
                                                    ),

                                                    
                                    'usd' => array(
                                    	'ftx' => 'RAY/USD',
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

                                                    
                        ) // pairing END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SRM
                    'SRM' => array(
                        
                        'name' => 'Serum',
                        'mcap_slug' => 'serum',
                        'pairing' => array(

                        
                                    'btc' => array(
                                        'binance' => 'SRMBTC',
                                        'kraken' => 'SRMXBT',
                                        'okex' => 'SRM-BTC',
                                        'huobi' => 'srmbtc',
                                        'kucoin' => 'SRM-BTC',
                                        'upbit' => 'BTC-SRM',
                                        'hitbtc' => 'SRMBTC',
                                        'poloniex' => 'BTC_SRM',
                                                    ),

                                                    
                                    'eth' => array(
                                        'gateio' => 'SRM_ETH',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'SRMEUR',
                                                    ),

                                                    
                                    'gbp' => array(
                                    	'kraken' => 'SRMGBP',
                                                    ),

                                                    
                                    'krw' => array(
                                          'upbit' => 'KRW-SRM',
                                                    ),

                                                    
                                    'usd' => array(
                                    	'ftx' => 'SRM/USD',
                                    	'kraken' => 'SRMUSD',
                                        'gateio' => 'SRM_USD',
                                    	'bitfinex' => 'tSRMUSD',
                                        'cex' => 'SRM:USD',
                                                    ),

                                                    
                                    'usdc' => array(
                                    	'crypto.com' => 'SRM_USDC',
                                        'poloniex' => 'USDC_SRM',
                                        'coinex' => 'SRMUSDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'binance' => 'SRMUSDT',
                                        'okex' => 'SRM-USDT',
                                        'huobi' => 'srmusdt',
                                        'kucoin' => 'SRM-USDT',
                                    	'bitfinex' => 'tSRMUSDT',
                                        'poloniex' => 'USDT_SRM',
                                        'coinex' => 'SRMUSDT',
                                        'hitbtc' => 'SRMUSDT',
                                        'hotbit' => 'SRM_USDT',
                                        'gateio' => 'SRM_USDT',
                                        'wazirx' => 'srmusdt',
                                        'bitmart' => 'SRM_USDT',
                                        'cex' => 'SRM:USDT',
                                                    ),

                                                    
                        ) // pairing END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SLRS
                    'SLRS' => array(
                        
                        'name' => 'Solrise Finance',
                        'mcap_slug' => 'solrise-finance',
                        'pairing' => array(

                        
                                    'eth' => array(
                                        'gateio' => 'SLRS_ETH',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'ftx' => 'SLRS/USD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'gateio' => 'SLRS_USDT',
                                        'coinex' => 'SLRSUSDT',
                                                    ),

                                                    
                        ) // pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // IN
                    'IN' => array(
                        
                        'name' => 'Invictus',
                        'mcap_slug' => 'invictus',
                        'pairing' => array(

                                                    
                                    'btc' => array(
                                          'generic_btc' => 'in',
                                                    ),

                                                    
                                    'usd' => array(
                                          'generic_usd' => 'in',
                                                    ),

                                                    
                        ) // pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // HNT
                    'HNT' => array(
                        
                        'name' => 'Helium',
                        'mcap_slug' => 'helium',
                        'pairing' => array(

                        
                                    'btc' => array(
                                        'binance' => 'HNTBTC',
                                        'hotbit' => 'HNT_BTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'gateio' => 'HNT_ETH',
                                                    ),

                                                    
                                    'inr' => array(
                                        'wazirx' => 'hntinr',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'ftx' => 'HNT/USD',
                                    	 'binance_us' => 'HNTUSD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'binance' => 'HNTUSDT',
                                    	'ftx' => 'HNT/USDT',
                                    	'binance_us' => 'HNTUSDT',
                                    	'crypto.com' => 'HNT_USDT',
                                        'hotbit' => 'HNT_USDT',
                                        'gateio' => 'HNT_USDT',
                                        'wazirx' => 'hntusdt',
                                                    ),

                                                    
                        ) // pairing END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // HIVE
                    'HIVE' => array(
                        
                        'name' => 'Hive',
                        'mcap_slug' => 'hive-blockchain',
                        'pairing' => array(

                        
                                    'btc' => array(
                                        'binance' => 'HIVEBTC',
                                        'bittrex' => 'HIVE-BTC',
                                        'huobi' => 'hivebtc',
                                        'hotbit' => 'HIVE_BTC',
                                                    ),

                        
                                    'usdt' => array(
                                        'huobi' => 'hiveusdt',
                                        'hotbit' => 'HIVE_USDT',
                                        'wazirx' => 'hiveusdt',
                                                    ),

                                                    
                        ) // pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MYST
                    'MYST' => array(
                        
                        'name' => 'Mysterium',
                        'mcap_slug' => 'mysterium',
                        'pairing' => array(

                        
                                    'btc' => array(
                                          'hitbtc' => 'MYSTBTC',
                                        	'bittrex_global' => 'MYST-BTC',
                                                    ),

                                                    
                                    'eth' => array(
                                          'hitbtc' => 'MYSTETH',
                                    	 	'defipulse' => 'MYST/WETH||0x5c56bf84dcbb1d3f9646528a68520b7e21791ddd',
                                                    ),

                                                    
                                    'usdt' => array(
                                        	'bittrex_global' => 'MYST-USDT',
                                                    ),

                                                    
                        ) // pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SAMO
                    'SAMO' => array(
                        
                        'name' => 'Samoyedcoin',
                        'mcap_slug' => 'samoyedcoin',
                        'pairing' => array(

                        
                                    'eth' => array(
                                        'gateio' => 'SAMO_ETH',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'okex' => 'SAMO-USDT',
                                        'gateio' => 'SAMO_USDT',
                                                    ),

                                                    
                        ) // pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SG
                    'SG' => array(
                        
                        'name' => 'SocialGood',
                        'mcap_slug' => 'socialgood',
                        'pairing' => array(

                        
                                    'btc' => array(
                                          'bitmart' => 'SG_BTC',
                                                    ),

                                                    
                                    'usdt' => array(
                                          'bittrex_global' => 'SG-USDT',
                                          'bitmart' => 'SG_USDT',
                                                    ),

                                                    
                        ) // pairing END
                        
                    ), // Asset END
                    
                
                    ////////////////////////////////////////////////////////////////////
                
                
); // All assets END



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PORTFOLIO ASSETS CONFIGURATION -END- //////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////



// !!! WHEN RE-CONFIGURING APP, LEAVE THIS CODE BELOW HERE, DON'T DELETE BELOW THESE LINES !!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
///////////////////////////////////////////////////////////////////////////////////////////////
require_once("app-lib/php/init.php"); // REQUIRED, DON'T DELETE BY ACCIDENT
///////////////////////////////////////////////////////////////////////////////////////////////
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


?>
