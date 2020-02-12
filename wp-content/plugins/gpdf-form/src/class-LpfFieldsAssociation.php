<?php
/**
 * 
 */
class LpfFieldsAssociation extends LpfAbstractModule
{
	
	public function __construct()
	{
        parent::__construct();
		$this->table = $this->db->prefix . 'fields_association';
	}
}