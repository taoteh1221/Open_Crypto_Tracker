<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Run some basic configuration file checks


// Proxy configuration check
if ( sizeof($app_config['proxy_list']) > 0 ) {
	

	$proxy_parse_errors = 0;
	
	
	// Email for proxy alerts
	if ( $app_config['proxy_alerts'] == 'email' || $app_config['proxy_alerts'] == 'all' ) {
		
      if ( trim($app_config['from_email']) != '' && trim($app_config['to_email']) != '' ) {
      	
					
			// Config error check(s)
         if ( validate_email($app_config['from_email']) != 'valid' ) {
         $config_parse_error[] = 'FROM email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          		
         if ( validate_email($app_config['to_email']) != 'valid' ) {
         $config_parse_error[] = 'TO email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          	
		}
		
	}
          
	
	// Text for proxy alerts
	if ( $app_config['proxy_alerts'] == 'text' || $app_config['proxy_alerts'] == 'all' ) {
		
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( trim($text_parse[0]) != '' && trim($text_parse[1]) != 'skip_network_name'
      || trim($app_config['textbelt_apikey']) != '' && $app_config['textlocal_account'] == ''
      || trim($app_config['textbelt_apikey']) == '' && $app_config['textlocal_account'] != '' ) {
      	
				
			// Config error check(s)
         if ( sizeof($text_parse) < 2 ) {
         $config_parse_error[] = 'Number / carrier formatting for text email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
			
         if ( is_numeric($text_parse[0]) == FALSE ) {
         $config_parse_error[] = 'Number for text email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          		
         if ( $text_parse[1] != 'skip_network_name' && validate_email( text_email($app_config['to_text']) ) != 'valid' ) {
         $config_parse_error[] = 'Mobile text services carrier name (for email-to-text) not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          	
		}
		
	}
          		
          	
	// proxy login configuration check
	
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
	if ( $app_config['proxy_login'] != '' ) {
		
	$proxy_login_parse = explode("||", $app_config['proxy_login'] );
         
		if ( sizeof($proxy_login_parse) < 2 || trim($proxy_login_parse[0]) == '' || $proxy_login_parse[1] == '' ) {
   	$config_parse_error[] = 'Proxy username / password not formatted properly.';
      $proxy_parse_errors = $proxy_parse_errors + 1;
		}
	
	}
	
          	
	// Check proxy config
	foreach ( $app_config['proxy_list'] as $proxy ) {
          		
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
if ( !isset( $app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing] ) ) {

	foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'] as $pairing_key => $unused ) {
	$avialable_btc_pairings .= strtolower($pairing_key) . ', ';
	}
	$avialable_btc_pairings = trim($avialable_btc_pairings);
	$avialable_btc_pairings = rtrim($avialable_btc_pairings,',');
	
$config_parse_error[] = 'Charts and price alerts cannot run properly, because the "btc_primary_currency_pairing" (default Bitcoin currency pairing) value \''.$app_config['btc_primary_currency_pairing'].'\' in config.php is not a valid Bitcoin pairing option (valid Bitcoin pairing options are: '.$avialable_btc_pairings.')';

}
elseif ( !isset( $app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing][$default_btc_primary_exchange] ) ) {

	foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing] as $pairing_key => $unused ) {
	$avialable_btc_primary_exchanges .= strtolower($pairing_key) . ', ';
	}
	$avialable_btc_primary_exchanges = trim($avialable_btc_primary_exchanges);
	$avialable_btc_primary_exchanges = rtrim($avialable_btc_primary_exchanges,',');
	
$config_parse_error[] = 'Charts and price alerts cannot run properly, because the "btc_primary_exchange" (default Bitcoin exchange) value \''.$default_btc_primary_exchange.'\' in config.php is not a valid option for \''.$default_btc_primary_currency_pairing.'\' Bitcoin pairings (valid \''.$default_btc_primary_currency_pairing.'\' Bitcoin pairing options are: '.$avialable_btc_primary_exchanges.')';

}


$text_parse = explode("||", trim($app_config['to_text']) );
          
          
// Check other charts/price alerts configs
if ( trim($app_config['from_email']) != '' && trim($app_config['to_email']) != '' || sizeof($text_parse) > 0 || trim($app_config['notifyme_accesscode']) != '' ) {
          
          
		// Email
      if ( trim($app_config['from_email']) != '' && trim($app_config['to_email']) != '' ) {
      	
      $alerts_enabled_types[] = 'Email';
					
			// Config error check(s)
         if ( validate_email($app_config['from_email']) != 'valid' ) {
         $config_parse_error[] = 'FROM email not configured properly for price alerts.';
         }
          		
         if ( validate_email($app_config['to_email']) != 'valid' ) {
         $config_parse_error[] = 'TO email not configured properly for price alerts.';
         }
          	
		}
          	
          	
		// Text
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( trim($text_parse[0]) != '' && trim($text_parse[1]) != 'skip_network_name'
      || trim($app_config['textbelt_apikey']) != '' && $app_config['textlocal_account'] == ''
      || trim($app_config['textbelt_apikey']) == '' && $app_config['textlocal_account'] != '' ) {
      	
      $alerts_enabled_types[] = 'Text';
				
			// Config error check(s)
         if ( sizeof($text_parse) < 2 ) {
         $config_parse_error[] = 'Number / carrier formatting for text email not configured properly for price alerts.';
         }
			
         if ( is_numeric($text_parse[0]) == FALSE ) {
         $config_parse_error[] = 'Number for text email not configured properly for price alerts.';
         }
          		
         if ( $text_parse[1] != 'skip_network_name' && validate_email( text_email($app_config['to_text']) ) != 'valid' ) {
         $config_parse_error[] = 'Mobile text services carrier name (for email-to-text) not configured properly for price alerts.';
         }
          	
		}
          	
          	
      // Notifyme (alexa)
      if ( trim($app_config['notifyme_accesscode']) != '' ) {
      $alerts_enabled_types[] = 'Alexa';
      }
          	
          	
      // Our alert types
      if ( sizeof($alerts_enabled_types) > 0 ) {
          		
        foreach ( $alerts_enabled_types as $type ) {
        $price_alert_type_text .= $type . ' / ';
        }
          		
      $price_alert_type_text = substr($price_alert_type_text, 0, -3);
          		
          		

			// Check $app_config['asset_charts_and_alerts'] config
			if ( !is_array($app_config['asset_charts_and_alerts']) ) {
			$config_parse_error[] = 'The asset / exchange / pairing price alert formatting is corrupt, or not configured yet.';
			}
			
			
			foreach ( $app_config['asset_charts_and_alerts'] as $key => $value ) {
   		       		
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
if ( $app_config['smtp_login'] != '' && $app_config['smtp_server'] != '' ) {
	
	
// SMTP configuration check
$smtp_login_parse = explode("||", $app_config['smtp_login'] );
$smtp_server_parse = explode(":", $app_config['smtp_server'] );

	if ( sizeof($smtp_login_parse) < 2 || trim($smtp_login_parse[0]) == '' || $smtp_login_parse[1] == '' ) {
   $config_parse_error[] = 'SMTP username / password not formatted properly.';
	}
	
	if ( sizeof($smtp_server_parse) < 2 || trim($smtp_server_parse[0]) == '' || !is_numeric( trim($smtp_server_parse[1]) ) ) {
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
if ( $app_config['mail_logs'] > 0 && trim($app_config['from_email']) != '' && trim($app_config['to_email']) != '' ) {
					
	// Config error check(s)
   if ( validate_email($app_config['from_email']) != 'valid' ) {
   $config_parse_error[] = 'FROM email not configured properly for emailing error logs.';
   }
          		
   if ( validate_email($app_config['to_email']) != 'valid' ) {
   $config_parse_error[] = 'TO email not configured properly for emailing error logs.';
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
if ( $app_config['charts_page'] == 'on' && $app_config['charts_backup_freq'] > 0 && trim($app_config['from_email']) != '' && trim($app_config['to_email']) != '' ) {
					
	// Config error check(s)
   if ( validate_email($app_config['from_email']) != 'valid' ) {
   $config_parse_error[] = 'FROM email not configured properly for emailing backup archive notice / link.';
   }
          		
   if ( validate_email($app_config['to_email']) != 'valid' ) {
   $config_parse_error[] = 'TO email not configured properly for emailing backup archive notice / link.';
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
          	



// Check $app_config['portfolio_assets'] config
if ( !is_array($app_config['portfolio_assets']) ) {
app_logging('config_error', 'The portfolio assets formatting is corrupt, or not configured yet');
}

// Check default / dynamic Bitcoin market/pairing configs
if ( !isset( $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']] ) ) {

	foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'] as $pairing_key => $unused ) {
	$avialable_btc_pairings .= strtolower($pairing_key) . ', ';
	}
	$avialable_btc_pairings = trim($avialable_btc_pairings);
	$avialable_btc_pairings = rtrim($avialable_btc_pairings,',');

app_logging('config_error', 'Portfolio cannot run properly, because the "btc_primary_currency_pairing" (Bitcoin primary currency pairing) value \''.$app_config['btc_primary_currency_pairing'].'\' is not a valid Bitcoin pairing option (valid Bitcoin pairing options are: '.$avialable_btc_pairings.')');

}
elseif ( !isset( $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']][$app_config['btc_primary_exchange']] ) ) {

	foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']] as $pairing_key => $unused ) {
	$avialable_btc_primary_exchanges .= strtolower($pairing_key) . ', ';
	}
	$avialable_btc_primary_exchanges = trim($avialable_btc_primary_exchanges);
	$avialable_btc_primary_exchanges = rtrim($avialable_btc_primary_exchanges,',');

app_logging('config_error', 'Portfolio cannot run properly, because the "btc_primary_exchange" (Bitcoin exchange) value \''.$app_config['btc_primary_exchange'].'\' is not a valid option for \''.$app_config['btc_primary_currency_pairing'].'\' Bitcoin pairings (valid \''.$app_config['btc_primary_currency_pairing'].'\' Bitcoin pairing options are: '.$avialable_btc_primary_exchanges.')');

}

			
			
			
// END of basic configuration file checks

  
 
 ?>