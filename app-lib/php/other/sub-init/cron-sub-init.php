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
	foreach($ocpt_conf['power']['news_feed'] as $cached_feed_key => $feed_unused) {
		if ( trim($news_feeds[$cached_feed_key]["url"]) != '' ) {
	 	$ocpt_api->rss($news_feeds[$feed_key]["url"], 'no_theme', 0, 1);
	 	}
	}


	// If coinmarketcap API key is added, cache data for faster UI runtimes later
	if ( trim($ocpt_conf['gen']['cmc_key']) != null ) {
	$coinmarketcap_api = $ocpt_api->coinmarketcap();
	}
	 

// Re-cache marketcap data for faster UI runtimes later
$coingecko_api = $ocpt_api->coingecko();
	 
	 
// Re-cache chain data for faster UI runtimes later

// Bitcoin
$ocpt_api->bitcoin('height');
$ocpt_api->bitcoin('difficulty');

// Ethereum
$ocpt_api->etherscan('number');
$ocpt_api->etherscan('difficulty');
$ocpt_api->etherscan('gasLimit');

// Hive
$ocpt_api->market('HIVE', 'bittrex', 'BTC-HIVE');

// Chain data END


}
//////////////////////////////////////////////////////////////////
// END CRON-ONLY LOGIC
//////////////////////////////////////////////////////////////////

 
 ?>