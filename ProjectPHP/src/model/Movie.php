<?php

namespace model;

class Movie{
	private $title;
	
	public function __construct($title){
		$this->title = $title;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
}
