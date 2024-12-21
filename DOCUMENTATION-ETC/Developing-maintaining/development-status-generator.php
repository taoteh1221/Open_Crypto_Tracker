<?php

$dev_status = array();

// DUMMY ENTRIES, TO PROPERLY FORMAT AS AN MULTI-DIMENSIONAL ARRAY WHEN CONVERTED TO JSON (EVEN IF NO REAL ENTRIES EXISTS)
$dev_status[] = array('dummy_entry' => true);
$dev_status[] = array('dummy_entry' => true);


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

// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2024-11-28'),

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.00.40',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => false,
                   
                   // DESCRIPTION
                   'affected_desc' => 'You can now download your price chart and app config backups, on the "Reset / Backup & Restore" ADMIN page. In an UPCOMING (unknown when) release, you will be able to RESTORE these backups on the same admin page.',

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