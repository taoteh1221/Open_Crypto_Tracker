<?php

namespace Telegram\Elements;

class PhotoSize extends Base {
	public $width = 0;
	public $height = 0;

	function __construct($data = NULL){
		parent::__construct($data);
	}

	function __toString(){
		return (string) $this->file_id;
	}
}

?>
