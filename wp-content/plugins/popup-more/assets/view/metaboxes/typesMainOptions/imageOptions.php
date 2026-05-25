<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="ycf-bootstrap-wrapper ypm-image-options-wrapper">
	<h3 class="ypm-image-header-text"><?php esc_attr_e('Please choose your picture', 'popup_master')?></h3>
	<div class="ypm-upload-wrapper">
		<input name="ypm-image-popup-url" id="ypm-image-popup-url" class="form-control ypm-image-popup-url" value="<?php echo esc_attr($typeObj->getOptionValue('ypm-image-popup-url'));?>" placeholder="<?php esc_attr_e("Image URL", 'popup_master')?>" required><button class="btn btn-primary ypm-upload-button"><?php esc_attr_e('Upload image', 'popup_master')?></button>
	</div>

	<div class="ypm-show-image-container">
		<span class="ypm-no-image">(No image selected)</span>
	</div>

</div>

