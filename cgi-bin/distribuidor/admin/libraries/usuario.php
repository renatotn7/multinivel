<?php

class Usuario{
	public function __construct(){
		$ci =& get_instance();
		if(isset(get_user()->di_id)){
		$login = $ci->db->where('di_id',get_user()->di_id)
	     ->join('cidades','di_cidade = ci_id')
	     ->join('distribuidor_qualificacao','dq_id=di_qualificacao')
	     ->get('distribuidores')->result();
		 
		 if(count($login)){
		   set_user($login[0],FALSE);
		 } 
		 
		}
		}
	}