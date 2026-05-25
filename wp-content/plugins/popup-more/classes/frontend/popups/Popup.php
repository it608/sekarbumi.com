<?php
namespace YpmPopup;
use \WP_Query;
use ypmFrontend\ConditionsChecker;
use ypmFrontend\DisplayChecker;

abstract class Popup {

	public $shortCodeName = 'ypm_popup';

	private $sanitizedData;
	private $popupId;
	private $content;
	private $options = array();

	public function __construct()
	{

	}

	public function setPopupId($popupId) {

		$this->popupId = $popupId;
	}

	public function getId()
	{
		return (int)$this->popupId;
	}

	public function getPopupId() {

		return $this->popupId;
	}

	public function setOptions()
	{
		$savedData = Popup::getSavedData($this->getId());
		$this->options = $savedData;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function insertIntoSanitizedData($sanitizedData) {

		if(!empty($sanitizedData)) {
			$this->sanitizedData[$sanitizedData['name']] = $sanitizedData['value'];
		}
	}

	public function setSanitizedData($sanitizedData) {

		$this->sanitizedData = $sanitizedData;
	}

	public function getSanitizedData() {

		return $this->sanitizedData;
	}

	public function sanitizeValueByType($value, $type) {

		$sanitizedValue = '';
		switch ($type) {
			case 'string':
				$sanitizedValue = sanitize_text_field($value);
				break;
			case 'array':
				$sanitizedValue = $this->recursiveSanitizeTextField($value);
				break;
			case 'email':
				$sanitizedValue = sanitize_email($value);
				break;
			case 'textMessage':
				$sanitizedValue = $value;
				break;
			default:
				$sanitizedValue = sanitize_text_field($value);
				break;
		}

		return $sanitizedValue;
	}

	public function recursiveSanitizeTextField($array) {

		foreach ($array as $key => &$value) {
			if (is_array($value)) {
				$value = $this->recursiveSanitizeTextField($value);
			}
			else {
				$value = sanitize_text_field($value);
			}
		}

		return $array;
	}

	public static function create($data, $obj) {

		$id = $data['ypm-popup-id'];
		$obj->setPopupId($id);

		foreach($data as $name => $value) {
			if(strpos($name, 'ypm') === 0) {
				$defaultData = $obj->getDefaultDataByName($name);
				if(empty($defaultData['type'])) {
					$defaultData['type'] = 'string';
				}

				$obj->saveDispayConditionsSettings($data);
				$obj->saveConditionConditionsSettings($data);
				$sanitizedValue = $obj->sanitizeValueByType($value, $defaultData['type']);
				$obj->insertIntoSanitizedData(array('name' => $name,'value' => $sanitizedValue));
			}

		}

		$obj->save();
	}

	private function saveDispayConditionsSettings($data) {
		if(empty($data['ypm-display-settings'])) {
			return '';
		}

		$postId = $this->getId();
		$settings = $data['ypm-display-settings'];

		$obj = new DisplayConditionBuilder();
		$obj->setSavedData($settings);
		$settings = $obj->filterForSave();

		update_post_meta($postId, 'ypm-display-settings', $settings);
	}

	private function saveConditionConditionsSettings($data) {
		if(empty($data['ypm-conditions-settings'])) {
			return '';
		}

		$postId = $this->getId();
		$settings = $data['ypm-conditions-settings'];

		$obj = new ConditionsConditionBuilder();
		$obj->setSavedData($settings);
		$settings = $obj->filterForSave();

		update_post_meta($postId, 'ypm-conditions-settings', $settings);
	}

	public function save() {

		$data = $this->getSanitizedData();
		update_post_meta($data['ypm-popup-id'], "ypm-data", $data);
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		$savedData = Popup::getSavedData($this->getPopupId());
		$defaultData = $this->getDefaultDataByName($optionName);

        $optionValue = null;
	
        if (empty($defaultData['type'])) {
            $defaultData['type'] = 'string';
        }

        if (!empty($savedData)) { //edit mode
            if (isset($savedData[$optionName])) { //option exists in the database
                $optionValue = $savedData[$optionName];
            }
            /* if it's a checkbox, it may not exist in the db
             * if we don't care about it's existence, return empty string
             * otherwise, go for it's default value
             */
            else if ($defaultData['type'] == 'checkbox' && !$forceDefaultValue) {
                $optionValue = '';
            }
        }

        if (($optionValue === null && !empty($defaultData['defaultValue'])) || ($defaultData['type'] == 'number' && !isset($optionValue))) {
            $optionValue = $defaultData['defaultValue'];
        }

        if ($defaultData['type'] == 'checkbox') {
            $optionValue = $this->boolToChecked($optionValue);
        }

        if(isset($defaultData['ver']) && $defaultData['ver'] > YPM_POPUP_PKG) {
            if (empty($defaultData['allow'])) {
                return $defaultData['defaultValue'];
            }
            else if (!in_array($optionValue, $defaultData['allow'])) {
                return $defaultData['defaultValue'];
            }
        }

		return $optionValue;
	}

	public static function getSavedData($popupId, $saveMode = true) {

		$savedData = @get_post_meta($popupId, 'ypm-data', $saveMode);
		$displaySettings = self::getDisplaySettings($popupId);
		$conditionsSettings = self::getConditionsSettings($popupId);

		if (!empty($displaySettings)) {
			$savedData['ypm-display-settings'] = $displaySettings;
		}
		if (!empty($conditionsSettings)) {
			$savedData['ypm-conditions-settings'] = $conditionsSettings;
		}

		return apply_filters('ypmSavedData', $savedData);
	}

	public static function getDisplaySettings($postId) {
		$savedData = get_post_meta($postId, 'ypm-display-settings', true);

		return $savedData;
	}

	public static function getConditionsSettings($postId) {
		$savedData = get_post_meta($postId, 'ypm-conditions-settings', true);

		return $savedData;
	}

	private function getDefaultDataByName($optionName) {

		global $YpmDefaults;
		foreach($YpmDefaults as $option) {

			if($option['name'] == $optionName) {
				return $option;
			}
		}

		return array('name' => $optionName, 'type' => 'text', 'defaultValue' => '');
	}

	public static function getPopupIdTitleData($options = array()) {

		$popupMasterData  = get_post_type_object(YPM_POPUP_POST_TYPE);
		$args = array(
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_status'      => 'publish',
			'post_type'        =>  YPM_POPUP_POST_TYPE,
		);
		$popupMasterData  = get_posts($args);
		$data = array();

		foreach($popupMasterData as $postData) {
			$popupId = $postData->ID;
			if (!empty($options['exclude_ids']) and in_array($popupId, $options['exclude_ids'])) {
				continue;
			}
			$title = $postData->post_title? $postData->post_title: '(no title)';
			$data[$popupId] = esc_attr($title).' - '.esc_attr($popupId);
		}

		return $data;
	}

	public function getSiteLocale() {

		$locale = get_bloginfo('language');
		$locale = str_replace('-', '_', $locale);

		return $locale;
	}

	public function boolToChecked($var) {
		return ($var?'checked':'');
	}

	public static function getModulesDataArray($args = array()) {

		global $YpmPostTypesInfo;
		$modulesDataArray = array();
		$postTypes = $YpmPostTypesInfo['postTypes'];

		if (!empty($args['type']) && !empty($postTypes[$args['type']])) {
			$postTypes = array($args['type'] => $postTypes[$args['type']]);
		}

		if(empty($postTypes)) {
			return $modulesDataArray;
		}
		unset($postTypes[YPM_POPUP_POST_TYPE]);
		foreach($postTypes as $postType => $postTypeLevel) {
			$moduleName = str_replace('ypm', '', $postType);
			$moduleName = ucfirst(strtolower($moduleName));

			$query = new WP_Query(
				array(
					'post_type'      => $postType,
					'posts_per_page' => - 1
				)
			);

			$currentModule = array();
			if($query->have_posts()) {
				/*We check all the popups one by one to realize whether they might be loaded or not.*/
				while($query->have_posts()) {
					$query->next_post();
					$id = $query->post->ID;
					$title = $query->post->post_title;
					if (!$title) {
						$title = 'Untitled';
					}
					$currentModule[$id] = esc_attr($title).' - '.esc_attr($id);
				}
				$modulesDataArray[$moduleName] = $currentModule;
			}
			else {
				if (!empty($args['returnFalse'])) {
					return false;
				}
				$modulesDataArray[$moduleName] = array('' => esc_attr__('No data', 'popup_master'));
			}
		}
		return $modulesDataArray;
	}

	public static function isPostPublished($postId) {

		$status = true;
		$postStatus = get_post_status($postId);
		
		if(empty($postStatus) || get_post_status($postId) != 'publish') {
			$status = false;
		}

		return $status;
	}

	public static function findByIdAndType($postType, $postId = 0) {

		$ypmPostType = str_replace('ypm','',$postType);
		$ypmPostType = ucfirst(strtolower($ypmPostType));
		$popupClassName = $ypmPostType.'Popup';
		if (!file_exists(YPM_POPUPS .esc_attr($popupClassName).'.php')) {
			return false;
		}
		require_once(YPM_POPUPS .esc_attr($popupClassName).'.php');
		$className = __NAMESPACE__.'\\'.esc_attr($popupClassName);
		$popupTypeObj = new $className;
		$popupTypeObj->setPopupId($postId);
		$popupTypeObj->setOptions();

		return $popupTypeObj;
	}

    public static function find($popupId, $args = array())
    {
        global $YpmPostTypesInfo;
        $popupPostType = get_post_type(sanitize_text_field($popupId));
	    $savedData = @get_post_meta($popupId, 'ypm-data', true);

	    if (!empty($savedData['ypm-popup-type'])) {
		    $popupPostType = $savedData['ypm-popup-type'];
	    }

        $popupPostTypes = $YpmPostTypesInfo['postTypes'];
        if ($popupPostType == YPM_POPUP_POST_TYPE || empty($popupPostTypes[$popupPostType])) {
            $popupPostType = 'ypmHtml';
        }

        $popupTypeObj = Popup::findByIdAndType($popupPostType, $popupId);

        return $popupTypeObj;
    }

    public function allowToLoad()
    {
	    global $post;
	    $popupChecker = DisplayChecker::instance();
	    $loadableModes = $popupChecker->isLoadable($this, $post);

	    return ($loadableModes['attr_event'] || $loadableModes['option_event']);
    }

	public function isActive()
	{
		return $this->getOptionValue('ypm-is-active', true);
	}

	public static function getCustomInsertedDataByPostId()
	{
		return array();
	}

	public static function getPopupTypes()
	{
		global $YPM_TYPES;
		$popupTypesObj = array();
		$popupTypes = $YPM_TYPES['typeName'];

		foreach($popupTypes as $type => $options) {
			$level = $options['level'];

			if(empty($level)) {
				$level = YCD_FREE_VERSION;
			}

			$isAvailable = false;
			$typeObj = new PopupType();

//			if ($level == 'comingSoon') {
//				$level = YCD_EXTENSION_VERSION;
//				$typeObj->setIsComingSoon(1);
//			}

			$typeObj->setType($type);
			$typeObj->setOptions($options);
			$typeObj->setHasType($options['hasType']);
			$typeObj->setName($options['displayText']);
			$typeObj->setAccessLevel($level);

			if (!empty($groups[$type])) {
				$typeObj->setGroup($groups[$type]);
			}

			if(YPM_POPUP_PKG >= $level) {
				$isAvailable = true;
			}
			$isAvailable = apply_filters('ypm'.esc_attr($type).'ExtensionAvailable', $isAvailable);
			$typeObj->setAvailable($isAvailable);
			$popupTypesObj[] = $typeObj;
		}

		return $popupTypesObj;
	}

	public static function customStyles($content, $includerObj) {
		$options = $includerObj->getOptions();
		$id = (int)@$options['ypm-popup-id'];

			$content .= '<style>';
			if (!empty($options['ypm-popup-enable-popup-close-button-position'])) {
				$buttonPosition = $options['ypm-popup-close-button-position'];
				list($first, $second) = explode('_', $buttonPosition);
				$content .= '.ypm-popup-content-'.esc_attr($id).' #ypmcboxClose {
					'.esc_attr($first).': '.esc_attr($options['ypm-popup-close-button-'.esc_attr($first)]).' !important;
					'.esc_attr($second).': '.esc_attr($options['ypm-popup-close-button-'.esc_attr($second)]).' !important;
				}';
			}
			if (!empty($options['ypm-content-bg-color'])) {
				$content .= '.ypm-popup-content-'.esc_attr($id).' #ypmcboxContent, .ypm-popup-content-'.esc_attr($id).' #ypmcboxLoadedContent {
					background-color: '.esc_attr($options['ypm-content-bg-color']).' !important;
				}';
			}
			if (!empty($options['ypm-content-text-color'])) {
				$content .= '.ypm-popup-content-'.esc_attr($id).' #ypmcboxContent, .ypm-popup-content-'.esc_attr($id).' #ypmcboxLoadedContent {
					color: '.esc_attr($options['ypm-content-text-color']).' !important;
				}';
			}
			if (!empty($options['ypm-popup-remove-borders'])) {
				$content .= '.ypm-popup-content-'.esc_attr($id).'  
					#ypmcboxTopLeft, #ypmcboxTopCenter, #ypmcboxTopRight,
					#ypmcboxMiddleRight, #ypmcboxBottomCenter, #ypmcboxBottomRight,
					#ypmcboxMiddleLeft, #ypmcboxBottomLeft {
					visibility: hidden !important;
				}';
			}
			$content .= '</style>';


		return $content;
	}

	public static function popupExtraDataRender($content, $popupOptions)
	{
		$id = @$popupOptions['ypm-popup-id'];
		$floatingButton = '';
		if (empty($popupOptions['ypm-popup-floating-enable'])) {
			return $content;
		}
		$allowed_html = \YpmAdminHelper::getAllowedTags();

		$position = $popupOptions['ypm-popup-floating-position'];
		$floatingStyle = $popupOptions['ypm-popup-floating-style'];
		
		$buttonStyles = 'z-index:99999999999;';
		$buttonClass = 'ypm-'.@esc_attr($floatingStyle).'-'.esc_attr($position);
		
		if (isset($floatingStyle) && $floatingStyle == 'button' && strstr($position, 'center')) {
			if (strstr($position, 'top')) {
				$buttonStyles .= 'top: 0%;';
				$buttonStyles .= 'right: 50%;';
				$buttonStyles .= 'transform: translate(50%, 0%);';
			}
			else if (strstr($position, 'bottom')) {
				$buttonStyles .= 'bottom: 0%;';
				$buttonStyles .= 'right: 50%;';
				$buttonStyles .= 'transform: translate(50%, 0%);';
			}
			else if (strstr($position, 'left') || strstr($position, 'right')) {
				$buttonStyles .= 'top: 50%;';

			}
		}

		if (isset($popupOptions['ypm-floating-button-font-size'])) {
			$buttonStyles .= 'font-size: '.esc_attr($popupOptions['ypm-floating-button-font-size']).'px;';
		}
		if (isset($popupOptions['ypm-floating-button-border-size'])) {
			$buttonStyles .= 'border-width: '.esc_attr($popupOptions['ypm-floating-button-border-size']).'px;';
			$buttonStyles .= 'border-style: solid;';
		}
		if (isset($popupOptions['ypm-floating-button-border-radius'])) {
			$buttonStyles .= 'border-radius: '.esc_attr($popupOptions['ypm-floating-button-border-radius']).'px;';
		}
		if (isset($popupOptions['ypm-floating-button-border-color'])) {
			$buttonStyles .= 'border-color: '.esc_attr($popupOptions['ypm-floating-button-border-color']).';';
		}
		if (isset($popupOptions['ypm-floating-button-bg-color'])) {
			$buttonStyles .= 'background-color: '.esc_attr($popupOptions['ypm-floating-button-bg-color']).';';
		}
		if (isset($popupOptions['ypm-floating-button-text-color'])) {
			$buttonStyles .= 'color: '.esc_attr($popupOptions['ypm-floating-button-text-color']).';';
		}

		if ($floatingStyle == 'corner') {
			if ($position === 'top_left') {
				$buttonStyles .= 'left: -220px;';
				$buttonStyles .= 'top: -40px;';
				$buttonStyles .= 'transform: rotate(140deg);';
				$buttonStyles .= 'transform-origin: right center;';
			}
			if ($position === 'bottom_left') {
				$buttonStyles .= 'left: -115px;';
				$buttonStyles .= 'bottom: -145px;';
				$buttonStyles .= 'transform: rotate(45deg);';
				$buttonStyles .= 'transform-origin: right center;';
			}
			if ($position === 'top_right') {
				$buttonStyles .= 'right: 62px;';
				$buttonStyles .= 'top: -145px;';
				$buttonStyles .= 'transform: rotate(-140deg);';
				$buttonStyles .= 'transform-origin: right;';
			}
			if ($position === 'bottom_right') {
				$buttonStyles .= 'right: -65px;';
				$buttonStyles .= 'bottom: -30px;';
				$buttonStyles .= 'transform: rotate(-45deg);';
				$buttonStyles .= 'transform-origin: right;';
			}
			$buttonStyles .= 'width: 160px;';
			$buttonStyles .= 'height: 160px;';
		}

		if ($floatingStyle == 'button') {
			if ($position === 'top_left') {
				$buttonStyles .= 'left: 0px;';
				$buttonStyles .= 'top: 0px;';
			}
			if ($position === 'bottom_left') {
				$buttonStyles .= 'left: 0px;';
				$buttonStyles .= 'bottom: 0px;';
			}
			if ($position === 'top_right') {
				$buttonStyles .= 'right: 0px;';
				$buttonStyles .= 'top: 0px;';
			}
			if ($position === 'bottom_right') {
				$buttonStyles .= 'right: 0px;';
				$buttonStyles .= 'bottom: 0px;';
			}
			if (!empty($popupOptions['ypm-popup-floating-border-radius'])) {
				$buttonStyles .= "border-radius:". esc_attr($popupOptions['ypm-popup-floating-border-radius']).";";
			}
		}
		if (!empty($popupOptions['ypm-popup-floating-border-status'])) {
			$buttonStyles .= 'border-width: '.esc_attr($popupOptions['ypm-popup-floating-border-width']).';';
			$buttonStyles .= 'border-color: '.esc_attr($popupOptions['ypm-popup-floating-border-color']).';';
		}
		$buttonStyles .= 'display: inline-grid;';
		$buttonStyles .= 'background: '.esc_attr($popupOptions['ypm-popup-floating-bg-color']).';';
		$buttonStyles .= 'color: '.esc_attr($popupOptions['ypm-popup-floating-text-color']).';';

		$textSize = $popupOptions['ypm-popup-floating-font-size'];
		$textStyles = 'font-size: '.esc_attr($textSize).'px;';

		$floatingButton = '<div 
			class="'.esc_attr($buttonClass).' ypm-floating-button ypm-popup-id-'.esc_attr($id).' ypm-'.esc_attr($floatingStyle).'-'.esc_attr($position).' ypm-open-popup" 
			data-popup-event="'.esc_attr($popupOptions['ypm-popup-floating-open-event']).'" 
			data-ypm-popup-id="'.esc_attr($id).'" 
			data-popup-event="click" 
			style="'.wp_kses($buttonStyles, $allowed_html).'">
				<span class="ypm-'.esc_attr($floatingStyle).'-floating-button-text" style="'.wp_kses($textStyles, $allowed_html).'">'.esc_attr($popupOptions['ypm-popup-floating-text']).'</span>
			</div>';
		if (!empty($popupOptions['ypm-popup-floating-enable-hover'])) {
			$floatingButton .= '<style>
			.ypm-'.esc_attr($floatingStyle).'-'.esc_attr($position).':hover {
				background: '.esc_attr($popupOptions['ypm-popup-floating-hover-bg-color']).' !important;
				color: '.esc_attr($popupOptions['ypm-popup-floating-hover-text-color']).' !important;
			}			
			</style>';
		}
		$content .= $floatingButton;

		return $content;
	}

	public function popupShortcodesInsidePopup() {
		$popups = array();
		$closeBeh = $this->getOptionValue('ypm-popup-close-behavior');

		if ($closeBeh === 'openPopup') {
			$popupId = $this->getOptionValue('ypm-popup-close-popup');

			$popup = Popup::find($popupId);
			if (!empty($popup) and $popup != 'trash' and $popup != 'inherit') {
				$popup->options['ypm-events-settings'] = array(array('key1' => 'select_settings'));
				$popups[$popupId] = $popup;
			}
		}

		return $popups;
	}

	public function getSubPopupObj() {
		$options = $this->getOptions();
		$subPopups = [];
		if ($options['ypm-popup-special-events-settings']) {
			foreach ($options['ypm-popup-special-events-settings'] as $current) {
				$key2 = '';
				if (!empty($current['key2'])) {
					$key2 = $current['key2'];
				}
				if ( in_array($key2, array('openAnotherPopup', 'wpformOpenAnotherPopup'))) {
					$subPopupId = $current['key3'];
					$subPopupObj = self::find($subPopupId);

					if (!empty($subPopupObj) && ($subPopupObj instanceof Popup)) {
						// We remove all events because this popup will be open after successful subscription
						//$subPopupObj->setEvents(array('param' => 'click', 'value' => ''));
						$subPopupObj->options['ypm-events-settings'] = array(array('param' => 'click', 'value' => ''));
						$subPopups[] = $subPopupObj;
					}
				}
			}
		}

		return $subPopups;
	}

	public function changeDefaultOptionsByNames($defaultOptions, $changingOptions)
	{
		if (empty($defaultOptions) || empty($changingOptions)) {
			return $defaultOptions;
		}
		$changingOptionsNames = array_keys($changingOptions);

		foreach ($defaultOptions as $key => $defaultOption) {
			$defaultOptionName = $defaultOption['name'];
			if (in_array($defaultOptionName, $changingOptionsNames)) {
				$defaultOptions[$key] = $changingOptions[$defaultOptionName];
			}
		}

		return $defaultOptions;
	}
}