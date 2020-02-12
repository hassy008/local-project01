<?php
/**
 *
 */
class LpfRequest
{
    public function __construct()
    {
        # code...
    }

    public function prepareRequest($url = null, $method = null, $data = null, $accessToken = null, $content_type = "application/json")
    {

        $args = [];
        if (!empty($data)){
            $args['body'] = $data;
        }
        if ('GET' == $method) {
            $response =  wp_remote_get( $url, $args );
            return $response['body'];
        }
        if ('POST' == $method) {
            $response =  wp_remote_post( $url, $args );
            return $response['body'];
        }
        return false;
    }

    public function send($url, $method = "POST", $data, $isFile = false, $isObject = true, $token = null)
    {
        if (!$isFile) {
            $args = [
                'headers' => [
                    'token' => $token,
                ]
            ];

            if (!empty($data)){
                $args['body'] = $data;
            }

            if ('GET' == $method) {
                $response =  wp_remote_get( $url, $args );
                return $response['body'];
            }
            if ('POST' == $method) {
                $response =  wp_remote_post( $url, $args );
                return $response['body'];
            }
        }
        return false;
    }
}
