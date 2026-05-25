<?php
if (!class_exists('Skbm_General_Meeting')) {
  class Skbm_General_Meeting
  {

    private $post_type = 'general-meeting';

    function __construct()
    {
      add_action('init', array($this, 'skbm_general_meeting_init'));
      add_action('add_meta_boxes', array($this, 'skbm_general_meeting_register_meta_boxes'));
      add_action('save_post', array($this, 'skbm_save_meta_box'));
      add_action('template_redirect', array($this, 'skbm_general_meeting_redirect_single_page'));
      add_action('wp_enqueue_scripts', array($this, 'skbm_general_meeting_scripts'));
      add_action('pre_get_posts', array($this, 'skbm_general_meeting_set_query'));
      add_action('skbm_before_content', array($this, 'skbm_general_meeting_page_title'), 10);
      add_action('skbm_before_content', array($this, 'skbm_general_meeting_get_all_categories'), 15);
      add_action('wp_ajax_general_meeting_get_post', array($this, 'skbm_general_meeting_get_posts'));
      add_action('wp_ajax_nopriv_general_meeting_get_post', array($this, 'skbm_general_meeting_get_posts'));
    }

    /**
     * Init Custom post type.
     * Register custom post type into wp
     *
     *
     */
    public function skbm_general_meeting_init()
    {
      $labels = array(
        'name'                  => _x('General Meetings of the Shareholders', 'Post type general name', 'skbm-extender'),
        'singular_name'         => _x('General Meeting', 'Post type singular name', 'skbm-extender'),
        'menu_name'             => _x('General Meetings', 'Admin Menu text', 'skbm-extender'),
        'name_admin_bar'        => _x('General Meeting', 'Add New on Toolbar', 'skbm-extender'),
        'add_new'               => __('Add New', 'skbm-extender'),
        'add_new_item'          => __('Add New General Meeting', 'skbm-extender'),
        'new_item'              => __('New General Meeting', 'skbm-extender'),
        'edit_item'             => __('Edit General Meeting', 'skbm-extender'),
        'view_item'             => __('View General Meeting', 'skbm-extender'),
        'all_items'             => __('All General Meetings', 'skbm-extender'),
        'search_items'          => __('Search General Meetings', 'skbm-extender'),
        'parent_item_colon'     => __('Parent General Meetings:', 'skbm-extender'),
        'not_found'             => __('No general meetings found.', 'skbm-extender'),
        'not_found_in_trash'    => __('No general meetings found in Trash.', 'skbm-extender'),
        'featured_image'        => _x('General Meeting Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'skbm-extender'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'skbm-extender'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'skbm-extender'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'skbm-extender'),
        'archives'              => _x('General Meeting archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'skbm-extender'),
        'insert_into_item'      => _x('Insert into general meeting', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'skbm-extender'),
        'uploaded_to_this_item' => _x('Uploaded to this general meeting', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'skbm-extender'),
        'filter_items_list'     => _x('Filter general meetings list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'skbm-extender'),
        'items_list_navigation' => _x('General Meetings list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'skbm-extender'),
        'items_list'            => _x('General Meetings list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'skbm-extender'),
      );

      $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'general-meetings'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail'),
      );

      register_post_type('general-meeting', $args);

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

      register_taxonomy('general-meeting-year', array('general-meeting'), $args_taxonomy);
    }

    /**
     * Register meta box(es).
     */
    public function skbm_general_meeting_register_meta_boxes()
    {
      add_meta_box('general_meeting_metabox', __('General Meeting Metabox', 'skbm_extender'), array($this, 'skbm_general_meeting_display_callback'), 'general-meeting');
    }

    /**
     * Meta box(es) display callback.
     *
     * @param WP_Post $post Current post object.
     */
    public function skbm_general_meeting_display_callback(WP_Post $post)
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
        'skbm_general_meeting_link',
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
    public function skbm_general_meeting_redirect_single_page()
    {
      $post_type = 'general-meeting';
      if (is_singular($post_type)) {
        global $post;
        $redirectLink = get_post_type_archive_link($post_type);
        wp_redirect($redirectLink, 302);
        exit;
      }
    }

    /**
     * Register Script on General Meeting custom post type.
     *
     * 
     */
    public function skbm_general_meeting_scripts()
    {
      if (is_post_type_archive('general-meeting')) {
        wp_enqueue_script('skbm-general-meeting', SKBM_EXTENDER_MODULES_URI . '/general-meeting/assets/general-meeting.js', array(), SKBM_VERSION, true);
        wp_localize_script('skbm-general-meeting', 'skbmPublicAjax', array(
          'ajaxUrl' => admin_url('admin-ajax.php')
        ));
      }
    }

    /**
     * Override the main query on a custom post type archive.
     *
     * @param \WP_Query $wp_query
     */
    public function skbm_general_meeting_set_query($wp_query)
    {
      // Only check this on the main query.
      if ($wp_query->is_main_query() && is_post_type_archive('general-meeting') && !is_admin()) {
        $wp_query->set('posts_per_page', -1);
        $wp_query->set('post_status', 'publish');
        wp_reset_query();
      }
    }

    /**
     * Render Page Title.
     *
     */
    public function skbm_general_meeting_page_title()
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
    public function skbm_general_meeting_get_all_categories()
    {
      if (is_post_type_archive($this->post_type)) {
        $categories = get_terms(
          array(
            'taxonomy'    => 'general-meeting-year',
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

        echo apply_filters('skbm_general_meeting_categories', $output);
      }
    }


    public function skbm_general_meeting_get_posts()
    {
      $tax_query = array(
        array(
          'taxonomy'  => 'general-meeting-year',
          'field'     => 'id',
          'terms'     => array($_POST['catId']),
          'operator'  => 'IN'
        )
      );
      if ($_POST['catId'] == 'all') {
        // Query Arguments
        $args = array(
          'post_type'       => array('general-meeting'),
          'post_status'     => array('publish'),
          'posts_per_page'  => -1,
          'order'           => 'DESC',
          'orderby'         => 'date',
        );
      } else {
        // Query Arguments
        $args = array(
          'post_type'       => array('general-meeting'),
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
      $content  = true;

      // The Query
      if ($ajax_posts->have_posts()) {
        while ($ajax_posts->have_posts()) {
          $ajax_posts->the_post();
          ob_start();
          include SKBM_EXTENDER_MODULES_PATH . '/general-meeting/templates/archive-item-general-meeting.php';
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
  new Skbm_General_Meeting();
}
?>