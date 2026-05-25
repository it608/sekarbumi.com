<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="ypm-free-options-wrapper ypm-pro-options-wrapper ycf-pro-wrapper">
	<div class="row">
		<div class="col-md-12">
			<?php echo YpmUpgradeText('Upgrade Events in PRO Version') ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 ypm-free-condition-wrapper">
			<label><?php esc_attr_e('Event', 'popup_master')?></label>
			<select class="js-ypm-select" disabled>
				<option>Scroll</option>
			</select>
		</div>
		<div class="col-md-3 ypm-free-condition-wrapper">
			<label><?php esc_attr_e('Condition', 'popup_master')?></label>
			<select class="js-ypm-select" disabled>
				<option>Distance from top</option>
			</select>
		</div>
		<div class="col-md-3 ypm-free-condition-wrapper">
			<label><?php esc_attr_e('Distance', 'popup_master'); ?></label>
			<input class="form-control" placeholder="10px or 10%" disabled>
		</div>
		<div class="col-md-3">
			<label><?php esc_attr__('Condition', 'popup_master')?></label>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 ypm-free-condition-wrapper">
			<label><?php esc_attr_e('Event', 'popup_master')?></label>
			<select class="js-ypm-select" disabled>
				<option>Exit intent</option>
			</select>
		</div>
		<div class="col-md-3 ypm-free-condition-wrapper">
			<label><?php esc_attr_e('Mode', 'popup_master')?></label>
			<select class="js-ypm-select" disabled>
				<option>Soft</option>
			</select>
		</div>
		<div class="col-md-3 ypm-free-condition-wrapper">
		</div>
		<div class="col-md-3">
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 ypm-free-condition-wrapper">
			<label><?php esc_attr_e('Event', 'popup_master')?></label>
			<select class="js-ypm-select" disabled>
				<option>Inactivity</option>
			</select>
		</div>
		<div class="col-md-3 ypm-free-condition-wrapper">
			<label><?php esc_attr_e('Duration', 'popup_master')?></label>
			<input class="form-control" placeholder="Duration in seconds" disabled>
		</div>
		<div class="col-md-3 ypm-free-condition-wrapper">
		</div>
		<div class="col-md-3">
			<label><?php esc_attr__('Condition', 'popup_master')?></label>
		</div>
	</div>
	<?php if(YPM_POPUP_PKG == YPM_POPUP_FREE): ?>
		<div class="ypm-pro-options">
		</div>
	<?php endif;?>
</div>