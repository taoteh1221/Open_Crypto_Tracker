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
     
                    
$stock_cached_notice = "*Current LIVE DATA THROTTLING (ONLY USED FOR *FREE TIER* Alpha Vantage STOCK DATA) retrieves the latest market data for " . $parse_ticker . " every " . $stock_cached_val . " " . $stock_cached_unit . "(s) (determined by the number of STOCK assets you have added, to avoid going over your *FREE TIER* " . $ct['var']->num_pretty($ct['conf']['ext_apis']['alphavantage_free_plan_daily_limit'], 2) . " DAILY LIVE requests limit).";			     
			     
}


// Stock overview
$stock_overview = $ct['api']->stock_overview($parse_ticker);

//var_dump($parse_ticker);

//var_dump($stock_overview);

//exit;
			     
?>


<h5 class="yellow align_center tooltip_title">AlphaVantage.co Summary For: <?=$_GET['name']?> (<?=$_GET['ticker']?>)</h5>
 
<?php
if ( isset($stock_overview['request_error']) ) {
?>

<p class="coin_info bitcoin">No Stock Overview data found for: <?=$parse_ticker?></p>

<?php
}
else {
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

<p class="coin_info balloon_notation red"><?=$stock_cached_notice?></p>



