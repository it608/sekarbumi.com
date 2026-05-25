<?php
Kirki::add_field( 'sekarbumi_settings', array(
	'type'			=> 'repeater',
	'settings'		=> 'social_account',
	'label'			=> '',
	'description'	=> esc_html__( 'Drag and drop to reorder the social account list, you can add and remove your social', 'sekarbumi' ),
	'default'		=> '',
	'section'		=> 'sekarbumi_social_section',
	'priority'		=> 12,
	'row_label'		=> array(
		'type'		=> 'field',
		'value'		=> esc_attr__( 'Your Custom Value', 'sekarbumi' ),
		'field'		=> 'socmed_type',
	),
	'fields'		=> array(
		'socmed_type'	=> array(
			'type'			=> 'select',
			'label'			=> esc_attr__( 'Your Social Media', 'sekarbumi' ),
			'description'	=> esc_attr__( 'Choose one your social media', 'sekarbumi' ),
			'default'		=> 'facebook',
			'choices'		=> array(
				'facebook'		=> esc_attr__( 'Facebook', 'sekarbumi' ),
				'twitter'		=> esc_attr__( 'Twitter', 'sekarbumi' ),
				'instagram'		=> esc_attr__( 'Instagram', 'sekarbumi' ),
				'youtube'		=> esc_attr__( 'Youtube', 'sekarbumi' ),
				'vimeo'			=> esc_attr__( 'Vimeo', 'sekarbumi' ),
				'behance'		=> esc_attr__( 'Behance', 'sekarbumi' ),
				'dribbble'		=> esc_attr__( 'Dribbble', 'sekarbumi' ),
				'whatsapp'		=> esc_attr__( 'Whatsapp', 'sekarbumi' ),
				'linkedin'		=> esc_attr__( 'LinkedIn', 'sekarbumi' ),
				'tiktok'		=> esc_attr__( 'TikTok', 'sekarbumi' ),
			),
		),
		'socmed_url'	=> array(
			'type'			=> 'text',
			'label'			=> esc_attr__( 'Social Media URL', 'sekarbumi' ),
			'description'	=> esc_attr__( 'Put your Social Media URL', 'sekarbumi' ),
			'default'		=> '',
		),
	),
) );