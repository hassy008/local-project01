<?php

class OpsWooCrud extends AbstractModule
{
    protected $table;
    /**
     * Class constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->table = $this->db->prefix . 'crud';
    }
    
}
