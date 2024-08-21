<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


<?php
if ( $ct['admin_area_sec_level'] == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {
?>
	
<div id='update_markets_ajax' style='margin: 1em;'>
	
	
    <button class='force_button_style input_margins' onclick='
     	
     ct_ajax_load("type=add_markets&step=1", "#update_markets_ajax", "add / remove asset markets", false, true); // Secured
     	
     '> Add / Remove Asset Markets </button>

  
  <br clear='all' /><br clear='all' />
  

          <fieldset class='subsection_fieldset'>
               
               <legend class='subsection_legend'> Portfolio Assets </legend>
               
               <!-- table_pager -->
               <div class="table_pager table_pager_portfolio_assets">

               	<span class="pagedisplay"></span> 
               	
               	<br /><br />
					&nbsp;<span class="bitcoin">Show Per Page:</span>
               	<span class="left choose_pp">
					<a href="#" data-track='5'>5</a> |
					<a href="#" data-track='10'>10</a> |
					<a href="#" data-track='25'>25</a> |
					<a href="#" data-track='50'>50</a>
				</span>
				
               	<br /><br />
				<span class="right">

					&nbsp;<span class="bitcoin">View Page:</span> <span class="prev">
						Prev
					</span>&nbsp;

					<span class="pagecount"></span>
					
					&nbsp;<span class="next">Next
					</span>
					
				</span>

               </div>
               
               <table id='portfolio_assets' border='0' cellpadding='10' cellspacing='0' class="data_table align_center" style='width: 100% !important;'>
                <thead>
                   <tr>
                    <th class="filter-match" data-placeholder="Filter Results">Asset</th>
                    <th class="filter-match" data-placeholder="Filter Results">Market Page Slug</th>
                    <th class="filter-match" data-placeholder="Filter Results">Pairs</th>
                    <th class="filter-match" data-placeholder="Filter Results">Exchanges</th>
                   </tr>
                 </thead>
                 
                <tbody>
                   
                   <?php
                   
                   $exclude_array = array(
                                          'MISCASSETS',
                                          'BTCNFTS',
                                          'ETHNFTS',
                                          'SOLNFTS',
                                          'ALTNFTS',
                                         );
                   
                   foreach ( $ct['conf']['assets'] as $asset_key => $asset_val ) {
                        
                        
                        if ( in_array($asset_key, $exclude_array) ) {
                        continue;
                        }
                        
                        
                   ?>
                   
                   <tr>
                   
                     <td><?=$asset_key?></td>
                     <td> <?=$asset_val['mcap_slug']?> </td>
                     <td> <?=sizeof($asset_val['pair'])?> </td>
                     <td> 
                     
                     <?php

                     $exchange_count = 0;
                     
                     foreach ( $asset_val['pair'] as $pair_key => $pair_val ) {
                     
                     $exchange_count = $exchange_count + sizeof($pair_val);
                          
                     }
                     
                     ?>
                     
                     <?=$exchange_count?>
                     
                     </td>
                   
                   </tr>
                   
                   <?php
                   }
                   ?>

                </tbody>
                </table>
               
           
          </fieldset>


</div>    
	
<?php
}
?>	