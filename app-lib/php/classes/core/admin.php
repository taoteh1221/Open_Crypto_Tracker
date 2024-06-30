<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



class ct_admin {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;

var $ct_array = array();

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function unconfigured_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false, $conf_id=false, $interface_id=false) {
        
   global $ct;
   
   ?>

         <p class='red_dotted'>
              
              <b class='red'>PLEASE *FULLY* CONFIGURE THIS SETTING'S 'FORM FIELD TYPE' BELOW IN THE ADMIN INTERFACE CONFIG!<br />
              <?=$ct['gen']->key_to_name($passed_key)?>:</b><br />
              <?=$passed_val?>
          
         </p>
         
         <script>
         
         parent.admin_interface_check['<?=md5($conf_id)?>'] = new Array();

         parent.admin_interface_check['<?=md5($conf_id)?>']['interface_id'] = '<?=$interface_id?>';

         parent.admin_interface_check['<?=md5($conf_id)?>']['interface_config_type'] = '<?=( $conf_id && preg_match('/plug_conf\|/', $conf_id) ? 'plugin' : 'section' )?>';

         parent.admin_interface_check['<?=md5($conf_id)?>']['missing_interface_configs'] = true;

         parent.admin_interface_check['<?=md5($conf_id)?>']['affected_section'] = '<?=$field_array_base?>';

         </script>
         
         <?php  
                
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function valid_secure_config_update_request() {
        
   global $ct;
   
      
        if ( isset($_POST['conf_id']) && preg_match('/plug_conf\|/', $_POST['conf_id']) ) {
        $parse_plugin_name = explode('|', $_POST['conf_id']);
        $field_array_base = $_POST[ $parse_plugin_name[1] ];
        }
        elseif ( isset($_POST['conf_id']) ) {
        $field_array_base = $_POST[ $_POST['conf_id'] ];
        }
        else {
        return false;
        }
      
        
        // Make sure ALL security checks pass / data seems valid for updating the admin config
        // (INCLUDES 'STRICT' 2FA MODE CHECK [returns true if 'strict' 2fa is turned off, OR 'strict' 2fa checked out as valid])
        if ( isset($_POST['conf_id']) && isset($_POST['interface_id']) && is_array($field_array_base) && $ct['gen']->pass_sec_check($_POST['admin_nonce'], $_POST['interface_id']) && $ct['gen']->valid_2fa('strict') ) {
        return $field_array_base;
        }
        else {
        return false;
        }
        
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function queue_config_update() {
        
   global $ct;
   
   // Check for VALIDATED / SECURE config updates IN PROGRESS
   $field_array_base = $this->valid_secure_config_update_request();
      
      
        if ( $ct['app_upgrade_check'] ) {
        $update_config_halt = 'The app is busy UPGRADING it\'s cached config, please wait a minute and try again.';
        }
        else if ( $ct['reset_config'] ) {
        $update_config_halt = 'The app is busy RESETTING it\'s cached config, please wait a minute and try again.';
        }
        
        
        if ( $field_array_base && !$update_config_halt ) {
   
      
              if ( preg_match('/plug_conf\|/', $_POST['conf_id']) ) {
              $parse_plugin_name = explode('|', $_POST['conf_id']);
              $is_plugin_config = true;
              $update_desc = 'plugin';
              }
              else {
              $update_desc = 'admin';
              }
              
               
              // Update the corrisponding admin config section, IF VALID SETTING VALUES
              if ( $this->valid_admin_settings() ) {
                  
                  if ( $is_plugin_config ) {
                  $ct['conf']['plug_conf'][ $parse_plugin_name[1] ] = $field_array_base;
                  }
                  else {
                  $ct['conf'][ $_POST['conf_id'] ] = $field_array_base;
                  }
               
              $ct['update_config'] = true; // Triggers saving updated config to disk
              
              $ct['update_config_success'] = 'Updating of "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" ' . $update_desc . ' settings SUCCEEDED.';
              
              }
              else {
              $ct['update_config_error'] = 'Updating of "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" ' . $update_desc . ' settings FAILED. ' . $ct['update_config_error'];
              }
              
               
        }
        // General error messages to display at top of page for UX
        elseif ( isset($_POST['conf_id']) && isset($_POST['interface_id']) ) {
          
              if ( $ct['check_2fa_error'] ) {
              $ct['update_config_error'] =  'Updating of "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" ' . $update_desc . ' settings FAILED. ' . $ct['check_2fa_error'] . '.';
              }
              else if ( $update_config_halt ) {
              $ct['update_config_error'] =  'Updating of "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" ' . $update_desc . ' settings FAILED. ' . $update_config_halt;
              }
          
        }
   
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function color_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct;
   
   
        if ( !isset($ct['repeatable_fields_tracking'][$passed_key]['is_color']) ) {
        $ct['repeatable_fields_tracking'][$passed_key]['is_color'] = 0;
        }
        
        
        // If a regular text area
        if ( isset($render_params[$passed_key]['is_color']) ) {
        ?>
         
         <p>
         
         <b class='blue'><?=$ct['gen']->key_to_name($passed_key)?>:</b> 
         
         <input type='color' name='<?=$field_array_base?>[<?=$passed_key?>]' value='<?=$passed_val?>' />
         
              <?php
              if ( isset($render_params[$passed_key]['is_notes']) ) {
              ?>
              <i class="notes_arrow arrow_up"></i><br /><span class='admin_settings_notes bitcoin random_tip'><?=$render_params[$passed_key]['is_notes']?></span>
              <?php
              }
              ?>
              
         </p>
         
        <?php
        }
         // If IS a subarray color field
         elseif ( isset($render_params[$passed_key]['is_subarray'][$subarray_key]['is_color']) ) {
                      
              // If string keyed array, show description from key value
              if ( $ct['gen']->has_string_keys($render_params[$passed_key]['is_subarray']) ) {
              $desc = '<b class="blue">' . $ct['gen']->key_to_name($subarray_key) . ':</b> &nbsp; ';
              }
              
         ?>
             
             <p>
         
                  <?=$desc?> <input type='color' data-track-index='<?=$subarray_key?>' name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>]' value='<?=( isset($passed_val[$subarray_key]) ? $passed_val[$subarray_key] : '' )?>' />
                  
                  <?php
                  if ( isset($render_params[$passed_key]['is_repeatable']['is_color']) ) {
                  $ct['repeatable_fields_tracking'][$passed_key]['is_color'] = $ct['repeatable_fields_tracking'][$passed_key]['is_color'] + 1;
                  echo '123PLACEHOLDER_RIGHT123';
                  }
                  ?>
              
             </p>
             
         <?php
         }
         // If HAS a subarray color field
         elseif ( is_array($render_params[$passed_key]['has_subarray'][$subarray_key]['is_color']) ) {
              
                      
             // If string keyed array, show description from key value
             // (do scanning BEFORE any loops, for speed)
             if ( $ct['gen']->has_string_keys($render_params[$passed_key]['has_subarray'][$subarray_key]['is_color']) ) {
             $is_string_keys = true;
             }
              
              
             foreach( $render_params[$passed_key]['has_subarray'][$subarray_key]['is_color'] as $sub_key => $unused ) {
                      
                  // If string keyed array, show description from key value
                  if ( $is_string_keys ) {
                  $desc = '<b class="blue">' . $ct['gen']->key_to_name($sub_key) . ':</b> &nbsp; ';
                  }
                  
             ?>
             
             <p>
         
                  <?=$desc?> <input type='color' data-track-index='<?=$subarray_key?>' name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>][<?=$sub_key?>]' value='<?=( isset($passed_val[$subarray_key][$sub_key]) ? $passed_val[$subarray_key][$sub_key] : '' )?>' />
                  
                  <?php
                  if ( isset($render_params[$passed_key]['is_repeatable']['is_color']) ) {
                  $ct['repeatable_fields_tracking'][$passed_key]['is_color'] = $ct['repeatable_fields_tracking'][$passed_key]['is_color'] + 1;
                  echo '123PLACEHOLDER_RIGHT123';
                  }
                  ?>

             </p>
             
             <?php
             }
        
        
         }
        
        
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function textarea_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct;
   
   
        if ( !isset($ct['repeatable_fields_tracking'][$passed_key]['is_textarea']) ) {
        $ct['repeatable_fields_tracking'][$passed_key]['is_textarea'] = 0;
        }
        
        
        // If a regular text area
        if ( isset($render_params[$passed_key]['is_textarea']) ) {
        ?>
         
         <p>
         
         <b class='blue'><?=$ct['gen']->key_to_name($passed_key)?>:</b> <br /> 
         
         <textarea data-autoresize name='<?=$field_array_base?>[<?=$passed_key?>]' style='height: auto; width: 100%;' <?=( isset($render_params[$passed_key]['is_password']) ? 'class="textarea_password" onblur="$(this).toggleClass(\'textarea_password\');autoresize_update();" onfocus="$(this).toggleClass(\'textarea_password\');autoresize_update();"' : '' )?>><?=$passed_val?></textarea>
         
              <?php
              if ( isset($render_params[$passed_key]['is_notes']) ) {
              ?>
              <i class="notes_arrow arrow_up"></i><br /><span class='admin_settings_notes bitcoin random_tip'><?=$render_params[$passed_key]['is_notes']?></span>
              <?php
              }
              ?>
              
         </p>
         
        <?php
        }
         // If IS a subarray textarea
         elseif ( isset($render_params[$passed_key]['is_subarray'][$subarray_key]['is_textarea']) ) {
                      
              // If string keyed array, show description from key value
              if ( $ct['gen']->has_string_keys($render_params[$passed_key]['is_subarray']) ) {
              $desc = '<b class="blue">' . $ct['gen']->key_to_name($subarray_key) . ':</b> &nbsp; ';
              }
              
         ?>
             
             <p>
         
                  <?=$desc?> <textarea data-track-index='<?=$subarray_key?>' data-autoresize name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>]' style='height: auto; width: 100%;' <?=( isset($render_params[$passed_key]['is_subarray'][$subarray_key]['is_password']) ? 'class="textarea_password" onblur="$(this).toggleClass(\'textarea_password\');autoresize_update();" onfocus="$(this).toggleClass(\'textarea_password\');autoresize_update();"' : '' )?>><?=( isset($passed_val[$subarray_key]) ? $passed_val[$subarray_key] : '' )?></textarea>
                  
                  <?php
                  if ( isset($render_params[$passed_key]['is_repeatable']['is_textarea']) ) {
                  $ct['repeatable_fields_tracking'][$passed_key]['is_textarea'] = $ct['repeatable_fields_tracking'][$passed_key]['is_textarea'] + 1;
                  echo '123PLACEHOLDER_RIGHT123';
                  }
                  ?>
              
             </p>
             
         <?php
         }
         // If HAS a subarray textarea
         elseif ( is_array($render_params[$passed_key]['has_subarray'][$subarray_key]['is_textarea']) ) {
              
                      
             // If string keyed array, show description from key value
             // (do scanning BEFORE any loops, for speed)
             if ( $ct['gen']->has_string_keys($render_params[$passed_key]['has_subarray'][$subarray_key]['is_textarea']) ) {
             $is_string_keys = true;
             }
              
              
             foreach( $render_params[$passed_key]['has_subarray'][$subarray_key]['is_textarea'] as $sub_key => $unused ) {
                      
                  // If string keyed array, show description from key value
                  if ( $is_string_keys ) {
                  $desc = '<b class="blue">' . $ct['gen']->key_to_name($sub_key) . ':</b> &nbsp; ';
                  }
                  
             ?>
             
             <p>
         
                  <?=$desc?> <textarea data-track-index='<?=$subarray_key?>' data-autoresize name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>][<?=$sub_key?>]' style='height: auto; width: 100%;' <?=( isset($render_params[$passed_key]['has_subarray'][$subarray_key]['is_password']) ? 'class="textarea_password" onblur="$(this).toggleClass(\'textarea_password\');autoresize_update();" onfocus="$(this).toggleClass(\'textarea_password\');autoresize_update();"' : '' )?>><?=( isset($passed_val[$subarray_key][$sub_key]) ? $passed_val[$subarray_key][$sub_key] : '' )?></textarea>
                  
                  <?php
                  if ( isset($render_params[$passed_key]['is_repeatable']['is_textarea']) ) {
                  $ct['repeatable_fields_tracking'][$passed_key]['is_textarea'] = $ct['repeatable_fields_tracking'][$passed_key]['is_textarea'] + 1;
                  echo '123PLACEHOLDER_RIGHT123';
                  }
                  ?>

             </p>
             
             <?php
             }
        
        
         }
        
        
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function hidden_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct;
              
              
         if ( isset($render_params[$passed_key]['is_trim']) ) {
         $passed_val = trim($passed_val);
         }
         ?>
          
              <input type='hidden' data-name="<?=md5($field_array_base . $passed_key)?>" name='<?=$field_array_base?>[<?=$passed_key?>]' value='<?=$passed_val?>' />
              
         <?php 
         
                
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function text_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct;
   
   
         if ( !isset($ct['repeatable_fields_tracking'][$passed_key]['is_text']) ) {
         $ct['repeatable_fields_tracking'][$passed_key]['is_text'] = 0;
         }
              
              
         if ( isset($render_params[$passed_key]['is_trim']) ) {
         $passed_val = trim($passed_val);
         }
         
         
         // If a regular text field (NOT a subarray)
         if ( isset($render_params[$passed_key]['is_text']) ) {


              if ( isset($render_params[$passed_key]['is_password']) ) {
              ?>
              
              <div class="password-container">
              
              <?php
              }
              ?>
          
              <p>
                
              <?php
              if ( isset($render_params[$passed_key]['is_password']) ) {
              ?>
              <span class='measure-password-field'>  
              <?php
              }
              ?>
              
              <b class='blue'><?=$ct['gen']->key_to_name($passed_key)?>:</b> &nbsp; <input type='<?=( isset($render_params[$passed_key]['is_password']) ? 'password' : 'text' )?>' data-name="<?=md5($field_array_base . $passed_key)?>" name='<?=$field_array_base?>[<?=$passed_key?>]' value='<?=$passed_val?>' <?=( isset($render_params[$passed_key]['text_field_size']) ? ' size="' . $render_params[$passed_key]['text_field_size'] . '"' : '' )?> <?=( isset($render_params[$passed_key]['is_readonly']) ? 'readonly="readonly" placeholder="' . $render_params[$passed_key]['is_readonly'] . '" title="' . $render_params[$passed_key]['is_readonly'] . '"' : '' )?> />
              
              <?php
              if ( isset($render_params[$passed_key]['is_password']) ) {
              ?>
              <i class="gg-eye-alt toggle-show-password" data-name="<?=md5($field_array_base . $passed_key)?>"></i>
              
              </span>
              <?php
              }
              ?>
              
              <?php
              if ( isset($render_params[$passed_key]['is_notes']) ) {
              ?>
          
              <br /><i class="notes_arrow arrow_up"></i><br /><span class='admin_settings_notes bitcoin random_tip'><?=$render_params[$passed_key]['is_notes']?></span>
              
              <?php
              }
              ?>
              
              </p>
              
              <?php   
              if ( isset($render_params[$passed_key]['is_password']) ) {
              ?>
              
              </div>
              
              <?php
              }
              
              
         }
         // If IS a subarray text field 
         elseif ( isset($render_params[$passed_key]['is_subarray'][$subarray_key]['is_text']) ) {
                      
              // If string keyed array, show description from key value
              if ( $ct['gen']->has_string_keys($render_params[$passed_key]['is_subarray']) ) {
              $desc = '<b class="blue">' . $ct['gen']->key_to_name($subarray_key) . ':</b> &nbsp; ';
              }
              
         ?>
             
             <p>
         
                  <?=$desc?> <input data-track-index='<?=$subarray_key?>' type='text' name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>]' value='<?=( isset($passed_val[$subarray_key]) ? $passed_val[$subarray_key] : '' )?>' <?=( isset($render_params[$passed_key]['is_subarray'][$subarray_key]['text_field_size']) ? ' size="' . $render_params[$passed_key]['is_subarray'][$subarray_key]['text_field_size'] . '"' : '' )?> />
                  
                  <?php
                  if ( isset($render_params[$passed_key]['is_repeatable']['is_text']) ) {
                  $ct['repeatable_fields_tracking'][$passed_key]['is_text'] = $ct['repeatable_fields_tracking'][$passed_key]['is_text'] + 1;
                  echo '123PLACEHOLDER_RIGHT123';
                  }
                  ?>
              
             </p>
             
         <?php
         }
         // If HAS a subarray text field 
         elseif ( is_array($render_params[$passed_key]['has_subarray'][$subarray_key]['is_text']) ) {
              
                      
             // If string keyed array, show description from key value
             // (do scanning BEFORE any loops, for speed)
             if ( $ct['gen']->has_string_keys($render_params[$passed_key]['has_subarray'][$subarray_key]['is_text']) ) {
             $is_string_keys = true;
             }
              
              
             foreach( $render_params[$passed_key]['has_subarray'][$subarray_key]['is_text'] as $deep_sub_key => $unused ) {
                      
                  // If string keyed array, show description from key value
                  if ( $is_string_keys ) {
                  $desc = '<b class="blue">' . $ct['gen']->key_to_name($deep_sub_key) . ':</b> &nbsp; ';
                  }
                  
             ?>
             
             <p>
         
                  <?=$desc?> <input data-track-index='<?=$subarray_key?>' type='text' name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>][<?=$deep_sub_key?>]' value='<?=( isset($passed_val[$subarray_key][$deep_sub_key]) ? $passed_val[$subarray_key][$deep_sub_key] : '' )?>' <?=( isset($render_params[$passed_key]['has_subarray'][$subarray_key]['text_field_size']) ? ' size="' . $render_params[$passed_key]['has_subarray'][$subarray_key]['text_field_size'] . '"' : '' )?> />
                  
                  <?php
                  if ( isset($render_params[$passed_key]['is_repeatable']['is_text']) ) {
                  $ct['repeatable_fields_tracking'][$passed_key]['is_text'] = $ct['repeatable_fields_tracking'][$passed_key]['is_text'] + 1;
                  echo '123PLACEHOLDER_RIGHT123';
                  }
                  ?>

             </p>
             
             <?php
             }
        
        
         }
         
                
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function radio_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct;
   
   
        if ( !isset($ct['repeatable_fields_tracking'][$passed_key]['is_radio']) ) {
        $ct['repeatable_fields_tracking'][$passed_key]['is_radio'] = 0;
        }
        
        
        // If a regular radio button
        if ( isset($render_params[$passed_key]['is_radio']) ) {
        ?>
         
         <p>
         
         <b class='blue'><?=$ct['gen']->key_to_name($passed_key)?>:</b> &nbsp; 
         
              <?php
              foreach( $render_params[$passed_key]['is_radio'] as $radio_key => $radio_val ) {
              
              
                   // If it's flagged as an associative array
                   if ( $radio_key === 'is_assoc' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                        
                        foreach( $render_params[$passed_key]['is_radio']['is_assoc'] as $assoc_val ) {
                        ?>
                        
                        <input type='radio' name='<?=$field_array_base?>[<?=$passed_key?>]' value='<?=$assoc_val['key']?>' <?=( $passed_val == $assoc_val['key'] ? 'checked' : '' )?> /> <?=$ct['gen']->key_to_name($assoc_val['val'])?> &nbsp;
                        
                        <?php
                        }
                        
                   }
                   // Everything else
                   else {
                   ?>
                   
                   <input type='radio' name='<?=$field_array_base?>[<?=$passed_key?>]' value='<?=$radio_val?>' <?=( $passed_val == $radio_val ? 'checked' : '' )?> /> <?=$ct['gen']->key_to_name($radio_val)?> &nbsp;
                   
                   <?php
                   }
              
              
              }
              
              
              if ( isset($render_params[$passed_key]['is_notes']) ) {
              ?>
              <br /><i class="notes_arrow arrow_up"></i><br /><span class='admin_settings_notes bitcoin random_tip'><?=$render_params[$passed_key]['is_notes']?></span>
              <?php
              }
              ?>
              
              </p>
         
        <?php
        }
        // If IS a subarray radio button 
        elseif ( is_array($render_params[$passed_key]['is_subarray'][$subarray_key]['is_radio']) ) {
        ?>
        
        <p>
        
        <b class='blue'><?=$ct['gen']->key_to_name($subarray_key)?>:</b> &nbsp; 
             
             <?php
             foreach( $render_params[$passed_key]['is_subarray'][$subarray_key]['is_radio'] as $setting_val ) {
             ?>
                 
                 <input data-track-index='<?=$subarray_key?>' type='radio' name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>]' value='<?=$setting_val?>' <?=( isset($passed_val[$subarray_key]) && $passed_val[$subarray_key] == $setting_val ? 'checked' : '' )?> /> <?=$ct['gen']->key_to_name($setting_val)?> &nbsp; 
                  
               <?php
               if ( isset($render_params[$passed_key]['is_repeatable']['is_radio']) ) {
               $ct['repeatable_fields_tracking'][$passed_key]['is_radio'] = $ct['repeatable_fields_tracking'][$passed_key]['is_radio'] + 1;
               echo '123PLACEHOLDER_RIGHT123';
               }
               ?>
                 
             <?php
             }
             ?>
                 
        </p>
             
        <?php 
        }
   
        
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function admin_config_interface($conf_id, $interface_id, $render_params=false) {
        
   global $ct;
   
   
      if ( !is_array($render_params) ) {
      return false;
      }
      
      
      if ( preg_match('/plug_conf\|/', $conf_id) ) {
           
      $is_plugin_config = true;
           
      $parse_plugin_data = explode('|', $conf_id);
      
      $field_array_base = $parse_plugin_data[1];
      
      $config_array_base = $ct['conf']['plug_conf'][ $parse_plugin_data[1] ];
      
      $hidden_plugin_settings = array(
                                      'runtime_mode',
                                      'ui_location',
                                      'ui_name',
                                      );
      
      }
      else {
      $field_array_base = $conf_id;
      $config_array_base = $ct['conf'][$conf_id];
      }
      
	 
	 // Set which OTHER admin pages should be refreshed AFTER submission of this section's setting changes
	 if ( isset($render_params['is_refresh_admin']) ) {
	 $refresh_admin_sections = $render_params['is_refresh_admin'];
	 }
	 else {
	 $refresh_admin_sections = 'none';
	 }
	 
	 ?>
	
	
	<div style='min-height: 1em;'></div>

	 
	 <?php
	 if ( $ct['update_config_success'] != null ) {
	 ?>
	 <div class='green green_dotted' style='font-weight: bold;'><?=$ct['update_config_success']?></div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 elseif ( $ct['update_config_error'] != null ) {
	 ?>
	 <div class='red red_dotted' style='font-weight: bold;'><?=$ct['update_config_error']?></div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 ?>
	 
	 
   <div class='pretty_text_fields'>
   
	
	<form name='update_config' id='update_config' action='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_' . $interface_id)?>&<?=( $is_plugin_config ? 'plugin' : 'section' )?>=<?=$interface_id?>&refresh=<?=$refresh_admin_sections?>' method='post'>
     
     <?php
     
     //var_dump($config_array_base);
     
     foreach( $config_array_base as $key => $val ) {
         
         // Radio buttons
         if ( isset($render_params[$key]['is_radio']) ) {
         $this->radio_form_fields($field_array_base, $key, $val, $render_params);
         }
         // Select dropdowns
         elseif ( isset($render_params[$key]['is_select']) ) {
         $this->select_form_fields($field_array_base, $key, $val, $render_params);
         }
         // Colors
         elseif ( isset($render_params[$key]['is_color']) ) {
         $this->color_form_fields($field_array_base, $key, $val, $render_params);
         }
         // Textareas
         elseif ( isset($render_params[$key]['is_textarea']) ) {
         $this->textarea_form_fields($field_array_base, $key, $val, $render_params);
         }
         // Ranges
         elseif ( isset($render_params[$key]['is_range']) ) {
         $this->range_form_fields($field_array_base, $key, $val, $render_params);
         }
         // Regular text fields
         elseif ( isset($render_params[$key]['is_text']) ) {
         $this->text_form_fields($field_array_base, $key, $val, $render_params);
         }
         // For plugin dev-only / any hidden fields
         elseif ( isset($render_params[$key]['is_hidden']) || $is_plugin_config && in_array($key, $hidden_plugin_settings) ) {
         $this->hidden_form_fields($field_array_base, $key, $val, $render_params);
         }
         // Subarray of ANY form field types (can be mixed)
         elseif ( is_array($render_params[$key]['is_subarray']) || is_array($render_params[$key]['has_subarray']) ) {
              
         $subarray_class = $field_array_base . '_' . $key;
	         
	    // Restore normal BOTTOM margin on any LAST compact margin element
	    $subarray_css .= '
         <style>
         .subarray_item.subarray_' . $subarray_class . '.compact_margins:last-of-type {
         margin-bottom: 2.5em;
         }
         </style>
	    ';
              
              
              if ( is_array($render_params[$key]['is_subarray']) ) {
              $subarray_type = 'is_subarray';
              }
              else {
              $subarray_type = 'has_subarray';
              }
              

              if ( is_array($render_params[$key]['is_repeatable']) ) {
              
              $repeat_id = 'repeat_' . $field_array_base . '_' . $key;
	         
	         $repeatable_seperator = "123PLACEHOLDER_BOTTOM123\n\n<div class='repeatable_seperator'></div>";
	         
	         $remove_button = '<input type="button" class="btn btn-danger span-2 delete" value="Remove" />';
              
              ?>
              
     		<fieldset style='margin-bottom: 2em;' class="<?=$subarray_class?> subsection_fieldset"><legend class='subsection_legend'> <b class='bitcoin'><?=$ct['gen']->key_to_name($key)?></b> </legend>
              
               <?php
               // We render any notes at the TOP of subarray settings
               if ( isset($render_params[$key]['is_notes']) ) {
               ?>
                    
               <i class="notes_arrow arrow_up"></i><br /><span class='admin_settings_notes bitcoin random_tip'><?=$render_params[$key]['is_notes']?></span>
                             
               <?php
               }
               ?>

               <p class='subarray_repeatable_add <?=$subarray_class?>_add'><input type="button" value="<?=$render_params[$key]['is_repeatable']['add_button']?>" class="btn btn-default add" align="center"></p>
                     
     		<div class="repeatable">
              
              <?php
              }
              else {         
              ?>
         
              <b class='bitcoin'><?=$ct['gen']->key_to_name($key)?></b> &nbsp; 

               <?php
               // We render any notes at the TOP of subarray settings
               if ( isset($render_params[$key]['is_notes']) ) {
               ?>
                    
               <br /><i class="notes_arrow arrow_up"></i><br /><span class='admin_settings_notes bitcoin random_tip'><?=$render_params[$key]['is_notes']?></span>
                             
               <?php
               }
               
              }
         
              
              // Subarray data can be mixed types of form fields, SO ALL CHECKS ARE 'IF' STATEMENTS
              foreach( $render_params[$key][$subarray_type] as $subarray_key => $unused ) {
              
              //var_dump($render_params[$key][$subarray_type][$subarray_key]);    
              
              ?>
              
              <div class='subarray_item subarray_<?=$subarray_class?> <?=( isset($render_params[$key][$subarray_type][$subarray_key]['compact_margins']) ? 'compact_margins' : '' )?>'>
              
              <?php
	         
	         
	         $field_count = 0;
	         $ct['repeatable_fields_tracking'][$key] = array(); // Reset counts              
                             
              ob_start();
              
              // WE USE isset below, as some subarray RENDER PARAMS are NOT SUBARRAYS THEMSELVES
              // (SOME ARE JUST THE CONFIGS FOR SUBARRAYS)
              
              // Ranges in subarray
              if ( isset($render_params[$key][$subarray_type][$subarray_key]['is_range']) ) {
              $this->range_form_fields($field_array_base, $key, $val, $render_params, $subarray_key);
              }
               
              // Radio buttons in subarray
              if ( isset($render_params[$key][$subarray_type][$subarray_key]['is_radio']) ) {
              $this->radio_form_fields($field_array_base, $key, $val, $render_params, $subarray_key);
              }
              
              // Select dropdowns in subarray
              if ( isset($render_params[$key][$subarray_type][$subarray_key]['is_select']) ) {
              $this->select_form_fields($field_array_base, $key, $val, $render_params, $subarray_key);
              }
              
              // Regular text fields in subarray
              if ( isset($render_params[$key][$subarray_type][$subarray_key]['is_text']) ) {
              $this->text_form_fields($field_array_base, $key, $val, $render_params, $subarray_key);
              }
              
              // Color fields in subarray
              if ( isset($render_params[$key][$subarray_type][$subarray_key]['is_color']) ) {
              $this->color_form_fields($field_array_base, $key, $val, $render_params, $subarray_key);
              }
              
              // Textarea fields in subarray
              if ( isset($render_params[$key][$subarray_type][$subarray_key]['is_textarea']) ) {
              $this->textarea_form_fields($field_array_base, $key, $val, $render_params, $subarray_key);
              }
              
	         
	         $rendered_form_fields = ob_get_contents();

	         ob_end_clean();
	          
	          
	              foreach ( $ct['repeatable_fields_tracking'][$key] as $count_val ) {
	              $field_count = $field_count + $count_val;
	              }
	                
	                
	              if ( $field_count > 1 ) {
	              $rendered_form_fields = $ct['gen']->str_replace_last('</p>', "</p>\n\n" . $repeatable_seperator . "\n\n", $rendered_form_fields);
	              $rendered_form_fields = preg_replace("/123PLACEHOLDER_BOTTOM123/i", $remove_button, $rendered_form_fields);
	              $rendered_form_fields = preg_replace("/123PLACEHOLDER_RIGHT123/i", "", $rendered_form_fields);
	              }
	              else {
	              $rendered_form_fields = preg_replace("/123PLACEHOLDER_RIGHT123/i", $remove_button, $rendered_form_fields);
	              $rendered_form_fields = preg_replace("/123PLACEHOLDER_BOTTOM123/i", "", $rendered_form_fields);
	              }
	                
	          
	         echo $rendered_form_fields;
              
              ?>
              
              </div>
              
              <?php
              
              }


              if ( is_array($render_params[$key]['is_repeatable']) ) {
              ?>
              
     	     </div>

               <p class='subarray_repeatable_add <?=$subarray_class?>_add'><input type="button" value="<?=$render_params[$key]['is_repeatable']['add_button']?>" class="btn btn-default add" align="center"></p>
     
     		</fieldset>

          	<!-- Scripting to run the form manipulations -->
          	
          
               <script type="text/template" id="<?=$repeat_id?>">
	          
	          <?php
	          
               // Add / remove (repeatable) form fields template
	          
	          $field_count = 0;
	          $ct['repeatable_fields_tracking'][$key] = array(); // Reset counts
               
               ob_start();
               
	          $this->repeatable_fields_template($field_array_base, $key, $val, $render_params);

	          $repeatable_template = ob_get_contents();

	          ob_end_clean();
	          
	          //var_dump($ct['repeatable_fields_tracking']);
	          
	          
	                foreach ( $ct['repeatable_fields_tracking'][$key] as $count_val ) {
	                $field_count = $field_count + $count_val;
	                }
	                
	                
	                if ( $field_count > 1 ) {
	                $repeatable_template = $ct['gen']->str_replace_last('</p>', "</p>\n\n" . $repeatable_seperator . "\n\n", $repeatable_template);
	                $repeatable_template = preg_replace("/123PLACEHOLDER_BOTTOM123/i", $remove_button, $repeatable_template);
	                $repeatable_template = preg_replace("/123PLACEHOLDER_RIGHT123/i", "", $repeatable_template);
	                }
	                else {
	                $repeatable_template = preg_replace("/123PLACEHOLDER_RIGHT123/i", $remove_button, $repeatable_template);
	                $repeatable_template = preg_replace("/123PLACEHOLDER_BOTTOM123/i", "", $repeatable_template);
	                }
	                
	          
	          echo $repeatable_template;
	                
		     ?>
         
         
          	</script>
          
          
          	<script>
          	
          		$(document).ready(function(){ 
          			$(".<?=$subarray_class?> .repeatable").repeatable({
          			     prefix: '',
          				addTrigger: ".<?=$subarray_class?>_add .add",
          				deleteTrigger: ".<?=$subarray_class?> .delete",
          				template: "#<?=$repeat_id?>",
          				itemContainer: ".subarray_<?=$subarray_class?>",
          				afterAdd: function () {
                              
                              red_save_button('iframe');
                                
                                  // Wait 1 seconds before Initiating the admin settings range sliders
                                  // (otherwise widths aren't always registered yet for CSS style manipulations)
                                  setTimeout(function(){
                                  init_range_sliders();
                                  }, 1000);
     
               			
               			    // Make any added textarea autosize
                                  $('textarea[data-autoresize]').each(function(){
                                  autosize(this);
                                  }).on('autosize:resized', function(){
                                   
                                       // Resize admin iframes after resizing textareas
                                       admin_iframe_load.forEach(function(iframe) {
                                       iframe_size_adjust(iframe);
                                       });
                                   
                                  });

                         
          				},
          				afterDelete: function () {
          				     
          				red_save_button('iframe');
          				     
          				// Update seperators beetween repeatables
                              $("div.repeatable > div.subarray_item:first-child").css("border-top", "0.0em solid #808080");
                              $("div.repeatable > div.subarray_item:first-child").css("padding-top", "0.0em");

          				},
          				min: 1,
          				max: 999
          			});
          		});
          		
          	</script>
		
         
              <?php
              }
         

         }
         // Everything else should just render as AN ALERT TO ADD THIS SETTING'S CONFIG PARAMETERS
         else {
         $this->unconfigured_form_fields($field_array_base, $key, $val, $render_params, false, $conf_id, $interface_id);
         }
         
     }
     ?>
     
	<input type='hidden' name='conf_id' id='conf_id' value='<?=$conf_id?>' />
     
	<input type='hidden' name='interface_id' id='interface_id' value='<?=$interface_id?>' />
	
	<input type='hidden' name='admin_nonce' value='<?=$ct['gen']->admin_nonce($interface_id)?>' />
	
	<?=$ct['gen']->input_2fa('strict')?>
	
	</form>
     
     
   </div>
   
   <?=$subarray_css?>
   
   <?php
   
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function valid_admin_settings() {
        
   global $ct, $plug, $this_plug;
   
   
        if ( !isset($_POST['conf_id']) ) {
        return false;
        }
        elseif ( preg_match('/plug_conf\|/', $_POST['conf_id']) ) {
        $parse_plugin_name = explode('|', $_POST['conf_id']);
        $is_plugin = $parse_plugin_name[1];
        }
   
        
        // ADD VALIDATION CHECKS HERE, BEFORE ALLOWING UPDATE OF THIS CONFIG SECTION
        
        // Plugin support (if found in plugin's class)
        if ( $is_plugin && method_exists($plug['class'][$is_plugin], 'admin_input_validation') && is_callable( array($plug['class'][$is_plugin], 'admin_input_validation') ) ) {
        $this_plug = $is_plugin;
        $ct['update_config_error'] = $plug['class'][$is_plugin]->admin_input_validation();
        unset($this_plug);
        }
        elseif ( $_POST['conf_id'] === 'gen' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
             
           // Make sure primary currency conversion params are set properly
           if ( !$ct['conf']['assets']['BTC']['pair'][ $_POST['gen']['bitcoin_primary_currency_pair'] ][ $_POST['gen']['bitcoin_primary_currency_exchange'] ] ) {
           $ct['update_config_error'] = 'Bitcoin Primary Exchange "' . $ct['gen']->key_to_name($_POST['gen']['bitcoin_primary_currency_exchange']) . '" does NOT have a "' . strtoupper($_POST['gen']['bitcoin_primary_currency_pair']) . '" market';
           }
        
        }
        elseif ( $_POST['conf_id'] === 'comms' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        
        $smtp_login_check = explode("||", $_POST['comms']['smtp_login']);
        
        $smtp_server_check = explode(":", $_POST['comms']['smtp_server']);
        
        $to_mobile_text_check = explode("||", $_POST['comms']['to_mobile_text']);
             
             
           // Make sure SMTP emailing params are set properly
           if ( isset($_POST['comms']['smtp_login']) && $_POST['comms']['smtp_login'] != '' && sizeof($smtp_login_check) < 2 ) {
           $ct['update_config_error'] = 'SMTP Login formatting is NOT valid (format MUST be: username||password)';
           }
           elseif ( isset($_POST['comms']['smtp_server']) && $_POST['comms']['smtp_server'] != '' && sizeof($smtp_server_check) < 2 ) {
           $ct['update_config_error'] = 'SMTP Server formatting is NOT valid (format MUST be: domain_or_ip:port_number)';
           }
           // Mobile text check
           elseif ( isset($_POST['comms']['to_mobile_text']) && $_POST['comms']['to_mobile_text'] != '' && sizeof($to_mobile_text_check) < 2 ) {
           $ct['update_config_error'] = 'To Mobile Text formatting is NOT valid (format MUST be: mobile_number||network_name)';
           }
           // Email FROM service check
           elseif ( isset($_POST['comms']['from_email']) && $_POST['comms']['from_email'] != '' && $ct['gen']->valid_email($_POST['comms']['from_email']) != 'valid' ) {
           $ct['update_config_error'] = 'FROM Email is NOT valid: ' . $_POST['comms']['from_email'] . ' (' . $ct['gen']->valid_email($_POST['comms']['from_email']) . ')';
           }
           // Email TO service check
           elseif ( isset($_POST['comms']['to_email']) && $_POST['comms']['to_email'] != '' && $ct['gen']->valid_email($_POST['comms']['to_email']) != 'valid' ) {
           $ct['update_config_error'] = 'TO Email is NOT valid: ' . $_POST['comms']['to_email'] . ' (' . $ct['gen']->valid_email($_POST['comms']['to_email']) . ')';
           }
        
        
        }
        elseif ( $_POST['conf_id'] === 'ext_apis' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
	
        // Test mode (retrieves current block height)    
	   $solana_block_height = $ct['api']->solana_rpc('getBlockHeight', false, 0, $_POST['ext_apis']['solana_rpc_server'])['result'];
	
	
           if (
           !isset($solana_block_height)
           || isset($solana_block_height) && !is_int($solana_block_height)
           || isset($solana_block_height) && $solana_block_height < 1
           ) {
           $ct['update_config_error'] .= 'Solana RPC Server "' . $_POST['ext_apis']['solana_rpc_server'] . '" query test FAILED (make sure you entered the RPC endpoint address correctly)';
           }
             
             
           // Make sure Twilio number is set properly
           if ( isset($_POST['ext_apis']['twilio_number']) && $_POST['ext_apis']['twilio_number'] != '' && !preg_match("/^\\d+$/", $_POST['ext_apis']['twilio_number']) ) {
           $ct['update_config_error'] = 'Twilio Number formatting is NOT valid: ' . $_POST['ext_apis']['twilio_number'] . ' (format MUST be ONLY NUMBERS)';
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
           $ct['update_config_error'] = 'Interface Login formatting is NOT valid (format MUST be: username||password)';
           }
           elseif ( $is_interface_login && $valid_username_check != 'valid' ) {
           $ct['update_config_error'] = 'Interface Login USERNAME requirements NOT met  (' . $valid_username_check . ')';
           }
           elseif ( $is_interface_login && $password_strength_check != 'valid' ) {
           $ct['update_config_error'] = 'Interface Login PASSWORD requirements NOT met  (' . $password_strength_check . ')';
           }
        
        
        }
        elseif ( $_POST['conf_id'] === 'news' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
		
        $news_feed_cache_min_max = array_map('trim', explode(',', $_POST['news']['news_feed_cache_min_max']) );
             
             
            // Make sure min / max cache time is set properly
            if ( isset($_POST['news']['news_feed_cache_min_max']) && trim($_POST['news']['news_feed_cache_min_max']) == '' ) {
            $ct['update_config_error'] = '"News Feed Cache Min Max" value is REQUIRED';
            }
            else if (
            !isset($news_feed_cache_min_max[0]) || !$ct['var']->whole_int($news_feed_cache_min_max[0]) || $news_feed_cache_min_max[0] < 30 || $news_feed_cache_min_max[0] > 720 
            || !isset($news_feed_cache_min_max[1]) || !$ct['var']->whole_int($news_feed_cache_min_max[1]) || $news_feed_cache_min_max[1] < 30 || $news_feed_cache_min_max[1] > 720
            || $news_feed_cache_min_max[0] > $news_feed_cache_min_max[1]
            ) {
            $ct['update_config_error'] = '"News Feed Cache Min Max" values MUST be between 30 and 720 (LARGER number last)';
            }
        
        
        }
        elseif ( $_POST['conf_id'] === 'proxy' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        
        
           if ( isset($_POST['proxy']['proxy_login']) && $_POST['proxy']['proxy_login'] != '' && !preg_match('/\s/', $_POST['proxy']['proxy_login']) ) {
           $is_proxy_login = true;
           $proxy_login_check = explode("||", $_POST['proxy']['proxy_login']);
           }
        
           
           if ( preg_match('/\s/', $_POST['proxy']['proxy_login']) ) {
           $ct['update_config_error'] .= 'WHITESPACE is not allowed in the Proxy LOGIN';
           }
           // Make sure proxy login params are set properly
           elseif ( $is_proxy_login && sizeof($proxy_login_check) < 2 ) {
           $ct['update_config_error'] .= 'Proxy LOGIN formatting is NOT valid (format MUST be: username||password)';
           }
           elseif ( isset($_POST['proxy']['allow_proxies']) && $_POST['proxy']['allow_proxies'] == 'on' && is_array($_POST['proxy']['proxy_list']) ) {
           
               foreach ( $_POST['proxy']['proxy_list'] as $proxy ) {
                    
               $proxy = trim($proxy);
               
               $proxy_check = explode(":", $proxy);
                    
                    if ( sizeof($_POST['proxy']['proxy_list']) == 1 && trim($proxy) == '' ) {
     	          // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
                    }
                    elseif ( sizeof($proxy_check) < 2 ) {
                    $ct['update_config_error'] .= '<br />Proxy LIST formatting is NOT valid (format MUST be: ip_address:port_number [in submission: "'.$proxy.'"])';
                    }
                    else {
                         
                         if ( $proxy_checked ) {
                         sleep(2); // Don't want to hit the testing server too hard too quick on consecutive requests
                         }
                    
                    $check_proxy = $ct['gen']->connect_test($proxy, 'proxy');
                    
                         if ( $check_proxy['status'] != 'ok' ) {
                         $ct['update_config_error'] .= '<br />Proxy TEST failed for submission: "'.$proxy.'" ('.$check_proxy['status'].')';
                         }
                         
                    $proxy_checked = true;
                    
                    }
               
               }
           
           }
           
        
        }
        elseif ( $_POST['conf_id'] === 'mobile_network' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        
        
		 foreach ( $_POST['mobile_network']['text_gateways'] as $val ) {
		     
		 $gateway_data = array_map( "trim", explode("||", $val) );
			
		 $test_result = $ct['gen']->valid_email( 'test@' . $gateway_data[1] );
		
                    
                if ( sizeof($_POST['mobile_network']['text_gateways']) == 1 && trim($val) == '' ) {
     	      // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
                }
			 elseif ( $ct['var']->begins_with_in_array($_POST['mobile_network']['text_gateways'], $gateway_data[0] . '||')['count'] > 1 ) {
                $ct['update_config_error'] .= '<br />Mobile text gateway KEY was USED TWICE (DUPLICATE): "'.$gateway_data[0].'" (in "'.$val.'", no duplicate keys allowed)';
			 }
		      elseif ( $test_result != 'valid' ) {
                $ct['update_config_error'] .= '<br />Mobile text gateway seems INVALID: "'.$gateway_data[1].'" ('.$test_result.')';
			 }
			 
		
		 }
           
        
        }
        elseif ( $_POST['conf_id'] === 'charts_alerts' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
     	     
        $update_config_error_seperator = '<br /> ';
        
        $light_chart_day_intervals = array_map( "trim", explode(',', $_POST['charts_alerts']['light_chart_day_intervals']) );
		
        $light_chart_all_rebuild_min_max = array_map('trim', explode(',', $_POST['charts_alerts']['light_chart_all_rebuild_min_max']) );
		
        $asset_performance_chart_defaults = array_map('trim', explode('||', $_POST['charts_alerts']['asset_performance_chart_defaults']) );
		
        $asset_marketcap_chart_defaults = array_map('trim', explode('||', $_POST['charts_alerts']['asset_marketcap_chart_defaults']) );
        
        $allowed_modes = array(
                               'chart',
                               'alert',
                               'both',
                               'none',
                              );
        
        
           if ( isset($_POST['charts_alerts']['whale_alert_thresholds']) && trim($_POST['charts_alerts']['whale_alert_thresholds']) != '' ) {
                
           $is_whale_alert = true;
        
           $whale_alert_check = array_map( "trim", explode("||", $_POST['charts_alerts']['whale_alert_thresholds']) );
               
               if ( sizeof($whale_alert_check) == 4 ) {
               
                    foreach ( $whale_alert_check as $val ) {
                         
                         if ( !is_numeric($val) ) {
                         $is_whale_alert = false;
                         }
                         
                    }
                    
               }
               else {
               $is_whale_alert = false;
               }
             
           }
           
           
           foreach ( $_POST['charts_alerts']['tracked_markets'] as $key => $val ) {
           
           // Auto-correct
           $_POST['charts_alerts']['tracked_markets'][$key] = $ct['var']->auto_correct_str($val, 'lower');
           
           $val = $ct['var']->auto_correct_str($val, 'lower');
            
           $val_config = array_map( "trim", explode("||", $val) ); // Convert $val into an array
			
		 // Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		 $chart_asset = ( stristr($val_config[0], "-") == false ? $val_config[0] : substr( $val_config[0], 0, mb_strpos($val_config[0], "-", 0, 'utf-8') ) );
		 $chart_asset = strtoupper($chart_asset);
            
           $exchange = $val_config[1];

           $pair = $val_config[2];

           $mode = $val_config[3];
     
     	 $mrkt_id = $ct['conf']['assets'][$chart_asset]['pair'][$pair][$exchange];
               
     	 $mrkt_val = $ct['var']->num_to_str( $ct['api']->market($chart_asset, $exchange, $mrkt_id)['last_trade'] );
     	
     	     
     	     if ( sizeof($_POST['charts_alerts']['tracked_markets']) == 1 && trim($val) == '' ) {
     	     // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
     	     }
     	     elseif ( $ct['var']->begins_with_in_array($_POST['charts_alerts']['tracked_markets'], $val_config[0] . '||')['count'] > 1 ) {
               $ct['update_config_error'] .= $update_config_error_seperator . 'Charts / Alerts KEY was USED TWICE (DUPLICATE): "'.$val_config[0].'" (no duplicate keys allowed)';
     	     }
     	     elseif ( !isset($mrkt_val) || isset($mrkt_val) && !is_numeric($mrkt_val) || isset($mrkt_val) && $mrkt_val == 0.00000000000000000000 ) {
     	     $ct['update_config_error'] .= $update_config_error_seperator . 'No market data found for ' . $chart_asset . ' / ' . strtoupper($pair) . ' @ ' . $ct['gen']->key_to_name($exchange) . ' (in submission: "'.$val.'"); Market MAY be down *temporarily* for maintenance, OR permanently removed (please verify on the exchange website)';
     	     }
     	     elseif ( !in_array($mode, $allowed_modes) ) {
     	     $ct['update_config_error'] .= $update_config_error_seperator . 'Unknown mode (in submission: "'.$val.'")';
     	     }
     	
     	 
           }
        
             
           // Make whale alert params are set properly
           if ( !$is_whale_alert ) {
           $ct['update_config_error'] .= $update_config_error_seperator . 'Whale Alert Thresholds formatting is NOT valid';
           }
             
             
           // Make sure light chart day intervals is set
           if ( isset($_POST['charts_alerts']['light_chart_day_intervals']) && trim($_POST['charts_alerts']['light_chart_day_intervals']) == '' ) {
           $ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart Day Intervals" MUST be filled in';
           }
           else {
           
                foreach ( $light_chart_day_intervals as $days ) {
                
                    if ( $days == 0 || !$ct['var']->whole_int($days) ) {
                    $ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart Day Intervals" MUST be whole numbers greater than zero ("'.$days.'" is invalid)';
                    }
                
                }
                
           }
             
             
           // Make sure asset performance chart config is set
           if ( isset($_POST['charts_alerts']['asset_performance_chart_defaults']) && trim($_POST['charts_alerts']['asset_performance_chart_defaults']) == '' ) {
           $ct['update_config_error'] .= $update_config_error_seperator . '"Asset Performance Chart Defaults" MUST be filled in';
           }
           else if (
           !isset($asset_performance_chart_defaults[0]) || !$ct['var']->whole_int($asset_performance_chart_defaults[0]) || $asset_performance_chart_defaults[0] < 400 || $asset_performance_chart_defaults[0] > 900 
           || !isset($asset_performance_chart_defaults[1]) || !$ct['var']->whole_int($asset_performance_chart_defaults[1]) || $asset_performance_chart_defaults[1] < 7 || $asset_performance_chart_defaults[1] > 16
           || !$ct['var']->whole_int($asset_performance_chart_defaults[0] / 100)
           ) {
           $ct['update_config_error'] .= $update_config_error_seperator . '"Asset Performance Chart Defaults" FORMATTING incorrect (see corrisponding setting\'s NOTES section)';
           }
             
             
           // Make sure marketcap chart config is set
           if ( isset($_POST['charts_alerts']['asset_marketcap_chart_defaults']) && trim($_POST['charts_alerts']['asset_marketcap_chart_defaults']) == '' ) {
           $ct['update_config_error'] .= $update_config_error_seperator . '"Asset Marketcap Chart Defaults" MUST be filled in';
           }
           else if (
           !isset($asset_marketcap_chart_defaults[0]) || !$ct['var']->whole_int($asset_marketcap_chart_defaults[0]) || $asset_marketcap_chart_defaults[0] < 400 || $asset_marketcap_chart_defaults[0] > 900 
           || !isset($asset_marketcap_chart_defaults[1]) || !$ct['var']->whole_int($asset_marketcap_chart_defaults[1]) || $asset_marketcap_chart_defaults[1] < 7 || $asset_marketcap_chart_defaults[1] > 16
           || !$ct['var']->whole_int($asset_marketcap_chart_defaults[0] / 100)
           ) {
           $ct['update_config_error'] .= $update_config_error_seperator . '"Asset Marketcap Chart Defaults" FORMATTING incorrect (see corrisponding setting\'s NOTES section)';
           }
           
           
           // Make sure min / max 'all' light chart rebuild time is set properly
           if ( isset($_POST['charts_alerts']['light_chart_all_rebuild_min_max']) && trim($_POST['charts_alerts']['light_chart_all_rebuild_min_max']) == '' ) {
           $ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart All Rebuild Min Max" MUST be filled in';
           }
           else if (
           !isset($light_chart_all_rebuild_min_max[0]) || !$ct['var']->whole_int($light_chart_all_rebuild_min_max[0]) || $light_chart_all_rebuild_min_max[0] < 3 || $light_chart_all_rebuild_min_max[0] > 12 
           || !isset($light_chart_all_rebuild_min_max[1]) || !$ct['var']->whole_int($light_chart_all_rebuild_min_max[1]) || $light_chart_all_rebuild_min_max[1] < 3 || $light_chart_all_rebuild_min_max[1] > 12
           || $light_chart_all_rebuild_min_max[0] > $light_chart_all_rebuild_min_max[1]
           ) {
           $ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart All Rebuild Min Max" values MUST be between 3 and 12 (LARGER number last)';
           }
        
        
        }
        
        
   return ( isset($ct['update_config_error']) && trim($ct['update_config_error']) != '' ? false : true );
        
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function range_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct;
   
   
         if ( !isset($ct['repeatable_fields_tracking'][$passed_key]['is_range']) ) {
         $ct['repeatable_fields_tracking'][$passed_key]['is_range'] = 0;
         }
         
         
         // If a regular range field
         if ( isset($render_params[$passed_key]['is_range']) ) {
              
              if ( $render_params[$passed_key]['range_ui_prefix'] == '+' ) {
              }
              
         ?>
          
              <div class='admin_range_fields'>

              
                 <div class="range-title blue setting_title"><?=$ct['gen']->key_to_name($passed_key)?>:</div>
                 
                 <div class="range-wrap">
                 
              <div class="range-tooltip"></div>
              
              <div class="range-min light_sea_green"><?=$ct['var']->num_pretty($render_params[$passed_key]['range_min'], 0)?></div>
              
              <input type='range' class='range-field' min="<?=$render_params[$passed_key]['range_min']?>" max="<?=$render_params[$passed_key]['range_max']?>" step="<?=$render_params[$passed_key]['range_step']?>" data-name="<?=md5($field_array_base . $passed_key)?>" name='<?=$field_array_base?>[<?=$passed_key?>]' value='<?=$passed_val?>' <?=( isset($render_params[$passed_key]['is_readonly']) ? 'readonly="readonly" title="' . $render_params[$passed_key]['is_readonly'] . '"' : '' )?> <?=( is_array($render_params[$passed_key]['is_custom_steps']) ? 'list="steplist_'. $field_array_base . '_' . $passed_key . '"' : '' )?> />
         
              <div class="range-max light_sea_green"><?=$ct['var']->num_pretty($render_params[$passed_key]['range_max'], 0)?></div>
              
              <div class="range-value light_sea_green"><?=$passed_val?></div>
              
              <div class="range-ui-prefix"><?=$render_params[$passed_key]['range_ui_prefix']?></div>
              
              <div class="range-ui-suffix"><?=$render_params[$passed_key]['range_ui_suffix']?></div>
              
              <div class="range-ui-meta-data"><?=$render_params[$passed_key]['range_ui_meta_data']?></div>
         
                 </div>
              
                 <?php
                 if ( is_array($render_params[$passed_key]['is_custom_steps']) ) {
                 ?>
          
                 <datalist id="steplist_<?=($field_array_base . '_' . $passed_key)?>">
              
                <?php
                foreach ( $render_params[$passed_key]['is_custom_steps'] as $custom_step ) {
                ?>
                <option><?=$custom_step?></option>
                <?php
                }
                ?>
                  
                 </datalist>
              
                 <?php
                 }


                 if ( isset($render_params[$passed_key]['is_notes']) ) {
                 ?>
          
                 <br /><i class="notes_arrow arrow_up"></i><br /><span class='admin_settings_notes bitcoin random_tip'><?=$render_params[$passed_key]['is_notes']?></span>
              
                 <?php
                 }
                 ?>
              
              </div>
              
         <?php   
         }
         // If IS a subarray range field 
         elseif ( isset($render_params[$passed_key]['is_subarray'][$subarray_key]['is_range']) ) {
                      
              // If string keyed array, show description from key value
              if ( $ct['gen']->has_string_keys($render_params[$passed_key]['is_subarray']) ) {
              $desc = '<div class="range-title blue setting_title">' . $ct['gen']->key_to_name($subarray_key) . ':</div>';
              }
              
         ?>
             
             <div class='admin_range_fields'>
         
                  <?=$desc?>
                 
                  <div class="range-wrap">
                 
               <div class="range-tooltip"></div>
              
               <div class="range-min light_sea_green"><?=$ct['var']->num_pretty($render_params[$passed_key]['is_subarray'][$subarray_key]['range_min'], 0)?></div>
                  
               <input data-track-index='<?=$subarray_key?>' type='range' class='range-field' min="<?=$render_params[$passed_key]['is_subarray'][$subarray_key]['range_min']?>" max="<?=$render_params[$passed_key]['is_subarray'][$subarray_key]['range_max']?>" step="<?=$render_params[$passed_key]['is_subarray'][$subarray_key]['range_step']?>" name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>]' value='<?=( isset($passed_val[$subarray_key]) ? $passed_val[$subarray_key] : '' )?>' <?=( is_array($render_params[$passed_key]['is_subarray'][$subarray_key]['is_custom_steps']) ? 'list="steplist_'. $field_array_base . '_' . $passed_key . '_' . $subarray_key . '"' : '' )?> />
         
               <div class="range-max light_sea_green"><?=$ct['var']->num_pretty($render_params[$passed_key]['is_subarray'][$subarray_key]['range_max'], 0)?></div>
              
               <div class="range-value light_sea_green"><?=( isset($passed_val[$subarray_key]) ? $passed_val[$subarray_key] : '' )?></div>
              
               <div class="range-ui-prefix"><?=$render_params[$passed_key]['is_subarray'][$subarray_key]['range_ui_prefix']?></div>
              
               <div class="range-ui-suffix"><?=$render_params[$passed_key]['is_subarray'][$subarray_key]['range_ui_suffix']?></div>
              
               <div class="range-ui-meta-data"><?=$render_params[$passed_key]['is_subarray'][$subarray_key]['range_ui_meta_data']?></div>
         
                  </div>
              
                  <?php
                  if ( is_array($render_params[$passed_key]['is_subarray'][$subarray_key]['is_custom_steps']) ) {
                  ?>
          
                  <datalist id="steplist_<?=($field_array_base . '_' . $passed_key . '_' . $subarray_key)?>">
              
                 <?php
                 foreach ( $render_params[$passed_key]['is_subarray'][$subarray_key]['is_custom_steps'] as $custom_step ) {
                 ?>
                 <option><?=$custom_step?></option>
                 <?php
                 }
                 ?>
                  
                  </datalist>
              
                  <?php
                  }


                  if ( isset($render_params[$passed_key]['is_repeatable']['is_range']) ) {
                  $ct['repeatable_fields_tracking'][$passed_key]['is_range'] = $ct['repeatable_fields_tracking'][$passed_key]['is_range'] + 1;
                  echo '123PLACEHOLDER_RIGHT123';
                  }
                  ?>
              
             </div>
             
         <?php
         }
         // If HAS a subarray range field 
         elseif ( is_array($render_params[$passed_key]['has_subarray'][$subarray_key]['is_range']) ) {
              
                      
             // If string keyed array, show description from key value
             // (do scanning BEFORE any loops, for speed)
             if ( $ct['gen']->has_string_keys($render_params[$passed_key]['has_subarray'][$subarray_key]['is_range']) ) {
             $is_string_keys = true;
             }
              
              
             foreach( $render_params[$passed_key]['has_subarray'][$subarray_key]['is_range'] as $sub_key => $unused ) {
                      
                  // If string keyed array, show description from key value
                  if ( $is_string_keys ) {
                  $desc = '<div class="range-title blue setting_title">' . $ct['gen']->key_to_name($sub_key) . ':</div>';
                  }
              
                  
             ?>
             
             <div class='admin_range_fields'>
         
                  <?=$desc?>
                 
                  <div class="range-wrap">
                 
               <div class="range-tooltip"></div>
              
               <div class="range-min light_sea_green"><?=$ct['var']->num_pretty($render_params[$passed_key]['has_subarray'][$subarray_key]['range_min'], 0)?></div>
                  
               <input data-track-index='<?=$subarray_key?>' type='range' class='range-field' min="<?=$render_params[$passed_key]['has_subarray'][$subarray_key]['range_min']?>" max="<?=$render_params[$passed_key]['has_subarray'][$subarray_key]['range_max']?>" step="<?=$render_params[$passed_key]['has_subarray'][$subarray_key]['range_step']?>" name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>][<?=$sub_key?>]' value='<?=( isset($passed_val[$subarray_key][$sub_key]) ? $passed_val[$subarray_key][$sub_key] : '' )?>' <?=( is_array($render_params[$passed_key]['has_subarray'][$subarray_key]['is_custom_steps']) ? 'list="steplist_'. $field_array_base . '_' . $passed_key . '_' . $subarray_key . '_' . $sub_key . '"' : '' )?>  />
         
               <div class="range-max light_sea_green"><?=$ct['var']->num_pretty($render_params[$passed_key]['has_subarray'][$subarray_key]['range_max'], 0)?></div>
              
               <div class="range-value light_sea_green"><?=( isset($passed_val[$subarray_key][$sub_key]) ? $passed_val[$subarray_key][$sub_key] : '' )?></div>
              
               <div class="range-ui-prefix"><?=$render_params[$passed_key]['has_subarray'][$subarray_key]['range_ui_prefix']?></div>
              
               <div class="range-ui-suffix"><?=$render_params[$passed_key]['has_subarray'][$subarray_key]['range_ui_suffix']?></div>
              
               <div class="range-ui-meta-data"><?=$render_params[$passed_key]['has_subarray'][$subarray_key]['range_ui_meta_data']?></div>
         
                  </div>
              
                  <?php
                  if ( is_array($render_params[$passed_key]['has_subarray'][$subarray_key]['is_custom_steps']) ) {
                  ?>
          
                  <datalist id="steplist_<?=($field_array_base . '_' . $passed_key . '_' . $subarray_key . '_' . $sub_key)?>">
              
                 <?php
                 foreach ( $render_params[$passed_key]['has_subarray'][$subarray_key]['is_custom_steps'] as $custom_step ) {
                 ?>
                 <option><?=$custom_step?></option>
                 <?php
                 }
                 ?>
                  
                  </datalist>
              
                  <?php
                  }


                  if ( isset($render_params[$passed_key]['is_repeatable']['is_range']) ) {
                  $ct['repeatable_fields_tracking'][$passed_key]['is_range'] = $ct['repeatable_fields_tracking'][$passed_key]['is_range'] + 1;
                  echo '123PLACEHOLDER_RIGHT123';
                  }
                  ?>

             </div>
             
             <?php
             }
        
        
         }
         
                
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function select_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct;
   
   
        if ( !isset($ct['repeatable_fields_tracking'][$passed_key]['is_select']) ) {
        $ct['repeatable_fields_tracking'][$passed_key]['is_select'] = 0;
        }
        
        
        // If a regular select field
        if ( isset($render_params[$passed_key]['is_select']) ) {
             
             
        ?>
        
        <p>
        
        <b class='blue'><?=$ct['gen']->key_to_name($passed_key)?>:</b> &nbsp; 
        
        <select id='id_<?=md5($field_array_base . $passed_key)?>' name='<?=$field_array_base?>[<?=$passed_key?>]' <?=$onchange?>>
        
             <?php
             foreach( $render_params[$passed_key]['is_select'] as $option_key => $option_val ) {
            
                 // If it's flagged as an associative array
                 if ( $option_key === 'is_assoc' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                 
                      foreach( $render_params[$passed_key]['is_select']['is_assoc'] as $assoc_val ) {
                      ?>
                      
                      <option value='<?=$assoc_val['key']?>' <?=( $passed_val == $assoc_val['key'] ? 'selected' : '' )?>> <?=$ct['gen']->key_to_name($assoc_val['val'])?> </option> 
                      
                      <?php
                      }
                 
                 }
                 else {
                 ?>
                 
                 <option value='<?=$option_val?>' <?=( $passed_val == $option_val ? 'selected' : '' )?>> <?=$ct['gen']->key_to_name($option_val)?> </option> 
                 
                 <?php
                 }
            
             }
             ?>
             
        </select>
        
             <?php
             if ( isset($render_params[$passed_key]['is_confirm']) ) {
             ?>
             <script>
             select_confirm("id_<?=md5($field_array_base . $passed_key)?>", "<?=$render_params[$passed_key]['is_confirm']?>");
             </script>
             <?php
             }
             
             
             if ( isset($render_params[$passed_key]['is_notes']) ) {
             ?>
             <br /><i class="notes_arrow arrow_up"></i><br /><span class='admin_settings_notes bitcoin random_tip'><?=$render_params[$passed_key]['is_notes']?></span>
             <?php
             }
             ?>
             
             </p>
        
        <?php
        }
        // If IS a subarray select field 
        elseif ( is_array($render_params[$passed_key]['is_subarray'][$subarray_key]['is_select']) ) {
        ?>
        
        <p>
        
             
        <b class='blue'><?=$ct['gen']->key_to_name($subarray_key)?>:</b> &nbsp; <select data-track-index='<?=$subarray_key?>' name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>]'>
                 
             <?php
             foreach( $render_params[$passed_key]['is_subarray'][$subarray_key]['is_select'] as $setting_val ) {
             ?>
                 
             <option value='<?=$setting_val?>' <?=( isset($passed_val[$subarray_key]) && $passed_val[$subarray_key] == $setting_val ? 'selected' : '' )?> > <?=$ct['gen']->key_to_name($setting_val)?> </option>
                 
             <?php
             }
             ?>
                  
        </select>
                  
             <?php
             if ( isset($render_params[$passed_key]['is_repeatable']['is_select']) ) {
             $ct['repeatable_fields_tracking'][$passed_key]['is_select'] = $ct['repeatable_fields_tracking'][$passed_key]['is_select'] + 1;
             echo '123PLACEHOLDER_RIGHT123';
             }
             ?>
                  
        </p>
             
        <?php
        }
        // If HAS a subarray select field 
        elseif ( is_array($render_params[$passed_key]['has_subarray'][$subarray_key]['is_select']) ) {
        
        
             foreach( $render_params[$passed_key]['has_subarray'][$subarray_key]['is_select'] as $sub_key => $sub_val ) {
             ?>
        
                  <p>
                  
                 <?php
                 // If it's flagged as an associative array
                 if ( $sub_key === 'is_assoc' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                 
                 foreach( $render_params[$passed_key]['has_subarray'][$subarray_key]['is_select']['is_assoc'] as $assoc_key => $assoc_val ) {
                      //var_dump($assoc_val);
                 ?>
        
                 <b class='blue'><?=$ct['gen']->key_to_name($assoc_key)?>:</b> &nbsp; <select data-track-index='<?=$subarray_key?>' name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>][<?=$assoc_key?>]'>
                 
                      <?php
                      foreach( $assoc_val as $setting_val ) {
                      ?>
                      
                      <option value='<?=$setting_val['key']?>' <?=( isset($passed_val[$subarray_key][$assoc_key]) && $passed_val[$subarray_key][$assoc_key] == $setting_val['key'] ? 'selected' : '' )?> > <?=$setting_val['val']?> </option>
                           
                      <?php
                      }
                      ?>
                  
                  </select>
                  
                      <?php
                      if ( isset($render_params[$passed_key]['is_repeatable']['is_select']) ) {
                      $ct['repeatable_fields_tracking'][$passed_key]['is_select'] = $ct['repeatable_fields_tracking'][$passed_key]['is_select'] + 1;
                      echo '123PLACEHOLDER_RIGHT123';
                      }
                      ?>
                  
              </p>
             
                 
                 <?php 
                 }
                 
                 }
                 else {
                 ?>
        
                 <b class='blue'><?=$ct['gen']->key_to_name($sub_key)?>:</b> &nbsp; <select data-track-index='<?=$subarray_key?>' name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>][<?=$sub_key?>]'>
                 
                 <?php
                 foreach( $sub_val as $setting_val ) {
                 ?>
                 
                 <option value='<?=$setting_val?>' <?=( isset($passed_val[$subarray_key][$sub_key]) && $passed_val[$subarray_key][$sub_key] == $setting_val ? 'selected' : '' )?> > <?=$ct['gen']->key_to_name($setting_val)?> </option>
                      
                 <?php
                 }
                 
                 }
                 ?>
                  
                  </select>
                  
                 <?php
                 if ( isset($render_params[$passed_key]['is_repeatable']['is_select']) ) {
                 $ct['repeatable_fields_tracking'][$passed_key]['is_select'] = $ct['repeatable_fields_tracking'][$passed_key]['is_select'] + 1;
                 echo '123PLACEHOLDER_RIGHT123';
                 }
                 ?>
                  
                  </p>
             
             <?php
             }
             
        }
        
        
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function repeatable_fields_template($field_array_base, $passed_key, $passed_val, $render_params) {
        
   global $ct;
              
   $subarray_class = $field_array_base . '_' . $passed_key;
   
        
        // If an add / remove (repeatable) setup
        if ( is_array($render_params[$passed_key]['is_repeatable']) ) {
        ?>
        
        <div class='subarray_item subarray_<?=$subarray_class?> <?=( isset($render_params[$passed_key]['is_repeatable']['compact_margins']) ? 'compact_margins' : '' )?>'>
        
             <?php
             // Subarray data can be mixed types of form fields, SO ALL CHECKS ARE 'IF' STATEMENTS
             foreach( $render_params[$passed_key]['is_repeatable'] as $sub_key => $sub_val ) {
   
   
                  // Tracking for rendering remove button
                  if ( !isset($ct['repeatable_fields_tracking'][$passed_key][$sub_key]) ) {
                  $ct['repeatable_fields_tracking'][$passed_key][$sub_key] = 0;
                  }
             
             
                  if ( $sub_key === 'is_radio' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                  
                  $is_string_keys = false; // RESET
                     
        
                  foreach( $render_params[$passed_key]['is_repeatable']['is_select'] as $sub2_key => $sub2_val ) {
                  
                  // Tracking for rendering remove button
                  $ct['repeatable_fields_tracking'][$passed_key][$sub_key] = $ct['repeatable_fields_tracking'][$passed_key][$sub_key] + 1;

                  // Add radio button logic here   
                  
                  }
                  
                  
                  }
                  
                  
                  if ( $sub_key === 'is_select' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                  
                  $is_string_keys = false; // RESET
                     
                 
                  // If it's flagged as an associative array
                  if ( is_array($render_params[$passed_key]['is_repeatable']['is_select']['is_assoc']) ) {
                      
                      foreach( $render_params[$passed_key]['is_repeatable']['is_select']['is_assoc'] as $assoc_key => $assoc_val ) {
                  
                      // Tracking for rendering remove button
                      $ct['repeatable_fields_tracking'][$passed_key][$sub_key] = $ct['repeatable_fields_tracking'][$passed_key][$sub_key] + 1;
                       
                      ?>
                  
                      <p>
             
                      <b class='blue'><?=$ct['gen']->key_to_name($assoc_key)?>:</b> &nbsp; <select data-track-index='{?}' name='<?=$field_array_base?>[<?=$passed_key?>][{?}][<?=$assoc_key?>]'>
                      
                           <?php
                           foreach( $assoc_val as $setting_val ) {
                           ?>
                           
                           <option value='<?=$setting_val['key']?>' <?=( isset($passed_val[$subarray_key][$assoc_key]) && $passed_val[$subarray_key][$assoc_key] == $setting_val['key'] ? 'selected' : '' )?> > <?=$setting_val['val']?> </option>
                                
                           <?php
                           }
                           ?>
                            
                            </select>
                           
                           123PLACEHOLDER_RIGHT123
                            
                            </p>
                           
                      <?php
                      }
                      
                  }
                  else {
                       
                       foreach( $render_params[$passed_key]['is_repeatable']['is_select'] as $sub2_key => $sub2_val ) {
                  
                       // Tracking for rendering remove button
                       $ct['repeatable_fields_tracking'][$passed_key][$sub_key] = $ct['repeatable_fields_tracking'][$passed_key][$sub_key] + 1;
                      
                       ?>
                  
                            <p>
                       
                            <b class='blue'><?=$ct['gen']->key_to_name($sub2_key)?>:</b> &nbsp; <select data-track-index='{?}' name='<?=$field_array_base?>[<?=$passed_key?>][{?}][<?=$sub2_key?>]'>
                                
                                <?php
                                foreach( $sub2_val as $setting_val ) {
                                ?>
                                
                                <option value='<?=$setting_val?>'> <?=$ct['gen']->key_to_name($setting_val)?> </option>
                                
                                <?php
                                }
                                ?>
                            
                            </select>
                           
                           123PLACEHOLDER_RIGHT123
                            
                            </p>
                       
                       <?php
                       }
                      
                  }
             
                  }
                  
                  
                  if ( $sub_key === 'is_textarea' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                  
                  $is_string_keys = false; // RESET
                      
                      
                       // If string keyed array, show description from key value
                       // (do scanning BEFORE loop, for speed)
                       if ( $ct['gen']->has_string_keys($render_params[$passed_key]['is_repeatable']['is_textarea']) ) {
                       $is_string_keys = true;
                       }
                           
             
                       foreach( $render_params[$passed_key]['is_repeatable']['is_textarea'] as $sub2_key => $unused ) {
     
                           
                           // If string keyed array, show description from key value
                           if ( $is_string_keys ) {
                           $desc = '<b class="blue">' . $ct['gen']->key_to_name($sub2_key) . ':</b> &nbsp; ';
                           }
                       
                       
                       // Tracking for rendering remove button
                       $ct['repeatable_fields_tracking'][$passed_key][$sub_key] = $ct['repeatable_fields_tracking'][$passed_key][$sub_key] + 1;
                      
                       ?>
                       
                            <p>
                       
                       
                            <?=$desc?> <textarea data-track-index='{?}' data-autoresize name='<?=$field_array_base?>[<?=$passed_key?>][{?}][<?=$sub2_key?>]' style='height: auto; width: 100%;' <?=( isset($render_params[$passed_key]['is_repeatable']['is_password']) ? 'class="textarea_password" onblur="$(this).toggleClass(\'textarea_password\');autoresize_update();" onfocus="$(this).toggleClass(\'textarea_password\');autoresize_update();"' : '' )?>></textarea>
                           
                           123PLACEHOLDER_RIGHT123
                            
                            </p>
                       
                       <?php
                       }
             
                  }
                  
                  
                  if ( $sub_key === 'is_color' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                  
                  $is_string_keys = false; // RESET
                      
                      
                       // If string keyed array, show description from key value
                       // (do scanning BEFORE loop, for speed)
                       if ( $ct['gen']->has_string_keys($render_params[$passed_key]['is_repeatable']['is_color']) ) {
                       $is_string_keys = true;
                       }
                           
             
                       foreach( $render_params[$passed_key]['is_repeatable']['is_color'] as $sub2_key => $unused ) {
     
                           
                           // If string keyed array, show description from key value
                           if ( $is_string_keys ) {
                           $desc = '<b class="blue">' . $ct['gen']->key_to_name($sub2_key) . ':</b> &nbsp; ';
                           }
                       
                       
                       // Tracking for rendering remove button
                       $ct['repeatable_fields_tracking'][$passed_key][$sub_key] = $ct['repeatable_fields_tracking'][$passed_key][$sub_key] + 1;
                      
                       ?>
                       
                            <p>
                       
                       
                            <?=$desc?> <input type='color' data-track-index='{?}' name='<?=$field_array_base?>[<?=$passed_key?>][{?}][<?=$sub2_key?>]' value='' />
                           
                           123PLACEHOLDER_RIGHT123
                            
                            </p>
                       
                       <?php
                       }
             
                  }
                  
                  
                  if ( $sub_key === 'is_text' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                  
                  $is_string_keys = false; // RESET
                  
                 // Array of values
                 if ( is_array($render_params[$passed_key]['is_repeatable']['is_text']) ) {

                      
                      // If string keyed array, show description from key value
                      // (do scanning BEFORE loop, for speed)
                      if ( $ct['gen']->has_string_keys($render_params[$passed_key]['is_repeatable']['is_text']) ) {
                      $is_string_keys = true;
                      }
     
     
                      foreach( $render_params[$passed_key]['is_repeatable']['is_text'] as $deep_sub_key => $unused ) {
                      
                           // If string keyed array, show description from key value
                           if ( $is_string_keys ) {
                           $desc = '<b class="blue">' . $ct['gen']->key_to_name($deep_sub_key) . ':</b> &nbsp; ';
                           }
                  
                      // Tracking for rendering remove button
                      $ct['repeatable_fields_tracking'][$passed_key][$sub_key] = $ct['repeatable_fields_tracking'][$passed_key][$sub_key] + 1;
                      
                      ?>
                       
                      <p>
                           
                          <?=$desc?> <input data-track-index='{?}' type='text' name='<?=$field_array_base?>[<?=$passed_key?>][{?}][<?=$deep_sub_key?>]' value='' <?=( isset($render_params[$passed_key]['is_repeatable']['text_field_size']) ? ' size="' . $render_params[$passed_key]['is_repeatable']['text_field_size'] . '"' : '' )?> /> 
                           
                           123PLACEHOLDER_RIGHT123
               
                      </p>
                       
                      <?php
                      }
                 
                 
                 }
                 // Single value
                 else {
     
                      // Tracking for rendering remove button
                      $ct['repeatable_fields_tracking'][$passed_key][$sub_key] = $ct['repeatable_fields_tracking'][$passed_key][$sub_key] + 1;
                      
                      ?>
                       
                      <p>
                           
                          <input data-track-index='{?}' type='text' name='<?=$field_array_base?>[<?=$passed_key?>][{?}]' value='' <?=( isset($render_params[$passed_key]['is_repeatable']['text_field_size']) ? ' size="' . $render_params[$passed_key]['is_repeatable']['text_field_size'] . '"' : '' )?> /> 
                           
                           123PLACEHOLDER_RIGHT123
               
                      </p>
                       
                      <?php
                 
                 }
                 

                  }
                  
                  
                  if ( $sub_key === 'is_range' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                  
                  $is_string_keys = false; // RESET
                  
                 // Array of values
                 if ( is_array($render_params[$passed_key]['is_repeatable']['is_range']) ) {

                      
                      // If string keyed array, show description from key value
                      // (do scanning BEFORE loop, for speed)
                      if ( $ct['gen']->has_string_keys($render_params[$passed_key]['is_repeatable']['is_range']) ) {
                      $is_string_keys = true;
                      }
     
     
                      foreach( $render_params[$passed_key]['is_repeatable']['is_range'] as $sub_key2 => $unused ) {
                      
                           // If string keyed array, show description from key value
                           if ( $is_string_keys ) {
                           $desc = '<b class="blue">' . $ct['gen']->key_to_name($sub_key2) . ':</b> &nbsp; ';
                           }
                  
                      // Tracking for rendering remove button
                      $ct['repeatable_fields_tracking'][$passed_key][$sub_key] = $ct['repeatable_fields_tracking'][$passed_key][$sub_key] + 1;
                      
                      ?>
          
                     <div class='admin_range_fields'>
          
                   
                        <div class="range-title blue setting_title"><?=$desc?></div>
                      
                        <div class="range-wrap">
                      
                          <div class="range-tooltip"></div>
                        
                          <div class="range-min light_sea_green"><?=$ct['var']->num_pretty($render_params[$passed_key]['is_repeatable']['range_min'], 0)?></div>
                   
                          <input data-track-index='{?}' type='range' class='range-field' min="<?=$render_params[$passed_key]['is_repeatable']['range_min']?>" max="<?=$render_params[$passed_key]['is_repeatable']['range_max']?>" step="<?=$render_params[$passed_key]['is_repeatable']['range_step']?>" name='<?=$field_array_base?>[<?=$passed_key?>][{?}][<?=$sub_key2?>]' value='' <?=( is_array($render_params[$passed_key]['is_repeatable']['is_custom_steps']) ? 'list="steplist_'. $field_array_base . '_' . $passed_key . '_{?}_' . $sub_key2 . '"' : '' )?> /> 
         
                          <div class="range-max light_sea_green"><?=$ct['var']->num_pretty($render_params[$passed_key]['is_repeatable']['range_max'], 0)?></div>
                        
                          <div class="range-value light_sea_green"></div>
                        
                          <div class="range-ui-prefix"><?=$render_params[$passed_key]['is_repeatable']['range_ui_prefix']?></div>
                        
                          <div class="range-ui-suffix"><?=$render_params[$passed_key]['is_repeatable']['range_ui_suffix']?></div>
                        
                          <div class="range-ui-meta-data"><?=$render_params[$passed_key]['is_repeatable']['range_ui_meta_data']?></div>
              
                       </div>
              
                       <?php
                       if ( is_array($render_params[$passed_key]['is_repeatable']['is_custom_steps']) ) {
                       ?>
               
                       <datalist id="steplist_<?=($field_array_base . '_' . $passed_key . '_{?}_' . $sub_key2)?>">
                   
                           <?php
                           foreach ( $render_params[$passed_key]['is_repeatable']['is_custom_steps'] as $custom_step ) {
                           ?>
                           <option><?=$custom_step?></option>
                           <?php
                           }
                           ?>
                       
                       </datalist>
                   
                       <?php
                       }
                       ?>
              
                           
                           123PLACEHOLDER_RIGHT123
               
                     </div>
                       
                      <?php
                      }
                 
                 
                 }
                 // Single value
                 else {
     
                      // Tracking for rendering remove button
                      $ct['repeatable_fields_tracking'][$passed_key][$sub_key] = $ct['repeatable_fields_tracking'][$passed_key][$sub_key] + 1;
                      
                      ?>
          
                     <div class='admin_range_fields'>
          
                   
                        <div class="range-title blue setting_title"></div>
                      
                        <div class="range-wrap">
                      
                          <div class="range-tooltip"></div>
                        
                          <div class="range-min light_sea_green"><?=$ct['var']->num_pretty($render_params[$passed_key]['is_repeatable']['range_min'], 0)?></div>
                    
                          <input data-track-index='{?}' type='range' class='range-field' min="<?=$render_params[$passed_key]['is_repeatable']['range_min']?>" max="<?=$render_params[$passed_key]['is_repeatable']['range_max']?>" step="<?=$render_params[$passed_key]['is_repeatable']['range_step']?>" name='<?=$field_array_base?>[<?=$passed_key?>][{?}]' value='' <?=( is_array($render_params[$passed_key]['is_repeatable']['is_custom_steps']) ? 'list="steplist_'. $field_array_base . '_' . $passed_key . '_{?}"' : '' )?> /> 
         
                          <div class="range-max light_sea_green"><?=$ct['var']->num_pretty($render_params[$passed_key]['is_repeatable']['range_max'], 0)?></div>
                        
                          <div class="range-value light_sea_green"></div>
                        
                          <div class="range-ui-prefix"><?=$render_params[$passed_key]['is_repeatable']['range_ui_prefix']?></div>
                        
                          <div class="range-ui-suffix"><?=$render_params[$passed_key]['is_repeatable']['range_ui_suffix']?></div>
                        
                          <div class="range-ui-meta-data"><?=$render_params[$passed_key]['is_repeatable']['range_ui_meta_data']?></div>
              
                       </div>
              
                       <?php
                       if ( is_array($render_params[$passed_key]['is_repeatable']['is_custom_steps']) ) {
                       ?>
               
                       <datalist id="steplist_<?=($field_array_base . '_' . $passed_key . '_{?}')?>">
                   
                           <?php
                           foreach ( $render_params[$passed_key]['is_repeatable']['is_custom_steps'] as $custom_step ) {
                           ?>
                           <option><?=$custom_step?></option>
                           <?php
                           }
                           ?>
                       
                       </datalist>
                   
                       <?php
                       }
                       ?>
              
                           
                           123PLACEHOLDER_RIGHT123
               
                     </div>
                       
                      <?php
                 
                 }
                 

                  }
                  
             }
             ?>
        
        </div>
        
    <?php
        }
        
        
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>