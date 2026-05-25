<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
$savedImage = $popupTypeObj->getOptionValue('ypm-gamification-gift-image');
$currentImage = YpmAdminHelper::getImageNameFromSavedData($savedImage);
?>
<div class="row form-group">
	<label class="col-md-12 control-label ypm-static-padding-top">
		<?php esc_attr_e('Input styles', 'popup_master'); ?>:
	</label>
</div>
<div class="row form-group">
	<label for="ypm-gamification-text-placeholder" class="col-md-5 control-label ypm-static-padding-top ">
		<?php esc_attr_e('Placeholder', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<input type="text" class="form-control js-gamification-dimension ypm-full-width-events" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="width" name="ypm-gamification-text-placeholder" id="ypm-gamification-text-placeholder" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-text-placeholder')); ?>">
	</div>
</div>
<div class="row form-group">
	<label for="gamification-text-width" class="col-md-5 control-label ypm-static-padding-top ">
		<?php esc_attr_e('Width', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<input type="text" class="form-control js-gamification-dimension ypm-full-width-events" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="width" name="ypm-gamification-text-width" id="gamification-text-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-text-width')); ?>">
	</div>
</div>
<div class="row form-group">
	<label for="gamification-text-height" class="col-md-5 control-label ypm-static-padding-top ">
		<?php esc_attr_e('Height', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<input class="form-control js-gamification-dimension ypm-full-width-events" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="height" type="text" name="ypm-gamification-text-height" id="gamification-text-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-text-height')); ?>">
	</div>
</div>
<div class="row form-group">
	<label for="gamification-text-border-width" class="col-md-5 control-label ypm-static-padding-top ">
		<?php esc_attr_e('Border width', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<input class="form-control js-gamification-dimension ypm-full-width-events" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="border-width" type="text" name="ypm-gamification-text-border-width" id="gamification-text-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-text-border-width')); ?>">
	</div>
</div>
<div class="row form-group">
	<label for="ypm-gamification-text-border-radius" class="col-md-5 control-label ">
		<?php esc_attr_e('Border radius', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<input class="form-control js-gamification-dimension ypm-full-width-events" data-gamification-rel="js-gamification-submit-btn" data-field-type="text" data-style-type="border-radius" type="text" name="ypm-gamification-text-border-radius" id="ypm-gamification-text-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-text-border-radius')); ?>">
	</div>
</div>
<div class="row form-group">
	<label class="col-md-5 control-label ">
		<?php esc_attr_e('Background color', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<div class="ypm-color-picker-wrapper">
			<input class="ypm-color-picker js-gamification-color-picker" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="background-color" type="text" name="ypm-gamification-text-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-text-bg-color')); ?>" >
		</div>
	</div>
</div>
<div class="row form-group">
	<label class="col-md-5 control-label ">
		<?php esc_attr_e('Border color', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<div class="ypm-color-picker-wrapper">
			<input class="ypm-color-picker js-gamification-color-picker" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="border-color" type="text" name="ypm-gamification-text-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-text-border-color')); ?>" >
		</div>
	</div>
</div>
<div class="row form-group">
	<label class="col-md-5 control-label ">
		<?php esc_attr_e('Text color', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<div class="ypm-color-picker-wrapper">
			<input class="ypm-color-picker js-gamification-color-picker" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="color" type="text" name="ypm-gamification-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-text-color')); ?>" >
		</div>
	</div>
</div>
<div class="row form-group">
	<label class="col-md-5 control-label ">
		<?php esc_attr_e('Placeholder color', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<div class="ypm-color-picker-wrapper">
			<input class="ypm-color-picker js-gamification-color-picker ypm-full-width-events" data-field-type="input" data-gamification-rel="js-gamification-text-inputs" data-style-type="placeholder" type="text" name="ypm-gamification-text-placeholder-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-text-placeholder-color')); ?>" >
		</div>
	</div>
</div>

<!-- Input styles end -->
<div class="row form-group">
	<label class="col-md-12 control-label ypm-static-padding-top">
		<?php esc_attr_e('Button styles', 'popup_master'); ?>:
	</label>
</div>
<div class="row form-group">
	<label for="ypm-gamification-btn-width" class="col-md-5 control-label ypm-static-padding-top ">
		<?php esc_attr_e('Width', 'popup_master')  ?>:
	</label>
	<div class="col-md-7">
		<input name="ypm-gamification-btn-width" id="ypm-gamification-btn-width" type="text" class="ypm-full-width form-control" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-btn-width'))?>" required>
	</div>
</div>
<div class="row form-group">
	<label for="ypm-gamification-btn-height" class="col-md-5 control-label ypm-static-padding-top ">
		<?php esc_attr_e('Height', 'popup_master')  ?>:
	</label>
	<div class="col-md-7">
		<input name="ypm-gamification-btn-height" id="ypm-gamification-btn-height" type="text" class="ypm-full-width form-control" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-btn-height'))?>" required>
	</div>
</div>
<div class="row form-group">
	<label for="ypm-gamification-btn-border-width" class="col-md-5 control-label ypm-static-padding-top ">
		<?php esc_attr_e('Border width', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<input class="form-control js-gamification-dimension ypm-full-width-events" data-field-type="submit" data-gamification-rel="js-gamification-submit-btn" data-style-type="border-width" type="text" name="ypm-gamification-btn-border-width" id="ypm-gamification-btn-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-btn-border-width')); ?>">
	</div>
</div>
<div class="row form-group">
	<label for="ypm-gamification-btn-border-radius" class="col-md-5 control-label ">
		<?php esc_attr_e('Border radius', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<input class="form-control js-gamification-dimension ypm-full-width-events" data-gamification-rel="js-gamification-submit-btn" data-field-type="submit" data-style-type="border-radius" type="text" name="ypm-gamification-btn-border-radius" id="ypm-gamification-btn-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-btn-border-radius')); ?>">
	</div>
</div>
<div class="row form-group">
	<label for="ypm-gamification-btn-border-color" class="col-md-5 control-label ">
		<?php esc_attr_e('Border color', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<div class="ypm-color-picker-wrapper">
			<input id="ypm-gamification-btn-border-color" class="ypm-color-picker js-gamification-color-picker" data-field-type="submit" data-gamification-rel="js-gamification-submit-btn" data-style-type="border-color" type="text" name="ypm-gamification-btn-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-btn-border-color')); ?>" >
		</div>
	</div>
</div>
<div class="row form-group">
	<label for="gamification-btn-title" class="col-md-5 control-label ypm-static-padding-top ">
		<?php esc_attr_e('Title', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<input type="text" name="ypm-gamification-btn-title" id="gamification-btn-title" class="form-control js-gamification-btn-title ypm-full-width-events" data-field-type="submit" data-gamification-rel="js-gamification-submit-btn" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-btn-title')); ?>">
	</div>
</div>
<div class="row form-group">
	<label for="btn-progress-title" class="col-md-5 control-label ypm-static-padding-top ">
		<?php esc_attr_e('Title (in progress)', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<input type="text" name="ypm-gamification-btn-progress-title" id="btn-progress-title" class="form-control ypm-full-width-events" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-btn-progress-title')); ?>">
	</div>
</div>
<div class="row form-group">
	<label class="col-md-5 control-label ">
		<?php esc_attr_e('Background color', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<div class="ypm-color-picker-wrapper">
			<input class="ypm-color-picker js-gamification-color-picker" data-field-type="submit" data-gamification-rel="js-gamification-submit-btn" data-style-type="background-color" type="text" name="ypm-gamification-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-btn-bg-color')); ?>" >
		</div>
	</div>
</div>
<div class="row form-group">
	<label class="col-md-5 control-label ">
		<?php esc_attr_e('Text color', 'popup_master'); ?>:
	</label>
	<div class="col-md-7">
		<div class="ypm-color-picker-wrapper">
			<input class="ypm-color-picker js-gamification-color-picker" data-field-type="submit" data-gamification-rel="js-gamification-submit-btn" data-style-type="color" type="text" name="ypm-gamification-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-btn-text-color')); ?>" >
		</div>
	</div>
</div>
<div class="row form-group">
	<div class="col-md-12">
		<label for="ypm-gamification-gift-image" class="control-label ypm-static-padding-top">
			<?php esc_attr_e('Gift image', 'popup_master')?>:
		</label>
	</div>
</div>
<div class="row form-group">
	<div class="col-md-12">
		<?php echo YpmAdminHelper::renderGiftIcons($currentImage); ?>
		<div class="ypm-gift-conging-wrapper">
			<div class="ypm-gift-btn-image-wrapper ypm-gift-config-margin">
				<div class="ypm-display-inline-block ypm-show-gamification-image-container" style="background-image: url(<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-gamification-gift-image')); ?>);">
					<span class="ypm-no-image"></span>
				</div>
			</div>
			<div class="ypm-display-inline-block ypm-close-btn-change-image-wrapper ypm-gift-config-margin">
				<input id="js-gamification-upload-image-button" class="btn btn-sm btn-default" type="button" value="<?php esc_attr_e('Custom image', 'popup_master');?>">
			</div>
			<div class="ypm-gift-config-margin ypm-display-inline-block js-ypm-remove-gamification-image <?php echo ($savedImage == YPM_GAMIFICATION_IMAGE_URL) ? ' sg-hide-remove-button' : '';?>">
				<input id="js-gamification-upload-image-remove-button" data-default-image-url="<?php echo YPM_GAMIFICATION_IMAGE_URL; ?>" class="btn btn-sm btn-danger" type="button" value="<?php esc_attr_e('Remove', 'popup_master');?>">
			</div>
		</div>
	</div>
</div>
<div class="row form-group">
	<div>
		<div class="ypm-button-image-uploader-wrapper">
			<input class="sg-hide" id="ypm-gamification-gift-image" type="text" name="ypm-gamification-gift-image" value="<?php echo esc_attr($savedImage); ?>">
		</div>
	</div>
	<div class="col-md-7">

	</div>
</div>
<div class="row form-group">
	<label class="col-md-6 control-label ypm-static-padding-top">
		<?php esc_attr_e('Error message', 'popup_master'); ?>:
	</label>
	<div class="col-md-6">
		<input type="text" class="form-control ypm-full-width-events" name="ypm-gamification-error-message" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-gamification-error-message')); ?>">
	</div>
</div>
<div class="row form-group">
	<label class="col-md-6 control-label ypm-static-padding-top">
		<?php esc_attr_e('Invalid email message', 'popup_master'); ?>:
	</label>
	<div class="col-md-6">
		<input type="text" class="form-control ypm-full-width-events" name="ypm-gamification-invalid-message" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-gamification-invalid-message')); ?>">
	</div>
</div>
<div class="row form-group">
	<label for="gamification-validation-message" class="col-md-6 control-label ypm-static-padding-top">
		<?php esc_attr_e('Required field message', 'popup_master'); ?>:
	</label>
	<div class="col-md-6">
		<input type="text" name="ypm-gamification-validation-message" id="gamification-validation-message" class="form-control ypm-full-width-events" maxlength="90" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-validation-message')); ?>">
	</div>
</div>
<div class="row form-group">
	<label for="ypm-gamification-gdpr-term" class="col-md-6 control-label ypm-static-padding-top">
		<?php esc_attr_e('GDPR terms', 'popup_master'); ?>:
	</label>
	<div class="col-md-6">
		<input type="text" name="ypm-gamification-gdpr-terms" id="ypm-gamification-gdpr-term" class="form-control ypm-full-width-events" value="<?php echo esc_html($popupTypeObj->getOptionValue('ypm-gamification-gdpr-term')); ?>">
	</div>
</div>
