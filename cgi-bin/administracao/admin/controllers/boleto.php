<?php 

class Boleto extends CI_Controller{
	

public function baixa_santander(){
	 $compraPaga = 0;
	 //Toda Leitura do Arquivo e Estração dos Dados
	 
	 if($compraPaga==1){
		 /*
		 *Inserindo o registro de Aguardando Aprovação
		 *Deve surgir um botão em cadastro pendentes(Escritório distribuidor) 
		 *onde o Patrocinador possa Autorizar a Entrada do Distribuidor na Rede 
		 */
		  $this->db->insert('aguardando_aprovacao',array(
		   'aa_distribuidor'=>$compra->co_id_distribuidor,
		   'aa_compra'=>$compra->co_id,
		   'aa_status'=>0,
		   'aa_data'=>date('Y-m-d H:i:s')
		  ));
		 } 
	}	


public function baixar_itau(){
	 $file = file($_FILES['arquivo']['tmp_name']);
	 
	$data['boletos_atualizados'] = array();
	 
	 unset($file[0]);
	 unset($file[count($file)]);
	 foreach($file as $linha){
		  $nosso_numero  = (int)substr($linha,127,8);
		  $id_compra = ($nosso_numero-1000000);
		  
		  $valor_pago = (int)substr($linha,176,88).'.'.$valor_pago = (int)substr($linha,265,28);
		  
		  $compra = $this->db
		  ->join('distribuidores','di_id=co_id_distribuidor')
		  ->where('co_id',$id_compra)
		  ->get('compras')->row();
		  
		  if(count($compra)>0){
		    if($compra->co_pago==0){
				 #-- Inicia a rotina de pagamento
				  
				  	$data['boletos_atualizados'][] = array(
					'nome'=>$compra->di_nome,
					'usuario'=>$compra->di_usuario,
					'valor'=>$valor_pago,
					'compra'=>$compra->co_id
					);
					
					 $this->load->library('estoque');
						
					 ##Inicia uma transação
		     		 $this->db->trans_start();


					   ##reduzir estoque do cd ou fabrica
						if($compra->co_id_cd != 0 ){
						 $this->estoque->saida_cd($compra->co_id);
						 $this->saida_credito($compra->co_id);
						}else{
							$this->estoque->saida_fabrica($compra->co_id);
							}
					   
					   #a compra e definida como paga
					  $this->db->where('co_id',$compra->co_id)
					  ->update('compras',array(
					   'co_pago'=>1,
					   'co_situacao'=>3,
					   'co_forma_pgt'=>1,
					   'co_forma_pgt_txt'=>'Boleto Bancário',
					   'co_data_compra'=>date('Y-m-d H:i:s')
					   )); 
					   
					   
					   #verificar se é plano para inserir as parcelas e pontos
					   if($compra->co_eplano==1){
							$this->load->library('rede');
							$this->rede->alocar($compra->co_id_distribuidor);
							$this->load->library('planos');
							$this->planos->lancar($compra);
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
					 //Fim do procedimento de conta paga	
				  
				  
				 #-- Fim da Rotina de Pagamento
				}
		    
		  
		  }
		  
		 }
		 
		$data['pagina'] = 'boleto/baixa';
		$this->load->view('home/index_view',$data);		 
		 
	}	


public function baixar(){		
				
		$data['boletos_atualizados'] = array();
			
		if(isset($_POST['baixar_boletos'])){
		
		
				
		$nome = $_FILES['arquivo']['name'];
		$type = $_FILES['arquivo']['type'];
		$size = $_FILES['arquivo']['size'];
		$tmp = $_FILES['arquivo']['tmp_name'];
		
		
		$file = file($tmp);
		 
		$pasta = "public/retorno_boleto/"; //Nome da pasta onde vao ficar armazenados os arquivos;
		 
		if($type == 'application/octet-stream'){
			if($tmp){
				if(move_uploaded_file($tmp, $pasta.$nome)){
					$lendo = @fopen($pasta.$nome,"r");
					if (!$lendo){
						echo "Erro ao abrir a URL.";
						exit;
					}
					$i = 0;
					while (!feof($lendo)){
						$i++;
						$linha = fgets($lendo,9999);
						
						
						$t_u_segmento = substr($linha,13,1);//Segmento T ou U
						
						
						$t_tipo_reg = substr($linha,7,1);//Tipo de Registro
						if($t_u_segmento == 'T'){
							
							$t_cod_banco = substr($linha,0,3);//Código do banco na compensação
							$t_lote = substr($linha,3,4);//Lote de serviço - Número seqüencial para identificar um lote de serviço.
							$t_n_sequencial = substr($linha,8,5);//Nº Sequencial do registro no lote
							$t_cod_seg = substr($linha,15,2);//Cód. Segmento do registro detalhe
							$t_cod_conv_banco = substr($linha,23,6);//Código do convênio no banco - Código fornecido pela CAIXA, através da agência de relacionamento do cliente. Deve ser preenchido com o código do Cedente (6 posições).
							$t_n_banco_sac = substr($linha,32,3);//Numero do banco de sacados
							$t_mod_nosso_n = substr($linha,39,2);//Modalidade nosso número
							$t_id_titulo_banco = substr($linha,41,15);//Identificação do titulo no banco - Número adotado pelo Banco Cedente para identificar o Título.
							$t_cod_carteira = substr($linha,57,1);//Código da carteira - Código adotado pela FEBRABAN, para identificar a característica dos títulos. 1=Cobrança Simples, 3=Cobrança Caucionada, 4=Cobrança Descontada
							$t_num_doc_cob = substr($linha,58,11);//Número do documento de cobrança - Número utilizado e controlado pelo Cliente, para identificar o título de cobrança.
							$t_dt_vencimento = substr($linha,73,8);//Data de vencimento do titulo - Data de vencimento do título de cobrança.
							$t_v_nominal = substr($linha,81,13);//Valor nominal do titulo - Valor original do Título. Quando o valor for expresso em moeda corrente, utilizar 2 casas decimais.
							$t_cod_banco2 = substr($linha,96,3);//Código do banco
							$t_cod_ag_receb = substr($linha,99,5);//Codigo da agencia cobr/receb - Código adotado pelo Banco responsável pela cobrança, para identificar o estabelecimento bancário responsável pela cobrança do título.
							$t_dv_ag_receb = substr($linha,104,1);//Dígito verificador da agencia cobr/receb
							$t_id_titulo_empresa = substr($linha,105,25);//identificação do título na empresa - Campo destinado para uso da Empresa Cedente para identificação do Título. Informar o Número do Documento - Seu Número.
							$t_cod_moeda = substr($linha,130,2);//Código da moeda
							$t_tip_inscricao = substr($linha,132,1);//Tipo de inscrição - Código que identifica o tipo de inscrição da Empresa ou Pessoa Física perante uma Instituição governamental: 0=Não informado, 1=CPF, 2=CGC / CNPJ, 9=Outros.
							$t_num_inscricao = substr($linha,133,15);//Número de inscrição - Número de inscrição da Empresa (CNPJ) ou Pessoa Física (CPF).
							$t_nome = substr($linha,148,40);//Nome - Nome que identifica a entidade, pessoa física ou jurídica, Cedente original do título de cobrança.
							$t_v_tarifa_custas = substr($linha,198,13);//Valor da tarifa/custas
							$t_id_rejeicoes = substr($linha,213,10);//Identificação para rejeições, tarifas, custas, liquidação e baixas
						}
						if($t_u_segmento == 'U'){
							
							$t_id_titulo_banco;
							$u_cod_banco = substr($linha,0,3);//Código do banco na compensação
							$u_lote = substr($linha,3,4);//Lote de serviço - Número seqüencial para identificar um lote de serviço.
							$u_tipo_reg = substr($linha,7,1);//Tipo de Registro - Código adotado pela FEBRABAN para identificar o tipo de registro: 0=Header de Arquivo, 1=Header de Lote, 3=Detalhe, 5=Trailer de Lote, 9=Trailer de Arquivo.
							$u_n_sequencial = substr($linha,8,5);//Nº Sequencial do registro no lote
							$u_cod_seg = substr($linha,15,2);//Cód. Segmento do registro detalhe
							$u_juros_multa = substr($linha,17,15);//Jurus / Multa / Encargos - Valor dos acréscimos efetuados no título de cobrança, expresso em moeda corrente.
							$u_desconto = substr($linha,32,15);//Valor do desconto concedido - Valor dos descontos efetuados no título de cobrança, expresso em moeda corrente.
							$u_abatimento = substr($linha,47,15);//Valor do abat. concedido/cancel. - Valor dos abatimentos efetuados ou cancelados no título de cobrança, expresso em moeda corrente.
							$u_iof = substr($linha,62,15);//Valor do IOF recolhido - Valor do IOF - Imposto sobre Operações Financeiras - recolhido sobre o Título, expresso em moeda corrente.
							$u_v_pago = substr($linha,77,15);//Valor pago pelo sacado - Valor do pagamento efetuado pelo Sacado referente ao título de cobrança, expresso em moeda corrente.
							$u_v_liquido = substr($linha,92,15);//Valor liquido a ser creditado - Valor efetivo a ser creditado referente ao Título, expresso em moeda corrente.
							$u_v_despesas = substr($linha,107,15);//Valor de outras despesas - Valor de despesas referente a Custas Cartorárias, se houver.
							$u_v_creditos = substr($linha,122,15);//Valor de outros creditos - Valor efetivo de créditos referente ao título de cobrança, expresso em moeda corrente.
							$u_dt_ocorencia = substr(substr($linha,137,8),4,4).'-'.substr(substr($linha,137,8),2,2).'-'.substr(substr($linha,137,8),0,2);//Data da ocorrência - Data do evento que afeta o estado do título de cobrança.
							$u_dt_efetivacao = substr($linha,145,8);//Data da efetivação do credito - Data de efetivação do crédito referente ao pagamento do título de cobrança.
							$u_dt_debito = substr($linha,157,8);//Data do débito da tarifa
							$u_cod_sacado = substr($linha,167,15);//Código do sacado no banco
							$u_cod_banco_comp = substr($linha,210,3);//Cód. Banco Correspondente compens - Código fornecido pelo Banco Central para identificação na Câmara de Compensação, do Banco ao qual será repassada a Cobrança do Título.
							$u_nn_banco = substr($linha,213,20);//Nosso Nº banco correspondente - Código fornecido pelo Banco Correspondente para identificação do Título de Cobrança. Deixar branco (Somente para troca de arquivos entre Bancos).
		 
							$u_juros_multa = substr($u_juros_multa,0,13).'.'.substr($u_juros_multa,13,2);
							$u_desconto = substr($u_desconto,0,13).'.'.substr($u_desconto,13,2);
							$u_abatimento = substr($u_abatimento,0,13).'.'.substr($u_abatimento,13,2);
							$u_iof = substr($u_iof,0,13).'.'.substr($u_iof,13,2);
							$u_v_pago = substr($u_v_pago,0,13).'.'.substr($u_v_pago,13,2);
							$u_v_liquido = substr($u_v_liquido,0,13).'.'.substr($u_v_liquido,13,2);
							$u_v_despesas = substr($u_v_despesas,0,13).'.'.substr($u_v_despesas,13,2);
							$u_v_creditos = substr($u_v_creditos,0,13).'.'.substr($u_v_creditos,13,2);
		 
							$data_agora = date('Y-m-d');
							$hora_agora = date('H:i:s');
							
							
							
							
							$id_i_tal = substr($t_id_titulo_banco,6);
							
							$id_compra = (substr($t_id_titulo_banco,6)-1000000);
							
							
							
							$data['boletos_atualizados'][] = array(
							'arquivo_retorno'=>$nome,
							'valor_pago'=>$u_v_pago,
							'data_atulizacao'=>$u_dt_ocorencia,
							'pago'=>1,
							'id'=>$id_compra
							);
						  
						  
						 $this->load->library('estoque');
						
						 ##Inicia uma transação
		     			 $this->db->trans_start();
						 //Compra como paga
						 $compra =  $this->db->where('co_id',$id_compra)->get('compras')->row();
						 if($compra){
							  if($compra->co_pago==0){
								   
								   
								   ##reduzir estoque do cd ou fabrica
									if($compra->co_id_cd !=0){
								     $this->estoque->saida_cd($compra->co_id);
									 $this->saida_credito($compra->co_id);
									}else{
										$this->estoque->saida_fabrica($compra->co_id);
										}
								   
								   #a compra e definida como paga
								  $this->db->where('co_id',$id_compra)->update('compras',array(
								   'co_pago'=>1,
								   'co_situacao'=>3,
								   'co_data_compra'=>date('Y-m-d H:i:s')
								   )); 
								   
								   
								   #verificar se é plano para inserir as parcelas e pontos
								   if($compra->co_eplano==1){
										$this->load->library('rede');
										$this->rede->alocar($compra->co_id_distribuidor);
									    $this->load->library('planos');
										$this->planos->lancar($compra);
									   }
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
					 //Fim do procedimento de conta paga	 
							
							
						}
					}
					fclose($lendo);
				}
			}
		}else{
		echo "Tipo de Arquivo invalido";
		}
			
		}#fim de verificação do post
		
		$data['pagina'] = 'boleto/baixa';
		$this->load->view('home/index_view',$data);
		
		}
	
	
	public function saida_credito($compra_id){
	 
		$compra_rs = $this->db->where('co_id',$compra_id)->get('compras')->row();
		
	   	$comprou_kit_1 = $this->db
		->join('produtos_comprados','pm_id_compra= co_id')
		->join('produtos','pr_id = pm_id_produto')
		->select(array('co_id'))
		->where('co_id_distribuidor',$compra_rs->co_id_distribuidor)
		->where('co_pago',1)
		->where('pr_kit_tipo',1)
		->get('compras',1)->num_rows;
	  
	  
	  $prods_ativacao = $this->db->query("
		SELECT `pm_id` FROM `produtos_comprados` 
		JOIN compras ON co_id = `pm_id_compra`
		JOIN produtos ON pr_id = `pm_id_produto`
		WHERE 
		co_id_distribuidor =  ".$compra_rs->co_id_distribuidor."  
		AND co_data_compra >= '".date('Y-m-01')." 00:00:00'
		AND `pr_ativacao` = 1
		AND co_id = {$compra_rs->co_id}
		")->num_rows;
		
		
	  $ativo_mes = $this->db->where('cp_distribuidor',$compra_rs->co_id_distribuidor)
	  ->where('cp_data >',date('Y-m-01 00:00:00'))
	  ->get('credito_repasse')->num_rows;		
				 
		
		if($comprou_kit_1 > 0 && $ativo_mes == 0 && $prods_ativacao >= 2){
			
				$this->db->insert('credito_repasse',array(
				'cp_cd'=>($compra_rs->id_cd+0),
				'cp_credito'=>0,
				'cp_debito'=>79.0,
				'cp_distribuidor'=>$di_id,
				'cp_time'=>time()
				));
				
			}
		
	
	}
	

	
	
		
} 

?>