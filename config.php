<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


session_start();

require_once("app.lib/functions.php");

require_once("app.lib/filters.php");

$version = '1.1.3';  // 2015/Sept/13th

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
                                          'cryptsy',
                                          'bittrex',
                                          'kraken'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_ETH',
                                          'cryptsy' => '497',
                                          'bittrex' => 'BTC-ETH',
                                          'kraken' => 'XETHXXBT'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'XRP' => array(
                        
                        'coin_name' => 'Ripple',
                        'coin_symbol' => 'XRP',
                        'markets' => array(
                                          'poloniex',
                                          'cryptsy',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_XRP',
                                          'cryptsy' => '454',
                                          'bittrex' => 'BTC-XRP'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'LTC' => array(
                        
                        'coin_name' => 'Litecoin',
                        'coin_symbol' => 'LTC',
                        'markets' => array(
                                          'poloniex',
                                          'cryptsy',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_LTC',
                                          'cryptsy' => '3',
                                          'bittrex' => 'BTC-LTC'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'PPC' => array(
                        
                        'coin_name' => 'Peercoin',
                        'coin_symbol' => 'PPC',
                        'markets' => array(
                                          'poloniex',
                                          'cryptsy',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_PPC',
                                          'cryptsy' => '28',
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
                                          'cryptsy',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_DASH',
                                          'cryptsy' => '155',
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
                                          'cryptsy',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_DGB',
                                          'cryptsy' => '167',
                                          'bittrex' => 'BTC-DGB'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'DOGE' => array(
                        
                        'coin_name' => 'Dogecoin',
                        'coin_symbol' => 'DOGE',
                        'markets' => array(
                                          'poloniex',
                                          'cryptsy',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_DOGE',
                                          'cryptsy' => '132',
                                          'bittrex' => 'BTC-DOGE'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'RDD' => array(
                        
                        'coin_name' => 'Reddcoin',
                        'coin_symbol' => 'RDD',
                        'markets' => array(
                                          'poloniex',
                                          'cryptsy',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_RDD',
                                          'cryptsy' => '169',
                                          'bittrex' => 'BTC-RDD'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'HYP' => array(
                        
                        'coin_name' => 'Hyperstake',
                        'coin_symbol' => 'HYP',
                        'markets' => array(
                                          'poloniex',
                                          'cryptsy',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_HYP',
                                          'cryptsy' => '445',
                                          'bittrex' => 'BTC-HYP'
                                          ),
                        'trade_pair' => 'btc'
                        
                    ),
                    'FTC' => array(
                        
                        'coin_name' => 'Feathercoin',
                        'coin_symbol' => 'FTC',
                        'markets' => array(
                                          'cryptsy'
                                          ),
                        'markets_ids' => array(
                                          'cryptsy' => '355'
                                          ),
                        'trade_pair' => 'xrp'
                        
                    ),
                    'TIPS' => array(
                        
                        'coin_name' => 'Fedoracoin',
                        'coin_symbol' => 'TIPS',
                        'markets' => array(
                                          'cryptsy'
                                          ),
                        'markets_ids' => array(
                                          'cryptsy' => '147'
                                          ),
                        'trade_pair' => 'ltc'
                        
                    )
                
                
);

?>
