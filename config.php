<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


session_start();

require_once("app.lib/php/functions.php");

require_once("app.lib/php/cookies.php");

$version = '1.6.6';  // 2017/MAY/2ND

$btc_in_usd = 'coinbase'; // Default Bitcoin value in USD: coinbase / bitfinex / gemini / okcoin / bitstamp / kraken

$steem_powerdown_time = 13;  // Weeks to power down all STEEM Power holdings

/*
 * STEEM Power yearly interest rate START 11/29/2016 (1.425%, decreasing every year by roughly 0.075% until it hits a minimum of 0.075% and stays there)
 */
$steempower_yearly_interest = 1.425;  // 1.425 (DON NOT INCLUDE PERCENT SIGN) the first year at 11/29/2016 refactored rates, see above for manual yearly adjustment

$eth_subtokens_ico_values = array(
                        // Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
                        'ETHSUBTOKENNAME' => '0.15',
                        'GOLEM' => '0.001',
                        'SWARMCITY' => '0.0133333333333333',
                        'HACKERGOLD' => '0.0071'
                        );

/*
 * USAGE (ADDING / UPDATING COINS) ...ONLY KRAKEN / GATECOIN / POLONIEX / COINBASE / BITTREX / bitfinex / cryptofresh / bter / gemini BTC, altcoin, and token API SUPPORT AS OF NOW
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
                                          'hitbtc' => 'hitbtc'
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
                                          'bter' => 'xmr_btc'
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
                                          'bter' => 'eth_btc',
                                          'cryptofresh' => 'OPEN.ETH'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'ethereum'
                        
                    ),
                    'DASH' => array(
                        
                        'coin_name' => 'Dash',
                        'coin_symbol' => 'DASH',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_DASH',
                                          'bittrex' => 'BTC-DASH',
                                          'hitbtc' => 'DASHBTC',
                                          'bter' => 'dash_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'dash'
                        
                    ),
                    'LTC' => array(
                        
                        'coin_name' => 'Litecoin',
                        'coin_symbol' => 'LTC',
                        'market_ids' => array(
                                          'bitfinex' => 'ltcbtc',
                                          'poloniex' => 'BTC_LTC',
                                          'bittrex' => 'BTC-LTC',
                                          'hitbtc' => 'LTCBTC',
                                          'bter' => 'ltc_btc',
                                          'cryptofresh' => 'OPEN.LTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'litecoin'
                        
                    ),
                    'STEEM' => array(
                        
                        'coin_name' => 'Steem',
                        'coin_symbol' => 'STEEM',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_STEEM',
                                          'bittrex' => 'BTC-STEEM',
                                          'hitbtc' => 'STEEMBTC',
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
                                          'bter' => 'bts_btc',
                                          'cryptofresh' => 'BTS'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'bitshares'
                        
                    ),
                    'FCT' => array(
                        
                        'coin_name' => 'Factom',
                        'coin_symbol' => 'FCT',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_FCT',
                                          'bittrex' => 'BTC-FCT'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'factom'
                        
                    ),
                    'PPC' => array(
                        
                        'coin_name' => 'Peercoin',
                        'coin_symbol' => 'PPC',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_PPC',
                                          'bittrex' => 'BTC-PPC',
                                          'bter' => 'ppc_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'peercoin'
                        
                    ),
                    'NXT' => array(
                        
                        'coin_name' => 'NXT',
                        'coin_symbol' => 'NXT',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_NXT',
                                          'bittrex' => 'BTC-NXT',
                                          'hitbtc' => 'NXTBTC',
                                          'bter' => 'nxt_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'nxt'
                        
                    ),
                    'SWT' => array(
                        
                        'coin_name' => 'Swarm City',
                        'coin_symbol' => 'SWT',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-SWT'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'swarm-city'
                        
                    ),
                    'LUN' => array(
                        
                        'coin_name' => 'Lunyr',
                        'coin_symbol' => 'LUN',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-LUN'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'lunyr'
                        
                    ),
                    'GNT' => array(
                        
                        'coin_name' => 'Golem',
                        'coin_symbol' => 'GNT',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_GNT'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'golem-network-tokens'
                        
                    ),
                    'TRST' => array(
                        
                        'coin_name' => 'WeTrust',
                        'coin_symbol' => 'TRST',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-TRST'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'trust'
                        
                    ),
                    'REP' => array(
                        
                        'coin_name' => 'Augur',
                        'coin_symbol' => 'REP',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_REP',
                                          'kraken' => 'XREPXXBT',
                                          'gatecoin' => 'REPBTC',
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
                                          'kraken' => 'GNOXBT'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'gnosis-gno'
                        
                    ),
                    'ZEC' => array(
                        
                        'coin_name' => 'Zcash',
                        'coin_symbol' => 'ZEC',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_ZEC',
                                          'bittrex' => 'BTC-ZEC',
                                          'hitbtc' => 'ZECBTC',
                                          'bter' => 'zec_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'zcash'
                        
                    ),
                    'DCR' => array(
                        
                        'coin_name' => 'Decred',
                        'coin_symbol' => 'DCR',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_DCR',
                                          'bittrex' => 'BTC-DCR'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'decred'
                        
                    ),
                    'PIVX' => array(
                        
                        'coin_name' => 'PIVX',
                        'coin_symbol' => 'PIVX',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-PIVX'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'pivx'
                        
                    ),
                    'XLM' => array(
                        
                        'coin_name' => 'Stellar',
                        'coin_symbol' => 'XLM',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_STR',
                                          'bittrex' => 'BTC-XLM'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'stellar'
                        
                    ),
                    'XRP' => array(
                        
                        'coin_name' => 'Ripple',
                        'coin_symbol' => 'XRP',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_XRP',
                                          'bittrex' => 'BTC-XRP'
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
                                          'bittrex' => 'BTC-MAID'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'maidsafecoin'
                        
                    ),
                    'HKG' => array(
                        
                        'coin_name' => 'Hacker Gold',
                        'coin_symbol' => 'HKG',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-HKG',
                                          'bter' => 'hkg_btc'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'hacker-gold'
                        
                    ),
                    'AMP' => array(
                        
                        'coin_name' => 'Synereo',
                        'coin_symbol' => 'AMP',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_AMP',
                                          'bittrex' => 'BTC-AMP'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'synereo'
                        
                    )
                
                
);

?>
