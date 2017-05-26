<?php

class ValidaSenhaAdministrador{
	 
	 private $d;
	 
	public function __construct(){
		 $ci =& get_instance();
		 $this->db = $ci->db;
        }
	 
	 
	 /*
	 * Para usar a função no sistema:
	 * $this->validasenhaadministrador->validar(int $usuario, string $senha,string $urlRetorno);
	 */
	 
	 public function validar($usuario, $senha, $urlRetornoError){
		   
		   $user = $this->db
                                ->where('rf_id',$usuario)
                                ->where('rf_pw',sha1($senha))
                                ->get('responsaveis_fabrica')->row();
		   
		if(count($user) == 0){
                       set_notificacao(0,'A senha que você informou é inválida');
                        redirect($urlRetornoError);
                        exit;
                }
		   
        }
	
	 }