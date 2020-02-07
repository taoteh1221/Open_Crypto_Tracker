<?php

namespace Telegram;

class Receiver {

	public function __construct($uid = NULL, $key = NULL, $name = NULL){
		$this->user = new User(NULL);
		$this->chat = new Chat(NULL);

		$this->process();
		if(!empty($uid)){
			if($uid instanceof Bot){
				$this->bot = $uid;
			}else{
				$this->set_access($uid, $key, $name);
			}
		}
		$this->send = new Sender($this);
	}

	private $raw;
	private $data = array();
	public $bot = array();
	public $key = NULL;
	public $id = NULL;
	public $message = NULL; // DEPRECATED
	public $message_id = NULL;
	public $timestamp = 0;
	public $chat = NULL;
	public $user = NULL;
	public $language = NULL;
	public $entities = NULL;
	public $reply = NULL;
	public $new_user = NULL;
	public $new_users = array();
	public $left_user = NULL;
	public $reply_user = NULL;
	public $forward_user = NULL;
	public $has_reply = FALSE;
	public $has_forward = FALSE;
	public $is_edit = FALSE;
	public $edit_date = NULL;
	public $reply_is_forward = FALSE;
	public $caption = NULL;
	public $offset = NULL; // inline query
	public $callback = FALSE;
	public $send = FALSE; // Class
	public $migrate_chat = NULL;
	public $input = NULL; // Text Regex Match
	public $author_signature = NULL;
	public $forward_signature = NULL;
	public $emojis = array();

	private function set_access($uid, $key = NULL, $name = NULL){
		$this->bot = new Bot($uid, $key, $name);

		// Set sender
		$this->send = new Sender($this->bot);
		return $this;
	}

	public function process($content = NULL){
		if($content === NULL){
			$content = file_get_contents("php://input");
		}

		if(!empty($content)){
			$this->raw = $content;
			$this->data = json_decode($content, TRUE);
			$this->id = $this->data['update_id'];
			if(isset($this->data['message']) or isset($this->data['edited_message'])){
				$this->key = (isset($this->data['edited_message']) ? "edited_message" : "message");
				if($this->key == "edited_message"){
					$this->is_edit = TRUE;
					$this->edit_date = $this->data[$this->key]['edit_date'];
				}
				$this->message = $this->data[$this->key]['message_id']; // DEPRECATED
				$this->message_id = intval($this->data[$this->key]['message_id']);
				$this->timestamp = $this->data[$this->key]['date']; // HACK Tener en cuenta edit_date
				$this->chat = new Chat($this->data[$this->key]['chat']);
				$this->user = new User($this->data[$this->key]['from']);
				if(isset($this->data[$this->key]['from']['language_code'])){
					$this->language = $this->data[$this->key]['from']['language_code'];
					if(strpos($this->language, "-") !== FALSE){
						$this->language = explode("-", $this->language);
						$this->language = strtolower($this->language[0]);
					}
				}
				if(isset($this->data[$this->key]['caption'])){
					$this->caption = $this->data[$this->key]['caption'];
				}
				if(isset($this->data[$this->key]['reply_to_message'])){
					$this->has_reply = TRUE;
					$this->reply_user = new User($this->data[$this->key]['reply_to_message']['from']);
					$this->reply = (object) $this->data[$this->key]['reply_to_message'];
					$this->reply_is_forward = (isset($this->data[$this->key]['reply_to_message']['forward_from']));
					if($this->reply_is_forward){
						$this->reply->forward_from = new User($this->data[$this->key]['reply_to_message']['forward_from']);
						// No se puede hacer reply a un forward con otro forward,
						// por lo que no hay problema en hacer esto.
						$this->forward_user = new User($this->data[$this->key]['reply_to_message']['forward_from']);
						if(isset($this->data[$this->key]['reply_to_message']['forward_from_chat'])){
							$this->reply->forward_from_chat = new Chat($this->data[$this->key]['reply_to_message']['forward_from_chat']);
						}
						$this->forward_signature = $this->data[$this->key]['reply_to_message']['forward_signature'];
					}
				}
				if(isset($this->data[$this->key]['forward_from']) or isset($this->data[$this->key]['forward_from_chat'])){
					$this->has_forward = TRUE;
					if(isset($this->data[$this->key]['forward_from'])){
						$this->forward_user = new User($this->data[$this->key]['forward_from']);
					}
				}
				if(isset($this->data[$this->key]['new_chat_members'])){
					foreach($this->data[$this->key]['new_chat_members'] as $user){
						$this->new_users[] = new User($user);
					}
					$this->new_user = $this->new_users[0]; // COMPATIBILITY: Tal y como hace Telegram, se agrega el primer usuario.
				// DEPRECTAED en un futuro?
				}elseif(isset($this->data[$this->key]['new_chat_member'])){
					$this->new_user = new User($this->data[$this->key]['new_chat_member']);
					$this->new_users = [$this->new_user];
				}elseif(isset($this->data[$this->key]['left_chat_member'])){
					// DEPRECATED
					$this->new_user = new User($this->data[$this->key]['left_chat_member']);
					$this->left_user = $this->new_user;
				}elseif(isset($this->data[$this->key]['migrate_to_chat_id'])){
					$this->migrate_chat = $this->data[$this->key]['migrate_to_chat_id'];
				}elseif(isset($this->data[$this->key]['migrate_from_chat_id'])){
					$this->migrate_chat = $this->data[$this->key]['migrate_from_chat_id'];
				}
				if(isset($this->data[$this->key]['entities'])){
					foreach($this->data[$this->key]['entities'] as $ent){
						$this->entities[] = new Elements\MessageEntity($ent, $this->text());
					}
				}
			}elseif(isset($this->data['callback_query'])){
				$this->key = "callback_query";
				$this->id = $this->data[$this->key]['id'];
				$this->message = $this->data[$this->key]['message']['message_id']; // DEPRECATED
				$this->message_id = $this->data[$this->key]['message']['message_id'];
				$this->chat = new Chat($this->data[$this->key]['message']['chat']);
				$this->user = new User($this->data[$this->key]['from']);
				$this->callback = $this->data[$this->key]['data'];
			}elseif(isset($this->data['channel_post']) or isset($this->data['edited_channel_post'])){
				$this->key = (isset($this->data['edited_channel_post']) ? "edited_channel_post" : "channel_post");
				if($this->key == "edited_channel_post"){
					$this->is_edit = TRUE;
					$this->edit_date = $this->data[$this->key]['edit_date'];
				}
				$this->id = $this->data['update_id'];
				$this->message_id = $this->data[$this->key]['message_id'];
				$this->timestamp = $this->data[$this->key]['date'];
				$this->chat = (object) $this->data[$this->key]['chat'];

				if(isset($this->data[$this->key]['from'])){
					$this->user = (object) $this->data[$this->key]['from'];
				}
			}elseif(isset($this->data['inline_query'])){
				$this->key = "inline_query";
				$this->id = $this->data[$this->key]['id'];
				// $this->message_id = $this->data[$this->key]['id'];
				$this->user = new User($this->data[$this->key]['from']);
				$this->chat = new Chat($this->data[$this->key]['from']); // Compatibility, but not set
				$this->offset = $this->data[$this->key]['offset'];
			}
		}
	}

	public function text_message(){
		if($this->key == "callback_query"){ return $this->data[$this->key]['message']['text']; }
		elseif($this->has_reply){ return $this->data[$this->key]['reply_to_message']['text']; }
		return NULL;
	}

	public function text($clean = FALSE){
		$text = @$this->data[$this->key]['text'];
		if($this->key == "callback_query"){
			$text = @$this->data[$this->key]['data'];
			if(substr($text, 0, 2) != "T:"){ return NULL; }
			$text = substr($text, 2);
		}
		if($clean === TRUE){ $text = $this->clean('alphanumeric-full-spaces', $text); }
		return $text;
	}

	public function text_query(){
		if($this->key != "inline_query"){ return FALSE; }
		$text = $this->data[$this->key]['query'];
		if(empty($text)){ $text = NULL; }
		return $text;
	}

	public function text_query_has($input, $next_word = NULL, $position = NULL){
		$text = $this->text_query();
		if(empty($text)){ return FALSE; }
		return $this->text_has($input, $next_word, $position, $text, TRUE);
	}

	public function text_encoded($clean_quotes = FALSE){
		$t = json_encode($this->text(FALSE));
		if($clean_quotes){ $t = substr($t, 1, -1); }
		return $t;
	}

	public function text_contains($input, $strpos = NULL){
		if(!is_array($input)){ $input = array($input); }
		$text = strtolower($this->text());
		$text = $this->text_cleanup_prepare($text, FALSE);
		foreach($input as $i){
			$j = $this->text_cleanup_prepare($i, FALSE);
			if(
				($strpos === NULL and strpos($text, strtolower($j)) !== FALSE) or // Buscar cualquier coincidencia
				($strpos === TRUE and strpos($text, strtolower($j)) === 0) or // Buscar textualmente eso al principio
				($strpos === FALSE and strpos($this->text(), $i) === 0) or // Buscar textualmente al principio + CASE sensitive
				($strpos !== NULL and strpos($text, strtolower($j)) == $strpos) // Buscar por strpos
			){
				return TRUE;
			}
		}
		return FALSE;
	}

	private function text_cleanup_prepare($input, $tolower = TRUE){
		if($tolower){ $input = strtolower($input); }
		$vocals = [
			"á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u",
			"à" => "a", "è" => "e", "ì" => "i", "ò" => "o", "ù" => "u",
			"Á" => "A", "É" => "E", "Í" => "I", "Ó" => "O", "Ú" => "U",
			"À" => "A", "È" => "E", "Ì" => "I", "Ò" => "O", "Ù" => "U"
		];
		$input = str_replace(array_keys($vocals), array_values($vocals), $input);
		$input = str_replace("%20", " ", $input); // HACK web
		if($tolower){ $input = strtolower($input); }
		return $input;
	}

	public function text_has($input, $next_word = NULL, $position = NULL, $text = NULL, $cleanup = TRUE){
		// A diferencia de text_contains, esto no será valido si la palabra no es la misma.
		// ($input = "fanta") -> fanta OK , fanta! OK , fantasma KO
		if(!is_array($input)){ $input = array($input); }
		if(empty($input)){ return FALSE; }
		// FIXME si algun input contiene un PIPE | , ya me ha jodio. Controlarlo.

		$input = implode("|", $input);
		$input = $this->text_cleanup_prepare($input, TRUE);
		$input = str_replace("/", "\/", $input); // CHANGED fix para escapar comandos y demás.

		if(is_bool($next_word)){ $position = $next_word; $next_word = NULL; }
		elseif($next_word !== NULL){
			if(!is_array($next_word)){ $next_word = array($next_word); }
			$next_word = implode("|", $next_word);
			$next_word = $this->text_cleanup_prepare($next_word, TRUE);
			$next_word = str_replace("/", "\/", $next_word); // CHANGED
		}

		// Al principio de frase
		if($position === TRUE){
			if($next_word === NULL){ $regex = "^(" .$input .')([\s!.,"]?)'; }
			else{ $regex = "^(" .$input .')([\s!.,"]?)\s(' .$next_word .')([\s!?.,"]?)'; }
		// Al final de frase
		}elseif($position === FALSE){
			if($next_word === NULL){ $regex = "(" .$input .')([!?,."]?)$'; }
			else{ $regex = "(" .$input .')([\s!.,"]?)\s(' .$next_word .')([?!.,"]?)$'; }
		// En cualquier posición
		}else{
			if($next_word === NULL){ $regex = "(" .$input .')([\s!?.,"])|(' .$input .')$'; }
			else{ $regex = "(" .$input .')([\s!.,"]?)\s(' .$next_word .')([\s!?.,"])|(' .$input .')([\s!.,"]?)\s(' .$next_word .')([!?.,"]?)$'; }
		}

		if($text === NULL){ $text = strtolower($this->text()); }
		if($cleanup){
			$text = $this->text_cleanup_prepare($text, FALSE);
			$text = strtolower($text);
		}
		return preg_match("/$regex/", $text);
	}

	// WIP TODO
	public function text_has_emoji($emoji = NULL, $return = FALSE){
		if(empty($emoji)){
			return (strpos($this->text_encoded(), '\u') !== FALSE);
		}elseif(is_array($emoji)){
			foreach($emoji as $e){
				if(empty($e)){ continue; }
				$r = $this->text_has_emoji($e, $return);
				if($r !== FALSE){ return $r; }
			}
			return FALSE;
		}
		if(in_array(substr($emoji, 0, 1), [':', '\\'])){ $emoji = $this->emoji($emoji); }
		$text = $this->text();
		return (strpos($text, $emoji) !== FALSE);
	}

	public function text_regex($expr, $cleanup = TRUE){
		if(!is_array($expr)){
			if(empty(trim($expr))){ return FALSE; }
			$expr = [$expr];
		}
		$text = $this->text();
		if($cleanup){ $text = $this->text_cleanup_prepare($text, FALSE); }
		$repls = [
			'/\{N:(\w+)\}/i' => '(?P<$1>[\\d]+)',
			'/\{S:(\w+)\}/i' => '(?P<$1>[\\w\\s?]+)',
			'/\{SL:(.+):(\w+)\}/i' => '(?P<$2>[\\w\\s?]+)$1',
			'/\{(\w+)\}/i'   => '(?P<$1>[^\\s]+)',
		];

		foreach($expr as $ex){
			$ex = preg_replace(array_keys($repls), array_values($repls), $ex);
			$r = preg_match_all("/$ex/i", $text, $matches);
			if($r){
				foreach($matches as $k => $v){
					if(is_numeric($k) and $k != 0){
						unset($matches[$k]);
						continue;
					}
					$matches[$k] = current($v); // Get value, not array
				}
				$this->input = (object) $matches;
				return $r;
			}
		}
		return FALSE;
	}

	public function text_mention($user = NULL){
		// Incluye users registrados y anónimos.
		// NULL -> decir si hay usuarios mencionados o no (T/F)
		// TRUE -> array [ID => @nombre o nombre]
		// NUM -> decir si el NUM ID usuario está mencionado o no, y si es @nombre, parsear para validar NUM ID.
		// STR -> decir si nombre o @nombre está mencionado o no.
		if(empty($this->entities)){ return FALSE; }
		$users = array();
		$text = $this->text(FALSE); // No UTF-8 clean
		foreach($this->entities as $e){
			if($e->type == 'text_mention'){
				$users[] = [$e->user->id => $e->value];
			}elseif($e->type == 'mention'){
				$u = trim($e->value); // @username
				// $d = $this->send->get_member_info($u); HACK
				$d = FALSE;
				$users[] = ($d === FALSE ? $u : [$d['user']['id'] => $u] );
			}
		}
		if($user == NULL){ return (count($users) > 0 ? $users[0] : FALSE); }
		if($user === TRUE){ return $users; }
		if(is_numeric($user)){
			if($user < count($users)){
				$k = array_keys($users);
				$v = array_values($users);
				return [ $k[$user] => $v[$user] ];
			}
			return in_array($user, array_keys($users));
		}
		if(is_string($user)){ return in_array($user, array_values($users)); }
		return FALSE;
	}

	public function text_email($email = NULL){
		// NULL -> saca el primer mail o FALSE.
		// TRUE -> array [emails]
		// STR -> email definido.
		if(empty($this->entities)){ return FALSE; }
		$emails = array();
		$text = $this->text(FALSE); // No UTF-8 clean
		foreach($this->entities as $e){
			if($e->type == 'email'){ $emails[] = strtolower($e->value); }
		}
		if($email == NULL){ return (count($emails) > 0 ? $emails[0] : FALSE); }
		if($email === TRUE){ return $emails; }
		if(is_string($email)){ return in_array(strtolower($email), $emails); }
		return FALSE;
	}

	public function text_command($cmd = NULL, $begin = TRUE){
		// NULL -> saca el primer comando o FALSE.
		// TRUE -> array [comandos]
		// STR -> comando definido.
		// $begin -> si es comando inicial
		// $begin STR -> si es comando con ese parametro
		if(empty($this->entities)){ return FALSE; }
		if($cmd === FALSE){ $begin = FALSE; $cmd = NULL; }
		$cmds = array();
		$initbegin = FALSE;
		foreach($this->entities as $e){
			if($e->type == 'bot_command'){
				$cmds[] = strtolower($e->value);
				if($initbegin == FALSE && $e->offset == 0){ $initbegin = TRUE; }
			}
		}
		if($cmd == NULL){
			if(count($cmds) > 0){
				if($begin === TRUE && !$initbegin){ return FALSE; }
				return $cmds[0];
			}
			return FALSE;
		}
		if($cmd === TRUE){ return $cmds; }
		if(is_string($cmd)){ $cmd = [$cmd]; }
		if(is_array($cmd)){
			foreach($cmd as $csel){
				if($csel[0] != "/"){ $csel = "/" .$csel; }
				$csel = strtolower($csel);
				if(in_array($csel, $cmds) && strpos($csel, "@") === FALSE){
					if(is_string($begin)){
						if(!$initbegin){ return FALSE; } // Only commands at begin
						$text = strtolower($this->text(FALSE)); // No UTF-8 clean
						return (preg_match('/^\\' ."$csel $begin" .'($|\s\w+)/i', $text));
					}
					return !($begin && !$initbegin);
				}
				// Add with bot name
				$name = strtolower($this->bot->username);
				if($name){
					if($name[0] != "@"){ $name = "@" .$name; }
					$csel = $csel.$name;
				}
				if(in_array($csel, $cmds)){
					if(is_string($begin)){
						if(!$initbegin){ return FALSE; } // Only commands at begin
						$text = strtolower($this->text(FALSE)); // No UTF-8 clean
						return (preg_match('/^\\' ."$csel $begin" .'($|\s\w+)/i', $text));
					}
					return !($begin && !$initbegin);
				}
			}
		}
		return FALSE;
	}

	public function text_hashtag($tag = NULL){
		// NULL -> saca el primer hashtag o FALSE.
		// TRUE -> array [hashtags]
		// STR -> hashtag definido.
		if(empty($this->entities)){ return FALSE; }
		$hgs = array();
		$text = $this->text(FALSE); // No UTF-8 clean
		foreach($this->entities as $e){
			if($e->type == 'hashtag'){ $hgs[] = strtolower($e->value); }
		}
		if($tag == NULL){ return (count($hgs) > 0 ? $hgs[0] : FALSE); }
		if($tag === TRUE){ return $hgs; }
		if(is_string($tag)){
			if($tag[0] != "#"){ $tag = "#" .$tag; }
			return in_array(strtolower($tag), $hgs);
		}
		return FALSE;
	}

	public function text_url($cmd = NULL){
		// NULL -> saca la primera URL o FALSE.
		// TRUE -> array [URLs]
		if(empty($this->entities)){ return FALSE; }
		$cmds = array();
		$text = $this->text(FALSE); // No UTF-8 clean
		foreach($this->entities as $e){
			if($e->type == 'url'){ $cmds[] = $e->value; }
		}
		if($cmd == NULL){ return (count($cmds) > 0 ? $cmds[0] : FALSE); }
		if($cmd === TRUE){ return $cmds; }
		return FALSE;
	}

	public function last_word($clean = FALSE){
		$text = $this->words(TRUE);
		if($clean === TRUE){ $clean = 'alphanumeric-accent'; }
		return $this->clean($clean, array_pop($text));
	}

	public function words($position = NULL, $amount = 1, $filter = FALSE){ // Contar + recibir argumentos
		if($position === NULL){
			return count(explode(" ", $this->text()));
		}elseif($position === TRUE){
			return explode(" ", $this->text());
		}elseif(is_numeric($position)){
			if($amount === TRUE){ $filter = 'alphanumeric'; $amount = 1; }
			elseif(is_string($amount)){ $filter = $amount; $amount = 1; }
			$t = explode(" ", $this->text());
			$a = $position + $amount;
			$str = '';
			for($i = $position; $i < $a; $i++){
				$str .= $t[$i] .' ';
			}
			if($filter !== FALSE){ $str = $this->clean($filter, $str); }
			return trim($str);
		}
	}

	public function word_position($find){
		$text = $this->text();
		if(empty($text)){ return FALSE; }
		$pos = strpos($text, $find);
		if($pos === FALSE){ return FALSE; }
		$text = substr($text, 0, $pos);
		return count(explode(" ", $text));
	}

	public function clean($pattern = 'alphanumeric-full', $text = NULL){
		$pats = [
			'number' => '/^[0-9]+/',
			'number-calc' => '/^([+-]?)\d+(([\.,]?)\d+?)/',
			'alphanumeric' => '/[^a-zA-Z0-9]+/',
			'alphanumeric-accent' => '/[^a-zA-Z0-9áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙ]+/',
			'alphanumeric-symbols-basic' => '/[^a-zA-Z0-9\._\-]+/',
			'alphanumeric-full' => '/[^a-zA-Z0-9áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙ\._\-]+/',
			'alphanumeric-full-spaces' => '/[^a-zA-Z0-9áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙ\.\s_\-]+/',
		];
		if(empty($text)){ $text = $this->text(); }
		if($pattern == FALSE){ return $text; }
		if(!isset($pats[$pattern])){ return FALSE; }
		return preg_replace($pats[$pattern], "", $text);
	}

	/**
	 *  Return date of message.
	 *  TRUE = diff time() - Telegram timestamp.
	 *  NULL = return date format.
	 *  int = diff int time() - Telegram timestamp.
	 *  string date = diff date - Telegram timestamp.
	 *  string date_format = return specified date format.
	 */
	public function date($parse = NULL, $time = NULL){
		if(empty($time)){ $time = $this->timestamp; }
		if(empty($time)){ $time = time(); } // TEMP HACK Si no hay timestamp.

		if($parse === NULL){ return date("Y-m-d H:i:s", $time); }
		elseif($parse === TRUE){ $parse = time(); }

		if(is_numeric($parse)){
			// timestamp, diff time.
			return ($parse - $time);
		}else{
			$date = strtotime($parse);
			if($date > 0){
				// Diff with timestamp
				return ($date - $time);
			}
			// Parse date format
			return date($parse, $time);
		}
	}

	public function progressbar($val, $max = 100, $chars = 12, $chfull = NULL, $chempty = NULL){
		$chfull  = (empty($chfull) ? "\u{2588}" : $this->emoji($chfull));
		$chempty = (empty($chempty) ? "\u{2592}" : $this->emoji($chempty));

		$nfull = floor(($val / $max) * $chars);
		if($nfull < 0){ $nfull = 0; }
		$nempty = max(($chars - $nfull), 0);

		$str = "";
		for($i = 0; $i < $nfull; $i++){ $str .= $chfull; }
		for($i = 0; $i < $nempty; $i++){ $str .= $chempty; }

		return $str;
	}

	public function is_chat_group(){ return isset($this->chat->type) && in_array($this->chat->type, ["group", "supergroup"]); }
	public function data_received($expect = NULL){
		if($expect !== NULL){
			return (isset($this->data[$this->key][$expect]));
		}
		$data = [
			"migrate_to_chat_id", "migrate_from_chat_id",
			"new_chat_participant", "left_chat_participant", "new_chat_members", "new_chat_member", "left_chat_member",
			"reply_to_message", "text", "audio", "document", "photo", "video_note", "voice", "location", "contact"
		];
		foreach($data as $t){
			if(isset($this->data[$this->key][$t])){
				if($expect == NULL or $expect == $t){ return $t; }
			}
		}
		return FALSE;
	}

	public function forward_type($expect = NULL){
		if(!$this->has_forward){ return FALSE; }
		$type = $this->data['message']['forward_from_chat']['type'];
		if($expect !== NULL){ return (strtolower($expect) == $type); }
		return $type;
	}

	public function is_bot($user = NULL){
		if($user === NULL){ $user = $this->user->username; }
		elseif($user === TRUE && $this->has_reply){ $user = $this->reply_user->username; }
		elseif($user instanceof User){
			if($user->is_bot){ return $user->is_bot; }
			$user = $user->username;
		}
		return (!empty($user) && substr(strtolower($user), -3) == "bot");
		// TODO Si realmente es un bot y se intenta hacer un chatAction, no debería dejar.
		// A no ser que ese usuario también haya bloqueado al bot.
	}

	// NOTE: Solo funcionará si el bot está en el grupo.
	public function user_in_chat(&$user, $chat = NULL, $object = FALSE){
		if($chat === TRUE){ $object = TRUE; $chat = NULL; }
		if(empty($chat)){ $chat = $this->chat; }
		if($chat instanceof Chat){ $chat = $chat->id; }

		$uid = $user;
		if($user instanceof User){ $uid = $user->id; }
		$info = $this->send->get_member_info($uid, $chat);
		$ret = ($object ? (object) $info : $info);

		// TODO CHECK DATA
		if($user instanceof User && $info !== FALSE){
			$user->status = $info['status'];
		}

		if(
			$info === FALSE or
			in_array($info['status'], ['left', 'kicked']) or
			(
				$info['status'] == 'restricted' and
				$info['until_date'] == 0 and
				!$info['can_send_messages']
			)
		){ return FALSE; }

		return TRUE;
	}

	// TODO Join function and deprecate
	public function grouplink($text, $url = FALSE){
		$link = "https://t.me/";
		if($text[0] != "@" and strlen($text) == 22){
			$link .= "joinchat/$text";
		}else{
			if($url && $text[0] == "@"){ $link .= substr($text, 1); }
			else{ $link = $text; }
		}
		return $link;
	}

	public function userlink($user, $text = NULL, $html = TRUE){
		if($user instanceof User){ $user = $user->id; }
		$link = "tg://user?id=" .$user;
		if(empty($text)){ return $link; }
		if($html){ return '<a href="' .$link .'">' .$text .'</a>'; }
		return '[' .$text .'](' .$link .')';
	}

	public function get_chat_link($chat = NULL){
		if(empty($chat)){ $chat = $this->chat->id; }
		return $this->send->get_chat_link($chat);
	}

	public function answer_if_callback($text = "", $alert = FALSE){
		if($this->key != "callback_query"){ return FALSE; }
		return $this->send
			->text($text)
		->answer_callback($alert);
	}

	public function dump($json = FALSE){ return($json ? json_encode($this->data) : $this->data); }

	public function get_admins($chat = NULL, $full = FALSE){
		$ret = array();
		if(empty($chat)){ $chat = $this->chat->id; }
		$admins = $this->send->get_admins($chat);
		if(!empty($admins)){
			foreach($admins as $a){	$ret[] = $a['user']->id; }
		}
		return ($full == TRUE ? $admins : $ret);
	}

	public function data($type, $object = TRUE){
		$accept = ["text", "audio", "video", "video_note", "document", "photo", "voice", "location", "contact"];
		$type = strtolower($type);
		if(in_array($type, $accept) && isset($this->data['message'][$type])){
			if($object){ return (object) $this->data['message'][$type]; }
			return $this->data['message'][$type];
		}
		return FALSE;
	}

	public function _generic_content($key, $object = NULL, $rkey = 'file_id'){
		if(!isset($this->data[$this->key][$key])){ return FALSE; }
		$data = $this->data[$this->key][$key];
		if(empty($data)){ return FALSE; }
		if($object === TRUE){ return (object) $data; }
		elseif($object === FALSE){ return array_values($data); }

		if(in_array($key, ["document", "location", "game"])){ return $data; }
		return $data[$rkey];
	}

	public function document($object = TRUE){ return $this->_generic_content('document', $object); }
	public function location($object = TRUE){ return $this->_generic_content('location', $object); }
	public function audio($object = NULL){ return $this->_generic_content('audio', $object); }
	public function voice($object = NULL){ return $this->_generic_content('voice', $object); }
 	public function video($object = NULL){ return $this->_generic_content('video', $object); }
	public function video_note($object = NULL){ return $this->_generic_content('video_note', $object); }
	public function sticker($object = NULL){ return $this->_generic_content('sticker', $object); }
	public function game($object = TRUE){ return $this->_generic_content('game', $object); }

	public function gif(){
		$gif = $this->document(TRUE);
		if(!$gif or !in_array($gif->mime_type, ["video/mp4"])){ return FALSE; }
		// TODO gif viene por size?
		return $gif->file_id;
	}

	public function photo($retall = FALSE, $sel = -1){
		if(!isset($this->data['message']['photo'])){ return FALSE; }
		$photos = $this->data['message']['photo'];
		if(empty($photos)){ return FALSE; }
		// Select last file or $sel_id
		$sel = ($sel == -1 or ($sel > count($photos) - 1) ? (count($photos) - 1) : $sel);
		if(!isset($photos[$sel])){ $sel = 0; } // TEMP FIX
		if($retall === FALSE){ return $photos[$sel]['file_id']; }
		elseif($retall === TRUE){ return (object) $photos[$sel]; }
	}

	public function contact($self = FALSE, $object = TRUE){
		$contact = $this->data['message']['contact'];
		if(empty($contact)){ return FALSE; }
		if(
			$self == FALSE or
			($self == TRUE && $this->user->id == $contact['user_id'])
		){
			if($object == TRUE){ return (object) $contact; }
			return $contact;
		}elseif($self == TRUE){
			return FALSE;
		}
	}

	public function reply_target($priority = NULL){
		if(!$this->has_reply){ return NULL; }
		// El reply puede ser hacia la persona del mensaje al cual se hace reply
		// o si es un forward, hacia ese usuario creador del mensaje.

		$ret = $this->reply_user;
		if($priority == NULL or $priority == TRUE or strtolower($priority) == 'forward'){
			if($this->reply_is_forward){
				$ret = $this->reply->forward_from;
			}
		}

		return $ret;
	}

	// Return UserID siempre que sea posible.
	public function user_selector($priority = NULL, $word = NULL){
		$user = $this->reply_target($priority);
		if(!empty($user)){ return $user->id; }
		// TODO
	}

	public function pinned_message($content = NULL){
		if(!isset($this->data['message']['pinned_message'])){ return FALSE; }
		$pin = $this->data['message']['pinned_message'];
		if($content === NULL){
			$user = (object) $pin['from'];
			$chat = (object) $pin['chat'];
			$data = $pin['text'];
			return (object) array(
				'user' => $user,
				'chat' => $chat,
				'data' => $data
			);
		}
		elseif($content === TRUE){ return $pin['text']; }
		elseif($content === FALSE){ return $this->send->pin_message(FALSE); }
	}

	public function user_can($action = NULL, $user = NULL, $chat = NULL){
		if(empty($user)){ $user = $this->user->id; }
		if(empty($chat)){ $chat = $this->chat->id; }
		$data = $this->send->get_member_info($user, $chat);

		$can = [
			'can_be_edited', 'can_change_info',
			'can_post_messages', 'can_edit_messages',
			'can_delete_messages', 'can_invite_users',
			'can_restrict_members', 'can_pin_messages',
			'can_promote_members', 'can_send_messages',
			'can_send_media_messages', 'can_send_other_messages',
			'can_add_web_page_previews'
		];

		// Return all results.
		if($action === TRUE or $action === NULL){
			$final = array();
			foreach($can as $c){
				if(isset($data['result'][$c])){ $final[$c] = $data['result'][$c]; }
			}
			return $final;
		}

		if(strpos($action, "can_") === FALSE){ $action = "can_" .$action; }
		$action = strtolower($action);

		if(!isset($data['result'][$action])){ return NULL; }
		return (bool) $data['result'][$action];
	}

	public function download($file_id, $path = NULL){
		$data = $this->send->get_file($file_id);
		$url = "https://api.telegram.org/file/bot" .$this->bot->id .":" .$this->bot->key ."/";
		$file = $url .$data['file_path'];
		if(!empty($path)){
			return file_put_contents($path, file_get_contents($file));
		}
		return $file;
	}

	public function emoji($text, $reverse = FALSE){
		// Load when used
		if(empty($this->emojis)){
			$this->emojis = require 'Emojis.php';
		}

		if(!$reverse){
			// TODO Needs delimiter /--/
			/* return preg_replace_callback(array_keys($this->emojis), function ($mts){
				return $this->emojis[$mts[1]];
			}, $text); */
			return str_ireplace(array_keys($this->emojis), array_values($this->emojis), $text);
		}

		// TODO
		return substr(json_encode($text), 1, -1); // No comas
	}
}

?>
