<?php

namespace Telegram\Elements;

class Document extends Base {
	public $file_name;
	public $mime_type;
	public $thumb;

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
