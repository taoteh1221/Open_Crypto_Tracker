<?php

namespace Telegram;

class Bot extends User {
	public $key = NULL;

	public function __construct($id, $key = NULL, $username = NULL, $first_name = NULL){
		if(is_array($id) && count($id) == 2){
			if(empty($first_name) && !empty($username) && !empty($key)){
				$first_name = $username;
				$username = $key;
			}elseif(empty($username) && !empty($key)){
				$username = $key;
			}
			$key = $id[1];
			$id = $id[0];
		}elseif(is_array($id) && count($id) > 2){
			foreach($id as $k => $v){ $$k = $v; }
		}elseif(is_string($id) && strpos($id, ":") !== FALSE){
			$id = explode(":", $id);
			if(empty($first_name) && !empty($username) && !empty($key)){
				$first_name = $username;
				$username = $key;
			}elseif(empty($username) && !empty($key)){
				$username = $key;
			}
			$key = $id[1];
			$id = $id[0];
		}

		$this->key = trim($key);
		return parent::__construct($id, $first_name, NULL, $username, NULL, TRUE);
	}

	/* public function info(){
		$send = new Sender($this);
		// TODO getWebhookInfo and return array with data
	} */

	public function __toString(){
		return ("@" .$this->username);
	}
}

?>
