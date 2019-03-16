<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

// Forbid direct access to this file
if ( realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    exit;
}

error_reporting(0); // Turn off all error reporting on production servers (0), or enable (1)

require_once("app.lib/php/functions/loader.php");
require_once("app.lib/php/init.php");

// WHEN RE-CONFIGURING COIN DATA, LEAVE THIS CODE ABOVE HERE, DON'T DELETE ABOVE THIS LINE
///////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// GENERAL CONFIG -START- ////////////////////////////////////////////////////

// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG, AND AN EXAMPLE SET OF PRE-CONFIGURED SETTINGS / ASSETS

$api_timeout = 15; // Seconds to wait for response from API endpoints

$purge_error_logs = 4; // Days to keep error logs before purging (deletes logs every X days) start low, especially when using proxies

$mail_error_logs = 'daily'; // 'no', 'daily', 'weekly' Email to / from !MUST BE SET! further down in this config file. MAY NOT BE RELIABLE WITHOUT A CRON JOB

$btc_exchange = 'binance'; // Default Bitcoin to USD: binance / coinbase / bitfinex / gemini / okcoin / bitstamp / kraken / hitbtc / gatecion / livecoin

$marketcap_site = 'coinmarketcap'; // Default marketcap data source: 'coinmarketcap', or 'coingecko'

$marketcap_ranks_max = 200; // Number of marketcap rankings to request from API. Ranks are grabbed 100 per request. Set to 100 if you are throttled a lot.

$marketcap_cache = 15; // Minutes to cache marketcap data...start high and test lower, it can be strict

$last_trade_cache = 1; // Minutes to cache real-time exchange data...can be zero to skip cache, set at least 1 minute to avoid your IP getting blocked

$chainstats_cache = 20; // Minutes to cache blockchain stats (for mining calculators)


// If using proxies and login is required
// Adding a user / pass here will automatically send login details for proxy connections
// If using ip address whitelisting instead, MUST BE LEFT BLANK
$proxy_login = ''; // Use format: 'username:password'

// If using proxies, add the ip address / port number here for each one, like examples below (without the double slashes in front)
// Adding proxies here will automatically choose one randomly for each API request
$proxy_list = array(
					// 'ipaddress1:portnumber1',
					// 'ipaddress2:portnumber2',
					);

$proxy_alerts = 'email'; // Alerts for failed proxy data connections. 'none', 'email', 'text', 'notifyme', 'all'

$proxy_alerts_runtime = 'cron'; // Which runtime mode should allow proxy alerts? Options: 'cron', 'ui', 'all'

$proxy_checkup_ok = 'ignore'; // 'include', or 'ignore' Proxy alerts even if checkup went OK? (after flagged, started working again when checked) 

$proxy_alerts_freq = 1; // Re-allow proxy alerts after X hours (per ip/port pair, can be 0)


// OPTIONALLY use SMTP authentication to send email, if you have no reverse lookup that matches domain name (on your home network etc)
// !!USE A THROWAWAY ACCOUNT ONLY!! If web server is hacked, HACKER WOULD THEN HAVE ACCESS YOUR EMAIL LOGIN FROM THIS FILE!!
// If SMTP credentials / settings are filled in, BUT not setup properly, APP EMAILING WILL FAIL
// SMTP SETTINGS CAN BE BLANK (PHP's built-in mail() function will be used instead)
$smtp_login = ''; //  CAN BE BLANK. This format MUST be used: 'username|password'

$smtp_server = ''; // CAN BE BLANK. This format MUST be used: 'domain_or_ip:port' example: 'example.com:25'

$smtp_secure = ''; // CAN BE BLANK '' for no secure connection, or 'tls', or 'ssl' for secure connections. Make sure port number ABOVE corresponds


// IF SMTP EMAIL --NOT-- USED, FROM email should be REAL address on the website domain, or risk having email blacklisted / sent to junk folder
// IF SMTP EMAIL --IS-- USED, FROM EMAIL MUST MATCH EMAIL ADDRESS associated with SMTP login
$from_email = ''; // For email features this MUST BE SET

$to_email = ''; // For email features this MUST BE SET

// For exchange price alert texts, CAN BE BLANK. Attempts to email text if carrier is set AND no textbelt / textlocal config is setup
 // Country format MUST be used: '12223334444|number_only' number_only (for textbelt / textlocal), alltel, att, tmobile, virgin, sprint, verizon, nextel
$to_text = '';

// For exchange price alert notifyme alexa notifications (sending Alexa devices notifications for free), CAN BE BLANK. 
// Setup: http://www.thomptronics.com/notify-me
$notifyme_accesscode = '';

// Do NOT use textbelt AND textlocal together. Leave one setting blank, or it will disable using both.

// For exchange price alert textbelt notifications, CAN BE BLANK. Setup: https://textbelt.com/
$textbelt_apikey = '';

// For exchange price alert textlocal notifications, CAN BE BLANK. Setup: https://www.textlocal.com/integrations/api/
$textlocal_account = ''; // This format MUST be used: 'username|hash_code'


$exchange_price_alerts_freq = 20; // Re-allow cron job price alerts after X minutes (per asset, set higher if issues with blacklisting...can be 0)

$exchange_price_alerts_percent = 11; // Price percentage change (WITHOUT percent sign: 15 = 15%), sends alerts when percent change reached (up or down)

// Refresh comparison prices every X days (since last refresh / alert) with latest prices...can be 0 to disable refreshing (until price alert triggered)
$exchange_price_alerts_refresh = 0; 

// EXCHANGE PRICE CHANGE ALERTS REQUIRES CRON JOB SETUP (see README.txt for cron job setup information) 
// Markets you want exchange price change alerts for (alert sent when $USD value change is equal to or above / below $exchange_price_alerts_percent) 
// Delete any double forward slashes from in front of each asset you want to enable cron job price alerts on (or add double slash to disable alerts)
// NOTE: This list must only contain assets / exchanges / trading pairs included in the primary coin data configuration further down in this config file
// TO ADD MULTIPLE ALERTS FOR SAME ASSET (FOR DIFFERENT EXCHANGES / TRADE PAIRINGS), FORMAT LIKE SO: symbol, symbol-1, symbol-2, etc.
$exchange_price_alerts = array(
					'btc' => 'bitstamp|btc', // exchange|trade_pairing
					'btc-2' => 'binance|btc', // exchange|trade_pairing
					'eth' => 'binance|usdt', // exchange|trade_pairing
					'eth-2' => 'bitstamp|btc', // exchange|trade_pairing
					'xmr' => 'binance|btc', // exchange|trade_pairing
					'dcr' => 'binance|btc', // exchange|trade_pairing
					'dcr-2' => 'bittrex|usdt', // exchange|trade_pairing
					'tusd' => 'binance|btc', // exchange|trade_pairing
				//	'dash' => 'bittrex|btc', // exchange|trade_pairing
				//	'ltc' => 'bittrex|btc', // exchange|trade_pairing
					'steem' => 'bittrex|btc', // exchange|trade_pairing
					'mana' => 'binance|btc', // exchange|trade_pairing
					'ant' => 'bittrex|btc', // exchange|trade_pairing
				//	'zrx' => 'bittrex|btc', // exchange|trade_pairing
					'zil' => 'binance|btc', // exchange|trade_pairing
				//	'trac' => 'kucoin|btc', // exchange|trade_pairing
				//	'snt' => 'bittrex|btc', // exchange|trade_pairing
				//	'gnt' => 'bittrex|btc', // exchange|trade_pairing
				//	'fct' => 'bittrex|btc', // exchange|trade_pairing
					'xlm' => 'bittrex|btc', // exchange|trade_pairing
					'ada' => 'bittrex|btc', // exchange|trade_pairing
					'rvn' => 'binance|btc', // exchange|trade_pairing
					'grin' => 'hotbit|btc', // exchange|trade_pairing
					'beam' => 'hotbit|btc', // exchange|trade_pairing
					'myst' => 'hitbtc|btc', // exchange|trade_pairing
					'myst-2' => 'hitbtc|eth', // exchange|trade_pairing
					'myst-3' => 'idex|eth' // exchange|trade_pairing
					);


// Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
$eth_subtokens_ico_values = array(
                        'ETHSUBTOKENNAME' => '0.15',
                        'GOLEM' => '0.001',
                        'SWARMCITY' => '0.0133333333333333',
                        'ARAGON' => '0.01',
                        'STATUS' => '0.0001',
                        'INVESTFEED' => '0.0001',
                        '0XPROJECT' => '0.00016929425',
                        'DECENTRALAND' => '0.00008'
                        );


// Mining rewards for different platforms (to prefill editable mining calculator forms)
$mining_rewards = array(
					'btc' => '12.5',
					'eth' => '2',
					'xmr' => monero_reward(),  // (2^64 - 1 - current_supply * 10^12) * 2^-19 * 10^-12
					'ltc' => '25',
					'dcr' => ( decred_api('subsidy', 'work_reward') / 100000000 ),
					'rvn' => '5000'
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

$coins_list = array(

                    // Misc. USD Assets
                    'USD' => array(
                        
                        'coin_name' => 'Misc. USD Assets',
                        'coin_symbol' => 'USD',
                        'marketcap-website-slug' => '',
                        'ico' => 'no',
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
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // TUSD
                    'TUSD' => array(
                        
                        'coin_name' => 'True USD',
                        'coin_symbol' => 'TUSD',
                        'marketcap-website-slug' => 'true-usd',
                        'ico' => 'yes',
                        'market_pairing' => array(
                                    'btc' => array(
                                        'bittrex' => 'BTC-TUSD',
                                        'upbit' => 'BTC-TUSD',
                                        'binance' => 'TUSDBTC'
                                                    ),
                                    'eth' => array(
                                        'bittrex' => 'ETH-TUSD',
                                        'upbit' => 'ETH-TUSD',
                                        'binance' => 'TUSDETH'
                                                    ),
                                    'usdt' => array(
                                        'bittrex' => 'USDT-TUSD'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // BTC
                    'BTC' => array(
                        
                        'coin_name' => 'Bitcoin',
                        'coin_symbol' => 'BTC',
                        'marketcap-website-slug' => 'bitcoin',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'coinbase' => 'coinbase',
                                          'binance' => 'binance',
                                          'bitstamp' => 'bitstamp',
                                          'okcoin' => 'okcoin',
                                          'bitfinex' => 'bitfinex',
                                          'kraken' => 'kraken',
                                          'gemini' => 'gemini',
                                          'hitbtc' => 'hitbtc',
                                          'gatecoin' => 'gatecoin',
                                          'livecoin' => 'livecoin'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // ETH
                    'ETH' => array(
                        
                        'coin_name' => 'Ethereum',
                        'coin_symbol' => 'ETH',
                        'marketcap-website-slug' => 'ethereum',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_ETH',
                                          'kraken' => 'XETHXXBT',
                                          'coinbase' => 'ETH-BTC',
                                          'hitbtc' => 'ETHBTC',
                                          'gatecoin' => 'ETHBTC',
                                          'bitfinex' => 'tETHBTC',
                                          'bitstamp' => 'ethbtc',
                                          'gemini' => 'ethbtc',
                                          'bittrex' => 'BTC-ETH',
                                          'upbit' => 'BTC-ETH',
                                          'binance' => 'ETHBTC',
                                          'kucoin' => 'ETH-BTC',
                                          'okex' => 'eth_btc',
                                          'livecoin' => 'ETH/BTC',
                                          'liqui' => 'eth_btc',
                                          'bter' => 'eth_btc',
                                          'cryptofresh' => 'OPEN.ETH'
                                                    ),
                                    'ltc' => array(
                                          'cryptopia' => 'ETH/LTC'
                                                    ),
                                    'usdt' => array(
                                          'poloniex' => 'USDT_ETH',
                                          'bittrex' => 'USDT-ETH',
                                          'upbit' => 'USDT-ETH',
                                        	'binance' => 'ETHUSDT',
                                          'hitbtc' => 'ETHUSD',
                                          'liqui' => 'eth_usdt',
                                          'okex' => 'eth_usdt'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // XMR
                    'XMR' => array(
                        
                        'coin_name' => 'Monero',
                        'coin_symbol' => 'XMR',
                        'marketcap-website-slug' => 'monero',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_XMR',
                                          'bittrex' => 'BTC-XMR',
                                        	'upbit' => 'BTC-XMR',
                                          'bitfinex' => 'tXMRBTC',
                                        	'binance' => 'XMRBTC',
                                          'hitbtc' => 'XMRBTC',
                                          'kraken' => 'XXMRXXBT',
                                          'cryptopia' => 'XMR/BTC',
                                          'okex' => 'xmr_btc',
                                          'bter' => 'xmr_btc',
                                          'livecoin' => 'XMR/BTC'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-XMR',
                                          'upbit' => 'ETH-XMR',
                                          'hitbtc' => 'XMRETH',
                                        	'binance' => 'XMRETH'
                                                    ),
                                    'usdt' => array(
                                          'bittrex' => 'USDT-XMR',
                                          'upbit' => 'USDT-XMR',
                                          'poloniex' => 'USDT_XMR',
                                          'okex' => 'xmr_usdt'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // DCR
                    'DCR' => array(
                        
                        'coin_name' => 'Decred',
                        'coin_symbol' => 'DCR',
                        'marketcap-website-slug' => 'decred',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_DCR',
                                          'bittrex' => 'BTC-DCR',
                                        	'binance' => 'DCRBTC',
                                          'upbit' => 'BTC-DCR',
                                       	'kucoin' => 'DCR-BTC',
                                          'okex' => 'dcr_btc',
                                          'cryptopia' => 'DCR/BTC',
                                          'gateio' => 'dcr_btc'
                                                    ),
                                		'eth' => array(
                                        	'kucoin' => 'DCR-ETH'
                                                    ),
                                    'usdt' => array(
                                          'bittrex' => 'USDT-DCR',
                                          'okex' => 'dcr_usdt',
                                          'cryptopia' => 'DCR/USDT',
                                          'gateio' => 'dcr_usdt'
                                          			)
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // DASH
                    'DASH' => array(
                        
                        'coin_name' => 'Dash',
                        'coin_symbol' => 'DASH',
                        'marketcap-website-slug' => 'dash',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                        'poloniex' => 'BTC_DASH',
                                        'bittrex' => 'BTC-DASH',
                                        'upbit' => 'BTC-DASH',
                                        'kraken' => 'DASHXBT',
                                        'bitfinex' => 'tDSHBTC',
                                        'binance' => 'DASHBTC',
                                        'hitbtc' => 'DASHBTC',
                                        'kucoin' => 'DASH-BTC',
                                        'okex' => 'dash_btc',
                                        'livecoin' => 'DASH/BTC',
                                        'cryptopia' => 'DASH/BTC',
                                        'liqui' => 'dash_btc',
                                        'bter' => 'dash_btc',
                                        'tradesatoshi' => 'DASH_BTC'
                                                    ),
												'xmr' => array(
													  'poloniex' => 'XMR_DASH'
                                                    ),
                                    'eth' => array(
                                         'bittrex' => 'ETH-DASH',
                                         'upbit' => 'ETH-DASH',
                                         'binance' => 'DASHETH',
                                         'hitbtc' => 'DASHETH',
                                         'kucoin' => 'DASH-ETH',
                                         'okex' => 'dash_eth',
                                         'liqui' => 'dash_eth'
                                                    ),
                                    'usdt' => array(
                                         'poloniex' => 'USDT_DASH',
                                         'bittrex' => 'USDT-DASH',
                                         'upbit' => 'USDT-DASH',
                                         'cryptopia' => 'DASH/USDT'
                                          			)
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // LTC
                    'LTC' => array(
                        
                        'coin_name' => 'Litecoin',
                        'coin_symbol' => 'LTC',
                        'marketcap-website-slug' => 'litecoin',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                        'coinbase' => 'LTC-BTC',
                                        'okex' => 'ltc_btc',
                                        'bitfinex' => 'tLTCBTC',
                                        'poloniex' => 'BTC_LTC',
                                        'bittrex' => 'BTC-LTC',
                                        'upbit' => 'BTC-LTC',
                                        'kraken' => 'XLTCXXBT',
                                        'hitbtc' => 'LTCBTC',
                                        'bitstamp' => 'ltcbtc',
                                        'binance' => 'LTCBTC',
                                        'kucoin' => 'LTC-BTC',
                                        'livecoin' => 'LTC/BTC',
                                        'cryptopia' => 'LTC/BTC',
                                        'liqui' => 'ltc_btc',
                                        'bter' => 'ltc_btc',
                                        'cryptofresh' => 'OPEN.LTC',
                                        'tradesatoshi' => 'LTC_BTC'
                                                    ),
                                    'xmr' => array(
                                        'poloniex' => 'XMR_LTC'
                                                    ),
                                    'eth' => array(
                                    	 'okex' => 'ltc_eth',
                                        'bittrex' => 'ETH-LTC',
                                        'upbit' => 'ETH-LTC',
                                        'binance' => 'LTCETH',
                                        'hitbtc' => 'LTCETH',
                                        'kucoin' => 'LTC-ETH',
                                        'liqui' => 'ltc_eth'
                                                    ),
                                    'usdt' => array(
                                        'poloniex' => 'USDT_LTC',
                                        'bittrex' => 'USDT-LTC',
                                        'upbit' => 'USDT-LTC',
                                        'okex' => 'ltc_usdt',
                                        'binance' => 'LTCUSDT',
                                        'hitbtc' => 'LTCUSD',
                                        'kucoin' => 'LTC-USDT',
                                        'cryptopia' => 'LTC/USDT',
                                        'liqui' => 'ltc_usdt'
                                          			)
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // STEEM
                    'STEEM' => array(
                        
                        'coin_name' => 'Steem',
                        'coin_symbol' => 'STEEM',
                        'marketcap-website-slug' => 'steem',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_STEEM',
                                          'bittrex' => 'BTC-STEEM',
                                          'upbit' => 'BTC-STEEM',
                                        	'binance' => 'STEEMBTC',
                                          'hitbtc' => 'STEEMBTC',
                                          'livecoin' => 'STEEM/BTC',
                                          'cryptofresh' => 'OPEN.STEEM'
                                                    ),
                                    'eth' => array(
                                          'poloniex' => 'ETH_STEEM',
                                        	'binance' => 'STEEMETH'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // MANA
                    'MANA' => array(
                        
                        'coin_name' => 'Decentraland',
                        'coin_symbol' => 'MANA',
                        'marketcap-website-slug' => 'decentraland',
                        'ico' => 'yes',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_MANA',
                                          'bittrex' => 'BTC-MANA',
                                        	'upbit' => 'BTC-MANA',
                                        	'binance' => 'MANABTC',
                                        	'ethfinex' => 'tMNABTC',
                                          'liqui' => 'mana_btc',
                                          'gatecoin' => 'MANBTC',
                                          'okex' => 'mana_btc',
                                          'kucoin' => 'MANA-BTC'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-MANA',
                                        	'upbit' => 'ETH-MANA',
                                        	'binance' => 'MANAETH',
                                          'hitbtc' => 'MANAETH',
                                        	'ethfinex' => 'tMNAETH',
                                          'liqui' => 'mana_eth',
                                          'gatecoin' => 'MANETH',
                                          'okex' => 'mana_eth',
                                          'kucoin' => 'MANA-ETH'
                                                    ),
                                    'usdt' => array(
                                          'liqui' => 'mana_usdt',
                                          'hitbtc' => 'MANAUSD',
                                          'okex' => 'mana_usdt'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // ANT
                    'ANT' => array(
                        
                        'coin_name' => 'Aragon',
                        'coin_symbol' => 'ANT',
                        'marketcap-website-slug' => 'aragon',
                        'ico' => 'yes',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-ANT',
                                        	'ethfinex' => 'tANTBTC',
                                        	'upbit' => 'BTC-ANT',
                                          'hitbtc' => 'ANTBTC',
                                          'liqui' => 'ant_btc'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-ANT',
                                        	'ethfinex' => 'tANTETH',
                                          'upbit' => 'ETH-ANT',
                                          'liqui' => 'ant_eth'
                                                    ),
                                    'usdt' => array(
                                        	'liqui' => 'ant_usdt'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // GRIN
                    'GRIN' => array(
                        
                        'coin_name' => 'Grin',
                        'coin_symbol' => 'GRIN',
                        'marketcap-website-slug' => 'grin',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                         'poloniex' => 'BTC_GRIN',
                                    	  'kucoin' => 'GRIN-BTC',
                                         'hotbit' => 'GRIN_BTC',
                                         'bitforex' => 'coin-btc-grin',
                                         'tradeogre' => 'BTC-GRIN'
                                                    ),
                                    'eth' => array(
                                    	  'kucoin' => 'GRIN-ETH',
                                         'hotbit' => 'GRIN_ETH'
                                                    ),
                                    'usdt' => array(
                                         'hotbit' => 'GRIN_USDT',
                                         'bitforex' => 'coin-usdt-grin'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // BEAM
                    'BEAM' => array(
                        
                        'coin_name' => 'Beam',
                        'coin_symbol' => 'BEAM',
                        'marketcap-website-slug' => 'beam',
                        'ico' => 'yes',
                        'market_pairing' => array(
                                    'btc' => array(
                                         'hotbit' => 'BEAM_BTC'
                                                    ),
                                    'eth' => array(
                                         'hotbit' => 'BEAM_ETH'
                                                    ),
                                    'usdt' => array(
                                         'hotbit' => 'BEAM_USDT'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // ZRX
                    'ZRX' => array(
                        
                        'coin_name' => 'oxProject',
                        'coin_symbol' => 'ZRX',
                        'marketcap-website-slug' => '0x',
                        'ico' => 'yes',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_ZRX',
                                          'bittrex' => 'BTC-ZRX',
                                        	'upbit' => 'BTC-ZRX',
                                        	'ethfinex' => 'tZRXBTC',
                                          'hitbtc' => 'ZRXBTC',
                                        	'binance' => 'ZRXBTC',
                                          'liqui' => 'zrx_btc',
                                          'livecoin' => 'ZRX/BTC',
                                          'gatecoin' => 'ZRXBTC',
                                          'bter' => 'zrx_btc'
                                                    ),
                                    'eth' => array(
                                          'poloniex' => 'ETH_ZRX',
                                          'bittrex' => 'ETH-ZRX',
                                          'upbit' => 'ETH-ZRX',
                                        	'ethfinex' => 'tZRXETH',
                                          'hitbtc' => 'ZRXETH',
                                        	'binance' => 'ZRXETH',
                                          'liqui' => 'zrx_eth',
                                          'livecoin' => 'ZRX/ETH',
                                          'gatecoin' => 'ZRXETH',
                                        	'okex' => 'zrx_eth'
                                                    ),
                                    'usdt' => array(
                                          'hitbtc' => 'ZRXUSD',
                                        	'liqui' => 'zrx_usdt',
                                        	'okex' => 'zrx_usdt'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // ADA
                    'ADA' => array(
                        
                        'coin_name' => 'Cardano',
                        'coin_symbol' => 'ADA',
                        'marketcap-website-slug' => 'cardano',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                        'bittrex' => 'BTC-ADA',
                                        'upbit' => 'BTC-ADA',
                                        'hitbtc' => 'ADABTC',
                                        'binance' => 'ADABTC'
                                                    ),
                                    'eth' => array(
                                        'bittrex' => 'ETH-ADA',
                                        'upbit' => 'ETH-ADA',
                                        'hitbtc' => 'ADAETH',
                                        'binance' => 'ADAETH'
                                                    ),
                                    'usdt' => array(
                                        'bittrex' => 'USDT-ADA',
                                        'hitbtc' => 'ADAUSD'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // ZIL
                    'ZIL' => array(
                        
                        'coin_name' => 'Zilliqa',
                        'coin_symbol' => 'ZIL',
                        'marketcap-website-slug' => 'zilliqa',
                        'ico' => 'yes',
                        'market_pairing' => array(
                                    'btc' => array(
                                        	'binance' => 'ZILBTC',
                                    		'kucoin' => 'ZIL-BTC',
                                        	'okex' => 'zil_btc'
                                                    ),
                                    'eth' => array(
                                        	'binance' => 'ZILETH',
                                          'kucoin' => 'ZIL-ETH',
                                        	'okex' => 'zil_eth'
                                                    ),
                                    'usdt' => array(
                                        	'okex' => 'zil_usdt'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // SNT
                    'SNT' => array(
                        
                        'coin_name' => 'Status',
                        'coin_symbol' => 'SNT',
                        'marketcap-website-slug' => 'status',
                        'ico' => 'yes',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-SNT',
                                          'upbit' => 'BTC-SNT',
                                        	'ethfinex' => 'tSNTBTC',
                                          'hitbtc' => 'SNTBTC',
                                        	'binance' => 'SNTBTC',
                                          'gatecoin' => 'SNTBTC',
                                          'liqui' => 'snt_btc',
                                        	'kucoin' => 'SNT-BTC',
                                        	'livecoin' => 'SNT/BTC',
                                       	'okex' => 'snt_btc',
                                          'bter' => 'snt_btc'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-SNT',
                                          'upbit' => 'ETH-SNT',
                                        	'ethfinex' => 'tSNTETH',
                                          'hitbtc' => 'SNTETH',
                                          'binance' => 'SNTETH',
                                          'liqui' => 'snt_eth',
                                        	'kucoin' => 'SNT-ETH',
                                        	'livecoin' => 'SNT/ETH',
                                          'gatecoin' => 'SNTETH',
                                        	'okex' => 'snt_eth'
                                                    ),
                                    'usdt' => array(
                                          'hitbtc' => 'SNTUSD',
                                        	'liqui' => 'snt_usdt',
                                        	'okex' => 'snt_usdt'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // GNT
                    'GNT' => array(
                        
                        'coin_name' => 'Golem',
                        'coin_symbol' => 'GNT',
                        'marketcap-website-slug' => 'golem-network-tokens',
                        'ico' => 'yes',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_GNT',
                                          'bittrex' => 'BTC-GNT',
                                        	'upbit' => 'BTC-GNT',
                                        	'ethfinex' => 'tGNTBTC',
                                          'liqui' => 'gnt_btc',
                                        	'livecoin' => 'GNT/BTC',
                                          'cryptopia' => 'GNT/BTC',
                                        	'okex' => 'gnt_btc'
                                                    ),
                                    'eth' => array(
                                          'poloniex' => 'ETH_GNT',
                                          'bittrex' => 'ETH-GNT',
                                          'upbit' => 'ETH-GNT',
                                        	'ethfinex' => 'tGNTETH',
                                          'liqui' => 'gnt_eth',
                                        	'livecoin' => 'GNT/ETH',
                                        	'okex' => 'gnt_eth'
                                                    ),
                                    'usdt' => array(
                                        	'liqui' => 'gnt_usdt',
                                        	'okex' => 'gnt_usdt'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // XLM
                    'XLM' => array(
                        
                        'coin_name' => 'Stellar',
                        'coin_symbol' => 'XLM',
                        'marketcap-website-slug' => 'stellar',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_STR',
                                          'bittrex' => 'BTC-XLM',
                                          'upbit' => 'BTC-XLM',
                                        	'binance' => 'XLMBTC',
                                          'hitbtc' => 'XLMBTC',
                                          'kraken' => 'XXLMXXBT',
                                        	'okex' => 'xlm_btc'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-XLM',
                                          'upbit' => 'ETH-XLM',
                                          'binance' => 'XLMETH',
                                          'hitbtc' => 'XLMETH',
                                        	'okex' => 'xlm_eth'
                                                    ),
                                    'usdt' => array(
                                        	'poloniex' => 'USDT_STR',
                                          'hitbtc' => 'XLMUSD',
                                        	'okex' => 'xlm_usdt'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // FCT
                    'FCT' => array(
                        
                        'coin_name' => 'Factom',
                        'coin_symbol' => 'FCT',
                        'marketcap-website-slug' => 'factom',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_FCT',
                                          'bittrex' => 'BTC-FCT',
                                        	'upbit' => 'BTC-FCT',
                                          'cryptopia' => 'FCT/BTC'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-FCT',
                                        	'upbit' => 'ETH-FCT'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // RVN
                    'RVN' => array(
                        
                        'coin_name' => 'Ravencoin',
                        'coin_symbol' => 'RVN',
                        'marketcap-website-slug' => 'ravencoin',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
    													'binance' => 'RVNBTC',
                                         'bittrex' => 'BTC-RVN',
                                         'graviex' => 'rvnbtc',
                                         'cryptofresh' => 'BRIDGE.RVN'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // MYST
                    'MYST' => array(
                        
                        'coin_name' => 'Mysterium',
                        'coin_symbol' => 'MYST',
                        'marketcap-website-slug' => 'mysterium',
                        'ico' => 'yes',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'hitbtc' => 'MYSTBTC',
                                          'bigone' => 'MYST-BTC'
                                                    ),
                                    'eth' => array(
                                          'hitbtc' => 'MYSTETH',
                                          'idex' => 'ETH_MYST'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // TRAC
                    'TRAC' => array(
                        
                        'coin_name' => 'OriginTrail',
                        'coin_symbol' => 'TRAC',
                        'marketcap-website-slug' => 'origintrail',
                        'ico' => 'yes',
                        'market_pairing' => array(
                                    'btc' => array(
                                    		'kucoin' => 'TRAC-BTC'
                                                    ),
                                    'eth' => array(
                                          'kucoin' => 'TRAC-ETH',
                                        	'hitbtc' => 'TRACETH'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    )
                
                
);

/////////////////// COIN MARKETS CONFIG -END- /////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////
// WHEN RE-CONFIGURING COIN DATA, LEAVE THIS CODE BELOW HERE, DON'T DELETE BELOW THIS LINE
require_once("app.lib/php/post-init.php");

?>