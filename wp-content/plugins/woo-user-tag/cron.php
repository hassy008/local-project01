<?php

function wutCustomSchedules($schedules)
{
    if (!isset($schedules["5min"])) {
        $schedules["5min"] = array(
            'interval' => 5 * 60,
            'display' => __('Once every 5 minutes')
        );
    }
    if (!isset($schedules["30min"])) {
        $schedules["30min"] = array(
            'interval' => 30 * 60,
            'display' => __('Once every 30 minutes')
        );
    }
    return $schedules;
}
add_filter('cron_schedules', 'wutCustomSchedules');



function my_activation_2() {
    if (! wp_next_scheduled ( 'wutCustomEvent' )) {
        wp_schedule_event(time(), '5min', 'wutCustomEvent');
    }
}

add_action('wutCustomEvent', 'do_this_daily_2');

function do_this_daily_2() {
    $WutSequence = new admin\modules\WutSequence();
    $WutCampaign = new admin\modules\WutCampaign();
    $WutEmail    = new admin\WutEmail();
    $WutMailTags = new admin\WutMailTags();    
    $sequences   = $WutSequence->getPassedSequences();

    if (empty($sequences)) {return;}
    foreach ($sequences as $sequence ) {
        $campaign = (array) $WutCampaign->getRow(['id' => $sequence->campaign_id]);
        if(empty($campaign))  {continue;}
        $tags     = $WutMailTags->allUserTags($sequence->user_id);

        $short_codes = $WutEmail->mergeShortCode($tags);
        $content     = $WutEmail->mergeReplace($short_codes, $campaign['content']); 
        $WutEmail->send($sequence->email, $campaign['subject'] , $content);

        $WutSequence->update(['status' => 'sent'], ['id' => $sequence->id]);
    }
    
}



function my_deactivation_2() {
    wp_clear_scheduled_hook('my_daily_event');
}
