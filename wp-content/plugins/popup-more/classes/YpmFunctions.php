<?php
class YpmFunctions {
	public static function createRadioButtons($data, $savedValue, $attrs) {

		$attrString = '';
		$selected = '';

		if(!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.esc_attr($attrName).'="'.esc_attr($attrValue).'" ';
			}
		}

		$radioButtons = '';

		foreach($data as $value) {

			$checked = '';
			if($value == $savedValue) {
				$checked = 'checked';
			}

			$radioButtons .= "<input type=\"radio\" value=\"$value\" $attrString  $checked>";
		}
		return $radioButtons;
	}

	public static function createSelectBox($data, $selectedValue, $attrs) {
		$attrString = '';
		$selected = '';
		$selectBoxCloseTag = '</select>';

		if(!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.esc_attr($attrName).'="'.esc_attr($attrValue).'" ';
			}
		}

		$selectBox = '<select '.wp_kses($attrString, YpmAdminHelper::getAllowedTags()).'>';

		if(empty($data)) {
			$selectBox .= $selectBoxCloseTag;
			return $selectBox;
		}
		foreach ($data as $value => $label) {
			/*When is multiSelect*/
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

			if(is_array($label)) {
				$selectBox .= '<optgroup label="'.esc_attr($value).'">';
				foreach($label as $key => $optionLabel) {
					$selected = '';
					if(is_array($selectedValue)) {
						$isSelected = in_array($key, $selectedValue);
						if($isSelected) {
							$selected = 'selected';
						}
					}
					else if($selectedValue == $key) {
						$selected = 'selected';
					}
					else if(is_array($key) && in_array($selectedValue, $key)) {
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

		$selectBox .= $selectBoxCloseTag;

		return $selectBox;
	}
}