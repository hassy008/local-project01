<?php

/**
 *
 */

class OpsShortCode
{

    public static function init()
    {
        $self = new self;
        add_shortcode('example', array($self, 'example'));
        add_shortcode('csv', array($self, 'csvTest'));
        add_shortcode('crud_test', array($self, 'crudTest'));
        add_shortcode('update_ajax', array($self, 'updateAjax'));
        add_shortcode('schedule_events', array($self, 'scheduleEvents'));
    }

    public function scheduleEvents()
    {
        return get_view(OP_VIEW_PATH . "schedule.php", compact('var'));
    }

    public function updateAjax()
    {
        return get_view(OP_VIEW_PATH . "update-ajax.php", compact('var'));
    }

    public function ViewEvents()
    {
        return get_view(OP_VIEW_PATH . "content-dropbox-callback.php", compact('var'));
    }

    public function csvTest()
    {
        return get_view(OP_VIEW_PATH . "csv.php", compact('var'));
    }

    public function crudTest()
    {
        return get_view(OP_VIEW_PATH . "crud_test.php", compact('var'));
    }
}
