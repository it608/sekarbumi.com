<?php
/**
 * Plugin Name: Popup More
 * Description: Popup More is the most complete pop up plugin in the WordPress popup plugins.
 * Version: 2.5.7
 * Author: Felix Moira
 * Author URI:
 * License: GPLv2
 */
namespace YpmPopup;

define('YPM_MAIN_FILE', __FILE__);
define('YPM_FOLDER_NAME',  dirname(plugin_basename(__FILE__)));

require_once(dirname(__FILE__).'/boot.php');
require_once(YPM_POPUP_CLASSES.'popupMasterInit.php');

$popupMasterInstance = new popupMasterInit();