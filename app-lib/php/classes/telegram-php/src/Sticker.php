<?php

namespace Telegram;

class Sticker {
	private $config;
	private $parent;
	private $file_id;
	private $user = NULL;

	public function __construct($parent){
		$this->parent = $parent;
	}

	public function get_set($name){
		return $this->parent
			->_push_method("getStickerSet")
			->_push('name', $name)
		->send();
	}

	// user = ID or TRUE to get file_id
	public function upload($file, $user = NULL){
		$retid = FALSE;
		if($user === TRUE){
			$retid = TRUE; $user = NULL;
		}
		if(empty($user)){ $user = $this->parent->user(); }
		if(filter_var($file, FILTER_VALIDATE_URL)){
			// TODO download temp file and upload
		}elseif(!file_exists($file) or !is_readable($file)){
			return FALSE;
		}

		$response = $this->parent
			->_push_method("uploadStickerFile")
			->_push('png_sticker', new \CURLFile(realpath($file)))
		->send();

		if($response !== FALSE){
			// TODO set file_id here
		}
		if($retid){ return $response; }
		return $this;
	}

	public function delete($file = NULL){
		if(empty($file) and !empty($this->file_id)){
			$file = $this->file_id;
		}elseif(empty($file)){
			return FALSE;
		}

		$this->parent
			->_push_method("deleteStickerFromSet")
			->_push('sticker', $file)
		->send();
	}

}

?>
