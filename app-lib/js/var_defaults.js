
// Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com


// Set global vars...

// Arrays

var modal_windows = new Array(); // Set the modal windows array (to dynamically populate)

var feeds_loaded = new Array();

var charts_loaded = new Array();
	
var pref_bitcoin_mrkts = new Array();

var limited_apis = new Array();

var secondary_mrkt_currencies = new Array();

// Strings

var ct_id; // Install ID (derived from this app's server path)
	
var app_edition;
	
var min_fiat_val_test;
	
var min_crypto_val_test;
	
var watch_only_flag_val;
	
var logs_csrf_sec_token;
	
var background_tasks_recheck;

var reload_countdown;
	
var reload_recheck;

var notes_storage;

var cookies_size_warning;
	
var theme_selected;

var iframe_font_val;

var iframe_height_adjuster;

var iframe_text_adjuster;

var admin_area_sec_level;

var enhanced_sec_token;
	
var feeds_num;
	
var charts_num;
	
var sorted_by_col;

var sorted_asc_desc;
	
var charts_background;

var charts_border;
	
var btc_prim_currency_val;

var btc_prim_currency_pair;

var font_size_css_selector;

var medium_font_size_css_selector;

var small_font_size_css_selector;

// With defaults

var emulated_cron_enabled = false;
	
// Register as no-action-needed (if cron is enabled in header.php, this will reset properly in init.js)
var cron_already_run = true; 

var custom_3deep_menu_on = false;

var is_admin = false;

var is_iframe = false;

var form_submit_queued = false;
	
var background_tasks_status = 'wait';

