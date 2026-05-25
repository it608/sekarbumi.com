<?php
if( !class_exists( 'ZOD_Core_404' ) ){
    class ZOD_Core_404 {

        public function __construct(){
            add_filter( '404_template', array( $this, 'zod_core_404_template' ), 11 );
        }

        public function zod_core_404_template( $page_template ){
            global $wp_query;
            $settings = get_option('zod_core_settings');
            if( is_404() && !empty( $settings['404_page'] ) ){
                $wp_query = null;
                $wp_query = new WP_Query(
                    array(
                        'page_id' => !empty( $settings['404_page'] ) ? $settings['404_page'] : ''
                    )
                );
                $wp_query->the_post();
                $page_template = get_page_template();
                rewind_posts();
            }
            return $page_template;
        }

    }

    new ZOD_Core_404();
}