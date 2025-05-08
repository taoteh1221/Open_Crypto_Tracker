<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>

				<div class='bitcoin align_center' style='margin-bottom: 20px;'>(advanced configuration, handle with care)</div>


<?php
if ( $ct['admin_area_sec_level'] == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['php_error_reporting']['is_radio']['is_assoc'][] = array(
                                                                                   'key' => 0,
                                                                                   'val' => 'off',
                                                                                  );


$ct['admin_render_settings']['php_error_reporting']['is_radio']['is_assoc'][] = array(
                                                                                   'key' => -1,
                                                                                   'val' => 'on',
                                                                                  );

$ct['admin_render_settings']['php_error_reporting']['is_notes'] = 'Enable / disable PHP error reporting (to SERVER logs on the app server)<br /><span class="red">ONLY ENABLE PHP ERROR REPORTING FOR SHORT PERIODS OF TIME, IF YOUR APP SERVER SHOWS ERRORS ON WEB PAGES (INSTEAD OF PRIVATELY RECORDING THEM TO A SERVER LOG FILE)!</span>';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['debug_mode']['is_select'] = array(
                                                               'off',
                                                               'conf_telemetry',
                                                               'light_chart_telemetry',
                                                               'memory_usage_telemetry',
                                                               'ext_data_live_telemetry',
                                                               'ext_data_cache_telemetry',
                                                               'smtp_telemetry',
                                                               'api_comms_telemetry',
                                                               'cron_telemetry',
                                                               'setup_wizards_io',
                                                               'markets',
                                                               'texts',
                                                               'alerts_charts',
                                                               'api_throttling',
                                                               'stats',
                                                               'markets_conf',
                                                              );

$ct['admin_render_settings']['debug_mode']['is_notes'] = 'Enable / disable the debugging logs for this app<br /> ' . nl2br("////
// ### GENERAL ###
////
// 'off' (disables), 
////
// ### TELEMETRY ###
////
// 'conf_telemetry' (ct['conf'] caching),
// 'light_chart_telemetry' (light chart caching),
// 'memory_usage_telemetry' (PHP system memory usage),
// 'ext_data_live_telemetry' (external API requests from server),
// 'ext_data_cache_telemetry' (external API requests from cache),
// 'smtp_telemetry' (smtp server responses to: /cache/logs/smtp_debug.log),
// 'api_comms_telemetry' (API comms responses to: /cache/logs/debug/external_data/last-response-[service].log),
// 'cron_telemetry' (cron runtime telemetry to: /cache/logs/debug/cron/cron_runtime_telemetry.log),
////
// ### VIEW INPUT / OUTPUT (ON INTERFACE PAGES) ###
////
// 'setup_wizards_io' (AJAX-based 'wizard' steps),
////
// ### CHECKS ###
////
// 'markets' (asset market checks),
// 'texts' (mobile texting gateway checks), 
// 'alerts_charts' (price chart / price alert checks),
// 'api_throttling' (API throttling checks),
////
// ### SUMMARIES ###
////
// 'stats' (hardware / software / runtime summary),
// 'markets_conf' (outputs a markets configuration summary),
////") . '</span>';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['log_verbosity']['is_radio'] = array(
                                                               'normal',
                                                               'verbose',
                                                              );

$ct['admin_render_settings']['log_verbosity']['is_notes'] = 'Level of detail / verbosity in log files. "normal" logs minimal details (basic information)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['override_curl_user_agent']['is_textarea'] = true;

$ct['admin_render_settings']['override_curl_user_agent']['is_notes'] = 'If you want to override the default CURL user agent string (sent with API requests, etc), adding a string here automatically enables that as the custom curl user agent<br /><span class="red">ONLY ADD SOMETHING HERE IF YOU KNOW WHAT YOU ARE DOING!</span>';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['desktop_cron_interval']['is_range'] = true;

$ct['admin_render_settings']['desktop_cron_interval']['range_ui_meta_data'] .= 'zero_is_disabled;';

$ct['admin_render_settings']['desktop_cron_interval']['range_ui_meta_data'] .= 'is_custom_steps;';

$ct['admin_render_settings']['desktop_cron_interval']['range_min'] = 0;

$ct['admin_render_settings']['desktop_cron_interval']['range_max'] = 60;

$ct['admin_render_settings']['desktop_cron_interval']['range_ui_prefix'] = 'Every ';

$ct['admin_render_settings']['desktop_cron_interval']['range_ui_suffix'] = ' Minutes';
     
$ct['admin_render_settings']['desktop_cron_interval']['is_custom_steps'] = array(
                                                                                   '0',
                                                                                   '5',
                                                                                   '10',
                                                                                   '15',
                                                                                   '20',
                                                                                   '30',
                                                                                   '60',
                                                                                  );

$ct['admin_render_settings']['desktop_cron_interval']['is_notes'] = 'MINUTES to wait until running consecutive DESKTOP EDITION emulated cron jobs / background tasks<br /><span class="red">(HOW OFTEN BACKGROUND TASKS ARE RUN...#NOT# USED IN SERVER EDITION)</span>';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['access_stats_delete_old']['is_range'] = true;

$ct['admin_render_settings']['access_stats_delete_old']['range_min'] = 15;

$ct['admin_render_settings']['access_stats_delete_old']['range_max'] = 360;

$ct['admin_render_settings']['access_stats_delete_old']['range_step'] = 15;

$ct['admin_render_settings']['access_stats_delete_old']['range_ui_prefix'] = 'Delete After ';

$ct['admin_render_settings']['access_stats_delete_old']['range_ui_suffix'] = ' Days';

$ct['admin_render_settings']['access_stats_delete_old']['is_notes'] = 'Delete access stats older than X DAYS';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['last_trade_cache_time']['is_range'] = true;

$ct['admin_render_settings']['last_trade_cache_time']['range_ui_meta_data'] .= 'zero_is_disabled;';

$ct['admin_render_settings']['last_trade_cache_time']['range_min'] = 0;

$ct['admin_render_settings']['last_trade_cache_time']['range_max'] = 60;

$ct['admin_render_settings']['last_trade_cache_time']['range_step'] = 1;

$ct['admin_render_settings']['last_trade_cache_time']['range_ui_suffix'] = ' Minutes';

$ct['admin_render_settings']['last_trade_cache_time']['is_notes'] = 'MINUTES to cache real-time exchange price data...can be zero to DISABLE cache, but set to at least 1 minute TO AVOID YOUR IP ADDRESS GETTING BLOCKED!';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['exchange_search_cache_time']['is_range'] = true;

$ct['admin_render_settings']['exchange_search_cache_time']['range_min'] = 10;

$ct['admin_render_settings']['exchange_search_cache_time']['range_max'] = 60;

$ct['admin_render_settings']['exchange_search_cache_time']['range_step'] = 5;

$ct['admin_render_settings']['exchange_search_cache_time']['range_ui_suffix'] = ' MINUTE cache';

$ct['admin_render_settings']['exchange_search_cache_time']['is_notes'] = 'MINUTES to cache data, for exchange "search" mode, when adding new asset markets in the admin interface';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['blockchain_stats_cache_time']['is_range'] = true;

$ct['admin_render_settings']['blockchain_stats_cache_time']['range_ui_meta_data'] .= 'zero_is_disabled;';

$ct['admin_render_settings']['blockchain_stats_cache_time']['range_min'] = 0;

$ct['admin_render_settings']['blockchain_stats_cache_time']['range_max'] = 100;

$ct['admin_render_settings']['blockchain_stats_cache_time']['range_step'] = 20;

$ct['admin_render_settings']['blockchain_stats_cache_time']['range_ui_suffix'] = ' Minutes';

$ct['admin_render_settings']['blockchain_stats_cache_time']['is_notes'] = 'MINUTES to cache blockchain stats (for mining calculators)...can be zero to DISABLE cache, but set to at least 60 minutes TO AVOID YOUR IP ADDRESS GETTING BLOCKED!';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['marketcap_cache_time']['is_range'] = true;

$ct['admin_render_settings']['marketcap_cache_time']['range_ui_meta_data'] .= 'zero_is_disabled;';

$ct['admin_render_settings']['marketcap_cache_time']['range_min'] = 0;

$ct['admin_render_settings']['marketcap_cache_time']['range_max'] = 120;

$ct['admin_render_settings']['marketcap_cache_time']['range_step'] = 20;

$ct['admin_render_settings']['marketcap_cache_time']['range_ui_suffix'] = ' Minutes';

$ct['admin_render_settings']['marketcap_cache_time']['is_notes'] = 'MINUTES to cache marketcap rankings...can be zero to DISABLE cache, but set to at least 100 minutes TO AVOID YOUR IP ADDRESS GETTING BLOCKED!';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['marketcap_ranks_max']['is_range'] = true;

$ct['admin_render_settings']['marketcap_ranks_max']['range_min'] = 100;

$ct['admin_render_settings']['marketcap_ranks_max']['range_max'] = 500;

$ct['admin_render_settings']['marketcap_ranks_max']['range_step'] = 100;

$ct['admin_render_settings']['marketcap_ranks_max']['range_ui_suffix'] = ' Rankings';

$ct['admin_render_settings']['marketcap_ranks_max']['is_notes'] = 'Number of marketcap rankings to request from API<br />300 rankings is a safe maximum to START WITH, to avoid getting your API requests THROTTLED / BLOCKED';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['margin_leverage_maximum']['is_range'] = true;

$ct['admin_render_settings']['margin_leverage_maximum']['range_min'] = 25;

$ct['admin_render_settings']['margin_leverage_maximum']['range_max'] = 250;

$ct['admin_render_settings']['margin_leverage_maximum']['range_step'] = 25;

$ct['admin_render_settings']['margin_leverage_maximum']['range_ui_suffix'] = 'x Leverage';

$ct['admin_render_settings']['margin_leverage_maximum']['is_notes'] = 'Maximum margin leverage available in the user interface ("Update Portfolio" page, etc)';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['backup_archive_delete_old']['is_range'] = true;

$ct['admin_render_settings']['backup_archive_delete_old']['range_min'] = 5;

$ct['admin_render_settings']['backup_archive_delete_old']['range_max'] = 30;

$ct['admin_render_settings']['backup_archive_delete_old']['range_step'] = 5;

$ct['admin_render_settings']['backup_archive_delete_old']['range_ui_suffix'] = ' Days';

$ct['admin_render_settings']['backup_archive_delete_old']['is_notes'] = 'Days TO WAIT UNTIL DELETING OLD backup archives (chart data archives, etc)';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['logs_purge']['is_range'] = true;

$ct['admin_render_settings']['logs_purge']['range_min'] = 5;

$ct['admin_render_settings']['logs_purge']['range_max'] = 15;

$ct['admin_render_settings']['logs_purge']['range_step'] = 5;

$ct['admin_render_settings']['logs_purge']['range_ui_suffix'] = ' Days';

$ct['admin_render_settings']['logs_purge']['is_notes'] = 'Keep logs X DAYS before purging (fully deletes logs every X days)<br />Start low (especially when using proxies)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['system_uptime_warning']['is_text'] = true;

$ct['admin_render_settings']['system_uptime_warning']['text_field_size'] = 15;

$ct['admin_render_settings']['system_uptime_warning']['is_notes'] = 'If SYSTEM UPTIME has only been up X DAYS (or less), trigger warning<br />This format MUST be used: days_uptime||hours_between_alerts';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['system_load_warning']['is_text'] = true;

$ct['admin_render_settings']['system_load_warning']['text_field_size'] = 15;

$ct['admin_render_settings']['system_load_warning']['is_notes'] = 'SYSTEM LOAD warning default is 2x number of cores your app server has (1 core system = load level 2.00 would trigger an alert)<br />SYSTEM LOAD THRESHOLD MULTIPLIER allows you to adjust when warning is triggered (0.5 is half default, 2.00 is 2x default, etc)<br />This format MUST be used: threshold_multiplier||hours_between_alerts';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['system_temperature_warning']['is_text'] = true;

$ct['admin_render_settings']['system_temperature_warning']['text_field_size'] = 15;

$ct['admin_render_settings']['system_temperature_warning']['is_notes'] = 'If system TEMPERATURE is X degrees celcius (or more), trigger warning<br />This format MUST be used: temp_celcius||hours_between_alerts';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['memory_used_percent_warning']['is_text'] = true;

$ct['admin_render_settings']['memory_used_percent_warning']['text_field_size'] = 15;

$ct['admin_render_settings']['memory_used_percent_warning']['is_notes'] = 'If USED MEMORY PERCENTAGE is X (or more), trigger warning<br />This format MUST be used: memory_used_percent||hours_between_alerts';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['free_partition_space_warning']['is_text'] = true;

$ct['admin_render_settings']['free_partition_space_warning']['text_field_size'] = 15;

$ct['admin_render_settings']['free_partition_space_warning']['is_notes'] = 'If FREE STORAGE space is X MEGABYTES (or less), trigger warning<br />This format MUST be used: free_space_megabytes||hours_between_alerts';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['portfolio_cache_warning']['is_text'] = true;

$ct['admin_render_settings']['portfolio_cache_warning']['text_field_size'] = 15;

$ct['admin_render_settings']['portfolio_cache_warning']['is_notes'] = 'If PORTFOLIO CACHE SIZE is X MEGABYTES (or more), trigger warning<br />This format MUST be used: portfolio_cache_megabytes||hours_between_alerts';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['cookies_size_warning']['is_text'] = true;

$ct['admin_render_settings']['cookies_size_warning']['text_field_size'] = 15;

$ct['admin_render_settings']['cookies_size_warning']['is_notes'] = 'If ALL COOKIES TOTAL DATA SIZE is X BYTES (or more), trigger warning<br />This format MUST be used: cookies_size_bytes||hours_between_alerts';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
$ct['admin_render_settings']['is_refresh_admin'] = 'all';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('power', 'power_user', $ct['admin_render_settings']);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	