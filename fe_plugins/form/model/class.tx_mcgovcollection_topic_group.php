<?php
class tx_mcgovcollection_topic_group {
	private $uid;
	private $title;
	private $description;
	private $services;
	
	public function __construct($uid, $title, $description) {
		$this->uid = $uid;
		$this->title = $title;
		$this->description = $description;
	}
	
	public function addService($service) {
		$this->services[] = $service;
	}
	
	public function getUid() {
		return $this->uid;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getServices() {
		return $this->services;
	}
}