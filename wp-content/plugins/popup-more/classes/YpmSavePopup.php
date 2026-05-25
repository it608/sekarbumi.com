<?php
class YpmSavePopup {

	public static function savePopupData($postId, $post, $update) {

		if(!$update) {
			return;
		}
		$postType = get_post_type($postId);

		if(get_post_status($postId) != 'publish') {
			return;
			die();
		}

		global $YpmPostTypesInfo;
		$postType = $post->post_type;
		self::saveMetaBoxPopup($postId);

		$popupPostTypes = $YpmPostTypesInfo['postTypes'];

		if(!is_array($popupPostTypes)) {
			$popupPostTypes = array();
		}

		if(!empty($popupPostTypes[$postType])) {

			$postTypeLevel = $popupPostTypes[$postType];

			/*When user try save pro any module*/
			if($postTypeLevel > YPM_POPUP_PKG) {
				wp_delete_post($post->ID, true);
				self::upgradeMessage($YpmPostTypesInfo, $postType);
				wp_die();
			}

			$post = ypm_recursive_sanitize_text_fields($_POST);;
			$ypmPostType = $postType;
			$ypmPostType = self::getClassNameFormStr($ypmPostType);
			if($postType == YPM_POPUP_POST_TYPE) {
				$ypmPostType = 'Html';
			}
			$popupClassName = $ypmPostType.'Popup';
			require_once(YPM_POPUPS . esc_attr($popupClassName) .'.php');

			$popupClassName = 'YpmPopup\\'.esc_attr($popupClassName);
			$post['ypm-popup-id'] = $postId;
			$popupClassName::create($post);
		}
	}

	private static function upgradeMessage($YpmPostTypesInfo, $postType) {

		$popupPostTypes = $YpmPostTypesInfo['postTypes'];
		$postTypeLevel = $popupPostTypes[$postType];
		$levelName = $YpmPostTypesInfo['levelLabels'][$postTypeLevel];
		$ypmPostType = self::getClassNameFormStr($postType);

		$upgradeMessage = sprintf(esc_attr('You are not allowed to save %s module. If you want to make use of it, you should <a href="'.YPM_POPUP_PRO_URL.'" target="_blank" style="color: red;">Upgrade your current plan</a>. It will be available from our %s version.'), esc_attr($ypmPostType), esc_attr($levelName));
		echo wp_kses($upgradeMessage, YpmAdminHelper::getAllowedTags());
		wp_die();
	}

	private static function saveMetaBoxPopup($postId) {

		$postId = (int)$postId;
		if(empty($_POST['ypm-metabox-popup'])) {
			delete_post_meta($postId, 'ypm-metabox-popup');
			return false;
		}
		else {
			update_post_meta($postId, 'ypm-metabox-popup', (int)$_POST['ypm-metabox-popup']);
		}
	}

	private static function getClassNameFormStr($typeStr) {

		$ypmPostType = str_replace('ypm','',$typeStr);
		$ypmPostType = ucfirst(strtolower($ypmPostType));

		return $ypmPostType;
	}
}