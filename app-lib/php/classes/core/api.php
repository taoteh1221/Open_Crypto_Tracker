<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


class ct_api {
	
	
// Class variables / arrays
var $prefixing_blacklist = array(
                             'binance', // Because 'binance_us' is a separate API
                             'coingecko', // Because 'coingecko_terminal' is a separate API
                             'gateio', // Because 'gateio_usdt_futures' is a separate API
                             'okex', // Because 'okex_perps' is a separate API
                            );


// We need an architecture that 'registers' each exchange API's specs / params in the app,
// for scanning ALL exchanges for a specific NEW ticker, when ADDING A NEW COIN VIA ADMIN INTERFACING
// For any exchange with an "all_markets_support" API endpoint structure, we don't need to bother adding any
// "search_endpoint" API, since THE MARKET ENDPOINT GIVES US ALL MARKETS IN ONE CALL. 
var $exchange_apis = array(


                           'aevo' => array(
                                                   'markets_endpoint' => 'https://api.aevo.xyz/instrument/[MARKET]',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'alphavantage_stock' => array(
                                                   'markets_endpoint' => 'https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=[MARKET]&apikey=[ALPHAVANTAGE_KEY]',
                                                   'markets_nested_path' => 'Global Quote', // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => 'https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=[SEARCH_QUERY]&apikey=[ALPHAVANTAGE_KEY]', // false|[API endpoint with all market pairings]
                                                  ),


                           'binance' => array(
                                                   'markets_endpoint' => 'https://www.binance.com/api/v3/ticker/24hr',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'binance_us' => array(
                                                   'markets_endpoint' => 'https://api.binance.us/api/v3/ticker/24hr',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bit2c' => array(
                                                   'markets_endpoint' => 'https://bit2c.co.il/Exchanges/[MARKET]/Ticker.json',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitbns' => array(
                                                   'markets_endpoint' => 'https://bitbns.com/order/getTickerWithVolume',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitfinex' => array(
                                                   'markets_endpoint' => 'https://api-pub.bitfinex.com/v2/tickers?symbols=ALL',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => '0', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitflyer' => array(
                                                   'markets_endpoint' => 'https://api.bitflyer.com/v1/getticker?product_code=[MARKET]',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitmart' => array(
                                                   'markets_endpoint' => 'https://api-cloud.bitmart.com/spot/v1/ticker',
                                                   'markets_nested_path' => 'data>tickers', // Delimit multiple depths with >
                                                   'all_markets_support' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           // GET NEWEST DATA SETS (25 one hour buckets, SINCE WE #NEED# THE CURRENT PARTIAL DATA SET, 
                           // OTHERWISE WE DON'T GET THE LATEST TRADE VALUE AND CAN'T CALCULATE REAL-TIME VOLUME)
                           // Sort NEWEST first, 'all_markets_support' MUST BE FALSE,
                           // (as we need to CUSTOM parse 25 different 1-hour data sets, AFTER generic data retrieval)
                           'bitmex' => array(
                                                   'markets_endpoint' => 'https://www.bitmex.com/api/v1/trade/bucketed?binSize=1h&partial=true&count=25&symbol=[MARKET]&reverse=true',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitso' => array(
                                                   'markets_endpoint' => 'https://api.bitso.com/v3/ticker/?book=[MARKET]',
                                                   'markets_nested_path' => 'payload', // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitstamp' => array(
                                                   'markets_endpoint' => 'https://www.bitstamp.net/api/v2/ticker/[MARKET]',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'btcmarkets' => array(
                                                   'markets_endpoint' => 'https://api.btcmarkets.net/market/[MARKET]/tick',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'btcturk' => array(
                                                   'markets_endpoint' => 'https://api.btcturk.com/api/v2/ticker',
                                                   'markets_nested_path' => 'data', // Delimit multiple depths with >
                                                   'all_markets_support' => 'pair', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'buyucoin' => array(
                                                   'markets_endpoint' => 'https://api.buyucoin.com/ticker/v1.0/liveData',
                                                   'markets_nested_path' => 'data', // Delimit multiple depths with >
                                                   'all_markets_support' => 'marketName', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bybit' => array(
                                                   'markets_endpoint' => 'https://api-testnet.bybit.com/v2/public/tickers',
                                                   'markets_nested_path' => 'result', // Delimit multiple depths with >
                                                   'all_markets_support' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'cex' => array(
                                                   'markets_endpoint' => 'https://cex.io/api/tickers/BTC/USD/USDT/EUR/GBP',
                                                   'markets_nested_path' => 'data', // Delimit multiple depths with >
                                                   'all_markets_support' => 'pair', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'coinbase' => array(
                                                   'markets_endpoint' => 'https://api.exchange.coinbase.com/products/[MARKET]/ticker',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => 'https://api.exchange.coinbase.com/products', // false|[API endpoint with all market pairings]
                                                  ),


                           'coindcx' => array(
                                                   'markets_endpoint' => 'https://public.coindcx.com/exchange/ticker',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'market', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'coinex' => array(
                                                   'markets_endpoint' => 'https://api.coinex.com/v1/market/ticker/all',
                                                   'markets_nested_path' => 'data>ticker', // Delimit multiple depths with >
                                                   'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),

                           
                           // 'all_markets_support' MUST BE FALSE, as we have to CUSTOM parse through funky data structuring 
                           'coingecko' => array(
                                                   'markets_endpoint' => 'https://api.coingecko.com/api/v3/simple/price?ids=[COINGECKO_ASSETS]&vs_currencies=[COINGECKO_PAIRS]&include_24hr_vol=true',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => 'https://api.coingecko.com/api/v3/search?query=[SEARCH_QUERY]', // false|[API endpoint with all market pairings]
                                                  ),
                                                  
                                                  
                           'coingecko_terminal' => array(
                                                   'markets_endpoint' => 'https://api.geckoterminal.com/api/v2/networks/[MARKET]?include=base_token,quote_token',
                                                   'markets_nested_path' => 'data', // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'coinspot' => array(
                                                   'markets_endpoint' => 'https://www.coinspot.com.au/pubapi/latest',
                                                   'markets_nested_path' => 'prices', // Delimit multiple depths with >
                                                   'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'crypto.com' => array(
                                                   'markets_endpoint' => 'https://api.crypto.com/v2/public/get-ticker',
                                                   'markets_nested_path' => 'result>data', // Delimit multiple depths with >
                                                   'all_markets_support' => 'i', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'gateio' => array(
                                                   'markets_endpoint' => 'https://api.gateio.ws/api/v4/spot/tickers',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'currency_pair', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'gateio_usdt_futures' => array(
                                                   'markets_endpoint' => 'https://api.gateio.ws/api/v4/futures/usdt/contracts',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'name', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'gemini' => array(
                                                   'markets_endpoint' => 'https://api.gemini.com/v1/pubticker/[MARKET]',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'hitbtc' => array(
                                                   'markets_endpoint' => 'https://api.hitbtc.com/api/2/public/ticker',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'huobi' => array(
                                                   'markets_endpoint' => 'https://api.huobi.pro/market/tickers',
                                                   'markets_nested_path' => 'data', // Delimit multiple depths with >
                                                   'all_markets_support' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'kuma' => array(
                                                   'markets_endpoint' => 'https://api.kuma.bid/v1/tickers',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'market', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),

                           
                           'jupiter_ag' => array(
                                                  // EVEN THOUGH V3 DOES ***NOT*** SUPPORT THE 'vsToken' PARAM, 
                                                  // WE ***EMULATE IT*** IN OUR LOGIC (FOR PAIRING UX), AND STRIP 'vsToken' BEFORE THE DATA REQUEST
                                                  'markets_endpoint' => 'https://lite-api.jup.ag/price/v3?ids=[JUP_AG_ASSETS]&vsToken=[JUP_AG_PAIRING]',
                                                  'markets_nested_path' => false, // Delimit multiple depths with >
                                                  'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                  'search_endpoint' => 'https://lite-api.jup.ag/tokens/v2/tag?query=[JUP_AG_TAG]', // false|[API endpoint with all market pairings]
                                                  ),


                           'korbit' => array(
                                                   'markets_endpoint' => 'https://api.korbit.co.kr/v1/ticker/detailed/all',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),

                           
                           'kraken' => array(
                                                   'markets_endpoint' => 'https://api.kraken.com/0/public/Ticker',
                                                   'markets_nested_path' => 'result', // Delimit multiple depths with >
                                                   'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'kucoin' => array(
                                                   'markets_endpoint' => 'https://api.kucoin.com/api/v1/market/allTickers',
                                                   'markets_nested_path' => 'data>ticker', // Delimit multiple depths with >
                                                   'all_markets_support' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           // 'markets_nested_path' MUST BE FALSE, as it varies dynamically (we set it dynamically later on in logic)
                           'loopring' => array(
                                                   'markets_endpoint' => 'https://api3.loopring.io/api/v3/allTickers',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'luno' => array(
                                                   'markets_endpoint' => 'https://api.mybitx.com/api/1/tickers',
                                                   'markets_nested_path' => 'tickers', // Delimit multiple depths with >
                                                   'all_markets_support' => 'pair', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'okcoin' => array(
                                                   'markets_endpoint' => 'https://www.okcoin.com/api/v5/market/tickers?instType=SPOT',
                                                   'markets_nested_path' => 'data', // Delimit multiple depths with >
                                                   'all_markets_support' => 'instId', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'okex' => array(
                                                   'markets_endpoint' => 'https://www.okx.com/api/v5/market/tickers?instType=SPOT',
                                                   'markets_nested_path' => 'data', // Delimit multiple depths with >
                                                   'all_markets_support' => 'instId', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'okex_perps' => array(
                                                   'markets_endpoint' => 'https://www.okx.com/api/v5/market/tickers?instType=SWAP',
                                                   'markets_nested_path' => 'data', // Delimit multiple depths with >
                                                   'all_markets_support' => 'instId', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'poloniex' => array(
                                                   'markets_endpoint' => 'https://api.poloniex.com/markets/ticker24h',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           // 'all_markets_support' MUST BE FALSE, as we have to CUSTOM parse through funky data structuring 
                           'tradeogre' => array(
                                                   'markets_endpoint' => 'https://tradeogre.com/api/v1/markets',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'unocoin' => array(
                                                   'markets_endpoint' => 'https://api.unocoin.com/api/trades/in/all/all',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'upbit' => array(
                                                   'markets_endpoint' => 'https://api.upbit.com/v1/ticker?markets=[UPBIT_BATCHED_MARKETS]',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'market', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'wazirx' => array(
                                                   'markets_endpoint' => 'https://api.wazirx.com/api/v2/tickers',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'zebpay' => array(
                                                   'markets_endpoint' => 'https://www.zebapi.com/pro/v1/market',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'pair', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),
                                                  
                                                  
                           );

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function dev_status() {
    
   global $ct;
         
   $url = 'https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/.dev-status.json';
         
   $response = @$ct['cache']->ext_data('url', $url, 90); // 90 minute cache
   
   $data = json_decode($response, true);
   
   return ( is_array($data) ? $data : array() ); // Parsing failsafe
     
   }
   

   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function coingecko_currencies() {
    
   global $ct;
         
   $url = 'https://api.coingecko.com/api/v3/simple/supported_vs_currencies';
         
   $response = @$ct['cache']->ext_data('url', $url, 1440); // Check DAILY
       
   $data = json_decode($response, true);
   
   return ( is_array($data) ? $data : array() ); // Parsing failsafe
     
   }
   
         
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function exchange_api_data($selected_exchange, $market_id, $ticker_search_mode=false) {
   
   global $ct;
   
   $selected_exchange = strtolower($selected_exchange);
   
      // IF exchange API exists
      if ( isset($this->exchange_apis[$selected_exchange]) ) {
           
           if ( $selected_exchange == 'jupiter_ag' ) {
           $return_all_results = true;
           }
           else {
           $return_all_results = false;
           }

      return $this->fetch_exchange_data($selected_exchange, $market_id, $ticker_search_mode, $return_all_results);
      
      }
      // IF exchange API doesn't exist, check to see if we are using our prefix delimiter, for a possible 'prefixed' exchange name
      // (for end-user descriptiveness / UX, BUT ONLY IF NOT A BLACKLISTED PREFIX!)
      elseif ( !in_array($selected_exchange, $this->prefixing_blacklist) && stristr($selected_exchange, '_') ) {
        
           foreach ( $this->exchange_apis as $exchange_key => $unused ) {
           
           $exchange_key = strtolower($exchange_key);
               
               // AUTO-CHECK FOR PREFIX USAGE: EXCHANGEKEY_
               if ( stristr($selected_exchange, $exchange_key . '_') ) {
               return $this->fetch_exchange_data($exchange_key, $market_id, $ticker_search_mode);
               break; // will assure leaving the foreach loop immediately
               }
           
           }
      
      }
      else {
      return false;
      }
   
   }
   

   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function stock_overview($ticker) {
    
   global $ct;
   
   $results = array();
   
   $secondary_cache = $ct['base_dir'] . '/cache/assets/stocks/overviews/'.$ticker.'.dat';
   
        
        // Check any secondary cache data (from previous data request)
        if ( file_exists($secondary_cache) ) {
        $secondary_cache_info = json_decode( trim( file_get_contents($secondary_cache) ) , true);
        }

        
        // IF we had an API request ERROR, LAST TIME we requested data for this stock asset
        if (
        isset($secondary_cache_info['request_error'])
        && trim($secondary_cache_info['request_error']) != ''
        ) {
                  
                  // IF no data is available for this stock asset, update the secondary cache modified
                  // timestamp, AND set ext_data() primary cache time to 100 YEARS (52560000 minutes)
                  // (so we NEVER bother to refresh again, as they have no data for this asset...and
                  // running touch() lets us do a 30-day maintenance cleanup on stale cache files [deleted assets])
                  if ( $secondary_cache_info['request_error'] == 'no_data_available' ) {
                  touch($secondary_cache);
                  $overview_cache_time = 52560000; 
                  }
                  // If data MAY be available, BUT we hit an API limit
                  // OR got no server response LAST CHECK, we want to
                  // only check again after 4 to 8 hours     
                  else {
                  $overview_cache_time = rand(4, 8) * 60; 
                  }
                  
        }
        // IF we do NOT have a PREMIUM PLAN, SPREAD UPDATES OVER 1 / 2 WEEKS
        elseif ( $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 ) {
        $overview_cache_time = rand(7, 14) * 1440;
        }
        // 1 DAY FOR ANY PREMIUM PLAN
        else {
        $overview_cache_time = 1440; 
        }
   
   
        // WE SAVE TO A SECONDARY CACHE, AS WE MAY STORE IT A LONG TIME,
        // IF WE ARE USING THE FREE API TIER, OR NO OVERVIEW DATA IS AVAILABLE
        if ( $ct['cache']->update_cache($secondary_cache, $overview_cache_time) == true ) {
         
        $url = 'https://www.alphavantage.co/query?function=OVERVIEW&symbol='.$ticker.'&apikey=' . $ct['conf']['ext_apis']['alphavantage_api_key'];
              
        $response = @$ct['cache']->ext_data('url', $url, $overview_cache_time);
        
        $data = json_decode($response, true);
            
            
            // Store error status, if no valid data detected
            if ( !isset($data['Symbol']) ) {
                 
                 if ( isset($data['Information']) ) {
                 $response = '{ "request_error": "api_limit" }';
                 }
                 elseif ( preg_match("/\{\}/i", $response) ) {
                 $response = '{ "request_error": "no_data_available" }';
                 }
                 else {
                 $response = '{ "request_error": "no_response" }';
                 }

            $data = json_decode($response, true);

            }
        
        
        $ct['cache']->save_file($secondary_cache, $response);
        
        }
        else {
        $data = json_decode( trim( file_get_contents($secondary_cache) ) , true);
        }
        
   
   $results['cache_timestamp'] = filemtime($secondary_cache);
   
   $results['data'] = $data;
   
   return $results;     
     
   }
		
		
   ////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
		
   
   function blockchain_rpc($network, $method, $params=false, $cache_time=0, $rpc_test='') {
		 
   global $ct;
   
   $rpc_test = trim($rpc_test);

        
        // RCP test
        if ( $rpc_test != '' ) {
        $rpc_server = $rpc_test;
        }
        // Bitcoin RCP server address in admin config
        // https://developer.bitcoin.org/reference/rpc
        elseif ( $network == 'bitcoin' ) {    
        $rpc_server = trim($ct['conf']['ext_apis']['bitcoin_rpc_server']);
        }
        // Solana RCP server address in admin config
        // https://solana.com/docs/rpc/http
        elseif ( $network == 'solana' ) {    
        $rpc_server = trim($ct['conf']['ext_apis']['solana_rpc_server']);
        }

	
   $headers = array(
                    'Content-Type: application/json'
                    );
               
   $request_params = array(
                           'jsonrpc' => '2.0', // Setting this right before sending
                           'id' => 1,
                           'method' => $method,
                          );
                    
                    
        if ( is_array($params) && sizeof($params) > 0 ) {
        $request_params['params'] = $params;
        }

     
   $response = @$ct['cache']->ext_data('params', $request_params, $cache_time, $rpc_server, 3, null, $headers);
			 
   return json_decode($response, true);
		
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function telegram($mode) {
      
   global $ct;
   
      if ( $mode == 'updates' ) {
      
      // Don't cache data, we are storing it as a specific (secured) cache var instead
      $response = @$ct['cache']->ext_data('url', 'https://api.telegram.org/bot'.$ct['conf']['ext_apis']['telegram_bot_token'].'/getUpdates', 0);
         
      $telegram_chatroom = json_decode($response, true);
   
      $telegram_chatroom = $telegram_chatroom['result']; 
   
          foreach( $telegram_chatroom as $chat_key => $chat_unused ) {
      
              // Overwrites any earlier value while looping, so we have the latest data
              if ( $telegram_chatroom[$chat_key]['message']['chat']['username'] == trim($ct['conf']['ext_apis']['telegram_your_username']) ) {
              $user_data = $telegram_chatroom[$chat_key];
              }
      
          }
   
      return $user_data;
      
      }
      elseif ( $mode == 'webhook' ) {
         
      // Don't cache data, we are storing it as a specific (secured) cache var instead
      $get_telegram_webhook_data = @$ct['cache']->ext_data('url', 'https://api.telegram.org/bot'.$ct['conf']['ext_apis']['telegram_bot_token'].'/getWebhookInfo', 0);
         
      $telegram_webhook = json_decode($get_telegram_webhook_data, true);
      
      // logic here
      
      }
      
      
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function etherscan($block_info) {
    
   global $ct;
   
   $url = 'https://api.etherscan.io/api?module=proxy&action=eth_blockNumber&apikey=' . $ct['conf']['ext_apis']['etherscan_api_key'];
   
   // 5 minute cache
   $response = @$ct['cache']->ext_data('url', $url, 5);
       
   $data = json_decode($response, true);
     
   $block_number = $data['result'];
       
       
      if ( !$block_number ) {
      return;
      }
      else {
            
          // Non-dynamic cache file name, because filename would change every recache and create cache bloat
          if ( $ct['cache']->update_cache('cache/secured/external_data/eth-stats.dat', 5) == true ) {
            
          $url = 'https://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag='.$block_number.'&boolean=true&apikey=' . $ct['conf']['ext_apis']['etherscan_api_key'];
          $response = @$ct['cache']->ext_data('url', $url, 0); // ZERO TO NOT CACHE DATA (WOULD CREATE CACHE BLOAT)
            
          $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/external_data/eth-stats.dat', $response);
            
          $data = json_decode($response, true);
            
          return $data['result'][$block_info];
            
          }
          else {
               
          $cached_data = trim( file_get_contents('cache/secured/external_data/eth-stats.dat') );
            
          $data = json_decode($cached_data, true);
            
          return $data['result'][$block_info];
   
          }
     
      }

   
   gc_collect_cycles(); // Clean memory cache
     
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function google_fonts($request) {
    
   global $ct;
   
   
      // API key check
      if ( isset($ct['conf']['ext_apis']['google_fonts_api_key']) && $ct['conf']['ext_apis']['google_fonts_api_key'] != '' ) {
      // CONTINUE
      }
      else {
      return false;
      }
      
   
   $result = array();
         
       
      if ( $request == 'list' ) {
      $url = 'https://webfonts.googleapis.com/v1/webfonts?key=' . $ct['conf']['ext_apis']['google_fonts_api_key'];
      }
      
         
   $response = @$ct['cache']->ext_data('url', $url, ($ct['conf']['ext_apis']['google_fonts_cache_time'] * 60)  );
       
   $data = json_decode($response, true);
   
   $data = $data['items'];
   
   
      if ( is_array($data) ) {
      
          foreach( $data as $val ) {
          
              if ( isset($val['family']) ) {
              
              // We don't want the word 'script' triggering a false positive result,
              // when we scan for possible script injection attacks in user input
              // (which could confuse / scare end users, IF they choose a font with 'script' in the name)
              $scan = strtolower($val['family']);
              $scan = str_replace($ct['dev']['script_injection_checks'], "", $scan, $has_script);
              
                 if ( $has_script == 0 ) {
                 $result[] = $val['family'];
                 }
              
              }
          
          }
      
      sort($result);
      
      }
      
   
   gc_collect_cycles(); // Clean memory cache
      	           
   return $result;
     
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function mcap_data_coingecko($force_prim_currency=null) {
      
   global $ct;
   
   
      // IF we were creating any new directory structure during this runtime,
      // skip getting marketcap data, as we PREFER price data
      // (in case we are throttled by the API, for heavy request loads, when rebuilding the API cache, etc)
      if ( $ct['dir_creation'] ) {
      return array(); // Parsing failsafe
      }
   
   
   $data = array();
   $sub_arrays = array();
   $result = array();
   
   // Don't overwrite global
   $coingecko_prim_currency = ( $force_prim_currency != null ? strtolower($force_prim_currency) : strtolower($ct['conf']['currency']['bitcoin_primary_currency_pair']) );
   
         
   // DON'T ADD ANY ERROR CHECKS HERE, OR RUNTIME MAY SLOW SIGNIFICANTLY!!
   
   
      // Convert NATIVE tickers to INTERNATIONAL for coingecko
      if ( $coingecko_prim_currency == 'nis' ) {
      $coingecko_prim_currency = 'ils';
      }
      elseif ( $coingecko_prim_currency == 'rmb' ) {
      $coingecko_prim_currency = 'cny';
      }
      
   
      // Batched / multiple API calls, if 'marketcap_ranks_max' is greater than 'coingecko_api_batched_maximum'
      if ( $ct['conf']['power']['marketcap_ranks_max'] > $ct['conf']['ext_apis']['coingecko_api_batched_maximum'] ) {
          
          // FAILSAFE (< V6.00.29 UPGRADES, IF UPGRADE MECHANISM FAILS FOR WHATEVER REASON)
          $batched_max = ( $ct['conf']['ext_apis']['coingecko_api_batched_maximum'] > 0 ? $ct['conf']['ext_apis']['coingecko_api_batched_maximum'] : 100 );
      
          $loop = 0;
          $calls = ceil($ct['conf']['power']['marketcap_ranks_max'] / $batched_max);
         
          while ( $loop < $calls ) {
         
          $url = 'https://api.coingecko.com/api/v3/coins/markets?per_page=' . $batched_max . '&page=' . ($loop + 1) . '&vs_currency=' . $coingecko_prim_currency . '&price_change_percentage=1h,24h,7d,14d,30d,200d,1y';
         
          $response = @$ct['cache']->ext_data('url', $url, $ct['conf']['power']['marketcap_cache_time']);
   
          $sub_arrays[] = json_decode($response, true);
         
          $loop = $loop + 1;
         
          }
      
      }
      else {
      	
      $response = @$ct['cache']->ext_data('url', 'https://api.coingecko.com/api/v3/coins/markets?per_page='.$ct['conf']['power']['marketcap_ranks_max'].'&page=1&vs_currency='.$coingecko_prim_currency.'&price_change_percentage=1h,24h,7d,14d,30d,200d,1y', $ct['conf']['power']['marketcap_cache_time']);
      
      $sub_arrays[] = json_decode($response, true);
      
      }
         
         
   // DON'T ADD ANY ERROR CHECKS HERE, OR RUNTIME MAY SLOW SIGNIFICANTLY!!
   
      
      // Merge any sub arrays into one data set
      foreach ( $sub_arrays as $sub ) {
          if ( is_array($sub) ) {
          $data = array_merge($data, $sub);
          }
      }
      
   
      if ( is_array($data) ) {
         
          foreach ($data as $key => $unused) {
            
              if ( isset($data[$key]['id']) && $data[$key]['id'] != '' ) {
              $result[ strtolower($data[$key]['id']) ] = $data[$key];
              }
       
          }
        
      }
           
           
   gc_collect_cycles(); // Clean memory cache
   
   return ( is_array($result) ? $result : array() ); // Parsing failsafe
     
   }
   

   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   

   function jup_address($ticker, $verified_only=true) {
        
   global $ct;
   
   // Trim whitespace
   $ticker = trim($ticker);
   
   
           // RUNTIME CACHE (TO SPEED THINGS UP)
           if ( isset($ct['jup_ag_runtime_cache'][$ticker]['id']) ) {
           return $ct['jup_ag_runtime_cache'][$ticker]['id'];
           }

   
   $results = array();
           
           
           // Filters
           if ( $verified_only ) {
           $tags = 'verified';
           }
           // WE ALLOW FILTERING BY TAG, FOR SAFETY
           // https://dev.jup.ag/docs/api/token-api/tagged
           elseif ( isset($_POST['jupiter_tag']) && trim($_POST['jupiter_tag']) != '' ) {
           $tags = trim($_POST['jupiter_tag']);
           }
           // Otherwise, allow verified tokens
           else {
           $tags = 'verified';
           }
      
      
      // 3 hour cache for VERIFIED token search, 1 hour for everything else
      $cache_time = ( $verified_only ? 180 : 60 );
      
      $response = @$ct['cache']->ext_data('url', 'https://lite-api.jup.ag/tokens/v2/tag?query=' . $tags, $cache_time);
      
      gc_collect_cycles(); // Clean memory cache
   
      $data = json_decode($response, true);
      
           
           foreach ( $data as $val ) {
          
               if ( isset($val['symbol']) && $val['symbol'] == $ticker ) {
               $results[] = $val;
               }

           }
          
          
           if ( $verified_only && sizeof($results) > 1 ) {
          
          $ct['gen']->log(
          				'market_error',
          				'address search for VERIFIED asset "' . $ticker . '" returned MORE THAN 1 RESULT (VERIFY address "'.$results[0]['id'].'" is correct [in "Market ID" field, of exchange results])'
          				);
          				          
           }
           elseif ( sizeof($results) < 1 ) {
          
           $ct['gen']->log(
          				'market_error',
          				'jupiter ag. address search for asset "' . $ticker . '" returned NO RESULT (filters: ' . $tags . ')'
          				);
          				          
           }
          
   
           if ( isset($results[0]) ) {
                 
           // RUNTIME CACHE (TO SPEED THINGS UP)
           
           $ct['jup_ag_runtime_cache'][$ticker] = $results[0];
           
           $ct['jup_ag_address_mapping'][ $ct['jup_ag_runtime_cache'][$ticker]['id'] ] = $ticker;
            
           return $ct['jup_ag_runtime_cache'][$ticker]['id'];
     
           }
           else {
           return false;
           }
 
 
   }
   

   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function coingecko_search($search_query, $app_id, $specific_pairing=false, $asset_data=false) {
   
   global $ct;
               
   $market_data = $this->fetch_exchange_data('coingecko', $app_id);
                                                  
                                                 
        if ( $specific_pairing ) {
        $check_pairing = $specific_pairing;
        }
        else {
        $check_pairing = strtolower('usd');
        }
                                       
                                       
        // Coingecko needs INTERNATIONAL versions of pairings
        if ( $check_pairing == 'nis' ) {
        $check_pairing = 'ils';
        }
        elseif ( $check_pairing == 'rmb' ) {
        $check_pairing = 'cny';
        }

                                  
        if ( isset($market_data[$check_pairing]) ) {
           
                                                  
             // Minimize calls
             if ( is_array($asset_data) ) {
             $coingecko_asset_data = $asset_data;
             }
             else {
             $coingecko_asset_data = $this->exchange_search_endpoint('coingecko', $app_id, false, true); // Get asset data only
             }
                       
                       
             if ( isset($coingecko_asset_data['name']) ) {
             $cg_name = $coingecko_asset_data['name'];
             }
             elseif ( isset($coingecko_asset_data['symbol']) ) {
             $cg_name = strtoupper($coingecko_asset_data['symbol']);
             }
             
             
             if ( $specific_pairing ) {
                                
             $parsed_market_data = array(
     	                        'last_trade' => $ct['var']->num_to_str($market_data[$specific_pairing]),
     	                        '24hr_pair_vol' => $ct['var']->num_to_str($market_data[$specific_pairing . "_24h_vol"])
     	                        );
             
             // We still need to parse out 'flagged_market'
             // Minimize calls
             $market_tickers_parse = $ct['asset']->market_tickers_parse('coingecko', $app_id, $specific_pairing, $coingecko_asset_data['symbol']);
                                                
             $results[] = array(
                                                                           'name' => $cg_name,
                                                                           'mcap_slug' =>  $app_id,
                                                                           'id' =>  $app_id,
                                                                           'asset' => $market_tickers_parse['asset'],
                                                                           'pairing' => $market_tickers_parse['pairing'],
                                                                           'flagged_market' => $market_tickers_parse['flagged_market'],
                                                                           'data' => $parsed_market_data,
                                                                          );
             }
             else {
                          
             $coingecko_pairings_search_array = array_map( "trim", explode(",", $ct['coingecko_pairs']) );
   
      
                  foreach( $coingecko_pairings_search_array as $pair ) {
                                
                  $parsed_market_data = array(
     	                        'last_trade' => $ct['var']->num_to_str($market_data[$pair]),
     	                        '24hr_pair_vol' => $ct['var']->num_to_str($market_data[$pair . "_24h_vol"])
     	                        );
                                                  
                  // We still need to parse out 'flagged_market'
                  // Minimize calls
                  $market_tickers_parse = $ct['asset']->market_tickers_parse('coingecko', $app_id, $pair, $coingecko_asset_data['symbol']);
                                                
                  $results[] = array(
                                                                           'name' => $cg_name,
                                                                           'mcap_slug' =>  $app_id,
                                                                           'id' =>  $app_id,
                                                                           'asset' => $market_tickers_parse['asset'],
                                                                           'pairing' => $market_tickers_parse['pairing'],
                                                                           'flagged_market' => $market_tickers_parse['flagged_market'],
                                                                           'data' => $parsed_market_data,
                                                                          );
          
                  }
             
             
             }                               
                      
                                               
        }
        
   
   gc_collect_cycles(); // Clean memory cache
    
   return $results;
                   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function mcap_data_coinmarketcap($force_prim_currency=null) {
      
   global $ct;
   
   
      // IF we were creating any new directory structure during this runtime,
      // skip getting marketcap data, as we PREFER price data
      // (in case we are throttled by the API, for heavy request loads, when rebuilding the API cache, etc)
      if ( $ct['dir_creation'] ) {
      return array(); // Parsing failsafe
      }
   
   
   
   $result = array();
   
   $coinmarketcap_currencies = array();
   
   
      if ( trim($ct['conf']['ext_apis']['coinmarketcap_api_key']) == null ) {
      	
      $ct['gen']->log(
      		    'notify_error',
      		    '"coinmarketcap_api_key" (free API key) is not configured in Admin Config EXTERNAL APIS section',
      		    false,
      		    'coinmarketcap_api_key'
      		    );
      
      return array(); // Parser failsafe
      
      }
         
      
   $headers = [
               'Accepts: application/json',
               'X-CMC_PRO_API_KEY: ' . $ct['conf']['ext_apis']['coinmarketcap_api_key']
      	      ];
   
      
   $cmc_params = array(
                       'start' => '1',
                       'limit' => 200
                       );
   
   
   $url = 'https://pro-api.coinmarketcap.com/v1/fiat/map';
         
   $qs = http_build_query($cmc_params); // query string encode the parameters
      
   $request = "{$url}?{$qs}"; // create the request URL
   
   // Cache fiat currency support list for a day (1440 minutes)
   $response = @$ct['cache']->ext_data('url', $request, 1440, null, null, null, $headers);
      
   $data = json_decode($response, true);
           
   $data = $data['data'];
   
   
      if ( is_array($data) ) {
      
          foreach ( $data as $currency ) {
          $coinmarketcap_currencies[] = strtoupper($currency['symbol']);
          }
      
      }
      
   
   // Don't overwrite global
   $coinmarketcap_prim_currency = strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']);
      
      
      // Convert NATIVE tickers to INTERNATIONAL for coinmarketcap
      if ( $coinmarketcap_prim_currency == 'NIS' ) {
      $coinmarketcap_prim_currency = 'ILS';
      }
      elseif ( $coinmarketcap_prim_currency == 'RMB' ) {
      $coinmarketcap_prim_currency = 'CNY';
      }
      
      
      if ( $force_prim_currency != null ) {
      $convert = strtoupper($force_prim_currency);
      $ct['mcap_data_force_usd'] = null;
      }
      elseif ( in_array($coinmarketcap_prim_currency, $coinmarketcap_currencies) ) {
      $convert = $coinmarketcap_prim_currency;
      $ct['mcap_data_force_usd'] = null;
      }
      // Default to USD, if currency is not supported
      else {
      $ct['cmc_notes'] = 'Coinmarketcap.com does not support '.$coinmarketcap_prim_currency.' stats,<br />showing USD stats instead.';
      $convert = 'USD';
      $ct['mcap_data_force_usd'] = 1;
      }
   
      
   $cmc_params = array(
                       'start' => '1',
                       'limit' => $ct['conf']['power']['marketcap_ranks_max'],
                       'convert' => $convert
                       );
   
   
   $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
         
   $qs = http_build_query($cmc_params); // query string encode the parameters
      
   $request = "{$url}?{$qs}"; // create the request URL
   
   $response = @$ct['cache']->ext_data('url', $request, $ct['conf']['power']['marketcap_cache_time'], null, null, null, $headers);
      
   $data = json_decode($response, true);
           
   $data = $data['data'];
              
   
      if ( is_array($data) ) {
         
          foreach ($data as $key => $unused) {
            
              if ( isset($data[$key]['symbol']) && $data[$key]['symbol'] != '' ) {
              $result[strtolower($data[$key]['symbol'])] = $data[$key];
              }
          
          }
      
      }
   
        
   gc_collect_cycles(); // Clean memory cache
   
   return ( is_array($result) ? $result : array() ); // Parsing failsafe
           
   }
   

   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function ticker_markets_search($ticker_search, $specific_exchange=false) {
    
   global $ct;
   
   $results = array();
   
   
       // Remove 'STOCK' from search (if end user types in this app's stock-flagging TICKER FORMATTING)
       if ( stristr($ticker_search, 'STOCK') ) {
       $ticker_search = preg_replace("/STOCK/i", "", $ticker_search);
       }
       
   
   $ticker_search = trim($ticker_search); // TRIM ANY USER INPUT WHITESPACE
       
       
       // If no data
       if ( $ticker_search == '' ) {
       return array();
       }
       elseif ( stristr($ticker_search, '/') ) {
       $pairing_parse = array_map( "trim", explode('/', $ticker_search) ); // TRIM ANY WHITESPACE
       $ticker_only = $pairing_parse[0];
       $included_pairing = $pairing_parse[1];
       }
       else {
       $ticker_only = $ticker_search;
       $included_pairing = false; // We need a set boolean val for processing further down below
       }
       
       
       // Include any added token presale markets (separate from other market logic)
       if ( !$specific_exchange || $specific_exchange && $specific_exchange == 'presale_usd_value' ) {
       
           foreach( $ct['opt_conf']['token_presales_usd'] as $key => $val ) {
           
               if ( 
               !$included_pairing && $ct['gen']->search_mode($key, $ticker_search) 
               || $included_pairing && strtolower($included_pairing) == 'usd' && $ct['gen']->search_mode($key, $ticker_only) 
               ) {
               
               // Minimize calls
               $check_market_data = $this->market($key, 'presale_usd_value', $key);
               
                    if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                         
                    // Minimize calls
                    $market_tickers_parse  = $ct['asset']->market_tickers_parse('presale_usd_value', $key, 'usd', $key);
               
                    $results['presale_usd_value'][] = array(
                                                            'name' => strtoupper($key),
                                                            'id' => $key,
                                                            'asset' => $key,
                                                            'pairing' => 'usd',
                                                            'flagged_market' => $market_tickers_parse['flagged_market'],
                                                            'data' => $check_market_data,
                                                            );
                    
                         if ( $specific_exchange ) {
                         gc_collect_cycles(); // Clean memory cache
                         return $results;
                         }
                    
                    }
                    
               }
           
           }
       
       }
       
       
       // If specific exchange / specific id
       if ( $specific_exchange ) {
            
       $ticker_search = $ct['gen']->auto_correct_market_id($ticker_search, $specific_exchange);
            
       $ticker_only = $ct['gen']->auto_correct_market_id($ticker_only, $specific_exchange);
            
            
            // ONLY PROCESS IF NOT BOOLEAN!
            if ( is_bool($included_pairing) !== true ) {
            $included_pairing = $ct['gen']->auto_correct_market_id($included_pairing, $specific_exchange);
            }
          
       
       // Defaults
       $exchange_check = $specific_exchange;
       $ticker_check = $ticker_search;
       
       // Parsing tickers for this market (before any id conversions)
       $ticker_parsing = $ticker_check;
          
          
          // Exchange-specific
          if ( $specific_exchange == 'coingecko' ) {
               
          $exchange_check = $specific_exchange . '_usd';

          $ticker_check = $ticker_only;

          // Parsing tickers for this market (before any id conversions)
          $ticker_parsing = $ticker_check;

          }
          elseif ( $specific_exchange == 'jupiter_ag' ) {
               
               if ( $included_pairing ) {
               $ticker_check = $ticker_search;
               }
               else {
               $ticker_check = $ticker_search . '/' . ( $ticker_search == 'SOL' ? 'WBTC' : 'SOL' );
               }
          
          // Parsing tickers for this market (before any id conversions)
          $ticker_parsing = $ticker_check;
               
          // Convert to token addresses for jupiter's price API
          $ticker_check_array = explode('/', $ticker_check);
          
          $jup_asset_check = $this->jup_address($ticker_check_array[0], false);
          
          $jup_pair_check = $this->jup_address($ticker_check_array[1]);
               
               
               // Make sure we successfully got token addresses
               if ( $jup_asset_check && $jup_pair_check ) {
               $ticker_check = $jup_asset_check . '/' . $jup_pair_check;
               }
               else {
               $logged_market_id = $jup_asset_check . '/' . $jup_pair_check;
               $ticker_check = false;
               }
          

          }
               
          
          // Make sure $ticker_check is still set (therefore presumed VALID)
          if ( $ticker_check ) {

          // Minimize calls
          $check_market_data = $this->market($ticker_only, $exchange_check, $ticker_check);
               
          
               if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                    
                    
                    if ( $specific_exchange == 'coingecko' ) {
                         
                    // Reformat, so the results structure is consistent
                    $parse_results = $this->coingecko_search($ticker_search, $ticker_only, $included_pairing);
                             
                             foreach ( $parse_results as $search_results ) {
                             $results[ $specific_exchange . '_' . $search_results['pairing'] ][] = $search_results;
                             }
                    
                    }
                    else {
                                
                                
                         // Get / set coingecko terminal asset / pair tickers
                         if ( isset($check_market_data['coingecko_terminal_asset']) ) {
                         $set_asset = $check_market_data['coingecko_terminal_asset'];
                         $set_pairing = 'usd';
                         }
                         elseif ( isset($check_market_data['alphavantage_asset']) ) {
                         $set_asset = $check_market_data['alphavantage_asset'];
                         $set_pairing = false;
                         }
                         else {
                         $set_asset = false;
                         $set_pairing = false;
                         }
                         
                         
                         // IF jupiter ag, get name, IF NOT IN RUNTIME CACHE
                         if ( $specific_exchange == 'jupiter_ag' && !isset($ct['jup_ag_runtime_cache'][ $ticker_check_array[0] ]['name']) ) {
                              
                   		// https://dev.jup.ag/docs/token-api#get-token-information
                         $jup_response = @$ct['cache']->ext_data('url', 'https://lite-api.jup.ag/tokens/v2/search?query=' . $this->jup_address($ticker_check_array[0], false) , 45); // 45 minute cache
                           
                         $jup_data = json_decode($jup_response, true);
                         
                         $jup_data = $jup_data[0];
                         
                         $ct['jup_ag_runtime_cache'][ $ticker_check_array[0] ] = $jup_data;
                         
                         $ct['jup_ag_address_mapping'][ $ct['jup_ag_runtime_cache'][ $ticker_check_array[0] ]['id'] ] = $ticker_check_array[0];
                         
                         }
                         elseif ( isset($ct['jup_ag_runtime_cache'][ $ticker_check_array[0] ]['name']) ) {
                         $jup_data = $ct['jup_ag_runtime_cache'][ $ticker_check_array[0] ];
                         }
                         
                         
                  // Minimize calls
                    $market_tickers_parse = $ct['asset']->market_tickers_parse($specific_exchange, $ticker_parsing, $set_pairing, $set_asset);
                                   
                    $results[$specific_exchange][] = array(
                                                       'name' => ( isset($jup_data['name']) && $jup_data['name'] != '' ? $jup_data['name'] : strtoupper($market_tickers_parse['asset']) ),
                                                       'id' => $ticker_check,
                                                       'asset' => $market_tickers_parse['asset'],
                                                       'pairing' => $market_tickers_parse['pairing'],
                                                       'orig_pairing' => $market_tickers_parse['orig_pairing'],
                                                       'flagged_market' => $market_tickers_parse['flagged_market'],
                                                       'data' => $check_market_data,
                                                       );
                    
                    }
            
            
               gc_collect_cycles(); // Clean memory cache
                 
               return $results;
               
               }
          
          
          }
          else {
          
          $ct['gen']->log(
                          'market_error',
                          'SPECIFIC exchange MARKET ID SEARCH (at: ' . $specific_exchange . ') CANCELLED, for INVALID MARKET ID "' . $logged_market_id . '"'
                          );
          
          }
          
          
       return false;

       }
       else {

              
            foreach ( $this->exchange_apis as $key => $val ) {
                 
            $try_pairing = false; // RESET
                 
            $ticker_search = $ct['gen']->auto_correct_market_id($ticker_search, $key);
                      
            $ticker_only = $ct['gen']->auto_correct_market_id($ticker_only, $key);
                    
                      
                    if ( is_bool($included_pairing) !== true ) {
                    $included_pairing = $ct['gen']->auto_correct_market_id($included_pairing, $key);
                    }
                 
                 
                    // IF it's flagged to skip searching alphavantage (to conserve LIMITED daily live request allowances)
                    if ( $key == 'alphavantage_stock' && isset($_POST['skip_alphavantage_search']) && $_POST['skip_alphavantage_search'] == 'yes' ) {
                    continue;
                    }
                     
                     
                    // Exchange APIs REGISTERED AS supporting 'all_markets_support' / 'search_endpoint'
                    if ( $val['all_markets_support'] || $val['search_endpoint'] ) {
                         
                         
                        if ( $key == 'upbit' ) {
                        $try_pairing = $ct['conf']['currency']['upbit_pairings_search'];
                        }
                        elseif ( $key == 'jupiter_ag' ) {
                        $try_pairing = $ct['conf']['currency']['jupiter_ag_pairings_search'];
                        }
                    
                        
                        // Trying different pairings
                        if ( $try_pairing ) {

                    
                        // Uppercase / lowercase correction
                        $try_pairing = $ct['gen']->auto_correct_market_id($try_pairing, $key);
                                       
                        $pairing_array = array_map( "trim", explode(',', $try_pairing) ); // TRIM ANY WHITESPACE
                             
                             
                             foreach ( $pairing_array as $pairing_val ) {
                                  
                                  
                                  // IF we have a USER-INPUTTED PAIRING IN THE SEARCH, then skip any pairings that are NOT RELEVANT
                                  // WE ALLOW ticker matches the pairing (instead of dis-allowing right here), because we ALSO ALLOW
                                  // 'similar' / relevant assets ('sol' search includes results for mSOL etc, and allows 'sol' as a pairing)
                                  if ( $included_pairing && !$ct['gen']->search_mode($pairing_val, $included_pairing) ) {
                                  continue;
                                  }
          
          
                             $check_results = $this->exchange_api_data($key, $ticker_search, $pairing_val); // SEARCH ONLY MODE (TICKER WITH PAIRING)
        
                             gc_collect_cycles(); // Clean memory cache
          
          
                                 if ( $check_results ) {
                                 $results[$key][$pairing_val] = $check_results;
                                 }
          
          
                             }
                             
                        
                        // Reformat, so the results structure is the same as NON $try_pairing
                        $temp_results = array();
                        
                         
                             foreach ( $results[$key] as $pair_search_results ) {
                                       
                                 foreach ( $pair_search_results as $market_result ) {
                                 $temp_results[] = $market_result;
                                 }
                                  
                             }
                                  
                                  
                             if ( sizeof($temp_results) > 0 ) {
                             $results[$key] = $temp_results;
                             }
                            
                        
                        }
                        else {
          
                             
                             if ( $included_pairing ) {
                             $check_results = $this->exchange_api_data($key, $ticker_search, $included_pairing); // SEARCH ONLY MODE (TICKER WITH PAIRING)
                             }
                             else {
                             $check_results = $this->exchange_api_data($key, $ticker_search, true); // SEARCH ONLY MODE (TICKER ONLY)
                             }
                             
        
                        gc_collect_cycles(); // Clean memory cache
                             
     
                             if ( $check_results ) {
                             $results[$key] = $check_results;
                             }
          
                             
                             
                             if ( $key == 'coingecko' ) {
                             
                             // Reformat, so the results structure is the same as NON $try_pairing
                             $temp_results = array();
                                  
                                  
                                  foreach ( $results[$key] as $pair_key => $pair_search_results ) {
                                  $temp_results[$pair_key] = $pair_search_results;
                                  }
                                  
                                  
                                  if ( sizeof($temp_results) > 0 ) {
                                  unset($results[$key]);
                                  $results = array_merge($results, $temp_results);
                                  }
                                  
                             
                             }
                             
          
                        }
                        
                        
                    }
                    
     
            }
     
     
            // Sort results by asset name
            foreach ( $results as $sort_key => $unused ) {
            $ct['sort_by_nested'] = 'root=>name';
            usort($results[$sort_key], array($ct['var'], 'usort_asc') );
            $ct['sort_by_nested'] = false; // RESET
            }     

     
       gc_collect_cycles(); // Clean memory cache
            
       return $results;

       }
         
         
   }
                           
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function exchange_search_endpoint($exchange_key, $market_search, $ticker_search_mode, $single_asset_info=false) {
   
   global $ct;
   
   // Defaults
   
   $possible_market_ids = array();
   
   $exchange_api = $this->exchange_apis[$exchange_key];
   
   $market_search = $ct['gen']->auto_correct_market_id($market_search, $exchange_key);
   
   $dyn_id = $market_search;
   
   
         // IF a PAIRING was included in the search string
         if ( $ticker_search_mode && stristr($market_search, '/') ) {
              
         $search_params = array_map( "trim", explode('/', $market_search) ); // TRIM ANY USER INPUT WHITESPACE

         $dyn_id = $search_params[0];
         $search_pairing = $search_params[1];

         }
         
         
         // IF $ticker_search_mode is NOT boolean, it's a REQUIRED pairing for SEARCHING this exchange for a certain ticker
         if ( is_bool($ticker_search_mode) !== true ) {
         $required_pairing = $ct['gen']->auto_correct_market_id($ticker_search_mode, $exchange_key);
         }
   
         
         // Coingecko's search API only takes tickers,
         // so we need their API ID info endpoint in $single_asset_info mode
         if ( $single_asset_info && $exchange_key == 'coingecko' ) {
         $url = 'https://api.coingecko.com/api/v3/coins/' . $market_search;
         }
         else {
         $url = $exchange_api['search_endpoint'];
         }
   
   
   $url = preg_replace("/\[SEARCH_QUERY\]/i", $dyn_id, $url);
   
       
       // WE ALLOW FILTERING BY TAG, FOR SAFETY
       // https://dev.jup.ag/docs/api/token-api/tagged
       if ( isset($_POST['jupiter_tag']) && trim($_POST['jupiter_tag']) != '' ) {
       $jupiter_ag_search_tag = trim($_POST['jupiter_tag']);
       }
       else {
       $jupiter_ag_search_tag = 'verified';
       }
       
   
   $url = preg_replace("/\[JUP_AG_TAG\]/i", $jupiter_ag_search_tag, $url); // Make dynamic in the future
         
   $url = preg_replace("/\[ALPHAVANTAGE_KEY\]/i", $ct['conf']['ext_apis']['alphavantage_api_key'], $url);
   
   //var_dump($url);
   
       
       // IF it's an alphavantage API request, AND it's minimum cache time
       // is higher than our global exchange search cache time, use that instead
       if (
       $exchange_key == 'alphavantage_stock'
       && $ct['dev']['throttled_apis']['alphavantage.co']['min_cache_time'] > $ct['conf']['power']['exchange_search_cache_time']
       ) {
       $cache_time = $ct['dev']['throttled_apis']['alphavantage.co']['min_cache_time'];
       }
       else {
       $cache_time = $ct['conf']['power']['exchange_search_cache_time'];
       }
       
   
   // API response data
   $response = @$ct['cache']->ext_data('url', $url, $cache_time);
   
   gc_collect_cycles(); // Clean memory cache
   
   $data = json_decode($response, true);
                
   //$ct['gen']->array_debugging($data, true); // DEBUGGING
   
   
       if ( is_array($data) ) {

       
                if ( $exchange_key == 'coinbase' ) {
            
            
                     foreach( $data as $val ) {
                          
                     // Minimize calls
                     $market_tickers_parse  = $ct['asset']->market_tickers_parse($exchange_key, $val['id']);
                          
                         
                         // If relevant
                         if ( 
                         !$search_pairing && $ct['gen']->search_mode($market_tickers_parse['asset'], $dyn_id) 
                         || $search_pairing && $ct['gen']->search_mode($market_tickers_parse['asset'], $dyn_id) 
                         && $ct['gen']->search_mode($market_tickers_parse['pairing'], $search_pairing) 
                         ) {
                         // Do nothing
                         }
                         else {
                         continue; // Skip
                         }
                         
                         
                     $check_market_data = $this->market($dyn_id, $exchange_key, $val['id']);
                                   
                                   
                         if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                        
                         $possible_market_ids[] = array(
                                                                       'name' => strtoupper($market_tickers_parse['asset']),
                                                                       'id' => $val['id'],
                                                                       'asset' => $market_tickers_parse['asset'],
                                                                       'pairing' => $market_tickers_parse['pairing'],
                                                                       'flagged_market' => $market_tickers_parse['flagged_market'],
                                                                       'data' => $check_market_data,
                                                                        );
                                                                        
                         }
                         
                     
                     }
                     
   
                gc_collect_cycles(); // Clean memory cache
                
                }
                elseif ( $exchange_key == 'jupiter_ag' ) {
                
                $temp_app_id_array = array();
                
                $debug_pairing = array(
                                       'required_pairing' => $required_pairing,
                                       'search_pairing' => $search_pairing,
                                      );
                
                //$ct['gen']->array_debugging($debug_pairing, false); // DEBUGGING
                
                //$ct['gen']->array_debugging($data, true); // DEBUGGING
                
                     
                     $loop_count = 0;
                     foreach( $data as $val ) {
                         
                         
                         if (
                         !$search_pairing && isset($val['symbol']) && $ct['gen']->search_mode($val['symbol'], $dyn_id)
                         || $search_pairing && isset($val['symbol']) && $ct['gen']->search_mode($val['symbol'], $dyn_id)
                         && $ct['gen']->search_mode($required_pairing, $search_pairing)
                         ) {

                              
                              // Skip
                              // JUPITER WON'T LET US GET MORE THAN [49 assets + 1 pairing] COINS W/ PRICE API ANYWAY,
                              // AND AS OF 2025/9/3, WE HAVE TO THROTTLE FREE TIER API CONNECTIONS TO 1 PER-SECOND,
                              // SO (FEASIBLY) OUR HARD CAP IS ~49 RELEVANT COINS PER PAIRING SEARCH (TO AVOID VERY LONG RUNTIMES)
                              // IF relevant, max $ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] results
                              // (to avoid gateway timeout on server, from too many results)
                              if (
                              $ct['gen']->search_mode($val['symbol'], $required_pairing)
                              || $loop_count >= 49 // 49 assets (+ 1 pairing PROCESSED LATER in logic [PRICE API HAS 50 MAXIMUM])
                              || $ct['jupiter_ag_search_results'] >= ($ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] * $ct['system_info']['cpu_threads'])
                              ) {
                              break;
                              }
                     
                     
                         //$ct['gen']->array_debugging($val, false); // DEBUGGING
                              
                              
                              if ( $val['symbol'] == 'SOL' || $required_pairing == 'BTC' ) {
                              $default_pairing = 'WBTC';
                              }
                              else {
                              $default_pairing = 'SOL';
                              }

                         
                         $asset_check = $val['symbol'];
                         $pairing_check = ( $required_pairing ? $required_pairing : $default_pairing );
                         
                         $pairing_address = $this->jup_address($pairing_check);
                                           
                              
                              // We need to keep MARKET VALIDATION CHECK requests to 49 or less, so we need to reset our pair array to empty,
                              // since we loop up to 49 times              
                              // (we use the ADDRESS here, as jupiter's v2 price API switched from tickers to token addresses)
                              if ( !isset($ct['jupiter_ag_pairs'][$pairing_address]) ) {
                              $ct['jupiter_ag_pairs'][$pairing_address] = $val['id'];
                              }
                              // IF APP ID wasn't bundled yet into the single call format we use for jupiter, add it now,
                              // to optimize this search loop INTO A SINGLE CALL (consecutive calls will automatically use the cache system)
                              // (THIS IS ***REQUIRED*** FOR MULTIPLE JUPITER SEARCH RESULTS, DUE TO IT'S 'BATCHED' DATA CALL STRUCTURE!!!)
                              elseif (
                              substr($ct['jupiter_ag_pairs'][$pairing_address], 0, strlen($val['id']) ) != $val['id']
                              && !strstr($ct['jupiter_ag_pairs'][$pairing_address], ',' . $val['id'])
                              ) {
                              $ct['jupiter_ag_pairs'][$pairing_address] = $ct['jupiter_ag_pairs'][$pairing_address] . ',' . $val['id'];
                              }
                         
                         
                         $temp_app_id_array[$pairing_check][ $val['symbol'] ] = array(
                                                                                      'data' => $val,
                                                                                      'asset_check' => $asset_check,
                                                                                      'asset_address' => $val['id'],
                                                                                      'pairing_check' => $pairing_check,
                                                                                      'pairing_address' => $this->jup_address($pairing_check),
                                                                                      );
                                   
                         $ct['jupiter_ag_search_results'] = $ct['jupiter_ag_search_results'] + 1;
                         
                         $loop_count = $loop_count + 1;
                         
                         }
                         
                     
                     }
                          
                          
                     //$ct['gen']->array_debugging($temp_app_id_array, true); // DEBUGGING
                     
                     
                     // Process results
                     foreach( $temp_app_id_array as $pairing_key => $pairing_data ) {
                               
                     
                          foreach( $pairing_data as $market_key => $market_data ) {
                          
                          //$ct['gen']->array_debugging($market_data, false); // DEBUGGING
                              
                          // FROM HERE ONWARD FOR JUPITER SEARCHES, WE MUST USE THE EXACT (CASE / SYMBOL SENSITIVE) MARKET IDS WE RETRIEVE,
                          // AS JUPITER CAN HAVE UPPERCASE / LOWERCASE / SYMBOLS IN THE TICKER FIELD ('symbol'), WHICH WE MUST MATCH EXACTLY!!!
                          
                               
                               // Make sure a PAIRING address was set, before checking for market data
                               if ( $market_data['pairing_address'] ) {
                          
                               // Returns ALL API results, with $return_all_results = true in the fetch_exchange_data() call
                               // (RARELY useful, as it requires unique parsing per-exchange, BUT jupiter can have MANY results,
                               // so this optimizes speed fairly well, for this one exchange's search results)
                               $check_market_data = $this->fetch_exchange_data($exchange_key, $market_data['asset_address'] . '/' . $market_data['pairing_address'], false, true);
          
                               //$ct['gen']->array_debugging($check_market_data, false); // DEBUGGING
                               
                               //$ct['gen']->array_debugging($pairing_data, false); // DEBUGGING
                               
                               $this_market_data = $check_market_data[ $market_data['asset_address'] ];
                                    
                               //$ct['gen']->array_debugging($this_market_data, false); // DEBUGGING
      
                               // Migrate v2 price API (depreciating 2025/Aug/1st) to v3, WHILE CALCULATING PAIRING VAL IN-APP (for UX)
                               $jup_asset_data = $check_market_data[ $market_data['asset_address'] ];
                               
                               $jup_pair_data = $check_market_data[ $market_data['pairing_address'] ];
                                    
                                    
                                     // DERIVE PAIRING PRICE
                                     if (
                                     isset($jup_asset_data['usdPrice'])
                                     && $ct['var']->num_to_str($jup_asset_data['usdPrice']) > 0.000000000000000000000000000000000000000
                                     && isset($jup_pair_data['usdPrice'])
                                     && $ct['var']->num_to_str($jup_pair_data['usdPrice']) > 0.000000000000000000000000000000000000000
                                     ) {
                                         
                                     $jup_asset_price = $ct['var']->num_to_str($jup_asset_data['usdPrice'] / $jup_pair_data['usdPrice']);
                         
                                     $parsed_market_data = array(        
                                                       'jup_ag_address' => $market_data['asset_address'],
                                                       'last_trade' => number_format($jup_asset_price, $ct['conf']['currency']['crypto_decimals_max'], '.', ''),
                                                       '24hr_usd_vol' => $ct['var']->num_to_str($market_data['data']['stats24h']['buyVolume'])
                                             	      );
                                             	      
                                     }
                                   	      
                                   	        
                                     if ( isset($parsed_market_data['last_trade']) && $parsed_market_data['last_trade'] > 0 ) {
                                              
                                     // Minimize calls
                                     $market_tickers_parse = $ct['asset']->market_tickers_parse($exchange_key, $market_key . '/' . $pairing_key, $pairing_key, $market_key);
                                         
                                     // IF they included coingecko app-id meta data, populate the mcap slug too
                                     $possible_market_ids[] = array(
                                                                         'name' => $market_data['data']['name'],
                                                                         'id' => $market_data['asset_address'] . '/' . $market_data['pairing_address'],
                                                                         'contract_address' => $market_data['asset_address'],
                                                                         'asset' => $market_tickers_parse['asset'],
                                                                         'pairing' => $market_tickers_parse['pairing'],
                                                                         'orig_pairing' => $market_tickers_parse['orig_pairing'],
                                                                         'flagged_market' => $market_tickers_parse['flagged_market'],
                                                                         'data' => $parsed_market_data,
                                                                          );
                                                                          
                                     }
                                    
          
                               }
                               // Otherwise, log this search error
                               else {
     
                               $ct['gen']->log(
                              				'market_error',
                              				'no token address set for PAIRING "' . $pairing_key . '" (for SEARCH at: ' . $exchange_key . '), so market data check was cancelled'
                              				);
                               
                               }
                                         
                                         
                          }
                     
                     
                     }
                     
       
                gc_collect_cycles(); // Clean memory cache
                       
                }
                elseif ( $exchange_key == 'coingecko' ) {
                
                
                     // Return an API ID's associated values
                     if ( $single_asset_info && isset($data['id']) && $data['id'] == $market_search ) {
                     gc_collect_cycles(); // Clean memory cache
                     return $data;
                     }
                     // Get search results
                     else {
                     
                     $temp_app_id_array = array();
                          
                     $data = $data['coins'];
                          
                     
                          // PROCESS RESULTS with $temp_app_id_array
                          foreach( $data as $val ) {
                              
                              
                              // Search only TICKER SYMBOL fields (to avoid NON-RELEVANT results)
                              if ( isset($val['api_symbol']) && isset($val['symbol']) && $ct['gen']->search_mode($val['symbol'], $dyn_id) ) {
                                   
                              $temp_app_id_array[ $val['api_symbol'] ] = $val;
                                
                                           
                                   if ( $ct['coingecko_assets'] == null ) {
                                   $ct['coingecko_assets'] = $val['api_symbol'];
                                   }
                                   // IF API ID wasn't bundled yet into the single call format we use for coingecko, add it now,
                                   // to optimize this search loop INTO A SINGLE CALL (consecutive calls will automatically use the cache system)
                                   // (THIS IS ***REQUIRED*** FOR MULTIPLE COINGECKO SEARCH RESULTS, DUE TO IT'S 'BATCHED' DATA CALL STRUCTURE!!!)
                                   elseif (
                                   substr($ct['coingecko_assets'], 0, strlen($val['api_symbol']) ) != $val['api_symbol']
                                   && !stristr($ct['coingecko_assets'], ',' . $val['api_symbol'])
                                   ) {
                                   $ct['coingecko_assets'] = $ct['coingecko_assets'] . ',' . $val['api_symbol'];
                                   }
                                   
                              }
                          
                          
                          }
                          
                          
                          ksort($temp_app_id_array); // Alphabetic sort, for UX
                          
                          //$ct['gen']->array_debugging($temp_app_id_array, true); // DEBUGGING
                     
                          // Process results
                          foreach( $temp_app_id_array as $app_id => $asset_data ) {
                     
                                   
                              if ( $search_pairing ) {
                              $pairing_for_initial_check = $search_pairing;
                              }
                              else {
                              $pairing_for_initial_check = 'usd';
                              }
                                   
                              
                              // If we searched with a pairing included, that's the only pairing we want in search results
                              if ( $search_pairing ) {
                              $ct['coingecko_pairs'] = $search_pairing;
                              }
                              elseif ( $ct['coingecko_pairs'] == null ) {
                              $ct['coingecko_pairs'] = 'usd';
                              }
                              // Make sure any SPECIFIED pairing is in our optimized single calls to coingecko
                              elseif (
                              substr($ct['coingecko_pairs'], 0, strlen($pairing_for_initial_check) ) != $pairing_for_initial_check
                              && !stristr($ct['coingecko_pairs'], ',' . $pairing_for_initial_check)
                              ) {
                              $ct['coingecko_pairs'] = $ct['coingecko_pairs'] . ',' . $pairing_for_initial_check;
                              }
                                   
                         
                          $check_market_data = $this->market($app_id, $exchange_key . '_' . $pairing_for_initial_check, $app_id);
                                        
                                        
                              if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                   
                              //var_dump($check_market_data);
                                   
                              // Reformat, so the results structure is consistent, and pass $asset_data to speed up runtime
                              $parse_results = $this->coingecko_search($market_search, $app_id, $search_pairing, $asset_data);
                              
                              //$ct['gen']->array_debugging($parse_results, true); // DEBUGGING ONLY
                                  
                                   foreach ( $parse_results as $search_results ) {
                                   $possible_market_ids[ $exchange_key . '_' . $search_results['pairing'] ][] = $search_results;
                                   }
                              
                              }
                                   
     
                          }
                          
   
                     gc_collect_cycles(); // Clean memory cache
                     
                     }
                     
                
                }
                elseif ( $exchange_key == 'alphavantage_stock' ) {
                
                
                    if ( isset($data['bestMatches']) && is_array($data['bestMatches']) && sizeof($data['bestMatches']) > 0 ) {
                         
                         
                         foreach( $data['bestMatches'] as $result ) {
                                   
                         // Minimize calls
                         $market_tickers_parse  = $ct['asset']->market_tickers_parse($exchange_key, $result["1. symbol"], $result["8. currency"]);
                         
                         
                              // Skip, if not relevant
                              if ( 
                              !$search_pairing && $ct['gen']->search_mode($market_tickers_parse['asset'], $dyn_id) 
                              || $search_pairing && $ct['gen']->search_mode($market_tickers_parse['asset'], $dyn_id) 
                              && $ct['gen']->search_mode($market_tickers_parse['pairing'], $search_pairing)
                              ) {
                              // Do nothing
                              }
                              else {
                              continue; // Skip
                              }
                              
                                   
                              
                              // DON'T DO ANY POTENTIALLY ***LIVE*** MARKET CHECKS FOR THE ALPHAVANTAGE ***FREE*** TIER,
                              // AS THE FREE TIER DAILY LIMITS ARE VERY LOW, AND COULD CUT OFF DAILY LIVE REQUESTS BEFORE END-OF-DAY!!!
                              if ( $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 ) {
                                   
                              $possible_market_ids[] = array(
                                                                                 'name' => $result["2. name"],
                                                                                 'id' => $result["1. symbol"],
                                           // Even though we know the pairing, we still need to replace any MULTI-TICKER CURRENCY (NIS/CNY) with ticker used in-app
                                           // (for pairing UX in the app)
                                                                                 'asset' => $market_tickers_parse['asset'],
                                                                                 'pairing' => $market_tickers_parse['pairing'],
                                                                                 'flagged_market' => $market_tickers_parse['flagged_market'],
                                                                                 'data' => array('last_trade' => 'SKIPPED, SO FREE TIER STAYS WITHIN DAILY LIMITS! (upgrade your alphavantage API KEY to a PREMIUM tier [and adjust "AlphaVantage.co Per Minute Limit" HIGHER THAN 5 accordingly, in the "External APIs" section], to SAFELY see price previews [without exceeding your limits])'),
                                                                                  );
                                                                                  
                              }
                              else {
                    
                              $check_market_data = $this->market($dyn_id, $exchange_key, $result["1. symbol"]);
                                             
                                             
                                    if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                                  
                                    $possible_market_ids[] = array(
                                                                                 'name' => $result["2. name"],
                                                                                 'id' => $result["1. symbol"],
                                           // Even though we know the pairing, we still need to replace any MULTI-TICKER CURRENCY (NIS/CNY) with ticker used in-app
                                           // (for pairing UX in the app)
                                                                                 'asset' => $market_tickers_parse['asset'],
                                                                                 'pairing' => $market_tickers_parse['pairing'],
                                                                                 'flagged_market' => $market_tickers_parse['flagged_market'],
                                                                                 'data' => $check_market_data,
                                                                                  );
                                                                                  
                                    }
                              
                              
                              }
                              
                         
                         }
                     
   
                    gc_collect_cycles(); // Clean memory cache

                    }
                
                }

       
       }

       
   gc_collect_cycles(); // Clean memory cache
   
       
       if ( sizeof($possible_market_ids) > 0 ) {
       return $possible_market_ids;
       }
       else {
       return false;
       }
       
   
   }
                           
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function fetch_exchange_data($exchange_key, $market_id, $ticker_search_mode=false, $return_all_results=false) {
        
   global $ct;
   
   $possible_market_ids = array();
   
   $limited_apis = array();
   
   $market_id = trim($market_id); // TRIM ANY USER INPUT WHITESPACE

     
     // ONLY TRIM IF NOT BOOLEEN!!!!!!!!!!
     if ( is_bool($ticker_search_mode) !== true ) {
     $ticker_search_mode = trim($ticker_search_mode); // TRIM ANY USER INPUT WHITESPACE
     }
     

   // DEFAULTS         
   $exchange_api = $this->exchange_apis[$exchange_key];
   
   $dyn_id = $market_id;
   
   // Adjust cache time accordingly
   
   $cache_time = $ct['conf']['power']['last_trade_cache_time']; // Default
   
   $cache_time = ( $ct['ticker_markets_search'] ? $ct['conf']['power']['exchange_search_cache_time'] : $cache_time ); // IF ticker searching, optimize for runtime speed
           
   // Get exchange's markets endpoint domain
   $tld_or_ip = $ct['gen']->get_tld_or_ip( $exchange_api['markets_endpoint'] );
   
   // IF 'min_cache_time' is set, we have to throttle LIVE requests with that value
   $cache_time = ( isset($ct['dev']['throttled_apis'][$tld_or_ip]['min_cache_time']) ? $ct['dev']['throttled_apis'][$tld_or_ip]['min_cache_time'] : $cache_time );
          
   $url = $exchange_api['markets_endpoint'];
   
         
         // IF ticker search AND a LIMITED API WITH A MARKETS LIST ENDPOINT
         if ( $ticker_search_mode && $exchange_api['search_endpoint'] ) {
         return $this->exchange_search_endpoint($exchange_key, $market_id, $ticker_search_mode);
         }
         // IF a PAIRING was included in the search string
         elseif ( $ticker_search_mode && stristr($market_id, '/') ) {
              
         $search_params = array_map( "trim", explode('/', $market_id) ); // TRIM ANY USER INPUT WHITESPACE

         $dyn_id = $search_params[0];
         $search_pairing = $search_params[1];
         
         $required_pairing = $search_pairing;

         }
         // ELSE IF $ticker_search_mode is NOT boolean, it's a REQUIRED pairing for SEARCHING this exchange for a certain ticker
         elseif ( is_bool($ticker_search_mode) !== true ) {
         $required_pairing = $ticker_search_mode;
         }
             
         
         // Exchange-specific logic
         if ( $exchange_key == 'alphavantage_stock' ) {
         $url = preg_replace("/\[ALPHAVANTAGE_KEY\]/i", $ct['conf']['ext_apis']['alphavantage_api_key'], $url);
         }
         elseif ( $exchange_key == 'coingecko' ) {
                     
         // (coingecko's response path is DYNAMIC, based off market id)
         $exchange_api['markets_nested_path'] = $dyn_id;
                   
                         
              if ( $ct['coingecko_assets'] == null ) {
              $ct['coingecko_assets'] = $dyn_id;
              }
              // IF API ID wasn't bundled yet into the single call format we use for coingecko,
              // add it now, or we WON'T GET RELEVANT RESULTS (when VALIDATING 'add market' search results, etc)
              // WE INCLUDE SEARCHING FOR A COMMA IN FRONT OF THE API ID, AS WELL AS IT BEING THE FIRST VALUE
              elseif (
              substr($ct['coingecko_assets'], 0, strlen($dyn_id) ) != $dyn_id
              && !stristr($ct['coingecko_assets'], ',' . $dyn_id)
              ) {
              $ct['coingecko_assets'] = $ct['coingecko_assets'] . ',' . $dyn_id;
              }
              
                   
         $url = preg_replace("/\[COINGECKO_ASSETS\]/i", $ct['coingecko_assets'], $url);
         $url = preg_replace("/\[COINGECKO_PAIRS\]/i", $ct['coingecko_pairs'], $url);
         
         //var_dump($url);
                
         }
         elseif ( $exchange_key == 'coingecko_terminal' ) {
              
         // DO NOT CONVERT TO LOWERCASE!!!
                
         $id_parse = array_map( "trim", explode("||", $dyn_id) );
           
               
             // Auto-correct for some inconsistencies in the API's semantics
             if ( $id_parse[0] == 'ethereum' ) {
             $id_parse[0] = 'eth';
             }
             elseif ( $id_parse[0] == 'sol' ) {
             $id_parse[0] = 'solana';
             }
             
         
         $url = preg_replace("/\[MARKET\]/i", $id_parse[0].'/pools/'.$id_parse[1], $url);

         }
         elseif ( $exchange_key == 'upbit' ) {

         
             if ( $required_pairing ) {
             $url = preg_replace("/\[UPBIT_BATCHED_MARKETS\]/i", $required_pairing . '-' . $dyn_id, $url);
             }
             else {
                  
                       
                   if ( $ct['upbit_batched_markets'] == null ) {
                   $ct['upbit_batched_markets'] = $dyn_id;
                   }
                   // IF MARKET ID wasn't bundled yet into the single call format we use for upbit,
                   // add it now, or we WON'T GET RELEVANT RESULTS (when VALIDATING 'add market' search results, etc)
                   // WE INCLUDE SEARCHING FOR A COMMA IN FRONT OF THE MARKET ID, AS WELL AS IT BEING THE FIRST VALUE
                   elseif (
                   substr($ct['upbit_batched_markets'], 0, strlen($dyn_id) ) != $dyn_id
                   && !stristr($ct['upbit_batched_markets'], ',' . $dyn_id)
                   ) {
                   $ct['upbit_batched_markets'] = $ct['upbit_batched_markets'] . ',' . $dyn_id;
                   }
                  
                  
             $url = preg_replace("/\[UPBIT_BATCHED_MARKETS\]/i", $ct['upbit_batched_markets'], $url);
             
             }


         }
         elseif ( $exchange_key == 'jupiter_ag' ) {
         
         $search_pairing = false; // NOT used for parsing jupiter_ag responses

         $jup_market = explode('/', $dyn_id);
         
         $dyn_id = $jup_market[0]; // Reset for parsing results (result key is only the asset ID)
             
             
              if ( sizeof($jup_market) < 2 ) {
          				          	
              $ct['gen']->log(
          				'market_error',
          				'ct_api->fetch_exchange_data(): REQUIRED asset PAIRING missing (exchange: '.$exchange_key.'; market_id: '.$market_id.'; ticker_pairing_search: '.$ticker_search_mode.'; )'
          				);
                 
              gc_collect_cycles(); // Clean memory cache
              
              return false;
                 
              }
                   
                       
              // IF market data wasn't bundled yet into the single calls format we use for jupiter,
              // add it now, or we WON'T GET RELEVANT RESULTS (when VALIDATING 'add market' search results, etc)
              if ( !isset($ct['jupiter_ag_pairs'][ $jup_market[1] ]) ) {
              $ct['jupiter_ag_pairs'][ $jup_market[1] ] = $jup_market[0];
              }
              // WE INCLUDE SEARCHING FOR A COMMA IN FRONT OF THE TICKER, AS WELL AS IT BEING THE FIRST VALUE
              // WE ALSO NEED CASE-SENSITIVE SEARCH WITH strstr INSTEAD FOR JUPITER
              // (AS JUP TICKERS CAN BE CASE-SENSITIVE AND DIFFER! [MSOL and mSOL are TWO DIFFERENT ASSETS])
              elseif (
              substr($ct['jupiter_ag_pairs'][ $jup_market[1] ], 0, strlen($jup_market[0]) ) != $jup_market[0]
              && !strstr($ct['jupiter_ag_pairs'][ $jup_market[1] ], ',' . $jup_market[0])
              ) {
              $ct['jupiter_ag_pairs'][ $jup_market[1] ] = $ct['jupiter_ag_pairs'][ $jup_market[1] ] . ',' . $jup_market[0];
              }

         
         $url = preg_replace("/\[JUP_AG_ASSETS\]/i", $ct['jupiter_ag_pairs'][ $jup_market[1] ], $url);
         
         $url = preg_replace("/\[JUP_AG_PAIRING\]/i", $jup_market[1], $url);
         
         // Migrate v2 price API (depreciating 2025/Aug/1st) to v3, WHILE CALCULATING PAIRING VAL IN-APP (for UX)
         $emulate_jup_pairing_price = explode("&vsToken=" , $url);
         
         $url = $emulate_jup_pairing_price[0] . ',' . $emulate_jup_pairing_price[1];
         
         $jup_assets_count = substr_count($url, ',') + 1;
         
              if ( $jup_assets_count > 50 ) {
              
              $ct['gen']->log(
               		     'market_error',
               			'jupiter aggregator\'s price API has a MAXIMUM of 50 assets PER-REQUEST ('.$jup_assets_count.' assets detected)'
               			);
          				        
              }

         }
         elseif ( $exchange_key == 'loopring' ) {
         
              if ( substr($dyn_id, 0, 4) == "AMM-" ) {
              $exchange_api['markets_nested_path'] = 'pools';
              }
              else {
              $exchange_api['markets_nested_path'] = 'markets';
              }
              
         }
         elseif ( $exchange_key == 'luno' ) {
         
             if ( $ticker_search_mode && strtolower($dyn_id) == 'btc' ) {
             $dyn_id = 'xbt';
             }

         }
   
   
         // When we are getting SPECIFIED markets (NOT all markets on the exchange)
         if ( !$ticker_search_mode || $exchange_api['search_endpoint'] ) {
         $url = preg_replace("/\[MARKET\]/i", $dyn_id, $url);
         }
          
          
   // API response data
   $response = @$ct['cache']->ext_data('url', $url, $cache_time);
   
   gc_collect_cycles(); // Clean memory cache
          
   $data = json_decode($response, true);
         
         
         // If our data set is in a subarray, dig down to SET IT AS THE BASE in $data
         if ( is_array($data) && $exchange_api['markets_nested_path'] ) {
              
         $markets_nested_path = explode('>', $exchange_api['markets_nested_path']);

              foreach( $markets_nested_path as $val ) {
              $data = $data[$val];
              }

         }
         
         
         // Optimize results
         // IF $exchange_api['all_markets_support'] SET AS: true|[associative key, including numbers]
         if (
         is_array($data) && $exchange_api['all_markets_support']
         || is_array($data) && is_bool($exchange_api['all_markets_support']) !== true
         ) {
         
              
              // If a specific key name is always holding the market ID info as a value
              if ( is_bool($exchange_api['all_markets_support']) !== true ) {
              
         
                   foreach ($data as $val) {
                  
                  
                       if ( isset($val[ $exchange_api['all_markets_support'] ]) ) {
                            
                            
                            // Minimize calls
                            if ( $ticker_search_mode ) {
                            $market_tickers_parse  = $ct['asset']->market_tickers_parse($exchange_key, $val[ $exchange_api['all_markets_support'] ]);
                            }
                            
                            
                            // As long as we are NOT in search mode, OR parsed asset ticker is relevant
                            if (
                            !$ticker_search_mode && $val[ $exchange_api['all_markets_support'] ] == $dyn_id
                            || $ticker_search_mode && !$search_pairing && $ct['gen']->search_mode($market_tickers_parse['asset'], $dyn_id)
                            || $ticker_search_mode && $search_pairing && $ct['gen']->search_mode($market_tickers_parse['asset'], $dyn_id)
                            && $ct['gen']->search_mode($market_tickers_parse['pairing'], $search_pairing)
                            ) {
                            // Do nothing
                            }
                            else {
                            continue; // Skip this loop
                            }
                            
                       
                            // Workaround for weird zebpay API bug, where they include a second array object
                            // with same 'all_markets_support' (key name = 'pair') property, that's mostly a NULL data set
                            if ( $exchange_key == 'zebpay' ) {
                                 
                            $test_data = $val;
                            
                       
                                 if ( isset($test_data["market"]) && $test_data["market"] > 0 ) {
                                 
                                    
                                    if ( $ticker_search_mode ) {
                                         
                                    // Minimize calls
                                    $check_market_data = $this->market($dyn_id, $exchange_key, $val[ $exchange_api['all_markets_support'] ]);
                              
                              
                                        if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                                        
                                        $possible_market_ids[] = array(
                                                                       'name' => strtoupper($market_tickers_parse['asset']),
                                                                       'id' => $val[ $exchange_api['all_markets_support'] ],
                                                                       'asset' => $market_tickers_parse['asset'],
                                                                       'pairing' => $market_tickers_parse['pairing'],
                                                                       'flagged_market' => $market_tickers_parse['flagged_market'],
                                                                       'data' => $check_market_data,
                                                                      );
                                                                                 
                                        }
                                        
   
                                    gc_collect_cycles(); // Clean memory cache

                                    }
                                    else {
                                    $data = $test_data;
                                    break; // will assure leaving the foreach loop immediately
                                    }
                                    
                                 
                                 }
                                 
                            }
                            else {
                                        
                            
                                 // As long as parsed asset ticker is relevant
                                 if ( $ticker_search_mode ) {
                                      
                                         
                                 // Minimize calls
                                 $check_market_data = $this->market($dyn_id, $exchange_key, $val[ $exchange_api['all_markets_support'] ]);
                              
                              
                                        if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                                        
                                        $possible_market_ids[] = array(
                                                                   'name' => strtoupper($market_tickers_parse['asset']),
                                                                   'id' => $val[ $exchange_api['all_markets_support'] ],
                                                                   'asset' => $market_tickers_parse['asset'],
                                                                   'pairing' => $market_tickers_parse['pairing'],
                                                                   'flagged_market' => $market_tickers_parse['flagged_market'],
                                                                   'data' => $check_market_data,
                                                                  );
                                                                  
                                        }
                                 
   
                                 gc_collect_cycles(); // Clean memory cache
                                 
                                 }
                                 else {
                                 $data = $val;
                                 break; // will assure leaving the foreach loop immediately
                                 }
                                 
                       
                            }
     
     
                       }
                  
                   
                   }
                   
                   
              }
              // SEARCH ONLY on top level key name
              elseif ( $ticker_search_mode ) {
         
         
                   foreach ($data as $key => $val) {

                   // Minimize calls
                   $market_tickers_parse = $ct['asset']->market_tickers_parse($exchange_key, $key);
                       
                       
                       if (
                       !$search_pairing && $ct['gen']->search_mode($market_tickers_parse['asset'], $dyn_id)
                       || $search_pairing && $ct['gen']->search_mode($market_tickers_parse['asset'], $dyn_id) 
                       && $ct['gen']->search_mode($market_tickers_parse['pairing'], $search_pairing)
                       ) {
                                         
                       // Minimize calls
                       $check_market_data = $this->market($dyn_id, $exchange_key, $key);
                              
                              
                            if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                             
                            $possible_market_ids[] = array(
                                                                   'name' => strtoupper($market_tickers_parse['asset']),
                                                                   'id' => $key,
                                                                   'asset' => $market_tickers_parse['asset'],
                                                                   'pairing' => $market_tickers_parse['pairing'],
                                                                   'flagged_market' => $market_tickers_parse['flagged_market'],
                                                                   'data' => $check_market_data,
                                                                  );
                                                                  
                            }
   
   
                       gc_collect_cycles(); // Clean memory cache
                                                                  
                       }                      
                       
                        
                   }
                   
              }
              // If the top level (parent) key name IS THE MARKET ID ITSELF,
              // AND we only need to get that one market's data
              // (otherwise, we'll get ALL results from the API's response [that we can use to optimize asset search results, etc])
              elseif ( !$return_all_results && isset($data[$dyn_id]) ) {
              $data = $data[$dyn_id];
              }

         
         }
         
         
         // If no data
         if ( !$ticker_search_mode && !is_array($data) || $ticker_search_mode && sizeof($possible_market_ids) < 1 ) {
         
              
              // DEBUG LOGGING
              if ( $ticker_search_mode && $ct['conf']['power']['debug_mode'] == 'markets' ) {
              
              $ct['gen']->log(
              		    'notify_debug',
              		    'NO DATA for market: "' . $dyn_id . ( $required_pairing ? '/' . $required_pairing : '' ) . '" @ ' . $exchange_key . '-SEARCH_ONLY',
              		    false,
              		    'no_market_data_' . $exchange_key . '-SEARCH_ONLY' . $dyn_id . ( $required_pairing ? $required_pairing : '' )
              		    );
         
              }
              elseif ( $ct['conf']['power']['debug_mode'] == 'markets' ) {
                   
              $ct['gen']->log(
              		    'notify_debug',
              		    'NO DATA for market: "' . $dyn_id . ( $required_pairing ? '/' . $required_pairing : '' ) . '" @ ' . $exchange_key,
              		    false,
              		    'no_market_data_' . $exchange_key . $dyn_id . ( $required_pairing ? $required_pairing : '' )
              		    );
              
              }
    
    
         gc_collect_cycles(); // Clean memory cache
         
         return false;
          
         }
         elseif ( $ticker_search_mode  ) {
         gc_collect_cycles(); // Clean memory cache
         return $possible_market_ids;  
         }
         else {
         gc_collect_cycles(); // Clean memory cache
         return $data;
         }
         
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // Credit: https://www.alexkras.com/simple-rss-reader-in-85-lines-of-php/
   function rss($url, $theme_selected, $feed_size, $cache_only=false, $email_only=false) {
      
   global $ct, $fetched_feeds;
   
   
      if ( !isset($_SESSION[$fetched_feeds]['all']) ) {
      $_SESSION[$fetched_feeds]['all'] = 0;
      }
      // Never re-cache FROM LIVE more than 'news_feed_batched_maximum' (EXCEPT for cron runtimes pre-caching), 
      // to avoid overloading low resource devices (raspi / pine64 / etc) and creating long feed load times
      elseif ( $_SESSION[$fetched_feeds]['all'] >= $ct['conf']['news']['news_feed_batched_maximum'] && $cache_only == false && $ct['runtime_mode'] != 'cron' ) {
      return '<span class="red">Live data fetching limit reached (' . $_SESSION[$fetched_feeds]['all'] . ').</span>';
      }
      // Avoid overloading low power devices with the precache hard limit
      elseif ( $cache_only == true && $ct['precache_feeds_count'] >= $ct['conf']['news']['news_feed_precache_maximum'] ) {
      return false;
      }
      
   
   $news_feed_cache_min_max = explode(',', $ct['conf']['news']['news_feed_cache_min_max']);
   // Cleanup
   $news_feed_cache_min_max = array_map('trim', $news_feed_cache_min_max);
      
   $rss_feed_cache_time = rand($news_feed_cache_min_max[0], $news_feed_cache_min_max[1]);
                                    
         
      // If we will be updating the feed (live data will be retreived)
      if ( $ct['cache']->update_cache($ct['base_dir'] . '/cache/secured/external_data/' . md5($url) . '.dat', $rss_feed_cache_time) == true ) {
          
          
          // IF WE ARE PRECACHING, COUNT TO STOP AT THE HARD LIMIT
          if ( $cache_only == true ) {
          $ct['precache_feeds_count'] = $ct['precache_feeds_count'] + 1;
          }
      
      
      $_SESSION[$fetched_feeds]['all'] = $_SESSION[$fetched_feeds]['all'] + 1; // Mark as a fetched feed, since it's going to update
      
      $tld_or_ip = $ct['gen']->get_tld_or_ip($url);
   
         
          if ( $ct['conf']['power']['debug_mode'] == 'memory_usage_telemetry' ) {
         	
          $ct['gen']->log(
         			  'system_debug',
         			  $tld_or_ip . ' news feed updating ('.$_SESSION[$fetched_feeds]['all'].'), CURRENT script memory usage is ' . $ct['gen']->conv_bytes(memory_get_usage(), 1) . ', PEAK script memory usage is ' . $ct['gen']->conv_bytes(memory_get_peak_usage(), 1) . ', php_sapi_name is "' . php_sapi_name() . '"'
         			   );
         
          }
      
      
      // Throttling multiple requests to same server
      $tld_session = $ct['gen']->safe_name($tld_or_ip);
   
            
          if ( !isset($_SESSION[$fetched_feeds][$tld_session]) ) {
          $_SESSION[$fetched_feeds][$tld_session] = 0;
          }
          // If it's a consecutive feed request to the same server,
          // sleep 1 second to avoid rate limiting request denials
          elseif ( $_SESSION[$fetched_feeds][$tld_session] > 0 ) {
             
             // 1 second for everything generic (NOT in $ct['dev']['throttled_apis'])
              if ( !array_key_exists($tld_or_ip, $ct['dev']['throttled_apis']) ) {
              sleep(1); 
              }
            
          }
   
               
      $_SESSION[$fetched_feeds][$tld_session] = $_SESSION[$fetched_feeds][$tld_session] + 1;	
   
         
      } // END if updating feed
         
               
   // Get feed data (whether cached or re-caching live data)
   $response = @$ct['cache']->ext_data('url', $url, $rss_feed_cache_time); 
         
      
      // Format output (UNLESS WE ARE ONLY CACHING DATA)
      if ( !$cache_only ) {
           
      // suppress warnings so we can handle them ourselves
      libxml_use_internal_errors(true);
         
      $rss = simplexml_load_string($response);
      
      
          // If invalid XML
          if ( $rss === false ) {
           
          $tld_or_ip = $ct['gen']->get_tld_or_ip($url);
           
          // Log full results to file, TO GET LINE NUMBERS FOR ERRORS
          
          // FOR SECURE ERROR LOGS, we redact the full path
          $xml_response_file_cache = '/cache/logs/debug/xml_error_parsing/xml-data-'.preg_replace("/\./", "_", $tld_or_ip).'.xml';
          
          $xml_response_file = $ct['base_dir'] . $xml_response_file_cache;
          
          
               // If we don't already have a saved XML file of this data
               if ( !file_exists($xml_response_file) ) {
           
               // Log this error response from this data request
               $save_result = $ct['cache']->save_file($xml_response_file, $response);
                    
               libxml_clear_errors();
               
               
                    if ( $save_result ) {
                         
                    $log_details = ', SAVED FOR 48 HOURS TO FILE FOR INSPECTION AT ' . $ct['sec']->obfusc_path_data($xml_response_file_cache);

                    sleep(1);
                    
                    // Load again, BUT FROM THE SAVED FILE (to get line numbers of all errors)
                    $rss_check = simplexml_load_string($xml_response_file);
                         
                    $xml_errors = libxml_get_errors();
                    
                         foreach ( $xml_errors as $error ) {
                         $ct['gen']->log('other_error', 'URL "' . $url . '" XML error details: ' . $ct['gen']->display_xml_error($error) );
                         }
                         
                    libxml_clear_errors();
                    
                    }
                    else {
                    $log_details = ' (SAVING TO FILE FOR INSPECTION FAILED)';
                    }

           
               $ct['gen']->log('other_error', 'error reading XML-based news feed data from ' . $url . $log_details);

               }


          gc_collect_cycles(); // Clean memory cache

          return '<span class="red">Error reading news feed data (XML error), SEARCH admin app logs for "XML" details.</span>';

          }
                     
                     
      $html .= '<ul>';
      
      $html_hidden .= '<ul class="hidden" id="'.md5($url).'">';
      
      $mark_new = ' &nbsp; <img alt="" src="templates/interface/media/images/auto-preloaded/twotone_fiber_new_' . $theme_selected . '_theme_48dp.png" height="25" title="New Article (under ' . $ct['conf']['news']['mark_as_new'] . ' days old)" />';
             
      $now_timestamp = time();
             
      $count = 0;
             
	      // Atom format
	      if ( is_object($rss->entry) && sizeof($rss->entry) > 0 ) {
	             
	      $sortable_feed = array();
	               
		      foreach($rss->entry as $item) {
		      $sortable_feed[] = $item;
		      }
		               
                if ( is_array($sortable_feed) ) { 
		      $usort_results = usort($sortable_feed,  array($ct['var'], 'rss_usort_newest') );
		      }
		               
		      if ( !$usort_results ) {
		      $ct['gen']->log( 'other_error', 'RSS feed failed to sort by newest items (' . $url . ')');
		      }
		             
		            
		      foreach($sortable_feed as $item) {
		                  
			     // If data exists, AND we aren't just caching data during a cron job
			     if ( isset($item->title) && trim($item->title) != '' && $feed_size > 0 ) {
			               
				     if ( $item->pubDate != '' ) {
				     $item_date = $item->pubDate;
				     }
				     elseif ( $item->published != '' ) {
				     $item_date = $item->published;
				     }
				     elseif ( $item->updated != '' ) {
				     $item_date = $item->updated;
				     }
			          // Support for the 'dc' namespace
			          elseif (
	                    is_object($item)
                    	&& is_object( $item->children('dc', true) )
                    	&& sizeof( $item->children('dc', true) ) > 0
			          ) {
			         
                         $dc_namespace = $item->children('dc', true);
                        
                            if ( $dc_namespace->date != '' ) {
                            $item_date = $dc_namespace->date;
                            }
			         
			          }
				               
				     if ( !$item->link['href'] && $item->enclosure['url'] ) {
				     $item_link = $item->enclosure['url'];
				     }
				     elseif ( $item->link['href'] != '' ) {
				     $item_link = $item->link['href'];
				     }
			                  
			     $date_array = date_parse($item_date);
			                  
			     $month_name = date("F", mktime(0, 0, 0, $date_array['month'], 10));
			                  
			     $date_ui = $month_name . ' ' . $ct['gen']->ordinal($date_array['day']) . ', ' . $date_array['year'] . ' @ ' . substr("0{$date_array['hour']}", -2) . ':' . substr("0{$date_array['minute']}", -2);
			            
			                  
				     // If publish date is OVER 'news_feed_entries_new' days old, DONT mark as new
				     // With offset, to try to catch any that would have been missed from runtime
				     if ( $ct['var']->num_to_str($now_timestamp) > $ct['var']->num_to_str( strtotime($item_date) + ($ct['conf']['news']['mark_as_new'] * 86400) ) ) {
				     $mark_new = null;
				     }
				     // If running as $email_only, we only want 'new' posts anyway (less than 'news_feed_email_frequency' days old)
				     // With offset, to try to catch any that would have been missed from runtime
				     elseif ( $email_only && $ct['var']->num_to_str($now_timestamp) <= $ct['var']->num_to_str( strtotime($item_date) + ($ct['conf']['news']['news_feed_email_frequency'] * 86400) ) ) { 
				     
    				     if ($count < $ct['conf']['news']['news_feed_email_entries_include']) {
    				     $html .= '<li style="padding: 8px;"><a style="color: #00b6db;" href="'.htmlspecialchars($item_link).'" target="_blank" title="'.htmlspecialchars($date_ui).'">'.htmlspecialchars($item->title).'</a> </li>';
    				     }
    				     
			         $count++;   
			         
				     }
				     
				     
				     if ( !$email_only ) {
				         
    				     if ($count < $feed_size) {
    				     $html .= '<li class="links_list"><a href="'.htmlspecialchars($item_link).'" target="_blank" title="'.htmlspecialchars($date_ui).'">'.htmlspecialchars($item->title).'</a> '.$mark_new.'</li>';
    				     }
    				     else {
    				     $html_hidden .= '<li class="links_list"><a href="'.htmlspecialchars($item_link).'" target="_blank" title="'.htmlspecialchars($date_ui).'">'.htmlspecialchars($item->title).'</a> '.$mark_new.'</li>';
    				     }
			                       
			         $count++;   
    				     
				     }
			               
			               
			     }

		               
		      }
	               
	             
	      }
	      // Standard RSS format(s)
	      elseif (
	      is_object($rss->channel->item) && sizeof($rss->channel->item) > 0
	      || is_object($rss->item) && sizeof($rss->item) > 0
	      ) {
	             
	      $sortable_feed = array();
	      
	          
	          // Detect which format (items in/out of the channel tag)
	          if ( is_object($rss->channel->item) && sizeof($rss->channel->item) > 0 ) {
	          $rss_items = $rss->channel->item;
	          }
	          else {
	          $rss_items = $rss->item;
	          }

	               
	          foreach($rss_items as $item) {
	          $sortable_feed[] = $item;
	          }
	               
               if ( is_array($sortable_feed) ) { 
	          $usort_results = usort($sortable_feed, array($ct['var'], 'rss_usort_newest') );
	          }
	               
	          if ( !$usort_results ) {
	          $ct['gen']->log( 'other_error', 'RSS feed failed to sort by newest items (' . $url . ')');
	          }
	            
	             
	          foreach($sortable_feed as $item) {
	                  
	                  
		         // If data exists, AND we aren't just caching data during a cron job
		         if ( isset($item->title) && trim($item->title) != '' && $feed_size > 0 ) {
		               
			         if ( $item->pubDate != '' ) {
			         $item_date = $item->pubDate;
			         }
			         elseif ( $item->published != '' ) {
			         $item_date = $item->published;
			         }
			         elseif ( $item->updated != '' ) {
			         $item_date = $item->updated;
			         }
			         // Support for the 'dc' namespace
			         elseif (
                        is_object($item)
                        && is_object( $item->children('dc', true) )
                        && sizeof( $item->children('dc', true) ) > 0
			         ) {
			         
                        $dc_namespace = $item->children('dc', true);
                        
                            if ( $dc_namespace->date != '' ) {
                            $item_date = $dc_namespace->date;
                            }
			         
			         }
			               
			         if ( !$item->link && $item->enclosure['url'] ) {
			         $item_link = $item->enclosure['url'];
			         }
			         elseif ( $item->link != '' ) {
			         $item_link = $item->link;
			         }
		                  
		         $date_array = date_parse($item_date);
		                  
		         $month_name = date("F", mktime(0, 0, 0, $date_array['month'], 10));
		                  
		         $date_ui = $month_name . ' ' . $ct['gen']->ordinal($date_array['day']) . ', ' . $date_array['year'] . ' @ ' . substr("0{$date_array['hour']}", -2) . ':' . substr("0{$date_array['minute']}", -2);
		                  
		                  
			         // If publish date is OVER 'news_feed_entries_new' days old, DONT mark as new
				     // With offset, to try to catch any that would have been missed from runtime
			         if ( $ct['var']->num_to_str($now_timestamp) > $ct['var']->num_to_str( strtotime($item_date) + ($ct['conf']['news']['mark_as_new'] * 86400) ) ) {
			         $mark_new = null;
			         }
				     // If running as $email_only, we only want 'new' posts anyway (less than 'news_feed_email_frequency' days old)
				     // With offset, to try to catch any that would have been missed from runtime
				     elseif ( $email_only && $ct['var']->num_to_str($now_timestamp) <= $ct['var']->num_to_str( strtotime($item_date) + ($ct['conf']['news']['news_feed_email_frequency'] * 86400) ) ) {
    			     
    				     if ($count < $ct['conf']['news']['news_feed_email_entries_include']) {
    				     $html .= '<li style="padding: 8px;"><a style="color: #00b6db;" href="'.htmlspecialchars($item_link).'" target="_blank" title="'.htmlspecialchars($date_ui).'">'.htmlspecialchars($item->title).'</a> </li>';
    				     }
    				     
			         $count++;   
			         
				     }
				     
				     
				     if ( !$email_only ) {
				         
    			         if ($count < $feed_size) {
    			         $html .= '<li class="links_list"><a href="'.htmlspecialchars($item_link).'" target="_blank" title="'.htmlspecialchars($date_ui).'">'.htmlspecialchars($item->title).'</a> '.$mark_new.'</li>';
    			         }
    			         else {
    			         $html_hidden .= '<li class="links_list"><a href="'.htmlspecialchars($item_link).'" target="_blank" title="'.htmlspecialchars($date_ui).'">'.htmlspecialchars($item->title).'</a> '.$mark_new.'</li>';
    			         }
		                        
		             $count++;     
    			         
				     }
		                        
		               
		         }
	               
	               
	          }
	             
	      }
             
      
      $rand_id = 'more_' . rand();
      $html .= '</ul>';
      $html_hidden .= '</ul>';
      $show_more_less = "<p><a id='".$rand_id."' href='javascript: show_more(\"".md5($url)."\", \"".$rand_id."\");' style='font-weight: bold;' title='Show more / less RSS feed entries.'>Show More</a></p>";
         
      }
      
      
   gc_collect_cycles(); // Clean memory cache
   
      
       if ( $feed_size == 0 || $cache_only ) {
       return true;
       }
       else {
	       
	       if ( !$email_only ) {
           return $html . "\n" . $show_more_less . "\n" . $html_hidden;
	       }
	       else {
	       return $html;
	       }
	       
       }
      
       
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // We only need $pair data if our function call needs 24hr trade volumes, so it's optional overhead
   function market($asset_symb, $sel_exchange, $mrkt_id, $pair=false) {
   
   global $ct;
   
   $sel_exchange = strtolower($sel_exchange);
   
   
     // Make sure the exchange key still exists
     if (
     !in_array($sel_exchange, $ct['dev']['special_asset_exchange_keys'])
     && !in_array( preg_replace("/_(.*)/i", "", $sel_exchange) , $this->prefixing_blacklist)
     && !isset($this->exchange_apis[$sel_exchange]) 
     ) {
          
     $try_exchange_key = preg_replace("/_(.*)/i", "", $sel_exchange);
      	
      	
      	if (
      	!in_array($try_exchange_key, $ct['dev']['special_asset_exchange_keys'])
      	&& !isset($this->exchange_apis[$try_exchange_key])
      	) {
     
          $ct['gen']->log(
      			'market_error',
      			'exchange "'.$sel_exchange.'" does NOT exist in the exchange APIs (you LIKELY have a stale asset configuration)'
      			);
      			
          return false;
          
          }
      	else {
      	$mapped_exchange_key = $try_exchange_key;
      	}		
      
      
     }
     else {
     $mapped_exchange_key = $sel_exchange;
     }

   
   // Get exchange's markets endpoint domain
   $tld_or_ip = $ct['gen']->get_tld_or_ip( $this->exchange_apis[$mapped_exchange_key]['markets_endpoint'] );
   
   // BEFORE GETTING MARKET DATA, see if we are currently throttled
   $api_is_throttled = $ct['cache']->api_is_throttled($tld_or_ip);
   
   
      // RUNTIME CACHING (to speed things up)
      if ( $sel_exchange == 'jupiter_ag' ) {
      
      $jup_market_id = explode('/', $mrkt_id);

          // IF we ALREADY runtime-cached price data for this jupiter asset AND pairing
          if (
          isset($ct['jup_ag_address_mapping'][ $jup_market_id[0] ])
          && isset($ct['jup_ag_address_mapping'][ $jup_market_id[1] ])
          ) {
          
          // RESET $data for jupiter
          $data = array();
          
          $data[ $jup_market_id[0] ] = $ct['jup_ag_runtime_cache'][ $ct['jup_ag_address_mapping'][ $jup_market_id[0] ] ];
          
          $data[ $jup_market_id[1] ] = $ct['jup_ag_runtime_cache'][ $ct['jup_ag_address_mapping'][ $jup_market_id[1] ] ];
          
          }
          
      
      }

      
      // Our data call, IF all jupiter conditions above were NOT applicable
      if ( !isset($data) ) {
      $data = $this->exchange_api_data($sel_exchange, $mrkt_id);
      }
   
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
      if ( $sel_exchange == 'presale_usd_value' ) {
      
      $result = array( // We ALWAYS force ID to lowercase in config-auto-adjust.php
                     'last_trade' => $ct['asset']->static_usd_price($sel_exchange, strtolower($mrkt_id) ),
                     '24hr_asset_vol' => null, // Unavailable, set null
                     '24hr_pair_vol' => null // Unavailable, set null
                	   );
      
      }
    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
      elseif ( $sel_exchange == 'aevo' || stristr( $sel_exchange , 'aevo_') ) {
           
           
           if (
           isset($data["markets"]["daily_volume_contracts"])
           && is_numeric($data["markets"]["daily_volume_contracts"])
           && $ct['var']->num_to_str($data["markets"]["daily_volume_contracts"]) > 0
           && isset($data["mark_price"])
           && is_numeric($data["mark_price"])
           && $ct['var']->num_to_str($data["mark_price"]) > 0
           ) {
           $temp_vol = $data["markets"]["daily_volume_contracts"] * $data["mark_price"];
           }
           else {
           $temp_vol = null; // Unavailable, set null
           }
      
      
      $result = array(
                     'last_trade' => $data["mark_price"],
                     '24hr_asset_vol' => null, // Unavailable, set null
                     '24hr_pair_vol' => $temp_vol
                	   );
      
      }
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
      elseif ( $sel_exchange == 'alphavantage_stock' ) {
	      
	 $result = array(
     	                         'alphavantage_asset' => preg_replace("/\.(.*)/i", "", $data["01. symbol"]),
	                              'last_trade' => $data["05. price"],
	                              '24hr_asset_vol' => null,
	                              '24hr_pair_vol' => $data["06. volume"]
	                     		    );
      
      
      }
    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( $sel_exchange == 'binance' ) {
           
      $result = array(
	                              'last_trade' => $data["lastPrice"],
	                              '24hr_asset_vol' => $data["volume"],
	                              '24hr_pair_vol' => $data["quoteVolume"]
	                     		    );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'binance_us' ) {
       
      $result = array(
	                              'last_trade' => $data["lastPrice"],
	                              '24hr_asset_vol' => $data["volume"],
	                              '24hr_pair_vol' => $data["quoteVolume"]
	                     			);
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( $sel_exchange == 'bit2c' ) {
      
      $result = array(
                     'last_trade' => $data["ll"],
                     '24hr_asset_vol' => $data["a"],
                     '24hr_pair_vol' => null // Unavailable, set null
                	   );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( $sel_exchange == 'bitbns' ) {
         
      $result = array(
	                              'last_trade' => $data["last_traded_price"],
	                              '24hr_asset_vol' => $data["volume"]["volume"],
	                              '24hr_pair_vol' => null // Unavailable, set null
	                    		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'bitfinex' ) {
      
      $finex_price = $data[( sizeof($data) - 4 )];
      
      $finex_vol = $data[( sizeof($data) - 3 )];
           
           
           // Bitfinex is a VERY funky data structure to parse for RESULTS VALIDITY,
           // so best way is to make sure the parsed data is NOT an array
           if ( !is_array($finex_price) && !is_array($finex_vol) ) {
           
           $result = array(
	                              'last_trade' => $finex_price,
	                              '24hr_asset_vol' => $finex_vol,
	                              '24hr_pair_vol' => null // Unavailable, set null
	                     		  );
           
           }
           else {
           
           $result = array(
	                              'last_trade' => 0,
	                              '24hr_asset_vol' => 0,
	                              '24hr_pair_vol' => null // Unavailable, set null
	                     		  );
           
           }
           
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( $sel_exchange == 'bitflyer' ) {
      
      $result = array(
                     'last_trade' => $data["ltp"],
                     '24hr_asset_vol' => $data["volume_by_product"],
                     '24hr_pair_vol' => null // Seems to be an EXACT duplicate of asset volume in MANY cases, skipping to be safe
               	     );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( $sel_exchange == 'bitmart' ) {
         
      $result = array(
	                              'last_trade' => $data["last_price"],
	                              '24hr_asset_vol' => $data["base_volume_24h"],
	                              '24hr_pair_vol' => $data["quote_volume_24h"]
	                    		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'bitmex' || $sel_exchange == 'bitmex_u20' || $sel_exchange == 'bitmex_z20' ) {
      
       
	        foreach ($data as $hourly_data) {
	                
			         
			         if ( isset($hourly_data['symbol']) && $hourly_data['symbol'] == $mrkt_id ) {
			              
			         // We only want the FIRST data set for trade value
			         $last_trade = ( !$last_trade ? $hourly_data['close'] : $last_trade );

			         $asset_vol = $ct['var']->num_to_str($asset_vol + $hourly_data['homeNotional']);
			         $pair_vol = $ct['var']->num_to_str($pair_vol + $hourly_data['foreignNotional']);
			                 
			         // Average of 24 hours, since we are always between 23.5 and 24.5
			         // (least resource-intensive way to get close enough to actual 24 hour volume,
			         // overwrites until it's the last values)
			         $half_oldest_hour_asset_vol = round($hourly_data['homeNotional'] / 2);
			         $half_oldest_hour_pair_vol = round($hourly_data['foreignNotional'] / 2);
			                 
			         }
	              
	        }
	          
	          
	  $result = array(
	                           'last_trade' => $last_trade,
	                           // Average of 24 hours, since we are always between 23.5 and 24.5
	                           // (least resource-intensive way to get close enough to actual 24 hour volume)
	                           '24hr_asset_vol' => ($asset_vol - $half_oldest_hour_asset_vol),
	                           '24hr_pair_vol' =>  ($pair_vol - $half_oldest_hour_pair_vol)
	                    	   );
      
      }
      
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( $sel_exchange == 'bitso' ) {
      
      $result = array(
                     'last_trade' => $data["last"],
                     '24hr_asset_vol' => $data["volume"],
                     '24hr_pair_vol' => null // Unavailable, set null
               	    );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'bitstamp' ) {
        
      $result = array(
                     'last_trade' => number_format( $data['last'], $ct['conf']['currency']['crypto_decimals_max'], '.', ''),
                     '24hr_asset_vol' => $data["volume"],
                     '24hr_pair_vol' => null // Unavailable, set null
      	           );
        
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'btcmarkets' ) {
    
      $result = array(
                     'last_trade' => $data['lastPrice'],
                     '24hr_asset_vol' => $data["volume24h"],
                     '24hr_pair_vol' => null // Unavailable, set null
                  	 );
       
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'btcturk' ) {
           
      $result = array(
	                              'last_trade' => $data["last"],
	                              '24hr_asset_vol' => $data["volume"],
	                              '24hr_pair_vol' => null // Unavailable, set null
	                    		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'buyucoin' ) {
         
      $result = array(
	                              'last_trade' => $data["LTRate"],
	                              '24hr_asset_vol' => $data["v24"], 
	                              '24hr_pair_vol' => $data["tp24"] 
	                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'bybit' ) {
      
      // If FLAGGED AS A '1000XXXXX' BYBIT MARKET ID, DIVIDE BY 1000
      $last_trade = ( stristr($mrkt_id, '1000') == true ? ($data["last_price"] / 1000) : $data["last_price"] );
             
	 $result = array(             
	                              'last_trade' => number_format( $last_trade, $ct['conf']['currency']['crypto_decimals_max'], '.', ''),
	                              '24hr_asset_vol' => 0, // Unavailable, set 0 to avoid 'price_alert_block_volume_error' suppression
	                              '24hr_pair_vol' => $data["volume_24h"] 
	                     		  );
	                     		  
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'cex' ) {
         
      $result = array(
	                              'last_trade' => $data["last"],
	                              '24hr_asset_vol' => $data["volume"],
	                              '24hr_pair_vol' => null // Unavailable, set null
	                     	       );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( $sel_exchange == 'coinbase' ) {
    
      $result = array(
                     'last_trade' => $data['price'],
                     '24hr_asset_vol' => $data["volume"],
                     '24hr_pair_vol' => null // Unavailable, set null
                  	 );
       
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'coindcx' ) {
         
      $result = array(
	                              'last_trade' => $data["last_price"],
	                              '24hr_asset_vol' => 0, // Unavailable, set 0 to avoid 'price_alert_block_volume_error' suppression
	                              '24hr_pair_vol' => $data["volume"]
	                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'coinex' ) {
      
	 $result = array(
	                              'last_trade' => $data["last"],
	                              '24hr_asset_vol' => $data["vol"],
	                              '24hr_pair_vol' => null // Unavailable, set null
	                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( stristr( $sel_exchange , 'coingecko_') ) {
     
      $coingecko_route = explode('_', $sel_exchange );
      $coingecko_route = strtolower($coingecko_route[1]);
      
      //$ct['gen']->array_debugging($data, true); // DEBUGGING ONLY
      
           
           // Use international tickers for data processing
           if ( $coingecko_route == 'rmb' ) {
           $coingecko_route = 'cny';
           }
           elseif ( $coingecko_route == 'nis' ) {
           $coingecko_route = 'ils';
           }
           
           
           // Coingecko terminal ( https://www.geckoterminal.com/dex-api )
           // Use data from coingecko, if API attributes exist
           if ( $coingecko_route == 'terminal' && isset($data['attributes']) ) {
                   
                   
                   if (  isset($data['attributes']['name']) ) {
                   $market_params = array_map( "trim", explode("/", $data['attributes']['name']) );
                   }

     
     	 $result = array(       
     	                        'coingecko_terminal_asset' => $market_params[0],
     	                        'last_trade' => $data['attributes']['base_token_price_usd'],
     	                        '24hr_asset_vol' => 0, // Unavailable, set 0 to avoid 'price_alert_block_volume_error' suppression
     	                        '24hr_pair_vol' => $data['attributes']['volume_usd']['h24']
     	                        );
           
           }
           // Use data from coingecko, if API ID / base currency exists
           elseif ( isset($data[$coingecko_route]) ) {
    
           $result = array(
     	                        'last_trade' => $data[$coingecko_route],
     	                        '24hr_asset_vol' => 0, // Unavailable, set 0 to avoid 'price_alert_block_volume_error' suppression
     	                        '24hr_pair_vol' => $data[$coingecko_route . "_24h_vol"]
     	                        );
           
           }
           else {
                
           //var_dump($ct['coingecko_currencies']);
           

               // For UX, we don't want "check your markets" user alerts,
               // IF IT'S JUST AN ASSET SEARCH (BEFORE EVEN ADDING AS A TRACKED MARKET)
               if (
               !$ct['ticker_markets_search']
               && $coingecko_route != 'terminal'
               && sizeof($ct['coingecko_currencies']) > 0
               && !in_array($coingecko_route, $ct['coingecko_currencies'])
               ) {
                     
               $ct['gen']->log(
                   		    'notify_error',
                   		    'coingecko does NOT support the currency "' . $coingecko_route . '". please REMOVE the "'.$asset_symb.' / '.$coingecko_route.'" coingecko-based market, to avoid seeing this message',
                   		    false,
                   		    'no_market_data_' . $sel_exchange
                   		    );
                   		    
               }
               elseif (
               !$ct['ticker_markets_search']
               && $coingecko_route != 'terminal'
               && sizeof($ct['coingecko_currencies']) == 0
               ) {
                     
               $ct['gen']->log(
                   		    'notify_error',
                   		    'the coingecko currency list API returned NO DATA, please try reloading / refreshing this app to fix this',
                   		    false,
                   		    'no_currency_data_coingecko'
                   		    );
                   		    
               }
               

           }
           
	     
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'coinspot' ) {
         
      $result = array(
	                              'last_trade' => $data["last"],
	                              '24hr_asset_vol' => 0, // Unavailable, set 0 to avoid 'price_alert_block_volume_error' suppression
	                              '24hr_pair_vol' => null // Unavailable, set null
	                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'crypto.com' ) {
         
      $result = array(
	                              'last_trade' => $data["a"],
	                              '24hr_asset_vol' => $data["v"],
	                              '24hr_pair_vol' => null // Unavailable, set null
	                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'gateio' ) {
      
      $result = array(
                              'last_trade' => $data["last"],
                              '24hr_asset_vol' => $data["base_volume"],
                              '24hr_pair_vol' => $data["quote_volume"]
                              );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'gateio_usdt_futures' ) {
      
      $result = array(
                              'last_trade' => $data["last_price"],
                              '24hr_asset_vol' => null, // Unavailable, set null
                              '24hr_pair_vol' => null // Unavailable, set null
                              );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( $sel_exchange == 'gemini' ) {
        
      $result = array(
                     'last_trade' => $data['last'],
                     '24hr_asset_vol' => $data['volume'][strtoupper($asset_symb)],
                     '24hr_pair_vol' => $data['volume'][strtoupper($pair)]
      	              );
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'hitbtc' ) {
      
      $result = array(
                              'last_trade' => $data["last"],
                              '24hr_asset_vol' => $data["volume"],
                              '24hr_pair_vol' => $data["volumeQuote"]
                              );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'huobi' ) {
         
      $result = array(
                              'last_trade' => $data["close"],
                              '24hr_asset_vol' => $data["amount"],
                              '24hr_pair_vol' => $data["vol"]
                              );
      
      }
     
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'kuma' ) {
      
      $result = array(
                              'last_trade' => $data["close"],
                              // ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
                              '24hr_asset_vol' => $data["quoteVolume"],
                              '24hr_pair_vol' => $data["baseVolume"]
                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'jupiter_ag' ) {
      
      // Migrate v2 price API (depreciating 2025/Aug/1st) to v3, WHILE CALCULATING PAIRING VAL IN-APP (for UX)
      $emulate_jup_pairing_price = explode('/', $mrkt_id);
      
      $asset_data = $data[ $emulate_jup_pairing_price[0] ];
      
      $pair_data = $data[ $emulate_jup_pairing_price[1] ];
           
           
           // DERIVE PAIRING PRICE
           if (
           isset($asset_data['usdPrice'])
           && $ct['var']->num_to_str($asset_data['usdPrice']) > 0.000000000000000000000000000000000000000
           && isset($pair_data['usdPrice'])
           && $ct['var']->num_to_str($pair_data['usdPrice']) > 0.000000000000000000000000000000000000000
           ) {
                
           $asset_price = $ct['var']->num_to_str($asset_data['usdPrice'] / $pair_data['usdPrice']);

           $result = array(        
                              'jup_ag_address' => $emulate_jup_pairing_price[0],
                              'last_trade' => number_format($asset_price, $ct['conf']['currency']['crypto_decimals_max'], '.', ''),
                              '24hr_asset_vol' => 0, // Unavailable, set 0 to avoid 'price_alert_block_volume_error' suppression
                              '24hr_pair_vol' => null // Unavailable, set null
                    	      );
                    	      
           }

        
      }
     
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'korbit' ) {
      
      $result = array(
                              'last_trade' => $data["last"],
                              '24hr_asset_vol' => $data["volume"],
                              '24hr_pair_vol' => null // Unavailable, set null
                    	      );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( $sel_exchange == 'kraken' ) {
           
      $result = array(
                                 'last_trade' => $data["c"][0],
                                 '24hr_asset_vol' => $data["v"][1],
                                 '24hr_pair_vol' => null // Unavailable, set null
                       		     );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'kucoin' ) {
      
      $result = array(
                              'last_trade' => $data["last"],
                              '24hr_asset_vol' => $data["vol"],
                              '24hr_pair_vol' => $data["volValue"]
                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
     // https://github.com/Loopring/protocols/wiki/Loopring-Exchange-Data-API
     
      elseif ( $sel_exchange == 'loopring' || $sel_exchange == 'loopring_amm' ) {
           
	 $result = array(
	                              'last_trade' => $data["last_price"],
	                              '24hr_asset_vol' => $data["base_volume"],
	                              '24hr_pair_vol' => $data["quote_volume"]
	                     	       );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'luno' ) {
      
      $result = array(
                              'last_trade' => $data["last_trade"],
                              '24hr_asset_vol' => $data["rolling_24_hour_volume"],
                              '24hr_pair_vol' => null // Unavailable, set null
                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'okcoin' ) {
      
      $result = array(
                              'last_trade' => $data['last'],
                              '24hr_asset_vol' => $data['vol24h'],
                              '24hr_pair_vol' => $data['volCcy24h']
                              );
        
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( $sel_exchange == 'okex' || $sel_exchange == 'okex_perps' ) {
       
      $result = array(
                              'last_trade' => $data["last"],
                              '24hr_asset_vol' => 0, // Unavailable, set 0 to avoid 'price_alert_block_volume_error' suppression
                              '24hr_pair_vol' => $data['volCcy24h']
                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( $sel_exchange == 'poloniex' ) {
      
      $result = array(
                              'last_trade' => $data["markPrice"],
                              // ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
                              '24hr_asset_vol' => $data["quantity"],
                              '24hr_pair_vol' => $data["amount"]
                     	     );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
      
      elseif ( $sel_exchange == 'tradeogre' ) {
      
      
         foreach ($data as $val) {
              
              if ( isset($val[$mrkt_id]) && $val[$mrkt_id] != '' ) {
               
              $result = array(
                              'last_trade' => $val[$mrkt_id]["price"],
                              '24hr_asset_vol' => 0, // Unavailable, set 0 to avoid 'price_alert_block_volume_error' suppression
                              '24hr_pair_vol' => $val[$mrkt_id]["volume"]
                     		  );
               
              }
          
         }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'unocoin' ) {
         
      $result = array(
	                              'last_trade' => $data["average_price"],
	                              '24hr_asset_vol' => 0, // Unavailable, set 0 to avoid 'price_alert_block_volume_error' suppression
	                              '24hr_pair_vol' => null // Unavailable, set null
	                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'upbit' ) {
      
      $result = array(
                              'last_trade' => $data["trade_price"],
                              '24hr_asset_vol' => $data["acc_trade_volume_24h"],
                              '24hr_pair_vol' => null // No 24 hour trade volume going by array keynames, skipping
                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'wazirx' ) {
      
      $result = array(
                              'last_trade' => $data["last"],
                              '24hr_asset_vol' => $data["volume"],
                              '24hr_pair_vol' => null // Unavailable, set null
                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'zebpay' ) {
      
      $result = array(
                                  'last_trade' => $data["market"],
                                  '24hr_asset_vol' => $data["volume"],
                                  '24hr_pair_vol' => null // Unavailable, set null
                         		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'misc_assets' || $sel_exchange == 'alt_nfts' ) {
      
          
         // BTC value of 1 unit of the default primary currency
         if ( $ct['sel_opt']['sel_btc_prim_currency_val'] > 0 ) {
         $currency_to_btc = $ct['var']->num_to_str(1 / $ct['sel_opt']['sel_btc_prim_currency_val']);	
         }
         // Cannot be determined, setting to zero
         else {
         $currency_to_btc = 0;
         }
      
      
         // BTC pair
         if ( $mrkt_id == 'btc' ) {
         $result = array(
     		            'last_trade' => $currency_to_btc
     		            );
         }
         // All other pair
     	 else {
     		        
         $pair_btc_val = $ct['asset']->pair_btc_val($mrkt_id);
     		      
     		      
          	if ( $pair_btc_val == null ) {
          				          	
          	$ct['gen']->log(
          				'market_error',
          				'ct_asset->pair_btc_val() returned null',
          				'market_id: ' . $mrkt_id
          				);
          				          
            }
     		      
           
            if ( $ct['var']->num_to_str($pair_btc_val) > 0 && $ct['var']->num_to_str($currency_to_btc) > 0 ) {
            $calc = ( 1 / $ct['var']->num_to_str($pair_btc_val / $currency_to_btc) );
            }
            else {
            $calc = 0;
            }     		      
     
     			      
         $result = array(
     		            'last_trade' => $calc
     		            );
     		                  		
         }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'btc_nfts' ) {
      
      // BTC value of 1 unit of BTC
      $currency_to_btc = 1;	
      
         // BTC pair
         if ( $mrkt_id == 'btc' ) {
         $result = array(
     		            'last_trade' => $currency_to_btc
     		            );
         }
         // All other pair
         else {
     		        
         $pair_btc_val = $ct['asset']->pair_btc_val($mrkt_id);
     		      
     		      
          	if ( $pair_btc_val == null ) {
          				          	
          	$ct['gen']->log(
          				'market_error',
          				'ct_asset->pair_btc_val() returned null',
          				'market_id: ' . $mrkt_id
          				);
          				          
            }
     		      
           
            if ( $ct['var']->num_to_str($pair_btc_val) > 0 && $ct['var']->num_to_str($currency_to_btc) > 0 ) {
            $calc = ( 1 / $ct['var']->num_to_str($pair_btc_val / $currency_to_btc) );
            }
            else {
            $calc = 0;
            }     		      
     
     			      
         $result = array(
     		            'last_trade' => $calc
     		            );
     		                  		
         }
      
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'eth_nfts' ) {
      
      // BTC value of 1 unit of ETH
      $currency_to_btc = $ct['asset']->pair_btc_val('eth');	
      
         // BTC pair
         if ( $mrkt_id == 'btc' ) {
         $result = array(
     		            'last_trade' => $currency_to_btc
     		            );
         }
         // All other pair
         else {
     		        
         $pair_btc_val = $ct['asset']->pair_btc_val($mrkt_id);
     		      
     		      
          	if ( $pair_btc_val == null ) {
          				          	
          	$ct['gen']->log(
          				'market_error',
          				'ct_asset->pair_btc_val() returned null',
          				'market_id: ' . $mrkt_id
          				);
          				          
            }
     		      
           
            if ( $ct['var']->num_to_str($pair_btc_val) > 0 && $ct['var']->num_to_str($currency_to_btc) > 0 ) {
            $calc = ( 1 / $ct['var']->num_to_str($pair_btc_val / $currency_to_btc) );
            }
            else {
            $calc = 0;
            }     		      
     
     			      
         $result = array(
     		            'last_trade' => $calc
     		            );
     		                  		
         }
      
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'sol_nfts' ) {
      
      // BTC value of 1 unit of SOL
      $currency_to_btc = $ct['asset']->pair_btc_val('sol');	
      
         // BTC pair
         if ( $mrkt_id == 'btc' ) {
         $result = array(
     		            'last_trade' => $currency_to_btc
     		            );
         }
         // All other pair
         else {
     		        
         $pair_btc_val = $ct['asset']->pair_btc_val($mrkt_id);
     		      
     		      
            if ( $pair_btc_val == null ) {
          				          	
          	$ct['gen']->log(
          				'market_error',
          				'ct_asset->pair_btc_val() returned null',
          				'market_id: ' . $mrkt_id
          				);
          				          
            }
     		      
           
            if ( $ct['var']->num_to_str($pair_btc_val) > 0 && $ct['var']->num_to_str($currency_to_btc) > 0 ) {
            $calc = ( 1 / $ct['var']->num_to_str($pair_btc_val / $currency_to_btc) );
            }
            else {
            $calc = 0;
            }     		      
     
     			      
         $result = array(
     		            'last_trade' => $calc
     		            );
     		                  		
         }
      
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
      if ( $sel_exchange != 'misc_assets' && $sel_exchange != 'btc_nfts' && $sel_exchange != 'eth_nfts' && $sel_exchange != 'sol_nfts' && $sel_exchange != 'alt_nfts' ) {
        
      // Better large / small number support
      $result['last_trade'] = $ct['var']->num_to_str($result['last_trade']);
      
      
    		if ( is_numeric($result['24hr_asset_vol']) ) {
          $result['24hr_asset_vol'] = $ct['var']->num_to_str($result['24hr_asset_vol']); // Better large / small number support
    		}
        
        
            // SET FIRST...emulate pair volume if non-existent
            // If no pair volume is available for this market, emulate it within reason with: asset value * asset volume
    		if ( is_numeric($result['24hr_pair_vol']) != true && is_numeric($result['last_trade']) == true && is_numeric($result['24hr_asset_vol']) == true ) {
          $result['24hr_pair_vol'] = $ct['var']->num_to_str($result['last_trade'] * $result['24hr_asset_vol']);
    		}
    		elseif ( is_numeric($result['24hr_pair_vol']) ) {
          $result['24hr_pair_vol'] = $ct['var']->num_to_str($result['24hr_pair_vol']); // Better large / small number support
    		}
		      
		      
    		// Set primary currency volume value...
    		
    		// JUP AG MUST BE FIRST CONDITION
    		if ( isset($result['jup_ag_address']) ) {
    		     
    		     
    		     // RUNTIME CACHING
    		     if ( isset($ct['jup_ag_address_mapping'][ $result['jup_ag_address'] ]) ) {
    		     $data = $ct['jup_ag_runtime_cache'][ $ct['jup_ag_address_mapping'][ $result['jup_ag_address'] ] ];
    		     }
    		     else {
    		
         		// https://dev.jup.ag/docs/token-api/v2
               $response = @$ct['cache']->ext_data('url', 'https://lite-api.jup.ag/tokens/v2/search?query=' . $result['jup_ag_address'], 45); // 45 minute cache
                 
               $data = json_decode($response, true);
               
               $data = $data[0];
               
               $ct['jup_ag_runtime_cache'][ $data['symbol'] ] = $data;
               
               $ct['jup_ag_address_mapping'][ $result['jup_ag_address'] ] = $data['symbol'];
    		     
    		     }

          
               if ( $ct['var']->num_to_str($data['stats24h']['buyVolume']) > 0 ) {
               $result['24hr_prim_currency_vol'] = $ct['var']->num_to_str( $ct['asset']->prim_currency_trade_vol($asset_symb, 'usd', $result['last_trade'], $data['stats24h']['buyVolume']) );
               $result['24hr_usd_vol'] = $ct['var']->num_to_str($data['stats24h']['buyVolume']);
               }

    		
    		}
    		elseif ( isset($result['24hr_pair_vol']) && isset($pair) && $pair == $ct['conf']['currency']['bitcoin_primary_currency_pair'] ) {
    		$result['24hr_prim_currency_vol'] = $ct['var']->num_to_str($result['24hr_pair_vol']); // Save on runtime, if we don't need to compute the fiat value
    		}
    		elseif ( isset($result['24hr_pair_vol']) && isset($pair) ) {
    		$result['24hr_prim_currency_vol'] = $ct['var']->num_to_str( $ct['asset']->prim_currency_trade_vol($asset_symb, $pair, $result['last_trade'], $result['24hr_pair_vol']) );
    		}
    		else {
    		$result['24hr_prim_currency_vol'] = null;
    		}
        
      
      }
      
      
      // Log if last trade value is under the minimum crypto value set in the config
      if ( isset($result['last_trade']) && $result['last_trade'] < $ct['min_crypto_val_test'] ) {
      
      $ct['gen']->log(
                   		    'notify_error',
                   		    'the '.$asset_symb.' trade value "'.$result['last_trade'].'" for the "' . $sel_exchange . '" exchange market ID "'.$mrkt_id.'" is LESS THAN THE ALLOWED "'.$ct['min_crypto_val_test'].'" VALUE (adjustable in: Admin Area => Asset Tracking => Currency Support => Crypto Decimals Maximum)',
                   		    false,
                   		    'low_market_value_' . $mrkt_id
                   		    );
                   		    
      }
                
                
      // For UX, we don't want "check your markets" user alerts,
      // IF IT'S JUST AN ASSET SEARCH (BEFORE EVEN ADDING AS A TRACKED MARKET)
      // ONLY FLAG A MARKET DATA ALERT, IF WE ARE NOT THROTTLING IN THE APP!
      if (
      !$api_is_throttled && !$ct['ticker_markets_search'] && !isset($result['last_trade'])
      || !$api_is_throttled && !$ct['ticker_markets_search'] && isset($result['last_trade']) && !is_numeric($result['last_trade'])
      ) {
           
      $invalid_last_trade = true;
                                    
      $ct['gen']->log(
                       'notify_error',
                       'the trade value of "'.$result['last_trade'].'" seems invalid for market ID "'.$mrkt_id.'". IF THIS MESSAGE PERSISTS IN THE FUTURE, make sure your markets for the "' . $sel_exchange . '" exchange are up-to-date (exchange APIs can go temporarily / permanently offline, OR have markets permanently removed / offline temporarily for maintenance [review their API status page / currently-available markets])',
                       false,
                       'no_market_data_' . $sel_exchange
                       );
                     
          
                   		    
      }


      // Track market data failure
      if ( $invalid_last_trade && $ct['conf']['comms']['market_error_alert_channels'] != 'off' ) {
      
      // Safe filename characters
      $market_error_cache_path = $ct['base_dir'] . '/cache/events/market_error_tracking/' . $ct['gen']->safe_name($sel_exchange . '_' . $asset_symb . '_' . $mrkt_id) . '.dat';
           
               
               // Get any existing count, OR set to zero
               if ( file_exists($market_error_cache_path) ) {
               $market_error_count = trim( file_get_contents($market_error_cache_path) );
               }
               else {
               $market_error_count = 0;
               }
           
               
               // ONLY UP COUNTS if it's been at least 1 day since the last error,
               // BUT less than 2 days since the last error (IN MINUTES)
               // (WE ONLY WANT TO TRACK WHEN CONSECUTIVE DAILY ERRORS HAPPEN!)
               if (
               $ct['cache']->update_cache($market_error_cache_path, 1440) == true
               && $ct['cache']->update_cache($market_error_cache_path, 2880) != true
               ) {
               $market_error_count = $market_error_count + 1;
               }
               // Otherwise, RESET count to 1 (to only count this error)
               else {
               $market_error_count = 1;
               }
               
               
               // IF we have reached the threshold to send an alert for this market
               if ( $market_error_count >= $ct['conf']['comms']['market_error_threshold'] ) {
                    
               $market_error_msg = 'There have been ' . $ct['conf']['comms']['market_error_threshold'] . ' days of DAILY market errors for the following market:' . "\n" . $asset_symb . ' @ ' . $ct['gen']->key_to_name($sel_exchange) . ' (market ID: '.$mrkt_id.')' . "\n\n" .  '(you have per-market error alerts triggered every '.$ct['conf']['comms']['market_error_threshold'].' days, in the communication settings)';
			
			$notifyme_msg = $market_error_msg . ' Timestamp: ' . $ct['gen']->time_date_format($ct['conf']['gen']['local_time_offset'], 'pretty_time') . '.';
			
               $market_error_txt = $ct['conf']['comms']['market_error_threshold'] . ' days of DAILY market errors for: ' . $asset_symb . ' @ ' . $ct['gen']->key_to_name($sel_exchange) . ' (market ID: '.$mrkt_id.')';
	
        	     // Minimize function calls
        	     $text_alert = $ct['gen']->detect_unicode($market_error_txt); 
			
        	     $market_error_send_params = array(
                                    			'notifyme' => $notifyme_msg,
                                    			'telegram' => $market_error_msg,
                                    			'text' => array(
                                    			               'message' => $text_alert['content'],
                                    			               'charset' => $text_alert['charset']
                                    			               ),
                                    			'email' => array(
                                                    			'subject' => 'Market Error Alert',
                                                    			'message' => $market_error_msg
                                                    			)
                                    			);
				
		    
		     // Only send to comm channels the user prefers, based off the config setting $ct['conf']['comms']['market_error_alert_channels']
		     $preferred_comms = $ct['gen']->preferred_comms($ct['conf']['comms']['market_error_alert_channels'], $market_error_send_params);
			
			// Queue notifications
			@$ct['cache']->queue_notify($preferred_comms);
			
			// RESET count to zero, since we just sent alerts
			$market_error_count = 0;

               }
           
                     
      // Update the market error tracking count
      $ct['cache']->save_file($market_error_cache_path, $market_error_count);
           
      }
   
   
   gc_collect_cycles(); // Clean memory cache
   
   return $result;
   
   }
   

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
      
   
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>