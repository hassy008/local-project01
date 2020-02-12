<?php
namespace admin\modules;
// use admin;
/**
 * Created by PhpStorm.
 * User: Mehedee
 * Date: 1/5/2019
 * Time: 12:22 PM
 */

class WutTag extends \admin\WutModule
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->prefix . "wut_tags";
    }
}