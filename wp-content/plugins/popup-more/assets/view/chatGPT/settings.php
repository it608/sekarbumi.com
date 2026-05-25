<div class="ycf-bootstrap-wrapper">
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ypm-ai-api-key"><?php esc_attr_e('API key', 'popup_master')?></label>
        </div>
        <div class="col-md-4">
            <input id="ypm-ai-api-key" class="form-control" type="text" name="ypm-ai-api-key" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-ai-api-key'))?>">
            <a href="https://platform.openai.com/api-keys" target="_blank">Get API key</a>
            <div class="ypm-api-checker-wrapper ypm-hide"></div>
        </div>
        <div class="col-md-4">
            <button class="ypm-check-api-key btn btn-primary">Check key</button>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ypm-ai-disable-save-message"><?php esc_attr_e('Disabel save messages', 'popup_master')?></label>
        </div>
        <div class="col-md-4">
            <label class="ypm-switch">
                <input type="checkbox" id="ypm-ai-disable-save-message" name="ypm-ai-disable-save-message" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-ai-disable-save-message')); ?>>
                <span class="ypm-slider ypm-round"></span>
            </label>
        </div>
    </div>
    <?php if(ypm_is_free()): ?>
    <div class="ycf-bootstrap-wrapper ypm-free-options-wrapper ypm-pro-options-wrapper ycf-pro-wrapper">
        <?php echo YpmUpgradeText('Upgrade to PRO Version'); ?>
    <?php endif?>
        <div class="row form-group">
			<div class="col-md-6">
				<label><?php esc_attr_e('Settings', 'popup_master')?></label>
			</div>
			<div class="col-md-6">
			</div>
		</div>
        <div class="ypm-sub-section">
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="ypm-ai-voice-enable"><?php esc_attr_e('Enable Voice', 'popup_master')?></label>
                </div>
                <div class="col-md-6">
                    <label class="ypm-switch">
                        <input type="checkbox" id="ypm-ai-voice-enable" class="js-ypm-accordion" name="ypm-ai-voice-enable" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-ai-voice-enable')); ?>>
                        <span class="ypm-slider ypm-round"></span>
                    </label>
                </div>
            </div>
           
        </div>
        <div class="row form-group">
			<div class="col-md-6">
				<label for="ypm-ai-button-label"><?php esc_attr_e('Button', 'popup_master')?></label>
			</div>
			<div class="col-md-6">
			</div>
		</div>
        <div class="ypm-sub-section">
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="ypm-ai-button-label"><?php esc_attr_e('label', 'popup_master')?></label>
                </div>
                <div class="col-md-6">
                    <input id="ypm-ai-button-label" class="form-control" type="text" name="ypm-ai-button-label" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-ai-button-label'))?>">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="ypm-ai-button-bg-color"><?php esc_attr_e('background color', 'popup_master')?></label>
                </div>
                <div class="col-md-6">
                    <div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
                        <input type="text" id="ypm-ai-button-bg-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-ai-button-bg-color" class=" minicolors-input ypm-minicolors" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-ai-button-bg-color')); ?>">
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="ypm-ai-button-color"><?php esc_attr_e('color', 'popup_master')?></label>
                </div>
                <div class="col-md-6">
                    <div class="minicolors minicolors-theme-default minicolors-position-bottom minicolors-position-left">
                        <input type="text" id="ypm-ai-button-color" placeholder="<?php esc_attr_e('Select color', 'popup_master')?>" name="ypm-ai-button-color" class=" minicolors-input ypm-minicolors" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-ai-button-color')); ?>">
                    </div>
                </div>
            </div>
        </div>
        
    <?php if(ypm_is_free()): ?>
        <div class="ypm-pro-options"></div>
    </div>
    <?php endif?>
</div>