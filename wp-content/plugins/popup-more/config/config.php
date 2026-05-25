<?php
class YpmConfig {

	public function __construct() {
		if (!defined( 'ABSPATH' )) {
			exit();
		}

		$this->addDefines();
		$this->addFilters();
	}

	public static function define($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	private function addFilters() {
		if(YPM_POPUP_PKG != YPM_POPUP_FREE) {
			require_once(YPM_POPUP_CLASSES_PRO.'YpmFiltersPro.php');
			require_once(YPM_POPUP_CLASSES_PRO.'YpmEventsFiltersPro.php');
			require_once(YPM_POPUP_CLASSES_PRO.'YpmConditionsFiltersPro.php');
		}
	}

	public function addDefines()
	{
		self::define('YPM_POPUP_PATH',WP_PLUGIN_DIR.'/'.YPM_FOLDER_NAME.'/');
		self::define('YPM_POPUP_URL', plugins_url().'/'.YPM_FOLDER_NAME.'/');
		self::define('YPM_POPUP_ADMIN_URL', admin_url());
		self::define('YPM_POPUP_ASSETS_URL', YPM_POPUP_URL.'assets/');
		self::define('YPM_POPUP_IMAGES_URL', YPM_POPUP_ASSETS_URL.'img/');
		self::define('YPM_POPUP_SOUNDS_URL', YPM_POPUP_ASSETS_URL.'sounds/');
		self::define('YPM_POPUP_FILE', plugin_basename(dirname(__FILE__).'/'));
		self::define('YPM_POPUP_FILES', YPM_POPUP_PATH . 'files/');
		self::define('YPM_POPUP_CLASSES', YPM_POPUP_PATH . 'classes/');
		self::define('YPM_POPUP_CLASSES_PRO', YPM_POPUP_CLASSES . 'PRO/');
		
		self::define('YPM_POPUP_CLASSES_FRONTEND', YPM_POPUP_CLASSES . '/frontend/');
		self::define('YPM_POPUPS', YPM_POPUP_CLASSES_FRONTEND . '/popups/');
		self::define('YPM_POPUP_ADMIN_CLASSES', YPM_POPUP_CLASSES . 'admin/');
		self::define('YPM_POPUP_ADMIN_CLASSES_HELPERS', YPM_POPUP_ADMIN_CLASSES . 'helpers/');
		self::define('YPM_POPUP_ADMIN_CONDITIONS', YPM_POPUP_ADMIN_CLASSES . 'conditionsBuilder/');
		self::define('YPM_POPUP_ADMIN_WHEEL', YPM_POPUP_ADMIN_CLASSES . 'wheel/');
		self::define('YPM_POPUP_HELPERS', YPM_POPUP_PATH . 'helpers/');
		self::define('YPM_POPUP_LIBS', YPM_POPUP_PATH . 'libs/');
		self::define('YPM_POPUP_TEXT_DOMAIN', 'popup_master');
		self::define('YPM_POPUP_POST_TYPE', 'popupmaster');
		self::define('YPM_IMAGE_POST_TYPE', 'ypmimage');
		self::define('YPM_FACEBOOK_POST_TYPE', 'ypmfacebook');
		self::define('YPM_YOUTUBE_POST_TYPE', 'ypmyoutube');
		self::define('YPM_IFRAME_POST_TYPE', 'ypmiframe');
		self::define('YPM_LINK_POST_TYPE', 'ypmlink');
		self::define('YPM_AGE_RESTRICTION_POST_TYPE', 'ypmagerestriction');
		self::define('YPM_GAMIFICATION_POST_TYPE', 'ypmgamification');
		self::define('YPM_WHEEL_POST_TYPE', 'ypmwheel');
		self::define('YPM_SOCIAL_POST_TYPE', 'ypmsocial');
		self::define('YPM_CONTACT_POST_TYPE', 'ypmcontactform');
		self::define('YPM_SUBSCRIPTION_POST_TYPE', 'ypmsubscription');
		self::define('YPM_COUNTDOWN_POST_TYPE', 'ypmcountdown');
		self::define('YPM_AICHAT_POST_TYPE', 'ypmaichat');
		self::define('YPM_SETTINGS_PAGE', 'ypmSettingsPage');
		self::define('YPM_SUBSCRIBERS_PAGE', 'ypmSubscribersPage');
		self::define('YPM_TRANSIENT_POPUPS_ALL_CATEGORIES', 'ypmGetPostsAllCategories');
		self::define('YPM_TRANSIENT_TIMEOUT_WEEK', 7 * DAY_IN_SECONDS);
		self::define('YPM_REVIEW_URL', 'https://wordpress.org/support/plugin/popup-more/reviews/?filter=5');
		self::define('YPM_SHOW_REVIEW_PERIOD', 15);
		self::define('YPM_AJAX_SUCCESS', 1);
		self::define('YPM_POPUP_FREE', 1);
		self::define('YPM_POPUP_SILVER', 2);
		self::define('YPM_POPUP_GOLD', 3);
		self::define('YPM_POPUP_PLATINUM', 4);
		require_once(dirname(__FILE__).'/config-pkg.php');

		self::define('YPM_POPUP_VERSION', 2.57);
		self::define('YPM_POPUP_PRO_VERSION', 4.24);

		self::define('YPM_POPUP_PRO_URL', 'https://popup-more.com/');
		self::define('YPM_POPUP_DEMO_URL', 'https://demo.popup-more.com/wp-login.php');
		self::define('YPM_SUPPORT_URL', 'https://wordpress.org/support/plugin/popup-more/');

		/*Assets defines*/
		self::define('YPM_POPUP_ASSETS', YPM_POPUP_PATH . 'assets/');
		self::define('YPM_POPUP_VIEW', YPM_POPUP_ASSETS . 'view/');
		self::define('YPM_POPUP_METABOXES', YPM_POPUP_VIEW . 'metaboxes/');
		self::define('YPM_POPUP_GENERAL_METABOXES', YPM_POPUP_METABOXES . 'general/');
		self::define('YPM_POPUP_CSS', YPM_POPUP_ASSETS . 'css/');
		self::define('YPM_POPUP_IMAGE_URL', YPM_POPUP_URL . 'assets/img/');
		self::define('YPM_POPUP_CSS_URL', YPM_POPUP_URL . 'assets/css/');
		self::define('YPM_POPUP_CSS_ADMIN_URL', YPM_POPUP_CSS_URL . '/admin/');
		self::define('YPM_POPUP_JS_URL', YPM_POPUP_URL . 'assets/javascript/');
		self::define('YPM_POPUP_ADMIN_JS_URL', YPM_POPUP_JS_URL . 'admin/');
		self::define('YPM_POPUP_FRONT_JS_URL', YPM_POPUP_JS_URL . 'frontend/');
		self::define('YPM_POPUP_PRO_JS_URL', YPM_POPUP_JS_URL . 'PRO/');
		self::define('YPM_POPUP_JS', YPM_POPUP_ASSETS . 'javascript/');
		self::define('YPM_POPUP_IMG', YPM_POPUP_ASSETS . 'img/');
		self::define('YPM_GAMIFICATION_IMAGE_URL', YPM_POPUP_IMAGE_URL.'gamification/ypm-gift-icon-1.png');
		self::define('YPM_GAMIFICATION_LOSER_IMG_URL', YPM_POPUP_IMAGE_URL.'gamification/loser-smile.png');
		self::define('YPM_GAMIFICATION_IMAGES_COUNT', 20);

		// custom types view
		self::define('YPM_POPUP_MENU_VIEW', YPM_POPUP_VIEW . 'menu/');
		self::define('YPM_POPUP_YOUTUBE_VIEW', YPM_POPUP_VIEW . 'youtube/');
		self::define('YPM_POPUP_COUNTDOWN_VIEW', YPM_POPUP_VIEW . 'countdown/');
		self::define('YPM_POPUP_GAMIFICATION_VIEW', YPM_POPUP_VIEW . 'gamification/');
		self::define('YPM_POPUP_WHEEL_VIEW', YPM_POPUP_VIEW . 'wheel/');

		self::define('YPM_PRO_KEY', 'ypmProVersion');
		self::define('YPM_STORE_URL', 'https://popup-more.com/');
		self::define('YPM_VERSION_'.YPM_PRO_KEY, YPM_POPUP_PRO_VERSION);
		self::define('YPM_LICENSE_PAGE', 'ypmLicensePage');
		self::define('YPM_MAX_OPEN_POPUP', 80);

		self::define('YPM_SUBSCRIBERS_TABLE_NAME', 'ypm_subscribers');
		self::define('YPM_SUBSCRIPTION_FIELDS_TABLE_NAME', 'ypm_subscription_form_fields');
		self::define('YPM_AI_CHAT_MESSAGES', 'ypm_ai_chat_messages');
	}

	public static function YpmHeaders() {

		$headers = "<script type='text/javascript'>
					function ypmAddEvent(element, eventName, fn) {
		                if (element.addEventListener)
		                    element.addEventListener(eventName, fn, false);
		                else if (element.attachEvent)
		                    element.attachEvent('on' + eventName, fn);
	                }
	                YPM_IDS = [];
	                YPM_DATA = [];
	               
	                </script>";

		return $headers;
	}
}

$configObj = new YpmConfig();
