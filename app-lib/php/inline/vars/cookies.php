<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
 
 
if ( !$_POST['submit_check'] && $_COOKIE['coin_amnts'] ) {

$ui_cookies = true;

$all_cookies_data_array = array('');
		
	
$all_asset_mrkts_cookie_array = explode("#", $_COOKIE['coin_mrkts']);
	
		if ( is_array($all_asset_mrkts_cookie_array) ) {
			
				foreach ( $all_asset_mrkts_cookie_array as $asset_mrkts ) {
        	       
                    $temp = null;
									
				$single_asset_mrkt_cookie_array = explode("-", $asset_mrkts);
					
				$asset_symb = strtoupper(preg_replace("/_mrkt/i", "", $single_asset_mrkt_cookie_array[0]));
					
				$temp = $single_asset_mrkt_cookie_array[1];
					
				//var_dump($single_asset_mrkt_cookie_array);
					
    					if ( $temp > 0 ) {
    					$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_mrkt'] = $temp;
    					}
    					else {
    					$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_mrkt'] = 1;
    					}
					
					
				}
					
		}
	
	
$all_asset_pairs_cookie_array = explode("#", $_COOKIE['coin_pairs']);
	
		if ( is_array($all_asset_pairs_cookie_array) ) {
			
				foreach ( $all_asset_pairs_cookie_array as $asset_pairs ) {
									
				$single_asset_pair_cookie_array = explode("-", $asset_pairs);
					
				$asset_symb = strtoupper(preg_replace("/_pair/i", "", $single_asset_pair_cookie_array[0]));
					
				$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_pair'] = $single_asset_pair_cookie_array[1];
					
				}
					
		}
	
	
$all_asset_paid_cookie_array = explode("#", $_COOKIE['coin_paid']);
	
		if ( is_array($all_asset_paid_cookie_array) ) {
			
				foreach ( $all_asset_paid_cookie_array as $asset_paid ) {
									
				$single_asset_paid_cookie_array = explode("-", $asset_paid);
					
				$asset_symb = strtoupper(preg_replace("/_paid/i", "", $single_asset_paid_cookie_array[0]));
					
				$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_paid'] = $single_asset_paid_cookie_array[1];
					
				}
					
		}
	
	
$all_asset_lvrg_cookie_array = explode("#", $_COOKIE['coin_lvrg']);
	
		if ( is_array($all_asset_lvrg_cookie_array) ) {
			
				foreach ( $all_asset_lvrg_cookie_array as $asset_lvrg ) {
									
				$single_asset_lvrg_cookie_array = explode("-", $asset_lvrg);
					
				$asset_symb = strtoupper(preg_replace("/_lvrg/i", "", $single_asset_lvrg_cookie_array[0]));
					
				$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_lvrg'] = $single_asset_lvrg_cookie_array[1];
					
				}
					
		}
	
	
$all_asset_mrgntyp_cookie_array = explode("#", $_COOKIE['coin_mrgntyp']);
	
		if ( is_array($all_asset_mrgntyp_cookie_array) ) {
			
				foreach ( $all_asset_mrgntyp_cookie_array as $asset_mrgntyp ) {
									
				$single_asset_mrgntyp_cookie_array = explode("-", $asset_mrgntyp);
					
				$asset_symb = strtoupper(preg_replace("/_mrgntyp/i", "", $single_asset_mrgntyp_cookie_array[0]));
					
                    // DECOMPRESS from saving on cookie storage space
				$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_mrgntyp'] = ( $single_asset_mrgntyp_cookie_array[1] == 'shrt' ? 'short' : 'long' );
					
				}
					
		}
	
	
		
		
	
$all_asset_amnts_cookie_array = explode("#", $_COOKIE['coin_amnts']);
	
		if ( is_array($all_asset_amnts_cookie_array) ) {
			
			
				foreach ( $all_asset_amnts_cookie_array as $asset_amnts ) {
									
				$single_asset_amnt_cookie_array = explode("-", $asset_amnts);
					
				$asset_symb = strtoupper(preg_replace("/_amnt/i", "", $single_asset_amnt_cookie_array[0]));
				
				    if ( $asset_symb == 'BTC' && !$btc_mrkt ) {
							    
    	                       // Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
    	                       // (we go by array index number here, rather than 1 or higher for html form values)
    	                       if ( $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_mrkt'] > 0 ) {
					   $btc_mrkt = ($all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_mrkt'] - 1);
    	                       }
    	                       else {
    	                       $btc_mrkt = 0;
    	                       }
							
				    }
	
				$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_amnt'] = $single_asset_amnt_cookie_array[1];
					
				}
					
					
		}
		

}


//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////


// If cookies are enabled or not, update accordingly
if ( $_POST['submit_check'] == 1 && $_POST['use_cookies'] != 1 || $reset_all_cookies ) {
    
$ct['sec']->delete_all_cookies(); // Delete any existing cookies, if cookies have been disabled / reset

    // Reset cookies / populated form data cleanly, with a redirect back to the app after 0.12 seconds
    if ( $reset_all_cookies ) {
    usleep(120000); // Wait 
    header("Location: " . $ct['gen']->start_page($_GET['start_page'])); // Preserve any start page data
    exit;
    }
    
}
elseif ( $_POST['submit_check'] == 1 && $_POST['use_cookies'] == 1 || $post_csv_import && $ui_cookies ) {
 
 
      // UI form POST data
      if ( $_POST['submit_check'] == 1 ) {
       
       
             // Parse portfolio values
             foreach ( $_POST as $key => $unused ) {
           
             $asset_symb = substr($key, 0, strpos($key, "_"));
            
            
                if ( preg_match("/_amnt/i", $key) ) {
                
                    if ( $ct['var']->rem_num_format($_POST[$key]) >= $watch_only_flag_val ) {
                    $set_asset_vals .= $key.'-'. $ct['var']->rem_num_format($_POST[$key]) . '#';
                    }
                
                }
             
            
                if ( preg_match("/_mrkt/i", $key) ) {
                        
                    if ( $ct['var']->rem_num_format($_POST[$asset_symb . '_amnt']) >= $watch_only_flag_val ) {
                    $set_mrkt_vals .= $key.'-'. $_POST[$key] . '#';
                    }
                
                }
             
            
                if ( preg_match("/_pair/i", $key) ) {
                        
                    if ( $ct['var']->rem_num_format($_POST[$asset_symb . '_amnt']) >= $watch_only_flag_val ) {
                    $set_pair_vals .= $key.'-'. $_POST[$key] . '#';
                    }
                
                }
            
            
                // If purchased amount (not just watched), AND cost basis
                
                if ( preg_match("/_paid/i", $key) ) {
                        
                    if (
                    $ct['var']->rem_num_format($_POST[$key]) >= $ct['min_fiat_val_test']
                    && $ct['var']->rem_num_format($_POST[$asset_symb . '_amnt']) >= $ct['min_crypto_val_test']
                    ) {
                    $set_paid_vals .= $key.'-'. $ct['var']->rem_num_format($_POST[$key]) . '#';
                    }
                        
               }
                     
                    
                           
               if ( preg_match("/_lvrg/i", $key) ) {
                                
                     if ( $ct['var']->rem_num_format($_POST[$asset_symb . '_paid']) >= $ct['min_fiat_val_test']
                     && $ct['var']->rem_num_format($_POST[$asset_symb . '_amnt']) >= $ct['min_crypto_val_test']
                     ) {
                     $set_lvrg_vals .= $key.'-'. $_POST[$key] . '#';
                     }
                                
               }
                             
                            
               if ( preg_match("/_mrgntyp/i", $key) ) {
                                
                     if ( $ct['var']->rem_num_format($_POST[$asset_symb . '_paid']) >= $ct['min_fiat_val_test']
                     && $ct['var']->rem_num_format($_POST[$asset_symb . '_amnt']) >= $ct['min_crypto_val_test']
                     ) {
                     // COMPRESS to save on cookie storage space
                     $set_mrgntyp_vals .= $key.'-'. ( $_POST[$key] == 'short' ? 'shrt' : 'lng' ) . '#';
                     }
                                
                }
             
            
             }
       
       
       }
       // File import form POST data
       elseif ( $post_csv_import ) {
       
       
     	    foreach( $csv_file_array as $key => $val ) {
     	        		
     	    // Must be compatible with UI form cookie storage method: $key.'-'. $_POST[$key] . '#'
     	    
     	    // We already validated / auto-corrected $csv_file_array
     	    
     	    $compat_key = strtolower($key);
     	    
     	     
          	       if ( $ct['var']->rem_num_format($val[1]) >= $watch_only_flag_val ) {
          	           
          	       $set_asset_vals .= $compat_key . '_amnt-' . $ct['var']->rem_num_format($val[1]) . '#';
          	       
              	       $set_mrkt_vals .= $compat_key . '_mrkt-' . $val[5] . '#';
              	     	
              	       $set_pair_vals .= $compat_key . '_pair-' . $val[6] . '#';
          	     
                        		  // If purchased amount (not just watched), AND cost basis
                        	       if (
                        	       $ct['var']->rem_num_format($val[2]) >= $ct['min_fiat_val_test']
                        	       && $ct['var']->rem_num_format($val[1]) >= $ct['min_crypto_val_test']
                        	       ) {
                        	       $set_paid_vals .= $compat_key . '_paid-' . $ct['var']->rem_num_format($val[2]) . '#';
                        	       $set_lvrg_vals .= $compat_key . '_lvrg-' . $val[3] . '#';
                                   // COMPRESS to save on cookie storage space
                        	       $set_mrgntyp_vals .= $compat_key . '_mrgntyp-' . ( $val[4] == 'short' ? 'shrt' : 'lng' ) . '#';
                        	       }
          	       
          	       }
     	     
     	     
     	    }
     	        		
       
       }



// Store all cookies and redirect to app URL, to clear any POST data from any future page refreshing

$set_asset_vals = ( $set_asset_vals != NULL ? $set_asset_vals : ' ' ); // Initialized with some whitespace when blank


// 'cookie_name' => cookie_value
$cookie_params = array(
				   'coin_amnts' => $set_asset_vals,
				   'coin_pairs' => $set_pair_vals,
				   'coin_mrkts' => $set_mrkt_vals,
				   'coin_paid' => $set_paid_vals,
				   'coin_lvrg' => $set_lvrg_vals,
				   'coin_mrgntyp' => $set_mrgntyp_vals,
				  );


$ct['sec']->update_all_cookies($cookie_params);

header("Location: " . $ct['gen']->start_page($_GET['start_page'])); // Preserve any start page data
exit;
 
}
  
 
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>