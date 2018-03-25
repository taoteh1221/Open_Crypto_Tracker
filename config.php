<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Forbid direct access to config.php
if ( realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    exit;
}


//apc_clear_cache(); apcu_clear_cache(); opcache_reset();  // DEBUGGING ONLY
 
$version = '1.9.8';  // 2018/MARCH/25TH
 
session_start();
require_once("app.lib/php/functions.php");
require_once("app.lib/php/cookies.php");
require_once("app.lib/php/init.php");


/*
 * USAGE (ADDING / UPDATING COINS) ...API support for: kraken / gatecoin / poloniex / coinbase / bittrex / bitfinex and ethfinex / cryptofresh / bter / gemini / hitbtc / liqui / cryptopia / livecoin / mercatox / upbit / kucoin...BTC, ETH, LTC, AND USDT trading pair support
 * Ethereum ICO subtoken support has been built in, but values are static ICO values in ETH
 *
 SEE THE BOTTOM OF THE README.txt FOR FOR AN EXAMPLE SET OF PRE-CONFIGURED ASSETS
 
 
                    // UPPERCASE_COIN_SYMBOL
                    'UPPERCASE_COIN_SYMBOL' => array(
                        
                        'coin_name' => 'COIN_NAME',
                        'coin_symbol' => 'UPPERCASE_COIN_SYMBOL',
                        'coinmarketcap' => 'coin-slug', // Is this coin listed on coinmarketcap, leave blank if not
                        'ico' => 'no', // yes / no ...was this an ICO
                        'market_ids' => array(
                                    'btc' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'BTC_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'BTC-COINSYMBOLHERE'
                                                    ),
                                    'eth' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'ETH_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'ETH-COINSYMBOLHERE',
                                          'eth_subtokens_ico' => 'THEDAO' // Must be defined in $eth_subtokens_ico_values at top of config.php
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
                        'default_pairing' => 'LOWERCASE_BTC_OR_ETH_OR_LTC_OR_USDT_TRADING_PAIR'
                        
                    ),
                    
                    
                    
 * 
 */


/////////////////// GENERAL CONFIG -START- ////////////////////////////////////////////////////

$api_timeout = 10; // Seconds to wait for response from API endpoint

$btc_in_usd = 'coinbase'; // Default Bitcoin value in USD: coinbase / bitfinex / gemini / okcoin / bitstamp / kraken / hitbtc / gatecion / livecoin

$coinmarketcap_ranks_max = '400'; // Maximum number of Coinmarketcap.com rankings to request from their API

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
					//Mining rewards for different platforms (to prefill editable mining calculator forms)
					'ethereum' => '3',
					'decred' => '13.082125'
					);


/*
 * STEEM Power yearly interest rate START 11/29/2016 (1.425%, decreasing every year by roughly 0.075% until it hits a minimum of 0.075% and stays there)
 */
$steempower_yearly_interest = 1.425;  // 1.425 (DO NOT INCLUDE PERCENT SIGN) the first year at 11/29/2016 refactored rates, see above for manual yearly adjustment
$steem_powerdown_time = 13;  // Weeks to power down all STEEM Power holdings

/////////////////// GENERAL CONFIG -END- ////////////////////////////////////////////////////




/////////////////// COIN MARKETS CONFIG -START- ////////////////////////////////////////////////////

$coins_array = array(
                
                    // BTC
                    'BTC' => array(
                        
                        'coin_name' => 'Bitcoin',
                        'coin_symbol' => 'BTC',
                        'coinmarketcap' => 'bitcoin',
                        'ico' => 'no',
                        'market_ids' => array(
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
                    // XMR
                    'XMR' => array(
                        
                        'coin_name' => 'Monero',
                        'coin_symbol' => 'XMR',
                        'coinmarketcap' => 'monero',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_XMR',
                                          'hitbtc' => 'XMRBTC',
                                          'bittrex' => 'BTC-XMR',
                                          'bitfinex' => 'tXMRBTC',
                                          'kraken' => 'XXMRXXBT',
                                        	'upbit' => 'BTC-XMR',
                                          'cryptopia' => 'XMR/BTC',
                                          'bter' => 'xmr_btc',
                                          'livecoin' => 'XMR/BTC'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-XMR',
                                          'hitbtc' => 'XMRETH'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // ETH
                    'ETH' => array(
                        
                        'coin_name' => 'Ethereum',
                        'coin_symbol' => 'ETH',
                        'coinmarketcap' => 'ethereum',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_ETH',
                                          'kraken' => 'XETHXXBT',
                                          'coinbase' => 'ETH',
                                          'hitbtc' => 'ETHBTC',
                                          'gatecoin' => 'ETHBTC',
                                          'bitfinex' => 'tETHBTC',
                                          'gemini' => 'ethbtc',
                                          'bittrex' => 'BTC-ETH',
                                          'binance' => 'ETHBTC',
                                          'upbit' => 'BTC-ETH',
                                          'livecoin' => 'ETH/BTC',
                                          'liqui' => 'eth_btc',
                                          'bter' => 'eth_btc',
                                          'cryptofresh' => 'OPEN.ETH',
                                          'mercatox' => 'ETH_BTC'
                                                    ),
                                    'ltc' => array(
                                          'cryptopia' => 'ETH/LTC'
                                                    ),
                                    'usdt' => array(
                                          'poloniex' => 'USDT_ETH'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // DCR
                    'DCR' => array(
                        
                        'coin_name' => 'Decred',
                        'coin_symbol' => 'DCR',
                        'coinmarketcap' => 'decred',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_DCR',
                                          'bittrex' => 'BTC-DCR',
                                          'upbit' => 'BTC-DCR',
                                          'cryptopia' => 'DCR/BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // DASH
                    'DASH' => array(
                        
                        'coin_name' => 'Dash',
                        'coin_symbol' => 'DASH',
                        'coinmarketcap' => 'dash',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                        'poloniex' => 'BTC_DASH',
                                        'bittrex' => 'BTC-DASH',
                                        'kraken' => 'DASHXBT',
                                        'bitfinex' => 'tDSHBTC',
                                        'hitbtc' => 'DASHBTC',
                                        'upbit' => 'BTC-DASH',
                                        'livecoin' => 'DASH/BTC',
                                        'cryptopia' => 'DASH/BTC',
                                        'liqui' => 'dash_btc',
                                        'bter' => 'dash_btc',
                                        'tradesatoshi' => 'DASH_BTC',
                                        'mercatox' => 'DASH_BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // LTC
                    'LTC' => array(
                        
                        'coin_name' => 'Litecoin',
                        'coin_symbol' => 'LTC',
                        'coinmarketcap' => 'litecoin',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                        'bitfinex' => 'tLTCBTC',
                                        'poloniex' => 'BTC_LTC',
                                        'bittrex' => 'BTC-LTC',
                                        'kraken' => 'XLTCXXBT',
                                        'hitbtc' => 'LTCBTC',
                                        'binance' => 'LTCBTC',
                                        'upbit' => 'BTC-LTC',
                                        'livecoin' => 'LTC/BTC',
                                        'cryptopia' => 'LTC/BTC',
                                        'liqui' => 'ltc_btc',
                                        'bter' => 'ltc_btc',
                                        'cryptofresh' => 'OPEN.LTC',
                                        'tradesatoshi' => 'LTC_BTC',
                                        'mercatox' => 'LTC_BTC'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-LTC',
                                          'liqui' => 'ltc_eth'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // TUSD
                    'TUSD' => array(
                        
                        'coin_name' => 'True USD',
                        'coin_symbol' => 'TUSD',
                        'coinmarketcap' => 'true-usd',
                        'ico' => 'yes',
                        'market_ids' => array(
                                    'btc' => array(
                                        'bittrex' => 'BTC-TUSD',
                                        'upbit' => 'BTC-TUSD'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // STEEM
                    'STEEM' => array(
                        
                        'coin_name' => 'Steem',
                        'coin_symbol' => 'STEEM',
                        'coinmarketcap' => 'steem',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_STEEM',
                                          'bittrex' => 'BTC-STEEM',
                                          'hitbtc' => 'STEEMBTC',
                                          'upbit' => 'BTC-STEEM',
                                          'livecoin' => 'STEEM/BTC',
                                          'cryptofresh' => 'OPEN.STEEM'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // FCT
                    'FCT' => array(
                        
                        'coin_name' => 'Factom',
                        'coin_symbol' => 'FCT',
                        'coinmarketcap' => 'factom',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_FCT',
                                          'bittrex' => 'BTC-FCT',
                                        	'upbit' => 'BTC-FCT',
                                          'cryptopia' => 'FCT/BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // XLM
                    'XLM' => array(
                        
                        'coin_name' => 'Stellar',
                        'coin_symbol' => 'XLM',
                        'coinmarketcap' => 'stellar',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_STR',
                                          'bittrex' => 'BTC-XLM',
                                          'kraken' => 'XXLMXXBT',
                                          'upbit' => 'BTC-XLM'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // ANT
                    'ANT' => array(
                        
                        'coin_name' => 'Aragon',
                        'coin_symbol' => 'ANT',
                        'coinmarketcap' => 'aragon',
                        'ico' => 'yes',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-ANT',
                                        	'upbit' => 'BTC-ANT',
                                          'liqui' => 'ant_btc'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // ZRX
                    'ZRX' => array(
                        
                        'coin_name' => 'oxProject',
                        'coin_symbol' => 'ZRX',
                        'coinmarketcap' => '0x',
                        'ico' => 'yes',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_ZRX',
                                          'bittrex' => 'BTC-ZRX',
                                        	'upbit' => 'BTC-ZRX',
                                        	'ethfinex' => 'tZRXBTC',
                                          'liqui' => 'zrx_btc',
                                          'hitbtc' => 'ZRXBTC',
                                          'gatecoin' => 'ZRXBTC',
                                          'bter' => 'zrx_btc',
                                          'mercatox' => 'ZRX_BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // MANA
                    'MANA' => array(
                        
                        'coin_name' => 'Decentraland',
                        'coin_symbol' => 'MANA',
                        'coinmarketcap' => 'decentraland',
                        'ico' => 'yes',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-MANA',
                                        	'upbit' => 'BTC-MANA',
                                        	'ethfinex' => 'tMNABTC',
                                          'liqui' => 'mana_btc',
                                          'gatecoin' => 'MANBTC',
                                          'mercatox' => 'MANA_BTC'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-MANA',
                                        	'ethfinex' => 'tMNAETH',
                                          'liqui' => 'mana_eth',
                                          'gatecoin' => 'MANETH',
                                          'mercatox' => 'MANA_ETH'
                                                    ),
                                    'usdt' => array(
                                          'liqui' => 'mana_usdt',
                                          'hitbtc' => 'MANAUSD'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // DGD
                    'DGD' => array(
                        
                        'coin_name' => 'DigixDAO',
                        'coin_symbol' => 'DGD',
                        'coinmarketcap' => 'digixdao',
                        'ico' => 'yes',
                        'market_ids' => array(
                                    'btc' => array(
                                        'binance' => 'DGDBTC',
                                        'liqui' => 'dgd_btc',
                                        'hitbtc' => 'DGDBTC',
                                        'livecoin' => 'DGD/BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // SNT
                    'SNT' => array(
                        
                        'coin_name' => 'Status',
                        'coin_symbol' => 'SNT',
                        'coinmarketcap' => 'status',
                        'ico' => 'yes',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bter' => 'snt_btc',
                                          'bittrex' => 'BTC-SNT',
                                          'upbit' => 'BTC-SNT',
                                        	'ethfinex' => 'tSNTBTC',
                                          'gatecoin' => 'SNTBTC',
                                          'liqui' => 'snt_btc'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-SNT',
                                        	'ethfinex' => 'tSNTETH',
                                          'gatecoin' => 'SNTETH',
                                          'hitbtc' => 'SNTETH',
                                          'binance' => 'SNTETH',
                                          'liqui' => 'snt_eth'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // GNT
                    'GNT' => array(
                        
                        'coin_name' => 'Golem',
                        'coin_symbol' => 'GNT',
                        'coinmarketcap' => 'golem-network-tokens',
                        'ico' => 'yes',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_GNT',
                                          'bittrex' => 'BTC-GNT',
                                        	'upbit' => 'BTC-GNT',
                                        	'ethfinex' => 'tGNTBTC',
                                          'liqui' => 'gnt_btc',
                                          'mercatox' => 'GNT_BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // ADA
                    'ADA' => array(
                        
                        'coin_name' => 'Cardano',
                        'coin_symbol' => 'ADA',
                        'coinmarketcap' => 'cardano',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                        'bittrex' => 'BTC-ADA',
                                        'binance' => 'ADABTC',
                                        'upbit' => 'BTC-ADA'
                                                    ),
                                    'eth' => array(
                                        'bittrex' => 'ETH-ADA',
                                        'binance' => 'ADAETH'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // DATA
                    'DATA' => array(
                        
                        'coin_name' => 'Streamr DATAcoin',
                        'coin_symbol' => 'DATA',
                        'coinmarketcap' => 'streamr-datacoin',
                        'ico' => 'yes',
                        'market_ids' => array(
                                    'btc' => array(
                                        'hitbtc' => 'DATABTC',
                                        'ethfinex' => 'tDATBTC'
                                                    ),
                                    'eth' => array(
                                        'hitbtc' => 'DATAETH',
                                        'ethfinex' => 'tDATETH',
                                        'mercatox' => 'DATA_ETH'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                    ),
                    // BTS
                    'BTS' => array(
                        
                        'coin_name' => 'BitShares',
                        'coin_symbol' => 'BTS',
                        'coinmarketcap' => 'bitshares',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_BTS',
                                          'livecoin' => 'BTS/BTC',
                                          'bter' => 'bts_btc',
                                          'cryptofresh' => 'BTS'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // XRP
                    'XRP' => array(
                        
                        'coin_name' => 'Ripple',
                        'coin_symbol' => 'XRP',
                        'coinmarketcap' => 'ripple',
                        'ico' => 'no',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_XRP',
                                          'bittrex' => 'BTC-XRP',
                                          'kraken' => 'XXRPXXBT',
                                          'bitfinex' => 'tXRPBTC',
                                          'upbit' => 'BTC-XRP'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // DNT
                    'DNT' => array(
                        
                        'coin_name' => 'District0x',
                        'coin_symbol' => 'DNT',
                        'coinmarketcap' => 'district0x',
                        'ico' => 'yes',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-DNT',
                                        	'upbit' => 'BTC-DNT',
                                          'liqui' => 'dnt_btc',
                                          'hitbtc' => 'DNTBTC',
                                          'bter' => 'dnt_btc',
                                          'mercatox' => 'DNT_BTC'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-DNT',
                                          'binance' => 'DNTETH',
                                          'liqui' => 'dnt_eth',
                                          'mercatox' => 'DNT_ETH'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),	
                    // SWT	
                    'SWT' => array(	
                        	
                        'coin_name' => 'Swarm City',	
                        'coin_symbol' => 'SWT',	
                        'coinmarketcap' => 'swarm-city',
                         'ico' => 'yes',	
                        'market_ids' => array(	
                                    'btc' => array(	
                                          'bittrex' => 'BTC-SWT',	
                                          'hitbtc' => 'SWTBTC',	
                                        	'upbit' => 'BTC-SWT'	
                                                    )	
                                        ),	
                        'default_pairing' => 'btc'	
                        	
                    ),	
                    // POA	
                    'POA' => array(	
                        	
                        'coin_name' => 'POA Network',	
                        'coin_symbol' => 'POA',	
                        'coinmarketcap' => 'poa-network',	
                         'ico' => 'yes',
                        'market_ids' => array(	
                                    'btc' => array(	
                                          'binance' => 'POABTC'	
                                                    ),	
                                    'eth' => array(	
                                          'binance' => 'POAETH'	
                                                    )	
                                        ),	
                        'default_pairing' => 'eth'	
                        	
                    ),	
                    // MYST	
                    'MYST' => array(	
                        	
                        'coin_name' => 'Mysterium',	
                        'coin_symbol' => 'MYST',	
                        'coinmarketcap' => 'mysterium',	
                         'ico' => 'yes',
                        'market_ids' => array(	
                                    'eth' => array(	
                                          'liqui' => 'myst_eth'	
                                                    )	
                                        ),	
                        'default_pairing' => 'eth'	
                        	
                    )
                
                
);

/////////////////// COIN MARKETS CONFIG -END- ////////////////////////////////////////////////////



?>