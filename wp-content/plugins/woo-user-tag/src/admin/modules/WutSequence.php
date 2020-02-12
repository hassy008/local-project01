<?php
namespace admin\modules;
/**
 * Created by PhpStorm.
 * User: Mehedee
 * Date: 1/5/2019
 * Time: 12:22 PM
 */

class WutSequence extends \admin\WutModule
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->prefix . "wut_sequence";
    }
    
    public function getPassedSequences()
    {
        $now = date('Y-m-d h:i:s');
        return $this->db->get_results("SELECT * FROM $this->table WHERE trigger_at <= '$now' AND status is null");
    }
    public function getGroups()
    {
        return $this->db->get_results("SELECT * FROM $this->table GROUP BY status HAVING status is null");
    }
}