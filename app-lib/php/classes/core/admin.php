<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



class ct_admin {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;

var $ct_array = array();

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function update_config() {
   
   global $ct;
   
   
       // Check validity of config array and name / admin hashed nonce / strict 2FA setup, return false if checks fail
       if ( !isset($_POST['conf_id']) || !isset($_POST['interface_id']) || !is_array($_POST[ $_POST['conf_id'] ]) || !$ct['gen']->pass_sec_check($_POST['admin_hashed_nonce'], $_POST['interface_id']) || !$ct['gen']->valid_2fa('strict') ) {
       return false;
       }
   
   
   // Update the corrisponding admin config section
   $ct['conf'][ $_POST['conf_id'] ] = $_POST[ $_POST['conf_id'] ];
   
   // Refresh the cached config (save to disk, AND USE THE NEW CONFIG)
   // (refresh_cached_ct_conf() contains checks / fallbacks)
   $ct['conf'] = $ct['cache']->refresh_cached_ct_conf($ct['conf']);
   
   return true; // If we got this far, we're good to go
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function settings_form_fields($conf_id, $interface_id, $render_params=false) {
        
   global $ct, $update_admin_conf_success, $update_admin_conf_error;
   
  
   ?>
   
   <div class='pretty_text_fields'>
   
	
	<form name='update_config' id='update_config' action='admin.php?iframe=<?=$ct['gen']->admin_hashed_nonce('iframe_' . $interface_id)?>&section=<?=$interface_id?>&refresh=none' method='post'>
     
     <?php
     foreach( $ct['conf'][$conf_id] as $key => $val ) {
   
         if ( is_array($render_params) && is_array($render_params[$key]['radio']) ) {
         ?>
         
         <p>
         
         <b class='blue'><?=$ct['gen']->key_to_name($key)?>:</b> &nbsp; 
         
              <?php
              foreach( $render_params[$key]['radio'] as $radio_key => $radio_val ) {
                   
                   // If it's flagged as an associative array
                   if ( $radio_key === 'assoc' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                        
                        foreach( $render_params[$key]['radio']['assoc'] as $assoc_val ) {
                        ?>
                        
                        <input type='radio' name='<?=$conf_id?>[<?=$key?>]' value='<?=$assoc_val['key']?>' <?=( $val == $assoc_val['key'] ? 'checked' : '' )?> /> <?=$ct['gen']->key_to_name($assoc_val['val'])?> &nbsp;
                        
                        <?php
                        }
                        
                   }
                   else {
                   ?>
                   
                   <input type='radio' name='<?=$conf_id?>[<?=$key?>]' value='<?=$radio_val?>' <?=( $val == $radio_val ? 'checked' : '' )?> /> <?=$ct['gen']->key_to_name($radio_val)?> &nbsp;
                   
                   <?php
                   }
              
              }
              
              
              if ( isset($render_params[$key]['notes']) ) {
              ?>
              <br /><span class='bitcoin random_tip' style='line-height: 1.7em;'><?=$render_params[$key]['notes']?></span>
              <?php
              }
              ?>
              
              </p>
         
         <?php
         }
         elseif ( is_array($render_params) && is_array($render_params[$key]['select']) ) {
         ?>
         
         <p>
         
         <b class='blue'><?=$ct['gen']->key_to_name($key)?>:</b> &nbsp; 
         
         <select name='<?=$conf_id?>[<?=$key?>]'>
         
              <?php
              foreach( $render_params[$key]['select'] as $option_key => $option_val ) {
                   
                   // If it's flagged as an associative array
                   if ( $option_key === 'assoc' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                        
                        foreach( $render_params[$key]['select']['assoc'] as $assoc_val ) {
                        ?>
                        
                        <option value='<?=$assoc_val['key']?>' <?=( $val == $assoc_val['key'] ? 'selected' : '' )?>> <?=$ct['gen']->key_to_name($assoc_val['val'])?> </option> 
                        
                        <?php
                        }
                        
                   }
                   else {
                   ?>
                   
                   <option value='<?=$option_val?>' <?=( $val == $option_val ? 'selected' : '' )?>> <?=$ct['gen']->key_to_name($option_val)?> </option> 
                   
                   <?php
                   }
                   
              }
              ?>
              
         </select>
         
              <?php
              if ( isset($render_params[$key]['notes']) ) {
              ?>
              <br /><span class='bitcoin random_tip' style='line-height: 1.7em;'><?=$render_params[$key]['notes']?></span>
              <?php
              }
              ?>
              
              </p>
         
         <?php
         }
         elseif ( is_array($render_params) && isset($render_params[$key]['textarea']) ) {
         ?>
         
         <p>
         
         <b class='blue'><?=$ct['gen']->key_to_name($key)?>:</b> <br /> 
         
         <textarea data-autoresize name='<?=$conf_id?>[<?=$key?>]' style='height: auto; width: 100%;'><?=$val?></textarea>
         
              <?php
              if ( isset($render_params[$key]['notes']) ) {
              ?>
              <span class='bitcoin random_tip' style='line-height: 1.7em;'><?=$render_params[$key]['notes']?></span>
              <?php
              }
              ?>
              
              </p>
         
         <?php
         }
         else {
              
              if ( isset($render_params[$key]['trim_value']) ) {
              $val = trim($val);
              }
              
         ?>
     
         <p>
         
         <b class='blue'><?=$ct['gen']->key_to_name($key)?>:</b> &nbsp; 
         
         <input type='text' name='<?=$conf_id?>[<?=$key?>]' value='<?=$val?>' <?=( isset($render_params[$key]['text_field_size']) ? ' size="' . $render_params[$key]['text_field_size'] . '"' : '' )?> />
     
              <?php
              if ( isset($render_params[$key]['notes']) ) {
              ?>
              <br /><span class='bitcoin random_tip' style='line-height: 1.7em;'><?=$render_params[$key]['notes']?></span>
              <?php
              }
              ?>
              
              </p>
         
         <?php   
         }
         
     }
     ?>
     
	<input type='hidden' name='conf_id' id='conf_id' value='<?=$conf_id?>' />
     
	<input type='hidden' name='interface_id' id='interface_id' value='<?=$interface_id?>' />
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct['gen']->admin_hashed_nonce($interface_id)?>' />
	
	<?=$ct['gen']->input_2fa('strict')?>
			
	<p><input type='submit' value='Save <?=$ct['gen']->key_to_name($interface_id)?> Settings' /></p>
	
	</form>
	
	
	 <?php
	 if ( $update_admin_conf_success != null ) {
	 ?>
	 <div style='min-height: 1em;'></div>
	 <div class='green green_dotted' style='font-weight: bold;'><?=$update_admin_conf_success?></div>
	 <?php
	 }
	 elseif ( $update_admin_conf_error != null ) {
	 ?>
	 <div style='min-height: 1em;'></div>
	 <div class='red red_dotted' style='font-weight: bold;'><?=$update_admin_conf_error?></div>
	 <?php
	 }
	 ?>
     
     
   </div>
   
   <?php
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>