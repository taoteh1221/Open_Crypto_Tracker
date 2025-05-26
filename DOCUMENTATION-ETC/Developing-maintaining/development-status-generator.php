<?php

$dev_status = array();

// DUMMY ENTRIES, TO PROPERLY FORMAT AS AN MULTI-DIMENSIONAL ARRAY WHEN CONVERTED TO JSON (EVEN IF NO REAL ENTRIES EXISTS)
$dev_status[] = array('dummy_entry' => true);
$dev_status[] = array('dummy_entry' => true);


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-5-26'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.00.41',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => true,
                   
                   // DESCRIPTION
                   'affected_desc' => 'The @JupiterExchange PRICE API has been upgraded to v2, in the v6.01.0 release that was made public today. This brings Jupiter market support BACK ONLINE (as they disabled the v1 PRICE API awhile ago).',

                   );


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-2-4'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.00.40',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => false,
                   
                   // DESCRIPTION
                   'affected_desc' => 'There is an UPSTREAM bug in Embedded Chromium (for Windows), causing JavaScript dialogue boxes to be suppressed in the DESKTOP EDITION of this app, FOR WINDOWS 11 USERS. v6.00.41 has been released today, with a fix for this issue.',

                   );


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2024-11-28'),
                   
                   'very_important' => false,

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