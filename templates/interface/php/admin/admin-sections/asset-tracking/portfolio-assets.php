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
     	
     	
     	<p class='input_margins red_dotted'>
     	
		<b class='red'>NOTE ABOUT ***STOCK MARKET*** ASSETS: ALREADY-ADDED ***AND*** SEARCH-RESULT ASSET MARKETS THAT ARE ***STOCK MARKET*** ASSETS ARE GIVEN A SUFFIX "STOCK" APPENDED TO THE STOCK TICKER VALUE, ***TO FLAG THE ASSET AS A STOCK WITHIN THIS APP*** (EG: IBM = IBMSTOCK).</b>
		
		</p>
  

          <fieldset class='subsection_fieldset'>
               
               <legend class='subsection_legend'> Portfolio Assets </legend>
               
               
               <?=$ct['gen']->table_pager_nav('portfolio_assets')?>
               
               <table id='portfolio_assets' border='0' cellpadding='10' cellspacing='0' class="data_table align_center" style='width: 100% !important;'>
                <thead>
                   <tr>
                    <th class="filter-match" data-placeholder="Filter Results">Asset Name <span class='bitcoin'>(EDITABLE soon&trade;)</span></th>
                    <th class="filter-match" data-placeholder="Filter Results">Asset Ticker</th>
                    <th class="filter-match" data-placeholder="Filter Results">MarketCap Page Slug <span class='bitcoin'>(EDITABLE soon&trade;)</span></th>
                    <th class="filter-match" data-placeholder="Filter Results">Trading Pairs</th>
                    <th class="filter-match" data-placeholder="Filter Results">Exchange Markets</th>
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
                   
                     <td><?=$asset_val['name']?></td>
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