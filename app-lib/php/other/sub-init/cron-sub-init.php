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
	foreach($ct_conf['power']['news_feed'] as $cached_feed_key => $feed_unused) {
		if ( trim($news_feeds[$cached_feed_key]["url"]) != '' ) {
	 	$ct_api->rss($news_feeds[$feed_key]["url"], 'no_theme', 0, 1);
	 	}
	}


	// If coinmarketcap API key is added, cache data for faster UI runtimes later
	if ( trim($ct_conf['gen']['cmc_key']) != null ) {
	$coinmarketcap_api = $ct_api->coinmarketcap();
	}
	 

// Re-cache marketcap data for faster UI runtimes later
$coingecko_api = $ct_api->coingecko();
	 
	 
// Re-cache chain data for faster UI runtimes later

// Bitcoin
$ct_api->bitcoin('height');
$ct_api->bitcoin('difficulty');

// Ethereum
$ct_api->etherscan('number');
$ct_api->etherscan('difficulty');
$ct_api->etherscan('gasLimit');

// Hive
$ct_api->market('HIVE', 'bittrex', 'BTC-HIVE');

// Chain data END


}
//////////////////////////////////////////////////////////////////
// END CRON-ONLY LOGIC
//////////////////////////////////////////////////////////////////

 
 ?>