<?php
class Bloqueio{
	
	public function get_permissao($usuario='')
	{
	 return in_array($usuario, $this->bloqueados());
	}
	
	private function bloqueados()
	{
		$ci =& get_instance();
		$usuarios=$ci->db->where('field','grupo_usuarios')->get('config')->row();
		$usuarios=explode(',',$usuarios->valor);
		return $usuarios;
	}
}