<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
global $YpmDefaultsData;
$devices = $YpmDefaultsData['devices'];
$countries = $YpmDefaultsData['countries'];
?>
<div class="ycf-bootstrap-wrapper ypm-free-options-wrapper ypm-pro-options-wrapper ycf-pro-wrapper">
    <?php echo YpmUpgradeText('Upgrade Conditions in PRO Version'); ?>
	<div class="row ypm-margin-bottom-15">
		<div class="col-md-3">
			<label for="ypm-show-on-device-status"><?php esc_attr_e('Show on selected devices', 'popup_master')?></label>
		</div>
		<div class="col-md-4">
			<label class="ypm-switch">
				<input type="checkbox" id="ypm-show-on-device-status" name="ypm-show-on-device-status" class="js-ypm-accordion" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-show-on-device-status'))?>>
				<span class="ypm-slider ypm-round"></span>
			</label>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<?php esc_attr_e('Select device(s)', 'popup_master')?>
		</div>
		<div class="col-md-4">
			<?php echo YpmFunctions::createSelectBox($devices, $popupTypeObj->getOptionValue('ypm-devices'), array('name' => 'ypm-devices[]', 'multiple' => 'multiple')); ?>
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-md-3">
			<label for="ypm-selected-countries-status"><?php esc_attr_e('Popup for selected countries', 'popup_master'); ?></label>
		</div>
		<div class="col-md-4">
			<label class="ypm-switch">
				<input type="checkbox" id="ypm-selected-countries-status" name="ypm-selected-countries-status" class="js-ypm-accordion" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-selected-countries-status'))?>>
				<span class="ypm-slider ypm-round"></span>
			</label>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<?php esc_attr_e('selected countries', 'popup_master')?>
		</div>
		<div class="col-md-4">
			<?php echo YpmFunctions::createSelectBox($countries, $popupTypeObj->getOptionValue('ypm-selected-countries'), array('name' => 'ypm-selected-countries[]', 'multiple' => 'multiple', 'class'=>'js-basic-select form-control')); ?>
		</div>
	</div>
    <div class="row ypm-margin-bottom-15">
        <div class="col-md-3">
            <label for="ypm-selected-countries-status"><?php esc_attr_e('Popup for the User roles', 'popup_master'); ?></label>
        </div>
        <div class="col-md-4">
            <label class="ypm-switch">
                <input type="checkbox" id="ypm-selected-countries-status" name="ypm-selected-countries-status" >
                <span class="ypm-slider ypm-round"></span>
            </label>
        </div>
    </div>
    <div class="row ypm-margin-bottom-15">
        <div class="col-md-3">
            <label for="ypm-selected-countries-status"><?php esc_attr_e('Popup for the User status', 'popup_master'); ?></label>
        </div>
        <div class="col-md-4">
            <label class="ypm-switch">
                <input type="checkbox" id="ypm-selected-countries-status" name="ypm-selected-countries-status" >
                <span class="ypm-slider ypm-round"></span>
            </label>
        </div>
    </div>
    <div class="row ypm-margin-bottom-15">
        <div class="col-md-3">
            <label for="ypm-selected-countries-status"><?php esc_attr_e('Detect by URL', 'popup_master'); ?></label>
        </div>
        <div class="col-md-4">
            <label class="ypm-switch">
                <input type="checkbox" id="ypm-selected-countries-status" name="ypm-selected-countries-status" >
                <span class="ypm-slider ypm-round"></span>
            </label>
        </div>
    </div>
    <div class="row ypm-margin-bottom-15">
        <div class="col-md-3">
            <label for="ypm-selected-countries-status"><?php esc_attr_e('Show popup by Cookie', 'popup_master'); ?></label>
        </div>
        <div class="col-md-4">
            <label class="ypm-switch">
                <input type="checkbox" id="ypm-selected-countries-status" name="ypm-selected-countries-status" >
                <span class="ypm-slider ypm-round"></span>
            </label>
        </div>
    </div>
	<div class="row">
        <div class="col-md-3">
            <label for="ypm-selected-countries-status"><?php esc_attr_e('Show by Web Browser(s)', 'popup_master'); ?></label>
        </div>
        <div class="col-md-4">
            <label class="ypm-switch">
                <input type="checkbox" id="ypm-selected-countries-status" name="ypm-selected-countries-status" >
                <span class="ypm-slider ypm-round"></span>
            </label>
        </div>
    </div>
	<div class="row">
        <div class="col-md-3">
            <label for="ypm-woo-stock-status"><?php esc_attr_e('WooCommerce product stock status', 'popup_master'); ?></label>
        </div>
        <div class="col-md-4">
            <label class="ypm-switch">
                <input type="checkbox" id="ypm-woo-stock-status" name="" >
                <span class="ypm-slider ypm-round"></span>
            </label>
        </div>
    </div>
	<div class="row">
        <div class="col-md-3">
            <label for="ypm-woo-stock-status"><?php esc_attr_e('WooCommerce product stock quantity', 'popup_master'); ?></label>
        </div>
        <div class="col-md-4">
            <label class="ypm-switch">
                <input type="checkbox" id="ypm-woo-stock-status" name="" >
                <span class="ypm-slider ypm-round"></span>
            </label>
        </div>
    </div>

	<?php if(YPM_POPUP_PKG == YPM_POPUP_FREE): ?>
		<div class="ypm-pro-options">
		</div>
	<?php endif;?>
</div>