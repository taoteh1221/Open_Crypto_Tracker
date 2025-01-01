
// Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)


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

var orig_iframe_src = new Array();

var all_admin_iframe_ids = new Array();

var selected_admin_iframe_ids = new Array();

var limited_apis = new Array();

var heading_tag_sizes = new Array();

var admin_interface_check = new Array();

var skip_corrupt_sections = new Array();

var feeds_loaded = new Array();

var charts_loaded = new Array();
	
var pref_bitcoin_mrkts = new Array();

var secondary_mrkt_currencies = new Array();

var original_page_title = new Array();

// Objects

var jstree_json_data = {};

// Strings

var ct_id; // Install ID (derived from this app's server path)
	
var app_edition;

var app_platform;

var app_container;
	
var logs_csrf_sec_token;

var medium_sec_token;
	
var admin_iframe_url;

var admin_area_sec_level;

var admin_area_2fa;

var admin_iframe_dom;
	
var sorted_by_col;

var sorted_asc_desc;
	
var theme_selected;

var scrollbar_theme;
	
var charts_background;

var charts_border;

var heading_css_unit;

var global_line_height_percent;

var set_font_size;

var font_name_url_formatting;

var font_size_css_selector;

var medium_font_size_css_selector;

var small_font_size_css_selector;

var tiny_font_size_css_selector;

var medium_font_size_css_percent;

var small_font_size_css_percent;

var tiny_font_size_css_percent;

var info_icon_size_css_selector;

var ajax_loading_size_css_selector;

var password_eye_size_css_selector;

var sidebar_toggle_storage;

var cookies_notice_storage;

var safari_notice_storage;

var desktop_windows_notice_storage;

var donations_notice_storage;

var refresh_cache_upgrade_notice_storage;

var issues_page_visit_time_storage;

var scroll_position_storage;

var notes_storage;

var reload_time;

var latest_important_dev_alerts_timestamp;

var reload_countdown;
	
var reload_recheck;

var alert_color_gain;

var alert_color_loss;

var audio_alert_played;

var iframe_height_adjuster;

var iframe_text_adjuster;

var iframe_font_val;
	
var min_fiat_val_test;
	
var min_crypto_val_test;
	
var feeds_num;
	
var charts_num;
	
var btc_prim_currency_val;

var bitcoin_primary_currency_pair;
	
var watch_only_flag_val;

var cookies_size_warning;
	
var background_tasks_recheck;

var range_inputs;

var collapsed_sidebar_scroll_position;

// With defaults
	
var gen_csrf_sec_token = Base64.encode('none');

var disable_nav_save_buttons = false;

var form_submit_queued = false;

var is_login_form = false;

var is_safari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;

var is_iframe = false;

var is_admin = false;

var admin_logged_in = false;

var admin_settings_save_init = false;

var unsaved_admin_config = false;

var unsaved_user_config = false;

var emulated_cron_enabled = false;
	
var emulated_cron_task_only = false;
	
// Register as no-action-needed (if cron is enabled in header.php, this will reset properly in init.js)
var cron_already_ran = true; 
	
var all_tasks_initial_load = true;
	
var background_tasks_status = 'wait';

var custom_3deep_menu_open = false;


