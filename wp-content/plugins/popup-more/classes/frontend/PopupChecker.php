<?php
namespace ypmFrontend;


abstract class PopupChecker
{
	private $popup;
	private $post;

	public function setPopup($popup)
	{
		$this->popup = $popup;
	}

	public function getPopup()
	{
		return $this->popup;
	}

	public function setPost($post)
	{
		$this->post = $post;
	}

	public function getPost()
	{
		return $this->post;
	}

	/**
	 * Divide the Popup target data into Permissive And Forbidden assoc array
	 *
	 * @since 1.5.8
	 *
	 * @param array $postMetaData popup saved target data
	 *
	 * @return array $postMetaDivideData
	 *
	 */
	public function divideIntoPermissiveAndForbidden($targetData)
	{
		
		$permissive = array();
		$forbidden = array();
		$permissiveOperators = array('is');
		$forbiddenOperators = array('isnot');
		$permissiveOperators = apply_filters('ypmAdditionalPermissiveOperators', $permissiveOperators);
		$forbiddenOperators = apply_filters('ypmAdditionalForbiddenOperators', $forbiddenOperators);
	
		if (!empty($targetData)) {
			foreach ($targetData as $data) {
				$param = $data['key1'];

				if (empty($data['key2']) && $param != 'everywhere' && !strpos($param, '_all') && $param != 'post_tags') {
					break;
				}
			
				$operator = $data['key2'];
				if ((isset($operator) && in_array($operator, $permissiveOperators))) {
					$permissive[] = $data;
				}
				else if (in_array($operator, $forbiddenOperators)) {
					$forbidden[] = $data;
				}
			}
		}

		$postMetaDivideData = array(
			'permissive' => $permissive,
			'forbidden' => $forbidden
		);

		return $postMetaDivideData;
	}

	protected function isPostInForbidden($target)
	{
		$isForbidden = false;

		if (empty($target['forbidden'])) {
			return $isForbidden;
		}

		foreach ($target['forbidden'] as $targetData) {
			if ($this->isSatisfyForParam($targetData)) {
				$isForbidden = true;
				break;
			}
		}

		return $isForbidden;
	}

	protected function isPermissive($target)
	{
		$isPermissive = false;

		if (empty($target['permissive'])) {
			$isPermissive = false;
			return $isPermissive;
		}

		foreach ($target['permissive'] as $targetData) {
			if ($this->isSatisfyForParam($targetData)) {
				$isPermissive = true;
				break;
			}
		}

		return $isPermissive;
	}
}