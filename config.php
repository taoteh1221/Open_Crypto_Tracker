<?php

/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
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
$app_config = array(); require_once("app-lib/php/loader.php");  // REQUIRED, DON'T DELETE BY ACCIDENT
// WHEN RE-CONFIGURING APP, LEAVE THIS CODE ABOVE HERE, DON'T DELETE ABOVE THESE LINES
///////////////////////////////////////////////////////////////////////////////////////////////
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// GENERAL CONFIG -START- ////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////



// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG

// SEE /DOCUMENTATION-ETC/CONFIG.EXAMPLE.txt FOR A FULL EXAMPLE OF THE CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC WILL BREAK THE APP, BE CAREFUL EDITING THIS CONFIG FILE



// $app_config['debug_mode'] enabled runs unit tests during ui runtimes (during webpage load), errors detected are error-logged and printed as alerts in footer
// It also logs ui / cron runtime telemetry to /cache/logs/debugging.log, AND /cache/logs/debugging/
// 'off' (disables), 'all' (all debugging), 'charts' (chart/price alert checks), 'texts' (mobile gateway checks), 
// 'markets' (coin market checks), 'telemetry' (logs in-app telemetries), 'stats' (basic hardware / software / runtime stats),
// 'btc_markets_config' (the current Bitcoin markets configuration), 'smtp' (smtp email server response logging, if smtp emailing is enabled),
// 'api_live_only' (log only live API requests, not cache requests), 'api_cache_only' (log only cache requests for API data, not live API requests)
// UNIT TESTS WILL ONLY RUN DURING WEB PAGE LOAD. MAY REQUIRE  
// SETTING MAXIMUM ALLOWED PHP EXECUTION TIME TO 120 SECONDS TEMPORARILY, 
// FOR ALL UNIT TESTS TO FULLY COMPLETE RUNNING, IF YOU GET AN ERROR 500.
// OPTIONALLY, TRY RUNNING ONE TEST PER PAGE LOAD, TO AVOID THIS.
// DON'T LEAVE DEBUGGING ENABLED AFTER USING IT, THE /cache/logs/debugging.log AND /cache/logs/debugging/
// LOG FILES !CAN GROW VERY QUICKLY IN SIZE! EVEN AFTER JUST A FEW RUNTIMES
$app_config['debug_mode'] = 'off'; 


// Htaccess password protection (password required to view this portfolio app's web interface)
// Username MUST BE at least 4 characters, beginning with ONLY LOWERCASE letters (may contain numbers AFTER first letter), NO SPACES
// Password MUST BE at least 13 characters, AND contain one number, one UPPER AND LOWER CASE letter, and one symbol, NO SPACES
// (enables automatically, when a valid username / password are filled in here)
// (disables automatically, when username / password are blank '')
$app_config['htaccess_username'] = ''; // Leave blank to keep disabled
$app_config['htaccess_password'] = ''; // Leave blank to keep disabled


// Your local time offset in hours compared to UTC time. Can be negative or positive.
// (Used for user experience 'pretty' timestamping only, will not change or screw up UTC log times etc if you change this)
$app_config['local_time_offset'] = -5; // example: -5 or 5


// Default BITCOIN-ONLY currency market pairing 
// (set for charts / price alert primary-currency-equivalent value determination [example: usd value of btc/ltc market, etc])
// 'aud' / 'bob' / 'brl' / 'cad' / 'chf' / 'cop' / 'eur' / 'eth' / 'gbp' / 'hkd' / 'jpy' / 'ltc' / 'mxn'
// 'nis' / 'pkr' / 'rub' / 'sgd' / 'try' / 'tusd' / 'usd' / 'usdc' / 'usdt' / 'vnd'
// SEE THE $app_config['portfolio_assets'] CONFIGURATION NEAR THE BOTTOM OF THIS CONFIG FILE, FOR THE PROPER (CORRESPONDING)
// CURRENCY PAIRING VALUE NEEDED FOR YOUR CHOSEN 'BTC' EXCHANGE (set in $app_config['btc_primary_exchange'] directly below)
$app_config['btc_primary_currency_pairing'] = 'usd'; 


// Default BITCOIN-ONLY exchanges 
// (set for charts / price alert primary-currency-equivalent value determination [example: usd value of btc/ltc market, etc])
// 'btcmarkets' / 'lakebtc' / 'localbitcoins' / 'braziliex' / 'kraken' / 'bitflyer' / 'bitlish' / 'bitpanda' / 'bitstamp'
// 'cex' / 'coinbase' / 'coss' / 'bitfinex' / 'tidebit' / 'bitso' / 'bit2c' / 'btcturk' / 'binance' / 'binance_us'
// 'gemini' / 'hitbtc' / 'livecoin' / 'okcoin' / 'southxchange' / 'huobi' / 'okex'
// SEE THE $app_config['portfolio_assets'] CONFIGURATION NEAR THE BOTTOM OF THIS CONFIG FILE, FOR THE PROPER (CORRESPONDING)
// MARKET PAIRING VALUE NEEDED FOR YOUR CHOSEN 'BTC' EXCHANGE (to populate $app_config['btc_primary_currency_pairing'] directly above with)
$app_config['btc_primary_exchange'] = 'kraken'; // SEE THE $app_config['limited_apis'] SETTING MUCH FURTHER DOWN, FOR EXCHANGES !NOT RECOMMENDED FOR USAGE HERE!


// Maximum decimal places for [primary currency] values of coins worth under 1.00 [usd/gbp/eur/jpy/brl/rub/etc], for prettier / less-cluttered interface
$app_config['primary_currency_decimals_max'] = 5; // IF YOU ADJUST $app_config['btc_primary_currency_pairing'] ABOVE, YOU MAY NEED TO ADJUST THIS ACCORDINGLY FOR !FUNCTIONAL! CHARTS / ALERTS


// Default marketcap data source: 'coingecko', or 'coinmarketcap' (coinmarketcap requires a FREE API key, see below)
$app_config['primary_marketcap_site'] = 'coingecko'; 


// API key for coinmarketcap.com Pro API (required unfortunately, but a FREE level is available): https://coinmarketcap.com/api
$app_config['coinmarketcapcom_api_key'] = '';


// Number of marketcap rankings to request from API. Ranks are grabbed 100 per request
$app_config['marketcap_ranks_max'] = 200; // 200 rankings is a safe maximum to start with, it avoids getting your API requests throttled / blocked


$app_config['margin_leverage_max'] = 125; // Maximum margin leverage available in the user interface ('Update Assets' page, etc)


// Block an asset price alert if price retrieved, BUT failed retrieving pair volume (not even a zero was retrieved, nothing)
// Good for blocking questionable exchanges bugging you with price alerts, especially when used in combination with the minimum volume filter
$app_config['block_alerts_volume_error'] = 'on'; // 'on' / 'off' 


$app_config['marketcap_cache_time'] = 20; // Minutes to cache above-mentioned marketcap rankings...start high and test lower, it can be strict


// Minutes to cache real-time exchange data...can be zero to skip cache, but set to at least 1 minute to avoid your IP getting blocked
$app_config['last_trade_cache_time'] = 4; 


$app_config['chainstats_cache_time'] = 30; // Minutes to cache blockchain stats (for mining calculators). Set high initially, can be strict


$app_config['api_timeout'] = 15; // Seconds to wait for response from API endpoints (exchange data, etc). Don't set too low, or you won't get data


// 'on' verifies ALL certificates for secure API connections, 'off' verifies NOTHING 
// Set to 'off' if some exchange's API servers have invalid certificates (which stops price data retrieval, but you still want to get price data from them)
$app_config['api_strict_ssl'] = 'on'; 


$app_config['delete_old_backups'] = 7; // Days until old zip archive backups should be deleted (chart data archives, etc)


$app_config['purge_logs'] = 7; // Days to keep logs before purging (deletes logs every X days) start low, especially when using proxies


// Every X days mail logs. 0 disables mailing logs. Email to / from !MUST BE SET! MAY NOT SEND IN TIMELY FASHION WITHOUT CRON JOB
$app_config['mail_logs'] = 1; 


// Shows system statistics in the user interface, if stats are available (system load, system temperature, free disk space, free system memory)
$app_config['system_stats'] = 'raspi'; // 'off' (disabled), 'on' (enabled for ANY system), 'raspi' (enabled ONLY for raspberry pi devices)





// ENABLING CHARTS REQUIRES A CRON JOB SETUP (see README.txt for cron job setup information)
// Caches the default [primary currency] ($app_config['btc_primary_currency_pairing'] at top of this config) price + crypto price / volume data for charts 
// of all assets added to $app_config['asset_charts_and_alerts'] (further down in this config file)
// Enables a charts tab / page with historical charts. STILL EARLY CODE (AS OF 5/29/2019), MAY SLOW PAGE LOADS SIGNIFICANTLY UNTIL FURTHER OPTIMIZED
// Disabling will disable EVERYTHING related to the charts features...the page, caching, even the javascript associated with the charts
$app_config['charts_page'] = 'on'; // 'on' / 'off'


// Chart colors (https://www.w3schools.com/colors/colors_picker.asp)


// Charts border color
$app_config['charts_border'] = '#808080';  


// Charts background color
$app_config['charts_background'] = '#515050';  


// Charts line color
$app_config['charts_line'] = '#444444';  


// Charts text color
$app_config['charts_text'] = '#dddddd';  


// Charts link color
$app_config['charts_link'] = '#b5b5b5';  


// Charts price (base) gradient color
$app_config['charts_price_gradient'] = '#000000'; 


// Charts tooltip background color
$app_config['charts_tooltip_background'] = '#bbbbbb';


// Charts tooltip text color
$app_config['charts_tooltip_text'] = '#222222';


// Backup chart data in a zip file in the 'backups' subdirectory (with a secure random 32 character hexadecimal suffix for privacy), only used if $app_config['charts_page'] above is on
$app_config['charts_backup_freq'] = 1; // Every X days backup chart data. 0 disables backups. Email to / from !MUST BE SET! (a download link is emailed to you of the chart data archive)





// IF SMTP EMAIL SENDING --NOT-- USED, FROM email should be REAL address on the website domain, or risk having email blacklisted / sent to junk folder
// IF SMTP EMAIL SENDING --IS-- USED, FROM EMAIL MUST MATCH EMAIL ADDRESS associated with SMTP login (SMTP Email settings are further down below this setting)
$app_config['from_email'] = ''; // MUST BE SET for price alerts and other email features


$app_config['to_email'] = ''; // MUST BE SET for price alerts and other email features


// For asset price alert texts to mobile phone numbers. 
// Attempts to email the text if a SUPPORTED MOBILE TEXTING NETWORK name is set, AND no textbelt / textlocal config is setup.
// SMTP-authenticated email sending MAY GET THROUGH TEXTING SERVICE CONTENT FILTERS BETTER THAN USING PHP'S BUILT-IN EMAILING FUNCTION
// SEE FURTHER DOWN IN THIS CONFIG FILE, FOR A LIST OF SUPPORTED MOBILE TEXTING NETWORK PROVIDER NAMES 
// IN THE EMAIL-TO-MOBILE-TEXT CONFIG SECTION (the "network name keys" in the $app_config['mobile_network_text_gateways'] variables array)
// CAN BE BLANK. Country code format MAY NEED TO BE USED (depending on your mobile network)
$app_config['to_text'] = ''; // 'phone_number||network_name_key' (example: '12223334444||virgin_us')


// For asset price alert notifyme alexa notifications (sending Alexa devices notifications for free). 
// CAN BE BLANK. Setup: http://www.thomptronics.com/notify-me
$app_config['notifyme_accesscode'] = '';


// Google Home settings (WORK IN PROGRESS, NOT FUNCTIONAL)
$app_config['google_home_client_id'] = '';

$app_config['google_home_client_secret'] = '';


// Do NOT use textbelt AND textlocal together. Leave one setting blank, or it will disable using both.


// CAN BE BLANK. For asset price alert textbelt notifications. Setup: https://textbelt.com/
$app_config['textbelt_apikey'] = '';


// CAN BE BLANK. For asset price alert textlocal notifications. Setup: https://www.textlocal.com/integrations/api/
$app_config['textlocal_account'] = ''; // This format MUST be used: 'username||hash_code'





// OPTIONALLY use SMTP authentication TO SEND EMAIL, if you have no reverse lookup that matches domain name (on your home network etc)
// !!USE A THROWAWAY ACCOUNT ONLY!! If web server is hacked, HACKER WOULD THEN HAVE ACCESS YOUR EMAIL LOGIN FROM THIS FILE!!
// If SMTP credentials / settings are filled in, BUT not setup properly, APP EMAILING WILL FAIL
// CAN BE BLANK (PHP's built-in mail function will be automatically used to send email instead)
$app_config['smtp_login'] = ''; //  CAN BE BLANK. This format MUST be used: 'username||password'


$app_config['smtp_server'] = ''; // CAN BE BLANK. This format MUST be used: 'domain_or_ip:port' example: 'example.com:25'


$app_config['smtp_secure'] = 'tls'; // CAN BE 'off' FOR NO SECURE CONNECTION, or 'tls', or 'ssl' for secure connections. MAKE SURE PORT NUMBER ABOVE CORRESPONDS


// 'on' verifies ALL SMTP server certificates for secure SMTP connections, 'off' verifies NOTHING 
// Set to 'off' if the SMTP server has an invalid certificate setup (which stops email sending, but you still want to send email through that server)
$app_config['smtp_strict_ssl'] = 'off'; // (DEFAULT IS 'off', TO ASSURE SMTP EMAIL SENDING STILL WORKS THROUGH SSL-MISCONFIGURED SMTP SERVERS)





// If using proxies and login is required
// Adding a user / pass here will automatically send login details for proxy connections
// CAN BE BLANK. IF using ip address whitelisting instead, MUST BE LEFT BLANK
$app_config['proxy_login'] = ''; // Use format: 'username||password'


// If using proxies, add the ip address / port number here for each one, like examples below (without the double slashes in front)
// CAN BE BLANK. Adding proxies here will automatically choose one randomly for each API request
$app_config['proxy_list'] = array(
					// 'ipaddress1:portnumber1',
					// 'ipaddress2:portnumber2',
					);


// Additional proxy configuration settings (only used if proxies are enabled above)


$app_config['proxy_alerts'] = 'email'; // Alerts for failed proxy data connections. 'none', 'email', 'text', 'notifyme', 'all'


$app_config['proxy_alerts_runtime'] = 'cron'; // Which runtime mode should allow proxy alerts? Options: 'cron', 'ui', 'all'


$app_config['proxy_checkup_ok'] = 'include'; // 'include', or 'ignore' Proxy alerts sent to you even if proxy checkup went OK? (after flagged, started working again when checked) 


$app_config['proxy_alerts_freq'] = 1; // Re-allow same proxy alert(s) after X hours (per ip/port pair, can be 0)





// Asset price alert settings
// Only used if $app_config['asset_charts_and_alerts'] is filled in properly below, AND a cron job is setup (see README.txt for cron job setup information) 


$app_config['asset_price_alerts_percent'] = 7.75; // Price percent change to send alerts for (WITHOUT percent sign: 15 = 15%). Sends alerts when percent change reached (up or down)


$app_config['asset_price_alerts_freq'] = 8; // Re-allow same asset price alert(s) after X hours (per asset, set higher if issues with blacklisting...can be 0)


// Minimum 24 hour volume filter. Only allows sending price alerts if minimum 24 hour volume reached
// CAN BE 0 TO DISABLE MINIMUM VOLUME FILTERING, NO DECIMALS OR SEPARATORS, NUMBERS ONLY, WITHOUT the [primary currency] prefix symbol: 4500 = $4,500 , 30000 = $30,000 , etc
// THIS FILTER WILL AUTO-DISABLE IF THERE IS AN ERROR RETRIEVING DATA ON A CERTAIN MARKET (WHEN NOT EVEN A ZERO IS RECEIVED)
$app_config['asset_price_alerts_min_volume'] = 12500;


// Refresh cached comparison prices every X days (since last refresh / alert) with latest prices
// Can be 0 to disable refreshing (until price alert triggers a refresh)
$app_config['asset_price_alerts_refresh'] = 0; 


// Whale alert (adds "WHALE ALERT" to beginning of alexa / google home / email alert text, and spouting whale emoji to email / text)
// Format: 'maximum_days_to_24hr_average_over||minimum_price_percent_change_24hr_average||minimum_volume_percent_change_24hr_average||minimum_volume_currency_change_24hr_average'
// DECIMALS ARE SUPPORTED, USE NUMBERS ONLY (NO CURRENCY SYMBOLS / COMMAS, ETC)
$app_config['whale_alert_thresholds'] = '3.15||9.5||17.5||18500';


// CHARTS / ASSET PRICE ALERTS SETUP REQUIRES A CRON JOB RUNNING ON YOUR WEBSITE SERVER (see README.txt for cron job setup information) 
// Markets you want charts or asset price change alerts for (alerts sent when default [primary currency] 
// [$app_config['btc_primary_currency_pairing'] at top of this config] value change is equal to or above / below $app_config['asset_price_alerts_percent']) 
// Delete any double forward slashes from in front of each asset you want to enable charts / price alerts on (or add double slashes in front to disable it)
// NOTE: This list must only contain assets / exchanges / trading pairs included in the primary coin list configuration further down in this config file
// TO ADD MULTIPLE CHARTS / ALERTS FOR SAME ASSET (FOR DIFFERENT EXCHANGES / TRADE PAIRINGS), FORMAT LIKE SO: symbol, symbol-1, symbol-2, symbol-3, etc.
// TO ENABLE CHART AND ALERT = both, TO ENABLE CHART ONLY = chart, TO ENABLE ALERT ONLY = alert
$app_config['asset_charts_and_alerts'] = array(


					// SYMBOL
				// 'symbol' => 'exchange||trade_pairing||both',
				// 'symbol-2' => 'exchange2||trade_pairing2||chart',
				
				
					// OTHERSYMBOL
				// 'othersymbol' => 'exchange||trade_pairing||both',
				// 'othersymbol-2' => 'exchange2||trade_pairing2||alert',
				// 'othersymbol-3' => 'exchange3||trade_pairing3||chart',
					
					
					// BTC
					'btc' => 'coinbase||usd||chart',
					'btc-2' => 'binance||usdt||both',
					'btc-3' => 'bitstamp||usd||chart',
					'btc-4' => 'kraken||usd||chart',
					'btc-5' => 'gemini||usd||chart',
					'btc-6' => 'bitfinex||usd||chart',
					'btc-7' => 'binance_us||usd||chart',
					'btc-8' => 'kraken||eur||chart',
					'btc-9' => 'coinbase||eur||chart',
					'btc-10' => 'coinbase||gbp||chart',
					'btc-11' => 'kraken||cad||chart',
					'btc-12' => 'btcmarkets||aud||chart',
					
					
					// ETH
					'eth' => 'coinbase||btc||chart',
					'eth-2' => 'bittrex||btc||chart',
					'eth-3' => 'kraken||btc||chart',
					'eth-4' => 'binance||usdt||both',
					'eth-5' => 'binance_us||btc||chart',
					'eth-6' => 'coinbase||usd||chart',
					'eth-7' => 'kraken||usd||chart',
					'eth-8' => 'bitstamp||usd||chart',
					'eth-9' => 'gemini||usd||chart',
					'eth-10' => 'coinbase||gbp||chart',
					'eth-11' => 'coinbase||eur||chart',
					'eth-12' => 'bittrex||usdt||chart',
					
					
					// XMR
					'xmr' => 'bittrex||btc||chart',
					'xmr-2' => 'bittrex||eth||chart',
					'xmr-4' => 'binance||btc||both',
					'xmr-5' => 'binance||eth||chart',
					
					
					// LTC
					'ltc' => 'bittrex||btc||chart',
					'ltc-2' => 'bittrex||eth||chart',
					'ltc-3' => 'binance||usdt||both',
					'ltc-4' => 'binance||eth||chart',
					'ltc-5' => 'binance_us||btc||chart',
					
					
					// DCR
					'dcr' => 'bittrex||btc||chart',
					'dcr-2' => 'bittrex||usdt||chart',
					'dcr-3' => 'binance||btc||both',
					'dcr-4' => 'kucoin||btc||chart',
					'dcr-5' => 'kucoin||eth||chart',
					
					
					// GRIN
					'grin-2' => 'bittrex_global||btc||chart',
					'grin-3' => 'gateio||usdt||chart',
					'grin-4' => 'kucoin||btc||both',
					'grin-5' => 'hitbtc||btc||chart',
					'grin-6' => 'hotbit||btc||chart',
					
					
					// ATOM
					'atom-2' => 'kraken||btc||chart',
					'atom-3' => 'binance||btc||both',
					'atom-4' => 'binance||tusd||chart',
					'atom-5' => 'binance||usdc||chart',
					'atom-6' => 'bittrex_global||btc||chart',
					'atom-7' => 'okex||btc||chart',
					'atom-8' => 'okex||eth||chart',
					
					
					// KDA
					'kda' => 'hotbit||btc||both',
					
					
					// STEEM
					'steem' => 'bittrex||btc||chart',
					'steem-2' => 'binance||btc||chart',
					
					
					// DOGE
					'doge' => 'bittrex||btc||chart',
					'doge-2' => 'binance||btc||both',
					'doge-3' => 'binance_us||usdt||chart',
					'doge-4' => 'kraken||btc||chart',
					
					
					// ANT
					'ant' => 'bittrex_global||btc||both',
					'ant-2' => 'hitbtc||btc||chart',
					'ant-3' => 'ethfinex||btc||chart',
					
					
					// MANA
					'mana' => 'bittrex||btc||chart',
					'mana-2' => 'binance||btc||both',
					'mana-3' => 'kucoin||btc||chart',
					'mana-4' => 'ethfinex||btc||chart',
					
					
					// GNT
					'gnt' => 'bittrex||btc||both',
					'gnt-2' => 'ethfinex||btc||chart',
					
					
					// DATA
					'data' => 'hitbtc||btc||chart',
					'data-2' => 'binance||btc||chart',
					
					
					// DAG
					'dag' => 'kucoin||btc||chart',
					'dag-2' => 'hitbtc||btc||chart',
					
					
					//MYST
					'myst' => 'hitbtc||btc||both',
					'myst-2' => 'hitbtc||eth||alert',
					'myst-3' => 'idex||eth||alert',
					
					
					//DAI
					'dai' => 'coinbase||usdc||both',
					'dai-2' => 'kraken||usd||both',
					'dai-3' => 'bittrex||btc||chart',
					
					
					);
					
// END $app_config['asset_charts_and_alerts']





// Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
$app_config['eth_subtokens_ico_values'] = array(
                        'ETHSUBTOKENNAME' => '0.15',
                        'GOLEM' => '0.001',
                        'ARAGON' => '0.01',
                        'DECENTRALAND' => '0.00008',
                        );





// Mining rewards for different platforms (to prefill editable mining calculator forms)
$app_config['mining_rewards'] = array(
					'btc' => '12.5',
					'eth' => '2',
					'ltc' => '12.5',
					'grin' => '60',
					'doge' => '10000',
					'xmr' => NULL,  // WE DYNAMICALLY UPDATE THIS IN INIT.PHP
					'dcr' => NULL,  // WE DYNAMICALLY UPDATE THIS IN INIT.PHP
					);





// STEEM Power yearly interest rate START 11/29/2016 (1.425%, decreasing every year by roughly 0.075% until it hits a minimum of 0.075% and stays there)
// 1.425 (DO NOT INCLUDE PERCENT SIGN) the first year at 11/29/2016 refactored rates, see above for manual yearly adjustment
$app_config['steempower_yearly_interest'] = 1.425;


// Weeks to power down all STEEM Power holdings
$app_config['steem_powerdown_time'] = 13; 





// POWER USER SETTINGS (ADJUST WITH CARE, OR YOU CAN BREAK THE APP!)




// Standard (default) app charset
$app_config['charset_standard'] = 'UTF-8'; 

// Unicode charset
// UCS-2 is outdated as it only covers 65536 characters of Unicode
// UTF-16BE / UTF-16LE / UTF-16 / UCS-2BE can represent ALL Unicode characters
$app_config['charset_unicode'] = 'UTF-16'; 




// Activate support for CURRENCY-paired markets (like usd/ltc, eur/eth, etc)
// EACH CURRENCY LISTED HERE !MUST HAVE! AN EXISTING BTC/CURRENCY MARKET (within 'market_pairing') 
// in Bitcoin's $app_config['portfolio_assets'] listing (further down in this config file) TO PROPERLY ACTIVATE
// (CAN BE A CRYPTO, !AS LONG AS THERE IS A BITCOIN PAIRING CONFIGURED!)
$app_config['bitcoin_market_currencies'] = array(
						//'lowercase_btc_market_or_stablecoin_pairing' => 'CURRENCY_SYMBOL',
						'aud' => 'A$',
						'bob' => 'Bs ',
						'brl' => 'R$',
						'cad' => 'C$',
						'chf' => 'CHf ',
						'cop' => 'Col$',
						'eur' => '€',
						'eth' => 'Ξ ',
						'gbp' => '£',
						'hkd' => 'HK$',
						'jpy' => 'J¥',
						'ltc' => 'Ł ',
						'mxn' => 'Mex$',
						'nis' => '₪',
						'pkr' => '₨',
						'rub' => '₽',
						'sgd' => 'S$',
						'try' => '₺',
						'tusd' => 'Ⓢ ',
						'usd' => '$',
						'usdc' => 'Ⓢ ',
						'usdt' => '₮ ',
						'vnd' => '₫',
							);





// Activate support for ALTCOIN-paired markets (like doge/ltc, dai/eth, etc)
// EACH ALTCOIN LISTED HERE !MUST HAVE! AN EXISTING 'btc' MARKET (within 'market_pairing') 
// in it's $app_config['portfolio_assets'] listing (further down in this config file) TO PROPERLY ACTIVATE
// ('btc' pairing support IS SKIPPED HERE, as it's ALREADY BUILT-IN to this app)
$app_config['crypto_to_crypto_pairing'] = array(
						//'lowercase_altcoin_abrv' => 'CRYPTO_SYMBOL',
						'eth' => 'Ξ ',
						'ltc' => 'Ł ',
						'xmr' => 'ɱ ',
							);




// DEVELOPER-ONLY CONFIGS, !CHANGE WITH EXTREME CARE!


// TLD-only (Top Level Domain) for each API service that requires multiple calls (for each market)
// Used to throttle these market calls a tiny bit (1.15 seconds), so we don't get easily blacklisted
// (THESE EXCHANGES ARE !NOT! RECOMMENDED TO BE USED AS THE PRIMARY CURRENCY MARKET IN THIS APP,
// AS ON OCCASION THEY CAN BE !UNRELIABLE! IF HIT WITH TOO MANY SEPARATE API CALLS FOR DIFFERENT COINS / ASSETS)
// !MUST BE LOWERCASE!
$app_config['limited_apis'] = array(
						'bit2c.co.il',
						'bitforex.com',
						'bitflyer.com',
						'bitso.com',
						'bitstamp.net',
						'blockchain.info',
						'btcmarkets.net',
						'coinbase.com',
						'cryptofresh.com',
						'dcrdata.org',
						'dogechain.info',
						'etherscan.io',
						'gemini.com',
						'litecoin.net',
						'okcoin.com',
							);




// TLD-extensions-only (Top Level Domain extensions) supported in the get_tld() function
// (NO LEADING DOTS, !MUST BE LOWERCASE!)
$app_config['top_level_domain_map'] = array(
					'co.il',
					'co.uk',
					'com', 
					'com.au',
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
					);
							



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// GENERAL CONFIG -END- //////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// EMAIL-TO-MOBILE-TEXT CONFIG -START- ///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////



/*


Below are the mobile networks supported by DFD Cryptocoin Value's email-to-mobile-text functionality. 


Using your corresponding "Network Name Key" (case-sensitive) listed below, 
add that EXACT name in this config file further above within the $app_config['to_text'] setting as the text network name variable,
to enable email-to-text alerts to your network's mobile phone number.


PLEASE REPORT ANY MISSING / INCORRECT / NON-FUNCTIONAL GATEWAYS HERE, AND I WILL FIX THEM:
https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues
(or you can add / update it yourself right in this configuration, if you know the correct gateway domain name)


*/



// All supported mobile network email-to-text gateway (domain name) configurations
// Network name keys MUST BE LOWERCASE (for reliability / consistency, 
// as these name keys are always called from (forced) lowercase name key lookups)


// DUPLICATE NETWORK NAME KEYS --WILL CANCEL EACH OTHER OUT--, !!USE A UNIQUE NAME FOR EACH KEY!!


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
                        'rogers' => 'pcs.rogers.com',
                        'sasktel' => 'pcs.sasktelmobility.com',
                        'telus' => 'mms.telusmobility.com',
                        'virgin_ca' => 'vmobile.ca',
                        'wind' => 'txt.windmobile.ca',
                        
                        
                        // [COLUMBIA]
                        'claro_co' => 'iclaro.com.co',
                        'movistar_co' => 'movistar.com.co',
                        
                        
                        // [EUROPE]
                        'freebie_sms' => 'smssturen.com',
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
                        'tmobile_uk' => 't-mobile.uk.net',
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
                        'clear_talk' => 'sms.cleartalk.us',
                        'cricket' => 'mms.cricketwireless.net',
                        'cspire' => 'cspire1.com',
                        'gci' => 'mobile.gci.net',
                        'googlefi' => 'msg.fi.google.com',
                        'nextech' => 'sms.ntwls.net',
                        'pioneer' => 'zsend.com',
                        'rogers' => 'pcs.rogers.com',
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
/////////////////// EMAIL-TO-MOBILE-TEXT CONFIG -END- /////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// COIN MARKETS CONFIG -START- ///////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////




// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG

// SEE /DOCUMENTATION-ETC/CONFIG.EXAMPLE.txt FOR A FULL EXAMPLE OF THE CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC WILL BREAK THE APP, BE CAREFUL EDITING THIS CONFIG FILE




$app_config['portfolio_assets'] = array(

                    
                    
                    // BTC
                    'BTC' => array(
                        
                        'coin_name' => 'Bitcoin',
                        'marketcap_website_slug' => 'bitcoin',
                        'market_pairing' => array(
                        
                        				'aud' => array(
                                    		'btcmarkets' => 'BTC/AUD',
                                          'lakebtc' => 'btcaud',
                                                    ),
                                                    
                                    'bob' => array(
                                          'localbitcoins' => 'BOB',
                                                    ),
                                                    
                                    'brl' => array(
                                          'braziliex' => 'btc_brl',
                                                    ),
                                                    
                                    'cad' => array(
                                          'kraken' => 'XXBTZCAD',
                                          'lakebtc' => 'btccad',
                                                    ),
                                                    
                                    'chf' => array(
                                          'lakebtc' => 'btcchf',
                                                    ),
                                                    
                                    'cop' => array(
                                          'localbitcoins' => 'COP',
                                                    ),
                                                    
                                    'eth' => array(
                                          'localbitcoins' => 'ETH',
                                                    ),
                                                    
                                    'eur' => array(
                                          'coinbase' => 'BTC-EUR',
                                          'kraken' => 'XXBTZEUR',
                                          'bitstamp' => 'btceur',
                                          'bitpanda' => 'BTC_EUR',
                                          'bitflyer' => 'BTC_EUR',
                                          'lakebtc' => 'btceur',
                                          'cex' => 'BTC:EUR',
                                          'bitlish' => 'btceur',
                                          'coss' => 'BTC-EUR',
                                                    ),
                                                    
                                    'gbp' => array(
                                          'coinbase' => 'BTC-GBP',
                                          'bitfinex' => 'tBTCGBP',
                                          'lakebtc' => 'btcgbp',
                                          'cex' => 'BTC:GBP',
                                          'bitlish' => 'btcgbp',
                                          'coss' => 'BTC-GBP',
                                                    ),
                                                    
                                    'hkd' => array(
                                          'tidebit' => 'btchkd'
                                                    ),
                                                    
                                    'jpy' => array(
                                          'bitflyer' => 'BTC_JPY',
                                          'lakebtc' => 'btcjpy',
                                          'bitlish' => 'btcjpy',
                                                    ),
                                                    
                                    'ltc' => array(
                                          'localbitcoins' => 'LTC',
                                                    ),
                                                    
                                    'mxn' => array(
                                          'bitso' => 'btc_mxn',
                                                    ),
                                                    
                                    'nis' => array(
                                          'bit2c' => 'BtcNis',
                                                    ),
                                                    
                                    'pkr' => array(
                                          'localbitcoins' => 'PKR',
                                                    ),
                                                    
                                    'rub' => array(
                                          'cex' => 'BTC:RUB',
                                          'bitlish' => 'btcrub',
                                                    ),
                                                    
                                    'sgd' => array(
                                          'lakebtc' => 'btcsgd',
                                                    ),
                                                    
                                    'try' => array(
                                          'btcturk' => 'BTCTRY',
                                                    ),
                                                    
                                    'tusd' => array(
                                          'binance' => 'BTCTUSD',
                                                    ),
                                                    
                                    'usd' => array(
                                          'coinbase' => 'BTC-USD',
                                          'binance_us' => 'BTCUSD',
                                          'bitstamp' => 'btcusd',
                                          'kraken' => 'XXBTZUSD',
                                          'gemini' => 'btcusd',
                                          'localbitcoins' => 'USD',
                                          'bitfinex' => 'tBTCUSD',
                                          'bitflyer' => 'BTC_USD',
                                          'lakebtc' => 'btcusd',
                                          'hitbtc' => 'BTCUSD',
                                          'okcoin' => 'btc_usd',
                                          'livecoin' => 'BTC/USD',
                                          'cex' => 'BTC:USD',
                                          'southxchange' => 'BTC/USD',
                                          'bitlish' => 'btcusd',
                                          'coss' => 'BTC-USD',
                                                    ),
                                                    
                                    'usdc' => array(
                                          'binance' => 'BTCUSDC',
                                                    ),
                                                    
                                    'usdt' => array(
                                          'binance' => 'BTCUSDT',
                                          'btcturk' => 'BTCUSDT',
                                          'huobi' => 'btcusdt',
                                          'okex' => 'BTC-USDT',
                                                    ),
                                                    
                                    'vnd' => array(
                                          'localbitcoins' => 'VND',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // ETH
                    'ETH' => array(
                        
                        'coin_name' => 'Ethereum',
                        'marketcap_website_slug' => 'ethereum',
                        'market_pairing' => array(
                                                    
                                    'brl' => array(
                                          'braziliex' => 'eth_brl',
                                                    ),
                                                    
                                    'btc' => array(
                                          'binance' => 'ETHBTC',
                                          'coinbase' => 'ETH-BTC',
                                          'binance_us' => 'ETHBTC',
                                          'bittrex' => 'BTC-ETH',
                                          'bitstamp' => 'ethbtc',
                                          'gemini' => 'ethbtc',
                                          'kraken' => 'XETHXXBT',
                                          'bitfinex' => 'tETHBTC',
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
                                          'bitlish' => 'ethbtc',
                                                    ),
                                                    
                                    'eur' => array(
                                          'coinbase' => 'ETH-EUR',
                                          'bitstamp' => 'etheur',
                                          'cex' => 'ETH:EUR',
                                          'bitlish' => 'etheur',
                                                    ),
                                                    
                                    'gbp' => array(
                                          'coinbase' => 'ETH-GBP',
                                          'cex' => 'BTC:GBP',
                                          'bitlish' => 'ethgbp',
                                                    ),
                                                    
                                    'hkd' => array(
                                          'tidebit' => 'ethhkd',
                                                    ),
                                                    
                                    'jpy' => array(
                                          'bitflyer' => 'ETH_JPY',
                                          'bitlish' => 'ethjpy',
                                                    ),
                                                    
                                    'mxn' => array(
                                          'bitso' => 'eth_mxn',
                                                    ),
                                                    
                                    'nis' => array(
                                          'bit2c' => 'EthNis',
                                                    ),
                                                    
                                    'rub' => array(
                                          'bitlish' => 'ethrub',
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
                                          'okcoin' => 'eth_usd',
                                          'cex' => 'ETH:USD',
                                          'bitlish' => 'ethusd',
                                          'coss' => 'ETH-USD',
                                                    ),
                                                    
                                    'usdt' => array(
                                        	'binance' => 'ETHUSDT',
                                          'btcturk' => 'ETHUSDT',
                                          'huobi' => 'ethusdt',
                                        	'binance_us' => 'ETHUSDT',
                                          'bittrex' => 'USDT-ETH',
                                          'hitbtc' => 'ETHUSD',
                                          'upbit' => 'USDT-ETH',
                                       	'kucoin' => 'ETH-USDT',
                                          'okex' => 'ETH-USDT',
                                          'poloniex' => 'USDT_ETH',
                                                    ),
                                                    
                                    'usdc' => array(
                                          'binance' => 'ETHUSDC',
                                          'coinbase' => 'ETH-USDC',
                                          'kucoin' => 'ETH-USDC',
                                          'poloniex' => 'USDC_ETH',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // XMR
                    'XMR' => array(
                        
                        'coin_name' => 'Monero',
                        'marketcap_website_slug' => 'monero',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'XMRBTC',
                                          'huobi' => 'xmrbtc',
                                          'bittrex' => 'BTC-XMR',
                                          'bitfinex' => 'tXMRBTC',
                                          'hitbtc' => 'XMRBTC',
                                          'kraken' => 'XXMRXXBT',
                                        	'upbit' => 'BTC-XMR',
                                          'okex' => 'XMR-BTC',
                                          'poloniex' => 'BTC_XMR',
                                          'bitlish' => 'xmrbtc',
                                                    ),
                                                    
                                    'eth' => array(
                                        	'binance' => 'XMRETH',
                                          'huobi' => 'xmreth',
                                          'bittrex' => 'ETH-XMR',
                                          'hitbtc' => 'XMRETH',
                                          'upbit' => 'ETH-XMR',
                                                    ),
                                                    
                                    'usdt' => array(
                                          'huobi' => 'xmrusdt',
                                          'bittrex' => 'USDT-XMR',
                                          'upbit' => 'USDT-XMR',
                                          'okex' => 'XMR-USDT',
                                          'poloniex' => 'USDT_XMR',
                                                    ),
                                                    
                                    'usdc' => array(
                                          'poloniex' => 'USDC_XMR',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // LTC
                    'LTC' => array(
                        
                        'coin_name' => 'Litecoin',
                        'marketcap_website_slug' => 'litecoin',
                        'market_pairing' => array(
                        
                                    'brl' => array(
                                          'braziliex' => 'ltc_brl',
                                                    ),
                                                    
                                    'btc' => array(
                                        'binance' => 'LTCBTC',
                                        'coinbase' => 'LTC-BTC',
                                        'huobi' => 'ltcbtc',
                                        'binance_us' => 'LTCBTC',
                                        'bittrex' => 'BTC-LTC',
                                        'bitstamp' => 'ltcbtc',
                                        'bitfinex' => 'tLTCBTC',
                                        'kraken' => 'XLTCXXBT',
                                        'hitbtc' => 'LTCBTC',
                                        'kucoin' => 'LTC-BTC',
                                        'upbit' => 'BTC-LTC',
                                        'okex' => 'LTC-BTC',
                                        'livecoin' => 'LTC/BTC',
                                        'poloniex' => 'BTC_LTC',
                                        'cryptofresh' => 'OPEN.LTC',
                                        'tradesatoshi' => 'LTC_BTC',
                                        'bitso' => 'ltc_btc',
                                        'braziliex' => 'ltc_btc',
                                        'bitlish' => 'ltcbtc',
                                                    ),
                                                    
                                    'eth' => array(
                                        'binance' => 'LTCETH',
                                        'bittrex' => 'ETH-LTC',
                                        'hitbtc' => 'LTCETH',
                                        'kucoin' => 'LTC-ETH',
                                        'upbit' => 'ETH-LTC',
                                    	 'okex' => 'LTC-ETH',
                                                    ),
                                                    
                                    'eur' => array(
                                          'coinbase' => 'LTC-EUR',
                                          'bitstamp' => 'ltceur',
                                          'cex' => 'LTC:EUR',
                                                    ),
                                                    
                                    'gbp' => array(
                                          'coinbase' => 'LTC-GBP',
                                          'cex' => 'LTC:GBP',
                                                    ),
                                                    
                                    'mxn' => array(
                                          'bitso' => 'ltc_mxn',
                                                    ),
                                                    
                                    'nis' => array(
                                          'bit2c' => 'LtcNis',
                                                    ),
                                          			
                                    'tusd' => array(
                                         'binance' => 'LTCTUSD',
                                                    ),
                                                    
                                    'usd' => array(
                                          'coinbase' => 'LTC-USD',
                                          'kraken' => 'XLTCZUSD',
                                          'bitstamp' => 'ltcusd',
                                          'gemini' => 'ltcusd',
                                          'bitfinex' => 'tLTCUSD',
                                          'okcoin' => 'ltc_usd',
                                          'cex' => 'LTC:USD',
                                                    ),
                                          			
                                    'usdc' => array(
                                         'binance' => 'LTCUSDC',
                                         'poloniex' => 'USDC_LTC',
                                                    ),
                                                    
                                    'usdt' => array(
                                        'binance' => 'LTCUSDT',
                                        'huobi' => 'ltcusdt',
                                        'binance_us' => 'LTCUSDT',
                                        'bittrex' => 'USDT-LTC',
                                        'hitbtc' => 'LTCUSD',
                                        'kucoin' => 'LTC-USDT',
                                        'upbit' => 'USDT-LTC',
                                        'okex' => 'LTC-USDT',
                                        'poloniex' => 'USDT_LTC',
                                          			),
                                                    
                                        ) // market_pairing END
                                        
                    ), // Asset END
                    
                    
                    
                    // DCR
                    'DCR' => array(
                        
                        'coin_name' => 'Decred',
                        'marketcap_website_slug' => 'decred',
                        'market_pairing' => array(
                        
                                    'brl' => array(
                                          'braziliex' => 'dcr_brl'
                                                    ),
                                                    
                                    'btc' => array(
                                        	'binance' => 'DCRBTC',
                                          'bittrex' => 'BTC-DCR',
                                       	'kucoin' => 'DCR-BTC',
                                          'upbit' => 'BTC-DCR',
                                          'okex' => 'DCR-BTC',
                                          'gateio' => 'dcr_btc',
                                          'braziliex' => 'dcr_btc',
                                                    ),
                                                    
                                		'eth' => array(
                                        	'kucoin' => 'DCR-ETH',
                                                    ),
                                                    
                                    'usdt' => array(
                                          'bittrex' => 'USDT-DCR',
                                          'okex' => 'DCR-USDT',
                                          'gateio' => 'dcr_usdt',
                                          			),
                                          			
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // GRIN
                    'GRIN' => array(
                        
                        'coin_name' => 'Grin',
                        'marketcap_website_slug' => 'grin',
                        'market_pairing' => array( 
                        
                                    'btc' => array(
                                         'bittrex_global' => 'BTC-GRIN',
                                    	  'kucoin' => 'GRIN-BTC',
                                         'hitbtc' => 'GRINBTC',
                                         'hotbit' => 'GRIN_BTC',
                                         'gateio' => 'grin_btc',
                                         'poloniex' => 'BTC_GRIN',
                                         'bitforex' => 'coin-btc-grin',
                                         'tradeogre' => 'BTC-GRIN',
                                         'bigone' => 'GRIN-BTC',
                                                    ),
                                                    
                                    'eth' => array(
                                    	  'kucoin' => 'GRIN-ETH',
                                         'hitbtc' => 'GRINETH',
                                         'hotbit' => 'GRIN_ETH',
                                         'gateio' => 'grin_eth',
                                                    ),
                                                    
                                    'nis' => array(
                                          'bit2c' => 'GrinNis',
                                                    ),
                                                    
                                    'usdc' => array(
                                         'poloniex' => 'USDC_GRIN',
                                                    ),
                                                    
                                    'usdt' => array(
                                    	  'kucoin' => 'GRIN-USDT',
                                         'hitbtc' => 'GRINUSD',
                                         'hotbit' => 'GRIN_USDT',
                                         'gateio' => 'grin_usdt',
                                         'bitforex' => 'coin-usdt-grin',
                                         'bigone' => 'GRIN-USDT',
                                                    ),
                                                    
                                        ) // market_pairing END
                                        
                    ), // Asset END
                    
                    
                    
                    // ATOM
                    'ATOM' => array(
                        
                        'coin_name' => 'Cosmos',
                        'marketcap_website_slug' => 'cosmos',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                         'binance' => 'ATOMBTC',
                                         'bittrex_global' => 'BTC-ATOM',
                                         'kraken' => 'ATOMXBT',
                                         'okex' => 'ATOM-BTC',
                                         'hotbit' => 'ATOM_BTC',
                                         'poloniex' => 'BTC_ATOM',
                                         'bitforex' => 'coin-btc-atom',
                                                    ),
                                                    
                                    'eth' => array(
                                         'kraken' => 'ATOMETH',
                                         'okex' => 'ATOM-ETH',
                                         'hotbit' => 'ATOM_ETH',
                                         'bitforex' => 'coin-eth-atom',
                                                    ),
                                                    
                                    'tusd' => array(
                                         'binance' => 'ATOMTUSD',
                                                    ),
                                                    
                                    'usdc' => array(
                                         'binance' => 'ATOMUSDC',
                                         'poloniex' => 'USDC_ATOM',
                                                    ),
                                                    
                                    'usdt' => array(
                                         'hotbit' => 'ATOM_USDT',
                                         'poloniex' => 'USDT_ATOM',
                                         'bitforex' => 'coin-usdt-atom',
                                                    ),
                                                    
                                        ) // market_pairing END
                                        
                    ), // Asset END
                    
                    
                    
                    // KDA
                    'KDA' => array(
                        
                        'coin_name' => 'Kadena',
                        'marketcap_website_slug' => 'kadena',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                         'hotbit' => 'KDA_BTC',
                                                    ),
                                                    
                                    'usdt' => array(
                                         'hotbit' => 'KDA_USDT',
                                                    ),
                                                    
                                        ) // market_pairing END
                                        
                    ), // Asset END
                    
                    
                    
                    // STEEM
                    'STEEM' => array(
                        
                        'coin_name' => 'Steem',
                        'marketcap_website_slug' => 'steem',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'STEEMBTC',
                                          'bittrex' => 'BTC-STEEM',
                                          'hitbtc' => 'STEEMBTC',
                                          'upbit' => 'BTC-STEEM',
                                          'cryptofresh' => 'OPEN.STEEM',
                                                    ),
                                                    
                                    'eth' => array(
                                        	'binance' => 'STEEMETH',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // DOGE
                    'DOGE' => array(
                        
                        'coin_name' => 'Dogecoin',
                        'marketcap_website_slug' => 'dogecoin',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'DOGEBTC',
                                        	'kraken' => 'XXDGXXBT',
                                          'bittrex' => 'BTC-DOGE',
                                          'upbit' => 'BTC-DOGE',
                                          'hitbtc' => 'DOGEBTC',
                                          'hotbit' => 'DOGE_BTC',
                                          'gateio' => 'doge_btc',
                                          'livecoin' => 'DOGE/BTC',
                                          'poloniex' => 'BTC_DOGE',
                                        	'tradesatoshi' => 'DOGE_BTC',
                                                    ),
                                                    
                                    'eth' => array(
                                        	'hotbit' => 'DOGE_ETH',
                                          'hitbtc' => 'DOGEETH',
                                        	'tradesatoshi' => 'DOGE_ETH',
                                         	'bitforex' => 'coin-eth-doge',
                                                    ),
                                                    
                                    'usdc' => array(
                                        	'poloniex' => 'USDC_DOGE',
                                                    ),
                                                    
                                    'usdt' => array(
                                        	'binance' => 'DOGEUSDT',
                                        	'binance_us' => 'DOGEUSDT',
                                          'bittrex' => 'USDT-DOGE',
                                          'hitbtc' => 'DOGEUSD',
                                          'okex' => 'DOGE-USDT',
                                          'poloniex' => 'USDT_DOGE',
                                         	'bitforex' => 'coin-usdt-doge',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // ANT
                    'ANT' => array(
                        
                        'coin_name' => 'Aragon',
                        'marketcap_website_slug' => 'aragon',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'bittrex_global' => 'BTC-ANT',
                                        	'ethfinex' => 'tANTBTC',
                                          'hitbtc' => 'ANTBTC',
                                        	'upbit' => 'BTC-ANT',
                                                    ),
                                                    
                                    'eth' => array(
                                        	'ethfinex' => 'tANTETH',
                                          'upbit' => 'ETH-ANT',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // MANA
                    'MANA' => array(
                        
                        'coin_name' => 'Decentraland',
                        'marketcap_website_slug' => 'decentraland',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'MANABTC',
                                          'bittrex' => 'BTC-MANA',
                                        	'ethfinex' => 'tMNABTC',
                                          'kucoin' => 'MANA-BTC',
                                        	'upbit' => 'BTC-MANA',
                                          'okex' => 'MANA-BTC',
                                          'bitso' => 'mana_btc',
                                          'poloniex' => 'BTC_MANA',
                                                    ),
                                                    
                                    'eth' => array(
                                        	'binance' => 'MANAETH',
                                          'bittrex' => 'ETH-MANA',
                                        	'ethfinex' => 'tMNAETH',
                                          'hitbtc' => 'MANAETH',
                                          'kucoin' => 'MANA-ETH',
                                        	'upbit' => 'ETH-MANA',
                                          'okex' => 'MANA-ETH',
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
                    
                    
                    
                    // GNT
                    'GNT' => array(
                        
                        'coin_name' => 'Golem',
                        'marketcap_website_slug' => 'golem-network-tokens',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'bittrex' => 'BTC-GNT',
                                        	'ethfinex' => 'tGNTBTC',
                                        	'upbit' => 'BTC-GNT',
                                        	'livecoin' => 'GNT/BTC',
                                        	'okex' => 'GNT-BTC',
                                          'bitso' => 'gnt_btc',
                                          'poloniex' => 'BTC_GNT',
                                          'braziliex' => 'gnt_btc',
                                                    ),
                                                    
                                    'eth' => array(
                                          'bittrex' => 'ETH-GNT',
                                        	'ethfinex' => 'tGNTETH',
                                          'upbit' => 'ETH-GNT',
                                                    ),
                                                    
                                    'mxn' => array(
                                          'bitso' => 'gnt_mxn',
                                                    ),
                                                    
                                    'usdc' => array(
                                          'coinbase' => 'GNT-USDC',
                                                    ),
                                                    
                                    'usdt' => array(
                                        	'okex' => 'GNT-USDT',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // DATA
                    'DATA' => array(
                        
                        'coin_name' => 'Streamr DATAcoin',
                        'marketcap_website_slug' => 'streamr-datacoin',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        'binance' => 'DATABTC',
                                        'ethfinex' => 'tDATBTC',
                                        'hitbtc' => 'DATABTC',
                                                    ),
                                                    
                                    'eth' => array(
                                        'binance' => 'DATAETH',
                                        'ethfinex' => 'tDATETH',
                                  		 'hitbtc' => 'DATAETH',
                                        'gateio' => 'data_eth',
                                        'idex' => 'ETH_DATA',
                                                    ),
                                                    
                                    'usdt' => array(
                                         'hitbtc' => 'DATAUSD',
                                         'gateio' => 'data_usdt',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // DAG
                    'DAG' => array(
                        
                        'coin_name' => 'Constellation',
                        'marketcap_website_slug' => 'constellation',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        'kucoin' => 'DAG-BTC',
                                        'hotbit' => 'DAG_BTC',
                                        'hitbtc' => 'DAGBTC',
                                                    ),
                                                    
                                    'eth' => array(
                                        'kucoin' => 'DAG-ETH',
                                        'hotbit' => 'DAG_ETH',
                                        'hitbtc' => 'DAGETH',
                                        'idex' => 'ETH_DAG',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // MYST
                    'MYST' => array(
                        
                        'coin_name' => 'Mysterium',
                        'marketcap_website_slug' => 'mysterium',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'hitbtc' => 'MYSTBTC',
                                                    ),
                                                    
                                    'eth' => array(
                                          'hitbtc' => 'MYSTETH',
                                          'idex' => 'ETH_MYST',
                                                    ),
                                                    
                                        ) // market_pairing END
                        
                    ), // Asset END
                    
                    
                    
                    // DAI
                    'DAI' => array(
                        
                        'coin_name' => 'Dai',
                        'marketcap_website_slug' => 'dai',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        'bittrex' => 'BTC-DAI',
                                        'upbit' => 'BTC-DAI',
                                                    ),
                                                    
                                    'eth' => array(
                                        'bittrex' => 'ETH-DAI',
                                    	 'bitfinex' => 'tDAIETH',
                                                    ),
                                                    
                                    'usd' => array(
                                    	 'kraken' => 'DAIUSD',
                                    	 'bitfinex' => 'tDAIUSD',
                                                    ),
                                                    
                                    'usdc' => array(
                                    	 'coinbase' => 'DAI-USDC',
                                        'hitbtc' => 'DAIUSDC',
                                                    ),
                                                    
                                    'usdt' => array(
                                    	 'kraken' => 'DAIUSDT',
                                                    ),
                                                    
                                        ) // market_pairing END
                                        
                    ), // Asset END
                    
                    
                    
                    // TUSD
                    'TUSD' => array(
                        
                        'coin_name' => 'True USD',
                        'marketcap_website_slug' => 'true-usd',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        'bittrex' => 'BTC-TUSD',
                                        'upbit' => 'BTC-TUSD',
                                                    ),
                                                    
                                    'eth' => array(
                                        'bittrex' => 'ETH-TUSD',
                                        'upbit' => 'ETH-TUSD',
                                                    ),
                                                    
                                    'usdt' => array(
                                    	 'binance' => 'TUSDUSDT',
                                        'bittrex' => 'USDT-TUSD',
                                                    ),
                                                    
                                        ) // market_pairing END
                                        
                    ), // Asset END
                    
                
                
                
); // portfolio_assets END





///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// COIN MARKETS CONFIG -END- /////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////




//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
///////////////////////////////////////////////////////////////////////////////////////////////
// WHEN RE-CONFIGURING APP, LEAVE THIS CODE BELOW HERE, DON'T DELETE BELOW THESE LINES
require_once("app-lib/php/init.php");  // REQUIRED, DON'T DELETE BY ACCIDENT
///////////////////////////////////////////////////////////////////////////////////////////////
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


?>