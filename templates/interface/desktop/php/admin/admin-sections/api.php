<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>

<div class='max_1200px_wrapper'>
	
		
		<p>This app has a built-in (internal) REST API available, so other external apps can connect to it and receive market data, including market conversion (converting the market values to their equivalent value in country fiat currencies and secondary cryptocurrency market pairs).</p>
		
		<p>To see a list of the supported assets in the API, use the endpoint: "<span class='bitcoin'>/api/asset_list</span>"</p>
		
		<p>To see a list of the supported exchanges in the API, use the endpoint: "<span class='bitcoin'>/api/exchange_list</span>"</p>
		
		<p>To see a list of the supported markets for a particular exchange in the API, use the endpoint: "<span class='bitcoin'>/api/market_list/[exchange name]</span>"</p>
		
		<p>To see a list of the supported conversion currencies (market values converted to these currency values) in the API, use the endpoint: "<span class='bitcoin'>/api/conversion_list</span>"</p>
		
		<p>To get raw market values AND also get a market conversion to a supported conversion currency (see ALL requested market values also converted to values in this currency) in the API, use the endpoint: "<span class='bitcoin'>/api/market_conversion/[conversion currency]/[exchange1-asset1-pair1],[exchange2-asset2-pair2],[exchange3-asset3-pair3]</span>"</p>
		
		<p><i>To skip conversions and just receive raw market values</i> in the API, you can use the endpoint: "<span class='bitcoin'>/api/market_conversion/market_only/[exchange1-asset1-pair1],[exchange2-asset2-pair2],[exchange3-asset3-pair3]</span>"</p>
		
		<p>For security, the API requires a key / token to access it. This key must be named "api_key", and must be sent with the "POST" data method.</p>
	
		<p>Below are <i>fully working examples <span class='bitcoin'>(including your auto-generated login authentication tokens)</span></i>, of connecting an external app with CURL command line or PHP, and Javascript.</p>
	
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> REST API Access Examples </legend>
	        
	    <p class='bitcoin'>Bash / CURL example:</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll bash' style='width: auto; height: auto;'># CURL command line example
# Add --insecure to the command, if your app's SSL certificate
# is SELF-SIGNED (not CA issued), #OR THE COMMAND WON'T WORK#

curl<?=( $htaccess_username != '' && $htaccess_password != '' ? ' -u "' . $htaccess_username . ':' . $htaccess_password . '"' : '' )?> -d "api_key=<?=$api_key?>" -X POST <?=$base_url?>api/market_conversion/eur/kraken-btc-usd,coinbase-dai-usdc,coinbase-eth-usd
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>Javascript example:</p>
	        	        	        
	                
<pre class='rounded'><code class='hide-x-scroll javascript' style='width: auto; height: auto;'>// Javascript example

<?php
if ( $htaccess_username != '' && $htaccess_password != '' ) {
?>
// Login htaccess user/pass (results sent to console log)
// (we are not looking for an API result YET, rather just logging in the htaccess user/pass)

var htaccess_login = new XMLHttpRequest();

htaccess_login.open("GET", "<?=$base_url?>api/market_conversion", true);

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

api_request.open("POST", "<?=$base_url?>api/market_conversion/eur/kraken-btc-usd,coinbase-dai-usdc,coinbase-eth-usd", true);

var params = "api_key=<?=$api_key?>";

api_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	api_request.onload = function () {
	console.log("api_request = " + api_request.responseText);
	};

<?php
if ( $htaccess_username != '' && $htaccess_password != '' ) {
?>
// Our API has a rate limit of once every <?=$ct_conf['dev']['local_api_rate_limit']?> seconds,
// so we must wait to reconnect after the htaccess authentication (<?=$ct_conf['dev']['local_api_rate_limit']?> + 1 seconds)
// ANY CONSECUTIVE CALLS #DON'T NEED# THE TIMEOUT (since htaccess is already logged in): api_request.send(params);
setTimeout(function(){ api_request.send(params); }, <?=( ($ct_conf['dev']['local_api_rate_limit'] + 1) * 1000)?>);
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
$ch = curl_init('<?=$base_url?>api/market_conversion/eur/kraken-btc-usd,coinbase-dai-usdc,coinbase-eth-usd');

$params = array('api_key' => '<?=$api_key?>');

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params) ); // Encode post data with http_build_query()

// Timeout in seconds (so we don't hang if API is not responsive)
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

<?php
if ( $htaccess_username != '' && $htaccess_password != '' ) {
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
	        
	    <p class='bitcoin'>/api/market_conversion/eur/kraken-btc-usd,coinbase-dai-usdc,coinbase-eth-usd</p>
	        	        
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
        "coinbase-dai-usdc": {
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

	    <p class='bitcoin' style='margin-top: 45px;'>/api/market_conversion/market_only/kraken-btc-usd,coinbase-dai-usdc,coinbase-eth-usd</p>
	        	        
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
        "coinbase-dai-usdc": {
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
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/api/asset_list</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll json' style='width: auto; height: auto;'>
{
    "asset_list": [
        "btc",
        "dai",
        "data",
        "eth",
        "gnt",
        "hive",
        "mana",
        "mkr",
        "myst"
    ],
    "minutes_cached": 4
}
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/api/exchange_list</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll json' style='width: auto; height: auto;'>
{
    "exchange_list": [
        "bigone",
        "binance",
        "binance_us",
        "bit2c",
        "bitbns",
        "bitfinex",
        "bitflyer",
        "bitforex",
        "bitpanda",
        "bitso",
        "bitstamp",
        "bittrex",
        "bittrex_global",
        "btcmarkets",
        "btcturk",
        "buyucoin",
        "cex",
        "coinbase",
        "coinex",
        "cryptofresh",
        "ethfinex",
        "gateio",
        "gemini",
        "hitbtc",
        "hotbit",
        "huobi",
        "idex",
        "korbit",
        "kraken",
        "kucoin",
        "livecoin",
        "localbitcoins",
        "luno",
        "okcoin",
        "okex",
        "poloniex",
        "southxchange",
        "tradeogre",
        "upbit",
        "zebpay"
    ],
    "minutes_cached": 4
}
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/api/market_list/binance</p>
	        	        
<pre class='rounded'><code class='hide-x-scroll json' style='width: auto; height: auto;'>
{
    "market_list": {
        "binance": [
            "binance-btc-usdc",
            "binance-btc-usdt",
            "binance-data-btc",
            "binance-data-eth",
            "binance-eth-btc",
            "binance-eth-usdc",
            "binance-eth-usdt",
            "binance-mana-btc",
            "binance-mana-eth"
        ]
    },
    "minutes_cached": 4
}
</code></pre>
	        
	        
	    <p class='bitcoin' style='margin-top: 45px;'>/api/conversion_list</p>
	        	        
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
        "vnd",
        "ves",
        "xaf",
        "xof",
        "zar",
        "zmw"
    ],
    "minutes_cached": 4
}
</code></pre>
	        
	        
	    </fieldset>
				
			    
	        
			    
</div> <!-- max_1200px_wrapper END -->



		    