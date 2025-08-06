<?php

$dev_status = array();

// DUMMY ENTRIES, TO PROPERLY FORMAT AS AN MULTI-DIMENSIONAL ARRAY WHEN CONVERTED TO JSON (EVEN IF NO REAL ENTRIES EXISTS)
$dev_status[] = array('dummy_entry' => true);
$dev_status[] = array('dummy_entry' => true);


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-8-10'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.01.03',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => true,
                   
                   // DESCRIPTION
                   'affected_desc' => 'v6.01.04 has been released, with FIXES for MAJOR ISSUES, related to the Currency Conversion function. IF you have any issues getting price conversions for your desired currency, upgrading this app should fix it.',

                   );


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-8-1'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.01.02',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => true,
                   
                   // DESCRIPTION
                   'affected_desc' => 'v6.01.03 has been released, with FIXES related to FALSE POSITIVE alerts in the malware scanner built-in to this app, for asset market IDs CONTAINING CRYPTO ADDRESSES, when adding new assets / markets in the admin interface (for Jupiter Aggregator, CoinGecko Terminal, etc), which caused "Invalid security token" error messages when reviewing desired market additions.',

                   );


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-7-19'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.01.01',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => true,
                   
                   // DESCRIPTION
                   'affected_desc' => 'v6.01.02 has been released. The @JupiterExchange PRICE API has been upgraded to v3, AND TOKEN API to v2. BOTH of the PREVIOUS versions of these APIs will be DEPRECIATED on August 1st, 2025. Upgrade now, if you want to avoid disruption of your Jupiter tracked markets / new asset search capabilities.',

                   );


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-7-1'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.01.00',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => true,
                   
                   // DESCRIPTION
                   'affected_desc' => 'v6.01.01 was released today, with significant BUG FIXES. This includes migration of user-selected Price Charts / News Feeds to JS localStorage (fixes app crashes when tracking MANY assets / running on same app server domain WITH OTHER APPS), Privacy Mode PROPERLY hiding the interface until everything sensitive is hidden, and other fixed issues / user experience improvements (see changelog.txt, for more details).',

                   );


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