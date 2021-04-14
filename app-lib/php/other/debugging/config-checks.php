<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



// Run some basic configuration file checks

$validate_from_email = $pt_gen->valid_email($pt_conf['comms']['from_email']);
      
$validate_to_email = $pt_gen->valid_email($pt_conf['comms']['to_email']);


// Proxy configuration check
if ( sizeof($pt_conf['proxy']['proxy_list']) > 0 ) {
	

	$proxy_parse_errors = 0;
	
	
	// Email for proxy alerts
	if ( $pt_conf['comms']['proxy_alert'] == 'email' || $pt_conf['comms']['proxy_alert'] == 'all' ) {
		
      if ( trim($pt_conf['comms']['from_email']) != '' && trim($pt_conf['comms']['to_email']) != '' ) {
      	
					
			// Config error check(s)
         if ( $validate_from_email != 'valid' ) {
         $conf_parse_error[] = 'FROM email not configured properly for proxy alerts (' . $validate_from_email . ')';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          		
         if ( $validate_to_email != 'valid' ) {
         $conf_parse_error[] = 'TO email not configured properly for proxy alerts (' . $validate_to_email . ')';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          	
		}
		
	}
          
	
	// Text for proxy alerts
	if ( $pt_conf['comms']['proxy_alert'] == 'text' || $pt_conf['comms']['proxy_alert'] == 'all' ) {
		
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( trim($text_parse[0]) != '' && trim($text_parse[1]) != 'skip_network_name'
      || trim($pt_conf['comms']['textbelt_apikey']) != '' && $pt_conf['comms']['textlocal_account'] == ''
      || trim($pt_conf['comms']['textbelt_apikey']) == '' && $pt_conf['comms']['textlocal_account'] != '' ) {
      	
				
			// Config error check(s)
         if ( sizeof($text_parse) < 2 ) {
         $conf_parse_error[] = 'Number / carrier formatting for text email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
			
         if ( is_numeric($text_parse[0]) == FALSE ) {
         $conf_parse_error[] = 'Number for text email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          		
         if ( $text_parse[1] != 'skip_network_name' && $pt_gen->valid_email( $pt_gen->text_email($pt_conf['comms']['to_mobile_text']) ) != 'valid' ) {
         $conf_parse_error[] = 'Mobile text services carrier name (for email-to-text) not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          	
		}
		
	}
          		
          	
	// proxy login configuration check
	
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
	if ( $pt_conf['proxy']['proxy_login'] != '' ) {
		
	$proxy_login_parse = explode("||", $pt_conf['proxy']['proxy_login'] );
         
		if ( sizeof($proxy_login_parse) < 2 || trim($proxy_login_parse[0]) == '' || $proxy_login_parse[1] == '' ) {
   	$conf_parse_error[] = 'Proxy username / password not formatted properly.';
      $proxy_parse_errors = $proxy_parse_errors + 1;
		}
	
	}
	
          	
	// Check proxy config
	foreach ( $pt_conf['proxy']['proxy_list'] as $proxy ) {
          		
	$proxy_str = explode(":",$proxy);
          	
		if ( !filter_var($proxy_str[0], FILTER_VALIDATE_IP) || !is_numeric($proxy_str[1]) ) {
		$conf_parse_error[] = $proxy;
      $proxy_parse_errors = $proxy_parse_errors + 1;
      }
     	
	}


	// Displaying that errors were found
	if ( $conf_parse_error >= 1 ) {
   $proxy_conf_alert .= '<span class="red">' . $proxy_parse_errors . ' proxy configuration error(s):</span>' . "<br /> \n";
   }
          		
	// Displaying any config errors
	foreach ( $conf_parse_error as $error ) {
   $proxy_conf_alert .= '<span class="red">Misconfigured proxy: ' . $error . '</span>' . "<br /> \n";
   }

	if ( $proxy_conf_alert ) {
	$pt_gen->log('conf_error', $proxy_conf_alert);
	}
          		
	// Displaying if checks passed
	if ( sizeof($conf_parse_error) < 1 ) {
   $proxy_conf_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
$conf_parse_error = NULL; // Blank it out for any other config checks
          		
}




// Charts and price change alerts configuration check


// Check default Bitcoin market/pairing configs (used by charts/alerts)
if ( !isset( $pt_conf['assets']['BTC']['pairing'][$default_btc_prim_currency_pairing] ) ) {

	foreach ( $pt_conf['assets']['BTC']['pairing'] as $pairing_key => $unused ) {
	$avialable_btc_pairings .= strtolower($pairing_key) . ', ';
	}
	$avialable_btc_pairings = trim($avialable_btc_pairings);
	$avialable_btc_pairings = rtrim($avialable_btc_pairings,',');
	
$conf_parse_error[] = 'Charts and price alerts cannot run properly, because the "btc_prim_currency_pairing" (default Bitcoin currency pairing) value \''.$pt_conf['gen']['btc_prim_currency_pairing'].'\' (in Admin Config GENERAL section) is not a valid Bitcoin pairing option (valid Bitcoin pairing options are: '.$avialable_btc_pairings.')';

}
elseif ( !isset( $pt_conf['assets']['BTC']['pairing'][$default_btc_prim_currency_pairing][$default_btc_prim_exchange] ) ) {

	foreach ( $pt_conf['assets']['BTC']['pairing'][$default_btc_prim_currency_pairing] as $pairing_key => $unused ) {
		
		if( stristr($pairing_key, 'bitmex_') == false ) { // Futures markets not allowed
		$avialable_btc_prim_exchanges .= strtolower($pairing_key) . ', ';
		}
		
	}
	$avialable_btc_prim_exchanges = trim($avialable_btc_prim_exchanges);
	$avialable_btc_prim_exchanges = rtrim($avialable_btc_prim_exchanges,',');
	
$conf_parse_error[] = 'Charts and price alerts cannot run properly, because the "btc_prim_exchange" (default Bitcoin exchange) value \''.$default_btc_prim_exchange.'\' (in Admin Config GENERAL section) is not a valid option for \''.$default_btc_prim_currency_pairing.'\' Bitcoin pairings (valid \''.$default_btc_prim_currency_pairing.'\' Bitcoin pairing options are: '.$avialable_btc_prim_exchanges.')';

}


$text_parse = explode("||", trim($pt_conf['comms']['to_mobile_text']) );
          
          
// Check other charts/price alerts configs
if ( trim($pt_conf['comms']['from_email']) != '' || trim($pt_conf['comms']['to_email']) != '' || sizeof($text_parse) > 0 || trim($pt_conf['comms']['notifyme_accesscode']) != '' ) {
          
          
		// Email
      if ( trim($pt_conf['comms']['from_email']) != '' || trim($pt_conf['comms']['to_email']) != '' ) {
      	
      $alerts_enabled_types[] = 'Email';
					
			// Config error check(s)
         if ( $validate_from_email != 'valid' ) {
         $conf_parse_error[] = 'FROM email not configured properly for price alerts (' . $validate_from_email . ')';
         }
          		
         if ( $validate_to_email != 'valid' ) {
         $conf_parse_error[] = 'TO email not configured properly for price alerts (' . $validate_to_email . ')';
         }
          	
		}
          	
          	
		// Text
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( trim($text_parse[0]) != '' && trim($text_parse[1]) != 'skip_network_name'
      || trim($pt_conf['comms']['textbelt_apikey']) != '' && $pt_conf['comms']['textlocal_account'] == ''
      || trim($pt_conf['comms']['textbelt_apikey']) == '' && $pt_conf['comms']['textlocal_account'] != '' ) {
      	
      $alerts_enabled_types[] = 'Text';
				
			// Config error check(s)
         if ( sizeof($text_parse) < 2 ) {
         $conf_parse_error[] = 'Number / carrier formatting for text email not configured properly for price alerts.';
         }
			
         if ( is_numeric($text_parse[0]) == FALSE ) {
         $conf_parse_error[] = 'Number for text email not configured properly for price alerts.';
         }
          		
         if ( $text_parse[1] != 'skip_network_name' && $pt_gen->valid_email( $pt_gen->text_email($pt_conf['comms']['to_mobile_text']) ) != 'valid' ) {
         $conf_parse_error[] = 'Mobile text services carrier name (for email-to-text) not configured properly for price alerts.';
         }
          	
		}
          	
          	
      // Notifyme (alexa)
      if ( trim($pt_conf['comms']['notifyme_accesscode']) != '' ) {
      $alerts_enabled_types[] = 'Alexa';
      }
          	
          	
      // Google Home
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( trim($pt_conf['comms']['google_application_name']) != '' && trim($pt_conf['comms']['google_client_id']) != '' && $pt_conf['comms']['google_client_secret'] != '' ) {
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
          		
          		

			// Check $pt_conf['charts_alerts']['tracked_markets'] config
			if ( !is_array($pt_conf['charts_alerts']['tracked_markets']) ) {
			$conf_parse_error[] = 'The asset / exchange / pairing price alert formatting is corrupt, or not configured yet.';
			}
			
			
			foreach ( $pt_conf['charts_alerts']['tracked_markets'] as $key => $val ) {
   		       		
			$alerts_str = explode("||",$val);
   		       	
				if ( sizeof($alerts_str) < 3 ) {
				$conf_parse_error[] = "'" . $key . "' price alert exchange / market not formatted properly: '" . $val . "'";
      		}
     	
			}

          		

         // Displaying that errors were found
         if ( $conf_parse_error >= 1 ) {
         $price_change_conf_alert .=  '<span class="red">' . $price_alert_type_text . ' alert configuration error(s):</span>' . "<br /> \n";
         }
          		
         // Displaying any config errors
         foreach ( $conf_parse_error as $error ) {
         $price_change_conf_alert .= '<span class="red">' . $error . '</span>' . "<br /> \n";
         }
          		

			if ( $price_change_conf_alert ) {
			$pt_gen->log('conf_error', $price_change_conf_alert);
			}
          		
         // Displaying if checks passed
         if ( sizeof($conf_parse_error) < 1 ) {
         $price_change_conf_alert .= '<span class="green">Config formatting seems ok.</span>';
         }
          		
      $conf_parse_error = NULL; // Blank it out for any other config checks
          		
      }
          	
          	
}





// Check SMTP configs
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $pt_conf['comms']['smtp_login'] != '' && $pt_conf['comms']['smtp_server'] != '' ) {
	
	
// SMTP configuration check
$smtp_email_login_parse = explode("||", $pt_conf['comms']['smtp_login'] );
$smtp_email_server_parse = explode(":", $pt_conf['comms']['smtp_server'] );

	if ( sizeof($smtp_email_login_parse) < 2 || trim($smtp_email_login_parse[0]) == '' || $smtp_email_login_parse[1] == '' ) {
   $conf_parse_error[] = 'SMTP username / password not formatted properly.';
	}
	
	if ( sizeof($smtp_email_server_parse) < 2 || trim($smtp_email_server_parse[0]) == '' || !is_numeric( trim($smtp_email_server_parse[1]) ) ) {
   $conf_parse_error[] = 'SMTP server domain_or_ip / port not formatted properly.';
	}
	
	
   // Displaying that errors were found
   if ( $conf_parse_error >= 1 ) {
   $smtp_conf_alert .=  '<span class="red">SMTP configuration error(s):</span>' . "<br /> \n";
   }
          		
   // Displaying any config errors
   foreach ( $conf_parse_error as $error ) {
   $smtp_conf_alert .= '<span class="red">' . $error . '</span>' . "<br /> \n";
   }
          		

	if ( $smtp_conf_alert ) {
	$pt_gen->log('conf_error', $smtp_conf_alert);
	}

        
   // Displaying if checks passed
   if ( sizeof($conf_parse_error) < 1 ) {
   $smtp_conf_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
   $conf_parse_error = NULL; // Blank it out for any other config checks
          		
	
}





// Email logs configs
if ( $pt_conf['power']['logs_email'] > 0 && trim($pt_conf['comms']['from_email']) != '' && trim($pt_conf['comms']['to_email']) != '' ) {
					
	// Config error check(s)
   if ( $validate_from_email != 'valid' ) {
   $conf_parse_error[] = 'FROM email not configured properly for emailing error logs (' . $validate_from_email . ')';
   }
          		
   if ( $validate_to_email != 'valid' ) {
   $conf_parse_error[] = 'TO email not configured properly for emailing error logs (' . $validate_to_email . ')';
   }


   // Displaying that errors were found
   if ( $conf_parse_error >= 1 ) {
   $logs_conf_alert .=  '<span class="red">Email error logs configuration error(s):</span>' . "<br /> \n";
   }
          		
   // Displaying any config errors
   foreach ( $conf_parse_error as $error ) {
   $logs_conf_alert .= '<span class="red">' . $error . '</span>' . "<br /> \n";
   }
          		

	if ( $logs_conf_alert ) {
	$pt_gen->log('conf_error', $logs_conf_alert);
	}

        
   // Displaying if checks passed
   if ( sizeof($conf_parse_error) < 1 ) {
   $logs_conf_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
   $conf_parse_error = NULL; // Blank it out for any other config checks
          		       	
}
          	



// Email backup archives configs
if ( $pt_conf['gen']['asset_charts_toggle'] == 'on' && $pt_conf['power']['charts_backup_freq'] > 0 && trim($pt_conf['comms']['from_email']) != '' && trim($pt_conf['comms']['to_email']) != '' ) {
					
	// Config error check(s)
   if ( $validate_from_email != 'valid' ) {
   $conf_parse_error[] = 'FROM email not configured properly for emailing backup archive notice / link (' . $validate_from_email . ')';
   }
          		
   if ( $validate_to_email != 'valid' ) {
   $conf_parse_error[] = 'TO email not configured properly for emailing backup archive notice / link (' . $validate_to_email . ')';
   }


   // Displaying that errors were found
   if ( $conf_parse_error >= 1 ) {
   $backuparchive_conf_alert .=  '<span class="red">Backup archiving configuration error(s):</span>' . "<br /> \n";
   }
          		
   // Displaying any config errors
   foreach ( $conf_parse_error as $error ) {
   $backuparchive_conf_alert .= '<span class="red">' . $error . '</span>' . "<br /> \n";
   }
          		

	if ( $backuparchive_conf_alert ) {
	$pt_gen->log('conf_error', $backuparchive_conf_alert);
	}

        
   // Displaying if checks passed
   if ( sizeof($conf_parse_error) < 1 ) {
   $backuparchive_conf_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
   $conf_parse_error = NULL; // Blank it out for any other config checks
          		       	
}
          	



// Check $pt_conf['assets'] config
if ( !is_array($pt_conf['assets']) ) {
$pt_gen->log('conf_error', 'The portfolio assets formatting is corrupt, or not configured yet');
}

// Check default / dynamic Bitcoin market/pairing configs
if ( !isset( $pt_conf['assets']['BTC']['pairing'][ $pt_conf['gen']['btc_prim_currency_pairing'] ] ) ) {

	foreach ( $pt_conf['assets']['BTC']['pairing'] as $pairing_key => $unused ) {
	$avialable_btc_pairings .= strtolower($pairing_key) . ', ';
	}
	$avialable_btc_pairings = trim($avialable_btc_pairings);
	$avialable_btc_pairings = rtrim($avialable_btc_pairings,',');

$pt_gen->log(
							'conf_error',
							'Portfolio cannot run properly, because the "btc_prim_currency_pairing" (Bitcoin primary currency pairing) value \''.$pt_conf['gen']['btc_prim_currency_pairing'].'\' is not a valid Bitcoin pairing option (valid Bitcoin pairing options are: '.$avialable_btc_pairings.')'
							);

}
elseif ( !isset( $pt_conf['assets']['BTC']['pairing'][ $pt_conf['gen']['btc_prim_currency_pairing'] ][ $pt_conf['gen']['btc_prim_exchange'] ] ) ) {

	foreach ( $pt_conf['assets']['BTC']['pairing'][ $pt_conf['gen']['btc_prim_currency_pairing'] ] as $pairing_key => $unused ) {
		
		if( stristr($pairing_key, 'bitmex_') == false ) { // Futures markets not allowed
		$avialable_btc_prim_exchanges .= strtolower($pairing_key) . ', ';
		}
		
	}
	$avialable_btc_prim_exchanges = trim($avialable_btc_prim_exchanges);
	$avialable_btc_prim_exchanges = rtrim($avialable_btc_prim_exchanges,',');

$pt_gen->log(
							'conf_error',
							'Portfolio cannot run properly, because the "btc_prim_exchange" (Bitcoin exchange) value \''.$pt_conf['gen']['btc_prim_exchange'].'\' is not a valid option for \''.$pt_conf['gen']['btc_prim_currency_pairing'].'\' Bitcoin pairings (valid \''.$pt_conf['gen']['btc_prim_currency_pairing'].'\' Bitcoin pairing options are: '.$avialable_btc_prim_exchanges.')'
							);

}

			
			
			
// END of basic configuration file checks

  
 
 ?>