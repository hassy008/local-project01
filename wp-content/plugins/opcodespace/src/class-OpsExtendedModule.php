<?php
/**
 * Created by PhpStorm.
 * User: Mehedee
 * Date: 1/5/2019
 * Time: 12:22 PM
 */

class OpsSivFile extends AbstractModule
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->prefix . "siv_file";
    }
}