<?php
if( !function_exists( 'zod_core_select' ) ){
	function zod_core_select( $field_data = array(), $is_echo = true, $cols = array( 'value' => 'ID', 'text' => 'post_title' ) ){
		if( ! is_object( $field_data ) ) $field_data = (object)$field_data;
		$field_data->value = is_array( $field_data->value ) ? $field_data->value : array( $field_data->value );
		$select = sprintf(
			'<select name="%s" id="%s" %s %s>',
			$field_data->name,
			$field_data->id,
			isset( $field_data->multiple ) ? 'multiple' : '',
			isset( $field_data->size ) ? 'size="' . $field_data->size . '"' : ''
		);
		if( isset( $field_data->placeholder ) ){
			$select .= '<option value="" disabled>' . $field_data->placeholder . '</option>';
		}
		foreach( $field_data->options as $option => $value ){
			if( isset( $value->ID ) || isset( $value->term_id ) ){
				$post_id = isset( $value->ID ) ? $value->ID : $value->term_id;
				$value = (array)$value;
				$select .= sprintf(
					'<option value="%s" %s>%s</option>',
					$value[ $cols['text'] ],
					in_array( $value[ $cols['text'] ] , $field_data->value ) ? 'selected' : '',
					$value[ $cols['value'] ]
				);
			}else{
				$select .= sprintf(
					'<option value="%s" %s>%s</option>',
					$option,
					in_array( $option, $field_data->value ) ? 'selected' : '',
					$value
				);
			}
		}
		$select .= '</select>';
		
		if( $is_echo ){
			echo apply_filters( 'zod_core_select', $select );
        } else {
			return apply_filters( 'zod_core_select', $select );
        }
	}
}

if( !function_exists( 'zod_core_checkbox' ) ){
    function zod_core_checkbox( $options = array() ){
        $value = get_option( $name );
        $checkbox = sprintf(
            '<input type="checkbox" name="%1$s" %2$s/>',
            esc_attr( 'zod_core_settings[$name]' ),
            esc_attr( checked( $value, 'on' ) )
        );

        return apply_filters( 'zod_core_checkbox', $checkbox );
    }
}

if( !function_exists('zod_core_render_widgets_gallery') ){
    function zod_core_render_widgets_gallery( $media ){
        if( !empty( $media ) ){
			$image = sprintf(
				'
				%1$s
				',
				wp_get_attachment_image( $media, 'full' )
			);
		} else {
			$image = sprintf(
				'
				<img src="%1$s" width="480" height="480" alt="Elementor Placeholder" />
				',
				esc_url( \Elementor\Utils::get_placeholder_image_src() )
			);
		}

        return $image;
    }
}

if( !function_exists('zod_core_render_widgets_image') ){
    function zod_core_render_widgets_image( $media ){
      if( isset( $media['id']) && !empty( $media['id'] ) ){
        $image = sprintf(
          '
          %1$s
          ',
          wp_get_attachment_image( $media['id'], 'full' )
        );
      } else {
        $image = sprintf(
          '
          <img width="480" height="480" src="%1$s" />
          ',
          esc_url( \Elementor\Utils::get_placeholder_image_src() )
        );
      }
      return $image;
    }
}

if( !function_exists('zod_core_include_file') ){
	function zod_core_include_file( $filePath = '', $value = array(), $isEcho = true ){
		if( $isEcho ) {
			extract( $value );
			include( $filePath );
		} else {
			ob_start();
				extract( $value );
				include( $filePath );
			return ob_get_clean();
		}
	}
}