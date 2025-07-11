<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>System Monitoring</strong> </legend>
    
	   
    <ul>  
    
        <li><a href='admin.php?iframe_nonce=<?=$ct['sec']->admin_nonce('iframe_system_stats')?>&parent=system_monitoring&subsection=system_stats'>System Stats</a></li>
        
        <li><a href='admin.php?iframe_nonce=<?=$ct['sec']->admin_nonce('iframe_access_stats')?>&parent=system_monitoring&subsection=access_stats'>Access Stats</a></li>
        
        <li><a href='admin.php?iframe_nonce=<?=$ct['sec']->admin_nonce('iframe_app_logs')?>&parent=system_monitoring&subsection=app_logs'>App Logs</a></li>
        
	</ul>
    	
	
	</fieldset>


<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {

section_ids['<?=$_GET['section']?>'] = window.parent.location.href.split('#')[1];

// Change page title

$('#' + section_ids['<?=$_GET['section']?>'] + ' h2.page_title', window.parent.document).html(parent.original_page_title[ section_ids['<?=$_GET['section']?>'] ]); // Restore previous page title
     
});

</script>