<?php


$dev_status = array(
                   'timestamp' => time(),
                   'affected_version' => '6.00.39',
                   'affected_earlier' => false,
                   'affected_desc' => 'I\'m now aware of @JupiterExchange COMPLETELY SCRAPPING their V1 PRICE API, right after my HEAVY integration of their SEARCH API in v6.00.39 of Open #Crypto Tracker. 😩 I will be upgrading to their V2 PRICE API, in the upcoming v6.01.0 release.',
                   );
                   

$store_cached_dev_status = json_encode($dev_status, JSON_PRETTY_PRINT);


if ( $store_cached_dev_status == false || $store_cached_dev_status == null || $store_cached_dev_status == "null" ) {
echo 'ERROR encoding';
}
else {
$result = file_put_contents('../../.dev-status.json', $store_cached_dev_status);
}
    		     

?>