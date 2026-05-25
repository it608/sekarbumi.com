<?php
use YpmPopup\ScriptsManager;

class YpmJsIncluder {

	public function __construct() {
		
		add_action('admin_enqueue_scripts', array($this, 'ypmEnqueueScripts'));
	}

	public static function getAllowedPages()
	{
		return array(
			YPM_POPUP_POST_TYPE.'_page_'.YPM_SETTINGS_PAGE,
			YPM_POPUP_POST_TYPE.'_page_'.YPM_LICENSE_PAGE,
			YPM_POPUP_POST_TYPE.'_page_'.YPM_POPUP_POST_TYPE,
			YPM_POPUP_POST_TYPE.'_page_'.YPM_SUBSCRIBERS_PAGE,
		);
	}

	public function ypmEnqueueScripts($hook) {

		global $post, $YpmPostTypesInfo;
		$currentPostType = '';

        $blockSettings = $this->gutenbergParams();
        ScriptsManager::registerScript('YpmSubscribers.js', array('dirUrl' => YPM_POPUP_ADMIN_JS_URL));
        ScriptsManager::registerScript('datetimepicker.js', array('dirUrl' => YPM_POPUP_ADMIN_JS_URL));
        ScriptsManager::registerScript('PopupMasterAdmin.js', array('dirUrl' => YPM_POPUP_ADMIN_JS_URL));
        ScriptsManager::registerScript('WpPopupMoreBlockMin.js', array('dirUrl' => YPM_POPUP_ADMIN_JS_URL));
        ScriptsManager::localizeScript('WpPopupMoreBlockMin.js', 'YPM_GUTENBERG_PARAMS', $blockSettings);
        ScriptsManager::enqueueScript('WpPopupMoreBlockMin.js');

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

		if (!in_array($currentPostType, array_keys($popupPostTypes)) && !$isInAllowedPages) {
			return false;
		}

		wp_enqueue_script('jquery');
		ScriptsManager::loadScript('jquery.colorbox.js');
		ScriptsManager::registerScript('YpmReviewPopup.js', array('dirUrl' => YPM_POPUP_ADMIN_JS_URL));
		ScriptsManager::enqueueScript('YpmReviewPopup.js');

		ScriptsManager::registerScript('ypmAdminJs.js', array('dep' => array('jquery', 'wp-color-picker')));
		ScriptsManager::localizeScript('ypmAdminJs.js', 'YpmAdminParams', array(
			'nonce' => wp_create_nonce('ypm_ajax_nonce'),
			'ajaxNonce' => wp_create_nonce('ypmPmNonce'),
			'copied' => esc_attr__('Copied', 'popup_master'),
			'areYouSure' => esc_attr__('Are you sure?', 'popup_master'),
			'copyToClipboard' => esc_attr__('Copy to clipboard', 'popup_master'),
			'proURL' => YPM_POPUP_PRO_URL,
			'imagesURL' => YPM_POPUP_IMAGE_URL,
			'imageSupportAlertMessage' => esc_attr__('Only image files supported', 'popup_master')
		));
		ScriptsManager::enqueueScript('datetimepicker.js');
		ScriptsManager::enqueueScript('YpmSubscribers.js');
		ScriptsManager::enqueueScript('ypmAdminJs.js');

		if ($hook == 'post-new.php' || $hook == 'post.php') {
			if(function_exists('wp_enqueue_code_editor')) {
				wp_enqueue_code_editor(array( 'type' => 'text/html'));
			}
			$popupPostTypes = $YpmPostTypesInfo['postTypes'];

			if (!empty($popupPostTypes[$post->post_type])) {
				wp_enqueue_media();
				wp_register_script('ionRangeSlider', YPM_POPUP_JS_URL . '/ionRangeSlider.js', array('jquery', 'wp-color-picker'));
				wp_enqueue_script('ionRangeSlider');
				wp_register_script('select2', YPM_POPUP_JS_URL . '/select2.js');
				wp_enqueue_script('select2');
				wp_register_script('bootstrapmin', YPM_POPUP_JS_URL . '/bootstrap.min.js');
				wp_enqueue_script('bootstrapmin');
                ScriptsManager::loadScript('YpmMinicolors.js');
                ScriptsManager::loadScript('Ypm.jquery.datetimepicker.full.min.js');
			}
		}
	}

    private function gutenbergParams() {

        $settings = array(
            'allpopups' => YpmPopup\Popup::getPopupIdTitleData(),
            'allEvents' => array(array('value' => '', 'title' => 'Select Event'),array('value' => 'load', 'title' => 'On load'),array('value' => 'click', 'title' => 'Click'),array('value' => 'hover', 'title' => 'Hover') ),
            'title'   => esc_attr__('Popup More', 'popup_master'),
            'description'   => esc_attr__('This block will help you to add popup’s shortcode inside the page content', 'popup_master'),
            'logo_classname' => 'ycd-gutenberg-logo',
            'popup_select' => esc_attr__('Select popup', 'popup_master')
        );

        return $settings;
    }
}

new YpmJsIncluder();