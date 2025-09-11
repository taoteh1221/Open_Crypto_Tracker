<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( $ct['admin_area_sec_level'] == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing plugin settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file plug-conf.php (in this plugin's subdirectory: <?=$ct['base_dir']?>/plugins/<?=$this_plug?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {
?>
	
<!-- chart_bootstrapping START -->

<fieldset class='subsection_fieldset'>

<legend class='subsection_legend'> Price Chart Bootstrapping </legend>

<p>SKIPS system / all light charts, AND any charts for assets NOT in the DEFAULT config. This allows developers to easily make a publicly available download archive, for end-users to bootstrap (import) some price chart data into new installations.</p>

<?php

$backup_files = $ct['gen']->sort_files($ct['base_dir'] . '/cache/secured/backups', 'zip', 'desc');


if ( is_array($backup_files) && sizeof($backup_files) > 0 ) {

$backup_links = array();

     foreach( $backup_files as $back_file ) {
     
          if ( preg_match("/charts-bootstrapping/i", $back_file) ) {
          $backup_links['charts-bootstrapping'][] = $back_file;
          }
     
     }

     
     if ( is_array($backup_links['charts-bootstrapping']) ) {
     $backup_count_max = sizeof($backup_links['charts-bootstrapping']);
     }

}


?>	
               
               <?=$ct['gen']->table_pager_nav('chart_bootstrapping')?>
               
               <table id='chart_bootstrapping' border='0' cellpadding='10' cellspacing='0' class="data_table no_default_sort align_center" style='width: 100% !important;'>
                <thead>
                   <tr>
                    <th class="filter-match" data-placeholder="Filter Results">Chart Bootstrapping Backups</th>
                   </tr>
                 </thead>
                 
                <tbody>
                   
                   <?php
                   
                   if ( isset($backup_count_max) ) {
                        
                      $loop = 0;
                      while ( $loop < $backup_count_max ) {
                        
                   ?>
                   
                   <tr>
                   
                     <td><?=( isset($backup_links['charts-bootstrapping'][$loop]) ? '<a href="download.php?backup='. $backup_links['charts-bootstrapping'][$loop] . '" target="_BLANK">' . $backup_links['charts-bootstrapping'][$loop] . '</a>' : '' )?></td>
                   
                   </tr>
                   
                   <?php
                      
                      $loop = $loop + 1;
                      }
                      
                   }
                   else {
                   ?>
                   
                   <tr>
                   
                     <td class='bitcoin'>No backups yet, please check back later.</td>
                     <td class='bitcoin'></td>
                   
                   </tr>
                   
                   <?php
                   }
                   ?>

                </tbody>
                </table>

	
</fieldset>

<?php

// Render config settings for this plugin...


////////////////////////////////////////////////////////////////////////////////////////////////





////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
// SHOULD BE COMMA-DELIMITED: 'iframe_reset_backup_restore,iframe_apis'
//$ct['admin_render_settings']['is_refresh_admin'] = 'none';


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>