<?php

namespace Telegram\Elements;
use Telegram\User as User;

class MessageEntity {
	public $type;
	public $offset;
	public $length;
	public $url;
	public $user;
	public $value;

	function __construct($data, $text = NULL){
		if(is_array($data)){
			foreach($data as $k => $v){ $this->$k = $v; }
		}
		if($this->type == "text_mention"){ $this->user = new User($data['user']); }
		elseif(!empty($text) && in_array($this->type, ['url', 'text_link', 'bot_command', 'mention', 'hashtag', 'email'])){
			// TODO
			if(function_exists('mb_convert_encoding')){
				// $text = mb_convert_encoding($text, 'UTF-16', 'UTF-8');
			}
			$this->value = substr($text, $this->offset, $this->length);
		}
	}

	function __toString(){
		if($this->type == "text_mention"){ return (string) $this->user->id; }
		return (string) $this->value;
	}
}

?>
