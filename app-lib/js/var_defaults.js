
// Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com


// Set ALL global vars (EVEN BLANK ONES)...

/*************************************************************************************************************************************
**************************************************************************************************************************************
*
* Javascript is unfortunately very nuanced in setting variables that are either GLOBAL (available EVERYWHERE in an app),
* or LOCAL (are 'sandboxed' inside functions, NOT CLASHING WITH SIMILAR-NAMED GLOBAL VARIABLES). That is why we EXPLICITLY DECLARE our
* GLOBAL variables / arrays VERY EARLY by putting them in this file (EVEN IF THEY ARE BLANK, AS THIS SETS THEM AS GLOBAL).
*
* DON'T FORGET TO ALWAYS DECLARE YOUR *LOCAL* VARIABLES / ARRAYS *INSIDE FUNCTIONS* (FOR SAFETY), USING THIS FORMAT: var my_variable
*
* https://stackoverflow.com/questions/10872006/how-do-i-change-the-value-of-a-global-variable-inside-of-a-function/10874509#10874509
*
**************************************************************************************************************************************
*************************************************************************************************************************************/

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

var font_size_css_selector;

var medium_font_size_css_selector;

var small_font_size_css_selector;
	
var theme_selected;
	
var sorted_by_col;

var sorted_asc_desc;
	
var charts_background;

var charts_border;

var desktop_zoom_storage; // Desktop edition zoom level

var sidebar_toggle_storage;

var cookies_notice_storage;

var scroll_position_storage;

var notes_storage;
	
var watch_only_flag_val;

var cookies_size_warning;
	
var logs_csrf_sec_token;

var admin_area_sec_level;

var enhanced_sec_token;

var reload_time;

var reload_countdown;
	
var reload_recheck;
	
var background_tasks_recheck;

var alert_color_gain;

var alert_color_loss;

var audio_alert_played;

var iframe_font_val;

var iframe_height_adjuster;

var iframe_text_adjuster;
	
var min_fiat_val_test;
	
var min_crypto_val_test;
	
var feeds_num;
	
var charts_num;
	
var btc_prim_currency_val;

var btc_prim_currency_pair;

// With defaults

var emulated_cron_enabled = false;
	
// Register as no-action-needed (if cron is enabled in header.php, this will reset properly in init.js)
var cron_already_ran = true; 

var custom_3deep_menu_on = false;

var is_admin = false;

var is_iframe = false;

var form_submit_queued = false;
	
var background_tasks_status = 'wait';

