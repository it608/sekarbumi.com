<?php
namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class ZOD_Core_Team_Member extends Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_style( 'zod-team-member-style', ZOD_CORE_WIDGETS_URI . '/elements/team-member/assets/team-member.css', array() , ZOD_CORE_VERSION );
		wp_register_script( 'zod-team-member-script', ZOD_CORE_WIDGETS_URI . '/elements/team-member/assets/team-member.js', array( 'elementor-frontend', 'zod-core-uikit-script' ) , ZOD_CORE_VERSION, true );
	}

	public function get_name() {
		return 'zod-team-member';
	}
	
	public function get_title() {
		return __( 'Team Member', 'zod-core' );
	}

	public function get_style_depends() {
		return array( 'zod-team-member-style' );
	}

	public function get_script_depends() {
		return array( 'zod-team-member-script' );
	}
	
	public function get_categories() {
		return array( 'zod' );
	}
	
	protected function register_controls() {

		// Start Content
		$this->start_controls_section(
			'team_section',
			array(
				'label' => __( 'Team Member', 'zod-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'first_name',
			array(
				'label' 	    => __( 'Full Name', 'zod-core' ),
				'label_block'   => true,
				'type' 		    => Controls_Manager::TEXT,
				'default'	    => array(),
			)
		);

		$this->add_control(
			'title',
			array(
				'label' 	    => __( 'Title', 'zod-core' ),
				'label_block'   => true,
				'type' 		    => Controls_Manager::TEXT,
				'default'	    => array(),
			)
		);

		$this->add_control(
			'description',
			array(
				'label' 	=> __( 'Description', 'zod-core' ),
				'type' 		=> Controls_Manager::TEXTAREA,
				'default'	=> array(),
			)
		);

		$this->add_control(
			'division',
			array(
				'label' 	=> __( 'Division', 'zod-core' ),
				'type' 		=> Controls_Manager::MEDIA,
				'default'	=> array(),
			)
		);

		$this->add_control(
			'images',
			array(
				'label' 	=> __( 'Images', 'zod-core' ),
				'type' 		=> Controls_Manager::MEDIA,
				'default'	=> array(),
			)
		);
	
		// End of Content
		$this->end_controls_section();

		// Start Section Socmed
		$this->start_controls_section(
			'socmed_section',
			array(
				'label' => __( 'Social Icons', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater_social = new Repeater();

		$repeater_social->add_control(
			'social_icon',
			array(
				'label'		=> esc_html__( 'Icon', 'zod-core' ),
				'type'		=> Controls_Manager::ICONS,
				'default'	=> array(
					'value' => 'fab fa-wordpress',
					'library' => 'fa-brands',
				),
				'recommended' => array(
					'fa-brands' => array(
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'deviantart',
						'digg',
						'dribbble',
						'zod-core',
						'facebook',
						'flickr',
						'foursquare',
						'free-code-camp',
						'github',
						'gitlab',
						'globe',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mix',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'shopping-cart',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'telegram',
						'thumb-tack',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'viber',
						'vimeo',
						'vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					),
					'fa-solid' => array(
						'envelope',
						'link',
						'rss',
					)
				),
			)
		);

		$repeater_social->add_control(
			'link',
			array(
				'label' 		=> esc_html__( 'Link', 'zod-core' ),
				'type' 			=> Controls_Manager::URL,
				'default'		=> array(
					'url'	=> ''
				),
				'placeholder' 	=> esc_html__( 'https://your-link.com', 'zod-core' ),
				'options'		=> false,
			)
		);

		$this->add_control(
			'social_icon_list',
			array(
				'label' 	=> esc_html__( 'Social Icons', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_social->get_controls(),
				'default' 	=> array(
					array(
						'social_icon' => array(
							'value' => 'fab fa-facebook',
							'library' => 'fa-brands',
						),
					),
					array(
						'social_icon' => array(
							'value' => 'fab fa-twitter',
							'library' => 'fa-brands',
						),
					),
					array(
						'social_icon' => array(
							'value' => 'fab fa-youtube',
							'library' => 'fa-brands',
						),
					),
				),
				'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ) }}}',
			)
		);
	
		$this->end_controls_section();

		// Start Section Progress Bar
		$this->start_controls_section(
			'progressbar_section',
			array(
				'label' => __( 'Progress Bar', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater_progress = new Repeater();

		$repeater_progress->add_control(
			'pb_title',
			array(
				'label' 		=> esc_html__( 'Title', 'zod-core' ),
				'label_block'	=> true,
				'type' 			=> Controls_Manager::TEXT,
				'default'		=> __( 'Title Progress Bar', 'zod-core' ),
			)
		);

		$repeater_progress->add_control(
			'pb_value',
			array(
				'label' 		=> esc_html__( 'Progress Bar Value', 'zod-core' ),
				'type' 			=> Controls_Manager::NUMBER,
				'default'		=> 100,
			)
		);

		$this->add_control(
			'prgoressbar_list',
			array(
				'label' 	=> esc_html__( 'Progress Bar List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_progress->get_controls(),
				'default' 	=> array(
					array(
						'pb_value' => 100,
					),
				),
				'title_field' => '{{{ pb_title }}} {{{ pb_value }}}%'
			)
		);
	
		$this->end_controls_section();

		// Start Section Style
		$this->start_controls_section(
			'section_style_primary',
			array(
				'label' => esc_html__( 'Primary', 'ks-extender' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'primary_typography',
				'global' => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} *:not(p):not(i)',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_secondary',
			array(
				'label' => esc_html__( 'Secondary', 'ks-extender' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'secondary_typography',
				'global' => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' => '{{WRAPPER}} p',
			)
		);
	
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings 		= $this->get_settings_for_display();
		$output			= '';

		// Social Icons
		$social_icons  = '<div class="zod-social-icon">';
		foreach( $settings['social_icon_list'] as $item ){
			// Render Icon
			if( !empty( $item['social_icon'] ) ){
				$social_icon = sprintf( '<i class="%1$s"></i>', esc_attr( $item['social_icon']['value'] ) );
			}

			$social_icons .= sprintf(
				'
				<a href="%1$s" class="zod-social-icon--item" target="_blank" rel="noopener nofollow">%2$s</a>
				',
				esc_url( $item['link']['url'] ),
				$social_icon
			);
		}
		$social_icons .= '</div>';

		// Progress Bar List
		$progress_bar = '<div class="zod-progress-bar">';
		foreach( $settings['prgoressbar_list'] as $item ){

			$progress_bar .= isset( $item['pb_title'] ) ? '<span class="zod-progress-bar--title">'. esc_html__( $item['pb_title'], 'zod-core' ) .'</span>' : '';

			$data_value = array(
				'value'	=> $item['pb_value'],
				'title'	=> $item['pb_title']
			);

			$progress_bar .= sprintf(
				'
				<div class="zod-progress-bar--item" data-progress-bar="%1$s"></div>
				',
				esc_attr( json_encode( $data_value ) )
			);
		}
		$progress_bar .= '</div>';

		// Render
		$output = sprintf(
			'
			<div class="zod-team-member">
				<div class="zod-team-member--image">
					%1$s
					<div class="zod-team-member--hover">
						%2$s
						%3$s
						%4$s
					</div>
				</div>
				<div class="zod-team-member--details">
					%5$s
					%6$s
				</div>
				<div class="zod-team-member--progress-bar">
					%7$s
				</div>
			</div>
			',
			zod_core_render_widgets_image( $settings['images'] ),
			zod_core_render_widgets_image( $settings['division'] ),
			isset( $settings['description'] ) ? '<p>'. esc_html__( $settings['description'], 'zod-core') .'</p>' : '',
			!empty( $social_icons ) ? $social_icons : '',
			isset( $settings['first_name'] ) ? '<h3 class="zod-team-member--name">'. __( $settings['first_name'], 'zod-core') .'</h3>' : '',
			isset( $settings['title'] ) ? '<span class="zod-team-member--title">'. esc_html__( $settings['title'], 'zod-core') .'</span>' : '',
			!empty( $progress_bar ) ? $progress_bar : ''
		);


		echo apply_filters( 'zod_team_member', $output );

	}
}