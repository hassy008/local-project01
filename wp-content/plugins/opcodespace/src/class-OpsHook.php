<?php

/**
 * Created by PhpStorm.
 * User: Mehedee
 * Date: 2/7/2019
 * Time: 12:44 AM
 */

// class OpsHook
// {

//     /* admin menu*/
//     public function wooSubscription()
//     {
//         add_menu_page('Users List', 'User List', 'edit_posts', 'users_list', array($this, 'usersList'), 'dashicons-admin-users');
//     }


//     public function usersList()
//     {
//         ob_start();
//         require_once OP_VIEW_PATH . 'admin-menu/content-woo-subscription.php';
//         $content = ob_get_contents();
//         return $content;
//     }

//     public function wooSubscription()
//     {
//         add_menu_page('Woo Subscription', 'Woo Subscription', 'edit_posts', 'woo_subscription', array($this, 'displayWooSubscription'), 'dashicons-admin-users');
//     }


//     public function displayWooSubscription()
//     {
//         ob_start();
//         require_once OP_VIEW_PATH . 'admin-menu/content-woo-subscription.php';
//         $content = ob_get_contents();
//         return $content;
//     }
// }


/**
 * Created by PhpStorm.
 * User: Mehedee
 * Date: 2/7/2019
 * Time: 12:44 AM
 */

class OpsHook
{
    public static function init()
    {
        $self = new self;

        /* Admin menu */
        add_action('admin_menu', array($self, 'usersList'));
    }


    /* admin menu*/
    public function usersList()
    {
        add_menu_page('Woo Subscription', 'Woo Subscription', 'edit_posts', 'woo_subscription', array($this, 'displayUsersList'), 'dashicons-admin-users');
    }


    public function displayUsersList()
    {
        ob_start();
        require_once OP_VIEW_PATH . 'admin-menu/content-users-list.php';
        $content = ob_get_contents();
        return $content;
    }
}