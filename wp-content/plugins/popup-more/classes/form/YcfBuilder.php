<?php
namespace YpmPopup;

require_once(YPM_POPUP_CLASSES.'form/YcfForm.php');

class YcfBuilder extends YcfForm {

	public function createFormAdminElement() {

		$formElements = $this->getFormElementsData();

		$content = '';
		$oderKeys = implode(',',array_keys($formElements));

		foreach($formElements as $key => $formElement) {
			$args['oderId'] = $key;
			$content .= YcfFunctions::createAdminViewHtml($formElement, $args);
		}
		$content .= '<input type="hidden" name="contact-fields-order" class="form-element-ordering" value="'.esc_attr($oderKeys).'">';
		
		return $content;
	}

	public function getFormDefaultConfig() {

		return array();
	}

	public function getFormFields() {

		$formId = $this->getFormId();
		$formData = $this->getFormElementsData();
		$args = array();

		$contactForm = '';

		foreach ($formData as $index => $formInfo) {

			$attrs = array();

			if(!empty($formInfo['attrs'])) {
				$attrs = $formInfo['attrs'];
			}
			$attrStr = $this->createAttrStr($attrs);
			$args['attrs'] = $attrStr;

			switch($formInfo['type']) {

				case 'text':
				case 'email':
				case 'number':
				case 'url':
					$contactForm .= $this->createSimpleInput($formInfo, $index, $args);
					break;
				case 'textarea':
					$contactForm .= $this->createTextareaElement($formInfo, $index, $args);
					break;
				case 'select':
					$contactForm .= $this->createSelectBox($formInfo, $index, $args);
					break;
				case 'gdpr':
					$contactForm .= $this->createGdpr($formInfo, $index, $args);
					break;
				case 'submit':
					$contactForm .= $this->createSubmitButton($formInfo, $index, $args);
					break;
				case 'ycfm-text':
				case 'ycfm-email':
				case 'ycfm-number':
				case 'ycfm-url':
					$formInfo['type'] = str_replace('ycfm-', '', $formInfo['type']);
					$contactForm .= $this->createMailchimpSimpleInput($formInfo, $index, $args);
					break;
				case 'ycfm-dropdown':
					$contactForm .= $this->createMailchimpSelectBox($formInfo, $index, $args);
					break;
				case 'ycfm-radio':
					$contactForm .= $this->createMailchimpRadioButtons($formInfo, $index, $args);
					break;
				case 'ycfm-date':
				case 'ycfm-birthday':
					$contactForm .= $this->createMailchimpDatesFields($formInfo, $index, $args);
					break;
				case 'ycfm-phone':
					$contactForm .= $this->createMailchimpPhoneField($formInfo, $index, $args);
					break;
				case 'ycfm-submit':
					$formInfo['type'] = str_replace('ycfm-', '', $formInfo['type']);
					$contactForm .= $this->createSubmitButton($formInfo, $index, $args);
					break;
			}
		}

		return $contactForm;
	}

	public function getOrderedDataFromOptions($formInfo) {

		$data = array();

		if(empty($formInfo['options'])) {
			return $data;
		}
		$options = $formInfo['options'];
		if(empty($options)) {
			$options = json_decode($formInfo['options'], true);
			if(empty($options)) {
				return $data;
			}
		}

		$fieldOptions = $options['fieldsOptions'];

		$fieldsOrder = $options['fieldsOrder'];

		if(empty($fieldOptions) || empty($fieldsOrder)) {
			return $data;
		}

		foreach($fieldsOrder as $orderId) {
			foreach($fieldOptions as $field) {

				if($field['orderId'] == $orderId) {
					$value= $field['value'];
					$data[$value] = $field['label'];
				}
			}
		}

		return $data;
	}

	public function createGdpr($elementData, $index, $args) {
		$name = $elementData['name'];
		$isRequired = $this->isRequired($elementData);
		$asterisk = $this->getAsterisk($isRequired);
		$element = '<div class="ycf-form-element" ycf-data-order="'.esc_attr($index).'">
						<div class="ycf-form-label-wrapper1">
							<div>
								<input class="gdpr-checkbox" type="checkbox" data-type="gdpr" id='.esc_attr($elementData['name']).' name='.esc_attr($elementData['name']).' value='.esc_attr($elementData['value']).' >
								<label for='.esc_attr($elementData['name']).' class="ycf-form-label gdpr-label">'.esc_attr($elementData['label']).esc_attr($asterisk).'</label>
							</div>
							<div id="gdpr-error"></div>
						</div>
						<div class="ycf-form-element-wrapper">
							'.esc_attr($elementData['text']).'
						</div>
					</div>';

		return $element;
	}

	public function createSelectBox($elementData, $index, $args) {

		$name = $elementData['name'];

		$data = static::getOrderedDataFromOptions($elementData);
		if(empty($data)) {
			return '';
		}
		$isRequired = $this->isRequired($elementData);
		$asterisk = $this->getAsterisk($isRequired);

		$element = '<div class="ycf-form-element" ycf-data-order="'.esc_attr($index).'">
						<div class="ycf-form-label-wrapper">
							<span class="ycf-form-label">'.esc_attr($elementData['label']).esc_attr($asterisk).'</span>
						</div>
						<div class="ycf-form-element-wrapper">
							'. wp_kses($this->createSelectBoxElemnt($data, '', array('name'=> $name)), \YpmAdminHelper::getAllowedTags()) .'
						</div>
					</div>';

		return $element;
	}

	public function createMailchimpSelectBox($elementData, $index, $args) {

		$name = $elementData['name'];

		$data = static::getOrderedDataFromOptions($elementData);
		if(empty($data)) {
			return '';
		}
		$isRequired = $this->isRequired($elementData);
		$asterisk = $this->getAsterisk($isRequired);

		$element = '<div class="ycf-form-element" ycf-data-order="'.esc_attr($index).'">
						<div class="ycf-form-label-wrapper">
							<span class="ycf-form-label">'.esc_attr($elementData['label']).esc_attr($asterisk).'</span>
						</div>
						<div class="ycf-form-element-wrapper">
							'. wp_kses($this->createSelectBoxElemnt($data, '', array('name'=> $name, 'data-error-message-class' => $name.'-error-message')), \YpmAdminHelper::getAllowedTags()) .'
						</div>
						<div class="'.esc_attr($name).'-error-message ycf-validate-message"></div>
					</div>';

		return $element;
	}

	public function createMailchimpRadioButtons($elementData, $index, $args) {

		$name = $elementData['name'];

		$data = static::getOrderedDataFromOptions($elementData);
		if(empty($data)) {
			return '';
		}
		$isRequired = $this->isRequired($elementData);
		$asterisk = $this->getAsterisk($isRequired);

		$element = '<div class="ycf-form-element" ycf-data-order="'.esc_attr($index).'">
						<div class="ycf-form-label-wrapper">
							<span class="ycf-form-label">'.esc_attr($elementData['label']).esc_attr($asterisk).'</span>
						</div>
						<div class="ycf-form-element-wrapper">
							'. YcfFunctions::createRadioButtons($data, '', array('name'=> $name, 'data-error-message-class' => $name.'-error-message')) .'
						</div>
						<div class="'.esc_attr($name).'-error-message ycf-validate-message"></div>
					</div>';

		return $element;
	}

	public function createMailchimpPhoneField($elementData, $index, $args) {

		if(empty($elementData['options'])) {
			return '';
		}
		$options = json_decode($elementData['options'], true);
		if(empty($options) || empty($options['format'])) {
			return '';
		}
		$attrStr = $args['attrs'];
		$isRequired = $this->isRequired($elementData);
		$asterisk = $this->getAsterisk($isRequired);
		$name = $elementData['name'];
		ob_start();
		?>
		<div class="ycf-form-element" ycf-data-order="<?php echo esc_attr($index); ?>">
			<div class="ycf-form-label-wrapper">
				<span class="ycf-form-label"><?php echo esc_attr($elementData['label']).esc_attr($asterisk); ?></span>
			</div>
			<div class="ycf-form-element-wrapper">
				<?php if($options['format'] == 'US'): ?>
					<div class="phonefield phonefield-us">
						(<span class="phonearea">
							<input type="number" name="<?php echo esc_attr($name).'[area]' ?>" class="ycfm-phonePart" data-class-name="ycfm-phonePart" maxlength="3" size="3" value="<?php echo @esc_attr($elementData['value']); ?>" data-error-message-class="<?php echo esc_attr($name); ?>-error-message">
						</span>)
						<span class="phonedetail1">
							<input type="number" name="<?php echo esc_attr($name).'[detail1]'; ?>" class="ycfm-phonePart" maxlength="3" size="3" value="<?php echo @esc_attr($elementData['value']); ?>" data-error-message-class="<?php echo esc_attr($name); ?>-error-message">
						</span>
						-
						<span class="phonedetail2">
							<input type="number" name="<?php echo esc_attr($name).'[detail2]'; ?>" class="ycfm-phonePart" maxlength="4" size="4" value="<?php echo @esc_attr($elementData['value']); ?>" data-error-message-class="<?php echo esc_attr($name); ?>-error-message">
						</span>
					</div>
				<?php else: ?>
					<input type="number" <?php echo wp_kses($attrStr, \YpmAdminHelper::getAllowedTags()); ?> name="<?php echo esc_attr($name); ?>" value="<?php echo @esc_attr($elementData['value']); ?>" data-error-message-class="<?php echo esc_attr($name); ?>-error-message">
				<?php endif; ?>
			</div>
			<div class="<?php echo esc_attr($name); ?>-error-message ycf-validate-message"></div>
		</div>
		<?php
		$content = ob_get_contents();
		ob_get_clean();

		return $content;
	}

	public function createMailchimpDatesFields($elementData, $index, $args) {

		if(empty($elementData['options'])) {
			return '';
		}
		$options = json_decode($elementData['options'], true);
		if(empty($options)) {
			return '';
		}
		$name = $elementData['name'];
		$formats = explode('/', $options['format']);
		$isRequired = $this->isRequired($elementData);
		$asterisk = $this->getAsterisk($isRequired);
		if(empty($formats)) {
			return '';
		}
		ob_start();
		?>
		<div class="ycf-form-element" ycf-data-order="<?php echo esc_attr($index); ?>">
			<div class="ycf-form-label-wrapper">
				<span class="ycf-form-label"><?php echo esc_attr($elementData['label']).esc_attr($asterisk); ?></span>
			</div>
			<div class="ycf-form-element-wrapper">
				<?php foreach($formats as $format): ?>
					<?php if($format == 'DD'): ?>
						<div class="subfield dayfield ycf-subfield ycf-form-element-wrapper">
							<input class="datepart ycf-datepart ycf-input ycf-inputs-simple" type="text" pattern="[0-9]*"  value="" placeholder="DD" size="2" maxlength="2" data-error-message-class="<?php echo esc_attr($name); ?>-error-message" name="<?php echo esc_attr($name); ?>[day]" id="mce-<?php echo esc_attr($name); ?>-day">
						</div>
					<?php endif; ?>
					<?php if($format == 'MM'): ?>
						<div class="subfield dayfield ycf-subfield ycf-form-element-wrapper">
							<input class="datepart ycf-datepart ycf-input ycf-inputs-simple" data-class-name="ycf-datepart" type="text" pattern="[0-9]*"  value="" placeholder="MM" size="2" maxlength="2" data-error-message-class="<?php echo esc_attr($name); ?>-error-message" name="<?php echo esc_attr($name); ?>[month]" id="mce-<?php echo esc_attr($name); ?>-month">
						</div>
					<?php endif; ?>
					<?php if($format == 'YYYY'): ?>
						<div class="subfield dayfield ycf-subfield ycf-form-element-wrapper">
							<input class="datepart ycf-datepart ycf-input ycf-inputs-simple" type="text" pattern="[0-9]*"  value="" placeholder="YYYY" size="2" maxlength="4" data-error-message-class="<?php echo esc_attr($name); ?>-error-message" name="<?php echo esc_attr($name); ?>[year]" id="mce-<?php echo esc_attr($name); ?>-year">
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				<div class="<?php echo esc_attr($name); ?>-error-message ycf-validate-message"></div>
			</div>
		</div>
		<?php
		$content = ob_get_contents();
		ob_get_clean();

		return $content;
	}

	public function createSimpleInput($elementData, $index, $args) {

		$attrStr = $args['attrs'];
		$isRequired = $this->isRequired($elementData);
		$asterisk = $this->getAsterisk($isRequired);

		ob_start();
		?>
		<div class="ycf-form-element" ycf-data-order="<?php echo esc_attr($index); ?>">
			<?php if (!empty($elementData['label']) && empty($elementData['settings']['disableLabel'])): ?>
				<div class="ycf-form-label-wrapper">
					<span class="ycf-form-label"><?php echo esc_attr($elementData['label']).esc_attr($asterisk); ?></span>
				</div>
			<?php endif;?>
			<div class="ycf-form-element-wrapper">
				<input type="<?php echo esc_attr($elementData['type']); ?>" <?php echo esc_attr($attrStr); ?> name="<?php echo esc_attr($elementData['name']); ?>" value="<?php echo @esc_attr($elementData['value']); ?>" placeholder="<?php echo (!empty($elementData['placeholder'])) ?  esc_attr($elementData['placeholder']): ''; ?>">
			</div>
		</div>
		<?php
		$element = ob_get_contents();
		ob_get_clean();

		return $element;
	}

	public function createMailchimpSimpleInput($elementData, $index, $args) {

		$attrStr = $args['attrs'];
		$isRequired = $this->isRequired($elementData);
		$asterisk = $this->getAsterisk($isRequired);
		$name = $elementData['name'];
		ob_start();
		?>
		<div class="ycf-form-element" ycf-data-order="<?php echo esc_attr($index); ?>">
			<div class="ycf-form-label-wrapper">
				<span class="ycf-form-label"><?php echo esc_attr($elementData['label']).esc_attr($asterisk); ?></span>
			</div>
			<div class="ycf-form-element-wrapper">
				<input type="<?php echo esc_attr($elementData['type']); ?>" <?php echo esc_attr($attrStr); ?> name="<?php echo esc_attr($name); ?>" value="<?php echo @esc_attr($elementData['value']); ?>" data-error-message-class="<?php echo esc_attr($name); ?>-error-message">
			</div>
			<div class="<?php echo esc_attr($name); ?>-error-message ycf-validate-message"></div>
		</div>
		<?php
		$element = ob_get_contents();
		ob_get_clean();

		return $element;
	}

	public function createTextareaElement($elementData, $index, $args) {

		$isRequired = $this->isRequired($elementData);
		$asterisk = $this->getAsterisk($isRequired);
		ob_start();
		?>
		<div class="ycf-form-element"  ycf-data-order="<?php echo esc_attr($index); ?>">
			<div class="ycf-form-label-wrapper">
				<span class="ycf-form-label"><?php echo esc_attr($elementData['label']).esc_attr($asterisk); ?></span>
			</div>
			<div class="ycf-form-element-wrapper">
				<textarea name="<?php echo esc_attr($elementData['name']); ?>" <?php echo esc_attr($args['attrs']); ?>></textarea>
			</div>
		</div>
		<?php
		$element = ob_get_contents();
		ob_get_clean();

		return $element;
	}

	public function createSubmitButton($elementData, $index, $args) {
		$attrs = rtrim($args['attrs'], '; ');
		ob_start();
		?>
		<div class="ycf-submit">
			<input type="<?php echo esc_attr($elementData['type']); ?>" <?php echo wp_kses($attrs, \YpmAdminHelper::getAllowedTags()); ?> value="<?php echo esc_attr($elementData['value']); ?>">
			<img src="<?php echo YPM_POPUP_IMAGES_URL; ?>wpAjax.gif" class="ycf-hide ypm-spinner">
		</div>
		<?php
		$element = ob_get_contents();
		ob_get_clean();

		return $element;
	}

	public function createSelectBoxElemnt($data, $selectedValue, $attrs) {

		$selected = '';
		$attrString = self::createAttrs($attrs);

		$selectBox = '<select '.wp_kses($attrString, \YpmAdminHelper::getAllowedTags()).'>';

		foreach($data as $value => $label) {

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

			$selectBox .= '<option value="'.esc_attr($value).'" '.esc_attr($selected).'>'.esc_attr($label).'</option>';
			$selected = '';
		}

		$selectBox .= '</select>';

		return $selectBox;
	}

	private function createAttrStr($attrs) {

		$attrsStr = '';
		foreach ($attrs as $styleKey => $styleValue) {

			$attrsStr .= esc_attr($styleKey).'="'.esc_attr($styleValue).'"; ';
		}

		return $attrsStr;
	}
}