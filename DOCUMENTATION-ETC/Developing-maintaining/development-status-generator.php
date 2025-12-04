<?php

$dev_status = array();

// DUMMY ENTRIES, TO PROPERLY FORMAT AS AN MULTI-DIMENSIONAL ARRAY WHEN CONVERTED TO JSON (EVEN IF NO REAL ENTRIES EXISTS)
$dev_status[] = array('dummy_entry' => true);
$dev_status[] = array('dummy_entry' => true);


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-12-4'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.01.06',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => true,
                   
                   // DESCRIPTION
                   'affected_desc' => 'MANY bugs (issues / errors) have been fixed in the v6.01.07 release today, along with MANY user experience improvements (see changelog.txt for more details, in the documentation folder). Additionally, the "On-Chain Stats" plugin now has Bitcoin / Solana telemetry available (UPGRADED installs require you to enable this plugin in the admin area [NEW installs have it enabled by default]).',

                   );


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-9-12'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.01.05',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => true,
                   
                   // DESCRIPTION
                   'affected_desc' => 'The error "No...data received...file /cache/secured/a*********s/ip_XX*********X.dat (aborting...)", has been fixed in the v6.01.06 release today. This was a bug in prune_access_stats(), related to NOT deleting outdated access stats properly. You can SAFELY IGNORE any old error logs like this. This release also fixes MANY issues with importing price chart backups, and includes an overhaul to the upgrade system, that now supports importing config backups SAFELY. Config / price chart backup importing must still be done MANUALLY for now, but the NEXT release will allow anybody to import backups from the "Reset / Backup & Restore" ADMIN page.',

                   );


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-8-26'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.01.05',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => true,
                   
                   // DESCRIPTION
                   'affected_desc' => 'IF you see the error "No...data received...file /cache/secured/a*********s/ip_XX*********X.dat (aborting...)", be aware this is only an issue with the prune_access_stats() function, when called during scheduled maintenance. This occurs when no stats remain, AFTER PRUNING any outdated access stats (the stats file is properly deleted in the UPCOMING [NOT yet] v6.01.06 release). You can safely ignore error logging that looks like this. Sorry! Another development status alert will be sent out, when the fix is released in v6.01.06 publicly.',

                   );


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-8-25'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.01.04',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => false,
                   
                   // DESCRIPTION
                   'affected_desc' => 'v6.01.05 has been released, which FIXES occasionally getting a blank page in the user area, related to automated CoinGecko.com multi-currency support (added in v6.01.04). API throttling has also been further refined, for better API data reliability. Other optimizations / smaller fixes / user experience improvements are also included.',

                   );


///////////////////////////////////////////////////////////////////


// NEW ENTRY
$dev_status[] = array(

                   // HUMAN-READABLE DATE, CONVERTED TO A TIMESTAMP
                   'timestamp' => strtotime('2025-8-17'),
                   
                   'very_important' => true,

                   // HIGHEST VERSION AFFECTED
                   'affected_version' => '6.01.03',

                   // DOES THIS AFFECT EARLIER VERSIONS
                   'affected_earlier' => true,
                   
                   // DESCRIPTION
                   'affected_desc' => 'v6.01.04 has been released, with FIXES for MAJOR ISSUES, related to the Currency Conversion function. IF you have any issues getting price conversions for your desired currency, upgrading this app should fix it. ADDITIONALLY, there are SIGNIFICANT User Experience improvements in this release too.',

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