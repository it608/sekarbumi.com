<?php
namespace YpmPopup;
require_once(dirname(__FILE__).'/Popup.php');
class HtmlPopup extends Popup {

	public static function create($data, $obj = '') {
		$obj = new self();
		parent::create($data, $obj);
	}
}