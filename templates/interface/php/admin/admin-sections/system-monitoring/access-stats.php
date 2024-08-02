<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


  <h3 class='bitcoin'>(<?=$ct['conf']['power']['access_stats_delete_old']?> Day Report)</h3>
    		
   <ul style='margin-top: 25px; font-weight: bold;'>
	
	<li class='bitcoin' style='font-weight: bold;'>You can adjust how long to store access stats for, in the Admin -> Power User section (with the "Access Stats Delete Old" setting).</li>
	
	<li class='bitcoin' style='font-weight: bold;'>Hover your mouse over the browser name, to see the full user agent string.</li>
	
   </ul>		
  
  
   <div id='access_stats_data'>
   
		<fieldset class='subsection_fieldset'>
		
		<legend class='subsection_legend'> <strong>Loading access stats...</strong> </legend>
		<img class='' src="templates/interface/media/images/auto-preloaded/loader.gif" height='<?=round($set_ajax_loading_size * 50)?>' alt="" style='vertical-align: middle;' />
		</fieldset>
   
   </div>
					
					
					<script>
	
					// Load AFTER page load, for quick interface loading
					$(document).ready(function(){
						
						
						$("#access_stats_data").load("ajax.php?token=" + Base64.decode(gen_csrf_sec_token) + "&type=access_stats&theme=<?=$ct['sel_opt']['theme_selected']?>", function(responseTxt, statusTxt, xhr){
							
							if( statusTxt == "success" ) {

                                   sorting_generic_tables(true);
                                   
                                   paged_tablesort_sizechange();
                                   
                                       // Resize admin iframes after adding repeatable elements
                                       admin_iframe_dom.forEach(function(iframe) {
                                       iframe_size_adjust(iframe);
                                       });
                                       
							}
							else if( statusTxt == "error" ) {
								
							$("#access_stats_data").html("<fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong class='bitcoin'>ERROR loading access stats...</strong> </legend><span class='red'>" + xhr.status + ": " + xhr.statusText + "</span></fieldset>");
								
							}
						
						});
	
					});
						
					</script>


		    