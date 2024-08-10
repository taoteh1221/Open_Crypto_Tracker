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
var $exchange_apis = array(


                           'aevo' => array(
                                                   'endpoint' => 'https://api.aevo.xyz/instrument/[MARKET]',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'alphavantage_stock' => array(
                                                   'endpoint' => 'https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=[MARKET]&apikey=[ALPHAVANTAGE_KEY]',
                                                   'response_path' => 'Global Quote', // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => 'https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=[SEARCH_QUERY]&apikey=[ALPHAVANTAGE_KEY]', // false|[API endpoint with all market pairings]
                                                  ),


                           'binance' => array(
                                                   'endpoint' => 'https://www.binance.com/api/v3/ticker/24hr',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'binance_us' => array(
                                                   'endpoint' => 'https://api.binance.us/api/v3/ticker/24hr',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bit2c' => array(
                                                   'endpoint' => 'https://bit2c.co.il/Exchanges/[MARKET]/Ticker.json',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitbns' => array(
                                                   'endpoint' => 'https://bitbns.com/order/getTickerWithVolume',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitfinex' => array(
                                                   'endpoint' => 'https://api-pub.bitfinex.com/v2/tickers?symbols=ALL',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => '0', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'ethfinex' => array(
                                                   'endpoint' => 'https://api-pub.bitfinex.com/v2/tickers?symbols=ALL',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => '0', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitflyer' => array(
                                                   'endpoint' => 'https://api.bitflyer.com/v1/getticker?product_code=[MARKET]',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitmart' => array(
                                                   'endpoint' => 'https://api-cloud.bitmart.com/spot/v1/ticker',
                                                   'response_path' => 'data>tickers', // Delimit multiple depths with >
                                                   'multiple_results' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           // GET NEWEST DATA SETS (25 one hour buckets, SINCE WE #NEED# THE CURRENT PARTIAL DATA SET, 
                           // OTHERWISE WE DON'T GET THE LATEST TRADE VALUE AND CAN'T CALCULATE REAL-TIME VOLUME)
                           // Sort NEWEST first, 'multiple_results' MUST BE FALSE,
                           // (as we need to CUSTOM parse 25 different 1-hour data sets, AFTER generic data retrieval)
                           'bitmex' => array(
                                                   'endpoint' => 'https://www.bitmex.com/api/v1/trade/bucketed?binSize=1h&partial=true&count=25&symbol=[MARKET]&reverse=true',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitso' => array(
                                                   'endpoint' => 'https://api.bitso.com/v3/ticker/?book=[MARKET]',
                                                   'response_path' => 'payload', // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bitstamp' => array(
                                                   'endpoint' => 'https://www.bitstamp.net/api/v2/ticker/[MARKET]',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'btcmarkets' => array(
                                                   'endpoint' => 'https://api.btcmarkets.net/market/[MARKET]/tick',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'btcturk' => array(
                                                   'endpoint' => 'https://api.btcturk.com/api/v2/ticker',
                                                   'response_path' => 'data', // Delimit multiple depths with >
                                                   'multiple_results' => 'pair', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'buyucoin' => array(
                                                   'endpoint' => 'https://api.buyucoin.com/ticker/v1.0/liveData',
                                                   'response_path' => 'data', // Delimit multiple depths with >
                                                   'multiple_results' => 'marketName', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'bybit' => array(
                                                   'endpoint' => 'https://api-testnet.bybit.com/v2/public/tickers',
                                                   'response_path' => 'result', // Delimit multiple depths with >
                                                   'multiple_results' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'cex' => array(
                                                   'endpoint' => 'https://cex.io/api/tickers/BTC/USD/USDT/EUR/GBP',
                                                   'response_path' => 'data', // Delimit multiple depths with >
                                                   'multiple_results' => 'pair', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'coinbase' => array(
                                                   'endpoint' => 'https://api.pro.coinbase.com/products/[MARKET]/ticker',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => 'https://api.exchange.coinbase.com/products', // false|[API endpoint with all market pairings]
                                                  ),


                           'coindcx' => array(
                                                   'endpoint' => 'https://public.coindcx.com/exchange/ticker',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => 'market', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'coinex' => array(
                                                   'endpoint' => 'https://api.coinex.com/v1/market/ticker/all',
                                                   'response_path' => 'data>ticker', // Delimit multiple depths with >
                                                   'multiple_results' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),

                           
                           // 'multiple_results' MUST BE FALSE, as we have to CUSTOM parse through funky data structuring 
                           'coingecko' => array(
                                                   'endpoint' => 'https://api.coingecko.com/api/v3/simple/price?ids=[COINGECKO_ASSETS]&vs_currencies=[COINGECKO_PAIRS]&include_24hr_vol=true',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),
                                                  
                                                  
                           'coingecko_terminal' => array(
                                                   'endpoint' => 'https://api.geckoterminal.com/api/v2/networks/[MARKET]?include=base_token,quote_token',
                                                   'response_path' => 'data', // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'coinspot' => array(
                                                   'endpoint' => 'https://www.coinspot.com.au/pubapi/latest',
                                                   'response_path' => 'prices', // Delimit multiple depths with >
                                                   'multiple_results' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'crypto.com' => array(
                                                   'endpoint' => 'https://api.crypto.com/v2/public/get-ticker',
                                                   'response_path' => 'result>data', // Delimit multiple depths with >
                                                   'multiple_results' => 'i', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'gateio' => array(
                                                   'endpoint' => 'https://api.gateio.ws/api/v4/spot/tickers',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => 'currency_pair', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'gemini' => array(
                                                   'endpoint' => 'https://api.gemini.com/v1/pubticker/[MARKET]',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'graviex' => array(
                                                   'endpoint' => 'https://graviex.net//api/v2/tickers.json',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'hitbtc' => array(
                                                   'endpoint' => 'https://api.hitbtc.com/api/2/public/ticker',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'huobi' => array(
                                                   'endpoint' => 'https://api.huobi.pro/market/tickers',
                                                   'response_path' => 'data', // Delimit multiple depths with >
                                                   'multiple_results' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'idex' => array(
                                                   'endpoint' => 'https://api-sandbox.idex.io/v4/tickers',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => 'market', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),

                           
                           // 'multiple_results' MUST BE FALSE, as we have to CUSTOM parse through pairings etc
                           'jupiter_ag' => array(
                                                   'endpoint' => 'https://price.jup.ag/v4/price?ids=[JUP_AG_ASSETS]&vsToken=[JUP_AG_PAIRING]',
                                                   'response_path' => 'data', // Delimit multiple depths with >
                                                   'multiple_results' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'korbit' => array(
                                                   'endpoint' => 'https://api.korbit.co.kr/v1/ticker/detailed/all',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),

                           
                           // 'multiple_results' MUST BE FALSE, as we have to CUSTOM parse through funky data structuring 
                           'kraken' => array(
                                                   'endpoint' => 'https://api.kraken.com/0/public/Ticker?pair=[KRAKEN_PAIRS]',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'kucoin' => array(
                                                   'endpoint' => 'https://api.kucoin.com/api/v1/market/allTickers',
                                                   'response_path' => 'data>ticker', // Delimit multiple depths with >
                                                   'multiple_results' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           // 'response_path' MUST BE FALSE, as it varies dynamically (we set it dynamically later on in logic)
                           'loopring' => array(
                                                   'endpoint' => 'https://api3.loopring.io/api/v3/allTickers',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'luno' => array(
                                                   'endpoint' => 'https://api.mybitx.com/api/1/tickers',
                                                   'response_path' => 'tickers', // Delimit multiple depths with >
                                                   'multiple_results' => 'pair', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'okcoin' => array(
                                                   'endpoint' => 'https://www.okcoin.com/api/v5/market/tickers?instType=SPOT',
                                                   'response_path' => 'data', // Delimit multiple depths with >
                                                   'multiple_results' => 'instId', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'okex' => array(
                                                   'endpoint' => 'https://www.okx.com/api/v5/market/tickers?instType=SPOT',
                                                   'response_path' => 'data', // Delimit multiple depths with >
                                                   'multiple_results' => 'instId', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'poloniex' => array(
                                                   'endpoint' => 'https://api.poloniex.com/markets/ticker24h',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => 'symbol', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           // 'multiple_results' MUST BE FALSE, as we have to CUSTOM parse through funky data structuring 
                           'tradeogre' => array(
                                                   'endpoint' => 'https://tradeogre.com/api/v1/markets',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => false, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'unocoin' => array(
                                                   'endpoint' => 'https://api.unocoin.com/api/trades/in/all/all',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'upbit' => array(
                                                   'endpoint' => 'https://api.upbit.com/v1/ticker?markets=[UPBIT_PAIRS]',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => 'market', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'wazirx' => array(
                                                   'endpoint' => 'https://api.wazirx.com/api/v2/tickers',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => true, // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
                                                  ),


                           'zebpay' => array(
                                                   'endpoint' => 'https://www.zebapi.com/pro/v1/market',
                                                   'response_path' => false, // Delimit multiple depths with >
                                                   'multiple_results' => 'pair', // false|true[IF key name is the ID]|market_info_key_name
                                                   'single_results_list' => false, // false|[API endpoint with all market pairings]
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
   
   
   function exchange_api_data($selected_exchange, $market_id, $ticker_pairing_search=false) {
   
   global $ct;
   
   $selected_exchange = strtolower($selected_exchange);
   
   $prefixing_blacklist = array(
                             'binance', // Because 'binance_us' is a separate API
                             'coingecko', // Because 'coingecko_terminal' is a separate API
                            );
   
      // IF exchange API exists
      if ( isset($this->exchange_apis[$selected_exchange]) ) {
      return $this->fetch_exchange_data($selected_exchange, $market_id, $ticker_pairing_search);
      }
      // IF exchange API doesn't exist, check to see if we are using our prefix delimiter, for a possible 'prefixed' exchange name
      // (for end-user descriptiveness / UX, BUT ONLY IF NOT A BLACKLISTED PREFIX!)
      elseif ( !in_array($selected_exchange, $prefixing_blacklist) && stristr($selected_exchange, '_') ) {
        
           foreach ( $this->exchange_apis as $exchange_key => $unused ) {
           
           $exchange_key = strtolower($exchange_key);
               
               // AUTO-CHECK FOR PREFIX USAGE: EXCHANGEKEY_
               if ( stristr($selected_exchange, $exchange_key . '_') ) {
               return $this->fetch_exchange_data($exchange_key, $market_id, $ticker_pairing_search);
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
      
      	           
   return $result;
     
   }
   

   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // The passed $ticker_search parameter can be a ticker by itself like 'sol', OR INCLUDE A PAIRING like 'sol/btc'
   
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->pairing_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->pairing_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
   function ticker_markets_search($ticker_search, $specific_exchange=false) {
    
   global $ct;
   
   $temp = array();
   
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
       }
       
       
       // Include any added token presale items
       if ( !$specific_exchange || $specific_exchange && $specific_exchange == 'presale_usd_value' ) {
       
           foreach( $ct['opt_conf']['token_presales_usd'] as $key => $val ) {
           
               if ( stristr($key, $ticker_search) || $included_pairing && strtolower($included_pairing) == 'usd' && stristr($key, $ticker_only) ) {
               
               // Minimize calls
               $check_market_data = $this->market($key, 'presale_usd_value', $key);
               
                    if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
               
                    $results['presale_usd_value'][] = array(
                                                            'id' => $key,
                                                            'pairing' => 'usd',
                                                            'data' => $check_market_data,
                                                            );
                    
                         if ( $specific_exchange ) {
                         return $results;
                         }
                    
                    }
                    
               }
           
           }
       
       }
       // If specific exchange / specific id
       elseif ( $specific_exchange ) {
            
       $force_uppercase_search = array(
                                       'alphavantage_stock',
                                      );
            
       $force_lowercase_search = array(
                                       // Exchange key name here
                                      );
                                      
       
          // Auto-correct, if we know we ABSOLUTELY MUST USE ALL UPPER / LOWER CASE
          // (important to auto-correct early here, as we are setting the ID in the results)
          if ( in_array($specific_exchange, $force_uppercase_search) ) {
          $ticker_search = strtoupper($ticker_search);
          }
          elseif ( in_array($specific_exchange, $force_lowercase_search) ) {
          $ticker_search = strtolower($ticker_search);
          }
          
               
       // Minimize calls
       $check_market_data = $this->market($ticker_only, $specific_exchange, $ticker_search);
               
          if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
               
          $results[$specific_exchange][] = array(
                                             'id' => $ticker_search,
                                             'pairing' => $this->pairing_parse($specific_exchange, $ticker_search, $ticker_search),
                                             'data' => $check_market_data,
                                             );
       
          return $results;
       
          }

       }
         
         
       foreach ( $this->exchange_apis as $key => $val ) {
       
       $try_pairing = false; // RESET
       
       
          // IF it's flagged to skip searching alphavantage (to conserve LIMITED daily live request allowances)
          if ( $key == 'alphavantage_stock' && isset($_POST['skip_alphavantage_search']) && $_POST['skip_alphavantage_search'] == 'yes' ) {
          continue;
          }
           
           
          // APIs REGISTERED AS supporting 'multiple_results' / 'single_results_list' params, AND SPECIFIC OTHERS (like 'coingecko_terminal', etc)
          // (KRAKEN / REGULAR COINGECKO return multiple results, BUT are not 'registered' as such, due to parsing requirements)
          if ( $val['multiple_results'] || $val['single_results_list'] || $key == 'kraken' || stristr($key, 'coingecko') ) {
               
               
              if ( $key == 'kraken' ) {
              $try_pairing = strtoupper($ct['conf']['currency']['kraken_pairings_search']);
              }
              elseif ( $key == 'upbit' ) {
              $try_pairing = strtoupper($ct['conf']['currency']['upbit_pairings_search']);
              }
              elseif ( $key == 'jupiter_ag' ) {
              $try_pairing = strtoupper($ct['conf']['currency']['jupiter_ag_pairings_search']);
              }
          
              
              // Trying different pairings
              if ( $try_pairing ) {
              
                   
                   // RESET $try_pairing to included pairing ONLY, IF a specific pairing was included in the search string
                   // (prevents unnecessary loops)
                   if ( $included_pairing ) {
                   $try_pairing = strtoupper($included_pairing);
                   }
              
          
              $pairing_array = array_map( "trim", explode(',', $try_pairing) ); // TRIM ANY WHITESPACE
              
              
                   $run_already = false;
                   foreach ( $pairing_array as $pairing_val ) {
                        
                        if ( $run_already ) {
                        sleep(1); // Throttle multiple requests, to avoid be blocked
                        }

                   $check_results = $this->exchange_api_data($key, $ticker_search, $pairing_val); // SEARCH ONLY MODE (TICKER WITH PAIRING)
                   
                   $run_already = true;

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
                   

                   if ( $key == 'coingecko' ) {
                   $search_pairings = strtolower($ct['conf']['currency']['coingecko_pairings_search']);
                   $search_pairings = preg_replace('/\s+/', '', $search_pairings); // REMOVE ALL WHITESPACE
                   }
                   else {
                   $search_pairings = true;
                   }


              $check_results = $this->exchange_api_data($key, $ticker_search, $search_pairings); // SEARCH ONLY MODE (TICKER ONLY, UNLESS SPECIFIED)


                   if ( $check_results ) {
                   $results[$key] = $check_results;
                   }


              }
              
              
          }
          

       }

       
   return $results;
     
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function coingecko($force_prim_currency=null) {
      
   global $ct;
   
   $data = array();
   $sub_arrays = array();
   $result = array();
   
   // Don't overwrite global
   $coingecko_prim_currency = ( $force_prim_currency != null ? strtolower($force_prim_currency) : strtolower($ct['conf']['gen']['bitcoin_primary_currency_pair']) );
   
         
   // DON'T ADD ANY ERROR CHECKS HERE, OR RUNTIME MAY SLOW SIGNIFICANTLY!!
   
   
      // Covert NATIVE tickers to INTERNATIONAL for coingecko
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
   
   
   function coinmarketcap($force_prim_currency=null) {
      
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
      
      //var_dump($coinmarketcap_currencies);
      
      }
      
   
   // Don't overwrite global
   $coinmarketcap_prim_currency = strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']);
      
      
      // Covert NATIVE tickers to INTERNATIONAL for coinmarketcap
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
   
   
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->pairing_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->pairing_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
   function limited_apis_markets_search($exchange_key, $market_search, $cache_time) {
   
   global $ct;
   
   $exchange_api = $this->exchange_apis[$exchange_key];
   
   $possible_market_ids = array();
   
   
         // IF a PAIRING was included in the search string
         if ( stristr($market_search, '/') ) {
              
         $search_params = array_map( "trim", explode('/', $market_search) ); // TRIM ANY USER INPUT WHITESPACE

         $dyn_id = $search_params[0];
         $search_pairing = $search_params[1];

         }
         else {
         $dyn_id = $market_search;
         }
   
         
   $url = $exchange_api['single_results_list'];
   
   $url = preg_replace("/\[SEARCH_QUERY\]/i", $dyn_id, $url);
         
   $url = preg_replace("/\[ALPHAVANTAGE_KEY\]/i", $ct['conf']['ext_apis']['alphavantage_api_key'], $url);
   
   // API response data
   $response = @$ct['cache']->ext_data('url', $url, $cache_time);
   
   $data = json_decode($response, true);
   
   
       if ( is_array($data) ) {

       
                if ( $exchange_key == 'coinbase' ) {
            
            
                     foreach( $data as $val ) {
          
                
                         if (
                         !$search_pairing && isset($val['id']) && stristr($val['id'], $dyn_id)
                         || $search_pairing && isset($val['id']) && stristr($val['id'], $dyn_id) && stristr($val['id'], $search_pairing)
                         ) {
                    
                         // Minimize calls
                         $check_market_data = $this->market($dyn_id, $exchange_key, $val['id']);
                                   
                                   
                              if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                        
                              $possible_market_ids[] = array(
                                                                       'id' => $val['id'],
                                                                       'pairing' => $this->pairing_parse($exchange_key, $market_search, $val['id']),
                                                                       'data' => $check_market_data,
                                                                        );
                                                                        
                              }
                          
     
                         }
                         
                     
                     }
                
                }
                elseif ( $exchange_key == 'alphavantage_stock' ) {
                
                
                    if ( isset($data['bestMatches']) && is_array($data['bestMatches']) && sizeof($data['bestMatches']) > 0 ) {
                         
                         
                         foreach( $data['bestMatches'] as $result ) {
                         
                         //var_dump($dyn_id);
                         //var_dump($result["1. symbol"]);
                         //var_dump($result["8. currency"]);
                              
                              
                              if ( 
                              !$search_pairing && isset($result["1. symbol"]) && stristr($result["1. symbol"], $dyn_id)
                              || $search_pairing && isset($result["1. symbol"]) && isset($result["8. currency"]) && stristr($result["1. symbol"], $dyn_id) && stristr($result["8. currency"], $search_pairing)
                              ) {
                                   
                              
                                   // DON'T DO ANY POTENTIALLY ***LIVE*** MARKET CHECKS FOR THE ALPHAVANTAGE ***FREE*** TIER,
                                   // AS THE FREE TIER DAILY LIMITS ARE VERY LOW, AND COULD CUT OFF DAILY LIVE REQUESTS BEFORE END-OF-DAY!!!
                                   if ( $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 ) {
                                   
                                   $possible_market_ids[] = array(
                                                                                 'id' => $result["1. symbol"],
                                           // Even though we know the pairing, we still need to replace any MULTI-TICKER CURRENCY (NIS/CNY) with ticker used in-app
                                           // (for pairing UX in the app)
                                                                                 'pairing' => $this->pairing_parse($exchange_key, $market_search, $result["1. symbol"], $result["8. currency"]),
                                                                                 'data' => array('last_trade' => 'SKIPPED, SO FREE TIER STAYS WITHIN DAILY LIMITS! (upgrade your alphavantage API KEY to a PREMIUM tier [and adjust "AlphaVantage.co Per Minute Limit" HIGHER THAN 5 accordingly, in the "External APIs" section], to see price previews)'),
                                                                                  );
                                                                                  
                                   }
                                   else {
                    
                                   // Minimize calls
                                   $check_market_data = $this->market($dyn_id, $exchange_key, $result["1. symbol"]);
                                             
                                             
                                        if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                                  
                                        $possible_market_ids[] = array(
                                                                                 'id' => $result["1. symbol"],
                                           // Even though we know the pairing, we still need to replace any MULTI-TICKER CURRENCY (NIS/CNY) with ticker used in-app
                                           // (for pairing UX in the app)
                                                                                 'pairing' => $this->pairing_parse($exchange_key, $market_search, $result["1. symbol"], $result["8. currency"]),
                                                                                 'data' => $check_market_data,
                                                                                  );
                                                                                  
                                        }
                              
                              
                                   }
                                   
                                   
                              }
                              
                         
                         }
                     

                    }
                
                }

       
       }
       
       
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
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->pairing_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->pairing_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
   function pairing_parse($exchange_key, $market_search, $market_id, $known_pairing=false) {
   
   global $ct;
   
              
         // IF a PAIRING was included in the search string
         if ( stristr($market_search, '/') ) {
                   
         $search_params = array_map( "trim", explode('/', $market_search) ); // TRIM ANY USER INPUT WHITESPACE
     
         $search_ticker = $search_params[0];
         $search_pairing = $search_params[1];
         
         // Optimization for market searches WITH PAIRING INCLUDED
         // (SAVES BIG TIME ON SEARCH RUNTIME LENGTH)
         $known_pairing = $search_pairing;
     
         }
         else {
         $search_ticker = trim($market_search);
         }
              
         
         // IF we ALREADY KNOW THE PAIRING
         if ( $known_pairing ) {
         $pairing_match = $known_pairing;
         }
         else {
        
             
             // PRE-PROCESS BASED ON EXCHANGE
             // https://www.threesl.com/blog/special-characters-regular-expressions-escape/
             if ( $search_pairing ) {
             return strtolower($search_pairing);
             }
             elseif ( $exchange_key == 'alphavantage_stock' ) {
             
                if ( stristr($market_search, '.') ) {
                
                    if ( stristr($market_search, '.TRT') || stristr($market_search, '.TRV') ) {
                    return 'cad';
                    }
                    elseif ( stristr($market_search, '.SHH') || stristr($market_search, '.SHZ') ) {
                    return 'cny';
                    }
                    elseif ( stristr($market_search, '.DEX') ) {
                    return 'eur';
                    }
                    elseif ( stristr($market_search, '.LON') ) {
                    return 'gbp';
                    }
                    elseif ( stristr($market_search, '.BSE') ) {
                    return 'inr';
                    }
                
                }
                else {
                return 'usd';
                }
             
             }
             elseif ( $exchange_key == 'bitbns' ) {
             return 'inr';
             }
             elseif ( $exchange_key == 'loopring_amm' ) {
             $market_id = preg_replace("/AMM-/i", "", $market_id);
             }
             elseif ( $exchange_key == 'bitmex' || $exchange_key == 'luno' ) {
             $market_id = preg_replace("/XBT/i", "BTC", $market_id);
             }
             elseif ( $exchange_key == 'aevo' ) {
             $market_id = preg_replace("/-PERP/i", "-USD", $market_id);
             }
             // WTF Kraken, LMFAO :)
             elseif ( $exchange_key == 'kraken' ) {
             $market_id = preg_replace("/XXBTZ/i", "BTC", $market_id);
             $market_id = preg_replace("/XXBT/i", "BTC", $market_id);
             $market_id = preg_replace("/XBT/i", "BTC", $market_id);
             $market_id = preg_replace("/XETHZ/i", "ETH", $market_id);
             $market_id = preg_replace("/XETH/i", "ETH", $market_id);
             }
             elseif ( $exchange_key == 'bybit' && substr($market_id, 0, 4) == '1000' ) {
             $market_id = substr($market_id, 4);
             }
             elseif (
             $exchange_key == 'bitfinex' && substr($market_id, 0, 1) == 't'
             || 
             $exchange_key == 'ethfinex' && substr($market_id, 0, 1) == 't'
             ) {
             $market_id = substr($market_id, 1);
             }
             
             
         $parsed_market_id = $market_id;
             
             
             if ( in_array($exchange_key, $ct['dev']['hyphen_delimited_markets']) ) {
             $parsed_market_id = preg_replace("/(.*)-/i", "", $parsed_market_id);
             }
             elseif ( in_array($exchange_key, $ct['dev']['reverse_hyphen_delimited_markets']) ) {
             $parsed_market_id = preg_replace("/-(.*)/i", "", $parsed_market_id);
             }
             elseif ( in_array($exchange_key, $ct['dev']['underscore_delimited_markets']) ) {
             $parsed_market_id = preg_replace("/(.*)_/i", "", $parsed_market_id);
             }
             elseif ( in_array($exchange_key, $ct['dev']['forwardlash_delimited_markets']) ) {
             $parsed_market_id = preg_replace("/(.*)\//i", "", $parsed_market_id);
             }
             elseif ( in_array($exchange_key, $ct['dev']['colon_delimited_markets']) ) {
             $parsed_market_id = preg_replace("/(.*):/i", "", $parsed_market_id);
             }
             // OTHERWISE, we just remove the ticker the end user included in their search
             else {
             $parsed_market_id = preg_replace("/".$search_ticker."/i", "", $parsed_market_id);
             }
             
             
             // If we haven't registered all pairs yet this runtime, do it now
             // (we do a RUNTIME memory cache, to optimize / increase runtime speed)
             if ( sizeof($ct['registered_pairs']) < 1 ) {
             
             $parsed_market_id = strtolower($parsed_market_id); // Prep for dynamic logic below
                  
             // IF the TICKER was a PARTIAL MATCH, we may NOT have CLEANLY parsed out the pairing yet...
                  
             // HARD-CODED SPECIFIC / POPULAR pairing support (that we don't bundle with fresh install DEMO data)
             $other_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['additional_pairings_search']) );
                  
             // Coingecko pairing support
             $coingecko_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['coingecko_pairings_search']) );
                  
             // Kraken pairing support
             $kraken_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['kraken_pairings_search']) );
                  
             // Upbit pairing support
             $upbit_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['upbit_pairings_search']) );
                  
             // jupiter_ag pairing support
             $jupiter_ag_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['jupiter_ag_pairings_search']) );
              
                  
                  // Other pairings    
                  foreach ( $other_pairings as $pair_val ) {
                  $ct['registered_pairs'][] = $pair_val;
                  }
              
                  
                  // Coingecko pairings    
                  foreach ( $coingecko_pairings as $pair_val ) {
                  $ct['registered_pairs'][] = $pair_val;
                  }
              
                  
                  // Kraken pairings    
                  foreach ( $kraken_pairings as $pair_val ) {
                  $ct['registered_pairs'][] = $pair_val;
                  }
              
                  
                  // Upbit pairings    
                  foreach ( $upbit_pairings as $pair_val ) {
                  $ct['registered_pairs'][] = $pair_val;
                  }
              
                  
                  // jupiter_ag pairings    
                  foreach ( $jupiter_ag_pairings as $pair_val ) {
                  $ct['registered_pairs'][] = $pair_val;
                  }
                  
                  
                  // 'bitcoin_currency_markets' pairings
                  foreach ( $ct['opt_conf']['bitcoin_currency_markets'] as $pairing_key => $unused ) {
                  $ct['registered_pairs'][] = $pairing_key;
                  }
                  
                  
                  // 'crypto_pair' pairings
                  foreach ( $ct['opt_conf']['crypto_pair'] as $pairing_key => $unused ) {
                  $ct['registered_pairs'][] = $pairing_key;
                  }
          
                 
                  // Cleanup
                  if ( is_array($ct['registered_pairs']) ) { 
                  
                  // Remove whitespace
                  $ct['registered_pairs'] = array_map("trim", $ct['registered_pairs']);
                  
                  // To lowercase
                  $ct['registered_pairs'] = array_map("strtolower", $ct['registered_pairs']);
                  
                  // Remove duplicates
                  $ct['registered_pairs'] = array_unique($ct['registered_pairs']);
                  
                  // Sort by length, so we are checking for LONGER pairings first (to assure SAFE results)
                  usort($ct['registered_pairs'], array($ct['gen'], 'usort_length') );
     
                  }
             
             
             }
                  
                  
             foreach ( $ct['registered_pairs'] as $val ) {
                  
                 if ( !$pairing_match && !in_array($parsed_market_id, $ct['registered_pairs']) && preg_match("/".$val."/i", $parsed_market_id) ) {
                 $pairing_match = $val;
                 }
                  
             }
             
             
         }
         
        
   $result = strtolower( ( $pairing_match ? $pairing_match : $parsed_market_id ) ); // Prep for dynamic logic below
        
        
        // Convert WRAPPED CRYPTO TICKERS to their NATIVE tickers
        if ( $result == 'tbtc' || $result == 'wbtc' ) {
        $result = 'btc';
        }
        elseif ( $result == 'weth' ) {
        $result = 'eth';
        }
        // Convert INTERNATIONAL TICKERS to their NATIVE tickers
        elseif ( $result == 'cny' ) {
        $result = 'rmb';
        }
        elseif ( $result == 'ils' ) {
        $result = 'nis';
        }
        
        
        if ( isset($result) ) {
        $result = trim($result);
        }
        
        
        if ( !isset($result) || $result == '' ) {
	   $ct['gen']->log( 'other_error', 'No pairing found in ct["api"]->pairing_parse() (exchange: ' . $exchange_key . '; market_search: ' . $market_search . '; market_id: ' . $market_id . ';)');
        }


   return $result;
      
   }
                           
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->pairing_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->pairing_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
   function fetch_exchange_data($exchange_key, $market_id, $ticker_pairing_search=false) {
        
   global $ct;
   
   $possible_market_ids = array();
   
   $limited_apis = array();
   
   $market_id = trim($market_id); // TRIM ANY USER INPUT WHITESPACE

   // DEFAULTS         
   $exchange_api = $this->exchange_apis[$exchange_key];
   
   $dyn_id = $market_id;
   
   $cache_time = ( $exchange_key == 'alphavantage_stock' ? $ct['throttled_api_cache_time']['alphavantage.co'] : $ct['conf']['power']['last_trade_cache_time'] );
          
   $url = $exchange_api['endpoint'];
   
         
         // IF ticker search AND a LIMITED API WITH A MARKETS LIST ENDPOINT
         if ( $ticker_pairing_search && $exchange_api['single_results_list'] ) {
         return $this->limited_apis_markets_search($exchange_key, $market_id, $cache_time);
         }
         // IF a PAIRING was included in the search string
         elseif ( $ticker_pairing_search && stristr($market_id, '/') ) {
              
         $search_params = array_map( "trim", explode('/', $market_id) ); // TRIM ANY USER INPUT WHITESPACE

         $dyn_id = $search_params[0];
         $search_pairing = $search_params[1];
         
         $required_pairing = $search_pairing;

         }
         // ELSE IF $ticker_pairing_search is NOT boolean, it's a REQUIRED pairing for SEARCHING this exchange for a certain ticker
         elseif ( is_bool($ticker_pairing_search) !== true ) {
         $required_pairing = $ticker_pairing_search;
         }
             
         
         if ( $exchange_key == 'alphavantage_stock' ) {

         // Alphavantage market IDs always seem to be uppercase, so if this is a search, convert to all uppercase
         $dyn_id = ( $ticker_pairing_search ? strtoupper($dyn_id) : $dyn_id );
         
         $url = preg_replace("/\[ALPHAVANTAGE_KEY\]/i", $ct['conf']['ext_apis']['alphavantage_api_key'], $url);

         }
         elseif ( $exchange_key == 'kraken' ) {
              
         $dyn_id = strtoupper($dyn_id);
         
         
             if ( $required_pairing ) {
              
             $required_pairing = strtoupper($required_pairing);
             
             $url = preg_replace("/\[KRAKEN_PAIRS\]/i", $dyn_id . $required_pairing, $url);
             
             }
             else {
             $url = preg_replace("/\[KRAKEN_PAIRS\]/i", $ct['kraken_pairs'], $url);
             }


         }
         elseif ( $exchange_key == 'coingecko_terminal' ) {
              
         // DO NOT CONVERT TO LOWERCASE!!!
                
         $id_parse = array_map( "trim", explode("||", $dyn_id) );
           
               
             // Auto-correct for some inconsistancies in the API's sementics
             if ( $id_parse[0] == 'ethereum' ) {
             $id_parse[0] = 'eth';
             }
             elseif ( $id_parse[0] == 'sol' ) {
             $id_parse[0] = 'solana';
             }
             
         
         $url = preg_replace("/\[MARKET\]/i", $id_parse[0].'/pools/'.$id_parse[1], $url);

         }
         elseif ( $exchange_key == 'coingecko' ) {
              
         $dyn_id = strtolower($dyn_id);
              
         // (coingecko's response path is DYNAMIC, based off market id)
         $exchange_api['response_path'] = $dyn_id;
         
             
             // IF app id wasn't bundled yet into the single call format we use for coingecko,
             // add it now, or we WON'T GET ANY RELEVANT RESULTS (when VALIDATING 'add market' search results, etc)
             if ( !stristr($ct['coingecko_assets'], $dyn_id) ) {
             $ct['coingecko_assets'] = $ct['coingecko_assets'] . ',' . $dyn_id;
             }
                
         
             if ( !$search_pairing && $required_pairing ) {
             $url = preg_replace("/\[COINGECKO_ASSETS\]/i", $dyn_id, $url);
             $url = preg_replace("/\[COINGECKO_PAIRS\]/i", strtolower($required_pairing), $url);
             }
             elseif ( $search_pairing && !stristr($ticker_pairing_search, $search_pairing) ) {
              
             $required_pairing = strtolower($ticker_pairing_search) . ',' . strtolower($search_pairing);
             
             $url = preg_replace("/\[COINGECKO_ASSETS\]/i", $dyn_id, $url);
             
             $url = preg_replace("/\[COINGECKO_PAIRS\]/i", strtolower($required_pairing), $url);
             
             }
             else {
             $url = preg_replace("/\[COINGECKO_ASSETS\]/i", $ct['coingecko_assets'], $url);
             $url = preg_replace("/\[COINGECKO_PAIRS\]/i", $ct['coingecko_pairs'], $url);
             }
             
             
             // RESET to true (instead of specific pairings), for rest of logic
             if ( $ticker_pairing_search ) {
             $temp_pairing_array = array_map( "trim", explode(",", $ticker_pairing_search) );
             $ticker_pairing_search = true; 
             }
         
         
         }
         elseif ( $exchange_key == 'upbit' ) {
              
         $dyn_id = strtoupper($dyn_id);

         
             if ( $required_pairing ) {
              
             $required_pairing = strtoupper($required_pairing);
             
             $url = preg_replace("/\[UPBIT_PAIRS\]/i", $required_pairing . '-' . $dyn_id, $url);

             }
             else {
             $url = preg_replace("/\[UPBIT_PAIRS\]/i", $ct['upbit_pairs'], $url);
             }


         }
         elseif ( $exchange_key == 'jupiter_ag' ) {
         
         $search_pairing = false; // NOT used for parsing jupiter_ag responses
              
         $dyn_id = strtoupper($dyn_id);

         
             if ( $required_pairing ) {
              
             $required_pairing = strtoupper($required_pairing);

             
                 if ( $required_pairing == 'BTC' ) {
                 $required_pairing = 'WBTC'; // It's ALWAYS wrapped BTC on Solana's jupiter_ag
                 }

                  
             $jup_pairs = explode('/', $dyn_id . '/' . $required_pairing);
         
             $url = preg_replace("/\[JUP_AG_ASSETS\]/i", $jup_pairs[0], $url);
             
             }
             else {
             
             $jup_pairs = explode('/', $dyn_id);
         
             $url = preg_replace("/\[JUP_AG_ASSETS\]/i", $ct['jupiter_ag_pairs'][ $jup_pairs[1] ], $url);
             
             }
         
         
         $dyn_id = $jup_pairs[0];
         
         $url = preg_replace("/\[JUP_AG_PAIRING\]/i", $jup_pairs[1], $url);

         }
         elseif ( $exchange_key == 'loopring' ) {
         
              if ( substr($dyn_id, 0, 4) == "AMM-" ) {
              $exchange_api['response_path'] = 'pools';
              }
              else {
              $exchange_api['response_path'] = 'markets';
              }
              
         }
         elseif ( $exchange_key == 'luno' ) {
         
             if ( $ticker_pairing_search && strtolower($dyn_id) == 'btc' ) {
             $dyn_id = 'xbt';
             }

         }
   
   
         // When we are getting SPECIFIED markets (NOT all markets on the exchange)
         if ( !$ticker_pairing_search || $exchange_api['single_results_list'] ) {
         $url = preg_replace("/\[MARKET\]/i", $dyn_id, $url);
         }
          
          
   // API response data
   $response = @$ct['cache']->ext_data('url', $url, $cache_time);
          
   $data = json_decode($response, true);
         
         
         // If our data set is in a subarray, dig down to SET IT AS THE BASE in $data
         if ( is_array($data) && $exchange_api['response_path'] ) {
              
         $response_path = explode('>', $exchange_api['response_path']);

              foreach( $response_path as $val ) {
              $data = $data[$val];
              }

         }
         
         
         // Optimize results
         // IF $exchange_api['multiple_results'] SET AS: true|[associative key, including numbers]
         if (
         is_array($data) && $exchange_api['multiple_results']
         || is_array($data) && is_bool($exchange_api['multiple_results']) !== true
         ) {
         
              
              // If a specific key name is always holding the market ID info as a value
              if ( is_bool($exchange_api['multiple_results']) !== true ) {
              
         
                   foreach ($data as $val) {
                  
                  
                       if ( isset($val[ $exchange_api['multiple_results'] ]) ) {
                            
                            
                            if (
                            !$ticker_pairing_search && $val[ $exchange_api['multiple_results'] ] == $dyn_id
                            || $ticker_pairing_search && !$search_pairing && stristr($val[ $exchange_api['multiple_results'] ], $dyn_id)
                            || $ticker_pairing_search && $search_pairing && stristr($val[ $exchange_api['multiple_results'] ], $dyn_id)
                            && stristr($val[ $exchange_api['multiple_results'] ], $search_pairing)
                            ) {
                            // Do nothing
                            }
                            else {
                            continue; // Skip this loop
                            }
                            
                       
                            // Workaround for weird zebpay API bug, where they include a second array object
                            // with same 'multiple_results' (key name = 'pair') property, that's mostly a NULL data set
                            if ( $exchange_key == 'zebpay' ) {
                                 
                            $test_data = $val;
                       
                                 if ( isset($test_data["market"]) && $test_data["market"] > 0 ) {
                                 
                                 
                                    if ( $ticker_pairing_search ) {
                                         
                                    // Minimize calls
                                    $check_market_data = $this->market($dyn_id, $exchange_key, $val[ $exchange_api['multiple_results'] ]);
                              
                              
                                        if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                                        
                                        $possible_market_ids[] = array(
                                                                       'id' => $val[ $exchange_api['multiple_results'] ],
                                                                       'pairing' => $this->pairing_parse($exchange_key, $market_id, $val[ $exchange_api['multiple_results'] ]),
                                                                       'data' => $check_market_data,
                                                                      );
                                                                                 
                                        }
                                        

                                    }
                                    else {
                                    $data = $test_data;
                                    break; // will assure leaving the foreach loop immediately
                                    }
                                    
                                 
                                 }
                                 
                            }
                            else {
                            
                                 if ( $ticker_pairing_search ) {
                                         
                                 // Minimize calls
                                 $check_market_data = $this->market($dyn_id, $exchange_key, $val[ $exchange_api['multiple_results'] ]);
                              
                              
                                        if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                                        
                                        $possible_market_ids[] = array(
                                                                   'id' => $val[ $exchange_api['multiple_results'] ],
                                                                   'pairing' => $this->pairing_parse($exchange_key, $market_id, $val[ $exchange_api['multiple_results'] ]),
                                                                   'data' => $check_market_data,
                                                                  );
                                                                  
                                        }
                                 
                                 
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
              elseif ( $ticker_pairing_search ) {
         
                   foreach ($data as $key => $val) {
                       
                       if (
                       !$search_pairing && stristr($key, $dyn_id)
                       || $search_pairing && stristr($key, $dyn_id) && stristr($key, $search_pairing)
                       ) {

                            if ( $exchange_key == 'jupiter_ag' ) {
                            // Even though we know the pairing, we still need to replace any 'wrapped' crypto's token with it's native ticker
                            // (for pairing UX in the app)
                            $detect_pairing = $this->pairing_parse($exchange_key, $market_id, $key, $val['vsTokenSymbol']);
                            }
                            else {
                            $detect_pairing = $this->pairing_parse($exchange_key, $market_id, $key);
                            }
                       
                                         
                       // Minimize calls
                       $check_market_data = $this->market($dyn_id, $exchange_key, $key);
                              
                              
                            if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                                             
                            $possible_market_ids[] = array(
                                                                   'id' => $key,
                                                                   'pairing' => $detect_pairing,
                                                                   'data' => $check_market_data,
                                                                  );
                                                                  
                            }
                                                                  
                                                                  
                       }                      
                        
                   }
                   
              }
              // If the top level (parent) key name IS THE MARKET ID ITSELF
              elseif ( isset($data[$dyn_id]) ) {
              $data = $data[$dyn_id];
              }

         
         }
         elseif ( $ticker_pairing_search && $exchange_key == 'kraken' ) {
      
              foreach ($data as $key => $val) {
                   
                 if ( $key == 'result' ) {
                   
                    foreach ($val as $key2 => $unused) {
                                         
                    // Minimize calls
                    $check_market_data = $this->market($dyn_id, $exchange_key, $key2);
                              
                              
                         if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                       
                         $possible_market_ids[] = array(
                                                                   'id' => $key2,
                                                                   'pairing' => $this->pairing_parse($exchange_key, $market_id, $key2),
                                                                   'data' => $check_market_data,
                                                                  );
                                                                  
                         }

                       
                    }
                 
                 }
               
              }
                   
         }
         elseif ( $ticker_pairing_search && $exchange_key == 'coingecko' ) {
      
              foreach( $temp_pairing_array as $pair ) {
                   
              $pair = strtolower($pair);
              
                   if ( isset($data[$pair]) ) {
                                         
                   // Minimize calls
                   $check_market_data = $this->market($dyn_id, 'coingecko_' . $pair, $dyn_id);
                              
                              
                         if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                       
                         $possible_market_ids[] = array(
                                                  'id' =>  $dyn_id,
                                                  'pairing' => $pair,
                                                  'data' => $check_market_data,
                                                 );
                                                 
                         }
                         
                           
                   }    
              
              }
              
         }
         elseif ( $ticker_pairing_search && $exchange_key == 'coingecko_terminal' ) {
      
              if ( isset($data['attributes']) ) {
                                         
              // Minimize calls
              $check_market_data = $this->market($dyn_id, $exchange_key, $dyn_id);
                              
                              
                         if ( isset($check_market_data['last_trade']) && $check_market_data['last_trade'] > 0 ) {
                       
                         $possible_market_ids[] = array(
                                             'id' => $dyn_id,
                                             'pairing' => $this->pairing_parse($exchange_key, $market_id, $dyn_id),
                                             'data' => $check_market_data,
                                            );
                                            
                         }
                         
                         
              }    
                   
         }
         
         
         // If no data
         if ( !$ticker_pairing_search && !is_array($data) || $ticker_pairing_search && sizeof($possible_market_ids) < 1 ) {
         
              
              // DEBUG LOGGING
              if (
              $ticker_pairing_search && $ct['conf']['power']['debug_mode'] == 'all'
              || $ticker_pairing_search && $ct['conf']['power']['debug_mode'] == 'markets'
              ) {
              
              $ct['gen']->log(
              		    'notify_debug',
              		    'NO DATA for market: "' . $dyn_id . ( $required_pairing ? '/' . $required_pairing : '' ) . '" @ ' . $exchange_key . '-SEARCH_ONLY',
              		    false,
              		    'no_market_data_' . $exchange_key . '-SEARCH_ONLY' . $dyn_id . ( $required_pairing ? $required_pairing : '' )
              		    );
         
              }
              
         
         $ct['gen']->log(
         		    'notify_error',
         		    'NO DATA for market: "' . $dyn_id . ( $required_pairing ? '/' . $required_pairing : '' ) . '" @ ' . $exchange_key,
         		    false,
         		    'no_market_data_' . $exchange_key . $dyn_id . ( $required_pairing ? $required_pairing : '' )
         		    );
         
         return false;
          
         }
         elseif ( $ticker_pairing_search  ) {
         return $possible_market_ids;  
         }
         else {
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
   
         
          if ( $ct['conf']['power']['debug_mode'] == 'all' || $ct['conf']['power']['debug_mode'] == 'all_telemetry' || $ct['conf']['power']['debug_mode'] == 'memory_usage_telemetry' ) {
         	
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
               
               gc_collect_cycles(); // Clean memory cache

               }


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
           
      //var_dump($data);
      
      $result = array(
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
      
      
         foreach ($data as $key => $val) {
              
            if ( $key == 'result' ) {
              
               foreach ($val as $key2 => $unused) {
                 
                 if ( $key2 == $mrkt_id ) {
                  
                 $result = array(
                                 'last_trade' => $val[$key2]["c"][0],
                                 '24hr_asset_vol' => $val[$key2]["v"][1],
                                 '24hr_pair_vol' => null // Unavailable, set null
                       		     );
                  
                 }
             
               }
            
            }
          
         }
      
      
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
    
    
    
      elseif ( stristr( $sel_exchange , 'coingecko_') ) {
     
      $coingecko_route = explode('_', $sel_exchange );
      $coingecko_route = strtolower($coingecko_route[1]);
      
           
           // Coingecko terminal ( https://www.geckoterminal.com/dex-api )
           // Use data from coingecko, if API attributes exist
           if ( $coingecko_route == 'terminal' && isset($data['attributes']) ) {
     
     	 $result = array(
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
    		else {
    		$result['24hr_prim_currency_vol'] = null;
    		}
        
      
      }
   
   
   return $result;
   
   }
   

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
      
   
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>