<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="row form-group">
    <div class="col-md-6">
        <label class="ypm-label-of-switch"><?php esc_attr_e('Deny button', 'popup_master')?></label>
    </div>
    <div class="col-md-6">
    </div>
</div>
<div class="row ypm-margin-bottom-15 ypm-sub-option">
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-deny-enable-dimension" class="ypm-label-of-switch"><?php esc_attr_e('Enable custom dimension', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6">
			<label class="ypm-switch">
				<input type="checkbox" id="ypm-restriction-deny-enable-dimension" name="ypm-restriction-deny-enable-dimension" class="js-ypm-accordion js-ypm-time-status" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-enable-dimension'));?>>
				<span class="ypm-slider ypm-round"></span>
			</label>
		</div>
	</div>
	<div class="ypm-accordion-content ypm-hide-content form-group">
		<div class="row form-group">
			<div class="col-md-6">
				<label for="ypm-restriction-deny-width"><?php esc_attr_e('Width', 'popup_master')?></label>
			</div>
			<div class="col-md-6">
				<input id="ypm-restriction-deny-width" class="form-control" type="text" name="ypm-restriction-deny-width" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-width'))?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label for="ypm-restriction-deny-height"><?php esc_attr_e('Height', 'popup_master')?></label>
			</div>
			<div class="col-md-6">
				<input id="ypm-restriction-deny-height" class="form-control" type="text" name="ypm-restriction-deny-height" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-height'))?>">
			</div>
		</div>
	</div>
    <div class="row form-group">
        <div class="col-md-6">
            <label for="ypm-restriction-deny-label"><?php esc_attr_e('Label', 'popup_master')?></label>
        </div>
        <div class="col-md-6">
            <input id="ypm-restriction-deny-label" class="form-control" type="text" name="ypm-restriction-deny-label" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-label'))?>">
        </div>
    </div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-deny-url"><?php esc_attr_e('URL', 'popup_master')?></label>
		</div>
		<div class="col-md-6">
			<input id="ypm-restriction-deny-url" placeholder="https://" class="form-control" type="text" name="ypm-restriction-deny-url" value="<?php echo esc_url($popupTypeObj->getOptionValue('ypm-restriction-deny-url'))?>">
		</div>
	</div>
    <div class="row form-group">
        <div class="col-md-6">
            <label for="ypm-restriction-deny-font-size"><?php esc_attr_e('Font size', 'popup_master')?></label>
        </div>
        <div class="col-md-6">
            <input id="ypm-restriction-deny-font-size" class="form-control" type="text" name="ypm-restriction-deny-font-size" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-font-size'))?>">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-6">
            <label for="ypm-restriction-deny-padding"><?php esc_attr_e('Padding', 'popup_master')?></label>
        </div>
        <div class="col-md-6">
            <input id="ypm-restriction-deny-padding" class="form-control" type="text" name="ypm-restriction-deny-padding" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-padding'))?>">
        </div>
    </div>
	<div class="row form-group">
        <div class="col-md-6">
            <label for="ypm-restriction-deny-border-radius"><?php esc_attr_e('Border radius', 'popup_master')?></label>
        </div>
        <div class="col-md-6">
            <input id="ypm-restriction-deny-border-radius" class="form-control" type="text" name="ypm-restriction-deny-border-radius" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-border-radius'))?>">
        </div>
    </div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-deny-bg-color" class=""><?php esc_attr_e('Background color', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6 ypm-option-wrapper">
			<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
				<input type="text" id="ypm-restriction-deny-bg-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-restriction-deny-bg-color" class=" minicolors-input ypm-minicolors" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-bg-color')); ?>">
			</div>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-deny-text-color" class=""><?php esc_attr_e('Text color', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6 ypm-option-wrapper">
			<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
				<input type="text" id="ypm-restriction-deny-text-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-restriction-deny-text-color" class=" minicolors-input ypm-minicolors" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-text-color')); ?>">
			</div>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-restriction-deny-enable-hover" class="ypm-label-of-switch"><?php esc_attr_e('Enable hover', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6">
			<label class="ypm-switch">
				<input type="checkbox" id="ypm-restriction-deny-enable-hover" name="ypm-restriction-deny-enable-hover" class="js-ypm-accordion js-ypm-time-status" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-enable-hover'));?>>
				<span class="ypm-slider ypm-round"></span>
			</label>
		</div>
	</div>
	<div class="ypm-accordion-content ypm-hide-content form-group">
		<div class="row form-group">
			<div class="col-md-6">
				<label for="ypm-restriction-deny-hover-bg-color" class=""><?php esc_attr_e('Background color', 'popup_master'); ?></label>
			</div>
			<div class="col-md-6 ypm-option-wrapper">
				<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
					<input type="text" id="ypm-restriction-deny-hover-bg-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-restriction-deny-hover-bg-color" class=" minicolors-input ypm-minicolors" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-hover-bg-color')); ?>">
				</div>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label for="ypm-restriction-deny-hover-text-color" class=""><?php esc_attr_e('Text color', 'popup_master'); ?></label>
			</div>
			<div class="col-md-6 ypm-option-wrapper">
				<div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
					<input type="text" id="ypm-restriction-deny-hover-text-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-restriction-deny-hover-text-color" class=" minicolors-input ypm-minicolors" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-restriction-deny-hover-text-color')); ?>">
				</div>
			</div>
		</div>
	</div>
</div>