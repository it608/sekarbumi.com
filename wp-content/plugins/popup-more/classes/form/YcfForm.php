<?php
namespace YpmPopup;

abstract class YcfForm {

	private $tableName = 'ypm_contact_form_fields';
	private $formId;
	private $formElementsData;
	private $orderFieldName = 'ypm-contact-fields-order';

	public function __call($name, $args) {

		$methodPrefix = substr($name, 0, 3);
		$methodProperty = lcfirst(substr($name,3));

		if($methodPrefix=='get') {
			return $this->$methodProperty;
		}
		else if($methodPrefix=='set') {
			$this->$methodProperty = $args[0];
		}
	}

	public function __construct()
	{
		if (!defined('YPM_FORM_REQUIRED_MESSAGE')) {
			define('YPM_FORM_REQUIRED_MESSAGE', esc_attr__('This field is required.'));
		}
		if (!defined('YPM_FORM_INVALID_EMAIL')) {
			define('YPM_FORM_INVALID_EMAIL', esc_attr__('Please enter a valid email address.'));
		}
	}

	public function setTableName($tableName) {
		$this->tableName = $tableName;
	}

	public function getTableName() {
		return $this->tableName;
	}

	public function setOrderFieldName($orderFieldName) {
		$this->orderFieldName = $orderFieldName;
	}

	public function getOrderFieldName() {
		return $this->orderFieldName;
	}

	public function getFormOptionsData()
	{
		$formOptionsData = $this->getFormData();

		update_option('YcfPopupFormDraft', $formOptionsData);
		// Magic setter
		$this->setFormElementsData($formOptionsData);

		return $this->createFormAdminElement();
	}

	// get form Data from default or from saved
	public function getFormData()
	{
		$formId = $this->getFormId();

		if(empty($formId)) {
			$formOptionsData = $this->defaultFormObjectData();
		}
		else {
			$formOptionsData = $this->getFormList();
			//$formOptionsData = YcfForm::getFormListById($formId);
		}

		return $formOptionsData;
	}

	public function getFormList()
	{
		global $wpdb;
		$formId = (int)$this->formId;
		$formData = array();

		$findByIdQuery = $wpdb->prepare("SELECT fields_data FROM %i WHERE form_id = %d", $wpdb->prefix .esc_attr($this->tableName), $formId);
		$fieldsData = $wpdb->get_row($findByIdQuery, ARRAY_A);

		if(!isset($fieldsData)) {
			return $formData;
		}

		$formData = json_decode($fieldsData['fields_data'], true);

		return $formData;
	}

	public function getFormDefaultConfigByKey($key) {

		$keyArgs = array();
		$defaultConfig = $this->getFormDefaultConfig();

		if(!empty($defaultConfig[$key])) {
			$keyArgs = $defaultConfig[$key];
		}

		return $keyArgs;
	}

	public function getFormDefaultConfig() {

		$typesData = array();
		$randomId = $this->getRandomNumber();
		$typesData['firstName'] = array(
			'id' => $randomId,
			'fieldType' => 'firstName',
			'isFree' => 1,
			'type' => 'text',
			'name' => 'ycf-'.esc_attr($randomId),
			'label' => 'First Name',
			'placeholder' => 'First Name',
			'orderNumber' => 0,
			'value' => '',
			'options' => '',
			'settings' => array(
				'required' => '',
				'disableLabel' => ''
			),
			'attrs' => array(),
			'disableConfig' => array(
				'name' => true
			)
		);

		$typesData['lastName'] = array(
			'id' => $randomId,
			'fieldType' => 'lastName',
			'isFree' => 1,
			'type' => 'text',
			'name' => 'ycf-'.esc_attr($randomId),
			'label' => 'Last Name',
			'placeholder' => 'Last Name',
			'orderNumber' => 0,
			'value' => '',
			'options' => '',
			'settings' => array(
				'required' => '',
				'disableLabel' => ''
			),
			'attrs' => array(),
			'disableConfig' => array(
				'name' => true
			)
		);

		$randomId = $this->getRandomNumber();
		$typesData['text'] = array(
			'id' => $randomId,
			'fieldType' => 'text',
			'type' => 'text',
			'name' => 'ycf-'.esc_attr($randomId),
			'label' => 'Text',
			'placeholder' => 'Text',
			'orderNumber' => 0,
			'value' => '',
			'options' => '',
			'settings' => array(
				'required' => '',
				'disableLabel' => ''
			),
			'attrs' => array(),
			'disableConfig' => array(
				'name' => true
			)
		);

		$randomId = $this->getRandomNumber();
		$typesData['gdpr'] = array(
			'id' => $randomId,
			'fieldType' => 'gdpr',
			'isFree' => 1,
			'type' => 'gdpr',
			'name' => 'ycf-'.esc_attr($randomId),
			'label' => 'Accept Terms',
			'text' => 'admin will use the information you provide on this form to be in touch with you and to provide updates and marketing.',
			'orderNumber' => 0,
			'value' => '',
			'options' => '',
			'settings' => array(
				'required' => true
			),
			'attrs' => array(),
			'disableConfig' => array(
				'name' => true
			)
		);

		$randomId = $this->getRandomNumber();
		$typesData['number'] = array(
			'id' => $randomId,
			'fieldType' => 'number',
			'type' => 'number',
			'name' => 'ycf-'.esc_attr($randomId),
			'label' => 'Number',
			'placeholder' => 'Number',
			'orderNumber' => 0,
			'value' => '',
			'options' => '',
			'settings' => array(
				'required' => '',
				'disableLabel' => ''
			),
			'attrs' => array(),
			'disableConfig' => array(
				'name' => true
			)
		);

		$randomId = $this->getRandomNumber();
		$typesData['email'] = array(
			'id' => $randomId,
			'fieldType' => 'email',
			'isFree' => 1,
			'type' => 'email',
			'name' => 'ycf-'.esc_attr($randomId),
			'label' => 'Email Address',
			'placeholder' => 'Email Address',
			'orderNumber' => 0,
			'value' => '',
			'options' => '',
			'settings' => array(
				'required' => true,
				'disableLabel' => ''
			),
			'attrs' => array(),
			'disableConfig' => array(
				'name' => true
			)
		);

		$randomId = $this->getRandomNumber();
		$typesData['textarea'] = array(
			'id' => $randomId,
			'fieldType' => 'textarea',
			'type' => 'textarea',
			'name' => 'ycf-'.esc_attr($randomId),
			'label' => 'Message',
			'placeholder' => 'Message',
			'orderNumber' => 0,
			'value' => '',
			'options' => '',
			'settings' => array(
				'required' => '',
				'disableLabel' => ''
			),
			'attrs' => array(),
			'disableConfig' => array(
				'name' => true
			)
		);

		$randomId = $this->getRandomNumber();
		$formOptionValueData = $this->formOptionValueData();
		$options['fieldsOptions'] = array_values($formOptionValueData);
		$options['fieldsOrder'] = array_keys($formOptionValueData);
		$typesData['select'] = array(
			'id' => $randomId,
			'fieldType' => 'select',
			'type' => 'select',
			'name' => 'ycf-'.esc_attr($randomId),
			'label' => 'Select box',
			'orderNumber' => 0,
			'value' => '',
			'settings' => array(
				'required' => '',
				'disableLabel' => ''
			),
			'attrs' => array(),
			'disableConfig' => array(
				'name' => true
			),
			'options' => $options
		);
		$typesData['submit'] = array(
			'id' => 'ycf-submit-wrapper',
			'isFree' => 1,
			'fieldType' => 'submit',
			'type' => 'submit',
			'name' => 'ycf-submit',
			'label' => 'Submit',
			'orderNumber' => 0,
			'value' => 'Submit',
			'settings' => array(
				'required' => ''
			),
			'attrs' => array(),
			'disableConfig' => array(
				'name' => true
			),
			'options' => json_encode($options)
		);

		return $typesData;
	}

	public static function formOptionValueData() {

		$data = array(
			0 => array('label' => 'One', 'value' => 'one', 'orderId' => 0, 'options'=> ''),
			1 => array('label' => 'Two', 'value' => 'two', 'orderId' => 1, 'options'=> ''),
			2 => array('label' => 'Three', 'value' => 'three', 'orderId' => 2, 'options'=> '')
		);

		return $data;
	}

	public function getRandomNumber($length = 5) {

		$result = '';

		for($i = 0; $i < $length; $i++) {
			$result .= mt_rand(0, 9);
		}

		return $result;
	}

	public function createFormAdminElement() {

		$formElements = $this->getFormElementsData();

		$content = '';
		$oderKeys = implode(',',array_keys($formElements));

		foreach($formElements as $key => $formElement) {
			$args['oderId'] = $key;
			$content .= YcfForm::createAdminViewHtml($formElement, $args);
		}
		$name = $this->orderFieldName;
		$content .= '<input type="hidden" name="'.esc_attr($name).'" class="form-element-ordering" value="'.esc_attr($oderKeys).'">';

		return $content;
	}

	public static function createAdminViewHtml($formElement, $args) {
		ob_start();
		$elementId = $formElement['id'];
		$orderId = $args['oderId'];
		?>
		<div class="ycf-element-info-wrapper">
			<div class="ycf-view-element-wrapper" data-options="false" id="<?php echo esc_attr($elementId) ?>">
				<div class="ycf-element-label-wrapper">
					<span class="sub-option-hidden-data"  data-order="<?php echo esc_attr($orderId); ?>"></span>
					<span class="ycf-element-label-text">
						<?php echo esc_attr($formElement['label']) ?>
						<?php if($formElement['fieldType'] === 'gdpr'): ?>
						<span> (GDPR)</span>
						<?php endif;?>
					</span>
				</div>
				<div class="ycf-element-conf-wrapper">
					<span class="ycf-conf-element ycf-conf-home"></span>
					<?php if((empty($formElement['disableConfig']['required']) || $formElement['disableConfig']['required'] !== true) && $formElement['fieldType'] != 'submit'): ?>
						<span class="ycf-conf-element ycf-delete-element ycf-hide-element" data-id="<?php echo esc_attr($elementId) ?>" data-type="<?php echo esc_attr($formElement['fieldType']);?>"></span>
					<?php endif; ?>
				</div>
			</div>
			<?php
			echo wp_kses(self::currentElementOptions($formElement, $args), \YpmAdminHelper::getAllowedTags());
			?>
			<div class="ycf-element-margin-bottom"></div>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public static function currentElementOptions($formElement, $args) {
		$elementId = $formElement['id'];
		$elementType = $formElement['type'];
		ob_start();
		?>
		<div class="ycf-element-options-wrapper ycf-hide-element" >
			<?php if(isset($formElement['label'])): ?>
				<div class="ycf-sub-option-wrapper row">
					<span class="element-option-sub-label col-md-4">Label</span>
					<div class="col-md-6">
						<input type="text" class="element-label ycf-element-sub-option form-control"  value="<?php echo esc_attr($formElement['label']);?>" data-key="label" data-id="<?php echo esc_attr($elementId);?>">
					</div>
				</div>
			<?php endif;?>
			<?php if(isset($formElement['placeholder'])): ?>
				<div class="ycf-sub-option-wrapper row">
					<span class="element-option-sub-label col-md-4">Placeholder</span>
					<div class="col-md-6">
						<input type="text" class="element-label ycf-element-sub-option form-control"  value="<?php echo esc_attr($formElement['placeholder']);?>" data-key="placeholder" data-id="<?php echo esc_attr($elementId);?>">
					</div>
				</div>
			<?php endif;?>
			<?php if(isset($formElement['name'])): ?>
				<?php $disabled = (isset($formElement['disableConfig']['name'])) ? 'disabled': '';?>
				<div class="ycf-sub-option-wrapper row">
					<span class="element-option-sub-label col-md-4">Name</span>
					<div class="col-md-6">
						<input type="text" class="element-name ycf-element-sub-option form-control" value="<?php echo esc_attr($formElement['name']); ?>" data-key="name" data-id="<?php echo esc_attr($elementId);?>" <?php echo esc_attr($disabled); ?>>
					</div>
				</div>
			<?php endif; ?>
			<?php if($formElement['fieldType'] == 'gdpr'): ?>
				<div class="ycf-sub-option-wrapper row">
					<span class="element-option-sub-label col-md-4">Text</span>
					<div class="col-md-6">
						<input type="text" class="element-name ycf-element-sub-option form-control" value="<?php echo esc_attr($formElement['text']); ?>" data-key="text" data-id="<?php echo esc_attr($elementId);?>" >
					</div>
				</div>
			<?php endif;?>
			<?php if($elementType == 'select'): ?>
				<?php echo self::selectBoxOptions($formElement, $args); ?>
			<?php endif; ?>
			<?php if(isset($formElement['settings'])): ?>
					<div class="ycf-sub-option-wrapper row">
					<?php  if(isset($formElement['settings']['required'])): ?>
						<?php
						$checked = (!empty($formElement['settings']['required']) && $formElement['settings']['required'] == true) ? 'checked': '';
						$disabled = (isset($formElement['disableConfig']['required'])) ? 'disabled': '';
						?>
						<span class="element-option-sub-label col-md-4">Required</span>

						<label class="ypm-switch">
							<input type="checkbox" class="ycf-element-sub-option" <?php echo esc_attr($checked) ?> <?php echo esc_attr($disabled); ?>  data-key="required" data-id="<?php echo esc_attr($elementId);?>" data-is-settings="true">
							<span class="ypm-slider ypm-round"></span>
						</label>
					<?php
						endif;
					?>
					</div>
					<div class="ycf-sub-option-wrapper row">
					<?php if(isset($formElement['settings']['disableLabel'])): ?>
						<?php
						$disableLabel = $formElement['settings']['disableLabel'];

						$checked = (!empty($disableLabel) && $disableLabel == "true") ? 'checked': '';
						$disabled = (isset($formElement['disableConfig']['disableLabel'])) ? 'disabled': '';
						?>
						<span class="element-option-sub-label col-md-4">Disable Label</span>

						<label class="ypm-switch">
							<input type="checkbox" class="ycf-element-sub-option" <?php echo esc_attr($checked) ?> <?php echo esc_attr($disabled); ?>  data-key="disableLabel" data-id="<?php echo esc_attr($elementId);?>" data-is-settings="true">
							<span class="ypm-slider ypm-round"></span>
						</label>
					<?php
						endif;
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public static function selectBoxOptions($formElement, $args) {

		$elementId = $formElement['id'];
		ob_start();
		?>
		<div class="ycf-sub-option-wrapper ycf-sub-options-header">
			<div class="row margin-bottom-fix">
				<div class="col-md-4">
					<span>Select Options</span>
				</div>
				<div class="col-md-7">
					<input type="button" value="Add option" class="ycf-add-sub-option-group btn btn-primary js-disable-in-ajax" data-id="<?php echo esc_attr($elementId); ?>" data-type="option">
				</div>
			</div>
			<?php
			echo self::optionsValuesHtml($formElement);
			?>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/*
	 * Options name value string
	 *
	 * @since 1.0.5
	 *
	 * @param array $fields
	 *
	 * @return string $optionsString
	 *
	 */
	public static function optionsValuesHtml($formElement) {

		$optionsString = '';

		if(empty($formElement)) {
			return $optionsString;
		}

		$elementId = $formElement['id'];
		$formElementOptions = $formElement['options'];
		//$formElementOptions= json_decode(stripslashes($formElementOptions), true);

		if(empty($formElementOptions)) {
			return $optionsString;
		}

		$fieldOptions = $formElementOptions['fieldsOptions'];

		if(empty($fieldOptions)) {
			$optionsString .= '<div class="ycf-options-data-names">';
			$optionsString .= '</div>';
			return $optionsString;
		}

		$fieldsOrder = $formElementOptions['fieldsOrder'];

		$optionsString .= '<div class="ycf-options-data-names">';
		$optionsString .= self::headerLabels();

		foreach($fieldsOrder as $fieldId) {

			$field = $fieldOptions[$fieldId];
			$label = $field['label'];
			$value = $field['value'];

			$optionsStringValues = self::subOptionsGroupOptions($fieldId, $elementId, $value, $label);
			$optionsString .= $optionsStringValues;
		}
		$optionsString .= '</div>';
		return $optionsString;
	}

	public static function headerLabels() {
		ob_start();
		?>
		<div class="row current-options data-type-sub-options sub-options-group-wrapper margin-bottom-fix" data-order="<?php echo esc_attr($fieldId); ?>" data-id="<?php echo esc_attr($elementId); ?>" data-type="option">
			<div class="col-md-4">
				<span><?php echo esc_attr__('Value', 'popup_master'); ?></span>
			</div>
			<div class="col-md-4">
				<span><?php echo esc_attr__('Label', 'popup_master'); ?></span>
			</div>
		</div>
		<?php
		$headerLabels = ob_get_contents();
		ob_get_clean();

		return $headerLabels;
	}

	public static function subOptionsGroupOptions($fieldId, $elementId, $value, $label)
	{
		ob_start();
		?>
		<div class="row current-options data-type-sub-options sub-options-group-wrapper margin-bottom-fix" data-order="<?php echo esc_attr($fieldId); ?>" data-id="<?php echo esc_attr($elementId); ?>" data-type="option">
			<div class="col-md-4">
				<input type="text" class="sub-option-name form-control" name="value" value="<?php echo esc_attr($value); ?>" style="margin-right: 5px;">
			</div>
			<div class="col-md-4">
				<input type="text" class="sub-option-value form-control" name="label" value="<?php echo esc_attr($label); ?>" data-id="<?php echo esc_attr($elementId); ?>" data-type="option">
			</div>
			<div class="col-md-2">
				<span class="delete-sub-option"></span>
			</div>
		</div>
		<?php
		$optionsStringValues = ob_get_contents();
		ob_end_clean();

		return $optionsStringValues;
	}

	protected function getAsterisk($required) {

		$asterisk = '';

		if($required) {
			$asterisk = '<span class="ycf-asterisk">*</span>';
		}

		return $asterisk;
	}

	public function createValidateObj($subsFields, $validationMessages = array())
	{

		$validateArray = array(
			'rules' => array(),
			'messages' => array()
		);

		//		$requiredMessage = $this->getOptionValue('ypm-subs-validation-message');
//		$emailMessage = $this->getOptionValue('ypm-subs-invalid-message');
//
//		if (empty($subsFields)) {
//			return $validateObj;
//		}
//
//		if (empty($emailMessage)) {
//			$emailMessage = 'defined message';
//		}
//
//		if (empty($requiredMessage)) {
//			$requiredMessage = 'defined message';
//		}
		$requiredMessage = YPM_FORM_REQUIRED_MESSAGE;
		$emailMessage = YPM_FORM_INVALID_EMAIL;

		foreach ($subsFields as $subsField) {

			if (empty($subsField['settings'])) {
				continue;
			}
			$settings = $subsField['settings'];
			$type = 'text';
			$name = '';
			$required = false;

			if (!empty($settings['required'])) {
				$required = $settings['required'];
			}

			if (!$required) {
				continue;
			}

			if (!empty($subsField['type'])) {
				$type = $subsField['type'];
			}
			if (!empty($subsField['name'])) {
				$name = $subsField['name'];
			}

			if ($type == 'email') {
				$validateArray['rules'][$name] = array('required' => $required, 'email' => true);
				$validateArray['messages'][$name] = array('required' => $requiredMessage, 'email' => $emailMessage);
				continue;
			}
			$validateArray['rules'][$name] = 'required';
			$validateArray['messages'][$name] = $requiredMessage;
		}

		return $validateArray;
	}

	public function getFieldButton($fieldObj) {
		ob_start();
		?>
			<div class="sortable-custom-element" id="one" data-element-type="<?php esc_attr_e(@$fieldObj['fieldType']);?>">
				<span>
					<?php echo esc_attr($fieldObj['label']);?>
					<?php if($fieldObj['fieldType'] === 'gdpr'): ?>
						<span> (GDPR)</span>
					<?php endif;?>
				</span>
			</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}