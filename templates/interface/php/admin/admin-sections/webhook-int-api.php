<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */

$webhook_base_endpoint = ( $ct['app_edition'] == 'server' || $ct['app_container'] == 'phpbrowserbox' ? 'hook/' : 'web-hook.php?webhook_params=' );

$api_base_endpoint = ( $ct['app_edition'] == 'server' || $ct['app_container'] == 'phpbrowserbox' ? 'api/' : 'internal-api.php?data_set=' );

?>

	<p>
	
	<b class='blue'>WEBHOOK DOCUMENTATION / KEYS:</b> <br /><br />
	
	Webhooks are added via the plugin system built into this app (when a specific plugin's "runtime_mode" is set to "webhook" OR "all").
	<br /><br />
	
	See <a href='https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/DOCUMENTATION-ETC/PLUGINS-README.txt' target='_blank'>/DOCUMENTATION-ETC/PLUGINS-README.txt</a> for more information on plugin creation / development.
	<br /><br />
	
	You can include ADDITIONAL PARAMETERS *AFTER* THE WEBOOK KEY, USING FORWARD SLASHES TO DELIMIT THEM: <br /><br />
	
	<b class='bitcoin'><?=$ct['base_url']?><?=$webhook_base_endpoint?>WEBHOOK_KEY/PARAM1/PARAM2/PARAM3/ETC</b>
	<br /><br />

     These parameters are then automatically put into a PHP array named: <b class='bitcoin'>$webhook_params</b>
     <br /><br />
     
     The webhook key is also available, in the auto-created variable: <b class='bitcoin'>$webhook_key</b>
     <br /><br />
     
     See the Plugins admin area, for additional settings / documentation related to each webhook plugin listed below.
	
	</p>
	
<fieldset class='subsection_fieldset'>
<legend class='subsection_legend'> Active Webhook Plugins  </legend>
<?php

if ( !isset($activated_plugins['webhook']) ) {
echo '<p><span class="black">None</span></p>';
}
	
foreach ( $activated_plugins['webhook'] as $plugin_key => $plugin_init ) {
        		
$webhook_plug = $plugin_key;
        	
    if ( file_exists($plugin_init) && isset($int_webhooks[$webhook_plug]) ) {
    ?>
       
     <p>
     
     <b class='blue'>Webhook endpoint for "<?=$plug_conf[$webhook_plug]['ui_name']?>" plugin:</b> <br /><br />
     
     <b class='bitcoin'><?=$ct['base_url']?><?=$webhook_base_endpoint?><?=$ct['gen']->nonce_digest($webhook_plug, $int_webhooks[$webhook_plug] . $webhook_master_key)?></b>
     
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
		
		<b class='bitcoin'>/<?=$api_base_endpoint?>asset_list</b></p>
		
		<p>To see a list of the supported exchanges in the API, use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$api_base_endpoint?>exchange_list</b></p>
		
		<p>To see a list of the supported markets for a particular exchange in the API, use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$api_base_endpoint?>market_list/[exchange name]</b></p>
		
		<p>To see a list of the supported conversion currencies (market values converted to these currency values) in the API, use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$api_base_endpoint?>conversion_list</b></p>
		
		<p>To get raw market values AND also get a market conversion to a supported conversion currency (see ALL requested market values also converted to values in this currency) in the API, use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$api_base_endpoint?>market_conversion/[conversion currency]/[exchange1-asset1-pair1],[exchange2-asset2-pair2],[exchange3-asset3-pair3]</b></p>
		
		<p><i>To skip conversions and just receive raw market values</i> in the API, you can use the endpoint: <br /><br />
		
		<b class='bitcoin'>/<?=$api_base_endpoint?>market_conversion/market_only/[exchange1-asset1-pair1],[exchange2-asset2-pair2],[exchange3-asset3-pair3]</b></p>
		
		<p>For security, the API requires a key / token to access it. This key must be named "api_key", and must be sent with the "POST" data method.</p>
	
		<p>Below are <i>fully working examples <span class='bitcoin'>(including your auto-generated login authentication tokens)</span></i>, of connecting an external app with CURL command line or PHP, and Javascript.</p>
	
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> REST API Access Examples </legend>
	        
	    <p class='bitcoin'>Bash / CURL example:</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll bash' style='width: auto; height: auto;'># CURL command line example
# Add --insecure to the command, if your app's SSL certificate
# is SELF-SIGNED (not CA issued), #OR THE COMMAND WON'T WORK#
# WINDOWS USERS: REMOVE THE "Invoke-WebRequest" CURL ALIAS FIRST: Remove-item alias:curl

curl<?=( isset($htaccess_username) && isset($htaccess_password) && $htaccess_username != '' && $htaccess_password != '' ? ' -u "' . $htaccess_username . ':' . $htaccess_password . '"' : '' )?> -d "api_key=<?=$int_api_key?>" -X POST <?=$ct['base_url']?><?=$api_base_endpoint?>market_conversion/eur/kraken-btc-usd,coinbase-dai-usd,coinbase-eth-usd
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>Javascript example:</p>
	        	        	        
	                
<pre class='rounded'><code class='hide-x-scroll javascript' style='width: auto; height: auto;'>// Javascript example

<?php
if ( isset($htaccess_username) && isset($htaccess_password) && $htaccess_username != '' && $htaccess_password != '' ) {
?>
// Login htaccess user/pass (results sent to console log)
// (we are not looking for an API result YET, rather just logging in the htaccess user/pass)

var htaccess_login = new XMLHttpRequest();

htaccess_login.open("GET", "<?=$ct['base_url']?><?=$api_base_endpoint?>market_conversion", true);

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

api_request.open("POST", "<?=$ct['base_url']?><?=$api_base_endpoint?>market_conversion/eur/kraken-btc-usd,coinbase-dai-usd,coinbase-eth-usd", true);

var params = "api_key=<?=$int_api_key?>";

api_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	api_request.onload = function () {
	console.log("api_request = " + api_request.responseText);
	};

<?php
if ( isset($htaccess_username) && isset($htaccess_password) && $htaccess_username != '' && $htaccess_password != '' ) {
?>
// Our API has a rate limit of once every <?=$ct['conf']['power']['local_api_rate_limit']?> seconds,
// so we must wait to reconnect after the htaccess authentication (<?=$ct['conf']['power']['local_api_rate_limit']?> + 1 seconds)
// ANY CONSECUTIVE CALLS #DON'T NEED# THE TIMEOUT (since htaccess is already logged in): api_request.send(params);
setTimeout(function(){ api_request.send(params); }, <?=( ($ct['conf']['power']['local_api_rate_limit'] + 1) * 1000)?>);
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
	        	        
	        
<pre class='rounded'><code class='hide-x-scroll php' style='width: auto; height: auto;'>// CURL PHP example (requires CURL PHP module)

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
$ch = curl_init('<?=$ct['base_url']?><?=$api_base_endpoint?>market_conversion/eur/kraken-btc-usd,coinbase-dai-usd,coinbase-eth-usd');

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
	        
	    <p class='bitcoin'>/<?=$api_base_endpoint?>market_conversion/eur/kraken-btc-usd,coinbase-dai-usd,coinbase-eth-usd</p>
	        	        
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

	    <p class='bitcoin' style='margin-top: 45px;'>/<?=$api_base_endpoint?>market_conversion/market_only/kraken-btc-usd,coinbase-dai-usd,coinbase-eth-usd</p>
	        	        
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
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/<?=$api_base_endpoint?>asset_list</p>
	        	        
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
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/<?=$api_base_endpoint?>exchange_list</p>
	        	        
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
        "bitpanda",
        "bitso",
        "bitstamp",
        "bittrex",
        "bittrex_global",
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
        "ethfinex",
        "gateio",
        "gemini",
        "hitbtc",
        "huobi",
        "jupiter_ag",
        "korbit",
        "kraken",
        "kucoin",
        "liquid",
        "loopring_amm",
        "luno",
        "okcoin",
        "okex",
        "poloniex",
        "southxchange",
        "unocoin",
        "upbit",
        "wazirx",
        "zebpay"
    ],
    "minutes_cached": 4
}
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/<?=$api_base_endpoint?>market_list/binance</p>
	        	        
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
            "binance-sol-usdc",
            "binance-sol-usdt",
            "binance-uni-btc",
            "binance-uni-usdt",
            "binance-usdc-usdt"
        ]
    },
    "minutes_cached": 4
}
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/<?=$api_base_endpoint?>conversion_list</p>
	        	        
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
				
			    
	        