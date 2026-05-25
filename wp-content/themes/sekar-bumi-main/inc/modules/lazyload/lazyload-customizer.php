<?php

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'		=> 'switch',
		'settings'	=> 'lazy_image',
		'label'		=> esc_html__( 'Enable Lazyload', 'sekarbumi' ),
		'section'	=> 'sekarbumi_lazyload_section',
		'default'	=> 'on',
		'priority'	=> 60,
		'choices'	=> array(
			'on'  => esc_html__( 'On', 'kirki' ),
			'off' => esc_html__( 'Off', 'kirki' )
		),
		'transport'	=> 'auto',
	)
);