<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="ycf-bootstrap-wrapper">
	<div class="row ypm-margin-bottom-15">
		<div class="col-md-12">
			<label for="ypm-editor-css"><?php esc_attr_e('Custom CSS', 'popup_master')?></label>
			<textarea id="ypm-editor-css" rows="5" name="ypm-custom-css" class="widefat textarea"><?php echo esc_attr($popupTypeObj->getOptionValue('ypm-custom-css')); ?></textarea>
		</div>
	</div>
</div>