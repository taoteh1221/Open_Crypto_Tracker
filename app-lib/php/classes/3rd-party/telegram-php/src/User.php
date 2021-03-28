<?php

namespace Telegram;

class User {
	public $id = NULL;
	public $first_name = NULL;
	public $last_name = NULL;
	public $language_code = NULL;
	public $username = NULL;
	public $is_bot = FALSE;
	protected $bot;
	protected $extra = array();

	public function __construct($id, $first_name = NULL, $last_name = NULL, $username = NULL, $language_code = NULL, $is_bot = FALSE){
		if(is_array($id)){
			foreach($id as $k => $v){
				$$k = $v;
			}
		}

		if($first_name instanceof Bot){
			$this->bot = $first_name;
			$this->is_bot = TRUE;
		}

		$first_name = str_replace("\u{202e}", "", $first_name);
		$last_name  = str_replace("\u{202e}", "", $last_name);

		$this->id = intval($id);
		$this->first_name = trim($first_name);
		$this->username = trim($username);
		$this->last_name = trim($last_name);
		$this->language_code = trim($language_code);
		$this->is_bot = (bool) $is_bot;

		/* if(!empty($this->username)){
			$this->is_bot = (strtolower(substr($this->username, -3)) == "bot");
		} */

		return $this;
	}

	public function avatar($id = NULL){
		// group or user, if not already get, get info
		// and save to self variable
	}

	public function info($bot = NULL){
		if(!empty($this->bot) && empty($bot)){ $bot = $this->bot; }
		$send = new Sender($bot);
		$info = $send->get_chat($this->id);
		return $this->__construct($info);
	}

	public function link($text = NULL, $html = TRUE){
		$url = "tg://user?id=" .$this->id;
		if($text === NULL){ return $url; }
		if($text === TRUE){ $text = strval($this); }
		if($html){ return '<a href="' .$url .'">' .$text .'</a>'; }
		return '[' .$text .'](' .$url .')';
	}

	public function __toString(){
		return trim($this->first_name ." " .$this->last_name);
	}

	public function __get($k){
		if(isset($this->$k)){ return $this->$k; }
		if(array_key_exists($k, $this->extra)){ return $this->extra[$k]; }
		return NULL;
	}

	public function __set($k, $v){
		if(isset($this->$k)){ $this->$k = $v; }
		else{ $this->extra[$k] = $v; }
	}
}

?>
