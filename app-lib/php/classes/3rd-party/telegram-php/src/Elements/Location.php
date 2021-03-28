<?php

namespace Telegram\Elements;

class Location {
	public $longitude;
	public $latitude;

	function __construct($data = NULL, $lon = NULL){
		if(is_array($data)){
			foreach($data as $k => $v){ $this->$k = $v; }
		}elseif(is_float($data) and is_float($lon)){
			$this->latitude = $data;
			$this->longitude = $lon;
		}elseif(is_string($data) and strpos($data, ",") !== FALSE){
			$data = explode(",", $data);
			if(count($data) == 2){
				$this->latitude = $data[0];
				$this->longitude = $data[1];
			}
		}
	}

	function __toString(){
		return (string) $this->latitude .", " .$this->longitude;
	}
}

?>
