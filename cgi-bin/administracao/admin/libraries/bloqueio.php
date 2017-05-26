<?php
class Bloqueio{
	
	public function get_permissao($nome='')
	{
	 return in_array($nome, $this->bloqueados());
	}
	
	private function bloqueados()
	{
		$ci =& get_instance();
		$usuarios=$ci->db->where('field','grupo_usuarios')->get('config')->row();
		$usuarios=explode(',',$usuarios->valor);
		return $usuarios;
	}
}