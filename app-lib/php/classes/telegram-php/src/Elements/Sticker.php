<?php

namespace Telegram\Elements;

class Sticker extends Base {
	public $height = 0;
	public $width = 0;
	public $thumb;
	public $emoji;

	function __construct($data = NULL){
		parent::__construct($data);
	}

	function addThumb($photo){
		$this->thumb = $photo;
		return $this;
	}

	function __toString(){
		return (string) $this->file_id;
	}
}

?>
