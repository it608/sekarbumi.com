<?php
namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class ZOD_Core_Management_Structure extends Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_style( 'zod-mgmt-structure-style', ZOD_CORE_WIDGETS_URI . '/elements/management-structure/assets/management-structure.css', array() , ZOD_CORE_VERSION );
	}

	public function get_name() {
		return 'zod-mgmt-structure';
	}
	
	public function get_title() {
		return __( 'Management Structure', 'zod-core' );
	}

	public function get_style_depends() {
		return array( 'zod-mgmt-structure-style' );
	}
	
	public function get_categories() {
		return array( 'zod' );
	}
	
	protected function register_controls() {
		// Start Section BOC
		$this->start_controls_section(
			'tabs_boc',
			array(
				'label' => __( 'BOC', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$repeater_boc = new Repeater();

		$repeater_boc->add_control(
			'boc_name', array(
				'label'       => esc_html__( 'BOC Name', 'zod-core' ),
				'type'        => Controls_Manager::TEXT,
        'label_block' => true,
				'default'     => __('BOC Name Here', 'zod-core' ),
			)
		);

		$repeater_boc->add_control(
			'boc_link', array(
				'label' 	=> __( 'BOC Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
        ),
			)
		);
	
		$this->add_control(
			'boc_list',
			array(
				'label' 	=> __( 'BOC List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_boc->get_controls(),
				'default' 	=> array(
					array(
            'boc_name'	=> __( 'BOC Name Here', 'zod-core' ),
            'boc_link'	=> '',
          ),
				),
				'title_field' => '{{{ boc_name }}}',
			)
		);
	
		$this->end_controls_section();

		// Start Audit Committe
		$this->start_controls_section(
			'tabs_audit_committe',
			array(
				'label' => __( 'Audit Committe', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$repeater_audit = new Repeater();

		$repeater_audit->add_control(
			'auditor_name',
			array(
				'label'       => esc_html__( 'Audit Name', 'zod-core' ),
				'type'        => Controls_Manager::TEXT,
        'label_block' => true,
				'default'     => __('Audit Name Here', 'zod-core' ),
      )
		);

		$repeater_audit->add_control(
			'auditor_link', array(
				'label' 	=> __( 'Audit Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
        ),
			)
		);
	
		$this->add_control(
			'auditor_list',
			array(
				'label' 	=> __( 'Audit List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_audit->get_controls(),
				'default' 	=> array(
					array(
            'auditor_name'	=> __( 'Audit Name Here', 'zod-core' ),
            'auditor_link'	=> '',
          ),
				),
				'title_field' => '{{{ auditor_name }}}',
			)
		);
	
		$this->end_controls_section();

		// Start President Director
		$this->start_controls_section(
			'tabs_presdir',
			array(
				'label' => __( 'President Director', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$repeater_presdir = new Repeater();

		$repeater_presdir->add_control(
			'presdir_name',
			array(
				'label'       => esc_html__( 'President Director Name', 'zod-core' ),
				'type'        => Controls_Manager::TEXT,
        'label_block' => true,
				'default'     => __('President Director Name Here', 'zod-core' ),
      )
		);

		$repeater_presdir->add_control(
			'presdir_link', array(
				'label' 	=> __( 'President Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
        ),
			)
		);
	
		$this->add_control(
			'presdir_list',
			array(
				'label' 	=> __( 'President Director List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_presdir->get_controls(),
				'default' 	=> array(
					array(
            'presdir_name'	=> __( 'President Director Name Here', 'zod-core' ),
            'presdir_link'	=> '',
          ),
				),
				'title_field' => '{{{ presdir_name }}}',
			)
		);
	
		$this->end_controls_section();

		// Start Internal Auditor
		$this->start_controls_section(
			'tabs_internal_auditor',
			array(
				'label' => __( 'Internal Audit', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$repeater_internal_auditor = new Repeater();

		$repeater_internal_auditor->add_control(
			'internal_auditor_name',
			array(
				'label'       => esc_html__( 'Internal Audit Name', 'zod-core' ),
				'type'        => Controls_Manager::TEXT,
        'label_block' => true,
				'default'     => __('Internal Audit Name Here', 'zod-core' ),
      )
		);

		$repeater_internal_auditor->add_control(
			'internal_auditor_link', array(
				'label' 	=> __( 'Internal Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
        ),
			)
		);
	
		$this->add_control(
			'internal_auditor_list',
			array(
				'label' 	=> __( 'Internal Audit List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_internal_auditor->get_controls(),
				'default' 	=> array(
					array(
            'internal_auditor_name'	=> __( 'Internal Audit Name Here', 'zod-core' ),
            'internal_link'	=> '',
          ),
				),
				'title_field' => '{{{ internal_auditor_name }}}',
			)
		);
	
		$this->end_controls_section();

		// Start Corporate Secretary
		$this->start_controls_section(
			'tabs_corp_sec',
			array(
				'label' => __( 'Corporate Secretary', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$repeater_corp_sec = new Repeater();

		$repeater_corp_sec->add_control(
			'corp_sec_name',
			array(
				'label'       => esc_html__( 'Corporate Secretary Name', 'zod-core' ),
				'type'        => Controls_Manager::TEXT,
        'label_block' => true,
				'default'     => __('Corporate Secretary Name Here', 'zod-core' ),
      )
		);

		$repeater_corp_sec->add_control(
			'corp_sec_link', array(
				'label' 	=> __( 'Corporate Secretary Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
        ),
			)
		);
	
		$this->add_control(
			'corp_sec_list',
			array(
				'label' 	=> __( 'Corporate Secretary List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_corp_sec->get_controls(),
				'default' 	=> array(
					array(
            'corp_sec_name'	=> __( 'Corporate Secretary Name Here', 'zod-core' ),
            'corp_sec_link'	=> '',
          ),
				),
				'title_field' => '{{{ corp_sec_name }}}',
			)
		);
	
		$this->end_controls_section();

		// Start Finance Director
		$this->start_controls_section(
			'tabs_findir',
			array(
				'label' => __( 'Finance Director', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$repeater_findir = new Repeater();

		$repeater_findir->add_control(
			'findir_name',
			array(
				'label'       => esc_html__( 'Finance Director Name', 'zod-core' ),
				'type'        => Controls_Manager::TEXT,
        'label_block' => true,
				'default'     => __('Finance Director Name Here', 'zod-core' ),
      )
		);

		$repeater_findir->add_control(
			'findir_link', array(
				'label' 	=> __( 'Finance Director Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
        ),
			)
		);
	
		$this->add_control(
			'findir_list',
			array(
				'label' 	=> __( 'Finance Director List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_findir->get_controls(),
				'default' 	=> array(
					array(
            'findir_name'	=> __( 'Finance Director Name Here', 'zod-core' ),
            'findir_link'	=> '',
          ),
				),
				'title_field' => '{{{ findir_name }}}',
			)
		);
	
		$this->end_controls_section();

		// Start Marketing Director
		$this->start_controls_section(
			'tabs_marcommdir',
			array(
				'label' => __( 'Marketing Director', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$repeater_marcommdir = new Repeater();

		$repeater_marcommdir->add_control(
			'marcommdir_name',
			array(
				'label'       => esc_html__( 'Marketing Director Name', 'zod-core' ),
				'type'        => Controls_Manager::TEXT,
        'label_block' => true,
				'default'     => __('Marketing Director Name Here', 'zod-core' ),
      )
		);

		$repeater_marcommdir->add_control(
			'marcommdir_link', array(
				'label' 	=> __( 'Marketing Director Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
        ),
			)
		);
	
		$this->add_control(
			'marcommdir_list',
			array(
				'label' 	=> __( 'Marketing Director List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_marcommdir->get_controls(),
				'default' 	=> array(
					array(
            'marcommdir_name'	=> __( 'Marketing Director Name Here', 'zod-core' ),
            'marcommdir_link'	=> '',
          ),
				),
				'title_field' => '{{{ marcommdir_name }}}',
			)
		);
	
		$this->end_controls_section();

		// Start Business Development Director
		$this->start_controls_section(
			'tabs_bdd',
			array(
				'label' => __( 'Business Development Director', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$repeater_bdd = new Repeater();

		$repeater_bdd->add_control(
			'bdd_name',
			array(
				'label'       => esc_html__( 'Business Development Director Name', 'zod-core' ),
				'type'        => Controls_Manager::TEXT,
        'label_block' => true,
				'default'     => __('Business Development Director Name Here', 'zod-core' ),
      )
		);

		$repeater_bdd->add_control(
			'bdd_link', array(
				'label' 	=> __( 'Business Development Director Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
        ),
			)
		);
	
		$this->add_control(
			'bdd_list',
			array(
				'label' 	=> __( 'Business Development Director List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_bdd->get_controls(),
				'default' 	=> array(
					array(
            'bdd_name'	      => __( 'Business Development Director Name Here', 'zod-core' ),
            'marcommdir_link'	=> '',
          ),
				),
				'title_field' => '{{{ bdd_name }}}',
			)
		);
	
		$this->end_controls_section();

		// Start Operations Director
		$this->start_controls_section(
			'tabs_operations',
			array(
				'label' => __( 'Operations Director', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$repeater_operations = new Repeater();

		$repeater_operations->add_control(
			'operations_name',
			array(
				'label'       => esc_html__( 'Operations Director Name', 'zod-core' ),
				'type'        => Controls_Manager::TEXT,
        'label_block' => true,
				'default'     => __('Operations Director Name Here', 'zod-core' ),
      )
		);

		$repeater_operations->add_control(
			'operations_link', array(
				'label' 	=> __( 'Operations Director Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
        ),
			)
		);
	
		$this->add_control(
			'operations_list',
			array(
				'label' 	=> __( 'Operations Director List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_operations->get_controls(),
				'default' 	=> array(
					array(
            'operations_name'	=> __( 'Operations Director Name Here', 'zod-core' ),
            'operations_link'	=> '',
          ),
				),
				'title_field' => '{{{ operations_name }}}',
			)
		);
	
		$this->end_controls_section();

		// Start Independent
		$this->start_controls_section(
			'tabs_independent',
			array(
				'label' => __( 'Independent Director', 'zod-core' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);
		
		$repeater_independent = new Repeater();

		$repeater_independent->add_control(
			'independent_name',
			array(
				'label'       => esc_html__( 'Independent Director Name', 'zod-core' ),
				'type'        => Controls_Manager::TEXT,
        'label_block' => true,
				'default'     => __('Independent Director Name Here', 'zod-core' ),
      )
		);

		$repeater_independent->add_control(
			'independent_link', array(
				'label' 	=> __( 'Independent Director Link', 'zod-core' ),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
        ),
			)
		);
	
		$this->add_control(
			'independent_list',
			array(
				'label' 	=> __( 'Independent Director List', 'zod-core' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_independent->get_controls(),
				'default' 	=> array(
					array(
            'independent_name'	=> __( 'Independent Director Name Here', 'zod-core' ),
            'independent_link'	=> '',
          ),
				),
				'title_field' => '{{{ independent_name }}}',
			)
		);
	
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings 	  = $this->get_settings_for_display();
		$output			  = '';

    $boc_item = '';
    foreach( $settings['boc_list'] as $item ){
      $boc_item .= sprintf(
        '
        <a href="%1$s"%2$s%3$s>
          %4$s
        </a>
        <br>
        ',
        $item['boc_link']['url'],
        $item['boc_link']['is_external'] ? ' target="_blank"' : '',
        $item['boc_link']['nofollow'] ? ' rel="nofollow"' : '',
        __( $item['boc_name'], 'zod-core' )
      );
    }

    $auditor_item = '';
    foreach( $settings['auditor_list'] as $item ){
      $auditor_item .= sprintf(
        '
        <a href="%1$s"%2$s%3$s>
          %4$s
        </a>
        <br>
        ',
        $item['auditor_link']['url'],
        $item['auditor_link']['is_external'] ? ' target="_blank"' : '',
        $item['auditor_link']['nofollow'] ? ' rel="nofollow"' : '',
        __( $item['auditor_name'], 'zod-core' )
      );
    }

    $presdir_item = '';
    foreach( $settings['presdir_list'] as $item ){
      $presdir_item .= sprintf(
        '
        <a href="%1$s"%2$s%3$s>
          %4$s
        </a>
        <br>
        ',
        $item['presdir_link']['url'],
        $item['presdir_link']['is_external'] ? ' target="_blank"' : '',
        $item['presdir_link']['nofollow'] ? ' rel="nofollow"' : '',
        __( $item['presdir_name'], 'zod-core' )
      );
    }

    $internal_auditor_item = '';
    foreach( $settings['internal_auditor_list'] as $item ){
      $internal_auditor_item .= sprintf(
        '
        <a href="%1$s"%2$s%3$s>
          %4$s
        </a>
        <br>
        ',
        $item['internal_auditor_link']['url'],
        $item['internal_auditor_link']['is_external'] ? ' target="_blank"' : '',
        $item['internal_auditor_link']['nofollow'] ? ' rel="nofollow"' : '',
        __( $item['internal_auditor_name'], 'zod-core' )
      );
    }

    $corp_sec_item = '';
    foreach( $settings['corp_sec_list'] as $item ){
      $corp_sec_item .= sprintf(
        '
        <a href="%1$s"%2$s%3$s>
          %4$s
        </a>
        <br>
        ',
        $item['corp_sec_link']['url'],
        $item['corp_sec_link']['is_external'] ? ' target="_blank"' : '',
        $item['corp_sec_link']['nofollow'] ? ' rel="nofollow"' : '',
        __( $item['corp_sec_name'], 'zod-core' )
      );
    }

    $findir_item = '';
    foreach( $settings['findir_list'] as $item ){
      $findir_item .= sprintf(
        '
        <a href="%1$s"%2$s%3$s>
          %4$s
        </a>
        <br>
        ',
        $item['findir_link']['url'],
        $item['findir_link']['is_external'] ? ' target="_blank"' : '',
        $item['findir_link']['nofollow'] ? ' rel="nofollow"' : '',
        __( $item['findir_name'], 'zod-core' )
      );
    }

    $marcommdir_item = '';
    foreach( $settings['marcommdir_list'] as $item ){
      $marcommdir_item .= sprintf(
        '
        <a href="%1$s"%2$s%3$s>
          %4$s
        </a>
        <br>
        ',
        $item['marcommdir_link']['url'],
        $item['marcommdir_link']['is_external'] ? ' target="_blank"' : '',
        $item['marcommdir_link']['nofollow'] ? ' rel="nofollow"' : '',
        __( $item['marcommdir_name'], 'zod-core' )
      );
    }

    $bdd_item = '';
    foreach( $settings['bdd_list'] as $item ){
      $bdd_item .= sprintf(
        '
        <a href="%1$s"%2$s%3$s>
          %4$s
        </a>
        <br>
        ',
        $item['bdd_link']['url'],
        $item['bdd_link']['is_external'] ? ' target="_blank"' : '',
        $item['bdd_link']['nofollow'] ? ' rel="nofollow"' : '',
        __( $item['bdd_name'], 'zod-core' )
      );
    }

    $operations_item = '';
    foreach( $settings['operations_list'] as $item ){
      $operations_item .= sprintf(
        '
        <a href="%1$s"%2$s%3$s>
          %4$s
        </a>
        <br>
        ',
        $item['operations_link']['url'],
        $item['operations_link']['is_external'] ? ' target="_blank"' : '',
        $item['operations_link']['nofollow'] ? ' rel="nofollow"' : '',
        __( $item['operations_name'], 'zod-core' )
      );
    }

    $independent_item = '';
    foreach( $settings['independent_list'] as $item ){
      $independent_item .= sprintf(
        '
        <a href="%1$s"%2$s%3$s>
          %4$s
        </a>
        <br>
        ',
        $item['independent_link']['url'],
        $item['independent_link']['is_external'] ? ' target="_blank"' : '',
        $item['independent_link']['nofollow'] ? ' rel="nofollow"' : '',
        __( $item['independent_name'], 'zod-core' )
      );
    }

    $output = sprintf(
      '
      <div class="content-ms">
        <figure class="org-chart cf">
          <div class="board">
            <ul class="columnOne director">
              <li>
                <span>
                  <div class="title"> Board Of Comissioners</div>
                  <div class="names boc">%1$s</div>
                </span>
              </li>
            </ul>
            <ul class="columnOne subdirector null">
            </ul>
            <ul class="columnOne subdirector">
              <li>
                <span>
                  <div class="title"> Audit Committee</div>
                  <div class="names audit-committee">%2$s</div>
                </span>
              </li>
            </ul>
            <ul class="columnOne">
              <li>
                <span>
                  <div class="title"> President Director</div>
                  <div class="names pres-dir">%3$s</div>
                </span>
              </li>
            </ul>
            <ul class="departments">
              <li class="toplevel">
                  <span>
                    <div class="title"> Internal Audit</div>
                    <div class="names internal-audit">%4$s</div>
                  </span>
              </li>
              <li class="toplevel">
                  <span>
                    <div class="title"> Corporate Secretary</div>
                    <div class="names corporate-sectretary">%5$s</div>
                  </span>
              </li>
            </ul>
            <ul class="departments">
              <li class="department">
                <span>
                  <div class="title"> Finance <br>Director </div>
                  <div class="names finance-director">%6$s</div>
                </span>
              </li>
              <li class="department">
                <span>
                  <div class="title"> Marketing <br>Director </div>
                  <div class="names marketing-director">%7$s</div>
                </span>
              </li>
              <li class="department">
                <span>
                  <div class="title"> Business Development <br>Director </div>
                  <div class="names business-development">%8$s</div>
                </span>
              </li>
              <li class="department operation-directors">
                <span>
                  <div class="title"> Operations <br>Directors </div>
                  <div class="names operation-directors">%9$s</div>
                </span>
              </li>
              <li class="department">
                <span>
                  <div class="title"> Independent <br>Directors </div>
                  <div class="names independent-directors">%10$s</div>
                </span>
              </li>
            </ul>
          </div>
        </figure>
      </div>
      ',
      !empty( $boc_item ) ? $boc_item : '',
      !empty( $auditor_item ) ? $auditor_item : '',
      !empty( $presdir_item ) ? $presdir_item : '',
      !empty( $internal_auditor_item ) ? $internal_auditor_item : '',
      !empty( $corp_sec_item ) ? $corp_sec_item : '',
      !empty( $findir_item ) ? $findir_item : '',
      !empty( $marcommdir_item ) ? $marcommdir_item : '',
      !empty( $bdd_item ) ? $bdd_item : '',
      !empty( $operations_item ) ? $operations_item : '',
      !empty( $independent_item ) ? $independent_item : '',
    );

		echo apply_filters( 'zod_core_mgmgt', $output );

	}
}