<?php

namespace Telegram\Elements;

class Voice extends Base {
	public $duration = 0;
	public $mime_type;

	function __construct($data = NULL){
		parent::__construct($data);
	}

	function __toString(){
		return (string) $this->file_id;
	}
}

?>
