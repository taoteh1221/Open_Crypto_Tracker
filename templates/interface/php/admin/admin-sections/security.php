<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>
	    

	<!-- ADMIN PAGES SECURITY LEVEL START -->

	<div class='red red_dotted' style='margin-bottom: 20px;'>
	
	<form name='toggle_admin_security' id='toggle_admin_security' action='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_security')?>&section=security&refresh=all' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct_gen->admin_hashed_nonce('toggle_admin_security')?>' />
	
	<input type='hidden' name='sel_admin_sec' id='sel_admin_sec' value='<?=$admin_area_sec_level?>' />
	
	<b>Admin Interface Security Level</b> &nbsp;<img class="tooltip_style_control admin_security_settings" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
	
	<br /> <input type='radio' name='opt_admin_sec' id='opt_admin_sec_high' value='high' onclick='set_admin_security(this);' <?=( $admin_area_sec_level == 'normal' ? '' : 'checked' )?> /> High &nbsp; <input type='radio' name='opt_admin_sec' id='opt_admin_sec_enhanced' value='enhanced' onclick='set_admin_security(this);' <?=( $admin_area_sec_level == 'enhanced' ? 'checked' : '' )?> /> Enhanced &nbsp; <input type='radio' name='opt_admin_sec' id='opt_admin_sec_normal' value='normal' onclick='set_admin_security(this);' <?=( $admin_area_sec_level == 'normal' ? 'checked' : '' )?> /> Normal
	
	</form>
	
	</div>
	
	
    <script>
            
            
    var admin_security_settings_content = '<h5 class="yellow tooltip_title">Admin Interface Security Level</h5>'
            
            			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;">Summary of the features of the different security modes available for this admin area...</p>'
            
            			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;"><span class="bitcoin">High Security Mode:</span><br />In High Security Mode, END-USERS MUST EDIT THIS APP\'S CONFIGURATION SETTINGS MANUALLY USING A TEXT EDITOR, to open / edit the config.php file AND the "plugins" folder (plug-conf.php files) in the main directory of this app. Don\'t forget to click "Save" in the text editor, AFTER you update the configuration file(s), AND EDIT *VERY CAREFULLY* TO AVOID CORRUPTING THE DATA FORMATTING.</p>'
            
            			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;"><span class="bitcoin">Enhanced Security Mode:</span><br />In Enhanced Security Mode, END-USERS MUST EDIT THIS APP\'S CONFIGURATION SETTINGS WITHIN THIS ADMIN INTERFACE AREA, BUT are required to click a "View Settings" button before each configuration section will load within this admin interface.<br /><br />Enhanced Security Mode is an extra security layer, that protects against CSRF attacks from "page scraping" sensitive user settings (a very rare / advanced attack, requiring the hacker to know your install\'s web address, AND they MUST trick YOU into visiting a malicious website which they control, AND you must be logged into the app\'s admin area).<br /><br />ALL ABOVE SAID, in my testing on protection against CSRF attacks, I found that the v6.00.8 upgrade (FIX) of this app to reliably using SAMESITE=STRICT COOKIES (in ALL Editions) AND SECURE COOKIES (in the Server Edition) TOTALLY BLOCKS ACCESS TO COOKIE DATA REMOTELY (so admin login access / user portfolio data access was NOT possible). This held true in my tests on google chrome / firefox. So PRACTICALLY speaking, "Enhanced" security mode is not needed, UNLESS you ADDITIONALLY want attack protection for <a href="https://en.wikipedia.org/wiki/Zero-day_(computing)" target="_blank">ZERO DAY</a> browser bugs.<br /><br />It\'s also worth noting here, that the COMPLETELY SEPERATE SETTING "access_control_origin" (further down in this "Security" configuration section), can COMPLETELY block all CSRF / XSS attacks on source files that use this setting (ajax / charts / portfolio and admin areas / etc), if set to \'strict\'. Just MAKE SURE YOU DO NOT have any domain redirects setup on your app server, OTHERWISE CHANGING THIS SETTING TO \'strict\' MAY BLOCK CHARTS / LOGS / NEW FEEDS / ADMIN SECTIONS FROM LOADING INSIDE THIS APP ITSELF.</p>'
            
            			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;"><span class="bitcoin">Normal Security Mode:</span><br />In Normal Security Mode, END-USERS MUST EDIT THIS APP\'S CONFIGURATION SETTINGS WITHIN THIS ADMIN INTERFACE AREA. There are no ADDITIONAL security features other than the standard username / password / captcha login being required, to access the admin area settings / logs / system and visitor stats / any other admin-related data.</p>'
            
            
            			+'';
            		
            		
            		
            			$('.admin_security_settings').balloon({
            			html: true,
            			position: "bottom",
              			classname: 'balloon-tooltips',
            			contents: admin_security_settings_content,
            			css: {
            					fontSize: "<?=$default_font_size?>em",
            					minWidth: "450px",
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
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$base_dir?>) with a text editor.
	
	</p>

<?php
}
else {
?>
	
	<p> Coming Soon&trade; </p>
	
	<p class='bitcoin bitcoin_dotted'>
	
	These sections / category pages will be INCREMENTALLY populated with the corrisponding admin configuration options, over a period of time AFTER the initial v6.00.x releases (versions 6.00.x will only test the back-end / under-the-hood stability of HIGH / ENHANCED / NORMAL MODES of the Admin Interface security levels). <br /><br />You may need to turn off "Enhanced" OR "Normal" mode of the Admin Interface security level (at the top of the "Security" section in this admin area), to edit any UNFINISHED SECTIONS by hand in the config files (config.php in the app install folder, and any plug-conf.php files in the plugins folders).
	
	</p>
	
<?php
}
?>	
	
		    