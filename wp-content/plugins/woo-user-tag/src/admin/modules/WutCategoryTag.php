<?php
namespace admin\modules;
/**
 * Created by PhpStorm.
 * User: Mehedee
 * Date: 1/5/2019
 * Time: 12:22 PM
 */

class WutCategoryTag extends \admin\WutModule
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->prefix . "wut_category_tag";
    }
}