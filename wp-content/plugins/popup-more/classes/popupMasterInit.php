<?php
namespace YpmPopup;
class popupMasterInit {
	
	public function __construct() {

		$this->includeClasses();
		$this->init();
	}

	public function includeClasses() {

		require_once(YPM_POPUPS.'PopupViewInterface.php');
		require_once(YPM_POPUPS.'PopupType.php');
		require_once(YPM_POPUPS.'Popup.php');
		require_once(YPM_POPUP_CLASSES_FRONTEND.'ConditionsChecker.php');
		require_once(YPM_POPUP_CLASSES_FRONTEND.'DisplayChecker.php');
		require_once(YPM_POPUP_CLASSES_FRONTEND.'ScriptsLoader.php');
		require_once(YPM_POPUP_CLASSES_FRONTEND.'PopupGroupFilter.php');
		require_once(YPM_POPUP_CLASSES_FRONTEND.'PopupLoader.php');
		require_once(YPM_POPUP_CLASSES_FRONTEND.'PopupIncluder.php');

		if(YPM_POPUP_PKG != YPM_POPUP_FREE) {
			require_once(YPM_POPUP_ADMIN_CLASSES.'Updates.php');
			require_once(YPM_POPUP_ADMIN_CLASSES_HELPERS.'PopupProData.php');
			require_once(YPM_POPUP_CLASSES_FRONTEND.'ConditionsFilers.php');
			require_once(YPM_POPUP_HELPERS.'ProHelper.php');
			require_once(YPM_POPUP_CLASSES.'ProShortcodes.php');
			require_once(YPM_POPUP_CLASSES.'AjaxPro.php');
			require_once(YPM_POPUP_CLASSES_PRO.'ActionsPro.php');
		}
		require_once(YPM_POPUP_CLASSES.'Installer.php');
		require_once(YPM_POPUP_CLASSES.'YpmFunctions.php');
		// wheel condions
		require_once(YPM_POPUP_ADMIN_WHEEL.'WheelOptions.php');
		// Conditions Builder
		require_once(YPM_POPUP_ADMIN_CONDITIONS.'ConditionBuilder.php');
		require_once(YPM_POPUP_ADMIN_CONDITIONS.'EventsConditionBuilder.php');
		require_once(YPM_POPUP_ADMIN_CONDITIONS.'SpecialEventsConditionBuilder.php');
		require_once(YPM_POPUP_ADMIN_CONDITIONS.'DisplayConditionBuilder.php');
		require_once(YPM_POPUP_ADMIN_CONDITIONS.'ConditionsConditionBuilder.php');
		// Conditions Builder
		// WordPress Script include function integration
		require_once(YPM_POPUP_HELPERS.'MultipleChoiceButton.php');
		require_once(YPM_POPUP_HELPERS.'AdminHelper.php');
		require_once(YPM_POPUP_HELPERS.'FunctionsHelper.php');
		require_once(YPM_POPUP_HELPERS.'ScriptsManager.php');
		require_once(YPM_POPUP_HELPERS.'TabBuilder.php');
		require_once(YPM_POPUP_CLASSES.'YpmMediaButton.php');
		// Popup maker main short code
		require_once(YPM_POPUP_CLASSES.'YpmShortcode.php');
		// popup maker sub post types short codes
		require_once(YPM_POPUP_CLASSES.'Shortcodes.php');
		require_once(YPM_POPUP_CLASSES.'PopupData.php');
		require_once(YPM_POPUP_CLASSES.'YpmSavePopup.php');
		require_once(YPM_POPUP_CLASSES.'YpmRegistration.php');
		require_once(YPM_POPUP_CLASSES.'Ajax.php');
		require_once(YPM_POPUP_CLASSES.'Header.php');
		require_once(YPM_POPUP_ADMIN_CLASSES.'AdminPost.php');
		require_once(YPM_POPUP_CLASSES.'Actions.php');
		require_once(YPM_POPUP_CLASSES.'Filters.php');
		require_once(YPM_POPUP_JS.'JsInluder.php');
		require_once(YPM_POPUP_CSS.'Style.php');
		require_once(YPM_POPUP_HELPERS.'ShowReviewNotice.php');
	}

	public function init() {

		$popupObj = new Actions();
		new Filters();
	}
}