<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

session_start();
require_once("app.lib/php/functions.php");
require_once("app.lib/php/cookies.php");

$version = '1.7.1';  // 2017/MAY/27TH


/*
 * USAGE (ADDING / UPDATING COINS) ...KRAKEN / GATECOIN / POLONIEX / COINBASE / BITTREX / bitfinex / cryptofresh / bter / gemini / HitBTC / liqui / cryptopia / livecoin BTC, ETH,
 * and ETH subtoken API SUPPORT AS OF NOW
 * Ethereum subtoken support has been built in, but values are static as no APIs exist yet
 *
 
 
                    'COIN_SYMBOL' => array(
                        
                        'coin_name' => 'COIN_NAME',
                        'coin_symbol' => 'COIN_SYMBOL',
                        'market_ids' => array(
                                          'MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'MARKETPLACE2' => 'BTC_COINSYMBOLHERE',
                                          'MARKETPLACE3' => 'BTC-COINSYMBOLHERE',
                                          'eth_subtokens_ico' => 'ETHSUBTOKENNAME' // Must be defined in $eth_subtokens_ico_values at top of config.php
                                          ),
                        'trade_pair' => 'LOWERCASE_BTC_OR_LTC_OR_ETH_TRADING_PAIR',
                        'coinmarketcap' => 'coin-slug' // Is this coin listed on coinmarketcap, leave blank if not
                        
                    )
                    
                    
                    
 * 
 */


/////////////////// GENERAL CONFIG -START- ////////////////////////////////////////////////////

$btc_in_usd = 'coinbase'; // Default Bitcoin value in USD: coinbase / bitfinex / gemini / okcoin / bitstamp / kraken

$eth_subtokens_ico_values = array(
                        // Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
                        'ETHSUBTOKENNAME' => '0.15',
                        'GOLEM' => '0.001',
                        'SWARMCITY' => '0.0133333333333333',
                        'HACKERGOLD' => '0.0071',
                        'ARAGON' => '0.01'
                        );


/*
 * STEEM Power yearly interest rate START 11/29/2016 (1.425%, decreasing every year by roughly 0.075% until it hits a minimum of 0.075% and stays there)
 */
$steempower_yearly_interest = 1.425;  // 1.425 (DON NOT INCLUDE PERCENT SIGN) the first year at 11/29/2016 refactored rates, see above for manual yearly adjustment
$steem_powerdown_time = 13;  // Weeks to power down all STEEM Power holdings

/////////////////// GENERAL CONFIG -END- ////////////////////////////////////////////////////



/////////////////// COIN MARKETS CONFIG -START- ////////////////////////////////////////////////////

$coins_array = array(
                
                
                    'BTC' => array(
                        
                        'coin_name' => 'Bitcoin',
                        'coin_symbol' => 'BTC',
                        'market_ids' => array(
                                          'okcoin' => 'okcoin',
                                          'bitfinex' => 'bitfinex',
                                          'kraken' => 'kraken',
                                          'coinbase' => 'coinbase',
                                          'bitstamp' => 'bitstamp',
                                          'gemini' => 'gemini',
                                          'hitbtc' => 'hitbtc',
                                          'gatecoin' => 'gatecoin',
                                          'livecoin' => 'livecoin'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'bitcoin'
                        
                    ),
                    'ETH' => array(
                        
                        'coin_name' => 'Ethereum',
                        'coin_symbol' => 'ETH',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_ETH',
                                          'kraken' => 'XETHXXBT',
                                          'coinbase' => 'ETH',
                                          'hitbtc' => 'ETHBTC',
                                          'gatecoin' => 'ETHBTC',
                                          'bitfinex' => 'ethbtc',
                                          'gemini' => 'ethbtc',
                                          'bittrex' => 'BTC-ETH',
                                          'livecoin' => 'ETH/BTC',
                                          'liqui' => 'eth_btc',
                                          'bter' => 'eth_btc',
                                          'cryptofresh' => 'OPEN.ETH'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'ethereum'
                        
                    ),
                    'XMR' => array(
                        
                        'coin_name' => 'Monero',
                        'coin_symbol' => 'XMR',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_XMR',
                                          'hitbtc' => 'XMRBTC',
                                          'bittrex' => 'BTC-XMR',
                                          'bitfinex' => 'xmrbtc',
                                          'kraken' => 'XXMRXXBT',
                                          'cryptopia' => 'XMR/BTC',
                                          'bter' => 'xmr_btc',
                                          'livecoin' => 'XMR/BTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'monero'
                        
                    ),
                    'LTC' => array(
                        
                        'coin_name' => 'Litecoin',
                        'coin_symbol' => 'LTC',
                        'market_ids' => array(
                                          'bitfinex' => 'ltcbtc',
                                          'poloniex' => 'BTC_LTC',
                                          'bittrex' => 'BTC-LTC',
                                          'kraken' => 'XLTCXXBT',
                                          'hitbtc' => 'LTCBTC',
                                          'livecoin' => 'LTC/BTC',
                                          'cryptopia' => 'LTC/BTC',
                                          'liqui' => 'ltc_btc',
                                          'bter' => 'ltc_btc',
                                          'cryptofresh' => 'OPEN.LTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'litecoin'
                        
                    ),
                    'DASH' => array(
                        
                        'coin_name' => 'Dash',
                        'coin_symbol' => 'DASH',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_DASH',
                                          'bittrex' => 'BTC-DASH',
                                          'kraken' => 'DASHXBT',
                                          'bitfinex' => 'dshbtc',
                                          'hitbtc' => 'DASHBTC',
                                          'livecoin' => 'DASH/BTC',
                                          'cryptopia' => 'DASH/BTC',
                                          'liqui' => 'dash_btc',
                                          'bter' => 'dash_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'dash'
                        
                    ),
                    'PPC' => array(
                        
                        'coin_name' => 'Peercoin',
                        'coin_symbol' => 'PPC',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_PPC',
                                          'bittrex' => 'BTC-PPC',
                                          'livecoin' => 'PPC/BTC',
                                          'cryptopia' => 'PPC/BTC',
                                          'bter' => 'ppc_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'peercoin'
                        
                    ),
                    'STEEM' => array(
                        
                        'coin_name' => 'Steem',
                        'coin_symbol' => 'STEEM',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_STEEM',
                                          'bittrex' => 'BTC-STEEM',
                                          'hitbtc' => 'STEEMBTC',
                                          'livecoin' => 'STEEM/BTC',
                                          'cryptofresh' => 'OPEN.STEEM'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'steem'
                        
                    ),
                    'BTS' => array(
                        
                        'coin_name' => 'BitShares',
                        'coin_symbol' => 'BTS',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_BTS',
                                          'bittrex' => 'BTC-BTS',
                                          'livecoin' => 'BTS/BTC',
                                          'bter' => 'bts_btc',
                                          'cryptofresh' => 'BTS'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'bitshares'
                        
                    ),
                    'NXT' => array(
                        
                        'coin_name' => 'NXT',
                        'coin_symbol' => 'NXT',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_NXT',
                                          'bittrex' => 'BTC-NXT',
                                          'hitbtc' => 'NXTBTC',
                                          'livecoin' => 'NXT/BTC',
                                          'bter' => 'nxt_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'nxt'
                        
                    ),
                    'FCT' => array(
                        
                        'coin_name' => 'Factom',
                        'coin_symbol' => 'FCT',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_FCT',
                                          'cryptopia' => 'FCT/BTC',
                                          'bittrex' => 'BTC-FCT'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'factom'
                        
                    ),
                    'SWT' => array(
                        
                        'coin_name' => 'Swarm City',
                        'coin_symbol' => 'SWT',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-SWT',
                                          'hitbtc' => 'SWTBTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'swarm-city'
                        
                    ),
                    'ANT' => array(
                        
                        'coin_name' => 'Aragon',
                        'coin_symbol' => 'ANT',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-ANT',
                                          'liqui' => 'ant_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'aragon'
                        
                    ),
                    'GNT' => array(
                        
                        'coin_name' => 'Golem',
                        'coin_symbol' => 'GNT',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_GNT',
                                          'bittrex' => 'BTC-GNT',
                                          'liqui' => 'gnt_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'golem-network-tokens'
                        
                    ),
                    'DGD' => array(
                        
                        'coin_name' => 'DigixDAO',
                        'coin_symbol' => 'DGD',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-DGD',
                                          'hitbtc' => 'DGDBTC',
                                          'gatecoin' => 'DGDBTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'digixdao'
                        
                    ),
                    'REP' => array(
                        
                        'coin_name' => 'Augur',
                        'coin_symbol' => 'REP',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_REP',
                                          'kraken' => 'XREPXXBT',
                                          'gatecoin' => 'REPBTC',
                                          'liqui' => 'rep_btc',
                                          'bter' => 'rep_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'augur'
                        
                    ),
                    'GNO' => array(
                        
                        'coin_name' => 'Gnosis',
                        'coin_symbol' => 'GNO',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_GNO',
                                          'bittrex' => 'BTC-GNO',
                                          'kraken' => 'GNOXBT',
                                          'hitbtc' => 'GNOBTC',
                                          'liqui' => 'gno_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'gnosis-gno'
                        
                    ),
                    'SNGLS' => array(
                        
                        'coin_name' => 'SingularDTV',
                        'coin_symbol' => 'SNGLS',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-SNGLS',
                                          'hitbtc' => 'SNGLSBTC',
                                          'gatecoin' => 'SNGBTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'singulardtv'
                        
                    ),
                    'LUN' => array(
                        
                        'coin_name' => 'Lunyr',
                        'coin_symbol' => 'LUN',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-LUN',
                                          'liqui' => 'lun_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'lunyr'
                        
                    ),
                    'TRST' => array(
                        
                        'coin_name' => 'WeTrust',
                        'coin_symbol' => 'TRST',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-TRST',
                                          'hitbtc' => 'TRSTBTC',
                                          'liqui' => 'trst_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'trust'
                        
                    ),
                    'TKN' => array(
                        
                        'coin_name' => 'TokenCard',
                        'coin_symbol' => 'TKN',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-TKN',
                                          'liqui' => 'tkn_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'tokencard'
                        
                    ),
                    'ZCL' => array(
                        
                        'coin_name' => 'Zclassic',
                        'coin_symbol' => 'ZCL',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-ZCL',
                                          'cryptopia' => 'ZCL/BTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'zclassic'
                        
                    ),
                    'ZEN' => array(
                        
                        'coin_name' => 'ZenCash',
                        'coin_symbol' => 'ZEN',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-ZEN'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => ''
                        
                    ),
                    'XLM' => array(
                        
                        'coin_name' => 'Stellar',
                        'coin_symbol' => 'XLM',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_STR',
                                          'bittrex' => 'BTC-XLM',
                                          'kraken' => 'XXLMXXBT'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'stellar'
                        
                    ),
                    'XRP' => array(
                        
                        'coin_name' => 'Ripple',
                        'coin_symbol' => 'XRP',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_XRP',
                                          'bittrex' => 'BTC-XRP',
                                          'kraken' => 'XXRPXXBT',
                                          'bitfinex' => 'xrpbtc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'ripple'
                        
                    ),
                    'LSK' => array(
                        
                        'coin_name' => 'Lisk',
                        'coin_symbol' => 'LSK',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_LSK',
                                          'bittrex' => 'BTC-LSK',
                                          'hitbtc' => 'LSKBTC',
                                          'livecoin' => 'LSK/BTC',
                                          'cryptofresh' => 'OPEN.LISK'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'lisk'
                        
                    ),
                    'MAID' => array(
                        
                        'coin_name' => 'MaidSafecoin',
                        'coin_symbol' => 'MAID',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_MAID',
                                          'bittrex' => 'BTC-MAID',
                                          'hitbtc' => 'MAIDBTC',
                                          'livecoin' => 'MAID/BTC',
                                          'cryptopia' => 'MAID/BTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'maidsafecoin'
                        
                    )
                
                
);

/////////////////// COIN MARKETS CONFIG -END- ////////////////////////////////////////////////////


?>