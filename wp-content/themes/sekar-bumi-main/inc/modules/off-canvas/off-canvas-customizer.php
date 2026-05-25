<?php
Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'        => 'color',
		'settings'    => 'off_canvas_toggle_color',
		'label'       => __( 'Off Canvas Toggle Color', 'sekarbumi' ),
		'section'     => 'sekarbumi_off_canvas',
		'default'     => '#000',
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'        => 'color',
		'settings'    => 'off_canvas_toggle_hover_color',
		'label'       => __( 'Off Canvas Toggle Hover Color', 'sekarbumi' ),
		'section'     => 'sekarbumi_off_canvas',
		'default'     => '#000',
	)
);

Kirki::add_field( 'sekarbumi_settings',
  array(
    'type'        => 'background',
    'settings'    => 'off_canvas_bg',
    'label'       => esc_html__( 'Off Canvas Background', 'sekarbumi' ),
    'section'     => 'sekarbumi_off_canvas',
    'default'     => array(
      'background-color'      => 'rgba(20,20,20,.8)',
      'background-image'      => '',
      'background-repeat'     => 'repeat',
      'background-position'   => 'center center',
      'background-size'       => 'cover',
      'background-attachment' => 'scroll',
    ),
  )
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'        => 'color',
		'settings'    => 'off_canvas_color',
		'label'       => __( 'Off Canvas Color', 'sekarbumi' ),
		'section'     => 'sekarbumi_off_canvas',
		'default'     => '#000',
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'        => 'color',
		'settings'    => 'off_canvas_hover_color',
		'label'       => __( 'Off Canvas Hover Color', 'sekarbumi' ),
		'section'     => 'sekarbumi_off_canvas',
		'default'     => '#d4d4d4',
	)
);