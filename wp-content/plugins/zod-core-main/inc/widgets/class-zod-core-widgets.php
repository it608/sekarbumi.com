<?php
if (!class_exists('ZOD_Core_Widgets')) {
  class ZOD_Core_Widgets
  {
    protected static $instance = null;

    public static function get_instance()
    {
      if (!isset(static::$instance)) {
        static::$instance = new static;
      }

      return static::$instance;
    }

    protected function __construct()
    {
      $this->zod_core_constant();
      $this->zod_core_register_scripts();

      // Include Widgets
      require_once('elements/animated-headline/animated-headline.php');
      require_once('elements/carousel/carousel.php');
      require_once('elements/card/card.php');
      require_once('elements/tabs/tabs.php');
      require_once('elements/image-tabs/image-tabs.php');
      require_once('elements/posts/posts.php');
      require_once('elements/management-structure/management-structure.php');
      require_once('elements/team-member/team-member.php');
      require_once('elements/date/date.php');
      require_once('elements/corp-gov/corp-gov.php');

      add_action('elementor/elements/categories_registered', array($this, 'zod_core_widgets_add_elementor_widget_categories'));
      add_action('elementor/widgets/widgets_registered', array($this, 'zod_core_widgets_register_widgets'));
    }

    public function zod_core_constant()
    {
      define('ZOD_CORE_WIDGETS_URI', ZOD_CORE_INC_URI . '/widgets');
      define('ZOD_CORE_WIDGETS_DIR', ZOD_CORE_INC_DIR . '/widgets');
    }

    public function zod_core_register_scripts()
    {
      if (!is_admin()) {
        wp_register_script('zod-core-uikit-script', ZOD_CORE_WIDGETS_URI . '/assets/js/uikit.min.js', array(), '3.14.1', true);
        wp_register_style('zod-core-uikit-style', ZOD_CORE_WIDGETS_URI . '/assets/css/uikit.min.css', array('elementor-frontend'), '3.14.1', true);

        wp_enqueue_scripts('zod-core-uikit-script');
        wp_enqueue_style('zod-core-uikit-style');
      }
      wp_register_script('slick-script', ZOD_CORE_WIDGETS_URI . '/assets/js/slick.min.js', array('elementor-frontend'), '1.8.1', true);
      wp_register_style('slick-style', ZOD_CORE_WIDGETS_URI . '/assets/css/slick.css', array(), '1.8.1');
    }

    public function zod_core_widgets_add_elementor_widget_categories($elements_manager)
    {

      $category = 'zod';

      $elements_manager->add_category(
        $category,
        array(
          'title' => __('ZOD Core Elements', 'zod-core'),
          'icon' => 'fa fa-plug',
        )
      );

      $reorder_cats = function () use ($category) {
        uksort($this->categories, function ($key_one, $key_two) use ($category) {
          if (substr($key_one, 0, 4) == $category) {
            return -1;
          }
          if (substr($key_two, 0, 4) == $category) {
            return 1;
          }
          return 0;
        });
      };
      $reorder_cats->call($elements_manager);
    }

    public function zod_core_widgets_register_widgets()
    {
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\ZOD_Core_Animated_Headline());
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\ZOD_Core_Carousel());
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\ZOD_Core_Widgets_Card());
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\ZOD_Core_Tabs());
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\ZOD_Core_Image_Tabs());
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\ZOD_Core_Posts());
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\ZOD_Core_Management_Structure());
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\ZOD_Core_Team_Member());
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\ZOD_Core_Corp_Gov());
    }
  }

  ZOD_Core_Widgets::get_instance();
}
