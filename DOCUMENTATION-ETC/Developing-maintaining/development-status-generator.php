<?php

$dev_status = array();


///////////////////////////////////////////////////////////////////

// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2024-12-21'),

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.00.39',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => false,
                   
                   // DESCRIPTION
                   'affected_desc' => 'I\'m now aware of @JupiterExchange COMPLETELY SCRAPPING their V1 PRICE API, right after my HEAVY integration of their SEARCH API in v6.00.39 of Open #Crypto Tracker. 😩 I will be upgrading to their V2 PRICE API, in the upcoming v6.01.0 release.',

                   );


// END OF ENTRIES

///////////////////////////////////////////////////////////////////


$store_cached_dev_status = json_encode($dev_status, JSON_PRETTY_PRINT);


if ( $store_cached_dev_status == false || $store_cached_dev_status == null || $store_cached_dev_status == "null" ) {
echo 'ERROR encoding';
}
else {
$result = file_put_contents('../../.dev-status.json', $store_cached_dev_status);
}
    		     

?>