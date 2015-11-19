
Just upload to your PHP-based web server, and you should be all set. See below for an example on adding your own markets to the coin list in config.php...

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