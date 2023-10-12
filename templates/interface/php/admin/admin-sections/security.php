<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>
	    

	<!-- ADMIN PAGES SECURITY LEVEL START -->

	<div class='blue_dotted'>
	
	
	<form name='toggle_admin_security' id='toggle_admin_security' action='admin.php?iframe=<?=$ct['gen']->admin_hashed_nonce('iframe_security')?>&section=security&refresh=all' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct['gen']->admin_hashed_nonce('toggle_admin_security')?>' />
	
	<input type='hidden' name='sel_admin_sec' id='sel_admin_sec' value='<?=$admin_area_sec_level?>' />
	
	<b class='blue'>Admin Interface Security Level:</b> &nbsp;<img class="tooltip_style_control admin_security_settings" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
	
	<br /> <input type='radio' name='opt_admin_sec' id='opt_admin_sec_normal' value='normal' onclick='set_admin_security(this);' <?=( $admin_area_sec_level == 'normal' ? 'checked' : '' )?> /> Normal &nbsp; <input type='radio' name='opt_admin_sec' id='opt_admin_sec_medium' value='medium' onclick='set_admin_security(this);' <?=( $admin_area_sec_level == 'medium' ? 'checked' : '' )?> /> Medium &nbsp; <input type='radio' name='opt_admin_sec' id='opt_admin_sec_high' value='high' onclick='set_admin_security(this);' <?=( $admin_area_sec_level == 'high' ? 'checked' : '' )?> /> High
	
	
	</form>
		
	
	 <?php
	 if ( $setup_admin_sec_success != null ) {
	 ?>
	 <div style='min-height: 1em;'></div>
	 <div class='green green_dotted' style='font-weight: bold;'><?=$setup_admin_sec_success?></div>
	 <?php
	 }
	 ?>
	 
	 <div style='min-height: 1em;'></div>
	
	
	<form name='toggle_admin_2fa' id='toggle_admin_2fa' action='admin.php?iframe=<?=$ct['gen']->admin_hashed_nonce('iframe_security')?>&section=security&refresh=all' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct['gen']->admin_hashed_nonce('toggle_admin_2fa')?>' />
	
	<input type='hidden' name='sel_admin_2fa' id='sel_admin_2fa' value='<?=( $force_show_2fa_setup ? $force_show_2fa_setup : $admin_area_2fa )?>' />
	
	<b class='blue'>Admin Two-Factor Authentication (ADDITIONAL time-based one-time password security):</b> &nbsp;<img class="tooltip_style_control admin_2fa_settings" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
	
	<br /> <input type='radio' name='opt_admin_2fa' id='opt_admin_2fa_off' value='off' onclick='set_admin_2fa(this);' <?=( $admin_area_2fa == 'off' && !$force_show_2fa_setup || $force_show_2fa_setup == 'off' ? 'checked' : '' )?> /> Off &nbsp; <input type='radio' name='opt_admin_2fa' id='opt_admin_2fa_on' value='on' onclick='set_admin_2fa(this);' <?=( $admin_area_2fa == 'on' || $force_show_2fa_setup == 'on' ? 'checked' : '' )?> /> On &nbsp; <input type='radio' name='opt_admin_2fa' id='opt_admin_2fa_scrict' value='strict' onclick='set_admin_2fa(this);' <?=( $admin_area_2fa == 'strict' || $force_show_2fa_setup == 'strict' ? 'checked' : '' )?> /> Strict
	
	
	</form>
		
	
	 <?php
	 if ( $setup_2fa_success != null ) {
	 ?>
	 <div style='min-height: 1em;'></div>
	 <div class='green green_dotted' style='font-weight: bold;'><?=$setup_2fa_success?></div>
	 <?php
	 }
	 
	 
	 // Setup to enable 2FA
	 if ( $admin_area_2fa == 'off' ) {
      ?>
               
      <div class='show_2fa_verification' <?=( isset($force_show_2fa_setup) && $force_show_2fa_setup != 'off' ? ' style="display: block;"' : '' )?>>

	 <p style='font-weight: bold; margin-top: 1.5em;' class='red'>Scan this QR code with your authenticator app:</p>
			
	 <p><img src='templates/interface/media/images/2fa_setup.php?2fa_setup=<?=$ct['gen']->admin_hashed_nonce('2fa_setup')?>' /></p>
			
	 <p class='red' style='font-weight: bold;'>--ENTER THE CODE IN YOUR AUTHENTICATOR APP BELOW-- TO ENABLE 2FA...</p>
	
	 <?=$ct['gen']->input_2fa('setup', 'force_show')?>
			
      <!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
      <p><button class='force_button_style' onclick='
      set_admin_2fa(false, true);
      '>Enable 2FA</button></p>
	
	 </div>
		
      <?php
      }
      // If 2FA is already enabled
      else {
      $ct['gen']->input_2fa();
      }
      ?>
			
	
	</div>
	
	 
	<div style='min-height: 1em;'></div>
	
	
    <script>
            
            
    var admin_security_settings_content = '<h5 class="yellow tooltip_title">Admin Interface Security Level</h5>'
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;">Summary of the features of the different security modes available for this admin area...</p>'
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;"><span class="bitcoin">Normal Security Mode:</span><br />In Normal Security Mode, END-USERS MUST EDIT THIS APP\'S CONFIGURATION SETTINGS WITHIN THIS ADMIN INTERFACE AREA. There are no ADDITIONAL security features other than the standard username / password / captcha / 2FA (if activated) login being required, to access the admin area settings / logs / system and visitor stats / any other admin-related data.</p>'
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;"><span class="bitcoin">Medium Security Mode:</span><br />In Medium Security Mode, END-USERS MUST EDIT THIS APP\'S CONFIGURATION SETTINGS WITHIN THIS ADMIN INTERFACE AREA, BUT are required to click a "View Settings" button before each configuration section will load within this admin interface.<br /><br />Medium Security Mode is a minimal extra security layer, that helps protect you against (<a href="https://en.wikipedia.org/wiki/Zero-day_(computing)" target="_blank">Zero Day</a>) <a href="https://owasp.org/www-community/attacks/csrf" target="_blank">CSRF attacks</a>. In v6.00.8 and higher of this app, <a href="https://owasp.org/www-community/attacks/csrf" target="_blank">CSRF attack</a> admin login access / user portfolio data access is NOT possible, UNLESS a related <a href="https://en.wikipedia.org/wiki/Zero-day_(computing)" target="_blank">ZERO DAY</a> web browser security vulnerability exists. Medium Security Mode assists in mitigating <a href="https://en.wikipedia.org/wiki/Zero-day_(computing)" target="_blank">Zero Day</a> security vulnerabilities that may exist for your web browser.<br /><br />It\'s also worth noting here, that the COMPLETELY SEPERATE SETTING "Access Control Origin" (further down in this "Security" configuration section), can also help block <a href="https://owasp.org/www-community/attacks/csrf" target="_blank">CSRF</a> / <a href="https://owasp.org/www-community/attacks/xss/" target="_blank">XSS</a> attacks on source files that use this setting (ajax / charts / portfolio and admin areas / etc), IF set to \'Strict\'. Just MAKE SURE YOU DO NOT have any domain redirects setup on your app server, OTHERWISE CHANGING THIS SETTING TO \'Strict\' MAY BLOCK CHARTS / LOGS / NEW FEEDS / ADMIN SECTIONS FROM LOADING INSIDE THIS APP ITSELF!</p>'
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;"><span class="bitcoin">High Security Mode:</span><br />In High Security Mode, END-USERS MUST EDIT THIS APP\'S CONFIGURATION SETTINGS MANUALLY USING A TEXT EDITOR, to open / edit the config.php file AND the "plugins" folder (plug-conf.php files) in the main directory of this app. Don\'t forget to click "Save" in the text editor / upload the file to the app server, AFTER you update the configuration file(s). EDIT THESE FILES *VERY CAREFULLY* TO AVOID CORRUPTING THE DATA FORMATTING. IF YOU SWITCH TO HIGH SECURITY MODE, ANY SETTING CHANGES YOU MADE IN A LOWER SECURITY MODE *WILL BE LOST*!</p>'
            
            
            			+'';
            		
            		
            		
            			$('.admin_security_settings').balloon({
            			html: true,
            			position: "bottom",
              			classname: 'balloon-tooltips',
            			contents: admin_security_settings_content,
            			css: {
            					fontSize: "<?=$set_font_size?>em",
            					minWidth: "350px",
            					padding: ".3rem .7rem",
            					border: "2px solid rgba(212, 212, 212, .4)",
            					borderRadius: "6px",
            					boxShadow: "3px 3px 6px #555",
            					color: "#eee",
            					backgroundColor: "#111",
            					opacity: "0.99",
            					zIndex: "32767",
            					textAlign: "left"
            					}
            			});
            			
            		
            		
            
            
    var admin_2fa_settings_content = '<h5 class="yellow tooltip_title">Admin Two-Factor Authentication</h5>'
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;">Also known as "2FA", two-factor authentication significantly increases login security, by adding a SECOND password known as a "time-based one-time password", which changes every 30 seconds. So even if a hacker gets your username / password, they CANNOT login without the "time-based one-time password" generated by an app on your phone (Google Authenticator / Microsoft Authenticator / Authy / etc).</p>'
            
            
            			+'';
            		
            		
            		
            			$('.admin_2fa_settings').balloon({
            			html: true,
            			position: "bottom",
              			classname: 'balloon-tooltips',
            			contents: admin_2fa_settings_content,
            			css: {
            					fontSize: "<?=$set_font_size?>em",
            					minWidth: "350px",
            					padding: ".3rem .7rem",
            					border: "2px solid rgba(212, 212, 212, .4)",
            					borderRadius: "6px",
            					boxShadow: "3px 3px 6px #555",
            					color: "#eee",
            					backgroundColor: "#111",
            					opacity: "0.99",
            					zIndex: "32767",
            					textAlign: "left"
            					}
            			});
            			
            		
            
            
    </script> 
    		
				
	<!-- ADMIN PAGES SECURITY LEVEL END -->
	

<?php
if ( $admin_area_sec_level == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor.
	
	</p>

<?php
}
else {


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////


if ( $ct['app_container'] == 'phpdesktop' ) {
// We use readonly instead of disabled, so we don't accidentally delete the empty value from the cached config
$admin_render_settings['interface_login']['is_readonly'] = 'Unavailable in PHPdesktop container';
$admin_render_settings['interface_login']['text_field_size'] = 30;
}
else {

$admin_render_settings['interface_login']['is_password'] = true;


$admin_render_settings['interface_login']['text_field_size'] = 25;

$admin_render_settings['interface_login']['is_notes'] = 'This format MUST be used: username||password<br />SEE ANY ALERTS (after saving changes), for weak username / password failures.';

}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['backup_archive_password']['is_password'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['login_alert']['is_select'] = array(
                                                          'off',
                                                          'email',
                                                          'text',
                                                          'notifyme',
                                                          'telegram',
                                                          'all',
                                                         );

$admin_render_settings['login_alert']['is_notes'] = 'See "External APIs" section for using any comms-related APIs.';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////


$loop = 1;
$loop_max = 6;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['admin_cookie_expires']['is_select']['is_assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => 'After ' . $loop . ' Hours',
                                                                      );
                                                                      
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['smtp_strict_ssl']['is_radio'] = array(
                                                               'off',
                                                               'on',
                                                              );

$admin_render_settings['smtp_strict_ssl']['is_notes'] = 'Set to "Off", if the SMTP server has an invalid certificate.<br />(for "SMTP Server" setting, in the "Communications" section)';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['remote_api_strict_ssl']['is_radio'] = array(
                                                                    'off',
                                                                    'on',
                                                                   );

$admin_render_settings['remote_api_strict_ssl']['is_notes'] = 'Set to "Off", if any exchange\'s API servers have invalid certificates.';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['access_control_origin']['is_radio'] = array(
                                                                    'any',
                                                                    'strict',
                                                                   );

$admin_render_settings['access_control_origin']['is_notes'] = '"Strict" #CAN BREAK THINGS LOADING# ON SOME SETUPS!';


////////////////////////////////////////////////////////////////////////////////////////////////


$loop = -35;
$loop_max = 35;
while ( $loop <= $loop_max ) {
     
$loop_key = ( $loop >= 0 ? '+' . $loop : $loop );
     
$admin_render_settings['captcha_text_contrast']['is_select']['is_assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => $loop_key,
                                                                      );
                                                                      
$loop = $loop + 1;
unset($loop_key);
}
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////


$loop = 0;
$loop_max = 35;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['captcha_text_angle']['is_select']['is_assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => $loop . ' degrees Maximum',
                                                                      );
                                                                      
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'all';

// $ct['admin']->settings_form_fields($conf_id, $interface_id)
$ct['admin']->settings_form_fields('sec', 'security', $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
	
		    