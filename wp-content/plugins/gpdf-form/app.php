<?php 
/*
Plugin Name: GPDF Form
Description: Dynamically populating data into PDF Form from gravity form. 
Plugin URI: https://linkpdfform.com
Author: Opcodespace <mehedee@opcodespace.com>
Author URI: http://opcodespace.com
Version: 0.1
Text Domain: lpfaddon
*/
if ( ! defined( 'ABSPATH' ) ) exit;
# CHECKS IF GRAVITY FORM IS ACTIVATED
register_activation_hook( __FILE__, 'lpf_link_pdf_plugin_activation_hook' );
function lpf_link_pdf_plugin_activation_hook(){
    if ( ! is_plugin_active( 'gravityforms/gravityforms.php' ) and current_user_can( 'activate_plugins' ) ) {
        wp_die('Sorry, but this plugin requires <a href="' . esc_url('https://www.gravityforms.com/') . '">Gravity Form</a> Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }
}

function lpf_gravity_form_activation_hook() {
    $class = 'notice notice-error';
    $message = __( 'GPDF Form can\'t work without activating Gravity Form.', 'lpfaddon' );
    if ( ! is_plugin_active( 'gravityforms/gravityforms.php' ) and current_user_can( 'activate_plugins' ) ) {
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }

}
add_action( 'admin_notices', 'lpf_gravity_form_activation_hook' );

define("LPF_PATH", plugin_dir_path(__FILE__));
define("LPF_VIEW_PATH", plugin_dir_path(__FILE__) . "view/");
define("LPF_ASSETSURL", plugins_url("assets/", __FILE__));

spl_autoload_register(function ($class_name) {
    if (false !== strpos($class_name, 'Lpf')) {
        include_once 'src/class-' .$class_name . '.php';
    }
});


# abstract
include_once 'src/abstract/class-LpfAbstractModule.php';
include_once 'src/abstract/class-LpfAbstractNotification.php';

include_once 'functions.php';

add_action('plugins_loaded', array('LpfEnqueue', 'init'));
add_action('plugins_loaded', array('LpfHook', 'init'));
add_action('plugins_loaded', array('LpfCustomPostType', 'init'));

# Install table
register_activation_hook(__FILE__, array('LpfInstallTable', 'fields_association'));
register_activation_hook(__FILE__, array('LpfInstallTable', 'generated_pdf'));

/**
 * Gravity form plugin add on
 */


define( 'LPF_ADDON_VERSION', '2.1' );

add_action( 'gform_loaded', array( 'LPF_AddOn_Bootstrap', 'load' ), 5 );

class LPF_AddOn_Bootstrap {

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        require_once( 'src/class-LpfGfaddon.php' );

        GFAddOn::register( 'LPFAddOn' );
    }

}

function lpf_addon() {
    return LPFAddOn::get_instance();
}