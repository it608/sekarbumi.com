<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="alert alert-info" role="alert">
	With age verification, user write their birthday day, month, and year, and if it's greater than the minimum age it's allowed to visit the page otherwise it's redirecting to the specified URL
</div>
<?php if(ypm_is_free()) : ?>
<div class="ypm-free-options-wrapper ypm-pro-options-wrapper ycf-pro-wrapper">
	<?php echo YpmUpgradeText('Upgrade Age Verification in PRO Version'); ?>
<?php endif; ?>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-min-age"><?php esc_attr_e('Minimum age', 'popup_master')?></label>
		</div>
		<div class="col-md-6">
			<input id="ypm-restriction-min-age" class="form-control" type="text" name="ypm-restriction-min-age" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-min-age'))?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-button-url"><?php esc_attr_e('Deny Redirect URL', 'popup_master')?></label>
		</div>
		<div class="col-md-6">
			<input id="ypm-restriction-button-url" placeholder="https://" class="form-control" type="text" name="ypm-restriction-button-url" value="<?php echo esc_url($popupTypeObj->getOptionValue('ypm-restriction-button-url'))?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-button-label"><?php esc_attr_e('Button label', 'popup_master')?></label>
		</div>
		<div class="col-md-6">
			<input id="ypm-restriction-button-label" class="form-control" type="text" name="ypm-restriction-button-label" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-label'))?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-button-width"><?php esc_attr_e('Button width', 'popup_master')?></label>
		</div>
		<div class="col-md-6">
			<input id="ypm-restriction-button-width" class="form-control" type="text" name="ypm-restriction-button-width" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-width'))?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-button-height"><?php esc_attr_e('Button height', 'popup_master')?></label>
		</div>
		<div class="col-md-6">
			<input id="ypm-restriction-button-height" class="form-control" type="text" name="ypm-restriction-button-height" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-height'))?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-button-font-size"><?php esc_attr_e('Font size', 'popup_master')?></label>
		</div>
		<div class="col-md-6">
			<input id="ypm-restriction-button-font-size" class="form-control" type="text" name="ypm-restriction-button-font-size" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-font-size'))?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-button-padding"><?php esc_attr_e('Padding', 'popup_master')?></label>
		</div>
		<div class="col-md-6">
			<input id="ypm-restriction-button-padding" class="form-control" type="text" name="ypm-restriction-button-padding" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-padding'))?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-button-border-radius"><?php esc_attr_e('Border radius', 'popup_master')?></label>
		</div>
		<div class="col-md-6">
			<input id="ypm-restriction-button-border-radius" class="form-control" type="text" name="ypm-restriction-button-border-radius" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-border-radius'))?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-button-bg-color" class=""><?php esc_attr_e('Background color', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6 ypm-option-wrapper">
			<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
				<input type="text" id="ypm-restriction-button-bg-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-restriction-button-bg-color" class=" minicolors-input ypm-minicolors" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-bg-color')); ?>">
			</div>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-button-text-color" class=""><?php esc_attr_e('Text color', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6 ypm-option-wrapper">
			<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
				<input type="text" id="ypm-restriction-button-text-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-restriction-button-text-color" class=" minicolors-input ypm-minicolors" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-text-color')); ?>">
			</div>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-button-enable-hover" class="ypm-label-of-switch"><?php esc_attr_e('Enable hover', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6">
			<label class="ypm-switch">
				<input type="checkbox" id="ypm-restriction-button-enable-hover" name="ypm-restriction-button-enable-hover" class="js-ypm-accordion js-ypm-time-status" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-enable-hover'));?>>
				<span class="ypm-slider ypm-round"></span>
			</label>
		</div>
	</div>
	<div class="ypm-accordion-content ypm-hide-content form-group">
		<div class="row form-group">
			<div class="col-md-6">
				<label for="ypm-restriction-button-hover-bg-color" class=""><?php esc_attr_e('Background color', 'popup_master'); ?></label>
			</div>
			<div class="col-md-6 ypm-option-wrapper">
				<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
					<input type="text" id="ypm-restriction-button-hover-bg-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-restriction-button-hover-bg-color" class=" minicolors-input ypm-minicolors" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-hover-bg-color')); ?>">
				</div>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label for="ypm-restriction-button-hover-text-color" class=""><?php esc_attr_e('Text color', 'popup_master'); ?></label>
			</div>
			<div class="col-md-6 ypm-option-wrapper">
				<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
					<input type="text" id="ypm-restriction-button-hover-text-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-restriction-button-hover-text-color" class=" minicolors-input ypm-minicolors" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-button-hover-text-color')); ?>">
				</div>
			</div>
		</div>
	</div>
<?php if(ypm_is_free()) : ?>
</div>
<?php endif; ?>