<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

?>


<?php
if ( $ct['admin_area_sec_level'] == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {
?>


	<p class='blue blue_dotted'>
	
	PRO TIP: An easy / reliable way to get your keys below is opposite-clicking over the key AFTER selecting all it's characters, and choosing "Copy". Then opposite-click inside whatever text editor you are developing in, and choose "Paste". Alternatively, you can also do this with the keyboard combinations: Ctrl + C (copy) / Ctrl + V (paste)
	
	</p>
	

<?php

// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['api_rate_limit']['is_range'] = true;

$ct['admin_render_settings']['api_rate_limit']['range_ui_meta_data'] .= 'zero_is_unlimited;';

$ct['admin_render_settings']['api_rate_limit']['range_min'] = 0;

$ct['admin_render_settings']['api_rate_limit']['range_max'] = 10;

$ct['admin_render_settings']['api_rate_limit']['range_step'] = 1;

$ct['admin_render_settings']['api_rate_limit']['range_ui_prefix'] = 'Every ';

$ct['admin_render_settings']['api_rate_limit']['range_ui_suffix'] = ' Seconds';

$ct['admin_render_settings']['api_rate_limit']['is_notes'] = 'MAXIMUM allowed connection rate (for Internal API *and* WebHooks)<br /><span class="red">LOWER POWER SERVERS may MISS fully enforcing intervals UNDER ~3 SECONDS.</span>';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['int_api_markets_limit']['is_range'] = true;

$ct['admin_render_settings']['int_api_markets_limit']['range_min'] = 5;

$ct['admin_render_settings']['int_api_markets_limit']['range_max'] = 100;

$ct['admin_render_settings']['int_api_markets_limit']['range_step'] = 5;

$ct['admin_render_settings']['int_api_markets_limit']['range_ui_suffix'] = ' Markets';

$ct['admin_render_settings']['int_api_markets_limit']['is_notes'] = 'MAXIMUM number of market data sets allowed per-request';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['int_api_cache_time']['is_range'] = true;

$ct['admin_render_settings']['int_api_cache_time']['range_min'] = 1;

$ct['admin_render_settings']['int_api_cache_time']['range_max'] = 10;

$ct['admin_render_settings']['int_api_cache_time']['range_step'] = 1;

$ct['admin_render_settings']['int_api_cache_time']['range_ui_suffix'] = ' Minutes';

$ct['admin_render_settings']['int_api_cache_time']['is_notes'] = 'Cache time (time to wait, before getting LIVE data again [Internal API only, NOT used for WebHooks])';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
// SHOULD BE COMMA-DELIMITED: 'iframe_reset_backup_restore,iframe_apis'
$ct['admin_render_settings']['is_refresh_admin'] = 'all';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('int_api', 'webhook_int_api', $ct['admin_render_settings']);


////////////////////////////////////////////////////////////////////////////////////////////////

?>
	
	<div style='min-height: 1em;'></div>

	<p>
	
	<b class='blue'>WEBHOOK DOCUMENTATION / KEYS:</b> <br /><br />
	
	Webhooks are added via the plugin system built into this app (when a specific plugin's "runtime_mode" is set to "webhook" OR "all").
	<br /><br />
	
	See <a href='https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/DOCUMENTATION-ETC/PLUGINS-README.txt' target='_blank'>/DOCUMENTATION-ETC/PLUGINS-README.txt</a> for more information on plugin creation / development.
	<br /><br />
	
	You can include ADDITIONAL PARAMETERS *AFTER* THE WEBOOK KEY, USING FORWARD SLASHES TO DELIMIT THEM: <br /><br />
	
	<b class='bitcoin'><?=$ct['base_url']?><?=$ct['int_webhook_base_endpoint']?>WEBHOOK_KEY/PARAM1/PARAM2/PARAM3/ETC</b>
	<br /><br />

     These parameters are then automatically put into a PHP array named: <pre class='rounded' style='position: relative; top: 0.65em; display: inline-block; padding: 0em !important;'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block; padding: 0em !important;'>$plug['webhook'][$this_plug]['params']</code></pre>
     <br /><br />
     
     The webhook key is also available, in the auto-created variable: <pre class='rounded' style='position: relative; top: 0.65em; display: inline-block; padding: 0em !important;'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block; padding: 0em !important;'>$plug['webhook'][$this_plug]['key']</code></pre>
     <br /><br />
     
     See the Plugins admin area, for additional settings / documentation related to each webhook plugin listed below.
	
	</p>
	
<fieldset class='subsection_fieldset'>
<legend class='subsection_legend'> Active Webhook Plugins  </legend>
<?php

if ( !isset($plug['activated']['webhook']) ) {
echo '<p><span class="bitcoin">No webhooks activated.</span></p>';
}
	
foreach ( $plug['activated']['webhook'] as $plugin_key => $plugin_init ) {
        		
$webhook_plug = $plugin_key;
        	
    if ( file_exists($plugin_init) && isset($ct['int_webhooks'][$webhook_plug]) ) {
    ?>
       
     <p>
     
     <b class='blue'>Webhook endpoint for "<?=$plug['conf'][$webhook_plug]['ui_name']?>" plugin:</b> <br /><br />
     
     <b class='bitcoin'><?=$ct['base_url']?><?=$ct['int_webhook_base_endpoint']?><?=$ct['sec']->nonce_digest($webhook_plug, $ct['int_webhooks'][$webhook_plug] . $webhook_master_key)?></b>
     
     </p>
     <br /> &nbsp; <br />
     
     <?php
     }
        	
// Reset $webhook_plug at end of loop
unset($webhook_plug); 
        
}
?>
</fieldset>


		    

		<p>
	
	<b class='blue'>INTERNAL API DOCUMENTATION / KEYS:</b> <br /><br />
	
	This app has a built-in (internal) REST API available, so other external apps can connect to it and receive market data, including market conversion (converting the market values to their equivalent value in country fiat currencies and secondary cryptocurrency market pairs).</p>
		
		<p>To see a list of the supported assets in the API, use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$ct['int_api_base_endpoint']?>asset_list</b></p>
		
		<p>To see a list of the supported exchanges in the API, use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$ct['int_api_base_endpoint']?>exchange_list</b></p>
		
		<p>To see a list of the supported markets for a particular exchange in the API, use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$ct['int_api_base_endpoint']?>market_list/[exchange name]</b></p>
		
		<p>To see a list of the supported conversion currencies (market values converted to these currency values) in the API, use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$ct['int_api_base_endpoint']?>conversion_list</b></p>
		
		<p>To get raw market values AND also get a market conversion to a supported conversion currency (see ALL requested market values also converted to values in this currency) in the API, use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$ct['int_api_base_endpoint']?>market_conversion/[conversion currency]/[exchange1-asset1-pair1],[exchange2-asset2-pair2],[exchange3-asset3-pair3]</b></p>
		
		<p><i>To skip conversions and just receive raw market values</i> in the API, you can use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$ct['int_api_base_endpoint']?>market_conversion/market_only/[exchange1-asset1-pair1],[exchange2-asset2-pair2],[exchange3-asset3-pair3]</b></p>
		
		<p>For security, the API requires a key / token to access it. This key must be named "api_key", and must be sent with the "POST" data method.</p>
	
		<p>Below are <i>fully working examples <span class='bitcoin'>(including your auto-generated login authentication tokens)</span></i>, of connecting an external app with CURL command line or PHP, and Javascript.</p>
	
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> REST API Access Examples </legend>
	        
	    <p class='bitcoin'>Bash / CURL example:</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll bash' style='width: auto; height: auto;'># CURL command line example:
<?php

// ALERT for WINDOWS SERVER EDITION
if ( $ct['app_edition'] == 'server' ) {
?>
# Add --insecure to the command, if your app's SSL certificate
# is SELF-SIGNED (not CA issued), #OR THE COMMAND WON'T WORK#

<?php
}


// Proper curl name for WINDOWS (ANY edition)
// https://stackoverflow.com/questions/69261782/why-does-the-same-curl-command-output-different-things-in-windows-and-linux
if (
isset($ct['system_info']['distro_name'])
&& preg_match("/windows/i", $ct['system_info']['distro_name'])
) {
$curl_name = 'curl.exe';
?>
# WINDOWS (ANY Edition) USERS: ALWAYS USE "curl.exe", AS "curl" IS A DIFFERENT PROGRAM!

<?php
}
else {
$curl_name = 'curl';
}


// ALERT for WINDOWS DESKTOP EDITIONS
if ( $ct['app_container'] == 'phpdesktop' && $ct['app_platform'] == 'windows' ) {
?>
# WINDOWS (DESKTOP Edition) USERS: <?=$curl_name?> COMMANDS MAY NOT WORK, because the
# PHPdesktop (used by Desktop Editions) server config sends MALFORMED header responses!

<?php
}
?>
<?=$curl_name?><?=( isset($htaccess_username) && isset($htaccess_password) && $htaccess_username != '' && $htaccess_password != '' ? ' -u "' . $htaccess_username . ':' . $htaccess_password . '"' : '' )?> -d "api_key=<?=$int_api_key?>" -X POST <?=$ct['base_url']?><?=$ct['int_api_base_endpoint']?>market_conversion/eur/kraken-btc-usd,coinbase-eth-usd,binance_us-sol-usdc
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>Javascript example:</p>
	        	        	        
	                
<pre class='rounded'><code class='hide-x-scroll javascript' style='width: auto; height: auto;'>// Javascript example:

<?php
if ( isset($htaccess_username) && isset($htaccess_password) && $htaccess_username != '' && $htaccess_password != '' ) {
?>
// Login htaccess user/pass (results sent to console log)
// (we are not looking for an API result YET, rather just logging in the htaccess user/pass)

var htaccess_login = new XMLHttpRequest();

htaccess_login.open("GET", "<?=$ct['base_url']?><?=$ct['int_api_base_endpoint']?>market_conversion", true);

htaccess_login.withCredentials = true;
htaccess_login.setRequestHeader("Authorization", 'Basic ' + btoa('<?=$htaccess_username?>:<?=$htaccess_password?>'));

	htaccess_login.onload = function () {
	console.log("htaccess_login = " + htaccess_login.responseText);
	};
	
htaccess_login.send();

<?php
}
?>
// API request (API results sent to console log)

var api_request = new XMLHttpRequest();

api_request.open("POST", "<?=$ct['base_url']?><?=$ct['int_api_base_endpoint']?>market_conversion/eur/kraken-btc-usd,coinbase-eth-usd,binance_us-sol-usdc", true);

var params = "api_key=<?=$int_api_key?>";

api_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	api_request.onload = function () {
	console.log("api_request = " + api_request.responseText);
	};

<?php
if ( isset($htaccess_username) && isset($htaccess_password) && $htaccess_username != '' && $htaccess_password != '' ) {
?>
// Our API has a rate limit of once every <?=$ct['conf']['int_api']['api_rate_limit']?> seconds,
// so we must wait to reconnect after the htaccess authentication (<?=$ct['conf']['int_api']['api_rate_limit']?> + 1 seconds)
// ANY CONSECUTIVE CALLS #DON'T NEED# THE TIMEOUT (since htaccess is already logged in): api_request.send(params);
setTimeout(function(){ api_request.send(params); }, <?=( ($ct['conf']['int_api']['api_rate_limit'] + 1) * 1000)?>);
<?php
}
else {
?>
api_request.send(params);
<?php
}
?>
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>PHP / CURL example:</p>
	        	        
	        
<pre class='rounded'><code class='hide-x-scroll php' style='width: auto; height: auto;'>// CURL PHP example (requires CURL PHP module):

// PHP version
if (!defined('PHP_VERSION_ID')) {
$version = explode('.', PHP_VERSION);
define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

// Curl version
if ( function_exists('curl_version') ) {
$curl_setup = curl_version();
define('CURL_VERSION_ID', str_replace(".", "", $curl_setup["version"]) );
}
else {
echo 'CURL module for PHP required.';
exit;
}

// Initiate CURL
$ch = curl_init('<?=$ct['base_url']?><?=$ct['int_api_base_endpoint']?>market_conversion/eur/kraken-btc-usd,coinbase-eth-usd,binance_us-sol-usdc');

$params = array('api_key' => '<?=$int_api_key?>');

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params) ); // Encode post data with http_build_query()

// Timeout in seconds (so we don't hang if API is not responsive)
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

<?php
if ( isset($htaccess_username) && isset($htaccess_password) && $htaccess_username != '' && $htaccess_password != '' ) {
?>
// Htaccess login
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_USERPWD, '<?=($htaccess_username . ':' . $htaccess_password)?>');

<?php
}
?>
// Strict SSL connections, or not? (see notes next to settings)
// If your app's SSL certificate is SELF-SIGNED (not CA issued), 
// #CHANGE ALL OF THESE# to false / 0, #OR THE API REQUEST WILL FAIL#
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, true); // SET TO false to skip verifying the peer's SSL certificate
curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2); // SET TO 0 to skip verifying the certificate's name against the host 
if ( PHP_VERSION_ID >= 70700 && CURL_VERSION_ID >= 7410 ) {
curl_setopt ($ch, CURLOPT_SSL_VERIFYSTATUS, true); // SET TO false to skip verifying the SSL certificate's status
}

// $json_api_data contains the API response, in JSON format
$json_api_data = curl_exec($ch);
curl_close($ch);

// JSON data converted to a PHP array
$api_data_array = json_decode($json_api_data, true);

// Print out the array data on screen for developing / etc
var_dump($api_data_array);
</code></pre>


	        
	    </fieldset>
				
			    
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> Example API Responses (JSON format) </legend>
	        
	    <p class='bitcoin'>/<?=$ct['int_api_base_endpoint']?>market_conversion/eur/kraken-btc-usd,coinbase-eth-usd,binance_us-sol-usdc</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll json' style='width: auto; height: auto;'>
{
    "market_conversion": {
        "kraken-btc-usd": {
            "market": {
                "usd": {
                    "spot_price": 9310,
                    "24hr_vol": 92767266
                }
            },
            "conversion": {
                "eur": {
                    "spot_price": 8611.49,
                    "24hr_vol": 85807151
                }
            }
        },
        "coinbase-dai-usd": {
            "market": {
                "usdc": {
                    "spot_price": 1.01,
                    "24hr_vol": 194164
                }
            },
            "conversion": {
                "eur": {
                    "spot_price": 0.93,
                    "24hr_vol": 179463
                }
            }
        },
        "coinbase-eth-usd": {
            "market": {
                "usd": {
                    "spot_price": 208.95,
                    "24hr_vol": 25066317
                }
            },
            "conversion": {
                "eur": {
                    "spot_price": 193.27,
                    "24hr_vol": 23185648
                }
            }
        }
    },
    "market_conversion_source": "kraken-btc-eur",
    "minutes_cached": 4
}
</code></pre>

	    <p class='bitcoin' style='margin-top: 45px;'>/<?=$ct['int_api_base_endpoint']?>market_conversion/market_only/kraken-btc-usd,coinbase-eth-usd,binance_us-sol-usdc</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll json' style='width: auto; height: auto;'>
{
    "market_conversion": {
        "kraken-btc-usd": {
            "market": {
                "usd": {
                    "spot_price": 9279.4,
                    "24hr_vol": 92642284
                }
            }
        },
        "coinbase-dai-usd": {
            "market": {
                "usdc": {
                    "spot_price": 1.01,
                    "24hr_vol": 199527
                }
            }
        },
        "coinbase-eth-usd": {
            "market": {
                "usd": {
                    "spot_price": 207.85,
                    "24hr_vol": 25135615
                }
            }
        }
    },
    "minutes_cached": 4
}
</code></pre>
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/<?=$ct['int_api_base_endpoint']?>asset_list</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll json' style='width: auto; height: auto;'>
{
    "asset_list": [
        "acusd",
        "apt",
        "atlas",
        "bit",
        "btc",
        "dai",
        "eth",
        "grape",
        "hive",
        "hnt",
        "mana",
        "mkr",
        "msol",
        "ray",
        "rndr",
        "samo",
        "shdw",
        "slc",
        "slrs",
        "sol",
        "uni",
        "usdc",
        "zbc"
    ],
    "minutes_cached": 4
}
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/<?=$ct['int_api_base_endpoint']?>exchange_list</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll json' style='width: auto; height: auto;'>
{
    "exchange_list": [
        "binance",
        "binance_us",
        "bit2c",
        "bitbns",
        "bitfinex",
        "bitflyer",
        "bitmart",
        "bitmex",
        "bitmex_u20",
        "bitmex_z20",
        "bitso",
        "bitstamp",
        "btcmarkets",
        "btcturk",
        "buyucoin",
        "bybit",
        "cex",
        "coinbase",
        "coindcx",
        "coinex",
        "coingecko_btc",
        "coingecko_eth",
        "coingecko_eur",
        "coingecko_gbp",
        "coingecko_usd",
        "coinspot",
        "crypto.com",
        "gateio",
        "gemini",
        "hitbtc",
        "huobi",
        "jupiter_ag",
        "korbit",
        "kraken",
        "kucoin",
        "loopring_amm",
        "luno",
        "okcoin",
        "okex",
        "poloniex",
        "unocoin",
        "upbit",
        "wazirx",
        "zebpay"
    ],
    "minutes_cached": 4
}
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/<?=$ct['int_api_base_endpoint']?>market_list/binance</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll json' style='width: auto; height: auto;'>
{
    "market_list": {
        "binance": [
            "binance-apt-btc",
            "binance-apt-eur",
            "binance-apt-try",
            "binance-apt-usdt",
            "binance-btc-dai",
            "binance-btc-tusd",
            "binance-btc-usdc",
            "binance-btc-usdt",
            "binance-eth-btc",
            "binance-eth-dai",
            "binance-eth-tusd",
            "binance-eth-usdc",
            "binance-eth-usdt",
            "binance-hive-btc",
            "binance-mana-btc",
            "binance-mana-eth",
            "binance-mkr-btc",
            "binance-mkr-usdt",
            "binance-ray-usdt",
            "binance-sol-aud",
            "binance-sol-brl",
            "binance-sol-btc",
            "binance-sol-eth",
            "binance-sol-eur",
            "binance-sol-rub",
            "binance-sol-try",
            "binance_us-sol-usdc",
            "binance-sol-usdt",
            "binance-uni-btc",
            "binance-uni-usdt",
            "binance-usdc-usdt"
        ]
    },
    "minutes_cached": 4
}
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/<?=$ct['int_api_base_endpoint']?>conversion_list</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll json' style='width: auto; height: auto;'>
{
    "conversion_list": [
        "aed",
        "ars",
        "aud",
        "bam",
        "bdt",
        "bob",
        "brl",
        "bwp",
        "byn",
        "cad",
        "chf",
        "clp",
        "cny",
        "cop",
        "crc",
        "czk",
        "dai",
        "dkk",
        "dop",
        "egp",
        "eth",
        "eur",
        "gbp",
        "gel",
        "ghs",
        "gtq",
        "hkd",
        "huf",
        "idr",
        "ils",
        "inr",
        "irr",
        "jmd",
        "jod",
        "jpy",
        "kes",
        "krw",
        "kwd",
        "kzt",
        "lkr",
        "mad",
        "mur",
        "mwk",
        "mxn",
        "myr",
        "ngn",
        "nis",
        "nok",
        "nzd",
        "pab",
        "pen",
        "php",
        "pkr",
        "pln",
        "pyg",
        "qar",
        "ron",
        "rsd",
        "rub",
        "rwf",
        "sar",
        "sek",
        "sgd",
        "thb",
        "try",
        "tusd",
        "twd",
        "tzs",
        "uah",
        "ugx",
        "usd",
        "usdc",
        "usdt",
        "uyu",
        "ves",
        "vnd",
        "xaf",
        "xof",
        "zar",
        "zmw"
    ],
    "minutes_cached": 4
}
</code></pre>
	        
	        
	    </fieldset>
	

<?php
}
?>

