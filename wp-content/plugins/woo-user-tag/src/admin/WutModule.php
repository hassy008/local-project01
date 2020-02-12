<?php
namespace admin;
/**
 * Created by PhpStorm.
 * User: Mehedee
 * Date: 1/5/2019
 * Time: 12:22 PM
 */

class WutModule extends \core\AbstractModule
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->prefix . "siv_file";
    }

    public function getById($id)
    {
    	return $this->getRow(['id' => $id]);
    }

    public function getNameById($id)
    {
    	$result = $this->getById($id);
    	return $result->name;
    }
}