<?php
$val_404        = isset( $settings['zod_404'] ) ? $settings['zod_404'] == 'on' ? 'checked' : '' : '';
$redirect_link  = isset( $settings['zod_redirect_link'] ) ? $settings['zod_redirect_link'] == 'on' ? 'checked' : '' : '';
$image_compress = isset( $settings['zod_image_compress'] ) ? $settings['zod_image_compress'] == 'on' ? 'checked' : '' : '';
$tracking_code  = isset( $settings['zod_tracking_code'] ) ? $settings['zod_tracking_code'] == 'on' ? 'checked' : '' : '';
?>

<table class="form-table">
	<tr>
		<th>
			<label for="zod_core_settings[zod_404]"><?php _e( '404', 'zod-core' ) ?></label> 
		</th>
		<td>
      <input type="checkbox" id="zod-404" class="zod-core-switch" name="zod_core_settings[zod_404]" <?php esc_attr_e( $val_404 ); ?>>
      <label for="zod-404" class="zod-core-toggle">Toggle</label>
		</td>
	</tr>
	<tr>
		<th>
			<label for="zod_core_settings[zod_redirect_link]"><?php _e( 'Redirect Link', 'zod-core' ) ?></label> 
		</th>
		<td>
      <input type="checkbox" id="zod-redirect-link" class="zod-core-switch" name="zod_core_settings[zod_redirect_link]" <?php esc_attr_e( $redirect_link ); ?>>
      <label for="zod-redirect-link" class="zod-core-toggle">Toggle</label>
		</td>
	</tr>
	<tr>
		<th>
			<label for="zod_core_settings[zod_image_compress]"><?php _e( 'Image Compress', 'zod-core' ) ?></label> 
		</th>
		<td>
      <input type="checkbox" id="zod-image-compress" class="zod-core-switch" name="zod_core_settings[zod_image_compress]" <?php esc_attr_e( $image_compress ); ?>>
      <label for="zod-image-compress" class="zod-core-toggle">Toggle</label>
		</td>
	</tr>
	<tr>
		<th>
			<label for="zod_core_settings[zod_tracking_code]"><?php _e( 'Tracking Code', 'zod-core' ) ?></label> 
		</th>
		<td>
      <input type="checkbox" id="zod-tracking-code" class="zod-core-switch" name="zod_core_settings[zod_tracking_code]" <?php esc_attr_e( $tracking_code ); ?>>
      <label for="zod-tracking-code" class="zod-core-toggle">Toggle</label>
		</td>
	</tr>
</table>