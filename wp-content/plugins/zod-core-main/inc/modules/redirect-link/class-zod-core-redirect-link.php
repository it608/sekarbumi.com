<?php

if( !class_exists( 'ZOD_Core_Redirect_Link' ) ){
    class ZOD_Core_Redirect_Link {

        public function __construct(){
            add_action( 'template_redirect', array( $this, 'zod_core_redirect_link_page' ) );
        }

        public function zod_core_redirect_link_page() {

            $settings               = get_option('zod_core_settings');
            $redirect_link          = isset( $settings['redirect_link'] ) ? $settings['redirect_link'] : '';

            if( !empty( $redirect_link ) ){
                foreach( $redirect_link as $item ){
                    $this->zod_core_redirect_execute( $item['redirect_from'], $item['redirect_to'] );
                }
            }
        }

        public function zod_core_redirect_execute( $from, $urlto ){

            $protocol = '';
            
            if( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
                $protocol = 'https://';
            } else {
                $protocol = 'http://';
            }

            $currenturl             = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $currenturl_relative    = wp_make_link_relative( $currenturl );
            $relative_link          = wp_make_link_relative( $from );

            if( $currenturl_relative === $relative_link ){
                wp_redirect( $urlto, 301 );
                exit();
            }
        }
    }

    new ZOD_Core_Redirect_Link();
}