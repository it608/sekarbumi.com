<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$defaultData = YpmPopupData::defaultsData();
$allowedTags = YpmAdminHelper::getAllowedTags();
?>
<?php
	global $YpmDefaultsData;
	$formWidthMeasure = $YpmDefaultsData['subscriptionFormWidthMeasure'];
?>
<div class="ycf-bootstrap-wrapper">
	<div class="row form-group">
		<div class="col-md-2">
			<label for="ypm-subscription-form-width"><?php esc_attr_e('Form width', 'popup_master')?></label>
		</div>
		<div class="col-md-4">
			<input type="number" class="ypm-subscription-form-width form-control" id="ypm-subscription-form-width" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-form-width'));?>">
		</div>
		<div class="col-md-2">
			<?php echo YpmFunctions::createSelectBox(
				$formWidthMeasure,
				$popupTypeObj->getOptionValue('ypm-facebook-action'),
				array(
					'name' => 'ypm-subscription-form-width-mesure',
					'class' => 'js-basic-select form-control ypm-fblike-action ypm-fblike-option')
				); ?>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-2">
			<label for="ypm-subscription-form-width"><?php esc_attr_e('Labels', 'popup_master')?></label>
		</div>
		<div class="col-md-4">
		
		</div>
	</div>
	<div class="ypm-sub-section">
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-labels-font-size"><?php esc_attr_e('Font size', 'popup_master')?></label>
				<?php echo ypm_info(esc_attr__('If empty it will take default value or you set example 10px', 'popup_master')); ?>
			</div>
			<div class="col-md-4">
				<input id="ypm-subscription-labels-font-size" class="form-control"  placeholder="Ex 10px" type="text" name="ypm-subscription-labels-font-size" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-labels-font-size'))?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-labels-font-weight"><?php esc_attr_e('Font wight', 'popup_master')?></label>
			</div>
			<div class="col-md-4">
			<?php echo wp_kses(YpmAdminHelper::selectBox($defaultData['fontWeight'], $popupTypeObj->getOptionValue('ypm-subscription-labels-font-weight'), array('name' => 'ypm-subscription-labels-font-weight', 'class' => 'js-basic-select')), $allowedTags); ?>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-labels-margin-bottom"><?php esc_attr_e('Margin bottom', 'popup_master')?></label>
				<?php echo ypm_info(esc_attr__('If empty it will take default value or you set example 10px', 'popup_master')); ?>
			</div>
			<div class="col-md-4">
				<input id="ypm-subscription-labels-margin-bottom" class="form-control"  placeholder="Ex 10px" type="text" name="ypm-subscription-labels-margin-bottom" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-labels-margin-bottom'))?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-labels-color" class=""><?php _e('Color', YPM_POPUP_TEXT_DOMAIN); ?></label>
			</div>
			<div class="col-md-4 ypm-option-wrapper<?php echo $isPro; ?>">
				<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
					<input type="text" id="ypm-subscription-labels-color"  placeholder="<?php _e('Select color', YPM_POPUP_TEXT_DOMAIN)?>" name="ypm-subscription-labels-color" class=" minicolors-input form-control js-ypm-time-text-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-labels-color')); ?>">
				</div>
			</div>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-2">
			<label for="ypm-subscription-form-width"><?php esc_attr_e('Input', 'popup_master')?></label>
		</div>
		<div class="col-md-4">
		
		</div>
	</div>
	<div class="ypm-sub-section">
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-input-width"><?php esc_attr_e('Width', 'popup_master')?></label>
				<?php echo ypm_info(esc_attr__('If empty it will take default value or you set example 100px, 100%, 10rem ', 'popup_master')); ?>
			</div>
			<div class="col-md-4">
				<input id="ypm-subscription-input-width" class="form-control" placeholder="Ex 100px, 100%, 10rem" type="text" name="ypm-subscription-input-width" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-input-width'))?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-input-height"><?php esc_attr_e('Height', 'popup_master')?></label>
				<?php echo ypm_info(esc_attr__('If empty it will take default value or you set example 50px, 50%, 10rem ', 'popup_master')); ?>
			</div>
			<div class="col-md-4">
				<input id="ypm-subscription-input-height" class="form-control" placeholder="Ex 50px, 50%, 10rem" type="text" name="ypm-subscription-input-height" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-input-height'))?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-input-font-size"><?php esc_attr_e('Font size', 'popup_master')?></label>
				<?php echo ypm_info(esc_attr__('If empty it will take default value or you set example 10px, 10rem', 'popup_master')); ?>
			</div>
			<div class="col-md-4">
				<input id="ypm-subscription-input-font-size" class="form-control"  placeholder="Ex 10px, 10rem" type="text" name="ypm-subscription-input-font-size" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-input-font-size'))?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-input-color" class=""><?php _e('Color', YPM_POPUP_TEXT_DOMAIN); ?></label>
			</div>
			<div class="col-md-4 ypm-option-wrapper<?php echo $isPro; ?>">
				<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
					<input type="text" id="ypm-subscription-input-color"  placeholder="<?php _e('Select color', YPM_POPUP_TEXT_DOMAIN)?>" name="ypm-subscription-input-color" class=" minicolors-input form-control js-ypm-time-text-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-input-color')); ?>">
				</div>
			</div>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-2">
			<label for="ypm-subscription-form-width"><?php esc_attr_e('Submit button', 'popup_master')?></label>
		</div>
		<div class="col-md-4">
		
		</div>
	</div>
	<div class="ypm-sub-section">
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-submit-width"><?php esc_attr_e('Width', 'popup_master')?></label>
				<?php echo ypm_info(esc_attr__('If empty it will take default value or you set example 100px, 100%, 10rem ', 'popup_master')); ?>
			</div>
			<div class="col-md-4">
				<input id="ypm-subscription-submit-width" class="form-control" placeholder="Ex 100px, 100%, 10rem" type="text" name="ypm-subscription-submit-width" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-submit-width'))?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-submit-height"><?php esc_attr_e('Height', 'popup_master')?></label>
				<?php echo ypm_info(esc_attr__('If empty it will take default value or you set example 50px, 50%, 10rem ', 'popup_master')); ?>
			</div>
			<div class="col-md-4">
				<input id="ypm-subscription-submit-height" class="form-control" placeholder="Ex 50px, 50%, 10rem" type="text" name="ypm-subscription-submit-height" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-submit-height'))?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-2">
				<label for="ypm-subscription-submit-font-size"><?php esc_attr_e('Font size', 'popup_master')?></label>
				<?php echo ypm_info(esc_attr__('If empty it will take default value or you set example 10px, 10rem', 'popup_master')); ?>
			</div>
			<div class="col-md-4">
				<input id="ypm-subscription-submit-font-size" class="form-control"  placeholder="Ex 10px, 10rem" type="text" name="ypm-subscription-submit-font-size" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-submit-font-size'))?>">
			</div>
		</div>
	</div>
</div>