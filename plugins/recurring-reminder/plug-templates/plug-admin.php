<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( $admin_area_sec_level == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing plugin settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file plug-conf.php (in this plugin's subdirectory: <?=$ct['base_dir']?>/plugins/<?=$this_plug?>) with a text editor.
	
	</p>

<?php
}
else {



// Render config settings for this plugin...


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['runtime_mode']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['ui_location']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['ui_name']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['do_not_disturb']['is_notes'] = '(BLANK OUT TO DISABLE, 24 hour format MUST be used: 00:00)';


foreach ( $ct['conf']['plug_conf'][$this_plug]['do_not_disturb'] as $key => $unused ) {
$admin_render_settings['do_not_disturb']['is_subarray'][$key]['compact_margins'] = true;
$admin_render_settings['do_not_disturb']['is_subarray'][$key]['is_text'] = true;
$admin_render_settings['do_not_disturb']['is_subarray'][$key]['text_field_size'] = 6;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['reminders']['is_notes'] = 'Remind yourself about important things, every X number of days.';


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$admin_render_settings['reminders']['is_repeatable']['add_button'] = 'Add Recurring Reminder (at bottom)';

     
$admin_render_settings['reminders']['is_repeatable']['is_range']['days'] = true;

$admin_render_settings['reminders']['is_repeatable']['range_min'] = 1;

$admin_render_settings['reminders']['is_repeatable']['range_max'] = 365;

$admin_render_settings['reminders']['is_repeatable']['range_step'] = 1;

$admin_render_settings['reminders']['is_repeatable']['range_ui_prefix'] = 'Every ';

$admin_render_settings['reminders']['is_repeatable']['range_ui_suffix'] = ' Days';


$admin_render_settings['reminders']['is_repeatable']['is_textarea']['message'] = true;


// FILLED IN setting values


foreach ( $ct['conf']['plug_conf'][$this_plug]['reminders'] as $key => $val ) {
         
     foreach ( $val as $reminder_key => $tracked_val ) {
     
          if ( $reminder_key === 'days' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
               
          $admin_render_settings['reminders']['has_subarray'][$key]['is_range'][$reminder_key] = true;

          $admin_render_settings['reminders']['has_subarray'][$key]['range_min'] = 1;
               
          $admin_render_settings['reminders']['has_subarray'][$key]['range_max'] = 365;
               
          $admin_render_settings['reminders']['has_subarray'][$key]['range_step'] = 1;
               
          $admin_render_settings['reminders']['has_subarray'][$key]['range_ui_prefix'] = 'Every ';
               
          $admin_render_settings['reminders']['has_subarray'][$key]['range_ui_suffix'] = ' Days';
          
          }
          else {                                               
          $admin_render_settings['reminders']['has_subarray'][$key]['is_textarea'][$reminder_key] = true;
          }

     }
                                                                      
}


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'none';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('plug_conf|' . $this_plug, $this_plug, $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>