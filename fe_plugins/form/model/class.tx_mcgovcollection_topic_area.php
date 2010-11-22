<?php
class tx_mcgovcollection_topic_area {
	private $uid;
	private $title;
	private $description;
	private $groups;
	
	public function __construct($uid, $title, $description) {
		$this->uid = $uid;
		$this->title = $title;
		$this->description = $description;
	}
	
	public function addGroup($group) {
		$this->groups[] = $group;
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
	
	public function getGroups() {
		return $this->groups;
	}
}