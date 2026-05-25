<?php

/**
 * Plugin Name: Sekarbumi Extender
 * Description: For extending Sekarbumi Themes
 * Author: Zero One Digital
 * Author URI: https://zero-one-digital.com/
 * Version: 1.0.0
 * Text Domain: skbm-extender
 * Requires at least: 5.2
 * Requires PHP: 7.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
  exit;
}

if (!defined('SKBM_EXTENDER_PATH')) {
  define('SKBM_EXTENDER_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
}
if (!defined('SKBM_EXTENDER_URI')) {
  define('SKBM_EXTENDER_URI', untrailingslashit(plugin_dir_url(__FILE__)));
}
if (!defined('SKBM_EXTENDER_MODULES_PATH')) {
  define('SKBM_EXTENDER_MODULES_PATH', SKBM_EXTENDER_PATH . '/modules');
}
if (!defined('SKBM_EXTENDER_MODULES_URI')) {
  define('SKBM_EXTENDER_MODULES_URI', SKBM_EXTENDER_URI . '/modules');
}
if (!defined('SKBM_VERSION')) {
  define('SKBM_VERSION', '1.0.0');
}

function sekarbumi_extender_register_admin_script()
{
  wp_enqueue_style('skbm-metabox-style', SKBM_EXTENDER_URI . '/assets/css/metabox.css', array(), SKBM_VERSION);
}
add_action('admin_enqueue_scripts', 'sekarbumi_extender_register_admin_script');

function sekarbumi_extender_register_script()
{
  wp_enqueue_style('skbm-extender-style', SKBM_EXTENDER_URI . '/assets/css/sekarbumi-extender.css', array(), SKBM_VERSION);
  wp_enqueue_script('skbm-extender-script', SKBM_EXTENDER_URI . '/assets/js/sekarbumi-extender.js', array('jquery'), SKBM_VERSION);
  wp_enqueue_style('slick-style', SKBM_EXTENDER_URI . '/assets/css/slick.css', array(), SKBM_VERSION);
  wp_enqueue_script('slick-script', SKBM_EXTENDER_URI . '/assets/js/slick.min.js', array(), SKBM_VERSION);
}
add_action('wp_enqueue_scripts', 'sekarbumi_extender_register_script');

require SKBM_EXTENDER_MODULES_PATH . '/include.php';

function enqueue_translate_script()
{
  if (is_archive() && 'id_ID' === get_locale()) {
    wp_enqueue_script('translate', SKBM_EXTENDER_URI . '/assets/js/translate.js', array(), SKBM_VERSION);
  }
}
add_action('wp_enqueue_scripts', 'enqueue_translate_script');
