<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



// Initial BLANK arrays

$ct['registered_light_charts'] = array();

$ct['coingecko_currencies'] = array();

$ct['jup_ag_address_mapping'] = array();

$ct['jup_ag_runtime_cache'] = array();

$ct['db_upgrade_desc'] = array();

$ct['plug_version'] = array();

$ct['plugin_setting_resets'] = array();

$ct['reset_plugin'] = array();

$ct['registered_pairs'] = array();

$ct['conf_parse_error'] = array();

$ct['admin_render_settings'] = array();

$ct['repeatable_fields_tracking'] = array();

$ct['check_crypto_pair'] = array();

$ct['log_errors'] = array();

$ct['log_debugging'] = array();

$ct['change_dir_perm'] = array();

$ct['sel_opt'] = array();

$ct['runtime_data'] = array();

$ct['plug_runtime_data'] = array();

$ct['system_warnings'] = array();

$ct['system_warnings_cron_interval'] = array();

$ct['rand_color_ranged'] =  array();

$ct['processed_msgs'] = array();

$ct['api_runtime_cache'] = array();

$ct['limited_api_calls'] = array();

$ct['coingecko_api'] = array();

$ct['coinmarketcap_api'] = array();

$ct['asset_stats_array'] = array();

$ct['asset_tracking'] =  array();

$ct['btc_pair_mrkts'] = array();

$ct['btc_pair_mrkts_excluded'] = array();

$ct['btc_crypto_pair_mrkts_excluded'] = array();

$ct['btc_worth_array'] = array();

$ct['stocks_btc_worth_array'] = array();

$ct['price_alert_fixed_reset_array'] = array();

$ct['proxy_checkup'] = array();

$ct['proxies_checked'] = array();

$ct['telegram_user_data'] = array();

$ct['last_valid_chart_data'] = array();

$ct['api_throttle_count'] = array();

$ct['int_webhooks'] = array();

$ct['activated_sms_services'] = array();
        
$ct['log_access_stats'] = array();
        
$ct['show_access_stats'] = array();

$ct['jupiter_ag_pairs'] =  array();

$plug['conf'] =  array();

$plug['class'] = array();

$plug['webhook'] = array();

$plug['activated'] =  array();


// Initial BLANK strings

$ct['alerts_gui_logs'] = null;

$ct['cmc_notes'] = null;

$ct['td_color_zebra'] = null;

$ct['mcap_data_force_usd'] = null;
        
$ct['upbit_batched_markets'] = null;
        
$ct['coingecko_pairs'] = null;
        
$ct['coingecko_assets'] = null;

$ct['restore_conf_path'] = null;

$ct['cached_conf_path'] = null;

$ct['sms_service'] = null;

$ct['check_2fa_id'] = null;

$ct['check_2fa_error'] = null;

$ct['telegram_user_data_path'] = null;


// Initial zero-set / false strings

$ct['dir_creation'] = false; // Flag if directory creation attempts occurred this runtime

$ct['light_chart_reset'] = false;

$ct['config_upgrade_check'] = false;

$ct['fast_runtime'] = false;

$ct['sort_by_nested'] = false;

$ct['is_subsection_config'] = false;

$ct['verified_update_request'] = false;

$ct['active_plugins_registered'] = false;

$ct['reset_config'] = false;

$ct['config_was_reset'] = false;

$ct['update_config'] = false;

$ct['possible_input_injection'] = false;

$ct['update_config_halt'] = false;

$ct['conf_upgraded'] = false;

$ct['is_login_form'] = false;

$ct['ticker_markets_search'] = false;

$ct['auth_secret'] = false;

$ct['smtp_server_ok'] = true; // true, in case we are NOT using it
        
$ct['alphavantage_pairs'] = 0;

$ct['precache_feeds_count'] = 0; 

$ct['light_chart_first_build_count'] = 0; 

$ct['count_2fa_fields'] = 0;

$ct['jupiter_ag_search_results'] = 0;

$ct['min_fiat_val_test'] = 0;

$ct['min_crypto_val_test'] = 0;

$ct['activate_proxies'] = 'off';


// INITIALLY defined


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>