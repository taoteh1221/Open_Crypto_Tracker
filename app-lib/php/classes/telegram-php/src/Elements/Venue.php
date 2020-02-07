<?php

namespace Telegram\Elements;

class Venue extends Location {
	public $location;
	public $title;
	public $address;
	public $foursquare_id;

	function __construct($data = NULL){
		if(is_array($data)){
			foreach($data as $k => $v){ $this->$k = $v; }
		}
		if(isset($data['latitude']) and isset($data['longitude'])){
			$this->location = new Location($data);
		}
	}

	function __toString(){
		return (string) $this->title ."\n" .$this->address;
	}
}

?>
