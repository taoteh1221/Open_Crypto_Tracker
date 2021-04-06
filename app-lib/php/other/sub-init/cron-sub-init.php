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
	foreach($pt_conf['power']['news_feed'] as $cached_feed_key => $feed_unused) {
		if ( trim($news_feeds[$cached_feed_key]["url"]) != '' ) {
	 	$pt_api->rss($news_feeds[$feed_key]["url"], 'no_theme', 0, 1);
	 	}
	}


	// If coinmarketcap API key is added, cache data for faster UI runtimes later
	if ( trim($pt_conf['gen']['cmc_key']) != null ) {
	$coinmarketcap_api = $pt_api->coinmarketcap();
	}
	 

// Re-cache marketcap data for faster UI runtimes later
$coingecko_api = $pt_api->coingecko();
	 
	 
// Re-cache chain data for faster UI runtimes later

// Bitcoin
$pt_api->bitcoin('height');
$pt_api->bitcoin('difficulty');

// Ethereum
$pt_api->etherscan('number');
$pt_api->etherscan('difficulty');
$pt_api->etherscan('gasLimit');

// Hive
$pt_api->market('HIVE', 'bittrex', 'BTC-HIVE');

// Chain data END


}
//////////////////////////////////////////////////////////////////
// END CRON-ONLY LOGIC
//////////////////////////////////////////////////////////////////

 
 ?>