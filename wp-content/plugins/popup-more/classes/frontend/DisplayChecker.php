<?php

namespace ypmFrontend;

require_once(dirname(__FILE__).'/PopupChecker.php');

use YpmPopup\Popup;


class DisplayChecker extends PopupChecker
{
	private static $instance;

	public static function instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * It checks whether popup should be loaded on the current page.
	 *
	 * @since 1.5.8
	 *
	 * @param int $popupId popup id
	 * @param  object $post page post data
	 *
	 * @return bool
	 *
	 */
	public function isLoadable($popup, $post)
	{
		$this->setPopup($popup);
		$this->setPost($post);

		$isActive = $popup->isActive();
		$allowToLoad = $this->allowToLoad();

		if (!$isActive) {
			$allowToLoad['option_event'] = false;
		}
		$checkActiveDate = $this->isStartDate();
		if (!$checkActiveDate) {
			$allowToLoad['option_event'] = false;
		}
		$checkEndDate = $this->checkEndDate();
		if ($checkEndDate === true) {
			$allowToLoad['option_event'] = false;
		}

		return $allowToLoad;
	}

	private function checkEndDate() {
		$popup = $this->getPopup();
		return apply_filters('ypmCheckIsEndDate', $popup);
	}

	private function isStartDate() {
		$popup = $this->getPopup();
		if (!$popup->getOptionValue('ypm-popup-enable-start-date')) {
			return true;
		}
		$date = $popup->getOptionValue('ypm-popup-start-date');
		$timeZone = $popup->getOptionValue('ypm-popup-start-date-time-zone');
		$dateObj = new \DateTime($date, new \DateTimeZone($timeZone));

		return $dateObj->getTimestamp() < time();
	}

	/**
	 * Decides whether popup data should be loaded or not
	 *
	 * @since 1.5.8
	 *
	 * @return array
	 *
	 */
	private function allowToLoad()
	{
		$isCustomInserted = $this->isCustomInserted();
		$popup = $this->getPopup();
	
		$insertedModes = array(
			'attr_event' => false,
			'option_event' => false
		);

		if ($isCustomInserted) {
			$insertedModes['attr_event'] = true;
		}

		$target = $this->divideTargetData();
		$isPostInForbidden = $this->isPostInForbidden($target);

		if ($isPostInForbidden) {
			return $insertedModes;
		}

		$isPermissive = $this->isPermissive($target);

		if ($isPermissive) {
			$insertedModes['option_event'] = $isPermissive;
		}
		$popup->allowBySettings = true;
		$popup = apply_filters('isAllowSettings', $popup);
		if (!$popup->allowBySettings) {
			$insertedModes = array(
				'attr_event' => false,
				'option_event' => false
			);
		}
		return $insertedModes;
	}

	/**
	 * Divide target data to Permissive and Forbidden
	 *
	 * @since 1.5.8
	 *
	 * @return array $popupTargetData
	 *
	 */
	public function divideTargetData()
	{
		$popup = $this->getPopup();
		$targetData = $popup->getOptionValue('ypm-display-settings');
		return $this->divideIntoPermissiveAndForbidden($targetData);
	}

	/**
	 * Check is popup inserted via short code or class attribute
	 *
	 * @since 1.5.8
	 *
	 * @param
	 *
	 * @return bool
	 *
	 */
	private function isCustomInserted()
	{
		$customInsertData = $this->getCustomInsertedData();
		$popup = $this->getPopup();
		// When popup object is empty it's mean popup is not custom inserted
		if (empty($popup)) {
			return false;
		}
		$popupId = $popup->getId();

		return in_array($popupId, $customInsertData);
	}

	/**
	 * Check whether the current page is corresponding to the saved target data
	 *
	 * @since 1.5.8
	 *
	 * @param array $targetData popup saved target data
	 *
	 * @return bool $isSatisfy
	 *
	 */
	public function isSatisfyForParam($targetData)
	{
		$isSatisfy = false;
		$postId = get_queried_object_id();

		if (empty($targetData['key1'])) {
			return $isSatisfy;
		}
		$targetParam = $targetData['key1'];

		$post = $this->getPost();
		if (isset($post) && empty($postId)) {
			$postId = $post->ID;
		}

		if ($targetParam == 'everywhere') {
			return true;
		}

		if ($targetParam == 'all_tag') {
			if (has_tag()) {
				$isSatisfy = true;
			}
		}
		else if ($targetParam == 'selected_tag') {
			$tagsObj = wp_get_post_tags($postId);
			if (!empty($targetData['key3'])) {
				$values = array_keys($targetData['key3']);
			}
			$selectedTags = array_values($targetData['key3']);

			foreach ($tagsObj as $tagObj) {
				if (in_array($tagObj->slug, $selectedTags)) {
					$isSatisfy = true;
					break;
				}
			}
		}
		else if (strpos($targetParam, 'all_') === 0) {
			$currentPostType = get_post_type($postId);
			if ('all_'.$currentPostType == $targetParam) {
				$isSatisfy = true;
			}
		}
		else if (strpos($targetParam, 'archive_') === 0) {
			$currentPostType = get_post_type();
			if ($targetParam == $currentPostType.'archive_') {
				if (is_post_type_archive($currentPostType)) {
					$isSatisfy = true;
				}
			}
		}
		else if (strpos($targetParam, 'selected_') === 0) {
			$values = array();

			if (!empty($targetData['key3'])) {
				$values = array_keys($targetData['key3']);
			}

			if (in_array($postId, $values)) {
				$isSatisfy = true;
			}
		}
		else if (strpos($targetParam, 'categories_') === 0 ) {
			$values = array();
			$isSatisfy = false;

			if (!empty($targetData['key3'])) {
				$values = array_values($targetData['key3']);
			}

			global $post;
			// get current all taxonomies of the current post
			$taxonomies = get_post_taxonomies($post);

			foreach ($taxonomies as $taxonomy) {
				// get current post all categories
				$terms = get_the_terms($post->ID, $taxonomy);

				if (!empty($terms)) {
					foreach ($terms as $term) {
						if (empty($term)) {
							continue;
						}
						if (in_array($term->term_id, $values)) {
							$isSatisfy = true;
							break;
						}
					}
				}
			}
		}
		else if ($targetParam == 'post_type' && !empty($targetData['key3'])) {
			$selectedCustomPostTypes = array_values($targetData['key3']);
			$currentPostType = get_post_type($postId);

			if (in_array($currentPostType, $selectedCustomPostTypes)) {
				$isSatisfy = true;
			}
		}
		else if ($targetParam == 'post_category' && !empty($targetData['key3'])) {
			$values = $targetData['key3'];
			$currentPostCategories = get_the_category($postId);
			$currentPostType = get_post_type($postId);
			if (empty($currentPostCategories) && $currentPostType == 'product') {
				$currentPostCategories = get_the_terms($postId, 'product_cat');
			}

			foreach ($currentPostCategories as $categoryName) {
				if (in_array($categoryName->term_id, $values)) {
					$isSatisfy = true;
					break;
				}

			}
		}
		else if ($targetParam == 'page_type' && !empty($targetData['key3'])) {
			$postTypes = $targetData['key3'];

			foreach ($postTypes as $postType) {

				if ($postType == 'is_home_page') {
					if (is_front_page() && is_home()) {
						// Default homepage
						$isSatisfy = true;
						break;
					} else if ( is_front_page() ) {
						// static homepage
						$isSatisfy = true;
						break;
					}
				}
				else if (function_exists($postType) && $postType()) {
					$isSatisfy = true;
					break;
				}
			}
		}
		else if ($targetParam == 'page_template' && !empty($targetData['key3'])) {
			$currentPageTemplate = basename(get_page_template());
			if (in_array($currentPageTemplate, $targetData['key3'])) {
				$isSatisfy = true;
			}
		}
		else if ($targetParam == 'shop_page') {
			if (function_exists('is_shop') && is_shop()) {
				$isSatisfy = true;
			}
		}
		else if ($targetParam == 'cart_page') {
			if (function_exists('is_cart') && is_cart()) {
				$isSatisfy = true;
			}
		}
		else if ($targetParam == 'account_page') {
			if (function_exists('is_account_page') && is_account_page()) {
				$isSatisfy = true;
			}
		}

		if (!$isSatisfy && do_action('isAllowedForTarget', $targetData, $post)) {
			$isSatisfy = true;
		}

		return $isSatisfy;
	}

	/**
	 * Get custom inserted data
	 *
	 * @since 1.5.8
	 *
	 * @return array $insertedData
	 */
	public function getCustomInsertedData()
	{
		$post = $this->getPost();
		$insertedData = array();
		/**
		 * ToDO check this logic
		 */
		if (isset($post)) {
			$insertedData = Popup::getCustomInsertedDataByPostId($post->ID);
		}

		return $insertedData;
	}
}