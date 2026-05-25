<?php
Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'        => 'background',
		'settings'    => 'footer_bg',
		'label'       => esc_html__( 'Footer Background', 'sekarbumi' ),
		'section'     => 'sekarbumi_footer_section',
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
		'settings'    => 'footer_widget_title_color',
		'label'       => esc_html__( 'Footer Widget Title Color', 'sekarbumi' ),
		'section'     => 'sekarbumi_footer_section',
		'default'     => '#ffffff',
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'        => 'color',
		'settings'    => 'footer_color',
		'label'       => esc_html__( 'Footer Color', 'sekarbumi' ),
		'section'     => 'sekarbumi_footer_section',
		'default'     => '#ffffff',
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'        => 'color',
		'settings'    => 'footer_link_color',
		'label'       => esc_html__( 'Footer Link Color', 'sekarbumi' ),
		'section'     => 'sekarbumi_footer_section',
		'default'     => '#ffffff',
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'        => 'color',
		'settings'    => 'footer_hover_color',
		'label'       => esc_html__( 'Footer Hover Color', 'sekarbumi' ),
		'section'     => 'sekarbumi_footer_section',
		'default'     => '#ffffff',
	)
);

Kirki::add_field( 'sekarbumi_settings',
	array(
		'type'        => 'text',
		'settings'    => 'footer_copyright_text',
		'label'       => esc_html__( 'Copyright Text', 'sekarbumi' ),
		'section'     => 'sekarbumi_footer_section',
		'default'     => esc_html__( 'Copyright ©', 'sekarbumi' ),
	)
);