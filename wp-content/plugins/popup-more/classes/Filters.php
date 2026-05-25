<?php
namespace YpmPopup;

class Filters
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		add_filter('admin_url', array($this, 'addNewPostUrl'), 10, 2);
		add_filter('ypmRenderContent', array($this, 'renderContent'), 1, 2);
		add_filter('ypmSavedData', array($this, 'filterSavedData'), 1, 1);
		add_filter('post_row_actions', array($this, 'duplicatePost'), 10, 2);
		add_filter('ypmRenderContent', array(Popup::CLASS, 'customStyles'), 2, 2);
		add_filter('ypmRenderContentEnd', array(Popup::CLASS,'popupExtraDataRender'),2,2);
		add_filter('YpmDefaultDataOptions', array($this, 'defaultOptions'));
	}

	public function duplicatePost($actions, $post)
	{
		if (current_user_can('edit_posts') && $post->post_type == YPM_POPUP_POST_TYPE) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=ypm_duplicate_post_as_draft&post=' . $post->ID, YPM_POPUP_POST_TYPE, 'duplicate_nonce') . '" title="Duplicate this item" rel="permalink">'.esc_attr__('Clone', 'popup_master').'</a>';
		}
		return $actions;
	}

	public function filterSavedData($savedData)
	{
		if ($savedData === false) {
			return $savedData;
		}
		$needToCheckSaved = array(
			'ypm-events-settings'
		);
		$getDefaultData = function ($optionName) {
			global $YpmDefaults;

			foreach($YpmDefaults as $option) {
				if($option['name'] == $optionName) {
					return $option['defaultValue'];
				}
			}
		};
		if (empty($savedData)) {
			$savedData = array();
		}
		foreach ($needToCheckSaved as $optionName) {
			if (!isset($savedData[$optionName])) {
				$savedData[$optionName] = $getDefaultData($optionName);
			}
		}

		return $savedData;
	}

	public function addNewPostUrl($url, $path) {
		if ($path == 'post-new.php?post_type='.YPM_POPUP_POST_TYPE) {
			$url = str_replace('post-new.php?post_type='.YPM_POPUP_POST_TYPE, 'edit.php?post_type='.YPM_POPUP_POST_TYPE.'&page='.YPM_POPUP_POST_TYPE, $url);
		}

		return $url;
	}

	public function renderContent($content, $typeObj)
	{
		$options = $typeObj->getOptions();

		if (!empty($options['ypm-custom-css'])) {
			$content .= "<style type='text/css'>".$options['ypm-custom-css']."</style>";
		}
		$content = $this->addCustomCloseImage($content, $typeObj);

		return $content;
	}

	private function addCustomCloseImage($content, $typeObj)
	{
		$popupOptions = $typeObj->getOptions();
		if (!empty($popupOptions['ypm-theme-close-image-url'])) {
			$imageUrl = $popupOptions['ypm-theme-close-image-url'];
		
			$content .= "<style type='text/css'>
				#ypmcboxClose {
					background: url('$imageUrl') !important;
					background-size: contain !important;
					background-repeat: no-repeat!important;
					background-position: center !important;
				}</style>";
		}
		
		return $content;
	}

	public function defaultOptions($options) {
		if (ypm_is_free()) {
			$options['ageRestriction']['ageVerification'] .= \YpmAdminHelper::proSpan();
		}
		return $options;
	}
}