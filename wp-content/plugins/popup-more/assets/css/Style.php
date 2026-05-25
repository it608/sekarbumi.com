<?php
namespace YpmPopup;

class Style {

	public function __construct() {

		$this->init();
	}

	public function init() {

	}

	public static function getAllowedPages()
	{
		return array(
			YPM_POPUP_POST_TYPE.'_page_'.YPM_SETTINGS_PAGE,
			YPM_POPUP_POST_TYPE.'_page_'.YPM_LICENSE_PAGE,
			YPM_POPUP_POST_TYPE.'_page_'.YPM_POPUP_POST_TYPE,
			YPM_POPUP_POST_TYPE.'_page_'.YPM_SUBSCRIBERS_PAGE,
			YPM_POPUP_POST_TYPE.'_page_'.YPM_AICHAT_POST_TYPE,
		);
	}

	public static function enqueueStyles($hook) {

		global $post, $YpmPostTypesInfo;
		$currentPostType = '';

		if (!empty($post->post_type)) {
			$currentPostType = $post->post_type;
		}
		else if (!empty($_GET['post_type'])) {
			$currentPostType = esc_attr($_GET['post_type']);
		}

		global $YpmPostTypesInfo;
		$popupPostTypes = $YpmPostTypesInfo['postTypes'];
		$allowedPages = self::getAllowedPages();
		$isInAllowedPages = in_array($hook, $allowedPages);

        ScriptsManager::registerStyle('PMGeneral.css', array('styleSrc' => YPM_POPUP_CSS_ADMIN_URL));
        ScriptsManager::enqueueStyle('PMGeneral.css');
  
		if (!in_array($currentPostType, array_keys($popupPostTypes)) && !$isInAllowedPages) {
			return false;
		}

		ScriptsManager::registerStyle('style.css');
		ScriptsManager::enqueueStyle('style.css');
        ScriptsManager::registerStyle('ypmbootstrap.css');
        ScriptsManager::enqueueStyle('ypmbootstrap.css');

		ScriptsManager::registerStyle('ypmcolorbox', array(
			'styleSrc' => YPM_POPUP_CSS_URL.'colorbox/',
			'fileName' => 'colorbox.css'
		));
		ScriptsManager::enqueueStyle('ypmcolorbox');

		if($hook == 'post-new.php' || $hook == 'post.php' || $isInAllowedPages) {

			$popupPostTypes = $YpmPostTypesInfo['postTypes'];

			if(!empty($popupPostTypes[$currentPostType]) || $isInAllowedPages) {

				ScriptsManager::registerStyle('ypmcolorbox.css', array('styleSrc' => YPM_POPUP_CSS_URL."colorbox/") );
				ScriptsManager::enqueueStyle('ypmcolorbox.css');
				ScriptsManager::registerStyle('select2.css');
				ScriptsManager::enqueueStyle('select2.css');
				ScriptsManager::registerStyle('ion.rangeSlider.css');
				ScriptsManager::enqueueStyle('ion.rangeSlider.css');
				ScriptsManager::registerStyle('ion.rangeSlider.skinFlat.css');
				ScriptsManager::enqueueStyle('ion.rangeSlider.skinFlat.css');

				ScriptsManager::loadStyle('YpmColorpicker.css');
				ScriptsManager::loadStyle('Ypm.jquery.dateTimePicker.min.css');

				wp_enqueue_style( 'wp-color-picker' ); 
			}
		}
	}
}