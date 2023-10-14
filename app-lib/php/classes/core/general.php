<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


class ct_gen {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;

var $ct_array = array();


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function titles_usort_alpha($a, $b) {
   return strcmp( strtolower($a["title"]) , strtolower($b["title"]) ); // Case-insensitive equivelent comparision via strtolower()
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function timestamps_usort_num($a, $b) {
   return strcmp($a['timestamp'], $b['timestamp']); 
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function test_ipv4($str) {
   return filter_var($str, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
   }
   
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function test_ipv6($str) {
   return filter_var($str, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function all_cookies_size() {
   $cookies = isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : null;
   return mb_strlen($cookies);
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function telegram_msg($msg, $chat_id) {
   
   // Using 3rd party Telegram class, initiated already as global var $telegram_messaging
   global $telegram_messaging;
   
      if ( $telegram_messaging ) {
      return $telegram_messaging->send->chat($chat_id)->text($msg)->send();
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function mob_number($str) {
   	
   global $ct;
   
   $str = explode("||",$str);
   
   return $ct['var']->strip_non_alpha($str[0]);
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function passed_medium_security_check() {
   
   global $admin_area_sec_level;
       
       if ( $admin_area_sec_level != 'medium' || $admin_area_sec_level == 'medium' && $this->pass_sec_check($_POST['medium_security_nonce'], 'medium_security_mode') && $this->valid_2fa('strict') ) {
       return true;
       }
       else {
       return false;
       }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function html_url($string) {
       
   $result = $string;
       
   $url = '%(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu';   
   
   $result = preg_replace($url, '<a href="$0" target="_blank" title="">$0</a>', $result);
   
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function ordinal($num) {
   
   $ends = array('th','st','nd','rd','th','th','th','th','th','th');
       
       if ( ( ($num % 100) >= 11 ) && ( ($num % 100) <= 13 ) ) {
       return $num. 'th';
       }
       else {
       return $num. $ends[$num % 10];
       }
       
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function dir_size($dir) {
   
   $size = 0;
   
      foreach ( glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each ) {
      $size += ( is_file($each) ? filesize($each) : $this->dir_size($each) );
      }
       
   return $size;
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function pass_sec_check($val, $hash_key) {
        
   global $possible_input_injection;
   
      if ( !$possible_input_injection && $this->admin_logged_in() && isset($val) && trim($val) != '' && isset($hash_key) && trim($hash_key) != '' && $this->admin_hashed_nonce($hash_key) != false && $val == $this->admin_hashed_nonce($hash_key) ) {
      return true;
      }
      else {
      return false;
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function regex_compat_path($path) {
      
   $regex_path = trim($path);
   $regex_path = preg_replace("/(http|https|ftp|tcp|ssl):\/\//i", "", $regex_path); // Internet protocols
   $regex_path = preg_replace("/[a-zA-Z]:\//i", "", $regex_path); // Windows drive paths (using forwardslashes instead [which PHP allows])
   $regex_path = preg_replace("/\//i", "\/", $regex_path);
   
   return $regex_path;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function split_text_msg($text, $char_length) {
   
   $chunks = explode("||||", wordwrap($msg, $char_length, "||||", false) );
   $total = count($chunks);
   
      foreach($chunks as $page => $chunk) {
      $msg = sprintf("(%d/%d) %s", $page+1, $total, $chunk);
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function hardy_sess_clear() {
   
   // This logic below helps assure session data is cleared
   session_unset();
   session_destroy();
   session_write_close();
   setcookie(session_name( $this->id() ),'',0,'/');
   session_regenerate_id(true);
   
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
   
   
   function del_all_files($dir) {
   
   $files = glob($dir . '/*'); // get all file names
   
      foreach($files as $file) { // iterate files
      
         if( is_file($file) ) {
         unlink($file); // delete file
         }
         
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function nonce_digest($data, $custom_nonce=false) {
      
      if ( isset($data) && $custom_nonce != false ) {
      return $this->digest( $data . $custom_nonce );
      }
      elseif ( isset($data) && isset($_SESSION['nonce']) ) {
      return $this->digest( $data . $_SESSION['nonce'] );
      }
      else {
      return false;
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   // To keep admin nonce key a secret, and make CSRF attacks harder with a different key per submission item
   function admin_hashed_nonce($key, $force=false) {
      
      // WE NEED A SEPERATE FUNCTION $this->nonce_digest(), SO WE DON'T #ENDLESSLY LOOP# FROM OUR
      // $this->admin_logged_in() CALL (WHICH ALSO USES $this->nonce_digest() INSTEAD OF $this->admin_hashed_nonce())
      if ( $this->admin_logged_in() || $force != false ) {
      return $this->nonce_digest($key);
      }
      else {
      return false;
      }
      
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function conv_bytes($bytes, $round) {
   
   $type = array("", "Kilo", "Mega", "Giga", "Tera", "Peta", "Exa", "Zetta", "Yotta");
   
     $index = 0;
     while( $bytes >= 1000 ) { // new standard (not 1024 anymore)
     $bytes/=1000; // new standard (not 1024 anymore)
     $index++;
     }
     
   return("".round($bytes, $round)." ".$type[$index]."bytes");
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function get_lines($file) {
   
   $f = fopen($file, 'rb');
   $lines = 0;
   
      while (!feof($f)) {
      $lines += substr_count(fread($f, 8192), "\n");
      }
   
   fclose($f); // Close file
   
   gc_collect_cycles(); // Clean memory cache
   
   return $lines;
   
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
   
   
   function valid_2fa($alt_mode=false, $force_check=false) {
   
   global $admin_area_2fa, $totp_auth, $auth_secret_2fa, $check_2fa_id, $check_2fa_error;
   
       // If 2FA is off, OR mode doesn't apply in this instance (AS LONG AS WE AREN'T *FORCE* CHECKING DURING 2FA SETUP)
       if ( !$force_check && $admin_area_2fa == 'off' || !$force_check && $alt_mode && $admin_area_2fa != $alt_mode || isset($_POST['2fa_code']) && $totp_auth->checkCode($auth_secret_2fa, $_POST['2fa_code']) ) {
       return true;
       }
       // Otherwise, alert end-user of the invalid 2FA code they entered
       else {
       $check_2fa_id = $_POST['2fa_code_id'];
       $check_2fa_error = '2FA passcode was invalid, please try again';
       return false;
       }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function create_csv($file, $save_as, $array) {
   
      if ( $file == 'temp' ) {
      $file = tempnam(sys_get_temp_dir(), 'temp');
      }
   
   $fp = fopen($file, 'w');
   
      foreach($array as $fields) {
      fputcsv($fp, $fields);
      }
   
   $this->file_download($file, $save_as); // Download file (by default deletes after download, then exits)
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function list_files($files_dir) {
      
   $scan_array = scandir($files_dir);
   $files = array();
     
     foreach($scan_array as $filename) {
       
       if ( is_file($files_dir.'/'.$filename) ) {
       $files[] = $filename;
       }
       
     }
   
   return $files;
     
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function text_email($str) {
   
   global $ct;
   
   $str = explode("||",$str);
   
   $phone_number = $ct['var']->strip_non_alpha($str[0]);
   
   $network_name = trim( strtolower($str[1]) ); // Force lowercase lookups for reliability / consistency
   
      // Set text domain
      if ( isset($phone_number) && trim($phone_number) != '' && isset($ct['conf']['mobile_network_text_gateways'][$network_name]) ) {
      return trim($phone_number) . '@' . trim($ct['conf']['mobile_network_text_gateways'][$network_name]); // Return formatted texting email address
      }
      else {
      return false;
      }
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function obfusc_url_data($url) {
      
   global $ct;
   
   // Keep our color-coded logs in the admin UI pretty, replace '://' with RIGHT parenthesis,
   // and put in LEFT parenthesis futher down when returning output
   $url = preg_replace("/:\/\//i", ") ", $url);
   
      foreach( $ct['dev']['url_obfuscating'] as $hide_key => $hide_val ) {
           
           if ( preg_match("/" . $hide_key . "/i", $url) ) {
           $url = str_replace($hide_val, $ct['var']->obfusc_str($hide_val, 2), $url);
           }
           
      }
   
   // Keep our color-coded logs in the admin UI pretty (SEE NOTES ABOVE)
   return '(' . $url;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function unicode_to_utf8($char, $format) {
      
       if ( $format == 'decimal' ) {
       $pre = '';
       }
       elseif ( $format == 'hexadecimal' ) {
       $pre = 'x';
       }
   
   $char = trim($char);
   $char = 'PREFIX' . $char;
   $char = preg_replace('/PREFIXx/', 'PREFIX', $char);
   $char = preg_replace('/PREFIXu/', 'PREFIX', $char);
   $char = preg_replace('/PREFIX/', '', $char);
   $char = '&#' . $pre . $char . ';';
   
   return html_entity_decode($char, ENT_COMPAT, 'UTF-8');
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function start_page($page, $href_link=false) {
   
      // We want to force a page reload for href links, so technically we change the URL but location remains the same
      if ( $href_link != false ) {
      $index = './';
      }
      else {
      $index = 'index.php';
      }
      
      if ( isset($page) && $page != '' ) {
      $url = $index . '?start_page=' . $page . '#' . $page;
      }
      else {
      $url = $index;
      }
      
   return $url;
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function admin_security_level_check() {
       
   global $default_ct_conf, $check_default_ct_conf, $admin_area_sec_level;
   
      if ( $admin_area_sec_level == 'high' ) {
      
         if ( $check_default_ct_conf == md5(serialize($default_ct_conf)) ) {
         return true;
         }
         else {
         return false;
         }
      
      }
      // Medium / Normal security modes
      else {
      return true;
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function timestamps_usort_newest($a, $b) {
      
      if ( isset($a->pubDate) && $a->pubDate != '' ) {
      $a = $a->pubDate;
      $b = $b->pubDate;
      }
      elseif ( isset($a->published) && $a->published != '' ) {
      $a = $a->published;
      $b = $b->published;
      }
      elseif ( isset($a->updated) && $a->updated != '' ) {
      $a = $a->updated;
      $b = $b->updated;
      }
   
   return strtotime($b) - strtotime($a);
      
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function upload_error($pointer) {
   
   global $ct;
   
   $errors = array(
                    0 => 'uploaded success',
                    1 => 'file exceeds upload_max_filesize',
                    2 => 'file exceeds MAX_FILE_SIZE',
                    3 => 'file partially uploaded',
                    4 => 'no file uploaded',
                    6 => 'no temporary folder',
                    7 => 'failed write to disk',
                    8 => 'PHP extension stopped upload',
                   );

   return $errors[$pointer] . ( $ct['app_platform'] == 'windows' ? ' [try running app as admin]' : '' );

   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function sanitize_requests($method, $ext_key, $data, $mysqli_connection=false) {
   
   
        if ( is_array($data) ) {
        
            foreach ( $data as $key => $val ) {
                
                if ( is_array($val) ) {
                $data[$key] = $this->sanitize_requests($method, $key, $val, false);
                }
                else {
                $data[$key] = $this->sanitize_string($method, $key, $val, false);
                }
            
            }
        
        }
        else {
        $data = $this->sanitize_string($method, $ext_key, $data, $mysqli_connection);
        }
   
   
   return $data;
        
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function rand_hash($num_bytes) {
   
      // Upgrade required
      if ( PHP_VERSION_ID < 70000 ) {
      	
      $this->log(
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
      $this->log('conf_error', 'auth_secret not set properly');
      return false;
      }
      else {
         
      $password_pepper_hashed = hash_hmac("sha256", $password, $auth_secret);
      
         if ( $password_pepper_hashed == false ) {
         $this->log('conf_error', 'hash_hmac() returned false in the ct_gen->pepper_hashed_pass() function');
         return false;
         }
         else {
         return password_hash($password_pepper_hashed, PASSWORD_DEFAULT);
         }
      
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function valid_email($email) {
   
   global $ct;
   
   // Trim whitespace off ends, since we do this before attempting to send anyways in our safe_mail function
   $email = trim($email);
   
   $email = strtolower($email);
   
   $address = explode("@",$email);
      
   $domain = $address[1];
      
      // Validate "To" address
      if ( !$email || !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/", $email) ) {
      return "please use a valid email address format";
      }
      elseif ( function_exists("getmxrr") && !getmxrr($domain, $mxrecords) ) {
      return "no mail server records found for domain '" . $ct['var']->obfusc_str($domain, 3) . "' [obfuscated]";
      }
      else {
      return "valid";
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function dir_struct($path) {
   
   global $ct, $possible_http_users, $http_runtime_user;

   
      // If path does not exist
      if ( !is_dir($path) ) {
      
         // Run cache compatibility on certain PHP setups
         if ( !$http_runtime_user || in_array($http_runtime_user, $possible_http_users) ) {
         $oldmask = umask(0);
         $result = mkdir($path, octdec($ct['dev']['chmod_cache_dir']), true); // Recursively create whatever path depth desired if non-existent
         umask($oldmask);
         return $result;
         }
         else {
         return  mkdir($path, octdec($ct['dev']['chmod_cache_dir']), true); // Recursively create whatever path depth desired if non-existent
         }
      
      }
      // If path is not writable, AND the chmod setting is not the app's default,
      // ATTEMPT TO CHMOD TO PROPER PERMISSIONS (IT'S OK IF IT DOESN'T WORK, WE'LL GET WRITE ERROR LOGS IF ANY REAL ISSUES EXIST)
      elseif ( !is_writable($path) && substr( sprintf( '%o' , fileperms($path) ) , -4 ) != $ct['dev']['chmod_cache_dir'] ) {
      $this->chmod_path($path, $ct['dev']['chmod_cache_dir']);
      }
      
      
   return true; // If we made it this far, we can safely return true
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function sort_files($files_dir, $extension, $sort) {
      
   $scan_array = scandir($files_dir);
   $files = array();
     
     
     foreach($scan_array as $filename) {
       
       if ( $extension == false ) {
       $mod_time = filemtime($files_dir.'/'.$filename);
       $files[$mod_time . '-' . $filename] = $filename;
       }
       elseif ( pathinfo($filename, PATHINFO_EXTENSION) == $extension ) {
       $mod_time = filemtime($files_dir.'/'.$filename);
       $files[$mod_time . '-' . $filename] = $filename;
       }
       
     }
   
   
     if ( $sort == 'asc' ) {
     ksort($files);
     }
     elseif ( $sort == 'desc' ) {
     krsort($files);
     }
   
   
   return $files;
     
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function input_2fa($alt_mode=false, $force_show=false) {
   
   global $admin_area_2fa, $check_2fa_error, $count_2fa_fields;
       
       if ( $force_show || !$alt_mode && $admin_area_2fa != 'off' || $alt_mode && $admin_area_2fa == $alt_mode ) {
	  ?>
	  
	  <div style='margin-top: 2em; margin-bottom: 2em;'>
	  
	  <p id='notice_2fa_code_<?=$count_2fa_fields?>' class='hidden red red_dotted' style='font-weight: bold;'><?=$check_2fa_error?>.</p>
	  
	  <p>
	  
	  <span class='<?=( $force_show != false ? 'red' : 'bitcoin' )?>' style='font-weight: bold;'>Enter 2FA Code (from phone app):</span><br />
	  
	  <input class='2fa_code_input' style='margin-top: 0.5em;' type='text' id='2fa_code_<?=$count_2fa_fields?>' name='2fa_code' value='' size='10' />
	  
	  </p>
	  
	  <input class='2fa_code_id_input' type='hidden' name='2fa_code_id' value='2fa_code_<?=$count_2fa_fields?>' />
	  
	  </div>
	  
	  <?php
	  $count_2fa_fields = $count_2fa_fields + 1;
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
         $path = str_replace($subpath_array[0], $ct['var']->obfusc_str($subpath_array[0], 1), $path);
         $path = str_replace($subpath_array[1], $ct['var']->obfusc_str($subpath_array[1], 5), $path);
         }
         // Files directly in /secured/
         else {
         $path = str_replace($subpath, $ct['var']->obfusc_str($subpath, 5), $path);
         }
            
      //$path = str_replace('cache/secured', $ct['var']->obfusc_str('cache', 0) . '/' . $ct['var']->obfusc_str('secured', 0), $path);
      
      }
      // Everything else, obfuscate just the filename OR deepest directory (last part of the path)
      elseif ( is_array($basepath_array) && sizeof($basepath_array) > 0 ) {
      $filename = sizeof($basepath_array) - 1;
      $path = str_replace($basepath_array[$filename], $ct['var']->obfusc_str($basepath_array[$filename], 5), $path);
      }
   
   
   return $path;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function detect_unicode($content) {
      
   global $ct;
   
   // Changs only if non-UTF-8 / non-ASCII characters are detected further down in this function
   $set_charset = $ct['dev']['charset_default'];
   
   $words = explode(" ", $content);
      
      
      foreach ( $words as $scan_key => $scan_val ) {
         
      $scan_val = trim($scan_val);
      $scan_charset = ( mb_detect_encoding($scan_val, 'auto') != false ? mb_detect_encoding($scan_val, 'auto') : null );
      
         if ( isset($scan_charset) && !preg_match("/" . $ct['dev']['charset_default'] . "/i", $scan_charset) && !preg_match("/ASCII/i", $scan_charset) ) {
         $set_charset = $ct['dev']['charset_unicode'];
         }
      
      }
      
   
   $result['charset'] = $set_charset;
   
   $result['content'] = $content; // We don't change anything on the content (just detect content's charset)
   
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function chmod_path($path, $perm) {
       
   global $change_dir_perm, $http_runtime_user;

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
         $change_dir_perm[] = $path . ':' . substr($chmod_val, 1);
         return false;
         }
     
    
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // Return the TLD only (no subdomain)
   function get_tld_or_ip($url) {
   
   global $ct;
   
   $urlData = parse_url($url);
      
      
      // If this is an ip address, then we can return that as the result now
      if ( $this->test_ipv4($urlData['host']) != false || $this->test_ipv6($urlData['host']) != false ) {
      return $urlData['host'];
      }
      
       
   // If this is a TLD or local alphanumeric name
   $array = explode(".", $urlData['host']);
      
      
      // Retrieve last 2 sections (the TLD), OR the local name
      if ( sizeof($array) >= 2 ) {
      return $array[( sizeof($array) - 2 )] . '.' . $array[( sizeof($array) - 1 )];
      }
      elseif ( sizeof($array) == 1 ) {
      return $array[0];
      }
      else {
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
      $this->log('conf_error', 'auth_secret not set properly');
      return false;
      }
      elseif ( !is_array($stored_admin_login) ) {
      $this->log('conf_error', 'No admin login set yet to check against');
      return false;
      }
      else {
         
      $input_password_pepper_hashed = hash_hmac("sha256", $input_password, $auth_secret);
      
         if ( $input_password_pepper_hashed == false ) {
         $this->log('conf_error', 'hash_hmac() returned false in the ct_gen->check_pepper_hashed_pass() function');
         return false;
         }
         else {
         return password_verify($input_password_pepper_hashed, $stored_hashed_password);
         }
         
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function system_stats_file($file, $delimiter, $subdelimiter) {
   
   $stats_info = @file_get_contents($file);
      
   $raw_stats_info_array = explode($delimiter, $stats_info);
   
      
         foreach ( $raw_stats_info_array as $stats_info_field ) {
         
            if ( isset($stats_info_field) && trim($stats_info_field) != '' ) {
               
            $temp_array = explode($subdelimiter, $stats_info_field);
            $temp_array_cleaned = array();
            
               $loop = 0;
               foreach ( $temp_array as $key => $val ) {
               $trimmed_val = ( $loop < 1 ? strtolower(trim($val)) : trim($val) );
               $trimmed_val = ( $loop < 1 ? preg_replace('/\s/', '_', $trimmed_val) : $trimmed_val );
               $trimmed_val = ( $loop < 1 ? preg_replace('/\s/', '_', $trimmed_val) : $trimmed_val );
               $temp_array_cleaned[strtolower($key)] = trim($trimmed_val,'\'"'); // Trim quotes
               $loop = $loop + 1;
               }
            
            $stats_info_array[ $temp_array_cleaned[0] ] = $temp_array_cleaned[1];
            }
         
         }
         
         
   return $stats_info_array;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function valid_username($username) {
   
   global $ct;
   
       if ( mb_strlen($username, $ct['dev']['charset_default']) < 4 ) {
       $error .= "requires 4 minimum characters; ";
       }
       
       if ( mb_strlen($username, $ct['dev']['charset_default']) > 30 ) {
       $error .= "requires 30 maximum characters; ";
       }
       
       if ( !preg_match("/^[a-z]([a-z0-9]+)$/", $username) ) {
       $error .= "lowercase letters and numbers only (lowercase letters first, then optionally numbers, no spaces); ";
       }
       
       if ( preg_match('/\s/',$username) ) {
       $error .= "no spaces allowed; ";
       }
   
   
       if( $error ){
       return 'valid_username_errors: ' . $error;
       }
       else {
       return 'valid';
       }
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function sort_log($log) {
       
      
      if ( isset($log) && $log != '' ) {
          
      $result = null;
      
      $sortable_array = array();
       
      $log_array = explode("[LOG]", $log);
       
          // Put logs in an array we can sort by timestamp
          foreach( $log_array as $entry ) {
              
              if ( stristr($entry, '[TIMESTAMP]') ) {
              $entry_array = explode("[TIMESTAMP]", $entry);
              $sortable_array[] = array('timestamp' => $entry_array[0], 'entry' => $entry_array[1]);
              }

          }
       
      // Sort by timestamp
      usort($sortable_array, array($this, 'timestamps_usort_num') );
       
          // Return to normal string, after sorting logs by timestamp
          foreach( $sortable_array as $val ) {
          $result .= $val['entry'];
          }
       
      return $result;
      }
      else {
      return false;
      }
      
   
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
   
   
   function ct_chmod($file, $chmod) {
   
   global $current_runtime_user, $http_runtime_user, $possible_http_users;
  
  
        if ( file_exists($file) && function_exists('posix_getpwuid') ) {
        $file_info = posix_getpwuid(fileowner($file));
        }
  
  
        // Does the current runtime user own this file (or will they own it after creating a non-existent file)?
        if ( file_exists($file) == false || isset($current_runtime_user) && isset($file_info['name']) && $current_runtime_user == $file_info['name'] ) {
        $is_file_owner = 1;
        }
   
   
        if ( $is_file_owner == 1 && !$http_runtime_user 
        || $is_file_owner == 1 && isset($http_runtime_user) && in_array($http_runtime_user, $possible_http_users) ) {
        // Continue, all is good
        }
        else {
        return false; // Not good, so we return false
        }
   
   
   $path_parts = pathinfo($file);
   
   $oldmask = umask(0);
        
   $did_chmod = chmod($file, $chmod);
       
       
          if ( !$did_chmod ) {
          	
          $this->log(
          		'system_error',
          							
          		'Chmod failed for file "' . $file . '" (check permissions for the path "' . $path_parts['dirname'] . '", and the file "' . $path_parts['basename'] . '")',
          							
          		'chmod_setting: ' . $chmod . '; current_runtime_user: ' . $current_runtime_user . '; file_owner: ' . $file_info['name'] . ';'
          		);
          
          }
          
       
   umask($oldmask);
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function smtp_mail($to, $subj, $msg, $content_type='text/plain', $charset=null) {
   
   // Using 3rd party SMTP class, initiated already as global var $smtp
   global $ct, $smtp;
   
      if ( $charset == null ) {
      $charset = $ct['dev']['charset_default'];
      }
      
      
      // Fallback, if no From email set in app config
      if ( $this->valid_email($ct['conf']['comms']['from_email']) == 'valid' ) {
      $from_email = $ct['conf']['comms']['from_email'];
      }
      else {
      $temp_data = explode("||", $ct['conf']['comms']['smtp_login']);
      $from_email = $temp_data[0];
      }
   
   
      if ( $smtp ) {
      
       $smtp->From($from_email); 
       $smtp->singleTo($to); 
       $smtp->Subject($subj);
       $smtp->Charset($charset);
       
       
          if ( $content_type == 'text/plain' ) {
          $smtp->Text($msg);
          $smtp->Body(null);
          }
          elseif ( $content_type == 'text/html' ) {
          $smtp->Body($msg);
          $smtp->Text(null);
          }
       
       
       return $smtp->Send();
      
      }
      
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // Pretty decimals calculation (ONLY returns num of decimals to use)
   // (NO DECIMALS OVER 100 IN UNIT VALUE, MAX 2 DECIMALS OVER 1, #AND MIN 2 DECIMALS# UNDER, FOR INTERFACE UX)
   function thres_dec($num, $mode, $type=false) {
       
   global $ct, $min_fiat_val_test, $min_crypto_val_test;
   
   $result = array();
   
   $num = $ct['var']->num_to_str($num);
   
      // Unit
      if ( $mode == 'u' && $type != false ) {
          
      $result['max_dec'] = $this->dyn_max_decimals( abs($num) , $type); // MUST BE PASSED AS ABSOLUTE
      
      $min_val = ( $type == 'fiat' ? $min_fiat_val_test : $min_crypto_val_test );
   
          if ( $num < $min_val ) {
          $result['min_dec'] = 0;
          }
          elseif ( $ct['conf']['gen']['price_round_fixed_decimals'] == 'on' ) {
          $result['min_dec'] = $result['max_dec'];
          }
   		elseif ( $type == 'fiat' ) {
          $result['min_dec'] = 2;
          }
          else {
          $result['min_dec'] = 0;
          }
          
      }
      // Percent 
      elseif ( $mode == 'p' ) {
          
          if ( abs($num) >= 100 ) {
          $result['max_dec'] = 0;
          $result['min_dec'] = 0;
          }
		  else {
          $result['max_dec'] = 2;
          $result['min_dec'] = 2;
          }
      
      }
      
   
   return $result;
      
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function delete_all_cookies() {
     
    // Portfolio
   unset($_COOKIE['coin_amnts']);
   unset($_COOKIE['coin_pairs']);
   unset($_COOKIE['coin_mrkts']);
   unset($_COOKIE['coin_paid']);
   unset($_COOKIE['coin_lvrg']);
   unset($_COOKIE['coin_mrgntyp']);
   
   $this->store_cookie('coin_amnts', '', time()-3600); // Delete
   $this->store_cookie('coin_pairs', '', time()-3600); // Delete
   $this->store_cookie('coin_mrkts', '', time()-3600); // Delete
   $this->store_cookie('coin_paid', '', time()-3600); // Delete
   $this->store_cookie('coin_lvrg', '', time()-3600); // Delete
   $this->store_cookie('coin_mrgntyp', '', time()-3600); // Delete
     
     
   // Settings
   unset($_COOKIE['coin_reload']);
   unset($_COOKIE['show_charts']);
   unset($_COOKIE['show_crypto_val']);
   unset($_COOKIE['show_secondary_trade_val']);
   unset($_COOKIE['show_feeds']);
   unset($_COOKIE['theme_selected']);
   unset($_COOKIE['sort_by']);
   unset($_COOKIE['alert_percent']);
   unset($_COOKIE['prim_currency_mrkt_standalone']);
   unset($_COOKIE['prim_currency_mrkt']);
   
   $this->store_cookie('coin_reload', '', time()-3600); // Delete
   $this->store_cookie('show_charts', '', time()-3600); // Delete
   $this->store_cookie('show_crypto_val', '', time()-3600); // Delete
   $this->store_cookie('show_secondary_trade_val', '', time()-3600); // Delete
   $this->store_cookie('show_feeds', '', time()-3600); // Delete
   $this->store_cookie('theme_selected', '', time()-3600); // Delete
   $this->store_cookie('sort_by', '', time()-3600); // Delete
   $this->store_cookie('alert_percent', '', time()-3600); // Delete
   $this->store_cookie('prim_currency_mrkt_standalone', '', time()-3600); // Delete
   $this->store_cookie('prim_currency_mrkt', '', time()-3600); // Delete
    
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
      	
      $this->log(
      		'other_error',
      		'Cookie size is greater than 4093 bytes (' . strlen($val) . ' bytes). If saving portfolio as cookie data fails on your browser, try using CSV file import / export instead for large portfolios.'
      		);
      
      }
      
      if ( $result == false ) {
      $this->log('system_error', 'Cookie modification / creation failed for cookie "' . $name . '"');
      }
      
      
   return $result;
   
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
      $this->log('system_error', 'Cookie modification / creation failed for cookie "' . $name . '"');
      }
      
      
   return $result;
   
   }
    
    
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
    
   function utf8_to_unicode($char, $format) {
      
       if (ord($char[0]) >=0 && ord($char[0]) <= 127)
           $result = ord($char[0]);
           
       if (ord($char[0]) >= 192 && ord($char[0]) <= 223)
           $result = (ord($char[0])-192)*64 + (ord($char[1])-128);
           
       if (ord($char[0]) >= 224 && ord($char[0]) <= 239)
           $result = (ord($char[0])-224)*4096 + (ord($char[1])-128)*64 + (ord($char[2])-128);
           
       if (ord($char[0]) >= 240 && ord($char[0]) <= 247)
           $result = (ord($char[0])-240)*262144 + (ord($char[1])-128)*4096 + (ord($char[2])-128)*64 + (ord($char[3])-128);
           
       if (ord($char[0]) >= 248 && ord($char[0]) <= 251)
           $result = (ord($char[0])-248)*16777216 + (ord($char[1])-128)*262144 + (ord($char[2])-128)*4096 + (ord($char[3])-128)*64 + (ord($char[4])-128);
           
       if (ord($char[0]) >= 252 && ord($char[0]) <= 253)
           $result = (ord($char[0])-252)*1073741824 + (ord($char[1])-128)*16777216 + (ord($char[2])-128)*262144 + (ord($char[3])-128)*4096 + (ord($char[4])-128)*64 + (ord($char[5])-128);
           
       if (ord($char[0]) >= 254 && ord($char[0]) <= 255)    //  error
           $result = false;
           
           
       if ( $format == 'decimal' ) {
       $result = $result;
       }
       elseif ( $format == 'hexadecimal' ) {
       $result = 'x'.dechex($result);
       }
       

   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function time_date_format($offset=false, $mode=false) {
   
   
      if ( $offset == false ) {
      $time = time();
      }
      else {
      $time = time() + round( $offset * (60 * 60) );  // Offset is in hours (ROUNDED, so it can be decimals)
      }
   
   
      if ( $mode == false ) {
      $date = date("Y-m-d H:i:s", $time); // Format: 2001-03-10 17:16:18 (the MySQL DATETIME format)
      }
      elseif ( $mode == 'standard_date' ) {
      $date = date("Y-m-d", $time); // Format: 2001-03-10
      }
      elseif ( $mode == 'standard_time' ) {
      $date = date("H:i", $time); // Format: 22:45
      }
      elseif ( $mode == 'pretty_date_time' ) {
      $date = date("F jS, @ g:ia", $time); // Format: March 10th, @ 5:16pm
      }
      elseif ( $mode == 'pretty_date' ) {
      $date = date("F jS", $time); // Format: March 10th
      }
      elseif ( $mode == 'pretty_time' ) {
      $date = date("g:ia", $time); // Format: 5:16pm
      }
   
   
   // 'at' is a stubborn word to escape into the date() function, so we cheated a little
   $date = preg_replace("/@/", "at", $date); 
   
   return $date;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function sanitize_string($method, $ext_key, $data, $mysqli_connection=false) {
   
   global $ct, $possible_input_injection;
   
   $original = $data;
   
   // Remove HTML
   $sanitized = strip_tags($original);
        
   // Scan for malicious content
   $scan = strtolower($sanitized);
                           
   $scan = str_replace($ct['dev']['script_injection_checks'], "", $scan, $has_script);
        
        
        // Wipe data value, if scripting / HTML detection flagged
        if ( $has_script > 0 || $original != $sanitized ) {
        $this->log('security_error', 'POSSIBLE code injection attack blocked in request data (' . strtoupper($method) . ') "' . $ext_key . '" (from ' . $ct['remote_ip'] . '), please DO NOT attempt to inject scripting / HTML into user inputs');
        $data = 'possible_scripting_blocked';
        $possible_input_injection = true;
        }
        // a mySQLi connection is required before using this function
        // Escapes special characters in a string for use in an SQL statement
        elseif ( $mysqli_connection ) {
        $data = mysqli_real_escape_string($mysqli_connection, $sanitized);
        }
        else {
        $data = $sanitized;
        }
        
        
   return $data;
        
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function file_download($file, $save_as, $delete=true) {
      
   global $ct;
   
   $type = pathinfo($save_as, PATHINFO_EXTENSION);
   
      if ( $type == 'csv' ) {
      $content_type = 'Content-type: text/csv; charset=' . $ct['dev']['charset_default'];
      }
      else {
      $content_type = 'Content-type: application/octet-stream';
      }
   
   
      if ( file_exists($file) ) {
         
         header('Content-description: file transfer');
         header($content_type);
         header('Content-disposition: attachment; filename="'.basename($save_as).'"');
         header('Expires: 0');
         header('Cache-control: must-revalidate');
         header('Pragma: public');
         header('Content-length: ' . filesize($file));
         
         $result = readfile($file);
         
            if ( $result != false && $delete == true ) {
            unlink($file); // Delete file
            }
         
         exit;
         
      }
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function csv_import_array($file) {
   
   global $ct;
   
      
      $row = 0;
      if ( ( $handle = fopen($file, "r") ) != false ) {
         
         while ( ( $data = fgetcsv($handle, 0, ",") ) != false ) {
            
         $num = count($data);
         $asset = strtoupper($data[0]);
         
            // ONLY importing if it exists in $ct['conf']['assets']
            if ( is_array($ct['conf']['assets'][$asset]) ) {
         
               for ($c=0; $c < $num; $c++) {
               $check_csv_rows[$asset][] = $data[$c];
               }
               
               // Validate / auto-correct the import data
               $validated_csv_import_row = $this->valid_csv_import_row($check_csv_rows[$asset]);
               
               if ( $validated_csv_import_row ) {
               $csv_rows[$asset] = $validated_csv_import_row;
               }
            
            }
            
         $row++;
            
         }
         fclose($handle);
   
         gc_collect_cycles(); // Clean memory cache
         
      }
   
   
   unlink($file); // Delete temp file
   
   return $csv_rows;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function in_megabytes($str) {
   
   $str_val = preg_replace("/ (.*)/i", "", $str);
   
      // Always in megabytes
      if ( preg_match("/kilo/i", $str) || preg_match("/kb/i", $str) ) {
      $in_megs = $str_val * 0.001;
      $type = 'Kilobytes';
      }
      elseif ( preg_match("/mega/i", $str) || preg_match("/mb/i", $str) ) {
      $in_megs = $str_val * 1;
      $type = 'Megabytes';
      }
      elseif ( preg_match("/giga/i", $str) || preg_match("/gb/i", $str) ) {
      $in_megs = $str_val * 1000;
      $type = 'Gigabytes';
      }
      elseif ( preg_match("/tera/i", $str) || preg_match("/tb/i", $str) ) {
      $in_megs = $str_val * 1000000;
      $type = 'Terabytes';
      }
      elseif ( preg_match("/peta/i", $str) || preg_match("/pb/i", $str) ) {
      $in_megs = $str_val * 1000000000;
      $type = 'Petabytes';
      }
      elseif ( preg_match("/exa/i", $str) || preg_match("/eb/i", $str) ) {
      $in_megs = $str_val * 1000000000000;
      $type = 'Exabytes';
      }
      elseif ( preg_match("/zetta/i", $str) || preg_match("/zb/i", $str) ) {
      $in_megs = $str_val * 1000000000000000;
      $type = 'Zettabytes';
      }
      elseif ( preg_match("/yotta/i", $str) || preg_match("/yb/i", $str) ) {
      $in_megs = $str_val * 1000000000000000000;
      $type = 'Yottabytes';
      }
   
   $result['num_val'] = $str_val;
   $result['type'] = $type;
   $result['in_megs'] = round($in_megs, 3);
   
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   /* Usage: 
   
   // HTML
   $content = $this->txt_between_tags('a', $html);
   
   foreach( $content as $item ) {
       echo $item.'<br />';
   }
   
   // XML
   $content2 = $this->txt_between_tags('description', $xml, 1);
   
   foreach( $content2 as $item ) {
       echo $item.'<br />';
   }
   
   */
   
   // Credit: https://phpro.org/examples/Get-Text-Between-Tags.html
   function txt_between_tags($tag, $html, $strict=0) {
   	
       /*** a new dom object ***/
       $dom = new domDocument;
   
       /*** load the html into the object ***/
       if($strict==1) {
       $dom->loadXML($html);
       }
       else {
       $dom->loadHTML($html);
       }
   
       /*** discard white space ***/
       $dom->preserveWhiteSpace = false;
   
       /*** the tag by its tag name ***/
       $content = $dom->getElementsByTagname($tag);
   
       /*** the array to return ***/
       $out = array();
       foreach ($content as $item) {
           /*** add node value to the out array ***/
           $out[] = $item->nodeValue;
       }
   
       
   /*** return the results ***/
   return $out;
       
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function smtp_vars() {
   
   // To preserve SMTPMailer class upgrade structure, by creating a global var to be run in classes/smtp-mailer/conf/config_smtp.php
   
   global $ct;
   
   $vars = array();
   
   $log_file = $ct['base_dir'] . "/cache/logs/smtp_error.log";
   $log_file_debug = $ct['base_dir'] . "/cache/logs/smtp_debug.log";
   
   // Don't overwrite globals
   $temp_smtp_email_login = explode("||", $ct['conf']['comms']['smtp_login'] );
   $temp_smtp_email_server = explode(":", $ct['conf']['comms']['smtp_server'] );
   
   // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
   $smtp_user = trim($temp_smtp_email_login[0]);
   $smtp_password = $temp_smtp_email_login[1];
   
   $smtp_host = trim($temp_smtp_email_server[0]);
   $smtp_port = trim($temp_smtp_email_server[1]);
   
   
      // Set encryption type based on port number
      if ( $smtp_port == 25 ) {
      $smtp_secure = 'off';
      }
      elseif ( $smtp_port == 465 ) {
      $smtp_secure = 'ssl';
      }
      elseif ( $smtp_port == 587 ) {
      $smtp_secure = 'tls';
      }
   
   
   // Port vars over to class format (so it runs out-of-the-box as much as possible)
   $vars['cfg_log_file']   = $log_file;
   $vars['cfg_log_file_debug']   = $log_file_debug;
   $vars['cfg_server']   = $smtp_host;
   $vars['cfg_port']     =  $smtp_port;
   $vars['cfg_secure']   = $smtp_secure;
   $vars['cfg_username'] = $smtp_user;
   $vars['cfg_password'] = $smtp_password;
   $vars['cfg_debug_mode'] = $ct['conf']['power']['debug_mode']; // Open Crypto Tracker debug mode setting
   $vars['cfg_strict_ssl'] = $ct['conf']['sec']['smtp_strict_ssl']; // Open Crypto Tracker strict SSL setting
   $vars['cfg_app_version'] = $ct['app_version']; // Open Crypto Tracker version
   
   return $vars;
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function preferred_comms($preferred_comms, $available_params) {
   
   $chosen_params = array();
   
   
    	// Message parameters added to $chosen_params for preferred comm methods
    	if ( $preferred_comms == 'all' ) {
    		
    	$chosen_params = array(
                                'notifyme' => $available_params['notifyme'],
                                'telegram' => $available_params['telegram'],
                                'text' => array(
                                               'message' => $available_params['text']['message'],
                                               'charset' => $available_params['text']['charset']
                                               ),
                                'email' => array(
                                                'subject' => $available_params['email']['subject'],
                                                'message' => $available_params['email']['message']
                                                )
                                );
    	
    	}
    	elseif ( $preferred_comms == 'email' ) {
    		
    	$chosen_params['email'] = array(
            			'subject' => $available_params['email']['subject'],
            			'message' => $available_params['email']['message']
            			);
    	
    	}
    	elseif ( $preferred_comms == 'text' ) {
    	
    	$chosen_params['text'] = array(
    			       'message' => $available_params['text']['message'],
    			       'charset' => $available_params['text']['charset']
    			       );
    	
    	}
    	elseif ( $preferred_comms == 'notifyme' ) {
    	$chosen_params['notifyme'] = $available_params['notifyme'];
    	}
    	elseif ( $preferred_comms == 'telegram' ) {
    	$chosen_params['telegram'] = $available_params['telegram'];
    	}					


   return $chosen_params;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function start_page_html($page) {
      
      if ( isset($_GET['start_page']) && $_GET['start_page'] != '' ) {
      $border_highlight = '_red';
      $text_class = 'red';
      }
      
   ?>
   <span class='start_page_menu<?=$border_highlight?>'> 
      
      <select class='browser-default custom-select' title='Sets alternate start pages, and saves your scroll position on alternate start pages during reloads.' class='<?=$text_class?>' onchange='
      
         if ( this.value == "index.php?start_page=<?=$page?>" ) {
         var anchor = "#<?=$page?>";
         }
         else {
         var anchor = "";
         //localStorage.setItem(scroll_position_storage, 0); // May be bad UX, with new nav system, disabled for now
         }
      
      // This start page method saves portfolio data during the session, even without cookie data enabled
      var set_action = this.value + anchor;
      set_target_action("coin_amnts", "_self", set_action);
      $("#coin_amnts").submit();
      
      '>
         <option value='index.php'> Show First: Last-Visited </option>
         <?php
         if ( isset($_GET['start_page']) && $_GET['start_page'] != '' && $_GET['start_page'] != $page ) {
         $another_set = 1;
         ?>
         <option value='index.php?start_page=<?=$_GET['start_page']?>' selected > Show First: <?=ucwords( preg_replace("/_/i", " ", $_GET['start_page']) )?> </option>
         <?php
         }
         ?>
         <option value='index.php?start_page=<?=$page?>' <?=( $_GET['start_page'] == $page ? 'selected' : '' )?> > Show First: <?=ucwords( preg_replace("/_/i", " ", $page) )?> </option>
      </select> 
      
   </span>
   
      <?php
      if ( $another_set == 1 ) {
      ?>
      <span class='red'>&nbsp;(other shows first)</span>
      <?php
      }
      elseif ( $_GET['start_page'] == $page ) {
      ?>
      <span class='red'>&nbsp;(current shows first)</span>
      <?php
      }
      
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function system_warning_log($type) {
   
   global $ct, $system_warnings, $system_warnings_cron_interval;
   
   
	  // With offset, to try keeping daily / hourly recurrences at same exact runtime (instead of moving up the runtime daily / hourly)
      if ( $ct['cache']->update_cache($ct['base_dir'] . '/cache/events/system/warning-' . $type . '.dat', ($system_warnings_cron_interval[$type] * 60) + $ct['dev']['tasks_time_offset'] ) == true ) {
          
      $this->log('system_warning', $system_warnings[$type]);
      
          if ( isset($ct['system_info']['distro_name']) ) {
          $system_info_summary = "\n\nApp Server System Info: " . $ct['system_info']['distro_name'] . ( isset($ct['system_info']['distro_version']) ? ' ' . $ct['system_info']['distro_version'] : '' );
          }
      
      $email_msg = 'Open Crypto Tracker detected an app server issue: ' . $system_warnings[$type] . '. (warning thresholds are adjustable in the Admin Config Power User section) ' . $system_info_summary;
               
      // Were're just adding a human-readable timestamp to smart home (audio) alerts
      $notifyme_msg = $email_msg . ' Timestamp: ' . $this->time_date_format($ct['conf']['gen']['local_time_offset'], 'pretty_time') . '.';
      
      $text_msg = 'Open Crypto Tracker app server issue: ' . $system_warnings[$type] . '.';
               
      // Minimize function calls
      $text_msg = $this->detect_unicode($text_msg); 
      
                    
      // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
                        
      $send_params = array(
                  
                           'notifyme' => $notifyme_msg,
                                    
                           'telegram' => $email_msg,
                                    
                           'text' => array(
                                           'message' => $text_msg['content'],
                                           'charset' => $text_msg['charset']
                                           ),
                                                    
                           'email' => array(
                                            'subject' => 'App Server Issue (' . preg_replace("/_/", " ", $type) . ')',
                                            'message' => $email_msg
                                            )
                                                       
                             );
                    
                    
      // Send notifications
      @$ct['cache']->queue_notify($send_params);
                        
      
      $ct['cache']->save_file($ct['base_dir'] . '/cache/events/system/warning-' . $type . '.dat', $this->time_date_format(false, 'pretty_date_time') );
      
      }
   
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function refresh_plugins_list() {
        
   global $ct, $default_ct_conf, $admin_area_sec_level, $reset_config, $update_config;
   
   $plugin_base = $ct['base_dir'] . '/plugins/';
   
   $dir = new DirectoryIterator($plugin_base);
   
      
      // Add any plugins not already in plugin list (default to off)
      foreach ($dir as $file_info) {
           
         if ( $file_info->isDir() && !$file_info->isDot() ) {
              
             if (
             file_exists($plugin_base . $file_info->getFilename() . '/plug-conf.php')
             && file_exists($plugin_base . $file_info->getFilename() . '/plug-lib/plug-init.php')
             ) {
                  
               
               // We also want to set any unset DEFAULT config (for existing plugins ONLY [no need to unset deleted further down])
               if ( !isset($default_ct_conf['conf']['plugins']['plugin_status'][ $file_info->getFilename() ]) ) {
               $default_ct_conf['conf']['plugins']['plugin_status'][ $file_info->getFilename() ] = 'off'; // Defaults to off
               }
               
             
               if ( !isset($ct['conf']['plugins']['plugin_status'][ $file_info->getFilename() ]) ) {
                    
               $ct['conf']['plugins']['plugin_status'][ $file_info->getFilename() ] = 'off'; // Defaults to off
	    
	               // If no reset / high security mode 
	               // (high security mode will auto-trigger a reset on plugin changes, FURTHER ALONG IN THE LOGIC)
     	          if ( $admin_area_sec_level != 'high' && !$reset_config ) {
     	               
         	          $update_config = true;
     	               
     	               if ( $ct['conf']['power']['debug_mode'] == 'all' || $ct['conf']['power']['debug_mode'] == 'all_telemetry' || $ct['conf']['power']['debug_mode'] == 'conf_telemetry' ) {
         	               $ct['gen']->log('conf_debug', 'plugin "'.$file_info->getFilename().'" ADDED, updating CACHED ct_conf');
         	               }
         	          
     	          }
               
               }
               
             
             }
             
         }
         
      }
      
      
      // Remove any plugins that no longer exist / do not have proper file structure
      foreach ( $ct['conf']['plugins']['plugin_status'] as $key => $unused ) {
           
           
         if (
         !file_exists($plugin_base . $key . '/plug-conf.php')
         || !file_exists($plugin_base . $key . '/plug-lib/plug-init.php')
         ) {
              
         unset($ct['conf']['plugins']['plugin_status'][$key]);
	    unset($ct['conf']['plug_conf'][$key]);
	    
	         // If no reset / high security mode 
	         // (high security mode will auto-trigger a reset on plugin changes, FURTHER ALONG IN THE LOGIC)
	         if ( $admin_area_sec_level != 'high' && !$reset_config ) {
	              
    	         $update_config = true;
    	         
    	            if ( $ct['conf']['power']['debug_mode'] == 'all' || $ct['conf']['power']['debug_mode'] == 'all_telemetry' || $ct['conf']['power']['debug_mode'] == 'conf_telemetry' ) {
    	            $ct['gen']->log('conf_debug', 'plugin "'.$key.'" REMOVED, updating CACHED ct_conf');
    	            }
    	         
	         }
	    
         }
         
      
      }
   
   
   }
    
    
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function valid_csv_import_row($csv_row) {
      
   global $ct, $min_crypto_val_test;
   
   // WE AUTO-CORRECT AS MUCH AS IS FEASIBLE, IF THE USER-INPUT IS CORRUPT / INVALID
   
   $csv_row = array_map('trim', $csv_row); // Trim entire array
      
   $csv_row[0] = strtoupper($csv_row[0]); // Asset to uppercase (we already validate it's existance in $this->csv_import_array())
          
   $csv_row[1] = $ct['var']->rem_num_format($csv_row[1]); // Remove any number formatting in held amount
   
   // Remove any number formatting in paid amount, default paid amount to null if not a valid positive number
   $csv_row[2] = ( $ct['var']->rem_num_format($csv_row[2]) >= 0 ? $ct['var']->rem_num_format($csv_row[2]) : null ); 
      
   // If leverage amount input is corrupt, default to 0 (ALSO simple auto-correct if negative)
   $csv_row[3] = ( $ct['var']->whole_int($csv_row[3]) != false && $csv_row[3] >= 0 ? $csv_row[3] : 0 ); 
      
   // If leverage is ABOVE 'margin_leverage_maximum', default to 'margin_leverage_maximum'
   $csv_row[3] = ( $csv_row[3] <= $ct['conf']['power']['margin_leverage_maximum'] ? $csv_row[3] : $ct['conf']['power']['margin_leverage_maximum'] ); 
   
   // Default to 'long', if not 'short' (set to lowercase...simple auto-correct, if set to anything other than 'short')
   $csv_row[4] = ( strtolower($csv_row[4]) == 'short' ? 'short' : 'long' );
   
   // If market ID input is corrupt, default to 1 (it's ALWAYS 1 OR GREATER)
   $csv_row[5] = ( $ct['var']->whole_int($csv_row[5]) != false && $csv_row[5] >= 1 ? $csv_row[5] : 1 ); 
      
   $csv_row[6] = strtolower($csv_row[6]); // Pair to lowercase
      
      
      // Pair auto-correction (if invalid pair)
      if ( $csv_row[6] == '' || !is_array($ct['conf']['assets'][ $csv_row[0] ]['pair'][ $csv_row[6] ]) ) {
         
      $csv_row[5] = 1; // We need to reset the market id to 1 (it's ALWAYS 1 OR GREATER), as the pair was not found
      
      // First key in $ct['conf']['assets'][ $csv_row[0] ]['pair']
      reset($ct['conf']['assets'][ $csv_row[0] ]['pair']);
      $csv_row[6] = key($ct['conf']['assets'][ $csv_row[0] ]['pair']);
      
      }
      // Market ID auto-correction (if invalid market ID)
      elseif ( is_array($ct['conf']['assets'][ $csv_row[0] ]['pair'][ $csv_row[6] ]) && sizeof($ct['conf']['assets'][ $csv_row[0] ]['pair'][ $csv_row[6] ]) < $csv_row[5] ) {
      $csv_row[5] = 1; // We need to reset the market id to 1 (it's ALWAYS 1 OR GREATER), as the ID was higher than available markets count
      }
      
      
      // Return false if there is no valid held amount
      if ( $csv_row[1] >= $min_crypto_val_test )  {
      return $csv_row;
      }
      else {
      return false;
      }
      
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   

   function dyn_max_decimals($price_raw, $type) {
       
   global $ct, $min_fiat_val_test, $min_crypto_val_test;
   
   $price_raw = abs($price_raw); // Assure no negative number used
   
        
        if ( $ct['conf']['gen']['price_round_percent'] == 'one' ) {
        $x = 1;
        }
        else if ( $ct['conf']['gen']['price_round_percent'] == 'tenth' ) {
        $x = 0.1;
        }
        else if ( $ct['conf']['gen']['price_round_percent'] == 'hundredth' ) {
        $x = 0.01;
        }
        else if ( $ct['conf']['gen']['price_round_percent'] == 'thousandth' ) {
        $x = 0.001;
        }
        
        
    $unit_percent = $ct['var']->num_to_str( ($price_raw / 100) * $x );
        
    
        if ( $type == 'fiat' ) {
             
        $track_target = preg_replace("/1/", "5", $min_fiat_val_test); // Set to 0.XXXXX5 instead of 0.XXXXX1
        
        
             $loop = 0;
             $track_decimals = $ct['conf']['gen']['currency_decimals_max'];
             while ( !isset($decimals) && $loop < $ct['conf']['gen']['currency_decimals_max'] ) {

                  // $track_decimals decimals rounding
                  if ( !isset($decimals) && $unit_percent <= $track_target ) {
                  $decimals = $track_decimals;
                  }
                  // 0 decimals rounding
                  elseif ( !isset($decimals) && $unit_percent > 0.5 ) {
                  $decimals = 0;
                  }
                  // Remove one decimal for any next try
                  else {
                  $track_target = $ct['var']->num_to_str($track_target * 10);
                  $track_decimals = $track_decimals - 1; 
                  }
        
             $loop = $loop + 1;

             }
             unset($loop);
             
             
             // Force to max decimals if applicable
             if ( $decimals > $ct['conf']['gen']['currency_decimals_max'] ) {
             return $ct['conf']['gen']['currency_decimals_max'];
             }
             else {
             return $decimals;
             }
        
        
        }
        else if ( $type == 'crypto' ) {
             
        $track_target = preg_replace("/1/", "5", $min_crypto_val_test); // Set to 0.XXXXX5 instead of 0.XXXXX1
        
        
             $loop = 0;
             $track_decimals = $ct['conf']['gen']['crypto_decimals_max'];
             while ( !isset($decimals) && $loop < $ct['conf']['gen']['crypto_decimals_max'] ) {

                  // $track_decimals decimals rounding
                  if ( !isset($decimals) && $unit_percent <= $track_target ) {
                  $decimals = $track_decimals;
                  }
                  // 0 decimals rounding
                  elseif ( !isset($decimals) && $unit_percent > 0.5 ) {
                  $decimals = 0;
                  }
                  // Remove one decimal for any next try
                  else {
                  $track_target = $ct['var']->num_to_str($track_target * 10);
                  $track_decimals = $track_decimals - 1; 
                  }
        
             $loop = $loop + 1;

             }
             unset($loop);
             
             
             // Force to max decimals if applicable
             if ( $decimals > $ct['conf']['gen']['crypto_decimals_max'] ) {
             return $ct['conf']['gen']['crypto_decimals_max'];
             }
             else {
             return $decimals;
             }


        }
        
    
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function log($log_type, $log_msg, $verbose_tracing=false, $hashcheck=false, $overwrite=false) {
   
   global $ct, $log_errors, $log_debugging, $is_iframe;
   
   // Since we sort by timestamp, we want millisecond accuracy (if possible), for ordering logs before output
   $timestamp_milliseconds = $ct['var']->num_to_str( floor(microtime(true) * 1000) );
   
   // Get millisecond decimals for log human-readable timestamps
   $decimals_milliseconds = '.' . substr($timestamp_milliseconds, -3);
   
   $formatted_time = date('Y-m-d H:i:s') . $decimals_milliseconds;
   
   
   // UX on log category
   $category = $log_type;
   $category = preg_replace("/_error/i", "|error", $category);
   $category = preg_replace("/_debug/i", "|debug", $category);
   $category = explode('|', $category);
   
      
      // 'notify' mode ALWAYS needs a hash check (even if we want multiple entries), to AVOID CORRUPTING log formatting
      if ( $category[0] == 'notify' && $hashcheck == false ) {
      $hashcheck = md5($timestamp_milliseconds . $category[0] . $log_msg);
      }
   
   
      // Disable logging any included verbose tracing, if log verbosity level config is set to normal
      if ( $ct['conf']['power']['log_verbosity'] == 'normal' ) {
      $verbose_tracing = false;
      }
      
      
      // Flag the runtime mode in logs if it's an iframe's runtime (for cleaner / less-confusing logs)
      // Change var name to avoid changing the GLOBAL value
      if ( $is_iframe ) {
      $logged_runtime_mode = $ct['runtime_mode'] . ' (' . ( isset($_GET['section']) ? 'iframe=' . $_GET['section'] : 'iframe=unknown' ) . ')';
      }
      else {
      $logged_runtime_mode = $ct['runtime_mode'];
      }
      
      
      if ( $category[1] == 'debug' ) {
          
   
          if ( $hashcheck != false ) {
          $log_debugging[$log_type][$hashcheck] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . ' (' . $category[1] . '): ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
          }
          // We parse cache errors as array entries (like when hashcheck is included, BUT NO ARRAY KEY)
          elseif ( $category[0] == 'cache' ) {
          $log_debugging[$log_type][] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . ' (' . $category[1] . '): ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
          }
          elseif ( $overwrite != false ) {
          $log_debugging[$log_type] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . ' (' . $category[1] . '): ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
          }
          else {
          $log_debugging[$log_type] .= '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . ' (' . $category[1] . '): ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
          }
      
      
      }
      else {
          
   
          if ( $hashcheck != false ) {
          $log_errors[$log_type][$hashcheck] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . ' (' . $category[1] . '): ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
          }
          // We parse cache errors as array entries (like when hashcheck is included, BUT NO ARRAY KEY)
          elseif ( $category[0] == 'cache' ) {
          $log_errors[$log_type][] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . ' (' . $category[1] . '): ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
          }
          elseif ( $overwrite != false ) {
          $log_errors[$log_type] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . ' (' . $category[1] . '): ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
          }
          else {
          $log_errors[$log_type] .= '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . ' (' . $category[1] . '): ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
          }
      
      
      }
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function key_to_name($str) {
   
   global $ct;
   
   // Uppercase every word's first character, after removing a few different delimiters between them,
   // and replacing with a space between words (and a placeholder where applicable)
   $str = preg_replace("/_/i", " ", $str);
   $str = preg_replace("/\|\|/i", " 123PLACEHOLDER123 ", $str); // Replaced with an arrow further down
   $str = preg_replace("/\|/i", " 123PLACEHOLDER123 ", $str); // Replaced with an arrow further down
   
   $str = ucwords($str);
   
   
      // Pretty up the individual words as needed
      $words = explode(" ",$str);
      
      foreach($words as $key => $val) {
   
   
         // Pretty up all asset market symbols
         foreach($ct['conf']['power']['bitcoin_currency_markets'] as $asset_key => $unused) {
               
             if ( strtolower($val) == strtolower($asset_key) ) {
             $words[$key] = strtoupper($asset_key); // All uppercase
             }
             
         }
        
        
         foreach($ct['conf']['assets'] as $asset_key => $unused) {
              
             if ( strtolower($val) == strtolower($asset_key) ) {
             $words[$key] = strtoupper($asset_key); // All uppercase
             }
             
         }
         
      
         if ( strtolower($val) == 'us' ) {
         $words[$key] = strtoupper($val); // All uppercase
         }
         elseif ( strtolower($val) == '123placeholder123' ) {
         $words[$key] = '=>';
         }
         elseif ( strtolower($val) == 'ext' ) {
         $words[$key] = 'External';
         }
         elseif ( strtolower($val) == 'ag' ) {
         $words[$key] = 'Aggregator';
         }
         elseif ( strtolower($val) == 'comms' ) {
         $words[$key] = 'Communications';
         }
         elseif ( strtolower($val) == 'ico' ) {
         $words[$key] = 'ICO';
         }
         elseif ( strtolower($val) == 'icos' ) {
         $words[$key] = 'ICOs';
         }
         elseif ( strtolower($val) == 'ido' ) {
         $words[$key] = 'IDO';
         }
         elseif ( strtolower($val) == 'idos' ) {
         $words[$key] = 'IDOs';
         }
         elseif ( strtolower($val) == 'nft' ) {
         $words[$key] = 'NFT';
         }
         elseif ( strtolower($val) == 'nfts' ) {
         $words[$key] = 'NFTs';
         }
         elseif ( strtolower($val) == 'amm' ) {
         $words[$key] = 'AMM';
         }
         elseif ( strtolower($val) == 'amms' ) {
         $words[$key] = 'AMMs';
         }
         elseif ( strtolower($val) == 'dex' ) {
         $words[$key] = 'DEX';
         }
         elseif ( strtolower($val) == 'ui' ) {
         $words[$key] = 'User Interface';
         }
         elseif ( strtolower($val) == 'sid' ) {
         $words[$key] = 'SID';
         }
         elseif ( strtolower($val) == 'id' ) {
         $words[$key] = 'ID';
         }
         elseif ( strtolower($val) == 'ids' ) {
         $words[$key] = 'IDs';
         }
         elseif ( strtolower($val) == 'api' ) {
         $words[$key] = 'API';
         }
         elseif ( strtolower($val) == 'apis' ) {
         $words[$key] = 'APIs';
         }
         elseif ( strtolower($val) == 'ssl' ) {
         $words[$key] = 'SSL';
         }
      
      
      $pretty_str .= $words[$key] . ' ';
      
      }
   
   
   $pretty_str = preg_replace("/btc/i", 'BTC', $pretty_str);
   $pretty_str = preg_replace("/smtp/i", 'SMTP', $pretty_str);
   $pretty_str = preg_replace("/mart/i", 'Mart', $pretty_str);
   $pretty_str = preg_replace("/coin/i", 'Coin', $pretty_str);
   $pretty_str = preg_replace("/bitcoin/i", 'Bitcoin', $pretty_str);
   $pretty_str = preg_replace("/exchange/i", 'Exchange', $pretty_str);
   $pretty_str = preg_replace("/market/i", 'Market', $pretty_str);
   $pretty_str = preg_replace("/base/i", 'Base', $pretty_str);
   $pretty_str = preg_replace("/forex/i", 'Forex', $pretty_str);
   $pretty_str = preg_replace("/finex/i", 'Finex', $pretty_str);
   $pretty_str = preg_replace("/stamp/i", 'Stamp', $pretty_str);
   $pretty_str = preg_replace("/flyer/i", 'Flyer', $pretty_str);
   $pretty_str = preg_replace("/panda/i", 'Panda', $pretty_str);
   $pretty_str = preg_replace("/pay/i", 'Pay', $pretty_str);
   $pretty_str = preg_replace("/swap/i", 'Swap', $pretty_str);
   $pretty_str = preg_replace("/iearn/i", 'iEarn', $pretty_str);
   $pretty_str = preg_replace("/pulse/i", 'Pulse', $pretty_str);
   $pretty_str = preg_replace("/defi/i", 'DeFi', $pretty_str);
   $pretty_str = preg_replace("/loopring/i", 'LoopRing', $pretty_str);
   $pretty_str = preg_replace("/erc20/i", 'ERC-20', $pretty_str);
   $pretty_str = preg_replace("/okex/i", 'OKex', $pretty_str);
   $pretty_str = preg_replace("/dcx/i", 'DCX', $pretty_str);
   $pretty_str = preg_replace("/gateio/i", 'Gate.io', $pretty_str);
   $pretty_str = preg_replace("/notifyme/i", 'NotifyMe', $pretty_str);
   $pretty_str = preg_replace("/etherscan/i", 'EtherScan', $pretty_str);
   $pretty_str = preg_replace("/precache/i", 'Pre-Cache', $pretty_str);
   $pretty_str = preg_replace("/coingecko/i", 'CoinGecko.com', $pretty_str);
   $pretty_str = preg_replace("/coinmarketcap/i", 'CoinMarketCap.com', $pretty_str);
   $pretty_str = preg_replace("/alphavantage/i", 'AlphaVantage.co', $pretty_str);
   $pretty_str = preg_replace("/bitcoin/i", 'Bitcoin', $pretty_str);
   
   
   return trim($pretty_str);
   
   }
  
  
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
  
  
   function news_feed_email($interval) {
  
   global $ct;
  
  
	  // With offset, to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $ct['cache']->update_cache($ct['base_dir'] . '/cache/events/news-feed-email.dat', ($interval * 1440) + $ct['dev']['tasks_time_offset'] ) == true ) {
      
      // Reset feed fetch telemetry 
      $_SESSION[$fetched_feeds] = false;
        	
      $header = '<html><head> <style> li {margin: 8px;} fieldset, legend {color: #F7931A;} </style> </head><body>' . "\n\n" . '<div style="padding: 15px;">' . "\n\n";
      
      $footer = "\n\n" . '</div>' . "\n\n" . '</body></html>';
        
        
        	// NEW RSS feed posts
        	$num_posts = 0;
        	foreach($ct['conf']['news_feeds'] as $feed_item) {
        	    
        		if ( isset($feed_item["url"]) && trim($feed_item["url"]) != '' ) {
        		    
        		$result = $ct['api']->rss($feed_item["url"], false, $ct['conf']['comms']['news_feed_email_entries_show'], false, true);
        		
        		  if ( trim($result) != '<ul></ul>' ) {
        		  $content .= '<div style="padding: 40px;"><fieldset><legend style="font-weight: bold;"> ' . $feed_item["title"] . ' </legend>' . "\n\n";
        	 	  $content .= $result . "\n\n";
        		  $content .= '</fieldset></div>' . "\n\n";
        	 	  $num_posts++;  
        		  }
        		  
        	 	}
        	 	
        	}      
      
      
      // Render summary after determining the content
      $summary .= '<h2 style="color: black;">' . $num_posts . ' Updated RSS Feeds (over ' . $ct['conf']['comms']['news_feed_email_frequency'] . ' days)</h3>' . "\n\n";
        	
        	if ( $ct['app_edition'] == 'server' ) {
          $summary .= '<p><a style="color: #00b6db;" title="View the news feeds page in the Open Crypto Tracker app here." target="_blank" href="' . $ct['base_url'] . 'index.php?start_page=news#news">View All News Feeds Here</a></p>' . "\n\n";
        	}
	
	 $summary .= '<p>You can disable receiving news feed emails in the Admin Config "Communications" section.</p>' . "\n\n";
	
	 $summary .= '<p>You can edit this list in the Admin Config "Power User" section.</p>' . "\n\n";
	
	 $summary .= '<p>To see the date / time an entry was published, hover over it.</p>' . "\n\n";
	
	 $summary .= '<p>Entries are sorted newest to oldest.</p>' . "\n\n";
      
      
      // Package HTML / text into the message body
      $email_body = $header . $summary . $content . $footer;
      
      // Convert any CSS for red coloring (without a class) on feed error messages, AND create a large margin around it
      $email_body = preg_replace("/class=\"red\"/i", "style=\"margin: 15px; color: red;\"", $email_body); 
      
               
      $send_params = array(
                                                    
                           'email' => array(
                                            'content_type' => 'text/html', // Have email sent as HTML content type
                                            'subject' => $num_posts . ' Updated RSS Feeds (over ' . $ct['conf']['comms']['news_feed_email_frequency'] . ' days)',
                                            'message' => $email_body
                                           )
                                                       
                          );
                    
                    
                    
      // Send notifications
      @$ct['cache']->queue_notify($send_params);
                        
      
      $ct['cache']->save_file($ct['base_dir'] . '/cache/events/news-feed-email.dat', $this->time_date_format(false, 'pretty_date_time') );
      
      }
      
   
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
        		
        	  $this->log(
        				'security_error',
        				'Cryptographically secure pseudo-random bytes could not be generated for API key (in secured cache storage), API key creation aborted to preserve security'
        				);
        	
        	  return false;
        	
        	  }
        	  else {
        	  $ct['cache']->save_file($ct['base_dir'] . '/' . $domain_check_filename, $set_256bit_hash);
        	  }

        		
        // HTTPS CHECK ONLY (for security if htaccess user/pass activated), don't cache API data
        	
        // domain check
        $domain_check_test_url = $set_url . $domain_check_filename;
        
        sleep(5); // Sleep 5 seconds, to complete the FILE WRITE before the check afterwards
        
        $domain_check_test = @$ct['cache']->ext_data('url', $domain_check_test_url, 0);
       
        sleep(5); // Sleep 5 seconds, to complete the CHECK before deleting afterwards
        
        // Delete domain check test file
        unlink($ct['base_dir'] . '/' . $domain_check_filename);
        
        	
        	  // If it's a possible hostname header attack
        	  if ( !preg_match("/" . $set_256bit_hash . "/i", $domain_check_test) ) {
        	  return array('security_error' => true, 'checked_url' => $domain_check_test_url, 'response_output' => $domain_check_test);
        	  }
        	
        
        }
   
   
   return $set_url;  // Return if we made it this far
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function prune_first_lines($filename, $num, $oldest_allowed_timestamp=false) {
   
   $result = array();
   $file = file($filename);
   
    if ( !is_array($file) ) {
    $result['lines_removed'] = 0;
    $result['data'] = false;
    return $result;
    }
    else {
    $size = sizeof($file);
    }
    
   
      $loop = 0;
   
      if ( $oldest_allowed_timestamp == false ) {
      
         while ( $loop < $num && !$stop_loop ) {
            
            if ( isset($file[$loop]) ) {
            unset($file[$loop]);
            }
            else {
            $stop_loop = true;
            }
            
         $loop = $loop + 1;
         }
      
      }
      else {
      
         while( $loop < $size && !$stop_loop ) {
         
            if ( isset($file[$loop]) ) {
               
            $line_array = explode("||", $file[$loop]);
            $line_timestamp = $line_array[0];
            
               // If timestamp is older than allowed, we remove the line
               if ( $line_timestamp < $oldest_allowed_timestamp ) {
               unset($file[$loop]);
               }
               else {
               $stop_loop = true;
               }
            
            }
            else {
            $stop_loop = true;
            }
            
         $loop = $loop + 1;
         }
      
      }
      
      
   $result['lines_removed'] = $size - sizeof($file);
   $result['data'] = implode("", $file); // WITHOUT newline delimiting, since file() maintains those by default
   
   return $result;
   
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
   
   
   function reset_price_alert_notice() {
   
   global $ct, $price_alert_fixed_reset_array, $default_bitcoin_primary_currency_pair;
   
   // Alphabetical asset sort, for message UX 
   ksort($price_alert_fixed_reset_array);
   
   
      $count = 0;
      foreach( $price_alert_fixed_reset_array as $reset_data ) {
      
         foreach( $reset_data as $asset_alert ) {
         
         $reset_list .= $asset_alert . ', ';
         
         $count = $count + 1;
         
         }
      
      }

      
   // Trim results
   $reset_list = trim($reset_list);
   $reset_list = rtrim($reset_list, ',');
   
      
      // Return if no resets occurred
      if ( $count < 1 ) {
      return;
      }
   
   
   $text_msg = $count . ' ' . strtoupper($default_bitcoin_primary_currency_pair) . ' Price Alert Fixed Resets: ' . $reset_list;
   
   $email_msg = 'The following ' . $count . ' ' . strtoupper($default_bitcoin_primary_currency_pair) . ' price alert fixed resets (run every ' . $ct['conf']['power']['price_alert_fixed_reset'] . ' days) have been processed, with the latest spot price data: ' . $reset_list;
   
   $notifyme_msg = $email_msg . ' Timestamp is ' . $this->time_date_format($ct['conf']['gen']['local_time_offset'], 'pretty_time') . '.';
   
   
   // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
                       
   // Minimize function calls
   $text_msg = $this->detect_unicode($text_msg); 
                       
   $send_params = array(
   
                        'notifyme' => $notifyme_msg,
                        'telegram' => $email_msg,
                        'text' => array(
                                        'message' => $text_msg['content'],
                                        'charset' => $text_msg['charset']
                                        ),
                        'email' => array(
                                         'subject' => 'Price Alert Fixed Reset Processed For ' . $count . ' Alert(s)',
                                         'message' => $email_msg 
                                         )
                                         
                          );
                   
                   
   // Send notifications
   @$ct['cache']->queue_notify($send_params);
         
   
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
				
   $_SESSION['admin_logged_in']['auth_hash'] = $this->admin_hashed_nonce($cookie_nonce, 'force'); // Force set, as we're not logged in fully yet
   
   
       // If admin login notifications are on
       if ( $ct['conf']['sec']['login_alert'] != 'off' ) {

      
            if ( isset($ct['system_info']['distro_name']) ) {
            $system_info_summary = "\n\nApp Server System Info: " . $ct['system_info']['distro_name'] . ( isset($ct['system_info']['distro_version']) ? ' ' . $ct['system_info']['distro_version'] : '' );
            }
              
                            
       // Build the different messages, configure comm methods, and send messages
                            
       $email_msg = 'New admin login from: ' . $ct['remote_ip'] . $system_info_summary;
                            
       // Were're just adding a human-readable timestamp to smart home (audio) alerts
       $notifyme_msg = $email_msg . ' Timestamp: ' . $this->time_date_format($ct['conf']['gen']['local_time_offset'], 'pretty_time') . '.';
                            
       $text_msg = $email_msg;
                        
       // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
                  
       // Minimize function calls
       $text_msg = $this->detect_unicode($text_msg); 
    			
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
    				
    		    
       // Only send to comm channels the user prefers, based off the config setting $ct['conf']['sec']['login_alert']
       $preferred_comms = $this->preferred_comms($ct['conf']['sec']['login_alert'], $admin_login_send_params);
    			
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
   
   
    // https://thisinterestsme.com/random-rgb-hex-color-php/ (MODIFIED)
    // Human visual perception of different color mixes seems a tad beyond what an algo can distinguish based off AVERAGE range minimums,
    // ESPECIALLY once a list of random-colored items get above a certain size in number (as this decreases your availiable range minimum)
    // That said, auto-adjusting range minimums based off available RGB palette / list size IS feasible AND seems about as good as it can get,
    // AS LONG AS YOU DON'T OVER-MINIMIZE THE RANDOM OPTIONS / EXAUST ALL RANDOM OPTIONS (AND ENDLESSLY LOOP)
   function rand_color($list_size) {
      
   global $rand_color_ranged;
   
   $result = array();
   
   // WE DON'T USE THE ENTIRE 0-255 RANGES, AS SOME COLORS ARE TOO DARK / LIGHT AT FULL RANGES
   $darkest = 79;
   $lightest = 178;
   $thres_min = 0.675; // (X.XXX) Only require X% of threshold, to avoid exhuasting decent amount / ALL of random options
       
   // Minimum range threshold, based on USED RGB pallette AND number of colored items 
   // (range minimum based on list size, AND $thres_min)
   $min_range = round( ( ($lightest - $darkest) / $list_size ) * $thres_min );
   // ABSOLUTE min (max auto-calculated within safe range)
   $min_range = ( $min_range < 1 ? 1 : $min_range );
   
   
      // Generate random colors, WITH minimum (average) range differences
      while ( $result['hex'] == '' ) {
      
      $result = array('rgb' => '', 'hex' => '');
      $hex = null;
      $range_too_close = false;
      
      
         /////////////////////////////////
         // Randomly generate a color
         /////////////////////////////////
         foreach( array('r', 'b', 'g') as $col ) {
         
         $rand = mt_rand($darkest, $lightest); 
         $rgb[$col] = $rand;
         $dechex = dechex($rand);
             
             if( strlen($dechex) < 2 ){
             $dechex = '0' . $dechex;
             }
             
         $hex .= $dechex;
         
         }
       
         
         /////////////////////////////////
         // Check to make sure new random color isn't within range (nearly same color codes) of any colors already generated
         /////////////////////////////////
         if( is_array($rand_color_ranged) && sizeof($rand_color_ranged) > 0 ) {
         
            // Compare new random color's range to any colors already generated
            foreach( $rand_color_ranged as $used_range ) {
               
            $overall_range = abs($rgb['r'] - $used_range['r']) + abs($rgb['g'] - $used_range['g']) + abs($rgb['b'] - $used_range['b']);
               
               // If we are too close to a previously-generated random color's range, flag it
               if ( $overall_range < ($min_range * 3) ) {
               $range_too_close = true;
               }
               
            }
         
            
            // If the new random color is NOT out of range, use it / add it to list of any colors already generated
            if ( !$range_too_close ) {
            $rand_color_ranged[] = $rgb;
            $result['hex'] = $hex;
            $result['rgb'] = $rgb;
            }
         
         }
         /////////////////////////////////
         // If this is the first random color generated
         /////////////////////////////////
         else {
         $rand_color_ranged[] = $rgb;
         $result['hex'] = $hex;
         $result['rgb'] = $rgb;
         }
       
       
      }
      
   
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function light_chart_time_period($light_chart_days, $mode) {
       
   global $ct;
      
   // Whole integer time periods only (otherwise UI shows "day[s]")
      
      if ( $mode == 'short' ) {
   
         if ( $light_chart_days == 'all' ) {
         $time_period_text = strtoupper($light_chart_days);
         }
         elseif ( $light_chart_days >= 365 && $ct['var']->whole_int($light_chart_days / 365) ) {
         $time_period_text = ($light_chart_days / 365) . 'Y';
         }
         elseif ( $light_chart_days >= 30 && $ct['var']->whole_int($light_chart_days / 30) ) {
         $time_period_text = ($light_chart_days / 30) . 'M';
         }
         elseif ( $light_chart_days >= 7 && $ct['var']->whole_int($light_chart_days / 7) ) {
         $time_period_text = ($light_chart_days / 7) . 'W';
         }
         else {
         $time_period_text = $light_chart_days . 'D';
         }
      
      }
      elseif ( $mode == 'long' ) {
   
         if ( $light_chart_days == 'all' ) {
         $time_period_text = ucfirst($light_chart_days);
         }
         elseif ( $light_chart_days >= 365 && $ct['var']->whole_int($light_chart_days / 365) ) {
         $plural = ( ($light_chart_days / 365) > 1 ? 's' : '' );
         $time_period_text = ($light_chart_days / 365) . ' Year' . $plural;
         }
         elseif ( $light_chart_days >= 30 && $ct['var']->whole_int($light_chart_days / 30) ) {
         $plural = ( ($light_chart_days / 30) > 1 ? 's' : '' );
         $time_period_text = ($light_chart_days / 30) . ' Month' . $plural;
         }
         elseif ( $light_chart_days >= 7 && $ct['var']->whole_int($light_chart_days / 7) ) {
         $plural = ( ($light_chart_days / 7) > 1 ? 's' : '' );
         $time_period_text = ($light_chart_days / 7) . ' Week' . $plural;
         }
         else {
         $plural = ( $light_chart_days > 1 ? 's' : '' );
         $time_period_text = $light_chart_days . ' Day' . $plural;
         }
      
      }
   
   
   return $time_period_text;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function chart_data($file, $chart_format, $start_timestamp=0) {
   
   global $ct, $default_bitcoin_primary_currency_pair, $runtime_data, $last_valid_chart_data;
   
   $data = array();
   
      // #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
      if ( array_key_exists($chart_format, $ct['conf']['power']['bitcoin_currency_markets']) && !array_key_exists($chart_format, $ct['conf']['power']['crypto_pair']) ) {
      $fiat_formatting = true;
      }
      elseif ( $chart_format == 'system' ) {
      $system_statistics_chart = true;
      }
      elseif ( $chart_format == 'performance' ) {
      $asset_perf_chart = true;
      $asset = $file;
      $asset = preg_replace("/(.*)_days\//i", "", $asset);
      $asset = preg_replace("/\/(.*)/i", "", $asset);
      }
   
   
   $fn = fopen($file,"r");
     
     while( !feof($fn) )  {
      
      $result = explode("||", fgets($fn) );
      
      $result = array_map('trim', $result); // Trim whitespace out of all array values
      
      
         if ( isset($result[0]) && trim($result[0]) != '' && trim($result[0]) >= $start_timestamp ) {
            
         $data['time'] .= trim($result[0]) . '000,';  // Zingchart wants 3 more zeros with unix time (milliseconds)
         
         
            if ( $system_statistics_chart ) {
            
            
                if ( isset($result[1]) && trim($result[1]) != 'NO_DATA' && trim($result[1]) != '' ) {
                $data['load_average_15_minutes'] .= trim($result[1]) . ',';
                $last_valid_chart_data['load_average_15_minutes'] = $result[1];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($last_valid_chart_data['load_average_15_minutes']) ) {
                $data['load_average_15_minutes'] .= trim($last_valid_chart_data['load_average_15_minutes']) . ',';
                }
            
            
                if ( isset($result[2]) && trim($result[2]) != 'NO_DATA' && trim($result[2]) != '' ) {
                $data['temperature_celsius'] .= trim($result[2]) . ',';
                $last_valid_chart_data['temperature_celsius'] = $result[2];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($last_valid_chart_data['temperature_celsius']) ) {
                $data['temperature_celsius'] .= trim($last_valid_chart_data['temperature_celsius']) . ',';
                }
            
            
                if ( isset($result[3]) && trim($result[3]) != 'NO_DATA' && trim($result[3]) != '' ) {
                $data['used_memory_gigabytes'] .= trim($result[3]) . ',';
                $last_valid_chart_data['used_memory_gigabytes'] = $result[3];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($last_valid_chart_data['used_memory_gigabytes']) ) {
                $data['used_memory_gigabytes'] .= trim($last_valid_chart_data['used_memory_gigabytes']) . ',';
                }
            
            
                if ( isset($result[4]) && trim($result[4]) != 'NO_DATA' && trim($result[4]) != '' ) {
                $data['used_memory_percentage'] .= trim($result[4]) . ',';
                $last_valid_chart_data['used_memory_percentage'] = $result[4];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($last_valid_chart_data['used_memory_percentage']) ) {
                $data['used_memory_percentage'] .= trim($last_valid_chart_data['used_memory_percentage']) . ',';
                }
            
            
                if ( isset($result[5]) && trim($result[5]) != 'NO_DATA' && trim($result[5]) != '' ) {
                $data['free_disk_space_terabytes'] .= trim($result[5]) . ',';
                $last_valid_chart_data['free_disk_space_terabytes'] = $result[5];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($last_valid_chart_data['free_disk_space_terabytes']) ) {
                $data['free_disk_space_terabytes'] .= trim($last_valid_chart_data['free_disk_space_terabytes']) . ',';
                }
            
            
                if ( isset($result[6]) && trim($result[6]) != 'NO_DATA' && trim($result[6]) != '' ) {
                $data['portfolio_cache_size_gigabytes'] .= trim($result[6]) . ',';
                $last_valid_chart_data['portfolio_cache_size_gigabytes'] = $result[6];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($last_valid_chart_data['portfolio_cache_size_gigabytes']) ) {
                $data['portfolio_cache_size_gigabytes'] .= trim($last_valid_chart_data['portfolio_cache_size_gigabytes']) . ',';
                }
            
            
                if ( isset($result[7]) && trim($result[7]) != 'NO_DATA' && trim($result[7]) != '' ) {
                $data['cron_core_runtime_seconds'] .= trim($result[7]) . ',';
                $last_valid_chart_data['cron_core_runtime_seconds'] = $result[7];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($last_valid_chart_data['cron_core_runtime_seconds']) ) {
                $data['cron_core_runtime_seconds'] .= trim($last_valid_chart_data['cron_core_runtime_seconds']) . ',';
                }
                
            
            }
            elseif ( $asset_perf_chart && isset($result[1]) && trim($result[1]) != 'NO_DATA' && trim($result[1]) != '' ) {
      
               if ( !$runtime_data['performance_stats'][$asset]['start_val'] ) {
               $runtime_data['performance_stats'][$asset]['start_val'] = $result[1];
               
               $data['percent'] .= '0.00,';
               $data['combined'] .= '[' . trim($result[0]) . '000, 0.00],';  // Zingchart wants 3 more zeros with unix time (milliseconds)
               }
               else {
                  
               // PRIMARY CURRENCY CONFIG price percent change (CAN BE NEGATIVE OR POSITIVE IN THIS INSTANCE)
               $percent_change = ($result[1] - $runtime_data['performance_stats'][$asset]['start_val']) / abs($runtime_data['performance_stats'][$asset]['start_val']) * 100;
               // Better decimal support
               $percent_change = $ct['var']->num_to_str($percent_change); 
               
               $data['percent'] .= round($percent_change, 2) . ',';
               $data['combined'] .= '[' . trim($result[0]) . '000' . ', ' . round($percent_change, 2) . '],';  // Zingchart wants 3 more zeros with unix time (milliseconds)
               
               }
            
            }
            elseif ( isset($result[1]) && isset($result[2]) && trim($result[1]) != 'NO_DATA' && trim($result[2]) != 'NO_DATA' && trim($result[1]) != '' && trim($result[2]) != '' ) {
            
               // Format or round primary currency price depending on value (non-stablecoin crypto values are already stored in the format we want for the interface)
               if ( $fiat_formatting ) {
               $data['spot'] .= $ct['var']->num_to_str($result[1]) . ',';
               $data['volume'] .= round($result[2]) . ',';
               }
               // Non-stablecoin crypto
               else {
               $data['spot'] .= $ct['var']->num_to_str($result[1]) . ',';
               $data['volume'] .= $ct['var']->num_to_str( round($result[2], $ct['conf']['gen']['chart_crypto_volume_decimals']) ) . ',';
               }
            
            }
         
         
         }
      
     }
   
   fclose($fn);
   
   gc_collect_cycles(); // Clean memory cache
   
   // Trim away extra commas
   $data['time'] = rtrim($data['time'],',');
   
   
      if ( $system_statistics_chart ) {
      $data['temperature_celsius'] = rtrim($data['temperature_celsius'],',');
      $data['used_memory_percentage'] = rtrim($data['used_memory_percentage'],',');
      $data['cron_core_runtime_seconds'] = rtrim($data['cron_core_runtime_seconds'],',');
      $data['used_memory_gigabytes'] = rtrim($data['used_memory_gigabytes'],',');
      $data['load_average_15_minutes'] = rtrim($data['load_average_15_minutes'],',');
      $data['free_disk_space_terabytes'] = rtrim($data['free_disk_space_terabytes'],',');
      $data['portfolio_cache_size_gigabytes'] = rtrim($data['portfolio_cache_size_gigabytes'],',');
      }
      elseif ( $asset_perf_chart ) {
      $data['percent'] = rtrim($data['percent'],',');
      $data['combined'] = rtrim($data['combined'],',');
      }
      else {
      $data['spot'] = rtrim($data['spot'],',');
      $data['volume'] = rtrim($data['volume'],',');
      }
      
   
   return $data;
   
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
               
                  
            if ( isset($_POST['show_charts']) ) {
            $this->store_cookie("show_charts", $_POST['show_charts'], time()+31536000);
            }
            else {
            unset($_COOKIE['show_charts']);
            $this->store_cookie('show_charts', '', time()-3600); // Delete
            }
                  
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
                  
            if ( isset($_POST['show_feeds']) ) {
            $this->store_cookie("show_feeds", $_POST['show_feeds'], time()+31536000);
            }
            else {
            unset($_COOKIE['show_feeds']);
            $this->store_cookie('show_feeds', '', time()-3600); // Delete
            }
                 
            if ( isset($_POST['theme_selected']) ) {
            $this->store_cookie("theme_selected", $_POST['theme_selected'], time()+31536000);
            }
            else {
            unset($_COOKIE['theme_selected']);
            $this->store_cookie('theme_selected', '', time()-3600); // Delete
            }
                  
            if ( isset($_POST['sort_by']) ) {
            $this->store_cookie("sort_by", $_POST['sort_by'], time()+31536000);
            }
            else {
            unset($_COOKIE['sort_by']);
            $this->store_cookie('sort_by', '', time()-3600); // Delete
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
              

   usleep(100000); // Wait 0.10 seconds, to give cookies a chance to save, before any redirect
    
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function safe_mail($to, $subj, $msg, $content_type='text/plain', $charset=null) {
      
   global $ct;
   
      if ( $charset == null ) {
      $charset = $ct['dev']['charset_default'];
      }
   
   // Stop injection vulnerability
   $ct['conf']['comms']['from_email'] = str_replace("\r\n", "", $ct['conf']['comms']['from_email']); // windows -> unix
   $ct['conf']['comms']['from_email'] = str_replace("\r", "", $ct['conf']['comms']['from_email']);   // remaining -> unix
   
   // Trim any (remaining) whitespace off ends
   $ct['conf']['comms']['from_email'] = trim($ct['conf']['comms']['from_email']);
   $to = trim($to);
         
         
      // Validate TO email
      $email_check = $this->valid_email($to);
      if ( $email_check != 'valid' ) {
      return $email_check;
      }
      
      
      // SMTP mailing, or PHP's built-in mail() function
      if ( $ct['conf']['comms']['smtp_login'] != '' && $ct['conf']['comms']['smtp_server'] != '' ) {
      return @$this->smtp_mail($to, $subj, $msg, $content_type, $charset); 
      }
      else {
         
         // Use array for safety from header injection >= PHP 7.2 
         if ( PHP_VERSION_ID >= 70200 ) {
            
            // Fallback, if no From email set in app config
            if ( $this->valid_email($ct['conf']['comms']['from_email']) == 'valid' ) {
            
            $headers = array(
                        'From' => 'From: ' . $ct['conf']['comms']['from_email'],
                        'X-Mailer' => $ct['system_info']['software'],
                        'Content-Type' => $content_type . '; charset=' . $charset
                           );
            
            }
            else {
            
            $headers = array(
                        'X-Mailer' => $ct['system_info']['software'],
                        'Content-Type' => $content_type . '; charset=' . $charset
                           );
            
            }
      
         }
         else {
            
            // Fallback, if no From email set in app config
            if ( $this->valid_email($ct['conf']['comms']['from_email']) == 'valid' ) {
            
            $headers = 'From: ' . $ct['conf']['comms']['from_email'] . "\r\n" .
            'X-Mailer: ' . $ct['system_info']['software'] . "\r\n" .
            'Content-Type: ' . $content_type . '; charset=' . $charset;
         
            }
            else {
            
            $headers = 'X-Mailer: ' . $ct['system_info']['software'] . "\r\n" .
            'Content-Type: ' . $content_type . '; charset=' . $charset;
         
            }
         
         }
         
      
      return @mail($to, $subj, $msg, $headers);
      
      }
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function get_last_lines($file, $linecount, $length) {
      
   $linecount = $linecount + 2; // Offset including blank data on ends
   
   $length = $length * 1.5; // Offset to assure we get enough data
   
   //we double the offset factor on each iteration
   //if our first guess at the file offset doesn't
   //yield $linecount lines
   $offset_factor = 1;
   
   $bytes = filesize($file);
   
   $fp = fopen($file, "r");
   
      if ( !$fp ) {
      return false;
      }
   
   
      $complete = false;
      while ( !$complete ) {
         
      //seek to a position close to end of file
      $offset = $linecount * $length * $offset_factor;
      fseek($fp, -$offset, SEEK_END);
      
      
         //we might seek mid-line, so read partial line
         //if our offset means we're reading the whole file, 
         //we don't skip...
         if ( $offset < $bytes ) {
         fgets($fp);
         }
      
      
         //read all following lines, store last x
         $lines = array();
         while( !feof($fp) ) {
            
            $line = fgets($fp);
            array_push($lines, $line);
            
            if ( count($lines) > $linecount ) {
            array_shift($lines);
            $complete = true;
            }
            
         }
      
      
         //if we read the whole file, we're done, even if we
         //don't have enough lines
         if ( $offset >= $bytes ) {
         $complete = true;
         }
         else {
         $offset_factor *= 2; //otherwise let's seek even further back
         }
          
          
      }
   
   fclose($fp);
   
   gc_collect_cycles(); // Clean memory cache
   
   
      if ( !$lines ) {
      return false;
      }
      else {
      return array_slice( $lines, (0 - $linecount) );
      }
   
   
   }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function test_proxy($problem_proxy_array) {
  
  global $ct, $proxies_checked;
  
  // Endpoint to test proxy connectivity: https://www.myip.com/api-docs/
  $proxy_test_url = 'https://api.myip.com/';
  
  $problem_endpoint = $problem_proxy_array['endpoint'];
  
  $obfusc_url_data = $this->obfusc_url_data($problem_endpoint); // Automatically removes sensitive URL data
  
  $problem_proxy = $problem_proxy_array['proxy'];
  
  $ip_port = explode(':', $problem_proxy);
  
  $ip = $ip_port[0];
  $port = $ip_port[1];
  
  
      // If no ip/port detected in data string, cancel and continue runtime
      if ( !$ip || !$port ) {
      $this->log('ext_data_error', 'proxy '.$problem_proxy.' is not a valid format');
      return false;
      }
  
  
  // Create cache filename / session var
  $cache_filename = $problem_proxy;
  $cache_filename = preg_replace("/\./", "-", $cache_filename);
  $cache_filename = preg_replace("/:/", "_", $cache_filename);
  
  
      if ( $ct['conf']['comms']['proxy_alert_runtime'] == 'all' ) {
      $run_alerts = 1;
      }
      elseif ( $ct['conf']['comms']['proxy_alert_runtime'] == 'cron' && $ct['runtime_mode'] == 'cron' ) {
      $run_alerts = 1;
      }
      elseif ( $ct['conf']['comms']['proxy_alert_runtime'] == 'ui' && $ct['runtime_mode'] == 'ui' ) {
      $run_alerts = 1;
      }
      else {
      $run_alerts = null;
      }
  
  
      if ( $run_alerts == 1 && $ct['cache']->update_cache('cache/alerts/proxy-check-'.$cache_filename.'.dat', ( $ct['conf']['comms']['proxy_alert_frequency_maximum'] * 60 ) ) == true
      && in_array($cache_filename, $proxies_checked) == false ) {
      
       
      // SESSION VAR first, to avoid duplicate alerts at runtime (and longer term cache file locked for writing further down, after logs creation)
      $proxies_checked[] = $cache_filename;
       
      $response = @$ct['cache']->ext_data('proxy-check', $proxy_test_url, 0, '', '', $problem_proxy);
      
      $data = json_decode($response, true);
      
      
         if ( is_array($data) && sizeof($data) > 0 ) {
          
            // Look for the IP in the response
            if ( strstr($data['ip'], $ip) == false ) {
             
            $misconfigured = 1;
            
            $notifyme_alert = 'A checkup on proxy ' . $ip . ', port ' . $port . ' detected a misconfiguration. Remote address ' . $data['ip'] . ' does not match the proxy address. Runtime mode is ' . $ct['runtime_mode'] . '.';
            
            $text_alert = 'Proxy ' . $problem_proxy . ' remote address mismatch (detected as: ' . $data['ip'] . '). runtime: ' . $ct['runtime_mode'];
           
            }
          
         $cached_logs = ( $misconfigured == 1 ? 'runtime: ' . $ct['runtime_mode'] . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = MISCONFIGURED (test endpoint ' . $proxy_test_url . ' detected the incoming ip as: ' . $data['ip'] . ')' . "; \n " . 'Remote address DOES NOT match proxy address;' : 'runtime: ' . $ct['runtime_mode'] . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = OK (test endpoint ' . $proxy_test_url . ' detected the incoming ip as: ' . $data['ip'] . ');' );
         
         }
         else {
          
         $misconfigured = 1;
         
         $notifyme_alert = 'A checkup on proxy ' . $ip . ', port ' . $port . ' resulted in a failed data request. No endpoint connection could be established. Runtime mode is ' . $ct['runtime_mode'] . '.';
          
         $text_alert = 'Proxy ' . $problem_proxy . ' failed, no endpoint connection. runtime: ' . $ct['runtime_mode'];
         
         $cached_logs = 'runtime: ' . $ct['runtime_mode'] . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = DATA REQUEST FAILED' . "; \n " . 'No connection established at test endpoint ' . $proxy_test_url . ';';
       
         }
       
       
         // Log to error logs
         if ( $misconfigured == 1 ) {
         	
         $this->log(
         			'ext_data_error',
         			'proxy '.$problem_proxy.' connection failed',
         			$cached_logs
         			);
         
         }
      
     
      // Update alerts cache for this proxy (to prevent running alerts for this proxy too often)
      $ct['cache']->save_file($ct['base_dir'] . '/cache/alerts/proxy-check-'.$cache_filename.'.dat', $cached_logs);
        
           
      $email_alert = " The proxy " . $problem_proxy . " recently did not receive data when accessing this endpoint: \n " . $obfusc_url_data . " \n \n A check on this proxy was performed at " . $proxy_test_url . ", and results logged: \n ============================================================== \n " . $cached_logs . " \n ============================================================== \n \n ";
                         
       
         // Send out alerts
         if ( $misconfigured == 1 || $ct['conf']['comms']['proxy_alert_checkup_ok'] == 'include' ) {
             
         // Minimize function calls
         $text_alert = $this->detect_unicode($text_alert); 
                           
         $send_params = array(
                             'notifyme' => $notifyme_alert,
                             'telegram' => $email_alert,
                             'text' => array(
                                   'message' => $text_alert['content'],
                                   'charset' => $text_alert['charset']
                                   ),
                             'email' => array(
                                   'subject' => 'A Proxy Was Unresponsive',
                                   'message' => $email_alert
                                   )
                              );
                  
		 
		 // Only send to comm channels the user prefers, based off the config setting $ct['conf']['comms']['proxy_alert']
		 $preferred_comms = $this->preferred_comms($ct['conf']['comms']['proxy_alert'], $send_params);			
                  
         // Queue notifications
         @$ct['cache']->queue_notify($preferred_comms);
                  
         }
               
       
      }
  
  
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function system_info() {
   
   global $ct;
   
   $system = array();
   
   // OS
   $system['operating_system'] = php_uname();
   
      
      // Distro
      if ( is_readable('/etc/os-release') ) {
      
      $distro['distro_info'] = $this->system_stats_file('/etc/os-release', "\n", "=");
      
         // Distro Name
         if ( file_exists('/boot/dietpi/.version') ) {
             
         $system['distro_name'] = 'DietPi OS';
         
            
            if ( is_readable('/boot/dietpi/.version') ) {
          
            $dietpi['dietpi_info'] = $this->system_stats_file('/boot/dietpi/.version', "\n", "=");
            
                // Distro Version
                if ( isset($dietpi['dietpi_info']['g_dietpi_version_core']) ) {
                    
                $system['distro_version'] = $dietpi['dietpi_info']['g_dietpi_version_core'] . ( isset($dietpi['dietpi_info']['g_dietpi_version_sub']) ? '.' . $dietpi['dietpi_info']['g_dietpi_version_sub'] . '.' . $dietpi['dietpi_info']['g_dietpi_version_rc'] : '' );
                
                $system['distro_version'] = $system['distro_version'] . ' ' . ( isset($distro['distro_info']['name']) ? '['.$distro['distro_info']['name'].' '.$distro['distro_info']['version'].']' : '' );
                
                }
          
            }
      
         }
         elseif ( file_exists('/usr/bin/raspi-config') ) {
         $system['distro_name'] = 'RaspberryPi OS';
         $system['distro_version'] = ( isset($distro['distro_info']['name']) ? '['.$distro['distro_info']['name'].' '.$distro['distro_info']['version'].']' : '' );
         }
         elseif ( file_exists('/usr/bin/armbian-config') ) {
         $system['distro_name'] = 'Armbian';
         $system['distro_version'] = ( isset($distro['distro_info']['name']) ? '['.$distro['distro_info']['name'].' '.$distro['distro_info']['version'].']' : '' );
         }
         elseif ( $distro['distro_info']['name'] ) {
         $system['distro_name'] = $distro['distro_info']['name'];
         }
         
         
         // Distro Version
         if ( isset($distro['distro_info']['version']) && !isset($system['distro_version']) ) {
         $system['distro_version'] = $distro['distro_info']['version'];
         }
         
      
      }
      elseif ( PHP_OS_FAMILY == 'Windows' ) {
          
          if ( preg_match("/windows 11/i", $system['operating_system']) ) {
          $win_ver = '11';
          }
          elseif ( preg_match("/windows 10/i", $system['operating_system']) ) {
          $win_ver = '10';
          }
          elseif ( preg_match("/windows server/i", $system['operating_system']) ) {
          $win_ver = 'Server';
          }
          elseif ( preg_match("/windows nt/i", $system['operating_system']) ) {
          $win_ver = 'NT';
          }
          
      $system['distro_name'] = 'Windows' . ( isset($win_ver) && trim($win_ver) != '' ? ' ' . $win_ver : '' );
      
      $win_os = getenv("OS");
      
      $system['distro_version'] = ( isset($win_os) && trim($win_os) != '' ? '['.$win_os.']' : '' );
         
      }
      
      
      // CPU stats on Linux
      if ( is_readable('/proc/cpuinfo') ) {
      
      $cpu['cpu_info'] = $this->system_stats_file('/proc/cpuinfo', "\n", ":");
      
      
         if ( $cpu['cpu_info']['model'] ) {
         $system['model'] = $cpu['cpu_info']['model'];
         }
         
         if ( $cpu['cpu_info']['hardware'] ) {
         $system['hardware'] = $cpu['cpu_info']['hardware'];
         }
         
         if ( $cpu['cpu_info']['model_name'] ) {
         $system['model_name'] = $cpu['cpu_info']['model_name'];
         }
      
         if ( $cpu['cpu_info']['processor'] ) {
         $system['cpu_threads'] = $cpu['cpu_info']['processor'] + 1; // (overwritten until last in loop, starts at 0)
         }
         elseif ( $cpu['cpu_info']['siblings'] ) {
         $system['cpu_threads'] = $cpu['cpu_info']['siblings'];
         }
         else {
         $system['cpu_threads'] = 1; // Presume only one, if nothing parsed
         }
         
      
      }
      // CPU core count on Windows
      elseif ( PHP_OS_FAMILY == 'Windows' ) {
      
      $win_cpu_model = getenv("PROCESSOR_IDENTIFIER");
      
         if ( isset($win_cpu_model) && trim($win_cpu_model) != '' ) {
         $system['model_name'] = $win_cpu_model;
         }
      
      $win_cpu_cores = getenv("NUMBER_OF_PROCESSORS") + 0;
      
         if ( $win_cpu_cores > 0 ) {
         $system['cpu_threads'] = $win_cpu_cores;
         }
         else {
         $system['cpu_threads'] = 1; // Presume only one, if nothing parsed
         }
      
      }
   
   
      
      // Uptime stats
      if ( is_readable('/proc/uptime') ) {
         
      $uptime_info = @file_get_contents('/proc/uptime');
      
      $num   = floatval($uptime_info);
      $secs  = fmod($num, 60); $num = (int)($num / 60);
      $mins  = $num % 60;      $num = (int)($num / 60);
      $hours = $num % 24;      $num = (int)($num / 24);
      $days  = $num;
      
      $system['uptime'] = $days . ' days, ' . $hours . ' hours, ' . $mins . ' minutes, ' . round($secs) . ' seconds';
      
      }
      
      
      
      // System loads
      if ( function_exists('sys_getloadavg') ) {
          
          $loop = 1;
          foreach ( sys_getloadavg() as $load ) {
             
             if ( $loop == 1 ) {
             $time = 1;
             }
             elseif ( $loop == 2 ) {
             $time = 5;
             }
             elseif ( $loop == 3 ) {
             $time = 15;
             }
             
          $system['system_load'] .= $load . ' (' . $time . ' min avg) ';
          $loop = $loop + 1;
          }
      
      $system['system_load'] = trim($system['system_load']);
      
      }
      
     
      // Temperature stats
      if ( is_readable('/sys/class/thermal/thermal_zone0/temp') ) {
      $temp_info = @file_get_contents('/sys/class/thermal/thermal_zone0/temp');
      $system['system_temp'] = round( ($temp_info/1000) , 2) . '° Celsius';
      }
      elseif ( is_readable('/sys/class/thermal/thermal_zone1/temp') ) {
      $temp_info = @file_get_contents('/sys/class/thermal/thermal_zone1/temp');
      $system['system_temp'] = round( ($temp_info/1000) , 2) . '° Celsius';
      }
      elseif ( is_readable('/sys/class/thermal/thermal_zone2/temp') ) {
      $temp_info = @file_get_contents('/sys/class/thermal/thermal_zone2/temp');
      $system['system_temp'] = round( ($temp_info/1000) , 2) . '° Celsius';
      }
      
   
   
      // Memory stats
      if ( is_readable('/proc/meminfo') ) {
         
      $data = explode("\n", file_get_contents("/proc/meminfo"));
       
       
         foreach ($data as $line) {
           list($key, $val) = explode(":", $line);
           $ram['ram_'.strtolower($key)] = trim($val);
         }
         
      
      $memory_applications_mb = $this->in_megabytes($ram['ram_memtotal'])['in_megs'] - $this->in_megabytes($ram['ram_memfree'])['in_megs'] - $this->in_megabytes($ram['ram_buffers'])['in_megs'] - $this->in_megabytes($ram['ram_cached'])['in_megs'];
      
      $system_memory_total_mb = $this->in_megabytes($ram['ram_memtotal'])['in_megs'];
      
      $memory_applications_percent = abs( ( $memory_applications_mb - $system_memory_total_mb ) / abs($system_memory_total_mb) * 100 );
      $memory_applications_percent = round( 100 - $memory_applications_percent, 2);
      
         
      $system['memory_total'] = $ram['ram_memtotal'];
      
      $system['memory_buffers'] = $ram['ram_buffers'];
      
      $system['memory_cached'] = $ram['ram_cached'];
      
      $system['memory_free'] = $ram['ram_memfree'];
      
      $system['memory_swap'] = $ram['ram_swapcached'];
      
      $system['memory_used_megabytes'] = $memory_applications_mb;
      
      $system['memory_used_percent'] = $memory_applications_percent;
   
      }
   
   
   // Free space on this partition
   $system['free_partition_space'] = $this->conv_bytes( disk_free_space($ct['base_dir']) , 3);
   
   
   $system['portfolio_cookies'] = $this->all_cookies_size();
   
   
      // Portfolio cache size (cached for efficiency)
      if ( file_exists($ct['base_dir'] . '/cache/vars/cache_size.dat') ) {
      $portfolio_cache = trim( file_get_contents($ct['base_dir'] . '/cache/vars/cache_size.dat') );
      $system['portfolio_cache'] = ( $ct['var']->num_to_str($portfolio_cache) > 0 ? $portfolio_cache : 0 );
      }
   
   
      // Server stats
      if ( is_readable('/proc/stat') ) {
      $server_info = @file_get_contents('/proc/stat');
      
      $raw_server_info_array = explode("\n", $server_info);
      
         foreach ( $raw_server_info_array as $server_info_field ) {
         
            if ( isset($server_info_field) && trim($server_info_field) != '' ) {
               
            $server_info_field = preg_replace('/\s/', ':', $server_info_field, 1);
               
            $temp_array = explode(":", $server_info_field);
               
               $loop = 0;
               foreach ( $temp_array as $key => $val ) {
               $trimmed_val = ( $loop < 1 ? strtolower(trim($val)) : trim($val) );
               $trimmed_val = ( $loop < 1 ? preg_replace('/\s/', '_', $trimmed_val) : $trimmed_val );
               $temp_array_cleaned[$key] = $trimmed_val;
               $loop = $loop + 1;
               }
            
            $server_info_array[ $temp_array_cleaned[0] ] = $temp_array_cleaned[1];
            }
         
         }
      
      $system['server_info'] = $server_info_array;
      
      }
      
      
      if ( isset($_ENV['SERVER_SOFTWARE']) && trim($_ENV['SERVER_SOFTWARE']) != '' ) {
      $server_soft = $_ENV['SERVER_SOFTWARE'];
      }
      elseif ( isset($_SERVER['SERVER_SOFTWARE']) && trim($_SERVER['SERVER_SOFTWARE']) != '' ) {
      $server_soft = $_SERVER['SERVER_SOFTWARE'];
      }
      else {
      $server_soft = '';
      }

      
      if ( !preg_match("/".$this->regex_compat_path( phpversion() )."/i", $server_soft) ) {
      $server_soft .= ' - PHP/' . phpversion();
      }
   
   
   // Software
   $system['software'] = ( isset($server_soft) && $server_soft != '' ? $server_soft . ' - ' : '' ) . 'Open_Crypto_Tracker/' . $ct['app_version'];
      
   
   return $system;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>