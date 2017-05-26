<?php 
class Astropay extends CI_Controller{

function __construct(){
	parent::__construct();
	$this->load->library('astropaydirect');
	autenticar();
}

/*
* Função AstroPay
*
*/

function create(){
	      
		  $invoice = $_GET['c'];
		  		  
		  $valorCompra = $this->db
		                      ->join('compras', 'compras.co_id=rp_compra')
							  ->where('rp_id', $invoice)
							  ->get('registro_astropay')->row();
		  
	      $urlRetorno = base_url('index.php/astropay/retorno/?c='.$invoice);
		  
		 
		 $jsonTransacao = $this->astropaydirect->create($invoice,$valorCompra->co_total_valor,966802, 'TE', 'BR', 'USD', '',''.get_user()->di_cpf.'', '',$urlRetorno,'','json');
         
		 $objTransacao = json_decode($jsonTransacao);
		 
		 if(isset($objTransacao->status)&&$objTransacao->status=='OK'){
			 redirect($objTransacao->link);
			 }else{
				  echo "Ocorreu um erro na transacao: ".$objTransacao->desc;
				 }
	   }
	

    public function retorno(){
	
	 header('Content-Type: text/html; charset=utf-8');
	 
	 $status = $this->astropaydirect->get_status($_GET['c']);
	 
	 $statusFatura = explode('|', $status);

	   //Resultado da transação
	   switch($statusFatura[0]){
		 case $statusFatura[0]==6 :
		 
		 echo '<span style="color:#333;background:#F9F9F9;font-size:16px; display:block;padding:10px;border:1px solid #ccc;width:600px;">Fatura Inválida.<br>
<a href="'.base_url().'">Voltar</a>
              </span>';
			  
		 break;
		 case $statusFatura[0]==7 :
		 
		 echo '<span style="color:#333;background:#F9F9F9;font-size:16px; display:block;padding:10px;border:1px solid #ccc;width:600px;">Aquardando aprovação da transação.<br>
<a href="'.base_url().'">Voltar</a>
              </span>';
			  
		 break;
		 case $statusFatura[0]==8 :
		 
		 echo '<span style="color:#333;background:#F9F9F9;font-size:16px; display:block;padding:10px;border:1px solid #ccc;width:600px;">Expirado, rejeitado pelo banco ou cancelada pelo usuário<br>
<a href="'.base_url().'">Voltar</a>
              </span>';
		 
		 break;
		 case $statusFatura[0]==9 :
		 
		  //Tabela registro astropay
		  $id_compra = $this->db
		                    ->where('rp_id', $_GET['c'])
							->get('registro_astropay')->row();
		  
		  $this->atualizacao_compra($id_compra->rp_compra);
		    
		  echo '<span style="color:#333;background:#F9F9F9;font-size:16px; display:block;padding:10px;border:1px solid #ccc;width:600px;">Valor pago.Transação concluída com sucesso.<br>
<a href="'.base_url().'">Voltar</a>
                </span>';
	 
		  break;
		 		   
	   } 
	
	}
	
	
public function atualizacao_compra($id_compra){
	
         $this->load->library('estoque');
		 $this->load->library('lib_bonus');
		
		 $compra = $this->db
		 ->join('distribuidores','co_id_distribuidor=di_id')
		 ->where('co_id',$id_compra)
		 ->where('co_pago',0)
		 ->get('compras')->row();
		 
		 $compraModel = new ComprasModel($compra);
	     $valorTotalCompra = $compraModel->valorCompra();
		
		if(count($compra)==0){
			set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>"Nenhuma compra encontrada.")));
			redirect(base_url());
			exit;
	    }
			
	    $arrayCompras = array();
		$arrayCompras = $compraModel->getCompras();
		
		 ##Inicia uma transação
		 $this->db->trans_start();
		 
		foreach($arrayCompras as $compra){
		 
		 $valor_compra = $compra->co_total_valor+$compra->co_frete_valor;
		
		##Conta como paga
		$this->db->where('co_id',$compra->co_id)->update('compras',array(
		  'co_forma_pgt_txt'=>'AstroPay',
		  'co_forma_pgt'=>8,
		  'co_pago'=>1,
		  'co_situacao'=>1,
		  'co_data_compra'=>date('Y-m-d H:i:s')  
		 ));
		
		
		##Debitar bonus distribuidor
		$this->lib_bonus->debitar_bonus(get_user()->di_id,
		$valor_compra,"Pagamento compra Nº ".$compra->co_id." do usuário <b>".$compra->di_usuario."</b>",$compra->co_id,3);
		
		
	   #verificar se é plano para inserir as parcelas e pontos
	   
	   if($compra->co_eplano==1){
			$this->load->library('rede');
			$this->rede->alocar($compra->co_id_distribuidor);
			$this->load->library('planos');
			$this->planos->lancar($compra);
	   }
		
		#-- Lançar ativação da compra --#
		$this->load->library('ativacao');
		$this->ativacao->lancar_ativacao($compra);
		#-- Lançar ativação da compra --#	   
			
		
		#-- Regitra se estiver pagando compra de outro distribuidor
		if(get_user()->di_id != $compra->co_id_distribuidor){
			 
			 $this->db->insert('registro_pagamento_compra_terceiro',array(
			  'rc_compra'=>$compra->co_id,
			  'rc_comprador'=>$compra->co_id_distribuidor,
			  'rc_pagante'=>get_user()->di_id,
			  'rc_data'=>date('Y-m-d H:i:s')
			 ));
				 
		 }		
			
		 }
	
	     //Se todas as operações ocorrem como esperado
			 if ($this->db->trans_status() === FALSE)
					{
						$this->db->trans_rollback();
					}
					else
					{
						$this->db->trans_commit();
					}			
				
     }	


}
?>