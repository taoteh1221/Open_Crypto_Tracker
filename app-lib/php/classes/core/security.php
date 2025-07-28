<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



class ct_sec {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;

var $ct_array = array();


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////

   
   function sanitize_string($data) {
   
   $data = strip_tags($data);
   $data = htmlspecialchars($data);
   
   return $data;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function digest($str, $max_length=false) {
   
      if ( $max_length != false && $max_length > 0 ) {
      $result = substr( hash('ripemd160', $str) , 0, $max_length);
      }
      else {
      $result = hash('ripemd160', $str);
      }
      
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function session_clear() {
   
   // This logic below helps assure session data is cleared
   session_unset();
   session_destroy();
   session_write_close();
   setcookie(session_name( $this->id() ),'',0,'/');
   session_regenerate_id(true);
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   // To keep admin nonce key a secret, and make CSRF attacks harder with a different key per submission item
   function admin_nonce($key, $force=false) {
      
      // WE NEED A SEPERATE FUNCTION $this->nonce_digest(), SO WE DON'T #ENDLESSLY LOOP# FROM OUR
      // $this->admin_logged_in() CALL (WHICH ALSO USES $this->nonce_digest() INSTEAD OF $this->admin_nonce())
      if ( $this->admin_logged_in() || $force != false ) {
      return $this->nonce_digest($key);
      }
      else {
      return false;
      }
      
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function pass_sec_check($val, $hash_key) {
        
   global $ct;
   
      if ( !$ct['possible_input_injection'] && $this->admin_logged_in() && isset($val) && trim($val) != '' && isset($hash_key) && trim($hash_key) != '' && $this->admin_nonce($hash_key) != false && $val == $this->admin_nonce($hash_key) ) {
      return true;
      }
      else {
      return false;
      }
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function passed_medium_security_check() {
   
   global $ct;
       
       if ( $ct['admin_area_sec_level'] != 'medium' || $ct['admin_area_sec_level'] == 'medium' && $this->pass_sec_check($_POST['medium_security_nonce'], 'medium_security_mode') && $this->valid_2fa('strict') ) {
       return true;
       }
       else {
       return false;
       }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function admin_logged_in() {
      
      // IF REQUIRED DATA NOT SET, REFUSE ADMIN AUTHORIZATION
      if (
      !isset( $_COOKIE['admin_auth_' . $this->id()] )
      || !isset( $_SESSION['nonce'] )
      || !isset( $_SESSION['admin_logged_in']['auth_hash'] ) 
      ) {
      return false;
      }
      // WE SPLIT THE LOGIN AUTH BETWEEN COOKIE AND SESSION DATA (TO BETTER SECURE LOGIN AUTHORIZATION)
      elseif ( $this->nonce_digest( $_COOKIE['admin_auth_' . $this->id()] ) == $_SESSION['admin_logged_in']['auth_hash'] ) {
      return true;
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function nonce_digest($data, $custom_nonce=false) {
      
      if ( isset($data) && $custom_nonce != false ) {
      return $this->digest( $data . $custom_nonce );
      }
      // FOR ASSURANCE OF SECURE DIGEST ENTROPY, WE ONLY ACCEPT THE SESSION NONCE IF
      // IT'S AT LEAST 32 CHARACTERS, AS WE CREATED IT WITH $this->rand_hash(32)
      // (WE'RE PLAYING IT SAFE UX-WISE WITH THIS CHECK, AS 32 BYTES SHOULD BE 64 CHARACTERS)
      elseif ( isset($data) && isset($_SESSION['nonce']) && strlen( trim($_SESSION['nonce']) ) >= 32 ) {
      return $this->digest( $data . $_SESSION['nonce'] );
      }
      else {
      return false;
      }
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function valid_2fa($alt_mode=false, $force_check=false) {
   
   global $ct, $auth_secret_2fa;
   
       // If 2FA is off, OR mode doesn't apply in this instance (AS LONG AS WE AREN'T *FORCE* CHECKING DURING 2FA SETUP)
       if ( !$force_check && $ct['admin_area_2fa'] == 'off' || !$force_check && $alt_mode && $ct['admin_area_2fa'] != $alt_mode || isset($_POST['2fa_code']) && $ct['auth_2fa']->checkCode($auth_secret_2fa, $_POST['2fa_code']) ) {
       return true;
       }
       // Otherwise, alert end-user of the invalid 2FA code they entered
       else {
       $ct['check_2fa_id'] = $_POST['2fa_code_id'];
       $ct['check_2fa_error'] = '2FA passcode was invalid, please try again';
       return false;
       }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function obfusc_data($data) {
      
   global $ct;
   
   // Keep our color-coded logs in the admin UI pretty
   $protocol = preg_match('/(?:(ht|f)tp(s?)\:\/\/)/', $data, $matches);
   
      if ( is_array($matches) && sizeof($matches) > 0 ) {
      $protocol = preg_replace("/:\/\/(.*)/i", "", $matches[0]);
      $data = preg_replace('/(?:(ht|f)tp(s?)\:\/\/)/', "(" . $protocol . ")", $data);
      }
      
      // Obfuscate everything in $ct['dev']['data_obfuscating']
      foreach( $ct['dev']['data_obfuscating'] as $hide_val ) {
      $data = str_replace($hide_val, $this->obfusc_str($hide_val, 2), $data);
      }
   
   return $data;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function malware_scan_requests($method, $ext_key, $data, $mysqli_connection=false) {
   
   
        if ( is_array($data) ) {
        
            foreach ( $data as $key => $val ) {
                
                if ( is_array($val) ) {
                $data[$key] = $this->malware_scan_requests($method, $key, $val, $mysqli_connection);
                }
                else {
                $data[$key] = $this->malware_scan_string($method, $key, $val, $mysqli_connection);
                }
            
            }
        
        }
        else {
        $data = $this->malware_scan_string($method, $ext_key, $data, $mysqli_connection);
        }
   
   
   return $data;
        
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function rand_hash($num_bytes) {
        
   global $ct;
   
      // Upgrade required
      if ( PHP_VERSION_ID < 70000 ) {
      	
      $ct['gen']->log(
      		'security_error',
      		'Upgrade to PHP v7 or later to support cryptographically secure pseudo-random bytes in this application, or your application may not function properly'
      		);
      
      }
      // >= PHP 7
      elseif ( PHP_VERSION_ID >= 70000 ) {
      $hash = random_bytes($num_bytes);
      }
   
      if ( strlen($hash) == $num_bytes ) {
      return bin2hex($hash);
      }
      else {
      return false;
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function pepper_hashed_pass($password) {
   
   global $auth_secret;
   
      if ( !$auth_secret ) {
      $ct['gen']->log('conf_error', 'auth_secret not set properly');
      return false;
      }
      else {
         
      $password_pepper_hashed = hash_hmac("sha256", $password, $auth_secret);
      
         if ( $password_pepper_hashed == false ) {
         $ct['gen']->log('conf_error', 'hash_hmac() returned false in the ct_gen->pepper_hashed_pass() function');
         return false;
         }
         else {
         return password_hash($password_pepper_hashed, PASSWORD_DEFAULT);
         }
      
      }
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function possible_hex_encoding($str) {
        
      if ( $str == '' ) {
      return false;
      }
   
   // Decode the string, TO CHECK FOR *POSSIBLE* HEX ENCODING
   // (checking for illegal hex characters)
   $possible_hex = hex2bin($str);   
      
      // TECHNICALLY, we CANNOT tell if ANY VALID hex string is hex-encoded, but if it validates WELL
      // as a hex string, we flag as possible encoding (to decode / scan for attack signatures)
      if ( $possible_hex && ctype_xdigit($str) && preg_match('/^(?:0x)?[a-f0-9]{1,}$/i', $str) ) {
      return true;
      }
      else {
      return false;
      }
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function possible_base64_encoding($str) {
        
      if ( $str == '' ) {
      return false;
      }
   
   // Decode the string in strict mode, TO CHECK FOR *POSSIBLE* BASE64 ENCODING
   // (checking for illegal base64 characters)
   $possible_base64 = base64_decode($str, true); 
      
      // TECHNICALLY, we CANNOT tell if ANY VALID base64 string is base64-encoded, but if it validates WELL
      // as a base64 string, we flag as possible encoding (to decode / scan for attack signatures)
      if (
      $possible_base64
      && preg_match("/^(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=|[A-Za-z0-9+\/]{4})$/", $str)
      ) {
      return true;
      }
      else {
      return false;
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function obfusc_str($str, $show=1) {
        
      
      // If an array, just return it, as it will only print the word 'Array' anyways,
      // AND it will throw a fatal error in this function, since it's NOT a string  
      if ( is_array($str) ) {
      return $str;
      }
      else {
      $len = strlen($str);
      }
   
   
      // If string is too short for the passed $show var on each end of string, 
      // make $show roughly 20% of string length (1/5 rounded)
      if ( $len <= ($show * 2) ) {
      $show = round($len / 5);
      }
   
   
      if ( $show == 0 ) {
      return str_repeat('*', $len);
      }
      else {
      return substr($str, 0, $show) . str_repeat('*', $len - (2*$show) ) . substr($str, $len - $show, $show);
      }
      
      
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function obfusc_path_data($path) {
      
   global $ct;
   
   $basepath_array = explode("/", $path);
   
   
      // Secured cache data
      if ( preg_match("/cache\/secured/i", $path) ) {
         
      $subpath = preg_replace("/(.*)cache\/secured\//i", "", $path);
      
      $subpath_array = explode("/", $subpath);
         
         // Subdirectories of /secured/
         if ( is_array($subpath_array) && sizeof($subpath_array) > 1 ) {
         $path = str_replace($subpath_array[0], $this->obfusc_str($subpath_array[0], 1), $path);
         $path = str_replace($subpath_array[1], $this->obfusc_str($subpath_array[1], 5), $path);
         }
         // Files directly in /secured/
         else {
         $path = str_replace($subpath, $this->obfusc_str($subpath, 5), $path);
         }
            
      //$path = str_replace('cache/secured', $this->obfusc_str('cache', 0) . '/' . $this->obfusc_str('secured', 0), $path);
      
      }
      // Everything else, obfuscate just the filename OR deepest directory (last part of the path)
      elseif ( is_array($basepath_array) && sizeof($basepath_array) > 0 ) {
      $filename = sizeof($basepath_array) - 1;
      $path = str_replace($basepath_array[$filename], $this->obfusc_str($basepath_array[$filename], 5), $path);
      }
   
   
   return $path;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function chmod_path($path, $perm) {
       
   global $ct;

   $perm = octdec($perm);
     
   $result = chmod($path, $perm);

       
         if ( $result ) {
         
         $dir = new DirectoryIterator($path);
        
             foreach ($dir as $item) {
                
                if ($item->isDir() && !$item->isDot()) {
                $this->chmod_path($item->getPathname(), $perm);
                }
                
             }
         
         return true;
         
         }
         else {
         $chmod_val = substr( sprintf( '%o' , fileperms($path) ) , -4 );
         $ct['change_dir_perm'][] = $path . ':' . substr($chmod_val, 1);
         return false;
         }
     
    
   }
    
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
    
    // For captcha image
    // Credit to: https://code.tutsplus.com/tutorials/build-your-own-captcha-and-contact-form-in-php--net-5362
   function captcha_str($input, $strength=10) {
      
   $input_length = strlen($input);
   $random_str = '';
           
          $count = 0;
          while ( $count < $strength ) {
                  
            $rand_case = rand(1, 2);
                  
               if( $rand_case % 2 == 0 ){ 
               // Even number  
               $random_char = strtoupper( $input[mt_rand(0, $input_length - 1)] );
               } 
               else { 
               // Odd number
               $random_char = strtolower( $input[mt_rand(0, $input_length - 1)] );
               } 
            
            
               if ( stristr($random_str, $random_char) == false ) {
               $random_str .= $random_char;
               $count = $count + 1;
               }

            
          }
           
   return $random_str;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function check_pepper_hashed_pass($input_password, $stored_hashed_password) {
   
   global $auth_secret, $stored_admin_login;
   
      if ( !$auth_secret ) {
      $ct['gen']->log('conf_error', 'auth_secret not set properly');
      return false;
      }
      elseif ( !is_array($stored_admin_login) ) {
      $ct['gen']->log('conf_error', 'No admin login set yet to check against');
      return false;
      }
      else {
         
      $input_password_pepper_hashed = hash_hmac("sha256", $input_password, $auth_secret);
      
         if ( $input_password_pepper_hashed == false ) {
         $ct['gen']->log('conf_error', 'hash_hmac() returned false in the ct_sec->check_pepper_hashed_pass() function');
         return false;
         }
         else {
         return password_verify($input_password_pepper_hashed, $stored_hashed_password);
         }
         
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function delete_all_cookies() {
   
    // Portfolio
   $this->store_cookie('coin_amnts', '', time()-3600); // Delete
   $this->store_cookie('coin_pairs', '', time()-3600); // Delete
   $this->store_cookie('coin_mrkts', '', time()-3600); // Delete
   $this->store_cookie('coin_paid', '', time()-3600); // Delete
   $this->store_cookie('coin_lvrg', '', time()-3600); // Delete
   $this->store_cookie('coin_mrgntyp', '', time()-3600); // Delete
     
   unset($_COOKIE['coin_amnts']);
   unset($_COOKIE['coin_pairs']);
   unset($_COOKIE['coin_mrkts']);
   unset($_COOKIE['coin_paid']);
   unset($_COOKIE['coin_lvrg']);
   unset($_COOKIE['coin_mrgntyp']);
   
   // Settings
   $this->store_cookie('show_crypto_val', '', time()-3600); // Delete
   $this->store_cookie('show_secondary_trade_val', '', time()-3600); // Delete
   $this->store_cookie('theme_selected', '', time()-3600); // Delete
   $this->store_cookie('alert_percent', '', time()-3600); // Delete
   $this->store_cookie('prim_currency_mrkt_standalone', '', time()-3600); // Delete
   $this->store_cookie('prim_currency_mrkt', '', time()-3600); // Delete
     
   unset($_COOKIE['show_crypto_val']);
   unset($_COOKIE['show_secondary_trade_val']);
   unset($_COOKIE['theme_selected']);
   unset($_COOKIE['alert_percent']);
   unset($_COOKIE['prim_currency_mrkt_standalone']);
   unset($_COOKIE['prim_currency_mrkt']);
    
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // Remove old cookies from before v6.00.8, that still have the domain EXPLICITLY set
   // (these are less reliable [on some server setups] than auto-set domain cookies, which are now used in v6.00.8 and higher)
   // DON'T USE unset($_COOKIE['namehere']) WITHIN THIS FUNCTION, AS IT DOESN'T REGISTER ANY RE-CREATING IT IMMEADIATELY AFTERWARDS FOR SOME REASON
   function remove_cookie_before_v6008($name) {
   
   global $ct;
   
   $secure = ( $ct['app_edition'] == 'server' ? true : false );
   
   $time = (time()-3600);
      
      
      if ( PHP_VERSION_ID >= 70300 ) {
        
      $arr_cookie_options = array (
                                    'expires' => $time,
                                    'path' => $ct['cookie_path'],
                                    'domain' => $ct['app_host'],
                                    'secure' => $secure,
                                    'httponly' => false,
                     	            'samesite' => 'Strict', // Strict for high privacy
                                    );
      
      
      $result = setcookie($name, '', $arr_cookie_options);
      
      }
      else {
      $result = setcookie($name, '', $time, $ct['cookie_path'] . '; samesite=Strict', $ct['app_host'], $secure, false);
      }
      
      if ( $result == false ) {
      $ct['gen']->log('system_error', 'Cookie modification / creation failed for cookie "' . $name . '"');
      }
      
      
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function malware_scan($input) {
        
   global $ct;
   
   $attack_signature_count = 0;
   
   // Replace any backslash entity: &bsol; (PHP does NOT detect it)
   $check_decoded_input = preg_replace('/&bsol;/', '&#92;', $input);
   
   // Decode ALL HTML entities
   $check_decoded_input = html_entity_decode($check_decoded_input, ENT_QUOTES | ENT_XML1, 'UTF-8');
   
   $open_tags = substr_count($check_decoded_input, '<');
   
   $close_tags = substr_count($check_decoded_input, '>');
   
   $all_tags = $open_tags + $close_tags;

   
       // If code tags appear present
       // (NOT JUST A SINGLE TAG, WHICH COULD BE VALID HEX FORMAT DECODED, ***CREATING A FALSE POSITIVE***)
       if ( $open_tags > 0 && $close_tags > 0  ) {
       $attack_signature_count = $all_tags;
       }
       // Scan for ADDITIONAL malicious content, ONLY IF CODE TAGS CHECK PASSED
       else {
       $scan_input = str_replace($ct['dev']['script_injection_checks'], "", strtolower($check_decoded_input), $attack_signature_count);
       }
   
   
   return $attack_signature_count;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // Install id (10 character hash, based off base url)
   function id() {
      
   global $ct;
   
   
      if ( isset($ct['base_dir']) && trim($ct['base_dir']) != '' ) {
      // DO NOTHING
      }
      else {
      return false;
      }
   
   
      // ALWAYS BEGINS WITH 'SESSX_', SO SE CAN USE IT AS A VAR NAME IN PHP (MUST START WITH A LETTER)
      // ALREADY SET
      if ( isset($ct['app_id']) ) {
      return $ct['app_id'];
      }
      // DIFFERENT APP ID FOR INTERNAL WEBHOOK / INTERNAL API RUNTIME SESSION NAMES, AS WE USE SAMESITE=STRICT COOKIES FOR
      // PHP SESSION COOKIE PARAMS, WHICH FOR SOME REASON CAUSES IN-BROWSER SESSION COOKIE RESETS EVERY RUNTIME FROM /api/ OR /hook/
      // (OTHERWISE WORKS FINE ACCESSING FILE URLS DIRECTLY *WITHOUT* THE RewriteRules /api/ OR /hook/)
      // (THIS KEEPS THE OTHER RUNTIME SESSIONS SEPERATED FROM THESE TWO, SO NO FORCED ADMIN LOGOUTS OR OTHER MISSING SESSION
      // DATA ISSUES WITH THE OTHER RUNTIMES, AFTER ACCESSING /api/ OR /hook/ ENDPOINTS WITH JAVASCRIPT OR DIRECTLY IN BROWSER)
      elseif ( $ct['runtime_mode'] == 'webhook' || $ct['runtime_mode'] == 'int_api' ) {
      return 'SESS2_'.substr( md5('secondary_session' . $ct['base_dir']) , 0, 10); // First 10 characters;
      }
      // DESKTOP EDITION (when not running the above condition of webhook / internal api runtimes)
      elseif ( $ct['app_edition'] == 'desktop' ) {
      return 'SESSD_'.substr( md5('desktop_session' . $ct['base_dir']) , 0, 10); // First 10 characters;
      }
      // CRON
      elseif ( $ct['runtime_mode'] == 'cron' ) {
      return 'SESSC_'.substr( md5('cron_session' . $ct['base_dir']) , 0, 10); // First 10 characters
      }
      // EVERYTHING ELSE
      else {
      return 'SESS1_'.substr( md5('primary_session' . $ct['base_dir']) , 0, 10); // First 10 characters
      }
      
   
   }

   
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
      
        
        // Make sure ALL security checks pass / data structure seems valid, for updating the admin config
        // (INCLUDES 'STRICT' 2FA MODE CHECK [returns true if 'strict' 2fa is turned off, OR 'strict' 2fa checked out as valid])
        if (
        isset($_POST['conf_id'])
        && isset($_POST['interface_id'])
        && is_array($field_array_base)
        && $this->pass_sec_check($_POST['admin_nonce'], $_POST['interface_id'])
        && $this->valid_2fa('strict')
        ) {
        return $field_array_base;
        }
        else {
        $ct['gen']->log('security_error', 'FAILED CHECK in valid_secure_config_update_request() (from '.$ct['remote_ip'].'), for "conf_id": ' . $_POST['conf_id']);
        return false;
        }
        
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function pass_strength($password, $min_length, $max_length) {
   
   global $ct;
   
       
       // If our request sanitizer flags the input as containing a programming code phrase,
       // let the user know they need to avoid this (we scan ALL inputs, no exclusions for better security)
       if( $password == 'code_not_allowed' ){
       return 'programming code phrases are not allowed inside ANY user inputs within this app; ';
       }
   
   
       if ( $min_length == $max_length && mb_strlen($password, $ct['dev']['charset_default']) != $min_length ) {
       $error .= "MUST BE EXACTLY ".$min_length." characters; ";
       }
       elseif ( mb_strlen($password, $ct['dev']['charset_default']) < $min_length ) {
       $error .= "requires AT LEAST ".$min_length." characters; ";
       }
       elseif ( mb_strlen($password, $ct['dev']['charset_default']) > $max_length ) {
       $error .= "requires NO MORE THAN ".$max_length." characters; ";
       }
       
       
       if ( !preg_match("#[0-9]+#", $password) ) {
       $error .= "include one number; ";
       }
       
       if ( !preg_match("#[a-z]+#", $password) ) {
       $error .= "include one LOWERCASE letter; ";
       }
       
       if ( !preg_match("#[A-Z]+#", $password) ) {
       $error .= "include one UPPERCASE letter; ";
       }
       
       if ( !preg_match("#\W+#", $password) ) {
       $error .= "include one symbol; ";
       }
       
       if ( preg_match('/\s/',$password) ) {
       $error .= "no spaces allowed; ";
       }
       
       if ( preg_match('/\|\|/',$password) ) {
       $error .= "no double pipe (||) allowed; ";
       }
       
       if ( preg_match('/\:/',$password) ) {
       $error .= "no colon (:) allowed; ";
       }
       
       
       if( $error ){
       return 'password_strength_errors: ' . $error;
       }
       else {
       return 'valid';
       }
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function store_cookie($name, $val, $time) {
   
   global $ct;
   
   $secure = ( $ct['app_edition'] == 'server' ? true : false );
      
      
      if ( PHP_VERSION_ID >= 70300 ) {
        
      $arr_cookie_options = array (
                                    'expires' => $time,
                                    'path' => $ct['cookie_path'],
                                    'domain' => '', // LEAVE DOMAIN BLANK, SO setcookie AUTO-SETS PROPERLY (IN CASE OF EDGE-CASE REDIRECTS)
                                    'secure' => $secure,
                                    'httponly' => false,
                     	           'samesite' => 'Strict', // Strict for high privacy
                                  );
      
      $this->remove_cookie_before_v6008($name); // Backwards compatibility
      
      $result = setcookie($name, $val, $arr_cookie_options);
      
      }
      else {
      
      $this->remove_cookie_before_v6008($name); // Backwards compatibility
      
       // LEAVE DOMAIN BLANK, SO setcookie AUTO-SETS PROPERLY (IN CASE OF EDGE-CASE REDIRECTS)
      $result = setcookie($name, $val, $time, $ct['cookie_path'] . '; samesite=Strict', '', $secure, false);
      
      }
   
      
      
      // Android / Safari maximum cookie size is 4093 bytes, Chrome / Firefox max is 4096
      if ( strlen($val) > 4093 ) {
      	
      $ct['gen']->log(
      		'other_error',
      		'Cookie size is greater than 4093 bytes (' . strlen($val) . ' bytes). If saving portfolio as cookie data fails on your browser, try using CSV file import / export instead for large portfolios.'
      		);
      
      }
      
      if ( $result == false ) {
      $ct['gen']->log('system_error', 'Cookie modification / creation failed for cookie "' . $name . '"');
      }
      
      
   return $result;
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // ONLY #SETS# ADMIN LOGIN, DOES #NOT# CHECK USER / PASS OR RESET AUTHORIZATION,
   // THAT #MUST# BE DONE WITHIN THE LOGIC THAT CALLS THIS FUNCTION, #BEFORE# CALLING THIS FUNCTION!
   function do_admin_login() {
       
   global $ct;
   
   // Login now (set admin security cookie / 'auth_hash' session var), before redirect
				
   // WE SPLIT THE LOGIN AUTH BETWEEN COOKIE AND SESSION DATA (TO BETTER SECURE LOGIN AUTHORIZATION)
				
   $cookie_nonce = $this->rand_hash(32); // 32 byte
		
   $this->store_cookie('admin_auth_' . $this->id(), $cookie_nonce, time() + ($ct['conf']['sec']['admin_cookie_expires'] * 3600) );
				
   $_SESSION['admin_logged_in']['auth_hash'] = $this->admin_nonce($cookie_nonce, 'force'); // Force set, as we're not logged in fully yet
   
   
       // If admin login notifications are on
       if ( $ct['conf']['sec']['login_alert_channels'] != 'off' ) {

      
            if ( isset($ct['system_info']['distro_name']) ) {
            $system_info_summary = "\n\nApp Server System Info:\n\n" . $ct['system_info']['distro_name'] . ( isset($ct['system_info']['distro_version']) ? ' ' . $ct['system_info']['distro_version'] : '' );
            }
              
                            
       // Build the different messages, configure comm methods, and send messages
                            
       $email_msg = 'New admin login from ' . $ct['remote_ip'] . ', using browser agent: ' . "\n\n" . $ct['user_agent'] . $system_info_summary;
                            
       // Were're just adding a human-readable timestamp to smart home (audio) alerts
       $notifyme_msg = $email_msg . ' Timestamp: ' . $ct['gen']->time_date_format($ct['conf']['gen']['local_time_offset'], 'pretty_time') . '.';
                            
       $text_msg = $email_msg = 'Admin login from ' . $ct['remote_ip'] . ', using browser: ' . "\n\n" . $ct['user_agent'] . $system_info_summary;
                        
       // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
                  
       // Minimize function calls
       $text_msg = $ct['gen']->detect_unicode($text_msg); 
    			
       $admin_login_send_params = array(
                                        'notifyme' => $notifyme_msg,
                                        'telegram' => $email_msg,
                                        'text' => array(
                                                       'message' => $text_msg['content'],
                                                       'charset' => $text_msg['charset']
                                                       ),
                                        'email' => array(
                                                        'subject' => 'New Admin Login From ' . $ct['remote_ip'],
                                                        'message' => $email_msg
                                                        )
                                        );
    				
    		    
       // Only send to comm channels the user prefers, based off the config setting $ct['conf']['sec']['login_alert_channels']
       $preferred_comms = $ct['gen']->preferred_comms($ct['conf']['sec']['login_alert_channels'], $admin_login_send_params);
    			
       // Queue notifications
       @$ct['cache']->queue_notify($preferred_comms);
        
        
       }
   
   
   // Log errors, send notifications BEFORE reload
   $ct['cache']->app_log();
   $ct['cache']->send_notifications();
				
   header("Location: admin.php");
   exit;
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function ct_chmod($file, $chmod) {
   
   global $ct;
  
  
        if ( file_exists($file) && function_exists('posix_getpwuid') ) {
        $file_info = posix_getpwuid(fileowner($file));
        }
  
  
        // Does the current runtime user own this file (or will they own it after creating a non-existent file)?
        if ( file_exists($file) == false || isset($ct['current_runtime_user']) && isset($file_info['name']) && $ct['current_runtime_user'] == $file_info['name'] ) {
        $is_file_owner = 1;
        }
   
   
        if ( $is_file_owner == 1 && !$ct['http_runtime_user'] 
        || $is_file_owner == 1 && isset($ct['http_runtime_user']) && in_array($ct['http_runtime_user'], $ct['possible_http_users']) ) {
        // Continue, all is good
        }
        else {
        return false; // Not good, so we return false
        }
   
   
   $path_parts = pathinfo($file);
   
   $oldmask = umask(0);
        
   $did_chmod = chmod($file, $chmod);
       
       
          if ( !$did_chmod ) {
          	
          $ct['gen']->log(
          		'system_error',
          							
          		'Chmod failed for file "' . $file . '" (check permissions for the path "' . $path_parts['dirname'] . '", and the file "' . $path_parts['basename'] . '")',
          							
          		'chmod_setting: ' . $chmod . '; current_runtime_user: ' . $ct['current_runtime_user'] . '; file_owner: ' . $file_info['name'] . ';'
          		);
          
          }
          
       
   umask($oldmask);
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // RECURSIVELY USED VIA malware_scan_requests() (scans all subarray values too)
   function malware_scan_string($method, $ext_key, $data, $mysqli_connection=false) {
   
   global $ct;

        
        // INPUTS THAT ARE *SECURITY (NONCE) TOKENS* / HARD-CODE-SANITIZED ARE *ALREADY* HEAVILY CHECKED, SO WE CAN SAFELY EXCLUDE THEM,
        // AND WE MUST LEAVE ANYTHING THAT'S FLAGGED AS A CRYPTO ADDRESS ALONE TOO
        // (AS THEY CAN ***TRIGGER ATTACK SIGNATURE FALSE POSITIVES*** on code opening and closing tag symbols <>,
        // ***WHEN HASHES / DIGESTS ARE RUN THROUGH THE HEXIDECIMAL DECODER FURTHER DOWN IN THIS FUNCTION***)
        if (
        stristr($ext_key, 'nonce')
        || stristr($ext_key, 'crypto_address')
        || in_array($ext_key, $ct['dev']['skip_injection_scanner'])
        ) {
        return $data;
        }


   // WE SCAN *PLAINTEXT* NO MATTER IF IT'S POSSIBLE HEX / BASE64 ENCODING OR NOT, AS THOSE COULD BE FALSE POSITIVE(S)!
   $plaintext_attack_signature_count = $this->malware_scan($data);
   
   
   // We decode AND scan any possible / known encoding, that would obfuscate attack signatures on user-input data...
   // NOTE: Only *POSSIBLE* ENCODING can be checked for, as technically we can only check for a valid encoding FORMAT,
   // THEREFORE FALSE POSITIVES ARE UNAVIODABLE IN RANDOM HASHES, ETC... (SEE STRICT SECURITY EXCEPTIONS AT THE TOP OF THIS FUNCTION)
   
   
        // Scan for malicious content in any POSSIBLE hexadecimal encoding (IF plaintext scan revealed nothing)
        if ( $plaintext_attack_signature_count == 0 && $this->possible_hex_encoding( trim($data) ) ) {
        // ONLY TRIM *EN*CODED DATA (OTHERWISE WE RISK DELETING *DE*CODED DATA CONTAINING SPECIAL CHARACTERS!)
        $check_decoded_hex = hex2bin( trim($data) );
        $hex_attack_signature_count = $this->malware_scan($check_decoded_hex);
        }
        else {
        $hex_attack_signature_count = 0;
        }
        
        
        // Scan for malicious content in any POSSIBLE base64 encoding (IF plaintext / hexidecimal scans revealed nothing)
        if ( $plaintext_attack_signature_count == 0 && $hex_attack_signature_count == 0 && $this->possible_base64_encoding( trim($data) ) ) {
        // ONLY TRIM *EN*CODED DATA (OTHERWISE WE RISK DELETING *DE*CODED DATA CONTAINING SPECIAL CHARACTERS!)
        $check_decoded_base64 = base64_decode( trim($data) );
        $base64_attack_signature_count = $this->malware_scan($check_decoded_base64);
        }
        else {
        $base64_attack_signature_count = 0;
        }
        
        
        // Wipe data value and flag as possible attack, if scripting / HTML detected
        if (
        $plaintext_attack_signature_count > 0
        || $hex_attack_signature_count > 0
        || $base64_attack_signature_count > 0
        ) {
        $ct['gen']->log('security_error', 'POSSIBLE code injection attack blocked in (' . strtoupper($method) . ') request data "' . $ext_key . '" (from ' . $ct['remote_ip'] . '), please DO NOT inject ANY scripting / HTML into user inputs');
        $ct['possible_input_injection'] = true; // GLOBAL flag, to IMMEADIATELY HALT RUNTIME ON ANY UPCOMING SECURITY CHECKS!
        $data = 'possible_attack_blocked';
        }
        // mySQLi connection is required, for escaping special characters in a string before storing any data
        elseif ( $mysqli_connection ) {
        $data = mysqli_real_escape_string($mysqli_connection, $data);
        }
        else {
        $data = $data;
        }
        
        
   return $data;
        
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function base_url($hostCheck=false, $atRoot=false, $atCore=false, $parse=false) {
       
   global $ct;
      
   // WARNING: THIS ONLY WORKS WELL FOR HTTP-BASED RUNTIME, ----NOT CLI---!
   // CACHE IT TO FILE DURING UI RUNTIME FOR CLI TO USE LATER ;-)

        
        // Detect base URL
        if ( isset($_SERVER['HTTP_HOST']) ) {
                
          $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
          $hostname = $_SERVER['HTTP_HOST'];
          $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
       
          $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), null, PREG_SPLIT_NO_EMPTY);
          $core = $core[0];
       
          $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
          $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
          $set_url = sprintf( $tmplt, $http, $hostname, $end );
                   
        }
        else $set_url = 'https://localhost/';
          
       
        if ($parse) {
          	
        $set_url = parse_url($set_url);
          
              if (isset($set_url['path'])) if ($set_url['path'] == '/') $set_url['path'] = '';
                  
        }
        
        
   // Strip any URI component out
   // COVER ALL POSSIBLE PATHS FOR CORE ONLY (NOT PLUGINS DIR)
   $set_url = preg_replace("/\/app-lib\/php(.*)/i", "/", $set_url);
   $set_url = preg_replace("/\/templates\/interface(.*)/i", "/", $set_url);


        // Check detected base URL security in runtime-type-init.php DURING RE-CACHES
        // https://expressionengine.com/blog/http-host-and-server-name-security-issues (HOSTNAME HEADER CAN BE SPOOFED FROM CLIENT)
        if ( $hostCheck == true ) {
	
        $set_128bit_hash = $this->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
        $set_256bit_hash = $this->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
        
        $domain_check_filename = 'domain_check_' . $set_128bit_hash.'.dat';
        	
        	
        	  // Halt the process if an issue is detected safely creating a random hash
        	  if ( $set_128bit_hash == false || $set_256bit_hash == false ) {
        		
        	  $ct['gen']->log(
        				'security_error',
        				'Cryptographically secure pseudo-random bytes could not be generated for API key (in secured cache storage), API key creation aborted to preserve security'
        				);
        	
        	  return false;
        	
        	  }
        	  else {
        	       
        	  $ct['cache']->save_file($ct['base_dir'] . '/' . $domain_check_filename, $set_256bit_hash);
        	  
            sleep(5); // Sleep 5 seconds, to ASSURE COMPLETION of the FILE WRITE before the check afterwards
             	
            // domain check
            $domain_check_test_url = $set_url . $domain_check_filename;
             
            $domain_check_test = @$ct['cache']->ext_data('url', $domain_check_test_url, 0);
            
            sleep(5); // Sleep 5 seconds, to ASSURE COMPLETION of the CHECK before deleting afterwards
             
            // Delete domain check test file
            unlink($ct['base_dir'] . '/' . $domain_check_filename);
             
             	
             	  // If it's a possible hostname header attack
             	  if ( !preg_match("/" . $set_256bit_hash . "/i", $domain_check_test) ) {
             	  return array('security_error' => true, 'checked_url' => $domain_check_test_url, 'response_output' => $domain_check_test);
             	  }
        	
        
        	  }
        	  
        }
   
   
   return $set_url;  // Return if we made it this far
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function update_all_cookies($cookie_params) {
   
              
   	// Portfolio data
   	// Cookies expire in 1 year (31536000 seconds)
   
   	  foreach ( $cookie_params as $cookie_key => $cookie_val ) {
   	  $this->store_cookie($cookie_key, $cookie_val, time()+31536000);
   	  }
              
   
      // UI settings (not included in any portfolio data)
      if ( $_POST['submit_check'] == 1 ) {
               
                  
            if ( isset($_POST['show_crypto_val']) ) {
            $this->store_cookie("show_crypto_val", $_POST['show_crypto_val'], time()+31536000);
            }
            else {
            unset($_COOKIE['show_crypto_val']);
            $this->store_cookie('show_crypto_val', '', time()-3600); // Delete
            }
                  
            if ( isset($_POST['show_secondary_trade_val']) ) {
            $this->store_cookie("show_secondary_trade_val", $_POST['show_secondary_trade_val'], time()+31536000);
            }
            else {
            unset($_COOKIE['show_secondary_trade_val']);
            $this->store_cookie('show_secondary_trade_val', '', time()-3600); // Delete
            }
                 
            if ( isset($_POST['theme_selected']) ) {
            $this->store_cookie("theme_selected", $_POST['theme_selected'], time()+31536000);
            }
            else {
            unset($_COOKIE['theme_selected']);
            $this->store_cookie('theme_selected', '', time()-3600); // Delete
            }
                 
            if ( isset($_POST['use_alert_percent']) ) {
            $this->store_cookie("alert_percent", $_POST['use_alert_percent'], time()+31536000);
            }
            else {
            unset($_COOKIE['alert_percent']);
            $this->store_cookie('alert_percent', '', time()-3600); // Delete
            }
                 
            if ( isset($_POST['prim_currency_mrkt_standalone']) ) {
            $this->store_cookie("prim_currency_mrkt_standalone", $_POST['prim_currency_mrkt_standalone'], time()+31536000);
            }
            else {
            unset($_COOKIE['prim_currency_mrkt_standalone']);
            $this->store_cookie('prim_currency_mrkt_standalone', '', time()-3600); // Delete
            }
                 
            if ( isset($_POST['prim_currency_mrkt']) ) {
            $this->store_cookie("prim_currency_mrkt", $_POST['prim_currency_mrkt'], time()+31536000);
            }
            else {
            unset($_COOKIE['prim_currency_mrkt']);
            $this->store_cookie('prim_currency_mrkt', '', time()-3600); // Delete
            }
              
              
      }
              

   usleep(150000); // Wait 0.15 seconds, to give cookies a chance to save, before any redirect
    
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>