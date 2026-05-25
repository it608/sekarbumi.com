<?php
namespace YpmPopup;

use \YpmAdminHelper;

class ConditionBuilder {

	private $columnCount = 4;
	private $additionalValue = null;
	private $valueFromFirst = true;
	private $currentIndex;
	private $nameString = '';
	private $paramKey;
	private $configData;
	private $savedData = array();
	private $popupId;

	public function setColumnCount($columnCount) {
		$this->columnCount = $columnCount;
	}

	public function getColumnCount() {
		return $this->columnCount;
	}

	public function setValueFromFirst($valueFromFirst) {
		$this->valueFromFirst = (bool)$valueFromFirst;
	}

	public function getValueFromFirst() {
		return (bool)$this->valueFromFirst;
	}

	public function setSavedData($savedData) {
		$this->savedData = $savedData;
	}

	public function getSavedData() {
		return $this->savedData;
	}

	public function setConfigData($configData) {
		$this->configData = $configData;
	}

	public function getConfigData() {
		return $this->configData;
	}

	public function setCurrentIndex($currentIndex) {
		$this->currentIndex = $currentIndex;
	}

	public function getCurrentIndex() {
		return $this->currentIndex;
	}

	public function setNameString($nameString) {
		$this->nameString = $nameString;
	}

	public function getNameString() {
		return $this->nameString;
	}

	public function setParamKey($paramKey) {
		$this->paramKey = $paramKey;
	}

	public function getParamKey() {
		return $this->paramKey;
	}
	
	public function setAdditionalValue($paramKey) {
		$this->additionalValue = $paramKey;
	}

	public function getAdditionalValue() {
		return $this->additionalValue;
	}

	public function setPopupId($popupId) {
		$this->popupId = $popupId;
	}

	public function getPopupId() {
		return $this->popupId;
	}

	public function getChildClassName() {
		$path = explode('\\', get_class($this));

		return array_pop($path);
	}

	public function render() {
		$childClassName = $this->getChildClassName();
		$content = '<div class="ypm-conditions-wrapper" data-child-class="'.wp_kses($childClassName, YpmAdminHelper::getAllowedTags()).'">';
		$content .= $this->renderConditions();
		$content .= '</div>';

		return $content;
	}

	private function renderConditions() {
		$savedData = $this->getSavedData();

		if(empty($savedData)) {
			return '<div>'.esc_attr__('No Data', 'popup_master').'</div>';
		}
		$conditions = '';
		foreach($savedData as $currentIndex => $data) {
			$this->setValueFromFirst(true);
			$conditions .=  $this->getConditionRowFromCurrentData($data, $currentIndex);
		}

		return $conditions;
	}

	private function getConditionRowFromCurrentData($data, $currentIndex) {

		$configData = $this->getConfigData();

		$configValues = $configData['values'];
		$attributes = $configData['attributes'];
		$valueFromFirst = $this->getValueFromFirst();

		$conditions = '<div class="ypm-condition-wrapper row form-group" data-value-from-first="'.esc_attr($valueFromFirst).'" data-condition-id="'.esc_attr($currentIndex).'">';

		if(empty($data['key3']) && isset($configData['values'][$data['key1']])) {
			$data['key3'] = '';
		}

		$currentConditionIndex = 1;

		foreach($data as $keyName => $value) {
			$valueFromFirst = $this->getValueFromFirst();
	
			$paramKey = $keyName;

			// get 3th column key
			if($currentConditionIndex > 1) {

				if($valueFromFirst) {
					if($keyName == 'key3') {
						$paramKey = $data['key1'];
					}
				}
				else {
					$lastIndex = $currentConditionIndex-1;
                    $paramKey = $this->getParamKeyValue($lastIndex, $data, $configValues);
				}
			}

			if(empty($attributes[$paramKey])) {
				if(empty($attributes[$data['key1']]['additionalValue'])) {
					continue;
				}
				else {
					$paramKey = $attributes[$data['key1']]['additionalValue'];
				}
			
			}
            $this->setParamKey($paramKey);
			++$currentConditionIndex;
			$this->setCurrentIndex($currentIndex);
			$attributes[$paramKey]['savedValue'] = $value;

			$conditions .= $this->renderCurrentConditionRow($configValues, $attributes, $keyName, $currentIndex);
		}

		$conditions .= $this->renderConditionConfig($currentIndex, $data);
		$conditions .= '</div>';

		return $conditions;
	}

	private function getParamKeyValue($lastIndex, $data, $configValues)
    {
        if ($data['key'.esc_attr($lastIndex)] !== '') {
            return $data['key'.esc_attr($lastIndex)];
        }
        $prevIndex = $lastIndex-1;
        $prevSelected = $data['key'.esc_attr($prevIndex)];
        if (!empty($configValues[$prevSelected]) && is_array($configValues[$prevSelected])) {
            return key($configValues[$prevSelected]);
        }

        return '';
    }

	public function renderCurrentConditionRow($configValues, $attributes, $keyName, $key) {
		$paramKey = $this->getParamKey();
		$nameString = $this->getNameString();

		$name = $nameString.'['.esc_attr($key).']['.esc_attr($keyName).']';
		$currentData = @$configValues[$paramKey];
		$currentAttributes = $attributes[$paramKey];
		$this->filterForFromFirstValue($configValues, $attributes, $paramKey, $key);
		$conditions = $this->renderConditionRow($name, $currentData, $currentAttributes);

		return $conditions;
	}

	private function filterForFromFirstValue($configValues, $attributes, $keyName, $key)
	{
		if (empty($attributes[$keyName])) {
			return false;
		}

		$currentAttributes = $attributes[$keyName];
		if (empty($currentAttributes['savedValue'])) {
			return false;
		}
		$savedValue = $currentAttributes['savedValue'];

		if (!is_null($savedValue) && (is_string($savedValue) || is_int($savedValue)) && is_array($attributes) && isset($attributes[$savedValue]) && isset($attributes[$savedValue]['allowFromFirstValue'])) {
			$this->setValueFromFirst($attributes[$savedValue]['allowFromFirstValue']);
		}

		return true;
	}

	private function renderConditionRow($name, $currentData, $attributes) {
		$fieldType = $attributes['fieldType'];
		$savedValue = $attributes['savedValue'];
		$fieldHtml = '<div class="col-md-3">';

		$fieldHtml .= $this->rowHeader($name, $currentData, $attributes);
		$fieldAttributes = $attributes['fieldAttributes'];

		if($fieldType == 'select') {
			if(!empty($fieldAttributes['multiple'])) {
				$name .= '[]';
			}
			$fieldAttributes['name'] = $name;
			if(!empty($fieldAttributes['data-select-type']) && $fieldAttributes['data-select-type'] == 'ajax') {
				$currentData = $savedValue;
				if (!empty($savedValue)) {
					$savedValue = @array_keys($savedValue);
				}
				else {
					$savedValue = array();
				}
			}
			$fieldHtml .= YpmAdminHelper::selectBox($currentData, $savedValue, $fieldAttributes);
		}
		else if ($fieldType == 'input') {
			$fieldAttributes['name'] = $name;

			$fieldHtml .= YpmAdminHelper::inputElement($currentData, $savedValue, $fieldAttributes);
		}
		$fieldHtml .= '</div>';

		return $fieldHtml;
	}

	private function rowHeader($name, $currentData, $attributes) {
		$fieldHtml = '<div class="ypm-condition-header">';

		if(!empty($attributes['label'])) {
			$fieldHtml .= '<label>'.esc_attr($attributes['label']).'</label>';
		}
		$fieldHtml .= '</div>';

		return $fieldHtml;
	}

	private function renderConditionConfig($currentConditionIndex, $data)
    {
		if(empty($data) || $data['key1'] == 'select_settings') {
			return '';
		}

        $configData = $this->getConfigData();
		$currentData = $configData['attributes'][$this->getParamKey()];
		$conf = !empty($currentData['conditionsConf']) ? true: false;
		ob_start();
		?>
        <div class="col-md-3">
            <div class="ypm-condition-header ypm-conditions-buttons"><label></label></div>
            <div data-condition-id="<?php echo esc_attr($currentConditionIndex)?>" class="ypm-condition-add btn btn-primary">
                <?php  esc_attr_e('Add', 'popup_master'); ?>
            </div>
            <?php if ($conf): ?>
                <div data-condition-id="<?php echo esc_attr($currentConditionIndex); ?>" class="ypm-condition-settings btn btn-info">
                    <?php esc_attr_e('Settings', 'popup_master'); ?>
                </div>
                <div style="position: absolute; left: -9000000px;">
                    <div id="ypm-setting-<?php echo esc_attr($currentConditionIndex); ?>">
                        <h1>Settings</h1>
                        <?php echo wp_kses($this->renderSettingsContent($configData, $currentConditionIndex)); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div data-condition-id="<?php echo esc_attr($currentConditionIndex); ?>" class="ypm-condition-delete btn btn-danger">
                <?php esc_attr_e('Delete', 'popup_master'); ?>
            </div>
        </div>
        <?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function renderConditionRowFromParam($data, $currentIndex) {
		
        $key2 = '';
        if (is_array($data)) {
            $param = $data[0];
            $key2 = $data[1];
        }
        else {
            $param = $data;
        }
		$configData = $this->getConfigData();
		$attributes = $configData['attributes'];

        $key3Val = '';
		if (!empty($attributes[$key2]['fieldAttributes']['defaultValue'])) {
			$key3Val = $attributes[$key2]['fieldAttributes']['defaultValue'];

			$key3Val = str_replace( '{popupId}', $this->getPopupId(), $key3Val);
		}
		$savedData = array(
			'key1' => $param,
			'key2' => $key2,
			'key3' => $key3Val
		);

		if (!empty($attributes[$param]['additionalValue'])) {
			$savedData['key4'] = $attributes[$attributes[$param]['additionalValue']]['savedValue'];
		}
	
		if($param == 'select_settings') {
			$savedData = array(
				'key1' => $param
			);
		}

		return $this->getConditionRowFromCurrentData($savedData, $currentIndex);
	}

	private function renderSettingsContent($data, $currentIndex)
    {
        $settings = $data['attributes'][$this->getParamKey().'_settings'];
        $nameString = $this->getNameString();
        $savedData = $this->getSavedData();
        $savedSettings = $savedData[$currentIndex]['key3'];
        $name = $nameString.'['.esc_attr($currentIndex).'][key3]';

        ob_start();
        foreach ($settings as $setting) {
            $fieldType = $setting['fieldType'];
            $infoAttrs = $setting['infoAttrs'];
            $htmlAttrs = $setting['htmlAttrs'];
            $attrName = $htmlAttrs['name'];
            $savedValue = @$savedSettings[$attrName];
            $htmlAttrs['name'] = $name.'['.esc_attr($attrName).']';
            ?>
            <div class="form-group row">
                <div class="col-md-6">
                    <label <?php echo wp_kses(YpmAdminHelper::formatHTMLAttrStr($infoAttrs['labelAttrs']), YpmAdminHelper::getAllowedTags())?>>
                    <?php echo  esc_attr($infoAttrs['label']); ?></label>
                </div>
                <div class="col-md-6">
                    <?php
                    if($fieldType == 'select') {
                        echo wp_kses(YpmAdminHelper::selectBox(array(), $savedValue, $htmlAttrs), YpmAdminHelper::getAllowedTags());
                    }
                    else if ($fieldType == 'input') {
						
                        echo wp_kses(YpmAdminHelper::inputElement('', $savedValue, $htmlAttrs), YpmAdminHelper::getAllowedTags());
                    }
                    else if ($fieldType == 'switch') {
                        echo wp_kses(YpmAdminHelper::switchElement('', $savedValue, $htmlAttrs), YpmAdminHelper::getAllowedTags());
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        $html = ob_get_contents();
        ob_end_clean();

        return '<div class="ycf-bootstrap-wrapper ypm-popup-content-wrapper">'.wp_kses($html, YpmAdminHelper::getAllowedTags()).'</div>';
    }

	public function filterForSave() {
		$settings = $this->getSavedData();
		$configData = $this->getConfigData();

		$configValues = $configData['values'];
		$attributes = $configData['attributes'];

		foreach($settings as $index => $setting) {
			$valueFromFirst = $this->getValueFromFirst();
			$valueKey = $setting['key1'];
			if (!$valueFromFirst) {
				$valueKey = $setting['key2'];
			}
			if(empty($setting['key3'])) {
				$settings[$index]['key3'] = array();
				continue;
			}
			$currentAttributes = $attributes[$valueKey]['fieldAttributes'];
			if(!empty($currentAttributes['data-select-type'])) {

				$args = array(
					'post__in' => array_values($setting['key3']),
					'posts_per_page' => 10,
					'post_type'      => $currentAttributes['data-post-type']
				);

				$searchResults = YpmAdminHelper::getPostTypeData($args);
				$settings[$index]['key3'] = $searchResults;
			}
		}

		return $settings;
	}
}