<?php
namespace core;
/**
 *
 */
class AbstractNotification
{

    public function send($to, $subject, $body, $attachments = null)
    {
        $WutEmail = new \admin\WutEmail();
        $emailConfig = $WutEmail->config();

        $headers = array('Content-Type: text/html; charset=UTF-8');
        $headers[] = "From: {$emailConfig['from_name']}  <{$emailConfig['from_email']}>";
        $headers[] = "Reply-To: {$emailConfig['reply_name']} <{$emailConfig['reply_email']}>";

        wp_mail( $to, $subject, $body, $headers, $attachments );
    }

    public function shortCode($data)
    {
        $short_codes = [];
        foreach ($data as $key => $value){
            $short_codes["{".$key ."}"] = $value;
        }

        return $short_codes;
    }


    public function replace($data, $subject)
    {
        $short_codes = $this->shortCode($data);
        return str_replace(array_keys($short_codes), array_values($short_codes), $subject);
    }
}