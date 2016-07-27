<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


session_start();

require_once("app.lib/functions.php");

require_once("app.lib/filters.php");

$version = '1.4.3';  // 2016/JULY/26th

$btc_in_usd = 'coinbase'; // Default Bitcoin value in USD

$eth_subtokens_values = array(
                        // Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
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
                                          'MARKETPLACE3',
                                          'ethereum_subtokens'  // Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
                                          ),
                        'markets_ids' => array(
                                          'MARKETPLACE1' => 'MARKETNUMBERHERE',
                                          'MARKETPLACE2' => 'BTC_COINSYMBOLHERE',
                                          'MARKETPLACE3' => 'BTC-COINSYMBOLHERE',
                                          'ethereum_subtokens' => 'THEDAO' // Must be defined in $eth_subtokens_values at top of config.php
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
                        'coinmarketcap' => 'bitcoin'
                        
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
                                          'bittrex',
                                          'cryptofresh'
                                          ),
                        'markets_ids' => array(
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
                    'ETC' => array(
                        
                        'coin_name' => 'Ethereum Classic',
                        'coin_symbol' => 'ETC',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_ETC',
                                          'bittrex' => 'BTC-ETC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'ethereum-classic'
                        
                    ),
                    'STEEM' => array(
                        
                        'coin_name' => 'Steem',
                        'coin_symbol' => 'STEEM',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex',
                                          'cryptofresh'
                                          ),
                        'markets_ids' => array(
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
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_SBD',
                                          'bittrex' => 'BTC-SBD'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => ''
                        
                    ),
                    'LSK' => array(
                        
                        'coin_name' => 'Lisk',
                        'coin_symbol' => 'LSK',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_LSK',
                                          'bittrex' => 'BTC-LSK'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'lisk'
                        
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
                        'coinmarketcap' => 'litecoin'
                        
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
                        'coinmarketcap' => 'dash'
                        
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
                        'coinmarketcap' => 'peercoin'
                        
                    ),
                    'AMP' => array(
                        
                        'coin_name' => 'Synereo',
                        'coin_symbol' => 'AMP',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_AMP',
                                          'bittrex' => 'BTC-AMP'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'synereo'
                        
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
                        'coinmarketcap' => 'maidsafecoin'
                        
                    ),
                    'DAO' => array(
                        
                        'coin_name' => 'TheDAO',
                        'coin_symbol' => 'DAO',
                        'markets' => array(
                                          'poloniex',
                                          'kraken',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_DAO',
                                          'kraken' => 'XDAOXXBT',
                                          'bittrex' => 'BTC-DAO'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'the-dao'
                        
                    ),
                    'XMR' => array(
                        
                        'coin_name' => 'Monero',
                        'coin_symbol' => 'XMR',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_XMR',
                                          'bittrex' => 'BTC-XMR'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'monero'
                        
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
                        'coinmarketcap' => 'bitshares'
                        
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
                        'coinmarketcap' => 'ripple'
                        
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
                        'coinmarketcap' => 'stellar'
                        
                    ),
                    'EXP' => array(
                        
                        'coin_name' => 'Expanse',
                        'coin_symbol' => 'EXP',
                        'markets' => array(
                                          'poloniex',
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'poloniex' => 'BTC_EXP',
                                          'bittrex' => 'BTC-EXP'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'expanse'
                        
                    ),
                    'SHF' => array(
                        
                        'coin_name' => 'Shift',
                        'coin_symbol' => 'SHF',
                        'markets' => array(
                                          'bittrex'
                                          ),
                        'markets_ids' => array(
                                          'bittrex' => 'BTC-SHF'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'shift'
                        
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
                        'coinmarketcap' => 'reddcoin'
                        
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
                        'coinmarketcap' => 'dogecoin'
                        
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
                        'coinmarketcap' => 'nxt'
                        
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
                        'coinmarketcap' => 'digibyte'
                        
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
                        'coinmarketcap' => ''
                        
                    )
                
                
);

?>
