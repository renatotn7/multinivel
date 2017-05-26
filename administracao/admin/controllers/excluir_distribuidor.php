<?php 
class Excluir_distribuidor extends CI_Controller{
	
	 
	 public function excluir_ni(){
		  autenticar();
		  $data['pagina'] = 'excluir_distribuidor/excluir_distribuidor';
		  $this->load->view("home/index_view",$data);
		 }
	 
	 public function realizar_exclusao(){
		  autenticar();
		  
		  $id_excluir = isset($_POST['ni'])?$_POST['ni']:'abc';
		  
		  $distribuidor = $this->db->where('di_id',$id_excluir)->get('distribuidores')->row();
		  
		  //Verifica se é valido o NI
		  if(count($distribuidor) > 0){
			  
			  if($distribuidor->di_esquerda==0 && $distribuidor->di_direita==0 ){
				  self::remover_distribuidor($distribuidor->di_id);
				  }else{
					  echo "Proibido excluir distribuidor com rede";
					  exit;
					  //self::remover_distribuidor_com_rede($distribuidor->di_id); 
					  }
			   
			   redirect(base_url('index.php/distribuidores/?msg=Excluido com sucesso'));
			   
			   
			  }else{
				  echo "Distribuidor nao existe";
				  }
		  
		  
		 }
		 

  
    #-- Essa funcção exclui o distribuidor da rede;
    private function remover_distribuidor_com_rede($di_id){
		
		 $distribuidor = $this->db->where('di_id',$di_id)->get('distribuidores')->row();
		      
		 if(count($distribuidor) > 0){
			  $this->db
			  ->where('di_id',$distribuidor->di_id)
			  ->update('distribuidores',array(
			   'di_cpf'=>$distribuidor->di_cpf.'-01',
			   'di_excluido'=>1
			  ));
			  
			}
		}

	#-- Essa funcção exclui o distribuidor da rede;
	private function remover_distribuidor($di_id){
		 autenticar();
		 $id_excluir = $di_id;
		 
		 $distribuidor = $this->db->where('di_id',$id_excluir)->get('distribuidores')->row();
		 
		 if(
		 count($distribuidor) > 0 //Verifica se o distribuidor existe
		 && $distribuidor->di_esquerda==0 //Verifica se não tem ninguem na perna esquerda
		 && $distribuidor->di_direita==0 //Verifica se não tem ninguem na perna direita
		 ){
			 
			  //Obter compras pagas
			  $compras = $this->db->where('co_id_distribuidor',$distribuidor->di_id)->get('compras')->result();
			  $rede_ligacao = $this->db->where('li_id_distribuidor',$distribuidor->di_id)->get('distribuidor_ligacao')->result();
			  
			  $bonusIndicacao = $this->db->query("SELECT * FROM conta_bonus WHERE  cb_descricao  LIKE '%<b>".$distribuidor->di_usuario."</b>%' AND cb_tipo = 1");
			  $bonusVolumeVenda = $this->db->query("DELETE FROM conta_bonus WHERE  cb_descricao  LIKE '%<b>".$distribuidor->di_usuario."</b>%' AND cb_tipo = 107");
			  
			  
			  $dados_excluidos = array();
			  $dados_excluidos['ni_id'] = $distribuidor->di_id;
			  $dados_excluidos['distribuidor'] = json_encode($distribuidor);
			  $dados_excluidos['compras'] = json_encode($compras);
			  $dados_excluidos['rede'] = json_encode($rede_ligacao);
			  $dados_excluidos['outros'] = json_encode(array('indicacao'=>$bonusIndicacao,'volume_venda'=>$bonusVolumeVenda));
			  
			  $this->db->insert('distribuidor_excluido',$dados_excluidos);
			  
			  //Remove o distribuidor
			  $this->db->query("DELETE FROM distribuidores WHERE di_id = {$id_excluir};");
			  //Remove a rede
			  $this->db->query("DELETE FROM distribuidor_ligacao WHERE li_id_distribuidor = {$id_excluir};");
			  //Remove as compras
			  $this->db->query("DELETE FROM compras WHERE co_id_distribuidor = {$id_excluir};");
			  $this->db->query("DELETE FROM conta_bonus WHERE  cb_descricao  LIKE '%<b>".$distribuidor->di_usuario."</b>%' AND cb_tipo = 1");
			  $this->db->query("DELETE FROM conta_bonus WHERE  cb_descricao  LIKE '%<b>".$distribuidor->di_usuario."</b>%' AND cb_tipo = 107");
			  
			  $this->db->query("DELETE FROM conta_bonus WHERE cb_distribuidor = ".$distribuidor->di_id);
			  $this->db->query("DELETE FROM registro_distribuidor_binario WHERE db_distribuidor = ".$distribuidor->di_id);
			  $this->db->query("DELETE FROM registro_ativacao WHERE at_distribuidor = ".$distribuidor->di_id);
			  $this->db->query("DELETE FROM conta_deposito WHERE cdp_distribuidor = ".$distribuidor->di_id);
			  $this->db->query("DELETE FROM historico_acesso WHERE ha_distribuidor = ".$distribuidor->di_id);
			  $this->db->query("DELETE FROM documentos WHERE do_distribuidor = ".$distribuidor->di_id);
			  $this->db->query("DELETE FROM ciclos WHERE cl_distribuidor = ".$distribuidor->di_id);
                          
                          //Removendo todos os sales de compra do usuario.
                          if(count($compras)>0){
                          
                           foreach ($compras as $compra) {
			     $this->db->query("DELETE FROM compras_sales WHERE sa_id_compra = {$compra->co_id}");
                           }
                           
                          }
			  
			  //Busca em qual nó está o distribuidor
			  $no_pai = $this->db
			  ->where('di_esquerda',$id_excluir)
			  ->or_where('di_direita',$id_excluir)
			  ->get('distribuidores')->row();
			  
			  //Verifica se nó existe
			  if(count($no_pai)>0){
				  
				   //Identifica o lado que está o distribuidor excluido
				  
				   $field_limpar = '';
				   if($no_pai->di_esquerda==$id_excluir){
					   $field_limpar = 'di_esquerda';
					   }else if($no_pai->di_direita==$id_excluir){
						   $field_limpar = 'di_direita';
						   }
				   
				   //Limpra o lado do distrbuidor excluido
				   if($field_limpar != ''){
					    $this->db->where('di_id',$no_pai->di_id)->update('distribuidores',array(
						$field_limpar=>0
						));
					   }
				   
				  }
			  
			  
			 }
		 
		}
				 
	
	}