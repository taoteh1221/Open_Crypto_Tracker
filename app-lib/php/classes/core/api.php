<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// !!!!!!! MAKE SURE API'S TLD HAS SUPPORT ADDED IN 'top_level_domain_map' in Admin Config DEVELOPER section !!!!!!!
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!



class oct_api {
	
// Class variables / arrays
var $oct_var1;
var $oct_var2;
var $oct_var3;
var $oct_array1 = array();

      

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function bitcoin($request) {
    
   global $oct_conf, $oct_cache;
         
       
      if ( $request == 'height' ) {
      $url = 'https://blockchain.info/q/getblockcount';
      }
      elseif ( $request == 'difficulty' ) {
      $url = 'https://blockchain.info/q/getdifficulty';
      }
         
   $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['chainstats_cache_time']);
       
   return (float)$response;
     
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function coingecko($force_prim_currency=null) {
      
   global $base_dir, $oct_conf, $oct_cache;
   
   $data = array();
   $sub_arrays = array();
   $result = array();
   
   // Don't overwrite global
   $coingecko_prim_currency = ( $force_prim_currency != null ? strtolower($force_prim_currency) : strtolower($oct_conf['gen']['btc_prim_currency_pairing']) );
   
         
   // DON'T ADD ANY ERROR CHECKS HERE, OR RUNTIME MAY SLOW SIGNIFICANTLY!!
      
   
      // Batched / multiple API calls, if 'mcap_ranks_max' is greater than 'coingecko_api_batched_max'
      if ( $oct_conf['power']['mcap_ranks_max'] > $oct_conf['dev']['coingecko_api_batched_max'] ) {
      
          $loop = 0;
          $calls = ceil($oct_conf['power']['mcap_ranks_max'] / $oct_conf['dev']['coingecko_api_batched_max']);
         
          while ( $loop < $calls ) {
         
          $url = 'https://api.coingecko.com/api/v3/coins/markets?per_page=' . $oct_conf['dev']['coingecko_api_batched_max'] . '&page=' . ($loop + 1) . '&vs_currency=' . $coingecko_prim_currency . '&price_change_percentage=1h,24h,7d,14d,30d,200d,1y';
            
              if ( $loop > 0 && $oct_cache->update_cache($base_dir . '/cache/secured/external_data/' . md5($url) . '.dat', $oct_conf['power']['mcap_cache_time']) == true ) {
              usleep(150000); // Wait 0.15 seconds between consecutive calls, to avoid being blocked / throttled by external server
              }
         
          $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['mcap_cache_time']);
   
          $sub_arrays[] = json_decode($response, true);
         
          $loop = $loop + 1;
         
          }
      
      }
      else {
      	
      $response = @$oct_cache->ext_data('url', 'https://api.coingecko.com/api/v3/coins/markets?per_page='.$oct_conf['power']['mcap_ranks_max'].'&page=1&vs_currency='.$coingecko_prim_currency.'&price_change_percentage=1h,24h,7d,14d,30d,200d,1y', $oct_conf['power']['mcap_cache_time']);
      
      $sub_arrays[] = json_decode($response, true);
      
      }
         
         
   // DON'T ADD ANY ERROR CHECKS HERE, OR RUNTIME MAY SLOW SIGNIFICANTLY!!
   
      
      // Merge any sub arrays into one data set
      foreach ( $sub_arrays as $sub ) {
      $data = array_merge($data, $sub);
      }
      
   
      if ( is_array($data) ) {
         
          foreach ($data as $key => $unused) {
            
              if ( $data[$key]['symbol'] != '' ) {
              $result[strtolower($data[$key]['symbol'])] = $data[$key];
              }
       
          }
        
      }
           
           
   gc_collect_cycles(); // Clean memory cache
   
   return $result;
     
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // https://core.telegram.org/bots/api
   
   // https://core.telegram.org/bots/api#making-requests
   
   // https://api.telegram.org/bot{my_bot_token}/setWebhook?url={url_to_send_updates_to}
   
   // https://api.telegram.org/bot{my_bot_token}/deleteWebhook
   
   // https://api.telegram.org/bot{my_bot_token}/getWebhookInfo
   
   function telegram($mode) {
      
   global $oct_conf, $oct_cache;
   
      if ( $mode == 'updates' ) {
      
      // Don't cache data, we are storing it as a specific (secured) cache var instead
      $response = @$oct_cache->ext_data('url', 'https://api.telegram.org/bot'.$oct_conf['comms']['telegram_bot_token'].'/getUpdates', 0);
         
      $telegram_chatroom = json_decode($response, true);
   
      $telegram_chatroom = $telegram_chatroom['result']; 
   
          foreach( $telegram_chatroom as $chat_key => $chat_unused ) {
      
              // Overwrites any earlier value while looping, so we have the latest data
              if ( $telegram_chatroom[$chat_key]['message']['chat']['username'] == trim($oct_conf['comms']['telegram_your_username']) ) {
              $telegram_user_data = $telegram_chatroom[$chat_key];
              }
      
          }
   
      return $telegram_user_data;
      
      }
      elseif ( $mode == 'webhook' ) {
         
      // Don't cache data, we are storing it as a specific (secured) cache var instead
      $get_telegram_webhook_data = @$oct_cache->ext_data('url', 'https://api.telegram.org/bot'.$oct_conf['comms']['telegram_bot_token'].'/getWebhookInfo', 0);
         
      $telegram_webhook = json_decode($get_telegram_webhook_data, true);
      
      // logic here
      
      }
      
      
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function etherscan($block_info) {
    
   global $base_dir, $oct_conf, $oct_cache;
   
      if ( trim($oct_conf['gen']['etherscan_key']) == '' ) {
      return false;
      }
   
   $url = 'https://api.etherscan.io/api?module=proxy&action=eth_blockNumber&apikey=' . $oct_conf['gen']['etherscan_key'];
     
   $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['chainstats_cache_time']);
       
   $data = json_decode($response, true);
     
   $block_number = $data['result'];
       
       
      if ( !$block_number ) {
      return;
      }
      else {
            
          // Non-dynamic cache file name, because filename would change every recache and create cache bloat
          if ( $oct_cache->update_cache('cache/secured/external_data/eth-stats.dat', $oct_conf['power']['chainstats_cache_time'] ) == true ) {
            
          $url = 'https://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag='.$block_number.'&boolean=true&apikey=' . $oct_conf['gen']['etherscan_key'];
          $response = @$oct_cache->ext_data('url', $url, 0); // ZERO TO NOT CACHE DATA (WOULD CREATE CACHE BLOAT)
            
          $oct_cache->save_file($base_dir . '/cache/secured/external_data/eth-stats.dat', $response);
            
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
   
   
   function coinmarketcap($force_prim_currency=null) {
      
   global $oct_conf, $oct_cache, $oct_gen, $coinmarketcap_currencies, $mcap_data_force_usd, $cmc_notes;
   
   $result = array();
   
   
      if ( trim($oct_conf['gen']['cmc_key']) == null ) {
      	
      $oct_gen->log(
      		    'notify_error',
      		    '"cmc_key" (free API key) is not configured in Admin Config GENERAL section',
      		    false,
      		    'cmc_key'
      		    );
      
      return false;
      
      }
      
   
   // Don't overwrite global
   $coinmarketcap_prim_currency = strtoupper($oct_conf['gen']['btc_prim_currency_pairing']);
      
         
      if ( $force_prim_currency != null ) {
      $convert = strtoupper($force_prim_currency);
      $mcap_data_force_usd = null;
      }
      elseif ( in_array($coinmarketcap_prim_currency, $coinmarketcap_currencies) ) {
      $convert = $coinmarketcap_prim_currency;
      $mcap_data_force_usd = null;
      }
      // Default to USD, if currency is not supported
      else {
      $cmc_notes = 'Coinmarketcap.com does not support '.$coinmarketcap_prim_currency.' stats,<br />showing USD stats instead.';
      $convert = 'USD';
      $mcap_data_force_usd = 1;
      }
         
      
   $headers = [
               'Accepts: application/json',
               'X-CMC_PRO_API_KEY: ' . $oct_conf['gen']['cmc_key']
      	      ];
   
      
   $cmc_params = array(
                       'start' => '1',
                       'limit' => $oct_conf['power']['mcap_ranks_max'],
                       'convert' => $convert
                       );
   
   
   $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
         
   $qs = http_build_query($cmc_params); // query string encode the parameters
      
   $request = "{$url}?{$qs}"; // create the request URL
   
   $response = @$oct_cache->ext_data('url', $request, $oct_conf['power']['remote_api_timeout'], null, null, null, $headers);
      
   $data = json_decode($response, true);
           
   $data = $data['data'];
              
   
      if ( is_array($data) ) {
         
          foreach ($data as $key => $unused) {
            
              if ( $data[$key]['symbol'] != '' ) {
              $result[strtolower($data[$key]['symbol'])] = $data[$key];
              }
          
          }
        
      gc_collect_cycles(); // Clean memory cache
      return $result;
      
      }
   
           
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // Credit: https://www.alexkras.com/simple-rss-reader-in-85-lines-of-php/
   function rss($url, $theme_selected, $feed_size, $cache_only=false){
      
   global $base_dir, $oct_conf, $oct_var, $oct_cache, $oct_gen, $fetched_feeds;
   
   
      if ( !isset($_SESSION[$fetched_feeds]['all']) ) {
      $_SESSION[$fetched_feeds]['all'] = 0;
      }
      // Never re-cache FROM LIVE more than 'news_feed_batched_max' (EXCEPT for cron runtimes pre-caching), 
      // to avoid overloading low resource devices (raspi / pine64 / etc) and creating long feed load times
      elseif ( $_SESSION[$fetched_feeds]['all'] >= $oct_conf['dev']['news_feed_batched_max'] && $cache_only == false ) {
      return '<span class="red">Live data fetching limit reached (' . $_SESSION[$fetched_feeds]['all'] . ').</span>';
      }
      
   
   $news_feed_cache_min_max = explode(',', $oct_conf['dev']['news_feed_cache_min_max']);
   // Cleanup
   $news_feed_cache_min_max = array_map('trim', $news_feed_cache_min_max);
      
   $rss_feed_cache_time = rand($news_feed_cache_min_max[0], $news_feed_cache_min_max[1]);
                                    
         
      // If we will be updating the feed
      if ( $oct_cache->update_cache($base_dir . '/cache/secured/external_data/' . md5($url) . '.dat', $rss_feed_cache_time) == true ) {
      
      
      $_SESSION[$fetched_feeds]['all'] = $_SESSION[$fetched_feeds]['all'] + 1; // Mark as a fetched feed, since it's going to update
      
      $endpoint_tld_or_ip = $oct_gen->get_tld_or_ip($url);
   
         
          if ( $oct_conf['dev']['debug'] == 'all' || $oct_conf['dev']['debug'] == 'all_telemetry' || $oct_conf['dev']['debug'] == 'memory_usage_telemetry' ) {
         	
          $oct_gen->log(
         			  'system_debug',
         			  $endpoint_tld_or_ip . ' news feed updating ('.$_SESSION[$fetched_feeds]['all'].'), CURRENT script memory usage is ' . $oct_gen->conv_bytes(memory_get_usage(), 1) . ', PEAK script memory usage is ' . $oct_gen->conv_bytes(memory_get_peak_usage(), 1) . ', php_sapi_name is "' . php_sapi_name() . '"'
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
              usleep(7100000); // 7.1 seconds (Reddit only allows rss feed connections every 7 seconds from ip addresses ACCORDING TO THEM)
              }
              else {
              usleep(550000); // 0.55 seconds
              }
            
          }
   
               
      $_SESSION[$fetched_feeds][$tld_session] = $_SESSION[$fetched_feeds][$tld_session] + 1;	
   
         
      } // END if updating feed
         
               
   // Get feed data (whether cached or re-caching live data)
   $response = @$oct_cache->ext_data('url', $url, $rss_feed_cache_time); 
         
      
      // Format output (UNLESS WE ARE ONLY CACHING DATA)
      if ( !$cache_only ) {
         
      $rss = simplexml_load_string($response);
      
          if ( $rss == false ) {
          gc_collect_cycles(); // Clean memory cache
          return '<span class="red">Error retrieving feed data.</span>';
          }
                     
      $html .= '<ul>';
      
      $html_hidden .= '<ul class="hidden" id="'.md5($url).'">';
      
      $mark_new = ' &nbsp; <img alt="" src="templates/interface/media/images/auto-preloaded/twotone_fiber_new_' . $theme_selected . '_theme_48dp.png" height="25" title="New Article (under ' . $oct_conf['power']['news_feed_entries_new'] . ' days old)" />';
             
      $now_timestamp = time();
             
      $count = 0;
             
	      // Atom format
	      if ( sizeof($rss->entry) > 0 ) {
	             
	      $sortable_feed = array();
	               
		      foreach($rss->entry as $item) {
		      $sortable_feed[] = $item;
		      }
		               
		  $usort_results = usort($sortable_feed,  array('oct_gen', 'timestamps_usort_newest') );
		               
		      if ( !$usort_results ) {
		      $oct_gen->log( 'other_error', 'RSS feed failed to sort by newest items (' . $url . ')');
		      }
		             
		            
		      foreach($sortable_feed as $item) {
		                  
			     // If data exists, AND we aren't just caching data during a cron job
			     if ( trim($item->title) != '' && $feed_size > 0 ) {
			               
				     if ( $item->pubDate != '' ) {
				     $item_date = $item->pubDate;
				     }
				     elseif ( $item->published != '' ) {
				     $item_date = $item->published;
				     }
				     elseif ( $item->updated != '' ) {
				     $item_date = $item->updated;
				     }
				               
				     if ( !$item->link['href'] && $item->enclosure['url'] ) {
				     $item_link = $item->enclosure['url'];
				     }
				     elseif ( $item->link['href'] != '' ) {
				     $item_link = $item->link['href'];
				     }
			                  
			     $date_array = date_parse($item_date);
			                  
			     $month_name = date("F", mktime(0, 0, 0, $date_array['month'], 10));
			                  
			     $date_ui = $month_name . ' ' . $oct_gen->ordinal($date_array['day']) . ', ' . $date_array['year'] . ' @ ' . substr("0{$date_array['hour']}", -2) . ':' . substr("0{$date_array['minute']}", -2);
			                  
				     // If publish date is OVER 'news_feed_entries_new' days old, DONT mark as new
				     if ( $oct_var->num_to_str($now_timestamp) > $oct_var->num_to_str( strtotime($item_date) + ($oct_conf['power']['news_feed_entries_new'] * 86400) ) ) { // 86400 seconds == 1 day
				     $mark_new = null;
				     }
				                  
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
	      // Standard RSS format
	      elseif ( sizeof($rss->channel->item) > 0 ) {
	             
	      $sortable_feed = array();
	               
	          foreach($rss->channel->item as $item) {
	          $sortable_feed[] = $item;
	          }
	               
	      $usort_results = usort($sortable_feed, array('oct_gen', 'timestamps_usort_newest') );
	               
	          if ( !$usort_results ) {
	          $oct_gen->log( 'other_error', 'RSS feed failed to sort by newest items (' . $url . ')');
	          }
	            
	             
	          foreach($sortable_feed as $item) {
	                  
	                  
		         // If data exists, AND we aren't just caching data during a cron job
		         if ( trim($item->title) != '' && $feed_size > 0 ) {
		               
			         if ( $item->pubDate != '' ) {
			         $item_date = $item->pubDate;
			         }
			         elseif ( $item->published != '' ) {
			         $item_date = $item->published;
			         }
			         elseif ( $item->updated != '' ) {
			         $item_date = $item->updated;
			         }
			               
			         if ( !$item->link && $item->enclosure['url'] ) {
			         $item_link = $item->enclosure['url'];
			         }
			         elseif ( $item->link != '' ) {
			         $item_link = $item->link;
			         }
		                  
		         $date_array = date_parse($item_date);
		                  
		         $month_name = date("F", mktime(0, 0, 0, $date_array['month'], 10));
		                  
		         $date_ui = $month_name . ' ' . $oct_gen->ordinal($date_array['day']) . ', ' . $date_array['year'] . ' @ ' . substr("0{$date_array['hour']}", -2) . ':' . substr("0{$date_array['minute']}", -2);
		                  
		         $item_link = preg_replace("/web\.bittrex\.com/i", "bittrex.com", $item_link); // Fix for bittrex blog links
		                  
			         // If publish date is OVER 'news_feed_entries_new' days old, DONT mark as new
			         if ( $oct_var->num_to_str($now_timestamp) > $oct_var->num_to_str( strtotime($item_date) + ($oct_conf['power']['news_feed_entries_new'] * 86400) ) ) { // 86400 seconds == 1 day
			         $mark_new = null;
			         }
			                  
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
       return $html . "\n" . $show_more_less . "\n" . $html_hidden;
       }
      
       
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // We only need $pairing data if our function call needs 24hr trade volumes, so it's optional overhead
   function market($asset_symb, $sel_exchange, $market_id, $pairing=false) {
   
   global $oct_conf, $oct_var, $oct_cache, $oct_gen, $oct_asset, $sel_opt, $defipulse_api_limit;
    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
      if ( strtolower($sel_exchange) == 'bigone' ) {
         
      $url = 'https://big.one/api/v3/asset_pairs/tickers';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
       
      $data = $data['data'];
      
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	               if ( $val["asset_pair_name"] == $market_id ) {
	               
	               $result = array(
	                              'last_trade' => $val["close"],
	                              '24hr_asset_vol' => $val["volume"],
	                              '24hr_pairing_vol' => null // No pairing volume data for this API
	                     		    );
	               
	               }
	            
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( strtolower($sel_exchange) == 'binance' ) {
         
      $url = 'https://www.binance.com/api/v3/ticker/24hr';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
       
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	              
	              if ( $val['symbol'] == $market_id ) {
	               
	              $result = array(
	                              'last_trade' => $val["lastPrice"],
	                              '24hr_asset_vol' => $val["volume"],
	                              '24hr_pairing_vol' => $val["quoteVolume"]
	                     		    );
	     
	              }
	            
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'binance_us' ) {
         
      $url = 'https://api.binance.us/api/v3/ticker/24hr';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
       
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	              
	              if ( $val['symbol'] == $market_id ) {
	               
	              $result = array(
	                              'last_trade' => $val["lastPrice"],
	                              '24hr_asset_vol' => $val["volume"],
	                              '24hr_pairing_vol' => $val["quoteVolume"]
	                     			);
	     
	              }
	            
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( strtolower($sel_exchange) == 'bit2c' ) {
      
      $url = 'https://bit2c.co.il/Exchanges/'.$market_id.'/Ticker.json';
      
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
      
      $data = json_decode($response, true);
      
      $result = array(
                     'last_trade' => $data["ll"],
                     '24hr_asset_vol' => $data["a"],
                     '24hr_pairing_vol' => null // No pairing volume data for this API
                	   );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( strtolower($sel_exchange) == 'bitbns' ) {
         
      $url = 'https://bitbns.com/order/getTickerWithVolume/';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	              if ( $key == $market_id ) {
	               
	              $result = array(
	                              'last_trade' => $val["last_traded_price"],
	                              '24hr_asset_vol' => $val["volume"]["volume"],
	                              '24hr_pairing_vol' => null // No pairing volume data for this API
	                    		  );
	     
	              }
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'bitfinex' || strtolower($sel_exchange) == 'ethfinex' ) {
         
      $url = 'https://api-pub.bitfinex.com/v2/tickers?symbols=ALL';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
	          if ( is_array($data) ) {
	      
	            foreach ( $data as $object ) {
	              
	              if ( $object[0] == $market_id ) {
	                      
	               
	              $result = array(
	                              'last_trade' => $object[( sizeof($object) - 4 )],
	                              '24hr_asset_vol' => $object[( sizeof($object) - 3 )],
	                              '24hr_pairing_vol' => null // No pairing volume data for this API
	                     		  );
	               
	              }
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( strtolower($sel_exchange) == 'bitforex' ) {
      
      $url = 'https://api.bitforex.com/api/v1/market/ticker?symbol=' . $market_id;
      
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
      
      $data = json_decode($response, true);
      
      $result = array(
                     'last_trade' => $data["data"]["last"],
                     '24hr_asset_vol' => $data["data"]["vol"],
                     '24hr_pairing_vol' => null // No pairing volume data for this API
                       );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( strtolower($sel_exchange) == 'bitflyer' ) {
      
      $url = 'https://api.bitflyer.com/v1/getticker?product_code=' . $market_id;
      
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
      
      $data = json_decode($response, true);
      
      $result = array(
                     'last_trade' => $data["ltp"],
                     '24hr_asset_vol' => $data["volume_by_product"],
                     '24hr_pairing_vol' => null // Seems to be an EXACT duplicate of asset volume in MANY cases, skipping to be safe
               	     );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'bitmex' || strtolower($sel_exchange) == 'bitmex_u20' || strtolower($sel_exchange) == 'bitmex_z20' ) {
      
      // GET NEWEST DATA SETS (25 one hour buckets, SINCE WE #NEED# THE CURRENT PARTIAL DATA SET, 
      // OTHERWISE WE DON'T GET THE LATEST TRADE VALUE AND CAN'T CALCULATE REAL-TIME VOLUME)
      $url = 'https://www.bitmex.com/api/v1/trade/bucketed?binSize=1h&partial=true&count=25&symbol='.$market_id.'&reverse=true'; // Sort NEWEST first
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
       
      
	         if ( is_array($data) ) {
	      
	             foreach ($data as $key => $val) {
	                
			         // We only want the FIRST data set for trade value
			         if ( !$last_trade && $val['symbol'] == $market_id ) {
			         $last_trade = $val['close'];
			         $asset_vol = $val['homeNotional'];
			         $pairing_vol = $val['foreignNotional'];
			         }
			         elseif ( $val['symbol'] == $market_id ) {
			                   
			         $asset_vol = $oct_var->num_to_str($asset_vol + $val['homeNotional']);
			         $pairing_vol = $oct_var->num_to_str($pairing_vol + $val['foreignNotional']);
			                 
			         // Average of 24 hours, since we are always between 23.5 and 24.5
			         // (least resource-intensive way to get close enough to actual 24 hour volume)
			         // Overwrites until it's the last values
			         $half_oldest_hour_asset_vol = round($val['homeNotional'] / 2);
			         $half_oldest_hour_pairing_vol = round($val['foreignNotional'] / 2);
			                 
			         }
	              
	             }
	          
	          
	          $result = array(
	                           'last_trade' => $last_trade,
	                           // Average of 24 hours, since we are always between 23.5 and 24.5
	                           // (least resource-intensive way to get close enough to actual 24 hour volume)
	                           '24hr_asset_vol' => $oct_var->num_to_str($asset_vol - $half_oldest_hour_asset_vol),
	                           '24hr_pairing_vol' =>  $oct_var->num_to_str($pairing_vol - $half_oldest_hour_pairing_vol)
	                    	   );
	      
	      
	         }
          
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'bitpanda' ) {
         
      $url = 'https://api.exchange.bitpanda.com/public/v1/market-ticker';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
       
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	              if ( $val['instrument_code'] == $market_id ) {
	               
	              $result = array(
	                              'last_trade' => $val["last_price"],
	                              '24hr_asset_vol' => $val["base_volume"],
	                              '24hr_pairing_vol' => $val["quote_volume"]
	                     	       );
	     
	              }
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( strtolower($sel_exchange) == 'bitso' ) {
      
      $url = 'https://api.bitso.com/v3/ticker/?book='.$market_id;
      
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
      
      $data = json_decode($response, true);
      
      $data = $data['payload'];
      
      $result = array(
                     'last_trade' => $data["last"],
                     '24hr_asset_vol' => $data["volume"],
                     '24hr_pairing_vol' => null // No pairing volume data for this API
               	    );
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'bitstamp' ) {
      
      $url = 'https://www.bitstamp.net/api/v2/ticker/' . $market_id;
      
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
        
      $data = json_decode($response, true);
        
      $result = array(
                     'last_trade' => number_format( $data['last'], 8, '.', ''),
                     '24hr_asset_vol' => $data["volume"],
                     '24hr_pairing_vol' => null // No pairing volume data for this API
      	              );
        
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'bittrex' || strtolower($sel_exchange) == 'bittrex_global' ) {
      
      $result = array();
         
      $url = 'https://api.bittrex.com/v3/markets/tickers';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	              if ( $val['symbol'] == $market_id ) {
	              $result['last_trade'] = $val["lastTradeRate"];
	              }
	          
	            }
	          
	          }
         
         
      usleep(55000); // Wait 0.055 seconds before fetching volume data
         
      // 24 HOUR VOLUME
      $url = 'https://api.bittrex.com/v3/markets/summaries';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
	          if ( is_array($data) ) {
	      
		        foreach ($data as $key => $val) {
		              
			       if ( $val['symbol'] == $market_id ) {
			       $result['24hr_asset_vol'] = $val["volume"];
			       $result['24hr_pairing_vol'] = $val["quoteVolume"];
			       }
		          
		        }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'btcmarkets' ) {
      
      $url = 'https://api.btcmarkets.net/market/'.$market_id.'/tick';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
    
      $result = array(
                     'last_trade' => $data['lastPrice'],
                     '24hr_asset_vol' => $data["volume24h"],
                     '24hr_pairing_vol' => null // No pairing volume data for this API
                  	 );
       
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'btcturk' ) {
         
      $url = 'https://api.btcturk.com/api/v2/ticker';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      $data = $data['data'];
         
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	              if ( $val['pair'] == $market_id ) {
	               
	              $result = array(
	                              'last_trade' => $val["last"],
	                              '24hr_asset_vol' => $val["volume"],
	                              '24hr_pairing_vol' => null // No pairing volume data for this API
	                    		  );
	               
	              }
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'buyucoin' ) {
         
      $url = 'https://api.buyucoin.com/ticker/v1.0/liveData';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      $data = $data['data'];
         
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	              if ( $val["marketName"] == $market_id ) {
	               
	              $result = array(
	                              'last_trade' => $val["LTRate"],
	                              '24hr_asset_vol' => $val["v24"], 
	                              '24hr_pairing_vol' => $val["tp24"] 
	                     		  );
	               
	              }
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'cex' ) {
         
      $url = 'https://cex.io/api/tickers/BTC/USD/USDT/RUB/EUR/GBP';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      $data = $data['data'];
         
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	              
	              if ( $val["pair"] == $market_id ) {
	               
	              $result = array(
	                              'last_trade' => $val["last"],
	                              '24hr_asset_vol' => $val["volume"],
	                              '24hr_pairing_vol' => null // No pairing volume data for this API
	                     	       );
	     
	              }
	            
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
    
      elseif ( strtolower($sel_exchange) == 'coinbase' ) {
      
      $url = 'https://api.pro.coinbase.com/products/'.$market_id.'/ticker';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
    
      $result = array(
                     'last_trade' => $data['price'],
                     '24hr_asset_vol' => $data["volume"],
                     '24hr_pairing_vol' => null // No pairing volume data for this API
                  	 );
       
       
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'coinex' ) {
         
      $url = 'https://api.coinex.com/v1/market/ticker/all';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      $data = $data['data']['ticker'];
         
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	              
	              if ( $key == $market_id ) {
	               
	              $result = array(
	                              'last_trade' => $val["last"],
	                              '24hr_asset_vol' => $val["vol"],
	                              '24hr_pairing_vol' => null // No pairing volume data for this API
	                     		  );
	     
	              }
	            
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'coinspot' ) {
         
      $url = 'https://www.coinspot.com.au/pubapi/latest';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      $data = $data['prices'];
         
      
	          if ( is_array($data) ) {
	      
	            foreach ($data as $key => $val) {
	              
	              
	              if ( $key == $market_id ) {
	               
	              $result = array(
	                              'last_trade' => $val["last"],
	                              '24hr_asset_vol' => null, // No asset volume data for this API
	                              '24hr_pairing_vol' => null // No pairing volume data for this API
	                     		  );
	     
	              }
	            
	          
	            }
	          
	          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( strtolower($sel_exchange) == 'cryptofresh' ) {
      
      $url = 'https://cryptofresh.com/api/asset/markets?asset=' . $market_id;
      
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
        
      $data = json_decode($response, true);
      
      
           if ( preg_match("/BRIDGE/", $market_id) ) {
     		          
           $result = array(
     		            'last_trade' => number_format( $data['BRIDGE.BTC']['price'], 8, '.', ''),
     		            '24hr_asset_vol' => $data['BRIDGE.BTC']['volume24'],
     		            '24hr_pairing_vol' => null // No pairing volume data for this API
     		            );
     		                  
     	 }
           elseif ( preg_match("/OPEN/", $market_id) ) {
     		          
           $result = array(
     		            'last_trade' => number_format( $data['OPEN.BTC']['price'], 8, '.', ''),
     		            '24hr_asset_vol' => $data['OPEN.BTC']['volume24'],
     		            '24hr_pairing_vol' => null // No pairing volume data for this API
     		            );
     		                  
           }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
      // https://docs.defipulse.com/api-docs-by-provider/pools.fyi/exchange
      elseif ( strtolower($sel_exchange) == 'defipulse' ) {
        
          
          if ( trim($oct_conf['gen']['defipulse_key']) == null ) {
          	
          $oct_gen->log(
                       'notify_error',
          		       '"defipulse_key" (free API key) is not configured in Admin Config GENERAL section',
          		       false,
          		       'defipulse_key'
          		       );
          
          return false;
          
          }
          
        
      $market_data = explode('||', $market_id);
        
      $pairing_data = explode('/', $market_data[0]);
        
      $pool_data = $market_data[1];
        
      $defi_pools_info = $oct_asset->defi_pools_info($pairing_data, $pool_data);
          
          
          if ( $defipulse_api_limit == true ) {
          	
          $oct_gen->log(
          		   'notify_error',
          		   'DeFiPulse.com monthly API limit exceeded (check your account there)',
          		   false,
          		   'defipulsecom_api_limit'
          		   );
          
          return false;
          
          }
          elseif ( !$defi_pools_info['pool_address'] ) {
          	
          $oct_gen->log(
          		   'market_error',
          		   'No DeFi liquidity pool found for ' . $market_id . ', try setting "defi_liquidity_pools_max" HIGHER in the POWER USER config (current setting is '.$oct_conf['power']['defi_liquidity_pools_max'].', results are sorted by highest trade volume pools first)'
          		   );
          
          return false;
          
          }
         
         
      $url = 'https://data-api.defipulse.com/api/v1/blocklytics/pools/v1/trades/' . $defi_pools_info['pool_address'] . '?limit=' . $oct_conf['power']['defi_pools_max_trades'] . '&orderBy=timestamp&direction=desc&api-key=' . $oct_conf['gen']['defipulse_key'];
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      $data = $data['results'];
         
      
         if ( is_array($data) ) {
          
		   if ( preg_match("/curve/i", $defi_pools_info['platform']) ) {
		   $fromSymbol = $pairing_data[0];
		   $toSymbol = $pairing_data[1];
		   }
		   else {
		   $fromSymbol = $pairing_data[1];
		   $toSymbol = $pairing_data[0];
		   }
		           
		      
		   foreach ($data as $key => $val) {
		             
	        // Check for main asset
		   if ( $val["fromSymbol"] == $fromSymbol || preg_match("/([a-z]{1})".$fromSymbol."/", $val["fromSymbol"]) ) {
		   $trade_asset = true;
	        }
				                   
		   // Check for pairing asset
		   if ( $val["toSymbol"] == $toSymbol || preg_match("/([a-z]{1})".$toSymbol."/", $val["toSymbol"]) ) {
		   $trade_pairing = true;
		   }
				                   
				              
		   if ( $trade_asset && $trade_pairing ) {
				               
		   $result = array(
				         'defi_pool_name' => $defi_pools_info['pool_name'],
				         'defi_platform' => $defi_pools_info['platform'],
				         'last_trade' => $val["price"],
				         '24hr_asset_vol' => null, // No asset volume data for this API
				         '24hr_pairing_vol' => null, // No pairing volume data for this API
				         '24hr_usd_vol' => $defi_pools_info['pool_usd_volume']
				          );
				     
		   }
		              
		            
		   $trade_asset = false;
		   $trade_pairing = false;
		            
		   }
		          
		          
		   if ( !$result ) {
		            	
		   $oct_gen->log(
		            	'market_error',
		                'No trades found for ' . $market_id . ', try setting "defi_pools_max_trades" HIGHER in the POWER USER config (current setting is '.$oct_conf['power']['defi_pools_max_trades'].', results are sorted by most recent trades first)'
		                );
		            
		   }
		   
          
         }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'gateio' ) {
    
      $url = 'https://api.gateio.ws/api/v4/spot/tickers';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $val["currency_pair"] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["last"],
                              '24hr_asset_vol' => $val["base_volume"],
                              '24hr_pairing_vol' => $val["quote_volume"]
                              );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( strtolower($sel_exchange) == 'gemini' ) {
      
      $url = 'https://api.gemini.com/v1/pubticker/' . $market_id;
      
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
        
      $data = json_decode($response, true);
        
      $result = array(
                     'last_trade' => $data['last'],
                     '24hr_asset_vol' => $data['volume'][strtoupper($asset_symb)],
                     '24hr_pairing_vol' => $data['volume'][strtoupper($pairing)]
      	              );
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
      
      elseif ( strtolower($sel_exchange) == 'graviex' ) {
    
      $url = 'https://graviex.net//api/v2/tickers.json';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $unused) {
              
              if ( $data[$market_id] != '' ) {
               
              $result = array(
                              'last_trade' => $data[$market_id]['ticker']['last'],
                              '24hr_asset_vol' => $data[$market_id]['ticker']['vol'],
                              '24hr_pairing_vol' => null // Weird pairing volume always in BTC according to array keyname, skipping
                     	      );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'hitbtc' ) {
    
      $url = 'https://api.hitbtc.com/api/2/public/ticker';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $val["symbol"] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["last"],
                              '24hr_asset_vol' => $val["volume"],
                              '24hr_pairing_vol' => $val["volumeQuote"]
                              );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'hotbit' ) {
    
      $url = 'https://api.hotbit.io/api/v1/allticker';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
       
      $data = $data['ticker'];
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $val["symbol"] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["last"],
                              '24hr_asset_vol' => $val["vol"],
                              '24hr_pairing_vol' => null // No pairing volume data for this API
                              );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'huobi' ) {
         
      $url = 'https://api.huobi.pro/market/tickers';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      $data = $data['data'];
         
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $val["symbol"] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["close"],
                              '24hr_asset_vol' => $val["amount"],
                              '24hr_pairing_vol' => $val["vol"]
                              );
     
              }
          
            }
          
          }
      
      
      }
     
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'idex' ) {
         
     	$url = 'https://api.idex.market/returnTicker';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $key == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["last"],
                              // ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
                              '24hr_asset_vol' => $val["quoteVolume"],
                              '24hr_pairing_vol' => $val["baseVolume"]
                     		  );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'korbit' ) {
         
      $url = 'https://api.korbit.co.kr/v1/ticker/detailed/all';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $key == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["last"],
                              '24hr_asset_vol' => $val["volume"],
                              '24hr_pairing_vol' => null // No pairing volume data for this API
                    	      );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( strtolower($sel_exchange) == 'kraken' ) {
        
      $kraken_pairs = null; // In case user messes up Admin Config, this helps
        
        
          foreach ( $oct_conf['assets'] as $markets ) {
          
	         foreach ( $markets['pairing'] as $exchange_pairs ) {
	            
		        if ( isset($exchange_pairs['kraken']) && $exchange_pairs['kraken'] != '' ) { // In case user messes up Admin Config, this helps
		        $kraken_pairs .= $exchange_pairs['kraken'] . ',';
		        }
	            
	         }
            
          }
    
    
      $kraken_pairs = substr($kraken_pairs, 0, -1);
       
      $url = 'https://api.kraken.com/0/public/Ticker?pair=' . $kraken_pairs;
      
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
      
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $key == 'result' ) {
              
               foreach ($val as $key2 => $unused) {
                 
                 if ( $key2 == $market_id ) {
                  
                 $result = array(
                                 'last_trade' => $val[$key2]["c"][0],
                                 '24hr_asset_vol' => $val[$key2]["v"][1],
                                 '24hr_pairing_vol' => null // No pairing volume data for this API
                       		     );
                  
                 }
             
               }
            
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'kucoin' ) {
    
      $url = 'https://api.kucoin.com/api/v1/market/allTickers';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      $data = $data['data']['ticker'];
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ($val['symbol'] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["last"],
                              '24hr_asset_vol' => $val["vol"],
                              '24hr_pairing_vol' => $val["volValue"]
                     		  );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'liquid' ) {
         
      $url = 'https://api.liquid.com/products';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $val["currency_pair_code"] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["last_traded_price"],
                              '24hr_asset_vol' => $val["volume_24h"],
                              '24hr_pairing_vol' => null // No pairing volume data for this API
                     	        );
     
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'localbitcoins' ) {
         
      $url = 'https://localbitcoins.com/bitcoinaverage/ticker-all-currencies/';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $key == $market_id ) {
               
              $result = array(
                              'last_trade' => $oct_var->num_to_str($val["rates"]["last"]), // Handle large / small values better with $oct_var->num_to_str()
                              '24hr_asset_vol' => $val["volume_btc"],
                              '24hr_pairing_vol' => null // No pairing volume data for this API
                              );
     
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
     // https://github.com/Loopring/protocols/wiki/Loopring-Exchange-Data-API
     
      elseif ( strtolower($sel_exchange) == 'loopring' || strtolower($sel_exchange) == 'loopring_amm' ) {
         
      $url = 'https://api3.loopring.io/api/v3/allTickers';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
         
	     if ( substr($market_id, 0, 4) == "AMM-" ) {
	     $data = $data['pools'];
	     }
	     else {
	     $data = $data['markets'];
	     }
	             
	      
	     if ( is_array($data) ) {
	      
	         foreach ($data as $key => $val) {
	             
	              if ( $key == $market_id ) {
	               
	              $result = array(
	                              'last_trade' => $val["last_price"],
	                              '24hr_asset_vol' => $val["base_volume"],
	                              '24hr_pairing_vol' => $val["quote_volume"]
	                     	       );
	     
	              }
	          
	         }
	          
	     }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'luno' ) {
         
      $url = 'https://api.mybitx.com/api/1/tickers';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
         
      $data = $data['tickers'];
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $val["pair"] == $market_id ) {
               
              $result = array(
                              'last_trade' => $oct_var->num_to_str($val["last_trade"]), // Handle large / small values better with $oct_var->num_to_str()
                              '24hr_asset_vol' => $val["rolling_24_hour_volume"],
                              '24hr_pairing_vol' => null // No pairing volume data for this API
                     		  );
     
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'okcoin' ) {
      
      $url = 'https://www.okcoin.com/api/spot/v3/instruments/ticker';
        
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
        
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
             
              
              if ( $val['instrument_id'] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val['last'],
                              '24hr_asset_vol' => $val['base_volume_24h'],
                              '24hr_pairing_vol' => $val['quote_volume_24h']
                              );
     
              }
            
          
            }
          
          }
      
        
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( strtolower($sel_exchange) == 'okex' ) {
      
      $url = 'https://www.okex.com/api/spot/v3/instruments/ticker';
      
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
        
      $data = json_decode($response, true);
       
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $val['instrument_id'] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["last"],
                              '24hr_asset_vol' => $val["base_volume_24h"],
                              '24hr_pairing_vol' => $val['quote_volume_24h']
                     		  );
     
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( strtolower($sel_exchange) == 'poloniex' ) {
    
      $url = 'https://poloniex.com/public?command=returnTicker';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $key == $market_id ) {
               
              $result = array(
                              'last_trade' =>$val["last"],
                              // ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
                              '24hr_asset_vol' => $val["quoteVolume"],
                              '24hr_pairing_vol' => $val["baseVolume"]
                     	        );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
    
    
      elseif ( strtolower($sel_exchange) == 'southxchange' ) {
    
      $url = 'https://www.southxchange.com/api/prices';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $val["Market"] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["Last"],
                              '24hr_asset_vol' => $val["Volume24Hr"],
                              '24hr_pairing_vol' => null // No pairing volume data for this API
                     		  );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
      
      
      
      elseif ( strtolower($sel_exchange) == 'tradeogre' ) {
    
      $url = 'https://tradeogre.com/api/v1/markets';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $val[$market_id] != '' ) {
               
              $result = array(
                              'last_trade' => $val[$market_id]["price"],
                              '24hr_asset_vol' => null, // No asset volume data for this API
                              '24hr_pairing_vol' => $val[$market_id]["volume"]
                     		  );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'upbit' ) {
        
      $upbit_pairs = null; // In case user messes up Admin Config, this helps
      
      
          foreach ( $oct_conf['assets'] as $markets ) {
          
	         foreach ( $markets['pairing'] as $exchange_pairs ) {
	            
		        if ( isset($exchange_pairs['upbit']) && $exchange_pairs['upbit'] != '' ) { // In case user messes up Admin Config, this helps
		        $upbit_pairs .= $exchange_pairs['upbit'] . ',';
		        }
	            
	         }
            
          }
    
    
      $upbit_pairs = substr($upbit_pairs, 0, -1);
    
      $url = 'https://api.upbit.com/v1/ticker?markets=' . $upbit_pairs;
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ( $data as $key => $val ) {
              
              if ( $val["market"] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["trade_price"],
                              '24hr_asset_vol' => $val["acc_trade_volume_24h"],
                              '24hr_pairing_vol' => null // No 24 hour trade volume going by array keynames, skipping
                     		  );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'wazirx' ) {
    
      $url = 'https://api.wazirx.com/api/v2/tickers';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $key == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["last"],
                              '24hr_asset_vol' => $val["volume"],
                              '24hr_pairing_vol' => null // No pairing volume data for this API
                     		  );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'zebpay' ) {
    
      $url = 'https://www.zebapi.com/pro/v1/market/';
         
      $response = @$oct_cache->ext_data('url', $url, $oct_conf['power']['last_trade_cache_time']);
         
      $data = json_decode($response, true);
      
      
          if ( is_array($data) ) {
      
            foreach ($data as $key => $val) {
              
              if ( $val['pair'] == $market_id ) {
               
              $result = array(
                              'last_trade' => $val["market"],
                              '24hr_asset_vol' => $val["volume"],
                              '24hr_pairing_vol' => null // No pairing volume data for this API
                     		  );
               
              }
          
            }
          
          }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
      elseif ( strtolower($sel_exchange) == 'misc_assets' ) {
      
      // BTC value of 1 unit of the default primary currency
      $currency_to_btc = $oct_var->num_to_str(1 / $sel_opt['sel_btc_prim_currency_val']);	
      
           // BTC pairing
           if ( $market_id == 'btc' ) {
           $result = array(
     		            'last_trade' => $currency_to_btc
     		            );
           }
           // All other pairing
     	 else {
     		        
           $pairing_btc_val = $oct_asset->pairing_btc_val($market_id);
     		      
          	 if ( $pairing_btc_val == null ) {
          				          	
          	 $oct_gen->log(
          				'market_error',
          				'oct_asset->pairing_btc_val() returned null',
          				'market_id: ' . $market_id
          				);
          				          
                }
     		      
           $result = array(
     		            'last_trade' => ( 1 / $oct_var->num_to_str($pairing_btc_val / $currency_to_btc) )
     		            );
     		                  		
           }
      
      
      }
     
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
      if ( strtolower($sel_exchange) != 'misc_assets' ) {
        
      // Better large / small number support
      $result['last_trade'] = $oct_var->num_to_str($result['last_trade']);
        
          // SET FIRST...emulate pairing volume if non-existent
		if ( is_numeric($result['24hr_pairing_vol']) != true ) {
		$result['24hr_pairing_vol'] = $oct_var->num_to_str($result['last_trade'] * $result['24hr_asset_vol']);
		}
		      
		// Set primary currency volume value
		if ( $pairing == $oct_conf['gen']['btc_prim_currency_pairing'] ) {
		$result['24hr_prim_currency_vol'] = $oct_var->num_to_str($result['24hr_pairing_vol']); // Save on runtime, if we don't need to compute the fiat value
		}
		elseif ( !$result['24hr_pairing_vol'] && $result['24hr_usd_vol'] ) {
		          
	         // Fiat or equivalent pairing?
	         // #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
		    if ( array_key_exists($pairing, $oct_conf['power']['btc_currency_markets']) && !array_key_exists($pairing, $oct_conf['power']['crypto_pairing']) ) {
		    $fiat_eqiv = 1;
		    }
		        
	     $pairing_btc_val = $oct_asset->pairing_btc_val($pairing);
		$usd_btc_val = $oct_asset->pairing_btc_val('usd');
		        
		$vol_in_btc = $result['24hr_usd_vol'] * $usd_btc_val;
		$vol_in_pairing = round( ($vol_in_btc / $pairing_btc_val) , ( $fiat_eqiv == 1 ? 0 : $oct_conf['power']['chart_crypto_vol_dec'] ) );
		        
		$result['24hr_pairing_vol'] = $oct_var->num_to_str($vol_in_pairing);
		$result['24hr_prim_currency_vol'] = $oct_var->num_to_str( $oct_asset->prim_currency_trade_vol('BTC', 'usd', 1, $result['24hr_usd_vol']) );
		        
		}
		else {
		$result['24hr_prim_currency_vol'] = $oct_var->num_to_str( $oct_asset->prim_currency_trade_vol($asset_symb, $pairing, $result['last_trade'], $result['24hr_pairing_vol']) );
		}
        
      
      }
   
   
   return $result;
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
      
   
}




?>