<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>APIs</strong> </legend>
    
	   
    <ul>  
    
        <li><a href='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_ext_apis')?>&parent=apis&subsection=ext_apis'>External APIs</a></li>
        
        <li><a href='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_webhook_int_api')?>&parent=apis&subsection=webhook_int_api'>Internal API / Webhook</a></li>
        
	</ul>
    	
	
	</fieldset>


<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {


var section_id = window.parent.location.href.split('#')[1];

// Change page title

$('#' + section_id + ' h2.page_title', window.parent.document).html(parent.original_page_title[section_id]); // Restore previous page title
     
});

</script>