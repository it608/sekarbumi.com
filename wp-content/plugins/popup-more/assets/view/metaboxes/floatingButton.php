<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
$position = $popupTypeObj->getOptionValue('ypm-popup-floating-position');
$getActiveClass = function ($current) use ($position) {
	$class = '';
	if ($current === $position) {
		$class = 'ypm-position-active';
	}

	return $class;
}
?>
<div class="ycf-bootstrap-wrapper">
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-3">
			<label class="control-label" for="ypm-popup-floating-enable"><?php esc_attr_e('Enable', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<label class="ypm-switch">
				<input type="checkbox" id="ypm-popup-floating-enable" name="ypm-popup-floating-enable" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-floating-enable'));?>>
				<span class="ypm-slider ypm-round"></span>
			</label>
			<br>
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-6">
			<label class="control-label" for="ypm-popup-floating-position"><?php esc_attr_e('Style type', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-6">
			<?php echo YpmFunctions::createSelectBox(array('corner' => 'Corner', 'button' => 'Button'), $popupTypeObj->getOptionValue('ypm-popup-floating-style'), array('name' => 'ypm-popup-floating-style', 'class' => 'js-basic-select form-control ypm-popup-floating-style')); ?>
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-12">
			<label class="control-label" for="ypm-popup-floating-position"><?php esc_attr_e('Position', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-12">
			<div class="ypm-positions-wrapper">
				<div class="ypm-positions-sub1">
					<input type="button" value="Top Left" data-value="top_left" class="ypm-position-button <?php echo esc_attr($getActiveClass('top_left'));?>">
					<input type="button" value="Top Center" data-value="top_center" class="ypm-position-button ypm-position-middle-button ypm-position-button-center <?php echo esc_attr($getActiveClass('top_center'));?>">
					<input type="button" value="Top Right" data-value="top_right" class="ypm-position-button <?php echo esc_attr($getActiveClass('top_right'));?>">
				</div>
				<div class="ypm-positions-sub2">
					<input type="button" value="Center Left" data-value="center_left" class="ypm-position-button ypm-position-button-center <?php echo esc_attr($getActiveClass('center_left'));?>">
					<input type="button" value="Center Center" disabled data-value="center_center" class="ypm-position-button ypm-position-middle-button">
					<input type="button" value="Center Right" data-value="center_right" class="ypm-position-button ypm-position-button-center <?php echo esc_attr($getActiveClass('center_right'));?>">
				</div>
				<div class="ypm-positions-sub2">
					<input type="button" value="Bottom Left" data-value="bottom_left" class="ypm-position-button <?php echo esc_attr($getActiveClass('bottom_left'));?>">
					<input type="button" value="Bottom Center" data-value="bottom_center" class="ypm-position-button ypm-position-middle-button ypm-position-button-center <?php echo esc_attr($getActiveClass('bottom_center'));?>">
					<input type="button" value="Bottom Right" data-value="bottom_right" class="ypm-position-button <?php echo esc_attr($getActiveClass('bottom_right'));?>">
				</div>
			</div>
			<input type="hidden" name="ypm-popup-floating-position" id="ypm-popup-floating-position" value="<?php echo esc_attr($position)?>">
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-md-6">
			<label class="control-label control-label" for="ypm-popup-floating-open-event"><?php esc_attr_e('Popup open event', 'popup_master');?>:</label>
		</div>
		<div class="col-md-6">
			<?php echo YpmFunctions::createSelectBox(array('click' => 'Click', 'hover' => 'Hover'), $popupTypeObj->getOptionValue('ypm-popup-floating-open-event'), array('name' => 'ypm-popup-floating-open-event', 'class' => 'js-basic-select form-control')); ?>
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-md-6">
			<label class="control-label control-label" for="ypm-popup-floating-font-size"><?php esc_attr_e('Font size', 'popup_master');?>:</label>
		</div>
		<div class="col-md-5">
			<input type="number" name="ypm-popup-floating-font-size" id="ypm-popup-floating-font-size" class="form-control ifrane-setting" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-floating-font-size')); ?>">
		</div>
		<div class="col-md-1">
			<span>px</span>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-popup-floating-bg-color" class=""><?php esc_attr_e('Background color', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6 ypm-option-wrapper">
			<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
				<input type="text" id="ypm-popup-floating-bg-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-popup-floating-bg-color" class="minicolors-input form-control js-ypm-time-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue("ypm-popup-floating-bg-color")); ?>">
			</div>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-popup-floating-text-color" class=""><?php esc_attr_e('Text color', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6 ypm-option-wrapper">
			<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
				<input type="text" id="ypm-popup-floating-text-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-popup-floating-text-color" class="minicolors-input form-control js-ypm-time-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue("ypm-popup-floating-text-color")); ?>">
			</div>
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-md-6">
			<label class="control-label control-label" for="ypm-popup-floating-text"><?php esc_attr_e('Text', 'popup_master');?>:</label>
		</div>
		<div class="col-md-6">
			<input type="text" name="ypm-popup-floating-text" id="ypm-popup-floating-text" class="form-control ifrane-setting" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-floating-text')); ?>">
		</div>
	</div>
	<div class="ypm-button-options">
		<div class="row ypm-margin-bottom-15">
			<div class="col-md-6">
				<label class="control-label control-label" for="ypm-popup-floating-border-radius"><?php esc_attr_e('Border radius', 'popup_master');?>:</label>
			</div>
			<div class="col-md-6">
				<input type="text" name="ypm-popup-floating-border-radius" id="ypm-popup-floating-border-radius" class="form-control ifrane-setting" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-floating-border-radius')); ?>">
			</div>
		</div>
		<div class="row ypm-margin-bottom-15">
			<div class="col-md-6">
				<label class="control-label control-label" for="ypm-popup-floating-border-status"><?php esc_attr_e('Enable border', 'popup_master');?>:</label>
			</div>
			<div class="col-md-6">
				<label class="ypm-switch">
					<input type="checkbox" id="ypm-popup-floating-border-status" class="js-ypm-accordion" name="ypm-popup-floating-border-status" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-floating-border-status')); ?>>
					<span class="ypm-slider ypm-round"></span>
				</label>
			</div>
		</div>
		<div class="ypm-accordion-content ypm-hide-content form-group">
			<div class="row ypm-margin-bottom-15">
				<div class="col-md-6">
					<label class="control-label control-label" for="ypm-popup-floating-border-width"><?php esc_attr_e('Border width', 'popup_master');?>:</label>
				</div>
				<div class="col-md-6">
					<input type="text" name="ypm-popup-floating-border-width" id="ypm-popup-floating-border-width" class="form-control ifrane-setting" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-floating-border-width')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6">
					<label for="ypm-popup-floating-border-color" class=""><?php esc_attr_e('Border color', 'popup_master'); ?></label>
				</div>
				<div class="col-md-6 ypm-option-wrapper">
					<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
						<input type="text" id="ypm-popup-floating-border-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-popup-floating-border-color" class="minicolors-input form-control js-ypm-time-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue("ypm-popup-floating-border-color")); ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row ypm-margin-bottom-15">
			<div class="col-md-6">
				<label class="control-label control-label" for="ypm-popup-floating-enable-hover"><?php esc_attr_e('Enable hover', 'popup_master');?>:</label>
			</div>
			<div class="col-md-6">
				<label class="ypm-switch">
					<input type="checkbox" id="ypm-popup-floating-enable-hover" class="js-ypm-accordion" name="ypm-popup-floating-enable-hover" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-floating-enable-hover')); ?>>
					<span class="ypm-slider ypm-round"></span>
				</label>
			</div>
		</div>
		<div class="ypm-accordion-content ypm-hide-content form-group">
			<div class="row form-group">
				<div class="col-md-6">
					<label for="ypm-popup-floating-hover-bg-color" class=""><?php esc_attr_e('Background color', 'popup_master'); ?></label>
				</div>
				<div class="col-md-6 ypm-option-wrapper">
					<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
						<input type="text" id="ypm-popup-floating-hover-bg-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-popup-floating-hover-bg-color" class="minicolors-input form-control js-ypm-time-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue("ypm-popup-floating-hover-bg-color")); ?>">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6">
					<label for="ypm-popup-floating-hover-text-color" class=""><?php esc_attr_e('Text color', 'popup_master'); ?></label>
				</div>
				<div class="col-md-6 ypm-option-wrapper">
					<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
						<input type="text" id="ypm-popup-floating-hover-text-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-popup-floating-hover-text-color" class="minicolors-input form-control js-ypm-time-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue("ypm-popup-floating-hover-text-color")); ?>">
					</div>
				</div>
			</div>
		</div>

	</div>
</div>