<?php

namespace Telegram\Elements;

class Audio extends Voice {
	public $performer;
	public $title;

	function __construct($data = NULL){
		parent::__construct($data);
	}

	function __toString(){
		return (string) $this->file_id;
	}
}

?>
