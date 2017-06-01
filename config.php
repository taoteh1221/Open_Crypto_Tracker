<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

session_start();
require_once("app.lib/php/functions.php");
require_once("app.lib/php/cookies.php");

$version = '1.7.4';  // 2017/JUNE/1ST


/*
 * USAGE (ADDING / UPDATING COINS) ...kraken / gatecoin / poloniex / coinbase / bittrex / bitfinex / cryptofresh / bter / gemini / hitbtc / liqui / cryptopia / livecoin BTC, ETH, and ETH subtoken SUPPORT AS OF NOW
 * Ethereum ICO subtoken support has been built in, but values are static ICO values in ETH
 *
 SEE THE BOTTOM OF THE README.txt FOR FOR AN EXAMPLE SET OF PRE-CONFIGURED ASSETS
 
                    'UPPERCASE_COIN_SYMBOL' => array(
                        
                        'coin_name' => 'COIN_NAME',
                        'coin_symbol' => 'UPPERCASE_COIN_SYMBOL',
                        'market_ids' => array(
                                          'LOWERCASE_MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'LOWERCASE_MARKETPLACE2' => 'BTC_COINSYMBOLHERE',
                                          'LOWERCASE_MARKETPLACE3' => 'BTC-COINSYMBOLHERE',
                                          'eth_subtokens_ico' => 'THEDAO' // Must be defined in $eth_subtokens_ico_values at top of config.php
                                          ),
                        'trade_pair' => 'LOWERCASE_BTC_OR_LTC_OR_ETH_TRADING_PAIR',
                        'coinmarketcap' => 'coin-slug' // Is this coin listed on coinmarketcap, leave blank if not
                        
                    )
                    
                    
                    
 * 
 */


/////////////////// GENERAL CONFIG -START- ////////////////////////////////////////////////////

$btc_in_usd = 'coinbase'; // Default Bitcoin value in USD: coinbase / bitfinex / gemini / okcoin / bitstamp / kraken / hitbtc / gatecion / livecoin

$eth_subtokens_ico_values = array(
                        // Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
                        'ETHSUBTOKENNAME' => '0.15',
                        'GOLEM' => '0.001',
                        'SWARMCITY' => '0.0133333333333333',
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
                                          'cryptofresh' => 'OPEN.LTC',
                                          'tradesatoshi' => 'LTC_BTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'litecoin'
                        
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
                                          'bter' => 'dash_btc',
                                          'tradesatoshi' => 'DASH_BTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'dash'
                        
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
                        
                    )
                
                
);

/////////////////// COIN MARKETS CONFIG -END- ////////////////////////////////////////////////////


?>