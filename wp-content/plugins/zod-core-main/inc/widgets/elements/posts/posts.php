<?php
namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class ZOD_Core_Posts extends Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_style( 'zod-posts-style', ZOD_CORE_WIDGETS_URI . '/elements/posts/assets/posts.css', array() , ZOD_CORE_VERSION );
	}

	public function get_name() {
		return 'zod-posts';
	}
	
	public function get_title() {
		return __( 'Posts', 'zod-core' );
	}
	
	public function get_description() {
		return __( 'Posts/Page query', 'zod-core' );
	}

	public function get_style_depends() {
		return array( 'zod-posts-style', 'slick-style' );
	}

	public function get_script_depends() {
		return array( 'zod-posts-script', 'slick-script' );
	}
	
	public function get_categories() {
		return array( 'zod' );
	}
	
	protected function register_controls() {
		// Start Section Performance History
		$this->start_controls_section(
			'tabs_general',
			array(
				'label' => __( 'General', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'post_type',
			array(
				'label'   => esc_html__( 'Post Type', 'zod-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
          ''                  => __( 'None', 'zod-core' ),
          'annual-report'     => __( 'Annual Report', 'zod-core' ),
          'financial-report'  => __( 'Financial Report', 'zod-core' ),
          'press-release'     => __( 'Press Release', 'zod-core' ),
          'public-disclosure' => __( 'Public Disclosure', 'zod-core' ),
        ),
				'default' => array(),
      )
		);
	
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings 	  = $this->get_settings_for_display();
		$output			  = '';
    $post_content = '';

    $the_post = get_posts( 
      array( 
        'post_type' => $settings['post_type'], 
        'posts_per_page' => 3 
      )
    );

    switch( $settings['post_type'] ){
      case 'annual-report':
        $report_link  = 'skbm_annual_report_link';
        break;
      case 'financial-report':
        $report_link  = 'skbm_financial_report_link';
        break;
      case 'press-release':
        $report_link  = 'skbm_press_report_link';
        break;

      default:
        $report_link  = 'skbm_report_link';
        break;
    }
    
    foreach( $the_post as $post ) {
      $press_url = get_post_meta( $post->ID, $report_link, true );
      $post_content .= sprintf(
        '
        <div>
          <span class="post-meta">
            %1$s / %2$s
          </span>
          <h3 class="post-title">
            %3$s
          </h3>
          <a href="%4$s" target="_blank" class="post-link">
            %5$s
          </a>
        </div>
        ',
        get_the_date("j F Y"),
        get_the_time("g:i"),
        esc_html__( $post->post_title, 'zod-core' ),
        !empty( $press_url ) ? esc_url( $press_url ) : '',
        esc_html__( 'Read More', 'zod-core' )
      );
    }

    $output = sprintf(
      '
      <div class="uk-child-width-1-3@m zod-widget-posts uk-grid-small" data-uk-grid>
        %2$s
      </div>
      ',
      isset( $settings['grid']['size'] ) ? esc_attr( $settings['grid']['size'] ) : '2',
      $post_content
    );

		echo apply_filters( 'zod_team_member', $output );

	}
}