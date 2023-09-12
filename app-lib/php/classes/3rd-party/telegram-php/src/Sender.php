<?php

namespace Telegram;
use Telegram\Elements; // TODO

class Sender {
	private $parent;
	public $bot;
	private $content = array();
	private $method = NULL;
	private $broadcast = NULL;
	private $language = "en";
	private $timeout = 0;
	public  $convert_emoji = TRUE; // Default
	public  $use_internal_resolver = FALSE; // SHOULD BE FALSE, TO RESOLVE THE TELEGRAM ENDPOINT NORMALLY VIA DNS (#NOT# VIA THE HARD-CODED STATIC IPS)
	private $_keyboard;
	private $_inline;
	private $_payment;
	private $_sticker;

	public function __construct($uid = NULL, $key = NULL, $name = NULL){
		$this->_keyboard = new \Telegram\Keyboards\Keyboard($this);
		$this->_inline = new \Telegram\Keyboards\InlineKeyboard($this);
		$this->_payment = new \Telegram\Payments\Stripe($this);
		$this->_sticker = new \Telegram\Sticker($this);

		if(!empty($uid)){
			if($uid instanceof Receiver){
				$this->parent = $uid;
				$this->bot = $this->parent->bot;
				$this->language = $this->parent->language;
			}elseif($uid instanceof Bot){
				$this->bot = $uid;
			}else{
				$this->set_access($uid, $key, $name);
			}
		}
	}

	private function set_access($uid, $key = NULL, $name = NULL){
		$this->bot = new \Telegram\Bot($uid, $key, $name);
		return $this;
	}

	public function chat($id = NULL){
		if(empty($id)){
			if(isset($this->content['chat_id'])){ return $this->content['chat_id']; }
			$id = TRUE; // HACK ?
		}
		if($id === TRUE && $this->parent instanceof \Telegram\Receiver){ $id = $this->parent->chat->id; }
		elseif($id instanceof Chat or $id instanceof User){ $id = $id->id; }
		$this->content['chat_id'] = $id;
		return $this;
	}

	public function chats($ids){
		if(empty($ids)){ return $this; } // HACK
		$this->broadcast = $ids;
		$this->content['chat_id'] = $ids[0]; // HACK
		return $this;
	}

	public function user($id = NULL){
		if(empty($id)){ return $this->content['user_id']; }
		elseif($id === TRUE){ $id = $this->parent->user->id; }
		elseif($id instanceof User){ $id = $id->id; }
		$this->content['user_id'] = $id;
		return $this;
	}

	public function message($id = NULL){
		if(empty($id)){ return $this->content['message_id']; }
		if($id === TRUE && $this->parent instanceof \Telegram\Receiver){ $id = $this->parent->message; }
		elseif(is_array($id) and isset($id['message_id'])){ $id = $id['message_id']; } // JSON Response from another message.
		$this->content['message_id'] = $id;
		return $this;
	}

	public function get_file($id){
		$this->method = "getFile";
		$this->content['file_id'] = $id;
		return $this->send();
	}

	public function duration($duration){
		$this->content['duration'] = (int) $duration;
		return $this;
	}

	public function resolution($width, $height){
		$this->content['width'] = (int) $width;
		$this->content['height'] = (int) $height;
		return $this;
	}

	public function file($type, $file, $caption = NULL, $keep = FALSE){
		if(!in_array($type, ["photo", "chatphoto", "audio", "voice", "document", "animation", "sticker", "video", "video_note", "videonote"])){ return FALSE; }

		$url = FALSE;
		if(filter_var($file, FILTER_VALIDATE_URL) !== FALSE){
			// ES URL, descargar y enviar.
			$url = TRUE;
			if($caption !== TRUE){
				$tmp = tempnam("/tmp", "telegram") .substr($file, -4); // .jpg
				file_put_contents($tmp, fopen($file, 'r'));
				$file = $tmp;
			}else{
				$caption = NULL;
			}
		}

		$this->method = "send" .ucfirst(strtolower($type));
		if(in_array($type, ["videonote", "video_note"])){
			$type = "video_note";
			$this->method = "sendVideoNote";
		}elseif($type == "chatphoto"){
			$type = "photo";
			$this->method = "sendChatPhoto";
		}
		if(file_exists(realpath($file))){
			$this->content[$type] = new \CURLFile(realpath($file));
		}else{
			$this->content[$type] = $file;
		}
		if($caption === NULL && isset($this->content['text'])){
			$caption = $this->content['text'];
			unset($this->content['text']);
		}
		if($caption !== NULL){
			$key = "caption";
			if($type == "audio"){ $key = "title"; }
			$this->content[$key] = $caption;
		}

		$output = $this->send("POSTKEEP");
		if($url === TRUE){ unlink($file); }
		if($keep === FALSE){ $this->_reset(); }
		$json = json_decode($output, TRUE);
		if($json){ return $json['result']; }
		return $output;
		// return $this;
	}

	public function location($lat, $lon = NULL, $live_period = NULL){
		if($live_period == NULL && $lon != NULL){ $live_period = $lon; }
		if(is_array($lat) && $lon == NULL){ $lon = $lat[1]; $lat = $lat[0]; }
		elseif(is_string($lat) && strpos($lat, ",") !== FALSE){
			$lat = explode(",", $lat);
			$lon = trim($lat[1]);
			$lat = trim($lat[0]);
		}
		$this->content['latitude'] = $lat;
		$this->content['longitude'] = $lon;
		if(
			is_numeric($live_period) && !is_float($live_period) &&
			$live_period >= 60 && $live_period <= 86400
		){
			$this->content['live_period'] = (int) $live_period;
		}
		$this->method = "sendLocation";
		return $this;
	}

	public function venue($title, $address, $foursquare = NULL){
		if(isset($this->content['latitude']) && isset($this->content['longitude'])){
			$this->content['title'] = $title;
			$this->content['address'] = $address;
			if(!empty($foursquare)){ $this->content['foursquare_id'] = $foursquare; }
			$this->method = "sendVenue";
		}
		return $this;
	}

	public function dump($user){
		var_dump($this->method); var_dump($this->content);
		$bm = $this->method;
		$bc = $this->content;

		$this->_reset();
		$this
			->chat($user)
			->text(json_encode($bc))
		->send();
		$this->method = $bm;
		$this->content = $bc;
		return $this;
	}

	public function contact($phone, $first_name, $last_name = NULL){
		$this->content['phone_number'] = $phone;
		$this->content['first_name'] = $first_name;
		if(!empty($last_name)){ $this->content['last_name'] = $last_name; }
		$this->method = "sendContact";
		return $this;
	}

	public function language($set){
		$this->language = $set;
		return $this;
	}

	public function text($text, $type = NULL){
		if(is_array($text)){
			if(isset($text[$this->language])){
				$text = $text[$this->language];
			}elseif(isset($text["en"])){
				$text = $text["en"];
			}else{
				$text = current($text); // First element.
			}
		}

		if($this->convert_emoji){ $text = $this->parent->emoji($text); }
		$this->content['text'] = $text;
		$this->method = "sendMessage";
		if($type === TRUE){ $this->content['parse_mode'] = 'Markdown'; }
		elseif(in_array($type, ['Markdown', 'HTML'])){ $this->content['parse_mode'] = $type; }
		elseif($text != strip_tags($text)){ $this->content['parse_mode'] = 'HTML'; } // Autodetect HTML.

		return $this;
	}

	public function text_replace($text, $replace, $type = NULL){
		if(is_array($text)){
			if(isset($text[$this->language])){
				$text = $text[$this->language];
			}elseif(isset($text["en"])){
				$text = $text["en"];
			}else{
				$text = current($text); // First element.
			}
		}

		if(strpos($text, "%s") !== FALSE){
			if(!is_array($replace)){ $replace = [$replace]; }
			$pos = 0;
			foreach($replace as $r){
				$pos = strpos($text, "%s", $pos);
				if($pos === FALSE){ break; }
				$text = substr_replace($text, $r, $pos, 2); // 2 = strlen("%s")
			}
		}else{
			$text = str_replace(array_keys($replace), array_values($replace), $text);
		}

		return $this->text($text, $type);
	}

	public function keyboard(){ return $this->_keyboard; }
	public function inline_keyboard(){ return $this->_inline; }
	public function payment($provider = "Stripe"){
		$this->_payment = new \Telegram\Payments\Stripe($this);
		return $this->_payment;
	}
	public function sticker($id = NULL){
		if(!empty($id)){ return $this->file('sticker', $id); }
		return $this->_sticker;
	}

	public function sticker_set($name, $chat = TRUE){
		$this->chat($chat);
		if(!empty($name)){ $this->content['sticker_set_name'] = $name; }
		$this->method = (empty($name) ? "delete" : "set") ."ChatStickerSet";
		return $this->send();
	}

	public function payment_precheckout($id, $ok = TRUE){
		$this->content['pre_checkout_query_id'] = $id;
		if($ok === TRUE){
			$this->content['ok'] = TRUE;
		}else{
			$this->content['ok'] = FALSE;
			$this->content['error_message'] = $ok;
		}

		$this->method = "answerPreCheckoutQuery";
		return $this->send();
	}

	public function force_reply($selective = TRUE){
		$this->content['reply_markup'] = ['force_reply' => TRUE, 'selective' => $selective];
		return $this;
	}

	public function caption($text){
		$this->content['caption'] = $text;
		return $this;
	}

	public function disable_web_page_preview($value = FALSE){
		if($value === TRUE){ $this->content['disable_web_page_preview'] = TRUE; }
		return $this;
	}

	public function notification($value = TRUE){
		if($value === FALSE){ $this->content['disable_notification'] = TRUE; }
		else{ if(isset($this->content['disable_notification'])){ unset($this->content['disable_notification']); } }
		return $this;
	}

	public function reply_to($message_id = NULL){
		if(is_bool($message_id) && $this->parent instanceof Receiver){
			if($message_id === TRUE or ($message_id === FALSE && !$this->parent->has_reply)){ $message_id = $this->parent->message; }
			elseif($message_id === FALSE){
				if(!$this->parent->has_reply){ return; }
				$message_id = $this->parent->reply->message_id;
			}
		}
		$this->content['reply_to_message_id'] = $message_id;
		return $this;
	}

	public function forward_to($chat_id_to){
		if(empty($this->chat()) or empty($this->content['message_id'])){ return $this; }
		$this->content['from_chat_id'] = $this->chat();
		$this->chat($chat_id_to);
		$this->method = "forwardMessage";

		return $this;
	}

	public function chat_action($type){
		$actions = [
			'typing', 'upload_photo', 'record_video', 'upload_video', 'record_audio', 'upload_audio',
			'upload_document', 'find_location', 'record_video_note', 'upload_video_note'
		];
		if(!in_array($type, $actions)){ $type = $actions[0]; } // Default is typing
		$this->content['action'] = $type;
		$this->method = "sendChatAction";
		return $this;
	}

	public function until_date($until){
		if(!is_numeric($until) and strtotime($until) !== FALSE){ $until = strtotime($until); }
		$this->content['until_date'] = $until;
		return $this;
	}

	public function kick($user = NULL, $chat = NULL, $keep = FALSE){
		$this->ban($user, $chat, $keep);
		return $this->unban($user, $chat, $keep);
	}

	public function restrict($option = NULL, $user = NULL, $chat = NULL){
		if(!empty($option) and strpos($option, "can_") === FALSE){ $option = "can_" . strtolower($option); }
		$this->method = "restrictChatMember";

		/* send_messages, send_media_messages,
		send_other_messages, add_web_page_previews */

		if($option == "can_none"){ // restrict none
			$this->content['can_send_other_messages'] = TRUE;
			$this->content['can_add_web_page_previews'] = TRUE;
		}elseif($option == "can_all"){ // restrict all = ban
			return $this->ban($user, $chat);
		}elseif(!empty($option)){
			$this->content[$option] = TRUE;
		}

		return $this->send();
	}

	public function restrict_until($until, $option = NULL, $user = NULL, $chat = NULL){
		return $this
			->until_date($until)
			->restrict($option, $user, $chat);
	}

	public function ban_until($until, $user = NULL, $chat = NULL, $keep = FALSE){
		return $this
			->until_date($until)
			->ban($user, $chat, $keep);
	}

	public function ban($user = NULL, $chat = NULL, $keep = FALSE){ return $this->_parse_generic_chatFunctions("kickChatMember", $keep, $chat, $user); }
	public function unban($user = NULL, $chat = NULL, $keep = FALSE){ return $this->_parse_generic_chatFunctions("unbanChatMember", $keep, $chat, $user); }
	public function leave_chat($chat = NULL, $keep = FALSE){ return $this->_parse_generic_chatFunctions("leaveChat", $keep, $chat); }
	public function get_chat($chat = NULL, $keep = FALSE){
		$res = $this->_parse_generic_chatFunctions("getChat", $keep, $chat);
		if($res !== FALSE){ $res['user'] = new User($res['user']); }
		return $res;
	}
	public function get_admins($chat = NULL, $keep = FALSE){
		$res = $this->_parse_generic_chatFunctions("getChatAdministrators", $keep, $chat);
		if($res !== FALSE){
			foreach($res as $k => $data){
				$res[$k]['user'] = new User($data['user']);
			}
		}
		return $res;
	}
	public function get_member_info($user = NULL, $chat = NULL, $keep = FALSE){
		$res = $this->_parse_generic_chatFunctions("getChatMember", $keep, $chat, $user);
		if($res !== FALSE){ $res['user'] = new User($res['user']); }
		return $res;
	}
	public function get_members_count($chat = NULL, $keep = FALSE){ return $this->_parse_generic_chatFunctions("getChatMembersCount", $keep, $chat); }
	public function get_chat_link($chat = NULL, $keep = FALSE){ return $this->_parse_generic_chatFunctions("exportChatInviteLink", $keep, $chat); }
	public function get_user_avatar($user = NULL, $offset = NULL, $limit = 100){
		if(!empty($user)){ $this->user($user); }
		$this->content['offset'] = $offset;
		$this->content['limit'] = $limit;
		$this->method = "getUserProfilePhotos";

		$res = $this->send($keep);
		if(!isset($res['photos']) or empty($res['photos'])){ return FALSE; }
		return $res['photos'];
	}

	public function set_title($text){
		$this->method = "setChatTitle";
		if($this->convert_emoji){ $text = $this->parent->emoji($text); }
		$this->content['title'] = $text;
		return $this->send();
	}

	public function set_description($text = ""){
		$this->method = "setChatDescription";
		if($this->convert_emoji){ $text = $this->parent->emoji($text); }
		$this->content['description'] = $text;
		return $this->send();
	}

	public function set_photo($path = FALSE){
		if($path === NULL or $path === FALSE){
			$this->method = "deleteChatPhoto";
			return $this->send();
		}
		return $this->file("chatphoto", $path);
	}

	public function promote($vars, $user = NULL, $defval = TRUE){
		if(!empty($user)){ $this->user($user); }
		if(!is_array($vars)){ $vars = [$vars]; }

		/* post_messages, edit_messages, delete_messages, pin_messages,
		change_info, invite_users, restrict_members, promote_members */

		$this->method = "promoteChatMember";
		foreach($vars as $k => $v){
			$key = (is_numeric($k) ? $v : $k);
			$value = (!is_numeric($k) and is_bool($v) ? $v : $defval);

			if(strpos($key, "can_") === FALSE){ $key = "can_" . $key; }
			$key = strtolower($key);

			$this->content[$key] = (bool) $value;
		}

		return $this;
	}

	// Alias for promote but negative
	public function demote($vars, $user = NULL){ return $this->promote($vars, $user, FALSE); }

	public function pin_message($message = NULL){
		$this->method = "pinChatMessage";

		if($message === FALSE){
			$this->method = "un" . $this->method; // unpin
			return $this->send();
		}

		if(!empty($message)){ $this->message($message); }
		return $this->send();
	}

	// DEBUG
	/* function get_message($message, $chat = NULL){
		$this->method = 'getMessage';
		if(empty($chat) && !isset($this->content['chat_id'])){
			$this->content['chat_id'] = $this->parent->chat->id;
		}

		return $this->send();
	} */

	public function answer_callback($alert = FALSE, $text = NULL, $id = NULL){
		// Function overload :>
		// $this->text can be empty. (Answer callback with empty response to finish request.)
		if($text == NULL && $id == NULL){
			$text = $this->content['text'];
			if($this->parent instanceof Receiver && $this->parent->key == "callback_query"){
				$id = $this->parent->id;
			}
			if(empty($id)){ return $this; } // HACK
			$this->content['callback_query_id'] = $id;
			if($this->convert_emoji){ $text = $this->parent->emoji($text); }
			$this->content['text'] = $text;
			$this->content['show_alert'] = $alert;
			$this->method = "answerCallbackQuery";
		}

		return $this->send();
	}

	public function media($type, $media, $extras = NULL){
		if(!in_array($type, ['photo', 'video', 'animation', 'audio', 'document'])){ return FALSE; }
		// TODO upload media file
		$data = [
			'type' => $type,
			'media' => $media
		];

		// Change text to caption
		if(isset($this->content['text'])){
			$this->content['caption'] = $this->content['text'];
			unset($this->content['text']);
		}

		// Move current set data to media
		foreach(['caption', 'parse_mode'] as $key){
			if(isset($this->content[$key])){
				$data[$key] = $this->content[$key];
				unset($this->content[$key]);
			}
		}

		// Set extra data
		if(is_array($extras)){
			foreach($extras as $k => $v){
				$data[$k] = $v;
			}
		}

		$this->content['media'] = $data;
		return $this;
	}

	public function edit($type){
		// if(!in_array($type, ['text', 'message', 'caption', 'keyboard', 'inline', 'markup', 'location', 'livelocation'])){ return FALSE; }
		if(isset($this->content['text']) && in_array($type, ['text', 'message'])){
			$this->method = "editMessageText";
		}elseif($type == "caption"){
			$this->method = "editMessageCaption";
			if(array_key_exists('text', $this->content) and !isset($this->content['caption'])){
				$this->content['caption'] = $this->content['text'];
				unset($this->content['text']);
			}
			if(!isset($this->content['caption'])){ return FALSE; }
		}elseif(isset($this->content['inline_keyboard']) && in_array($type, ['keyboard', 'inline', 'markup'])){
			$this->method = "editMessageReplyMarkup";
		}elseif(isset($this->content['latitude']) && isset($this->content['longitude']) && in_array($type, ['location', 'livelocation'])){
			$this->method = "editMessageLiveLocation";
		}elseif(in_array($type, ['location', 'livelocation'])){
			$this->method = "stopMessageLiveLocation";
		}elseif(isset($this->content['media']) && $type == 'media'){
			$this->method = "editMessageMedia";
		}else{
			return FALSE;
		}

		return $this->send();
	}

	public function delete($message = NULL, $chat = NULL){
		if($message === TRUE or (empty($message) && !isset($this->content['message_id']))){
			$this->message(TRUE);
		}elseif(is_array($message) and isset($message["message_id"])){
			$this->message($message["message_id"]);
		}elseif(!empty($message)){
			$this->message($message);
		}

		if($message === TRUE or (empty($chat) && !isset($this->content['chat_id']))){
			$this->chat(TRUE);
		}elseif(!empty($chat)){
			$this->chat($chat);
		}

		$this->method = "deleteMessage";
		return $this->send();
	}

	public function game($name, $notification = FALSE){
		$this->content['game_short_name'] = $name;
		$this->content['disable_notification'] = (bool) $notification;

		$this->method = "sendGame";
		return $this;
	}

	public function game_score($user, $score = NULL, $force = FALSE, $edit_message = TRUE){
		$this->user($user);

		if($score == NULL){
			$this->method = "getGameHighScores";
			return $this;
		}

		$this->content['score'] = (int) $score;
		if($force){ $this->content['force'] = (bool) $force; }
		if(!$edit_message){ $this->content['disable_edit_message'] = FALSE; }

		$this->method = "setGameScore";
		return $this;
	}

	public function _push($key, $val){
		$this->content[$key] = $val;
		return $this;
	}

	public function _push_method($name){
		$this->method = $name;
		return $this;
	}

	private function _reset(){
		$this->method = NULL;
		$this->content = array();
	}

	private function _url($with_method = FALSE, $host = "api.telegram.org"){
		$url = ("https://$host/bot" .$this->bot->id .':' .$this->bot->key .'/');
		if($with_method){ $url .= $this->method; }
		return $url;
	}

	public function send($keep = FALSE, $_broadcast = FALSE){
		if($this->timeout > time()){
			sleep($this->timeout - time());
		}
		if(!empty($this->broadcast) and !$_broadcast){
			$result = array();
			if(in_array(strtoupper($keep), ["POST", "POSTKEEP"])){ $keep = "POSTKEEP"; }
			else{ $keep = TRUE; }
			foreach($this->broadcast as $chat){
				$this->chat($chat);
				// Send and keep data
				$result[] = $this->send($keep, TRUE);
			}
			return $result;
		}

		if(empty($this->method)){ return FALSE; }
		if(empty($this->chat()) && $this->parent instanceof Receiver){ $this->chat($this->parent->chat->id); }

		$post = FALSE;

		if(is_string($keep)){
			$keep = strtoupper($keep);
			if($keep == "POST"){ $keep = FALSE; $post = TRUE; }
			elseif($keep = "POSTKEEP"){ $keep = TRUE; $post = TRUE; }
		}

		$result = $this->Request($this->method, $this->content, $post);
		if($keep === FALSE){ $this->_reset(); }
		return $result;
	}

	private function _parse_generic_chatFunctions($action, $keep, $chat, $user = FALSE){
		$this->method = $action;
		if($user === FALSE){ // No hay user.
			if(empty($chat) && empty($this->chat())){ return FALSE; }
		}else{
			if(empty($user) && empty($chat) && (empty($this->chat()) or empty($this->user()))){ return FALSE; }
		}
		if(!empty($chat)){ $this->chat($chat); }
		if(!empty($user)){ $this->user($user); }
		return $this->send($keep);
		// return $this;
	}

	private function RequestWebhook($method, $parameters) {
		if (!is_string($method)) {
			error_log("Method name must be a string\n");
			return false;
		}

		if (!$parameters) {
			$parameters = array();
		} else if (!is_array($parameters)) {
			error_log("Parameters must be an array\n");
			return false;
		}

		$parameters["method"] = $method;

		header("Content-Type: application/json");
		echo json_encode($parameters);
		return true;
	}

	private function exec_curl_request($handle) {
		$response = curl_exec($handle);

		if ($response === false) {
			$errno = curl_errno($handle);
			$error = curl_error($handle);
			error_log("Curl returned error $errno: $error\n");
			curl_close($handle);
			return false;
		}

		$http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
		curl_close($handle);

		if ($http_code >= 500) {
		// do not wat to DDOS server if something goes wrong
			sleep(10);
			return false;
		} else if ($http_code != 200) {
			$response = json_decode($response, true);
			error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
			if ($http_code == 429) {
				if(isset($response['parameters']['retry_after'])){
					$this->timeout = time() + (int) $response['parameters']['retry_after'];
				}
			} else if ($http_code == 401) {
				throw new \Exception('Invalid access token provided');
			}
			return false;
		} else {
			$response = json_decode($response, true);
			if (isset($response['description'])) {
				error_log("Request was successfull: {$response['description']}\n");
			}
			$response = $response['result'];
		}

		return $response;
	}

	private function Request($method, $parameters, $post = FALSE) {
	
	global $ct;
	
		if (!is_string($method)) {
			error_log("Method name must be a string\n");
			return false;
		}

		if (!$parameters) {
			$parameters = array();
		} else if (!is_array($parameters)) {
			error_log("Parameters must be an array\n");
			return false;
		}

		foreach ($parameters as $key => &$val) {
		// encoding to JSON array parameters, for example reply_markup
			if (!is_numeric($val) && !is_string($val) && !($val instanceof \CURLFile) ) {
				$val = json_encode($val);
			}
		}

		$url = $this->_url(TRUE);
		if(!$post){ $url .= '?'.http_build_query($parameters); }

		$handle = curl_init($url);
     
     
          // If this is a windows desktop edition
          if ( file_exists($ct['base_dir'] . '/cache/other/win_curl_cacert.pem') ) {
          curl_setopt($handle, CURLOPT_CAINFO, $ct['base_dir'] . '/cache/other/win_curl_cacert.pem');
          }
      
      
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($handle, CURLOPT_TIMEOUT, 60);
		if($this->use_internal_resolver){
			$apihosts = [
			    "149.154.167.197",
			    "149.154.167.198",
			    "149.154.167.199",
			    "149.154.167.200"
			];

			$apihost = $apihosts[mt_rand(0,count($apihosts) - 1)];
			curl_setopt($handle, CURLOPT_RESOLVE, ["api.telegram.org:443:$apihost"]);
		}

		if($post){
			curl_setopt($handle, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
			curl_setopt($handle, CURLOPT_POSTFIELDS, $parameters);
		}

		return $this->exec_curl_request($handle);
	}
}

?>
