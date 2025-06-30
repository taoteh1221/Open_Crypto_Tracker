<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


header('Access-Control-Allow-Headers: *'); // Allow ALL headers
header('Access-Control-Allow-Origin: *'); // Allow ALL origins, since we don't load init.php here


$parse_ticker = strtoupper( preg_replace("/stock/i", "", $_GET['ticker']) );

			     
// IF we do NOT have a PREMIUM PLAN (determined by the per-minute setting)
if ( $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 ) {
			     
			     
     if ( $ct['throttled_api_min_cache_time']['alphavantage.co'] >= 1440 ) {
     $stock_cached_unit = 'day';
     $stock_cached_val = $ct['var']->num_pretty( ($ct['throttled_api_min_cache_time']['alphavantage.co'] / 1440) , 2);
     }
     elseif ( $ct['throttled_api_min_cache_time']['alphavantage.co'] >= 60 ) {
     $stock_cached_unit = 'hour';
     $stock_cached_val = $ct['var']->num_pretty( ($ct['throttled_api_min_cache_time']['alphavantage.co'] / 60) , 2);
     }
     else {
     $stock_cached_unit = 'minute';
     $stock_cached_val = $ct['var']->num_pretty($ct['throttled_api_min_cache_time']['alphavantage.co'], 2);
     }
     
                    
$stock_cached_notice = "*Current (AlphaVantage *FREE TIER*) THROTTLING retrieves LIVE market data every " . $stock_cached_val . " " . $stock_cached_unit . "(s), for " . $parse_ticker . " (determined by number of STOCKS added, to avoid going over your *FREE TIER* " . $ct['var']->num_pretty($ct['conf']['ext_apis']['alphavantage_free_plan_daily_limit'], 2) . " DAILY LIVE requests limit).";			     

$app_cache_time = '1 to 2 Weeks (minimizes FREE Tier DAILY limit impacts)';
			     
}
else {
$app_cache_time = '1 Day';
}


$market_pair = $ct['gen']->array_key_first($ct['conf']['assets'][ $_GET['ticker'] ]['pair']);

$market_id = $ct['conf']['assets'][ $_GET['ticker'] ]['pair'][$market_pair]['alphavantage_stock'];

// Stock overview
$stock_overview = $ct['api']->stock_overview($market_id);
			     
?>


<h5 class="yellow align_center tooltip_title">AlphaVantage.co Summary For: <?=$_GET['name']?> (<?=$_GET['ticker']?>)</h5>
 
<?php
if ( isset($stock_overview['request_error']) && preg_match("/\./i", $market_id) ) {
     
$orig_market_id = $market_id;

// Remove period, AND everything AFTER
// (AS MANY OVERSEAS STOCK IDS HAVE AN EQUIVALENT USA-BASED STOCK ID [WITH NO PERIOD IN IT])
$market_id = preg_replace("/\.(.*)/i", "", $market_id);

// Retry
$stock_overview = $ct['api']->stock_overview($market_id);

$tried_usa_equiv = true;

}


if ( isset($stock_overview['request_error']) ) {

     if ( $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 ) {
     $stock_cached_notice .= '<br /><br /> You MAY have gone over your AlphaVantage DAILY LIMITS. IF SO, after the "Summary Cache Time" ABOVE has passed, the Stock Overview should show here (IF available for ' . $parse_ticker . ').';     
     }
     
?>

<p class="coin_info"><span class="bitcoin">No Stock Overview data found for:</span> <?=$parse_ticker?></p>

<?php
}
else {
     
     if ( $tried_usa_equiv ) {
     ?>

     <p class="coin_info red">Notice: <br />Market ID "<?=$orig_market_id?>" returned NO STOCK OVERVIEW RESULTS, BUT "<?=$market_id?>" data WAS FOUND on the <?=$stock_overview['Exchange']?> (USA-based) exchange.</p>

     <?php
     }
     ?>

<p class="coin_info"><span class="bitcoin">Name:</span> <?=$stock_overview['Name']?></p>

<p class="coin_info"><span class="bitcoin">Asset Type:</span> <?=$stock_overview['AssetType']?></p>

<p class="coin_info"><span class="bitcoin">Exchange:</span> <?=$stock_overview['Exchange']?></p>

<p class="coin_info"><span class="bitcoin">Sector:</span> <?=$stock_overview['Sector']?></p>

<p class="coin_info"><span class="bitcoin">Industry:</span> <?=$stock_overview['Industry']?></p>

<p class="coin_info"><span class="bitcoin">MarketCap:</span> <?=$ct['var']->num_pretty($stock_overview['MarketCapitalization'], 0)?> (<?=$stock_overview['Currency']?>)</p>

<p class="coin_info"><span class="bitcoin">52 Week High:</span> <?=$ct['var']->num_pretty($stock_overview['52WeekHigh'], 2)?> (<?=$stock_overview['Currency']?>)</p>

<p class="coin_info"><span class="bitcoin">52 Week Low:</span> <?=$ct['var']->num_pretty($stock_overview['52WeekLow'], 2)?> (<?=$stock_overview['Currency']?>)</p>

<p class="coin_info"><span class="bitcoin">Description:</span> <br /><?=$stock_overview['Description']?></p>

<?php
}
?>

<p class="coin_info"><span class="bitcoin">Summary Cache Time:</span> <?=$app_cache_time?></p>

<p class="coin_info balloon_notation red"><?=$stock_cached_notice?></p>



