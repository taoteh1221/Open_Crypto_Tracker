<?php
/*************************************************************
 Description: PHP Class for sending SMTP Mail
 Author     : halojoy  https://github.com/halojoy
 Copyright  : 2018 halojoy
 License    : MIT License  https://opensource.org/licenses/MIT
 *************************************************************/

Class SMTPMailer {
	
	
    private $server = 'smtp.gmail.com';
    private $port   =  587;
    private $secure = 'tls';
    private $username = '';
    private $password = '';
    private $debug_mode = '';  // Open Crypto Tracker debug mode setting
    private $strict_ssl = '';  // Open Crypto Tracker strict SSL setting
    private $app_version = ''; // Open Crypto Tracker version
    public $to       = array();
    public $from     = array();
    public $cc       = array();
    public $bcc      = array();
    public $reply_to = array();
    public $subject  = 'No subject';
    public $body     = '';
    public $text     = '';
    public $file     = array();
    public $charset  = 'UTF-8';
    public $transferEncoding = '8bit';
    private $headers;
    private $ahead;
    private $sock;
    private $hostname;
    private $local;
    private $result;
    private $meta      = array();
    private $log      = array();
    private $debug      = array();
    private $logfile = '';
    private $logfile_debug = '';




    public function __construct($server=false, $port=false, $secure=false) {
    	
        // Setup basic configuration
        if (file_exists( dirname(__FILE__) . '/conf/config_smtp.php')) {
        	
            include dirname(__FILE__) . '/conf/config_smtp.php';
            $this->logfile   = $cfg_log_file;
            $this->logfile_debug   = $cfg_log_file_debug;
            $this->server   = $cfg_server;
            $this->port     = $cfg_port;
            $this->secure   = $cfg_secure;
            $this->username = $cfg_username;
            $this->password = $cfg_password;
            $this->debug_mode = $cfg_debug_mode;   // Open Crypto Tracker debug mode setting
            $this->strict_ssl = $cfg_strict_ssl;   // Open Crypto Tracker strict SSL setting
            $this->app_version = $cfg_app_version; // Open Crypto Tracker version
            
        }
        
        if ($server !== false) {
            $this->server   = $server;
            $this->username = '';
            $this->password = '';
        }
        
        if ($port   !== false) $this->port   = $port;
        
        if ($secure !== false) $this->secure = $secure;

        // Define connection hostname and localhost
        $this->hostname = $this->server;
        
        if ($this->secure == 'tls') $this->hostname = 'tcp://'.$this->server;
        
        if ($this->secure == 'ssl') $this->hostname = 'ssl://'.$this->server;
        
        if (!empty($_SERVER['HTTP_HOST']))
            $this->local = $_SERVER['HTTP_HOST'];
        elseif (!empty($_SERVER['SERVER_NAME']))
            $this->local = $_SERVER['SERVER_NAME'];
        else
            $this->local = php_uname("n"); // Use CLI compatible if all else fails
            
        if ($this->username)
            $this->from = array($this->username, '');
            
        define("NL", "\r\n");
        
    }
 
 
 
    // Authentication Login
    public function Auth($user, $pass) {
        $this->username = $user;
        $this->password = $pass;
    }



    // Set from email address
    public function From($address, $name = '') {
        $this->from = array($address, $name);
    }



    // Add email reply to address
    public function addReplyTo($address, $name = '') {
        $this->reply_to[] = array($address, $name);
    }



    // Single recipient email addresses
    public function singleTo($address, $name = '') {
        $this->to = array(); // Clear any other recipient email addresses
        $this->to[] = array($address, $name);
    }



    // Add recipient email address
    public function addTo($address, $name = '') {
        $this->to[] = array($address, $name);
    }



    // Add carbon copy email address
    public function addCc($address, $name = '') {
        $this->cc[] = array($address, $name);
    }



    // Add blind carbon copy email address
    public function addBcc($address, $name = '') {
        $this->bcc[] = array($address, $name);
    }



    // Set email subject
    public function Subject($subject) {
        $this->subject = $subject;
    }



    // Set email html body
    public function Body($html) {
        $this->body = $html;
    }



    // Set email plain text
    public function Text($text) {
        $this->text = $text;
    }



    // Add attachment file
    public function File($path) {
        $this->file[] = $path;
    }



    // Set charset. Default 'UTF-8'
    public function Charset($charset) {
        $this->charset = $charset;
    }



    // Set Content Transfer Encoding. Default '8bit'
    public function TransferEncoding($encode) {
        $this->transferEncoding = $encode;
    }



    // Display current log file
    public function ShowLog() {
        echo "\n SMTP Mail Transaction Log \n";
        print_r($this->log);
    }
    
    
    
    // Log to error file
    public function LogFile() {
    		$format = "\n" . date('Y-m-d H:i:s') . " UTC | smtp_error: \n =========SMTP error log START================================================== \n ".print_r($this->log, true)." \n =========SMTP error log END================================================== \n\n";
    		file_put_contents($this->logfile, $format, FILE_APPEND | LOCK_EX);
    }
    
    
    
    // Log to debugging file
    public function LogFileDebugging() {
    		$format = "\n" . date('Y-m-d H:i:s') . " UTC | smtp_debug: \n =========SMTP debugging log START================================================== \n ".print_r($this->debug, true)." \n =========SMTP debugging log END================================================== \n\n";
    		file_put_contents($this->logfile_debug, $format, FILE_APPEND | LOCK_EX);
    }
    
    

    // Display current headers
    public function ShowHeaders() {
        echo "\n SMTP Mail Headers \n";
        echo $this->doHeaders(false);
    }



    // Send the SMTP Mail
    public function Send() {
    	
        // Prepare data for sending
        $this->headers = $this->doHeaders();
        $user64 = base64_encode($this->username);
        $pass64 = base64_encode($this->password);
        $mailfrom = '<'.$this->from[0].'>';
        
        foreach(array_merge($this->to, $this->cc, $this->bcc) as $address)
            $mailto[] = '<'.$address[0].'>';  

        // Open server connection and run transfers
		  $stream_context = stream_context_create(
		  [ 
		  	'ssl' => [
				'verify_peer'       => ( $this->strict_ssl == 'on' ? true : false ),
				'verify_peer_name'  => ( $this->strict_ssl == 'on' ? true : false ),
				'allow_self_signed' => ( $this->strict_ssl == 'on' ? false : true ),
				'verify_depth'      => 0 // ALWAYS KEEP AS ZERO
			 ]
			]
			);


        if ( $this->secure == 'tls' || $this->secure == 'ssl' ) {
        $this->sock = stream_socket_client($this->hostname.':'.$this->port, $enum, $estr, 30, STREAM_CLIENT_CONNECT, $stream_context);
        }
        else {
        $this->sock = stream_socket_client($this->hostname.':'.$this->port, $enum, $estr, 30, STREAM_CLIENT_CONNECT);
        }
        
        
        if ( !is_resource($this->sock) ) {
         $this->log[] = 'Socket connection error for host: ' . $this->hostname.':'.$this->port . '. Make sure your hostname and firewall settings are correct.';
         $this->LogFile();
         return false;
        }
        
        
        $this->log[] = 'CONNECTION: '.$this->hostname.':'.$this->port;
        $this->debug[] = 'CONNECTION: '.$this->hostname.':'.$this->port;
        $this->response('220');
        $this->logreq('EHLO '.$this->local, '250');


        if ($this->secure == 'tls') {
            $this->logreq('STARTTLS', '220');
            stream_socket_enable_crypto($this->sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $this->logreq('EHLO '.$this->local, '250');
        }
        

        $this->logreq('AUTH LOGIN', '334');
        $this->logreq($user64, '334');
        $this->logreq($pass64, '235');

        $this->logreq('MAIL FROM: '.$mailfrom, '250');
        
        foreach ($mailto as $address)
            $this->logreq('RCPT TO: '.$address, '250');

        $this->logreq('DATA', '354');
        $this->log[] = $this->doHeaders(false);
        $this->request($this->headers, '250');

        $this->logreq('QUIT', '221');
        
            if ( is_resource($this->sock) ) {
            fclose($this->sock);
            }
        
		gc_collect_cycles(); // Clean memory cache
				
				if ( $this->debug_mode == 'on' || $this->debug_mode == 'smtp_telemetry' ) {
        		
				$this->debug[] = "\n\n SMTP Server response (debugging mode [".$this->debug_mode."]): \n";
				
        		$this->debug[] = "\n\n ( Reference: https://en.wikipedia.org/wiki/List_of_SMTP_server_return_codes ) \n";
        
				$this->debug[] = 'SERVER RESPONSE: ' . $this->result;
				
				$this->debug[] = "\n\n META DATA: \n";
				
					foreach ( $this->meta as $info_key => $info_val ) {
					$this->debug[] = $info_key . ' => ' . $info_val;
					}
					
				
				$this->debug[] = "\n\n HEADER DATA: \n";
				
					foreach ( $this->ahead as $header ) {
						
						if ( !$truncate_following && trim($header) != '' || !$truncate_following && is_array($header) ) {
							
							if ( is_array($header) ) {
								
								foreach ( $header as $key => $value ) {
								$this->debug[] = $key . ' => ' . $value;
								}
							
							}
							else {
							$this->debug[] = trim($header);
							}
        				
        				$truncate_following = ( preg_match("/Content-Transfer-Encoding/i", $header) ? 1 : NULL );
        				
						}
						else {
						$this->debug[] = ( strlen($header) > 45 ? substr( trim($header) , 0, 45) . '...[truncated to 45 characters max]' : trim($header) );
						}
						
					}
					
				$this->LogFileDebugging();
						
				}
				
        return true;  // If we get this far, return true (indicating high probability of success)
        
    }



    // Log command and do request
    private function logreq($cmd, $code) {
        $this->log[] = $cmd;
        $this->debug[] = $cmd;
        $this->request($cmd, $code);
        return;
    }    



    // Send one command and test response
    private function request($cmd, $code) {
        fwrite($this->sock, $cmd.NL);
        $this->response($code);
        return;
    }



    // Read and verify response code
    private function response($code) {
    	
        stream_set_timeout($this->sock, 8);
        $this->result = fread($this->sock, 768);
        $this->meta = stream_get_meta_data($this->sock);
        
        
        if ($this->meta['timed_out'] === true) {
            
            if ( is_resource($this->sock) ) {
            fclose($this->sock);
            }
            
			gc_collect_cycles(); // Clean memory cache
            $this->log[] = "\n\n Was a timeout in Server response \n";
            $this->LogFile();            
            print_r($this->meta);
            return false;
        }
        
        $this->log[] = "\n\n ( Reference: https://en.wikipedia.org/wiki/List_of_SMTP_server_return_codes ) \n";
        
        
        $this->log[] = "\n\n SMTP Server response error(s): \n";
        
        $this->log[] = 'SERVER RESPONSE: ' . $this->result;
        
        
        if (substr($this->result, 0, 3) == $code) {
            return false;
        }
        
            
            if ( is_resource($this->sock) ) {
            fclose($this->sock);
            }
        
		gc_collect_cycles(); // Clean memory cache
        
        $this->log[] = "\n\n META DATA: \n";
        
        	foreach ( $this->meta as $info_key => $info_val ) {
        	$this->log[] = $info_key . ' => ' . $info_val;
        	}
        
        $this->log[] = "\n\n HEADER DATA: \n";
        	
        	foreach ( $this->ahead as $header ) {
						
						if ( !$truncate_following && trim($header) != '' || !$truncate_following && is_array($header) ) {
							
							if ( is_array($header) ) {
								
								foreach ( $header as $key => $value ) {
								$this->log[] = $key . ' => ' . $value;
								}
							
							}
							else {
							$this->log[] = trim($header);
							}
        				
        				$truncate_following = ( preg_match("/Content-Transfer-Encoding/i", $header) ? 1 : NULL );
        				
						}
						else {
						$this->log[] = ( strlen($header) > 45 ? substr( trim($header) , 0, 45) . '...[truncated to 45 characters max]' : trim($header) );
						}
						
        	}
        	
        $this->LogFile();
        
        return false;
        
    }



    // Do create headers after precheck
    private function doHeaders($filedata = true) {
    	
        // Precheck. Test if we have necessary data
        if (empty($this->username) || empty($this->password)) {
        $this->log[] = "\n\n SMTP Error: \n";
        $this->log[] = 'We need username and password for: <b>'.$this->server.'</b>';
        $this->LogFile();
        return false;
        }
            
        if (empty($this->from)) {
        $this->from = array($this->username, '');
        }
        
        if (empty($this->to) || !filter_var($this->to[0][0], FILTER_VALIDATE_EMAIL)) {
        $this->log[] = "\n\n SMTP Error: \n";
        $this->log[] = 'We need a valid email address to send to';
        $this->LogFile();
        return false;
        }
            
        if (strlen(trim($this->body)) < 3 && strlen(trim($this->text)) < 3) {
        $this->log[] = "\n\n SMTP Error: \n";
        $this->log[] = 'We really need a message to send';
        $this->LogFile();
        return false;
        }

        // Create Headers
        $headerstring = '';
        $this->createHeaders($filedata);
        
        foreach($this->ahead as $val) {
            $headerstring .= $val.NL;
        }

        return rtrim($headerstring);
        
    }



    // Headers
    private function createHeaders($filedata) {
    	
    	
        $this->ahead = array();
        $this->ahead[] = 'Date: '.date('r');
        $this->ahead[] = 'To: '.$this->formatAddressList($this->to);
        $this->ahead[] = 'From: '.$this->formatAddress($this->from);
        
        if (!empty($this->cc)) {
            $this->ahead[] = 'Cc: '.$this->formatAddressList($this->cc);
        }
        
        if (!empty($this->bcc)) {
            $this->ahead[] = 'Bcc: '.$this->formatAddressList($this->bcc);
        }
        
        if (!empty($this->reply_to)) {
            $this->ahead[] = 'Reply-To: '.$this->formatAddressList($this->reply_to);
        }
        
        $this->ahead[] = 'Subject: '.'=?UTF-8?B?'.base64_encode($this->subject).'?=';
        $this->ahead[] = 'Message-ID: '.$this->generateMessageID();
        $this->ahead[] = 'X-Mailer: '.'Open_Crypto_Tracker/' . $this->app_version . ' - PHP/' . phpversion();
        $this->ahead[] = 'MIME-Version: '.'1.0';

        $boundary = md5(uniqid());
        
        // Email contents
        if (empty($this->file) || !file_exists($this->file[0])) {
        	
        	
            if ($this->text && $this->body) {
            	
                // add multipart
                $this->ahead[] = 'Content-Type: multipart/alternative; boundary="'
                                                            .$boundary.'"';
                $this->ahead[] = '';
                $this->ahead[] = 'This is a multi-part message in MIME format.';
                $this->ahead[] = '--'.$boundary;
                
                // add text
                $this->defContent('plain', 'text');
                $this->ahead[] = '--'.$boundary;
                
                // add html
                $this->defContent('html', 'body');
                $this->ahead[] = '--'.$boundary.'--';
                
            }
            elseif ($this->text) {
                // add text
                $this->defContent('plain', 'text');
            }
            else {
                // add html
                $this->defContent('html', 'body');
            }
            
            
        }
        else {
        	
        	
            // add multipart with attachment
            $this->ahead[] = 'Content-Type: multipart/mixed; boundary="'
                                                            .$boundary.'"';
            $this->ahead[] = '';
            $this->ahead[] = 'This is a multi-part message in MIME format.';
            $this->ahead[] = '--'.$boundary;
            
            if ($this->text) {
                // add text
                $this->defContent('plain', 'text');
                $this->ahead[] = '--'.$boundary;
            }
            
            if ($this->body) {
                // add html
                $this->defContent('html', 'body');
                $this->ahead[] = '--'.$boundary;
            }
            
            // spin thru attachments...
            foreach ($this->file as $path) {
            	
                if (file_exists($path)) {
                	
                    // add attachment
                    $this->ahead[] = 'Content-Type: application/octet-stream; '
                                                 .'name="'.basename($path).'"';
                    $this->ahead[] = 'Content-Transfer-Encoding: base64';
                    $this->ahead[] = 'Content-Disposition: attachment';
                    $this->ahead[] = '';
                    
                    if ($filedata) {
                        // encode file contents
                        $contents = chunk_split(base64_encode(file_get_contents($path)));
                        $this->ahead[] = $contents;
                    }
                    
                    $this->ahead[] = '--'.$boundary;
                    
                }
                
            }   
            
            // add last "--"
            $this->ahead[count($this->ahead)-1] .= '--';
            
            
        }
        // final period
        $this->ahead[] = '.';

        return;
        
        
    }



    // Define and code the contents
    private function defContent($type, $msg) {
    	
        $this->ahead[] = 'Content-Type: text/'.$type.'; charset="'.$this->charset.'"';
        $this->ahead[] = 'Content-Transfer-Encoding: '.$this->transferEncoding;
        $this->ahead[] = '';
        
        if ($this->transferEncoding == 'quoted-printable')
            $this->ahead[] = quoted_printable_encode($this->$msg);
        else
            $this->ahead[] = $this->$msg;
            
    }



    // Format email address (with name)
    private function formatAddress($address) {
        return ($address[1] == '') ? $address[0] : '"'.$address[1].'" <'.$address[0].'>';
    }



    // Format email address list
    private function formatAddressList($addresses) {
    	
        $list = '';
        
        foreach ($addresses as $address) {
        	
            if ($list) {
                $list .= ', '.NL."\t";
            }
            
            $list .= $this->formatAddress($address);
            
        }
        
        return $list;
        
    }



    private function generateMessageID() {
    	
        return sprintf(
            "<%s.%s@%s>",
            base_convert(microtime(), 10, 36),
            base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36),
            $this->local
        );
        
    }
    
    

}



