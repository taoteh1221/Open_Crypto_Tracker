<?php

namespace Telegram\Elements;

class VideoNote extends Base {
	public $length = 0;
	public $duration = 0;
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
