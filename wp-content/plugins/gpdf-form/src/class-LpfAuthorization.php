<?php


class LpfAuthorization
{
    public function can ($user_id, $capability){
        return user_can($user_id, $capability );
    }
}