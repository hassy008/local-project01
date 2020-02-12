<?php

/*
Plugin Name: Woo User Tag
Description: Custom user email notification
Plugin URI: http://opcodespace.com
Author: Md. Mehedee Rahman <mehedee@opcodespace.com>
Author URI: http://opcodespace.com
Version: 1.0
Text Domain: opcodespace
 */
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL); 

define("WUT_PATH", plugin_dir_path(__FILE__));
define("WUT_VIEW_PATH", plugin_dir_path(__FILE__) . "view/");
define("WUT_ASSETSURL", plugins_url("assets/", __FILE__));

/** class autoloader **/
class WutAutoloader
{
    public static function loader($className)
    {
        $filename = WUT_PATH . "src/" . str_replace("\\", '/', $className) . ".php";
        if (file_exists($filename)) {
            include $filename;
            if (class_exists($className)) {
                return true;
            }
        }
        return false;
    }
}
spl_autoload_register('WutAutoloader::loader');

# abstract
include_once 'src/core/class-AbstractModule.php';
include_once 'src/core/class-AbstractNotification.php';

include_once 'functions.php';
include_once 'cron.php';
require_once 'inc/vendor/autoload.php';


add_action('plugins_loaded', array('admin\WutShortCode', 'init'));
add_action('plugins_loaded', array('admin\WutAction', 'init'));
add_action('plugins_loaded', array('admin\WutEnqueue', 'init'));
add_action('plugins_loaded', array('admin\WutAjaxAction', 'init'));
add_action('plugins_loaded', array('admin\WutFilter', 'init'));
add_action('plugins_loaded', array('admin\WutHook', 'init'));

# Install table
register_activation_hook(__FILE__, array('admin\WutInstallTable', 'wut_categories'));
register_activation_hook(__FILE__, array('admin\WutInstallTable', 'wut_tags'));
register_activation_hook(__FILE__, array('admin\WutInstallTable', 'wut_tag_rules'));
register_activation_hook(__FILE__, array('admin\WutInstallTable', 'wut_emails'));
register_activation_hook(__FILE__, array('admin\WutInstallTable', 'wut_sequence'));
register_activation_hook(__FILE__, array('admin\WutInstallTable', 'wut_campaign'));

# Daily cron schedule
register_activation_hook(__FILE__, 'my_activation');
register_deactivation_hook(__FILE__, 'my_deactivation');

function wutRegisterSessionCustomization()
{
    if (!session_id()) {
        session_start();
    }

}

add_action('init', 'wutRegisterSessionCustomization');

add_action('wp_ajax_wut_delete_campaign', 'wutDeleteCampaign');

function wutDeleteCampaign()
{
    $id = intval($_POST['id']);
    if (empty($id)) {
        wp_send_json_error(['mesage' => 'something went wrong.']);
    }

    $WutCampaign = new admin\modules\WutCampaign();
    $return = $WutCampaign->delete(['id' => $id]);
    wp_send_json_success(['mesage' => 'Successfully deleted.']);
}