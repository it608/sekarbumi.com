<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<form class="form-horizontal">


<div class="ycf-bootstrap-wrapper">
	<?php
	if ($_GET['post']) {
		echo YpmAdminHelper::createTypePopupNotice(YPM_IFRAME_POST_TYPE, (int)sanitize_text_field($_GET['post']));
	}
	?>
	<div class="row">
		<div class="col-md-6">
			<div class="row ypm-margin-bottom-15">
				<div class="col-md-6">
					<label class="control-label control-label "col-md-6" for="iframe-url"><?php esc_attr_e('Ifrane URL', 'popup_master');?>:</label>
				</div>
				<div class="col-md-6">
					<input type="url" name="ypm-iframe-url" id="iframe-url" class="form-control ifrane-setting" value="<?php echo esc_url($popupTypeObj->getOptionValue('ypm-iframe-url')); ?>">
				</div>
			</div>
			<div class="row ypm-margin-bottom-15">
				<div class="col-md-6">
					<label class="control-label" for="iframe-width"><?php esc_attr_e('Width', 'popup_master');?>:</label>
				</div>
				<div class="col-md-6">
					<input type="text" name="ypm-iframe-width" id="iframe-width" class="form-control ifrane-setting" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-iframe-width')); ?>">
				</div>
			</div>
			<div class="row ypm-margin-bottom-15">
				<div class="col-md-6">
					<label class="control-label" for="iframe-height"><?php esc_attr_e('Height', 'popup_master');?>:</label>
				</div>
				<div class="col-md-6">
					<input type="text" name="ypm-iframe-height" id="iframe-height" class="form-control ifrane-setting" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-iframe-width')); ?>">
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<h3 style="text-align: center;">Live preview</h3>
			<div class="iframe-preview-wrapper"></div>
		</div>
	</div>
</div>