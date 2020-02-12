<?php
namespace admin;

class WutMailTags 
{
    public function __construct()
    {
        #
    }

    public function allUserTags($user_id = null, $extra_tags = [], $descArr = [])
    {
        $user_id   = $user_id ?? get_current_user_id();
        $userdata  = $this->userData($user_id);
        $userMetas = $this->userMetas($user_id);
        $full_arr  = array_merge($userdata, $userMetas, $extra_tags);
        if (!empty($descArr) && is_array($descArr) ) {
            foreach ($descArr as $descRow) {
                $key = array_search($descRow['meta_key'], array_column($full_arr, 'meta_key'));
                $full_arr[$key]['description'] = $descRow['description'];
            }
        }

        return $full_arr;
    }

    public function userData($user_id)
    {
        $user = (array) get_user_by('id', $user_id)->data;
        unset($user['user_pass']);
        unset($user['user_activation_key']);
        $return_arr = [];
        $index = 0;
        foreach ($user as $user_key => $user_value) {
            $return_arr[$index]['meta_key']    = $user_key;
            $return_arr[$index]['meta_value']  = $user_value;
            $return_arr[$index]['description'] = "";
            $index++;
        }
        return $return_arr;
    }

    public function userMetas($user_id)
    {
        $user_metas = get_user_meta($user_id);
        $return_arr = [];
        $index      = 0;
        foreach ($user_metas as $meta_key => $meta_value) {
            $return_arr[$index]['meta_key']    = $meta_key;
            $return_arr[$index]['meta_value']  = $meta_value[0];
            $return_arr[$index]['description'] = "";
            $index ++;
        }
        return $return_arr;
    }


}
