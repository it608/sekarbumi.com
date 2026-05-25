<?php
$ga_code = isset( $settings['zod_ga_code'] ) ? $settings['zod_ga_code'] : '';
$fbp_code = isset( $settings['zod_fbp_code'] ) ? $settings['zod_fbp_code'] : '';
?>
<table class="form-table">
	<tr>
		<th>
			<label for="zod_ga_field"><?php _e( 'Google Analytics Code', 'zog-image-compress' ) ?></label> 
		</th>
		<td>
            <input type="text" id="zod-ga-field" name="zod_core_settings[zod_ga_code]" value="<?php echo esc_html( $ga_code ); ?>">
		</td>
	</tr>
	<tr>
		<th>
			<label for="zod_fbp_field"><?php _e( 'Facebook Pixel Code', 'zog-image-compress' ) ?></label> 
		</th>
		<td>
            <input type="text" id="zod-fbp-field" name="zod_core_settings[zod_fbp_code]" value="<?php echo esc_html( $fbp_code ); ?>">
		</td>
	</tr>
</table>