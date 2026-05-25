<?php

if( !function_exists( 'zod_widget_date' ) ){
	function zod_widget_date($atts) {

		$output = '';

		$default = array(
			'options' => 'year'
		);
		
		$value = shortcode_atts( $default, $atts );
		
		if( isset( $value['options'] ) ){
			switch( $value['options'] ) {
				case 'year':
					$output = sprintf(
						'
						%1$s
						',
						wp_date( 'Y' )
					);
					break;
				case 'month':
					$output = sprintf(
						'
						%1$s
						',
						wp_date( 'm' )
					);
					break;
				case 'date':
					$output = sprintf(
						'
						%1$s
						',
						wp_date( 'd' )
					);
					break;
				default:
					$output = sprintf(
						'
						%1$s
						',
						wp_date( 'Y' )
					);
					break;
			}
		}
		
		return apply_filters( 'zod_widget_date', $output );
	}
	
	add_shortcode('zod_date', 'zod_widget_date');
}
