<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$parse_ticker = strtoupper( preg_replace("/stock/i", "", $_GET['ticker']) );

			     
// IF we do NOT have a PREMIUM PLAN (determined by the per-minute setting)
if ( $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 ) {
			     
			     
     if ( $ct['dev']['throttled_apis']['alphavantage.co']['min_cache_time'] >= 1440 ) {
     $stock_cached_unit = 'day';
     $stock_cached_val = $ct['var']->num_pretty( ($ct['dev']['throttled_apis']['alphavantage.co']['min_cache_time'] / 1440) , 2);
     }
     elseif ( $ct['dev']['throttled_apis']['alphavantage.co']['min_cache_time'] >= 60 ) {
     $stock_cached_unit = 'hour';
     $stock_cached_val = $ct['var']->num_pretty( ($ct['dev']['throttled_apis']['alphavantage.co']['min_cache_time'] / 60) , 2);
     }
     else {
     $stock_cached_unit = 'minute';
     $stock_cached_val = $ct['var']->num_pretty($ct['dev']['throttled_apis']['alphavantage.co']['min_cache_time'], 2);
     }
     
                    
$stock_cached_notice = "*Current (AlphaVantage *FREE TIER*) THROTTLING retrieves LIVE market data roughly (on average) every ~" . $stock_cached_val . " " . $stock_cached_unit . "(s), for " . $parse_ticker . ". The ACTUAL live data update time is determined by the number of STOCKS added, and any additional API throttling needed for recent asset (adding) searches / stock overview updates. This keeps the app running within your *FREE TIER* " . $ct['var']->num_pretty($ct['conf']['ext_apis']['alphavantage_free_plan_daily_limit'], 2) . " DAILY LIVE requests limit.";			     

$app_cache_time = '1 to 2 Weeks (minimizes FREE Tier issues [last cache: {LAST_CACHE_TIME}])';

// UX for FREE TIER cache time
$app_cache_time = preg_replace('/\{LAST_CACHE_TIME\}/i', date('Y-M-d', $stock_overview['cache_timestamp']), $app_cache_time);
			     
}
else {
$app_cache_time = '1 Day';
}


$market_pair = $ct['var']->array_key_first($ct['conf']['assets'][ $_GET['ticker'] ]['pair']);

$market_id = $ct['conf']['assets'][ $_GET['ticker'] ]['pair'][$market_pair]['alphavantage_stock'];

// Stock overview
$stock_overview = $ct['api']->stock_overview($market_id);
			     
?>


<h5 class="yellow align_center tooltip_title">AlphaVantage.co Summary For: <?=$_GET['name']?> (<?=$_GET['ticker']?>)</h5>
 
<?php

if ( isset($stock_overview['data']['request_error']) ) {
     
          
     if ( $stock_overview['data']['request_error'] == 'no_data_available' ) {
     $app_cache_time = 'Permanent (no stock overview available)';
     }
     else {
     $app_cache_time = '4 to 8 Hours until re-try (API Error: '. $ct['gen']->key_to_name($stock_overview['data']['request_error']) .')';
     }


     if (
     $stock_overview['data']['request_error'] == 'api_limit'
     && $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5
     ) {
     $stock_cached_notice .= '<br /><br /> You have gone over your AlphaVantage DAILY LIMITS. After the "Summary Cache Time" ABOVE has passed, the Stock Overview MAY show here (IF available for ' . $parse_ticker . ' [not all stocks have overviews]).';  
     }
     elseif ( $stock_overview['data']['request_error'] == 'api_limit' ) {
     $stock_cached_notice .= '<br /><br /> You have gone over your AlphaVantage PER-MINUTE LIMITS. After the "Summary Cache Time" ABOVE has passed, the Stock Overview MAY show here (IF available for ' . $parse_ticker . ' [not all stocks have overviews]).';     
     }


     if ( preg_match("/\./i", $market_id) ) {
     ?>
     <p class="coin_info"><span class="bitcoin">No Stock Overview data found for:</span> <?=$parse_ticker?> <span class="yellow">(NON-USA exchange ID: <?=$market_id?>)</span></p>
     <?php
     }
     else {
     ?>
     <p class="coin_info"><span class="bitcoin">No Stock Overview data found for:</span> <?=$parse_ticker?></p>
     <?php
     }
     
     
}
else {
?>

<p class="coin_info"><span class="bitcoin">Name:</span> <?=$stock_overview['data']['Name']?></p>

<p class="coin_info"><span class="bitcoin">Asset Type:</span> <?=$stock_overview['data']['AssetType']?></p>

<p class="coin_info"><span class="bitcoin">Exchange:</span> <?=$stock_overview['data']['Exchange']?></p>

<p class="coin_info"><span class="bitcoin">Sector:</span> <?=$stock_overview['data']['Sector']?></p>

<p class="coin_info"><span class="bitcoin">Industry:</span> <?=$stock_overview['data']['Industry']?></p>

<p class="coin_info"><span class="bitcoin">MarketCap:</span> <?=$ct['var']->num_pretty($stock_overview['data']['MarketCapitalization'], 0)?> (<?=$stock_overview['data']['Currency']?>)</p>

<p class="coin_info"><span class="bitcoin">52 Week High:</span> <?=$ct['var']->num_pretty($stock_overview['data']['52WeekHigh'], 2)?> (<?=$stock_overview['data']['Currency']?>)</p>

<p class="coin_info"><span class="bitcoin">52 Week Low:</span> <?=$ct['var']->num_pretty($stock_overview['data']['52WeekLow'], 2)?> (<?=$stock_overview['data']['Currency']?>)</p>

<p class="coin_info"><span class="bitcoin">Description:</span> <br /><?=$stock_overview['data']['Description']?></p>

<?php
}
?>

<p class="coin_info"><span class="bitcoin">Summary Cache Time:</span> <?=$app_cache_time?></p>

<p class="coin_info balloon_notation red"><?=$stock_cached_notice?></p>



