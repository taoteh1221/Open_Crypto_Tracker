<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


session_start();

require_once("app.lib/functions.php");

require_once("app.lib/filters.php");

$version = '1.1.8';  // 2016/JAN/18th

$btc_in_usd = 'coinbase'; // Get Bitcoin value in USD  ... ONLY COINBASE SUPPORTED AS OF NOW

/*
 * USAGE (ADDING / UPDATING COINS) ...ONLY CRYPTSY / POLONIEX / BITTREX API SUPPORT AS OF NOW
 *
 
 
                    'COIN_SYMBOL' => array(
                        
                        'coin_name' => 'COIN_NAME',
                        'coin_symbol' => 'COIN_SYMBOL',
                        'markets' => array(
                                          'MARKETPLACE1',
                                          'MARKETPLACE2',
                                          'MARKETPLACE3'
                                          ),
                        'markets_ids' => array(
                                          'MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'MARKETPLACE2' => 'BTC_COINSYMBOLHERE',
                                          'MARKETPLACE3' => 'BTC-COINSYMBOLHERE'
                                          ),
                        'trade_pair' => 'LOWERCASE_BTC_OR_LTC_TRADING_PAIR'
                        
                    )
                    
                    
                    
 * 
 */
$coins_array = array(
                
                
                    'BTC' => array(
                        
                        'coin_name' => 'Bitcoin',
                        'coin_symbol' => 'BTC',
                        'markets' => array(
                                          'coinbase'
                                          ),
                        'markets_ids' => array(
                                          'coinbase' => 'btc'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'ETH' => array(
                        
                        'coin_name' => 'Ethereum',
                        'coin_symbol' => 'ETH',
                        'markets' => array(
                                          'poloniex',
                                          'kraken',
                                          'gatecoin',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_ETH',
                                          'kraken' => 'XETHXXBT',
                                          'gatecoin' => 'ETHBTC',
                                          'bittrex' => 'BTC-ETH'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'XRP' => array(
                        
                        'coin_name' => 'Ripple',
                        'coin_symbol' => 'XRP',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_XRP',
                                          'bittrex' => 'BTC-XRP'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'LTC' => array(
                        
                        'coin_name' => 'Litecoin',
                        'coin_symbol' => 'LTC',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_LTC',
                                          'bittrex' => 'BTC-LTC'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'PPC' => array(
                        
                        'coin_name' => 'Peercoin',
                        'coin_symbol' => 'PPC',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_PPC',
                                          'bittrex' => 'BTC-PPC'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'STR' => array(
                        
                        'coin_name' => 'Stellar',
                        'coin_symbol' => 'STR',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_STR',
                                          'bittrex' => 'BTC-STR'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'DASH' => array(
                        
                        'coin_name' => 'Dash',
                        'coin_symbol' => 'DASH',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_DASH',
                                          'bittrex' => 'BTC-DASH'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'HYPER' => array(
                        
                        'coin_name' => 'Hyper',
                        'coin_symbol' => 'HYPER',
                        'markets' => array(
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'bittrex' => 'BTC-HYPER'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'DGB' => array(
                        
                        'coin_name' => 'Digibyte',
                        'coin_symbol' => 'DGB',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_DGB',
                                          'bittrex' => 'BTC-DGB'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'DOGE' => array(
                        
                        'coin_name' => 'Dogecoin',
                        'coin_symbol' => 'DOGE',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_DOGE',
                                          'bittrex' => 'BTC-DOGE'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'RDD' => array(
                        
                        'coin_name' => 'Reddcoin',
                        'coin_symbol' => 'RDD',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_RDD',
                                          'bittrex' => 'BTC-RDD'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'HYP' => array(
                        
                        'coin_name' => 'Hyperstake',
                        'coin_symbol' => 'HYP',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_HYP',
                                          'bittrex' => 'BTC-HYP'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'BTS' => array(
                        
                        'coin_name' => 'BitShares',
                        'coin_symbol' => 'BTS',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_BTS',
                                          'bittrex' => 'BTC-BTS'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'NXT' => array(
                        
                        'coin_name' => 'NXT',
                        'coin_symbol' => 'NXT',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_NXT',
                                          'bittrex' => 'BTC-NXT'
                                          ),
                        'trade_pair' => 'btc'
                        
                    )
                
                
);

?>
