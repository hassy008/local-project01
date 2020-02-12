<?php
/*
Plugin Name: Opcodespace
Description: Custom coding and desgin
Plugin URI: http://opcodespace.com
Author: Md. Mehedee Rahman <mehedee@opcodespace.com>
Author URI: http://opcodespace.com
Version: 1.0
Text Domain: opcodespace
 */

define("OP_PATH", plugin_dir_path(__FILE__));
define("OP_VIEW_PATH", plugin_dir_path(__FILE__) . "view/");
define("OP_ASSET_PATH", plugin_dir_path(__FILE__) . "assets/"); //for using excel template or any other view file 
define("OP_ASSETSURL", plugins_url("assets/", __FILE__));

spl_autoload_register(function ($class_name) {
    if (false !== strpos($class_name, 'Ops')) {
        include_once 'src/class-' . $class_name . '.php';
    }
});

// class Autoloader {
//     static public function loader($className) {
//         $filename = PFM_PATH . "src/" . str_replace("\\", '/', $className) . ".php";
//         if (file_exists($filename)) {
//             include($filename);
//             if (class_exists($className)) {
//                 return TRUE;
//             }
//         }
//         return FALSE;
//     }
// }
// spl_autoload_register('Autoloader::loader');

# abstract
include_once 'src/abstract/class-AbstractModule.php';
include_once 'src/abstract/class-AbstractNotification.php';

include_once 'functions.php';
include_once 'page-options.php';
require_once 'inc/vendor/autoload.php';
// require __dir__ . '/vendor/autoload.php'

//add_action( add all the Action class)
add_action('plugins_loaded', array('OpsShortCode', 'init'));
add_action('plugins_loaded', array('OpsAction', 'init'));
add_action('plugins_loaded', array('OpsEnqueue', 'init'));
add_action('plugins_loaded', array('OpsAjaxAction', 'init'));
add_action('plugins_loaded', array('OpsFilter', 'init'));
add_action('plugins_loaded', array('OpsHook', 'init'));

# Install table
//register_activation_hook(__FILE__, array('OpsInstallTable', 'token'));
register_activation_hook(__FILE__, array('OpsInstallTable', 'token'));
register_activation_hook(__FILE__, array('OpsInstallTable', 'crud'));
register_activation_hook(__FILE__, array('OpsInstallTable', 'progress'));
register_activation_hook(__FILE__, array('OpsInstallTable', 'schedules'));
