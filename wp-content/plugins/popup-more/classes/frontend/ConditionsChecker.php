<?php

namespace ypmFrontend;
require_once(dirname(__FILE__).'/PopupChecker.php');

class ConditionsChecker extends PopupChecker
{
	private static $instance;

	public static function instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new static;
		}

		return self::$instance;
	}

	public function isAllow() {

		$status = true;
		$popup = $this->getPopup();
		if (empty($popup)) {
			return false;
		}
		$isActive = $popup->isActive();

		if(!$this->checkConditions() || !$isActive) {
			return false;
		}

		return $status;
	}

	private function checkConditions() {
		
		$status = true;
		$conditions = $this->divideTargetData();
		
		$isPostInForbidden = $this->isPostInForbidden($conditions);

		if ($isPostInForbidden && !empty($conditions['forbidden'])) {
			return false;
		}

		if (empty($conditions['permissive'])) {
			return $status;
		}

		$isPermissive = $this->isPermissive($conditions);

		if (!$isPermissive) {
			return $isPermissive;
		}

		return $status;
	}

	public function divideTargetData()
	{
		$popup = $this->getPopup();
		$targetData = $popup->getOptionValue('ypm-conditions-settings');
		return $this->divideIntoPermissiveAndForbidden($targetData);
	}

	public function isSatisfyForParam($targetData)
	{
		$isSatisfy = false;

		$targetData['isSatisfy'] = $isSatisfy;
		$targetData = apply_filters('ypmIsSatisfyForConditions', $targetData);
	
		return $targetData['isSatisfy'];
	}
}