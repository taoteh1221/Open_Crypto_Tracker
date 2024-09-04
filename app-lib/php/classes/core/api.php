<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


class ct_api {
	
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;


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


                           'ethfinex' => array(
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
                                                   'markets_endpoint' => 'https://api.pro.coinbase.com/products/[MARKET]/ticker',
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


                           'gemini' => array(
                                                   'markets_endpoint' => 'https://api.gemini.com/v1/pubticker/[MARKET]',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'graviex' => array(
                                                   'markets_endpoint' => 'https://graviex.net//api/v2/tickers.json',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
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


                           'idex' => array(
                                                   'markets_endpoint' => 'https://api-sandbox.idex.io/v4/tickers',
                                                   'markets_nested_path' => false, // Delimit multiple depths with >
                                                   'all_markets_support' => 'market', // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => false, // false|[API endpoint with all market pairings]
                                                  ),

                           
                           'jupiter_ag' => array(
                                                   'markets_endpoint' => 'https://price.jup.ag/v6/price?ids=[JUP_AG_ASSETS]&vsToken=[JUP_AG_PAIRING]',
                                                   'markets_nested_path' => 'data', // Delimit multiple depths with >
                                                   'all_markets_support' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'search_endpoint' => 'https://tokens.jup.ag/tokens?tags=[JUP_AG_TAGS]', // false|[API endpoint with all market pairings]
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
   
   
   function bitcoin($request) {
    
   global $ct;
         
   $url = 'https://blockchain.info/q/' . $request;
         
   $response = @$ct['cache']->ext_data('url', $url, $ct['conf']['power']['blockchain_stats_cache_time']);
       
   return (float)$response;
     
   }
   
         
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function exchange_api_data($selected_exchange, $market_id, $ticker_search_mode=false) {
   
   global $ct;
   
   $selected_exchange = strtolower($selected_exchange);
   
   $prefixing_blacklist = array(
                             'binance', // Because 'binance_us' is a separate API
                             'coingecko', // Because 'coingecko_terminal' is a separate API
                            );
   
      // IF exchange API exists
      if ( isset($this->exchange_apis[$selected_exchange]) ) {
      return $this->fetch_exchange_data($selected_exchange, $market_id, $ticker_search_mode);
      }
      // IF exchange API doesn't exist, check to see if we are using our prefix delimiter, for a possible 'prefixed' exchange name
      // (for end-user descriptiveness / UX, BUT ONLY IF NOT A BLACKLISTED PREFIX!)
      elseif ( !in_array($selected_exchange, $prefixing_blacklist) && stristr($selected_exchange, '_') ) {
        
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
		
		
   ////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
		
		
   function solana_rpc($method, $params=false, $cache_time=0, $rpc_test=false) {
		 
   global $ct;

                                
        if ( $rpc_test != false ) {
        $rpc_server = $rpc_test;
        }
        else {    
        $rpc_server = $ct['conf']['ext_apis']['solana_rpc_server'];
        }

	
   $headers = array(
                    'Content-Type: application/json'
                    );
               
   $request_params = array(
                           'jsonrpc' => '2.0', // Setting this right before sending
                           'id' => 1,
                           'method' => $method,
                          );
                    
                    
        if ( $params ) {
        $request_params['params'] = $params;
        }

     
   // https://solana.com/docs/core/clusters#mainnet-beta-rate-limits
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
   
      if ( trim($ct['conf']['ext_apis']['etherscan_api_key']) == '' ) {
      return false;
      }
   
   $url = 'https://api.etherscan.io/api?module=proxy&action=eth_blockNumber&apikey=' . $ct['conf']['ext_apis']['etherscan_api_key'];
     
   $response = @$ct['cache']->ext_data('url', $url, $ct['conf']['power']['blockchain_stats_cache_time']);
       
   $data = json_decode($response, true);
     
   $block_number = $data['result'];
       
       
      if ( !$block_number ) {
      return;
      }
      else {
            
          // Non-dynamic cache file name, because filename would change every recache and create cache bloat
          if ( $ct['cache']->update_cache('cache/secured/external_data/eth-stats.dat', $ct['conf']['power']['blockchain_stats_cache_time'] ) == true ) {
            
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
   
   $data = array();
   $sub_arrays = array();
   $result = array();
   
   // Don't overwrite global
   $coingecko_prim_currency = ( $force_prim_currency != null ? strtolower($force_prim_currency) : strtolower($ct['conf']['gen']['bitcoin_primary_currency_pair']) );
   
         
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
            
              // Wait 6.55 seconds between consecutive calls, to avoid being blocked / throttled by external server
              // (coingecko #ABSOLUTELY HATES# DATA CENTER IPS [DEDICATED / VPS SERVERS], BUT GOES EASY ON RESIDENTIAL IPS)
              if ( $loop > 0 && $ct['cache']->update_cache($ct['base_dir'] . '/cache/secured/external_data/' . md5($url) . '.dat', $ct['conf']['power']['marketcap_cache_time']) == true ) {
              sleep(6);
              usleep(550000); 
              }
         
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
            
              if ( isset($data[$key]['symbol']) && $data[$key]['symbol'] != '' ) {
              $result[strtolower($data[$key]['symbol'])] = $data[$key];
              }
       
          }
        
      }
           
           
   gc_collect_cycles(); // Clean memory cache
   
   return $result;
     
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
     	                        'last_trade' => $market_data[$specific_pairing],
     	                        '24hr_pair_vol' => $market_data[$specific_pairing . "_24h_vol"]
     	                        );
             
             // We still need to parse out 'flagged_market'
             // Minimize calls
             $market_id_parse = $this->market_id_parse('coingecko', $app_id, $specific_pairing, $coingecko_asset_data['symbol']);
                                                
             $results[] = array(
                                                                           'name' => $cg_name,
                                                                           'mcap_slug' =>  $app_id,
                                                                           'id' =>  $app_id,
                                                                           'asset' => $market_id_parse['asset'],
                                                                           'pairing' => $market_id_parse['pairing'],
                                                                           'flagged_market' => $market_id_parse['flagged_market'],
                                                                           'data' => $parsed_market_data,
                                                                          );
             }
             else {
                          
             $coingecko_pairings_search_array = array_map( "trim", explode(",", $ct['coingecko_pairs']) );
   
      
                  foreach( $coingecko_pairings_search_array as $pair ) {
                                
                  $parsed_market_data = array(
     	                        'last_trade' => $market_data[$pair],
     	                        '24hr_pair_vol' => $market_data[$pair . "_24h_vol"]
     	                        );
                                                  
                  // We still need to parse out 'flagged_market'
                  // Minimize calls
                  $market_id_parse = $this->market_id_parse('coingecko', $app_id, $pair, $coingecko_asset_data['symbol']);
                                                
                  $results[] = array(
                                                                           'name' => $cg_name,
                                                                           'mcap_slug' =>  $app_id,
                                                                           'id' =>  $app_id,
                                                                           'asset' => $market_id_parse['asset'],
                                                                           'pairing' => $market_id_parse['pairing'],
                                                                           'flagged_market' => $market_id_parse['flagged_market'],
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
   
   $result = array();
   
   $coinmarketcap_currencies = array();
   
   
      if ( trim($ct['conf']['ext_apis']['coinmarketcap_api_key']) == null ) {
      	
      $ct['gen']->log(
      		    'notify_error',
      		    '"coinmarketcap_api_key" (free API key) is not configured in Admin Config EXTERNAL APIS section',
      		    false,
      		    'coinmarketcap_api_key'
      		    );
      
      return false;
      
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
   $coinmarketcap_prim_currency = strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']);
      
      
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
        
      gc_collect_cycles(); // Clean memory cache
      
      return $result;
      
      }
   
           
   }
   

   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // The passed $ticker_search parameter can be a ticker by itself like 'sol', OR INCLUDE A PAIRING like 'sol/btc'
   
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_id_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_id_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
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
                    $market_id_parse  = $this->market_id_parse('presale_usd_value', $key, 'usd', $key);
               
                    $results['presale_usd_value'][] = array(
                                                            'name' => strtoupper($key),
                                                            'id' => $key,
                                                            'asset' => $key,
                                                            'pairing' => 'usd',
                                                            'flagged_market' => $market_id_parse['flagged_market'],
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
          
          
          // Exchange-specific
          if ( $specific_exchange == 'coingecko' ) {
          $exchange_check = $specific_exchange . '_usd';
          $ticker_check = $ticker_only;
          }
          elseif ( $specific_exchange == 'jupiter_ag' ) {
               
               if ( $included_pairing ) {
               $ticker_check = $ticker_search;
               }
               else {
               $ticker_check = $ticker_search . '/' . ( $ticker_search == 'SOL' ? 'WBTC' : 'SOL' );
               }

          }

               
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
                    
                    
             // Minimize calls
               $market_id_parse = $this->market_id_parse($specific_exchange, $ticker_check, $set_pairing, $set_asset);
                              
               $results[$specific_exchange][] = array(
                                                  'name' => strtoupper($market_id_parse['asset']),
                                                  'id' => $ticker_check,
                                                  'asset' => $market_id_parse['asset'],
                                                  'pairing' => $market_id_parse['pairing'],
                                                  'flagged_market' => $market_id_parse['flagged_market'],
                                                  'data' => $check_market_data,
                                                  );
               
               }
       
       
          gc_collect_cycles(); // Clean memory cache
            
          return $results;
          
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
                        
                        
                             $run_already = false;
                             foreach ( $pairing_array as $pairing_val ) {
     
                                  
                                  if ( $run_already ) {
                                  sleep(1); // Throttle multiple requests, to avoid be blocked
                                  }
                                  else {
                                  $run_already = true;
                                  }
                                  
                                  
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
            $ct['usort_alpha'] = 'name';
            usort($results[$sort_key], array($ct['gen'], 'usort_alpha') );
            }     

     
       gc_collect_cycles(); // Clean memory cache
            
       return $results;

       }
         
         
   }
                           
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_id_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_id_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
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
         // so we need their app id info endpoint in $single_asset_info mode
         if ( $single_asset_info && $exchange_key == 'coingecko' ) {
         $url = 'https://api.coingecko.com/api/v3/coins/' . $market_search;
         }
         else {
         $url = $exchange_api['search_endpoint'];
         }
   
   
   $url = preg_replace("/\[SEARCH_QUERY\]/i", $dyn_id, $url);
   
   
       // https://station.jup.ag/docs/token-list/token-list-api
       if ( isset($_POST['jupiter_tags']) && trim($_POST['jupiter_tags']) != '' ) {
       $jupiter_ag_search_tags = $_POST['jupiter_tags'];
       }
       else {
       $jupiter_ag_search_tags = 'verified';
       }
       
   
   $url = preg_replace("/\[JUP_AG_TAGS\]/i", $jupiter_ag_search_tags, $url); // Make dynamic in the future
         
   $url = preg_replace("/\[ALPHAVANTAGE_KEY\]/i", $ct['conf']['ext_apis']['alphavantage_api_key'], $url);
   
   // API response data
   $response = @$ct['cache']->ext_data('url', $url, $ct['conf']['power']['exchange_search_cache_time']);
   
   gc_collect_cycles(); // Clean memory cache
   
   $data = json_decode($response, true);
   
   
       if ( is_array($data) ) {

       
                if ( $exchange_key == 'coinbase' ) {
            
            
                     foreach( $data as $val ) {
                          
                     // Minimize calls
                     $market_id_parse  = $this->market_id_parse($exchange_key, $val['id']);
                          
                         
                         // Skip, if not relevant
                         if ( 
                         !$ct['gen']->search_mode($market_id_parse['asset'], $dyn_id) 
                         || $search_pairing && !$ct['gen']->search_mode($market_id_parse['pairing'], $search_pairing) 
                         ) {
                         continue;
                         }
                         
                         
                     // Minimize calls, AND throttle to avoid being blocked
                     usleep(1500000); // Wait 1.5 seconds
                     $check_market_data = $this->market($dyn_id, $exchange_key, $val['id']);
                                   
                                   
                         if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                        
                         $possible_market_ids[] = array(
                                                                       'name' => strtoupper($market_id_parse['asset']),
                                                                       'id' => $val['id'],
                                                                       'asset' => $market_id_parse['asset'],
                                                                       'pairing' => $market_id_parse['pairing'],
                                                                       'flagged_market' => $market_id_parse['flagged_market'],
                                                                       'data' => $check_market_data,
                                                                        );
                                                                        
                         }
                         
                     
                     }
                     
   
                gc_collect_cycles(); // Clean memory cache
                
                }
                elseif ( $exchange_key == 'jupiter_ag' ) {
                
                $temp_app_id_array = array();
                
                     
                     $loop_count = 0;
                     foreach( $data as $val ) {
          
                         
                         if (
                         !$search_pairing && isset($val['symbol']) && $ct['gen']->search_mode($val['symbol'], $dyn_id)
                         || $search_pairing && isset($val['symbol']) && $ct['gen']->search_mode($val['symbol'], $dyn_id)
                         && $ct['gen']->search_mode($required_pairing, $search_pairing)
                         ) {

                              
                              // Skip
                              // JUPITER WON'T LET US GET MORE THAN 100 COINS W/ PRICE API ANYWAY (NO PAGINATION OPTION AS OF 2024/9/3),
                              // SO (FEASIBLY) OUR HARD CAP IS 100 RELEVANT COINS PER SEARCH
                              // IF relevant, max $ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] results
                              // (to avoid gateway timeout on server, from too many results)
                              if (
                              $ct['gen']->search_mode($val['symbol'], $required_pairing)
                              || $loop_count >= 100 
                              || $ct['jupiter_ag_search_results'] >= ($ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] * $ct['system_info']['cpu_threads'])
                              ) {
                              break;
                              }
                              
                              
                              if ( $val['symbol'] == 'SOL' ) {
                              $default_pairing = 'WBTC';
                              }
                              else {
                              $default_pairing = 'SOL';
                              }

                         
                         $asset_check = $val['symbol'];
                         $pairing_check = ( $required_pairing ? $required_pairing : $default_pairing );
                                           
                              
                              // We need to keep data set request to 100 or less, so we need to reset our pair array to empty,
                              // since we loop up to 100 times              
                              if ( !isset($ct['jupiter_ag_pairs'][$pairing_check]) ) {
                              $ct['jupiter_ag_pairs'][$pairing_check] = $val['symbol'];
                              }
                              // IF APP ID wasn't bundled yet into the single call format we use for coingecko, add it now,
                              // to optimize this search loop INTO A SINGLE CALL (consecutive calls will automatically use the cache system)
                              // (THIS IS ***REQUIRED*** FOR MULTIPLE COINGECKO SEARCH RESULTS, DUE TO IT'S 'BATCHED' DATA CALL STRUCTURE!!!)
                              elseif (
                              substr($ct['jupiter_ag_pairs'][$pairing_check], 0, strlen($val['symbol']) ) != $val['symbol']
                              && !strstr($ct['jupiter_ag_pairs'][$pairing_check], ',' . $val['symbol'])
                              ) {
                              $ct['jupiter_ag_pairs'][$pairing_check] = $ct['jupiter_ag_pairs'][$pairing_check] . ',' . $val['symbol'];
                              }
                         
                         
                         $temp_app_id_array[$pairing_check][ $val['symbol'] ] = array(
                                                                                      'data' => $val,
                                                                                      'asset_check' => $asset_check,
                                                                                      );
                                   
                         $ct['jupiter_ag_search_results'] = $ct['jupiter_ag_search_results'] + 1;
                         
                         $loop_count = $loop_count + 1;
                         
                         }
                         
                     
                     }
                     
                     
                     // Process results
                     foreach( $temp_app_id_array as $pairing_key => $pairing_data ) {
                          
                     $current = current($pairing_data);
                         
                     // FROM HERE ONWARD FOR JUPITER SEARCHES, WE MUST USE THE EXACT (CASE / SYMBOL SENSITIVE) MARKET IDS WE RETRIEVE,
                     // AS JUPITER CAN HAVE UPPERCASE / LOWERCASE / SYMBOLS IN THE TICKER FIELD ('symbol'), WHICH WE MUST MATCH EXACTLY!!!
                     // Minimize calls, AND throttle to avoid being blocked
                     usleep(1500000); // Wait 1.5 seconds
                     // Returns ALL API results, with $return_all_results = true in the fetch_exchange_data() call
                     // (RARELY useful, as it requires unique parsing per-exchange, BUT jupiter can have HUNDREDS of results,
                     // so this optimizes speed fairly well, for this one exchange's search results)
                     $check_market_data = $this->fetch_exchange_data($exchange_key, $current['asset_check'] . '/' . $pairing_key, false, true);
                     
                          
                          foreach( $pairing_data as $market_key => $market_data ) {
                               
                          $this_market_data = $check_market_data[$market_key];
                          
                          $parsed_market_data = array(        
                                             'jup_ag_address' => $this_market_data['id'],
                                             'last_trade' => number_format( $this_market_data['price'], $ct['conf']['gen']['crypto_decimals_max'], '.', ''),
                                             '24hr_usd_vol' => $market_data['data']['daily_volume'],
                                   	      );
                         	      
                         	        
                               if ( isset($parsed_market_data['last_trade']) && $parsed_market_data['last_trade'] > 0 ) {
                                    
                               // Minimize calls
                               $market_id_parse  = $this->market_id_parse($exchange_key, $market_key . '/' . $pairing_key, $pairing_key, $market_key);
                                         
                               $possible_market_ids[] = array(
                                                               'name' => $market_data['data']['name'],
                                                               'id' => $market_key . '/' . $pairing_key,
                                                               'contract_address' => $this_market_data['id'],
                                                               'asset' => $market_id_parse['asset'],
                                                               'pairing' => $market_id_parse['pairing'],
                                                               'flagged_market' => $market_id_parse['flagged_market'],
                                                               'data' => $parsed_market_data,
                                                                );
                                                                
                               }
                               
                               
                          }
                     
                     
                     }
                     
       
                gc_collect_cycles(); // Clean memory cache
                       
                }
                elseif ( $exchange_key == 'coingecko' ) {
                
                
                     // Return an APP ID's associated values
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
                                   // IF APP ID wasn't bundled yet into the single call format we use for coingecko, add it now,
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
                                   
                         
                          // Minimize calls, AND throttle to avoid being blocked
                          usleep(1500000); // Wait 1.5 seconds
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
                         $market_id_parse  = $this->market_id_parse($exchange_key, $result["1. symbol"], $result["8. currency"]);
                         
                         
                              // Skip, if not relevant
                              if ( 
                              !$ct['gen']->search_mode($market_id_parse['asset'], $dyn_id) 
                              || $search_pairing && !$ct['gen']->search_mode($market_id_parse['pairing'], $search_pairing)
                              ) {
                              continue;
                              }
                              
                                   
                              
                              // DON'T DO ANY POTENTIALLY ***LIVE*** MARKET CHECKS FOR THE ALPHAVANTAGE ***FREE*** TIER,
                              // AS THE FREE TIER DAILY LIMITS ARE VERY LOW, AND COULD CUT OFF DAILY LIVE REQUESTS BEFORE END-OF-DAY!!!
                              if ( $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 ) {
                                   
                              $possible_market_ids[] = array(
                                                                                 'name' => $result["2. name"],
                                                                                 'id' => $result["1. symbol"],
                                           // Even though we know the pairing, we still need to replace any MULTI-TICKER CURRENCY (NIS/CNY) with ticker used in-app
                                           // (for pairing UX in the app)
                                                                                 'asset' => $market_id_parse['asset'],
                                                                                 'pairing' => $market_id_parse['pairing'],
                                                                                 'flagged_market' => $market_id_parse['flagged_market'],
                                                                                 'data' => array('last_trade' => 'SKIPPED, SO FREE TIER STAYS WITHIN DAILY LIMITS! (upgrade your alphavantage API KEY to a PREMIUM tier [and adjust "AlphaVantage.co Per Minute Limit" HIGHER THAN 5 accordingly, in the "External APIs" section], to see price previews)'),
                                                                                  );
                                                                                  
                              }
                              else {
                    
                              // Minimize calls, AND throttle to avoid being blocked
                              usleep(1500000); // Wait 1.5 seconds
                              $check_market_data = $this->market($dyn_id, $exchange_key, $result["1. symbol"]);
                                             
                                             
                                    if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                   
                                    // Minimize calls
                                    $market_id_parse  = $this->market_id_parse($exchange_key, $result["1. symbol"], $result["8. currency"]);
                                                  
                                    $possible_market_ids[] = array(
                                                                                 'name' => $result["2. name"],
                                                                                 'id' => $result["1. symbol"],
                                           // Even though we know the pairing, we still need to replace any MULTI-TICKER CURRENCY (NIS/CNY) with ticker used in-app
                                           // (for pairing UX in the app)
                                                                                 'asset' => $market_id_parse['asset'],
                                                                                 'pairing' => $market_id_parse['pairing'],
                                                                                 'flagged_market' => $market_id_parse['flagged_market'],
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
   
   
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_id_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_id_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
   function market_id_parse($exchange_key, $market_id, $known_pairing=false, $known_asset=false) {
   
   global $ct;
   
   
        // We only need this function during market searches
        if ( !$ct['ticker_markets_search'] ) {
        return false;
        }
        
   
   $results = array();
   
   
        if ( $known_pairing && trim($known_pairing) != '' ) {
        $results['pairing'] = $known_pairing;
        }
        // Otherwise, parse pairing results out
        else {
             
              
              // Easy parsing
              if ( $exchange_key == 'bitbns' || $exchange_key == 'unocoin' ) {
              $results['pairing'] = 'inr';
              }
              elseif ( $exchange_key == 'coinspot' ) {
              $results['pairing'] = 'aud';
              }
              // Alphavantage still needs pairing determination for SINGLE-EXCHANGE SEARCHES
              elseif ( $exchange_key == 'alphavantage_stock' ) {
                   
                   
                   if ( stristr($market_id, '.') ) {
                        
                        if ( stristr($market_id, '.TRT') || stristr($market_id, '.TRV') ) {
                        $results['pairing'] = 'cad';
                        }
                        elseif ( stristr($market_id, '.DEX') || stristr($market_id, '.FRK') ) {
                        $results['pairing'] = 'eur';
                        }
                        elseif ( stristr($market_id, '.SHH') || stristr($market_id, '.SHZ') ) {
                        $results['pairing'] = 'rmb';
                        }
                        elseif ( stristr($market_id, '.LON') ) {
                        $results['pairing'] = 'usd'; // usd for some odd reason
                        }
                        elseif ( stristr($market_id, '.SAO') ) {
                        $results['pairing'] = 'brl';
                        }
                        elseif ( stristr($market_id, '.BSE') ) {
                        $results['pairing'] = 'inr';
                        }
                   
                   }
                   else {
                   $results['pairing'] = 'usd';
                   }
                   
              
              }
              // Advanced parsing
              else {
                   
              $cleaned_market_id = $market_id;
             
                       
                   // IF WE NEED SOME REGEX MAGIC TO PARSE THE VALUES WE WANT
                   // https://www.threesl.com/blog/special-characters-regular-expressions-escape/
                   if ( $exchange_key == 'loopring' ) {
                   $cleaned_market_id = preg_replace("/AMM-/i", "", $cleaned_market_id);
                   }
                   elseif ( $exchange_key == 'bitmex' || $exchange_key == 'luno' ) {
                   $cleaned_market_id = preg_replace("/XBT/i", "BTC", $cleaned_market_id);
                   }
                   elseif ( $exchange_key == 'aevo' ) {
                   $cleaned_market_id = preg_replace("/-PERP/i", "-USD", $cleaned_market_id);
                   }
                   elseif ( $exchange_key == 'crypto.com' ) {
                   $cleaned_market_id = preg_replace("/-PERP(.*)/i", "", $cleaned_market_id);
                   $cleaned_market_id = preg_replace("/USD-(.*)/i", "USD", $cleaned_market_id);
                   }
                   // WTF Kraken, LMFAO :)
                   elseif ( $exchange_key == 'kraken' ) {
                   
                   $cleaned_market_id = preg_replace("/XXBTZ/i", "BTC", $cleaned_market_id);
                   $cleaned_market_id = preg_replace("/XXBT/i", "BTC", $cleaned_market_id);
                   $cleaned_market_id = preg_replace("/XBT/i", "BTC", $cleaned_market_id);
                   $cleaned_market_id = preg_replace("/XETHZ/i", "ETH", $cleaned_market_id);
                   $cleaned_market_id = preg_replace("/XETH/i", "ETH", $cleaned_market_id);
          
                   }
                   elseif ( $exchange_key == 'bybit' && substr($cleaned_market_id, 0, 4) == '1000' ) {
                   $cleaned_market_id = substr($cleaned_market_id, 4);
                   }
                   elseif (
                   $exchange_key == 'bitfinex' && substr($cleaned_market_id, 0, 1) == 't'
                   || 
                   $exchange_key == 'ethfinex' && substr($cleaned_market_id, 0, 1) == 't'
                   ) {
                   $cleaned_market_id = substr($cleaned_market_id, 1);
                   }
                   

              $parsed_pairing = strtolower($cleaned_market_id);
                  
                  
                  if ( in_array($exchange_key, $ct['dev']['hyphen_delimited_markets']) ) {
                  $parsed_pairing = preg_replace("/(.*)-/i", "", $parsed_pairing);
                  }
                  elseif ( in_array($exchange_key, $ct['dev']['reverse_hyphen_delimited_markets']) ) {
                  $parsed_pairing = preg_replace("/-(.*)/i", "", $parsed_pairing);
                  }
                  elseif ( in_array($exchange_key, $ct['dev']['underscore_delimited_markets']) ) {
                  $parsed_pairing = preg_replace("/(.*)_/i", "", $parsed_pairing);
                  }
                  elseif ( in_array($exchange_key, $ct['dev']['forwardlash_delimited_markets']) ) {
                  $parsed_pairing = preg_replace("/(.*)\//i", "", $parsed_pairing);
                  }
                  elseif ( in_array($exchange_key, $ct['dev']['colon_delimited_markets']) ) {
                  $parsed_pairing = preg_replace("/(.*):/i", "", $parsed_pairing);
                  }                 
                  
                  
                  // If we haven't registered all pairs yet this runtime, do it now
                  // (we do a RUNTIME memory cache, to optimize / increase runtime speed)
                  if ( sizeof($ct['registered_pairs']) < 1 ) {
                       
                  $temp_array = array();
                       
                  // IF the TICKER was a PARTIAL MATCH, we may NOT have CLEANLY parsed out the pairing yet...
                       
                  // HARD-CODED SPECIFIC / POPULAR pairing support (that we don't bundle with fresh install DEMO data)
                  $other_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['additional_pairings_search']) );
                       
                  // Coingecko pairing support
                  $coingecko_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['coingecko_pairings_search']) );
                       
                  // Upbit pairing support
                  $upbit_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['upbit_pairings_search']) );
                       
                  // jupiter_ag pairing support
                  $jupiter_ag_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['jupiter_ag_pairings_search']) );
                   
                       
                       // Other pairings    
                       foreach ( $other_pairings as $pair_val ) {
                       $temp_array[] = $pair_val;
                       }
                   
                       
                       // Coingecko pairings    
                       foreach ( $coingecko_pairings as $pair_val ) {
                       $temp_array[] = $pair_val;
                       }
                   
                       
                       // Upbit pairings    
                       foreach ( $upbit_pairings as $pair_val ) {
                       $temp_array[] = $pair_val;
                       }
                   
                       
                       // jupiter_ag pairings    
                       foreach ( $jupiter_ag_pairings as $pair_val ) {
                       $temp_array[] = $pair_val;
                       }
                       
                       
                       // BTC currency pairings
                       foreach ( $ct['conf']['assets']['BTC']['pair'] as $pairing_key => $unused ) {
                       $temp_array[] = $pairing_key;
                       }
                       
                       
                       // 'crypto_pair' pairings
                       foreach ( $ct['opt_conf']['crypto_pair'] as $pairing_key => $unused ) {
                       $temp_array[] = $pairing_key;
                       }
               
                      
                       // Cleanup
                       if ( sizeof($temp_array) > 0 ) { 
                       
                       // Remove whitespace
                       $temp_array = array_map("trim", $temp_array);
                       
                       // To lowercase
                       $temp_array = array_map("strtolower", $temp_array);
                       
                       // Remove duplicates
                       $temp_array = array_unique($temp_array);
                       
                       // Get usd near top, and btc / eth below eur, before the usort below,
                       // so we are looking for usd / eur pairings as early as possible (in the 3 character range)
                       rsort($temp_array);
                       
                       // Now sort by length, so we are checking for LONGER pairings first (to assure SAFEST parsed results)
                       usort($temp_array, array($ct['gen'], 'usort_length') );
                       
                       $ct['registered_pairs'] = $temp_array; // Set global now, since we finished building / sorting
          
                       }
                  
        
                  gc_collect_cycles(); // Clean memory cache
                  
                  }
                       
                  
                  if ( is_array($ct['registered_pairs']) && sizeof($ct['registered_pairs']) > 0 ) {


                       foreach ( $ct['registered_pairs'] as $val ) {
                            
                             
                             // Leave loop, if match already found
                             if ( $pairing_match ) {
                             break; 
                             }
                             // REVERSE ordered (pairing at FRONT of market id)
                             elseif ( in_array($exchange_key, $ct['dev']['reverse_id_markets']) ) {
                                     
                                 if ( substr($parsed_pairing, 0, strlen($val) ) == $val ) {
                                 $pairing_match = $val;
                                 }
                                     
                             }
                             // COMMON ordered (pairing at END of market id)
                             elseif ( substr($parsed_pairing, -strlen($val) ) == $val ) {
                             $pairing_match = $val;
                             }
                            
                            
                       }
                       
                       
                  $results['pairing'] = $pairing_match;          
                  
                  }
                  else {
                       
                  $ct['gen']->log( 'market_error', '"registered_pairs" array was not populated with any pairings (to search for in market ids)');
                  
                  // Set EVERYTHING to false, except exchange key
                  $results['pairing'] = false;
                  $results['asset'] = false;
                  $results['flagged_market'] = false;
                  $results['exchange'] = $exchange_key;
                  
                  return $results;
        
                  }
                  
                  
              }
              
             
             // Log / return false if no pairing was parsed out
             if (
             !isset($results['pairing'])
             || trim($results['pairing']) == ''
             || strtolower($results['pairing']) == strtolower($market_id)
             ) {
                  
                  
                  if ( $ct['conf']['power']['debug_mode'] == 'setup_wizards_io' ) {
                  $ct['gen']->log( 'other_debug', 'value NOT parsed for "pairing" (value = '.$results['pairing'].'), within market id "'.$market_id.'", during asset market search: "' . $_POST['add_markets_search'] . '" (for exchange API '.$exchange_key.')');
                  }
                  
             
             // Set EVERYTHING to false, except exchange key
             $results['pairing'] = false;
             $results['asset'] = false;
             $results['flagged_market'] = false;
             $results['exchange'] = $exchange_key;
             
             return $results;
             
             }
        
        
        } // END OF PAIRING PARSE ROUTINE
   
   
   // NOW WE CAN SAFELY PARSE OUT THE ASSET...
   
        if ( $known_asset && trim($known_asset) != '' ) {
        $results['asset'] = $known_asset;
        }
        // If we couldn't parse out the pairing, we also can't parse out the asset
        elseif ( trim($results['pairing']) == '' ) {
        $results['asset'] = ''; 
        }
        // We flag stocks in this app with the suffix: STOCK [TICKERSTOCK]
        elseif ( $exchange_key == 'alphavantage_stock' ) {
        $results['asset'] = preg_replace("/\.(.*)/i", "", $market_id) . 'STOCK'; 
        }
        else {
             
        $parsed_asset = ( $cleaned_market_id ? $cleaned_market_id : $market_id );
             
        $results['asset'] = preg_replace("/".$results['pairing']."/i", "", $parsed_asset);
        
        }
        
        
   // Remove 'perp', if in asset name
   $results['asset'] = preg_replace("/perp/i", "", $results['asset']);
           
   // Remove everything NOT alphanumeric,
   // AS IT'S A SHITSHOW SUPPORTING $TICKER etc in this app AT THE DATASET STORAGE LEVEL (should be interface ONLY!)
   $results['asset'] = preg_replace("/[^0-9a-zA-Z]+/i", "", $results['asset']);
        
   // Lowercase / Trim any whitespace off ends
   $results['asset'] = strtolower( trim($results['asset']) );
      
        
   // Remove everything NOT alphanumeric, Lowercase / Trim any whitespace off ends
   $results['pairing'] = preg_replace("/[^0-9a-zA-Z]+/i", "", $results['pairing']);
   $results['pairing'] = strtolower( trim($results['pairing']) );
   
        
        if ( $exchange_key == 'coingecko' ) {
        $exchange_check = $exchange_key . '_' . $results['pairing'];
        }
        else {
        $exchange_check = $exchange_key;
        }
             
             
        // Convert WRAPPED CRYPTO TICKERS to their NATIVE tickers
        // MUST BE AFTER PARSING OUT ASSET!
        if ( $results['pairing'] == 'tbtc' || $results['pairing'] == 'wbtc' ) {
        $results['pairing'] = 'btc';
        }
        elseif ( $results['pairing'] == 'weth' ) {
        $results['pairing'] = 'eth';
        }
        // Convert INTERNATIONAL TICKERS to their NATIVE tickers
        elseif ( $results['pairing'] == 'cny' ) {
        $results['pairing'] = 'rmb';
        }
        elseif ( $results['pairing'] == 'ils' ) {
        $results['pairing'] = 'nis';
        }
   
   
        // If already added
        if ( isset($ct['conf']['assets'][strtoupper($results['asset'])]['pair'][strtolower($results['pairing'])][$exchange_check]) ) {
       
           if ( $ct['conf']['assets'][strtoupper($results['asset'])]['pair'][strtolower($results['pairing'])][$exchange_check] == $market_id ) {
           $results['flagged_market'] = 'already_added_' . $market_id;
           }
           else {
           $results['flagged_market'] = 'replacement_for_' . $ct['conf']['assets'][strtoupper($results['asset'])]['pair'][strtolower($results['pairing'])][$exchange_check];
           }
       
        }
        // If no bitcoin market for pairing (to convert currency value to other currencies)
        elseif ( strtolower($results['asset']) != 'btc' && strtolower($results['pairing']) != 'btc' && !isset($ct['conf']['assets']['BTC']['pair'][strtolower($results['pairing'])]) ) {
        $results['flagged_market'] = 'pairing_not_supported_' . strtolower($results['pairing']);
        }
        else {
        $results['flagged_market'] = false;
        }
        

   $results['exchange'] = $exchange_key;
        
   gc_collect_cycles(); // Clean memory cache

   return $results;
      
   }
                           
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_id_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_id_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
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
   
   // IF alphavantage, we have to throttle LIVE requests for the FREE tier
   $cache_time = ( $exchange_key == 'alphavantage_stock' ? $ct['throttled_api_cache_time']['alphavantage.co'] : $cache_time );
          
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
              // IF APP ID wasn't bundled yet into the single call format we use for coingecko,
              // add it now, or we WON'T GET RELEVANT RESULTS (when VALIDATING 'add market' search results, etc)
              // WE INCLUDE SEARCHING FOR A COMMA IN FRONT OF THE APP ID, AS WELL AS IT BEING THE FIRST VALUE
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
                   // WE INCLUDE SEARCHING FOR A COMMA IN FRONT OF THE APP ID, AS WELL AS IT BEING THE FIRST VALUE
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
         
         //var_dump($url);
         
         $dyn_id = $jup_market[0]; // Reset for parsing results (result key is only the asset ticker)

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
                       $market_id_parse  = $this->market_id_parse($exchange_key, $val[ $exchange_api['all_markets_support'] ]);
                            
                            
                            // As long as we are NOT in search mode, OR parsed asset ticker is relevant
                            if (
                            !$ticker_search_mode && $val[ $exchange_api['all_markets_support'] ] == $dyn_id
                            || $ticker_search_mode && $ct['gen']->search_mode($market_id_parse['asset'], $dyn_id)
                            || $ticker_search_mode && $search_pairing && $ct['gen']->search_mode($market_id_parse['asset'], $dyn_id)
                            && $ct['gen']->search_mode($market_id_parse['pairing'], $search_pairing)
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
                                                                       'name' => strtoupper($market_id_parse['asset']),
                                                                       'id' => $val[ $exchange_api['all_markets_support'] ],
                                                                       'asset' => $market_id_parse['asset'],
                                                                       'pairing' => $market_id_parse['pairing'],
                                                                       'flagged_market' => $market_id_parse['flagged_market'],
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
                                   
                            // Minimize calls
                            $market_id_parse  = $this->market_id_parse($exchange_key, $val[ $exchange_api['all_markets_support'] ]);
                                        
                            
                                 // As long as parsed asset ticker is relevant
                                 if ( 
                                    !$search_pairing && $ticker_search_mode && $ct['gen']->search_mode($market_id_parse['asset'], $dyn_id)
                                    || $search_pairing && $ticker_search_mode && $ct['gen']->search_mode($market_id_parse['asset'], $dyn_id)
                                    && $ct['gen']->search_mode($market_id_parse['pairing'], $search_pairing)
                                    ) {
                                         
                                 // Minimize calls
                                 $check_market_data = $this->market($dyn_id, $exchange_key, $val[ $exchange_api['all_markets_support'] ]);
                              
                              
                                        if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                                        
                                        $possible_market_ids[] = array(
                                                                   'name' => strtoupper($market_id_parse['asset']),
                                                                   'id' => $val[ $exchange_api['all_markets_support'] ],
                                                                   'asset' => $market_id_parse['asset'],
                                                                   'pairing' => $market_id_parse['pairing'],
                                                                   'flagged_market' => $market_id_parse['flagged_market'],
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
                   $market_id_parse = $this->market_id_parse($exchange_key, $key);
                       
                       
                       if (
                       !$search_pairing && $ct['gen']->search_mode($market_id_parse['asset'], $dyn_id)
                       || $search_pairing && $ct['gen']->search_mode($market_id_parse['asset'], $dyn_id) 
                       && $ct['gen']->search_mode($market_id_parse['pairing'], $search_pairing)
                       ) {
                                         
                       // Minimize calls
                       $check_market_data = $this->market($dyn_id, $exchange_key, $key);
                              
                              
                            if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                             
                            $possible_market_ids[] = array(
                                                                   'name' => strtoupper($market_id_parse['asset']),
                                                                   'id' => $key,
                                                                   'asset' => $market_id_parse['asset'],
                                                                   'pairing' => $market_id_parse['pairing'],
                                                                   'flagged_market' => $market_id_parse['flagged_market'],
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
              // For UX, we don't want to log 'no data' errors on exchange APIs DURING TICKER SEARCHES
              elseif ( !$ticker_search_mode ) {
         
              $ct['gen']->log(
              		    'notify_error',
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
      
      $endpoint_tld_or_ip = $ct['gen']->get_tld_or_ip($url);
   
         
          if ( $ct['conf']['power']['debug_mode'] == 'all_telemetry' || $ct['conf']['power']['debug_mode'] == 'memory_usage_telemetry' ) {
         	
          $ct['gen']->log(
         			  'system_debug',
         			  $endpoint_tld_or_ip . ' news feed updating ('.$_SESSION[$fetched_feeds]['all'].'), CURRENT script memory usage is ' . $ct['gen']->conv_bytes(memory_get_usage(), 1) . ', PEAK script memory usage is ' . $ct['gen']->conv_bytes(memory_get_peak_usage(), 1) . ', php_sapi_name is "' . php_sapi_name() . '"'
         			   );
         
          }
      
      
      // Throttling multiple requests to same server
      $tld_session = strtr($endpoint_tld_or_ip, ".", "");
   
            
          if ( !isset($_SESSION[$fetched_feeds][$tld_session]) ) {
          $_SESSION[$fetched_feeds][$tld_session] = 0;
          }
          // If it's a consecutive feed request to the same server, sleep X seconds to avoid rate limiting request denials
          elseif ( $_SESSION[$fetched_feeds][$tld_session] > 0 ) {
            
              if ( $endpoint_tld_or_ip == 'reddit.com' ) {
              sleep(7);
              usleep(100000); // 0.1 seconds (Reddit only allows rss feed connections every 7 seconds from ip addresses ACCORDING TO THEM)
              }
              else {
              usleep(550000); // 0.55 seconds
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
           
          $endpoint_tld_or_ip = $ct['gen']->get_tld_or_ip($url);
           
          // Log full results to file, TO GET LINE NUMBERS FOR ERRORS
          
          // FOR SECURE ERROR LOGS, we redact the full path
          $xml_response_file_cache = '/cache/other/xml_error_parsing/xml-data-'.preg_replace("/\./", "_", $endpoint_tld_or_ip).'.xml';
          
          $xml_response_file = $ct['base_dir'] . $xml_response_file_cache;
          
          
               // If we don't already have a saved XML file of this data
               if ( !file_exists($xml_response_file) ) {
           
               // Log this error response from this data request
               $ct['cache']->save_file($xml_response_file, $response);
                    
               libxml_clear_errors();
               
               sleep(2);
               
               // Load again, BUT FROM THE SAVED FILE (to get line numbers of all errors)
               $rss_check = simplexml_load_string($xml_response_file);
                
               $xml_lines_parsed = file($xml_response_file);
                    
               $xml_errors = libxml_get_errors();
               
                    foreach ( $xml_errors as $error ) {
                    $xml_error_summary .= $ct['gen']->display_xml_error($error, $xml_lines_parsed);
                    }
                    
               libxml_clear_errors();
           
               $ct['gen']->log('other_error', 'error reading XML-based news feed data from ' . $url . ', SAVED FOR 48 HOURS TO FILE FOR INSPECTION AT ' . $xml_response_file_cache . $xml_error_summary);

               }


          gc_collect_cycles(); // Clean memory cache

          return '<span class="red">Error reading news feed data (XML error), see admin app logs for details.</span>';

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
		      $usort_results = usort($sortable_feed,  array($ct['gen'], 'timestamps_usort_newest') );
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
			          elseif ( sizeof( $item->children('dc', true) ) > 0 ) {
			         
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
				     if ( $ct['var']->num_to_str($now_timestamp) > $ct['var']->num_to_str( strtotime($item_date) + ($ct['conf']['news']['mark_as_new'] * 86400) + $ct['dev']['tasks_time_offset'] ) ) {
				     $mark_new = null;
				     }
				     // If running as $email_only, we only want 'new' posts anyway (less than 'news_feed_email_frequency' days old)
				     // With offset, to try to catch any that would have been missed from runtime
				     elseif ( $email_only && $ct['var']->num_to_str($now_timestamp) <= $ct['var']->num_to_str( strtotime($item_date) + ($ct['conf']['news']['news_feed_email_frequency'] * 86400) + $ct['dev']['tasks_time_offset'] ) ) { 
				     
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
	          $usort_results = usort($sortable_feed, array($ct['gen'], 'timestamps_usort_newest') );
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
			         elseif ( sizeof( $item->children('dc', true) ) > 0 ) {
			         
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
			         if ( $ct['var']->num_to_str($now_timestamp) > $ct['var']->num_to_str( strtotime($item_date) + ($ct['conf']['news']['mark_as_new'] * 86400) + $ct['dev']['tasks_time_offset'] ) ) {
			         $mark_new = null;
			         }
				     // If running as $email_only, we only want 'new' posts anyway (less than 'news_feed_email_frequency' days old)
				     // With offset, to try to catch any that would have been missed from runtime
				     elseif ( $email_only && $ct['var']->num_to_str($now_timestamp) <= $ct['var']->num_to_str( strtotime($item_date) + ($ct['conf']['news']['news_feed_email_frequency'] * 86400) + $ct['dev']['tasks_time_offset'] ) ) {
    			     
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
   
   $data = $this->exchange_api_data($sel_exchange, $mrkt_id);
   
   gc_collect_cycles(); // Clean memory cache
   
    
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
      
      $result = array(
                     'last_trade' => $data["mark_price"],
                     '24hr_asset_vol' => null, // Unavailable, set null
                     '24hr_pair_vol' => $ct['var']->num_to_str($data["markets"]["daily_volume_contracts"] * $data["mark_price"])
                	   );
      
      }
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
      elseif ( $sel_exchange == 'alphavantage_stock' ) {
   
   
          if ( trim($ct['conf']['ext_apis']['alphavantage_api_key']) == null ) {
          	
          $ct['gen']->log(
          		    'notify_error',
          		    '"alphavantage_api_key" (free API key) is not configured in Admin Config EXTERNAL APIS section',
          		    false,
          		    'alphavantage_api_key'
          		    );
          
          return false;
          
          }
          
	      
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
    
    
    
      elseif ( $sel_exchange == 'bitfinex' || $sel_exchange == 'ethfinex' ) {
      
      $result = array(
	                              'last_trade' => $data[( sizeof($data) - 4 )],
	                              '24hr_asset_vol' => $data[( sizeof($data) - 3 )],
	                              '24hr_pair_vol' => null // Unavailable, set null
	                     		  );
      
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
	                           '24hr_asset_vol' => $ct['var']->num_to_str($asset_vol - $half_oldest_hour_asset_vol),
	                           '24hr_pair_vol' =>  $ct['var']->num_to_str($pair_vol - $half_oldest_hour_pair_vol)
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
                     'last_trade' => number_format( $data['last'], $ct['conf']['gen']['crypto_decimals_max'], '.', ''),
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
	                              'last_trade' => number_format( $last_trade, $ct['conf']['gen']['crypto_decimals_max'], '.', ''),
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
      
    
    
      elseif ( $sel_exchange == 'gemini' ) {
        
      $result = array(
                     'last_trade' => $data['last'],
                     '24hr_asset_vol' => $data['volume'][strtoupper($asset_symb)],
                     '24hr_pair_vol' => $data['volume'][strtoupper($pair)]
      	              );
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
      
      elseif ( $sel_exchange == 'graviex' ) {
      
      $result = array(
                              'last_trade' => $data['ticker']['last'],
                              '24hr_asset_vol' => $data['ticker']['vol'],
                              '24hr_pair_vol' => null // Weird pair volume always in BTC according to array keyname, skipping
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
    
    
    
      elseif ( $sel_exchange == 'idex' ) {
      
      $result = array(
                              'last_trade' => $data["close"],
                              // ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
                              '24hr_asset_vol' => $data["quoteVolume"],
                              '24hr_pair_vol' => $data["baseVolume"]
                     		  );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( $sel_exchange == 'jupiter_ag' ) {
      
      $result = array(        
                              'jup_ag_address' => $data['id'],
                              'last_trade' => number_format( $data['price'], $ct['conf']['gen']['crypto_decimals_max'], '.', ''),
                              '24hr_asset_vol' => 0, // Unavailable, set 0 to avoid 'price_alert_block_volume_error' suppression
                              '24hr_pair_vol' => null // Unavailable, set null
                    	      );
        
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
                              'last_trade' => $ct['var']->num_to_str($data["last_trade"]), // Handle large / small values better with $ct['var']->num_to_str()
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
      
    
    
      elseif ( $sel_exchange == 'okex' ) {
       
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
		      
		      
    		// Set primary currency volume value
    		if ( isset($result['24hr_pair_vol']) && isset($pair) && $pair == $ct['conf']['gen']['bitcoin_primary_currency_pair'] ) {
    		$result['24hr_prim_currency_vol'] = $ct['var']->num_to_str($result['24hr_pair_vol']); // Save on runtime, if we don't need to compute the fiat value
    		}
    		elseif ( isset($result['24hr_pair_vol']) ) {
    		$result['24hr_prim_currency_vol'] = $ct['var']->num_to_str( $ct['asset']->prim_currency_trade_vol($asset_symb, $pair, $result['last_trade'], $result['24hr_pair_vol']) );
    		}
    		// (ONLY IF WE ARE ***NOT*** RUNNING A TICKER MARKETS SEARCH, AS IT COULD CAUSE HUNDREDS OF NEW API CALLS PER MINUTE!)
    		elseif ( !$ct['ticker_markets_search'] && isset($result['jup_ag_address']) ) {
    		
    		// As of 2024/9/3, ONLY ONE COIN AT A TIME IS SUPPORTED:
    		// https://station.jup.ag/docs/token-list/token-list-api
          $response = @$ct['cache']->ext_data('url', 'https://tokens.jup.ag/token/' . $result['jup_ag_address'], 45); // 45 minute cache
            
          $data = json_decode($response, true);
          
               if ( $ct['var']->num_to_str($data['daily_volume']) > 0 ) {
               $result['24hr_prim_currency_vol'] = $ct['var']->num_to_str( $ct['asset']->prim_currency_trade_vol($asset_symb, 'usd', $result['last_trade'], $data['daily_volume']) );
               $result['24hr_usd_vol'] = $data['daily_volume'];
               }
    		
    		}
    		else {
    		$result['24hr_prim_currency_vol'] = null;
    		}
        
      
      }
   
   
   gc_collect_cycles(); // Clean memory cache
   
   return $result;
   
   }
   

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
      
   
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>