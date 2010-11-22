<?php
class tx_mcgovcollection_service {
	private $uid;
	private $title;
	private $description;
	private $type;
	private $link;
	private $price;
	private $groupuid;
	
	public function __construct($uid, $title, $description, $type, $link, $price, $guid) {
		$this->uid = $uid;
		$this->title = $title;
		$this->description = $description;
		$this->type = $type;
		$this->link = $link;
		$this->price = $price;
		$this->groupuid = $guid;
	}
	
	public function getGroupUid() {
		return $this->groupuid;
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
	
	public function getType() {
		return $this->type;
	}
	
	public function getLink() {
		return $this->link;
	}
	
	public function getPrice() {
		return $this->price;
	}
}