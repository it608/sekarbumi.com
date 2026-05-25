<?php
Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'		=> 'color',
		'settings'	=> 'page_title_background_color',
		'label'		=> esc_html__( 'Background Color', 'sekarbumi' ),
		'section'	=> 'sekarbumi_page_title_section',
		'default'	=> '',
		'priority'	=> 10,
		'choices'	=> array(
			'alpha' => true,
		),
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'		=> 'background',
		'settings'	=> 'page_title_background',
		'label'		=> esc_html__( 'Page Title Background', 'sekarbumi' ),
		'section'	=> 'sekarbumi_page_title_section',
		'default'	=> array(
			'background-color'      => 'rgba(20,20,20,.8)',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'cover',
			'background-attachment' => 'scroll',
		),
		'priority'	=> 20,
		'transport'	=> 'auto',
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'		=> 'color',
		'settings'	=> 'page_title_overlay_color',
		'label'		=> esc_html__( 'Overlay Color', 'sekarbumi' ),
		'section'	=> 'sekarbumi_page_title_section',
		'default'	=> '',
		'priority'	=> 30,
		'choices'	=> array(
			'alpha' => true,
		),
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'		=> 'color',
		'settings'	=> 'page_title_text_color',
		'label'		=> esc_html__( 'Text Color', 'sekarbumi' ),
		'section'	=> 'sekarbumi_page_title_section',
		'default'	=> '',
		'priority'	=> 40,
	)
);