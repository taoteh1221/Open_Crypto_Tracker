<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



// Run some basic configuration file checks

$validate_from_email = validate_email($ocpt_conf['comms']['from_email']);
      
$validate_to_email = validate_email($ocpt_conf['comms']['to_email']);


// Proxy configuration check
if ( sizeof($ocpt_conf['proxy']['proxy_list']) > 0 ) {
	

	$proxy_parse_errors = 0;
	
	
	// Email for proxy alerts
	if ( $ocpt_conf['comms']['proxy_alert'] == 'email' || $ocpt_conf['comms']['proxy_alert'] == 'all' ) {
		
      if ( trim($ocpt_conf['comms']['from_email']) != '' && trim($ocpt_conf['comms']['to_email']) != '' ) {
      	
					
			// Config error check(s)
         if ( $validate_from_email != 'valid' ) {
         $config_parse_error[] = 'FROM email not configured properly for proxy alerts (' . $validate_from_email . ')';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          		
         if ( $validate_to_email != 'valid' ) {
         $config_parse_error[] = 'TO email not configured properly for proxy alerts (' . $validate_to_email . ')';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          	
		}
		
	}
          
	
	// Text for proxy alerts
	if ( $ocpt_conf['comms']['proxy_alert'] == 'text' || $ocpt_conf['comms']['proxy_alert'] == 'all' ) {
		
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( trim($text_parse[0]) != '' && trim($text_parse[1]) != 'skip_network_name'
      || trim($ocpt_conf['comms']['textbelt_apikey']) != '' && $ocpt_conf['comms']['textlocal_account'] == ''
      || trim($ocpt_conf['comms']['textbelt_apikey']) == '' && $ocpt_conf['comms']['textlocal_account'] != '' ) {
      	
				
			// Config error check(s)
         if ( sizeof($text_parse) < 2 ) {
         $config_parse_error[] = 'Number / carrier formatting for text email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
			
         if ( is_numeric($text_parse[0]) == FALSE ) {
         $config_parse_error[] = 'Number for text email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          		
         if ( $text_parse[1] != 'skip_network_name' && validate_email( text_email($ocpt_conf['comms']['to_mobile_text']) ) != 'valid' ) {
         $config_parse_error[] = 'Mobile text services carrier name (for email-to-text) not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          	
		}
		
	}
          		
          	
	// proxy login configuration check
	
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
	if ( $ocpt_conf['proxy']['proxy_login'] != '' ) {
		
	$proxy_login_parse = explode("||", $ocpt_conf['proxy']['proxy_login'] );
         
		if ( sizeof($proxy_login_parse) < 2 || trim($proxy_login_parse[0]) == '' || $proxy_login_parse[1] == '' ) {
   	$config_parse_error[] = 'Proxy username / password not formatted properly.';
      $proxy_parse_errors = $proxy_parse_errors + 1;
		}
	
	}
	
          	
	// Check proxy config
	foreach ( $ocpt_conf['proxy']['proxy_list'] as $proxy ) {
          		
	$proxy_string = explode(":",$proxy);
          	
		if ( !filter_var($proxy_string[0], FILTER_VALIDATE_IP) || !is_numeric($proxy_string[1]) ) {
		$config_parse_error[] = $proxy;
      $proxy_parse_errors = $proxy_parse_errors + 1;
      }
     	
	}


	// Displaying that errors were found
	if ( $config_parse_error >= 1 ) {
   $proxy_config_alert .= '<span class="red">' . $proxy_parse_errors . ' proxy configuration error(s):</span>' . "<br /> \n";
   }
          		
	// Displaying any config errors
	foreach ( $config_parse_error as $error ) {
   $proxy_config_alert .= '<span class="red">Misconfigured proxy: ' . $error . '</span>' . "<br /> \n";
   }

	if ( $proxy_config_alert ) {
	app_logging('config_error', $proxy_config_alert);
	}
          		
	// Displaying if checks passed
	if ( sizeof($config_parse_error) < 1 ) {
   $proxy_config_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
$config_parse_error = NULL; // Blank it out for any other config checks
          		
}




// Charts and price change alerts configuration check


// Check default Bitcoin market/pairing configs (used by charts/alerts)
if ( !isset( $ocpt_conf['assets']['BTC']['pairing'][$default_btc_prim_curr_pairing] ) ) {

	foreach ( $ocpt_conf['assets']['BTC']['pairing'] as $pairing_key => $unused ) {
	$avialable_btc_pairings .= strtolower($pairing_key) . ', ';
	}
	$avialable_btc_pairings = trim($avialable_btc_pairings);
	$avialable_btc_pairings = rtrim($avialable_btc_pairings,',');
	
$config_parse_error[] = 'Charts and price alerts cannot run properly, because the "btc_prim_curr_pairing" (default Bitcoin currency pairing) value \''.$ocpt_conf['gen']['btc_prim_curr_pairing'].'\' (in Admin Config GENERAL section) is not a valid Bitcoin pairing option (valid Bitcoin pairing options are: '.$avialable_btc_pairings.')';

}
elseif ( !isset( $ocpt_conf['assets']['BTC']['pairing'][$default_btc_prim_curr_pairing][$default_btc_prim_exchange] ) ) {

	foreach ( $ocpt_conf['assets']['BTC']['pairing'][$default_btc_prim_curr_pairing] as $pairing_key => $unused ) {
		
		if( stristr($pairing_key, 'bitmex_') == false ) { // Futures markets not allowed
		$avialable_btc_prim_exchanges .= strtolower($pairing_key) . ', ';
		}
		
	}
	$avialable_btc_prim_exchanges = trim($avialable_btc_prim_exchanges);
	$avialable_btc_prim_exchanges = rtrim($avialable_btc_prim_exchanges,',');
	
$config_parse_error[] = 'Charts and price alerts cannot run properly, because the "btc_prim_exchange" (default Bitcoin exchange) value \''.$default_btc_prim_exchange.'\' (in Admin Config GENERAL section) is not a valid option for \''.$default_btc_prim_curr_pairing.'\' Bitcoin pairings (valid \''.$default_btc_prim_curr_pairing.'\' Bitcoin pairing options are: '.$avialable_btc_prim_exchanges.')';

}


$text_parse = explode("||", trim($ocpt_conf['comms']['to_mobile_text']) );
          
          
// Check other charts/price alerts configs
if ( trim($ocpt_conf['comms']['from_email']) != '' || trim($ocpt_conf['comms']['to_email']) != '' || sizeof($text_parse) > 0 || trim($ocpt_conf['comms']['notifyme_accesscode']) != '' ) {
          
          
		// Email
      if ( trim($ocpt_conf['comms']['from_email']) != '' || trim($ocpt_conf['comms']['to_email']) != '' ) {
      	
      $alerts_enabled_types[] = 'Email';
					
			// Config error check(s)
         if ( $validate_from_email != 'valid' ) {
         $config_parse_error[] = 'FROM email not configured properly for price alerts (' . $validate_from_email . ')';
         }
          		
         if ( $validate_to_email != 'valid' ) {
         $config_parse_error[] = 'TO email not configured properly for price alerts (' . $validate_to_email . ')';
         }
          	
		}
          	
          	
		// Text
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( trim($text_parse[0]) != '' && trim($text_parse[1]) != 'skip_network_name'
      || trim($ocpt_conf['comms']['textbelt_apikey']) != '' && $ocpt_conf['comms']['textlocal_account'] == ''
      || trim($ocpt_conf['comms']['textbelt_apikey']) == '' && $ocpt_conf['comms']['textlocal_account'] != '' ) {
      	
      $alerts_enabled_types[] = 'Text';
				
			// Config error check(s)
         if ( sizeof($text_parse) < 2 ) {
         $config_parse_error[] = 'Number / carrier formatting for text email not configured properly for price alerts.';
         }
			
         if ( is_numeric($text_parse[0]) == FALSE ) {
         $config_parse_error[] = 'Number for text email not configured properly for price alerts.';
         }
          		
         if ( $text_parse[1] != 'skip_network_name' && validate_email( text_email($ocpt_conf['comms']['to_mobile_text']) ) != 'valid' ) {
         $config_parse_error[] = 'Mobile text services carrier name (for email-to-text) not configured properly for price alerts.';
         }
          	
		}
          	
          	
      // Notifyme (alexa)
      if ( trim($ocpt_conf['comms']['notifyme_accesscode']) != '' ) {
      $alerts_enabled_types[] = 'Alexa';
      }
          	
          	
      // Google Home
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( trim($ocpt_conf['comms']['google_application_name']) != '' && trim($ocpt_conf['comms']['google_client_id']) != '' && $ocpt_conf['comms']['google_client_secret'] != '' ) {
      $alerts_enabled_types[] = 'Google Home';
      }
          	
          	
      // Telegram
      if ( $telegram_activated == 1 ) {
      $alerts_enabled_types[] = 'Telegram';
      }
          	
          	
      // Our alert types
      if ( sizeof($alerts_enabled_types) > 0 ) {
          		
        foreach ( $alerts_enabled_types as $type ) {
        $price_alert_type_text .= $type . ' / ';
        }
          		
      $price_alert_type_text = substr($price_alert_type_text, 0, -3);
          		
          		

			// Check $ocpt_conf['charts_alerts']['tracked_markets'] config
			if ( !is_array($ocpt_conf['charts_alerts']['tracked_markets']) ) {
			$config_parse_error[] = 'The asset / exchange / pairing price alert formatting is corrupt, or not configured yet.';
			}
			
			
			foreach ( $ocpt_conf['charts_alerts']['tracked_markets'] as $key => $value ) {
   		       		
			$alerts_string = explode("||",$value);
   		       	
				if ( sizeof($alerts_string) < 3 ) {
				$config_parse_error[] = "'" . $key . "' price alert exchange / market not formatted properly: '" . $value . "'";
      		}
     	
			}

          		

         // Displaying that errors were found
         if ( $config_parse_error >= 1 ) {
         $price_change_config_alert .=  '<span class="red">' . $price_alert_type_text . ' alert configuration error(s):</span>' . "<br /> \n";
         }
          		
         // Displaying any config errors
         foreach ( $config_parse_error as $error ) {
         $price_change_config_alert .= '<span class="red">' . $error . '</span>' . "<br /> \n";
         }
          		

			if ( $price_change_config_alert ) {
			app_logging('config_error', $price_change_config_alert);
			}
          		
         // Displaying if checks passed
         if ( sizeof($config_parse_error) < 1 ) {
         $price_change_config_alert .= '<span class="green">Config formatting seems ok.</span>';
         }
          		
      $config_parse_error = NULL; // Blank it out for any other config checks
          		
      }
          	
          	
}





// Check SMTP configs
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $ocpt_conf['comms']['smtp_login'] != '' && $ocpt_conf['comms']['smtp_server'] != '' ) {
	
	
// SMTP configuration check
$smtp_email_login_parse = explode("||", $ocpt_conf['comms']['smtp_login'] );
$smtp_email_server_parse = explode(":", $ocpt_conf['comms']['smtp_server'] );

	if ( sizeof($smtp_email_login_parse) < 2 || trim($smtp_email_login_parse[0]) == '' || $smtp_email_login_parse[1] == '' ) {
   $config_parse_error[] = 'SMTP username / password not formatted properly.';
	}
	
	if ( sizeof($smtp_email_server_parse) < 2 || trim($smtp_email_server_parse[0]) == '' || !is_numeric( trim($smtp_email_server_parse[1]) ) ) {
   $config_parse_error[] = 'SMTP server domain_or_ip / port not formatted properly.';
	}
	
	
   // Displaying that errors were found
   if ( $config_parse_error >= 1 ) {
   $smtp_config_alert .=  '<span class="red">SMTP configuration error(s):</span>' . "<br /> \n";
   }
          		
   // Displaying any config errors
   foreach ( $config_parse_error as $error ) {
   $smtp_config_alert .= '<span class="red">' . $error . '</span>' . "<br /> \n";
   }
          		

	if ( $smtp_config_alert ) {
	app_logging('config_error', $smtp_config_alert);
	}

        
   // Displaying if checks passed
   if ( sizeof($config_parse_error) < 1 ) {
   $smtp_config_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
   $config_parse_error = NULL; // Blank it out for any other config checks
          		
	
}





// Email logs configs
if ( $ocpt_conf['power']['logs_email'] > 0 && trim($ocpt_conf['comms']['from_email']) != '' && trim($ocpt_conf['comms']['to_email']) != '' ) {
					
	// Config error check(s)
   if ( $validate_from_email != 'valid' ) {
   $config_parse_error[] = 'FROM email not configured properly for emailing error logs (' . $validate_from_email . ')';
   }
          		
   if ( $validate_to_email != 'valid' ) {
   $config_parse_error[] = 'TO email not configured properly for emailing error logs (' . $validate_to_email . ')';
   }


   // Displaying that errors were found
   if ( $config_parse_error >= 1 ) {
   $logs_config_alert .=  '<span class="red">Email error logs configuration error(s):</span>' . "<br /> \n";
   }
          		
   // Displaying any config errors
   foreach ( $config_parse_error as $error ) {
   $logs_config_alert .= '<span class="red">' . $error . '</span>' . "<br /> \n";
   }
          		

	if ( $logs_config_alert ) {
	app_logging('config_error', $logs_config_alert);
	}

        
   // Displaying if checks passed
   if ( sizeof($config_parse_error) < 1 ) {
   $logs_config_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
   $config_parse_error = NULL; // Blank it out for any other config checks
          		       	
}
          	



// Email backup archives configs
if ( $ocpt_conf['gen']['asset_charts_toggle'] == 'on' && $ocpt_conf['power']['charts_backup_freq'] > 0 && trim($ocpt_conf['comms']['from_email']) != '' && trim($ocpt_conf['comms']['to_email']) != '' ) {
					
	// Config error check(s)
   if ( $validate_from_email != 'valid' ) {
   $config_parse_error[] = 'FROM email not configured properly for emailing backup archive notice / link (' . $validate_from_email . ')';
   }
          		
   if ( $validate_to_email != 'valid' ) {
   $config_parse_error[] = 'TO email not configured properly for emailing backup archive notice / link (' . $validate_to_email . ')';
   }


   // Displaying that errors were found
   if ( $config_parse_error >= 1 ) {
   $backuparchive_config_alert .=  '<span class="red">Backup archiving configuration error(s):</span>' . "<br /> \n";
   }
          		
   // Displaying any config errors
   foreach ( $config_parse_error as $error ) {
   $backuparchive_config_alert .= '<span class="red">' . $error . '</span>' . "<br /> \n";
   }
          		

	if ( $backuparchive_config_alert ) {
	app_logging('config_error', $backuparchive_config_alert);
	}

        
   // Displaying if checks passed
   if ( sizeof($config_parse_error) < 1 ) {
   $backuparchive_config_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
   $config_parse_error = NULL; // Blank it out for any other config checks
          		       	
}
          	



// Check $ocpt_conf['assets'] config
if ( !is_array($ocpt_conf['assets']) ) {
app_logging('config_error', 'The portfolio assets formatting is corrupt, or not configured yet');
}

// Check default / dynamic Bitcoin market/pairing configs
if ( !isset( $ocpt_conf['assets']['BTC']['pairing'][$ocpt_conf['gen']['btc_prim_curr_pairing']] ) ) {

	foreach ( $ocpt_conf['assets']['BTC']['pairing'] as $pairing_key => $unused ) {
	$avialable_btc_pairings .= strtolower($pairing_key) . ', ';
	}
	$avialable_btc_pairings = trim($avialable_btc_pairings);
	$avialable_btc_pairings = rtrim($avialable_btc_pairings,',');

app_logging('config_error', 'Portfolio cannot run properly, because the "btc_prim_curr_pairing" (Bitcoin primary currency pairing) value \''.$ocpt_conf['gen']['btc_prim_curr_pairing'].'\' is not a valid Bitcoin pairing option (valid Bitcoin pairing options are: '.$avialable_btc_pairings.')');

}
elseif ( !isset( $ocpt_conf['assets']['BTC']['pairing'][$ocpt_conf['gen']['btc_prim_curr_pairing']][$ocpt_conf['gen']['btc_prim_exchange']] ) ) {

	foreach ( $ocpt_conf['assets']['BTC']['pairing'][$ocpt_conf['gen']['btc_prim_curr_pairing']] as $pairing_key => $unused ) {
		
		if( stristr($pairing_key, 'bitmex_') == false ) { // Futures markets not allowed
		$avialable_btc_prim_exchanges .= strtolower($pairing_key) . ', ';
		}
		
	}
	$avialable_btc_prim_exchanges = trim($avialable_btc_prim_exchanges);
	$avialable_btc_prim_exchanges = rtrim($avialable_btc_prim_exchanges,',');

app_logging('config_error', 'Portfolio cannot run properly, because the "btc_prim_exchange" (Bitcoin exchange) value \''.$ocpt_conf['gen']['btc_prim_exchange'].'\' is not a valid option for \''.$ocpt_conf['gen']['btc_prim_curr_pairing'].'\' Bitcoin pairings (valid \''.$ocpt_conf['gen']['btc_prim_curr_pairing'].'\' Bitcoin pairing options are: '.$avialable_btc_prim_exchanges.')');

}

			
			
			
// END of basic configuration file checks

  
 
 ?>