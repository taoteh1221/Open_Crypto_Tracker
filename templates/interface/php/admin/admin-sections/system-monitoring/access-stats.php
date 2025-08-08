<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


  <h3 class='bitcoin'>(<?=$ct['conf']['power']['access_stats_delete_old']?> Day Report)</h3>
    		
   <ul style='margin-top: 25px; font-weight: bold;'>
	
	<li class='bitcoin' style='font-weight: bold;'>You can adjust how long to store access stats for, in the Admin -> Power User section (with the "Access Stats Delete Old" setting).</li>
	
	<li class='bitcoin' style='font-weight: bold;'>The DEFAULT sorting (on INITIAL load) is "Last Visit Time" first, AND "Total Visits" second (both descending).</li>
	
	<li class='bitcoin' style='font-weight: bold;'>Clicking the "Show" button again will refresh the stats, and show any newer data (if available).</li>
	
	<li class='bitcoin' style='font-weight: bold;'>Hover your mouse over the browser name, to see the full user agent string.</li>
	
	<li class='bitcoin' style='font-weight: bold;'>All visit timestamps are UTC time (Coordinated Universal Time).</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>Current UTC time: <span class='utc_timestamp red'></span></li>	
	
	<li class='bitcoin' style='font-weight: bold;'>YOUR IP Address: <span class='red'><?=$ct['remote_ip']?></span></li>	
	
   </ul>		
  
  
   <p>
  
   <button class='load_bundled_access_stats_onclick force_button_style' style='margin: 1em;'>Show / Refresh BUNDLED Stats</button>
   
   &nbsp; &nbsp; &nbsp; 
   
   <button class='load_ip_access_stats_onclick force_button_style' style='margin: 1em;'>Show / Refresh PER IP ADDRESS Stats</button>
	
   </p>
  
  
   <div id='access_stats_data'></div>
					


		    