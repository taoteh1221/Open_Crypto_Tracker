<?php

namespace Telegram\Keyboards;

class Keyboard {
	private $rows;
	private $config;
	private $parent;

	function __construct($parent){
		$this->selective(FALSE);
		$this->parent = $parent;
	}

	function row($array = NULL){
		if(!is_array($array)){ return new KeyboardRow($this); }
		// -------
		$row = new KeyboardRow($this);
		foreach($array as $v){
			$row->button($v);
		}
		$row->end_row();
		return $this;
	}

	function row_button($text, $request = NULL){
		return $this->row()
			->button($text, $request)
		->end_row();
	}

	function push($data){
		if(!is_array($data)){ return FALSE; }
		$this->rows[] = $data;
		return $this;
	}

	function selective($val = TRUE){
		$this->config['selective'] = $val;
		return $this;
	}

	function show($one_time = FALSE, $resize = FALSE){
		$this->parent->_push('reply_markup', [
			'keyboard' => $this->rows,
			'resize_keyboard' => $resize,
			'one_time_keyboard' => $one_time,
			'selective' => $this->config['selective']
		]);
		$this->_reset();
		return $this->parent;
	}

	function hide($sel = FALSE){
		if($sel === TRUE){ $this->selective(TRUE); }
		$this->parent->_push('reply_markup', [
			'hide_keyboard' => TRUE,
			'selective' => $this->config['selective']
		]);
		$this->_reset();
		return $this->parent;
	}

	function _reset(){
		$this->rows = array();
		return $this;
	}
}

class KeyboardRow {
	private $buttons;
	private $parent;

	function __construct($parent){
		$this->parent = $parent;
	}

	function button($text, $request = NULL){
		$data = array();
		if($this->parent->convert_emoji){ /* TODO */ }
		$data['text'] = $text;
		if($request === TRUE or $request == "contact"){ $data['request_contact'] = TRUE; }
		elseif($request === FALSE or $request == "location"){ $data['request_location'] = TRUE; }
		$this->buttons[] = $data;
		return $this;
	}
	function end_row(){
		$this->parent->push($this->buttons);
		return $this->parent;
	}
}

?>
