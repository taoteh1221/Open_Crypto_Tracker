<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


session_start();

require_once("app.lib/functions.php");

require_once("app.lib/filters.php");

$version = '1.3.1';  // 2016/MAY/24th

$btc_in_usd = 'coinbase'; // Default Bitcoin value in USD

$eth_subtokens_values = array(
                        // Values in ETH for subtokens
                        'THEDAO' => 0.01
                        );

/*
 * USAGE (ADDING / UPDATING COINS) ...ONLY KRAKEN / GATECOIN / POLONIEX / COINBASE / BITTREX / bitfinex / cryptofresh / gemini BTC, altcoin, and token API SUPPORT AS OF NOW
 * Ethereum subtoken support has been built in, but values are static as no APIs exist yet
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
                        'trade_pair' => 'LOWERCASE_BTC_OR_LTC_OR_ETH_TRADING_PAIR',
                        'coinmarketcap' => 'yes' // Is this coin listed on coinmarketcap
                        
                    )
                    
                    
                    
 * 
 */
$coins_array = array(
                
                
                    'BTC' => array(
                        
                        'coin_name' => 'Bitcoin',
                        'coin_symbol' => 'BTC',
                        'markets' => array(
                                          'okcoin',
                                          'bitfinex',
                                          'kraken',
                                          'coinbase',
                                          'bitstamp',
                                          'gemini'
                                          ),
                        'markets_ids' => array(
                                          'okcoin' => 'USD',
                                          'bitfinex' => 'btcusd',
                                          'kraken' => 'XXBTZUSD',
                                          'coinbase' => 'USD',
                                          'bitstamp' => 'USD',
                                          'gemini' => 'btcusd'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
                    ),
                    'ETH' => array(
                        
                        'coin_name' => 'Ethereum',
                        'coin_symbol' => 'ETH',
                        'markets' => array(
                                          'poloniex',
                                          'kraken',
                                          'coinbase',
                                          'gatecoin',
                                          'bitfinex',
                                          'gemini',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_ETH',
                                          'kraken' => 'XETHXXBT',
                                          'coinbase' => 'ETH',
                                          'gatecoin' => 'ETHBTC',
                                          'bitfinex' => 'ethbtc',
                                          'gemini' => 'ethbtc',
                                          'bittrex' => 'BTC-ETH'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
                    ),
                    'DAO' => array(
                        
                        'coin_name' => 'TheDAO',
                        'coin_symbol' => 'DAO',
                        'markets' => array(
                                          'ethereum_subtokens'
                                          ),
                        'markets_ids' => array(
                                          'ethereum_subtokens' => 'THEDAO'
                                          ),
                        'trade_pair' => 'eth',
                        'coinmarketcap' => 'no'
                        
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
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
                    ),
                    'MAID' => array(
                        
                        'coin_name' => 'MaidSafecoin',
                        'coin_symbol' => 'MAID',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_MAID',
                                          'bittrex' => 'BTC-MAID'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
                    ),
                    'LTC' => array(
                        
                        'coin_name' => 'Litecoin',
                        'coin_symbol' => 'LTC',
                        'markets' => array(
                                          'bitfinex',
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'bitfinex' => 'ltcbtc',
                                          'poloniex' => 'BTC_LTC',
                                          'bittrex' => 'BTC-LTC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
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
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
                    ),
                    'XLM' => array(
                        
                        'coin_name' => 'Stellar',
                        'coin_symbol' => 'XLM',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_STR',
                                          'bittrex' => 'BTC-XLM'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
                    ),
                    'AMP' => array(
                        
                        'coin_name' => 'Synereo',
                        'coin_symbol' => 'AMP',
                        'markets' => array(
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'bittrex' => 'BTC-AMP'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
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
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
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
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
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
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
                    ),
                    'MKR' => array(
                        
                        'coin_name' => 'Makercoin',
                        'coin_symbol' => 'MKR',
                        'markets' => array(
                                          'cryptofresh'
                                          ),
                        'markets_ids' => array(
                                          'cryptofresh' => 'MKR'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'no'
                        
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
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
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
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
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
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
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
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
                    ),
                    'HYP' => array(
                        
                        'coin_name' => 'Hyperstake',
                        'coin_symbol' => 'HYP',
                        'markets' => array(
                                          'poloniex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_HYP'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'yes'
                        
                    )
                
                
);

?>
