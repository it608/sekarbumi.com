<?php
namespace YpmPopup;

class PopupType
{
	private $available = false;
	private $isComingSoon = false;
	private $hasType = 1;
	private $type = '';
	private $name = '';
	private $group = array();
	private $accessLevel = YPM_POPUP_FREE;
	private $options = array();

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setGroup($group)
	{
		$this->group = $group;
	}

	public function getGroup()
	{
		return $this->group;
	}

	public function setAvailable($available)
	{
		$this->available = $available;
	}

	public function isAvailable()
	{
		return $this->available;
	}

	public function setAccessLevel($accessLevel)
	{
		$this->accessLevel = $accessLevel;
	}

	public function getAccessLevel()
	{
		return $this->accessLevel;
	}

	public function setIsComingSoon($isComingSoon)
	{
		$this->isComingSoon = $isComingSoon;
	}

	public function getIsComingSoon()
	{
		return $this->isComingSoon;
	}

	public function setHasType($hasType) {
		$this->hasType = $hasType;
	}

	public function getHasType() {
		return $this->hasType;
	}

	public function setOptions($options) {
		$this->options = $options;
	}

	public function getOptions() {
		return $this->options;
	}

	public function isVisible()
	{
		$status = true;
		$isAvailable = $this->isAvailable();

		if (!$isAvailable && YPM_POPUP_PKG != YPM_POPUP_FREE) {
			$status = false;
		}

//		if (!empty($_GET['ycd_group_name']) && $_GET['ycd_group_name'] != 'all') {
//			$group = $this->getGroup();
//			$status = $status && in_array($_GET['ycd_group_name'], $group);
//		}

		return $status;
	}
}