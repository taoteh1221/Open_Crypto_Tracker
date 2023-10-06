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
   
   
   function queue_config_update() {
        
   global $ct, $app_upgrade_check, $reset_config, $update_config, $check_2fa_error, $update_config_error, $update_config_success;
   
        
        // Updating the admin config
        // (MUST run after primary-init, BUT BEFORE load-config-by-security-level.php)
        // (STRICT 2FA MODE ONLY)
        if ( isset($_POST['conf_id']) && isset($_POST['interface_id']) && is_array($_POST[ $_POST['conf_id'] ]) && $ct['gen']->pass_sec_check($_POST['admin_hashed_nonce'], $_POST['interface_id']) && $ct['gen']->valid_2fa('strict') ) {
          
              if ( $app_upgrade_check ) {
              $update_config_error = 'The CACHED config is currently in the process of CHECKING FOR UPGRADES. Please wait a minute, and then try updating again.';
              }
              elseif ( $reset_config ) {
              $update_config_error = 'The CACHED config is currently in the process of RESETTING. Please wait a minute, and then try updating again.';
              }
              else {
               
               
                   // ADD VALIDATION CHECKS HERE, BEFORE ALLOWING UPDATE OF THIS CONFIG SECTION
                   if ( $_POST['conf_id'] === 'gen' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                        
                        
                      // Make sure primary currency conversion params are set properly
                      if ( !$ct['conf']['assets']['BTC']['pair'][ $_POST['gen']['bitcoin_primary_currency_pair'] ][ $_POST['gen']['bitcoin_primary_exchange'] ] ) {
                      $update_config_error = 'Bitcoin Primary Exchange "' . $ct['gen']->key_to_name($_POST['gen']['bitcoin_primary_exchange']) . '" does NOT have a "' . strtoupper($_POST['gen']['bitcoin_primary_currency_pair']) . '" market';
                      }
                      // If we made it this far, we passed all checks
                      else {
                      $update_config_valid = true;
                      }
                   
                   
                   }
                   elseif ( $_POST['conf_id'] === 'comms' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                   
                   $smtp_login_check = explode("||", $_POST['comms']['smtp_login']);
                   
                   $smtp_server_check = explode(":", $_POST['comms']['smtp_server']);
                   
                   $to_mobile_text_check = explode("||", $_POST['comms']['to_mobile_text']);
                        
                      // Make sure SMTP emailing params are set properly
                      if ( isset($_POST['comms']['smtp_login']) && $_POST['comms']['smtp_login'] != '' && sizeof($smtp_login_check) < 2 ) {
                      $update_config_error = 'SMTP Login formatting is NOT valid (format MUST be: username||password)';
                      }
                      elseif ( isset($_POST['comms']['smtp_server']) && $_POST['comms']['smtp_server'] != '' && sizeof($smtp_server_check) < 2 ) {
                      $update_config_error = 'SMTP Server formatting is NOT valid (format MUST be: domain_or_ip:port_number)';
                      }
                      // Mobile text check
                      elseif ( isset($_POST['comms']['to_mobile_text']) && $_POST['comms']['to_mobile_text'] != '' && sizeof($to_mobile_text_check) < 2 ) {
                      $update_config_error = 'To Mobile Text formatting is NOT valid (format MUST be: mobile_number||network_name)';
                      }
                      // Email FROM service check
                      elseif ( isset($_POST['comms']['from_email']) && $_POST['comms']['from_email'] != '' && $ct['gen']->valid_email($_POST['comms']['from_email']) != 'valid' ) {
                      $update_config_error = 'FROM Email is NOT valid: ' . $_POST['comms']['from_email'] . ' (' . $ct['gen']->valid_email($_POST['comms']['from_email']) . ')';
                      }
                      // Email TO service check
                      elseif ( isset($_POST['comms']['to_email']) && $_POST['comms']['to_email'] != '' && $ct['gen']->valid_email($_POST['comms']['to_email']) != 'valid' ) {
                      $update_config_error = 'TO Email is NOT valid: ' . $_POST['comms']['to_email'] . ' (' . $ct['gen']->valid_email($_POST['comms']['to_email']) . ')';
                      }
                      // If we made it this far, we passed all checks
                      else {
                      $update_config_valid = true;
                      }
                   
                   
                   }
                   elseif ( $_POST['conf_id'] === 'ext_apis' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                        
                      // Make sure Twilio number is set properly
                      if ( isset($_POST['ext_apis']['twilio_number']) && $_POST['ext_apis']['twilio_number'] != '' && !preg_match("/^\\d+$/", $_POST['ext_apis']['twilio_number']) ) {
                      $update_config_error = 'Twilio Number formatting is NOT valid: ' . $_POST['ext_apis']['twilio_number'] . ' (format MUST be ONLY NUMBERS)';
                      }
                      // If we made it this far, we passed all checks
                      else {
                      $update_config_valid = true;
                      }
                   
                   
                   }
                   elseif ( $_POST['conf_id'] === 'sec' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                   
                   
                      if ( isset($_POST['sec']['interface_login']) && $_POST['sec']['interface_login'] != '' ) {
                           
                      $is_interface_login = true;
                   
                      $interface_login_check = explode("||", $_POST['sec']['interface_login']);
                         
                      $htaccess_username_check = $interface_login_check[0];
                      $htaccess_password_check = $interface_login_check[1];
                        
                      $valid_username_check = $ct['gen']->valid_username($htaccess_username_check);
                        
                      // Password must be exactly 8 characters long for good htaccess security (htaccess only checks the first 8 characters for a match)
                      $password_strength_check = $ct['gen']->pass_strength($htaccess_password_check, 8, 8);
                        
                      }
                   
                        
                      // Make sure interface login params are set properly
                      if ( $is_interface_login && sizeof($interface_login_check) < 2 ) {
                      $update_config_error = 'Interface Login formatting is NOT valid (format MUST be: username||password)';
                      }
                      elseif ( $is_interface_login && $valid_username_check != 'valid' ) {
                      $update_config_error = 'Interface Login USERNAME requirements NOT met  (' . $valid_username_check . ')';
                      }
                      elseif ( $is_interface_login && $password_strength_check != 'valid' ) {
                      $update_config_error = 'Interface Login PASSWORD requirements NOT met  (' . $password_strength_check . ')';
                      }
                      // If we made it this far, we passed all checks
                      else {
                      $update_config_valid = true;
                      }
                   
                   
                   }
                   // If we are NOT forcing a particular validation check, flag as passed
                   else {
                   $update_config_valid = true;
                   }
                    
                    
                   // Update the corrisponding admin config section
                   if ( $update_config_valid ) {
                  
                   $ct['conf'][ $_POST['conf_id'] ] = $_POST[ $_POST['conf_id'] ];
                    
                   $update_config = true; // Triggers saving updated config to disk
                   
                   $update_config_success = 'Updating of admin section "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" SUCCEEDED.';
                   
                   }
                   else {
                   $update_config_error = 'Updating of admin section "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" FAILED. ' . $update_config_error;
                   }
                   
                    
              }
                    
        }
        // General error messages to display at top of page for UX
        elseif ( isset($_POST['conf_id']) && isset($_POST['interface_id']) ) {
          
              if ( $check_2fa_error ) {
              $update_config_error =  'Updating of admin section "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" FAILED. ' . $check_2fa_error . '.';
              }
          
        }
   
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function settings_form_fields($conf_id, $interface_id, $render_params=false) {
        
   global $ct, $update_config_success, $update_config_error;
   
	 
	 // Set which OTHER admin pages should be refreshed AFTER submission of this section's setting changes
	 if ( is_array($render_params) && isset($render_params['is_refresh_admin']) ) {
	 $refresh_admin_sections = $render_params['is_refresh_admin'];
	 }
	 else {
	 $refresh_admin_sections = 'none';
	 }
	 ?>
	
	
	<div style='min-height: 1em;'></div>

	 
	 <?php
	 if ( $update_config_success != null ) {
	 ?>
	 <div class='green green_dotted' style='font-weight: bold;'><?=$update_config_success?></div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 elseif ( $update_config_error != null ) {
	 ?>
	 <div class='red red_dotted' style='font-weight: bold;'><?=$update_config_error?></div>
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
              
              if ( isset($render_params[$key]['is_readonly']) ) {
              $val = '';
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
              
              <b class='blue'><?=$ct['gen']->key_to_name($key)?>:</b> &nbsp; <input type='<?=( isset($render_params[$key]['is_password']) ? 'password' : 'text' )?>' data-name="<?=md5($conf_id . $key)?>" name='<?=$conf_id?>[<?=$key?>]' value='<?=$val?>' <?=( isset($render_params[$key]['text_field_size']) ? ' size="' . $render_params[$key]['text_field_size'] . '"' : '' )?> <?=( isset($render_params[$key]['is_readonly']) ? 'readonly="readonly" placeholder="' . $render_params[$key]['is_readonly'] . '"' : '' )?> />
              
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