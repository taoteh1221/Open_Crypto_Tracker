<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


session_start();

require_once("app.lib/php/functions.php");

require_once("app.lib/php/cookies.php");

$version = '1.5.4';  // 2016/NOV/22th

$btc_in_usd = 'coinbase'; // Default Bitcoin value in USD: coinbase / bitfinex / gemini / okcoin / bitstamp / kraken

$steem_powerdown_time = 12;  // Weeks to power down all STEEM Power holdings

/*
 * STEEM Power yearly interest rate START 11/29/2016 (1.425%, decreasing every year by roughly 0.075% until it hits a minimum of 0.075% and stays there)
 */
$steempower_yearly_interest = 1.425;  // 1.425 (DON NOT INCLUDE PERCENT SIGN) the first year at 11/29/2016 refactored rates, see above for manual yearly adjustment

$eth_subtokens_values = array(
                        // Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
                        'ETHSUBTOKENNAME' => '0.15',
                        'GOLEM' => '0.001',
                        'ARCADECITY' => '0.0133333333333333'
                        );

/*
 * USAGE (ADDING / UPDATING COINS) ...ONLY KRAKEN / GATECOIN / POLONIEX / COINBASE / BITTREX / bitfinex / cryptofresh / gemini BTC, altcoin, and token API SUPPORT AS OF NOW
 * Ethereum subtoken support has been built in, but values are static as no APIs exist yet
 *
 
 
                    'COIN_SYMBOL' => array(
                        
                        'coin_name' => 'COIN_NAME',
                        'coin_symbol' => 'COIN_SYMBOL',
                        'market_ids' => array(
                                          'MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'MARKETPLACE2' => 'BTC_COINSYMBOLHERE',
                                          'MARKETPLACE3' => 'BTC-COINSYMBOLHERE',
                                          'ethereum_subtokens' => 'ETHSUBTOKENNAME' // Must be defined in $eth_subtokens_values at top of config.php
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
                                          'gemini' => 'gemini'
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
                                          'gatecoin' => 'ETHBTC',
                                          'bitfinex' => 'ethbtc',
                                          'gemini' => 'ethbtc',
                                          'bittrex' => 'BTC-ETH',
                                          'cryptofresh' => 'OPEN.ETH'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'ethereum'
                        
                    ),
                    'STEEM' => array(
                        
                        'coin_name' => 'Steem',
                        'coin_symbol' => 'STEEM',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_STEEM',
                                          'bittrex' => 'BTC-STEEM',
                                          'cryptofresh' => 'OPEN.STEEM'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'steem'
                        
                    ),
                    'SBD' => array(
                        
                        'coin_name' => 'SteemDollars',
                        'coin_symbol' => 'SBD',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_SBD',
                                          'bittrex' => 'BTC-SBD'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => ''
                        
                    ),
                    'DASH' => array(
                        
                        'coin_name' => 'Dash',
                        'coin_symbol' => 'DASH',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_DASH',
                                          'bittrex' => 'BTC-DASH'
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
                                          'cryptofresh' => 'OPEN.LTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'litecoin'
                        
                    ),
                    'PPC' => array(
                        
                        'coin_name' => 'Peercoin',
                        'coin_symbol' => 'PPC',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_PPC',
                                          'bittrex' => 'BTC-PPC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'peercoin'
                        
                    ),
                    'XMR' => array(
                        
                        'coin_name' => 'Monero',
                        'coin_symbol' => 'XMR',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_XMR',
                                          'bittrex' => 'BTC-XMR'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'monero'
                        
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
                    'NXT' => array(
                        
                        'coin_name' => 'NXT',
                        'coin_symbol' => 'NXT',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_NXT',
                                          'bittrex' => 'BTC-NXT'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'nxt'
                        
                    ),
                    'BTS' => array(
                        
                        'coin_name' => 'BitShares',
                        'coin_symbol' => 'BTS',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_BTS',
                                          'bittrex' => 'BTC-BTS',
                                          'cryptofresh' => 'BTS'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'bitshares'
                        
                    ),
                    'GNT' => array(
                        
                        'coin_name' => 'Golem',
                        'coin_symbol' => 'GNT',
                        'market_ids' => array(
                                          'ethereum_subtokens' => 'GOLEM'
                                          ),
                        'trade_pair' => 'eth',
                        'coinmarketcap' => ''
                        
                    ),
                    'ARC' => array(
                        
                        'coin_name' => 'Arcade City',
                        'coin_symbol' => 'ARC',
                        'market_ids' => array(
                                          'ethereum_subtokens' => 'ARCADECITY'
                                          ),
                        'trade_pair' => 'eth',
                        'coinmarketcap' => ''
                        
                    ),
                    'XZC' => array(
                        
                        'coin_name' => 'Zcoin',
                        'coin_symbol' => 'XZC',
                        'market_ids' => array(
                                          'bittrex' => 'BTC-XZC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'zcoin'
                        
                    ),
                    'ZEC' => array(
                        
                        'coin_name' => 'Zcash',
                        'coin_symbol' => 'ZEC',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_ZEC',
                                          'bittrex' => 'BTC-ZEC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'zcash'
                        
                    ),
                    'REP' => array(
                        
                        'coin_name' => 'Augur',
                        'coin_symbol' => 'REP',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_REP',
                                          'kraken' => 'XREPXXBT',
                                          'gatecoin' => 'REPBTC',
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'augur'
                        
                    ),
                    'SDC' => array(
                        
                        'coin_name' => 'ShadowCash',
                        'coin_symbol' => 'SDC',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_SDC',
                                          'bittrex' => 'BTC-SDC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'shadowcoin'
                        
                    ),
                    'BLK' => array(
                        
                        'coin_name' => 'Blackcoin',
                        'coin_symbol' => 'BLK',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_BLK',
                                          'bittrex' => 'BTC-BLK'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'blackcoin'
                        
                    ),
                    'RDD' => array(
                        
                        'coin_name' => 'Reddcoin',
                        'coin_symbol' => 'RDD',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_RDD',
                                          'bittrex' => 'BTC-RDD'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'reddcoin'
                        
                    ),
                    'DOGE' => array(
                        
                        'coin_name' => 'Dogecoin',
                        'coin_symbol' => 'DOGE',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_DOGE',
                                          'bittrex' => 'BTC-DOGE'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'dogecoin'
                        
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
                    'LSK' => array(
                        
                        'coin_name' => 'Lisk',
                        'coin_symbol' => 'LSK',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_LSK',
                                          'bittrex' => 'BTC-LSK',
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
