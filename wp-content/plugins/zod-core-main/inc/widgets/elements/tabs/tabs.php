<?php
namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class ZOD_Core_Tabs extends Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_style( 'zod-tabs-style', ZOD_CORE_WIDGETS_URI . '/elements/tabs/assets/tabs.css', array() , ZOD_CORE_VERSION );
		wp_register_script( 'zod-tabs-script', ZOD_CORE_WIDGETS_URI . '/elements/tabs/assets/tabs.js', array( 'elementor-frontend' ) , ZOD_CORE_VERSION, true );
	}

	public function get_name() {
		return 'zod-tabs';
	}
	
	public function get_title() {
		return __( 'Tabs', 'zod-core' );
	}

	public function get_style_depends() {
		return array( 'zod-tabs-style', 'slick-style' );
	}

	public function get_script_depends() {
		return array( 'zod-tabs-script', 'slick-script' );
	}
	
	public function get_categories() {
		return array( 'zod' );
	}
	
	protected function register_controls() {

		// Start Section Performance History
		$this->start_controls_section(
			'tabs_performance',
			array(
				'label' => __( 'Project Performance History', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

    $repeater_performance = new Repeater();

    $repeater_performance->add_control(
			'media',
			array(
				'label' 	=> __( 'Add Images', 'zod-core' ),
				'type' 		=> Controls_Manager::MEDIA,
				'default'	=> array(),
			)
		);

		$repeater_performance->add_control(
			'caption',
			array(
				'label' 	    => esc_html__( 'Caption', 'zod-core' ),
				'type' 		    => Controls_Manager::TEXT,
				'default'     => __( 'Caption', 'zod-core' ),
        'label_block' => true,
			)
		);

		$this->add_control(
			'project_performance',
			array(
				'label'   => esc_html__( 'Carousel Content', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_performance->get_controls(),
				'default' => array(
            array(
              'media' => array(
                'url' => Utils::get_placeholder_image_src()
              ),
              'caption' => __( 'Caption Image', 'zod-core' ),
            ),
            array(
              'media' => array(
                'url' => Utils::get_placeholder_image_src()
              ),
              'caption' => __( 'Caption Image', 'zod-core' ),
            ),
        ),
				'title_field' => __('Carousel Content', 'zod-core'),
      )
		);

    $repeater_project_text = new Repeater();

		$repeater_project_text->add_control(
			'paragraph_text',
			array(
				'label'       => esc_html__( 'Paragraph', 'zod-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 10,
				'default'     => esc_html__( 'Default description', 'zod-core' ),
				'placeholder' => esc_html__( 'Type your description here', 'zod-core' ),
      )
		);

		$this->add_control(
			'project_text',
			array(
				'label'   => esc_html__( 'Project Text', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_project_text->get_controls(),
				'default' => array(
            array(
              'paragraph_text' => esc_html__( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'zod-core' ),
            ),
            array(
              'paragraph_text' => esc_html__( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'zod-core' ),
            ),
        ),
				'title_field' => __('Text', 'zod-core'),
      )
		);
	
		$this->end_controls_section();

		// Start Section Collaborators
		$this->start_controls_section(
			'tabs_collaborators',
			array(
				'label' => __( 'Project Colaborators', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

    $repeater_paragraph = new Repeater();

		$repeater_paragraph->add_control(
			'paragraph_content',
			array(
				'label'       => esc_html__( 'Paragraph', 'zod-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 10,
				'default'     => esc_html__( 'Default description', 'zod-core' ),
				'placeholder' => esc_html__( 'Type your description here', 'zod-core' ),
      )
		);

		$this->add_control(
			'collaborators_paragraph_lists',
			array(
				'label'   => esc_html__( 'Paragraph', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_paragraph->get_controls(),
				'default' => array(
            array(
              'paragraph' => esc_html__( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'zod-core' ),
            ),
            array(
              'paragraph' => esc_html__( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'zod-core' ),
            ),
        ),
				'title_field' => __('Paragraph', 'zod-core'),
      )
		);

    $repeater_collaborators = new Repeater();

    $repeater_collaborators->add_control(
			'name',
			array(
				'label' 	=> __( 'Team Member Name', 'zod-core' ),
				'type' 		=> Controls_Manager::TEXT,
				'default'	=> esc_html__( 'John Doe', 'zod-core' ),
			)
		);

    $repeater_collaborators->add_control(
			'media',
			array(
				'label' 	=> __( 'Team Member Image', 'zod-core' ),
				'type' 		=> Controls_Manager::MEDIA,
				'default'	=> array(),
			)
		);

		$repeater_collaborators->add_control(
			'content',
			array(
				'label' 	=> esc_html__( 'Team Member Content', 'zod-core' ),
				'type' 		=> Controls_Manager::TEXTAREA,
        'row'     => 10,
				'default' => __( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'zod-core' ),
			)
		);

		$this->add_control(
			'team_member_lists',
			array(
				'label'   => esc_html__( 'Team Member', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_collaborators->get_controls(),
				'default' => array(
            array(
              'media' => array(
                'url' => Utils::get_placeholder_image_src()
              ),
              'content' => __( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'zod-core' ),
            ),
            array(
              'media' => array(
                'url' => Utils::get_placeholder_image_src()
              ),
              'caption' => __( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'zod-core' ),
            ),
        ),
				'title_field' => '{{{ name }}}',
      )
		);

    $repeater_client = new Repeater();

    $repeater_client->add_control(
			'client_name',
			array(
				'label' 	=> __( 'Client Name', 'zod-core' ),
				'type' 		=> Controls_Manager::TEXT,
				'default'	=> esc_html__( 'Client Name', 'zod-core' ),
			)
		);

    $repeater_client->add_control(
			'client_image',
			array(
				'label' 	=> __( 'Client Image', 'zod-core' ),
				'type' 		=> Controls_Manager::MEDIA,
				'default'	=> array(),
			)
		);

		$repeater_client->add_control(
			'client_content',
			array(
				'label' 	=> esc_html__( 'Client Content', 'zod-core' ),
				'type' 		=> Controls_Manager::TEXTAREA,
        'row'     => 10,
				'default' => __( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'zod-core' ),
			)
		);

		$this->add_control(
			'client_lists',
			array(
				'label'   => esc_html__( 'Client', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_client->get_controls(),
				'default' => array(
            array(
              'media' => array(
                'url' => Utils::get_placeholder_image_src()
              ),
              'content' => __( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'zod-core' ),
            ),
            array(
              'media' => array(
                'url' => Utils::get_placeholder_image_src()
              ),
              'caption' => __( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", 'zod-core' ),
            ),
        ),
				'title_field' => '{{{ client_name }}}',
      )
		);

    // Start Supported By
    $repeater_supported = new Repeater();

    $repeater_supported->add_control(
			'supported_image',
			array(
				'label' 	=> __( 'Supported By Logo', 'zod-core' ),
				'type' 		=> Controls_Manager::MEDIA,
				'default'	=> array(),
			)
		);

    $repeater_supported->add_control(
			'supported_image_link',
			array(
				'label' 	=> __( 'Image Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default'	=> array(),
        'label_block' => true
			)
		);

		$this->add_control(
			'supported_lists',
			array(
				'label'   => esc_html__( 'Supported By', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_supported->get_controls(),
        'item_actions' => array(
          'add'       => false,
          'duplicate' => false,
          'remove'    => false,
          'sort'      => true,
        ),
				'default' => array(
            array(
              'supported_image' => array(
                'url' => Utils::get_placeholder_image_src()
              ),
            ),
            array(
              'supported_image' => array(
                'url' => Utils::get_placeholder_image_src()
              ),
            ),
        ),
				'title_field' => __( 'Supported By', 'zod-core' ),
      )
		);

    // Start Thanks To
    $repeater_thanks_to = new Repeater();

    $repeater_thanks_to->add_control(
			'thanks_to_image',
			array(
				'label' 	=> __( 'Thanks To Logo', 'zod-core' ),
				'type' 		=> Controls_Manager::MEDIA,
				'default'	=> array(),
			)
		);

    $repeater_thanks_to->add_control(
			'thanks_to_image_url',
			array(
				'label' 	=> __( 'Thanks To Logo', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default'	=> array(),
        'label_block' => true
			)
		);

		$this->add_control(
			'thanks_to_lists',
			array(
				'label'   => esc_html__( 'Thanks To', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_thanks_to->get_controls(),
        'item_actions' => array(
          'add'       => false,
          'duplicate' => false,
          'remove'    => false,
          'sort'      => true,
        ),
				'default' => array(
            array(
              'thanks_to_image' => array(
                'url' => Utils::get_placeholder_image_src()
              ),
            ),
        ),
				'title_field' => __( 'Thanks To', 'zod-core' ),
      )
		);
	
		$this->end_controls_section();

		// Start Section Event
		$this->start_controls_section(
			'tabs_event',
			array(
				'label' => __( 'Event Artists', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

    $repeater_events = new Repeater();

		$repeater_events->add_control(
			'artists',
			array(
				'label'       => esc_html__( 'Artists', 'zod-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 10,
				'default'     => esc_html__( 'Artists Name', 'zod-core' ),
        'label_block' => true,
      )
		);

		$this->add_control(
			'artists_list',
			array(
				'label'   => esc_html__( 'Artis Lists', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_events->get_controls(),
				'default' => array(
            array(
              'artists' => esc_html__( 'Artists Name', 'zod-core' ),
            ),
        ),
				'title_field' => esc_html__( 'Artists Name', 'zod-core' ),
      )
		);
	
		$this->end_controls_section();

		// Start Section Presenting Partners
		$this->start_controls_section(
			'tabs_partners',
			array(
				'label' => __( 'Presenting Partners', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

    $repeater_partners = new Repeater();

		$repeater_partners->add_control(
			'partners',
			array(
				'label'       => esc_html__( 'Partners', 'zod-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 10,
				'default'     => esc_html__( 'Partners Name', 'zod-core' ),
        'label_block' => true,
      )
		);

		$this->add_control(
			'partners_list',
			array(
				'label'   => esc_html__( 'Partners Lists', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_partners->get_controls(),
				'default' => array(
            array(
              'partners' => esc_html__( 'Partners Name', 'zod-core' ),
            ),
        ),
				'title_field' => esc_html__( 'Partners Name', 'zod-core' ),
      )
		);

    $repeater_creative_thought = new Repeater();

		$repeater_creative_thought->add_control(
			'creative_partners',
			array(
				'label'       => esc_html__( 'Creative Thought Partners', 'zod-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 10,
				'default'     => esc_html__( 'Partners Name', 'zod-core' ),
        'label_block' => true,
      )
		);

		$this->add_control(
			'creative_list',
			array(
				'label'   => esc_html__( 'Creative Thought Partners', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_creative_thought->get_controls(),
				'default' => array(
            array(
              'creative_partners' => esc_html__( 'Partners Name', 'zod-core' ),
            ),
        ),
				'title_field' => esc_html__( 'Creative Partners', 'zod-core' ),
      )
		);

    $repeater_special_thanks = new Repeater();

		$repeater_special_thanks->add_control(
			'special_partners',
			array(
				'label'       => esc_html__( 'Special Thanks', 'zod-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 10,
				'default'     => esc_html__( 'Partners Name', 'zod-core' ),
        'label_block' => true,
      )
		);

		$this->add_control(
			'special_partners_list',
			array(
				'label'   => esc_html__( 'Special Thanks list', 'zod-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater_special_thanks->get_controls(),
				'default' => array(
            array(
              'special_partners' => esc_html__( 'Partners Name', 'zod-core' ),
            ),
        ),
				'title_field' => esc_html__( 'Special Thanks Partners', 'zod-core' ),
      )
		);
	
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings 	= $this->get_settings_for_display();
		$output			= '';

    // Project Performance History
    $project_carousel         = '';
    $project_caption          = '';
    $project_performance_text = '';

    if( !empty( $settings['project_performance'] ) ){
      foreach( $settings['project_performance'] as $item ){
        $project_carousel .= sprintf(
          '
          <div class="zod-project-carousel__item">
            %1$s
          </div>
          ',
          zod_core_render_widgets_gallery( $item['media']['id'] ),
        );
        $project_caption .= sprintf(
          '
          %1$s
          ',
          !empty( $item['caption'] ) ? '<p>'. __( $item['caption'], 'zod-core' ) .'</p>' : ''
        );
      }
    }

    if( !empty( $settings['project_text'] ) ){
      foreach( $settings['project_text'] as $item ){
        $project_performance_text .= sprintf(
          '
          <p>%1$s</p>
          ',
          __( $item['paragraph_text'], 'zod-core' ),
        );
      }
    }

    $project_performance_history = sprintf(
      '
      <div class="uk-child-width-1-2@m" uk-grid>
        <div class="zod-project">
          <div class="zod-project-carousel">
            %1$s
          </div>
          <div class="zod-project-caption">
            %2$s
          </div>
        </div>
        <div class="zod-project-text">
          %3$s
        </div>
      </div>
      ',
      $project_carousel,
      $project_caption,
      $project_performance_text
    );

    // Project Collaborators
    $project_collaborators_text = '';
    $team_member                = '';
    $team_member_image          = '';
    $team_member_name           = '';
    $team_member_content        = '';
    $client                     = '';

    if( !empty( $settings['collaborators_paragraph_lists'] ) ){
      foreach( $settings['collaborators_paragraph_lists'] as $item ){
        $project_collaborators_text .= sprintf(
          '
          <p>%1$s</p>
          ',
          __( $item['paragraph_content'], 'zod-core' )
        );
      }
    }

    if( !empty( $settings['team_member_lists'] ) ){
      foreach( $settings['team_member_lists'] as $item ){
        $team_member .= sprintf(
          '
          <div class="team-member__item">
            <div class="team-member__avatar">
              %1$s
            </div>
            <div class="team-member__content">
              <p>
                <b>%2$s</b> %3$s
              </p>
            </div>
          </div>
          ',
          zod_core_render_widgets_gallery( $item['media']['id'] ),
          __( $item['name'], 'zod-core' ),
          __( $item['content'], 'zod-core' )
        );
      }
    }

    if( !empty( $settings['client_lists'] ) ){
      foreach( $settings['client_lists'] as $item ){
        $client .= sprintf(
          '
          <div class="client__item">
            <div class="client__image">
              %1$s
            </div>
            <div class="client__content">
              <p>
                <b>%2$s</b> %3$s
              </p>
            </div>
          </div>
          ',
          zod_core_render_widgets_gallery( $item['client_image']['id'] ),
          __( $item['client_name'], 'zod-core' ),
          __( $item['client_content'], 'zod-core' )
        );
      }
    }

    // Supported By
    $supported_by         = '';
    $supported_by_item  = '';
    if( !empty( $settings['supported_lists'] ) ){
      foreach( $settings['supported_lists'] as $item ){
        if( !empty( $item['supported_image_link']['url'] ) ){
          $supported_by_item .= sprintf(
            '
            <a %1$s%2$s%3$s>
              %4$s
            </a>
            ',
            !empty( $item['supported_image_link']['url'] ) ? 'href="'. esc_url( $item['supported_image_link']['url'] ).'"' : '',
            !empty( $item['supported_image_link']['is_external'] ) && $item['supported_image_link']['is_external'] == 'on' ? ' target="_blank"' : '',
            !empty( $item['supported_image_link']['nofollow'] ) && $item['supported_image_link']['nofollow'] == 'on' ? 'rel="nofollow"' : '',
            zod_core_render_widgets_gallery( $item['supported_image']['id'] )
          );
        } else {
          $supported_by_item .= sprintf(
            '
            %1$s
            ',
            zod_core_render_widgets_gallery( $item['supported_image']['id'] )
          );
        }
      }
    }
    $supported_by = sprintf(
      '
      <div class="supported-by">
        <h5 class="supported-by--heading uk-margin-remove">%1$s</h5>
        <div class="supported-by__image">
          %2$s
        </div>
      </div>
      ',
      __( 'Additional support by', 'zod-core' ),
      $supported_by_item
    );

    // Thanks To
    $thanks_to         = '';
    $thanks_to_item  = '';
    if( !empty( $settings['thanks_to_lists'] ) ){
      foreach( $settings['thanks_to_lists'] as $item ){
        $thanks_to_item .= sprintf(
          '
          <a %1$s%2$s%3$s>
            %4$s
          </a>
          ',
          !empty( $item['thanks_to_image_url']['url'] ) ? 'href="'. esc_url( $item['thanks_to_image_url']['url'] ).'"' : '',
          !empty( $item['thanks_to_image_url']['is_external'] ) && $item['thanks_to_image_url']['is_external'] == 'on' ? ' target="_blank"' : '',
          !empty( $item['thanks_to_image_url']['nofollow'] ) && $item['thanks_to_image_url']['nofollow'] == 'on' ? 'rel="nofollow"' : '',
          zod_core_render_widgets_gallery( $item['thanks_to_image']['id'] )
        );
      }
    }
    $thanks_to = sprintf(
      '
      <div class="thanks-to">
        <div></div>
        <div class="thanks-to__content">
          <h5 class="thanks-to--heading uk-margin-remove">%1$s</h5>
          <div class="thanks-to__image">
            %2$s
          </div>
        </div>
      </div>
      ',
      __( 'Site Designed by', 'zod-core' ),
      $thanks_to_item
    );

    $project_collaborators = sprintf(
      '
      <div class="project-collaborators">
        %1$s
        <div class="team-member uk-child-width-1-2@m" uk-grid>
          %2$s
        </div>
        <div class="client uk-child-width-1-2@m" uk-grid>
          %3$s
        </div>
        <div class="internal-section uk-child-width-1-2@m" uk-grid>
          %4$s
          %5$s
        </div>
      </div>
      ',
      $project_collaborators_text,
      $team_member,
      $client,
      $supported_by,
      $thanks_to
    );

    // Event Artists
    $event_artists      = '';
    $event_artists_item = '';
    if( !empty( $settings['artists_list'] ) ){
      foreach( $settings['artists_list'] as $item ){
        $event_artists_item .= sprintf(
          '
          <div class="event-artist__item uk-text-center">
            <p>%1$s</p>
          </div>
          ',
          $item['artists']
        );
      }
    }

    $event_artists = sprintf(
      '
      <div class="event-artists uk-child-width-1-4@m uk-grid-small" uk-grid>
        %1$s
      </div>
      ',
      $event_artists_item
    );

    // Presenting Partners
    $partners_item = '';
    if( !empty( $settings['partners_list'] ) ){
      foreach( $settings['partners_list'] as $item ){
        $partners_item .= sprintf(
          '
          <p>%1$s</p>
          ',
          $item['partners']
        );
      }
    }

    $partners = sprintf(
      '
      <div class="partners uk-text-center">
        %1$s
      </div>
      ',
      $partners_item
    );

    // Creative Partner List
    $creative_partners       = '';
    $creative_partners_item  = '';
    if( !empty( $settings['creative_list'] ) ){
      foreach( $settings['creative_list'] as $item ){
        $creative_partners_item .= sprintf(
          '
          <div>
            <p>%1$s</p>
          </div>
          ',
          $item['creative_partners']
        );
      }
    }

    $creative_partners = sprintf(
      '
      <div class="creative-partners uk-text-center">
        <h6 class="creative-partners--heading">%1$s</h6>
        %2$s
      </div>
      ',
      __( 'Creative Thought Partners', 'zod-core' ),
      $creative_partners_item
    );

    // Special Partner List
    $special_partners       = '';
    $special_thanks_item  = '';
    if( !empty( $settings['special_partners_list'] ) ){
      foreach( $settings['special_partners_list'] as $item ){
        $special_thanks_item .= sprintf(
          '
          <div>
            <p>%1$s</p>
          </div>
          ',
          $item['special_partners']
        );
      }
    }

    $special_partners = sprintf(
      '
      <div class="special-thanks uk-text-center">
        <h6 class="special-thanks--heading">%1$s</h6>
        %2$s
      </div>
      ',
      __( 'Special Thanks to', 'zod-core' ),
      $special_thanks_item
    );

		// Render
		$output = sprintf(
			'
			<div class="zod-tabs">
        <ul class="zod-tabs__title uk-flex-center" uk-tab>
            <li><a href="#">%1$s</a></li>
            <li><a href="#">%2$s</a></li>
            <li><a href="#">%3$s</a></li>
            <li><a href="#">%4$s</a></li>
        </ul>
        <ul class="zod-tabs__content uk-switcher uk-margin">
            <li>%5$s</li>
            <li>%6$s</li>
            <li>%7$s</li>
            <li>
              %8$s
              %9$s
              %10$s
            </li>
        </ul>
			</div>
			',
      __( 'Project Performance History', 'zod-core' ),
      __( 'Project Collaborators', 'zod-core' ),
      __( 'Event Artists', 'zod-core' ),
      __( 'Presenting Partners', 'zod-core' ),
      $project_performance_history,
      $project_collaborators,
      $event_artists,
      $partners,
      $creative_partners,
      $special_partners
		);


		echo apply_filters( 'zod_team_member', $output );

	}
}