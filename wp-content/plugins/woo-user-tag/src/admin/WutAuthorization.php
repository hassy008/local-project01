<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29-Aug-19
 * Time: 4:57 PM
 */

namespace admin;


class WutAuthorization
{
    public function can ($user_id, $capability){
        return user_can($user_id, $capability );
    }
}