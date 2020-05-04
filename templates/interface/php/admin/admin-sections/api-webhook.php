<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='max_1350px_wrapper'>
	
				<h3 class='align_center'>API / Webhook</h3>
		
		
		<p>This app has a built-in (internal) REST API available, so other external apps can connect to it and receive market data, including conversion to country fiat currencies and secondary cryptocurrency market pairings.</p>
		
		<?php
		
		$supported_primary_currency_count = 0;
		foreach ( $app_config['power_user']['bitcoin_currency_markets'] as $key => $unused ) {
		$supported_primary_currency_list .= strtoupper($key) . ' / ';
		$supported_primary_currency_count = $supported_primary_currency_count + 1;
		}
		$supported_primary_currency_list = trim($supported_primary_currency_list);
		$supported_primary_currency_list = rtrim($supported_primary_currency_list,'/');
		$supported_primary_currency_list = trim($supported_primary_currency_list);
		
		?>
		
		<p><span class='bitcoin'><?=$supported_primary_currency_count?> conversion pairings are supported:</span> <?=$supported_primary_currency_list?>.</p>
		
		<p><i>To skip conversions and just receive raw market values</i>, you can use "<span class='bitcoin'>/api/market_conversion/market_only/</span>" INSTEAD OF a conversion value (like "<span class='bitcoin'>/api/market_conversion/eur/</span>", "<span class='bitcoin'>/api/market_conversion/usd/</span>", "<span class='bitcoin'>/api/market_conversion/gbp/</span>", etc).</p>
	
		<p>Below are <i>fully working examples <span class='bitcoin'>(including your auto-generated login authentication tokens)</span></i>, of connecting an external app with CURL command line or PHP, and Javascript.</p>
	
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> REST API Access Examples </legend>
	        
	        
	        	        
<pre><code class='hide-x-scroll bash rounded' style='width: auto; height: auto;'># CURL command line example
# Add --insecure to the command, if your app's SSL certificate
# is SELF-SIGNED (not CA issued), #OR THE COMMAND WON'T WORK#

curl<?=( $htaccess_username != '' && $htaccess_password != '' ? ' -u "' . $htaccess_username . ':' . $htaccess_password . '"' : '' )?> -d "api_key=<?=$api_key?>" -X POST <?=$base_url?>api/market_conversion/eur/kraken-btc-usd,coinbase-dai-usdc,coinbase-eth-usd
</code></pre>
	        
	        
	                
<pre><code class='hide-x-scroll javascript rounded' style='width: auto; height: auto;'>// Javascript example

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
// Our API has a rate limit of once every <?=$app_config['power_user']['local_api_rate_limit']?> seconds,
// so we must wait to reconnect after the htaccess authentication (<?=$app_config['power_user']['local_api_rate_limit']?> + 1 seconds)
// ANY CONSECUTIVE CALLS #DON'T NEED# THE TIMEOUT (since htaccess is already logged in): api_request.send(params);
setTimeout(function(){ api_request.send(params); }, <?=( ($app_config['power_user']['local_api_rate_limit'] + 1) * 1000)?>);
<?php
}
else {
?>
api_request.send(params);
<?php
}
?>
</code></pre>
	        
	        
	        
	        
<pre><code class='hide-x-scroll php rounded' style='width: auto; height: auto;'>// CURL PHP example (requires CURL PHP module)

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
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

<?php
if ( $htaccess_username != '' && $htaccess_password != '' ) {
?>
// Htaccess login
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_USERPWD, '<?=($htaccess_username . ':' . $htaccess_password)?>');

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
<?php
}
?>
</code></pre>


	        
	    </fieldset>
				
			    
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> Example API Response (JSON format) </legend>
	        
	    
	        	        
<pre><code class='hide-x-scroll json rounded' style='width: auto; height: auto;'>
{
    "market_conversion": {
        "kraken-btc-usd": {
            "market": {
                "usd": {
                    "spot_price": 9097.6,
                    "24hr_volume": 68069122
                }
            },
            "conversion": {
                "eur": {
                    "spot_price": 8291.94,
                    "24hr_volume": 62041081
                }
            }
        },
        "coinbase-dai-usdc": {
            "market": {
                "usdc": {
                    "spot_price": 1.01,
                    "24hr_volume": 586010
                }
            },
            "conversion": {
                "eur": {
                    "spot_price": 0.92,
                    "24hr_volume": 533920
                }
            }
        },
        "coinbase-eth-usd": {
            "market": {
                "usd": {
                    "spot_price": 216.43,
                    "24hr_volume": 22287471
                }
            },
            "conversion": {
                "eur": {
                    "spot_price": 197.26,
                    "24hr_volume": 20313745
                }
            }
        }
    },
    "market_conversion_source": "kraken-btc-eur"
}

</code></pre>
	        
	        
	    </fieldset>
				
			    
	        
			    
</div> <!-- max_1350px_wrapper END -->



		    