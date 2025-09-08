<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


class ct_gen {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;

var $ct_array = array();
   
   
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
   
   
   // LIMIT string to a max length
   function truncate_str($var, $max) {
        
     if ( strlen($var) > $max ) {
     return substr($var, 0, $max);
     }
     else {
     return $var;
     }
     
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function all_cookies_size() {
   $cookies = isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : null;
   return mb_strlen($cookies);
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function valid_domain($url) {
   return (preg_match("/^([a-zd](-*[a-zd])*)(.([a-zd](-*[a-zd])*))*$/i", $url) //valid characters check
   && preg_match("/^.{1,253}$/", $url) //overall length check
   && preg_match("/^[^.]{1,63}(.[^.]{1,63})*$/", $url) ); //length of every label
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
   
   
   function safe_name($var) {
        
   // Replace ALL symbols with an underscore (for filesystem compatibility, as filenames etc)
   $var = preg_replace('/[^\p{L}\p{N}\s]/u', "_", $var);
   
   // MAX 30 characters, to avoid going over the WINDOWS path character limit
   // https://learn.microsoft.com/en-us/windows/win32/fileio/maximum-file-path-limitation
   return $this->truncate_str($var, 30); 

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
   
   
   function checkdns($host) {
      
      if ( function_exists('checkdnsrr')  ) {
      return checkdnsrr($host, 'A');
      }
      else {
      return true; // Skip check, as function does NOT exist
      }
      
   }
   
   
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

   
   function auto_correct_market_id($var, $exchange) {

   global $ct;                                      
                                      
       // Auto-correct, if we know we ABSOLUTELY MUST USE ALL UPPER / LOWER CASE
       // (important to auto-correct early here, as we are setting the ID in the results)
       if ( in_array($exchange, $ct['dev']['markets_uppercase_search']) ) {
       $var = strtoupper($var);
       }
       elseif ( in_array($exchange, $ct['dev']['markets_lowercase_search']) ) {
       $var = strtolower($var);
       }
       
   return $var;

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
   
   
   function config_state_synced() {
       
   global $ct, $check_default_ct_conf;
   
      if ( $ct['admin_area_sec_level'] == 'high' ) {
      
         if ( $check_default_ct_conf == md5(serialize($ct['default_conf'])) ) {
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

   
   function ajax_wizard_back_button($ajax_id, $secured=true) {

     if ( isset($_GET['step']) && $_GET['step'] > 1 ) {
     ?>
     <a style='font-weight: bold;' class='blue input_margins' href='javascript: ct_ajax_load("type=<?=$_GET['type']?>&step=<?=($_GET['step'] - 1)?>", "<?=$ajax_id?>", "previous step", prev_post_data, <?=( $secured ? 'true' : 'false' )?>);' title='Go back to the previous step in this wizard. (previous CHOICES are only saved for the LAST PREVIOUS STEP)'>Go Back To Previous Step</a>
     
     <script>
     
     var prev_post_data = <?php echo json_encode($_POST); ?>;
     	                          
     </script>
     
     <?php
     }

   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////

   
   function smtp_server_online() {

   global $ct, $smtp_vars;
   
      // Set connection type based on port number
      if ( $smtp_vars['cfg_port'] == 465 ) {
      $connection_type = 'ssl';
      }
      // Everything else is over TCP (including TCP on port 587 FOR TLS ENCRYPTION)
      else {
      $connection_type = 'tcp';
      }
   
   return $this->server_online($smtp_vars['cfg_server'], $smtp_vars['cfg_port'], $connection_type);

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
      return "no mail server records found for domain '" . $ct['sec']->obfusc_str($domain, 3) . "' [obfuscated]";
      }
      else {
      return "valid";
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function dir_struct($path) {
   
   global $ct;

   
      // If path does not exist
      if ( !is_dir($path) ) {
           
      $ct['dir_creation'] = true; // Flag global, indicating directory creation was attempted this runtime
      
         // Run cache compatibility on certain PHP setups
         if ( !$ct['http_runtime_user'] || in_array($ct['http_runtime_user'], $ct['possible_http_users']) ) {
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
      $ct['sec']->chmod_path($path, $ct['dev']['chmod_cache_dir']);
      }
      
      
   return true; // If we made it this far, we can safely return true
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function text_email($str) {
   
   global $ct;
   
   $str = array_map( "trim", explode("||", $str) );
   
   $phone_number = $ct['var']->strip_non_alpha($str[0]);
   
   $network_name = $str[1];
   
   $network_data = $ct['var']->begins_with_in_array($ct['conf']['mobile_network']['text_gateways'], $network_name)['key'];
   
      // Set text domain
      if ( $network_data >= 0 ) {
           
      $network_data_array = array_map( "trim", explode("||", $ct['conf']['mobile_network']['text_gateways'][$network_data]) );
      
          if ( isset($network_data_array[1]) && $network_data_array[1] != '' && isset($phone_number) && trim($phone_number) != '' ) {
          return trim($phone_number) . '@' . $network_data_array[1]; // Return formatted texting email address
          }
          else {
          return false;
          }
      
      }
      else {
      return false;
      }
   
   
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
   
   global $ct;
       
       if ( $force_show || !$alt_mode && $ct['admin_area_2fa'] != 'off' || $alt_mode && $ct['admin_area_2fa'] == $alt_mode ) {
	  ?>
	  
	  <div class='bitcoin_dotted' style='margin-top: 2em; margin-bottom: 2em;'>
	  
	  <p id='notice_2fa_code_<?=$ct['count_2fa_fields']?>' class='hidden red red_dotted' style='font-weight: bold;'><?=$ct['check_2fa_error']?>.</p>
	  
	  <p>
	  
	  <span class='<?=( $force_show != false ? 'red' : 'bitcoin' )?>' style='font-weight: bold;'>Enter 2FA Code (from phone app):</span><br />
	  
	  <input class='2fa_code_input' style='margin-top: 0.5em;' type='text' id='2fa_code_<?=$ct['count_2fa_fields']?>' name='2fa_code' value='' size='10' />
	  
	  </p>
	  
	  <input class='2fa_code_id_input' type='hidden' name='2fa_code_id' value='2fa_code_<?=$ct['count_2fa_fields']?>' />
	  
	  </div>
	  
	  <?php
	  $ct['count_2fa_fields'] = $ct['count_2fa_fields'] + 1;
	  }
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function pretty_app_uri($include_host=false, $section_only=false, $url_check=false) {
        
   global $ct;
   
   $parsed_params = array();
   
      
      // IF system path, get path to parse
      if (
      substr($url_check, 0, 1) == '/'
      && substr($url_check, 0, 2) != '//'
      && !preg_match('/\?/i', $url_check)
      ) {
      $path_parts = pathinfo($url_check);
      }
   
      
      // If passing in a URL, and it's NOT the app's base URL, just set path / query data
      if (
      $url_check != false
      && $url_check != ''
      && !is_array($path_parts)
      && !preg_match('/' . $ct['gen']->regex_compat_path($ct['base_url']) . '/i', $url_check)
      ) {
      
      $url_check_path = preg_replace( '/\?(.*)/i', '', $url_check);
      
      $url_check_query = preg_replace( '/(.*)\?/i', '', $url_check);
      
      }
      // Otherwise IF populated, truncate path down to app main directory (for UX, in access stats UI)
      elseif ( $url_check != false && $url_check != '' ) {
           
           
           // IF system path
           if ( is_array($path_parts) ) {
           
           $url_check_path = preg_replace( '/(.*)' . $ct['gen']->regex_compat_path($path_parts['dirname']) . '\//i', '/', $url_check);
     
           $url_check_query = ''; // MUST be set to something, so blank it out
           
           }
           // Anything else
           else {
           
           $url_check_path = parse_url($url_check, PHP_URL_PATH);
     
           $url_check_query = parse_url($url_check, PHP_URL_QUERY);
           
           $url_check = preg_replace( '/(.*)' . $ct['gen']->regex_compat_path($ct['base_url']) . '/i', '', $url_check);
           
           }

           
      }
      // Otherwise just return blank data, IF blank data was passed in
      elseif ( $url_check != false && $url_check == '' ) {
      return $url_check;
      }
      
      
      // Set condition vars...
      
      if ( $url_check ) {
      
      $query_params = explode('&', $url_check_query);
      
      
          foreach ( $query_params as $param ) {
          
          $param_array = explode('=', $param);
          
          $parsed_params[ $param_array[0] ] = $param_array[1];
          
          }
      
      
      $script_name = ( $url_check_path != '' ? $url_check_path : '/' );
      
      $server_uri = ( $url_check_query != '' ? $url_check_path . '?' . $url_check_query : $url_check_path );
      
      }
      else {
           
      $parsed_params = $_GET;

      $script_name = $_SERVER['SCRIPT_NAME'];
      
      $server_uri = $_SERVER['REQUEST_URI'];

      }

      
      // Render the pretty app path (for access stats UI, etc)
      
      if ( $include_host ) {
           
           if( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ) {
           $url = "https://";
           }
           else {
           $url = "http://"; 
           }

      $url .= $_SERVER['HTTP_HOST'];   

      }
      
      
      if ( !$section_only ) {
      $url .= $server_uri;
      }
      elseif ( $parsed_params['section'] ) {
      $url .= $script_name . '?section=' . $parsed_params['section'];
      }
      elseif ( $parsed_params['subsection'] ) {
      $url .= $script_name . '?parent=' . $parsed_params['parent'] . '&subsection=' . $parsed_params['subsection'];
      }
      elseif ( $parsed_params['plugin'] ) {
      $url .= $script_name . '?plugin=' . $parsed_params['plugin'];
      }
      elseif ( $parsed_params['type'] ) {
      $url .= $script_name . '?type=' . $parsed_params['type'] . ( $parsed_params['mode'] ? '&mode=' . $parsed_params['mode'] : '' );
      }
      else {
      $url .= $script_name;
      }
       
   
   return $url;
     
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////

   
   function search_mode($haystack, $needle, $mode='stristr', $strip='symbols,space') {

   global $ct;
   
   
       // If haystack equals false, return false
       if ( $haystack == false ) {
       return false;
       }
   
       
       // Remove everything NOT alphanumeric
       if ( stristr($strip, 'symbols') ) {
       $haystack = preg_replace("/[^0-9a-zA-Z]+/i", "", $haystack);
       $needle = preg_replace("/[^0-9a-zA-Z]+/i", "", $needle);
       }
       
       
       // Trim any whitespace off ends
       if ( stristr($strip, 'space') ) {
       $haystack = trim($haystack);
       $needle = trim($needle);
       }
       
       
       // 'strict_search' MUST ALWAYS OVERRIDE stristr!
       // Case insensitive EXACT MATCH
       if ( $_POST['strict_search'] == 'yes' ) {
           
           if ( strtolower($haystack) == strtolower($needle) ) {
           return true;
           }
           else {
           return false;
           }
           
       }
       // Case insensitive PARTIAL MATCH
       elseif ( $mode == 'stristr' ) {
       return stristr($haystack, $needle);
       }
   

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
      return $array[ ( sizeof($array) - 2 ) ] . '.' . $array[ ( sizeof($array) - 1 ) ];
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
   
   
   function display_xml_error($error) {

      switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "XML Warning $error->code: ";
            break;
         case LIBXML_ERR_ERROR:
            $return .= "XML Error $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "XML Fatal Error $error->code: ";
            break;
      }

   $return .= $error->message;

   $return = preg_replace("/\\n/i", "; ", $return); // Replace linebreaks with semicolons
          
   return $return;
   
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
   
   
   function telegram_msg($full_message) {
   
   // Using 3rd party Telegram class, initiated already as $ct['telegram_connect']
   global $ct;
               
   $message_size = strlen($full_message);

                  
      // If telegram message bytes is over 4096, it will fail to send, so we split it into multiple messages
      // https://developers.cm.com/messaging/docs/telegram
      if ( $ct['telegram_connect'] && $message_size > 4000 ) { // Leave some wiggle room
           
      $split_messages = str_split($full_message, 4000); // Leave some wiggle room
          
          
          $count = 0;
          foreach($split_messages as $message){
               
               // Throttle a bit, so we don't piss off the API server
               if ( $count > 0 ) {
               sleep(1); 
               }

          $message_sent = $ct['telegram_connect']->send->chat($ct['telegram_user_data']['message']['chat']['id'])->text($message)->send();
               
               // If ANY message sending fails, abort / return false
               if ( !$message_sent ) {
               return false;
               }
          
          $count = $count + 1;
          
          }

          
      return true; // If we made it this far, all messages were sent OK
           
      }
      // If NOT over limit
      elseif ( $ct['telegram_connect'] ) {
      return $ct['telegram_connect']->send->chat($ct['telegram_user_data']['message']['chat']['id'])->text($full_message)->send();
      }

   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function sort_log($log) {
        
   global $ct;
       
      
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
          if ( is_array($sortable_array) ) { 
          $ct['sort_by_nested'] = 'root=>timestamp';
          usort($sortable_array, array($ct['var'], 'usort_asc') );
          $ct['sort_by_nested'] = false; // RESET
          }
       
          // Return to normal string, after sorting logs by timestamp
          foreach( $sortable_array as $val ) {
          $val['entry'] = preg_replace("/\\n/i", "; ", $val['entry']); // Replace linebreaks with semicolons
          $result .= $val['entry'] . "\n";
          }
       
      return $result;
      }
      else {
      return false;
      }
      
   
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
       
   global $ct;
   
   $result = array();
   
   // MUST BE PASSED AS ABSOLUTE, use floatval to assure it's cleaned up
   $num = abs( floatval( $ct['var']->num_to_str($num) ) );
   
      // Unit
      if ( $mode == 'u' ) {
          
      $result['max_dec'] = $this->dyn_max_decimals($num, $type);
      
      $min_val = ( $type == 'fiat' ? $ct['min_fiat_val_test'] : $ct['min_crypto_val_test'] );
   
          if ( $num < $min_val ) {
          $result['min_dec'] = 0;
          }
          elseif ( $ct['conf']['currency']['price_rounding_fixed_decimals'] == 'on' ) {
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
          
          if ( $num >= 1000 ) {
          $result['max_dec'] = 0;
          $result['min_dec'] = 0;
          }
          elseif ( $num >= 100 ) {
          $result['max_dec'] = 1;
          $result['min_dec'] = 1;
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
   
   
   function connect_test($server_and_port, $mode='ping', $transport='tcp://', $tcp_error=false) {
        
   global $ct;
   
   $server_and_port = trim($server_and_port);
        
   $check_connect = explode(':', $server_and_port); // Separate IP and port
   
       if ( $mode == 'ping' ) {
       
       $con = @fsockopen('tcp://' . $check_connect[0], $check_connect[1], $errno, $errstr, 5);
        
            if ( $con ) {
            fclose($con); // Close the socket handle
            return array('status' => 'ok');
            }
            elseif ( $transport == 'tcp://' ) {
            return $this->connect_test($server_and_port, 'ping', 'ssl://', '['.$transport.'] error ' . $errno . ': ' . $errstr);
            }
            else {
            return array('status' => $tcp_error . '; ['.$transport.'] error ' . $errno . ': ' . $errstr);
            }
       
       }
       elseif ( $mode == 'proxy' ) {
            
       $response = @$ct['cache']->ext_data('proxy-check', 'https://api.myip.com/', 0, '', '', $server_and_port);

       $data = json_decode($response, true);
      
           if ( is_array($data) && sizeof($data) > 0 ) {
  
           $ip_port = explode(':', $server_and_port);
            
           $ip = $ip_port[0];
          
              // Look for the IP in the response
              if ( strstr($data['ip'], $ip) == false ) {
              return array('status' => 'remote address mismatch [detected as: ' . $data['ip'] . ']');
              }
              else {
              return array('status' => 'ok');
              }
         
           }
           else {
           return array('status' => 'proxy failed, no endpoint connection');
           }
         
       }
       
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

   
   function array_debugging($array, $assoc_detailed=false) {

   global $ct;                                      
     
     if ( !is_array($array) ) {
     return false;
     }
     elseif ( $assoc_detailed && $ct['var']->has_string_keys($array) ) {
          
          foreach ( $array as $key => $val ) {
          ?>
          
          <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%;'>
          
               <?php
               if ( is_array($val) ) {
               ?>
               <?=$key?> (<?=sizeof($val)?> value[s]):
               <?php
               }
               else {
               ?>
               <?=$key?>:
               <?php
               }
               ?>
          
          <?=print_r($val, true)?>
          
          </code></pre>
          
          <br /><br /><br />
          
          <?php
          }
     
     }
     else {
     ?>
          
     <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%;'>
          
     <?=print_r($array, true)?>
          
     </code></pre>
          
     <br /><br /><br />
          
     <?php
     }


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
      $border_highlight = '_blue';
      $text_class = 'red';
      }
      
   ?>
   <span class='start_page_menu<?=$border_highlight?>'> 
      
      <span class='blue' style='font-weight: bold;'>Start Page:</span> <select class='browser-default custom-select' title='Sets alternate start pages, and saves your scroll position on alternate start pages during reloads.' class='<?=$text_class?>' onchange='
      
         if ( this.value == "index.php?start_page=<?=$page?>" ) {
         var anchor = "#<?=$page?>";
         }
         else {
         var anchor = "";
         //localStorage.setItem(scroll_position_storage, 0); // May be bad UX, with new nav system, disabled for now
         }
      
      // This start page method saves portfolio data during the session, even without cookie data enabled
      var set_action = this.value + anchor;
      set_target_action("user_area_settings", "_self", set_action);
      $("#user_area_settings").submit();
      
      '>
         <option value='index.php#<?=$page?>'> Last-Visited </option>
         <?php
         if ( isset($_GET['start_page']) && $_GET['start_page'] != '' && $_GET['start_page'] != $page ) {
         $another_set = 1;
         ?>
         <option value='index.php?start_page=<?=$_GET['start_page']?>' selected > <?=ucwords( preg_replace("/_/i", " ", $_GET['start_page']) )?> </option>
         <?php
         }
         ?>
         <option value='index.php?start_page=<?=$page?>' <?=( $_GET['start_page'] == $page ? 'selected' : '' )?> > <?=ucwords( preg_replace("/_/i", " ", $page) )?> </option>
      </select> 
      
   </span>
   
   <?php
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function system_warning_log($type) {
   
   global $ct;
   
   
	  // With offset, to try keeping daily / hourly recurrences at same exact runtime (instead of moving up the runtime daily / hourly)
      if ( $ct['cache']->update_cache($ct['base_dir'] . '/cache/events/system/warning-' . $type . '.dat', ($ct['system_warnings_cron_interval'][$type] * 60) + $ct['dev']['tasks_time_offset'] ) == true ) {
          
      $this->log('system_error', $ct['system_warnings'][$type]);
      
          if ( isset($ct['system_info']['distro_name']) ) {
          $system_info_summary = "\n\nApp Server System Info:\n\n" . $ct['system_info']['distro_name'] . ( isset($ct['system_info']['distro_version']) ? ' ' . $ct['system_info']['distro_version'] : '' );
          }
      
      $email_msg = 'Open Crypto Tracker detected an app server issue: ' . $ct['system_warnings'][$type] . '. (warning thresholds are adjustable in the Admin Config Power User section) ' . $system_info_summary;
               
      // Were're just adding a human-readable timestamp to smart home (audio) alerts
      $notifyme_msg = $email_msg . ' Timestamp: ' . $this->time_date_format($ct['conf']['gen']['local_time_offset'], 'pretty_time') . '.';
      
      $text_msg = 'Open Crypto Tracker app server issue: ' . $ct['system_warnings'][$type] . '.';
               
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

   
   function table_pager_nav($pager_id, $custom_class=false, $pager_options=false) {

   global $ct;                                      
   
   
       if ( !$pager_options || !is_array($pager_options) || sizeof($pager_options) < 1 ) {
       
       // Default pager options
       $pager_options = array(
                           5,
                           10,
                           25,
                           50,
                           );
       
       }

   
   ?>
               <!-- table_pager -->
               <div class="table_pager table_pager_<?=$pager_id?> <?=( $custom_class ? $custom_class : '' )?>">

               	<span class="pagedisplay"></span> 
               	
               	<br /><br />
					&nbsp;<span class="blue">Show Per Page:</span>
					
               	<span class="left choose_pp">
               	
               	     <?php
               	     
               	     $first_rendered = false;

               	     foreach ( $pager_options as $per_page ) {
               	          
               	          if ( $first_rendered ) {
               	          echo ' | ';
               	          }
               	          
               	     ?>
               	     
					<a href="#" data-track='<?=$per_page?>'><?=$per_page?></a>
					
               	     <?php

               	     $first_rendered = true;

               	     }
               	     ?>
					
				</span>
				
               	<br /><br />
				<span class="right">

					&nbsp;<span class="blue">View Page:</span> <span class="prev">
						Prev
					</span>&nbsp;

					<span class="pagecount"></span>
					
					&nbsp;<span class="next">Next
					</span>
					
				</span>

               </div>
   <?php
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function refresh_plugins_list() {
        
   global $ct;
   
   $plugin_base = $ct['base_dir'] . '/plugins/';
   
   $dir = new DirectoryIterator($plugin_base);
   
      
      // Add any plugins not already in plugin list (default to off)
      foreach ($dir as $file_info) {
           
         if ( $file_info->isDir() && !$file_info->isDot() ) {
              
             if (
             file_exists($plugin_base . $file_info->getFilename() . '/plug-conf.php')
             && file_exists($plugin_base . $file_info->getFilename() . '/plug-lib/plug-init.php')
             ) {
                  
               
               // We also want to set any unset DEFAULT config
               // (does NOT need $ct['update_config'] flag)
               if ( !isset($ct['default_conf']['plugins']['plugin_status'][ $file_info->getFilename() ]) ) {
               $ct['default_conf']['plugins']['plugin_status'][ $file_info->getFilename() ] = 'off'; // Defaults to off
               }
               
             
               if ( !isset($ct['conf']['plugins']['plugin_status'][ $file_info->getFilename() ]) ) {
                    
               $ct['conf']['plugins']['plugin_status'][ $file_info->getFilename() ] = 'off'; // Defaults to off

	    
	               // If no reset / high security mode 
	               // (high security mode will auto-trigger a reset on plugin changes, FURTHER ALONG IN THE LOGIC)
     	          if ( $ct['admin_area_sec_level'] != 'high' && !$ct['reset_config'] ) {
     	               
         	          $ct['update_config'] = true;

     	               
     	               if ( $ct['conf']['power']['debug_mode'] == 'conf_telemetry' ) {
         	               $ct['gen']->log('conf_debug', 'plugin "'.$file_info->getFilename().'" ADDED, updating CACHED ct_conf');
         	               }

         	          
     	          }
               
               
               }
               
             
             }
             
         }
         
      }
      
      
      // Remove any plugins that no longer exist / do not have proper file structure
      // MAIN CONFIG
      foreach ( $ct['conf']['plugins']['plugin_status'] as $key => $unused ) {
           
           
         if (
         !file_exists($plugin_base . $key . '/plug-conf.php')
         || !file_exists($plugin_base . $key . '/plug-lib/plug-init.php')
         ) {
         
	    // Just unset EVERYTHING to be safe,
	    // as unset() will NOT throw an error if the var does not exist
         unset($ct['default_conf']['plugins']['plugin_status'][$key]);
         unset($ct['conf']['plugins']['plugin_status'][$key]);
	    unset($ct['conf']['plug_conf'][$key]);
	    
	    
	         // If no reset / high security mode 
	         // (high security mode will auto-trigger a reset on plugin changes, FURTHER ALONG IN THE LOGIC)
	         if ( $ct['admin_area_sec_level'] != 'high' && !$ct['reset_config'] ) {
	              
    	         $ct['update_config'] = true;

    	         
    	            if ( $ct['conf']['power']['debug_mode'] == 'conf_telemetry' ) {
    	            $ct['gen']->log('conf_debug', 'plugin "'.$key.'" REMOVED, updating CACHED ct_conf');
    	            }

    	         
	         }

	    
         }
         
      
      }
      
      
      // Remove any plugins that no longer exist / do not have proper file structure
      // DEFAULT CONFIG (does NOT need $ct['update_config'] flag)
      foreach ( $ct['default_conf']['plugins']['plugin_status'] as $key => $unused ) {
           
         if (
         !file_exists($plugin_base . $key . '/plug-conf.php')
         || !file_exists($plugin_base . $key . '/plug-lib/plug-init.php')
         ) {
         unset($ct['default_conf']['plugins']['plugin_status'][$key]);
	    unset($ct['default_conf']['plug_conf'][$key]);
         }
      
      }
   
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////

   
   function version_compare($base_version, $compared_version) {
        
   global $ct;
   
   $results = array();
   
   // Defaults
   $results['base_diff'] = 0;
   $results['new_bug_fixes'] = 0;
	
   // Parse BASE version
   $base_version_array = explode(".", $base_version);
	
   $base_major_minor = $ct['var']->num_to_str($base_version_array[0] . '.' . $base_version_array[1]);
	
   $base_bug_fixes = $ct['var']->num_to_str($base_version_array[2]);
   
     
        // In bug fix versioning, remove any leading zeros if more than one character
        if ( substr($base_bug_fixes, 0, 1) == '0' && strlen($base_bug_fixes) > 1 ) {
        $base_bug_fixes = substr($base_bug_fixes, 1);
        }

   
   // Parse COMPARED version
   $compared_version_array = explode(".", $compared_version);
	
   $compared_major_minor = $ct['var']->num_to_str($compared_version_array[0] . '.' . $compared_version_array[1]);
	
   $compared_bug_fixes = $ct['var']->num_to_str($compared_version_array[2]);
   
     
        // In bug fix versioning, remove any leading zeros if more than one character
        if ( substr($compared_bug_fixes, 0, 1) == '0' && strlen($compared_bug_fixes) > 1 ) {
        $compared_bug_fixes = substr($compared_bug_fixes, 1);
        }

   
        // If BASE or COMPARED version is blank (not cached yet, etc),
        // we flag it (and add any needed edge-case logic external to this function)
        if ( !is_numeric($base_major_minor) || !is_numeric($compared_major_minor) ) {
        $results['base_diff'] = false;
        }
        // If the BASE release is an OLDER version than COMPARED release
        elseif (
        $base_major_minor < $compared_major_minor
        || $base_major_minor == $compared_major_minor && $base_bug_fixes < $compared_bug_fixes
        ) {
        $results['base_diff'] = -1;
        $results['new_bug_fixes'] = $compared_bug_fixes;
        }
        // If the BASE release is a NEWER version than COMPARED release
        elseif (
        $base_major_minor > $compared_major_minor
        || $base_major_minor == $compared_major_minor && $base_bug_fixes > $compared_bug_fixes
        ) {
        $results['base_diff'] = 1;
        }
        

   $debugging = array(
                      'base_version' => $base_version,
                      'compared_version' => $compared_version,
                      'base_diff' => $results['base_diff'],
                     );
                     
   //var_dump($debugging);
   
   return $results;
   
   }
    
    
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function valid_csv_import_row($csv_row) {
      
   global $ct;
   
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
      if ( $csv_row[1] >= $ct['min_crypto_val_test'] )  {
      return $csv_row;
      }
      else {
      return false;
      }
      
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   

   function dyn_max_decimals($price_raw, $type) {
       
   global $ct;
   
   $price_raw = abs($price_raw); // Assure no negative number used
   
        
        if ( $ct['conf']['currency']['price_rounding_percent'] == 'one' ) {
        $x = 1;
        }
        else if ( $ct['conf']['currency']['price_rounding_percent'] == 'tenth' ) {
        $x = 0.1;
        }
        else if ( $ct['conf']['currency']['price_rounding_percent'] == 'hundredth' ) {
        $x = 0.01;
        }
        else if ( $ct['conf']['currency']['price_rounding_percent'] == 'thousandth' ) {
        $x = 0.001;
        }
        
        
    $unit_percent = $ct['var']->num_to_str( ($price_raw / 100) * $x );
        
    
        if ( $type == 'fiat' ) {
             
        $track_target = preg_replace("/1/", "5", $ct['min_fiat_val_test']); // Set to 0.XXXXX5 instead of 0.XXXXX1
        
        
             $loop = 0;
             $track_decimals = $ct['conf']['currency']['currency_decimals_max'];
             while ( !isset($decimals) && $loop < $ct['conf']['currency']['currency_decimals_max'] ) {

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
             if ( $decimals > $ct['conf']['currency']['currency_decimals_max'] ) {
             return $ct['conf']['currency']['currency_decimals_max'];
             }
             else {
             return $decimals;
             }
        
        
        }
        else if ( $type == 'crypto' ) {
             
        $track_target = preg_replace("/1/", "5", $ct['min_crypto_val_test']); // Set to 0.XXXXX5 instead of 0.XXXXX1
        
        
             $loop = 0;
             $track_decimals = $ct['conf']['currency']['crypto_decimals_max'];
             while ( !isset($decimals) && $loop < $ct['conf']['currency']['crypto_decimals_max'] ) {

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
             if ( $decimals > $ct['conf']['currency']['crypto_decimals_max'] ) {
             return $ct['conf']['currency']['crypto_decimals_max'];
             }
             else {
             return $decimals;
             }


        }
        
    
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function log($log_type, $log_msg, $verbose_tracing=false, $hashcheck=false, $overwrite=false) {
   
   global $ct, $is_iframe;
   
   // Obfuscate any sensitive data
   $log_msg = $ct['sec']->obfusc_data($log_msg);
   
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
   
      
      // 'notify' mode ALWAYS needs a hash check (even if we want multiple entries),
      // to AVOID CORRUPTING log formatting
      if ( $category[0] == 'notify' && !$hashcheck ) {
      $hashcheck = md5($log_type . $log_msg);
      }
      // Otherwise, if hashcheck is set, assure compatibility as an array key
      elseif ( $hashcheck ) {
      $hashcheck = md5($hashcheck);
      }
      
      
      if ( isset($category[1]) && trim($category[1]) != '' ) {
      $type_desc = ' (' . $category[1] . ')';
      }
      else {
      $type_desc = null;
      }
   
   
      // Disable logging any included verbose tracing, if log verbosity level config is set to normal
      if ( $ct['conf']['power']['log_verbosity'] == 'normal' ) {
      $verbose_tracing = false;
      }
      
      
      // Flag the runtime mode in logs if it's an iframe's runtime (for cleaner / less-confusing logs)
      // Change var name to avoid changing the GLOBAL value
      if ( $is_iframe ) {
      $logged_runtime_mode = $ct['runtime_mode'] . ' (' . ( isset($_GET['section']) ? 'iframe: ' . $_GET['section'] : 'iframe: unknown' ) . ')';
      }
      else {
      $logged_runtime_mode = $ct['runtime_mode'];
      }
      
      
      if ( $category[1] == 'debug' ) {
          
   
          if ( $hashcheck != false ) {
          $ct['log_debugging'][$log_type][$hashcheck] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . $type_desc . ': ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' );
          }
          // We parse cache errors as array entries (like when hashcheck is included, BUT NO ARRAY KEY)
          elseif ( $category[0] == 'cache' ) {
          $ct['log_debugging'][$log_type][] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . $type_desc . ': ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' );
          }
          elseif ( $overwrite != false ) {
          $ct['log_debugging'][$log_type] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . $type_desc . ': ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' );
          }
          else {
          $ct['log_debugging'][$log_type] .= '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . $type_desc . ': ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' );
          }
      
      
      }
      else {
          
   
          if ( $hashcheck != false ) {
          $ct['log_errors'][$log_type][$hashcheck] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . $type_desc . ': ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' );
          }
          // We parse cache errors as array entries (like when hashcheck is included, BUT NO ARRAY KEY)
          elseif ( $category[0] == 'cache' ) {
          $ct['log_errors'][$log_type][] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . $type_desc . ': ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' );
          }
          elseif ( $overwrite != false ) {
          $ct['log_errors'][$log_type] = '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . $type_desc . ': ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' );
          }
          else {
          $ct['log_errors'][$log_type] .= '[LOG]'.$timestamp_milliseconds.'[TIMESTAMP][' . $formatted_time . '] ' . $logged_runtime_mode . ' => ' . $category[0] . $type_desc . ': ' . $log_msg . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' );
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
   
   $str = ucwords($str); // Uppercase first character, in ALL words
   
   
      // Pretty up the individual words as needed
      $words = explode(" ",$str);
         
      
      foreach($words as $key => $val) {
      
      
         // Coingecko Asset support key names
         if (
         strtolower($words[0]) == 'coingecko'
         && strtolower($words[1]) != 'terminal'
         ) {
         $words[1] = strtoupper($words[1]); // All uppercase
         }
      
   
         // Pretty up all asset market PAIRS AND TICKERS
         
         foreach($ct['conf']['assets']['BTC']['pair'] as $asset_key => $unused) {
               
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
         elseif ( strtolower($val) == 'int' ) {
         $words[$key] = 'Internal';
         }
         elseif ( strtolower($val) == 'ext' ) {
         $words[$key] = 'External';
         }
         elseif ( strtolower($val) == 'precache' ) {
         $words[$key] = 'PreCache';
         }
         elseif ( strtolower($val) == 'io' ) {
         $words[$key] = 'IO';
         }
         elseif ( strtolower($val) == 'ag' ) {
         $words[$key] = 'Aggregator';
         }
         elseif ( strtolower($val) == 'url' ) {
         $words[$key] = 'URL';
         }
         elseif ( strtolower($val) == 'comms' ) {
         $words[$key] = 'Communications';
         }
         elseif ( strtolower($val) == 'max' ) {
         $words[$key] = 'Maximum';
         }
         elseif ( strtolower($val) == 'cpu' ) {
         $words[$key] = 'CPU';
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
         elseif ( strtolower($val) == 'rpc' ) {
         $words[$key] = 'RPC';
         }
      
      
      $pretty_str .= $words[$key] . ' ';
      
      }
   
   
   $pretty_str = preg_replace("/btc/i", 'BTC', $pretty_str);
   $pretty_str = preg_replace("/bitcoin/i", 'Bitcoin', $pretty_str);
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
   $pretty_str = preg_replace("/upbit/i", 'UpBit', $pretty_str);
   $pretty_str = preg_replace("/notifyme/i", 'NotifyMe', $pretty_str);
   $pretty_str = preg_replace("/etherscan/i", 'EtherScan', $pretty_str);
   $pretty_str = preg_replace("/precache/i", 'Pre-Cache', $pretty_str);
   $pretty_str = preg_replace("/coingecko/i", 'CoinGecko.com', $pretty_str);
   $pretty_str = preg_replace("/coinmarketcap/i", 'CoinMarketCap.com', $pretty_str);
   $pretty_str = preg_replace("/alphavantage stock/i", 'AlphaVantage.co', $pretty_str);
   $pretty_str = preg_replace("/anti proxy/i", 'Anti-Proxy', $pretty_str);
   $pretty_str = preg_replace("/price alerts charts/i", 'Price Alerts / Charts', $pretty_str);
   $pretty_str = preg_replace("/webhook internal api/i", 'Internal API / Webhook', $pretty_str);
   
   
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
        	foreach($ct['conf']['news']['feeds'] as $feed_item) {
        	    
        		if ( isset($feed_item["url"]) && trim($feed_item["url"]) != '' ) {
        		    
        		$result = $ct['api']->rss($feed_item["url"], false, $ct['conf']['news']['news_feed_email_entries_include'], false, true);
        		
        		  if ( trim($result) != '<ul></ul>' ) {
        		  $content .= '<div style="padding: 40px;"><fieldset><legend style="font-weight: bold;"> ' . $feed_item["title"] . ' </legend>' . "\n\n";
        	 	  $content .= $result . "\n\n";
        		  $content .= '</fieldset></div>' . "\n\n";
        	 	  $num_posts++;  
        		  }
        		  
        	 	}
        	 	
        	}      
      
      
      // Render summary after determining the content
      $summary .= '<h2 style="color: black;">' . $num_posts . ' Updated RSS Feeds (over ' . $ct['conf']['news']['news_feed_email_frequency'] . ' days)</h3>' . "\n\n";
        	
        	if ( $ct['app_edition'] == 'server' ) {
          $summary .= '<p><a style="color: #00b6db;" title="View the news feeds page in the Open Crypto Tracker app here." target="_blank" href="' . $ct['base_url'] . 'index.php?start_page=news#news">View All News Feeds Here</a></p>' . "\n\n";
        	}
	
	 $summary .= '<p>You can disable receiving news feed emails, OR edit this list, in the Admin Config "News Feeds" section.</p>' . "\n\n";
	
	 $summary .= '<p>To see the date / time an entry was published, hover over it.</p>' . "\n\n";
	
	 $summary .= '<p>Entries are sorted newest to oldest.</p>' . "\n\n";
      
      
      // Package HTML / text into the message body
      $email_body = $header . $summary . $content . $footer;
      
      // Convert any CSS for red coloring (without a class) on feed error messages, AND create a large margin around it
      $email_body = preg_replace("/class=\"red\"/i", "style=\"margin: 15px; color: red;\"", $email_body); 
      
               
      $send_params = array(
                                                    
                           'email' => array(
                                            'content_type' => 'text/html', // Have email sent as HTML content type
                                            'subject' => $num_posts . ' Updated RSS Feeds (over ' . $ct['conf']['news']['news_feed_email_frequency'] . ' days)',
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
   
   
   function reset_price_alert_notice() {
   
   global $ct;
   
   // Alphabetical asset sort, for message UX 
   ksort($ct['price_alert_fixed_reset_array']);
   
   
      $count = 0;
      foreach( $ct['price_alert_fixed_reset_array'] as $reset_data ) {
      
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
   
   
   $text_msg = $count . ' ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' Price Alert Fixed Resets: ' . $reset_list;
   
   $email_msg = 'The following ' . $count . ' ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' price alert fixed resets (run every ' . $ct['conf']['charts_alerts']['price_alert_fixed_reset'] . ' days) have been processed, with the latest spot price data: ' . $reset_list;
   
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

   
   // Default to TCP, if not specified in passed params
   function server_online($host, $port, $connection_type='tcp') {
        
   global $ct, $smtp_vars;

   // 5 second timeout (make it quick, as we are just running a check)
   $timeout = 5;
   
   // ALL SSL-designated ports
   $ssl_ports = array(
                      443,
                      465,
                      587
                     );
   
   // MAIL SSL-designated ports
   $ssl_mail_ports = array(
                      465,
                      587
                     );
       
       
       // USE SSL CONTEXT PARAMS, based on port number
       if ( in_array($port, $ssl_ports) ) {

            
            // USER SETTINGS for *MAIL* SSL support (we want to know if the SSL config on the server is per-user-settings preferences)
            if ( in_array($port, $ssl_mail_ports) ) {

                 
                 if ( $smtp_vars['cfg_strict_ssl'] == 'on' ) {
                 $ssl_desc = 'strict SSL = on';
                 }
                 
                 
            $ssl_params = array(
                              "ssl" => array(
                         				'verify_peer'       => ( $smtp_vars['cfg_strict_ssl'] == 'on' ? true : false ),
                         				'verify_peer_name'  => ( $smtp_vars['cfg_strict_ssl'] == 'on' ? true : false ),
                         				'allow_self_signed' => ( $smtp_vars['cfg_strict_ssl'] == 'on' ? false : true ),
                                             'verify_depth'      => 0, // ALWAYS KEEP AS ZERO
                                            ),
                             );  
                             
            }
            // *NON-MAIL* SSL support (we just want to know if the server IS ONLINE [we don't care about invalid SSL server configs])
            else {
                 
            $ssl_params = array(
                              "ssl" => array(
                                             "verify_peer" => false,
                                             "verify_peer_name" => false,
                                        	"allow_self_signed" => true,
                                             "verify_depth"      => 0, // ALWAYS KEEP AS ZERO
                                            ),
                             );  
                             
            }
            
       
       $ssl_context = stream_context_create($ssl_params);
       
       $connection = stream_socket_client($connection_type . '://' . $host . ':' . $port, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $ssl_context);
       
       }
       else {
       $connection = stream_socket_client($connection_type . '://' . $host . ':' . $port, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
       }
      

       if ( !is_resource($connection) ) {
            
       $ct['gen']->log(
    			'other_error',
    			'Server at "' . $ct['sec']->obfusc_str($host, 3) . '" SEEMS offline (port = "' . $port . '", connection type = "' . $connection_type . '", timeout = "' . $timeout . '"' . ( isset($ssl_desc) ? ', ' . $ssl_desc : '' ) . '): ' . "$errstr ($errno)"
    			);
       
       return false;
       
       }
       else {
       fclose($connection);
       return true;
       }

   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
    // https://thisinterestsme.com/random-rgb-hex-color-php/ (MODIFIED)
    // Human visual perception of different color mixes seems a tad beyond what an algo can distinguish based off AVERAGE range minimums,
    // ESPECIALLY once a list of random-colored items get above a certain size in number (as this decreases your availiable range minimum)
    // That said, auto-adjusting range minimums based off available RGB palette / list size IS feasible AND seems about as good as it can get,
    // AS LONG AS YOU DON'T OVER-MINIMIZE THE RANDOM OPTIONS / EXAUST ALL RANDOM OPTIONS (AND ENDLESSLY LOOP)
   function rand_color($list_size) {
      
   global $ct;
   
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
         if( is_array($ct['rand_color_ranged']) && sizeof($ct['rand_color_ranged']) > 0 ) {
         
            // Compare new random color's range to any colors already generated
            foreach( $ct['rand_color_ranged'] as $used_range ) {
               
            $overall_range = abs($rgb['r'] - $used_range['r']) + abs($rgb['g'] - $used_range['g']) + abs($rgb['b'] - $used_range['b']);
               
               // If we are too close to a previously-generated random color's range, flag it
               if ( $overall_range < ($min_range * 3) ) {
               $range_too_close = true;
               }
               
            }
         
            
            // If the new random color is NOT out of range, use it / add it to list of any colors already generated
            if ( !$range_too_close ) {
            $ct['rand_color_ranged'][] = $rgb;
            $result['hex'] = $hex;
            $result['rgb'] = $rgb;
            }
         
         }
         /////////////////////////////////
         // If this is the first random color generated
         /////////////////////////////////
         else {
         $ct['rand_color_ranged'][] = $rgb;
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
   
   global $ct;
   
   $data = array();
   
      // #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
      if ( array_key_exists($chart_format, $ct['conf']['assets']['BTC']['pair']) && !array_key_exists($chart_format, $ct['opt_conf']['crypto_pair']) ) {
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
      
      
         // If the data set on this line is NOT valid, skip it
         if (
         $chart_format == 'system' && !isset($result[7])
         || $chart_format != 'system' && !isset($result[2])
         ) {
         continue;
         }
      
      
     $result = array_map('trim', $result); // Trim whitespace out of all array values
      
      
         if ( trim($result[0]) != '' && trim($result[0]) >= $start_timestamp ) {
            
         $data['time'] .= trim($result[0]) . '000,';  // Zingchart wants 3 more zeros with unix time (milliseconds)
         
         
            if ( $system_statistics_chart ) {
            
            
                if ( trim($result[1]) != 'NO_DATA' && trim($result[1]) != '' && is_numeric($result[1]) ) {
                $data['load_average_15_minutes'] .= trim($result[1]) . ',';
                $ct['last_valid_chart_data']['load_average_15_minutes'] = $result[1];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($ct['last_valid_chart_data']['load_average_15_minutes']) ) {
                $data['load_average_15_minutes'] .= trim($ct['last_valid_chart_data']['load_average_15_minutes']) . ',';
                }
            
            
                if ( trim($result[2]) != 'NO_DATA' && trim($result[2]) != '' && is_numeric($result[2]) ) {
                $data['temperature_celsius'] .= trim($result[2]) . ',';
                $ct['last_valid_chart_data']['temperature_celsius'] = $result[2];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($ct['last_valid_chart_data']['temperature_celsius']) ) {
                $data['temperature_celsius'] .= trim($ct['last_valid_chart_data']['temperature_celsius']) . ',';
                }
            
            
                if ( trim($result[3]) != 'NO_DATA' && trim($result[3]) != '' && is_numeric($result[3]) ) {
                $data['used_memory_gigabytes'] .= trim($result[3]) . ',';
                $ct['last_valid_chart_data']['used_memory_gigabytes'] = $result[3];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($ct['last_valid_chart_data']['used_memory_gigabytes']) ) {
                $data['used_memory_gigabytes'] .= trim($ct['last_valid_chart_data']['used_memory_gigabytes']) . ',';
                }
            
            
                if ( trim($result[4]) != 'NO_DATA' && trim($result[4]) != '' && is_numeric($result[4]) ) {
                $data['used_memory_percentage'] .= trim($result[4]) . ',';
                $ct['last_valid_chart_data']['used_memory_percentage'] = $result[4];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($ct['last_valid_chart_data']['used_memory_percentage']) ) {
                $data['used_memory_percentage'] .= trim($ct['last_valid_chart_data']['used_memory_percentage']) . ',';
                }
            
            
                if ( trim($result[5]) != 'NO_DATA' && trim($result[5]) != '' && is_numeric($result[5]) ) {
                $data['free_disk_space_terabytes'] .= trim($result[5]) . ',';
                $ct['last_valid_chart_data']['free_disk_space_terabytes'] = $result[5];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($ct['last_valid_chart_data']['free_disk_space_terabytes']) ) {
                $data['free_disk_space_terabytes'] .= trim($ct['last_valid_chart_data']['free_disk_space_terabytes']) . ',';
                }
            
            
                if ( trim($result[6]) != 'NO_DATA' && trim($result[6]) != '' && is_numeric($result[6]) ) {
                $data['crypto_tracker_cache_size_gigabytes'] .= trim($result[6]) . ',';
                $ct['last_valid_chart_data']['crypto_tracker_cache_size_gigabytes'] = $result[6];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($ct['last_valid_chart_data']['crypto_tracker_cache_size_gigabytes']) ) {
                $data['crypto_tracker_cache_size_gigabytes'] .= trim($ct['last_valid_chart_data']['crypto_tracker_cache_size_gigabytes']) . ',';
                }
            
            
                if ( trim($result[7]) != 'NO_DATA' && trim($result[7]) != '' && is_numeric($result[7]) ) {
                $data['cron_core_runtime_seconds'] .= trim($result[7]) . ',';
                $ct['last_valid_chart_data']['cron_core_runtime_seconds'] = $result[7];
                }
                // Just repeat any last valid data if available, so zingchart timestamps in GUI charts correctly
                elseif ( isset($ct['last_valid_chart_data']['cron_core_runtime_seconds']) ) {
                $data['cron_core_runtime_seconds'] .= trim($ct['last_valid_chart_data']['cron_core_runtime_seconds']) . ',';
                }
                
            
            }
            elseif ( $asset_perf_chart && trim($result[1]) != 'NO_DATA' && trim($result[1]) != '' && is_numeric($result[1]) ) {
      
               if ( !$ct['runtime_data']['performance_stats'][$asset]['start_val'] ) {
               $ct['runtime_data']['performance_stats'][$asset]['start_val'] = $result[1];
               
               $data['percent'] .= '0.00,';
               $data['combined'] .= '[' . trim($result[0]) . '000, 0.00],';  // Zingchart wants 3 more zeros with unix time (milliseconds)
               }
               else {
                  
               // PRIMARY CURRENCY CONFIG price percent change (CAN BE NEGATIVE OR POSITIVE IN THIS INSTANCE)
               $percent_change = ($result[1] - $ct['runtime_data']['performance_stats'][$asset]['start_val']) / abs($ct['runtime_data']['performance_stats'][$asset]['start_val']) * 100;
               // Better decimal support
               $percent_change = $ct['var']->num_to_str($percent_change); 
               
               $data['percent'] .= round($percent_change, 2) . ',';
               $data['combined'] .= '[' . trim($result[0]) . '000' . ', ' . round($percent_change, 2) . '],';  // Zingchart wants 3 more zeros with unix time (milliseconds)
               
               }
            
            }
            elseif ( trim($result[1]) != 'NO_DATA' && trim($result[1]) != '' && is_numeric($result[1]) ) {
                 
               // Some APIs don't have trade volume data, so we just set trade volume to zero if none exists
               if ( $result[2] == 'NO_DATA' || trim($result[2]) == '' || !is_numeric($result[2]) ) {
               $result[2] = 0;
               }
            
               // Format or round primary currency price depending on value (non-stablecoin crypto values are already stored in the format we want for the interface)
               if ( $fiat_formatting ) {
               $data['spot'] .= $ct['var']->num_to_str($result[1]) . ',';
               $data['volume'] .= round($result[2]) . ',';
               }
               // Non-stablecoin crypto
               else {
               $data['spot'] .= $ct['var']->num_to_str($result[1]) . ',';
               $data['volume'] .= $ct['var']->num_to_str( round($result[2], $ct['conf']['charts_alerts']['chart_crypto_volume_decimals']) ) . ',';
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
      $data['crypto_tracker_cache_size_gigabytes'] = rtrim($data['crypto_tracker_cache_size_gigabytes'],',');
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
   
   
   function config_versioning($conf_passed, $set_defaults=false) {
   
   global $ct;
   
      
      // If we are resetting defaults this runtime, no checks needed
      if ( $ct['reset_config'] || $ct['config_was_reset'] || $set_defaults ) {
      
      $conf_passed['version_states']['app_version'] = $ct['app_version'];
      
          if ( $ct['active_plugins_registered'] ) {
          $conf_passed['version_states']['plug_version'] = $ct['plug_version'];
          }
          
          // We're resetting EVERYTHING, so we don't need any upgrade checks
          if ( $ct['reset_config'] || $ct['config_was_reset'] ) {
          $ct['config_upgrade_check'] = false; 
          }
      
      return $conf_passed;
      
      }
      // We do NOT sync version states, IF runtime is ajax OR 'fast_runtime'
      elseif ( $ct['fast_runtime'] || $ct['runtime_mode'] == 'ajax' ) {
      return $conf_passed;
      }
   
   
      // PLUGIN CHECKS
      if ( $ct['active_plugins_registered'] ) {
           
           
           // Set the version states
           foreach ( $ct['plug_version'] as $key => $val ) {
                
                
                if ( isset($conf_passed['version_states']['plug_version'][$key]) ) {
                     
                $config_version_compare = $this->version_compare($ct['plug_version'][$key], $conf_passed['version_states']['plug_version'][$key]);
      
                     
                     // Upgrades
                     if ( $config_version_compare['base_diff'] > 0 ) {
                     
                     // Auto-set back to false in upgrade_cached_ct_conf(), AFTER processing
                     $ct['config_upgrade_check'] = true; 
           
                     }
                     // Downgrades
                     elseif ( $config_version_compare['base_diff'] < 0 ) {
                     
                     // Auto-set back to false in upgrade_cached_ct_conf(), AFTER processing
                     $ct['config_upgrade_check'] = true;
                     
                     // RESET this plugin's config
          	      $conf_passed['plug_conf'][$key] = $ct['default_conf']['plug_conf'][$key];
                              
                     $ct['gen']->log(
                              			'notify_error',
                              			'"' . $key . '" plugin DOWNGRADE detected, so a RESET to defaults on this ENTIRE plugin configuration was done, TO ASSURE COMPATIBILITY'
                                 			);
                     
                     }
                
                
                }
                // IF cached plugin version doesn't exist yet, AND we are NOT mid-flight on
                // activating / deactivating this plugin in the admin interface, trigger an upgrade check
                elseif ( !isset($ct['verified_update_request']['plugin_status'][$key]) ) {
                     
                // Auto-set back to false in upgrade_cached_ct_conf(), AFTER processing
                $ct['config_upgrade_check'] = true;
           
                }
   
           
           }

           
           // Directly updating / reloading config RIGHT NOW avoids conflicts
           // (AS $ct['update_config'] / $ct['reset_config'] / "XXX_upgrade_check" CANNOT BE MIXED WITH EACH OTHER)
           if ( $ct['config_upgrade_check'] ) {

           // Flag for UI alerts that we UPGRADED / DOWNGRADED
           // (general message about cached CSS / JS [WITHOUT VERSION NUMBERS], so shown even when NOT logged in)
           $ui_was_upgraded_alert_data = array( 'run' => 'yes', 'time' => time() );
                    
           $ct['cache']->save_file($ct['base_dir'] . '/cache/events/upgrading/ui_was_upgraded_alert.dat', json_encode($ui_was_upgraded_alert_data, JSON_PRETTY_PRINT) );


               // Configure any developer-added plugin DB SETTING RESETS (for RELIABLE DB upgrading)
               // PLUGINS INCLUDE THEIR RESET DATA IN THEIR CONFIG FILE (FOR DEV UX), SO WE JUST NEED TO PARSE / CONFIG IT
               foreach( $ct['plugin_setting_resets'] as $this_plug ) {
               require($ct['base_dir'] . '/app-lib/php/classes/core/includes/config/plugin-setting-reset-config.php');
               }
               
               
           unset($this_plug);  // Reset
           
           // Update plugin version states (prunes any removed plugins, mirroring current active plugin versions)
           $conf_passed['version_states']['plug_version'] = $ct['plug_version'];
                
           $conf_passed = $ct['cache']->update_cached_config($conf_passed, true); // UPGRADE MODE

           }
           // Otherwise, IF we have a VALIDATED admin interface user update submission in-progress,
           // update plugin version states (mirroring active plugin versions, will be saved during the user update)
           elseif ( $ct['verified_update_request'] ) {
           $conf_passed['version_states']['plug_version'] = $ct['plug_version'];
           }
           

      }
      else {
     
     
           // APP CHECKS
           if ( isset($conf_passed['version_states']['app_version']) ) {
           
           $config_version_compare = $this->version_compare($ct['app_version'], $conf_passed['version_states']['app_version']);
        
                
                // Upgrades
                if ( $config_version_compare['base_diff'] > 0 ) {

                // Auto-set back to false in upgrade_cached_ct_conf(), AFTER processing
                $ct['config_upgrade_check'] = true;
                
                }
                // Downgrades
                elseif ( $config_version_compare['base_diff'] < 0 ) {
                
                $ct['reset_config'] = true; // RESET ENTIRE CONFIG
          
                $ct['update_config_halt'] = 'The app was busy RESETTING it\'s cached config, please wait a minute and try again.';
                    
                $ct['gen']->log(
                         			'notify_error',
                         			'app DOWNGRADE detected, so a RESET to defaults on app AND plugin configurations were done, TO ASSURE COMPATIBILITY'
                            			);
                
                }
                
                
           }
           // IF cached app version doesn't exist yet, trigger an upgrade check
           else {

           // Auto-set back to false in upgrade_cached_ct_conf(), AFTER processing
           $ct['config_upgrade_check'] = true;
           
           }

           
           // Directly updating / reloading config RIGHT NOW avoids conflicts
           // (AS $ct['update_config'] / $ct['reset_config'] / "XXX_upgrade_check" CANNOT BE MIXED WITH EACH OTHER)
           if ( $ct['config_upgrade_check'] ) {
         
           // Flag for UI alerts that we UPGRADED / DOWNGRADED
           // (general message about cached CSS / JS [WITHOUT VERSION NUMBERS], so shown even when NOT logged in)
           $ui_was_upgraded_alert_data = array( 'run' => 'yes', 'time' => time() );
          
          $ct['cache']->save_file($ct['base_dir'] . '/cache/events/upgrading/ui_was_upgraded_alert.dat', json_encode($ui_was_upgraded_alert_data, JSON_PRETTY_PRINT) );
               
           // Process any developer-added APP DB SETTING RESETS (for RELIABLE DB upgrading)
           require($ct['base_dir'] . '/app-lib/php/classes/core/includes/config/setting-reset-config.php');

           $conf_passed['version_states']['app_version'] = $ct['app_version'];
                          
           $conf_passed = $ct['cache']->update_cached_config($conf_passed, true); // UPGRADE MODE

           }
           

      }

   
   return $conf_passed;
   
   }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function test_proxy($problem_proxy_array) {
  
  global $ct;
  
  // Endpoint to test proxy connectivity: https://www.myip.com/api-docs/
  $proxy_test_url = 'https://api.myip.com/';
  
  $problem_endpoint = $problem_proxy_array['endpoint'];
  
  $obfusc_url_data = $ct['sec']->obfusc_data($problem_endpoint); // Automatically removes sensitive URL data
  
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
  
  
      if ( $ct['conf']['proxy']['proxy_alert_runtime'] == 'all' ) {
      $run_alerts = 1;
      }
      elseif ( $ct['conf']['proxy']['proxy_alert_runtime'] == 'cron' && $ct['runtime_mode'] == 'cron' ) {
      $run_alerts = 1;
      }
      elseif ( $ct['conf']['proxy']['proxy_alert_runtime'] == 'ui' && $ct['runtime_mode'] == 'ui' ) {
      $run_alerts = 1;
      }
      else {
      $run_alerts = null;
      }
  
  
      if ( $run_alerts == 1 && $ct['cache']->update_cache('cache/alerts/proxy-check-'.$cache_filename.'.dat', ( $ct['conf']['proxy']['proxy_alert_frequency_maximum'] * 60 ) ) == true
      && in_array($cache_filename, $ct['proxies_checked']) == false ) {
      
       
      // SESSION VAR first, to avoid duplicate alerts at runtime (and longer term cache file locked for writing further down, after logs creation)
      $ct['proxies_checked'][] = $cache_filename;
       
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
         if ( $misconfigured == 1 || $ct['conf']['proxy']['proxy_alert_checkup_ok'] == 'include' ) {
             
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
                  
		 
		 // Only send to comm channels the user prefers, based off the config setting $ct['conf']['proxy']['proxy_alert_channels']
		 $preferred_comms = $this->preferred_comms($ct['conf']['proxy']['proxy_alert_channels'], $send_params);			
                  
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
   
   // Defaults
   
   $system['cpu_threads'] = 1; 
   
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
      elseif ( $ct['ms_windows_server'] ) {
          
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
         
      
      }
      // CPU core count on Windows
      elseif ( $ct['ms_windows_server'] ) {
      
      $win_cpu_model = getenv("PROCESSOR_IDENTIFIER");
      
         if ( isset($win_cpu_model) && trim($win_cpu_model) != '' ) {
         $system['model_name'] = $win_cpu_model;
         }
      
      $win_cpu_cores = getenv("NUMBER_OF_PROCESSORS") + 0;
      
         if ( $win_cpu_cores > 0 ) {
         $system['cpu_threads'] = $win_cpu_cores;
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