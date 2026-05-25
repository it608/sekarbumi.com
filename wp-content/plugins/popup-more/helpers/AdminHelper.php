<?php

class YpmAdminHelper
{
	public static function copyClipboard($postId, $shortCodeName, $eventName = '')
	{
		$id = esc_attr($postId).'-'.esc_attr($eventName);
		return '<div class="ypm-tooltip">
					<span class="ypm-tooltiptext" id="ypm-tooltip-'.esc_attr($id).'">'.
			esc_attr__('Copy to clipboard', 'popup_master').'</span>
					
					<input onfocus="this.select();" readonly="" value=\''.esc_attr($shortCodeName).'\' data-id="'.esc_attr($id).'" class="large-text code ypm-shortcode-input ypm-shortcode-input-'.esc_attr($id).'" id="ypm-shortcode-input-'.esc_attr($id).'" type="text">
				</div>';
	}

	public static function selectBox($data = array(), $selectedValue = array(), $attrs = array())
	{
		$attrString = self::formatHTMLAttrStr($attrs);
		$selected = '';

		$selectBox = '<select '.wp_kses($attrString, YpmAdminHelper::getAllowedTags()).'>';
		if (!empty($data)) {
			foreach ($data as $value => $label) {

				/*When is multiselect*/
				if(is_array($selectedValue)) {
					$isSelected = in_array($value, $selectedValue);
					if($isSelected) {
						$selected = 'selected';
					}
				}
				else if($selectedValue == $value) {
					$selected = 'selected';
				}
				else if(is_array($value) && in_array($selectedValue, $value)) {
					$selected = 'selected';
				}

				if (is_array($label)) {
					$selectBox .= '<optgroup label="'.esc_attr($value).'">';
					foreach ($label as $key => $optionLabel) {
						$selected = '';
						if (is_array($selectedValue)) {
							$isSelected = in_array($key, $selectedValue);
							if ($isSelected) {
								$selected = 'selected';
							}
						}
						else if ($selectedValue == $key) {
							$selected = 'selected';
						}
						else if (is_array($key) && in_array($selectedValue, $key)) {
							$selected = 'selected';
						}

						$selectBox .= '<option value="'.esc_attr($key).'" '.esc_attr($selected).'>'.esc_attr($optionLabel).'</option>';
					}
					$selectBox .= '</optgroup>';
				}
				else {
					$selectBox .= '<option value="'.esc_attr($value).'" '.esc_attr($selected).'>'.esc_attr($label).'</option>';
				}

				$selected = '';
			}
		}

		$selectBox .= '</select>';

		return $selectBox;
	}

	public static function inputElement($currentData, $savedValue, $fieldAttributes)
	{
	    $fieldAttributes['value'] = isset($savedValue) ? $savedValue : @$fieldAttributes['value'];
		$attrString = self::formatHTMLAttrStr($fieldAttributes);

		return "<input $attrString>";
	}

	public static function switchElement($currentData, $savedValue, $fieldAttributes)
    {
        $value = !empty($savedValue) ? 'checked': '';
        $attrString = self::formatHTMLAttrStr($fieldAttributes);
        ob_start();
        ?>
            <label class="ypm-switch">
                <input <?php echo wp_kses($attrString, YpmAdminHelper::getAllowedTags()); ?><?php echo esc_attr($value); ?>>
                <span class="ypm-slider ypm-round"></span>
            </label>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

	public static function getPostTypeData($args = array())
	{
		$query = self::getQueryDataByArgs($args);

		$posts = array();
		foreach ($query->posts as $post) {
			$posts[$post->ID] = $post->post_title;
		}

		return $posts;
	}

	public static function getQueryDataByArgs($args = array())
	{
		$defaultArgs = array(
			'offset'           =>  0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'post_type'        => 'post',
			'posts_per_page'   => 1000
		);

		$args = wp_parse_args($args, $defaultArgs);
		$query = new WP_Query($args);

		return $query;
	}

	public static function getCurrentPostType()
	{
		global $post_type;
		global $post;
		$currentPostType = '';

		if (is_object($post)) {
			$currentPostType = $post->post_type;
		}

		// in some themes global $post returns null
		if (empty($currentPostType)) {
			$currentPostType = $post_type;
		}

		if (empty($currentPostType) && !empty($_GET['post'])) {
			$currentPostType = get_post_type((int)$_GET['post']);
		}

		return $currentPostType;
	}

	public static function serializeData($data = array())
	{
		$serializedData = serialize($data);

		return $serializedData;
	}

	public static function updateOption($optionKey, $optionValue)
	{
		update_option($optionKey, $optionValue);
	}

	public static function getOption($optionKey, $default = false)
	{
		return get_option($optionKey, $default);
	}

	public static function deleteOption($optionKey)
	{
		delete_option($optionKey);
	}

	public static function formatHTMLAttrStr($attrs)
	{
		$attrString = '';
		if(!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.esc_attr($attrName).'="'.esc_attr($attrValue).'" ';
			}
		}

		return $attrString;
	}

	public static function getReferalUrl()
	{
		$url = self::wpGetRawReferer();
		$url = self::filterUrl($url);

		return $url;
	}

	public static function wpGetRawReferer()
	{
		if (!empty($_REQUEST['_wp_http_referer'])) {
			return wp_unslash(sanitize_text_field($_REQUEST['_wp_http_referer']));
		}
		else if (!empty($_SERVER['HTTP_REFERER'])) {
			return wp_unslash(sanitize_text_field($_SERVER['HTTP_REFERER']));
		}
		else if (function_exists('wp_get_raw_referer')) {
			$url = wp_get_raw_referer();
			if (!empty($url)) {
				return $url;
			}
		}

		return false;
	}

	public static function filterUrl($url = '')
	{
		if ($url != '') {
			$url = str_replace('www.', '', $url);
			$count = (int)strlen($url);
			$index = $count - 1;
			if (isset($url[$index]) && $url[$index] == '/') {
				$url = substr_replace($url, '', $index, 1);
			}
		}

		return $url;
	}

    public static function getDefaultTimezone()
    {
        $timezone = get_option('timezone_string');
        if (!$timezone) {
            $timezone = 'America/New_York';
        }

        return $timezone;
    }

	public static function getAllowedTags() {
		$generalArray = array(
			'type'  => array(),
			'id'    => array(),
			'name'  => array(),
			'value' => array(),
			'class' => array(),
			'data-options' => array(),
			'data-settings' => array(),
			'data-condition-id' => array(),
			'data-child-class' => array(),
			'data-id' => array(),
			'data-ajaxnonce' => array(),
			'onclick' => array(),
			'data-*' => true,
			'checked' => true,
			'disabled' => true,
			'selected' => true,
			'href' => true,
			'target' => true,
			'src' => true,
			'border' => true,
			'alt' => true,
			'width' => true,
			'height' => true,
			'colspan' => true,
			'for' => true,
			'multiple' => true,
			'style' => true,
			'placeholder' => true,
			'i' => true,
		);

		$allowed_html = array(
			'div' => $generalArray,
			'p' => $generalArray,
			'h1' => $generalArray,
			'h2' => $generalArray,
			'h3' => $generalArray,
			'h4' => $generalArray,
			'ul' => $generalArray,
			'li' => $generalArray,
			'button' => $generalArray,
			'b' => $generalArray,
			'br' => $generalArray,
			'style' => $generalArray,
			'script' => $generalArray,
			'meta' => $generalArray,
			'link' => $generalArray,
			'form' => $generalArray,
			'textarea' => $generalArray,
			'input' => $generalArray,
			'img' => $generalArray,
			'span' => $generalArray,
			'i' => $generalArray,
			'canvas' => $generalArray,
			'label' => array(
				'id' => array(),
				'class' => array(),
				'style' => array(),
				'for' => array(),
				'data-*' => true
			),
			'select' => array(
				'option' => array('value', 'selected'),
				'name' => array(),
				'class' => array(),
				'js-circle-time-zone' => array(),
				'style' => array(),
				'multiple' => array(),
				'data-*' => true,
				'data-id' => array(),
				'optgroup' => array('label'),
			),
			'optgroup' => array(
				'label' => array(),
				'data-*' => true
			),
			'option' => array(
				'value' => array(),
				'selected' => array(),
				'data-*' => true
			),
			'a' => array(
				'href' => array(),
				'target' => array(),
				'class' => array(),
				'style' => array(),
				'data-*' => true
			)
		);

		return $allowed_html;
	}

	public static function buildCreatePopupAttrs($type) {
		$attrStr = '';
		$isAvailable = $type->isAvailable();

		if (!$isAvailable) {
			$args = array(
				'target' => '_blank'
			);
			$attrStr = self::createAttrs($args);
		}

		return $attrStr;
	}

	public static function getPopupThumbClass($type) {
		$isAvailable = $type->isAvailable();
		$name = $type->getType();

		$typeClassName = $name.'-popup';

		if (!$isAvailable) {
			$typeClassName = esc_attr($typeClassName) .' '.esc_attr($typeClassName).'-pro ypm-pro-version';
		}

		return $typeClassName;
	}

	public static function createAttrs($attrs)
	{
		$attrStr = '';

		if (empty($attrs)) {
			return $attrStr;
		}

		foreach ($attrs as $attrKey => $attrValue) {
			$attrStr .= esc_attr($attrKey).'="'.esc_attr($attrValue).'" ';
		}

		return $attrStr;
	}

	public static function createTypeURLByType($type) {
		return admin_url().'post-new.php?post_type='.YPM_POPUP_POST_TYPE.'&ypm_type='.esc_attr($type);
	}

	public static function buildCreatePopupUrl($type) {
		$isAvailable = $type->isAvailable();
		$name = $type->getType();

		$url = self::createTypeURLByType($name);

		if (!$isAvailable) {
			$url = YPM_POPUP_PRO_URL;
		}

		return $url;
	}

	public static function createTypePopupNotice($type, $id) {
		$link = self::createTypeURLByType($type).'&ypm_module_id='.($id);
		$content = '<div class="alert alert-info" role="alert">
			You can create a popup with this link <a href="'.esc_attr($link).'" class="alert-link">Create Popup</a>
		</div>';

		return $content;
	}

	public static function getFormattedDate($date) {
		$date = strtotime($date);
		$month = date('F', $date);
		$year = date('Y', $date);

		return $month.' '.esc_attr($year);
	}

	public static function getGamificationSettingsTabConfig()
	{
		$settings = array();
		$settings['contents'] = esc_attr__('Content', 'popup_master');
		$settings['design'] = esc_attr__('Design', 'popup_master');
		$settings['options'] = esc_attr__('Options', 'popup_master');

		return apply_filters('ypmNotificationTabs', $settings);
	}

	public static function getTyneMceArgs()
	{
		$args = array(
			'wpautop' => false,
			'tinymce' => array(
				'width' => '100%'
			),
			'textarea_rows' => '3',
			'media_buttons' => true
		);

		return apply_filters('ypmGamificationTyneMceArgs', $args);
	}

	public static function getImageNameFromSavedData($savedImage)
	{
		$explodedData = explode('img/', $savedImage);
		$imageName = $explodedData[1];
		$imageName = str_replace('.png', '', $imageName) ;

		return $imageName;
	}

	public static function renderGiftIcons($selectedIcon = '')
	{
		$isAvailable = function ($index) {
			return (ypm_is_free() && $index != 1);
		};
		ob_start();
		?>
		<div class="ypm-gift-icons-wrapper">
			<?php
				$currentIndex = 1;
			?>
			<?php while ($currentIndex <= YPM_GAMIFICATION_IMAGES_COUNT): ?>
				<?php
					$currentActiveClassName = '';
					$freeClass = '';
					if ($isAvailable($currentIndex)) {
						$freeClass = 'ypm-pro-icon';
					}
				?>
				<?php if ('ypm-gift-icon-'.esc_attr($currentIndex) == $selectedIcon): ?>
					<?php $currentActiveClassName = 'ypm-active-gift'; ?>
				<?php endif;?>
				<div class="ypm-gift-icon ypm-gift-icon-<?php echo esc_attr($currentIndex); ?> <?php echo esc_attr($currentActiveClassName); echo esc_attr($freeClass);?>" data-image-name="ypm-gift-icon-<?php echo esc_attr($currentIndex); ?>.png" data-is-free="<?php echo esc_attr($isAvailable($currentIndex))?>">
					<?php if($isAvailable($currentIndex)): ?>
						<span class="gamification-pro-icon"><?php esc_attr_e('PRO')?></span>
					<?php endif;?>
				</div>
				<?php ++$currentIndex; ?>
			<?php endwhile; ?>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public static function winningChance()
	{
		$chance = array(
			0 => '0%',
			10 => '10%',
			20 => '20%',
			30 => '30%',
			40 => '40%',
			50 => '50%',
			60 => '60%',
			70 => '70%',
			80 => '80%',
			90 => '90%',
			100 => '100%'
		);

		return apply_filters('ypmGamificationWinChance', $chance);
	}

	public static function proSpan() {
		return '<span class="ypm-pro-span"> (PRO) </span>';
	}

	public static function getCSSSafeSize($dimension)
	{
		if (empty($dimension)) {
			return 'inherit';
		}

		$size = (int)$dimension.'px';
		// If user write dimension in px or % we give that dimension to target otherwise the default value will be px
		if (strpos($dimension, '%') || strpos($dimension, 'px')) {
			$size = $dimension;
		}

		return $size;
	}

	public static function getTimeZones()
	{
		return array(
			"Pacific/Midway"=>"(GMT-11:00) Midway",
			"Pacific/Niue"=>"(GMT-11:00) Niue",
			"Pacific/Pago_Pago"=>"(GMT-11:00) Pago Pago",
			"Pacific/Honolulu"=>"(GMT-10:00) Hawaii Time",
			"Pacific/Rarotonga"=>"(GMT-10:00) Rarotonga",
			"Pacific/Tahiti"=>"(GMT-10:00) Tahiti",
			"Pacific/Marquesas"=>"(GMT-09:30) Marquesas",
			"America/Anchorage"=>"(GMT-09:00) Alaska Time",
			"Pacific/Gambier"=>"(GMT-09:00) Gambier",
			"America/Los_Angeles"=>"(GMT-08:00) Pacific Time",
			"America/Tijuana"=>"(GMT-08:00) Pacific Time - Tijuana",
			"America/Vancouver"=>"(GMT-08:00) Pacific Time - Vancouver",
			"America/Whitehorse"=>"(GMT-08:00) Pacific Time - Whitehorse",
			"Pacific/Pitcairn"=>"(GMT-08:00) Pitcairn",
			"America/Dawson_Creek"=>"(GMT-07:00) Mountain Time - Dawson Creek",
			"America/Denver"=>"(GMT-07:00) Mountain Time",
			"America/Edmonton"=>"(GMT-07:00) Mountain Time - Edmonton",
			"America/Hermosillo"=>"(GMT-07:00) Mountain Time - Hermosillo",
			"America/Mazatlan"=>"(GMT-07:00) Mountain Time - Chihuahua, Mazatlan",
			"America/Phoenix"=>"(GMT-07:00) Mountain Time - Arizona",
			"America/Yellowknife"=>"(GMT-07:00) Mountain Time - Yellowknife",
			"America/Belize"=>"(GMT-06:00) Belize",
			"America/Chicago"=>"(GMT-06:00) Central Time",
			"America/Costa_Rica"=>"(GMT-06:00) Costa Rica",
			"America/El_Salvador"=>"(GMT-06:00) El Salvador",
			"America/Guatemala"=>"(GMT-06:00) Guatemala",
			"America/Managua"=>"(GMT-06:00) Managua",
			"America/Mexico_City"=>"(GMT-06:00) Central Time - Mexico City",
			"America/Regina"=>"(GMT-06:00) Central Time - Regina",
			"America/Tegucigalpa"=>"(GMT-06:00) Central Time - Tegucigalpa",
			"America/Winnipeg"=>"(GMT-06:00) Central Time - Winnipeg",
			"Pacific/Easter"=>"(GMT-06:00) Easter Island",
			"Pacific/Galapagos"=>"(GMT-06:00) Galapagos",
			"America/Bogota"=>"(GMT-05:00) Bogota",
			"America/Cayman"=>"(GMT-05:00) Cayman",
			"America/Guayaquil"=>"(GMT-05:00) Guayaquil",
			"America/Havana"=>"(GMT-05:00) Havana",
			"America/Iqaluit"=>"(GMT-05:00) Eastern Time - Iqaluit",
			"America/Jamaica"=>"(GMT-05:00) Jamaica",
			"America/Lima"=>"(GMT-05:00) Lima",
			"America/Montreal"=>"(GMT-05:00) Eastern Time - Montreal",
			"America/Nassau"=>"(GMT-05:00) Nassau",
			"America/New_York"=>"(GMT-05:00) Eastern Time",
			"America/Panama"=>"(GMT-05:00) Panama",
			"America/Port-au-Prince"=>"(GMT-05:00) Port-au-Prince",
			"America/Rio_Branco"=>"(GMT-05:00) Rio Branco",
			"America/Toronto"=>"(GMT-05:00) Eastern Time - Toronto",
			"America/Caracas"=>"(GMT-04:30) Caracas",
			"America/Antigua"=>"(GMT-04:00) Antigua",
			"America/Asuncion"=>"(GMT-04:00) Asuncion",
			"America/Barbados"=>"(GMT-04:00) Barbados",
			"America/Boa_Vista"=>"(GMT-04:00) Boa Vista",
			"America/Campo_Grande"=>"(GMT-04:00) Campo Grande",
			"America/Cuiaba"=>"(GMT-04:00) Cuiaba",
			"America/Curacao"=>"(GMT-04:00) Curacao",
			"America/Grand_Turk"=>"(GMT-04:00) Grand Turk",
			"America/Guyana"=>"(GMT-04:00) Guyana",
			"America/Halifax"=>"(GMT-04:00) Atlantic Time - Halifax",
			"America/La_Paz"=>"(GMT-04:00) La Paz",
			"America/Manaus"=>"(GMT-04:00) Manaus",
			"America/Martinique"=>"(GMT-04:00) Martinique",
			"America/Port_of_Spain"=>"(GMT-04:00) Port of Spain",
			"America/Porto_Velho"=>"(GMT-04:00) Porto Velho",
			"America/Puerto_Rico"=>"(GMT-04:00) Puerto Rico",
			"America/Santiago"=>"(GMT-04:00) Santiago",
			"America/Santo_Domingo"=>"(GMT-04:00) Santo Domingo",
			"America/Thule"=>"(GMT-04:00) Thule",
			"Antarctica/Palmer"=>"(GMT-04:00) Palmer",
			"Atlantic/Bermuda"=>"(GMT-04:00) Bermuda",
			"America/St_Johns"=>"(GMT-03:30) Newfoundland Time - St. Johns",
			"America/Araguaina"=>"(GMT-03:00) Araguaina",
			"America/Argentina/Buenos_Aires"=>"(GMT-03:00) Buenos Aires",
			"America/Bahia"=>"(GMT-03:00) Salvador",
			"America/Belem"=>"(GMT-03:00) Belem",
			"America/Cayenne"=>"(GMT-03:00) Cayenne",
			"America/Fortaleza"=>"(GMT-03:00) Fortaleza",
			"America/Godthab"=>"(GMT-03:00) Godthab",
			"America/Maceio"=>"(GMT-03:00) Maceio",
			"America/Miquelon"=>"(GMT-03:00) Miquelon",
			"America/Montevideo"=>"(GMT-03:00) Montevideo",
			"America/Paramaribo"=>"(GMT-03:00) Paramaribo",
			"America/Recife"=>"(GMT-03:00) Recife",
			"America/Sao_Paulo"=>"(GMT-03:00) Sao Paulo",
			"Antarctica/Rothera"=>"(GMT-03:00) Rothera",
			"Atlantic/Stanley"=>"(GMT-03:00) Stanley",
			"America/Noronha"=>"(GMT-02:00) Noronha",
			"Atlantic/South_Georgia"=>"(GMT-02:00) South Georgia",
			"America/Scoresbysund"=>"(GMT-01:00) Scoresbysund",
			"Atlantic/Azores"=>"(GMT-01:00) Azores",
			"Atlantic/Cape_Verde"=>"(GMT-01:00) Cape Verde",
			"Africa/Abidjan"=>"(GMT+00:00) Abidjan",
			"Africa/Accra"=>"(GMT+00:00) Accra",
			"Africa/Bissau"=>"(GMT+00:00) Bissau",
			"Africa/Casablanca"=>"(GMT+00:00) Casablanca",
			"Africa/El_Aaiun"=>"(GMT+00:00) El Aaiun",
			"Africa/Monrovia"=>"(GMT+00:00) Monrovia",
			"America/Danmarkshavn"=>"(GMT+00:00) Danmarkshavn",
			"Atlantic/Canary"=>"(GMT+00:00) Canary Islands",
			"Atlantic/Faroe"=>"(GMT+00:00) Faeroe",
			"Atlantic/Reykjavik"=>"(GMT+00:00) Reykjavik",
			"Etc/GMT"=>"(GMT+00:00) GMT (no daylight saving)",
			"Europe/Dublin"=>"(GMT+00:00) Dublin",
			"Europe/Lisbon"=>"(GMT+00:00) Lisbon",
			"Europe/London"=>"(GMT+00:00) London",
			"Africa/Algiers"=>"(GMT+01:00) Algiers",
			"Africa/Ceuta"=>"(GMT+01:00) Ceuta",
			"Africa/Lagos"=>"(GMT+01:00) Lagos",
			"Africa/Ndjamena"=>"(GMT+01:00) Ndjamena",
			"Africa/Tunis"=>"(GMT+01:00) Tunis",
			"Africa/Windhoek"=>"(GMT+01:00) Windhoek",
			"Europe/Amsterdam"=>"(GMT+01:00) Amsterdam",
			"Europe/Andorra"=>"(GMT+01:00) Andorra",
			"Europe/Belgrade"=>"(GMT+01:00) Central European Time - Belgrade",
			"Europe/Berlin"=>"(GMT+01:00) Berlin",
			"Europe/Brussels"=>"(GMT+01:00) Brussels",
			"Europe/Budapest"=>"(GMT+01:00) Budapest",
			"Europe/Copenhagen"=>"(GMT+01:00) Copenhagen",
			"Europe/Gibraltar"=>"(GMT+01:00) Gibraltar",
			"Europe/Luxembourg"=>"(GMT+01:00) Luxembourg",
			"Europe/Madrid"=>"(GMT+01:00) Madrid",
			"Europe/Malta"=>"(GMT+01:00) Malta",
			"Europe/Monaco"=>"(GMT+01:00) Monaco",
			"Europe/Oslo"=>"(GMT+01:00) Oslo",
			"Europe/Paris"=>"(GMT+01:00) Paris",
			"Europe/Prague"=>"(GMT+01:00) Central European Time - Prague",
			"Europe/Rome"=>"(GMT+01:00) Rome",
			"Europe/Stockholm"=>"(GMT+01:00) Stockholm",
			"Europe/Tirane"=>"(GMT+01:00) Tirane",
			"Europe/Vienna"=>"(GMT+01:00) Vienna",
			"Europe/Warsaw"=>"(GMT+01:00) Warsaw",
			"Europe/Zurich"=>"(GMT+01:00) Zurich",
			"Africa/Cairo"=>"(GMT+02:00) Cairo",
			"Africa/Johannesburg"=>"(GMT+02:00) Johannesburg",
			"Africa/Maputo"=>"(GMT+02:00) Maputo",
			"Africa/Tripoli"=>"(GMT+02:00) Tripoli",
			"Asia/Amman"=>"(GMT+02:00) Amman",
			"Asia/Beirut"=>"(GMT+02:00) Beirut",
			"Asia/Damascus"=>"(GMT+02:00) Damascus",
			"Asia/Gaza"=>"(GMT+02:00) Gaza",
			"Asia/Jerusalem"=>"(GMT+02:00) Jerusalem",
			"Asia/Nicosia"=>"(GMT+02:00) Nicosia",
			"Europe/Athens"=>"(GMT+02:00) Athens",
			"Europe/Bucharest"=>"(GMT+02:00) Bucharest",
			"Europe/Chisinau"=>"(GMT+02:00) Chisinau",
			"Europe/Helsinki"=>"(GMT+02:00) Helsinki",
			"Europe/Istanbul"=>"(GMT+02:00) Istanbul",
			"Europe/Kaliningrad"=>"(GMT+02:00) Moscow-01 - Kaliningrad",
			"Europe/Kiev"=>"(GMT+02:00) Kiev",
			"Europe/Riga"=>"(GMT+02:00) Riga",
			"Europe/Sofia"=>"(GMT+02:00) Sofia",
			"Europe/Tallinn"=>"(GMT+02:00) Tallinn",
			"Europe/Vilnius"=>"(GMT+02:00) Vilnius",
			"Africa/Addis_Ababa"=>"(GMT+03:00) Addis Ababa",
			"Africa/Asmara"=>"(GMT+03:00) Asmera",
			"Africa/Dar_es_Salaam"=>"(GMT+03:00) Dar es Salaam",
			"Africa/Djibouti"=>"(GMT+03:00) Djibouti",
			"Africa/Kampala"=>"(GMT+03:00) Kampala",
			"Africa/Khartoum"=>"(GMT+03:00) Khartoum",
			"Africa/Mogadishu"=>"(GMT+03:00) Mogadishu",
			"Africa/Nairobi"=>"(GMT+03:00) Nairobi",
			"Antarctica/Syowa"=>"(GMT+03:00) Syowa",
			"Asia/Aden"=>"(GMT+03:00) Aden",
			"Asia/Baghdad"=>"(GMT+03:00) Baghdad",
			"Asia/Bahrain"=>"(GMT+03:00) Bahrain",
			"Asia/Kuwait"=>"(GMT+03:00) Kuwait",
			"Asia/Qatar"=>"(GMT+03:00) Qatar",
			"Asia/Riyadh"=>"(GMT+03:00) Riyadh",
			"Europe/Minsk"=>"(GMT+03:00) Minsk",
			"Europe/Moscow"=>"(GMT+03:00) Moscow+00",
			"Indian/Antananarivo"=>"(GMT+03:00) Antananarivo",
			"Indian/Comoro"=>"(GMT+03:00) Comoro",
			"Indian/Mayotte"=>"(GMT+03:00) Mayotte",
			"Asia/Tehran"=>"(GMT+03:30) Tehran",
			"Asia/Baku"=>"(GMT+04:00) Baku",
			"Asia/Dubai"=>"(GMT+04:00) Dubai",
			"Asia/Muscat"=>"(GMT+04:00) Muscat",
			"Asia/Tbilisi"=>"(GMT+04:00) Tbilisi",
			"Asia/Yerevan"=>"(GMT+04:00) Yerevan",
			"Europe/Samara"=>"(GMT+04:00) Moscow+00 - Samara",
			"Indian/Mahe"=>"(GMT+04:00) Mahe",
			"Indian/Mauritius"=>"(GMT+04:00) Mauritius",
			"Indian/Reunion"=>"(GMT+04:00) Reunion",
			"Asia/Kabul"=>"(GMT+04:30) Kabul",
			"Antarctica/Mawson"=>"(GMT+05:00) Mawson",
			"Asia/Aqtau"=>"(GMT+05:00) Aqtau",
			"Asia/Aqtobe"=>"(GMT+05:00) Aqtobe",
			"Asia/Ashgabat"=>"(GMT+05:00) Ashgabat",
			"Asia/Dushanbe"=>"(GMT+05:00) Dushanbe",
			"Asia/Karachi"=>"(GMT+05:00) Karachi",
			"Asia/Tashkent"=>"(GMT+05:00) Tashkent",
			"Asia/Yekaterinburg"=>"(GMT+05:00) Moscow+02 - Yekaterinburg",
			"Indian/Kerguelen"=>"(GMT+05:00) Kerguelen",
			"Indian/Maldives"=>"(GMT+05:00) Maldives",
			"Asia/Calcutta"=>"(GMT+05:30) India Standard Time",
			"Asia/Colombo"=>"(GMT+05:30) Colombo",
			"Asia/Katmandu"=>"(GMT+05:45) Katmandu",
			"Antarctica/Vostok"=>"(GMT+06:00) Vostok",
			"Asia/Almaty"=>"(GMT+06:00) Almaty",
			"Asia/Bishkek"=>"(GMT+06:00) Bishkek",
			"Asia/Dhaka"=>"(GMT+06:00) Dhaka",
			"Asia/Omsk"=>"(GMT+06:00) Moscow+03 - Omsk, Novosibirsk",
			"Asia/Thimphu"=>"(GMT+06:00) Thimphu",
			"Indian/Chagos"=>"(GMT+06:00) Chagos",
			"Asia/Rangoon"=>"(GMT+06:30) Rangoon",
			"Indian/Cocos"=>"(GMT+06:30) Cocos",
			"Antarctica/Davis"=>"(GMT+07:00) Davis",
			"Asia/Bangkok"=>"(GMT+07:00) Bangkok",
			"Asia/Hovd"=>"(GMT+07:00) Hovd",
			"Asia/Jakarta"=>"(GMT+07:00) Jakarta",
			"Asia/Krasnoyarsk"=>"(GMT+07:00) Moscow+04 - Krasnoyarsk",
			"Asia/Saigon"=>"(GMT+07:00) Hanoi",
			"Indian/Christmas"=>"(GMT+07:00) Christmas",
			"Antarctica/Casey"=>"(GMT+08:00) Casey",
			"Asia/Brunei"=>"(GMT+08:00) Brunei",
			"Asia/Choibalsan"=>"(GMT+08:00) Choibalsan",
			"Asia/Hong_Kong"=>"(GMT+08:00) Hong Kong",
			"Asia/Irkutsk"=>"(GMT+08:00) Moscow+05 - Irkutsk",
			"Asia/Kuala_Lumpur"=>"(GMT+08:00) Kuala Lumpur",
			"Asia/Macau"=>"(GMT+08:00) Macau",
			"Asia/Makassar"=>"(GMT+08:00) Makassar",
			"Asia/Manila"=>"(GMT+08:00) Manila",
			"Asia/Shanghai"=>"(GMT+08:00) China Time - Beijing",
			"Asia/Singapore"=>"(GMT+08:00) Singapore",
			"Asia/Taipei"=>"(GMT+08:00) Taipei",
			"Asia/Ulaanbaatar"=>"(GMT+08:00) Ulaanbaatar",
			"Australia/Perth"=>"(GMT+08:00) Western Time - Perth",
			"Asia/Dili"=>"(GMT+09:00) Dili",
			"Asia/Jayapura"=>"(GMT+09:00) Jayapura",
			"Asia/Pyongyang"=>"(GMT+09:00) Pyongyang",
			"Asia/Seoul"=>"(GMT+09:00) Seoul",
			"Asia/Tokyo"=>"(GMT+09:00) Tokyo",
			"Asia/Yakutsk"=>"(GMT+09:00) Moscow+06 - Yakutsk",
			"Pacific/Palau"=>"(GMT+09:00) Palau",
			"Australia/Adelaide"=>"(GMT+09:30) Central Time - Adelaide",
			"Australia/Darwin"=>"(GMT+09:30) Central Time - Darwin",
			"Antarctica/DumontDUrville"=>"(GMT+10:00) Dumont D'Urville",
			"Asia/Magadan"=>"(GMT+10:00) Moscow+08 - Magadan",
			"Asia/Vladivostok"=>"(GMT+10:00) Moscow+07 - Yuzhno-Sakhalinsk",
			"Australia/Brisbane"=>"(GMT+10:00) Eastern Time - Brisbane",
			"Australia/Hobart"=>"(GMT+10:00) Eastern Time - Hobart",
			"Australia/Sydney"=>"(GMT+10:00) Eastern Time - Melbourne, Sydney",
			"Pacific/Chuuk"=>"(GMT+10:00) Truk",
			"Pacific/Guam"=>"(GMT+10:00) Guam",
			"Pacific/Port_Moresby"=>"(GMT+10:00) Port Moresby",
			"Pacific/Saipan"=>"(GMT+10:00) Saipan",
			"Pacific/Efate"=>"(GMT+11:00) Efate",
			"Pacific/Guadalcanal"=>"(GMT+11:00) Guadalcanal",
			"Pacific/Kosrae"=>"(GMT+11:00) Kosrae",
			"Pacific/Noumea"=>"(GMT+11:00) Noumea",
			"Pacific/Pohnpei"=>"(GMT+11:00) Ponape",
			"Pacific/Norfolk"=>"(GMT+11:30) Norfolk",
			"Asia/Kamchatka"=>"(GMT+12:00) Moscow+08 - Petropavlovsk-Kamchatskiy",
			"Pacific/Auckland"=>"(GMT+12:00) Auckland",
			"Pacific/Fiji"=>"(GMT+12:00) Fiji",
			"Pacific/Funafuti"=>"(GMT+12:00) Funafuti",
			"Pacific/Kwajalein"=>"(GMT+12:00) Kwajalein",
			"Pacific/Majuro"=>"(GMT+12:00) Majuro",
			"Pacific/Nauru"=>"(GMT+12:00) Nauru",
			"Pacific/Tarawa"=>"(GMT+12:00) Tarawa",
			"Pacific/Wake"=>"(GMT+12:00) Wake",
			"Pacific/Wallis"=>"(GMT+12:00) Wallis",
			"Pacific/Apia"=>"(GMT+13:00) Apia",
			"Pacific/Enderbury"=>"(GMT+13:00) Enderbury",
			"Pacific/Fakaofo"=>"(GMT+13:00) Fakaofo",
			"Pacific/Tongatapu"=>"(GMT+13:00) Tongatapu",
			"Pacific/Kiritimati"=>"(GMT+14:00) Kiritimati"
		);
	}
}

function ypm_is_free() {
	return (YPM_POPUP_PKG == YPM_POPUP_FREE);
}

function ypm_info($message) {
	$content = '<div class="ypm-tooltip"><span class="dashicons dashicons-editor-help ypm-info-dashicon"></span>';
		$content.= '<span class="ypm-tooltiptext">'.esc_attr($message).'</span>';
	$content.= '</div>';

	return $content;
}

function YpmUpgradeText($text) {
    ob_start();
    ?>
    <h3><?php echo esc_attr($text)?></h3>
    <?php echo YpmUpgradeButton(); ?>
    <?php
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

function YpmUpgradeButton() {
	ob_start();
	?>
	<div class="ypm-events-button-wrapper">
        <a href="<?php echo YPM_POPUP_PRO_URL?>" target="_blank">
            <button type="button" class="ypm-upgrade-button-red ypm-metabox-upgrade-button">
                <b class="h4">Upgrade Now</b>
            </button>
        </a>
    </div>
	<?php
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

function ypmTypeNow() {
	if ( ! empty( $GLOBALS['typenow'] ) ) {
		return $GLOBALS['typenow'];
	}

	// when editing pages, $typenow isn't set until later!
	// try to pick it up from the query string
	if ( ! empty( $_GET['post_type'] ) ) {
		return sanitize_text_field( $_GET['post_type'] );
	} elseif ( ! empty( $_GET['post'] ) && $_GET['post'] > 0 ) {
		$post = get_post( (int)sanitize_text_field($_GET['post']) );
	} elseif ( ! empty( $_POST['post_ID'] ) && $_POST['post_ID'] > 0 ) {
		$post = get_post( (int)sanitize_text_field($_POST['post_ID']) );
	}

	return isset( $post ) && is_object( $post ) && $post->ID > 0 ? $post->post_type : false;
}

function ypm_recursive_sanitize_text_fields($data) {
	if (is_array($data)) {
		return array_map('ypm_recursive_sanitize_text_fields', $data);
	} elseif (is_string($data)) {
		return wp_kses($data, YpmAdminHelper::getAllowedTags());
	} else {
		return $data;
	}
}

function ypmGetIpAddress() {

	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}