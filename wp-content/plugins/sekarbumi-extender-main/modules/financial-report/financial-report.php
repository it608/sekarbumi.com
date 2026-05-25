<?php
if (!class_exists('Skbm_Financial_Report')) {
  class Skbm_Financial_Report
  {

    private $post_type = 'financial-report';

    function __construct()
    {
      add_action('init', array($this, 'skbm_financial_report_init'));
      add_action('add_meta_boxes', array($this, 'skbm_financial_report_register_meta_boxes'));
      add_action('save_post', array($this, 'skbm_save_meta_box'));
      add_action('template_redirect', array($this, 'skbm_financial_report_redirect_single_page'));
      add_action('wp_enqueue_scripts', array($this, 'skbm_financial_report_scripts'));
      add_action('pre_get_posts', array($this, 'skbm_financial_report_set_query'));
      add_action('skbm_before_content', array($this, 'skbm_financial_report_page_title'), 10);
      add_action('skbm_before_content', array($this, 'skbm_financial_report_get_all_categories'), 15);
      add_action('wp_ajax_financial_report_get_post', array($this, 'skbm_financial_report_get_posts'));
      add_action('wp_ajax_nopriv_financial_report_get_post', array($this, 'skbm_financial_report_get_posts'));
    }

    /**
     * Init Custom post type.
     * Register custom post type into wp
     *
     *
     */
    public function skbm_financial_report_init()
    {
      $labels = array(
        'name'                  => _x('Financial Reports', 'Post type general name', 'skbm-extender'),
        'singular_name'         => _x('Financial Report', 'Post type singular name', 'skbm-extender'),
        'menu_name'             => _x('Financial Reports', 'Admin Menu text', 'skbm-extender'),
        'name_admin_bar'        => _x('Financial Report', 'Add New on Toolbar', 'skbm-extender'),
        'add_new'               => __('Add New', 'skbm-extender'),
        'add_new_item'          => __('Add New Financial Report', 'skbm-extender'),
        'new_item'              => __('New Financial Report', 'skbm-extender'),
        'edit_item'             => __('Edit Financial Report', 'skbm-extender'),
        'view_item'             => __('View Financial Report', 'skbm-extender'),
        'all_items'             => __('All Financial Reports', 'skbm-extender'),
        'search_items'          => __('Search Financial Reports', 'skbm-extender'),
        'parent_item_colon'     => __('Parent Financial Reports:', 'skbm-extender'),
        'not_found'             => __('No financial reports found.', 'skbm-extender'),
        'not_found_in_trash'    => __('No financial reports found in Trash.', 'skbm-extender'),
        'featured_image'        => _x('Financial Report Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'skbm-extender'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'skbm-extender'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'skbm-extender'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'skbm-extender'),
        'archives'              => _x('Financial Report archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'skbm-extender'),
        'insert_into_item'      => _x('Insert into financial report', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'skbm-extender'),
        'uploaded_to_this_item' => _x('Uploaded to this financial report', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'skbm-extender'),
        'filter_items_list'     => _x('Filter financial reports list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'skbm-extender'),
        'items_list_navigation' => _x('Financial Reports list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'skbm-extender'),
        'items_list'            => _x('Financial Reports list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'skbm-extender'),
      );

      $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'financial-reports'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail'),
      );

      register_post_type('financial-report', $args);

      // Add new taxonomy, make it hierarchical (like categories)
      $taxonomy = array(
        'name'              => _x('Years', 'taxonomy general name', 'skbm-extender'),
        'singular_name'     => _x('Year', 'taxonomy singular name', 'skbm-extender'),
        'search_items'      => __('Search Years', 'skbm-extender'),
        'all_items'         => __('All Years', 'skbm-extender'),
        'parent_item'       => __('Parent Year', 'skbm-extender'),
        'parent_item_colon' => __('Parent Year:', 'skbm-extender'),
        'edit_item'         => __('Edit Year', 'skbm-extender'),
        'update_item'       => __('Update Year', 'skbm-extender'),
        'add_new_item'      => __('Add New Year', 'skbm-extender'),
        'new_item_name'     => __('New Year Name', 'textdomain'),
        'menu_name'         => __('Year', 'textdomain'),
      );

      $args_taxonomy = array(
        'hierarchical'      => true,
        'labels'            => $taxonomy,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'pbd-years'),
      );

      register_taxonomy('financial-report-year', array('financial-report'), $args_taxonomy);
    }

    /**
     * Register meta box(es).
     */
    public function skbm_financial_report_register_meta_boxes()
    {
      add_meta_box('financial_report_metabox', __('Financial Report Metabox', 'skbm_extender'), array($this, 'skbm_financial_report_display_callback'), 'financial-report');
    }

    /**
     * Meta box(es) display callback.
     *
     * @param WP_Post $post Current post object.
     */
    public function skbm_financial_report_display_callback(WP_Post $post)
    {
      include('metabox/metabox-form.php');
    }

    /**
     * Save meta box content.
     *
     * @param int $post_id Post ID
     */
    public function skbm_save_meta_box(int $post_id)
    {
      if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
      if ($parent_id = wp_is_post_revision($post_id)) {
        $post_id = $parent_id;
      }
      $fields = [
        'skbm_financial_report_link',
      ];
      foreach ($fields as $field) {
        if (array_key_exists($field, $_POST)) {
          update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
      }
    }

    /**
     * Redirect single page custom post type into archive (No Single Page).
     *
     * 
     */
    public function skbm_financial_report_redirect_single_page()
    {
      if (is_singular($this->post_type)) {
        global $post;
        $redirect_link = get_post_type_archive_link($this->post_type);
        wp_redirect($redirect_link, 302);
        exit;
      }
    }

    /**
     * Register Script on Financial Report custom post type.
     *
     * 
     */
    public function skbm_financial_report_scripts()
    {
      if (is_post_type_archive('financial-report')) {
        wp_enqueue_script('skbm-financial-report', SKBM_EXTENDER_MODULES_URI . '/financial-report/assets/financial-report.js', array(), SKBM_VERSION, true);
        wp_localize_script('skbm-financial-report', 'skbmPublicAjax', array(
          'ajaxUrl' => admin_url('admin-ajax.php')
        ));
      }
    }

    /**
     * Override the main query on a custom post type archive.
     *
     * @param \WP_Query $wp_query
     */
    public function skbm_financial_report_set_query($wp_query)
    {
      if ($wp_query->is_main_query() && is_post_type_archive('financial-report') && !is_admin()) {
        $wp_query->set('posts_per_page', -1);
        $wp_query->set('post_status', 'publish');
        wp_reset_query();
      }
    }

    /**
     * Render Page Title.
     *
     */
    public function skbm_financial_report_page_title()
    {
      if (is_post_type_archive($this->post_type)) {
?>
        <header class="page-header uk-margin-medium-bottom">
          <?php
          the_archive_title('<h2 class="page-title">', '</h2>');
          ?>
        </header><!-- .page-header -->
<?php
      }
    }

    /**
     * Get all categories on custom post type
     *
     */
    public function skbm_financial_report_get_all_categories()
    {
      if (is_post_type_archive($this->post_type)) {
        $categories = get_terms(
          array(
            'taxonomy'    => 'financial-report-year',
            'parent'      => 0,
            'order'       => 'DESC',
            'hide_empty'  => false
          )
        );

        $output     = '';

        $output .= '<div class="skbm-cpt-tax-switcher uk-flex uk-margin-medium-bottom"><h5 class="uk-margin-remove">' . __('Select Year:', 'skbm-extender') . '</h5><div class="uk-width-expand uk-position-relative uk-padding uk-padding-remove-top uk-padding-remove-bottom">';
        if (is_array($categories)) {
          $output .= '<ul class="skbm-cpt-tax-switcher__inner">';
          $output .= '<li class="uk-text-center"><a href="#" data-category-id="all">All</a></li>';
          foreach ($categories as $category) {
            $output .= sprintf(
              '
                <li class="uk-text-center"><a href="#" data-category-id="%1$s">%2$s</a></li>
                ',
              esc_attr($category->term_taxonomy_id),
              esc_html__($category->name, 'skbm-extender')
            );
          }
          $output .= '</ul>';
          $output .= '<a href="#" class="cpt-prev uk-position-center-left"><span data-uk-icon="icon: chevron-left; ratio: 1.2"></span></a><a href="#" class="cpt-next uk-position-center-right"><span data-uk-icon="icon: chevron-right; ratio: 1.5"></span></a>';
        }
        $output .= '</div></div>';
        echo apply_filters('skbm_financial_report_categories', $output);
      }
    }


    public function skbm_financial_report_get_posts()
    {
      $tax_query = array(
        array(
          'taxonomy'  => 'financial-report-year',
          'field'     => 'id',
          'terms'     => array($_POST['catId']),
          'operator'  => 'IN'
        )
      );
      if ($_POST['catId'] == 'all') {
        // Query Arguments
        $args = array(
          'post_type'       => array('financial-report'),
          'post_status'     => array('publish'),
          'posts_per_page'  => -1,
          'order'           => 'DESC',
          'orderby'         => 'date',
        );
      } else {
        // Query Arguments
        $args = array(
          'post_type'       => array('financial-report'),
          'post_status'     => array('publish'),
          'posts_per_page'  => -1,
          'order'           => 'DESC',
          'orderby'         => 'date',
          'tax_query'       => $tax_query
        );
      }

      // The Query
      $ajax_posts = new WP_Query($args);

      $response = '';

      // The Query
      if ($ajax_posts->have_posts()) {
        while ($ajax_posts->have_posts()) {
          $ajax_posts->the_post();
          ob_start();
          include SKBM_EXTENDER_MODULES_PATH . '/financial-report/templates/archive-item-financial-report.php';
          $response .= ob_get_clean();
          $content = true;
        }
      } else {
        $response .= '<h4>' . __('No Item Found', 'skbm-extender') . '</h4>';
        $content = false;
      }

      $result = array(
        'hasContent'  => $content,
        'html'        => $response,
      );

      echo json_encode($result);

      wp_reset_query();
      exit(); // leave ajax call
    }
  }
  new Skbm_Financial_Report();
}
?>