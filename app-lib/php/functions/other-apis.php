<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// !!!!!!! MAKE SURE API'S TLD HAS SUPPORT ADDED IN $app_config['developer']['top_level_domain_map'] @ config.php !!!!!!!
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function grin_api($request) {
 
global $app_config;
 		
$json_string = 'https://api.grinmint.com/v1/networkStats';

$jsondata = @external_api_data('url', $json_string, $app_config['power_user']['chainstats_cache_time']);
    
$data = json_decode($jsondata, true);
    
return $data[$request];
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function monero_api($request) {
 
global $app_config;
 		
 	$json_string = 'https://moneroblocks.info/api/get_stats';
 	$jsondata = @external_api_data('url', $json_string, $app_config['power_user']['chainstats_cache_time']);
  	
  	$data = json_decode($jsondata, true);
    
		if ( !$data ) {
		return;
		}
		else {
		
		return $data[$request];
		  
		}
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function bitcoin_api($request) {
 
global $app_config;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'https://blockchain.info/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'https://blockchain.info/q/getdifficulty';
		  
		}
		
    $data = @external_api_data('url', $string, $app_config['power_user']['chainstats_cache_time']);
    
  return (float)$data;
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function dogecoin_api($request) {
 
global $app_config;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'https://dogechain.info/chain/Dogecoin/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'https://dogechain.info/chain/Dogecoin/q/getdifficulty';
		  
		}
		
    $data = @external_api_data('url', $string, $app_config['power_user']['chainstats_cache_time']);
    
  return (float)$data;
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function litecoin_api($request) {
 
global $app_config;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'http://explorer.litecoin.net/chain/Litecoin/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'http://explorer.litecoin.net/chain/Litecoin/q/getdifficulty';
		  
		}
		
    $data = @external_api_data('url', $string, $app_config['power_user']['chainstats_cache_time']);
    
  return (float)$data;
  
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function decred_api($type, $request) {
 
global $app_config, $runtime_mode;


 	if ( $type == 'block' ) {
 	$json_string = 'https://explorer.dcrdata.org/api/block/best/verbose';
 	}
	elseif ( $type == 'subsidy' ) {
 	$json_string = 'https://explorer.dcrdata.org/api/block/best/subsidy';
 	}

 		
$jsondata = @external_api_data('url', $json_string, $app_config['power_user']['chainstats_cache_time']);
  		
$data = json_decode($jsondata, true);
   	 
return $data[$request];
			  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function coingecko_api($force_primary_currency=null) {
	
global $app_config;

$data = array();
$sub_arrays = array();
$result = array();

// Don't overwrite global
$coingecko_primary_currency = ( $force_primary_currency != null ? strtolower($force_primary_currency) : strtolower($app_config['general']['btc_primary_currency_pairing']) );

$max_fetch = 151; // So we can quickly adjust if they change API defaults again

	if ( $app_config['power_user']['marketcap_ranks_max'] > $max_fetch ) {
	
		$loop = 0;
		$calls = ceil($app_config['power_user']['marketcap_ranks_max'] / $max_fetch);
		while ( $loop < $calls ) {
			
			if ( $loop > 0 ) {
			usleep(150000); // Wait 0.15 seconds between consecutive calls, to avoid blacklisting
			}
		
		$jsondata = @external_api_data('url', 'https://api.coingecko.com/api/v3/coins/markets?per_page='.$max_fetch.'&page='.($loop + 1).'&vs_currency='.$coingecko_primary_currency.'&price_change_percentage=1h,24h,7d,14d,30d,200d,1y', $app_config['power_user']['marketcap_cache_time']);

		$sub_arrays[] = json_decode($jsondata, true);
		$loop = $loop + 1;
		}
	
	}
	else {
	$jsondata = @external_api_data('url', 'https://api.coingecko.com/api/v3/coins/markets?per_page='.$app_config['power_user']['marketcap_ranks_max'].'&page=1&vs_currency='.$coingecko_primary_currency.'&price_change_percentage=1h,24h,7d,14d,30d,200d,1y', $app_config['power_user']['marketcap_cache_time']);
	$sub_arrays[] = json_decode($jsondata, true);
	}
	   
	   
// DON'T ADD ANY ERROR CHECKS HERE, OR RUNTIME MAY SLOW SIGNIFICANTLY!!

	
	// Merge sub arrays
	foreach ( $sub_arrays as $sub ) {
	$data = array_merge($data, $sub);
	}
	

   if ( is_array($data) || is_object($data) ) {
  		
  	 	foreach ($data as $key => $value) {
     	  	
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

function telegram_user_data($mode) {
	
global $app_config;

	if ( $mode == 'updates' ) {
	
	// Don't cache data, we are storing it as a specific (secured) cache var instead
	$get_telegram_chatroom_data = @external_api_data('url', 'https://api.telegram.org/bot'.$app_config['comms']['telegram_bot_token'].'/getUpdates', 0);
		
	$telegram_chatroom = json_decode($get_telegram_chatroom_data, true);

	$telegram_chatroom = $telegram_chatroom['result']; 

		foreach( $telegram_chatroom as $chat_key => $chat_unused ) {
	
			// Overwrites any earlier value while looping, so we have the latest data
			if ( $telegram_chatroom[$chat_key]['message']['chat']['username'] == trim($app_config['comms']['telegram_your_username']) ) {
			$telegram_user_data = $telegram_chatroom[$chat_key];
			}
	
		}

	return $telegram_user_data;
	
	}
	elseif ( $mode == 'webhook' ) {
		
	// Don't cache data, we are storing it as a specific (secured) cache var instead
	$get_telegram_webhook_data = @external_api_data('url', 'https://api.telegram.org/bot'.$app_config['comms']['telegram_bot_token'].'/getWebhookInfo', 0);
		
	$telegram_webhook = json_decode($get_telegram_webhook_data, true);
	
	// logic here
	
	}
	
	
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function etherscan_api($block_info) {
 
global $base_dir, $app_config;


	if ( $app_config['general']['etherscanio_api_key'] == '' ) {
	return false;
	}


  $json_string = 'https://api.etherscan.io/api?module=proxy&action=eth_blockNumber&apikey=' . $app_config['general']['etherscanio_api_key'];
  $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['chainstats_cache_time']);
    
  $data = json_decode($jsondata, true);
  
  $block_number = $data['result'];
    
    	if ( !$block_number ) {
    	return;
    	}
    	else {
    		
    		// Non-dynamic cache file name, because filename would change every recache and create cache bloat
    		if ( update_cache_file('cache/secured/external_api/eth-stats.dat', $app_config['power_user']['chainstats_cache_time'] ) == true ) {
			
  			$json_string = 'https://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag='.$block_number.'&boolean=true&apikey=' . $app_config['general']['etherscanio_api_key'];
  			$jsondata = @external_api_data('url', $json_string, 0); // ZERO TO NOT CACHE DATA (WOULD CREATE CACHE BLOAT)
    		
    		store_file_contents($base_dir . '/cache/secured/external_api/eth-stats.dat', $jsondata);
    		
    		$data = json_decode($jsondata, true);
    		
    		return $data['result'][$block_info];
    		
    		}
    		else {
    			
    		$cached_data = trim( file_get_contents('cache/secured/external_api/eth-stats.dat') );
    		
    		$data = json_decode($cached_data, true);
    		
    		return $data['result'][$block_info];

    		}
  
    	}
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function coinmarketcap_api() {
	
global $app_config, $coinmarketcap_currencies, $cap_data_force_usd, $cmc_notes;

$result = array();


	if ( trim($app_config['general']['coinmarketcapcom_api_key']) == null ) { 
	app_logging('config_error', '"coinmarketcapcom_api_key" (free API key) is not configured in config.php', false, false, true);
	return false;
	}
	

	// Don't overwrite global
	$coinmarketcap_primary_currency = strtoupper($app_config['general']['btc_primary_currency_pairing']);
	
		
		if ( in_array($coinmarketcap_primary_currency, $coinmarketcap_currencies) ) {
		$convert = $coinmarketcap_primary_currency;
		$cap_data_force_usd = null;
		}
		// Default to USD, if currency is not supported
		else {
		$cmc_notes = 'Coinmarketcap.com does not support '.$coinmarketcap_primary_currency.' stats,<br />showing USD stats instead.';
		$convert = 'USD';
		$cap_data_force_usd = 1;
		}
		
	
	$headers = [
  'Accepts: application/json',
  'X-CMC_PRO_API_KEY: ' . $app_config['general']['coinmarketcapcom_api_key']
	];

	$cmc_params = array(
	  							'start' => '1',
	 							'limit' => $app_config['power_user']['marketcap_ranks_max'],
	  							'convert' => $convert
								);

	$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
		
	$qs = http_build_query($cmc_params); // query string encode the parameters
	
	$request = "{$url}?{$qs}"; // create the request URL

	$jsondata = @external_api_data('url', $request, $app_config['power_user']['remote_api_timeout'], null, null, null, $headers);
	
	$data = json_decode($jsondata, true);
        
   $data = $data['data'];
        
	

    if ( is_array($data) || is_object($data) ) {
  		
  	   	foreach ($data as $key => $value) {
     	  	
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
function get_rss_feed($url, $feed_size, $cache_only=false){
	
global $app_config, $base_dir, $fetched_feeds;


	if ( !isset($_SESSION[$fetched_feeds]['all']) ) {
	$_SESSION[$fetched_feeds]['all'] = 0;
	}
	// Never re-cache FROM LIVE more than 'batched_news_feeds_max' (even for cron runtimes), 
	// to avoid overloading low power devices (raspi / pine64 / etc)
	elseif ( $_SESSION[$fetched_feeds]['all'] >= $app_config['developer']['batched_news_feeds_max'] ) {
	return '<span class="red">Live data fetching limit reached (' . $_SESSION[$fetched_feeds]['all'] . ').</span>';
	}
	

$news_feeds_cache_min_max = explode(',', $app_config['power_user']['news_feeds_cache_min_max']);
// Cleanup
$news_feeds_cache_min_max = array_map('trim', $news_feeds_cache_min_max);
	
$rss_feed_cache_time = rand($news_feeds_cache_min_max[0], $news_feeds_cache_min_max[1]);
											
		
	// If we will be updating the feed
	if ( update_cache_file($base_dir . '/cache/secured/external_api/' . md5($url) . '.dat', $rss_feed_cache_time) == true ) {
	
	
	$_SESSION[$fetched_feeds]['all'] = $_SESSION[$fetched_feeds]['all'] + 1; // Mark as a fetched feed, since it's going to update
	
	$endpoint_tld_or_ip = get_tld_or_ip($url);

		
		if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' || $app_config['developer']['debug_mode'] == 'memory' ) {
		app_logging('system_debugging', $endpoint_tld_or_ip . ' news feed updating ('.$_SESSION[$fetched_feeds]['all'].'), CURRENT script memory usage is ' . convert_bytes(memory_get_usage(), 1) . ', PEAK script memory usage is ' . convert_bytes(memory_get_peak_usage(), 1) . ', php_sapi_name is "' . php_sapi_name() . '"' );
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
		
				
// Get feed data (whether cached or re-caching live data), and format output (UNLESS WE ARE ONLY CACHING DATA)
$xmldata = @external_api_data('url', $url, $rss_feed_cache_time); 
		
		
	if ( !$cache_only ) {
	
		
	$rss = simplexml_load_string($xmldata);
	
	
		if ( $rss == false ) {
		gc_collect_cycles(); // Clean memory cache
	 	return '<span class="red">Error retrieving feed data.</span>';
	 	}
						
						
	$html .= '<ul>';
	$html_hidden .= '<ul class="hidden" id="'.md5($url).'">';
			 
			 
		$count = 0;
			 
		// Atom format
		if ( sizeof($rss->entry) > 0 ) {
			 
		$sortable_feed = array();
				
			foreach($rss->entry as $item) {
			$sortable_feed[] = $item;
			}
				
			$usort_results = usort($sortable_feed, __NAMESPACE__ . '\timestamps_usort_newest');
				
			if ( !$usort_results ) {
			app_logging( 'other_error', 'RSS feed failed to sort by newest items (' . $url . ')');
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
							
				$item_date = preg_replace("/ 00\:(.*)/i", '', $item_date);
					
				$date_array = date_parse($item_date);
					
				$month_name = date("F", mktime(0, 0, 0, $date_array['month'], 10));
					
				$date_ui = $month_name . ' ' . ordinal($date_array['day']) . ', ' . $date_array['year'];
					
					
					if ($count < $feed_size) {
					$html .= '<li class="links_list"><a href="'.htmlspecialchars($item_link).'" target="_blank" title="'.htmlspecialchars($date_ui).'">'.htmlspecialchars($item->title).'</a></li>';
					}
					else {
					$html_hidden .= '<li class="links_list"><a href="'.htmlspecialchars($item_link).'" target="_blank" title="'.htmlspecialchars($date_ui).'">'.htmlspecialchars($item->title).'</a></li>';
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
				
		$usort_results = usort($sortable_feed, __NAMESPACE__ . '\timestamps_usort_newest');
				
			if ( !$usort_results ) {
			app_logging( 'other_error', 'RSS feed failed to sort by newest items (' . $url . ')');
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
							
				$item_date = preg_replace("/00\:(.*)/i", '', $item_date);
					
				$date_array = date_parse($item_date);
					
				$month_name = date("F", mktime(0, 0, 0, $date_array['month'], 10));
					
				$date_ui = $month_name . ' ' . ordinal($date_array['day']) . ', ' . $date_array['year'];
					
				$item_link = preg_replace("/web\.bittrex\.com/i", "bittrex.com", $item_link); // Fix for bittrex blog links
					
					
					if ($count < $feed_size) {
					$html .= '<li class="links_list"><a href="'.htmlspecialchars($item_link).'" target="_blank" title="'.htmlspecialchars($date_ui).'">'.htmlspecialchars($item->title).'</a></li>';
					}
					else {
					$html_hidden .= '<li class="links_list"><a href="'.htmlspecialchars($item_link).'" target="_blank" title="'.htmlspecialchars($date_ui).'">'.htmlspecialchars($item->title).'</a></li>';
					}
							
				$count++;     
				}
				
				
			}
			 
		}
			 
						
	$html .= '</ul>';
	$html_hidden .= '</ul>';
	$show_more_less = "<p><a href='javascript: show_more(\"".md5($url)."\");' style='font-weight: bold;' title='Show more / less RSS feed entries.'>Show More</a></p>";
		
	
	}
	
	
gc_collect_cycles(); // Clean memory cache

	
	 if ( $feed_size == 0 || $cache_only ) {
	 return true;
	 }
	 else {
	 return $html . "\n" . $show_more_less . "\n" . $html_hidden;
	 }
	
    
}


?>