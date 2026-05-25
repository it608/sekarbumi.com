<?php
$custom_fonts = array(
	'fonts' => array(
		'families' => array(
			'custom'	=> array(
				'text'     => 'Sekarbumi Custom Fonts',
				'children' => array(
					array( 
						'id'	=> 'Classica Sans', 
						'text' 	=> 'Classica Sans'
					),
					array( 
						'id' 	=> 'Basic Sans',
						'text' 	=> 'Basic Sans'
					),
				),
			),
		),
		'variants' => array(
			'Classica Sans'	=> array(),
			'Basic Sans'   	=> array(),
		),
	)
);

Kirki::add_field( 
	'sekarbumi_settings',
	array(
		'type'          => 'switch',
		'settings'      => 'enable_secondary_typography',
		'label'         => esc_html__( 'Secondary Typography', 'sekarbumi' ),
		'description'   => esc_html__( 'Enabling this will allow us to use secondary typography.', 'sekarbumi' ),
		'section'       => 'sekarbumi_typography_section',
		'default'       => 'on',
		'choices'       => array(
      'on'    => esc_html__( 'On', 'sekarbumi' ),
      'off'   => esc_html__( 'Off', 'sekarbumi' )
    ),
		'transport'     => 'auto'
	)
);

Kirki::add_field( 
	'sekarbumi_settings',
	array(
		'type'          => 'typography',
		'settings'      => 'typography_main_font',
		'label'         => esc_html__( 'Primary Font', 'sekarbumi' ),
		'description'   => esc_html__( 'Will be used on heading', 'sekarbumi' ),
		'section'       => 'sekarbumi_typography_section',
		'choices'       => array(
      'fonts' => array(
        'google'   => [ 'Plus Jakarta Sans', 'Poppins', 'Lato', 'Roboto' ],
        'standard' => array( 
          'serif',
          'sans-serif'
        )
      ),
    ),
		'default'       => array(
			'font-family'    => 'Poppins',
			'variant'        => '600',
		),
		'priority'      => 10,
		'transport'     => 'auto'
	)
);

Kirki::add_field(
	'sekarbumi_settings',
	array(
		'type'          => 'typography',
		'settings'      => 'typography_secondary_font',
		'label'         => esc_html__( 'Secondary Font', 'sekarbumi' ),
		'description'   => esc_html__( 'Will be used on paragraph', 'sekarbumi' ),
		'section'       => 'sekarbumi_typography_section',
		'choices'       => $custom_fonts,
		'default'       => array(
			'font-family'    => 'Lato',
			'variant'        => '300',
		),
		'priority'      => 10,
		'transport'     => 'auto',
    'active_callback' => array(
      array(
        'setting'  => 'enable_secondary_typography',
        'operator' => '===',
        'value'    => true,
      )
    ),
	)
);