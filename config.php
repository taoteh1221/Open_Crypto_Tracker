<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


session_start();

require_once("app.lib/php/functions.php");

require_once("app.lib/php/cookies.php");

$version = '1.4.7 BETA';  // 2016/AUG/9th

$btc_in_usd = 'coinbase'; // Default Bitcoin value in USD

$eth_subtokens_values = array(
                        // Static values in ETH for Ethereum subtokens, like during crowdsale periods etc
                        'ETHSUBTOKENNAME' => 0.15 
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
                        
                    ),
                    'LBC' => array(
                        
                        'coin_name' => 'LBRY',
                        'coin_symbol' => 'LBC',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_LBC',
                                          'bittrex' => 'BTC-LBC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'library-credit'
                        
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
                    'EXP' => array(
                        
                        'coin_name' => 'Expanse',
                        'coin_symbol' => 'EXP',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_EXP',
                                          'bittrex' => 'BTC-EXP'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'expanse'
                        
                    ),
                    'ETC' => array(
                        
                        'coin_name' => 'Ethereum Classic',
                        'coin_symbol' => 'ETC',
                        'market_ids' => array(
                                          'poloniex' => 'BTC_ETC',
                                          'kraken' => 'XETCXXBT',
                                          'bitfinex' => 'etcbtc',
                                          'bittrex' => 'BTC-ETC'
                                          ),
                        'trade_pair' => 'btc',
                        'coinmarketcap' => 'ethereum-classic'
                        
                    )
                
                
);

?>
