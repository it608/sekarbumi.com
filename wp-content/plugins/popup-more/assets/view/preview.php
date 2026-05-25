<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="ypm-live-preview" id="ypm-live-preview">
	<div class="ypm-live-preview-text">
		<h3><?php esc_attr_e('Live preview','popup_master')?></h3>
		<div class="ypm-toggle-icon ypm-toggle-icon-open"></div>
	</div>
	<div class="ypm-live-preview-content">
		<?php
		if(method_exists($popupTypeObj, 'renderLivePreview')) {
			$popupTypeObj->renderLivePreview();
		}
		?>
	</div>
</div>