<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="ycf-bootstrap-wrapper">
	<div class="row form-group">
		<div class="col-md-3">
			<label for="ypm-subscription-send-to-email"><?php esc_attr_e('To', 'popup_master')?></label>
		</div>
		<div class="col-md-5">
			<input type="email" class="ypm-subscription-form-width form-control" id="ypm-subscription-send-to-email" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-send-to-email'));?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-3">
			<label for="ypm-subscription-send-from-email"><?php esc_attr_e('From', 'popup_master')?></label>
		</div>
		<div class="col-md-5">
			<input type="email" class="ypm-subscription-form-width form-control" id="ypm-subscription-send-from-email" name="ypm-subscription-send-from-email" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-send-from-email'));?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-3">
			<label for="ypm-subscription-send-email-subject"><?php esc_attr_e('Subject', 'popup_master')?></label>
		</div>
		<div class="col-md-5">
			<input type="text" class="ypm-subscription-form-width form-control" id="ypm-subscription-send-email-subject" name="ypm-subscription-send-email-subject" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-subscription-send-email-subject'));?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-3">
			<label for="ypm-subscription-send-email-subject"><?php esc_attr_e('Message', 'popup_master')?></label>
		</div>
		<div class="col-md-5">
			<?php
			$content = $popupTypeObj->getOptionValue('ypm-subscription-message');
			$editorId = 'ypm-subscription-message';
			$settings = array(
				'wpautop' => false,
				'tinymce' => array(
					'width' => '100%',
				),
				'textarea_rows' => '6',
				'media_buttons' => true
			);
			wp_editor($content, $editorId, $settings);
			?>
		</div>
	</div>
</div>