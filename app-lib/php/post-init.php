<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



/////////////////////////////////////////////////


// Set BTC / USD default value
// $btc_usd ACTS UP TRYING TO PASS INTO FUNCTIONS FOR SOME VERY ODD REASON
// FOR NOW, PASS $btc_exchange INTO FUNCTIONS INSTEAD, AND REUSE THIS CODE WITHIN THE FUNCTION
$btc_usd = get_btc_usd($btc_exchange)['last_trade'];




// Only need below logic during UI runtime
if ( $runtime_mode == 'ui' ) {

	// We can safely dismiss alerts with cookies enabled, without losing data
	if ( $_COOKIE['coin_amounts'] != '' ) {
	$dismiss_alert = ' <br /><br /><a href="'.start_page($_GET['start_page'], 'href').'">Dismiss Alert</a>';
	}
		
	
	// If CSV file import is in process, check it
	if ( $_POST['csv_check'] == 1 ) {
		
		
		// Checks and importing
		if ( $_FILES['csv_file']['tmp_name'] != NULL ) {
		$csv_file_array = csv_file_array($_FILES['csv_file']['tmp_name']);
   	}
   	else {
   	$csv_import_fail = 'You forgot to select your CSV import file.' . $dismiss_alert;
   	}
   	
   	
		if ( !$csv_import_fail && !is_array($csv_file_array) ) {
   	$csv_import_fail = 'Your CSV import file does not appear to be formatted correctly. You can <a href="download.php?example_template=1" target="_blank">download this example template</a> to start over with correct formatting.' . $dismiss_alert;
   	}
		elseif ( is_array($csv_file_array) ) {
   	$csv_import_succeed = 'Your CSV import succeeded.' . $dismiss_alert;
   	}
   	
   	if ( !$csv_import_fail && $_POST['csv_check'] == 1 ) {
   	$run_csv_import = 1;
   	}
   
	}
	
	
// Now that $run_csv_import has been determined, we can call our cookie logic
require_once( $base_dir . "/app-lib/php/other/cookies.php");


$marketcap_site = ( $alert_percent[0] != '' ? $alert_percent[0] : $marketcap_site );


}



// Chart data caches
foreach ( $asset_charts_and_alerts as $key => $value ) {
	
	// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
	$asset_dir = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
	$asset_dir = strtoupper($asset_dir);
		
	$asset_cache_params = explode("||", $value);
	
	if ( $asset_cache_params[2] == 'chart' || $asset_cache_params[2] == 'both' ) {
	
		if ( dir_structure($base_dir . '/cache/charts/'.$asset_dir.'/') != TRUE ) { // Attempt to create directory if it doesn't exist
		$disabled_caching = 1;
		}
	
	}
	
	
}


	
if ( $disabled_caching == 1 ) {
echo "Improper directory permissions on the '/cache/charts/' directory, cannot create asset sub-directories. Make sure the folder '/cache/charts/' itself has read / write permissions (and these sub-directories should be created automatically)";
exit;
}


// Run some basic configuration file checks


// Proxy configuration check
if ( sizeof($proxy_list) > 0 ) {
	

	$proxy_parse_errors = 0;
	
	
	// Email for proxy alerts
	if ( $proxy_alerts == 'email' || $proxy_alerts == 'all' ) {
		
      if ( trim($from_email) != '' && trim($to_email) != '' ) {
      	
					
			// Config error check(s)
         if ( validate_email($from_email) != 'valid' ) {
         $config_parse_error[] = 'FROM email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          		
         if ( validate_email($to_email) != 'valid' ) {
         $config_parse_error[] = 'TO email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          	
		}
		
	}
          
	
	// Text for proxy alerts
	if ( $proxy_alerts == 'text' || $proxy_alerts == 'all' ) {
		
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( trim($text_parse[0]) != '' && trim($text_parse[1]) != 'number_only'
      || trim($textbelt_apikey) != '' && $textlocal_account == ''
      || trim($textbelt_apikey) == '' && $textlocal_account != '' ) {
      	
				
			// Config error check(s)
         if ( sizeof($text_parse) < 2 ) {
         $config_parse_error[] = 'Number / carrier formatting for text email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
			
         if ( is_numeric($text_parse[0]) == FALSE ) {
         $config_parse_error[] = 'Number for text email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          		
         if ( $text_parse[1] != 'number_only' && validate_email( text_email($to_text) ) != 'valid' ) {
         $config_parse_error[] = 'Carrier for text email not configured properly for proxy alerts.';
      	$proxy_parse_errors = $proxy_parse_errors + 1;
         }
          	
		}
		
	}
          		
          	
	// proxy login configuration check
	
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
	if ( $proxy_login != '' ) {
		
	$proxy_login_parse = explode("||", $proxy_login );
         
		if ( sizeof($proxy_login_parse) < 2 || trim($proxy_login_parse[0]) == '' || $proxy_login_parse[1] == '' ) {
   	$config_parse_error[] = 'Proxy username / password not formatted properly.';
      $proxy_parse_errors = $proxy_parse_errors + 1;
		}
	
	}
	
          	
	// Check proxy config
	foreach ( $proxy_list as $proxy ) {
          		
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
	app_error('config_error', $proxy_config_alert);
	}
          		
	// Displaying if checks passed
	if ( sizeof($config_parse_error) < 1 ) {
   $proxy_config_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
$config_parse_error = NULL; // Blank it out for any other config checks
          		
}




// Price change alerts configuration check
$text_parse = explode("||", trim($to_text) );
          
// Check price alert configs
if ( trim($from_email) != '' && trim($to_email) != '' || sizeof($text_parse) > 0 || trim($notifyme_accesscode) != '' ) {
          
          
		// Email
      if ( trim($from_email) != '' && trim($to_email) != '' ) {
      	
      $alerts_enabled_types[] = 'Email';
					
			// Config error check(s)
         if ( validate_email($from_email) != 'valid' ) {
         $config_parse_error[] = 'FROM email not configured properly for price alerts.';
         }
          		
         if ( validate_email($to_email) != 'valid' ) {
         $config_parse_error[] = 'TO email not configured properly for price alerts.';
         }
          	
		}
          	
          	
		// Text
		// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
      if ( trim($text_parse[0]) != '' && trim($text_parse[1]) != 'number_only'
      || trim($textbelt_apikey) != '' && $textlocal_account == ''
      || trim($textbelt_apikey) == '' && $textlocal_account != '' ) {
      	
      $alerts_enabled_types[] = 'Text';
				
			// Config error check(s)
         if ( sizeof($text_parse) < 2 ) {
         $config_parse_error[] = 'Number / carrier formatting for text email not configured properly for price alerts.';
         }
			
         if ( is_numeric($text_parse[0]) == FALSE ) {
         $config_parse_error[] = 'Number for text email not configured properly for price alerts.';
         }
          		
         if ( $text_parse[1] != 'number_only' && validate_email( text_email($to_text) ) != 'valid' ) {
         $config_parse_error[] = 'Carrier for text email not configured properly for price alerts.';
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
          		
          		

			// Check $asset_charts_and_alerts config
			if ( !is_array($asset_charts_and_alerts) ) {
			$config_parse_error[] = 'The asset / exchange / pairing price alert formatting is corrupt, or not configured yet.';
			}
			
			
			foreach ( $asset_charts_and_alerts as $key => $value ) {
   		       		
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
			app_error('config_error', $price_change_config_alert);
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
if ( $smtp_login != '' && $smtp_server != '' ) {
	
	
// SMTP configuration check
$smtp_login_parse = explode("||", $smtp_login );
$smtp_server_parse = explode(":", $smtp_server );

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
	app_error('config_error', $smtp_config_alert);
	}

        
   // Displaying if checks passed
   if ( sizeof($config_parse_error) < 1 ) {
   $smtp_config_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
   $config_parse_error = NULL; // Blank it out for any other config checks
          		
	
}





// Email logs configs
if ( $mail_error_logs > 0 && trim($from_email) != '' && trim($to_email) != '' ) {
					
	// Config error check(s)
   if ( validate_email($from_email) != 'valid' ) {
   $config_parse_error[] = 'FROM email not configured properly for emailing error logs.';
   }
          		
   if ( validate_email($to_email) != 'valid' ) {
   $config_parse_error[] = 'TO email not configured properly for emailing error logs.';
   }


   // Displaying that errors were found
   if ( $config_parse_error >= 1 ) {
   $errorlogs_config_alert .=  '<span class="red">Email error logs configuration error(s):</span>' . "<br /> \n";
   }
          		
   // Displaying any config errors
   foreach ( $config_parse_error as $error ) {
   $errorlogs_config_alert .= '<span class="red">' . $error . '</span>' . "<br /> \n";
   }
          		

	if ( $errorlogs_config_alert ) {
	app_error('config_error', $errorlogs_config_alert);
	}

        
   // Displaying if checks passed
   if ( sizeof($config_parse_error) < 1 ) {
   $errorlogs_config_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
   $config_parse_error = NULL; // Blank it out for any other config checks
          		       	
}
          	



// Email backup archives configs
if ( $charts_page == 'on' && $charts_backup_freq > 0 && trim($from_email) != '' && trim($to_email) != '' ) {
					
	// Config error check(s)
   if ( validate_email($from_email) != 'valid' ) {
   $config_parse_error[] = 'FROM email not configured properly for emailing backup archive notice / link.';
   }
          		
   if ( validate_email($to_email) != 'valid' ) {
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
	app_error('config_error', $backuparchive_config_alert);
	}

        
   // Displaying if checks passed
   if ( sizeof($config_parse_error) < 1 ) {
   $backuparchive_config_alert .= '<span class="green">Config formatting seems ok.</span>';
   }
          		
   $config_parse_error = NULL; // Blank it out for any other config checks
          		       	
}
          	



// Check $coins_list config
if ( !is_array($coins_list) ) {
app_error('config_error', 'The coins list formatting is corrupt, or not configured yet');
}
			
			
			
// END of basic configuration file checks



/////////////////////////////////////////////////




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



// Re-check the average time interval between chart data points, once every 24 hours
// If we just started collecting data, check frequently
// (placeholder is always set to 1 to keep chart buttons from acting weird until we have enough data)
if ( $charts_page == 'on' && update_cache_file('cache/vars/chart_interval.dat', (60 * 24) ) == true
|| !is_numeric(trim(file_get_contents('cache/vars/chart_interval.dat'))) || trim(file_get_contents('cache/vars/chart_interval.dat')) == 1 ) {  
	
	foreach ( $asset_charts_and_alerts as $key => $value ) {
	
		if ( trim($find_first_filename) == '' ) {
			
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$find_first_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$find_first_asset = strtoupper($find_first_asset);
	
		$find_first_chart = explode("||", $value);
		
			if ( $find_first_asset == 'BTC' ) {
			$find_first_chart[1] = 'usd';
			}

			if ( $find_first_chart[2] == 'both' || $find_first_chart[2] == 'chart' ) {
			$find_first_filename = 'cache/charts/'.$find_first_asset.'/'.$key.'_chart_'.$find_first_chart[1].'.dat';
			}

		}
		
	}

// Dynamically determine average time interval with the last 500 lines (or max available if less), presume an average max characters length of ~40
$charts_update_freq = chart_time_interval($find_first_filename, 500, 40);

store_file_contents($base_dir . '/cache/vars/chart_interval.dat', $charts_update_freq);

}


// Chart update frequency
$charts_update_freq = ( $charts_update_freq != '' ? $charts_update_freq : trim( file_get_contents('cache/vars/chart_interval.dat') ) );



?>