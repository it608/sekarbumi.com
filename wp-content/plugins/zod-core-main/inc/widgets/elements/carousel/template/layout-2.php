<?php

foreach( $settings['media'] as $key => $image ){
	$output .= sprintf(
		'
		<div class="zod-carousel__item">
			%1$s
		</div>
		',
		zod_core_render_widgets_gallery($image['id'])
	);
}