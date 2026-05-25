<?php
Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'			=> 'image',
		'settings'		=> 'sekarbumi_site_logo',
		'label'			=> esc_html__( 'Normal Logo', 'sekarbumi' ),
		'section'		=> 'sekarbumi_site_identity_section',
		'default'		=> '',
		'priority'		=> 20,
		'choices'		=> array(
			'save_as'	=> 'id',
		)
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'			=> 'image',
		'settings'		=> 'sekarbumi_mobile_logo',
		'label'			=> esc_html__( 'Mobile Logo', 'sekarbumi' ),
		'section'		=> 'sekarbumi_site_identity_section',
		'default'		=> '',
		'priority'		=> 20,
		'choices'		=> array(
			'save_as'	=> 'id',
		)
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'			=> 'image',
		'settings'		=> 'sekarbumi_sticky_logo',
		'label'			=> esc_html__( 'Sticky Logo', 'sekarbumi' ),
		'section'		=> 'sekarbumi_site_identity_section',
		'default'		=> '',
		'priority'		=> 20,
		'choices'		=> array(
			'save_as'	=> 'id',
		)
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'			=> 'image',
		'settings'		=> 'sekarbumi_footer_logo',
		'label'			=> esc_html__( 'Footer Logo', 'sekarbumi' ),
		'section'		=> 'sekarbumi_site_identity_section',
		'default'		=> '',
		'priority'	=> 20,
		'choices'		=> array(
			'save_as'	=> 'id',
		)
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'			=> 'slider',
		'settings'	=> 'sekarbumi_logo_sizes',
		'label'			=> esc_html__( 'Logo Sizes Desktop', 'sekarbumi' ),
		'section'		=> 'sekarbumi_site_identity_section',
		'default'		=> 200,
		'priority'		=> 20,
		'choices'		=> array(
			'min'  => 0,
			'max'  => 400,
			'step' => 10,
		)
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'			=> 'slider',
		'settings'		=> 'sekarbumi_logo_sizes_mobile',
		'label'			=> esc_html__( 'Logo Sizes Mobile', 'sekarbumi' ),
		'section'		=> 'sekarbumi_site_identity_section',
		'default'		=> 250,
		'priority'		=> 20,
		'choices'		=> array(
			'min'  => 0,
			'max'  => 400,
			'step' => 10,
		)
	)
);