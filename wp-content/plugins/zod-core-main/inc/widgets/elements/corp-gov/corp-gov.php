<?php

namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class ZOD_Core_Corp_Gov extends Widget_Base
{
	public function __construct($data = [], $args = null)
	{
		parent::__construct($data, $args);

		wp_register_style('zod-corp-gov-style', ZOD_CORE_WIDGETS_URI . '/elements/corp-gov/assets/corp-gov.css', array(), ZOD_CORE_VERSION);
	}

	public function get_name()
	{
		return 'zod-corp-gov';
	}

	public function get_title()
	{
		return __('Corporate Governance', 'zod-core');
	}

	public function get_style_depends()
	{
		return array('zod-corp-gov-style');
	}

	public function get_categories()
	{
		return array('zod');
	}

	protected function register_controls()
	{
		// Start Section Level 1 
		$this->start_controls_section(
			'tabs_l1',
			array(
				'label' => __('Level 1', 'zod-core'),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater_l1 = new Repeater();

		$repeater_l1->add_control(
			'l1_name',
			array(
				'label'       => esc_html__('Level 1 Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 1 Text here', 'zod-core'),
			)
		);

		$repeater_l1->add_control(
			'l1_name_highlighted',
			array(
				'label'       => esc_html__('Level 1 Highlighted Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 1 Highlighted Text here', 'zod-core'),
			)
		);


		$repeater_l1->add_control(
			'l1_link',
			array(
				'label' 	=> __('Level 1 Link', 'zod-core'),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				),
			)
		);

		$this->add_control(
			'l1_list',
			array(
				'label' 	=> __('Level 1', 'zod-core'),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_l1->get_controls(),
				'default' 	=> array(
					array(
						'l1_name_highlighted'	=> __('Level 1 highlighted here', 'zod-core'),
						'l1_name'	=> __('Level 1 here', 'zod-core'),
						'l1_link'	=> '',
					),
				),
				'title_field' => '{{{ l1_name }}}',
			)
		);
		$this->end_controls_section();
		//End Level 1 

		//Start Level 2_1
		$this->start_controls_section(
			'tabs_l2_1',
			array(
				'label' => __('Level 2_1', 'zod-core'),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater_l2_1 = new Repeater();

		$repeater_l2_1->add_control(
			'l2_1_name_highlighted',
			array(
				'label'       => esc_html__('Level 2_1 Highlighted Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 2_1 Text Highlighted Text here', 'zod-core'),
			)
		);

		$repeater_l2_1->add_control(
			'l2_1_name',
			array(
				'label'       => esc_html__('Level 2_1  Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 2_1 Text here', 'zod-core'),
			)
		);

		$repeater_l2_1->add_control(
			'l2_1_link',
			array(
				'label' 	=> __('Level 2_1 Link', 'zod-core'),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				),
			)
		);

		$this->add_control(
			'l2_1_list',
			array(
				'label' 	=> __('Level 2_1 List', 'zod-core'),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_l2_1->get_controls(),
				'default' 	=> array(
					array(
						'l2_1_name_highlighted'	=> __('Level 2_1 Highlighted here', 'zod-core'),
						'l2_1_name'	=> __('Level 2_1 here', 'zod-core'),
						'l2_1_link'	=> '',
					),
				),
				'title_field' => '{{{ l2_1_name }}}',
			)
		);

		$this->end_controls_section();
		//End Level 2_1

		//Start Level 2_2
		$this->start_controls_section(
			'tabs_l2_2',
			array(
				'label' => __('Level 2_2', 'zod-core'),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater_l2_2 = new Repeater();

		$repeater_l2_2->add_control(
			'l2_2_name_highlighted',
			array(
				'label'       => esc_html__('Level 2_2 Highlighted Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 2_2 Highlighted Text here', 'zod-core'),
			)
		);

		$repeater_l2_2->add_control(
			'l2_2_name',
			array(
				'label'       => esc_html__('Level 2_2 Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 2_2 Text here', 'zod-core'),
			)
		);

		$repeater_l2_2->add_control(
			'l2_2_link',
			array(
				'label' 	=> __('Level 2_2 Link', 'zod-core'),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				),
			)
		);

		$this->add_control(
			'l2_2_list',
			array(
				'label' 	=> __('Level 2_2 List', 'zod-core'),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_l2_2->get_controls(),
				'default' 	=> array(
					array(
						'l2_2_name_highlighted' => ('Level 2_2 highlighted here'),
						'l2_2_name'	=> __('Level 2_2 here', 'zod-core'),
						'l2_2_link'	=> '',
					),
				),
				'title_field' => '{{{ l2_2_name }}}',
			)
		);

		$this->end_controls_section();
		//End Level 2_2

		//Start Level 2_1_1
		$this->start_controls_section(
			'tabs_l2_1_1',
			array(
				'label' => __('Level 2_1_1', 'zod-core'),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater_l2_1_1 = new Repeater();

		$repeater_l2_1_1->add_control(
			'l2_1_1_name_highlighted',
			array(
				'label'       => esc_html__('Level 2_1_1 Highlighted Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 2_1_1 Highlighted Text here', 'zod-core'),
			)
		);

		$repeater_l2_1_1->add_control(
			'l2_1_1_name',
			array(
				'label'       => esc_html__('Level 2_1_1 Title Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 2_1_1 Title Text here', 'zod-core'),
			)
		);

		$repeater_l2_1_1->add_control(
			'l2_1_1_link',
			array(
				'label' 	=> __('Level 2_1_1 Link', 'zod-core'),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				),
			)
		);

		$this->add_control(
			'l2_1_1_list',
			array(
				'label' 	=> __('Level 2_1_1 List', 'zod-core'),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_l2_1_1->get_controls(),
				'default' 	=> array(
					array(
						'l2_1_1_name_highlighted'	=> __('Level 2_1_1 highlighted here', 'zod-core'),
						'l2_1_1_name'	=> __('Level 2_1_1 here', 'zod-core'),
						'l2_1_1_link'	=> '',
					),
				),
				'title_field' => '{{{ l2_1_1_name }}}',
			)
		);

		$this->end_controls_section();
		//End Level 2_1_1

		//Start Level 2_1_2
		$this->start_controls_section(
			'tabs_l2_1_2',
			array(
				'label' => __('Level 2_1_2', 'zod-core'),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater_l2_1_2 = new Repeater();

		$repeater_l2_1_2->add_control(
			'l2_1_2_name_highlighted',
			array(
				'label'       => esc_html__('Level 2_1_2 Highlighted Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 2_1_2 Highlighted Text here', 'zod-core'),
			)
		);

		$repeater_l2_1_2->add_control(
			'l2_1_2_name',
			array(
				'label'       => esc_html__('Level 2_1_2 Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 2_1_2 Text here', 'zod-core'),
			)
		);

		$repeater_l2_1_2->add_control(
			'l2_1_2_link',
			array(
				'label' 	=> __('Level 2_1_2 Link', 'zod-core'),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				),
			)
		);

		$this->add_control(
			'l2_1_2_list',
			array(
				'label' 	=> __('Level 2_1_2 List', 'zod-core'),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_l2_1_2->get_controls(),
				'default' 	=> array(
					array(
						'l2_1_2_name_highlighted'	=> __('Level 2_1_2 highlighted here', 'zod-core'),
						'l2_1_2_name'	=> __('Level 2_1_2 here', 'zod-core'),
						'l2_1_2_link'	=> '',
					),
				),
				'title_field' => '{{{ l2_1_2_name }}}',
			)
		);
		$this->end_controls_section();
		//End Level 2_1_2

		//Start Level 2_2_1 
		$this->start_controls_section(
			'tabs_l2_2_1',
			array(
				'label' => __('Level 2_2_1', 'zod-core'),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater_l2_2_1 = new Repeater();

		$repeater_l2_2_1->add_control(
			'l2_2_1_name_highlighted',
			array(
				'label'       => esc_html__('Level 2_2_1 Highlighted Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 2_2_1 Highlighted Text here', 'zod-core'),
			)
		);

		$repeater_l2_2_1->add_control(
			'l2_2_1_name',
			array(
				'label'       => esc_html__('Level 2_2_1 Title Text', 'zod-core'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __('Level 2_2_1 Title Text here', 'zod-core'),
			)
		);

		$repeater_l2_2_1->add_control(
			'l2_2_1_link',
			array(
				'label' 	=> __('Level 2_2_1 Link', 'zod-core'),
				'type' 		=> Controls_Manager::URL,
				'default' => array(
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				),
			)
		);

		$this->add_control(
			'l2_2_1_list',
			array(
				'label' 	=> __('Level 2_2_1 List', 'zod-core'),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater_l2_2_1->get_controls(),
				'default' 	=> array(
					array(
						'l2_2_1_name_highlighted'	=> __('Level 2_2_1 highlighted here', 'zod-core'),
						'l2_2_1_name'	=> __('Level 2_2_1 here', 'zod-core'),
						'l2_2_1_link'	=> '',
					),
				),
				'title_field' => '{{{ l2_2_1_name }}}',
			)
		);

		$this->end_controls_section();
		//End Level 2_2_1
	}

	protected function render()
	{
		$settings 	  = $this->get_settings_for_display();
		$output			  = '';

		$l1_item = '';
		foreach ($settings['l1_list'] as $item) {
			$l1_item .= sprintf(
				'
        <a href="%1$s"%2$s%3$s>
          %4$s
		<p class="indo-text">%5$s</p>
        </a>

        ',
				$item['l1_link']['url'],
				$item['l1_link']['is_external'] ? ' target="_blank"' : '',
				$item['l1_link']['nofollow'] ? ' rel="nofollow"' : '',
				__($item['l1_name_highlighted'], 'zod-core'),
				__($item['l1_name'], 'zod-core')
			);
		}

		$l2_1_item = '';
		foreach ($settings['l2_1_list'] as $item) {
			$l2_1_item .= sprintf(
				'
        <a href="%1$s"%2$s%3$s>
          %4$s
		<p class="indo-text">%5$s</p>
        </a>

        ',
				$item['l2_1_link']['url'],
				$item['l2_1_link']['is_external'] ? ' target="_blank"' : '',
				$item['l2_1_link']['nofollow'] ? ' rel="nofollow"' : '',
				__($item['l2_1_name_highlighted'], 'zod-core'),
				__($item['l2_1_name'], 'zod-core')
			);
		}

		$l2_2_item = '';
		foreach ($settings['l2_2_list'] as $item) {
			$l2_2_item .= sprintf(
				'
        <a href="%1$s"%2$s%3$s>
          %4$s
		<p class="indo-text">%5$s</p>  
        </a>

        ',
				$item['l2_2_link']['url'],
				$item['l2_2_link']['is_external'] ? ' target="_blank"' : '',
				$item['l2_2_link']['nofollow'] ? ' rel="nofollow"' : '',
				__($item['l2_2_name_highlighted'], 'zod-core'),
				__($item['l2_2_name'], 'zod-core')
			);
		}

		$l2_1_1_item = '';
		foreach ($settings['l2_1_1_list'] as $item) {
			$l2_1_1_item .= sprintf(
				'
        <a href="%1$s"%2$s%3$s>
          %4$s
		<p class="indo-text">%5$s</p>   
        </a>

        ',
				$item['l2_1_1_link']['url'],
				$item['l2_1_1_link']['is_external'] ? ' target="_blank"' : '',
				$item['l2_1_1_link']['nofollow'] ? ' rel="nofollow"' : '',
				__($item['l2_1_1_name_highlighted'], 'zod-core'),
				__($item['l2_1_1_name'], 'zod-core')
			);
		}

		$l2_1_2_item = '';
		foreach ($settings['l2_1_2_list'] as $item) {
			$l2_1_2_item .= sprintf(
				'
        <a href="%1$s"%2$s%3$s>
          %4$s
		<p class="indo-text">%5$s</p>   
        </a>

        ',
				$item['l2_1_2_link']['url'],
				$item['l2_1_2_link']['is_external'] ? ' target="_blank"' : '',
				$item['l2_1_2_link']['nofollow'] ? ' rel="nofollow"' : '',
				__($item['l2_1_2_name_highlighted'], 'zod-core'),
				__($item['l2_1_2_name'], 'zod-core')
			);
		}

		$l2_2_1_item = '';
		foreach ($settings['l2_2_1_list'] as $item) {
			$l2_2_1_item .= sprintf(
				'
        <a href="%1$s"%2$s%3$s>
          %4$s
		<p class="indo-text">%5$s</p>   
        </a>
        ',
				$item['l2_2_1_link']['url'],
				$item['l2_2_1_link']['is_external'] ? ' target="_blank"' : '',
				$item['l2_2_1_link']['nofollow'] ? ' rel="nofollow"' : '',
				__($item['l2_2_1_name_highlighted'], 'zod-core'),
				__($item['l2_2_1_name'], 'zod-core')
			);
		}

		$output = sprintf(
			'
		<div class="content-corgov">
		<figure class="org-chart cf">
			<div class="board">
			<ul class="columnOne">
				<li>
				<span>
					<div class="names">
					%1$s
					</div>
				</span>
				</li>
			</ul>
			<ul class="departments">
				<li class="toplevel direksi dewan">
				<span>
					<div class="names below">
					%2$s
					</div>
				</span>
				<ul class="below departments direksi sekretaris">
					<li class="below toplevel direksi sekretaris">
					<span>
						<div class="names below">
						%4$s
						</div>
					</span>
					</li>
					<li class="below toplevel direksi">
					<span>
						<div class="names below">
						%5$s
						</div>
					</span>
					</li>
				</ul>
				</li>
				<li class="toplevel komisaris">
				<span>
					<div class="names below">
					%3$s
					</div>
				</span>
				<ul class="below departments audit">
					<li class="below toplevel right">
					<span>
						<div class="names below">
						%6$s
						</div>
					</span>
					</li>
				</ul>
				</li>
			</ul>
			</div>
		</figure>
		</div>
      ',
			!empty($l1_item) ? $l1_item : '',
			!empty($l2_1_item) ? $l2_1_item : '',
			!empty($l2_2_item) ? $l2_2_item : '',
			!empty($l2_1_1_item) ? $l2_1_1_item : '',
			!empty($l2_1_2_item) ? $l2_1_2_item : '',
			!empty($l2_2_1_item) ? $l2_2_1_item : '',

		);

		echo apply_filters('zod_core_corpgov', $output);
	}
}
