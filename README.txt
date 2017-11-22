
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Developed by Michael Kilday <mike@dragonfrugal.com>, released free / open source under GPL v3
https://dragonfrugal.com/downloads/
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Just upload to your PHP-based web server, and you should be all set. You must have curl modules activated on your HTTP server, most web hosting companies provide this already. Contact your hosting provider if you encounter issues getting the real-time prices feeds from exchanges, and ask if curl is setup already. See below for an example on adding / editing your own markets into the coin list in config.php...it's very quick / easy to do (see bottom of this file for a pre-configured example set of assets / markets). Currently BTC / ETH / LTC / USDT based market pairing is compatible. Contact any supported exchanges help desk if you are unaware of the correct formatting of the trading pair name you are adding in the API configuration file (example: kraken has abitrary Xs inserted everywhere in SOME older pair names).

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
 * USAGE (ADDING / UPDATING COINS) ...API support for: kraken / gatecoin / poloniex / coinbase / bittrex / bitfinex / cryptofresh / bter / gemini / hitbtc / liqui / cryptopia / livecoin / mercatox...BTC, ETH, LTC, AND USDT trading pair support
 * Ethereum ICO subtoken support has been built in, but values are static ICO values in ETH
 *
 SEE THE BOTTOM OF THE README.txt FOR FOR AN EXAMPLE SET OF PRE-CONFIGURED ASSETS
 
 
                    // UPPERCASE_COIN_SYMBOL
                    'UPPERCASE_COIN_SYMBOL' => array(
                        
                        'coin_name' => 'COIN_NAME',
                        'coin_symbol' => 'UPPERCASE_COIN_SYMBOL',
                        'coinmarketcap' => 'coin-slug', // Is this coin listed on coinmarketcap, leave blank if not
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
                                                    )
                                          ),
                        'default_pairing' => 'LOWERCASE_BTC_OR_LTC_OR_ETH_TRADING_PAIR'
                        
                    ),
                    
                    
 * 
 */
 
BELOW IS AN !---EXAMPLE---! SET OF CONFIGURED ASSETS. PLEASE NOTE THIS IS PROVIDED TO ASSIST YOU IN ADDING YOUR PARTICULAR FAVORITE ASSETS TO THE DEFAULT LIST, AND !---IN NO WAY---! INDICATES ENDORSEMENT OF !---ANY---! OF THESE ASSETS:



/////////////////// GENERAL CONFIG -START- ////////////////////////////////////////////////////

$btc_in_usd = 'coinbase'; // Default Bitcoin value in USD: coinbase / bitfinex / gemini / okcoin / bitstamp / kraken / hitbtc / gatecion / livecoin

$coinmarketcap_ranks_max = '450'; // Maximum number of Coinmarketcap.com rankings to request from their API

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
                        'market_ids' => array(
                                    'btc' => array(
                                          'okcoin' => 'okcoin',
                                          'bitfinex' => 'bitfinex',
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
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_XMR',
                                          'hitbtc' => 'XMRBTC',
                                          'bittrex' => 'BTC-XMR',
                                          'bitfinex' => 'xmrbtc',
                                          'kraken' => 'XXMRXXBT',
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
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_ETH',
                                          'kraken' => 'XETHXXBT',
                                          'coinbase' => 'ETH',
                                          'hitbtc' => 'ETHBTC',
                                          'gatecoin' => 'ETHBTC',
                                          'bitfinex' => 'ethbtc',
                                          'gemini' => 'ethbtc',
                                          'bittrex' => 'BTC-ETH',
                                          'binance' => 'ETHBTC',
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
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_DCR',
                                          'bittrex' => 'BTC-DCR',
                                          'cryptopia' => 'DCR/BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // STEEM
                    'STEEM' => array(
                        
                        'coin_name' => 'Steem',
                        'coin_symbol' => 'STEEM',
                        'coinmarketcap' => 'steem',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_STEEM',
                                          'bittrex' => 'BTC-STEEM',
                                          'hitbtc' => 'STEEMBTC',
                                          'livecoin' => 'STEEM/BTC',
                                          'cryptofresh' => 'OPEN.STEEM'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // DASH
                    'DASH' => array(
                        
                        'coin_name' => 'Dash',
                        'coin_symbol' => 'DASH',
                        'coinmarketcap' => 'dash',
                        'market_ids' => array(
                                    'btc' => array(
                                        'poloniex' => 'BTC_DASH',
                                        'bittrex' => 'BTC-DASH',
                                        'kraken' => 'DASHXBT',
                                        'bitfinex' => 'dshbtc',
                                        'hitbtc' => 'DASHBTC',
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
                        'market_ids' => array(
                                    'btc' => array(
                                        'bitfinex' => 'ltcbtc',
                                        'poloniex' => 'BTC_LTC',
                                        'bittrex' => 'BTC-LTC',
                                        'kraken' => 'XLTCXXBT',
                                        'hitbtc' => 'LTCBTC',
                                        'binance' => 'LTCBTC',
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
                    // VTC
                    'VTC' => array(
                        
                        'coin_name' => 'Vertcoin',
                        'coin_symbol' => 'VTC',
                        'coinmarketcap' => 'vertcoin',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_VTC',
                                          'bittrex' => 'BTC-VTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // XMY
                    'XMY' => array(
                        
                        'coin_name' => 'Myriad',
                        'coin_symbol' => 'XMY',
                        'coinmarketcap' => 'myriad',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-XMY',
                                          'cryptopia' => 'XMY/BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // PPC
                    'PPC' => array(
                        
                        'coin_name' => 'Peercoin',
                        'coin_symbol' => 'PPC',
                        'coinmarketcap' => 'peercoin',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_PPC',
                                          'bittrex' => 'BTC-PPC',
                                          'livecoin' => 'PPC/BTC',
                                          'cryptopia' => 'PPC/BTC',
                                          'bter' => 'ppc_btc'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // FTC
                    'FTC' => array(
                        
                        'coin_name' => 'Feathercoin',
                        'coin_symbol' => 'FTC',
                        'coinmarketcap' => 'feathercoin',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-FTC',
                                          'cryptopia' => 'FTC/BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // ZRX
                    'ZRX' => array(
                        
                        'coin_name' => 'oxProject',
                        'coin_symbol' => 'ZRX',
                        'coinmarketcap' => '0x',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_ZRX',
                                          'liqui' => 'zrx_btc',
                                          'hitbtc' => 'ZRXBTC',
                                          'gatecoin' => 'ZRXBTC',
                                          'bter' => 'zrx_btc',
                                          'mercatox' => 'ZRX_BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // ANT
                    'ANT' => array(
                        
                        'coin_name' => 'Aragon',
                        'coin_symbol' => 'ANT',
                        'coinmarketcap' => 'aragon',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-ANT',
                                          'liqui' => 'ant_btc'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // DNT
                    'DNT' => array(
                        
                        'coin_name' => 'District0x',
                        'coin_symbol' => 'DNT',
                        'coinmarketcap' => 'district0x',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-DNT',
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
                    // MANA
                    'MANA' => array(
                        
                        'coin_name' => 'Decentraland',
                        'coin_symbol' => 'MANA',
                        'coinmarketcap' => 'decentraland',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-MANA',
                                          'liqui' => 'mana_btc',
                                          'gatecoin' => 'MANBTC',
                                          'mercatox' => 'MANA_BTC'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-MANA',
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
                    // SWT
                    'SWT' => array(
                        
                        'coin_name' => 'Swarm City',
                        'coin_symbol' => 'SWT',
                        'coinmarketcap' => 'swarm-city',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-SWT',
                                          'hitbtc' => 'SWTBTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // GNT
                    'GNT' => array(
                        
                        'coin_name' => 'Golem',
                        'coin_symbol' => 'GNT',
                        'coinmarketcap' => 'golem-network-tokens',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_GNT',
                                          'bittrex' => 'BTC-GNT',
                                          'liqui' => 'gnt_btc',
                                          'mercatox' => 'GNT_BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // MYST
                    'MYST' => array(
                        
                        'coin_name' => 'Mysterium',
                        'coin_symbol' => 'MYST',
                        'coinmarketcap' => 'mysterium',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-MYST',
                                          'liqui' => 'myst_btc'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // SNT
                    'SNT' => array(
                        
                        'coin_name' => 'Status',
                        'coin_symbol' => 'SNT',
                        'coinmarketcap' => 'status',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bter' => 'snt_btc',
                                          'bittrex' => 'BTC-SNT',
                                          'gatecoin' => 'SNTBTC',
                                          'liqui' => 'snt_btc'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-SNT',
                                          'gatecoin' => 'SNTETH',
                                          'hitbtc' => 'SNTETH',
                                          'binance' => 'SNTETH',
                                          'liqui' => 'snt_eth'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // MCO
                    'MCO' => array(
                        
                        'coin_name' => 'Monaco',
                        'coin_symbol' => 'MCO',
                        'coinmarketcap' => 'monaco',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-MCO',
                                          'binance' => 'MCOBTC',
                                          'liqui' => 'mco_btc',
                                          'livecoin' => 'MCO/BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // BAT
                    'BAT' => array(
                        
                        'coin_name' => 'Basic Attention Token',
                        'coin_symbol' => 'BAT',
                        'coinmarketcap' => 'basic-attention-token',
                        'market_ids' => array(
                                    'btc' => array(
                                          'bittrex' => 'BTC-BAT',
                                          'liqui' => 'bat_btc',
                                          'bter' => 'bat_btc',
                                          'mercatox' => 'BAT_BTC'
                                                    ),
                                    'eth' => array(
                                          'bittrex' => 'ETH-BAT',
                                          'liqui' => 'bat_eth',
                                          'mercatox' => 'BAT_ETH'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // BTS
                    'BTS' => array(
                        
                        'coin_name' => 'BitShares',
                        'coin_symbol' => 'BTS',
                        'coinmarketcap' => 'bitshares',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_BTS',
                                          'bittrex' => 'BTC-BTS',
                                          'livecoin' => 'BTS/BTC',
                                          'bter' => 'bts_btc',
                                          'cryptofresh' => 'BTS'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // FCT
                    'FCT' => array(
                        
                        'coin_name' => 'Factom',
                        'coin_symbol' => 'FCT',
                        'coinmarketcap' => 'factom',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_FCT',
                                          'bittrex' => 'BTC-FCT',
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
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_STR',
                                          'bittrex' => 'BTC-XLM',
                                          'kraken' => 'XXLMXXBT'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // XRP
                    'XRP' => array(
                        
                        'coin_name' => 'Ripple',
                        'coin_symbol' => 'XRP',
                        'coinmarketcap' => 'ripple',
                        'market_ids' => array(
                                    'btc' => array(
                                          'poloniex' => 'BTC_XRP',
                                          'bittrex' => 'BTC-XRP',
                                          'kraken' => 'XXRPXXBT',
                                          'bitfinex' => 'xrpbtc'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    ),
                    // PIRL
                    'PIRL' => array(
                        
                        'coin_name' => 'Pirl',
                        'coin_symbol' => 'PIRL',
                        'coinmarketcap' => 'pirl',
                        'market_ids' => array(
                                    'btc' => array(
                                          'cryptopia' => 'PIRL/BTC'
                                                    )
                                        ),
                        'default_pairing' => 'btc'
                        
                    )
                
                
);

/////////////////// COIN MARKETS CONFIG -END- ////////////////////////////////////////////////////


