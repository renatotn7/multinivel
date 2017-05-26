<?php
class Venda_dd extends CI_Controller{
	
	public function escolher_comprador(){
		$data['pagina'] = 'venda_dd/escolher_comprador';
		 $this->load->view('home/index_view',$data);
		}
	
	public function vender(){
		 
		 $user = $this->db->where('di_usuario',$_GET['usuario'])->get('distribuidores')->row();
		 
		 if(count($user)==0){
			 redirect(base_url('index.php/venda_dd/escolher_comprador'));
			 }
		 $data['comprador'] = $user;
		 $data['pagina'] = 'venda_dd/vender';
		 $this->load->view('home/index_view',$data);
		}
		
		
	public function finalizar_venda(){
		autenticar();
		self::validar_senha_seguranca($this->input->post('senha_segurancao'),base_url('index.php/venda_dd/escolher_comprador'));
		
		$comprador = $this->db->where('di_id',$this->input->post('di_id'))->get('distribuidores')->row();
		
		#--Verifica se o usuário comprador existe
		if(count($comprador)==0){
			 set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>"Comprador não encontrado, tente novamente.")));
			 redirect('index.php/venda_dd/escolher_comprador');exit;
			}
		
		#-- Verifica se tem algum produto
		if(!isset($_POST['produtos']) || count($_POST['produtos'])==0){
			 set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>"Nenhum produto encontrado na venda.")));
			 redirect(base_url('index.php/venda_dd/escolher_comprador'));exit;
			}	
		
		#--Nova compra
		$compra = array(
		'co_tipo'=>3,
		'co_entrega'=>0,
		'co_entrega_uf'=>$comprador->di_uf,
		'co_entrega_cidade'=>$comprador->di_cidade,
		'co_frete_valor'=>0,
		'co_id_distribuidor'=>$comprador->di_id,
		'co_situacao'=>1,
		'co_pago'=>1,
		'co_forma_pgt'=>2,
		'co_forma_pgt_txt'=>'Pagamento para DD',
		'co_pago_industria'=>0,
		'co_data_compra'=>date('Y-m-d H:i:s'),
		'co_data_insert'=>date('Y-m-d H:i:s')
		);
		 
		 
		 
		 $produtos_compra = array();
		 $total_pontos = 0;
		 $total_compra = 0;
		 foreach($_POST['produtos'] as $id_produto_comprado => $qtd){
			  if($qtd>0){
				  
				  #-- Busca o registro do produto comprado pelo DD
				  $produto_comprados = $this->db->where('pm_id',$id_produto_comprado)->get('produtos_comprados')->row();
				  if(count($produto_comprados)>0){
					  
					   $quantidade_em_estoque = $this->db
						 ->select(array('SUM(pm_quantidade) as estoque'))
						 ->join('produtos','pr_id=pm_id_produto')
						 ->join('compras','co_id=pm_id_compra')
						 ->where('co_e_dd',1)
						 ->where('co_pago',1)
						 ->where('pm_id_produto',$produto_comprados->pm_id_produto)
						 ->group_by('pm_id_produto')
						 ->get('produtos_comprados')->row();
					    
						#-- Se não tiver estoque
						if(($quantidade_em_estoque->estoque+0) < $qtd){
						 set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>"Você adicionou um produto a venda que não tem no seu estoque.")));
						 redirect(base_url('index.php/venda_dd/escolher_comprador'));exit;
						}
							
						  $produtos_compra[] = array(
						  'pm_id_produto'=>$produto_comprados->pm_id_produto,
						  'pm_quantidade'=>$qtd,
						  'pm_pontos'=>$produto_comprados->pm_pontos,
						  'pm_valor'=>$produto_comprados->pm_valor
						  );
				  
						  $total_pontos += $produto_comprados->pm_pontos*$qtd;
						  $total_compra += $produto_comprados->pm_valor*$qtd;
				  
				 }
			  }
			  
			 }
		
		 $compra['co_total_pontos'] = $total_pontos;
		 $compra['co_total_valor'] = $total_compra;
		 
		 $this->db->trans_begin();
		 
		 $this->db->insert('compras',$compra);
		 
		 $this->db->insert('pontos_distribuidor',array(
		  'pd_distribuidor'=>$comprador->di_id,
		  'pd_pontos'=>$total_pontos,
		  'pd_tipo'=>2,
		  'pd_data'=>date('Y-m-d')
		 ));
		 
		 $id_compra = $this->db->insert_id();
		 
		 foreach($produtos_compra as $produto_insert){
			  $produto_insert['pm_id_compra'] = $id_compra;
			  $this->db->insert('produtos_comprados',$produto_insert);
			  self::_remover_produto_dd($produto_insert['pm_id_produto'],$produto_insert['pm_quantidade'],$id_compra);
			 }
		 
		 
		 if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
		 
		 set_notificacao(array(0=>array('tipo'=>1,'mensagem'=>"<h4>Compra finalizada com sucesso</h4>Compra efetuada com sucesso!")));
		 redirect(base_url('index.php/venda_dd/escolher_comprador'));exit;
		
		}

private function _remover_produto_dd($id_produto,$quantidade,$id_compra){
	
		 $produtos = $this->db
		 ->select(array('pm_quantidade','pm_id','pm_id_produto'))
		 ->join('produtos','pr_id=pm_id_produto')
		 ->join('compras','co_id=pm_id_compra')
		 ->where('co_e_dd',1)
		 ->where('co_pago',1)
		 ->where('pm_id_produto',$id_produto)
		 ->get('produtos_comprados')->result();
		
		$ja_removidos = 0;
		foreach($produtos as $p){
			
		 $quantidade_falta = $quantidade-$ja_removidos;
		 if($p->pm_quantidade > 0 && $quantidade_falta > 0){
			  
			  $remover_agora = 0;
			  #-- Se a quantidade nessa lilha e maior que a quantidade que falta;
			  if($p->pm_quantidade >= $quantidade_falta){
				   $remover_agora = $quantidade_falta;
				  }else{
					  $remover_agora = $p->pm_quantidade; 
					  }
					  
			    $ja_removidos =  $remover_agora;	
				$this->db->query("UPDATE produtos_comprados SET pm_quantidade = pm_quantidade - {$remover_agora} 
				WHERE pm_id = ".$p->pm_id);	  
				
				$this->db->insert('produto_vendido_dd',array(
				 'pv_compra'=>$id_compra,
				 'pv_id_produtos_comprado'=>$p->pm_id,
				 'pv_quantidade'=>$remover_agora,
				 'pv_data'=>date('Y-m-d H:i:s')
				));
			  
			 }
		 
		 if($ja_removidos == $quantidade){
			  continue;
			 }
		 
		}
		  
	}	

public function validar_senha_seguranca($senha,$retorno){
	
	  $senha_seguranca = $this->db
	     ->select('di_id')
		 ->where('di_pw',sha1($senha))
		 ->where('di_id',get_user()->di_id)
		 ->get('distribuidores')->row();	
			
	   if(count($senha_seguranca)==0){
				set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>"Senha de segurança incorreta.")));
				redirect($retorno);
				exit;
			}
	
	}	
	
 }