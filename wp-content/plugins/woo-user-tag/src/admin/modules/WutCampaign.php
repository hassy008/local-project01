<?php
namespace admin\modules;
/**
 * Created by PhpStorm.
 * User: Mehedee
 * Date: 1/5/2019
 * Time: 12:22 PM
 */

class WutCampaign extends \admin\WutModule
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->prefix . "wut_campaign";
    }

    public function getByTagId($tag_id)
    {
        return $this->db->get_results("SELECT * FROM $this->table WHERE  FIND_IN_SET ($tag_id, tag_ids)");
    }
}