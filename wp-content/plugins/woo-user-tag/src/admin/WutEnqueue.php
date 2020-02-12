<?php

/**
 *
 */
namespace admin;

class WutEnqueue
{

    public static function init()
    {
        $self = new self;
        add_action('wp_enqueue_scripts', array($self, 'wpScripts'));
        add_action('admin_enqueue_scripts', array($self, 'wpCustomAdminEnqueue'));
    }

    /**
     * to enqueue scripts and styles.
     */
    public function wpScripts()
    {
        wp_localize_script('form-script', 'frontend_form_object',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('ajax_nonce'),
            )
        );
    }

    public function wpCustomAdminEnqueue()
    {
        wp_enqueue_style('wut_admin_font_awesome_css', WUT_ASSETSURL . 'add-on/font-awesome/css/font-awesome.min.css', '1.0.0', false);
        wp_enqueue_style('wut_admin_bootstrap_css', WUT_ASSETSURL . 'add-on/bootstrap/css/boostrap-wrapper.min.css', '1.0.0', false);
        wp_enqueue_style('wut_admin_sweet_alert_css', WUT_ASSETSURL . 'add-on/sweet-alert/sweetalert2.min.css', '1.0.0', false);
        wp_enqueue_style('wut_admin_data_table_css', WUT_ASSETSURL . 'add-on/data-table/datatables.min.css', '1.0.0', false);
        wp_enqueue_style('wut_admin_select2_css', WUT_ASSETSURL . 'add-on/select2/select2.min.css', '1.0.0', false);
        wp_enqueue_style('wut_admin_form-style', WUT_ASSETSURL . "css/style.css", '1.0.0', false);

        wp_enqueue_script('wut_admin_popper_js', WUT_ASSETSURL . 'add-on/bootstrap/js/popper.min.js', '1.0.0', true);
        wp_enqueue_script('wut_admin_bootstrap_js', WUT_ASSETSURL . 'add-on/bootstrap/js/bootstrap.min.js', '1.0.0', true);
        wp_enqueue_script('wut_admin_sweet_alert_js', WUT_ASSETSURL . 'add-on/sweet-alert/sweetalert2.min.js', '1.0.0', true);
        wp_enqueue_script('wut_admin_select2_js', WUT_ASSETSURL . 'add-on/select2/select2.min.js', '1.0.0', true);
        wp_enqueue_script('wut_admin_data_table_js', WUT_ASSETSURL . 'add-on/data-table/datatables.min.js', '1.0.0', true);
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('wut_admin_form_script', WUT_ASSETSURL . "js/admin-script.js", '1.0.1', true);

        wp_localize_script('wut_admin_form_script', 'frontend_form_object',
            array(
                'ajaxurl'       => admin_url('admin-ajax.php'),
                'ajax_nonce'    => wp_create_nonce('ajax_nonce'),
            )
        );
    }

}

