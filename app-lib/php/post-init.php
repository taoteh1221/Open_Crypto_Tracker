<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Run some basic configuration file checks

// Proxy configuration check
if ( sizeof($proxy_list) > 0 ) {
	

	$proxy_parse_errors = 0;
	
	// proxy login configuration check
	
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
	if ( $proxy_login != '' ) {
		
	$proxy_login_parse = explode("||", $proxy_login );

		if ( trim($proxy_login_parse[0]) == '' || $proxy_login_parse[1] == '' ) {
   	$config_parse_error[] = 'Proxy username / password not configured properly.' . " \n";
      $proxy_parse_errors = $proxy_parse_errors + 1;
		}
	
	}
	
          	
	// Check proxy config
	foreach ( $proxy_list as $proxy ) {
          		
	$string = explode(":",$proxy);
          	
		if ( !filter_var($string[0], FILTER_VALIDATE_IP) || !is_numeric($string[1]) ) {
		$config_parse_error[] = $proxy;
      $proxy_parse_errors = $proxy_parse_errors + 1;
      }
     	
	}


	// Displaying that errors were found
	if ( $config_parse_error >= 1 ) {
   $proxy_config_alert .= '<br /><span style="color: red;">' . $proxy_parse_errors . ' proxy configuration error(s):</span>' . " \n";
   }
          		
	// Displaying any config errors
	foreach ( $config_parse_error as $error ) {
   $proxy_config_alert .= '<br /><span style="color: red;">Misconfigured proxy: ' . $error . '</span>' . " \n";
   }
          		
$_SESSION['config_error'] .= ( $proxy_config_alert ? date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | configuration error: ' . $proxy_config_alert . "<br /> \n" : '' );
          		
	// Displaying if checks passed
	if ( sizeof($config_parse_error) < 1 ) {
   $proxy_config_alert .= '<br /><span style="color: green;">Config formatting seems ok.</span>';
   }
          		
$config_parse_error = NULL; // Blank it out for any other config checks
          		
}


// Price change alerts configuration check
$text_parse = explode("||", trim($to_text) );
          
// Check price alert configs
if ( trim($from_email) != '' && trim($to_email) != '' || sizeof($text_parse) == 2 || trim($notifyme_accesscode) != '' ) {
          
          
		// Email
      if ( trim($from_email) != '' && trim($to_email) != '' ) {
      	
      $alerts_enabled_types[] = 'Email';
					
			// Config error check(s)
         if ( validate_email($from_email) != 'valid' ) {
         $config_parse_error[] = 'FROM email not configured properly.' . " \n";
         }
          		
         if ( validate_email($to_email) != 'valid' ) {
         $config_parse_error[] = 'TO email not configured properly.' . " \n";
         }
          	
		}
          	
          	
		// Text
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( $text_parse[1] != 'number_only'
      || trim($textbelt_apikey) != '' && $textlocal_account == ''
      || trim($textbelt_apikey) == '' && $textlocal_account != '' ) {
      	
      $alerts_enabled_types[] = 'Text';
				
			// Config error check(s)
         if ( is_numeric($text_parse[0]) == FALSE ) {
         $config_parse_error[] = 'Number for text email not configured properly.' . " \n";
         }
          		
         if ( $text_parse[1] != 'number_only' && validate_email( text_email($to_text) ) != 'valid' ) {
         $config_parse_error[] = 'Carrier for text email not configured properly.' . " \n";
         }
          	
		}
          	
          	
      // Notifyme (alexa)
      if ( trim($notifyme_accesscode) != '' ) {
      $alerts_enabled_types[] = 'Alexa';
      }
          	
          	
      // Our alert types
      if ( sizeof($alerts_enabled_types) > 0 ) {
          		
        foreach ( $alerts_enabled_types as $type ) {
        $price_alert_type_text .= $type . ' / ';
        }
          		
      $price_alert_type_text = substr($price_alert_type_text, 0, -3);
          		

         // Displaying that errors were found
         if ( $config_parse_error >= 1 ) {
         $price_change_config_alert .=  '<br /><span style="color: red;">' . $price_alert_type_text . ' alert configuration error(s):</span>' . " \n";
         }
          		
         // Displaying any config errors
         foreach ( $config_parse_error as $error ) {
         $price_change_config_alert .= '<br /><span style="color: red;">' . $error . '</span>';
         }
          		
      $_SESSION['config_error'] .= ( $price_change_config_alert ? date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | configuration error: ' . $price_change_config_alert . "<br /> \n" : '');
          		
         // Displaying if checks passed
         if ( sizeof($config_parse_error) < 1 ) {
         $price_change_config_alert .= '<br /><span style="color: green;">Config formatting seems ok.</span>';
         }
          		
      $config_parse_error = NULL; // Blank it out for any other config checks
          		
      }
          	
          	
}



// Check SMTP configs
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $smtp_login != '' && $smtp_server != '' ) {
	
	
// SMTP configuration check
$smtp_login_parse = explode("||", $smtp_login );
$smtp_server_parse = explode(":", $smtp_server );

	if ( trim($smtp_login_parse[0]) == '' || $smtp_login_parse[1] == '' ) {
   $config_parse_error[] = 'SMTP username / password not configured properly.' . " \n";
	}
	
	if ( trim($smtp_server_parse[0]) == '' || !is_numeric( trim($smtp_server_parse[1]) ) ) {
   $config_parse_error[] = 'SMTP server domain_or_ip / port not configured properly.' . " \n";
	}
	
	
   // Displaying that errors were found
   if ( $config_parse_error >= 1 ) {
   $smtp_config_alert .=  '<br /><span style="color: red;">SMTP configuration error(s):</span>' . " \n";
   }
          		
   // Displaying any config errors
   foreach ( $config_parse_error as $error ) {
   $smtp_config_alert .= '<br /><span style="color: red;">' . $error . '</span>';
   }
	
   
   $_SESSION['config_error'] .= ( $smtp_config_alert ? date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | configuration error: ' . $smtp_config_alert . "<br /> \n" : '');

        
   // Displaying if checks passed
   if ( sizeof($config_parse_error) < 1 ) {
   $smtp_config_alert .= '<br /><span style="color: green;">Config formatting seems ok.</span>';
   }
          		
   $config_parse_error = NULL; // Blank it out for any other config checks
          		
	
}

// END of basic configuration file checks



// User agent
if ( sizeof($proxy_list) > 0 ) {
$user_agent = 'Mozilla/5.0 (compatible; API_Endpoint_Parser;) Gecko Firefox';  // If proxies in use, preserve some privacy
}
else {
$user_agent = 'Mozilla/5.0 (compatible; ' . $_SERVER['SERVER_SOFTWARE'] . '; PHP/' .phpversion(). '; Curl/' .$curl_setup["version"]. '; DFD_Cryptocoin_Values/' . $app_version . '; API_Endpoint_Parser; +https://github.com/taoteh1221/DFD_Cryptocoin_Values) Gecko Firefox';
}



// SMTP email setup
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $smtp_login != '' && $smtp_server != '' ) {

require_once( dirname(__FILE__) . '/classes/smtp-mailer/SMTPMailer.php');

// Passing smtp server login vars to config file structure used by the 3rd party SMTP class, to maintain ease with any future upgrade compatibility
// Must be loaded as a global var before class instance is created
$smtp_vars = smtp_vars();
global $smtp_vars; // Needed for class compatibility (along with second instance in the class config_smtp.php file)

// Initiation of the 3rd party SMTP class
$smtp = new SMTPMailer();
$smtp->addTo($to_email); // Add to email here one time...because class adds to an array each call, even if already added

}



// Only need below logic during UI runtime
if ( $runtime_mode == 'ui' ) {

$marketcap_site = ( $alert_percent[0] != '' ? $alert_percent[0] : $marketcap_site );

}


?>