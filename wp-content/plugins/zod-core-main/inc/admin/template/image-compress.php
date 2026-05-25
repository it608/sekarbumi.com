<?php
$image_quality 		= isset( $settings['image_quality'] ) ? $settings['image_quality'] : 90;
$upload_convert_jpg = isset( $settings['upload_convert_jpg'] ) ? $settings['upload_convert_jpg'] : 0;
$imageSizes			= isset( $settings['disable_image_size'] ) ? $settings['disable_image_size'] : 0;
?>
<table class="form-table">
	<tr>
		<th>
			<label for="image_quality"><?php _e( 'Image Quality', 'zog-image-compress' ) ?></label> 
		</th>
		<td>
			<input type="number" min="1" max="100" step="1" name="zod_core_settings[image_quality]" placeholder="90" value="<?php echo esc_attr( $image_quality ); ?>"> %
		</td>
	</tr>
	<tr>
		<th>
			<label for="upload_convert_jpg"><?php _e( 'Convert PNG to JPEG during upload', 'zog-image-compress' ) ?></label> 
		</th>
		<td>
			<?php
			zod_core_select(array(
				'name' => 'zod_core_settings[upload_convert_jpg]',
				'id' => 'zic_field_2',
				'value' => esc_attr( $upload_convert_jpg ),
				'options' => array(
					0 => __('No'),
					1 => __('Yes'),
					2 => __( 'Yes, but only images without transparency', 'zog-image-compress' )
				)
			));
			?>
		</td>
	</tr>
	<tr>
		<th>
			<label for="disable_image_size"><?php _e( 'Disable Image Sizes', 'zog-image-compress' ) ?></label> 
		</th>
		<td>
			<?php
				zod_core_select(array(
					'name' => 'zod_core_settings[disable_image_size]',
					'id' => 'zic_field_4',
					'value' => esc_attr( $imageSizes ),
					'options' => array(
						0 => __('No'),
						1 => __('Yes'),
					)
				));
			?>
		</td>
	</tr>
</table>