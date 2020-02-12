<?php

/**
 *
 */

use Leafo\ScssPhp\Compiler;

class OpsEnqueue
{
    public $scss_dir;
    public $css_dir;
    protected $Compiler;

    public function __construct()
    {
        $this->css_dir  = OP_PATH . '/assets/css/';
        $this->scss_dir = OP_PATH . '/assets/scss/';

        $this->Compiler = new Compiler();
        $this->Compiler->setImportPaths($this->scss_dir);
    }

    public static function init()
    {
        $self = new self;
        add_action('wp_enqueue_scripts', array($self, 'wp_scripts'));
        add_action('admin_enqueue_scripts', array($self, 'enqueue_admin_script'));
    }

    /**
     * to enqueue scripts and styles.
     */

    public function compiler($in, $out)
    {
        $css = $this->Compiler->compile(file_get_contents($in), $in);
        file_put_contents($out, $css);
    }

    public function wp_scss()
    {
        foreach (new DirectoryIterator($this->scss_dir) as $file) {
            if (substr($file, 0, 1) != "_" && pathinfo($file->getFilename(), PATHINFO_EXTENSION) == 'scss') {
                $input = $this->scss_dir . $file->getFilename();
                $outputName = preg_replace("/\.[^$]*/", ".css", $file->getFilename());
                $output = $this->css_dir . $outputName;
                $this->compiler($input, $output);
            }
        }
    }

    public function wp_scripts()
    {
        $this->wp_scss();
        wp_enqueue_style('font-awesome', OP_ASSETSURL . "add-on/font-awesome/css/font-awesome.min.css");
        wp_enqueue_style('bootstrap-css', OP_ASSETSURL . "add-on/bootstrap/css/bootstrap-wrapper.css", false, '1.0.0');
        wp_enqueue_style('jquery-ui-css', OP_ASSETSURL . "add-on/jquery-ui/jquery-ui.min.css", false, '1.0.0');
        wp_enqueue_style('chosen-css', OP_ASSETSURL . "add-on/choosen/bootstrap-chosen.css", false, '1.0.0');
        //        wp_enqueue_style('date-picker-css', OP_ASSETSURL . "add-on/date-picker/bootstrap-datetimepicker.min.css", false, '1.0.0');
        wp_enqueue_style('date-picker-css', OP_ASSETSURL . "add-on/datetime-picker/jquery-ui-timepicker-addon.css", false, '1.0.0');
        wp_enqueue_style('sweet-alert', "https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.10.3/sweetalert2.css");
        wp_enqueue_style('form-style', OP_ASSETSURL . "css/style.css", array(), time());

        wp_enqueue_script('loading-overlay', "https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js", array(), '1.0.0', true);
        wp_enqueue_script('bootstrap-js', OP_ASSETSURL . "add-on/bootstrap/js/bootstrap.min.js", array(), '1.0.0', true);
        wp_enqueue_script('chosen-js', OP_ASSETSURL . "add-on/choosen/chosen.jquery.min.js", array(), '1.0.0', true);
        wp_enqueue_script('cleave-js', OP_ASSETSURL . "add-on/cleave-js/cleave.min.js", array(), '1.0.0', true);
        wp_enqueue_script('mask-js', OP_ASSETSURL . "add-on/mask-js/jquery.mask.min.js", array(), '1.0.0', true);
        wp_enqueue_script('date-moment-js', OP_ASSETSURL . "add-on/date-picker/moment.min.js", array(), '1.0.0', true);
        //        wp_enqueue_script('date-picker-js', OP_ASSETSURL . "add-on/date-picker/bootstrap-datetimepicker.min.js", array(), '1.0.0', true);
        wp_enqueue_script('sweet-alert-js', OP_ASSETSURL . "add-on/sweet-alert/sweetalert.min.js", array(), '1.0.0', true);
        wp_enqueue_script('jquery-ui-js', OP_ASSETSURL . "add-on/jquery-ui/jquery-ui.min.js", array(), '1.0.0', true);
        wp_enqueue_script('rxp-js', OP_ASSETSURL . "js/rxp.min.js", array(), '1.0.0', true);

        wp_enqueue_script('time-picker-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js', array(), '1.0.0', true);
        wp_enqueue_script('dash-2', OP_ASSETSURL . "js/dash_2.js", array(),  '1.0.4', true);
        wp_enqueue_script('script', OP_ASSETSURL . "js/script.js", array(),  '1.0.4', true);
        wp_enqueue_script('form-script', OP_ASSETSURL . "js/script.main.js", array(),  '1.0.4', true);

        wp_localize_script(
            'form-script',
            'frontend_form_object',
            array(
                'ajaxurl'           => admin_url('admin-ajax.php')
            )
        );
        wp_localize_script(
            'dash-2',
            'script',
            'congress',
            array(
                'congress_start'    => get_option('congress_start_date'),
                'congress_end'      => get_option('congress_end_date')
            )
        );
    }

    public function enqueue_admin_script()
    {
        wp_enqueue_style('admin-bootstrap-css', OP_ASSETSURL . "add-on/bootstrap/css/bootstrap-wrapper.css", false, '1.0.0');
        wp_enqueue_style('admin-font-awesome', OP_ASSETSURL . "add-on/font-awesome/css/font-awesome.min.css");
        wp_enqueue_script('admin-bootstrap-js', OP_ASSETSURL . "add-on/bootstrap/js/bootstrap.min.js", array(), '1.0.0', true);
        wp_enqueue_script('admin-script', OP_ASSETSURL . "js/admin-script.js", array(), '1.0.0', true);
        wp_localize_script(
            'admin-script',
            'frontend_form_object',
            array(
                'ajaxurl' => admin_url('admin-ajax.php')
            )
        );
    }
}