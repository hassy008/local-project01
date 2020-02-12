<?php

/**
 *
 */

class LpfEnqueue
{
    public static function init()
    {
        $self = new self;
        add_action('wp_enqueue_scripts', array($self, 'wpScripts'));
        add_action( 'admin_enqueue_scripts', array($self, 'adminScripts') );
    }

    public function wpScripts()
    {
        wp_localize_script('form-script', 'frontend_form_object',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            )
        );
    }

    public function adminScripts()
    {
        wp_enqueue_style('font-awesome', LPF_ASSETSURL . "add-on/font-awesome/css/font-awesome.min.css");
        wp_enqueue_style('chosen-css', LPF_ASSETSURL . "add-on/chosen/chosen.min.css");
        wp_enqueue_style('bootstrap-pdf-css', LPF_ASSETSURL . "add-on/bootstrap/css/boostrap-wrapper.min.css", null, '4.3.1');
        wp_enqueue_style('style-css', LPF_ASSETSURL . "css/style.css");

        wp_enqueue_script('chosen-js', LPF_ASSETSURL . "add-on/chosen/chosen.min.js", array(), '1.8.7', true);
        wp_enqueue_script('proper-js', LPF_ASSETSURL . "add-on/bootstrap/js/popper.min.js", array(), '1.14.7', true);
        wp_enqueue_script('bootstrap-js', LPF_ASSETSURL . "add-on/bootstrap/js/bootstrap.min.js", array(), '4.3.1', true);
        wp_enqueue_script('admin-lpf-script', LPF_ASSETSURL . "js/admin-script.js", array(), '1.1.0', true);   
        wp_localize_script('form-script', 'frontend_form_object',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            )
        );     
    }
}
