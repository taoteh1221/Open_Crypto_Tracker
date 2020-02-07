<?php

namespace Telegram\Payments;

class Stripe {
	private $config;
	private $parent;

	function __construct($parent){
		$this->parent = $parent;
		$this
			->currency("EUR")
			->payload(time() . mt_rand(1000, 9999));
	}

	function title($name, $description = NULL){
		$this->config['title'] = $name;
		if(!empty($description)){ return $this->description($description); }
		return $this;
	}

	function description($text){
		$this->config['description'] = $text;
		return $this;
	}

	function token($token, $payload = NULL, $start = NULL){
		$this->config['provider_token'] = $token;
		if(!empty($payload)){ $this->payload($payload); }
		if(!empty($start)){ $this->start($start); }
		return $this;
	}

	function payload($data){
		$this->config['payload'] = $data;
		return $this;
	}

	function start($data){
		$this->config['start_parameter'] = $data;
		return $this;
	}

	function currency($data){
		$this->config['currency'] = $data;
		return $this;
	}

	function price($label, $amount = NULL){
		if(is_array($label) and empty($amount)){
			foreach($label as $l => $a){ $this->price($l, $a); }
			return $this;
		}

		if(is_float($amount)){ $amount = $amount * 100; }

		$this->config['prices'][] = [
			"label" => $label,
			"amount" => $amount
		];

		return $this;
	}

	function flexible($value = TRUE){
		$this->config['is_flexible'] = (bool) $value;
		return $this;
	}

	function request($data){
		if(is_string($data)){ $data = [$data]; }
		elseif($data === TRUE){ $data = ['name', 'phone', 'email', 'shipping']; }
		foreach($data as $k){
			if(in_array($k, ['name'])){ $this->request_name(TRUE); }
			elseif(in_array($k, ['phone', 'number', 'phone_number', 'mobile'])){ $this->request_phone(TRUE); }
			elseif(in_array($k, ['email'])){ $this->request_email(TRUE); }
			elseif(in_array($k, ['shipping', 'address'])){ $this->request_shipping(TRUE); }
		}
		return $this;
	}

	function request_name($value = TRUE){
		$this->config['need_name'] = (bool) $value;
		return $this;
	}

	function request_phone($value = TRUE){
		$this->config['need_phone_number'] = (bool) $value;
		return $this;
	}

	function request_email($value = TRUE){
		$this->config['need_email'] = (bool) $value;
		return $this;
	}

	function request_shipping($value = TRUE){
		$this->config['need_shipping_address'] = (bool) $value;
		return $this;
	}

	function photo($url, $width = NULL, $height = NULL, $size = NULL){
		if(!empty($width) and strpos($width, "x") !== FALSE){
			$width = explode("x", $width);
			$height = $width[1];
			$width = $width[0];
		}

		if(filter_var($url, FILTER_VALIDATE_URL) !== FALSE){ return $this; }

		$this->config['photo_url'] = $url;
		if($width and $height){
			$this->config['photo_width'] = $width;
			$this->config['photo_height'] = $height;
		}
		if($size){
			$this->config['photo_size'] = $size;
		}

		return $this;
	}

	function show(){
		foreach($this->config as $k => $v){ $this->parent->_push($k, $v); }
		$this->_reset();
		return $this->parent;
	}

	function _reset(){
		$this->config = array();
		return $this;
	}
}

?>
