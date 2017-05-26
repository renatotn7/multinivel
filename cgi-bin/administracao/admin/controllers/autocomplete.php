<?php
class Autocomplete extends CI_Controller{
	
	/**
	 * filtro de autrocomplete de distribuidores
	 */
	public function autocompleteDistribuidores(){

		if(isset($_REQUEST['nome']))
		{
			$data=  $this->db
			->like('lower(di_nome)',strtolower($_REQUEST['nome']),'after')
			->get('distribuidores')->result();
			if(count($data) > 0 ){
				echo json_encode(array('data'=>$data,'error'=>0));
			} else 
			   echo json_encode(array('error'=>1));
		}
	}
	
}