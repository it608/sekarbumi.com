<?php
namespace ypmFrontend;

use \YpmAdminHelper;

// load popups data's from popups object
class ScriptsLoader
{
	// all loadable popups objects
	private $loadablePopups = array();
	private $isAdmin = false;
	private static $alreadyLoadedPopups = array();

	public function setLoadablePopups($loadablePopups)
	{
		$this->loadablePopups = $loadablePopups;
	}

	public function getLoadablePopups()
	{
		return apply_filters('ypmLoadablePopups', $this->loadablePopups);
	}

	public function setIsAdmin($isAdmin)
	{
		$this->isAdmin = $isAdmin;
	}

	public function getIsAdmin()
	{
		return $this->isAdmin;
	}

	/**
	 * Get encoded popup options
	 *
	 * @since 3.0.4
	 *
	 * @param object $popup
	 *
	 * @return array|mixed|string|void $popupOptions
	 */
	private function getEncodedOptionsFromPopup($popup)
	{
		/*
		 * ToDo check this code
		 * */
		$extraOptions = array();
		//$extraOptions = $popup->getExtraRenderOptions();
		$id = $popup->getId();
		$popupOptions = $popup->getOptions();
		$popupOptions = apply_filters('ypmPopupRenderOptions', $popupOptions);
		/*
		 * ToDo check this code
		 * */
		//$popupCondition = $popup->getConditions();
		$popupCondition = array();

		$popupOptions = array_merge($popupOptions, $extraOptions);
		$popupOptions['ypmConditions'] = apply_filters('ypmRenderCondtions',  $popupCondition);
		// These two lines have been added in order to not use the json_econde and to support PHP 5.3 version.
		$popupOptions = YpmAdminHelper::serializeData($popupOptions);
		$popupOptions = base64_encode($popupOptions);

		return $popupOptions;
	}

	// load popup scripts and styles and add popup data to the footer
	public function loadToFooter()
	{
		$popups = $this->getLoadablePopups();
		$currentPostType = YpmAdminHelper::getCurrentPostType();
		global $wp;
		if (empty($popups)) {
			return false;
		}

		global $post;
		$postId = 0;

		if (!empty($post)) {
			$postId = $post->ID;
		}

		if ($this->getIsAdmin()) {
			$this->loadToAdmin();
			return true;
		}

		foreach ($popups as $popup) {
			$isActive = $popup->isActive();

			if (!$isActive) {
				continue;
			}

			$popupId = $popup->getId();

			//	$popupContent = apply_filters('ypmPopupContentLoadToPage', $popup->getContent(), $popupId);

			$includer = new PopupIncluder();
			$includer->setId($popupId);
			$includer->setPopup($popup);
			$includer->setLoadable(true);
			$includer->setReferBy(2);
			$includer->includePopup();
		}
	}

	public function loadToAdmin()
	{
		$popups = $this->getLoadablePopups();

		foreach ($popups as $popup) {
			$popupId = $popup->getId();

			$events = array();

			$events = json_encode($events);

			$popupOptions = $this->getEncodedOptionsFromPopup($popup);

			$popupContent = apply_filters('ypmPopupContentLoadToPage', $popup->getContent(), $popupId);

			add_action('admin_footer', function() use ($popupId, $events, $popupOptions, $popupContent) {
				$footerPopupContent = '<div style="position:absolute;top: -999999999999999999999px;">
							<div class="ypm-popup-content" id="ypm-popup-content-wrapper-'.$popupId.'" data-id="'.esc_attr($popupId).'" data-events="'.esc_attr($events).'" data-options="'.esc_attr($popupOptions).'">
								<div class="ypm-popup-content-'.esc_attr($popupId).' ypm-popup-content-html">'.$popupContent.'</div>
							</div>
						  </div>';

				echo wp_kses($footerPopupContent, YpmAdminHelper::getAllowedTags());
			});
		}
		$this->includeScripts($popups);

	}
}
