<?php 

class Boleto extends CI_Controller{
	
	public function config_boleto(){
		 $id_boleto = $_GET['c'];
		 $this->db->where('co_id',$id_boleto)
		 ->update('compras',array('co_forma_pgt_txt'=>'Boleto Bancário','co_forma_pgt'=>1));
		 
		 $data['pagina'] = strtolower(__CLASS__)
		 ."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	     $this->load->view('home/index_view',$data);	  
		}
	
   function gerar_boleto(){
             
			 
			 if(!isset($_GET['c'])
			 || $_GET['c']=="" 
			 || !isset($_GET['seg'])
			 || $_GET['seg']==""  
			 ){
				 redirect(base_url()."index.php/pedidos");exit();
				 }
			
			 $id_boleto = $_GET['c'];
			
			//Mensalidade
			$m = $this->db->where('co_id',$id_boleto)
				 ->join("distribuidores","di_id = co_id_distribuidor")
				 ->join("cidades","ci_id = co_entrega_cidade")
				 ->get("compras")->result();
				 
				 
			if(!$m || ($m[0]->co_hash_boleto!=$_GET['seg'])){
				echo "<p>Nenhuma compra encontrada</p>";exit;
				}	 
			
			
			$fabrica = $this->db->get('fabricas')->row();
			
		   
		   $this->db->where('co_id',$id_boleto)
		   ->update('compras',array('co_forma_pgt_txt'=>'Boleto Bancário','co_forma_pgt'=>1));								 
			
			$dias_de_prazo_para_pagamento = 2;
			$taxa_boleto = 0.0;
			
			$time =  strtotime($m[0]->co_data_compra);
			
			
			$dias_atraso = time()-$time;
			$dias_atraso = round($dias_atraso/86400);
			
			if($dias_atraso<=4){
				$dias_tolerancia = 2;
				}else{
					$dias_tolerancia = -1;
					}
			
		

// DADOS DO BOLETO PARA O SEU CLIENTE
			$data_venc = date('d/m/Y',
			mktime(0,0,0,
			date('m'),
			(date('d')+$dias_tolerancia),
			date('Y')
			));  // Prazo de X dias OU informe data: "13/04/2006";
			
			$compraModel = new ComprasModel($m[0]);
			
			$taxa_boleto = 0;
			$valor_cobrado = number_format($compraModel->valorCompra(),2,",",""); // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
			$valor_cobrado = str_replace(",", ".",$valor_cobrado);
			$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
			
			$dadosboleto["numero_compra"] = $m[0]->co_id;
			
			$dadosboleto["nosso_numero"] = (1000000+$m[0]->co_id);  // Nosso numero sem o DV - REGRA: Máximo de 7 caracteres!
			$dadosboleto["numero_documento"] = (1000000+$m[0]->co_id);	// Num do pedido ou nosso numero
			$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
			$dadosboleto["data_documento"] = date('d/m/Y',strtotime($m[0]->co_data_compra)); // Data de emissão do Boleto
			$dadosboleto["data_processamento"] = date('d/m/Y',strtotime($m[0]->co_data_compra)); // Data de processamento do boleto (opcional)
			$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
						
		
			// DADOS DO SEU CLIENTE
			$dadosboleto["sacado"] = $m[0]->di_nome ." / ".$m[0]->di_usuario;
			$dadosboleto["endereco1"] = $m[0]->di_endereco.", ".$m[0]->ci_nome."/".$m[0]->ci_uf;
			$dadosboleto["endereco2"] = $m[0]->di_cep;

			// INFORMACOES PARA O CLIENTE
			$dadosboleto["demonstrativo1"] = "Pagamento de compra compra Nº ".$m[0]->co_id;
			$dadosboleto["demonstrativo2"] = '<div style="color:#c00;font-size:13px;">A CONFIRMAÇÃO DO PAGAMENTO DESTE BOLETO LEVA 3 DIAS ÚTEIS APÓS O PAGAMENTO</div>';
			$dadosboleto["demonstrativo3"] = "";
			$dadosboleto["instrucoes1"] = "";
			$dadosboleto["instrucoes2"] = "";
			$dadosboleto["instrucoes3"] = "";
			$dadosboleto["instrucoes4"] = "";
			
			// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
			$dadosboleto["quantidade"] = "";
			$dadosboleto["valor_unitario"] = "";
			$dadosboleto["aceite"] = "";		
			$dadosboleto["especie"] = "R$";
			$dadosboleto["especie_doc"] = "";


			// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
			
			
			$boletoConfig = $this->db->where('bo_id',1)->get('boleto_config')->row();
			
			// DADOS PERSONALIZADOS - SANTANDER BANESPA
			$dadosboleto["codigo_cliente"] = $boletoConfig->bo_codigo_cliente; // Código do Cliente (PSK) (Somente 7 digitos)
			$dadosboleto["ponto_venda"] = $boletoConfig->bo_ponto_venda; // Ponto de Venda = Agencia
			$dadosboleto["carteira"] = $boletoConfig->bo_carteira;  // Cobrança Simples - SEM Registro
			$dadosboleto["carteira_descricao"] = $boletoConfig->bo_carteira_descricao;  // Descrição da Carteira

			$industria = $this->db
			->join('cidades','ci_id=fa_cidade')
			->get('fabricas')->row();

			// SEUS DADOS
			$dadosboleto["identificacao"] = $industria->fa_nome;
			$dadosboleto["cpf_cnpj"] = $industria->fa_cnpj;
			$dadosboleto["endereco"] = $industria->fa_endereco;
			$dadosboleto["cidade_uf"] = $industria->ci_nome.'/'.$industria->ci_uf;
			$dadosboleto["cedente"] = $industria->fa_nome;					
			
			
			// NÃO ALTERAR!
			include(FCPATH."public/util/boleto/include/funcoes_santander_banespa.php"); 
			include(FCPATH."public/util/boleto/include/layout_santander_banespa.php");
		
		}
		
	
	function gerar_plano(){
             
			 if(!isset($_GET['c'])|| $_GET['c']=="" || !isset(get_user()->di_id)){
				 redirect(base_url()."index.php/pedidos");exit();
				 }
			
			 $id_boleto = $_GET['c'];
			
			//Mensalidade
			$m = $this->db->where('cl_id',$id_boleto)
			     ->where('cl_distribuidor',get_user()->di_id)
				 ->join("distribuidores","di_id = cl_distribuidor")
				 ->join('cidades','di_cidade = ci_id')
				 ->get("compra_plano")->result();
				 
			if(!$m){
				echo "<p>Nenhuma compra encontrada</p>";exit;
				}	 
			
		   
		   $this->db->where('co_id',$id_boleto)
		   ->update('compras',array('co_forma_pgt_txt'=>'Boleto Bancário','co_forma_pgt'=>1));								 
			
			$dias_de_prazo_para_pagamento = 5;
			$taxa_boleto = 0.0;
			 
			$data_venc = date('d/m/Y',
			mktime(0,0,0,
			date('m',strtotime($m[0]->cl_data_insert)),
			(date('d',strtotime($m[0]->cl_data_insert))+3),
			date('Y',strtotime($m[0]->cl_data_insert))
			));  // Prazo de X dias OU informe data: "13/04/2006";
			
			$valor_cobrado = number_format($m[0]->cl_valor,2,",","");; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
			$valor_cobrado = str_replace(",", ".",$valor_cobrado);
			$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
			
			// Composição Nosso Numero - CEF SIGCB
			$dadosboleto["nosso_numero1"] = "100"; // tamanho 3
			$dadosboleto["nosso_numero_const1"] = "2"; //constanto 1 , 1=registrada , 2=sem registro
			$dadosboleto["nosso_numero2"] = "000"; // tamanho 3
			$dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=emitido pelo proprio cliente
			$dadosboleto["nosso_numero3"] = (1000000+$m[0]->cl_id); // tamanho 9 
			
			
			$dadosboleto["numero_documento"] = (1000000+$m[0]->cl_id);	// Num do pedido ou do documento
			$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
			$dadosboleto["data_documento"] = date('d/m/Y',strtotime($m[0]->cl_data_insert)); // Data de emissão do Boleto
			$dadosboleto["data_processamento"] = date('d/m/Y',strtotime($m[0]->cl_data_insert)); // Data de processamento do boleto (opcional)
			$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
			
			// DADOS DO SEU CLIENTE
			$dadosboleto["sacado"] = $m[0]->di_nome ." / NI: ".$m[0]->di_id;
			$dadosboleto["endereco1"] = $m[0]->di_endereco.", ".$m[0]->ci_nome."/".$m[0]->ci_uf;
			$dadosboleto["endereco2"] = $m[0]->di_cep;
			
			// INFORMACOES PARA O CLIENTE
			$dadosboleto["demonstrativo1"] = "Pagamento do Plano. Nª pedido ".$m[0]->di_id;
			$dadosboleto["demonstrativo2"] = '<div style="color:#c00;font-size:13px;">A CONFIRMAÇÃO DO PAGAMENTO DESTE BOLETO LEVA 3 DIAS ÚTEIS APÓS O PAGAMENTO</div>';
			$dadosboleto["demonstrativo3"] = '';
			
			// INSTRUÇÕES PARA O CAIXA
			$dadosboleto["instrucoes1"] = 'Pagamento preferêncial nas Caixas Loterias ou Caixa Econômica Federal';
			$dadosboleto["instrucoes2"] = '';
			$dadosboleto["instrucoes3"] = '';
			$dadosboleto["instrucoes4"] = '';
			
			// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
			$dadosboleto["quantidade"] = "";
			$dadosboleto["valor_unitario"] = "";
			$dadosboleto["aceite"] = "";		
			$dadosboleto["especie"] = "R$";
			$dadosboleto["especie_doc"] = "";
			
			
			// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
			
			
			// DADOS DA SUA CONTA - CEF
			$dadosboleto["agencia"] = '4339'; // Num da agencia, sem digito
			$dadosboleto["conta"] = '00000028'; 	// Num da conta, sem digito
			$dadosboleto["conta_dv"] = '8'; 	// Digito do Num da conta
			
			// DADOS PERSONALIZADOS - CEF
			$dadosboleto["conta_cedente"] = '378802'; // Código Cedente do Cliente, com 6 digitos (Somente Números)
			$dadosboleto["carteira"] = "SR";  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
			
			// SEUS DADOS
			$dadosboleto["identificacao"] = 'Portal Brasil';
			$dadosboleto["cpf_cnpj"] = "07.040.000/0002-00";
			$dadosboleto["endereco"] = "Av. T6";
			$dadosboleto["cidade_uf"] = "São Paulo / São Paulo";
			$dadosboleto["cedente"] = 'Portal Brasil';
			
			
			// NÃO ALTERAR!
			include(FCPATH."public/util/boleto/include/funcoes_cef_sigcb.php"); 
			include(FCPATH."public/util/boleto/include/layout_cef.php");
		
		}
		
	
	
	function boleto_gerado(){
		if(!$this->uri->segment(3)){
			redirect(base_url()."index.php/financeiro/meu_financeiro");
			}
		$data['pagina'] = "painel/boleto/boleto_gerado";
		$this->load->view('painel/painel',$data);
		}	
	


public function baixar(){		
				
		$data['boletos_atualizados'] = array();
			
		if(isset($_POST['baixar_boletos'])){
		
		
				
		$nome = $_FILES['arquivo']['name'];
		$type = $_FILES['arquivo']['type'];
		$size = $_FILES['arquivo']['size'];
		$tmp = $_FILES['arquivo']['tmp_name'];
		 
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
							
							$this->db->where("pg_id",$id_i_tal)->update('pagamento',array(
							'pg_valor'=>$u_v_pago,
							'pg_tipo'=>2,
							'pg_pagamento'=>1
							));

							
						
							
							$data['boletos_atualizados'][] = array(
							'arquivo_retorno'=>$nome,
							'valor_pago'=>$u_v_pago,
							'data_atulizacao'=>$u_dt_ocorencia,
							'pago'=>1,
							'id'=>substr($t_id_titulo_banco,6)
							);
						}
					}
					fclose($lendo);
				}
			}
		}else{
		echo "Tipo de Arquivo invalido";
		}
			
		}#fim de verificação do post
		
		$data['pagina'] = 'painel1232/boleto/baixa';
		$this->load->view('painel1232/painel1232',$data);
		
		}
	
		
	} 

?>