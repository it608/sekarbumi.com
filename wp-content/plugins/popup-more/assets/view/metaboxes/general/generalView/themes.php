<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
$defaults = YpmPopupData::defaultsData();
$themData = $defaults['themes'];
$allowedTags = YpmAdminHelper::getAllowedTags();
$savedCloseImage = $popupTypeObj->getOptionValue('ypm-theme-close-image-url');

$closeButtonSettings = function ($theme = '', $fileName = 'close.png') use ($popupTypeObj) {
	$imageUrl = YPM_POPUP_IMAGE_URL.esc_attr($theme).'/'.esc_attr($fileName);
	$savedImageURL = $popupTypeObj->getOptionValue('ypm-theme-close-image-url');

	$hideClassname = 'ypm-hide';

	if (!empty($savedImageURL)) {
		$imageUrl = $savedImageURL;
		$hideClassname = '';
	}
	ob_start();
	?>
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-3">
			<label class="control-label" for="ypm-popup-theme-close-text"><?php esc_attr_e('Close image', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-1">
			<div class="ypm-close-image-wrapper ypm-close-image-wrapper-<?php echo esc_attr($theme); ?>"></div>
			<style id="ypm-default-preview-style-<?php echo esc_attr($theme); ?>">
				.ypm-close-image-wrapper-<?php echo esc_attr($theme); ?> {
					background-image: url("<?php echo esc_attr($imageUrl);?>");
				}
			</style>
		</div>
		<div class="col-xs-3">
			<input  class="btn btn-default js-button-upload-image-button" type="button" value="<?php esc_attr_e('Change image', 'popup_master');?>">
			<input class="btn btn-danger js-ypm-remove-close-button-image <?php echo esc_attr($hideClassname); ?>" type="button" value="<?php esc_attr_e('Reset', 'popup_master');?>">
		</div>
	</div>
	<?php
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
?>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-3">
		<label class="control-label" for="textinput"><?php esc_attr_e('Popup theme', 'popup_master');?>:</label>
	</div>
	<div class="col-xs-4">
		<?php echo YpmFunctions::createRadioButtons($themData, $popupTypeObj->getOptionValue('ypm-popup-theme'), array('name' => 'ypm-popup-theme', 'class' => 'ypm-popup-theme'))?>
		<div class="themes-preview theme-preview-1"></div>
		<div class="themes-preview theme-preview-2"></div>
		<div class="themes-preview theme-preview-3"></div>
		<div class="themes-preview theme-preview-4"></div>
		<div class="themes-preview theme-preview-5"></div>
		<div class="themes-preview theme-preview-6"></div>
	</div>
</div>
<div class="ypm-themes-sub-options ypm-themes-sub-options-colorbox1 ypm-hide">
	<?php echo wp_kses($closeButtonSettings('colorbox1'), $allowedTags); ?>
</div>
<div class="ypm-themes-sub-options ypm-themes-sub-options-colorbox2 ypm-hide">
	<?php echo wp_kses($closeButtonSettings('colorbox2'), $allowedTags); ?>
</div>
<div class="ypm-themes-sub-options ypm-themes-sub-options-colorbox3 ypm-hide">
	<?php echo wp_kses($closeButtonSettings('colorbox3'), $allowedTags); ?>
</div>
<div class="ypm-themes-sub-options ypm-themes-sub-options-colorbox4 ypm-hide">
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-3">
			<label class="control-label" for="ypm-popup-theme-close-text"><?php esc_attr_e('Close text', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<input id="ypm-popup-theme-close-text" class="form-control" name="ypm-popup-theme-close-text" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-theme-close-text'));?>" >
		</div>
	</div>
</div>
<div class="ypm-themes-sub-options ypm-themes-sub-options-colorbox5 ypm-hide">
	<?php echo wp_kses($closeButtonSettings('colorbox5'), $allowedTags); ?>
</div>
<div class="ypm-themes-sub-options ypm-themes-sub-options-colorbox6 ypm-hide">
	<?php echo wp_kses($closeButtonSettings('colorbox6', 'close.png'), $allowedTags); ?>
</div>
<input name="ypm-theme-close-image-url" id="ypm-theme-close-image-url" type="hidden" value="<?php echo esc_attr($savedCloseImage);?>">

<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-popup-enable-popup-close-button-position"><?php esc_attr_e('Change close button position', 'popup_master');?>:</label>
		<?php echo ypm_info(esc_attr__('Customize close button position', 'popup_master')); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-popup-enable-popup-close-button-position" class="js-ypm-accordion" name="ypm-popup-enable-popup-close-button-position" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-enable-popup-close-button-position')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="row ypm-margin-bottom-15 ypm-sub-option">
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-popup-popup-close-position"><?php esc_attr_e('Position', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<?php
				echo YpmAdminHelper::selectBox($defaults['close-button-positions'], esc_attr($popupTypeObj->getOptionValue('ypm-popup-close-button-position')), array('name' => 'ypm-popup-close-button-position', 'class' => 'js-ypm-select js-close-button-positions')); ?>
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-3 ypm-popup-close-button-wrapper ypm-close-wrapper-top ypm-hide">
			<label for="ypm-popup-close-button-top"><?php esc_attr_e('Top',"popup_master" )?></label>
			<input name="ypm-popup-close-button-top" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-close-button-top'));?>" id="ypm-popup-close-button-top" class="form-control">
		</div>
		<div class="col-xs-3 ypm-popup-close-button-wrapper ypm-close-wrapper-right ypm-hide">
			<label for="ypm-popup-close-button-right"><?php esc_attr_e('Right',"popup_master" )?></label>
			<input name="ypm-popup-close-button-right" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-close-button-right'));?>" id="ypm-popup-close-button-right" class="form-control">
		</div>
		<div class="col-xs-3 ypm-popup-close-button-wrapper ypm-close-wrapper-bottom ypm-hide">
			<label for="ypm-popup-close-button-bottom"><?php esc_attr_e('Bottom',"popup_master" )?></label>
			<input name="ypm-popup-close-button-bottom" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-close-button-bottom'));?>" id="ypm-popup-close-button-bottom" class="form-control">
		</div>
		<div class="col-xs-3 ypm-popup-close-button-wrapper ypm-close-wrapper-left ypm-hide">
			<label for="ypm-popup-close-button-left"><?php esc_attr_e('Left','popup_master' )?></label>
			<input name="ypm-popup-close-button-left" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-close-button-left'));?>" id="ypm-popup-close-button-left" class="form-control">
		</div>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-popup-remove-borders"><?php esc_attr_e('Remove borders', 'popup_master');?>:</label>
		<?php echo ypm_info(esc_attr__('Allow remove borders', 'popup_master')); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-popup-remove-borders" name="ypm-popup-remove-borders" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-remove-borders')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>