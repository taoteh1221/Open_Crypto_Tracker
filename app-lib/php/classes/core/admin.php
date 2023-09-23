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
   
   
   function settings_form_fields($conf_id, $interface_id, $render_params=false) {
        
   global $ct, $update_admin_conf_success, $update_admin_conf_error;
   
	 
	 // Set which OTHER admin pages should be refreshed AFTER submission of this section's setting changes
	 if ( is_array($render_params) && isset($render_params['is_refresh_admin']) ) {
	 $refresh_admin_sections = $render_params['is_refresh_admin'];
	 }
	 else {
	 $refresh_admin_sections = 'none';
	 }
	 ?>
	 
	
	<p class='red red_dotted'>
	
	No input validation has been added yet (only input sanitization is active), SO BE CAREFUL TO ADD CORRECTLY-FORMATTED VALUES! If anything gets corrupted, delete the file '/cache/secured/ct_conf_XXXXXXXXXXXXX.dat' in the app directory, to RESET to defaults.
	
	</p>
	
	
	<div style='min-height: 1em;'></div>

	 
	 <?php
	 
  
	 if ( $update_admin_conf_success != null ) {
	 ?>
	 <div class='green green_dotted' style='font-weight: bold;'><?=$update_admin_conf_success?></div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 elseif ( $update_admin_conf_error != null ) {
	 ?>
	 <div class='red red_dotted' style='font-weight: bold;'><?=$update_admin_conf_error?></div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 ?>
	 
	 
   <div class='pretty_text_fields'>
   
	
	<form name='update_config' id='update_config' action='admin.php?iframe=<?=$ct['gen']->admin_hashed_nonce('iframe_' . $interface_id)?>&section=<?=$interface_id?>&refresh=<?=$refresh_admin_sections?>' method='post'>
     
     <?php
     foreach( $ct['conf'][$conf_id] as $key => $val ) {
   
         if ( is_array($render_params) && is_array($render_params[$key]['is_radio']) ) {
         ?>
         
         <p>
         
         <b class='blue'><?=$ct['gen']->key_to_name($key)?>:</b> &nbsp; 
         
              <?php
              foreach( $render_params[$key]['is_radio'] as $radio_key => $radio_val ) {
                   
                   // If it's flagged as an associative array
                   if ( $radio_key === 'assoc' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                        
                        foreach( $render_params[$key]['is_radio']['assoc'] as $assoc_val ) {
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
              
              
              if ( isset($render_params[$key]['is_notes']) ) {
              ?>
              <br /><span class='bitcoin random_tip' style='line-height: 1.7em;'><?=$render_params[$key]['is_notes']?></span>
              <?php
              }
              ?>
              
              </p>
         
         <?php
         }
         elseif ( is_array($render_params) && is_array($render_params[$key]['is_select']) ) {
         ?>
         
         <p>
         
         <b class='blue'><?=$ct['gen']->key_to_name($key)?>:</b> &nbsp; 
         
         <select name='<?=$conf_id?>[<?=$key?>]'>
         
              <?php
              foreach( $render_params[$key]['is_select'] as $option_key => $option_val ) {
                   
                   // If it's flagged as an associative array
                   if ( $option_key === 'assoc' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                        
                        foreach( $render_params[$key]['is_select']['assoc'] as $assoc_val ) {
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
              if ( isset($render_params[$key]['is_notes']) ) {
              ?>
              <br /><span class='bitcoin random_tip' style='line-height: 1.7em;'><?=$render_params[$key]['is_notes']?></span>
              <?php
              }
              ?>
              
              </p>
         
         <?php
         }
         elseif ( is_array($render_params) && isset($render_params[$key]['is_textarea']) ) {
         ?>
         
         <p>
         
         <b class='blue'><?=$ct['gen']->key_to_name($key)?>:</b> <br /> 
         
         <textarea data-autoresize name='<?=$conf_id?>[<?=$key?>]' style='height: auto; width: 100%;'><?=$val?></textarea>
         
              <?php
              if ( isset($render_params[$key]['is_notes']) ) {
              ?>
              <span class='bitcoin random_tip' style='line-height: 1.7em;'><?=$render_params[$key]['is_notes']?></span>
              <?php
              }
              ?>
              
              </p>
         
         <?php
         }
         else {
              
              if ( isset($render_params[$key]['is_trim']) ) {
              $val = trim($val);
              }
              
         ?>
              
              <?php
              if ( isset($render_params[$key]['is_password']) ) {
              ?>
                   
              <div class="password-container">
                   
              <?php
              }
              ?>
     
         <p>
              
              <b class='blue'><?=$ct['gen']->key_to_name($key)?>:</b> &nbsp; <input type='<?=( isset($render_params[$key]['is_password']) ? 'password' : 'text' )?>' data-name="<?=md5($conf_id . $key)?>" name='<?=$conf_id?>[<?=$key?>]' value='<?=$val?>' <?=( isset($render_params[$key]['text_field_size']) ? ' size="' . $render_params[$key]['text_field_size'] . '"' : '' )?> />
              
              <?php
              if ( isset($render_params[$key]['is_notes']) ) {
              ?>
     
              <br /><span class='bitcoin random_tip' style='line-height: 1.7em;'><?=$render_params[$key]['is_notes']?></span>
                   
              <?php
              }
              ?>
              
              </p>
         
         <?php   
              if ( isset($render_params[$key]['is_password']) ) {
              ?>
                   
                  <i class="gg-eye-alt toggle-show-password" data-name="<?=md5($conf_id . $key)?>"></i>
                      
              </div>
                   
              <?php
              }
                   
                   
         }
         
     }
     ?>
     
	<input type='hidden' name='conf_id' id='conf_id' value='<?=$conf_id?>' />
     
	<input type='hidden' name='interface_id' id='interface_id' value='<?=$interface_id?>' />
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct['gen']->admin_hashed_nonce($interface_id)?>' />
	
	<?=$ct['gen']->input_2fa('strict')?>
			
	<p><input type='submit' value='Save <?=$ct['gen']->key_to_name($interface_id)?> Settings' /></p>
	
	</form>
     
     
   </div>
   
   <?php
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>