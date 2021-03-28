<?php

namespace Telegram\Elements;

class Base {
	public $file_id;
	public $file_size = 0;

	function __construct($data = NULL){
		if(is_array($data)){
			foreach($data as $k => $v){ $this->$k = $v; }
		}
	}

	function __toString(){
		return (string) $this->file_id;
	}
}

?>
