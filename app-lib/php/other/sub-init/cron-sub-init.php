<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Only need below logic during cron runtime
//////////////////////////////////////////////////////////////////
if ( $runtime_mode == 'cron' ) {

	
// Reset feed fetch telemetry 
$_SESSION[$fetched_feeds] = false;

	// Re-cache RSS feeds for faster UI runtimes later
	foreach($oct_conf['power']['news_feed'] as $cached_feed_key => $feed_unused) {
		if ( trim($news_feeds[$cached_feed_key]["url"]) != '' ) {
	 	$oct_api->rss($news_feeds[$feed_key]["url"], 'no_theme', 0, 1);
	 	}
	}


	// If coinmarketcap API key is added, cache data for faster UI runtimes later
	if ( trim($oct_conf['gen']['cmc_key']) != null ) {
	$coinmarketcap_api = $oct_api->coinmarketcap();
	}
	 

// Re-cache marketcap data for faster UI runtimes later
$coingecko_api = $oct_api->coingecko();
	 
	 
// Re-cache chain data for faster UI runtimes later

// Bitcoin
$oct_api->bitcoin('height');
$oct_api->bitcoin('difficulty');

// Ethereum
$oct_api->etherscan('number');
$oct_api->etherscan('difficulty');
$oct_api->etherscan('gasLimit');

// Hive
$oct_api->market('HIVE', 'bittrex', 'BTC-HIVE');

// Chain data END


}
//////////////////////////////////////////////////////////////////
// END CRON-ONLY LOGIC
//////////////////////////////////////////////////////////////////

 
 ?>