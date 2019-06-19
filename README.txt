
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

DFD Cryptocoin Values - Open source / free private cryptocurrency investment portfolio tracker, with email / text / Alexa alerts, charts, mining calculators, and other crypto tools
Copyright 2014-2019 GPLv3
Developed by Michael Kilday <mike@dragonfrugal.com>, released free / open source under GPL v3

LIVE DEMO: https://dragonfrugal.com/coin-prices/

Download: https://github.com/taoteh1221/DFD_Cryptocoin_Values/releases

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Donations support further development... 

PAYPAL: https://www.paypal.me/dragonfrugal

XMR: 47mWWjuwPFiPD6t2MaWcMEfejtQpMuz9oj5hJq18f7nvagcmoJwxudKHUppaWnTMPaMWshMWUTPAUX623KyEtukbSMdmpqu

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Questions, feature requests, and bug reports can be filed at the following URLS:

https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues

https://dragonfrugal.com/contact/

Web server setup / install is available for $30 hourly if needed. PM me on Twitter / Skype @ taoteh1221, or contact me using above contact links.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


Just upload to your PHP-based web server (with an FTP client like FileZilla) and you should be all set, unless your host is a strict setup related to file writing permissions, in which case the 'cache' directory should be set to '777' chmod on unix / linux systems (or 'readable / writable' on windows systems). Your web host must have CURL modules activated on your HTTP server. Most web hosting companies provide this "out-of-the-box" already. This app will detect whether or not CURL is setup on your website server. 


################################################################################################################


Setting up a cron job for charts and asset price alerts by email / mobile phone text / amazon alexa notifications (get notifications sent to you, even when you are offline): 

If you want to take advantage of cron job based features like charts, chart data backups, asset price alerts, daily or weekly error log emails / etc, then the file cron.php (located in the primary directory of this app) must be setup as a cron job on your website's web server. Consult your web server host's documentation or help desk, for their particular method of setting up a cron job. Note that you should have it run every X minutes 24/7, based on how often you want alerts / any other cron based features to run. Setting up the cron job to run at least every 15 minutes is a good default time interval to start off with. 

Here is an example cron job command line for reference below (not including any cron parameters your host interface may require), to setup as the "command" within a cron job. Replace system paths in the example with the correct ones for your server (TIP - A very common path to PHP on a server is /usr/bin/php):

/path/to/php -q /home/username/path/to/website/this_app/cron.php


################################################################################################################


Adding / editing / deleting assets and markets in the coins list:

Below is an example for editing your assets / markets into the coin list in the file config.php (located in the primary directory of this app). It's very quick / easy to do (after you get the hang of it, lol). Also see further down in this README for a pre-configured set of default settings and example assets / markets. Currently BTC / XMR / ETH / LTC / USDT (Tether) / TUSD (True USD) / USDC based market pairing is compatible. Contact any supported exchange's help desk if you are unaware of the correct formatting of the trading pair naming you are adding in the configuration file (examples: Kraken has arbitrary Xs inserted in SOME older pair names, HitBTC sometimes has tether pairing without the "T" in the symbol name).


 * USAGE (ADDING / UPDATING COINS) ...API support for (contact me to request adding more): coinbase / binance / bittrex and bittrex_intl / kraken / poloniex / bitstamp / bitfinex and ethfinex / cryptofresh / gemini / hitbtc / livecoin / upbit / kucoin / okex / gateio / graviex / idex / hotbit / tradeogre / bitforex / bigone / tradesatoshi...BTC, XMR, ETH, LTC, USDT, TUSD, and USDC trading pair support
 * Ethereum ICO subtoken support has been built in, but values are static ICO values in ETH
 
 
                    // UPPERCASE_COIN_SYMBOL
                    'UPPERCASE_COIN_SYMBOL' => array(
                        
                        'coin_name' => 'COIN_NAME',
                        'marketcap_website_slug' => 'WEBSITE_SLUG', // Website slug (URL data) on coinmarketcap / coingecko, leave blank if not listed there
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'BTC_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'BTC-COINSYMBOLHERE'
                                                    ),
                                                    
                                    'xmr' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'XMR_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'XMR-COINSYMBOLHERE'
                                                    ),
                                                    
                                    'eth' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'ETH_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'ETH-COINSYMBOLHERE',
                                          'eth_subtokens_ico' => 'ETHSUBTOKENNAME' // Must be defined in $eth_subtokens_ico_values in config.php
                                                    ),
                                                    
                                    'ltc' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'LTC_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'LTC-COINSYMBOLHERE'
                                                    ),
                                                    
                                    'usdt' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'USDT_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'USDT-COINSYMBOLHERE'
                                                    ),
                                                    
                                    'tusd' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'TUSD_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'TUSD-COINSYMBOLHERE'
                                                    ),
                                                    
                                    'usdc' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'USDC_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'USDC-COINSYMBOLHERE'
                                                    )
                                                    
                                          ) // market_pairing END
                        
                    ), // Coin END
                    
      
################################################################################################################              
    
 
BELOW IS AN EXAMPLE SET OF CONFIGURED ASSETS AND DEFAULT SETTINGS. PLEASE NOTE THIS IS PROVIDED TO ASSIST YOU IN ADDING YOUR PARTICULAR FAVORITE ASSETS TO THE DEFAULT LIST, AND !---IN NO WAY---! INDICATES ENDORSEMENT OF !---ANY---! OF THESE ASSETS:


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// GENERAL CONFIG -START- ////////////////////////////////////////////////////



// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG, AND AN EXAMPLE SET OF PRE-CONFIGURED SETTINGS / EXAMPLE ASSETS
// TYPOS LIKE MISSED COMMAS / MISSED SINGLE-STYLE QUOTES / ETC WILL BREAK THE APP, BE CAREFUL EDITING THIS CONFIG FILE



$api_timeout = 13; // Seconds to wait for response from API endpoints. Don't set too low, or you won't get data

$api_strict_ssl = 'on'; // 'on' verifies ALL SSL certificates for HTTPS API servers, 'off' verifies NOTHING (NOT RECOMMENDED in production environment)

$btc_exchange = 'binance'; // Default Bitcoin to USD (or equiv stable coin): coinbase / binance / bitstamp / bitfinex / kraken / gemini / hitbtc / okcoin / livecoin

$marketcap_site = 'coinmarketcap'; // Default marketcap data source: 'coinmarketcap', or 'coingecko'

$marketcap_ranks_max = 200; // Number of marketcap rankings to request from API. Ranks are grabbed 100 per request. Set to 100 or 200 if you are blocked a lot

$marketcap_cache = 15; // Minutes to cache above-mentioned marketcap rankings...start high and test lower, it can be strict

$last_trade_cache = 1; // Minutes to cache real-time exchange data...can be zero to skip cache, but set to at least 1 minute to avoid your IP getting blocked

$chainstats_cache = 15; // Minutes to cache blockchain stats (for mining calculators). Set high initially, can be strict

$delete_old_backups = 7; // Days until old zip archive backups should be deleted (chart data archives, etc)

$purge_error_logs = 5; // Days to keep error logs before purging (deletes logs every X days) start low, especially when using proxies

$mail_error_logs = 'daily'; // 'off', 'daily', 'weekly' Email to / from !MUST BE SET! MAY NOT SEND IN TIMELY FASHION WITHOUT CRON JOB



// ENABLING CHARTS REQUIRES A CRON JOB SETUP (see README.txt for cron job setup information)
// Caches USD + crypto price / volume data for charts of all assets added to $asset_charts_and_alerts (further down in this config file)
// Enables a charts tab / page with historical charts. STILL EARLY CODE (AS OF 5/29/2019), MAY SLOW PAGE LOADS SIGNIFICANTLY UNTIL FURTHER OPTIMIZED
// Disabling will disable EVERYTHING related to the charts features...the page, caching, even the javascript associated with the charts
$charts_page = 'on'; // 'on' / 'off'

// Determines the presets for the chart time period buttons (1day,1week,1month,3month,6month,1year,2year,4year), only used if $charts_page above is on
$charts_update_freq = 4; // How many times per hour did you setup your cron job to run? (see README.txt for cron job setup information) 

// Backup chart data in a zip file in the 'backups' subdirectory (with a secure random 32 character hexadecimal suffix for privacy), only used if $charts_page above is on
$charts_backup_freq = 2; // Every X days backup chart data. 0 disables backups. Email to / from !MUST BE SET! (a download link is emailed to you of the chart data archive)



// IF SMTP EMAIL SENDING --NOT-- USED, FROM email should be REAL address on the website domain, or risk having email blacklisted / sent to junk folder
// IF SMTP EMAIL SENDING --IS-- USED, FROM EMAIL MUST MATCH EMAIL ADDRESS associated with SMTP login (SMTP Email settings are further down below this setting)
$from_email = ''; // MUST BE SET for price alerts and other email features

$to_email = ''; // MUST BE SET for price alerts and other email features

// For asset price alert texts to mobile phones. Attempts to email text if carrier is set AND no textbelt / textlocal config is setup
// CAN BE BLANK. Country format MUST be used: '12223334444||number_only' number_only (for textbelt / textlocal), alltel, att, tmobile, virgin, sprint, verizon, nextel
$to_text = '';

// For asset price alert notifyme alexa notifications (sending Alexa devices notifications for free). 
// NOTE: Amazon's Alexa API will only allow a maximum of 5 notifications every 5 minutes
// CAN BE BLANK. Setup: http://www.thomptronics.com/notify-me
$notifyme_accesscode = '';

// Do NOT use textbelt AND textlocal together. Leave one setting blank, or it will disable using both.

// CAN BE BLANK. For asset price alert textbelt notifications. Setup: https://textbelt.com/
$textbelt_apikey = '';

// CAN BE BLANK. For asset price alert textlocal notifications. Setup: https://www.textlocal.com/integrations/api/
$textlocal_account = ''; // This format MUST be used: 'username||hash_code'



// OPTIONALLY use SMTP authentication TO SEND EMAIL, if you have no reverse lookup that matches domain name (on your home network etc)
// !!USE A THROWAWAY ACCOUNT ONLY!! If web server is hacked, HACKER WOULD THEN HAVE ACCESS YOUR EMAIL LOGIN FROM THIS FILE!!
// If SMTP credentials / settings are filled in, BUT not setup properly, APP EMAILING WILL FAIL
// CAN BE BLANK (PHP's built-in mail function will be automatically used to send email instead)
$smtp_login = ''; //  CAN BE BLANK. This format MUST be used: 'username||password'

$smtp_server = ''; // CAN BE BLANK. This format MUST be used: 'domain_or_ip:port' example: 'example.com:25'

$smtp_secure = ''; // CAN BE BLANK '' for no secure connection, or 'tls', or 'ssl' for secure connections. Make sure port number ABOVE corresponds



// If using proxies and login is required
// Adding a user / pass here will automatically send login details for proxy connections
// CAN BE BLANK. IF using ip address whitelisting instead, MUST BE LEFT BLANK
$proxy_login = ''; // Use format: 'username||password'

// If using proxies, add the ip address / port number here for each one, like examples below (without the double slashes in front)
// CAN BE BLANK. Adding proxies here will automatically choose one randomly for each API request
$proxy_list = array(
					// 'ipaddress1:portnumber1',
					// 'ipaddress2:portnumber2',
					);


// Additional proxy configuration settings (only used if proxies are enabled above)

$proxy_alerts = 'email'; // Alerts for failed proxy data connections. 'none', 'email', 'text', 'notifyme', 'all'

$proxy_alerts_runtime = 'cron'; // Which runtime mode should allow proxy alerts? Options: 'cron', 'ui', 'all'

$proxy_checkup_ok = 'include'; // 'include', or 'ignore' Proxy alerts sent to you even if proxy checkup went OK? (after flagged, started working again when checked) 

$proxy_alerts_freq = 1; // Re-allow same proxy alert(s) after X hours (per ip/port pair, can be 0)



// Asset price alert settings
// Only used if $asset_charts_and_alerts is filled in properly below, AND a cron job is setup (see README.txt for cron job setup information) 

$asset_price_alerts_percent = 7; // Price percent change to send alerts for (WITHOUT percent sign: 15 = 15%). Sends alerts when percent change reached (up or down)

$asset_price_alerts_freq = 4; // Re-allow same asset price alert(s) after X minutes (per asset, set higher if issues with blacklisting...can be 0)

// Minimum 24 hour volume filter. Only allows sending asset price alerts if minimum 24 hour volume reached
// CAN BE 0 TO DISABLE MINIMUM VOLUME FILTERING, NO DECIMALS OR SEPARATORS, NUMBERS ONLY, WITHOUT dollar sign: 250 = $250 , 4500 = $4,500 , etc
// THIS FILTER WILL AUTO-DISABLE IF THERE IS AN ERROR RETRIEVING DATA ON A CERTAIN MARKET (WHEN NOT EVEN A ZERO IS RECEIVED)
$asset_price_alerts_minvolume = 750;

// Refresh cached comparison prices every X days (since last refresh / alert) with latest prices
// Can be 0 to disable refreshing (until price alert triggers a refresh)
$asset_price_alerts_refresh = 0; 

// CHARTS / ASSET PRICE ALERTS SETUP REQUIRES A CRON JOB RUNNING ON YOUR WEBSITE SERVER (see README.txt for cron job setup information) 
// Markets you want charts or asset price change alerts for (alerts sent when $USD value change is equal to or above / below $asset_price_alerts_percent) 
// Delete any double forward slashes from in front of each asset you want to enable charts / price alerts on (or add double slashes in front to disable it)
// NOTE: This list must only contain assets / exchanges / trading pairs included in the primary coin list configuration further down in this config file
// TO ADD MULTIPLE CHARTS / ALERTS FOR SAME ASSET (FOR DIFFERENT EXCHANGES / TRADE PAIRINGS), FORMAT LIKE SO: symbol, symbol-1, symbol-2, symbol-3, etc.
// TO ENABLE CHART AND ALERT = both, TO ENABLE CHART ONLY = chart, TO ENABLE ALERT ONLY = alert
$asset_charts_and_alerts = array(

					// SYMBOL
				// 'symbol' => 'exchange||trade_pairing||both',
				// 'symbol-2' => 'exchange2||trade_pairing2||chart',
				
					// OTHERSYMBOL
				// 'othersymbol' => 'exchange||trade_pairing||both',
				// 'othersymbol-2' => 'exchange2||trade_pairing2||alert',
				// 'othersymbol-3' => 'exchange3||trade_pairing3||chart',
					
					// BTC
					'btc' => 'coinbase||btc||chart',
					'btc-2' => 'binance||btc||both',
					'btc-3' => 'bitstamp||btc||chart',
					'btc-4' => 'kraken||btc||chart',
					'btc-5' => 'gemini||btc||chart',
					'btc-6' => 'bitfinex||btc||chart',
					
					// ETH
					'eth' => 'coinbase||btc||chart',
					'eth-2' => 'bittrex||btc||chart',
					'eth-3' => 'bittrex||usdt||chart',
					'eth-4' => 'poloniex||btc||chart',
					'eth-5' => 'poloniex||usdt||chart',
					'eth-6' => 'kraken||btc||chart',
					'eth-7' => 'binance||usdt||both',
					
					// XMR
					'xmr' => 'bittrex||btc||chart',
					'xmr-2' => 'bittrex||eth||chart',
					'xmr-3' => 'poloniex||btc||chart',
					'xmr-4' => 'binance||btc||both',
					'xmr-5' => 'binance||eth||chart',
					
					// LTC
					'ltc' => 'bittrex||btc||chart',
					'ltc-2' => 'bittrex||eth||chart',
					'ltc-3' => 'poloniex||btc||chart',
					'ltc-4' => 'poloniex||xmr||chart',
					'ltc-5' => 'binance||usdt||both',
					'ltc-6' => 'binance||eth||chart',
					
					// DCR
					'dcr' => 'bittrex||btc||chart',
					'dcr-2' => 'bittrex||usdt||chart',
					'dcr-3' => 'binance||btc||both',
					'dcr-4' => 'kucoin||btc||chart',
					'dcr-5' => 'kucoin||eth||chart',
					
					// GRIN
					'grin' => 'poloniex||btc||both',
					'grin-2' => 'bittrex_intl||btc||chart',
					'grin-3' => 'bittrex_intl||usdt||chart',
					'grin-4' => 'gateio||usdt||chart',
					'grin-5' => 'kucoin||btc||chart',
					'grin-6' => 'hitbtc||btc||chart',
					'grin-7' => 'hotbit||btc||chart',
					
					// ATOM
					'atom' => 'poloniex||btc||chart',
					'atom-2' => 'kraken||btc||chart',
					'atom-3' => 'binance||btc||both',
					'atom-4' => 'binance||tusd||chart',
					'atom-5' => 'binance||usdc||chart',
					'atom-6' => 'bittrex_intl||btc||chart',
					'atom-7' => 'bittrex_intl||eth||chart',
					'atom-8' => 'bittrex_intl||usdt||chart',
					'atom-9' => 'okex||btc||chart',
					'atom-10' => 'okex||eth||chart',
					
					// STEEM
					'steem' => 'bittrex||btc||chart',
					'steem-2' => 'poloniex||btc||chart',
					'steem-3' => 'binance||btc||both',
					
					// ANT
					'ant' => 'bittrex_intl||btc||both',
					'ant-2' => 'hitbtc||btc||chart',
					'ant-3' => 'ethfinex||btc||chart',
					
					// MANA
					'mana' => 'bittrex||btc||chart',
					'mana-2' => 'poloniex||btc||chart',
					'mana-3' => 'binance||btc||both',
					'mana-4' => 'kucoin||btc||chart',
					'mana-5' => 'ethfinex||btc||chart',
					
					// GNT
					'gnt' => 'bittrex||btc||both',
					'gnt-2' => 'poloniex||btc||chart',
					'gnt-3' => 'ethfinex||btc||chart',
					
					// DATA
					'data' => 'hitbtc||btc||chart',
					'data-2' => 'binance||btc||both',
					
					//MYST
					'myst' => 'hitbtc||btc||both',
					'myst-2' => 'hitbtc||eth||alert',
					'myst-3' => 'idex||eth||alert',
					
					
					);
// END $asset_charts_and_alerts



// Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
$eth_subtokens_ico_values = array(
                        'ETHSUBTOKENNAME' => '0.15',
                        'GOLEM' => '0.001',
                        'ARAGON' => '0.01',
                        'DECENTRALAND' => '0.00008',
                        );



// Mining rewards for different platforms (to prefill editable mining calculator forms)
$mining_rewards = array(
					'btc' => '12.5',
					'eth' => '2',
					'xmr' => monero_reward(),  // (2^64 - 1 - current_supply * 10^12) * 2^-19 * 10^-12
					'ltc' => '25',
					'dcr' => ( decred_api('subsidy', 'work_reward') / 100000000 ),
					);



// STEEM Power yearly interest rate START 11/29/2016 (1.425%, decreasing every year by roughly 0.075% until it hits a minimum of 0.075% and stays there)
// 1.425 (DO NOT INCLUDE PERCENT SIGN) the first year at 11/29/2016 refactored rates, see above for manual yearly adjustment
$steempower_yearly_interest = 1.425;

// Weeks to power down all STEEM Power holdings
$steem_powerdown_time = 13; 



/////////////////// GENERAL CONFIG -END- //////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// COIN MARKETS CONFIG -START- ///////////////////////////////////////////////



// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG, AND AN EXAMPLE SET OF PRE-CONFIGURED SETTINGS / EXAMPLE ASSETS
// TYPOS LIKE MISSED COMMAS / MISSED SINGLE-STYLE QUOTES / ETC WILL BREAK THE APP, BE CAREFUL EDITING THIS CONFIG FILE



$coins_list = array(

                    
                    
                    // BTC
                    'BTC' => array(
                        
                        'coin_name' => 'Bitcoin',
                        'marketcap_website_slug' => 'bitcoin',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'coinbase' => 'coinbase',
                                          'binance' => 'binance',
                                          'bitstamp' => 'bitstamp',
                                          'kraken' => 'XXBTZUSD',
                                          'gemini' => 'gemini',
                                          'bitfinex' => 'bitfinex',
                                          'hitbtc' => 'hitbtc',
                                          'okcoin' => 'okcoin',
                                          'livecoin' => 'livecoin'
                                                    )
                                                    
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                    
                    // ETH
                    'ETH' => array(
                        
                        'coin_name' => 'Ethereum',
                        'marketcap_website_slug' => 'ethereum',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'coinbase' => 'ETH-BTC',
                                          'binance' => 'ETHBTC',
                                          'bittrex' => 'BTC-ETH',
                                          'poloniex' => 'BTC_ETH',
                                          'bitstamp' => 'ethbtc',
                                          'gemini' => 'ethbtc',
                                          'kraken' => 'XETHXXBT',
                                          'bitfinex' => 'tETHBTC',
                                          'hitbtc' => 'ETHBTC',
                                          'upbit' => 'BTC-ETH',
                                          'kucoin' => 'ETH-BTC',
                                          'okex' => 'ETH-BTC',
                                          'livecoin' => 'ETH/BTC',
                                          'cryptofresh' => 'OPEN.ETH'
                                                    ),
                                                    
                                    'usdt' => array(
                                        	'binance' => 'ETHUSDT',
                                          'bittrex' => 'USDT-ETH',
                                          'poloniex' => 'USDT_ETH',
                                          'hitbtc' => 'ETHUSD',
                                          'upbit' => 'USDT-ETH',
                                          'okex' => 'ETH-USDT'
                                                    ),
                                                    
                                    'tusd' => array(
                                          'binance' => 'ETHTUSD'
                                                    )
                                                    
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                    
                    // XMR
                    'XMR' => array(
                        
                        'coin_name' => 'Monero',
                        'marketcap_website_slug' => 'monero',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'XMRBTC',
                                          'bittrex' => 'BTC-XMR',
                                          'poloniex' => 'BTC_XMR',
                                          'bitfinex' => 'tXMRBTC',
                                          'hitbtc' => 'XMRBTC',
                                          'kraken' => 'XXMRXXBT',
                                        	'upbit' => 'BTC-XMR',
                                          'okex' => 'XMR-BTC',
                                          'livecoin' => 'XMR/BTC'
                                                    ),
                                                    
                                    'eth' => array(
                                        	'binance' => 'XMRETH',
                                          'bittrex' => 'ETH-XMR',
                                          'hitbtc' => 'XMRETH',
                                          'upbit' => 'ETH-XMR'
                                                    ),
                                                    
                                    'usdt' => array(
                                          'bittrex' => 'USDT-XMR',
                                          'poloniex' => 'USDT_XMR',
                                          'upbit' => 'USDT-XMR',
                                          'okex' => 'XMR-USDT'
                                                    )
                                                    
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                    
                    // LTC
                    'LTC' => array(
                        
                        'coin_name' => 'Litecoin',
                        'marketcap_website_slug' => 'litecoin',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        'coinbase' => 'LTC-BTC',
                                        'binance' => 'LTCBTC',
                                        'bittrex' => 'BTC-LTC',
                                        'poloniex' => 'BTC_LTC',
                                        'bitstamp' => 'ltcbtc',
                                        'bitfinex' => 'tLTCBTC',
                                        'kraken' => 'XLTCXXBT',
                                        'hitbtc' => 'LTCBTC',
                                        'kucoin' => 'LTC-BTC',
                                        'upbit' => 'BTC-LTC',
                                        'okex' => 'LTC-BTC',
                                        'livecoin' => 'LTC/BTC',
                                        'cryptofresh' => 'OPEN.LTC',
                                        'tradesatoshi' => 'LTC_BTC'
                                                    ),
                                                    
                                    'xmr' => array(
                                        'poloniex' => 'XMR_LTC'
                                                    ),
                                                    
                                    'eth' => array(
                                        'binance' => 'LTCETH',
                                        'bittrex' => 'ETH-LTC',
                                        'hitbtc' => 'LTCETH',
                                        'kucoin' => 'LTC-ETH',
                                        'upbit' => 'ETH-LTC',
                                    	 'okex' => 'LTC-ETH'
                                                    ),
                                                    
                                    'usdt' => array(
                                        'binance' => 'LTCUSDT',
                                        'bittrex' => 'USDT-LTC',
                                        'poloniex' => 'USDT_LTC',
                                        'hitbtc' => 'LTCUSD',
                                        'kucoin' => 'LTC-USDT',
                                        'upbit' => 'USDT-LTC',
                                        'okex' => 'LTC-USDT'
                                          			),
                                          			
                                    'tusd' => array(
                                         'binance' => 'LTCTUSD'
                                                    )
                                                    
                                        ) // market_pairing END
                                        
                    ), // Coin END
                    
                    
                    // DCR
                    'DCR' => array(
                        
                        'coin_name' => 'Decred',
                        'marketcap_website_slug' => 'decred',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'DCRBTC',
                                          'bittrex' => 'BTC-DCR',
                                       	'kucoin' => 'DCR-BTC',
                                          'upbit' => 'BTC-DCR',
                                          'okex' => 'DCR-BTC',
                                          'gateio' => 'dcr_btc'
                                                    ),
                                                    
                                		'eth' => array(
                                        	'kucoin' => 'DCR-ETH'
                                                    ),
                                                    
                                    'usdt' => array(
                                          'bittrex' => 'USDT-DCR',
                                          'okex' => 'DCR-USDT',
                                          'gateio' => 'dcr_usdt'
                                          			)
                                          			
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                    
                    // GRIN
                    'GRIN' => array(
                        
                        'coin_name' => 'Grin',
                        'marketcap_website_slug' => 'grin',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                         'poloniex' => 'BTC_GRIN',
                                         'bittrex_intl' => 'BTC-GRIN',
                                    	  'kucoin' => 'GRIN-BTC',
                                         'hitbtc' => 'GRINBTC',
                                         'hotbit' => 'GRIN_BTC',
                                         'gateio' => 'grin_btc',
                                         'bitforex' => 'coin-btc-grin',
                                         'tradeogre' => 'BTC-GRIN',
                                         'bigone' => 'GRIN-BTC'
                                                    ),
                                                    
                                    'eth' => array(
                                    	  'kucoin' => 'GRIN-ETH',
                                         'hitbtc' => 'GRINETH',
                                         'hotbit' => 'GRIN_ETH',
                                         'gateio' => 'grin_eth',
                                         'bitforex' => 'coin-eth-grin'
                                                    ),
                                                    
                                    'usdt' => array(
                                         'bittrex_intl' => 'USDT-GRIN',
                                    	  'kucoin' => 'GRIN-USDT',
                                         'hitbtc' => 'GRINUSD',
                                         'hotbit' => 'GRIN_USDT',
                                         'gateio' => 'grin_usdt',
                                         'bitforex' => 'coin-usdt-grin',
                                         'bigone' => 'GRIN-USDT'
                                                    )
                                                    
                                        ) // market_pairing END
                                        
                    ), // Coin END
                    
                    
                    // ATOM
                    'ATOM' => array(
                        
                        'coin_name' => 'Cosmos',
                        'marketcap_website_slug' => 'cosmos',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                         'binance' => 'ATOMBTC',
                                         'poloniex' => 'BTC_ATOM',
                                         'bittrex_intl' => 'BTC-ATOM',
                                         'kraken' => 'ATOMXBT',
                                         'okex' => 'ATOM-BTC',
                                         'hotbit' => 'ATOM_BTC',
                                         'bitforex' => 'coin-btc-atom'
                                                    ),
                                                    
                                    'eth' => array(
                                         'kraken' => 'ATOMETH',
                                         'bittrex_intl' => 'ETH-ATOM',
                                         'okex' => 'ATOM-ETH',
                                         'hotbit' => 'ATOM_ETH',
                                         'bitforex' => 'coin-eth-atom'
                                                    ),
                                                    
                                    'usdt' => array(
                                         'poloniex' => 'USDT_ATOM',
                                         'bittrex_intl' => 'USDT-ATOM',
                                         'hotbit' => 'ATOM_USDT',
                                         'bitforex' => 'coin-usdt-atom'
                                                    ),
                                                    
                                    'tusd' => array(
                                         'binance' => 'ATOMTUSD'
                                                    ),
                                                    
                                    'usdc' => array(
                                         'binance' => 'ATOMUSDC'
                                                    )
                                                    
                                        ) // market_pairing END
                                        
                    ), // Coin END
                    
                    
                    // STEEM
                    'STEEM' => array(
                        
                        'coin_name' => 'Steem',
                        'marketcap_website_slug' => 'steem',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'STEEMBTC',
                                          'bittrex' => 'BTC-STEEM',
                                          'poloniex' => 'BTC_STEEM',
                                          'hitbtc' => 'STEEMBTC',
                                          'upbit' => 'BTC-STEEM',
                                          'livecoin' => 'STEEM/BTC',
                                          'cryptofresh' => 'OPEN.STEEM'
                                                    ),
                                                    
                                    'eth' => array(
                                        	'binance' => 'STEEMETH',
                                          'poloniex' => 'ETH_STEEM'
                                                    )
                                                    
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                    
                    // ANT
                    'ANT' => array(
                        
                        'coin_name' => 'Aragon',
                        'marketcap_website_slug' => 'aragon',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'bittrex_intl' => 'BTC-ANT',
                                        	'ethfinex' => 'tANTBTC',
                                          'hitbtc' => 'ANTBTC',
                                        	'upbit' => 'BTC-ANT'
                                                    ),
                                                    
                                    'eth' => array(
                                          'bittrex_intl' => 'ETH-ANT',
                                        	'ethfinex' => 'tANTETH',
                                          'upbit' => 'ETH-ANT'
                                                    )
                                                    
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                    
                    // MANA
                    'MANA' => array(
                        
                        'coin_name' => 'Decentraland',
                        'marketcap_website_slug' => 'decentraland',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        	'binance' => 'MANABTC',
                                          'bittrex' => 'BTC-MANA',
                                          'poloniex' => 'BTC_MANA',
                                        	'ethfinex' => 'tMNABTC',
                                          'kucoin' => 'MANA-BTC',
                                        	'upbit' => 'BTC-MANA',
                                          'okex' => 'MANA-BTC'
                                                    ),
                                                    
                                    'eth' => array(
                                        	'binance' => 'MANAETH',
                                          'bittrex' => 'ETH-MANA',
                                        	'ethfinex' => 'tMNAETH',
                                          'hitbtc' => 'MANAETH',
                                          'kucoin' => 'MANA-ETH',
                                        	'upbit' => 'ETH-MANA',
                                          'okex' => 'MANA-ETH'
                                                    ),
                                                    
                                    'usdt' => array(
                                          'hitbtc' => 'MANAUSD',
                                          'okex' => 'MANA-USDT'
                                                    )
                                                    
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                    
                    // GNT
                    'GNT' => array(
                        
                        'coin_name' => 'Golem',
                        'marketcap_website_slug' => 'golem-network-tokens',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'bittrex' => 'BTC-GNT',
                                          'poloniex' => 'BTC_GNT',
                                        	'ethfinex' => 'tGNTBTC',
                                        	'upbit' => 'BTC-GNT',
                                        	'livecoin' => 'GNT/BTC',
                                        	'okex' => 'GNT-BTC'
                                                    ),
                                                    
                                    'eth' => array(
                                          'bittrex' => 'ETH-GNT',
                                          'poloniex' => 'ETH_GNT',
                                        	'ethfinex' => 'tGNTETH',
                                          'upbit' => 'ETH-GNT',
                                        	'livecoin' => 'GNT/ETH',
                                        	'okex' => 'GNT-ETH'
                                                    ),
                                                    
                                    'usdt' => array(
                                        	'okex' => 'GNT-USDT'
                                                    )
                                                    
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                    
                    // DATA
                    'DATA' => array(
                        
                        'coin_name' => 'Streamr DATAcoin',
                        'marketcap_website_slug' => 'streamr-datacoin',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        'binance' => 'DATABTC',
                                        'ethfinex' => 'tDATBTC',
                                        'hitbtc' => 'DATABTC'
                                                    ),
                                                    
                                    'eth' => array(
                                        'binance' => 'DATAETH',
                                        'ethfinex' => 'tDATETH',
                                  		 'hitbtc' => 'DATAETH',
                                        'gateio' => 'data_eth',
                                        'idex' => 'ETH_DATA'
                                                    ),
                                                    
                                    'usdt' => array(
                                         'hitbtc' => 'DATAUSD',
                                         'gateio' => 'data_usdt',
                                         'bitforex' => 'coin-usdt-data'
                                                    )
                                                    
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                    
                    // MYST
                    'MYST' => array(
                        
                        'coin_name' => 'Mysterium',
                        'marketcap_website_slug' => 'mysterium',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'hitbtc' => 'MYSTBTC',
                                          'bigone' => 'MYST-BTC'
                                                    ),
                                                    
                                    'eth' => array(
                                          'hitbtc' => 'MYSTETH',
                                          'idex' => 'ETH_MYST'
                                                    )
                                                    
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                    
                    // TUSD
                    'TUSD' => array(
                        
                        'coin_name' => 'True USD',
                        'marketcap_website_slug' => 'true-usd',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                        'bittrex' => 'BTC-TUSD',
                                        'upbit' => 'BTC-TUSD'
                                                    ),
                                                    
                                    'eth' => array(
                                        'bittrex' => 'ETH-TUSD',
                                        'upbit' => 'ETH-TUSD'
                                                    ),
                                                    
                                    'usdt' => array(
                                    	 'binance' => 'TUSDUSDT',
                                        'bittrex' => 'USDT-TUSD'
                                                    )
                                                    
                                        ) // market_pairing END
                                        
                    ), // Coin END
                    
                    
                    // Misc. USD Assets
                    'USD' => array(
                        
                        'coin_name' => 'Misc. USD Assets',
                        'marketcap_website_slug' => '',
                        'market_pairing' => array(
                        
                                    'btc' => array(
                                          'usd_assets' => 'usdtobtc'
                                                    ),
                                                    
                                    'xmr' => array(
                                          'usd_assets' => 'usdtoxmr'
                                                    ),
                                                    
                                    'eth' => array(
                                          'usd_assets' => 'usdtoeth'
                                                    ),
                                                    
                                    'ltc' => array(
                                          'usd_assets' => 'usdtoltc'
                                                    )
                                                    
                                        ) // market_pairing END
                        
                    ), // Coin END
                    
                
                
); // coins_list END



/////////////////// COIN MARKETS CONFIG -END- /////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

