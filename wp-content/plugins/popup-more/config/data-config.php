<?php
namespace YpmPopup;
require_once(YPM_POPUP_HELPERS.'ConfigDataHelper.php');

class DataConfig
{
	public static function init()
	{
		self::conditionInit();
		self::defaultValues();
		self::types();
	}

	public static function conditionInit()
	{
		self::addFilters();
		self::globalDisplaySettings();
		self::conditionsSettings();
		self::eventsSettings();
		self::customEventsSettings();
	}

	private static function eventsSettings()
	{
		global $YPM_EVENTS_SETTINGS_CONFIG;

		$keys = array(
			'select_settings' => 'Select settings',
			'Onload' => 'On Load',
			'OnClick' => 'On Click'
		);

		$keys = apply_filters('ypmEventConditionsKeys', $keys);
		$values = array(
			'key1' => $keys,
			'key2' => array('is' => 'Is', 'isnot' => 'Is not'),
			'Onload' => '',
			'OnClick' => array(
				'clickDefault' => esc_attr__('Default', 'popup_master'),
				'clickCustom' => esc_attr__('Custom', 'popup_master')
			)
		);

		$attributes = array(
			'key1' => array(
				'label' => esc_attr__('Select Conditions'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'class' => 'ypm-condition-select js-ypm-select js-conditions-param',
					'value' => ''
				)
			),
			'key2' => array(
				'label' => esc_attr__('Select Conditions'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'class' => 'ypm-condition-select',
					'value' => ''
				)
			),
			'Onload' => array(
				'label' => esc_attr__('Delay'),
				'fieldType' => 'input',
				'allowFromFirstValue' => false,
				'fieldAttributes' => array(
					'class' => 'form-control js-ypm-select'
				)
			),
			'OnClick' => array(
				'label' => esc_attr__('Option'),
				'fieldType' => 'select',
				'allowFromFirstValue' => false,
				'conditionsConf' => true,
				'fieldAttributes' => array(
					'defaultValue' => 'clickDefault',
					'class' => 'js-ypm-sub-param form-control js-ypm-select'
				)
			),
			'clickDefault' => array(
				'label' => esc_attr__('Default class'),
				'fieldType' => 'input',
				'conditional' => 'Please save to generate class name',
				'allowFromFirstValue' => false,
				'fieldAttributes' => array(
					'class' => 'form-control js-ypm-select',
					'defaultValue' => 'ypm-popup-click-{popupId}',
					'readonly' => 'readonly',
					'value' => 'ypm-popup-click-'
				)
			),
			'clickCustom' => array(
				'label' => esc_attr__('Custom class'),
				'fieldType' => 'input',
				'allowFromFirstValue' => false,
				'fieldAttributes' => array(
					'class' => 'form-control js-ypm-select'
				)
			)
		);

		$values = apply_filters('ypmEventConditionsValues', $values);
		$attributes = apply_filters('ypmEventConditionsAttributes', $attributes);

		$YPM_EVENTS_SETTINGS_CONFIG = array(
			'keys' => $keys,
			'values' => $values,
			'attributes' => $attributes
		);
	}

	private static function customEventsSettings()
	{
		global $YPM_CUSTOM_EVENTS_SETTINGS_CONFIG;

		$keys = array(
			'select_settings' => 'Select event',
			'Cf7' => 'Contact form 7',
			'wpform' => 'Contact Form by WPForms',
		);

		$values = array(
			'key1' => $keys,
			'key2' => array('is' => 'Is', 'isnot' => 'Is not'),
			'Cf7' => array(
				'selectBehavior' => esc_attr__('Select behavior', 'popup_master'),
				'redirectToUrl' => esc_attr__('Redirect to URL', 'popup_master'),
				'openAnotherPopup' => esc_attr__('Open another popup', 'popup_master'),
				'closePopup' => esc_attr__('Close current popup', 'popup_master'),
			),
			'wpform' => array(
				'wpformSelectBehavior' => esc_attr__('Select behavior', 'popup_master'),
				'wpformRedirectToUrl' => esc_attr__('Redirect to URL', 'popup_master'),
				'wpformOpenAnotherPopup' => esc_attr__('Open another popup', 'popup_master'),
				'wpformClosePopup' => esc_attr__('Close current popup', 'popup_master'),
			),
			'openAnotherPopup' => \YpmPopup\Popup::getPopupIdTitleData(),
			'wpformOpenAnotherPopup' => \YpmPopup\Popup::getPopupIdTitleData()
		);

		$attributes = array(
			'key1' => array(
				'label' => esc_attr__('Select Conditions'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'class' => 'ypm-condition-select js-ypm-select js-conditions-param',
					'value' => ''
				)
			),
			'key2' => array(
				'label' => esc_attr__('Select Conditions'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'class' => 'ypm-condition-select',
					'value' => ''
				)
			),
			'wpform' => array(
				'label' => esc_attr__('Select Condition'),
				'fieldType' => 'select',
				'allowFromFirstValue' => false,
				'conditionsConf' => false,
				'fieldAttributes' => array(
					'defaultValue' => 'clickDefault',
					'class' => 'js-ypm-sub-param form-control js-ypm-select'
				)
			),
			'Cf7' => array(
				'label' => esc_attr__('Select Condition'),
				'fieldType' => 'select',
				'allowFromFirstValue' => false,
				'conditionsConf' => false,
				'fieldAttributes' => array(
					'defaultValue' => 'clickDefault',
					'class' => 'js-ypm-sub-param form-control js-ypm-select'
				)
			),
			'redirectToUrl' => array(
				'label' => esc_attr__('URL'),
				'fieldType' => 'input',
				'allowFromFirstValue' => false,
				'fieldAttributes' => array(
					'class' => 'form-control js-ypm-select',
					'defaultValue' => '',
					'placeholder' => 'https://',
					'value' => ''
				)
			),
			'wpformRedirectToUrl' => array(
				'label' => esc_attr__('URL'),
				'fieldType' => 'input',
				'allowFromFirstValue' => false,
				'fieldAttributes' => array(
					'class' => 'form-control js-ypm-select',
					'defaultValue' => '',
					'placeholder' => 'https://',
					'value' => ''
				)
			),
			'closePopup' => array(
				'label' => esc_attr__('Delay'),
				'fieldType' => 'input',
				'allowFromFirstValue' => false,
				'fieldAttributes' => array(
					'class' => 'form-control js-ypm-select',
					'placeholder' => '',
					'value' => '0'
				)
			),
			'wpformClosePopup' => array(
				'label' => esc_attr__('Delay'),
				'fieldType' => 'input',
				'allowFromFirstValue' => false,
				'fieldAttributes' => array(
					'class' => 'form-control js-ypm-select',
					'placeholder' => '',
					'value' => '0'
				)
			),
			'openAnotherPopup' => array(
				'label' => esc_attr__('Select popup'),
				'fieldType' => 'select',
				'allowFromFirstValue' => false,
				'fieldAttributes' => array(
					'class' => 'form-control js-ypm-select',
					'defaultValue' => '',
					'placeholder' => '',
					'type' => 'number',
					'value' => 0
				)
			),
			'wpformOpenAnotherPopup' => array(
				'label' => esc_attr__('Select popup'),
				'fieldType' => 'select',
				'allowFromFirstValue' => false,
				'fieldAttributes' => array(
					'class' => 'form-control js-ypm-select',
					'defaultValue' => '',
					'placeholder' => '',
					'type' => 'number',
					'value' => 0
				)
			),
		);

		$values = apply_filters('ypmCustomEventConditionsValues', $values);
		$attributes = apply_filters('ypmCustomEventConditionsAttributes', $attributes);

		$YPM_CUSTOM_EVENTS_SETTINGS_CONFIG = array(
			'keys' => $keys,
			'values' => $values,
			'attributes' => $attributes
		);
	}

	private static function conditionsSettings()
	{
		global $YPM_CONDITIONS_SETTINGS_CONFIG;
		$keys = array(
			'select_settings' => 'Select settings'
		);

		$keys = apply_filters('ypmConditionConditionsKeys', $keys);

		$values = array(
			'key1' => $keys,
			'key2' => array('is' => 'Is', 'isnot' => 'Is not')
		);

		$attributes = array(
			'key1' => array(
				'label' => esc_attr__('Select Conditions'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'class' => 'ypm-condition-select js-ypm-select js-conditions-param',
					'value' => ''
				)
			),
			'key2' => array(
				'label' => esc_attr__('Select Conditions'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'class' => 'ypm-condition-select js-ypm-select',
					'value' => ''
				)
			)
		);

		$values = apply_filters('ypmConditionConditionsValues', $values);
		$attributes = apply_filters('ypmConditionConditionsAttributes', $attributes);

		$YPM_CONDITIONS_SETTINGS_CONFIG = array(
			'keys' => $keys,
			'values' => $values,
			'attributes' => $attributes
		);
	}

	private static function globalDisplaySettings()
	{
		global $YPM_DISPLAY_SETTINGS_CONFIG;

		$keys = array(
			'select_settings' => 'Select settings',
			'everywhere' => 'Everywhere',
			'Post' => array(
				'all_post' => 'All posts',
				'selected_post' => 'Select posts',
				'categories_post' => 'Select post categories'
			),
			'Page' => array(
				'selected_page' => 'Select pages',
				'page_type' => 'Page type',
				'all_page' => 'All pages',
			),
			'Tag' => array(
				'all_tag' => 'All Tags',
				'selected_tag' => 'Select tags',
			)
		);

		$keys = apply_filters('ypmConditionsDisplayKeys', $keys);

		$values = array(
			'key1' => $keys,
			'key2' => array('is' => 'Is', 'isnot' => 'Is not'),
			'selected_post' => array(),
			'categories_post' => self::postTypeCategories(),
			'selected_tag' => self::getAllTags(),
			'all_post' => array(),
			'selected_page' => array(),
			'all_page' => array(),
			'page_type' => self::getPageTypes(),
			'everywhere' => array()
		);

		$attributes = array(
			'key1' => array(
				'label' => esc_attr__('Select Conditions'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'class' => 'ypm-condition-select js-ypm-select js-conditions-param',
					'value' => ''
				)
			),
			'key2' => array(
				'label' => esc_attr__('Select Conditions'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'class' => 'ypm-condition-select js-ypm-select',
					'value' => ''
				)
			),
			'selected_post' => array(
				'label' => esc_attr__('Select Post(s)'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'data-post-type' => 'post',
					'data-select-type' => 'ajax',
					'multiple' => 'multiple',
					'class' => 'ypm-condition-select js-ypm-select',
					'value' => ''
				)
			),
			'categories_post' => array(
				'label' => esc_attr__('Select Post categories'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'multiple' => 'multiple',
					'class' => 'ypm-condition-select js-ypm-select',
					'value' => ''
				)
			),
			'selected_page' => array(
				'label' => esc_attr__('Select Page(s)'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'data-post-type' => 'page',
					'data-select-type' => 'ajax',
					'multiple' => 'multiple',
					'class' => 'ypm-condition-select js-ypm-select',
					'value' => ''
				)
			),
			'selected_tag' => array(
				'label' => esc_attr__('Select Tag'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'multiple' => 'multiple',
					'class' => 'ypm-condition-select js-ypm-select',
					'value' => ''
				)
			),
			'page_type' => array(
				'label' => esc_attr__('Select specific page types'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'class' => 'ypm-condition-select js-ypm-select',
					'multiple' => 'multiple',
					'value' => ''
				)
			),
		);

		$values = apply_filters('ycdConditionsDisplayValues', $values);
		$attributes = apply_filters('ycdConditionsDisplayAttributes', $attributes);

		$YPM_DISPLAY_SETTINGS_CONFIG = array(
			'keys' => $keys,
			'values' => $values,
			'attributes' => $attributes
		);
	}

	public static function defaultValues() {
		global $YpmDefaults;
		global $YpmDefaultsData;
		global $YpmPostTypesInfo;

		$options = array();

		$exitMode = array(
			'soft' => esc_attr__('Soft mode', 'popup_master'),
			'agressive' => esc_attr__('Aggressive mode', 'popup_master'),
			'softAgres' => esc_attr__('Soft and Aggressive modes', 'popup_master'),
			'alert' => esc_attr__('Aggressive without popup', 'popup_master')
		);

		$socialThemes = array(
			'flat' => esc_attr__('Flat', 'popup_master'),
			'classic' => esc_attr__('Classic', 'popup_master'),
			'minima' => esc_attr__('Minima', 'popup_master'),
			'plain' => esc_attr__('Plain', 'popup_master')
		);

		$socialShareIn = array(
			'blank' => esc_attr__('Blank', 'popup_master'),
			'popup' => esc_attr__('Inside window', 'popup_master'),
			'self' => esc_attr__('Self', 'popup_master')
		);

		$socialFontSizes = array(
			'8' => '8',
			'10' => '10',
			'12' => '12',
			'14' => '14',
			'16' => '16',
			'18' => '18',
			'20' => '20',
			'24' => '24'
		);

		$socialThemeShareCount = array(
			'true' => esc_attr__('True', 'popup_master'),
			'false' => esc_attr__('False', 'popup_master'),
			'inside' => esc_attr__('Inside', 'popup_master')
		);

		$fblikeLayout = array(
			'standard' => esc_attr__('Standard', 'popup_master'),
			'button_count' => esc_attr__('Button with count', 'popup_master'),
			'box_count' => esc_attr__('Box with count', 'popup_master'),
			'button' => esc_attr__('Button', 'popup_master')
		);

		$fblikeShareLayout = array(
			'box_count' => esc_attr__('Box with count', 'popup_master'),
			'button_count' => esc_attr__('Button with count', 'popup_master'),
			'button' => esc_attr__('Button', 'popup_master')
		);

		$fblikeAction = array(
			'like' => esc_attr__('Like', 'popup_master'),
			'recommend' => esc_attr__('Recommend', 'popup_master')
		);

		$fblikeSize = array(
			'small' => esc_attr__('Small', 'popup_master'),
			'large' => esc_attr__('Large', 'popup_master')
		);

		$fbLikeAlignment = array(
			'left' => esc_attr__('Left', 'popup_master'),
			'center' => esc_attr__('Center', 'popup_master'),
			'right' => esc_attr__('Right', 'popup_master')
		);

		$devices = array(
			'desktop' => esc_attr__('Desktop', 'popup_master'),
			'tablet' => esc_attr__('Tablet', 'popup_master'),
			'isiOS' => esc_attr__('Ios', 'popup_master'),
			'isAndroid' => esc_attr__('Android', 'popup_master')
		);

		$countries = array(
		);

		$postTypes = apply_filters('ypm-post-types', array(
			YPM_POPUP_POST_TYPE,
			YPM_IMAGE_POST_TYPE,
			YPM_FACEBOOK_POST_TYPE,
			YPM_AGE_RESTRICTION_POST_TYPE,
			YPM_AICHAT_POST_TYPE,
			YPM_GAMIFICATION_POST_TYPE,
			YPM_WHEEL_POST_TYPE,
			YPM_YOUTUBE_POST_TYPE,
			YPM_IFRAME_POST_TYPE,
			YPM_SOCIAL_POST_TYPE,
			YPM_CONTACT_POST_TYPE,
			YPM_SUBSCRIPTION_POST_TYPE,
			YPM_COUNTDOWN_POST_TYPE
		));

		$targetDefaultValue = array(array('key1' => 'select_settings'));
		$eventsDefaultValue = array(array('key1' => 'Onload', 'key2' => '0'));

		$options[] = array('name' => 'ypm-events-settings', 'type' => 'array', 'defaultValue' => $eventsDefaultValue);
		$options[] = array('name' => 'ypm-popup-special-events-settings', 'type' => 'array', 'defaultValue' => $targetDefaultValue);
		$options[] = array('name' => 'ypm-conditions-settings', 'type' => 'array', 'defaultValue' => $targetDefaultValue);
		$options[] = array('name' => 'ypm-display-settings', 'type' => 'array', 'defaultValue' => $targetDefaultValue);
		$options[] = array('name' => 'ypm-popup-width', 'type' => 'string', 'defaultValue' => '640px');
		$options[] = array('name' => 'ypm-popup-height', 'type' => 'string', 'defaultValue' => '480px');
		$options[] = array('name' => 'ypm-popup-max-width', 'type' => 'string', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-max-height', 'type' => 'string', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-dimensions-mode', 'type' => 'string', 'defaultValue' => 'custom');
		$options[] = array('name' => 'ypm-popup-dimensions-auto-size', 'type' => 'string', 'defaultValue' => 'auto');
		$options[] = array('name' => 'ypm-popup-theme', 'type' => 'string', 'defaultValue' => 'colorbox1');
		$options[] = array('name' => 'ypm-popup-theme-close-text', 'type' => 'string', 'defaultValue' => 'close');
		$options[] = array('name' => 'ypm-esc-key', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'ypm-close-button', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'ypm-enable-close-delay', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'ypm-show-close-delay', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-close-delay-font-size', 'type' => 'text', 'defaultValue' => '16px');
		$options[] = array('name' => 'ypm-close-delay-color', 'type' => 'text', 'defaultValue' => '#dd3333');
		$options[] = array('name' => 'ypm-close-button-delay', 'type' => 'text', 'defaultValue' => '1');
		$options[] = array('name' => 'ypm-close-button-click-sound', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-overlay-click', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'ypm-overlay-color', 'type' => 'string', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-disable-overlay', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-enable-bg-image', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-title', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-close-delay', 'type' => 'string', 'defaultValue' => 0);
		$options[] = array('name' => 'ypm-delay', 'type' => 'string', 'defaultValue' => 0);
		$options[] = array('name' => 'ypm-popup-opening-animation-speed', 'type' => 'string', 'defaultValue' => 1);
		$options[] = array('name' => 'ypm-popup-close-animation-speed', 'type' => 'string', 'defaultValue' => 1);
		$options[] = array('name' => 'ypm-z-index', 'type' => 'string', 'defaultValue' => 9999);
		$options[] = array('name' => 'ypm-content-padding', 'type' => 'string', 'defaultValue' => "0px");
		$options[] = array('name' => 'ypm-content-border-radius', 'type' => 'string', 'defaultValue' => "0px");
		$options[] = array('name' => 'ypm-popup-exit-mode', 'type' => 'string', 'defaultValue' => 'soft');
		$options[] = array('name' => 'ypm-exit-alert-text', 'type' => 'string', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-exit-per-day', 'type' => 'string', 'defaultValue' => '1');
		$options[] = array('name' => 'ypm-exit-page-lavel', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'ypm-exit-leave-top', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-exit-enable', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-overlay-opacity', 'type' => 'text', 'defaultValue' => 0.8);
		$options[] = array('name' => 'ypm-is-active', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'ypm-facebook-type', 'type' => 'text', 'defaultValue' => 'likeButton');
		$options[] = array('name' => 'ypm-facebook-layout', 'type' => 'text', 'defaultValue' => 'likeButton');
		$options[] = array('name' => 'ypm-facebook-action', 'type' => 'text', 'defaultValue' => 'like');
		$options[] = array('name' => 'ypm-facebook-size', 'type' => 'text', 'defaultValue' => 'small');
		$options[] = array('name' => 'ypm-facebook-url', 'type' => 'text', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-facebook-share-button', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'ypm-facebook-like-alignment', 'type' => 'text', 'defaultValue' => 'center');
		$options[] = array('name' => 'ypm-facebook-type', 'type' => 'text', 'defaultValue' => 'center');
		$options[] = array('name' => 'ypm-facebook-share-url', 'type' => 'text', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-facebook-share-layout', 'type' => 'text', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-facebook-share-size', 'type' => 'text', 'defaultValue' => 'small');
		$options[] = array('name' => 'ypm-facebook-share-alignment', 'type' => 'text', 'defaultValue' => 'center');
		$options[] = array('name' => 'ypm-show-on-device-status', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-devices', 'type' => 'array', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-selected-countries-status', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-selected-countries', 'type' => 'array', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-content-click-status', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-content-click-redirect-enable', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-content-click-redirect-enable', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-content-click-redirect-tab', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-close-redirection-url-tab', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-showing-limitation', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-limitation-shwoing-count', 'type' => 'text', 'defaultValue' => 1);
		$options[] = array('name' => 'ypm-limitation-shwoing-expiration', 'type' => 'text', 'defaultValue' => 1);
		$options[] = array('name' => 'ypm-show-popup-same-user-page-level', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-content-click-count', 'type' => 'text', 'defaultValue' => 1);
		$options[] = array('name' => 'ypm-title-color', 'type' => 'text', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-title-font-size', 'type' => 'text', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-disable-page-scrolling', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-location', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-floating-enable', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-floating-position', 'type' => 'text', 'defaultValue' => 'bottom_right');
		$options[] = array('name' => 'ypm-popup-floating-font-size', 'type' => 'text', 'defaultValue' => '16');
		$options[] = array('name' => 'ypm-popup-floating-bg-color', 'type' => 'text', 'defaultValue' => '#5263eb');
		$options[] = array('name' => 'ypm-popup-floating-text-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$options[] = array('name' => 'ypm-popup-floating-text', 'type' => 'text', 'defaultValue' => 'Click it!');
		$options[] = array('name' => 'ypm-popup-floating-border-radius', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'ypm-popup-floating-border-status', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-floating-enable-hover', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-floating-border-width', 'type' => 'text', 'defaultValue' => '1px');
		$options[] = array('name' => 'ypm-popup-floating-border-color', 'type' => 'text', 'defaultValue' => '#5263eb');
		$options[] = array('name' => 'ypm-popup-floating-open-event', 'type' => 'text', 'defaultValue' => 'click');
		$options[] = array('name' => 'ypm-iframe-url', 'type' => 'text', 'defaultValue' => 'https://www.wikipedia.org/');
		$options[] = array('name' => 'ypm-iframe-width', 'type' => 'text', 'defaultValue' => '300px');
		$options[] = array('name' => 'ypm-iframe-height', 'type' => 'text', 'defaultValue' => '200px');
		$options[] = array('name' => 'ypm-popup-floating-hover-text-color', 'type' => 'text', 'defaultValue' => '#5263eb');
		$options[] = array('name' => 'ypm-popup-floating-hover-bg-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		// youtube defaults
		$options[] = array('name' => 'ypm-youtube-width', 'type' => 'text', 'defaultValue' => '300px');
		$options[] = array('name' => 'ypm-youtube-height', 'type' => 'text', 'defaultValue' => '300px');
		$options[] = array('name' => 'ypm-youtube-start', 'type' => 'text', 'defaultValue' => '0');
		$options[] = array('name' => 'ypm-popup-type', 'type' => 'text', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-remove-borders', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-enable-popup-close-button-position', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-close-button-top', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'ypm-popup-close-button-right', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'ypm-popup-close-button-bottom', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'ypm-popup-close-button-left', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'ypm-popup-close-behavior', 'type' => 'text', 'defaultValue' => 'default');
		$options[] = array('name' => 'ypm-popup-link-selector', 'type' => 'text', 'defaultValue' => 'all');
		$options[] = array('name' => 'ypm-popup-disable-statistic', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-enable-start-date', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-enable-end-date', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-schedule-working-hours', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-popup-schedule-working-hours-day', 'type' => 'array', 'defaultValue' => '');

		$options[] = array('name' => 'ypm-ai-button-label', 'type' => 'text', 'defaultValue' => 'Send');
        $options[] = array('name' => 'ypm-ai-button-bg-color', 'type' => 'text', 'defaultValue' => '#007bff');
        $options[] = array('name' => 'ypm-ai-button-color', 'type' => 'text', 'defaultValue' => '#ffffff');
        $options[] = array('name' => 'ypm-ai-voice-enable', 'type' => 'checkbox', 'defaultValue' => '');
        $options[] = array('name' => 'ypm-ai-disable-save-message', 'type' => 'checkbox', 'defaultValue' => '');

		$options[] = array('name' => 'ypm-popup-enable-sus-notifcation', 'type' => 'checkbox', 'defaultValue' => '');

		$dataOptions = [
			1 => ['label' => 'Option 1', 'color' => '#ff0000', 'probability' => 20],
			2 => ['label' => 'Option 2', 'color' => '#00ff00', 'probability' => 15],
			3 => ['label' => 'Option 3', 'color' => '#0000ff', 'probability' => 25],
			4 => ['label' => 'Option 4', 'color' => '#ff9900', 'probability' => 10],
			5 => ['label' => 'Option 5', 'color' => '#6600cc', 'probability' => 20],
			6 => ['label' => 'Option 6', 'color' => '#0099cc', 'probability' => 10],
		];		
		$options[] = array('name' => 'ypm-wheeloptions', 'type' => 'array', 'defaultValue' => $dataOptions);
		$options[] = array('name' => 'ypm-wheel-win-sound', 'type' => 'checkbox', 'defaultValue' => "true");
		$options[] = array('name' => 'ypm-wheel-win-sound', 'type' => 'checkbox', 'defaultValue' => "true");
		$options[] = array('name' => 'ypm-wheel-sound', 'type' => 'checkbox', 'defaultValue' => "true");
		$options[] = array('name' => 'ypm-wheel-text-colors', 'type' => 'text', 'defaultValue' => "#000");
		$options[] = array('name' => 'ypm-wheel-sound-url', 'type' => 'text', 'defaultValue' => esc_attr(YPM_POPUP_SOUNDS_URL."tick.mp3"));
		$options[] = array('name' => 'ypm-wheel-win-sound-url', 'type' => 'text', 'defaultValue' => esc_attr(YPM_POPUP_SOUNDS_URL."winner.mp3"));
		$options[] = array('name' => 'ypm-wheel-button-title', 'type' => 'text', 'defaultValue' => 'Spin');
		$options[] = array('name' => 'ypm-wheel-button-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$options[] = array('name' => 'ypm-wheel-button-bg-color', 'type' => 'text', 'defaultValue' => '#cd2653');
		$options[] = array('name' => 'ypm-wheel-button-hover-color', 'type' => 'text', 'defaultValue' => '#cd2653');
		$options[] = array('name' => 'ypm-wheel-button-hover-bg-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$options[] = array('name' => 'ypm-wheel-btn-hover', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ypm-wheel-arrow-size', 'type' => 'text', 'defaultValue' => '1');

		$YpmDefaults = apply_filters('ypmDefaultOptions', $options);
		// ToDo: fixed thix
		$YpmDefaults[] = array('name' => 'ypm-gamification-start-text', 'type' => 'textMessage', 'defaultValue' => '');
		$YpmDefaults[] = array('name' => 'ypm-gamification-play-text', 'type' => 'textMessage', 'defaultValue' =>  '');
		$YpmDefaults[] = array('name' => 'ypm-gamification-lose-text', 'type' => 'textMessage', 'defaultValue' =>  '');
		$YpmDefaults[] = array('name' => 'ypm-gamification-win-text', 'type' => 'textMessage', 'defaultValue' =>  '');

		$YpmDefaultsData['exitMode'] = $exitMode;
		$YpmDefaultsData['socialShareIn'] = $socialShareIn;
		$YpmDefaultsData['socialThemes'] = $socialThemes;
		$YpmDefaultsData['socialThemeShareCount'] = $socialThemeShareCount;
		$YpmDefaultsData['socialFontSizes'] = $socialFontSizes;
		$YpmDefaultsData['fblikeLayout'] = $fblikeLayout;
		$YpmDefaultsData['fblikeAction'] = $fblikeAction;
		$YpmDefaultsData['fblikeSize'] = $fblikeSize;
		$YpmDefaultsData['postTypes'] = $postTypes;
		$YpmDefaultsData['fbLikeAlignment'] = $fbLikeAlignment;
		$YpmDefaultsData['fblikeShareLayout'] = $fblikeShareLayout;
		$YpmDefaultsData['devices'] = $devices;
		$YpmDefaultsData['countries'] = apply_filters('ypm-countries', $countries);

		$YpmPostTypesInfo['postTypes'] = array(
			YPM_POPUP_POST_TYPE => YPM_POPUP_FREE,
			YPM_IMAGE_POST_TYPE => YPM_POPUP_FREE,
			YPM_FACEBOOK_POST_TYPE => YPM_POPUP_FREE,
			YPM_AGE_RESTRICTION_POST_TYPE => YPM_POPUP_FREE,
			YPM_AICHAT_POST_TYPE => YPM_POPUP_FREE,
			YPM_SUBSCRIPTION_POST_TYPE => YPM_POPUP_FREE,
			YPM_YOUTUBE_POST_TYPE => YPM_POPUP_FREE,
			YPM_SUBSCRIPTION_POST_TYPE => YPM_POPUP_FREE,
			YPM_GAMIFICATION_POST_TYPE => YPM_POPUP_FREE,
			YPM_WHEEL_POST_TYPE => YPM_POPUP_FREE,
			YPM_IFRAME_POST_TYPE => YPM_POPUP_SILVER,
			YPM_SOCIAL_POST_TYPE => YPM_POPUP_SILVER,
			YPM_LINK_POST_TYPE => YPM_POPUP_GOLD,
			YPM_CONTACT_POST_TYPE => YPM_POPUP_GOLD,
			YPM_COUNTDOWN_POST_TYPE => YPM_POPUP_GOLD,
		);

		$YpmPostTypesInfo['postTypesLabels'] = array(
			YPM_AICHAT_POST_TYPE => esc_attr__('AI ChatGPT', 'popup_master'),
			YPM_POPUP_POST_TYPE => YPM_POPUP_FREE,
			YPM_IMAGE_POST_TYPE => esc_attr__('Image', 'popup_master'),
			YPM_FACEBOOK_POST_TYPE => esc_attr__('Facebook', 'popup_master'),
			YPM_AGE_RESTRICTION_POST_TYPE => esc_attr__('Age Restriction', 'popup_master'),
			YPM_YOUTUBE_POST_TYPE => esc_attr__('Youtube', 'popup_master'),
			YPM_IFRAME_POST_TYPE => esc_attr__('Iframe', 'popup_master'),
			YPM_SOCIAL_POST_TYPE => esc_attr__('Social', 'popup_master'),
			YPM_LINK_POST_TYPE => esc_attr__('Dynamic Link Content', 'popup_master'),
			YPM_CONTACT_POST_TYPE => esc_attr__('Contact Form', 'popup_master'),
			YPM_COUNTDOWN_POST_TYPE => esc_attr__('Countdown', 'popup_master'),
			YPM_SUBSCRIPTION_POST_TYPE => esc_attr__('Subscription', 'popup_master'),
			YPM_GAMIFICATION_POST_TYPE => esc_attr__('Gamification', 'popup_master'),
			YPM_WHEEL_POST_TYPE => esc_attr__('Wheel', 'popup_master'),
		);

		$YpmPostTypesInfo['levelLabels'] = array(
			YPM_POPUP_FREE => esc_attr__('Free', 'popup_master'),
			YPM_POPUP_SILVER => esc_attr__('Silver', 'popup_master'),
			YPM_POPUP_GOLD => esc_attr__('Gold', 'popup_master')
		);

	}

	// helpers
	public static function getPageTypes()
	{
		$postTypes = array();

		$postTypes['is_home_page'] = esc_attr__('Home Page', 'popup_master');
		$postTypes['is_home'] = esc_attr__('Posts Page', 'popup_master');
		$postTypes['is_search'] = esc_attr__('Search Pages', 'popup_master');
		$postTypes['is_404'] = esc_attr__('404 Pages', 'popup_master');
		if (function_exists('is_shop')) {
			$postTypes['is_shop'] = esc_attr__('Shop Page', 'popup_master');
		}
		if (function_exists('is_archive')) {
			$postTypes['is_archive'] = esc_attr__('Archive Page', 'popup_master');
		}

		return $postTypes;
	}

	public static function postTypeCategories($postType = 'post', $taxonomy = '')
	{
		if (empty($taxonomy)) {
			$taxonomy = 'category';
		}
		$args = array(
			'type'         => $postType,
			'orderby'      => 'name',
			'hide_empty'   => 0,
			'taxonomy'     => $taxonomy
		);

		$categories = get_terms( $args );
		$slugs = array();

		foreach( $categories as $category ) {
			$slugs[ $category->term_id] =  $category->slug;
		}

		return $slugs;
	}

	public static function getAllTags() {
		$allTags = array();
		$tags = get_tags(array(
			'hide_empty' => false
		));

		foreach ($tags as $tag) {
			$allTags[$tag->slug] = $tag->name;
		}

		return $allTags;
	}

	public static function addFilters()
	{
		add_filter('ypmConditionsDisplayKeys', array(__CLASS__, 'conditionsDisplayKeys'),1,1);
		add_filter('ycdConditionsDisplayAttributes', array(__CLASS__, 'conditionsDisplayAttributes'),1,1);
		add_filter('ycdConditionsDisplayValues', array(__CLASS__, 'conditionsDisplayValues'),1,1);
	}

	public static function conditionsDisplayKeys($keys) {
		$allCustomPostTypes = self::getAllCustomPosts();
		foreach ($allCustomPostTypes as $customPostType) {
			if ($customPostType === 'product') {
				$keys['WooCommerce'] = array(
					'all_'.$customPostType => 'All WooCommerce',
					'selected_'.$customPostType => 'Select '.ucfirst($customPostType),
					'shop_page' => 'Shop Page',
					'cart_page' => 'Cart Page',
					'account_page' => 'Account Page',
					'categories_'.$customPostType => 'Select WooCommerce categories'
				);
				continue;
			}
			$keys[$customPostType] = array(
				'all_'.$customPostType => 'All '.ucfirst($customPostType).'s',
			//	$customPostType.'_archive' => 'Archives '.ucfirst($customPostType).'s',
				'selected_'.$customPostType => 'Select '.ucfirst($customPostType).'s',
				'categories_'.$customPostType => 'Select '.ucfirst($customPostType).' categories'
			);
		}

		return $keys;
	}

	public static function conditionsDisplayAttributes($attributes) {

		$allCustomPostTypes = self::getAllCustomPosts();
		foreach ($allCustomPostTypes as $customPostType) {
			$attributes['selected_'.$customPostType] = array(
				'label' => esc_attr('Select Post(s)'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'data-post-type' => $customPostType,
					'data-select-type' => 'ajax',
					'multiple' => 'multiple',
					'class' => 'ypm-condition-select js-ypm-select',
					'value' => ''
				)
			);
			$attributes['categories_'.$customPostType] = array(
				'label' => esc_attr('Select '.ucfirst($customPostType).' categories'),
				'fieldType' => 'select',
				'fieldAttributes' => array(
					'multiple' => 'multiple',
					'class' => 'ypm-condition-select js-ypm-select',
					'value' => ''
				)
			);
		}

		return $attributes;
	}

	public static function conditionsDisplayValues($values)
	{
		$allCustomPostTypes = self::getAllCustomPosts();
		foreach ($allCustomPostTypes as $customPostType) {
			$taxonomyObjects = get_object_taxonomies($customPostType);
			if ($customPostType == 'product') {
				$taxonomyObjects = array('product_cat');
			}
			$values['categories_'.$customPostType] = self::postTypeCategories($customPostType,$taxonomyObjects);
		}

		return $values;
	}

	public static function getAllCustomPosts()
	{
		$args = array(
			'public' => true,
			'_builtin' => false
		);

		$allCustomPosts = get_post_types($args);

		if (isset($allCustomPosts[YPM_POPUP_POST_TYPE])) {
			unset($allCustomPosts[YPM_POPUP_POST_TYPE]);
		}

		return $allCustomPosts;
	}

	public static function types() {
		global $YPM_TYPES;

		$YPM_TYPES['typeName'] = apply_filters('ypmTypes', array(
			YPM_AICHAT_POST_TYPE => array(
				'level' => YPM_POPUP_FREE,
				'hasType' => true,
				'displayText' => 'AI ChatGPT'
			),
			YPM_IMAGE_POST_TYPE => array(
				'level' => YPM_POPUP_FREE,
				'hasType' => false,
				'displayText' => 'Image',
				'videoURL' => 'https://www.youtube.com/watch?v=sWYJeEzZTKY'
			),
			'HTML' => array(
				'level' => YPM_POPUP_FREE,
				'hasType' => false,
				'displayText' => 'HTML'
			),
			YPM_SUBSCRIPTION_POST_TYPE => array(
				'level' => YPM_POPUP_FREE,
				'hasType' => true,
				'displayText' => 'Subscription'
			),
			YPM_FACEBOOK_POST_TYPE => array(
				'level' => YPM_POPUP_FREE,
				'hasType' => true,
				'displayText' => 'Facebook'
			),
			YPM_AGE_RESTRICTION_POST_TYPE => array(
				'level' => YPM_POPUP_FREE,
				'hasType' => true,
				'displayText' => 'Age Restriction'
			),
			YPM_GAMIFICATION_POST_TYPE => array(
				'level' => YPM_POPUP_FREE,
				'hasType' => true,
				'displayText' => 'Gamification'
			),
			YPM_WHEEL_POST_TYPE => array(
				'level' => YPM_POPUP_FREE,
				'hasType' => true,
				'displayText' => 'Wheel'
			),
			YPM_YOUTUBE_POST_TYPE => array(
				'level' => YPM_POPUP_FREE,
				'hasType' => true,
				'displayText' => 'Youtube'
			),
			YPM_IFRAME_POST_TYPE => array(
				'level' => YPM_POPUP_SILVER,
				'hasType' => true,
				'displayText' => 'Iframe'
			),
			YPM_SOCIAL_POST_TYPE => array(
				'level' => YPM_POPUP_SILVER,
				'hasType' => true,
				'displayText' => 'Social'
			),
			YPM_CONTACT_POST_TYPE => array(
				'level' => YPM_POPUP_SILVER,
				'hasType' => true,
				'displayText' => 'Contact form'
			),
			YPM_CONTACT_POST_TYPE => array(
				'level' => YPM_POPUP_GOLD,
				'hasType' => true,
				'displayText' => 'Contact form'
			),
			YPM_COUNTDOWN_POST_TYPE => array(
				'level' => YPM_POPUP_GOLD,
				'hasType' => true,
				'displayText' => 'Countdown',
				'videoURL' => 'https://www.youtube.com/watch?v=qe1TfIcmKGc'
			),
			YPM_LINK_POST_TYPE => array(
				'level' => YPM_POPUP_GOLD,
				'hasType' => false,
				'displayText' => 'Dynamic Link Content',
				'videoURL' => 'https://www.youtube.com/watch?v=jhA9WQ1L1i8'
			),
		));
	}

}
