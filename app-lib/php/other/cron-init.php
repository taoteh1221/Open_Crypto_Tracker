<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Only need below logic during cron runtime
//////////////////////////////////////////////////////////////////
if ( $runtime_mode == 'cron' ) {


// Re-cache RSS feeds for faster UI runtimes later
rss_feeds(false, 10, true);


// Re-cache marketcap data for faster UI runtimes later
$coingecko_api = coingecko_api();
$coinmarketcap_api = coinmarketcap_api();
	 
	 
// Re-cache chain data for faster UI runtimes later

// Bitcoin
bitcoin_api('height');
bitcoin_api('difficulty');

// Ethereum
etherscan_api('number');
etherscan_api('difficulty');

// Monero
monero_api('height');
monero_api('hashrate');
monero_reward();

// Decred
decred_api('block', 'height');
decred_api('block', 'difficulty');
decred_api('subsidy', 'work_reward');

// Dogecoin
dogecoin_api('height');
dogecoin_api('difficulty');

// Grin
grin_api('height');
grin_api('target_difficulty');

// Hive
asset_market_data('HIVE', 'bittrex', 'BTC-HIVE');

// Litecoin
litecoin_api('height');
litecoin_api('difficulty');

// Chain data END


}
//////////////////////////////////////////////////////////////////
// END CRON-ONLY LOGIC
//////////////////////////////////////////////////////////////////

 
 ?>