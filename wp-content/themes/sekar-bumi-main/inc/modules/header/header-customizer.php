<?php
Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'		  => 'background',
		'settings'	=> 'header_bg',
		'label'		  => esc_html__( 'Header Background', 'sekarbumi' ),
		'section'	  => 'sekarbumi_header_section',
		'default'	  => array(
			'background-color'      => 'rgba(20,20,20,.8)',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'cover',
			'background-attachment' => 'scroll',
		),
		'priority'	=> 60,
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'		  => 'color',
		'settings'	=> 'header_color',
		'label'		  => esc_html__( 'Header Color', 'sekarbumi' ),
		'section' 	=> 'sekarbumi_header_section',
		'priority'	=> 60,
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'		=> 'color',
		'settings'	=> 'header_color_hover',
		'label'		=> esc_html__( 'Header Color Hover', 'sekarbumi' ),
		'section'	=> 'sekarbumi_header_section',
		'priority'	=> 60,
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'		=> 'color',
		'settings'	=> 'header_submenu_color',
		'label'		=> esc_html__( 'Header Sub-Menu Color', 'sekarbumi' ),
		'section'	=> 'sekarbumi_header_section',
		'priority'	=> 60,
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'		=> 'color',
		'settings'	=> 'header_submenu_hover',
		'label'		=> esc_html__( 'Header Sub-Menu Color Hover', 'sekarbumi' ),
		'section'	=> 'sekarbumi_header_section',
		'priority'	=> 60,
	)
);