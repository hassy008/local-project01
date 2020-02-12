<?php

class OpsUser
{
    public function getUserByRole($role)
    {
        $args = array(
            'role' => $role,
        );

        if ($role == 'all') {
            $args = array(
                'role__in' => ['all', 'author', 'subscriber', 'administrator'],
            );
        }

        $User = new WP_User_Query($args);

        return $User->get_results();
    }
}