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
   
   
   // (must be carefully checked)
   function remove_markets($field_array_base) {
        
   global $ct;
                       
                      
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // (must be carefully checked)
   function add_markets($field_array_base) {
        
   global $ct;
                       
                      
          foreach ( $field_array_base as $submitted_asset_key => $submitted_asset_val ) {
          
          
               // If the asset doesn't exist
               if ( !isset($ct['conf']['assets'][$submitted_asset_key]) ) {
               $ct['conf']['assets'][$submitted_asset_key] = $submitted_asset_val;
               }
               else {
                    
                    // We allow updating 'name' / 'mcap_slug', if either are blank...
               
                    if ( trim($ct['conf']['assets'][$submitted_asset_key]['name']) == '' ) {
                    $ct['conf']['assets'][$submitted_asset_key]['name'] = $submitted_asset_val['name'];
                    }
               
               
                    if ( trim($ct['conf']['assets'][$submitted_asset_key]['mcap_slug']) == '' ) {
                    $ct['conf']['assets'][$submitted_asset_key]['mcap_slug'] = $submitted_asset_val['mcap_slug'];
                    }
                    
                    
                    // Check data in pairings
                    foreach ( $submitted_asset_val['pair'] as $pairing_key => $pairing_val ) {
                    
                    
                        // If the pairing doesn't exist
                        if ( !isset($ct['conf']['assets'][$submitted_asset_key]['pair'][$pairing_key]) ) {
                        $ct['conf']['assets'][$submitted_asset_key]['pair'][$pairing_key] = $pairing_val;
                        }
                        else {
                        
                        
                             // We allow overwriting old exchange markets, so we can safely add them all
                             foreach ( $pairing_val as $exchange_key => $exchange_val ) {
                             $ct['conf']['assets'][$submitted_asset_key]['pair'][$pairing_key][$exchange_key] = $exchange_val;
                             }
                             
                        
                        }
                        
                        
                    }
                    
               
               }
          
          
          // Sanitize name value, make sure first character is uppercase
          $ct['conf']['assets'][$submitted_asset_key]['name'] = ucfirst( filter_var($ct['conf']['assets'][$submitted_asset_key]['name'], FILTER_SANITIZE_STRING) );
                  
          }
         
   
   }

   
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
        
        $valid_existing_base = isset($ct['conf']['plug_conf'][ $parse_plugin_name[1] ]);

        }
        elseif ( isset($_POST['conf_id']) ) {
             
        $field_array_base = $_POST[ $_POST['conf_id'] ];
        
        $valid_existing_base = isset($ct['conf'][ $_POST['conf_id'] ]);
        
        }
        else {
        return false;
        }
      
        
        // Make sure ALL security checks pass / data structure seems valid, for updating the admin config
        // (INCLUDES 'STRICT' 2FA MODE CHECK [returns true if 'strict' 2fa is turned off, OR 'strict' 2fa checked out as valid])
        if (
        $valid_existing_base
        && isset($_POST['conf_id'])
        && isset($_POST['interface_id'])
        && is_array($field_array_base)
        && $ct['gen']->pass_sec_check($_POST['admin_nonce'], $_POST['interface_id'])
        && $ct['gen']->valid_2fa('strict')
        ) {
        return $field_array_base;
        }
        else {
        return false;
        }
        
   
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
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-general.php');
        }
        elseif ( $_POST['conf_id'] === 'comms' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-comms.php');
        }
        elseif ( $_POST['conf_id'] === 'ext_apis' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-ext-apis.php');
        }
        elseif ( $_POST['conf_id'] === 'sec' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-security.php');
        }
        elseif ( $_POST['conf_id'] === 'news' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-news.php');
        }
        elseif ( $_POST['conf_id'] === 'proxy' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-proxy.php');
        }
        elseif ( $_POST['conf_id'] === 'mobile_network' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-mobile-network.php');
        }
        elseif ( $_POST['conf_id'] === 'charts_alerts' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-charts-alerts.php');
        }
        elseif ( $_POST['conf_id'] === 'currency' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-currency.php');
        }
        elseif ( $_POST['conf_id'] === 'power' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-power.php');
        }
        elseif ( $_POST['conf_id'] === 'assets' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
        require($ct['base_dir'] . '/app-lib/php/classes/core/includes/admin/input-validation-assets.php');
        }
        
        
   return ( isset($ct['update_config_error']) && trim($ct['update_config_error']) != '' ? false : true );
        
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
              
               
              // Update the corresponding admin config section, IF VALID SETTING VALUES
              if ( $this->valid_admin_settings() ) {
                  
                  
                  // Plugins (can fully overwrite)
                  if ( $is_plugin_config ) {
                  $ct['conf']['plug_conf'][ $parse_plugin_name[1] ] = $field_array_base;
                  }
                  // Adding / removing assets and markets
                  // PHP7.4 NEEDS === HERE INSTEAD OF ==
                  elseif ( $_POST['conf_id'] === 'assets' ) {
                       
                       
                       if (
                       $_POST['markets_update'] === 'add'
                       && $ct['gen']->pass_sec_check($_POST['markets_nonce'], $_POST['markets_update'])
                       ) {
                       $this->add_markets($field_array_base);
                       }
                       elseif (
                       $_POST['markets_update'] === 'remove'
                       && $ct['gen']->pass_sec_check($_POST['markets_nonce'], $_POST['markets_update'])
                       ) {
                       $this->remove_markets($field_array_base);
                       }
                       else {
                       $ct['gen']->log('conf_error', '"markets_nonce" MISMATCH on SECURITY check against "markets_update" (assets update mode): ' . $_POST['markets_update']);
                       }
          
          
                  }
                  // Everything else (can fully overwrite)
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
         if ( isset($render_params[$passed_key]['is_notes']) ) {
         ?>
          
         <p><span class='admin_settings_notes red red_dotted'><?=$render_params[$passed_key]['is_notes']?></span></p>
              
         <?php
         }
              
                
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
   
	<?php
	
	if ( $is_plugin_config ) {
	$cat_key = 'plugin';
	}
	elseif ( $ct['is_subsection_config'] ) {
	$cat_key = 'parent=' . $_GET['parent'] . '&subsection';
	}
	else {
	$cat_key = 'section';
	}
	
	?>
	
	<p class='save_notice red red_dotted'>Click "Save Admin Changes" in the NAVIGATION MENU, to SAVE the changes you have made in this section (when you are finished).</p>
	
	<form name='update_config' id='update_config' action='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_' . $interface_id)?>&<?=$cat_key?>=<?=$interface_id?>&refresh=<?=$refresh_admin_sections?>' method='post'>
     
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
                                
                                
                                  // Wait 1.5 seconds before Initiating
                                  // (otherwise ELEMENT SIZES / ETC aren't always registered yet for DOM manipulations)
                                  setTimeout(function(){
                                       
               			
                    			    // Make any added textarea autosize
                                       $('textarea[data-autoresize]').each(function(){
                                       autosize(this);
                                       }).on('autosize:resized', function(){
                                        
                                            // Resize admin iframes after resizing textareas
                                            admin_iframe_dom.forEach(function(iframe) {
                                            iframe_size_adjust(iframe);
                                            });
                                        
                                       });
                                        
                                            
                                       // Resize admin iframes after adding repeatable elements
                                       admin_iframe_dom.forEach(function(iframe) {
                                       iframe_size_adjust(iframe);
                                       });
                                       
                                       
                                  init_range_sliders();
                                  
                                  }, 1500);

                         
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
	
	<p class='save_notice red red_dotted'>Click "Save Admin Changes" in the NAVIGATION MENU, to SAVE the changes you have made in this section (when you are finished).</p>
	
     
   </div>
   
   <?=$subarray_css?>
   
   <?php
   
   
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