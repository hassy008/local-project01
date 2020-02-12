<?php
/**
 * 
 */
class LpfGeneratedPdf extends LpfAbstractModule
{
	
	public function __construct()
	{
        parent::__construct();
		$this->table = $this->db->prefix . 'generated_pdf';
	}
}