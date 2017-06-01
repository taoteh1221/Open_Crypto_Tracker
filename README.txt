
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Developed by Michael Kilday <mike@dragonfrugal.com>, released free / open source under GPL v3
https://dragonfrugal.com/downloads/
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Just upload to your PHP-based web server, and you should be all set. You must have curl modules activated on your HTTP server, most web hosting companies provide this already. Contact your hosting provider if you encounter issues getting the real-time prices feeds from exchanges, and ask if curl is setup already. See below for an example on adding / editing your own markets into the coin list in config.php...it's very quick / easy to do (see bottom of this file for a pre-configured example set of assets / markets). Currently only BTC / ETH based markets are compatible. Contact any supported exchanges help desk if you are unaware of the correct formatting of the trading pair name you are adding in the API configuration file (example: kraken has abitrary Xs inserted everywhere in SOME older pair names).

Feature requests and bug reports can be filed at the following URLS:

https://github.com/taoteh1221/DFD_Cryptocoin_Values/issues

https://dragonfrugal.com/contact/

Donations are welcome to support further development... 

BTC: 1FfWHekHPLH7hQcU4d5MBVQ4WekJiA8Mk2

XMR: 47mWWjuwPFiPD6t2MaWcMEfejtQpMuz9oj5hJq18f7nvagcmoJwxudKHUppaWnTMPaMWshMWUTPAUX623KyEtukbSMdmpqu

ETH: 0xf3da0858c3cfcc28a75c1232957a7fb190d7e5e9

STEEM: taoteh1221

OTHER CRYPTOCURRENCIES AND PAYPAL ACCEPTED HERE: https://dragonfrugal.com/donate/

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
 
BELOW IS AN EXAMPLE SET OF ASSETS FULLY CONFIGURED TO THE LATEST MARKETS AT THE TIME OF THIS WRITING (2017-JUNE-1ST). PLEASE NOTE THIS IS PROVIDED TO ASSIST YOU IN ADDING YOUR PARTICULAR FAVORITE ASSETS TO THE DEFAULT LIST, AND ---IN NO WAY--- INDICATES ENDORSEMENT OF ---ANY-- OF THESE ASSETS:

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
                    'ZCL' => array(
                        
                        'coin_name' => 'Zclassic',
                        'coin_symbol' => 'ZCL',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-ZCL',
                                          'cryptopia' => 'ZCL/BTC',
                                          'tradesatoshi' => 'ZCL_BTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'zclassic'
                        
                    ),
                    'ZEN' => array(
                        
                        'coin_name' => 'ZenCash',
                        'coin_symbol' => 'ZEN',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-ZEN',
                                          'tradesatoshi' => 'ZEN_BTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'zencash'
                        
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

