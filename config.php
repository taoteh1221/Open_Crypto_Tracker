<?php
// DON'T LEAVE ANY WHITESPACE ABOVE THE OPENING PHP TAG!

/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
header('HTTP/1.0 403 Forbidden', TRUE, 403);
exit;
}


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PRIMARY CONFIGURATIONS -START- ////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

 
// BELOW IS AN EXAMPLE SET OF CONFIGURED ASSETS AND DEFAULT SETTINGS. PLEASE NOTE THIS IS PROVIDED TO ASSIST YOU IN ADDING YOUR PARTICULAR FAVORITE ASSETS TO THE DEFAULT LIST,
// AND !---IN NO WAY---! INDICATES ENDORSEMENT OR RECOMMENDATION OF !---ANY---! OF THE *DEMO* ASSETS!

// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG

// SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// See TROUBLESHOOTING.txt for tips / troubleshooting FAQs.

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC !!!!WILL BREAK THE APP!!!!, BE CAREFUL EDITING THIS CONFIG FILE!


////////////////////////////////////////
// !START! GENERAL CONFIGURATION
////////////////////////////////////////


// Your local time offset IN HOURS COMPARED TO UTC TIME (#CAN BE DECIMAL# TO SUPPORT 15 / 30 / 45 MINUTE TIME ZONES). Can be negative or positive.
// (Used for user experience 'pretty' timestamping in interface logic ONLY, WILL NOT change or screw up UTC log times etc if you change this)
$ct['conf']['gen']['local_time_offset'] = -4; // example: -5 or 5, -5.5 or 5.75


// Displays interface text in ANY google font found at: https://fonts.google.com
// Set as '' (blank) for default bootstrap / system / browser font
// 'font' OR 'font name' IN QUOTES for ANY google font, OR '' to skip
$ct['conf']['gen']['google_font'] = 'Exo 2'; // 'Exo 2' / 'Tektur' / etc any google font (default = 'Exo 2')


// DEFAULT font size PERCENTAGE (*WITHOUT* THE PERCENT SYMBOL!)
// LIMITS: MINIMUM OF 50 / MAXIMUM OF 200
$ct['conf']['gen']['default_font_size'] = 100; // Default = 100 (equal to 100%)


// Configure which interface theme you want as the default theme (also can be manually switched later, on the settings page in the interface)
$ct['conf']['gen']['default_theme'] = 'dark'; // 'dark' or 'light'


// Default BITCOIN market currencies (20+ currencies supported)
// (set for default Bitcoin market, and charts / price alert primary-currency-equivalent value determination [example: usd value of btc/ltc market, etc])
// aud / brl / cad / chf / dai / eth / eur / gbp / hkd / inr / jpy
// krw / mxn / nis / rub / sgd / try / twd / usd / usdc / usdt / zar
// SEE THE $ct['conf']['assets']['BTC'] CONFIGURATION NEAR THE BOTTOM OF THIS CONFIG FILE, FOR THE PROPER (CORRESPONDING)
// MARKET PAIR VALUE NEEDED FOR YOUR CHOSEN 'BTC' EXCHANGE (set in $ct['conf']['gen']['bitcoin_primary_currency_exchange'] directly below)
$ct['conf']['gen']['bitcoin_primary_currency_pair'] = 'usd'; // PUT INSIDE SINGLE QUOTES ('selection')


// Default BITCOIN market exchanges (30+ bitcoin exchanges supported)
// (set for default Bitcoin market, and charts / price alert primary-currency-equivalent value determination [example: usd value of btc/ltc market, etc])
// binance / binance_us / bit2c / bitbns / bitfinex / bitflyer / bitmex
// bitpanda / bitso / bitstamp / btcmarkets / btcturk / buyucoin / cex
// coinbase / coindcx / coingecko_sgd / coingecko_twd / coingecko_usd
// coinspot / gemini / hitbtc / huobi / korbit / kraken / kucoin / liquid
// loopring_amm / luno / okcoin / okex / southxchange / unocoin / upbit / wazirx
// SEE THE $ct['conf']['assets']['BTC'] CONFIGURATION NEAR THE BOTTOM OF THIS CONFIG FILE, FOR THE PROPER (CORRESPONDING)
// 'BTC' EXCHANGE VALUE NEEDED FOR YOUR CHOSEN MARKET PAIR (set in $ct['conf']['gen']['bitcoin_primary_currency_pair'] directly above)
$ct['conf']['gen']['bitcoin_primary_currency_exchange'] = 'kraken';  // PUT INSIDE SINGLE QUOTES ('selection')


// Default marketcap data source: 'coingecko', or 'coinmarketcap'
// (COINMARKETCAP REQUIRES A #FREE# API KEY, SEE $ct['conf']['ext_apis']['coinmarketcap_api_key'] BELOW in the APIs section)
$ct['conf']['gen']['primary_marketcap_site'] = 'coingecko'; 


// Maximum decimal places for *CURRENCY* VALUES, of fiat currencies worth under 1.00 in unit value [usd/gbp/eur/jpy/brl/rub/etc]
// Sets the minimum-allowed CURRENCY value, adjust with care!
// For prettier / less-cluttered interface. IF YOU ADJUST $ct['conf']['gen']['bitcoin_primary_currency_pair'] ABOVE, 
// YOU MAY NEED TO ADJUST THIS ACCORDINGLY FOR !PRETTY / FUNCTIONAL! CHARTS / ALERTS FOR YOUR CHOSEN PRIMARY CURRENCY
// ALSO KEEP THIS NUMBER AS LOW AS IS FEASIBLE, TO SAVE ON CHART DATA STORAGE SPACE / MAINTAIN QUICK CHART LOAD TIMES
$ct['conf']['gen']['currency_decimals_max'] = 8; // Whole numbers only (represents number of decimals maximum to use...default = 8)


// Maximum decimal places for *CRYPTO* VALUES ACROSS THE ENTIRE APP (*INCLUDING UNDER-THE-HOOD CALCULATIONS*)
// Sets the minimum-allowed CRYPTO value, adjust with care!
// LOW VALUE ALTERNATE COINS / CURRENCIES NEED THIS SET REALLY HIGH TO BE INCLUDED IN THE ASSETS LIST (TO NOT HAVE A ZERO VALUE),
// *ESPECIALLY* SINCE WE USE BITCOIN AS OUR BASE CURRENCY *CONVERTER* (DUE TO IT'S RELIABLY HIGH LIQUIDITY ACROSS THE PLANET)
// !!!IF YOU CHANGE THIS, THE 'WATCH ONLY' FLAG ON THE 'UPDATE' PAGE *WILL ALSO CHANGE* (CHANGING WHAT IS FLAGGED 'WATCH ONLY')!!!
$ct['conf']['gen']['crypto_decimals_max'] = 13; // Whole numbers only (represents number of decimals maximum to use...default = 13)


// PRICE PERCENTAGE to round off INTERFACE-DISPLAYED price IN DECIMALS (DYNAMIC / RELATIVE to price amount)
// (FINE-GRAINED CONTROL OVER INTERFACE PRICE ROUNDING #AMOUNT OF DECIMALS SHOWN#)
// (interface examples: one = 1000, tenth = 1000, hundredth = 1000.9, thousandth = 1000.09)
// (interface examples: one = 100, tenth = 100.9, hundredth = 100.09, thousandth = 100.009)
// (interface examples: one = 10.9, tenth = 10.09, hundredth = 10.009, thousandth = 10.0009)
// #FIAT# CURRENCY VALUES UNDER 100 #ARE ALWAYS FORCED TO 2 DECIMALS MINUMUM#
// #FIAT# CURRENCY VALUES UNDER 1 #ARE ALWAYS FORCED TO 'currency_decimals_max' DECIMALS MAXIMUM#
// THIS SETTING ONLY AFFECTS INTERFACE / COMMS PRICE DISPLAY ROUNDING, IT DOES #NOT# AFFECT BACKGROUND CALCULATIONS
$ct['conf']['gen']['price_rounding_percent'] = 'thousandth'; // (OF A PERCENT) 'one', 'tenth', 'hundredth', 'thousandth'
////
// FORCE a FIXED MINIMUM amount of decimals on interface price, CALCULATED OFF ABOVE price_rounding_percent SETTING
// (ALWAYS SAME AMOUNT OF DECIMALS, #EVEN IF IT INCLUDES TRAILING ZEROS#) 
$ct['conf']['gen']['price_rounding_fixed_decimals'] = 'on'; // 'off', 'on'


////////////////////////////////////////
// !END! GENERAL CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! COMMUNICATIONS CONFIGURATION
////////////////////////////////////////


// Allow or disallow sending out ANY communications (email / text / telegram / alexa / etc), so no comms are sent to you unless allowed here
// (PAUSES ALL COMMS IF SET TO 'off')
$ct['conf']['comms']['allow_comms'] = 'on'; // 'on' / 'off' (Default = 'on' [comms are sent out normally])


// Enable / disable upgrade checks / alerts (DEFAULT: ALL USER-ACTIVATED COMM CHANNELS)
// (Checks latest release version via github.com API endpoint value "tag_name" 
// @ https://api.github.com/repos/taoteh1221/Open_Crypto_Tracker/releases/latest)
// Choosing 'all' will send to all properly-configured communication channels (and automatically skip any not properly setup)
$ct['conf']['comms']['upgrade_alert_channels'] = 'all'; // 'off' (disabled) / 'all' / 'ui' (web interface) / 'email' / 'text' / 'notifyme' / 'telegram'
////
// Wait X days between upgrade reminders
$ct['conf']['comms']['upgrade_alert_reminder'] = 7; // (only used if upgrade check is enabled above)


// Use SMTP authentication TO SEND EMAIL, if your IP has no reverse lookup that matches the email domain name (on your home network etc)
// #REQUIRED WHEN INSTALLED ON A HOME NETWORK#, OR ALL YOUR EMAIL ALERTS WILL BE BLACKHOLED / SEEN AS SPAM EMAIL BY EMAIL SERVERS!
// If SMTP credentials / configuration is filled in, BUT not setup properly, APP EMAILING WILL FAIL!
// !!USE A THROWAWAY ACCOUNT ONLY!! If this app server is hacked, HACKER WOULD THEN HAVE ACCESS YOUR EMAIL LOGIN FROM THIS FILE!!
// CAN BE BLANK (PHP's built-in mail function will be automatically used to send email instead)
$ct['conf']['comms']['smtp_login'] = ''; // This format MUST be used: 'username||password'
////
// SMTP Server examples (protocol auto-detected / used based off port number): 
// 'example.com:25' (non-encrypted), 'example.com:465' (ssl-encrypted), 'example.com:587' (tls-encrypted)
$ct['conf']['comms']['smtp_server'] = ''; // CAN BE BLANK. This format MUST be used: 'domain_or_ip:port_number' 


// IF SMTP EMAIL SENDING --NOT-- USED, FROM email should be a REAL address on the server domain, or risk having email sent to junk folder
// IF SMTP EMAIL SENDING --IS-- USED, FROM EMAIL MUST MATCH EMAIL ADDRESS associated with SMTP login (SMTP Email configuration is above this setting)
$ct['conf']['comms']['from_email'] = ''; // #SHOULD BE SET# to avoid email going to spam / junk
////
$ct['conf']['comms']['to_email'] = ''; // #MUST BE SET# for price alerts and other email features


// For alert texts to mobile phone numbers. 
// Attempts to email the text if a SUPPORTED MOBILE TEXTING NETWORK name is set, AND no textbelt / textlocal config is setup.
// SMTP-authenticated email sending MAY GET THROUGH TEXTING SERVICE CONTENT FILTERS #BETTER# THAN USING PHP'S BUILT-IN EMAILING FUNCTION
// SEE FURTHER DOWN IN THIS CONFIG FILE, FOR A LIST OF SUPPORTED MOBILE TEXTING NETWORK PROVIDER NAMES 
// IN THE EMAIL-TO-MOBILE-TEXT CONFIG SECTION (the "network name keys" in the $ct['conf']['mobile_network']['text_gateways'] variables array)
// CAN BE BLANK. Country code format MAY NEED TO BE USED (depending on your mobile network)
// skip_network_name SHOULD BE USED IF USING a texting (SMS) SERVICE (IN EXTERNAL APIS SECTION)
// 'phone_number||network_name_key' (examples: '12223334444||virgin_us' / '12223334444||skip_network_name')
$ct['conf']['comms']['to_mobile_text'] = '';


// Email logs every X days. 
// 0 to disable. Email to / from !MUST BE SET IN COMMS CHANNELS SETUP!, MAY NOT SEND IN TIMELY FASHION WITHOUT A CRON JOB / SCHEDULED TASK
$ct['conf']['comms']['logs_email'] = 3; // (default = 3)


////////////////////////////////////////
// !END! COMMUNICATIONS CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! EXTERNAL API SETTINGS CONFIGURATION
////////////////////////////////////////
							
							
// SECONDS to wait for response from REMOTE API endpoints (exchange data, etc). 
// Set too low you won't get ALL data (partial or zero bytes), set too high the interface can take a long time loading if an API server hangs up
// RECOMMENDED MINIMUM OF 60 FOR INSTALLS BEHIND #LOW BANDWIDTH# NETWORKS 
// (which may need an even higher timeout above 60 if data still isn't FULLY received from all APIs)
// YOU WILL GET ALERTS IN THE ERROR LOGS IF YOU NEED TO ADJUST THIS
$ct['conf']['ext_apis']['remote_api_timeout'] = 30; // (default = 30)


// For notifyme / alexa notifications (sending Alexa devices notifications for free). 
// CAN BE BLANK. Setup: https://www.thomptronics.com/about/notify-me
// (NOTE: THIS APP'S BUILT-IN QUEUE SYSTEM THROTTLES / SENDS OUT ONLY 5 ALERTS EVERY 5 MINUTES MAXIMUM FOR NOTIFYME ALERTS,
// TO STAY WITHIN NOTIFYME API MESSAGE LIMITS, SO YOU WILL ALWAYS #STILL GET ALL YOUR QUEUED NOTIFYME ALERTS#, JUST SLIGHTLY DELAYED)
$ct['conf']['ext_apis']['notifyme_access_code'] = '';


// Sending alerts to your own telegram bot chatroom. 
// (USEFUL IF YOU HAVE ISSUES SETTING UP MOBILE TEXT ALERTS, INCLUDING EMOJI / UNICODE CHARACTER ENCODING)
// Setup: https://core.telegram.org/bots/features#creating-a-new-bot , OR JUST SEARCH / VISIT "BotFather" in the telegram app
// YOU MUST SETUP A TELEGRAM USERNAME #FIRST / BEFORE SETTING UP THE BOT#, IF YOU HAVEN'T ALREADY (IN THE TELEGRAM APP SETTINGS)
// SET UP YOUR BOT WITH "BotFather", AND SAVE YOUR BOT NAME / USERNAME / ACCESS TOKEN / BOT'S CHATROOM IN TELEGRAM APP
// VISIT THE BOT'S CHATROOM IN TELEGRAM APP, #SEND THE MESSAGE "/start" TO THIS CHATROOM# (THIS WILL CREATE USER CHAT DATA THIS APP NEEDS)
// THE USER CHAT DATA #IS REQUIRED# FOR THIS APP TO DETERMINE / SECURELY SAVE YOUR TELEGRAM USER'S CHAT ID WITH THE BOT YOU CREATED
// #DO NOT DELETE THE BOT CHATROOM IN THE TELEGRAM APP, OR YOU WILL STOP RECEIVING MESSAGES FROM THE BOT!#
$ct['conf']['ext_apis']['telegram_your_username'] = ''; // Your telegram username (REQUIRED, setup in telegram app settings)
////
$ct['conf']['ext_apis']['telegram_bot_username'] = '';  // Your bot's username
////
$ct['conf']['ext_apis']['telegram_bot_name'] = ''; // Your bot's human-readable name (example: 'My Alerts Bot')
////
$ct['conf']['ext_apis']['telegram_bot_token'] = '';  // Your bot's access token


// Do NOT use MORE THAN ONE texting (SMS) service below. Only fill in settings for one, or it will DISABLE THEM ALL.
// LEAVE ALL BLANK to use a mobile text gateway set ABOVE


// CAN BE BLANK. For asset price alert twilio notifications. Setup: https://twilio.com/
// YOU MUST SET $ct['conf']['comms']['to_mobile_text'] (IN THE COMMS SECTION) IN THE SERVICE PROVIDER AREA TO: skip_network_name
////
// Twilio acount phone number (Format: '12223334444' [no plus symbol])
$ct['conf']['ext_apis']['twilio_number'] = '';
////
// Twilio account SID
$ct['conf']['ext_apis']['twilio_sid'] = '';
////
// Twilio account auth token
$ct['conf']['ext_apis']['twilio_token'] = '';


// CAN BE BLANK. For asset price alert textbelt notifications. Setup: https://textbelt.com/
// YOU MUST SET $ct['conf']['comms']['to_mobile_text'] ABOVE IN THE SERVICE PROVIDER AREA TO: skip_network_name
// CONTACT support@textbelt.com IF YOUR MESSAGES DON'T GO THROUGH, THEY ARE USUALLY *VERY* RESPONSIVE
$ct['conf']['ext_apis']['textbelt_api_key'] = '';


// CAN BE BLANK. For asset price alert textlocal notifications. Setup: https://www.textlocal.com/integrations/api/
// DOES NOT SEEM TO WORK OUTSIDE THE UNITED KINGDOM! (account dashboard says it was sent, but it's NEVER recieved)
// YOU MUST SET $ct['conf']['comms']['to_mobile_text'] ABOVE IN THE SERVICE PROVIDER AREA TO: skip_network_name
////
// Textlocal human-readable sender name (eg: 'J. Smith'), REQUIRED IF USING TEXTLOCAL TO SEND TEXTS!
// THIS SHOULD MATCH THE SENDER NAME YOU ALREADY SETUP IN YOUR TEXTLOCAL ACCOUNT
$ct['conf']['ext_apis']['textlocal_sender'] = '';
////
// API Key
$ct['conf']['ext_apis']['textlocal_api_key'] = '';


// API key for Google Fonts API (required unfortunately, but a FREE level is available):
// https://support.google.com/googleapi/answer/6158862?hl=en&ref_topic=7013279
// (USED TO GET A LIST OF ALL GOOGLE FONTS, TO CHOOSE FROM IN THE ADMIN INTERFACE)
$ct['conf']['ext_apis']['google_fonts_api_key'] = '';


// HOURS to cache google font list (for admin interface). Set high, we rarely need it updated
$ct['conf']['ext_apis']['google_fonts_cache_time'] = 24;  // (default = 24)


// IF you are using on-chain data from the Solana blockchain, you can choose which RPC server you want to use.
// DEFAULT = 'https://api.mainnet-beta.solana.com'
$ct['conf']['ext_apis']['solana_rpc_server'] = 'https://api.mainnet-beta.solana.com';


// Maximum number of BATCHED coingecko marketcap data results to fetch, per API call (during multiple / paginated calls) 
// (coingecko #ABSOLUTELY HATES# DATA CENTER IPS [DEDICATED / VPS SERVERS], BUT GOES EASY ON RESIDENTIAL IPS)
$ct['conf']['ext_apis']['coingecko_api_batched_maximum'] = 100; // (default = 100), ADJUST WITH CARE!!!


// API key for coinmarketcap.com Pro API (required unfortunately, but a FREE level is available): https://coinmarketcap.com/api
$ct['conf']['ext_apis']['coinmarketcap_api_key'] = '';


// API key for etherscan.io (required unfortunately, but a FREE level is available): https://etherscan.io/apis
$ct['conf']['ext_apis']['etherscan_api_key'] = '';


// API key for Alpha Vantage (global stock APIs as well as foreign exchange rates (forex) and cryptocurrency data feeds)
// (required unfortunately, but a FREE level is available [paid premium also available]): https://www.alphavantage.co/support/#api-key
$ct['conf']['ext_apis']['alphavantage_api_key'] = '';
////
// The below setting will automatically limit your API requests to NEVER go over your Alpha Vantage API requests limit
// (WE AUTO-ADJUST THE *DAILY* LIMIT, BASED ON YOUR PER-MINUTE SETTING BELOW [*ALL* PREMIUM PLANS ARE UNLIMITED *DAILY* REQUESTS])
// The requests-per-*MINUTE* limit on your Alpha Vantage API key (varies depending on your free / paid member level)
// Default = 5 [FOR FREE SERVICE], and 30,75,150,300,600,1200 [FOR PREMIUM PLANS]:
// https://www.alphavantage.co/premium/
$ct['conf']['ext_apis']['alphavantage_per_minute_limit'] = 5;
////
// The DEFAULT (FREE PLAN) requests-per-DAY limit on the Alpha Vantage API key
// WE AUTO-ADJUST TO UNLIMITED FOR PREMIUM PLANS:
// https://www.alphavantage.co/premium/
// (they have been known to change this amount occassionally for the free plan, so we have this setting)
$ct['conf']['ext_apis']['alphavantage_free_plan_daily_limit'] = 25;


////////////////////////////////////////
// !END! EXTERNAL API SETTINGS CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! INTERNAL API SETTINGS CONFIGURATION
////////////////////////////////////////


// Local / internal REST API rate limit (maximum of once every X SECONDS, per ip address) for accepting remote requests
// Can be 0 to disable rate limiting (unlimited)
$ct['conf']['int_api']['int_api_rate_limit'] = 1; // (default = 1)
////
// Local / internal REST API market limit (maximum number of MARKETS requested per call)
$ct['conf']['int_api']['int_api_markets_limit'] = 35; // (default = 35)
////
// Local / internal REST API cache time (MINUTES that previous requests are cached for)
$ct['conf']['int_api']['int_api_cache_time'] = 1; // (default = 1)


////////////////////////////////////////
// !END! INTERNAL API SETTINGS CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! PROXIES CONFIGURATION
////////////////////////////////////////


// Allow or disallow using proxies for API data requests
$ct['conf']['proxy']['allow_proxies'] = 'off'; // 'on' / 'off' (Default = 'off')


// If using proxies and login is required
// Adding a user / pass here will automatically send login details for proxy connections
// CAN BE BLANK. IF using ip address authentication instead, MUST BE LEFT BLANK
$ct['conf']['proxy']['proxy_login'] = ''; // Use format: 'username||password'


// Alerts for failed proxy data connections (#ONLY USED# IF proxies are enabled further down in PROXY CONFIGURATION). 
// Choosing 'all' will send to all properly-configured communication channels (and automatically skip any not properly setup)
$ct['conf']['proxy']['proxy_alert_channels'] = 'email'; // 'off' (disabled) / 'all' / 'email' / 'text' / 'notifyme' / 'telegram'


// Re-allow same proxy alert(s) after X HOURS (per ip/port pair, can be 0)
$ct['conf']['proxy']['proxy_alert_frequency_maximum'] = 1; 


// Which runtime mode should allow proxy alerts? Options: 'cron', 'ui', 'all' 
$ct['conf']['proxy']['proxy_alert_runtime'] = 'cron'; // (default = 'cron')


// Include or ignore proxy alerts if proxy checkup went OK? (after flagged, started working again when checked)
$ct['conf']['proxy']['proxy_alert_checkup_ok'] = 'include'; // 'include' / 'ignore' 
    
     
// API servers that do NOT like the user-setup proxy servers
// (this app will SKIP USING PROXY SERVERS for these domains)
$ct['conf']['proxy']['anti_proxy_servers'] = array(
                                                   //'domain.com',
                                                   'binance.com',
                                                  );


// If using proxies, add the ip address / port number here for each one, like examples below (without the double slashes in front enables the code)
// CAN BE BLANK. Adding proxies here will automatically choose one randomly for each API request
// BEST PROXY SERVICE I'VE TESTED ("free forever" trial): https://proxyscrape.com/premium-free-trial
$ct['conf']['proxy']['proxy_list'] = array(
                    				   // 'ipaddress1:portnumber1',
                    				   // 'ipaddress2:portnumber2',
                    				  );


////////////////////////////////////////
// !END! PROXIES CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! SECURITY CONFIGURATION
////////////////////////////////////////


// Interface login protection (htaccess user/password required to view this portfolio app's web interface)
// Username MUST BE at least 4 characters, beginning with ONLY LOWERCASE letters (may contain numbers AFTER first letter), NO SPACES
// Password MUST BE EXACTLY 8 characters, AND contain one number, one UPPER AND LOWER CASE letter, and one symbol, NO SPACES
// (ENABLES / UPDATES automatically, when a valid username / password are filled in or updated here)
// (DISABLES automatically, when username / password are blank '' OR invalid) 
// (!ONLY #UPDATES OR DISABLES# AUTOMATICALLY #AFTER# LOGGING IN ONCE WITH YOUR #OLD LOGIN# [or if a cron job / scheduled task runs with the new config]!)
// DOES #NOT# WORK ON #LINUX DESKTOP EDITION# (ONLY WORKS ON #SERVER EDITION AND WINDOWS DESKTOP EDITION#)
// #IF THIS SETTING GIVES YOU ISSUES# ON YOUR SYSTEM, BLANK IT OUT TO '', AND DELETE '.htaccess' IN THE MAIN DIRECTORY OF 
// THIS APP (TO RESTORE PAGE ACCESS), AND PLEASE REPORT IT HERE: https://github.com/taoteh1221/Open_Crypto_Tracker/issues
$ct['conf']['sec']['interface_login'] = ''; // Leave blank to disable requiring an interface login. This format MUST be used: 'username||password'


// Password protection / encryption security for backup archives (REQUIRED for app config backup archives, #NOT# USED FOR CHART BACKUPS)
$ct['conf']['sec']['backup_archive_password'] = ''; // LEAVE BLANK TO DISABLE


// Enable / disable admin login alerts (DEFAULT: ALL USER-ACTIVATED COMM CHANNELS)
// Choosing 'all' will send to all properly-configured communication channels, (and automatically skip any not properly setup)
$ct['conf']['sec']['login_alert_channels'] = 'all'; // 'off' (disabled) / 'all' / 'email' / 'text' / 'notifyme' / 'telegram'
							
							
// HOURS until admin login cookie expires (requiring you to login again)
// The lower number the better for higher security, epecially if the app server temporary session data 
// doesn't auto-clear often (that also logs you off automatically, REGARDLESS of this setting's value)
$ct['conf']['sec']['admin_cookie_expires'] = 6; // (default = 6, MAX ALLOWED IS 6)


// 'on' verifies ALL SMTP server certificates for secure SMTP connections, 'off' verifies NOTHING 
// Set to 'off', if the SMTP server has an invalid certificate setup (which stops email sending, but you still want to send email through that server)
$ct['conf']['sec']['smtp_strict_ssl'] = 'off'; // (DEFAULT IS 'off', TO ASSURE SMTP EMAIL SENDING STILL WORKS THROUGH MISCONFIGURED SMTP SERVERS)


// 'on' verifies ALL REMOTE API server certificates for secure API connections, 'off' verifies NOTHING 
// Set to 'off', if some exchange's API servers have invalid certificates (which stops price data retrieval...but you still want to get price data from them)
$ct['conf']['sec']['remote_api_strict_ssl'] = 'off'; // (default = 'off')


// Set CORS 'Access-Control-Allow-Origin' (controls what web domains can load this app's admin / user pages, AJAX scripts, etc)
// Set to 'any' if this app server's domain can vary / redirect (eg: some INITIAL visits are 'www.mywebsite.com', AND some are 'mywebsite.com')
// Set to 'strict' if this app server's domain CANNOT VARY / REDIRECT (it's always 'mywebsite.com', EVERY VISIT #WITHOUT EXCEPTIONS#)
// 'strict' mode blocks all CSRF / XSS attacks on resources using this setting, ALTHOUGH NOT REALLY NEEDED AS SERVER EDITIONS USE STRICT / SECURE COOKIES
// #CHANGE WITH CAUTION#, AS 'strict' #CAN BREAK CHARTS / LOGS / NEWS FEEDS / ADMIN SECTIONS / ETC FROM LOADING# ON SOME SETUPS!
$ct['conf']['sec']['access_control_origin'] = 'any'; // 'any' / 'strict' (default = 'any')
		

// CONTRAST of CAPTCHA IMAGE text against background (on login pages)
// 0 for neutral contrast, positive for more contrast, negative for less contrast (MAXIMUM OF +-35)
$ct['conf']['sec']['captcha_text_contrast'] = -8; // example: -5 or 5 (default = -8)
////
// MAX OFF-ANGLE DEGREES (tilted backward / forward) of CAPTCHA IMAGE text characters (MAXIMUM OF 35)
$ct['conf']['sec']['captcha_text_angle'] = 35; // (default = 35)


////////////////////////////////////////
// !END! SECURITY CONFIGURATION
////////////////////////////////////////


////////////////////////////////////////
// !START! CURRENCY SUPPORT
////////////////////////////////////////


// HIVE INTEREST CALCULATOR SETTINGS
// Weeks to power down all HIVE Power holdings
$ct['conf']['currency']['hive_powerdown_time'] = 13; 
////
// HIVE Power yearly interest rate AS OF 11/29/2023 (0.9%, decreasing every year by roughly 0.075%, until it hits a minimum of 0.075% and stays there)
// (DO NOT INCLUDE PERCENT SIGN), see above for manual yearly adjustment
$ct['conf']['currency']['hivepower_yearly_interest'] = 0.9; // (Default = 0.9 as of 11/29/23)


// Static values in USD for token presales, like during crowdsale / VC funding periods etc (before exchange listings)
// RAW NUMBERS ONLY (NO THOUSANDTHS FORMATTING)
$ct['conf']['currency']['token_presales_usd'] = array(
                                                      // 'TOKENNAME = 1.23',
                                                      'ETHEREUM = 0.3',
                                                      'SOLANA = 0.22',
                                                      'DECENTRALAND = 0.025',
                                                   );
                                                   


// Auto-activate support for PRIMARY CURRENCY MARKETS (to use as your preferred local currency in the app)
// EACH CURRENCY LISTED HERE !MUST HAVE! AN EXISTING BITCOIN ASSET MARKET (within 'pair') in 
// Bitcoin's $ct['conf']['assets'] listing (further down in this config file) TO PROPERLY AUTO-ACTIVATE
// #CAN# BE A CRYPTO / HAVE A DUPLICATE IN $ct['conf']['currency']['crypto_pair'], 
// !AS LONG AS THERE IS A PAIR CONFIGURED WITHIN THE BITCOIN ASSET SETUP!
$ct['conf']['currency']['bitcoin_currency_markets'] = array(
                              						//'lowercase_btc_mrkt_or_stablecoin_pair = CURRENCY_SYMBOL',
                              						'aed = د.إ',
                              						'ars = ARS$',
                              						'aud = A$',
                              						'bam = KM ',
                              						'bdt = ৳',
                              						'bob = Bs ',
                              						'brl = R$',
                              						'bwp = P ',
                              						'byn = Br ',
                              						'cad = C$',
                              						'chf = CHf ',
                              						'clp = CLP$',
                              						'cny = C¥',
                              						'cop = Col$',
                              						'crc = ₡',
                              						'czk = Kč ',
                              						'dai = ◈ ',
                              						'dkk = Kr. ',
                              						'dop = RD$',
                              						'egp = ج.م',
                              						'eth = Ξ ',
                              						'eur = €',
                              						'gbp = £',
                              						'gel = ლ',
                              						'ghs = GH₵',
                              						'gtq = Q ',
                              						'hkd = HK$',
                              						'huf = Ft ',
                              						'idr = Rp ',
                              						'ils = ₪',
                              						'inr = ₹',
                              						'irr = ﷼',
                              						'jmd = JA$',
                              						'jod = د.ا',
                              						'jpy = J¥',
                              						'kes = Ksh ',
                              						'krw = ₩',
                              						'kwd = د.ك',
                              						'kzt = ₸',
                              						'lkr = රු, ரூ',
                              						'mad = د.م.',
                              						'mur = ₨ ',
                              						'mwk = MK ',
                              						'mxn = Mex$',
                              						'myr = RM ',
                              						'ngn = ₦',
                              						'nis = ₪',
                              						'nok = kr ',
                              						'nzd = NZ$',
                              						'pab = B/. ',
                              						'pen = S/ ',
                              						'php = ₱',
                              						'pkr = ₨ ',
                              						'pln = zł ',
                              						'pyg = ₲',
                              						'qar = ر.ق',
                              						'ron = lei ',
                              						'rsd = din ',
                              						'rub = ₽',
                              						'rwf = R₣ ',
                              						'sar = ﷼',
                              						'sek = kr ',
                              						'sgd = S$',
                              						'thb = ฿',
                              						'try = ₺',
                              						'tusd = Ⓢ ',
                              						'twd = NT$',
                              						'tzs = TSh ',
                              						'uah = ₴',
                              						'ugx = USh ',
                              						'usd = $',
                              						'usdc = Ⓢ ',
                              						'usdt = ₮ ',
                              						'uyu = $U ',
                              						'vnd = ₫',
                              						'ves = Bs ',
                              						'xaf = FCFA ',
                              						'xof = CFA ',
                              						'zar = R ',
                              						'zmw = ZK ',
                         						);



// Preferred BITCOIN market(s) for getting a certain currency's value
// (when other exchanges for this currency have poor api / volume / price discovery / etc)
// EACH CURRENCY LISTED HERE MUST EXIST IN $ct['conf']['currency']['bitcoin_currency_markets'] ABOVE
// #USE LIBERALLY#, AS YOU WANT THE BEST PRICE DISCOVERY FOR THIS CURRENCY'S VALUE
$ct['conf']['currency']['bitcoin_preferred_currency_markets'] = array(
                                   						     //'lowercase_btc_mrkt_or_stablecoin_pair = PREFERRED_MRKT',
                                   							'aud = kraken',  // WAY BETTER api than ALL alternatives
                                   							'chf = kraken',  // WAY MORE reputable than ALL alternatives
                                   							'dai = kraken',  // WAY MORE reputable than ALL alternatives
                                   							'eur = kraken',  // WAY BETTER api than ALL alternatives
                                   							'gbp = kraken',  // WAY BETTER api than ALL alternatives
                                   							'jpy = kraken',  // WAY MORE reputable than ALL alternatives
                                   							'inr = wazirx',  // One of the biggest exchanges in India (should be good price discovery)
                                   							'rub = binance',  // WAY MORE volume / price discovery than ALL alternatives
                                   							'usd = kraken',  // WAY BETTER api than ALL alternatives
                                   					       );

							

// Auto-activate support for ALTCOIN PAIRED MARKETS (like COIN/sol or COIN/eth, etc...markets where the base pair is an altcoin)
// EACH CRYPTO COIN LISTED HERE !MUST HAVE! AN EXISTING 'btc' MARKET (within 'pair') in it's 
// $ct['conf']['assets'] listing (further down in this config file) TO PROPERLY AUTO-ACTIVATE
// THIS ALSO ADDS THESE ASSETS AS OPTIONS IN THE "Show Crypto Value Of ENTIRE Portfolio In" SETTING, ON THE SETTINGS PAGE,
// AND IN THE "Show Secondary Trade / Holdings Value" SETTING, ON THE SETTINGS PAGE TOO
// !!!!!TRY TO #NOT# ADD STABLECOINS HERE!!!!!, FIRST TRY $ct['conf']['currency']['bitcoin_currency_markets'] INSTEAD (TO AUTO-CLIP UN-NEEDED DECIMAL POINTS) 
$ct['conf']['currency']['crypto_pair'] = array(
                                             // !!!!!BTC IS ALREADY ADDED *AUTOMATICALLY*, NO NEED TO ADD IT HERE!!!!!
                                             ////
               						//'lowercase_altcoin_ticker = UNICODE_SYMBOL', // Add whitespace after the symbol, if you prefer that
               						////
               						// Bluechip native tokens...
               						'eth = Ξ ',
               						'sol = ◎ ',
               						// Bluechip ERC-20 tokens on Ethereum / SPL tokens on Solana, etc...
               						'uni = 🦄 ',
               						'mkr = 𐌼 ',
               						'jup = Ɉ ',
     							    );



// Preferred ALTCOIN PAIRED MARKETS market(s) for getting a certain crypto's value
// EACH ALTCOIN LISTED HERE MUST EXIST IN $ct['conf']['currency']['crypto_pair'] ABOVE,
// AND !MUST HAVE! AN EXISTING 'btc' MARKET (within 'pair') in it's 
// $ct['conf']['assets'] listing (further down in this config file),
// AND #THE EXCHANGE NAME MUST BE IN THAT 'btc' LIST#
// #USE LIBERALLY#, AS YOU WANT THE BEST PRICE DISCOVERY FOR THIS CRYPTO'S VALUE
$ct['conf']['currency']['crypto_pair_preferred_markets'] = array(
                              						     //'lowercase_btc_mrkt_or_stablecoin_pair = PREFERRED_MRKT',
                              							'eth = binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
                              							'sol = binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
                              							'uni = binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
                              							'mkr = binance',  // WAY MORE volume , WAY BETTER price discovery than ALL alternatives
                              							'jup = coingecko_btc',  // coingecko global average price IN BTC
               							           );
						


////////////////////////////////////////
// !END! CURRENCY SUPPORT
////////////////////////////////////////


////////////////////////////////////////
// !START! CHART AND PRICE ALERT MARKETS
////////////////////////////////////////


// CHARTS / PRICE ALERTS SETUP REQUIRES A CRON JOB OR SCHEDULED TASK RUNNING ON YOUR APP SERVER (see README.txt for setup information)


// Enable / disable price alerts (DEFAULT: ALL USER-ACTIVATED COMM CHANNELS)
// Choosing 'all' will send to all properly-configured communication channels, (and automatically skip any not properly setup)
$ct['conf']['charts_alerts']['price_alert_channels'] = 'all'; // 'off' (disabled) / 'all' / 'email' / 'text' / 'notifyme' / 'telegram'


// Price percent change to send alerts for (WITHOUT percent sign: 15.75 = 15.75%). Sends alerts when percent change reached (up or down)
$ct['conf']['charts_alerts']['price_alert_threshold'] = 8.75; // CAN BE 0 TO DISABLE PRICE ALERTS


// Re-allow SAME asset price alert(s) messages after X HOURS (per alert config)
// Set higher if sent to email junk folder / other comms APIs are blocking or throttling your alert messeges 
$ct['conf']['charts_alerts']['price_alert_frequency_maximum'] = 1; // Can be 0, to have no limits


// Block an asset price alert if price retrieved, BUT failed retrieving pair volume (not even a zero was retrieved, nothing)
// Good for BLOCKING QUESTIONABLE EXCHANGES from bugging you with price alerts, especially when used in combination with the minimum volume filter
// (EXCHANGES WITH NO TRADE VOLUME API ARE EXCLUDED [VOLUME IS SET TO ZERO BEFORE THIS FILTER RUNS])
$ct['conf']['charts_alerts']['price_alert_block_volume_error'] = 'off'; // 'on' / 'off' 


// Minimum 24 hour trade volume filter. Only allows sending price alerts if minimum 24 hour trade volume reached
// CAN BE 0 TO DISABLE MINIMUM VOLUME FILTERING, NO DECIMALS OR SEPARATORS, NUMBERS ONLY, WITHOUT the [primary currency] prefix symbol
// THIS FILTER WILL AUTO-DISABLE IF THERE IS ANY ERROR RETRIEVING VOLUME DATA ON A CERTAIN MARKET (NOT EVEN A ZERO IS RECEIVED ON VOLUME API)
// !!WARNING!!: IF AN EXCHANGE DOES #NOT# PROVIDE TRADE VOLUME API DATA FOR MARKETS, SETTING THIS ABOVE 0 WILL 
// #DISABLE ANY CONFIGURED PRICE ALERTS# FOR MARKETS ON THAT EXCHANGE, SO USE WITH CARE!
$ct['conf']['charts_alerts']['price_alert_minimum_volume'] = 0; // (default = 0)

																		
// Fixed time interval RESET of CACHED comparison asset prices every X DAYS (since last price reset / alert), compared with the current latest spot prices
// Helpful if you only want price alerts for a certain time window. Resets also send alerts that reset occurred, with summary of price changes since last reset
// Can be 0 to DISABLE fixed time interval resetting (IN WHICH CASE RESETS WILL ONLY OCCUR DYNAMICALLY when the next price alert is triggered / sent out)
$ct['conf']['charts_alerts']['price_alert_fixed_reset'] = 0; // (default = 0)


// Whale alert (adds "WHALE ALERT" to beginning of alexa / email / telegram alert text, and spouting whale emoji to email / text / telegram)
// Format: 'max_days_to_24hr_avg_over||min_price_percent_change_24hr_avg||min_vol_percent_increase_24hr_avg||min_vol_currency_increase_24hr_avg'
// ("min_price_percent_change_24hr_avg" should be the same value or higher as $ct['conf']['charts_alerts']['price_alert_threshold'] to work properly)
// Leave BLANK '' TO DISABLE. DECIMALS ARE SUPPORTED, USE NUMBERS ONLY (NO CURRENCY SYMBOLS / COMMAS, ETC)
$ct['conf']['charts_alerts']['whale_alert_thresholds'] = '1.25||9.75||12.75||25000'; // (default: '1.25||9.75||12.75||25000')	


// ENABLING CHARTS REQUIRES A CRON JOB / TASK SCHEDULER SETUP (see README.txt for setup information)
// Enables a charts tab / page, and caches real-time updated spot price / 24 hour trade volume chart data on your device's storage drive
// Disabling will disable EVERYTHING related to the price charts (price charts tab / page, and price chart data caching)
$ct['conf']['charts_alerts']['enable_price_charts'] = 'on'; // 'on' / 'off'


// Number of decimals for price chart CRYPTO 24 hour volumes (NOT USED FOR FIAT VOLUMES, 4 decimals example: 24 hr vol = 91.3874 BTC)
// KEEP THIS NUMBER AS LOW AS IS FEASIBLE, TO SAVE ON CHART DATA STORAGE SPACE / MAINTAIN QUICK CHART LOAD TIMES
$ct['conf']['charts_alerts']['chart_crypto_volume_decimals'] = 4;  // (default = 4) 


// Every X days backup chart data. 0 disables backups. Email to / from !MUST BE SET! (a download link is emailed to you of the chart data archive)
$ct['conf']['charts_alerts']['charts_backup_frequency'] = 1; 


// PRICE CHARTS colors (https://www.w3schools.com/colors/colors_picker.asp)
////
// Charts border color
$ct['conf']['charts_alerts']['charts_border'] = '#808080'; // (default: '#808080')
////
// Charts background color
$ct['conf']['charts_alerts']['charts_background'] = '#515050';   // (default: '#515050')
////
// Charts line color
$ct['conf']['charts_alerts']['charts_line'] = '#444444';   // (default: '#444444')
////
// Charts text color
$ct['conf']['charts_alerts']['charts_text'] = '#e8e8e8';   // (default: '#e8e8e8')
////
// Charts link color
$ct['conf']['charts_alerts']['charts_link'] = '#939393';   // (default: '#939393')
////
// Charts price (base) gradient color
$ct['conf']['charts_alerts']['charts_base_gradient'] = '#000000';  // (default: '#000000')
////
// Charts tooltip background color
$ct['conf']['charts_alerts']['charts_tooltip_background'] = '#bbbbbb'; // (default: '#bbbbbb')
////
// Charts tooltip text color
$ct['conf']['charts_alerts']['charts_tooltip_text'] = '#222222'; // (default: '#222222')


// Default settings for Asset Performance chart height / menu size (in the 'View More Stats' modal window, linked at bottom of Portfolio page)
// CHART HEIGHT MIN/MAX = 400/900 (increments of 100), MENU SIZE MIN/MAX (increments of 1) = 7/16
$ct['conf']['charts_alerts']['asset_performance_chart_defaults'] = '800||10'; // 'chart_height||menu_size' (default = '800||10')


// Default settings for Marketcap Comparison chart height / menu size (in the 'View More Stats' modal window, linked at bottom of Portfolio page)
// CHART HEIGHT MIN/MAX = 400/900 (increments of 100), MENU SIZE MIN/MAX (increments of 1) = 7/16
$ct['conf']['charts_alerts']['asset_marketcap_chart_defaults'] = '600||10'; // 'chart_height||menu_size' (default = '600||10')


// Highest allowed sensor value to scale vertical axis for, in the FIRST system information chart  (out of two)
// (higher sensor data is moved into the second chart, to keep ranges easily readable between both charts...only used IF CRON JOB IS SETUP)
$ct['conf']['charts_alerts']['system_stats_first_chart_maximum_scale'] = 3.25; // (default = 3.25) 
////
// Highest allowed sensor value to scale vertical axis for, in the SECOND system information chart (out of two)
// (to prevent anomaly results from scaling vertical axis TOO HIGH to read LESSER-VALUE sensor data...only used IF CRON JOB IS SETUP)
$ct['conf']['charts_alerts']['system_stats_second_chart_maximum_scale'] = 325; // (default = 325) 


// (Light) time period charts (load just as quickly for any time period, 7 day / 30 day / 365 day / etc)
// Structure of light charts #IN DAYS# (X days time period charts)
// Interface will auto-detect and display days IN THE INTERFACE as: 365 = 1Y, 180 = 6M, 30 = 1M, 7 = 1W, etc
// (JUST MAKE SURE YOU USE 365 / 30 / 7 *MULTIPLIED BY THE NUMBER OF* YEARS / MONTHS / WEEKS FOR PROPER AUTO-DETECTION/CONVERSION)
// (LOWER TIME PERIODS [UNDER 180 DAYS] #SHOULD BE KEPT SOMEWHAT MINIMAL#, TO REDUCE RUNTIME LOAD / DISK WRITES DURING CRON JOBS)
$ct['conf']['charts_alerts']['light_chart_day_intervals'] = '14,30,90,180,365,730,1460';
// (default = '14,30,90,180,365,730,1460')
////
// The maximum number of data points allowed in each light chart 
// (saves on disk storage / speeds up chart loading times SIGNIFICANTLY #WITH A NUMBER OF 875 OR LESS#)
$ct['conf']['charts_alerts']['light_chart_data_points_maximum'] = 875; // (default = 875), ADJUST WITH CARE!!!
////
// The space between light chart links inside the chart interface
$ct['conf']['charts_alerts']['light_chart_link_spacing'] = 50; // (default = 50), ADJUST WITH CARE!!!
////
// The GUESSED offset (width) for light chart link fonts inside the chart interface (NOT MONOSPACE, SO WE GUESS AN AVERAGE)
$ct['conf']['charts_alerts']['light_chart_link_font_offset'] = 4; // (default = 4), ADJUST WITH CARE!!!
////
// Maximum number of light chart NEW BUILDS allowed during background tasks, PER CPU CORE (only reset / new, NOT the 'all' chart REbuilds)
// (THIS IS MULTIPLIED BY THE NUMBER OF CPU CORES [if detected], avoids overloading low power devices / still builds fast on multi-core)
$ct['conf']['charts_alerts']['light_chart_first_build_hard_limit'] = 20; // (default = 20), ADJUST WITH CARE!!!
////
// Randomly rebuild the 'ALL' light chart between the minimum and maximum HOURS set here  (so they don't refresh all at once, for faster runtimes)
// LARGER AVERAGE TIME SPREAD IS EASIER ON LOW POWER DEVICES (TO ONLY UPDATE A FEW AT A TIME), FOR A MORE CONSISTANT CRON JOB RUNTIME SPEED!!
$ct['conf']['charts_alerts']['light_chart_all_rebuild_min_max'] = '4,8'; // 'min,max' (default = '4,8'), ADJUST WITH CARE!!!


// Markets you want charts or asset price change alerts for (see the COMMUNICATIONS section for price alerts threshold settings) 
// NOTE: This list must only contain assets / exchanges / trading pairs included in the primary portfolio assets list configuration further down in this config file
// TO ADD MULTIPLE CHARTS / ALERTS FOR SAME ASSET (FOR DIFFERENT EXCHANGES / TRADE PAIRS), FORMAT LIKE SO: symbol, symbol-1, symbol-2, symbol-3, etc.
// TO DISABLE A CHART / ALERT = none, TO ENABLE CHART AND ALERT = both, TO ENABLE CHART ONLY = chart, TO ENABLE ALERT ONLY = alert
$ct['conf']['charts_alerts']['tracked_markets'] = array(


					// TICKER
     				// 'ticker||exchange||trade_pair||alert',
     				// 'ticker-2||exchange2||trade_pair2||chart',
     				// 'ticker-3||exchange3||trade_pair3||both',
     				// 'ticker-4||exchange4||trade_pair4||none',
					
					
					// BTC
					'btc||coinbase||usd||chart',
					'btc-2||binance||usdt||chart',
					'btc-3||bitstamp||usd||none',
					'btc-4||kraken||usd||chart',
					'btc-5||gemini||usd||none',
					'btc-6||bitfinex||usd||none',
					'btc-8||kraken||eur||chart',
					'btc-9||coinbase||eur||none',
					'btc-10||coinbase||gbp||none',
					'btc-11||kraken||cad||none',
					'btc-12||btcmarkets||aud||none',
					'btc-13||bitbns||inr||none',
					'btc-16||bitflyer||jpy||chart',
					'btc-17||liquid||hkd||none',
					'btc-19||upbit||krw||none',
					'btc-20||bitso||mxn||none',
					'btc-24||btcturk||try||none',
					'btc-25||coingecko_twd||twd||none',
					'btc-26||luno||zar||none',
					'btc-27||kraken||dai||none',
					'btc-28||bitmex||usd||both',
					
					
					// TBTC
					'tbtc||kraken||usd||both',
					
					
					// FBTCSTOCK (Fidelity Bitcoin ETF)
					'fbtcstock||alphavantage_stock||usd||both',
					
					
					// ETH
					'eth||coinbase||btc||none',
					'eth-3||kraken||btc||chart',
					'eth-4||binance||usdt||chart',
					'eth-5||binance_us||btc||none',
					'eth-6||coinbase||usd||chart',
					'eth-7||kraken||usd||none',
					'eth-8||bitstamp||usd||none',
					'eth-9||gemini||usd||none',
					'eth-10||coinbase||gbp||none',
					'eth-11||coinbase||eur||chart',
					'eth-13||bitbns||inr||none',
					'eth-14||bitmex||usd||both',
					
					
					// SOL
					'sol||binance||btc||none',
					'sol-2||coinbase||usd||both',
					'sol-3||coinbase||btc||chart',
					'sol-4||binance||eth||chart',
					
					
					// MKR
					'mkr||okex||btc||none',
					'mkr-2||kucoin||btc||none',
					'mkr-3||coinbase||btc||both',
					
					
					// DAI
					'dai||coinbase||usd||both',
					'dai-2||kraken||usd||none',
					
					
					// USDC
					'usdc||kraken||usd||both',
					
					
					// UNI
					'uni||binance||btc||both',
					'uni-3||binance||usdt||none',
					'uni-4||coinbase||usd||none',
					
					
					// JUP
					'jup||coingecko_terminal||usd||chart',
					'jup-2||jupiter_ag||sol||chart',
					'jup-3||binance||usdt||chart',
					'jup-4||coingecko_btc||btc||both',
					
					
					// RNDR
					'rndr||kucoin||btc||both',
					'rndr-2||gateio||usdt||none',
					
					
					// IMX
					'imx||coinbase||usd||both',
					'imx-2||kraken||eur||chart',
					'imx-3||binance||btc||chart',
					
					
					// ZEUS
					'zeus||coingecko_terminal||usd||both',
					'zeus-2||jupiter_ag||sol||chart',
					
					
					// NEON
					'neon||jupiter_ag||sol||both',
					
					
					// SHDW
					'shdw||coingecko_terminal||usd||both',
					
					
					// MANA
					'mana-2||binance||btc||both',
					'mana-3||kucoin||btc||none',
					'mana-4||ethfinex||btc||none',
					'mana-5||binance||eth||none',
					
					
					// POLIS
					'polis||coingecko_btc||btc||chart',
					'polis-2||kraken||usd||both',
					
					
					// ATLAS
					'atlas||gateio||usdt||chart',
					'atlas-2||coingecko_btc||btc||chart',
					'atlas-3||kraken||usd||both',
					
					
					// HIVE
					'hive||binance||btc||both',
					
					
					// BONK
					'bonk||huobi||usdt||chart',
					'bonk-2||gateio||usdt||both',
					'bonk-3||coingecko_btc||btc||chart',
					'bonk-4||coingecko_eth||eth||chart',
					
					
					// WEN
					'wen||jupiter_ag||sol||both',
					
					
					// SHOPSTOCK (Shopify stock)
					'shopstock||alphavantage_stock||cad||both',
					
					
					// DTGSTOCK (Daimler Truck Holding stock)
					'dtgstock||alphavantage_stock||eur||both',
					
					
					// COINSTOCK (Coinbase stock)
					'coinstock||alphavantage_stock||usd||both',
					
					
					// AMZNSTOCK (Amazon stock)
					'amznstock||alphavantage_stock||usd||both',
					
					
					// AMDSTOCK (Advanced Micro Devices stock)
					'amdstock||alphavantage_stock||usd||both',
					
					
					// NFLXSTOCK (Netflix stock)
					'nflxstock||alphavantage_stock||usd||both',
					
					
					// MCDSTOCK (McDonalds stock)
					'mcdstock||alphavantage_stock||usd||both',
					
					
					);
					
// END $ct['conf']['charts_alerts']['tracked_markets']


////////////////////////////////////////
// !END! CHART AND PRICE ALERT MARKETS
////////////////////////////////////////


////////////////////////////////////////
// !START! PLUGINS CONFIG
////////////////////////////////////////


// Activate any built-in included plugins / custom plugins you've created (that run from the /plugins/ directory)
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt for creating your own custom plugins
// ADD ANY NEW PLUGIN HERE BY USING THE FOLDER NAME THE NEW PLUGIN IS LOCATED IN
// !!NEVER ADD A PLUGIN SOMEBODY ELSE WROTE, UNLESS YOU OR SOMEONE YOU TRUST 
// HAVE REVIEWED THE CODE AND ARE ABSOLUTELY SURE IT IS NOT MALICIOUS!!
// PLUGINS *MAY REQUIRE* A CRON JOB / SCHEDULED TASK RUNNING ON YOUR APP SERVER (if built for cron jobs...see README.txt for setup information)
// PLUGIN CONFIGS are in the /plugins/ directory associated with that plugin
// CHANGE 'off' to 'on' FOR THE PLUGIN YOU WANT ACTIVATED 
$ct['conf']['plugins']['plugin_status'] = array(

                      						//'plugin-folder-name' => 'on', // (disabled example...your LOWERCASE plugin folder name in the folder: /plugins/)
                      								  
                      						'debt-interest-tracker' => 'on',  // Track how much you pay in TOTAL interest MONTHLY on ALL your debt (credit cards, auto / personal / mortgage loan, etc)
          
                      						'recurring-reminder' => 'off',  // Recurring Reminder plugin (alert yourself every X days to do something)
          
                      						'price-target-alert' => 'off',  // Price target alert plugin (alert yourself when an asset's price target is reached)
          
                      						'address-balance-tracker' => 'off',  // Alerts for BTC / ETH / [SOL|SPL Token] address balance changes (when coins are sent / recieved)
          
                      						'crypto-info-bot' => 'off', // WORK-IN-PROGRESS, NOT FUNCTIONAL YET!
          
                      						'on-chain-stats' => 'off', // WORK-IN-PROGRESS, NOT FUNCTIONAL YET!
          
                      					   );


////////////////////////////////////////
// !END! PLUGINS CONFIG
////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
// !START! POWER USER CONFIGURATION (ADJUST WITH CARE, OR YOU CAN BREAK THE APP!)
/////////////////////////////////////////////////////////////////////////////


// Enable / disable PHP error reporting (to error logs on the app server)
// https://www.php.net/manual/en/function.error-reporting.php
$ct['conf']['power']['php_error_reporting'] = 0; // 0 == off / -1 == on


// $ct['conf']['power']['debug_mode'] enabled runs unit tests during ui runtimes (during webpage load),
// errors detected are error-logged and printed as alerts in header alert bell area
// It also logs ui / cron runtime telemetry to /cache/logs/app_log.log, AND /cache/logs/debug/
////////////////////////////////////////////////////////////////////////////////////////////
////
// ### GENERAL ###
////
// 'off' (disables), 
// 'all' (all debugging), 
////
// ### TELEMETRY ###
////
// 'all_telemetry' (ALL in-app telemetry), 
// 'conf_telemetry' (ct['conf'] caching),
// 'light_chart_telemetry' (light chart caching),
// 'memory_usage_telemetry' (PHP system memory usage),
// 'ext_data_live_telemetry' (external API requests from server),
// 'ext_data_cache_telemetry' (external API requests from cache),
// 'smtp_telemetry' (smtp server responses to: /cache/logs/smtp_debug.log),
// 'api_comms_telemetry' (API comms responses to: /cache/logs/debug/external_data/last-response-[service].log),
// 'cron_telemetry' (cron runtime telemetry to: /cache/logs/debug/cron/cron_runtime_telemetry.log),
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
////
////////////////////////////////////////////////////////////////////////////////////////////
// UNIT TESTS ('CHECKS' SECTION) WILL ONLY RUN DURING WEB PAGE INTERFACE RUNTIMES
// PHP MAX EXECUTION TIME *SHOULD* AUTO-SET TO 1320 SECONDS (22 MINUTES) IN *ANY* DEBUG MODE (EXCEPT 'off')
// IF YOU GET AN ERROR 500, TRY RUNNING ONE DEBUG MODE AT A TIME, TO AVOID GOING OVER THE PHP EXECUTION TIME LIMIT
// DON'T LEAVE DEBUGGING ENABLED AFTER USING IT, THE /cache/logs/app_log.log AND /cache/logs/debug/
// LOG FILES !CAN GROW VERY QUICKLY IN SIZE! EVEN AFTER JUST A FEW RUNTIMES!
$ct['conf']['power']['debug_mode'] = 'off'; 


// Level of detail / verbosity in log files. 'normal' logs minimal details (basic information), 
// 'verbose' logs maximum details (additional information IF AVAILABLE, for heavy debugging / tracing / etc)
// IF DEBUGGING IS ENABLED ABOVE, LOGS ARE AUTOMATICALLY VERBOSE #WITHOUT THE NEED TO ADJUST THIS SETTING#
$ct['conf']['power']['log_verbosity'] = 'normal'; // 'normal' / 'verbose'


// If you want to override the default CURL user agent string (sent with API requests, etc)
// Adding a string here automatically enables that as the custom curl user agent
// LEAVING BLANK '' USES THE DEFAULT CURL USER AGENT LOGIC BUILT-IN TO THIS APP (WHICH INCLUDES ONLY BASIC SYSTEM CONFIGURATION STATS)
$ct['conf']['power']['override_curl_user_agent'] = ''; 
							
							
// MINUTES to wait until running consecutive desktop edition emulated cron jobs
// (HOW OFTEN BACKGROUND TASKS ARE RUN...#NOT# USED IN SERVER EDITION)
// SET TO ZERO DISABLES EMULATED CRON JOBS ON #DESKTOP EDITIONS#
// DON'T SET TOO LOW, OR EXCHANGE PRICE DATA MAY BE BLOCKED / THROTTLED TEMPORARILY ON OCASSION!
// IF USING ADD-WIN10-SCHEDULER-JOB.bat, #THIS SETTING NEEDS TO BE DISABLED# OR THE SCHEDULED TASK WILL #NOT# BE ALLOWED TO RUN!
// IF YOU CHANGE THIS SETTING, YOU *MUST* RESTART / RELOAD THE APP *AFTERWARDS*!
$ct['conf']['power']['desktop_cron_interval'] = 20; // (default = 20, 0 disables this feature)


// Delete visitor stats older than X DAYS
$ct['conf']['power']['access_stats_delete_old'] = 30; // (default = 30, MAX = 360)


// MINUTES to cache real-time exchange price data...can be zero to DISABLE cache, but set to at least 1 minute TO AVOID YOUR IP ADDRESS GETTING BLOCKED
// SOME APIS PREFER THIS SET TO AT LEAST A FEW MINUTES, SO IT'S RECOMMENDED TO KEEP FAIRLY HIGH
$ct['conf']['power']['last_trade_cache_time'] = 4; // (default = 4, MAX = 60)


// MINUTES to cache blockchain stats (for mining calculators). Set high initially, it can be strict
$ct['conf']['power']['blockchain_stats_cache_time'] = 60;  // (default = 60, MAX = 100)


// MINUTES to cache marketcap rankings...START HIGH and test lower, it can be STRICT
// (coingecko #ABSOLUTELY HATES# DATA CENTER IPS [DEDICATED / VPS SERVERS], BUT GOES EASY ON RESIDENTIAL IPS)
$ct['conf']['power']['marketcap_cache_time'] = 100;  // (default = 100, MAX = 120)
////
// Number of marketcap rankings to request from API.
// 300 rankings is a safe maximum to START WITH, to avoid getting your API requests THROTTLED / BLOCKED
// (coingecko #ABSOLUTELY HATES# DATA CENTER IPS [DEDICATED / VPS SERVERS], BUT GOES EASY ON RESIDENTIAL IPS)
$ct['conf']['power']['marketcap_ranks_max'] = 300; // (default = 300)


// Maximum margin leverage available in the user interface ('Update Portfolio' page, etc)
$ct['conf']['power']['margin_leverage_maximum'] = 150; 


// Days TO WAIT UNTIL DELETING OLD backup archives (chart data archives, etc)
$ct['conf']['power']['backup_archive_delete_old'] = 15; 


// Keep logs X DAYS before purging (fully deletes logs every X days). Start low (especially when using proxies)
$ct['conf']['power']['logs_purge'] = 5; // (default = 5)
			
			
// Configuration for system resource warning thresholds (logs to error log, and sends comms alerts to any activated comms)
// (WHEN THE SYSTEM RESOURCES REACH THESE VALUES [and it's been hours_between_alerts since last alert],
// THE WARNINGS ARE TRIGGERED TO BE LOGGED / SENT TO ADMIN COMMS)
// !!LEAVE YOURSELF SOME #EXTRA ROOM# ON THESE VALUES, TO BE ALERTED #BEFORE# YOUR SYSTEM WOULD RISK CRASHING!!
////
// If SYSTEM UPTIME has only been up X DAYS (or less), trigger warning
$ct['conf']['power']['system_uptime_warning'] = '0||36'; // 'days_uptime||hours_between_alerts' (default = '0||36')
////
// SYSTEM LOAD warning default is 2x number of cores your app server has (1 core system = load level 2.00 would trigger an alert)
// SYSTEM LOAD THRESHOLD MULTIPLIER allows you to adjust when warning is triggered (0.5 is half default, 2.00 is 2x default, etc)
$ct['conf']['power']['system_load_warning'] = '1.00||4';  // 'threshold_multiplier||hours_between_alerts' (default = '1.00||4')
////
// If system TEMPERATURE is X degrees celcius (or more), trigger warning
$ct['conf']['power']['system_temperature_warning'] = '70||1'; // 'temp_celcius||hours_between_alerts' (default = '70||1')
////
// If USED MEMORY PERCENTAGE is X (or more), trigger warning
$ct['conf']['power']['memory_used_percent_warning'] = '90||4'; // 'memory_used_percent||hours_between_alerts' (default = '90||4')
////
// If FREE STORAGE space is X MEGABYTES (or less), trigger warning
$ct['conf']['power']['free_partition_space_warning'] = '1000||24'; // 'free_space_megabytes||hours_between_alerts' (default = '1000||24')
////
// If PORTFOLIO CACHE SIZE is X MEGABYTES (or more), trigger warning
$ct['conf']['power']['portfolio_cache_warning'] = '2000||72'; // 'portfolio_cache_megabytes||hours_between_alerts' (default = '2000||72')
////
// If ALL COOKIES TOTAL DATA SIZE is X BYTES (or more), trigger warning
// Because the header data MAY be approaching the server limit (WHICH CAN CRASH THIS APP!!)
// STANDARD SERVER HEADER SIZE LIMITS (IN BYTES)...Apache: 8000, NGINX: 4000 - 8000, IIS: 8000 - 16000, Tomcat: 8000 - 48000
$ct['conf']['power']['cookies_size_warning'] = '7000||6'; // 'cookies_size_bytes||hours_between_alerts' (default = '7000||6')
     
     
// Servers with STRICT CONSECUTIVE CONNECT limits (we add 1.11 seconds to the wait between consecutive connections)
$ct['conf']['power']['strict_consecutive_connect_servers'] = array(
                                      					       'alphavantage.co',
                                      					      );


////////////////////////////////////////
// !END! POWER USER CONFIGURATION
////////////////////////////////////////
				

////////////////////////////////////////
// !START! NEWS FEEDS CONFIGURATION
////////////////////////////////////////


// NEWS FEED SETTINGS (ATOM / RSS formats supported)
// RSS feed entries to show (per-feed) on News page (without needing to click the "show more / less" link)
$ct['conf']['news']['entries_to_show'] = 10; // (default = 10)


// RSS feed entries under X DAYS old are marked as 'new' on the news page
$ct['conf']['news']['mark_as_new'] = 2; // (default = 2)


// Every X days email a list of #NEW# RSS feed posts. 
// 0 to disable. Email to / from !MUST BE SET IN COMMS CHANNELS SETUP!
$ct['conf']['news']['news_feed_email_frequency'] = 2; // (default = 2)


// MAXIMUM #NEW# RSS feed entries to include (per-feed) in news feed EMAIL (that are less then 'news_feed_email_frequency' days old)
$ct['conf']['news']['news_feed_email_entries_include'] = 20; // (default = 20)


// Minutes to cache RSS feeds for News page
// Randomly cache each RSS feed between the minimum and maximum MINUTES set here (so they don't refresh all at once, for faster runtimes)
// THE WIDER THE GAP BETWEEN THE NUMBERS, MORE SPLIT UP / FASTER THE FEEDS WILL LOAD IN THE INTERFACE #CONSISTANTLY#
$ct['conf']['news']['news_feed_cache_min_max'] = '100,200'; // 'min,max' (default = '100,200'), ADJUST WITH CARE!!!


// Maximum number of BATCHED news feed fetches / re-caches per ajax OR cron runtime 
// (#TO HELP PREVENT RUNTIME CRASHES# ON LOW POWER DEVICES OR HIGH TRAFFIC INSTALLS, USE A LOW NUMBER OF 20 OR LESS)
$ct['conf']['news']['news_feed_batched_maximum'] = 20; // (default = 20), ADJUST WITH CARE!!!


// Maximum number of news feeds allowed to be pre-cached during background tasks (to avoid overloading low power devices)
$ct['conf']['news']['news_feed_precache_maximum'] = 45; // (default = 45), ADJUST WITH CARE!!!
    
     
// RSS feed services that are a bit funky with allowed user agents, so we need to let them know this is a real feed parser (not just a spammy bot)
// (user agent string is EXPLICITLY SET AS A CUSTOM FEED PARSER)
$ct['conf']['news']['strict_news_feed_servers'] = array(
                                                      'cointelegraph.com',
                                                      'medium.com',
                                                      'reddit.com',
                                                      'whatbitcoindid.com',
                                                      'simplecast.com',
                                                     );


// RSS news feeds available on the News page
$ct['conf']['news']['feeds'] = array(
    
    
    					/////////////////////////////////////////////////////
    					// STANDARD RSS #AND# ATOM FORMAT ARE SUPPORTED
    					/////////////////////////////////////////////////////
    
    
        				array(
            			      "title" => "Blog - BitcoinCore.org",
            			      "url" => "https://bitcoincore.org/en/rss.xml"
        				     ),
        
        
        				array(
            			      "title" => "Blog - Bitmex",
            			      "url" => "https://blog.bitmex.com/feed/?lang=en_us"
        				     ),
        
        
        				array(
            			      "title" => "Blog - Ethereum.org (community-driven on-chain smart contracts)",
            			      "url" => "https://blog.ethereum.org/feed.xml"
        				     ),
    
    
        				array(
            			      "title" => "Blog - Kraken",
            			      "url" => "https://blog.kraken.com/feed/"
        				     ),
        
        
        				array(
            			      "title" => "Blog - RNDR Network (Blockchain-Distributed GPU Rendering)",
            			      "url" => "https://medium.com/feed/render-token"
        				     ),
        
        
        				array(
            			      "title" => "Blog - Star Atlas (NFT-based Space Shooter Metaverse on Solana)",
            			      "url" => "https://medium.com/feed/star-atlas"
        				     ),
        
        
        				array(
            			      "title" => "News - Bitcoin Magazine",
            			      "url" => "https://bitcoinmagazine.com/feed"
        				     ),
        
        
        				array(
            			      "title" => "News - CoinTelegraph",
            			      "url" => "https://cointelegraph.com/feed"
        				     ),
        
        
        				array(
            			      "title" => "News - Decrypt",
            			      "url" => "https://decrypt.co/feed"
        				     ),
        
        
        				array(
            			      "title" => "News - Slashdot",
            			      "url" => "https://rss.slashdot.org/Slashdot/slashdot"
        				     ),
    
    
        				array(
            			      "title" => "Newsletter - Alpha Please",
            			      "url" => "https://alphapls.substack.com/feed"
        				     ),
    					
    					
        				array(
            			      "title" => "Newsletter - Bitcoin Optech",
            			      "url" => "https://bitcoinops.org/feed.xml"
        				     ),
    					
    					
        				array(
            			      "title" => "Newsletter - CoinCenter (D.C. non-profit crypto lobbying)",
            			      "url" => "https://www.newsletter.coincenter.org/feed"
        				     ),
    
    
        				array(
            			      "title" => "Newsletter - Lightning Labs (Bitcoin Layer 2 Network)",
            			      "url" => "https://lightninglabs.substack.com/feed"
        				     ),
    
    
        				array(
            			      "title" => "Newsletter - Our Network",
            			      "url" => "https://ournetwork.substack.com/feed"
        				     ),
    
    
        				array(
            			      "title" => "Newsletter - Page One",
            			      "url" => "https://page1.substack.com/feed"
        				     ),
    
    
        				array(
            			      "title" => "Newsletter - The Daily Degen",
            			      "url" => "https://thedailydegen.substack.com/feed"
        				     ),
    
    
        				array(
            			      "title" => "Newsletter - The Solana Six",
            			      "url" => "https://solanafloor.substack.com/feed"
        				     ),
    
    
        				array(
            			      "title" => "Newsletter - Step Data Insights (for Solana)",
            			      "url" => "https://stepdata.substack.com/feed"
        				     ),
    
    
        				array(
            			      "title" => "Newsletter - Week In Ethereum",
            			      "url" => "https://weekinethereumnews.com/feed/"
        				     ),
        
        
        				array(
            			      "title" => "Podcast - Bankless",
            			      "url" => "http://podcast.banklesshq.com/rss"
        				     ),
        
        
        				array(
            			      "title" => "Podcast - Citadel Dispatch",
            			      "url" => "https://anchor.fm/s/45563e80/podcast/rss"
        				     ),
    					
    					
        				array(
            			      "title" => "Podcast - Stephan Livera",
            			      "url" => "https://anchor.fm/s/7d083a4/podcast/rss"
        				     ),

    					
        				array(
            			      "title" => "Podcast - Tales From The Crypt",
            			      "url" => "https://anchor.fm/s/558f520/podcast/rss"
        				     ),
    
    
        				array(
            			      "title" => "Podcast - The Solana Podcast",
            			      "url" => "https://feeds.simplecast.com/W1NI2v3Z"
        				     ),

    					
        				array(
            			      "title" => "Podcast - The Scoop",
            			      "url" => "http://feeds.megaphone.fm/theblock-thescoop"
        				     ),

    					
        				array(
            			      "title" => "Podcast - Unchained",
            			      "url" => "https://unchained.libsyn.com/unchained"
        				     ),

    					
        				array(
            			      "title" => "Podcast - Unlayered",
            			      "url" => "https://feeds.megaphone.fm/CON1529801782"
        				     ),
    
    
        				array(
            			      "title" => "Podcast - What Bitcoin Did",
            			      "url" => "https://www.whatbitcoindid.com/podcast?format=RSS"
        				     ),
    
    
        				array(
            			      "title" => "Podcast - Zero Knowledge",
            			      "url" => "https://feeds.fireside.fm/zeroknowledge/rss"
        				     ),
    
    
        				array(
            			      "title" => "Reddit - Bitcoin (hot)",
            			      "url" => "https://www.reddit.com/r/Bitcoin/hot/.rss?format=xml"
        				     ),
    
    
        				array(
            			      "title" => "Reddit - Ethereum (hot)",
            			      "url" => "https://www.reddit.com/r/Ethereum/hot/.rss?format=xml"
        				     ),
    
    
        				array(
            			      "title" => "Reddit - Solana (hot)",
            			      "url" => "https://www.reddit.com/r/solana/hot/.rss?format=xml"
        				     ),
    
    
        				array(
            			      "title" => "Reddit - StarAtlas (hot)",
            			      "url" => "https://www.reddit.com/r/staratlas/hot/.rss?format=xml"
        				     ),
    
    
        				array(
            			      "title" => "StackExchange - Bitcoin (hot)",
            			      "url" => "https://bitcoin.stackexchange.com/feeds/hot"
        				     ),
    
    
        				array(
            			      "title" => "StackExchange - Ethereum (hot)",
            			      "url" => "https://ethereum.stackexchange.com/feeds/hot"
        				     ),
    
    
        				array(
            			      "title" => "StackExchange - Solana (hot)",
            			      "url" => "https://solana.stackexchange.com/feeds/hot"
        				     ),
    
    
        				array(
            			      "title" => "Stocks - CNBC: US Top News and Analysis",
            			      "url" => "https://search.cnbc.com/rs/search/combinedcms/view.xml?partnerId=wrss01&id=100003114"
        				     ),
    
    
        				array(
            			      "title" => "Stocks - AlphaStreet",
            			      "url" => "https://news.alphastreet.com/feed/"
        				     ),
    
    
        				array(
            			      "title" => "Stocks - Grit Capital",
            			      "url" => "https://gritcapital.substack.com/feed"
        				     ),
    
    
        				array(
            			      "title" => "Stocks - Investing.com: News",
            			      "url" => "https://www.investing.com/rss/news.rss"
        				     ),
    
    
        				array(
            			      "title" => "Stocks - Sunday Morning Markets",
            			      "url" => "https://sundaymorningmarkets.substack.com/feed"
        				     ),
    
    
        				array(
            			      "title" => "Youtube - BTC Sessions",
            			      "url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UChzLnWVsl3puKQwc5PoO6Zg"
        				     ),
    
    
        				array(
            			      "title" => "Youtube - Epicenter Podcast",
            			      "url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCh-0T48JrvvmKDX41aWB_Vg"
        				     ),
    
    
        				array(
            			      "title" => "Youtube - Ethereum Foundation",
            			      "url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCNOfzGXD_C9YMYmnefmPH0g"
        				     ),
    
    
        				array(
            			      "title" => "Youtube - Kripto Emre (turkish)",
            			      "url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UC87A7vsRlyZ68gtu-z1Q3ow"
        				     ),
    
    
        				array(
            			      "title" => "Youtube - Kripto Sözlük (turkish)",
            			      "url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UC5rV0QEGbv0Y-umDwshs_HA"
        				     ),
    
    
        				array(
            			      "title" => "Youtube - Lightspeed (Solana / Alt L1 podcast)",
            			      "url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCjsgQKPpR7ubPQhPqjf8kyA"
        				     ),
    
    
        				array(
            			      "title" => "Youtube - Solana Labs",
            			      "url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UC9AdQPUe4BdVJ8M9X7wxHUA"
        				     ),
    
    
        				array(
            			      "title" => "Youtube - The Daily Gwei",
            			      "url" => "https://www.youtube.com/feeds/videos.xml?channel_id=UCvCp6vKY5jDr87htKH6hgDA"
        				     ),
        
        
    				);
				

////////////////////////////////////////
// !END! NEWS FEEDS CONFIGURATION
////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PRIMARY CONFIGURATIONS -END- //////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// EMAIL-TO-MOBILE-TEXT CONFIGURATION -START- ////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


/*

Below are the mobile networks supported by Open Crypto Tracker's email-to-mobile-text functionality. 

Using your corresponding "Network Name Key" (case-sensitive) listed below, 
add that EXACT name in this config file further above within the $ct['conf']['comms']['to_mobile_text'] setting as the text network name variable,
to enable email-to-text alerts to your network's mobile phone number.

PLEASE REPORT ANY MISSING / INCORRECT / NON-FUNCTIONAL GATEWAYS HERE, AND I WILL FIX THEM:
https://github.com/taoteh1221/Open_Crypto_Tracker/issues
(or you can add / update it yourself right in this configuration, if you know the correct gateway domain name)

*/


// All supported mobile network email-to-text gateway (domain name) configurations
// Network name keys MUST BE LOWERCASE (for reliability / consistency, 
// as these name keys are always called from (forced) lowercase name key lookups)

// DUPLICATE NETWORK NAME KEYS --WILL CANCEL EACH OTHER OUT--, !!USE A UNIQUE NAME FOR EACH KEY!!

// WHEN ADDING YOUR OWN GATEWAYS, ONLY INCLUDE THE DOMAIN (THIS APP WILL AUTOMATICALLY FORMAT TO your_phone_number@your_gateway)


$ct['conf']['mobile_network']['text_gateways'] = array(
                        
                        
                        // [EXAMPLE]
                        // 'network_name_key||network_gateway1.com',
                        // 'unique_network_name_key||network_gateway2.com',
                        
                        
                        // [INTERNATIONAL]
                        'esendex||echoemail.net',
                        'global_star||msg.globalstarusa.com',
                        
                        
                        // [MISCELLANEOUS COUNTRIES]
                        'movistar_ar||sms.movistar.net.ar', // Argentina
                        'setar||mas.aw',                    // Aruba
                        'mobiltel||sms.mtel.net',           // Bulgaria
                        'china_mobile||139.com',            // China
                        'ice||sms.ice.cr',                  // Costa Rica
                        'tmobile_hr||sms.t-mobile.hr',      // Croatia
                        'digicel||digitextdm.com',          // Dominica
                        'tellus_talk||esms.nu',             // Europe
                        'guyana_tt||sms.cellinkgy.com',     // Guyana
                        'csl||mgw.mmsc1.hkcsl.com',         // Hong Kong
                        'vodafone_it||sms.vodafone.it',     // Italy
                        'tele2_lv||sms.tele2.lv',           // Latvia
                        'emtel||emtelworld.net',            // Mauritius
                        'telcel||itelcel.com',              // Mexico
                        'tmobile_nl||gin.nl',               // Netherlands
                        'mas_movil||cwmovil.com',           // Panama
                        'claro_pr||vtexto.com',             // Puerto Rico
                        'beeline||sms.beemail.ru',          // Russia
                        'm1||m1.com.sg',                    // Singapore
                        'mobitel||sms.mobitel.lk',          // Sri Lanka
                        'tele2_se||sms.tele2.se',           // Sweden
                        'sunrise_ch||gsm.sunrise.ch',       // Switzerland
                        'movistar_uy||sms.movistar.com.uy', // Uruguay
                        
                        
                        // [AUSTRALIA]
                        'sms_broadcast||send.smsbroadcast.com.au',
                        'sms_central||sms.smscentral.com.au',
                        'sms_pup||smspup.com',
                        'tmobile_au||optusmobile.com.au',
                        'telstra||sms.tim.telstra.com',
                        'ut_box||sms.utbox.net',
                        
                        
                        // [AUSTRIA]
                        'firmen_sms||subdomain.firmensms.at',
                        'tmobile_at||sms.t-mobile.at',
                        
                        
                        // [CANADA]
                        'bell||txt.bell.ca',
                        'koodo||msg.telus.com',
                        'pc_telecom_ca||mobiletxt.ca',
                        'rogers_ca||pcs.rogers.com',
                        'sasktel||pcs.sasktelmobility.com',
                        'telus_ca||mms.telusmobility.com',
                        'virgin_ca||vmobile.ca',
                        'wind||txt.windmobile.ca',
                        
                        
                        // [COLUMBIA]
                        'claro_co||iclaro.com.co',
                        'movistar_co||movistar.com.co',
                        
                        
                        // [FRANCE]
                        'bouygues||mms.bouyguestelecom.fr',
                        'orange_fr||orange.fr',
                        'sfr||sfr.fr',
                        
                        
                        // [GERMANY]
                        'o2||o2online.de',
                        'tmobile_de||t-mobile-sms.de',
                        'vodafone_de||vodafone-sms.de',
                        
                        
                        // [ICELAND]
                        'vodafone_is||sms.is',
                        'box_is||box.is',
                        
                        
                        // [INDIA]
                        'aircel||aircel.co.in',
                        'airtel||airtelmail.com',
                        
                        
                        // [NEW ZEALAND]
                        'telecom_nz||etxt.co.nz',
                        'vodafone_nz||mtxt.co.nz',
                        
                        
                        // [NORWAY]
                        'sendega||sendega.com',
                        'teletopia||sms.teletopiasms.no',
                        
                        
                        // [POLAND]
                        'orange_pl||orange.pl',
                        'plus||text.plusgsm.pl',
                        'polkomtel||text.plusgsm.pl',
                        
                        
                        // [SOUTH AFRICA]
                        'mtn||sms.co.za',
                        'vodacom||voda.co.za',
                        
                        
                        // [SPAIN]
                        'esendex_es||esendex.net',
                        'movistar_es||movistar.net',
                        'vodafone_es||vodafone.es',
                        
                        
                        // [UNITED KINGDOM]
                        'media_burst||sms.mediaburst.co.uk',
                        'txt_local||txtlocal.co.uk',
                        'virgin_uk||vxtras.com',
                        'vodafone_uk||vodafone.net',
                        
                        
                        // [UNITED STATES]
                        'att||txt.att.net',
                        'bluegrass||mms.myblueworks.com',
                        'cellcom||cellcom.quiktxt.com',
                        'chariton_valley||sms.cvalley.net',
                        'cricket||mms.cricketwireless.net',
                        'cspire||cspire1.com',
                        'gci||mobile.gci.net',
                        'googlefi||msg.fi.google.com',
                        'nextech||sms.ntwls.net',
                        'pioneer||zsend.com',
                        'rogers_us||pcs.rogers.com',
                        'simple_mobile||smtext.com',
                        'southern_linc||page.southernlinc.com',
                        'south_central_comm||rinasms.com',
                        'sprint||messaging.sprintpcs.com',
                        'tmobile_us||tmomail.net',
                        'telus_us||mms.telusmobility.com',
                        'tracfone||mmst5.tracfone.com',
                        'union||union-tel.com',
                        'us_cellular||email.uscc.net',
                        'verizon||vtext.com',
                        'viaero||mmsviaero.com',
                        'virgin_us||vmobl.com',
                        'west_central||sms.wcc.net',
                        'xit||sms.xit.net',
                        

); // mobile_network_text_gateways END



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// EMAIL-TO-MOBILE-TEXT CONFIGURATION -END- //////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PORTFOLIO ASSETS CONFIGURATION -START- ////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

 
// BELOW IS AN EXAMPLE SET OF CONFIGURED ASSETS. PLEASE NOTE THIS IS PROVIDED TO ASSIST YOU IN ADDING YOUR PARTICULAR FAVORITE ASSETS TO THE DEFAULT LIST,
// AND !---IN NO WAY---! INDICATES ENDORSEMENT OR RECOMMENDATION OF !---ANY---! OF THE *DEMO* ASSETS!

// SEE README.txt FOR HOW TO ADD / EDIT / DELETE COINS IN THIS CONFIG

// SEE /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt FOR A FULL EXAMPLE OF THE DEFAULT CONFIGURATION (ESPECIALLY IF YOU MESS UP config.php, lol)

// See TROUBLESHOOTING.txt for tips / troubleshooting FAQs.

// TYPOS LIKE MISSED COMMAS / MISSED QUOTES / ETC !!!!WILL BREAK THE APP!!!!, BE CAREFUL EDITING THIS CONFIG FILE!


$ct['conf']['assets'] = array(

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MISCASSETS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    // (!!!!DO NOT DELETE, MISCASSETS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'MISCASSETS' => array(), 
                    // Asset END

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // BTCNFTS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    // (!!!!DO NOT DELETE, BTCNFTS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'BTCNFTS' => array(), 
                    // Asset END

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ETHNFTS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    // (!!!!DO NOT DELETE, ETHNFTS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'ETHNFTS' => array(), 
                    // Asset END

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SOLNFTS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    // (!!!!DO NOT DELETE, SOLNFTS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'SOLNFTS' => array(), 
                    // Asset END

                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ALTNFTS 
                    // (KEY PLACED HERE FOR ORDERING ONLY, DYNAMICALLY POPULATED BY THE APP AT RUNTIME)
                    // (!!!!DO NOT DELETE, ALTNFTS IS *REQUIRED* TO RUN THIS APP!!!!)
                    'ALTNFTS' => array(), 
                    // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // BTC 
                    // (!!!!*BTC #MUST# BE THE VERY FIRST* IN THIS CRYPTO ASSET MARKETS LIST!!!!)
                    // (!!!!DO NOT DELETE, BTC IS *REQUIRED* TO RUN THIS APP!!!!)
                    'BTC' => array(
                        
                        'name' => 'Bitcoin',
                        'mcap_slug' => 'bitcoin',
                        'pair' => array(
                        
                        
                        			'aud' => array(
                                    	  'kraken' => 'XBTAUD',
                                    	  'btcmarkets' => 'BTC/AUD',
                                          'coinspot' => 'btc',
                                                    ),

                                                    
                                    'brl' => array(
                                        'binance' => 'BTCBRL',
                                                    ),

                        
                                    'cad' => array(
                                          'kraken' => 'XXBTZCAD',
                                                    ),

                                                    
                                    'chf' => array(
                                          'kraken' => 'XBTCHF',
                                                    ),

                                                    
                                    'dai' => array(
                                        	'binance' => 'BTCDAI',
                                        	'hitbtc' => 'BTCDAI',
                                    	 	'kraken' => 'XBTDAI',
                                        	'okex' => 'BTC-DAI',
                                        	'kucoin' => 'BTC-DAI',
                                                    ),

                                                    
                                    'eth' => array(
                                          'loopring_amm' => 'AMM-WBTC-ETH',
                                                    ),

                                                    
                                    'eur' => array(
                                          'coinbase' => 'BTC-EUR',
                                          'binance' => 'BTCEUR',
                                          'kraken' => 'XXBTZEUR',
                                          'bitstamp' => 'btceur',
                                          'bitpanda' => 'BTC_EUR',
                                          'bitflyer' => 'BTC_EUR',
                                          'cex' => 'BTC:EUR',
                                          'luno' => 'XBTEUR',
                                                    ),

                                                    
                                    'gbp' => array(
                                          'coinbase' => 'BTC-GBP',
                                          'kraken' => 'XXBTZGBP',
                                          'bitfinex' => 'tBTCGBP',
                                          'cex' => 'BTC:GBP',
                                                    ),

                                                    
                                    'hkd' => array(
                                          'liquid' => 'BTCHKD',
                                                    ),

                                                    
                                    'inr' => array(
                                          'bitbns' => 'BTC',
                                          'buyucoin' => 'INR-BTC',
                                          'wazirx' => 'btcinr',
                                          'coindcx' => 'BTCINR',
                                          'unocoin' => 'BTC',
                                                    ),

                                                    
                                    'jpy' => array(
                                          'kraken' => 'XXBTZJPY',
                                          'bitflyer' => 'BTC_JPY',
                                                    ),

                                                    
                                    'krw' => array(
                                          'upbit' => 'KRW-BTC',
                                          'korbit' => 'btc_krw',
                                                    ),

                                                    
                                    'mxn' => array(
                                          'bitso' => 'btc_mxn',
                                                    ),

                                                    
                                    'nis' => array(
                                          'bit2c' => 'BtcNis',
                                                    ),

                                                    
                                    'rub' => array(
                                         'binance' => 'BTCRUB',
                                                    ),

                                                    
                                    'sgd' => array(
                                          'coingecko_sgd' => 'bitcoin',
                                                    ),

                        
                                    'sol' => array(
                                    	 'jupiter_ag' => 'WBTC/SOL',
                                                    ),

                                                    
                                    'try' => array(
                                          'btcturk' => 'BTCTRY',
                                          'binance' => 'BTCTRY',
                                                    ),

                                                    
                                    'twd' => array(
                                          'coingecko_twd' => 'bitcoin',
                                                    ),

                                                    
                                    'usd' => array(
                                          'coingecko_usd' => 'bitcoin',
                                          'coinbase' => 'BTC-USD',
                                          'bitstamp' => 'btcusd',
                                          'kraken' => 'XXBTZUSD',
                                          'gemini' => 'btcusd',
                                          'bitmex' => 'XBTUSD',
                                          'bitmex_u20' => 'XBTU20',
                                          'bitmex_z20' => 'XBTZ20',
                                          'bitfinex' => 'tBTCUSD',
                                          'bitflyer' => 'BTC_USD',
                                          'hitbtc' => 'BTCUSD',
                                          'okcoin' => 'BTC-USD',
                                          'cex' => 'BTC:USD',
                                                    ),

                                                    
                                    'usdc' => array(
                                          'kraken' => 'XBTUSDC',
                                          'binance_us' => 'BTCUSDC',
                                          'southxchange' => 'BTC/USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                          'binance' => 'BTCUSDT',
                                    	  'kraken' => 'XBTUSDT',
                                          'btcturk' => 'BTCUSDT',
                                          'huobi' => 'btcusdt',
                                          'okex' => 'BTC-USDT',
                                          'bitbns' => 'BTCUSDT',
                                          'wazirx' => 'btcusdt',
                                          'southxchange' => 'BTC/USDT',
                                                    ),

                                                    
                                    'zar' => array(
                                          'luno' => 'XBTZAR',
                                          'binance' => 'BTCZAR',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END (!!!!*BTC MUST BE THE VERY FIRST* IN THIS ASSET LIST, DO NOT DELETE, BTC IS *REQUIRED* TO RUN THIS APP!!!!)
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // TBTC
                    'TBTC' => array(
                        
                        'name' => 'tBTC',
                        'mcap_slug' => 'tbtc',
                        'pair' => array(

                        
                                    'btc' => array(
                                    	 'kraken' => 'TBTCXBT',
                                    	 'jupiter_ag' => 'TBTC/WBTC',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'TBTCEUR',
                                                    ),

                                                    
                                    'sol' => array(
                                    	 'jupiter_ag' => 'TBTC/SOL',
                                                    ),

                                                    
                                    'usd' => array(
                                    	'kraken' => 'TBTCUSD',
                                    	'coingecko_terminal' => 'ethereum||0xb7ecb2aa52aa64a717180e030241bc75cd946726',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // FBTCSTOCK
                    'FBTCSTOCK' => array(
                        
                        'name' => 'Fidelity Bitcoin ETF',
                        'mcap_slug' => 'FBTC:BATS',
                        'pair' => array(

                        
                                    'usd' => array(
                                        'alphavantage_stock' => 'FBTC',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ETH
                    'ETH' => array(
                        
                        'name' => 'Ethereum',
                        'mcap_slug' => 'ethereum',
                        'pair' => array(

                        
                        			'aud' => array(
                                    	  'kraken' => 'ETHAUD',
                                    	  'btcmarkets' => 'ETH/AUD',
                                          'coinspot' => 'eth',
                                                    ),

                                                    
                                    'brl' => array(
                                        'binance' => 'ETHBRL',
                                                    ),

                        
                                    'btc' => array(
                                          'binance' => 'ETHBTC',
                                          'coinbase' => 'ETH-BTC',
                                          'binance_us' => 'ETHBTC',
                                          'bitstamp' => 'ethbtc',
                                          'gemini' => 'ethbtc',
                                          'kraken' => 'XETHXXBT',
                                          'bitfinex' => 'tETHBTC',
                                          'bitmex_u20' => 'ETHU20',
                                    	  'jupiter_ag' => 'ETH/WBTC',
                                          'hitbtc' => 'ETHBTC',
                                          'upbit' => 'BTC-ETH',
                                          'bitflyer' => 'ETH_BTC',
                                          'kucoin' => 'ETH-BTC',
                                          'okex' => 'ETH-BTC',
                                          'poloniex' => 'ETH_BTC',
                                          'bitso' => 'eth_btc',
                                          'zebpay' => 'ETH-BTC',
                                          'luno' => 'ETHXBT',
                                          'wazirx' => 'ethbtc',
                                                    ),

                                                    
                                    'cad' => array(
                                          'kraken' => 'XETHZCAD',
                                                    ),

                                                    
                                    'chf' => array(
                                          'kraken' => 'ETHCHF',
                                                    ),

                                                    
                                    'dai' => array(
                                          'binance' => 'ETHDAI',
                                          'coinbase' => 'ETH-DAI',
                                          'kraken' => 'ETHDAI',
                                          'kucoin' => 'ETH-DAI',
                                          'hitbtc' => 'ETHDAI',
                                          'loopring_amm' => 'AMM-ETH-DAI',
                                                    ),

                                                    
                                    'eur' => array(
                                          'coinbase' => 'ETH-EUR',
                                          'binance' => 'ETHEUR',
                                          'kraken' => 'XETHZEUR',
                                          'bitstamp' => 'etheur',
                                          'cex' => 'ETH:EUR',
                                                    ),

                                                    
                                    'gbp' => array(
                                          'coinbase' => 'ETH-GBP',
                                          'kraken' => 'XETHZGBP',
                                          'cex' => 'BTC:GBP',
                                                    ),

                                                    
                                    'hkd' => array(
                                          'liquid' => 'ETHHKD',
                                                    ),

                                                    
                                    'inr' => array(
                                          'bitbns' => 'ETH',
                                          'buyucoin' => 'INR-ETH',
                                          'wazirx' => 'ethinr',
                                                    ),

                                                    
                                    'jpy' => array(
                                          'kraken' => 'XETHZJPY',
                                          'bitflyer' => 'ETH_JPY',
                                                    ),

                                                    
                                    'krw' => array(
                                          'upbit' => 'KRW-ETH',
                                          'korbit' => 'eth_krw',
                                                    ),

                                                    
                                    'mxn' => array(
                                          'bitso' => 'eth_mxn',
                                                    ),

                                                    
                                    'nis' => array(
                                          'bit2c' => 'EthNis',
                                                    ),

                                                    
                                    'sgd' => array(
                                          'coingecko_sgd' => 'ethereum',
                                                    ),

                                                    
                                    'sol' => array(
                                    	'jupiter_ag' => 'ETH/SOL',
                                                    ),

                                                    
                                    'try' => array(
                                          'btcturk' => 'ETHTRY',
                                          'binance' => 'ETHTRY',
                                                    ),

                                                    
                                    'usd' => array(
                                          'coingecko_usd' => 'ethereum',
                                          'coinbase' => 'ETH-USD',
                                          'kraken' => 'XETHZUSD',
                                          'bitstamp' => 'ethusd',
                                          'gemini' => 'ethusd',
                                          'bitfinex' => 'tETHUSD',
                                          'bitmex' => 'ETHUSD',
                                          'bitmex_u20' => 'ETHUSDU20',
                                          'okcoin' => 'ETH-USD',
                                          'cex' => 'ETH:USD',
                                          'presale_usd_value' => 'ETHEREUM',
                                                    ),

                                                    
                                    'usdt' => array(
                                          'binance' => 'ETHUSDT',
                                          'kraken' => 'ETHUSDT',
                                          'btcturk' => 'ETHUSDT',
                                          'huobi' => 'ethusdt',
                                          'binance_us' => 'ETHUSDT',
                                          'hitbtc' => 'ETHUSD',
                                          'upbit' => 'USDT-ETH',
                                       	  'kucoin' => 'ETH-USDT',
                                          'okex' => 'ETH-USDT',
                                          'loopring_amm' => 'AMM-ETH-USDT',
                                          'poloniex' => 'ETH_USDT',
                                          'bitbns' => 'ETHUSDT',
                                          'wazirx' => 'ethusdt',
                                                    ),

                                                    
                                    'usdc' => array(
                                          'kraken' => 'ETHUSDC',
                                          'binance_us' => 'ETHUSDC',
                                          'kucoin' => 'ETH-USDC',
                                          'loopring_amm' => 'AMM-ETH-USDC',
                                          'poloniex' => 'ETH_USDC',
                                                    ),

                                                    
                                    'zar' => array(
                                          'luno' => 'ETHZAR',
                                          'binance' => 'BTCZAR',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SOL
                    'SOL' => array(
                        
                        'name' => 'Solana',
                        'mcap_slug' => 'solana',
                        'pair' => array(

                                                    
                                    'brl' => array(
                                        'binance' => 'SOLBRL',
                                                    ),

                        
                                    'btc' => array(
                                    	'coinbase' => 'SOL-BTC',
                                        'binance' => 'SOLBTC',
                                        'huobi' => 'solbtc',
                                        'okex' => 'SOL-BTC',
                                    	'crypto.com' => 'SOL_BTC',
                                    	'jupiter_ag' => 'SOL/WBTC',
                                        'hitbtc' => 'SOLBTC',
                                        'coinex' => 'SOLBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'okex' => 'SOL-ETH',
                                        'binance' => 'SOLETH',
                                    	'jupiter_ag' => 'SOL/ETH',
                                        'hitbtc' => 'SOLETH',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'coinbase' => 'SOL-EUR',
                                         'binance' => 'SOLEUR',
                                         'binance' => 'SOLEUR',
                                    	 'kraken' => 'SOLEUR',
                                                    ),

                                                    
                                    'gbp' => array(
                                    	 'kraken' => 'SOLGBP',
                                                    ),

                                                    
                                    'try' => array(
                                         'binance' => 'SOLTRY',
                                         'gateio' => 'SOL_TRY',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'coinbase' => 'SOL-USD',
                                    	 'kraken' => 'SOLUSD',
                                    	 'bitfinex' => 'tSOLUSD',
                                         'hitbtc' => 'SOLUSD',
                                         'cex' => 'SOL:USD',
                                         'presale_usd_value' => 'SOLANA',
                                                    ),

                                                    
                                    'usdc' => array(
                                    	 'jupiter_ag' => 'SOL/USDC',
                                         'binance_us' => 'SOLUSDC',
                                         'gateio' => 'SOL_USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                    	'coinbase' => 'SOL-USDT',
                                        'binance' => 'SOLUSDT',
                                        'okex' => 'SOL-USDT',
                                        'huobi' => 'solusdt',
                                    	'binance_us' => 'SOLUSDT',
                                    	'crypto.com' => 'SOL_USDT',
                                        'kucoin' => 'SOL-USDT',
                                        'coinex' => 'SOLUSDT',
                                        'gateio' => 'SOL_USDT',
                                        'bitmart' => 'SOL_USDT',
                                        'wazirx' => 'solusdt',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MKR
                    'MKR' => array(
                        
                        'name' => 'Maker',
                        'mcap_slug' => 'maker',
                        'pair' => array(

                        
                                    'btc' => array(
                                          'binance' => 'MKRBTC',
                                          'coinbase' => 'MKR-BTC',
                                       	  'kucoin' => 'MKR-BTC',
                                          'okex' => 'MKR-BTC',
                                          'hitbtc' => 'MKRBTC',
                                          'coinex' => 'MKRBTC',
                                                    ),

                                                    
                                	'dai' => array(
                                        	'kucoin' => 'MKR-DAI',
                                        	'hitbtc' => 'MKRDAI',
                                                    ),

                                                    
                                	'eth' => array(
                                        	'kucoin' => 'MKR-ETH',
                                        	'hitbtc' => 'MKRETH',
                                            'gateio' => 'MKR_ETH',
                                                    ),

                                                    
                                	'krw' => array(
                                        	'korbit' => 'mkr_krw',
                                                    ),

                                                    
                                    'usd' => array(
                                          'coinbase' => 'MKR-USD',
                                          			),

                                                    
                                    'usdc' => array(
                                            'okex' => 'MKR-USDC',
                                          			),

                                                    
                                    'usdt' => array(
                                          'binance' => 'MKRUSDT',
                                          'okex' => 'MKR-USDT',
                                          'bitfinex' => 'tMKRUSD',
                                          'hitbtc' => 'MKRUSD',
                                          'gateio' => 'MKR_USDT',
                                          'coinex' => 'MKRUSDT',
                                          			),

                                          			
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // DAI
                    'DAI' => array(
                        
                        'name' => 'Dai',
                        'mcap_slug' => 'dai',
                        'pair' => array(

                        
                                    'btc' => array(
                                        'upbit' => 'BTC-DAI',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'DAIEUR',
                                                    ),

                                                    
                                    'krw' => array(
                                         'korbit' => 'dai_krw',
                                                    ),

                                                    
                                    'usd' => array(
                                    	'coinbase' => 'DAI-USD',
                                    	'kraken' => 'DAIUSD',
                                    	'bitfinex' => 'tDAIUSD',
                                        'gemini' => 'daiusd',
                                                    ),

                                                    
                                    'usdc' => array(
                                        'hitbtc' => 'DAIUSDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                    	'kraken' => 'DAIUSDT',
                                        'okex' => 'DAI-USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // USDC
                    'USDC' => array(
                        
                        'name' => 'USD Coin',
                        'mcap_slug' => 'usd-coin',
                        'pair' => array(

                        
                                    'btc' => array(
                                    	 'jupiter_ag' => 'USDC/WBTC',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'USDCEUR',
                                                    ),

                                                    
                                    'gbp' => array(
                                    	 'kraken' => 'USDCGBP',
                                                    ),

                                                    
                                    'sol' => array(
                                    	 'jupiter_ag' => 'USDC/SOL',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'kraken' => 'USDCUSD',
                                                    ),

                                                    
                                    'usdt' => array(
                                    	'kraken' => 'USDCUSDT',
                                        'huobi' => 'usdcusdt',
                                        'kucoin' => 'USDC-USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // UNI
                    'UNI' => array(
                        
                        'name' => 'Uniswap',
                        'mcap_slug' => 'uniswap',
                        'pair' => array(
                                                    
                                                    
                                    'btc' => array(
                                        'binance' => 'UNIBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'loopring_amm' => 'AMM-UNI-ETH',
                                                    ),


                                    'inr' => array(
                                        'bitbns' => 'UNI',
                                        'wazirx' => 'uniinr',
                                        'zebpay' => 'UNI-INR',
                                                    ),

                                                    
                                    'usd' => array(
                                        'coinbase' => 'UNI-USD',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'binance' => 'UNIUSDT',
                                        'binance_us' => 'UNIUSDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // JUP
                    'JUP' => array(
                        
                        'name' => 'Jupiter Aggregator',
                        'mcap_slug' => 'jupiter',
                        'pair' => array(

                        
                                    'btc' => array(
                                         'coingecko_btc' => 'jupiter-exchange-solana',
                                    	 'jupiter_ag' => 'JUP/WBTC',
                                                    ),

                                                    
                                    'sol' => array(
                                    	 'jupiter_ag' => 'JUP/SOL',
                                                    ),

                                                    
                                    'try' => array(
                                        'binance' => 'JUPTRY',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'coingecko_terminal' => 'solana||FgTCR1ufcaTZMwZZYhNRhJm2K3HgMA8V8kXtdqyttm19',
                                    	 'aevo_futures' => 'JUP-PERP',
                                                    ),

                                                    
                                    'usdc' => array(
                                    	 'jupiter_ag' => 'JUP/USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'binance' => 'JUPUSDT',
                                        'gateio' => 'JUP_USDT',
                                        'okex' => 'JUP-USDT',
                                        'bybit' => 'JUPUSDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // RNDR
                    'RNDR' => array(
                        
                        'name' => 'Render',
                        'mcap_slug' => 'render-token',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'kucoin' => 'RNDR-BTC',
                                        'hitbtc' => 'RNDRBTC',
                                                    ),

                                                    
                                    'eth' => array(
                                        'gateio' => 'RNDR_ETH',
                                                    ),

                                                    
                                    'sol' => array(
                                    	 'jupiter_ag' => 'RENDER/SOL',
                                                    ),

                                                    
                                    'usd' => array(
                                        'hitbtc' => 'RNDRUSD',
                                                    ),

                                                    
                                    'usdc' => array(
                                    	 'jupiter_ag' => 'RENDER/USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'huobi' => 'rndrusdt',
                                        'gateio' => 'RNDR_USDT',
                                        'kucoin' => 'RNDR-USDT',
                                        'coinex' => 'RNDRUSDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // IMX
                    'IMX' => array(
                        
                        'name' => 'IMX',
                        'mcap_slug' => 'immutable-x',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'binance' => 'IMXBTC',
                                        'upbit' => 'BTC-IMX',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'IMXEUR',
                                         'bitstamp' => 'imxeur',
                                                    ),

                                                    
                                    'krw' => array(
                                          'upbit' => 'KRW-IMX',
                                                    ),

                                                    
                                    'try' => array(
                                         'gateio' => 'IMX_TRY',
                                                    ),

                                                    
                                    'usd' => array(
                                         'coinbase' => 'IMX-USD',
                                    	 'kraken' => 'IMXUSD',
                                         'gemini' => 'imxusd',
                                    	 'crypto.com' => 'IMX_USD',
                                         'bitstamp' => 'imxusd',
                                    	 'coingecko_terminal' => 'ethereum||0x81fbbc40cf075fd7de6afce1bc72eda1bb0e13aa',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'binance' => 'IMXUSDT',
                                        'binance_us' => 'IMXUSDT',
                                        'coinbase' => 'IMX-USDT',
                                        'gateio' => 'IMX_USDT',
                                        'kucoin' => 'IMX-USDT',
                                        'bitmart' => 'IMX_USDT',
                                        'coinex' => 'IMXUSDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ZEUS
                    'ZEUS' => array(
                        
                        'name' => 'ZEUS',
                        'mcap_slug' => 'zeus-network',
                        'pair' => array(

                                                    
                                    'sol' => array(
                                    	 'jupiter_ag' => 'ZEUS/SOL',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'coingecko_terminal' => 'solana||exmN8ua4Y7qKXUZ2n8JugTNgFWrLGJAUkEBYeTKPNCX',
                                                    ),

                                                    
                                    'usdc' => array(
                                    	 'jupiter_ag' => 'ZEUS/USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'gateio' => 'ZEUS_USDT',
                                        'okex' => 'ZEUS-USDT',
                                        'kucoin' => 'ZEUS-USDT',
                                        'bitmart' => 'ZEUS_USDT',
                                        'coinex' => 'ZEUSUSDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // NEON
                    'NEON' => array(
                        
                        'name' => 'Neon',
                        'mcap_slug' => 'neon',
                        'pair' => array(

                                                    
                                    'sol' => array(
                                    	 'jupiter_ag' => 'NEON/SOL',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'coingecko_terminal' => 'solana||GUWM1arUyDnkMGCHvJu3yt1qomJ988utqC3dFN2AUCDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SHDW
                    'SHDW' => array(
                        
                        'name' => 'Shadow',
                        'mcap_slug' => 'genesysgo-shadow',
                        'pair' => array(

                                                    
                                    'sol' => array(
                                    	 'jupiter_ag' => 'SHDW/SOL',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'coingecko_terminal' => 'solana||2wbnvtStBTRRGJhCAwpLSWxrUrfRL4H2FTsujseALsm1',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MANA
                    'MANA' => array(
                        
                        'name' => 'Decentraland',
                        'mcap_slug' => 'decentraland',
                        'pair' => array(

                        
                                    'btc' => array(
                                          'binance' => 'MANABTC',
                                          'ethfinex' => 'tMNABTC',
                                          'kucoin' => 'MANA-BTC',
                                          'upbit' => 'BTC-MANA',
                                          'okex' => 'MANA-BTC',
                                          'poloniex' => 'MANA_BTC',
                                                    ),

                                                    
                                    'eth' => array(
                                          'binance' => 'MANAETH',
                                          'hitbtc' => 'MANAETH',
                                          'kucoin' => 'MANA-ETH',
                                                    ),

                                                    
                                    'krw' => array(
                                          'upbit' => 'KRW-MANA',
                                                    ),

                                                    
                                    'mxn' => array(
                                          'bitso' => 'mana_mxn',
                                                    ),

                                                    
                                    'usd' => array(
                                          'coinbase' => 'MANA-USD',
                                          'presale_usd_value' => 'DECENTRALAND',
                                                    ),

                                                    
                                    'usdc' => array(
                                            'okex' => 'MANA-USDC',
                                          			),

                                                    
                                    'usdt' => array(
                                          'hitbtc' => 'MANAUSD',
                                          'okex' => 'MANA-USDT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // POLIS
                    'POLIS' => array(
                        
                        'name' => 'Star Atlas DAO',
                        'mcap_slug' => 'star-atlas-dao',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'coingecko_btc' => 'star-atlas-dao',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'POLISEUR',
                                                    ),

                                                    
                                    'sol' => array(
                                    	 'jupiter_ag' => 'POLIS/SOL',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'kraken' => 'POLISUSD',
                                                    ),

                                                    
                                    'usdc' => array(
                                    	 'jupiter_ag' => 'POLIS/USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'gateio' => 'POLIS_USDT',
                                        'coinex' => 'POLISUSDT',
                                        'bitmart' => 'ATLAS_USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // ATLAS
                    'ATLAS' => array(
                        
                        'name' => 'Star Atlas',
                        'mcap_slug' => 'star-atlas',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'coingecko_btc' => 'star-atlas',
                                                    ),

                                                    
                                    'eur' => array(
                                    	 'kraken' => 'ATLASEUR',
                                                    ),

                                                    
                                    'sol' => array(
                                    	 'jupiter_ag' => 'ATLAS/SOL',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'kraken' => 'ATLASUSD',
                                    	 'coingecko_usd' => 'star-atlas',
                                                    ),

                                                    
                                    'usdc' => array(
                                    	 'jupiter_ag' => 'ATLAS/USDC',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'gateio' => 'ATLAS_USDT',
                                        'coinex' => 'ATLASUSDT',
                                        'bitmart' => 'ATLAS_USDT',
                                                    ),

                                                    
                        ) // pair END
                                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // HIVE
                    'HIVE' => array(
                        
                        'name' => 'Hive',
                        'mcap_slug' => 'hive',
                        'pair' => array(

                        
                                    'btc' => array(
                                        'binance' => 'HIVEBTC',
                                                    ),

                        
                                    'usdt' => array(
                                        'huobi' => 'hiveusdt',
                                        'wazirx' => 'hiveusdt',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // BONK
                    'BONK' => array(
                        
                        'name' => 'Bonk Inu',
                        'mcap_slug' => 'bonk',
                        'pair' => array(

                                                    
                                    'btc' => array(
                                        'coingecko_btc' => 'bonk',
                                                    ),

                                                    
                                    'eth' => array(
                                        'coingecko_eth' => 'bonk',
                                                    ),

                                                    
                                    'usd' => array(
                                        'coingecko_usd' => 'bonk',
                                                    ),

                                                    
                                    'usdt' => array(
                                        'bybit' => '1000BONKUSDT', // x1000 VAL (processed in api.php to normal val [divided by 1000])
                                        'huobi' => 'bonkusdt',
                                        'gateio' => 'BONK_USDT',
                                        'bitmart' => 'BONK_USDT',
                                        'coinex' => 'BONKUSDT',
                                        'bitforex' => 'coin-usdt-bonk',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // WEN
                    'WEN' => array(
                        
                        'name' => 'WEN',
                        'mcap_slug' => 'wen-4',
                        'pair' => array(

                                                    
                                    'sol' => array(
                                    	 'jupiter_ag' => 'WEN/SOL',
                                                    ),

                                                    
                                    'usd' => array(
                                    	 'coingecko_terminal' => 'solana||5WGx6mE9Xww3ocYzSenGVQMJLCVVwK7ePnYV6cXcpJtK',
                                                    ),

                                                    
                                    'usdc' => array(
                                    	 'jupiter_ag' => 'WEN/USDC',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // SHOPSTOCK
                    'SHOPSTOCK' => array(
                        
                        'name' => 'Shopify Inc',
                        'mcap_slug' => 'SHOP:TSE',
                        'pair' => array(

                        
                                    'cad' => array(
                                        'alphavantage_stock' => 'SHOP.TRT',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // DTGSTOCK
                    'DTGSTOCK' => array(
                        
                        'name' => 'Daimler Truck Holding AG',
                        'mcap_slug' => 'DTG:ETR',
                        'pair' => array(

                        
                                    'eur' => array(
                                        'alphavantage_stock' => 'DTG.DEX',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // COINSTOCK
                    'COINSTOCK' => array(
                        
                        'name' => 'Coinbase Global Inc',
                        'mcap_slug' => 'COIN:NASDAQ',
                        'pair' => array(

                        
                                    'usd' => array(
                                        'alphavantage_stock' => 'COIN',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // AMZNSTOCK
                    'AMZNSTOCK' => array(
                        
                        'name' => 'Amazon Inc',
                        'mcap_slug' => 'AMZN:NASDAQ',
                        'pair' => array(

                        
                                    'usd' => array(
                                        'alphavantage_stock' => 'AMZN',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // AMDSTOCK
                    'AMDSTOCK' => array(
                        
                        'name' => 'Advanced Micro Devices',
                        'mcap_slug' => 'AMD:NASDAQ',
                        'pair' => array(

                        
                                    'usd' => array(
                                        'alphavantage_stock' => 'AMD',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // NFLXSTOCK
                    'NFLXSTOCK' => array(
                        
                        'name' => 'Netflix Inc',
                        'mcap_slug' => 'NFLX:NASDAQ',
                        'pair' => array(

                        
                                    'usd' => array(
                                        'alphavantage_stock' => 'NFLX',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                    
                    ////////////////////////////////////////////////////////////////////
                    
                    
                    // MCDSTOCK
                    'MCDSTOCK' => array(
                        
                        'name' => 'McDonalds Corp',
                        'mcap_slug' => 'MCD:NYSE',
                        'pair' => array(

                        
                                    'usd' => array(
                                        'alphavantage_stock' => 'MCD',
                                                    ),

                                                    
                        ) // pair END
                        
                    ), // Asset END
                    
                
                    ////////////////////////////////////////////////////////////////////
                
                
); // All assets END



///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// PORTFOLIO ASSETS CONFIGURATION -END- //////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
?>