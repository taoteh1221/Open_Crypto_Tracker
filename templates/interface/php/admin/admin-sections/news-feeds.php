<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


<?php
if ( $admin_area_sec_level == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {



// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['entries_to_show']['is_range'] = true;

$admin_render_settings['entries_to_show']['range_min'] = 5;

$admin_render_settings['entries_to_show']['range_max'] = 30;

$admin_render_settings['entries_to_show']['range_step'] = 5;

$admin_render_settings['entries_to_show']['range_ui_suffix'] = ' Entries Per-Feed';

$admin_render_settings['entries_to_show']['is_notes'] = '(WITHOUT clicking "Show More" button)';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['mark_as_new']['is_range'] = true;

$admin_render_settings['mark_as_new']['range_min'] = 1;

$admin_render_settings['mark_as_new']['range_max'] = 7;

$admin_render_settings['mark_as_new']['range_step'] = 1;

$admin_render_settings['mark_as_new']['range_ui_prefix'] = 'Less Than ';

$admin_render_settings['mark_as_new']['range_ui_suffix'] = ' Days Old';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['news_feed_email_frequency']['is_range'] = true;

$admin_render_settings['news_feed_email_frequency']['range_ui_meta_data'] = 'zero_is_disabled';

$admin_render_settings['news_feed_email_frequency']['range_min'] = 0;

$admin_render_settings['news_feed_email_frequency']['range_max'] = 30;

$admin_render_settings['news_feed_email_frequency']['range_step'] = 1;

$admin_render_settings['news_feed_email_frequency']['range_ui_prefix'] = 'Every ';

$admin_render_settings['news_feed_email_frequency']['range_ui_suffix'] = ' Days';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['news_feed_email_entries_include']['is_range'] = true;

$admin_render_settings['news_feed_email_entries_include']['range_min'] = 5;

$admin_render_settings['news_feed_email_entries_include']['range_max'] = 30;

$admin_render_settings['news_feed_email_entries_include']['range_step'] = 5;

$admin_render_settings['news_feed_email_entries_include']['range_ui_prefix'] = 'Include ';

$admin_render_settings['news_feed_email_entries_include']['range_ui_suffix'] = ' Entries MAXIMUM Per-News-Feed';

$admin_render_settings['news_feed_email_entries_include']['is_notes'] = '(only includes NEW entries since the previous email)';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['news_feed_cache_min_max']['is_notes'] = 'IN MINUTES. This format MUST be used: number_min,number_max';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['news_feed_batched_maximum']['is_range'] = true;

$admin_render_settings['news_feed_batched_maximum']['range_min'] = 15;

$admin_render_settings['news_feed_batched_maximum']['range_max'] = 30;

$admin_render_settings['news_feed_batched_maximum']['range_step'] = 5;

$admin_render_settings['news_feed_batched_maximum']['range_ui_suffix'] = ' Batched Feeds';

$admin_render_settings['news_feed_batched_maximum']['is_notes'] = '(LOW POWER devices should batch-load NO MORE THAN 20 feeds)';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['news_feed_precache_maximum']['is_range'] = true;

$admin_render_settings['news_feed_precache_maximum']['range_min'] = 25;

$admin_render_settings['news_feed_precache_maximum']['range_max'] = 75;

$admin_render_settings['news_feed_precache_maximum']['range_step'] = 5;

$admin_render_settings['news_feed_precache_maximum']['range_ui_suffix'] = ' Pre-Caches Per-Run';

$admin_render_settings['news_feed_precache_maximum']['is_notes'] = '(per background tasks run, LOW POWER devices should pre-cache NO MORE THAN 45 feeds)';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$admin_render_settings['strict_news_feed_servers']['is_repeatable']['add_button'] = 'Add Strict News Server Domain (at bottom)';

$admin_render_settings['strict_news_feed_servers']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$admin_render_settings['strict_news_feed_servers']['is_repeatable']['text_field_size'] = 25;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['news']['strict_news_feed_servers']) > 0 ) {

     foreach ( $ct['conf']['news']['strict_news_feed_servers'] as $key => $val ) {
     $admin_render_settings['strict_news_feed_servers']['is_subarray'][$key]['is_text'] = true;
     $admin_render_settings['strict_news_feed_servers']['is_subarray'][$key]['text_field_size'] = 25;
     }

}
else {
$admin_render_settings['strict_news_feed_servers']['is_subarray'][0]['is_text'] = true;
$admin_render_settings['strict_news_feed_servers']['is_subarray'][0]['text_field_size'] = 25;
}


$admin_render_settings['strict_news_feed_servers']['is_notes'] = '(DOMAIN ONLY, servers that PREFER an explicit "News Feed" user agent)';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$admin_render_settings['feeds']['is_repeatable']['add_button'] = 'Add News Feed (at bottom)';

$admin_render_settings['feeds']['is_repeatable']['is_text']['title'] = true;
$admin_render_settings['feeds']['is_repeatable']['is_text']['url'] = true;
$admin_render_settings['feeds']['is_repeatable']['text_field_size'] = 60;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['news']['feeds']) > 0 ) {

     foreach ( $ct['conf']['news']['feeds'] as $key => $val ) {
              
          foreach ( $val as $feed_key => $unused ) {
          $admin_render_settings['feeds']['has_subarray'][$key]['is_text'][$feed_key] = true;
          $admin_render_settings['feeds']['has_subarray'][$key]['text_field_size'] = 60;
          }
                                                                           
     }

}
else {
$admin_render_settings['feeds']['has_subarray'][0]['is_text']['title'] = true;
$admin_render_settings['feeds']['has_subarray'][0]['is_text']['url'] = true;
$admin_render_settings['feeds']['has_subarray'][0]['text_field_size'] = 60;
}


$admin_render_settings['feeds']['is_notes'] = 'Add RSS News Feeds here, to display in the USER AREA "News Feeds" section.<br />(auto-sorted alphabetically by title)';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'none';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('news', 'news_feeds', $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	