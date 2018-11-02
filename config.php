<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all error reporting (0), or enable (1)

// Forbid direct access to this file
if ( realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    exit;
}

//apc_clear_cache(); apcu_clear_cache(); opcache_reset();  // DEBUGGING ONLY
session_start();
require_once("app.lib/php/functions.php");
require_once("app.lib/php/cookies.php");
require_once("app.lib/php/init.php");

///////////////////////////////////////////////////////////////////////////////////////////


$version = '2.2.2';  // 2018/NOVEMBER/1ST
 

/*
 * USAGE (ADDING / UPDATING COINS) ...API support for: kraken / gatecoin / poloniex / coinbase / bitstamp / bittrex / bitfinex and ethfinex / cryptofresh / bter / gemini / hitbtc / liqui / cryptopia / livecoin / upbit / kucoin / okex / gate.io / graviex...BTC, XMR, ETH, LTC, AND USDT trading pair support
 * Ethereum ICO subtoken support has been built in, but values are static ICO values in ETH
 *
 SEE THE BOTTOM OF THE README.txt FOR FOR AN EXAMPLE SET OF PRE-CONFIGURED ASSETS
 
 
                    // UPPERCASE_COIN_SYMBOL
                    'UPPERCASE_COIN_SYMBOL' => array(
                        
                        'coin_name' => 'COIN_NAME',
                        'coin_symbol' => 'UPPERCASE_COIN_SYMBOL',
                        'marketcap-website-slug' => 'coin-slug', // Is this coin listed on coinmarketcap / coingecko, leave blank if not
                        'ico' => 'no', // yes / no ...was this an ICO
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
                                          'eth_subtokens_ico' => 'THEDAO' // Must be defined in $eth_subtokens_ico_values in config.php
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
                                                    )
                                          ),
                        'default_pairing' => 'LOWERCASE_BTC_OR_XMR_OR_ETH_OR_LTC_OR_USDT_TRADING_PAIR'
                        
                    ),
                    
                    
                    
 * 
 */


/////////////////// GENERAL CONFIG -START- ////////////////////////////////////////////////////

$btc_exchange = 'coinbase'; // Default Bitcoin value in USD: coinbase / bitfinex / gemini / okcoin / bitstamp / kraken / hitbtc / gatecion / livecoin

$marketcap_site = 'coingecko'; // Default marketcap data source: coinmarketcap / coingecko

$api_timeout = 10; // Seconds to wait for response from API endpoint

$last_trade_ttl = 1; // Minutes to cache last real-time exchange data...can be zero to skip cache, but set at least 1 minute to safely avoid your IP getting blocked

$marketcap_ttl = 20; // Minutes to cache marketcap data...start high and test lower, it can be strict

$marketcap_ranks_max = 100; // Maximum number of marketcap rankings to request from API

// FROM email should be a REAL address on the website domain name, or you risk having sent email blacklisted / sent to junk folder
$from_email = ''; // For cron job email alerts, MUST BE SET (see README.txt for cron job setup information) 

$to_email = ''; // For cron job email alerts, MUST BE SET

$to_text = ''; // For cron job text alerts, CAN BE BLANK, country format MUST be used: '12223334444|att' // number_only (for textbelt / textlocal), alltel, att, tmobile, virgin, sprint, verizon, nextel...attempts to email text if carrier is set AND no textbelt / textlocal config is setup

// For cron job notifyme notifications, CAN BE BLANK. Setup: http://www.thomptronics.com/notify-me
$notifyme_accesscode = '';

// Do NOT use textbelt AND textlocal together. Leave one setting blank, or it will disable using both.
// For cron job textbelt notifications, CAN BE BLANK. Setup: https://textbelt.com/
$textbelt_apikey = '';

// For cron job textlocal notifications, CAN BE BLANK. Setup: https://www.textlocal.com/integrations/api/
$textlocal_account = ''; // This format MUST be used: 'username|hash_code'

$cron_alerts_freq = 1; // Re-allow cron job email / text alerts after X hours (per asset, set higher if issues with email / text blacklisting)

$cron_alerts_percent = 15; // $USD price percentage change (WITHOUT percent sign: 15 = 15%), sends alerts when percent change is reached

$cron_alerts_refresh = 45; // Refresh prices every X days with latest prices...can be 0 to disable refreshing (until price alert is triggered)

$cron_alerts = array(
					// Markets you want cron alerts for (alert sent when $USD value change is equal to or above / below $cron_alerts_percent...see README.txt for cron job setup information) 
					// Delete any double forward slashes from in front of each asset you want to enable cron job price alerts on (or add double slash to disable)...
					// NOTE: This list must only contain assets / exchanges / trading pairs included in the primary coin data configuration further down in this config file.
					'btc' => 'coinbase|btc', // exchange|trade_pairing
					'eth' => 'bittrex|btc', // exchange|trade_pairing
					'xmr' => 'bittrex|btc', // exchange|trade_pairing
					'dcr' => 'binance|btc', // exchange|trade_pairing
					'tusd' => 'binance|btc', // exchange|trade_pairing
				//	'dash' => 'bittrex|btc', // exchange|trade_pairing
				//	'ltc' => 'bittrex|btc', // exchange|trade_pairing
					'steem' => 'bittrex|btc', // exchange|trade_pairing
					'mana' => 'bittrex|btc', // exchange|trade_pairing
				//	'zrx' => 'bittrex|btc', // exchange|trade_pairing
					'zil' => 'binance|btc', // exchange|trade_pairing
					'trac' => 'kucoin|btc', // exchange|trade_pairing
				//	'snt' => 'bittrex|btc', // exchange|trade_pairing
					'gnt' => 'bittrex|btc', // exchange|trade_pairing
				//	'fct' => 'bittrex|btc', // exchange|trade_pairing
					'xlm' => 'bittrex|btc', // exchange|trade_pairing
					'ada' => 'bittrex|btc', // exchange|trade_pairing
				//	'xrp' => 'bittrex|btc', // exchange|trade_pairing
					'rvn' => 'bittrex|btc', // exchange|trade_pairing
					'myst' => 'hitbtc|btc' // exchange|trade_pairing
					);

$eth_subtokens_ico_values = array(
                        // Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
                        'ETHSUBTOKENNAME' => '0.15',
                        'GOLEM' => '0.001',
                        'SWARMCITY' => '0.0133333333333333',
                        'ARAGON' => '0.01',
                        'STATUS' => '0.0001',
                        'INVESTFEED' => '0.0001',
                        '0XPROJECT' => '0.00016929425',
                        'DECENTRALAND' => '0.00008'
                        );


$mining_rewards = array(
					// Mining rewards for different platforms (to prefill editable mining calculator forms)
					'xmr' => monero_reward(),  // (2^64 - 1 - current_supply * 10^12) * 2^-19 * 10^-12
					'eth' => '3',
					'dcr' => '13',
					'rvn' => '5000'
					);


/*
 * STEEM Power yearly interest rate START 11/29/2016 (1.425%, decreasing every year by roughly 0.075% until it hits a minimum of 0.075% and stays there)
 */
$steempower_yearly_interest = 1.425;  // 1.425 (DO NOT INCLUDE PERCENT SIGN) the first year at 11/29/2016 refactored rates, see above for manual yearly adjustment
$steem_powerdown_time = 13;  // Weeks to power down all STEEM Power holdings

/////////////////// GENERAL CONFIG -END- ////////////////////////////////////////////////////




/////////////////// COIN MARKETS CONFIG -START- ////////////////////////////////////////////////////

$coins_array = array(
                
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
                                          'okcoin' => 'okcoin',
                                          'bitfinex' => 'tBTCUSD',
                                          'kraken' => 'kraken',
                                          'coinbase' => 'coinbase',
                                          'bitstamp' => 'bitstamp',
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
                                          'coinbase' => 'ETH',
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
                                        'coinbase' => 'LTC',
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
                    // XRP
                    'XRP' => array(
                        
                        'coin_name' => 'Ripple',
                        'coin_symbol' => 'XRP',
                        'marketcap-website-slug' => 'ripple',
                        'ico' => 'no',
                        'market_pairing' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_XRP',
                                          'bittrex' => 'BTC-XRP',
                                          'upbit' => 'BTC-XRP',
                                        	'binance' => 'XRPBTC',
                                          'kraken' => 'XXRPXXBT',
                                          'bitfinex' => 'tXRPBTC',
                                          'bitstamp' => 'xrpbtc',
                                        	'hitbtc' => 'XRPBTC'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-XRP',
                                          'upbit' => 'ETH-XRP',
                                        	'binance' => 'XRPETH',
                                        	'hitbtc' => 'XRPETH',
                                        	'okex' => 'xrp_eth'
                                                    ),
                                    'usdt' => array(
                                        	'poloniex' => 'USDT_XRP',
                                          'bittrex' => 'USDT-XRP',
                                          'upbit' => 'USDT-XRP',
                                        	'hitbtc' => 'XRPUSDT'
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
                                          'hitbtc' => 'MYSTBTC'
                                                    ),
                                    'eth' => array(
                                          'hitbtc' => 'MYSTETH',
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    )
                
                
);

/////////////////// COIN MARKETS CONFIG -END- ////////////////////////////////////////////////////



require_once("app.lib/php/post-init.php");
?>