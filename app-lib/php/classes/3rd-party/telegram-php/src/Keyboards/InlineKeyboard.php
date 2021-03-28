<?php

namespace Telegram\Keyboards;

class InlineKeyboard {
	private $rows;
	private $config;
	private $parent;

	function __construct($parent){
		$this->parent = $parent;
	}

	function row($array = NULL){
		if(!is_array($array)){ return new InlineKeyboardRow($this, $this->parent->bot); }
		// ------
		$row = new InlineKeyboardRow($this, $this->parent->bot);
		foreach($array as $but){
			$text = $but;
			$request = $but;
			$switch = NULL;
			if(is_array($but)){
				if(isset($but['text'])){ $text = $but['text']; }
				elseif(isset($but[0])){ $text = $but[0]; }

				if(isset($but['request'])){ $request = $but['request']; }
				elseif(isset($but[1])){ $request = $but[1]; }

				if(isset($but['switch'])){ $switch = $but['switch']; }
				elseif(isset($but[2])){ $switch = $but[2]; }
			}
			$row->button($text, $request, $switch);
		}
		$row->end_row();
		return $this;
	}

	function row_button($text, $request = NULL, $switch = NULL){
		return $this->row()
			->button($text, $request, $switch)
		->end_row();
	}

	function push($data){
		if(!is_array($data)){ return FALSE; }
		$this->rows[] = $data;
		return $this;
	}

	function show(){
		$this->parent->_push('reply_markup', [
			'inline_keyboard' => $this->rows,
		]);
		$this->_reset();
		return $this->parent;
	}

	function _reset(){
		$this->rows = array();
		return $this;
	}
}

class InlineKeyboardRow {
	private $buttons;
	private $parent;
	private $bot;

	function __construct($parent, $bot = NULL){
		$this->parent = $parent;
		if(!empty($bot)){ $this->bot = $bot; }
	}

	function button($text, $request = NULL, $switch = NULL){
		$data = array();
		if($this->parent->convert_emoji){ /* TODO */ }
		$data['text'] = $text;
		if(filter_var($request, FILTER_VALIDATE_URL) !== FALSE){ $data['url'] = $request; }
		elseif($switch === TRUE or (is_string($switch) && strtolower($switch) == "command")){
			// Iniciar por privado
			$request = preg_replace("/([^-_a-zA-Z0-9]+)/i", "", $request); // Caracteres permitidos
			$data['url'] = "https://t.me/" .$this->bot->username ."?start=" .$request;
		}elseif(is_string($switch) && strtolower($switch) == "share"){
			$enc = NULL;
			if(is_array($request) && count($request) == 2){
				$enc = ['url' => urlencode($request[0]), 'text' => urldecode($request[1])];
			}else{
				$enc = ['url' => urlencode($request)];
			}
			$data['url'] = "https://t.me/share/url?" .http_build_query($enc);
		}elseif(strtolower($switch) == "text"){
			$data['switch_inline_query'] = $switch;
			$data['callback_data'] = "T:" .$request;
		}elseif(strtolower($switch) == "pay"){
			$data['switch_inline_query'] = $switch;
			$data['pay'] = TRUE;
		}elseif($switch === FALSE){
			$data['switch_inline_query'] = $request;
		}else{
			$data['switch_inline_query'] = $switch;
			$data['callback_data'] = $request;
		}
		$this->buttons[] = $data;
		return $this;
	}
	function end_row(){
		$this->parent->push($this->buttons);
		return $this->parent;
	}
}

?>
