<?php
namespace ypmFrontend;

abstract class Popup {

	private $id;
	private $popup;
	private $loadable;
	private $referBy;
	private $filterable;
	private $content;
	private $options;

	public function setId($id) {
		$this->id = (int)$id;
	}
	public function getId() {
		return $this->id;
	}
	public function setPopup($popup) {
		$this->popup = $popup;
	}
	public function getPopup() {
		return $this->popup;
	}
	public function setLoadable($loadable) {
		$this->loadable = $loadable;
	}
	public function getLoadable() {
		return $this->loadable;
	}
	public function setReferBy($referBy) {
		$this->referBy = $referBy;
	}
	public function getReferBy() {
		return $this->referBy;
	}
	public function setFilterable($filterable) {
		$this->filterable = $filterable;
	}
	public function getFilterable() {
		return $this->filterable;
	}
	public function setContent($content) {
		$this->content = do_shortcode($content);
	}
	public function getContent() {
		return $this->content;
	}
	public function setOptions($options) {
		$this->options = $options;
	}
	public function getOptions() {
		return $this->options;
	}

	abstract public function includePopup();
}