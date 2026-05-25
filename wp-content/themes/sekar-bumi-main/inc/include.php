<?php

include_once get_template_directory() . '/inc/template-functions.php';

// Include Modules
foreach ( glob( get_template_directory() . '/inc/*/include.php' ) as $customizer_file ) {
    include_once $customizer_file;
}

// Include Modules
foreach ( glob( get_template_directory() . '/inc/modules/*/include.php' ) as $modules ) {
    include_once $modules;
}