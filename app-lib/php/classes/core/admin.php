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
      
        
        // Make sure security checks pass / data seems valid for updating the admin config (STRICT 2FA MODE ONLY)
        if ( isset($_POST['conf_id']) && isset($_POST['interface_id']) && is_array($field_array_base) && $ct['gen']->pass_sec_check($_POST['admin_hashed_nonce'], $_POST['interface_id']) && $ct['gen']->valid_2fa('strict') ) {
        return $field_array_base;
        }
        else {
        return false;
        }
        
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function repeatable_fields_template($field_array_base, $passed_key, $passed_val, $render_params) {
        
   global $ct, $repeatable_fields_tracking;
              
   $subarray_class = $field_array_base . '_' . $passed_key;
   
        
        // If an add / remove (repeatable) setup
        if ( is_array($render_params[$passed_key]['is_repeatable']) ) {
        ?>
        
        <div class='subarray_item subarray_<?=$subarray_class?> <?=( isset($render_params[$passed_key]['is_repeatable']['compact_margins']) ? 'compact_margins' : '' )?>'>
        
             <?php
             // Subarray data can be mixed types of form fields, SO ALL CHECKS ARE 'IF' STATEMENTS
             foreach( $render_params[$passed_key]['is_repeatable'] as $sub_key => $sub_val ) {
   
   
                  // Tracking for rendering remove button
                  if ( !isset($repeatable_fields_tracking[$passed_key][$sub_key]) ) {
                  $repeatable_fields_tracking[$passed_key][$sub_key] = 0;
                  }
             
             
                  if ( $sub_key === 'is_radio' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                  
                  $is_string_keys = false; // RESET
                     
        
                  foreach( $render_params[$passed_key]['is_repeatable']['is_select'] as $sub2_key => $sub2_val ) {
                  
                  // Tracking for rendering remove button
                  $repeatable_fields_tracking[$passed_key][$sub_key] = $repeatable_fields_tracking[$passed_key][$sub_key] + 1;

                  // Add radio button logic here   
                  
                  }
                  
                  
                  }
                  
                  
                  if ( $sub_key === 'is_select' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                  
                  $is_string_keys = false; // RESET
                     
                 
                  // If it's flagged as an associative array
                  if ( is_array($render_params[$passed_key]['is_repeatable']['is_select']['is_assoc']) ) {
                      
                      foreach( $render_params[$passed_key]['is_repeatable']['is_select']['is_assoc'] as $assoc_key => $assoc_val ) {
                  
                      // Tracking for rendering remove button
                      $repeatable_fields_tracking[$passed_key][$sub_key] = $repeatable_fields_tracking[$passed_key][$sub_key] + 1;
                       
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
                       $repeatable_fields_tracking[$passed_key][$sub_key] = $repeatable_fields_tracking[$passed_key][$sub_key] + 1;
                      
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
                  $repeatable_fields_tracking[$passed_key][$sub_key] = $repeatable_fields_tracking[$passed_key][$sub_key] + 1;
                 
                  ?>
                  
                       <p>
                  
                  
                       <?=$desc?> <textarea data-track-index='{?}' data-autoresize name='<?=$field_array_base?>[<?=$passed_key?>][{?}][<?=$sub2_key?>]' style='height: auto; width: 100%;' <?=( isset($render_params[$passed_key]['is_repeatable']['is_password']) ? 'class="textarea_password" onblur="$(this).toggleClass(\'textarea_password\');autoresize_update();" onfocus="$(this).toggleClass(\'textarea_password\');autoresize_update();"' : '' )?>></textarea>
                      
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
     
     
                      foreach( $render_params[$passed_key]['is_repeatable']['is_text'] as $sub_key2 => $unused ) {
                      
                           // If string keyed array, show description from key value
                           if ( $is_string_keys ) {
                           $desc = '<b class="blue">' . $ct['gen']->key_to_name($sub_key2) . ':</b> &nbsp; ';
                           }
                  
                      // Tracking for rendering remove button
                      $repeatable_fields_tracking[$passed_key][$sub_key] = $repeatable_fields_tracking[$passed_key][$sub_key] + 1;
                      
                      ?>
                       
                      <p>
                           
                          <?=$desc?> <input data-track-index='{?}' type='text' name='<?=$field_array_base?>[<?=$passed_key?>][{?}][<?=$sub_key2?>]' value='' <?=( isset($render_params[$passed_key]['is_repeatable']['text_field_size']) ? ' size="' . $render_params[$passed_key]['is_repeatable']['text_field_size'] . '"' : '' )?> /> 
                           
                           123PLACEHOLDER_RIGHT123
               
                      </p>
                       
                      <?php
                      }
                 
                 
                 }
                 // Single value
                 else {
     
                      // Tracking for rendering remove button
                      $repeatable_fields_tracking[$passed_key][$sub_key] = $repeatable_fields_tracking[$passed_key][$sub_key] + 1;
                      
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
                      $repeatable_fields_tracking[$passed_key][$sub_key] = $repeatable_fields_tracking[$passed_key][$sub_key] + 1;
                      
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
                      $repeatable_fields_tracking[$passed_key][$sub_key] = $repeatable_fields_tracking[$passed_key][$sub_key] + 1;
                      
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
   
   
   function textarea_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct, $repeatable_fields_tracking;
   
   
        if ( !isset($repeatable_fields_tracking[$passed_key]['is_textarea']) ) {
        $repeatable_fields_tracking[$passed_key]['is_textarea'] = 0;
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
                  $repeatable_fields_tracking[$passed_key]['is_textarea'] = $repeatable_fields_tracking[$passed_key]['is_textarea'] + 1;
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
                  $repeatable_fields_tracking[$passed_key]['is_textarea'] = $repeatable_fields_tracking[$passed_key]['is_textarea'] + 1;
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
   
   
   function range_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct, $repeatable_fields_tracking;
   
   
         if ( !isset($repeatable_fields_tracking[$passed_key]['is_range']) ) {
         $repeatable_fields_tracking[$passed_key]['is_range'] = 0;
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
                  $repeatable_fields_tracking[$passed_key]['is_range'] = $repeatable_fields_tracking[$passed_key]['is_range'] + 1;
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
                  $repeatable_fields_tracking[$passed_key]['is_range'] = $repeatable_fields_tracking[$passed_key]['is_range'] + 1;
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
   
   
   function text_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct, $repeatable_fields_tracking;
   
   
         if ( !isset($repeatable_fields_tracking[$passed_key]['is_text']) ) {
         $repeatable_fields_tracking[$passed_key]['is_text'] = 0;
         }
              
              
         if ( isset($render_params[$passed_key]['is_trim']) ) {
         $passed_val = trim($passed_val);
         }
         
         
         // If a regular text field (NOT a subarray)
         if ( !isset($render_params[$passed_key]['is_subarray'][$subarray_key]['is_text']) && !isset($render_params[$passed_key]['has_subarray'][$subarray_key]['is_text']) ) {


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
                  $repeatable_fields_tracking[$passed_key]['is_text'] = $repeatable_fields_tracking[$passed_key]['is_text'] + 1;
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
              
              
             foreach( $render_params[$passed_key]['has_subarray'][$subarray_key]['is_text'] as $sub_key => $unused ) {
                      
                  // If string keyed array, show description from key value
                  if ( $is_string_keys ) {
                  $desc = '<b class="blue">' . $ct['gen']->key_to_name($sub_key) . ':</b> &nbsp; ';
                  }
                  
             ?>
             
             <p>
         
                  <?=$desc?> <input data-track-index='<?=$subarray_key?>' type='text' name='<?=$field_array_base?>[<?=$passed_key?>][<?=$subarray_key?>][<?=$sub_key?>]' value='<?=( isset($passed_val[$subarray_key][$sub_key]) ? $passed_val[$subarray_key][$sub_key] : '' )?>' <?=( isset($render_params[$passed_key]['has_subarray'][$subarray_key]['text_field_size']) ? ' size="' . $render_params[$passed_key]['has_subarray'][$subarray_key]['text_field_size'] . '"' : '' )?> />
                  
                  <?php
                  if ( isset($render_params[$passed_key]['is_repeatable']['is_text']) ) {
                  $repeatable_fields_tracking[$passed_key]['is_text'] = $repeatable_fields_tracking[$passed_key]['is_text'] + 1;
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
   
   
   function select_form_fields($field_array_base, $passed_key, $passed_val, $render_params, $subarray_key=false) {
        
   global $ct, $repeatable_fields_tracking;
   
   
        if ( !isset($repeatable_fields_tracking[$passed_key]['is_select']) ) {
        $repeatable_fields_tracking[$passed_key]['is_select'] = 0;
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
             $repeatable_fields_tracking[$passed_key]['is_select'] = $repeatable_fields_tracking[$passed_key]['is_select'] + 1;
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
                      $repeatable_fields_tracking[$passed_key]['is_select'] = $repeatable_fields_tracking[$passed_key]['is_select'] + 1;
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
                 $repeatable_fields_tracking[$passed_key]['is_select'] = $repeatable_fields_tracking[$passed_key]['is_select'] + 1;
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
        
   global $ct, $repeatable_fields_tracking;
   
   
        if ( !isset($repeatable_fields_tracking[$passed_key]['is_radio']) ) {
        $repeatable_fields_tracking[$passed_key]['is_radio'] = 0;
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
               $repeatable_fields_tracking[$passed_key]['is_radio'] = $repeatable_fields_tracking[$passed_key]['is_radio'] + 1;
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
        
   global $ct, $repeatable_fields_tracking, $update_config_success, $update_config_error, $usort_alpha;
   
   
      if ( !is_array($render_params) ) {
      return false;
      }
      
      
      if ( preg_match('/plug_conf\|/', $conf_id) ) {
           
      $is_plugin_config = true;
           
      $parse_plugin_data = explode('|', $conf_id);
      
      $field_array_base = $parse_plugin_data[1];
      
      $config_array_base = $ct['conf']['plug_conf'][ $parse_plugin_data[1] ];
      
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
   
	
	<form name='update_config' id='update_config' action='admin.php?iframe=<?=$ct['gen']->admin_hashed_nonce('iframe_' . $interface_id)?>&<?=( $is_plugin_config ? 'plugin' : 'section' )?>=<?=$interface_id?>&refresh=<?=$refresh_admin_sections?>' method='post'>
     
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
         // Textareas
         elseif ( isset($render_params[$key]['is_textarea']) ) {
         $this->textarea_form_fields($field_array_base, $key, $val, $render_params);
         }
         // Ranges
         elseif ( isset($render_params[$key]['is_range']) ) {
         $this->range_form_fields($field_array_base, $key, $val, $render_params);
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
	         $repeatable_fields_tracking[$key] = array(); // Reset counts              
                             
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
              
              // Textarea fields in subarray
              if ( isset($render_params[$key][$subarray_type][$subarray_key]['is_textarea']) ) {
              $this->textarea_form_fields($field_array_base, $key, $val, $render_params, $subarray_key);
              }
              
	         
	         $rendered_form_fields = ob_get_contents();

	         ob_end_clean();
	          
	          
	              foreach ( $repeatable_fields_tracking[$key] as $count_val ) {
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
	          $repeatable_fields_tracking[$key] = array(); // Reset counts
               
               ob_start();
               
	          $this->repeatable_fields_template($field_array_base, $key, $val, $render_params);

	          $repeatable_template = ob_get_contents();

	          ob_end_clean();
	          
	          //var_dump($repeatable_fields_tracking);
	          
	          
	                foreach ( $repeatable_fields_tracking[$key] as $count_val ) {
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
         // Everything else should just render as a text field
         else {
         $this->text_form_fields($field_array_base, $key, $val, $render_params);
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
   
   <?=$subarray_css?>
   
   <?php
   
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function queue_config_update() {
        
   global $ct, $app_upgrade_check, $reset_config, $update_config, $check_2fa_error, $update_config_error, $update_config_success;
   
   // Check for VALIDATED / SECURE config updates IN PROGRESS
   $field_array_base = $this->valid_secure_config_update_request();
      
      
        if ( $app_upgrade_check ) {
        $update_config_halt = 'The app is busy UPGRADING it\'s cached config, please wait a minute and try again.';
        }
        else if ( $reset_config ) {
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
        
          
              // ADD VALIDATION CHECKS HERE, BEFORE ALLOWING UPDATE OF THIS CONFIG SECTION
              if ( $_POST['conf_id'] === 'gen' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                   
                   
                 // Make sure primary currency conversion params are set properly
                 if ( !$ct['conf']['assets']['BTC']['pair'][ $_POST['gen']['bitcoin_primary_currency_pair'] ][ $_POST['gen']['bitcoin_primary_currency_exchange'] ] ) {
                 $update_config_error = 'Bitcoin Primary Exchange "' . $ct['gen']->key_to_name($_POST['gen']['bitcoin_primary_currency_exchange']) . '" does NOT have a "' . strtoupper($_POST['gen']['bitcoin_primary_currency_pair']) . '" market';
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
                  
                  if ( $is_plugin_config ) {
                  $ct['conf']['plug_conf'][ $parse_plugin_name[1] ] = $field_array_base;
                  }
                  else {
                  $ct['conf'][ $_POST['conf_id'] ] = $field_array_base;
                  }
               
              $update_config = true; // Triggers saving updated config to disk
              
              $update_config_success = 'Updating of "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" ' . $update_desc . ' settings SUCCEEDED.';
              
              }
              else {
              $update_config_error = 'Updating of "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" ' . $update_desc . ' settings FAILED. ' . $update_config_error;
              }
              
               
        }
        // General error messages to display at top of page for UX
        elseif ( isset($_POST['conf_id']) && isset($_POST['interface_id']) ) {
          
              if ( $check_2fa_error ) {
              $update_config_error =  'Updating of "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" ' . $update_desc . ' settings FAILED. ' . $check_2fa_error . '.';
              }
              else if ( $update_config_halt ) {
              $update_config_error =  'Updating of "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" ' . $update_desc . ' settings FAILED. ' . $update_config_halt;
              }
          
        }
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>