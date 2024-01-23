<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


///////////////////////////////////////////////////
// **START** DEVELOPER-ONLY CONFIGS
///////////////////////////////////////////////////


// Runs in /app-lib/php/init.php
if ( $dev_only_configs_mode == 'init' ) {


// Application version
$ct['app_version'] = '6.00.35';  // 2024/JANUARY/23RD


// #PHP# ERROR LOGGING
// Can take any setting shown here: https://www.php.net/manual/en/function.error-reporting.php
// 0 = off, -1 = on (IF *NOT* SET TO ZERO HERE, THIS #OVERRIDES# PHP ERROR DEBUG SETTINGS IN THE APP'S USER CONFIG SETTINGS)
// WRAP VALUE(S) IN PARENTHESIS, SO MUTIPLE VALUES CAN BE USED: (0) / (-1) / (E_ERROR | E_PARSE)
$ct['dev']['debug_php_errors'] = (0); 


// Time offset (IN MINUTES) on daily background tasks 
// (so they run at the same time everyday [without 'creeping' up / down for it's time-of-day run])
$ct['dev']['tasks_time_offset'] = -4; // Auto-adjusts (higher) for systems with LOW CORE COUNTS in config-init.php


// min / max font RESIZE percentages allowed (as decimal representing 100% @ 1.00)
$ct['dev']['min_font_resize'] = 0.5; // 50%
////
$ct['dev']['max_font_resize'] = 2.0; // 200%


// FONT WEIGHT for ALL text in app (as a CSS value)
$ct['dev']['global_font_weight'] = 400; // 400 for ANY font size


// LINE HEIGHT PERCENTAGE for ALL text in app (as a decimal)
$ct['dev']['global_line_height_percent'] = 1.50; // 150% line height for ANY font size


// info icon size CSS configs
$ct['dev']['info_icon_size_css_selector'] = "img.tooltip_style_control";

// ajax loading size CSS configs
$ct['dev']['ajax_loading_size_css_selector'] = "img.ajax_loader_image";

// password eye icon size CSS configs
$ct['dev']['password_eye_size_css_selector'] = "i.gg-eye, i.gg-eye-alt";

// standard font size CSS configs
$ct['dev']['font_size_css_selector'] = "#sidebar_menu, #header_size_warning, #alert_bell_area, #background_loading, radio, .full_width_wrapper:not(.custom-select), .iframe_wrapper:not(.custom-select), .footer_content, .footer_banner, .countdown_notice, .sidebar-slogan, .pw_prompt";

// These selector(s) are wonky for some reason in LINUX PHPDESKTOP (but work fine in all modern browsers)
// (dynamically appended conditionally in primary-init.php)
$ct['dev']['small_font_size_css_selector_adjusted'] = ", #admin_conf_quick_links a:link, #admin_conf_quick_links legend, td.data";
$ct['dev']['tiny_font_size_css_selector_adjusted'] = ", .crypto_worth";

// medium font size CSS configs
$ct['dev']['medium_font_size_css_selector'] = ".unused_for_appending";

// small font size CSS configs
$ct['dev']['small_font_size_css_selector'] = ".unused_for_appending, .gain, .loss, .crypto_worth, .extra_data";

// small font size CSS configs
$ct['dev']['tiny_font_size_css_selector'] = ".accordion-button";


// PERCENT of STANDARD font size (as a decimal)
$ct['dev']['medium_font_size_css_percent'] = 0.90; // 90% of $set_font_size
////
// PERCENT of STANDARD font size (as a decimal)
$ct['dev']['small_font_size_css_percent'] = 0.75; // 75% of $set_font_size
////
// PERCENT of STANDARD font size (as a decimal)
$ct['dev']['tiny_font_size_css_percent'] = 0.60; // 60% of $set_font_size


// Default charset used
$ct['dev']['charset_default'] = 'UTF-8'; 
////
// Unicode charset used (if needed)
// UCS-2 is outdated as it only covers 65536 characters of Unicode
// UTF-16BE / UTF-16LE / UTF-16 / UCS-2BE can represent ALL Unicode characters
$ct['dev']['charset_unicode'] = 'UTF-16'; 


// Cache directories / files and .htaccess / index.php files permissions (CHANGE WITH #EXTREME# CARE, to adjust security for your PARTICULAR setup)
// THESE PERMISSIONS ARE !ALREADY! CALLED THROUGH THE octdec() FUNCTION *WITHIN THE APP WHEN USED*
////
// Cache directories permissions
$ct['dev']['chmod_cache_dir'] = '0770'; // (default = '0770' [owner/group read/write/exec])
////
// Cache files permissions
$ct['dev']['chmod_cache_file'] = '0660'; // (default = '0660' [owner/group read/write])
////
// .htaccess / index.php index security files permissions
$ct['dev']['chmod_index_sec'] = '0660'; // (default = '0660' [owner/group read/write])
			
									
// !!!!! BE #VERY CAREFUL# LOWERING MAXIMUM EXECUTION TIMES BELOW, #OR YOU MAY CRASH THE RUNNING PROCESSES EARLY, 
// OR CAUSE MEMORY LEAKS THAT ALSO CRASH YOUR !ENTIRE SYSTEM!#
// (ALL maximum execution times are automatically 900 seconds [15 minutes] IN DEBUG MODE)
////
// Maximum execution time for interface runtime in seconds (how long it's allowed to run before automatically killing the process)
$ct['dev']['ui_max_exec_time'] = 250; // (default = 250)
////
// Maximum execution time for ajax runtime in seconds (how long it's allowed to run before automatically killing the process)
$ct['dev']['ajax_max_exec_time'] = 250; // (default = 250)
////
// Maximum execution time for cron job runtime in seconds (how long it's allowed to run before automatically killing the process)
$ct['dev']['cron_max_exec_time'] = 1320; // (default = 1320)
////
// Maximum execution time for internal API runtime in seconds (how long it's allowed to run before automatically killing the process)
$ct['dev']['int_api_max_exec_time'] = 120; // (default = 120)
////
// Maximum execution time for webhook runtime in seconds (how long it's allowed to run before automatically killing the process)
$ct['dev']['webhook_max_exec_time'] = 120; // (default = 120)


// CAPTCHA text settings...
// Text size
$ct['dev']['captcha_text_size'] = 50; // Text size (default = 50)
////
// Number of characters
$ct['dev']['captcha_chars_length'] = 7; // Number of characters in captcha image (default = 7)
////
// Configuration for advanced CAPTCHA image settings on all admin login / reset pages
$ct['dev']['captcha_image_width'] = 525; // Image width (default = 525)
////
$ct['dev']['captcha_image_height'] = 135; // Image height (default = 135)
////
$ct['dev']['captcha_text_margin'] = 10; // MINIMUM margin of text from edge of image (approximate / average) (default = 10)
////		
// Only allow the MOST READABLE characters for use in captcha image 
// (DON'T SET TOO LOW, OR BOTS CAN GUESS THE CAPTCHA CODE EASIER)
$ct['dev']['captcha_permitted_chars'] = 'ABCDEFHJKMNPRSTUVWXYZ23456789'; // (default = 'ABCDEFHJKMNPRSTUVWXYZ23456789')
     
     
// Servers requiring TRACKED THROTTLE-LIMITING, due to limited-allowed minute / hour / daily requests
// (are processed by ct_cache->api_throttling(), to avoid using up daily request limits)
// ADDITIONAL (CORRISPONDING) LOGIC MUST BE ADDED IN /inline/config/throttled-markets-config.php
$ct['dev']['tracked_throttle_limited_servers'] = array(
                                                       // 'tld_domain_name' => 'corrisponding_exchange_identifier_OR_BOOLEAN_TRUE',
                                                     	'alphavantage.co' => 'alphavantage_stock',
                                                      );
     
     
// Servers which are known to block API access by location / jurasdiction
// (we alert end-users in error logs, when a corrisponding API server connection fails [one-time notice per-runtime])
$ct['dev']['location_blocked_servers'] = array(
                                               // 'tld_domain_name',
                                               'binance.com',
                                               'bybit.com',
                                              );


// MAIN CONFIG settings subarray keys to ALLOW cached config RESETS on (during cached config upgrades)
// (can manipulate later on, based on app version number / user input / etc)
// THIS ALWAYS OVERRIDES 'config_deny_additions' AND 'config_deny_removals'
$ct['dev']['config_allow_resets'] = array();


// MAIN CONFIG settings subarray keys to DENY cached config settings ADDITIONS on (during cached config upgrades)
// (can manipulate later on, based on app version number / user input / etc)
// INCLUDE NUMERIC / AUTO-INDEXING KEYED ARRAYS, EVEN THOUGH WE DON'T SUPPORT THEM WELL *YET*
$ct['dev']['config_deny_additions'] = array(
                                           'anti_proxy_servers', // Subarray setting (anti-proxy servers)
                                           'proxy_list', // Subarray setting (proxy servers)
                                           );


// MAIN CONFIG settings subarray keys to DENY cached config settings REMOVALS on (during cached config upgrades)
// (can manipulate later on, based on app version number / user input / etc)
// INCLUDE NUMERIC / AUTO-INDEXING KEYED ARRAYS, EVEN THOUGH WE DON'T SUPPORT THEM WELL *YET*
$ct['dev']['config_deny_removals'] = array(
                                           'anti_proxy_servers', // Subarray setting (anti-proxy servers)
                                           'proxy_list', // Subarray setting (proxy servers)
                                           'strict_news_feed_servers', // Subarray setting (strict news feed servers)
                                           'feeds', // Subarray setting (news feeds)
                                           'tracked_markets', // Subarray setting (asset charts / price alerts)
                                           'text_gateways', // Subarray setting (mobile text gateways)
                                           'assets', // Main category (portfolio assets)
                                          );


// PLUGIN setting keys to ALLOW cached config RESETS on (during cached config upgrades)
// (can manipulate later on, based on app version number / user input / etc)
$ct['dev']['plugin_allow_resets'] = array(
                                          
                                          // Format example (dynamically add at top of plugin's plug-conf.php file)
                                          'my-plugin-name' => array(
                                                                    'plugin-setting-key',
                                                                   ),
                                                                   
                                         );
     
     
// Exchange APIs that have NO TRADE VOLUME DATA
// (for UX on trade volume data in interface)
$ct['dev']['no_trade_volume_api_data'] = array(
                                                // 'exchange-config-key-name-here',
                                                'misc_assets',
                                                'btc_nfts',
                                                'eth_nfts',
                                                'sol_nfts',
                                                'alt_nfts',
                                                'jupiter_ag',
                                                'coinspot',
                                                'unocoin',
                                               );
							

// PRIMARY Domain only (NO SUBDOMAINS), for each API service that requires multiple calls (for each data set)
// Used to throttle these market calls a tiny bit (0.35 seconds), so we don't get easily blocked / throttled by external APIs etc
// (ANY EXCHANGES LISTED HERE ARE FLAGGED IN THE INTERFACE AS !NOT! RECOMMENDED TO BE USED AS THE PRIMARY CURRENCY MARKET IN THIS APP,
// AS ON OCCASSION THEY CAN BE !UNRELIABLE! IF HIT WITH TOO MANY SEPARATE API CALLS FOR MULTIPLE COINS / ASSETS)
// !MUST BE LOWERCASE!
// #DON'T ADD ANY WEIRD TLD HERE LIKE 'xxxxx.co.il'#, AS DETECTING TLD DOMAINS WITH MORE THAN ONE PERIOD IN THEM ISN'T SUPPORTED
// WE DON'T WANT THE REQUIRED EXTRA LOGIC TO PARSE THESE DOUBLE-PERIOD TLDs BOGGING DOWN / CLUTTERING APP CODE, FOR JUST ONE TINY FEATURE
$ct['dev']['limited_apis'] = array(
                          		'aevo.xyz',
                          		'alphavantage.co',
                          		'anchor.fm',
                          		'bitforex.com',
                          		'bitflyer.com',
                          		'bitmex.com',
                          		'bitso.com',
                          		'bitstamp.net',
                          		'blockchain.info',
                          		'btcmarkets.net',
                          		'coinbase.com',
                          		// (coingecko #ABSOLUTELY HATES# DATA CENTER IPS [DEDICATED / VPS SERVERS], BUT GOES EASY ON RESIDENTIAL IPS)
                          	     'coingecko.com',
                          	     'geckoterminal.com',
                          		'etherscan.io',
                          		'gemini.com',
                          		'medium.com',
                          		'megaphone.fm',
                          		'reddit.com',
                          		'solana.com',
                          		'substack.com',
                          		'stackexchange.com',
                          		'youtube.com',
          				  );

        
// Attack signatures, used when scanning for script / HTML injection attacks
// (via sanitize_string(), called in sanitize_requests() / early-security-logic.php, when scanning all POST / GET data submissions)
// MUST BE LOWERCASE!!! (AS WE TEMPORARILY CONVERT THE DATA SUBMISSION TO LOWERCASE WHEN SCANNING!!!)
$ct['dev']['script_injection_checks'] = array(
                                               "base64", // base64 PHP ENCODE / DECODE
                                               "btoa(", // base64 javascript ENCODE
                                               "atob(", // base64 javascript DECODE
                                               "bin2hex", // hex PHP ENCODE
                                               "hex2bin", // hex PHP DECODE
                                               "\u", // Unicode ENCODE
                                               "\x", // Hex ENCODE
                                               "char(", // SQL CHAR() function
                                               "javascript", // Javascript
                                               "script", // Javascript
                                               "href=", // HTML
                                               "src=", // HTML
                                               // ALL javascript 'on' events
                                               "onclick",
                                               "onmouse",
                                               "onresize",
                                               "onchange",
                                               "onabort",
                                               "onblur",
                                               "ondblclick",
                                               "ondragdrop",
                                               "onerror",
                                               "onfocus",
                                               "onkey",
                                               "onload",
                                               "onmove",
                                               "onreset",
                                               "onselect",
                                               "onsubmit",
                                               "onunload",
                                             );
                           

}
// Runs in /app-lib/php/inline/init/config-init.php, within the logic that runs during upgrade checks
elseif ( $dev_only_configs_mode == 'config-init-upgrade-check' ) {


     // v6.00.30:
     // RESET the 'assets' and 'tracked_markets' cached config values
     // (bittrex is going out of business GLOBALLY on 2023/12/4, AND WE HAVE TONS OF BITTREX MARKETS IN OUR PREVIOUS DEMO DATA WE NEED TO PURGE)
     if ( $ct['app_version'] == '6.00.30' ) {
     $ct['dev']['config_allow_resets'][] = 'tracked_markets'; // Subarray setting (asset charts / price alerts)
     $ct['dev']['config_allow_resets'][] = 'assets'; // Main category (portfolio assets)
     }
     
                                     
}
// Runs in /app-lib/php/inline/config/after-load-config.php (because user config values are used)
elseif ( $dev_only_configs_mode == 'after-load-config' ) {


// Obfuscate these matches in ALL error / debugging logs
// (ONLY ADD SENSITIVE VALUES HERE THAT COULD SHOW IN URL GET REQUEST DATA / ERROR NOTICES / ETC)
$ct['dev']['data_obfuscating'] = array(
                                      // 'hide_this',
                                      $ct['conf']['comms']['from_email'],
                                      $ct['conf']['comms']['to_email'],
                                      $ct['conf']['ext_apis']['telegram_your_username'],
                                      $ct['conf']['ext_apis']['telegram_bot_username'],
                                      $ct['conf']['ext_apis']['telegram_bot_token'],
                                      $ct['conf']['ext_apis']['twilio_number'],
                                      $ct['conf']['ext_apis']['twilio_sid'],
                                      $ct['conf']['ext_apis']['twilio_token'],
                                      $ct['conf']['ext_apis']['textbelt_api_key'],
                                      $ct['conf']['ext_apis']['textlocal_api_key'],
                                      $ct['conf']['ext_apis']['google_fonts_api_key'],
                                      $ct['conf']['ext_apis']['etherscan_api_key'],
                                      $ct['conf']['ext_apis']['alphavantage_api_key'],
                                     );
                                     
                                     
}


///////////////////////////////////////////////////
// **END** DEVELOPER-ONLY CONFIGS
///////////////////////////////////////////////////


// DON'T CREATE ANY WHITESPACE AFTER CLOSING PHP TAG, A WE ARE STILL IN INIT! (NO HEADER ESTABLISHED YET)

?>