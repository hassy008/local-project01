<?php 
namespace admin;

class WutEmail extends \core\AbstractNotification 
{
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function config()
    {
        $data       = [];
        $site_title = get_bloginfo('name');
        $owner_email = get_option('admin_email');

        $data['from_name'] = get_option('wut_email_from_name') ? get_option('wut_email_from_name') : $site_title;
        $data['reply_name'] = get_option('wut_email_reply_name') ? get_option('wut_email_reply_name') : $site_title;
        $data['from_email'] = get_option('wut_email_from_email') ? get_option('wut_email_from_email') : $owner_email;
        $data['reply_email'] = get_option('wut_email_reply_email') ? get_option('wut_email_reply_email') : $owner_email;

        return $data;
    }

    public function mergeShortCode($tags)
    {
        $short_codes = [];

        if (!empty($tags) && is_array($tags)) {
            foreach ($tags as $tag) {
                $short_codes["{" . $tag['meta_key'] . "}"] = $tag['meta_value'];
            }
        }
        return $short_codes;
    }


    public function mergeReplace($short_codes, $subject)
    {
        return str_replace(array_keys($short_codes), array_values($short_codes), $subject);
    }
}