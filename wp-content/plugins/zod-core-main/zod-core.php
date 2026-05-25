<?php
/*
Plugin Name: ZOD Core
Plugin URI:
description: ZOD Core Plugin.
Version: 1.1.0
Author: Zero One Digital
Author URI: https://zero-one-digital.com/
Text Domain: zod-core
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' ); // Die if accessed directly
}

define( 'ZOD_CORE_URI', plugin_dir_url( __FILE__ ) );
define( 'ZOD_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'ZOD_CORE_INC_URI', ZOD_CORE_URI . 'inc' );
define( 'ZOD_CORE_INC_DIR', ZOD_CORE_DIR . 'inc' );
define( 'ZOD_CORE_VERSION', '1.1.0' );

if( !function_exists( 'zod_debug' ) ){
    function zod_debug( $debug = '' ){
        echo '<pre>'. print_r( $debug, true ) .'</pre>';
    }
}

require ZOD_CORE_DIR . 'zod-core-functions.php';
require ZOD_CORE_DIR . 'class-zod-core-admin-init.php';