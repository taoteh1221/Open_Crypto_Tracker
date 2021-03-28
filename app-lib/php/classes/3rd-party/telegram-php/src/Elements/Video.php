<?php

namespace Telegram\Elements;

class Video extends Base {
	public $height = 0;
	public $width = 0;
	public $duration = 0;
	public $thumb;
	public $mime_type;

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
