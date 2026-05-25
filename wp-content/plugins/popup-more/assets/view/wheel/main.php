
<?php
//https://chatgpt.com/c/67b5ab07-5cbc-8007-b130-0f75042774b6
use \YpmAdminHelper as AdminHelper;

$options = $popupTypeObj->getOptionValue('ypm-wheeloptions');

$optionsRender = new WheelOptionsRenderer($options);
$allowed_html = AdminHelper::getAllowedTags();
?>
<div class="ycf-bootstrap-wrapper ypm-settings-wrapper">
    <!-- Wheel Options Section -->
    <div class="ypm-section-builder">
        <h2 class="ypm-title">Wheel Options</h2>
        <ul id="ypm-sliceList" class="ypm-slice-list">
            <?php echo wp_kses($optionsRender->render(), $allowed_html); ?>
        </ul>
    </div>

    <!-- General Settings Section -->
    <div class="ypm-section-general">
        <h3 class="ypm-subtitle">General Settings</h3>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-sound" class="ypm-option-label">
                    <?php esc_attr_e('Enable Wheel Spin Sound', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <label class="ypm-switch">
                    <input type="checkbox" id="ypm-wheel-sound" name="ypm-wheel-sound" class="js-ypm-accordion" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-sound')) ?>>
                    <span class="ypm-slider ypm-round"></span>
                </label>
            </div>
        </div>
        <div class="ypm-accordion-content ypm-hide-content">
            <div class="row form-group">
				<div class="col-md-5">
					<input id="js-upload-sound-button" class="button js-upload-sound-button" type="button" value="<?php _e('Select sound', 'popup_master')?>" data-element="#ypm-wheel-win-sound-url">
					<input type="button" data-default-song="<?php echo esc_attr(YPM_POPUP_SOUNDS_URL."tick.mp3"); ?>" id="js-reset-to-click-sound" class="btn btn-sm btn-danger" value="<?php _e('Reset', YRM_LANG); ?>"> 
				</div>
				
				<div class="col-md-5 ycd-circles-width-wrapper">
					<input type="url" name="ypm-wheel-sound-url" id="ypm-wheel-sound-url" class="form-control" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-sound-url')); ?>">
				</div>
				<div class="col-md-1">
					<span class="dashicons dashicons-controls-volumeon js-preview-button-click-sound" data-element="#ypm-wheel-sound-url"></span>
				</div>
        	</div>
        </div>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-win-sound" class="ypm-option-label">
                    <?php esc_attr_e('Enable Wheel Win Sound', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <label class="ypm-switch">
                    <input type="checkbox" id="ypm-wheel-win-sound" name="ypm-wheel-win-sound" class="js-ypm-accordion" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-win-sound')) ?>>
                    <span class="ypm-slider ypm-round"></span>
                </label>
            </div>
        </div>
        <div class="ypm-accordion-content ypm-hide-content">
            <div class="row form-group">
				<div class="col-md-5">
					<input id="js-upload-sound-button" class="button js-upload-sound-button" type="button" value="<?php _e('Select sound', 'popup_master')?>" data-element="#ypm-wheel-win-sound-url">
					<input type="button" data-default-song="<?php echo esc_attr(YPM_POPUP_SOUNDS_URL."winner.mp3"); ?>" id="js-reset-to-click-sound" class="btn btn-sm btn-danger" value="<?php _e('Reset', YRM_LANG); ?>"> 
				</div>
				
				<div class="col-md-5 ycd-circles-width-wrapper">
					<input type="url" name="ypm-wheel-win-sound-url" id="ypm-wheel-win-sound-url" class="form-control" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-win-sound-url')); ?>">
				</div>
				<div class="col-md-1">
					<span class="dashicons dashicons-controls-volumeon js-preview-button-click-sound" data-element="#ypm-wheel-win-sound-url"></span>
				</div>
        	</div>
        </div>
    </div>
    <div class="ypm-section-general">
        <h3 class="ypm-subtitle">Button Settings</h3>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-button-width" class="ypm-option-label">
                    <?php esc_attr_e('Width', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <input type="text" name="ypm-wheel-button-width" placeholder="Width ex 10px" id="ypm-wheel-button-width" class="form-control" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-button-width')); ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-button-height" class="ypm-option-label">
                    <?php esc_attr_e('Height', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <input type="text" name="ypm-wheel-button-height" placeholder="Height ex 10px" id="ypm-wheel-button-height" class="form-control" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-button-height')); ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-button-font-size" class="ypm-option-label">
                    <?php esc_attr_e('Font size', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <input type="text" name="ypm-wheel-button-font-size" placeholder="Font size ex 18px" id="ypm-wheel-button-font-size" class="form-control" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-button-font-size')); ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-button-padding" class="ypm-option-label">
                    <?php esc_attr_e('Padding', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <input type="text" name="ypm-wheel-button-padding" placeholder="Ex 18px" id="ypm-wheel-button-paddin" class="form-control" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-button-padding')); ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-button-border-radius" class="ypm-option-label">
                    <?php esc_attr_e('Border Radius', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <input type="text" name="ypm-wheel-button-border-radius" placeholder="Ex 10px" id="ypm-wheel-button-border-radius" class="form-control" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-button-border-radius')); ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-button-title" class="ypm-option-label">
                    <?php esc_attr_e('Title', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <input type="text" name="ypm-wheel-button-title" id="ypm-wheel-button-title" class="form-control" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-button-title')); ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-button-color" class="ypm-option-label">
                    <?php esc_attr_e('Color', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <input type="color" name="ypm-wheel-button-color" value="<?php esc_attr_e($popupTypeObj->getOptionValue('ypm-wheel-button-color'))?>" class="ypm-type-color" />
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-button-bg-color" class="ypm-option-label">
                    <?php esc_attr_e('Background Color', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <input type="color" name="ypm-wheel-button-bg-color" id="ypm-wheel-button-bg-color" value="<?php esc_attr_e($popupTypeObj->getOptionValue('ypm-wheel-button-bg-color'))?>" class="ypm-type-color" />
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-btn-hover" class="ypm-option-label">
                    <?php esc_attr_e('Enable hover colors', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <label class="ypm-switch">
                    <input type="checkbox" id="ypm-wheel-btn-hover" name="ypm-wheel-btn-hover" class="js-ypm-accordion" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-wheel-btn-hover')) ?>>
                    <span class="ypm-slider ypm-round"></span>
                </label>
            </div>
        </div>
        <div class="ypm-accordion-content ypm-hide-content">
            <div class="row form-group">
                <div class="col-md-5">
                    <label for="ypm-wheel-button-hover-color" class="ypm-option-label">
                        <?php esc_attr_e('Color', 'popup_master'); ?>:
                    </label>
                </div>
                <div class="col-md-5">
                    <input type="color" name="ypm-wheel-button-hover-color" value="<?php esc_attr_e($popupTypeObj->getOptionValue('ypm-wheel-button-hover-color'))?>" class="ypm-type-color" />
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-5">
                    <label for="ypm-wheel-button-hover-bg-color" class="ypm-option-label">
                        <?php esc_attr_e('Background Color', 'popup_master'); ?>:
                    </label>
                </div>
                <div class="col-md-5">
                    <input type="color" name="ypm-wheel-button-hover-bg-color" value="<?php esc_attr_e($popupTypeObj->getOptionValue('ypm-wheel-button-hover-bg-color'))?>" class="ypm-type-color" />
                </div>
            </div>
        </div>
    </div>
    <div class="ypm-section-general">
        <h3 class="ypm-subtitle">Arrow Settings</h3>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-arrow-size" class="ypm-option-label">
                    <?php esc_attr_e('Arrow Size', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <?php 
                    $options = YpmAdminHelper::selectBox(array('0.5' => 'Small', '1' => 'Normal', '2' => 'Large', '4' => 'Extra large'), esc_attr($popupTypeObj->getOptionValue('ypm-wheel-arrow-size')), array('name' => 'ypm-wheel-arrow-size', 'class' => 'js-ycd-select'));
                    echo wp_kses($options, YpmAdminHelper::getAllowedTags())
                ?>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-5">
                <label for="ypm-wheel-arrow-color" class="ypm-option-label">
                    <?php esc_attr_e('Arrow color', 'popup_master'); ?>:
                </label>
            </div>
            <div class="col-md-5">
                <input type="color" name="ypm-wheel-arrow-color" value="<?php esc_attr_e($popupTypeObj->getOptionValue('ypm-wheel-arrow-color'))?>" class="ypm-type-color" />
            </div>
        </div>
    </div>
</div>
