<?php

/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
///////////////////////////////////////////////////////////////////////////////////////////////
// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
header('HTTP/1.0 403 Forbidden', TRUE, 403);
exit;
}
error_reporting(0); // Turn off all PHP error reporting on production servers (0), or enable (1)
//apc_clear_cache(); apcu_clear_cache(); opcache_reset();  // DEBUGGING ONLY
$app_config = array(); // REQUIRED, DON'T DELETE BY ACCIDENT
// WHEN RE-CONFIGURING APP, LEAVE THIS CODE ABOVE HERE, DON'T DELETE ABOVE THESE LINES
///////////////////////////////////////////////////////////////////////////////////////////////
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PRIMARY CONFIGURATIONS -START- ////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG

// SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// See /DOCUMENTATION-ETC/HELP-FAQ.txt for tips / troubleshooting FAQs.

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC !!!!WILL BREAK THE APP!!!!, BE CAREFUL EDITING THIS CONFIG FILE


////////////////////////////////////////
// !START! COMMUNICATIONS CONFIGURATION
////////////////////////////////////////


// Enable / disable daily upgrade checks / alerts (DEFAULT: WEB INTERFACE ONLY)
// (Checks latest release version via github.com API endpoint value "tag_name" 
// @ https://api.github.com/repos/taoteh1221/DFD_Cryptocoin_Values/releases/latest)
// Choosing 'all' will send to all properly-configured communication channels, and automatically skip any not properly setup
$app_config['comms']['upgrade_alert'] = 'ui'; // 'off' (disabled) / 'all' / 'ui' (web interface) / 'email' / 'text' / 'notifyme' / 'telegram'
////
// Wait X days between upgrade reminders
$app_config['comms']['upgrade_alert_reminder'] = 7; // (only used if upgrade check is enabled above)


// IF SMTP EMAIL SENDING --NOT-- USED, FROM email should be REAL address on the website domain, or risk having email sent to junk folder
// IF SMTP EMAIL SENDING --IS-- USED, FROM EMAIL MUST MATCH EMAIL ADDRESS associated with SMTP login (SMTP Email configuration is below this setting)
$app_config['comms']['from_email'] = ''; // #SHOULD BE SET# to avoid email going to spam / junk
////
$app_config['comms']['to_email'] = ''; // #MUST BE SET# for price alerts and other email features


// OPTIONALLY use SMTP authentication TO SEND EMAIL, if you have no reverse lookup that matches domain name (on your home network etc)
// !!USE A THROWAWAY ACCOUNT ONLY!! If web server is hacked, HACKER WOULD THEN HAVE ACCESS YOUR EMAIL LOGIN FROM THIS FILE!!
// If SMTP credentials / configuration is filled in, BUT not setup properly, APP EMAILING WILL FAIL
// CAN BE BLANK (PHP's built-in mail function will be automatically used to send email instead)
$app_config['comms']['smtp_login'] = ''; //  CAN BE BLANK. This format MUST be used: 'username||password'
////
// Examples: 'example.com:25' (non-encrypted), 'example.com:465' (ssl-encrypted), 'example.com:587' (tls-encrypted)
$app_config['comms']['smtp_server'] = ''; // CAN BE BLANK. This format MUST be used: 'domain_or_ip:port_number' 


// For alert texts to mobile phone numbers. 
// Attempts to email the text if a SUPPORTED MOBILE TEXTING NETWORK name is set, AND no textbelt / textlocal config is setup.
// SMTP-authenticated email sending MAY GET THROUGH TEXTING SERVICE CONTENT FILTERS #BETTER# THAN USING PHP'S BUILT-IN EMAILING FUNCTION
// SEE FURTHER DOWN IN THIS CONFIG FILE, FOR A LIST OF SUPPORTED MOBILE TEXTING NETWORK PROVIDER NAMES 
// IN THE EMAIL-TO-MOBILE-TEXT CONFIG SECTION (the "network name keys" in the $app_config['mobile_network_text_gateways'] variables array)
// CAN BE BLANK. Country code format MAY NEED TO BE USED (depending on your mobile network)
// skip_network_name SHOULD BE USED IF USING textbelt / textlocal BELOW
// 'phone_number||network_name_key' (examples: '12223334444||virgin_us' / '12223334444||skip_network_name')
$app_config['comms']['to_mobile_text'] = '';


// Do NOT use textbelt AND textlocal together. Leave one setting blank, OR IT WILL DISABLE USING BOTH.
// LEAVE textbelt AND textlocal BOTH BLANK to use a mobile text gateway set ABOVE

// CAN BE BLANK. For asset price alert textbelt notifications. Setup: https://textbelt.com/
$app_config['comms']['textbelt_apikey'] = '';


// CAN BE BLANK. For asset price alert textlocal notifications. Setup: https://www.textlocal.com/integrations/api/
$app_config['comms']['textlocal_account'] = ''; // This format MUST be used: 'username||hash_code'


// For notifyme / alexa notifications (sending Alexa devices notifications for free). 
// CAN BE BLANK. Setup: http://www.thomptronics.com/notify-me
// (NOTE: THIS APP'S BUILT-IN QUEUE SYSTEM THROTTLES / SENDS OUT 6 ALERTS EVERY 6 MINUTES MAXIMUM FOR NOTIFYME ALERTS,
// TO STAY WITHIN NOTIFYME API MESSAGE LIMITS, SO YOU ALWAYS STILL GET ALL YOUR ALERTS, JUST SLIGHTLY DELAYED)
$app_config['comms']['notifyme_accesscode'] = '';


// Google Home alert configuration (WORK IN PROGRESS, !!NOT FUNCTIONAL!!)
// CAN BE BLANK. Setup: https://developers.google.com/assistant/engagement/notifications
$app_config['comms']['google_application_name'] = '';
////
$app_config['comms']['google_client_id'] = '';
////
$app_config['comms']['google_client_secret'] = '';


// Sending alerts to your own telegram bot chatroom. 
// (USEFUL IF YOU HAVE ISSUES SETTING UP MOBILE TEXT ALERTS, INCLUDING EMOJI / UNICODE CHARACTER ENCODING)
// Setup: https://core.telegram.org/bots , OR JUST SEARCH / VISIT "BotFather" in the telegram app
// YOU MUST SETUP A TELEGRAM USERNAME #FIRST / BEFORE SETTING UP THE BOT#, IF YOU HAVEN'T ALREADY (IN THE TELEGRAM APP SETTINGS)
// SET UP YOUR BOT WITH "BotFather", AND SAVE YOUR BOT NAME / USERNAME / ACCESS TOKEN / BOT CHATROOM LINK
// VISIT THE BOT CHATROOM, #SEND THE MESSAGE "/start" TO THIS CHATROOM# (THIS WILL CREATE USER CHAT DATA THE APP NEEDS)
// THE USER CHAT DATA #IS REQUIRED# FOR THIS APP TO INITIALLY DETERMINE AND SECURELY SAVE YOU TELEGRAM USER'S CHAT ID
// #DO NOT DELETE THE BOT CHATROOM IN THE TELEGRAM APP, OR YOU WILL STOP RECEIVING MESSAGES FROM THE BOT#
$app_config['comms']['telegram_your_username'] = ''; // Your telegram username (REQUIRED, setup in telegram app settings)
////
$app_config['comms']['telegram_bot_username'] = '';  // Your bot's username
////
$app_config['comms']['telegram_bot_name'] = ''; // Your bot's human-readable name (example: 'My Alerts Bot')
////
$app_config['comms']['telegram_bot_token'] = '';  // Your bot's access token


// PRICE ALERTS SETUP REQUIRES A CRON JOB RUNNING ON YOUR WEB SERVER (see README.txt for cron job setup information) 
// Price alerts will send to all properly-configured communication channels, and automatically skip any not properly setup
// Price percent change to send alerts for (WITHOUT percent sign: 15.75 = 15.75%). Sends alerts when percent change reached (up or down)
$app_config['comms']['price_alerts_threshold'] = 9.25; // CAN BE 0 TO DISABLE PRICE ALERTS
////
// Re-allow SAME asset price alert(s) messages after X hours (per asset, set higher if issues with sent to junk folder / API blocking or throttling...can be 0)
// Price alerts AUTOMATICALLY will send to all properly-configured communication channels, and automatically skip any not properly setup
$app_config['comms']['price_alerts_freq_max'] = 2; 
////
// Block an asset price alert if price retrieved, BUT failed retrieving pair volume (not even a zero was retrieved, nothing)
// Good for blocking questionable exchanges bugging you with price alerts, especially when used in combination with the minimum volume filter
$app_config['comms']['price_alerts_block_volume_error'] = 'on'; // 'on' / 'off' 
////
// Minimum 24 hour volume filter. Only allows sending price alerts if minimum 24 hour volume reached
// CAN BE 0 TO DISABLE MINIMUM VOLUME FILTERING, NO DECIMALS OR SEPARATORS, NUMBERS ONLY, WITHOUT the [primary currency] prefix symbol: 4500 = $4,500 , 30000 = $30,000 , etc
// THIS FILTER WILL AUTO-DISABLE IF THERE IS ANY ERROR RETRIEVING DATA ON A CERTAIN MARKET (WHEN NOT EVEN A ZERO IS RECEIVED)
$app_config['comms']['price_alerts_min_volume'] = 3500;


// Alerts for failed proxy data connections (#ONLY USED# IF proxies are enabled further down in PROXY CONFIGURATION). 
// Choosing 'all' will send to all properly-configured communication channels, and automatically skip any not properly setup
$app_config['comms']['proxy_alerts'] = 'email'; // 'off' (disabled) / 'all' / 'email' / 'text' / 'notifyme' / 'telegram'
////
$app_config['comms']['proxy_alerts_freq_max'] = 1; // Re-allow same proxy alert(s) after X hours (per ip/port pair, can be 0)
////
$app_config['comms']['proxy_alerts_runtime'] = 'cron'; // Which runtime mode should allow proxy alerts? Options: 'cron', 'ui', 'all' 
////
// 'include' or 'ignore' proxy alerts, if proxy checkup went OK? (after flagged, started working again when checked)
$app_config['comms']['proxy_alerts_checkup_ok'] = 'include'; 


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
$app_config['general']['interface_login'] = ''; // Leave blank to disable requiring an interface login. This format MUST be used: 'username||password'


// Password protection / encryption security for backup archives (REQUIRED for app config backup archives, NOT USED FOR CHART BACKUPS)
$app_config['general']['backup_archive_password'] = ''; // LEAVE BLANK TO DISABLE


// API key for etherscan.io (required unfortunately, but a FREE level is available): https://etherscan.io/apis
$app_config['general']['etherscanio_api_key'] = '';


// API key for coinmarketcap.com Pro API (required unfortunately, but a FREE level is available): https://coinmarketcap.com/api
$app_config['general']['coinmarketcapcom_api_key'] = '';


// API key for defipulse.com API (required unfortunately, but a FREE level is available): https://data.defipulse.com/
$app_config['general']['defipulsecom_api_key'] = '';


// Default BITCOIN market currencies (80+ currencies supported)
// (set for default Bitcoin market, and charts / price alert primary-currency-equivalent value determination [example: usd value of btc/ltc market, etc])
// aed / ars / aud / bam / bdt / bob / brl / bwp / byn / cad / chf / clp / cny / cop / crc / czk / dai / dkk / dop
// egp / eth / eur / gbp / gel / ghs / gtq / hkd / huf / idr / ils / inr / irr / jmd / jod / jpy / kes / krw / kwd 
// kzt / lkr / mad / mur / mwk / mxn / myr / ngn / nis / nok / nzd / pab / pen / php / pkr / pln / pyg / qar / ron 
// rsd / rub / rwf / sar / sek / sgd / thb / try / tusd / twd / tzs / uah / ugx / usdc / usdt / uyu / ves / vnd / xaf / xof / zar / zmw
// SEE THE $app_config['portfolio_assets'] CONFIGURATION NEAR THE BOTTOM OF THIS CONFIG FILE, FOR THE PROPER (CORRESPONDING)
// CURRENCY PAIRING VALUE NEEDED FOR YOUR CHOSEN 'BTC' EXCHANGE (set in $app_config['general']['btc_primary_exchange'] directly below)
$app_config['general']['btc_primary_currency_pairing'] = 'usd'; // PUT INSIDE SINGLE QUOTES ('selection')


// Default BITCOIN market exchanges (30+ bitcoin exchanges supported)
// (set for default Bitcoin market, and charts / price alert primary-currency-equivalent value determination [example: usd value of btc/ltc market, etc])
// binance / binance_us / bit2c / bitbns / bitfinex / bitflyer / bitmex / bitpanda / bitso / bitstamp / bittrex 
// bittrex_global / braziliex / btcmarkets / btcturk / buyucoin / cex / coinbase / gemini / hitbtc / huobi 
// korbit / kraken / kucoin / liquid / livecoin / localbitcoins / luno / okcoin / okex / southxchange / upbit / wazirx
// SEE THE $app_config['portfolio_assets'] CONFIGURATION NEAR THE BOTTOM OF THIS CONFIG FILE, FOR THE PROPER (CORRESPONDING)
// MARKET PAIRING VALUE NEEDED FOR YOUR CHOSEN 'BTC' EXCHANGE (to populate $app_config['general']['btc_primary_currency_pairing'] directly above with)
// SEE THE $app_config['developer']['limited_apis'] SETTING MUCH FURTHER DOWN, FOR EXCHANGES !NOT RECOMMENDED FOR USAGE HERE!
$app_config['general']['btc_primary_exchange'] = 'kraken';  // PUT INSIDE SINGLE QUOTES ('selection')


// Maximum decimal places for [primary currency] values,
// of coins worth under 'primary_currency_decimals_max_threshold' [usd/gbp/eur/jpy/brl/rub/etc] (below this setting),
// for prettier / less-cluttered interface. IF YOU ADJUST $app_config['general']['btc_primary_currency_pairing'] ABOVE, 
// YOU MAY NEED TO ADJUST THIS ACCORDINGLY FOR !PRETTY / FUNCTIONAL! CHARTS / ALERTS FOR YOUR PRIMARY CURRENCY
// KEEP THIS NUMBER AS LOW AS IS FEASIBLE, TO SAVE ON CHART DATA STORAGE SPACE / MAINTAIN QUICK CHART LOAD TIMES
$app_config['general']['primary_currency_decimals_max'] = 5; // Whole numbers only (represents number of decimals maximum to use)
////
// Below what currency amount do we switch from 2 decimals, over to using the above 'primary_currency_decimals_max' setting
$app_config['general']['primary_currency_decimals_max_threshold'] = 0.70; // Can be decimals, NO SYMBOLS, NUMBERS ONLY


// ENABLING CHARTS REQUIRES A CRON JOB SETUP (see README.txt for cron job setup information)
// Enables a charts tab / page, and caches real-time updated historical chart data on your device's storage drive
// Disabling will disable EVERYTHING related to the price charts (price charts tab / page, and price chart data caching)
$app_config['general']['asset_charts_toggle'] = 'on'; // 'on' / 'off'


// Your local time offset IN HOURS, COMPARED TO UTC TIME. Can be negative or positive.
// (Used for user experience 'pretty' timestamping in interface logic ONLY, WILL NOT change or screw up UTC log times etc if you change this)
$app_config['general']['local_time_offset'] = -5; // example: -5 or 5


// Configure which interface theme you want as the default theme (also can be manually switched later, on the settings page in the interface)
$app_config['general']['default_theme'] = 'dark'; // 'dark' or 'light'


// Default marketcap data source: 'coingecko', or 'coinmarketcap' (COINMARKETCAP REQUIRES A #FREE# API KEY, see $app_config['general']['coinmarketcapcom_api_key'] above)
$app_config['general']['primary_marketcap_site'] = 'coingecko'; 


$app_config['general']['margin_leverage_max'] = 150; // Maximum margin leverage available in the user interface ('Update' page, etc)


////////////////////////////////////////
// !END! GENERAL CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! PROXY CONFIGURATION
////////////////////////////////////////


// If using proxies and login is required
// Adding a user / pass here will automatically send login details for proxy connections
// CAN BE BLANK. IF using ip address authentication instead, MUST BE LEFT BLANK
$app_config['proxy']['proxy_login'] = ''; // Use format: 'username||password'
////
// If using proxies, add the ip address / port number here for each one, like examples below (without the double slashes in front enables the code)
// CAN BE BLANK. Adding proxies here will automatically choose one randomly for each API request
$app_config['proxy']['proxy_list'] = array(
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
// Only used if $app_config['charts_alerts']['tracked_markets'] is filled in properly below, AND a cron job is setup (see README.txt for cron job setup information) 
////
// Fixed time interval RESET of cached comparison asset prices every X days (since last price reset / alert) with the current latest spot prices
// Can be 0 to disable fixed time interval resetting (IN WHICH CASE RESETS WILL ONLY OCCUR DYNAMICALLY when the next price alert is triggered / sent out)
$app_config['charts_alerts']['price_alerts_fixed_reset'] = 0; // (default = 0)
////
// Whale alert (adds "WHALE ALERT" to beginning of alexa / google home / email / telegram alert text, and spouting whale emoji to email / text / telegram)
// Format: 'max_days_to_24hr_average_over||min_price_percent_change_24hr_average||min_volume_percent_increase_24hr_average||min_volume_currency_increase_24hr_average'
// ("min_price_percent_change_24hr_average" should be the same value or higher as $app_config['comms']['price_alerts_threshold'] to work properly)
// Leave BLANK '' TO DISABLE. DECIMALS ARE SUPPORTED, USE NUMBERS ONLY (NO CURRENCY SYMBOLS / COMMAS, ETC)
$app_config['charts_alerts']['price_alerts_whale_alert_threshold'] = '1.65||8.85||9.1||16000'; // (default: '1.65||8.85||9.1||16000')
////
// Markets you want charts or asset price change alerts for (alerts sent when default [primary currency] 
// [$app_config['general']['btc_primary_currency_pairing'] at top of this config] value change is equal to or above / below $app_config['comms']['price_alerts_threshold']) 
// NOTE: This list must only contain assets / exchanges / trading pairs included in the primary portfolio assets list configuration further down in this config file
// TO ADD MULTIPLE CHARTS / ALERTS FOR SAME ASSET (FOR DIFFERENT EXCHANGES / TRADE PAIRINGS), FORMAT LIKE SO: symbol, symbol-1, symbol-2, symbol-3, etc.
// TO DISABLE CHART AND ALERT = none, TO ENABLE CHART AND ALERT = both, TO ENABLE CHART ONLY = chart, TO ENABLE ALERT ONLY = alert
$app_config['charts_alerts']['tracked_markets'] = array(


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
					'eth-15' => 'defipulse||usdt||none',
					
					
					// XMR
					'xmr' => 'bittrex||btc||chart',
					'xmr-2' => 'bittrex||eth||none',
					'xmr-4' => 'binance||btc||both',
					'xmr-5' => 'binance||eth||none',
					
					
					// MKR
					'mkr' => 'okex||btc||none',
					'mkr-2' => 'kucoin||btc||none',
					'mkr-3' => 'coinbase||btc||both',
					'mkr-4' => 'defipulse||eth||none',
					
					
					//DAI
					'dai' => 'coinbase||usdc||both',
					'dai-2' => 'kraken||usd||none',
					'dai-3' => 'bittrex||btc||none',
					'dai-4' => 'defipulse||usdc||none',
					
					
					// UNI
					'uni' => 'binance||btc||both',
					'uni-2' => 'defipulse||eth||none',
					
					
					// MANA
					'mana' => 'bittrex||btc||chart',
					'mana-2' => 'binance||btc||both',
					'mana-3' => 'kucoin||btc||none',
					'mana-4' => 'ethfinex||btc||none',
					
					
					// ANT
					'ant' => 'bittrex_global||btc||both',
					'ant-2' => 'hitbtc||btc||chart',
					'ant-3' => 'ethfinex||btc||none',
					'ant-4' => 'defipulse||eth||none',
					
					
					// GLM
					'glm' => 'bittrex||btc||both',
					'glm-2' => 'ethfinex||btc||chart',
					
					
					// DCR
					'dcr' => 'bittrex||btc||chart',
					'dcr-2' => 'bittrex||usdt||none',
					'dcr-3' => 'binance||btc||both',
					'dcr-4' => 'kucoin||btc||none',
					'dcr-5' => 'kucoin||eth||none',
					
					
					// HIVE
					'hive' => 'bittrex||btc||both',
					
					
					// SXP
					'sxp' => 'bittrex_global||btc||none',
					'sxp-2' => 'kucoin||btc||both',
					'sxp-3' => 'binance||btc||none',
					
					
					// ENJ
					'enj' => 'bittrex||btc||none',
					'enj-2' => 'binance||btc||both',
					'enj-3' => 'kucoin||btc||none',
					'enj-4' => 'bitfinex||usd||none',
					
					
					// DATA
					'data' => 'hitbtc||btc||chart',
					'data-2' => 'binance||btc||chart',
					
					
					//MYST
					'myst' => 'hitbtc||btc||both',
					'myst-2' => 'hitbtc||eth||none',
					
					
					);
					
// END $app_config['charts_alerts']['tracked_markets']


////////////////////////////////////////
// !END! CHART AND PRICE ALERT CONFIGURATION
////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
// !START! POWER USER CONFIGURATION (ADJUST WITH CARE, OR YOU CAN BREAK THE APP!)
/////////////////////////////////////////////////////////////////////////////


// Activate any custom plugins you've created (that run from the /plugins/ directory)
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt for creating your own custom plugins
// ADD ANY NEW PLUGIN HERE BY USING THE FOLDER NAME THE NEW PLUGIN IS LOCATED IN
// !!NEVER ADD A PLUGIN SOMEBODY ELSE WROTE, UNLESS YOU OR SOMEONE YOU TRUST 
// HAVE REVIEWED THE CODE AND ARE ABSOLUTELY SURE IT IS NOT MALICIOUS!!
// PLUGINS *MAY REQUIRE* A CRON JOB RUNNING ON YOUR WEB SERVER (if they are configured to...see README.txt for cron job setup information)
// CHANGE 'off' to 'on' FOR THE PLUGIN YOU WANT ACTIVATED 
$app_config['power_user']['activate_plugins'] = array(
									//'plugin-folder-name' => 'on', // (disabled example...your LOWERCASE plugin folder name in the folder: /plugins/)
									'recurring-reminder' => 'off',  // Recurring Reminder plugin (alert yourself every X days to do something)
									'decred-proposals' => 'off',  // Decred Proposals plugin (alerts when a new Decred proposal is up for discussion / vote)
									);
							
							
// Seconds to wait for response from REMOTE API endpoints (exchange data, etc). 
// Set too low you won't get ALL data (partial or zero bytes), set too high the interface can take a long time loading if an API server hangs up
// RECOMMENDED MINIMUM OF 60 FOR INSTALLS BEHIND #LOW BANDWIDTH# NETWORKS 
// (which may need an even higher timeout above 60 if data still isn't FULLY received from all APIs)
$app_config['power_user']['remote_api_timeout'] = 60; // (default = 60)


// MINUTES to cache real-time exchange price data...can be zero to skip cache, but set to at least 1 minute TO AVOID YOUR IP ADDRESS GETTING BLOCKED
// SOME APIS PREFER THIS SET TO AT LEAST A FEW MINUTES, SO HIGHLY RECOMMENDED TO KEEP FAIRLY HIGH
$app_config['power_user']['last_trade_cache_time'] = 4; // (default = 4)


// Minutes to cache blockchain stats (for mining calculators). Set high initially, it can be strict
$app_config['power_user']['chainstats_cache_time'] = 75;  // (default = 75)


// Minutes to cache marketcap rankings...start high and test lower, it can be strict
$app_config['power_user']['marketcap_cache_time'] = 50;  // (default = 50)
////
// Number of marketcap rankings to request from API.
// 300 rankings is a safe maximum to start with, to avoid getting your API requests throttled / blocked
$app_config['power_user']['marketcap_ranks_max'] = 300; // (default = 300)


// Lite charts (load just as quickly for any time interval, 7 day / 30 day / 365 day / etc)
// Structure of lite charts #IN DAYS# (X days time period charts)
// Interface will auto-detect and display days as 365 = 1Y, 180 = 6M, 7 = 1W, etc
// (LOWER TIME PERIODS [UNDER 180 DAYS] #SHOULD BE KEPT SOMEWHAT MINIMAL#, TO REDUCE RUNTIME LOAD / DISK WRITES DURING CRON JOBS)
$app_config['power_user']['lite_chart_day_intervals'] = array(10, 30, 180, 365, 730, 1460); // (default = 10, 30, 180, 365, 730, 1460)
////
// The maximum number of data points allowed in each lite chart 
// (saves on disk storage / speeds up chart loading times SIGNIFICANTLY #WITH A NUMBER OF 400 OR LESS#)
$app_config['power_user']['lite_chart_data_points_max'] = 400; // (default = 400), ADJUST WITH CARE!!!


// Number of decimals for price chart CRYPTO 24 hour volumes (NOT USED FOR FIAT VOLUMES, 4 decimals example: 24 hr vol = 91.3874 BTC)
// KEEP THIS NUMBER AS LOW AS IS FEASIBLE, TO SAVE ON CHART DATA STORAGE SPACE / MAINTAIN QUICK CHART LOAD TIMES
$app_config['power_user']['charts_crypto_volume_decimals'] = 4;  // (default = 4)
////
// Every X days backup chart data. 0 disables backups. Email to / from !MUST BE SET! (a download link is emailed to you of the chart data archive)
$app_config['power_user']['charts_backup_freq'] = 1; 


// Default settings for Asset Performance chart height / menu size (in the 'View More Stats' modal window, linked at bottom of Portfolio page)
// CHART HEIGHT MIN/MAX = 400/900 (increments of 100), MENU SIZE MIN/MAX (increments of 1) = 7/16
$app_config['power_user']['asset_performance_chart_defaults'] = '600||15'; // 'chart_height||menu_size' (default = '600||15')


// Highest numeric value sensor data to include, in the FIRST system information chart (out of two)
// (higher sensor data is moved into the second chart, to keep ranges easily readable between both charts...only used IF CRON JOB IS SETUP)
$app_config['power_user']['system_stats_first_chart_highest_value'] = 0.5; // (default = 0.5) 
////
// Highest allowed sensor value to scale vertical axis for, in the SECOND system information chart (out of two)
// (to prevent anomaly results from scaling vertical axis too high to read LESSER-VALUE sensor data...only used IF CRON JOB IS SETUP)
$app_config['power_user']['system_stats_second_chart_max_scale'] = 150; // (default = 150) 


// MINUTES to cache real-time DeFi pool info (pool eth address / name / volume / etc)
// THIS SETTING DOES #NOT# AFFECT PRICE / TRADE VALUE REFRESHING, IT ONLY AFFECTS THE POOL'S TRADE VOLUME STATS / STORED ETH ADDRESS
// LOTS OF DATA, the higher number the better for fast page load times
$app_config['power_user']['defi_pools_info_cache_time'] = 25; // (default = 25)
////
// Maximum number of HIGHEST 24 HOUR TRADE VOLUME DeFi pools to fetch
// INCREASE IF YOUR POOL DOESN'T GET DETECTED, BUT YOU KNOW THE POOL EXISTS, AS POOLS ARE SORTED BY HIGHEST 24 HOUR TRADE VOLUME
$app_config['power_user']['defi_liquidity_pools_max'] = 350; // (default = 350)
////
// Maximum number of MOST RECENT trades to fetch per DeFi pool
// INCREASE IF TRADES FOR YOUR PAIRING DON'T GET DETECTED, AS TRADES ARE SORTED BY NEWEST FIRST
$app_config['power_user']['defi_pools_max_trades'] = 60; // (default = 60)


// Email logs every X days. 0 disables mailing logs. Email to / from !MUST BE SET!, MAY NOT SEND IN TIMELY FASHION WITHOUT A CRON JOB
$app_config['power_user']['logs_email'] = 0; 
////
// Keep logs X days before purging (fully deletes logs every X days). Start low (especially when using proxies)
$app_config['power_user']['logs_purge'] = 10; 
		

// Contrast of CAPTCHA IMAGE text against background (on login pages)...0 for default, positive for extra contrast, negative for less contrast (maximum of +-35)
$app_config['power_user']['captcha_text_contrast'] = 0; // example: -5 or 5 (default = 0)


// Days until old backup archives should be deleted (chart data archives, etc)
$app_config['power_user']['backup_archive_delete_old'] = 7; 

																					
// ASSET MARKETS chart colors (https://www.w3schools.com/colors/colors_picker.asp)
////
// Charts border color
$app_config['power_user']['charts_border'] = '#808080'; // (default: '#808080')
////
// Charts background color
$app_config['power_user']['charts_background'] = '#515050';   // (default: '#515050')
////
// Charts line color
$app_config['power_user']['charts_line'] = '#444444';   // (default: '#444444')
////
// Charts text color
$app_config['power_user']['charts_text'] = '#e8e8e8';   // (default: '#e8e8e8')
////
// Charts link color
$app_config['power_user']['charts_link'] = '#939393';   // (default: '#939393')
////
// Charts price (base) gradient color
$app_config['power_user']['charts_price_gradient'] = '#000000';  // (default: '#000000')
////
// Charts tooltip background color
$app_config['power_user']['charts_tooltip_background'] = '#bbbbbb'; // (default: '#bbbbbb')
////
// Charts tooltip text color
$app_config['power_user']['charts_tooltip_text'] = '#222222'; // (default: '#222222')
							
							

// Auto-activate support for ALTCOIN PAIRED MARKETS (like glm/eth or mkr/eth, etc...markets where the base pairing is an altcoin)
// EACH ALTCOIN LISTED HERE !MUST HAVE! AN EXISTING 'btc' MARKET (within 'market_pairing') in it's 
// $app_config['portfolio_assets'] listing (further down in this config file) TO PROPERLY AUTO-ACTIVATE
// THIS ALSO ADDS THESE ASSETS AS OPTIONS IN THE "Show Crypto Value Of ENTIRE Portfolio In" SETTING, ON THE SETTINGS PAGE
// !!!!!TRY TO #NOT# ADD STABLECOINS HERE!!!!!, FIRST TRY $app_config['power_user']['bitcoin_currency_markets'] INSTEAD (TO AUTO-CLIP UN-NEEDED DECIMAL POINTS) 
// !!!!!BTC IS ALREADY ADDED AUTOMATICALLY, NO NEED TO ADD IT HERE!!!!!
$app_config['power_user']['crypto_pairing'] = array(
						//'lowercase_altcoin_ticker' => 'UNICODE_SYMBOL', // Add whitespace after the symbol, if you prefer that
						// Native chains...
						'eth' => 'Ξ ',
						'xmr' => 'ɱ ',
						// Liquidity pools / ERC-20 tokens on Ethereum, etc etc...
						'mkr' => '𐌼 ',
						//....
							);



// Preferred ALTCOIN PAIRED MARKETS market(s) for getting a certain crypto's value
// EACH ALTCOIN LISTED HERE MUST EXIST IN $app_config['power_user']['crypto_pairing'] ABOVE,
// AND !MUST HAVE! AN EXISTING 'btc' MARKET (within 'market_pairing') in it's 
// $app_config['portfolio_assets'] listing (further down in this config file),
// AND #THE EXCHANGE NAME MUST BE IN THAT 'btc' LIST#
// #USE LIBERALLY#, AS YOU WANT THE BEST PRICE DISCOVERY FOR THIS CRYPTO'S VALUE
$app_config['power_user']['crypto_pairing_preferred_markets'] = array(
						//'lowercase_btc_market_or_stablecoin_pairing' => 'PREFERRED_MARKET',
							'eth' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'xmr' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							'mkr' => 'binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
							);



// Auto-activate support for PRIMARY CURRENCY MARKETS (to use as your preferred local currency in the app)
// EACH CURRENCY LISTED HERE !MUST HAVE! AN EXISTING BITCOIN ASSET MARKET (within 'market_pairing') in 
// Bitcoin's $app_config['portfolio_assets'] listing (further down in this config file) TO PROPERLY AUTO-ACTIVATE
// #CAN# BE A CRYPTO / HAVE A DUPLICATE IN $app_config['power_user']['crypto_pairing'], 
// !AS LONG AS THERE IS A PAIRING CONFIGURED WITHIN THE BITCOIN ASSET SETUP!
$app_config['power_user']['bitcoin_currency_markets'] = array(
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
// EACH CURRENCY LISTED HERE MUST EXIST IN $app_config['power_user']['bitcoin_currency_markets'] ABOVE
// #USE CONSERVATIVELY#, AS YOU'LL BE RECOMMENDING IN THE INTERFACE TO END-USERS TO AVOID USING ANY OTHER MARKETS FOR THIS CURRENCY
$app_config['power_user']['bitcoin_preferred_currency_markets'] = array(
						//'lowercase_btc_market_or_stablecoin_pairing' => 'PREFERRED_MARKET',
							'chf' => 'kraken',  // WAY MORE reputable than ALL alternatives
							'dai' => 'kraken',  // WAY MORE reputable than ALL alternatives
							'eur' => 'kraken',  // WAY BETTER api than ALL alternatives
							'gbp' => 'kraken',  // WAY BETTER api than ALL alternatives
							'inr' => 'localbitcoins',  // WAY MORE volume / price discovery than ALL alternatives
							'jpy' => 'kraken',  // WAY MORE reputable than ALL alternatives
							'rub' => 'localbitcoins',  // WAY MORE volume / price discovery than ALL alternatives
							'usd' => 'kraken',  // WAY BETTER api than ALL alternatives
							);



// Mining rewards for different crypto networks (to prefill the editable mining calculator forms)
$app_config['power_user']['mining_rewards'] = array(
					'btc' => '6.25',
					'eth' => '2',
					// WE DYNAMICALLY UPDATE THESE BELOW IN INIT.PHP...
					'xmr' => 'PLACEHOLDER',  
					'dcr' => 'PLACEHOLDER',  
					);



// Static values in ETH for Ethereum subtokens, like during crowdsale periods etc (before exchange listings)
$app_config['power_user']['ethereum_subtoken_ico_values'] = array(
                        'ETHSUBTOKENNAME' => '0.15',
                        'GOLEM' => '0.001',
                        'ARAGON' => '0.01',
                        'DECENTRALAND' => '0.00008',
                        );
						


// HIVE INTEREST CALCULATOR SETTINGS
// Weeks to power down all HIVE Power holdings
$app_config['power_user']['hive_powerdown_time'] = 13; 
////
// HIVE Power yearly interest rate START 11/29/2019 (1.2%, decreasing every year by roughly 0.075% until it hits a minimum of 0.075% and stays there)
// 1.2 (DO NOT INCLUDE PERCENT SIGN) the first year at 11/29/2019 refactored rates, see above for manual yearly adjustment
$app_config['power_user']['hivepower_yearly_interest'] = 1.2;


// NEWS FEED (RSS) SETTINGS
// RSS feed entries to show per-feed on News page (without needing to click the "show more / less" link)
$app_config['power_user']['news_feeds_entries_show'] = 5; // (default = 5)
////
// RSS news feeds available on the News page
$app_config['power_user']['news_feeds'] = array(
    
    
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
            			"title" => "Blog - Blockstream Engineering",
            			"url" => "https://medium.com/feed/blockstream"
        						),
        
        
        				array(
            			"title" => "Blog - BTCPay Server",
            			"url" => "https://blog.btcpayserver.org/feed/"
        						),
        
        
        				array(
            			"title" => "Blog - Decred.org (community-governed high security hybrid POS/POW coin)",
            			"url" => "https://blog.decred.org/index.xml"
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
            			"title" => "Blog - Kraken",
            			"url" => "https://blog.kraken.com/feed/"
        						),
    
    
        				array(
            			"title" => "Blog - Kraken Market Reports",
            			"url" => "https://blog.kraken.com/post/category/market-reports/feed"
        						),
    
    
        				array(
            			"title" => "Blog - Kraken News",
            			"url" => "https://blog.kraken.com/post/category/kraken-news/feed/"
        						),
    
    
        				array(
            			"title" => "Blog - Monero (community-driven privacy coin)",
            			"url" => "https://web.getmonero.org/feed.xml"
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
            			"title" => "Blog - Raiden Network (Ethereum Layer 2)",
            			"url" => "https://medium.com/feed/raiden-network"
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
            			"title" => "News - CoinDesk",
            			"url" => "https://coindesk.com/feed?x=1"
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
            			"title" => "Newsletter - Lightning Labs (Bitcoin Layer 2)",
            			"url" => "https://lightninglabs.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - Our Network",
            			"url" => "https://ournetwork.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - Tari (Monero's mimblewimble sidechain)",
            			"url" => "https://tari.substack.com/feed"
        						),
    
    
        				array(
            			"title" => "Newsletter - The Daily Gwei",
            			"url" => "https://thedailygwei.substack.com/feed"
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
            			"title" => "Podcast - Citizen Bitcoin",
            			"url" => "https://feeds.simplecast.com/620_gQYv"
        						),
        
        
        				array(
            			"title" => "Podcast - Decred In Depth",
            			"url" => "https://decredindepth.libsyn.com/rss"
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
            			"title" => "Podcast - Rough Consensus",
            			"url" => "https://roughconsensus.libsyn.com/rss"
        						),
    					
    					
        				array(
            			"title" => "Podcast - Stephan Livera",
            			"url" => "https://anchor.fm/s/7d083a4/podcast/rss"
        						),
    					
    					
        				array(
            			"title" => "Podcast - Swan Signal",
            			"url" => "https://feeds.simplecast.com/Z1tu2Hds"
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
            			"title" => "Reddit - Monero (top)",
            			"url" => "https://www.reddit.com/r/Monero/top/.rss?format=xml"
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
            			"title" => "Youtube - Decred (Official)",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCJ2bYDaPYHpSmJPh_M5dNSg"
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
            			"title" => "Youtube - Nugget's News",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCLo66QVfEod0nNM_GzKNxmQ"
        						),
    
    
        				array(
            			"title" => "Youtube - Off Chain with Jimmy Song",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCEFJVYNiPp8xeIUyfaPCPQw"
        						),
    
    
        				array(
            			"title" => "Youtube - Tone Vays",
            			"url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCbiWJYRg8luWHnmNkJRZEnw"
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


// $app_config['developer']['debug_mode'] enabled runs unit tests during ui runtimes (during webpage load),
// errors detected are error-logged and printed as alerts in footer
// It also logs ui / cron runtime telemetry to /cache/logs/debugging.log, AND /cache/logs/debugging/
////////////////////////////////////////////////////////////////////////////////////////////
// 'off' (disables), 'all' (all debugging), 'charts' (chart/price alert checks),
// 'api_live_only' (log only live API requests, not cache requests),
// 'api_cache_only' (log only cache requests for API data, not live API requests),
// 'all_telemetry' (logs ALL in-app telemetries), 'stats' (basic hardware / software / runtime stats),
// 'comms_telemetry' (logs communications API responses to /cache/logs/debugging/external_api/last-response-[service].log),
// 'smtp' (smtp email server response logging, if smtp emailing is enabled),
// 'all_markets_config' (the current markets configuration),
// 'texts' (mobile gateway checks), 'markets' (coin market checks),
// 'lite_chart' (lite chart caching routines),
// 'memory' (script memory usage data)
////////////////////////////////////////////////////////////////////////////////////////////
// UNIT TESTS WILL ONLY RUN DURING WEB PAGE LOAD. MAY REQUIRE SETTING MAXIMUM ALLOWED 
// PHP EXECUTION TIME TO 120 SECONDS TEMPORARILY, FOR ALL UNIT TESTS TO FULLY COMPLETE RUNNING, 
// IF YOU GET AN ERROR 500. OPTIONALLY, TRY RUNNING ONE TEST PER PAGE LOAD, TO AVOID THIS.
// DON'T LEAVE DEBUGGING ENABLED AFTER USING IT, THE /cache/logs/debugging.log AND /cache/logs/debugging/
// LOG FILES !CAN GROW VERY QUICKLY IN SIZE! EVEN AFTER JUST A FEW RUNTIMES
$app_config['developer']['debug_mode'] = 'off'; 


// Level of detail / verbosity in log files. 'normal' logs minimal details (basic information), 
// 'verbose' logs maximum details (additional information IF AVAILABLE, for heavy debugging / tracing / etc)
// IF DEBUGGING IS ENABLED ABOVE, LOGS ARE AUTOMATICALLY VERBOSE #WITHOUT THE NEED TO ADJUST THIS SETTING#
$app_config['developer']['log_verbosity'] = 'normal'; // 'normal' / 'verbose'


// 'on' verifies ALL SMTP server certificates for secure SMTP connections, 'off' verifies NOTHING 
// Set to 'off' if the SMTP server has an invalid certificate setup (which stops email sending, but you still want to send email through that server)
$app_config['developer']['smtp_strict_ssl'] = 'off'; // (DEFAULT IS 'off', TO ASSURE SMTP EMAIL SENDING STILL WORKS THROUGH MISCONFIGURED SMTP SERVERS)


// 'on' verifies ALL REMOTE API server certificates for secure API connections, 'off' verifies NOTHING 
// Set to 'off' if some exchange's API servers have invalid certificates (which stops price data retrieval...but you still want to get price data from them)
$app_config['developer']['remote_api_strict_ssl'] = 'off'; // (default = 'off')


// Local / internal API rate limit (maximum of once every X seconds, per ip address) for accepting remote requests
// Can be 0 to disable rate limiting (unlimited)
$app_config['developer']['local_api_rate_limit'] = 5; // (default = 5)
////
// Local / internal API market limit (maximum number of markets requested per call)
$app_config['developer']['local_api_market_limit'] = 20; // (default = 20)
////
// Local / internal API cache time (minutes that previous requests are cached for)
$app_config['developer']['local_api_cache_time'] = 4; // (default = 4)


// Maximum number of BATCHED coingecko marketcap data results to fetch, per API call (during multiple / paginated calls) 
$app_config['developer']['batched_coingecko_api_call'] = 100; // (default = 100), ADJUST WITH CARE!!!


// Maximum number of BATCHED news feed fetches / re-caches per ajax OR cron runtime 
// (#TO HELP PREVENT RUNTIME CRASHES# ON LOW POWER DEVICES OR HIGH TRAFFIC INSTALLS, WITH A LOW NUMBER OF 25 OR LESS)
$app_config['developer']['batched_news_feeds_max'] = 25; // (default = 25), ADJUST WITH CARE!!!
////
// Minutes to cache RSS feeds for News page
// Randomly cache each RSS feed between the minimum and maximum minutes set here (so they don't refresh all at once, for faster load times)
// THE WIDER THE GAP BETWEEN THE NUMBERS, MORE SPLIT UP / FASTER THE FEEDS WILL LOAD IN THE INTERFACE
$app_config['developer']['news_feeds_cache_min_max'] = '60,160'; // 'min,max' (default = '60,160'), ADJUST WITH CARE!!!


// If you want to override the default user agent string (sent with API requests, etc)
// Adding a string here automatically enables that as the custom user agent
// LEAVING BLANK '' USES THE DEFAULT USER AGENT LOGIC BUILT-IN TO THIS APP (INCLUDES BASIC SYSTEM CONFIGURATION STATS)
$app_config['developer']['override_user_agent'] = ''; 


// Ignore warning to use PHP-FPM (#PHP-FPM HELPS PREVENT RUNTIME CRASHES# ON LOW POWER DEVICES OR HIGH TRAFFIC INSTALLS)
$app_config['developer']['ignore_php_fpm_warning'] = 'no'; // (default = 'no', options are 'yes' / 'no')


// Default charset used
$app_config['developer']['charset_default'] = 'UTF-8'; 
////
// Unicode charset used (if needed)
// UCS-2 is outdated as it only covers 65536 characters of Unicode
// UTF-16BE / UTF-16LE / UTF-16 / UCS-2BE can represent ALL Unicode characters
$app_config['developer']['charset_unicode'] = 'UTF-16'; 


// Cache directories / files and .htaccess / index.php files permissions (CHANGE WITH #EXTREME# CARE, to adjust security for your PARTICULAR setup)
// THESE PERMISSIONS ARE !ALREADY! CALLED THROUGH THE octdec() FUNCTION WITHIN THE APP WHEN USED
// Cache directories permissions
$app_config['developer']['chmod_cache_directories'] = '0777'; // (default = '0777')
////
// Cache files permissions
$app_config['developer']['chmod_cache_files'] = '0666'; // (default = '0666')
////
// .htaccess / index.php index security files permissions
$app_config['developer']['chmod_index_security'] = '0664'; // (default = '0664')
			
									
// !!!!! BE #VERY CAREFUL# LOWERING MAXIMUM EXECUTION TIMES BELOW, #OR YOU MAY CRASH THE RUNNING PROCESSES EARLY, 
// OR CAUSE MEMORY LEAKS THAT ALSO CRASH YOUR !ENTIRE SYSTEM!#
////
// Maximum execution time for interface runtime in seconds (how long it's allowed to run before automatically killing the process)
// (ALL execution times are automatically 600 IN DEBUG MODE)
$app_config['developer']['ui_max_execution_time'] = 120; // (default = 120)
////
// Maximum execution time for ajax runtime in seconds (how long it's allowed to run before automatically killing the process)
// (ALL execution times are automatically 600 IN DEBUG MODE)
$app_config['developer']['ajax_max_execution_time'] = 120; // (default = 120)
////
// Maximum execution time for cron job runtime in seconds (how long it's allowed to run before automatically killing the process)
// (ALL execution times are automatically 600 IN DEBUG MODE)
$app_config['developer']['cron_max_execution_time'] = 600; // (default = 600)
////
// Maximum execution time for internal API runtime in seconds (how long it's allowed to run before automatically killing the process)
// (ALL execution times are automatically 600 IN DEBUG MODE)
$app_config['developer']['int_api_max_execution_time'] = 90; // (default = 90)
////
// Maximum execution time for webhook runtime in seconds (how long it's allowed to run before automatically killing the process)
// (ALL execution times are automatically 600 IN DEBUG MODE)
$app_config['developer']['webhook_max_execution_time'] = 90; // (default = 90)
			
			
// Configuration for advanced CAPTCHA image settings on all admin login / reset pages
$app_config['developer']['captcha_image_width'] = 430; // Image width (default = 430)
////
$app_config['developer']['captcha_image_height'] = 130; // Image height (default = 130)
////
$app_config['developer']['captcha_text_margin'] = 4; // MINIMUM margin of text from edge of image (approximate / average) (default = 4)
////
$app_config['developer']['captcha_text_size'] = 50; // Text size (default = 50)
////
$app_config['developer']['captcha_chars_length'] = 6; // Number of characters in captcha image (default = 6)
////
$app_config['developer']['captcha_permitted_chars'] = 'ACEFHMNPRTUWXY234567'; // Characters allowed for use in captcha image (default = 'ACEFHMNPRTUWXY234567')
							

// TLD-only (Top Level Domain only, NO SUBDOMAINS) for each API service that UN-EFFICIENTLY requires multiple calls (for each market / data set)
// Used to throttle these market calls a tiny bit (0.15 seconds), so we don't get easily blocked / throttled by external APIs etc
// (ANY EXCHANGES LISTED HERE ARE !NOT! RECOMMENDED TO BE USED AS THE PRIMARY CURRENCY MARKET IN THIS APP,
// AS ON OCCASION THEY CAN BE !UNRELIABLE! IF HIT WITH TOO MANY SEPARATE API CALLS FOR MULTIPLE COINS / ASSETS)
// !MUST BE LOWERCASE!
$app_config['developer']['limited_apis'] = array(
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
						'dcrdata.org',
						'defipulse.com',
						'etherscan.io',
						'gemini.com',
							);


// TLD-extensions-only mapping (Top Level Domain extensions only, supported in the get_tld_or_ip() function, which removes subdomains for tld checks)
// IF YOU ADD A NEW API, !MAKE SURE IT'S DOMAIN EXTENSION EXISTS HERE!
// (NO LEADING DOTS, !MUST BE LOWERCASE!)
$app_config['developer']['top_level_domain_map'] = array(
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
add that EXACT name in this config file further above within the $app_config['comms']['to_mobile_text'] setting as the text network name variable,
to enable email-to-text alerts to your network's mobile phone number.

PLEASE REPORT ANY MISSING / INCORRECT / NON-FUNCTIONAL GATEWAYS HERE, AND I WILL FIX THEM:
https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues
(or you can add / update it yourself right in this configuration, if you know the correct gateway domain name)

*/


// All supported mobile network email-to-text gateway (domain name) configurations
// Network name keys MUST BE LOWERCASE (for reliability / consistency, 
// as these name keys are always called from (forced) lowercase name key lookups)

// SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// See /DOCUMENTATION-ETC/HELP-FAQ.txt for tips / troubleshooting FAQs.

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC !!!!WILL BREAK THE APP!!!!, BE CAREFUL EDITING THIS CONFIG FILE

// DUPLICATE NETWORK NAME KEYS --WILL CANCEL EACH OTHER OUT--, !!USE A UNIQUE NAME FOR EACH KEY!!

// WHEN ADDING YOUR OWN GATEWAYS, ONLY INCLUDE THE DOMAIN (THIS APP WILL AUTOMATICALLY FORMAT TO your_phone_number@your_gateway)


$app_config['mobile_network_text_gateways'] = array(
                        
                        
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
                        

); // mobile_network_text_gateways END



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// EMAIL-TO-MOBILE-TEXT CONFIGURATION -END- //////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PORTFOLIO ASSETS CONFIGURATION -START- ////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG

// SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// See /DOCUMENTATION-ETC/HELP-FAQ.txt for tips / troubleshooting FAQs.

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC !!!!WILL BREAK THE APP!!!!, BE CAREFUL EDITING THIS CONFIG FILE


$app_config['portfolio_assets'] = array(

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // BTC (!!!!*BTC MUST BE THE VERY FIRST* IN THIS ASSET LIST, DO NOT DELETE, BTC IS *REQUIRED* TO RUN THIS APP!!!!)
                    'BTC' => array(
                        
                        'asset_name' => 'Bitcoin',
                        'marketcap_website_slug' => 'bitcoin',
                        'market_pairing' => array(
                        
                        				'aed' => array(
                                          'localbitcoins' => 'AED',
                                                    ),
                        
                        				'ars' => array(
                                          'localbitcoins' => 'ARS',
                                                    ),
                        
                        				'aud' => array(
                                    		'btcmarkets' => 'BTC/AUD',
                                          'localbitcoins' => 'AUD',
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
                                          'braziliex' => 'btc_brl',
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
                                          'livecoin' => 'BTC/USD',
                                          'cex' => 'BTC:USD',
                                          'southxchange' => 'BTC/USD',
                                                    ),
                                                    
                                    'usdc' => array(
                                          'binance' => 'BTCUSDC',
                                          'kraken' => 'XBTUSDC',
                                        	'korbit' => 'btc_usdc',
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
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END (!!!!*BTC MUST BE THE VERY FIRST* IN THIS ASSET LIST, DO NOT DELETE, BTC IS *REQUIRED* TO RUN THIS APP!!!!)
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ETH
                    'ETH' => array(
                        
                        'asset_name' => 'Ethereum',
                        'marketcap_website_slug' => 'ethereum',
                        'market_pairing' => array(
                                                    
                                    'brl' => array(
                                          'braziliex' => 'eth_brl',
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
                                          'livecoin' => 'ETH/BTC',
                                          'poloniex' => 'BTC_ETH',
                                          'cryptofresh' => 'OPEN.ETH',
                                          'bitso' => 'eth_btc',
                                          'braziliex' => 'eth_btc',
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
                                          'poloniex' => 'USDT_ETH',
                                          'bitbns' => 'ETHUSDT',
                                          'wazirx' => 'ethusdt',
                                    	 	'defipulse' => 'ETH/USDT',
                                                    ),
                                                    
                                    'usdc' => array(
                                          'binance' => 'ETHUSDC',
                                          'coinbase' => 'ETH-USDC',
                                          'kraken' => 'ETHUSDC',
                                          'kucoin' => 'ETH-USDC',
                                          'poloniex' => 'USDC_ETH',
                                                    ),
                                                    
                                    'zar' => array(
                                          'luno' => 'ETHZAR',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // XMR
                    'XMR' => array(
                        
                        'asset_name' => 'Monero',
                        'marketcap_website_slug' => 'monero',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'XMRBTC',
                                          'huobi' => 'xmrbtc',
                                          'bittrex' => 'XMR-BTC',
                                          'bitfinex' => 'tXMRBTC',
                                          'hitbtc' => 'XMRBTC',
                                          'kraken' => 'XXMRXXBT',
                                          'okex' => 'XMR-BTC',
                                          'poloniex' => 'BTC_XMR',
                                                    ),
                                                    
                                    'eth' => array(
                                        	'binance' => 'XMRETH',
                                          'huobi' => 'xmreth',
                                          'bittrex' => 'XMR-ETH',
                                          'hitbtc' => 'XMRETH',
                                                    ),
                                                    
                                    'inr' => array(
                                          'bitbns' => 'XMR',
                                          'buyucoin' => 'INR-XMR',
                                                    ),
                                                    
                                    'usdt' => array(
                                        	'binance' => 'XMRUSDT',
                                          'huobi' => 'xmrusdt',
                                          'bittrex' => 'XMR-USDT',
                                          'okex' => 'XMR-USDT',
                                          'poloniex' => 'USDT_XMR',
                                                    ),
                                                    
                                    'usdc' => array(
                                          'poloniex' => 'USDC_XMR',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MISCASSETS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    'MISCASSETS' => array(), 
                    // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MKR
                    'MKR' => array(
                        
                        'asset_name' => 'Maker',
                        'marketcap_website_slug' => 'maker',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'MKRBTC',
                                          'coinbase' => 'MKR-BTC',
                                          'bittrex' => 'MKR-BTC',
                                       	'kucoin' => 'MKR-BTC',
                                          'okex' => 'MKR-BTC',
                                          'bitfinex' => 'tMKRBTC',
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
                                          'bitfinex' => 'tMKRETH',
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
                                        	'hitbtc' => 'MKRUSD',
                                          'gateio' => 'MKR_USDT',
                                          'coinex' => 'MKRUSDT',
                                          			),
                                          			
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // DAI
                    'DAI' => array(
                        
                        'asset_name' => 'Dai',
                        'marketcap_website_slug' => 'dai',
                        'market_pairing' => array(
                        
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
                                    	 'defipulse' => 'DAI/USDT',
                                                    ),
                                                    
                                        ) // market_pairing END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // UNI
                    'UNI' => array(
                        
                        'asset_name' => 'Uniswap',
                        'marketcap_website_slug' => 'uniswap',
                        'market_pairing' => array(
                                                    
                                    'btc' => array(
                                        'binance' => 'UNIBTC',
                                                    ),
                                                    
                                    'eth' => array(
                                    	 'defipulse' => 'UNI/WETH',
                                                    ),
                                                    
                                    'usd' => array(
                                        'coinbase' => 'UNI-USD',
                                        'binance_us' => 'UNIUSD',
                                                    ),
                                                    
                                    'usdt' => array(
                                        'binance' => 'UNIUSDT',
                                        'binance_us' => 'UNIUSDT',
                                                    ),
                                                    
                                        ) // market_pairing END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MANA
                    'MANA' => array(
                        
                        'asset_name' => 'Decentraland',
                        'marketcap_website_slug' => 'decentraland',
                        'market_pairing' => array(
                        
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
                                                    
                                    'usdc' => array(
                                          'coinbase' => 'MANA-USDC',
                                                    ),
                                                    
                                    'usdt' => array(
                                          'hitbtc' => 'MANAUSD',
                                          'okex' => 'MANA-USDT',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ANT
                    'ANT' => array(
                        
                        'asset_name' => 'Aragon',
                        'marketcap_website_slug' => 'aragon',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'ANTBTC',
                                          'bittrex_global' => 'ANT-BTC',
                                        	'okex' => 'ANT-BTC',
                                          'huobi' => 'antbtc',
                                        	'ethfinex' => 'tANTBTC',
                                          'hitbtc' => 'ANTBTC',
                                        	'upbit' => 'BTC-ANT',
                                                    ),
                                                    
                                    'eth' => array(
                                          'bittrex_global' => 'ANT-ETH',
                                          'huobi' => 'anteth',
                                        	'ethfinex' => 'tANTETH',
                                    	 	'defipulse' => 'ANT/WETH',
                                                    ),
                                                    
                                    'usd' => array(
                                        	'bitfinex' => 'tANTUSD',
                                                    ),
                                                    
                                    'usdt' => array(
                                        	'binance' => 'ANTUSDT',
                                        	'okex' => 'ANT-USDT',
                                          'huobi' => 'antusdt',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // GLM
                    'GLM' => array(
                        
                        'asset_name' => 'Golem',
                        'marketcap_website_slug' => 'golem-network-tokens',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'bittrex' => 'GNT-BTC',
                                        	'ethfinex' => 'tGNTBTC',
                                        	'upbit' => 'BTC-GLM',
                                          'bitso' => 'gnt_btc',
                                          'poloniex' => 'BTC_GLM',
                                          'braziliex' => 'gnt_btc',
                                        	'wazirx' => 'gntbtc',
                                                    ),
                                                    
                                    'eth' => array(
                                          'bittrex' => 'GNT-ETH',
                                        	'ethfinex' => 'tGNTETH',
                                                    ),
                                                    
                                    'inr' => array(
                                          'bitbns' => 'GNT',
                                                    ),
                                                    
                                    'krw' => array(
                                        	'upbit' => 'KRW-GLM',
                                                    ),
                                                    
                                    'mxn' => array(
                                          'bitso' => 'gnt_mxn',
                                                    ),
                                                    
                                    'usdc' => array(
                                          'coinbase' => 'GNT-USDC',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // DCR
                    'DCR' => array(
                        
                        'asset_name' => 'Decred',
                        'marketcap_website_slug' => 'decred',
                        'market_pairing' => array(
                        
                                    'brl' => array(
                                          'braziliex' => 'dcr_brl'
                                                    ),
                                                    
                                    'btc' => array(
                                        	'binance' => 'DCRBTC',
                                          'bittrex' => 'DCR-BTC',
                                       	'kucoin' => 'DCR-BTC',
                                          'upbit' => 'BTC-DCR',
                                          'okex' => 'DCR-BTC',
                                          'gateio' => 'DCR_BTC',
                                          'braziliex' => 'dcr_btc',
                                                    ),
                                                    
                                		'eth' => array(
                                        	'kucoin' => 'DCR-ETH',
                                                    ),
                                                    
                                    'usd' => array(
                                        	'bittrex' => 'DCR-USD',
                                          			),
                                                    
                                    'usdt' => array(
                                        	'binance' => 'DCRUSDT',
                                          'bittrex' => 'DCR-USDT',
                                          'okex' => 'DCR-USDT',
                                          'gateio' => 'DCR_USDT',
                                          			),
                                          			
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // HIVE
                    'HIVE' => array(
                        
                        'asset_name' => 'Hive',
                        'marketcap_website_slug' => 'hive-blockchain',
                        'market_pairing' => array(
                        
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
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SXP
                    'SXP' => array(
                        
                        'asset_name' => 'Swipe',
                        'marketcap_website_slug' => 'swipe',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'SXPBTC',
                                          'bittrex_global' => 'SXP-BTC',
                                          'kucoin' => 'SXP-BTC',
                                                    ),
                                                    
                                    'usdt' => array(
                                          'kucoin' => 'SXP-USDT',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ENJ
                    'ENJ' => array(
                        
                        'asset_name' => 'Enjin Coin',
                        'marketcap_website_slug' => 'enjin-coin',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'ENJBTC',
                                          'bittrex' => 'ENJ-BTC',
                                          'hitbtc' => 'ENJBTC',
                                          'kucoin' => 'ENJ-BTC',
                                          'coinex' => 'ENJBTC',
                                          'liquid' => 'ENJBTC',
                                          'livecoin' => 'ENJ/BTC',
                                        	'upbit' => 'BTC-ENJ',
                                                    ),
                                                    
                                    'eth' => array(
                                        	'binance' => 'ENJETH',
                                          'bittrex' => 'ENJ-ETH',
                                          'hitbtc' => 'ENJETH',
                                          'kucoin' => 'ENJ-ETH',
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
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // DATA
                    'DATA' => array(
                        
                        'asset_name' => 'Streamr DATAcoin',
                        'marketcap_website_slug' => 'streamr-datacoin',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        'binance' => 'DATABTC',
                                        'ethfinex' => 'tDATBTC',
                                        'hitbtc' => 'DATABTC',
                                                    ),
                                                    
                                    'eth' => array(
                                        'binance' => 'DATAETH',
                                  		 'hitbtc' => 'DATAETH',
                                        'gateio' => 'DATA_ETH',
                                                    ),
                                                    
                                    'usdt' => array(
                                         'hitbtc' => 'DATAUSD',
                                         'gateio' => 'DATA_USDT',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MYST
                    'MYST' => array(
                        
                        'asset_name' => 'Mysterium',
                        'marketcap_website_slug' => 'mysterium',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'hitbtc' => 'MYSTBTC',
                                                    ),
                                                    
                                    'eth' => array(
                                          'hitbtc' => 'MYSTETH',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                
                    ////////////////////////////////////////////////////////////////////
                
                
); // portfolio_assets END



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PORTFOLIO ASSETS CONFIGURATION -END- //////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
///////////////////////////////////////////////////////////////////////////////////////////////
// WHEN RE-CONFIGURING APP, LEAVE THIS CODE BELOW HERE, DON'T DELETE BELOW THESE LINES
require_once("app-lib/php/init.php"); // REQUIRED, DON'T DELETE BY ACCIDENT
///////////////////////////////////////////////////////////////////////////////////////////////
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


?>