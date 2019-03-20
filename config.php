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

$api_timeout = 12; // Seconds to wait for response from API endpoints. Don't set too low, or you won't get data

$purge_error_logs = 3; // Days to keep error logs before purging (deletes logs every X days) start low, especially when using proxies

$mail_error_logs = 'daily'; // 'no', 'daily', 'weekly' Email to / from !MUST BE SET! further down in this config file. MAY NOT SEND IN TIMELY FASHION WITHOUT CRON JOB

$btc_exchange = 'coinbase'; // Default Bitcoin to USD (or equiv stable coin): coinbase / binance / bitstamp / bitfinex / kraken / gemini / hitbtc / okcoin / livecoin

$marketcap_site = 'coinmarketcap'; // Default marketcap data source: 'coinmarketcap', or 'coingecko'

$marketcap_ranks_max = 200; // Number of marketcap rankings to request from API. Ranks are grabbed 100 per request. Set to 100 or 200 if you are blocked a lot

$marketcap_cache = 30; // Minutes to cache above-mentioned marketcap rankings...start high and test lower, it can be strict

$last_trade_cache = 1; // Minutes to cache real-time exchange data...can be zero to skip cache, but set to at least 1 minute to avoid your IP getting blocked

$chainstats_cache = 60; // Minutes to cache blockchain stats (for mining calculators). Set high initially, can be strict


// If using proxies and login is required
// Adding a user / pass here will automatically send login details for proxy connections
// CAN BE BLANK. IF using ip address whitelisting instead, MUST BE LEFT BLANK
$proxy_login = ''; // Use format: 'username:password'

// If using proxies, add the ip address / port number here for each one, like examples below (without the double slashes in front)
// CAN BE BLANK. Adding proxies here will automatically choose one randomly for each API request
$proxy_list = array(
					// 'ipaddress1:portnumber1',
					// 'ipaddress2:portnumber2',
					);


// Proxy configuration settings (only used if proxies are enabled above)

$proxy_alerts = 'email'; // Alerts for failed proxy data connections. 'none', 'email', 'text', 'notifyme', 'all'

$proxy_alerts_runtime = 'cron'; // Which runtime mode should allow proxy alerts? Options: 'cron', 'ui', 'all'

$proxy_checkup_ok = 'ignore'; // 'include', or 'ignore' Proxy alerts even if checkup went OK? (after flagged, started working again when checked) 

$proxy_alerts_freq = 1; // Re-allow same proxy alert(s) after X hours (per ip/port pair, can be 0)


// OPTIONALLY use SMTP authentication to send email, if you have no reverse lookup that matches domain name (on your home network etc)
// !!USE A THROWAWAY ACCOUNT ONLY!! If web server is hacked, HACKER WOULD THEN HAVE ACCESS YOUR EMAIL LOGIN FROM THIS FILE!!
// If SMTP credentials / settings are filled in, BUT not setup properly, APP EMAILING WILL FAIL
// CAN BE BLANK (PHP's built-in mail function will be automatically used instead)
$smtp_login = ''; //  CAN BE BLANK. This format MUST be used: 'username|password'

$smtp_server = ''; // CAN BE BLANK. This format MUST be used: 'domain_or_ip:port' example: 'example.com:25'

$smtp_secure = ''; // CAN BE BLANK '' for no secure connection, or 'tls', or 'ssl' for secure connections. Make sure port number ABOVE corresponds


// IF SMTP EMAIL --NOT-- USED, FROM email should be REAL address on the website domain, or risk having email blacklisted / sent to junk folder
// IF SMTP EMAIL --IS-- USED, FROM EMAIL MUST MATCH EMAIL ADDRESS associated with SMTP login
$from_email = ''; // For email features this MUST BE SET

$to_email = ''; // For email features this MUST BE SET

// For exchange price alert texts. Attempts to email text if carrier is set AND no textbelt / textlocal config is setup
// CAN BE BLANK. Country format MUST be used: '12223334444|number_only' number_only (for textbelt / textlocal), alltel, att, tmobile, virgin, sprint, verizon, nextel
$to_text = '';

// For exchange price alert notifyme alexa notifications (sending Alexa devices notifications for free). 
// CAN BE BLANK. Setup: http://www.thomptronics.com/notify-me
$notifyme_accesscode = '';

// Do NOT use textbelt AND textlocal together. Leave one setting blank, or it will disable using both.

// CAN BE BLANK. For exchange price alert textbelt notifications. Setup: https://textbelt.com/
$textbelt_apikey = '';

// CAN BE BLANK. For exchange price alert textlocal notifications. Setup: https://www.textlocal.com/integrations/api/
$textlocal_account = ''; // This format MUST be used: 'username|hash_code'


// Exchange price alert settings
// Only used if $exchange_price_alerts is filled in properly below, AND a cron job is setup (see README.txt for cron job setup information) 

$exchange_price_alerts_freq = 15; // Re-allow same exchange price alert(s) after X minutes (per asset, set higher if issues with blacklisting...can be 0)

$exchange_price_alerts_percent = 10; // Price percent change to send alerts for (WITHOUT percent sign: 15 = 15%). Sends alerts when percent change reached (up or down)

// Minimum 24 hour volume filter. Only allows sending exchange price alerts if minimum 24 hour volume reached
// CAN BE 0 TO DISABLE MINIMUM VOLUME FILTERING, NO DECIMALS OR SEPARATORS, NUMBERS ONLY, WITHOUT dollar sign: 250 = $250 , 4500 = $4,500 , etc
$exchange_price_alerts_minvolume = 350;

// Refresh cached comparison prices every X days (since last refresh / alert) with latest prices...can be 0 to disable refreshing (until price alert triggers a refresh)
$exchange_price_alerts_refresh = 0; 

// EXCHANGE PRICE CHANGE ALERTS REQUIRES CRON JOB SETUP (see README.txt for cron job setup information) 
// Markets you want exchange price change alerts for (alert sent when $USD value change is equal to or above / below $exchange_price_alerts_percent) 
// Delete any double forward slashes from in front of each asset you want to enable cron job price alerts on (or add double slash to disable alerts)
// NOTE: This list must only contain assets / exchanges / trading pairs included in the primary coin data configuration further down in this config file
// TO ADD MULTIPLE ALERTS FOR SAME ASSET (FOR DIFFERENT EXCHANGES / TRADE PAIRINGS), FORMAT LIKE SO: symbol, symbol-1, symbol-2, etc.
$exchange_price_alerts = array(
				// 'symbol' => 'exchange1|trade_pairing1',
				// 'symbol-2' => 'exchange2|trade_pairing2',
					'tusd' => 'bittrex|btc',
					'btc' => 'coinbase|btc',
					'btc-2' => 'binance|btc',
					'eth' => 'binance|usdt',
					'eth-2' => 'bittrex|btc',
					'xmr' => 'binance|btc',
					'dcr' => 'binance|btc',
					'dcr-2' => 'bittrex|usdt',
				//	'dash' => 'bittrex|btc',
				//	'ltc' => 'bittrex|btc',
					'steem' => 'binance|eth',
					'mana' => 'binance|btc',
					'ant' => 'bittrex|btc',
				//	'zrx' => 'bittrex|btc',
					'zil' => 'binance|btc',
				//	'trac' => 'kucoin|btc',
				//	'snt' => 'bittrex|btc',
				//	'gnt' => 'bittrex|btc',
				//	'fct' => 'bittrex|btc',
					'xlm' => 'binance|tusd',
					'xlm-2' => 'bittrex|btc',
					'ada' => 'binance|tusd',
					'rvn' => 'binance|btc',
					'grin' => 'kucoin|btc',
					'grin-2' => 'hotbit|eth',
					'beam' => 'hotbit|btc',
					'myst' => 'hitbtc|btc',
					'myst-2' => 'hitbtc|eth',
					'myst-3' => 'idex|eth',
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
                                        ),
                        'default_pairing' => 'usdt'
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
                                          'bitfinex' => 'bitfinex',
                                          'kraken' => 'kraken',
                                          'gemini' => 'gemini',
                                          'hitbtc' => 'hitbtc',
                                          'okcoin' => 'okcoin',
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
                                          'okex' => 'eth_btc',
                                          'livecoin' => 'ETH/BTC',
                                          'cryptofresh' => 'OPEN.ETH'
                                                    ),
                                    'ltc' => array(
                                          'cryptopia' => 'ETH/LTC'
                                                    ),
                                    'usdt' => array(
                                        	'binance' => 'ETHUSDT',
                                          'bittrex' => 'USDT-ETH',
                                          'poloniex' => 'USDT_ETH',
                                          'hitbtc' => 'ETHUSD',
                                          'upbit' => 'USDT-ETH',
                                          'okex' => 'eth_usdt'
                                                    ),
                                    'tusd' => array(
                                          'binance' => 'ETHTUSD'
                                                    )
                                        ),
                        'default_pairing' => 'usdt'
                        
                    ),
                    // XMR
                    'XMR' => array(
                        
                        'coin_name' => 'Monero',
                        'coin_symbol' => 'XMR',
                        'marketcap-website-slug' => 'monero',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                        	'binance' => 'XMRBTC',
                                          'bittrex' => 'BTC-XMR',
                                          'poloniex' => 'BTC_XMR',
                                          'bitfinex' => 'tXMRBTC',
                                          'hitbtc' => 'XMRBTC',
                                          'kraken' => 'XXMRXXBT',
                                          'cryptopia' => 'XMR/BTC',
                                        	'upbit' => 'BTC-XMR',
                                          'okex' => 'xmr_btc',
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
                                        	'binance' => 'DCRBTC',
                                          'bittrex' => 'BTC-DCR',
                                          'poloniex' => 'BTC_DCR',
                                       	'kucoin' => 'DCR-BTC',
                                          'upbit' => 'BTC-DCR',
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
                                        'binance' => 'DASHBTC',
                                        'bittrex' => 'BTC-DASH',
                                        'poloniex' => 'BTC_DASH',
                                        'kraken' => 'DASHXBT',
                                        'bitfinex' => 'tDSHBTC',
                                        'hitbtc' => 'DASHBTC',
                                        'kucoin' => 'DASH-BTC',
                                        'upbit' => 'BTC-DASH',
                                        'okex' => 'dash_btc',
                                        'livecoin' => 'DASH/BTC',
                                        'cryptopia' => 'DASH/BTC',
                                        'tradesatoshi' => 'DASH_BTC'
                                                    ),
												'xmr' => array(
													  'poloniex' => 'XMR_DASH'
                                                    ),
                                    'eth' => array(
                                         'binance' => 'DASHETH',
                                         'bittrex' => 'ETH-DASH',
                                         'hitbtc' => 'DASHETH',
                                         'kucoin' => 'DASH-ETH',
                                         'upbit' => 'ETH-DASH',
                                         'okex' => 'dash_eth'
                                                    ),
                                    'usdt' => array(
                                         'poloniex' => 'USDT_DASH',
                                         'bittrex' => 'USDT-DASH',
                                         'cryptopia' => 'DASH/USDT',
                                         'upbit' => 'USDT-DASH'
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
                                        'binance' => 'LTCBTC',
                                        'bittrex' => 'BTC-LTC',
                                        'poloniex' => 'BTC_LTC',
                                        'bitstamp' => 'ltcbtc',
                                        'bitfinex' => 'tLTCBTC',
                                        'kraken' => 'XLTCXXBT',
                                        'hitbtc' => 'LTCBTC',
                                        'kucoin' => 'LTC-BTC',
                                        'upbit' => 'BTC-LTC',
                                        'okex' => 'ltc_btc',
                                        'livecoin' => 'LTC/BTC',
                                        'cryptopia' => 'LTC/BTC',
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
                                    	 'okex' => 'ltc_eth'
                                                    ),
                                    'usdt' => array(
                                        'binance' => 'LTCUSDT',
                                        'bittrex' => 'USDT-LTC',
                                        'poloniex' => 'USDT_LTC',
                                        'hitbtc' => 'LTCUSD',
                                        'kucoin' => 'LTC-USDT',
                                        'cryptopia' => 'LTC/USDT',
                                        'upbit' => 'USDT-LTC',
                                        'okex' => 'ltc_usdt'
                                          			),
                                    'tusd' => array(
                                          'binance' => 'LTCTUSD'
                                                    )
                                        ),
                        'default_pairing' => 'usdt'
                    ),
                    // STEEM
                    'STEEM' => array(
                        
                        'coin_name' => 'Steem',
                        'coin_symbol' => 'STEEM',
                        'marketcap-website-slug' => 'steem',
                        'ico' => 'no',
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
                                        	'binance' => 'MANABTC',
                                          'bittrex' => 'BTC-MANA',
                                          'poloniex' => 'BTC_MANA',
                                        	'ethfinex' => 'tMNABTC',
                                          'kucoin' => 'MANA-BTC',
                                        	'upbit' => 'BTC-MANA',
                                          'okex' => 'mana_btc'
                                                    ),
                                    'eth' => array(
                                        	'binance' => 'MANAETH',
                                          'bittrex' => 'ETH-MANA',
                                        	'ethfinex' => 'tMNAETH',
                                          'hitbtc' => 'MANAETH',
                                          'kucoin' => 'MANA-ETH',
                                        	'upbit' => 'ETH-MANA',
                                          'okex' => 'mana_eth'
                                                    ),
                                    'usdt' => array(
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
                                          'hitbtc' => 'ANTBTC',
                                        	'upbit' => 'BTC-ANT'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-ANT',
                                        	'ethfinex' => 'tANTETH',
                                          'upbit' => 'ETH-ANT'
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
                                        	'binance' => 'ZRXBTC',
                                          'bittrex' => 'BTC-ZRX',
                                          'poloniex' => 'BTC_ZRX',
                                        	'ethfinex' => 'tZRXBTC',
                                          'hitbtc' => 'ZRXBTC',
                                        	'upbit' => 'BTC-ZRX',
                                          'livecoin' => 'ZRX/BTC'
                                                    ),
                                    'eth' => array(
                                        	'binance' => 'ZRXETH',
                                          'bittrex' => 'ETH-ZRX',
                                          'poloniex' => 'ETH_ZRX',
                                        	'ethfinex' => 'tZRXETH',
                                          'hitbtc' => 'ZRXETH',
                                          'upbit' => 'ETH-ZRX',
                                          'livecoin' => 'ZRX/ETH',
                                        	'okex' => 'zrx_eth'
                                                    ),
                                    'usdt' => array(
                                          'hitbtc' => 'ZRXUSD',
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
                                        'binance' => 'ADABTC',
                                        'bittrex' => 'BTC-ADA',
                                        'hitbtc' => 'ADABTC',
                                        'upbit' => 'BTC-ADA'
                                                    ),
                                    'eth' => array(
                                        'binance' => 'ADAETH',
                                        'bittrex' => 'ETH-ADA',
                                        'hitbtc' => 'ADAETH',
                                        'upbit' => 'ETH-ADA'
                                                    ),
                                    'usdt' => array(
                                        'bittrex' => 'USDT-ADA',
                                        'hitbtc' => 'ADAUSD'
                                                    ),
                                    'tusd' => array(
                                          'binance' => 'ADATUSD'
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
                                        	'binance' => 'SNTBTC',
                                          'bittrex' => 'BTC-SNT',
                                        	'ethfinex' => 'tSNTBTC',
                                          'hitbtc' => 'SNTBTC',
                                        	'kucoin' => 'SNT-BTC',
                                          'upbit' => 'BTC-SNT',
                                        	'livecoin' => 'SNT/BTC',
                                       	'okex' => 'snt_btc'
                                                    ),
                                    'eth' => array(
                                          'binance' => 'SNTETH',
                                          'bittrex' => 'ETH-SNT',
                                        	'ethfinex' => 'tSNTETH',
                                          'hitbtc' => 'SNTETH',
                                        	'kucoin' => 'SNT-ETH',
                                          'upbit' => 'ETH-SNT',
                                        	'livecoin' => 'SNT/ETH',
                                        	'okex' => 'snt_eth'
                                                    ),
                                    'usdt' => array(
                                          'hitbtc' => 'SNTUSD',
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
                                          'bittrex' => 'BTC-GNT',
                                          'poloniex' => 'BTC_GNT',
                                        	'ethfinex' => 'tGNTBTC',
                                          'cryptopia' => 'GNT/BTC',
                                        	'upbit' => 'BTC-GNT',
                                        	'livecoin' => 'GNT/BTC',
                                        	'okex' => 'gnt_btc'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-GNT',
                                          'poloniex' => 'ETH_GNT',
                                        	'ethfinex' => 'tGNTETH',
                                          'upbit' => 'ETH-GNT',
                                        	'livecoin' => 'GNT/ETH',
                                        	'okex' => 'gnt_eth'
                                                    ),
                                    'usdt' => array(
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
                                        	'binance' => 'XLMBTC',
                                          'bittrex' => 'BTC-XLM',
                                          'poloniex' => 'BTC_STR',
                                          'hitbtc' => 'XLMBTC',
                                          'kraken' => 'XXLMXXBT',
                                          'upbit' => 'BTC-XLM',
                                        	'okex' => 'xlm_btc'
                                                    ),
                                    'eth' => array(
                                          'binance' => 'XLMETH',
                                          'bittrex' => 'ETH-XLM',
                                          'hitbtc' => 'XLMETH',
                                          'upbit' => 'ETH-XLM',
                                        	'okex' => 'xlm_eth'
                                                    ),
                                    'usdt' => array(
                                        	'poloniex' => 'USDT_STR',
                                          'hitbtc' => 'XLMUSD',
                                        	'okex' => 'xlm_usdt'
                                                    ),
                                    'tusd' => array(
                                          'binance' => 'XLMTUSD'
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
                                          'bittrex' => 'BTC-FCT',
                                          'poloniex' => 'BTC_FCT',
                                          'cryptopia' => 'FCT/BTC',
                                        	'upbit' => 'BTC-FCT'
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
                                          'bittrex' => 'BTC-TRAC',
                                    		'kucoin' => 'TRAC-BTC'
                                                    ),
                                    'eth' => array(
                                        	'hitbtc' => 'TRACETH',
                                          'kucoin' => 'TRAC-ETH',
                                          'idex' => 'ETH_TRAC'
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