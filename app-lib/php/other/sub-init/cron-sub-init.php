<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Only need below logic during cron runtime
//////////////////////////////////////////////////////////////////
if ( $runtime_mode == 'cron' ) {

	
// Reset feed fetch telemetry 
$_SESSION[$fetched_feeds] = false;

	// Re-cache RSS feeds for faster UI runtimes later
	foreach($app_config['power_user']['news_feeds'] as $cached_feed_key => $feed_unused) {
		if ( trim($news_feeds[$cached_feed_key]["url"]) != '' ) {
	 	get_rss_feed($news_feeds[$feed_key]["url"], 'no_theme', 0, 1);
	 	}
	}


	// If coinmarketcap API key is added, cache data for faster UI runtimes later
	if ( trim($app_config['general']['coinmarketcapcom_api_key']) != null ) {
	$coinmarketcap_api = coinmarketcap_api();
	}
	 

// Re-cache marketcap data for faster UI runtimes later
$coingecko_api = coingecko_api();
	 
	 
// Re-cache chain data for faster UI runtimes later

// Bitcoin
bitcoin_api('height');
bitcoin_api('difficulty');

// Ethereum
etherscan_api('number');
etherscan_api('difficulty');
etherscan_api('gasLimit');

// Hive
asset_market_data('HIVE', 'bittrex', 'BTC-HIVE');

// Chain data END


}
//////////////////////////////////////////////////////////////////
// END CRON-ONLY LOGIC
//////////////////////////////////////////////////////////////////

 
 ?>