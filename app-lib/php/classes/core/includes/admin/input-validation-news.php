<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$news_feed_cache_min_max = array_map('trim', explode(',', $_POST['news']['news_feed_cache_min_max']) );
 
 
// Make sure min / max cache time is set properly
if ( isset($_POST['news']['news_feed_cache_min_max']) && trim($_POST['news']['news_feed_cache_min_max']) == '' ) {
$ct['update_config_error'] = '"News Feed Cache Min Max" value is REQUIRED';
}
else if (
!isset($news_feed_cache_min_max[0]) || !$ct['var']->whole_int($news_feed_cache_min_max[0]) || $news_feed_cache_min_max[0] < 30 || $news_feed_cache_min_max[0] > 720 
|| !isset($news_feed_cache_min_max[1]) || !$ct['var']->whole_int($news_feed_cache_min_max[1]) || $news_feed_cache_min_max[1] < 30 || $news_feed_cache_min_max[1] > 720
|| $news_feed_cache_min_max[0] > $news_feed_cache_min_max[1]
) {
$ct['update_config_error'] = '"News Feed Cache Min Max" values MUST be between 30 and 720 (LARGER number last)';
}


$_POST['news']['strict_news_feed_servers'] = array_map( "trim", $_POST['news']['strict_news_feed_servers']); 

foreach ( $_POST['news']['strict_news_feed_servers'] as $domain ) {

     if ( !$ct['gen']->valid_domain($domain) ) {
     $ct['update_config_error'] .= '<br />"strict_news_feed_servers" seems INVALID (NOT a domain): ' . $domain;
     }

}
        
        
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>