<?php 





class Cielo extends CI_Controller{

	

	

	function transacao(){
		//Mensalidades do usuario

		 $mensalidade = $this->db->where('co_id',$_GET['c'])
				 ->join("distribuidores","di_id = co_id_distribuidor")
				 ->join("cidades","ci_id = co_entrega_cidade")
				 ->get("compras")->result();

	

		$this->load->helper("cielo");

		

		

		define("TIPO_PARCELAMENTO",3);

        define("CAPTURAR_AUT",'true');

        define("AUTORIZACAO",2);

		

		

		$bandeira = $_GET['bandeira'];

		$foma_pagamento = $_GET['parcelas'];

			

		$Pedido = new Pedido();

		

		//Gravar os dados do Pedido

		

		

		// Lê dados do $_POST

		$Pedido->formaPagamentoBandeira = $bandeira; 

		

			$Pedido->formaPagamentoProduto = $foma_pagamento;

			$Pedido->formaPagamentoParcelas = $foma_pagamento;

		

		

		$Pedido->dadosEcNumero = CIELO;

		$Pedido->dadosEcChave = CIELO_CHAVE;

		

		$Pedido->capturar = CAPTURAR_AUT;	

		$Pedido->autorizar = AUTORIZACAO;

		

		$Pedido->dadosPedidoNumero = $mensalidade[0]->co_id; 

		$Pedido->dadosPedidoValor  = number_format($mensalidade[0]->co_frete_valor+$mensalidade[0]->co_total_valor,2,'','');

		$Pedido->urlRetorno = base_url("index.php/cielo/retorno")."?c=".$_GET['c'];

		

		// ENVIA REQUISIÇÃO SITE CIELO

		$objResposta = $Pedido->RequisicaoTransacao(false);

		

		$Pedido->tid = $objResposta->tid;

		$Pedido->pan = $objResposta->pan;

		$Pedido->status = $objResposta->status;

		

		$urlAutenticacao = "url-autenticacao";

		$Pedido->urlAutenticacao = $objResposta->$urlAutenticacao;

	

		// Serializa Pedido e guarda na SESSION

		$StrPedido = $Pedido->ToString();

		

		$this->db->where("co_id",$_GET['c'])->update("compras",array(
		"co_cielo_xml"=>$StrPedido,
		));

		

		redirect($Pedido->urlAutenticacao);

		

		}

		

	

	

	function retorno(){

       $this->load->helper("cielo");
	   $c = $this->db->where("co_id",$_GET['c'])->get("compras")->result();
	   $Pedido = new Pedido();
	   $Pedido->FromString($c[0]->co_cielo_xml);
	   $objResultado = $Pedido->RequisicaoConsulta();
        
		$compra = $this->db->where('co_id',$_GET['c'])->get('compras')->result();
		
		if($objResultado->status==6&&$compra[0]->co_pago==0){
		 
		 if($compra[0]->co_id_cd!=0 && $compra[0]->co_entrega==0){
			$st_situacao = 3; 
		 }else{
			 $st_situacao = 6; 
			 }
		   
	        ##Conta como paga
			$this->db->where('co_id',$_GET['c'])->update('compras',array(
			  'co_pago'=>1,
			  'co_situacao'=>$st_situacao 
			 ));
			 
			$this->load->library('estoque');
			##Credita o CD ou a Fabrica
			if($compra[0]->co_id_cd!=0 && $compra[0]->co_entrega==0){	  
			  $this->estoque->saida_cd($compra[0]->co_id);
			 }else{
				$this->estoque->saida_fabrica($compra[0]->co_id);				
				}
				
			 $this->saida_credito($compra[0]->co_id_distribuidor,$compra[0]->co_id,$compra[0]->co_id_cd);	

		}
		

		$data['resultado'] =  $this->status_transacao($objResultado->status);


		$data['pagina'] = strtolower(__CLASS__)
	 ."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	 
	   $this->load->view('home/index_view',$data);

		

	}



public function saida_credito($di_id,$compra_id,$id_cd){
	 
		
		#verifico já é ativo outros meses
		$ativacao = $this->db->query("
		SELECT di_nome,co_data_compra  FROM `compras` 
		JOIN distribuidores ON di_id = co_id_distribuidor
		WHERE `co_data_compra` < '".date('Y-m')."-01 00:00:00'
		AND co_pago = 1
		AND di_id = $di_id
		")->num_rows;
		
		
	  $ativo_mes = $this->db->where('cp_distribuidor',$di_id)
	  ->where('cp_data >',date('Y-m-01 00:00:00'))
	  ->get('credito_repasse')->num_rows;
		
		if($ativo_mes==0 && $ativacao>0){
			
				$this->db->insert('credito_repasse',array(
				'cp_cd'=>$id_cd,
				'cp_credito'=>0,
				'cp_debito'=>39.5,
				'cp_distribuidor'=>$di_id,
				'cp_time'=>time()
				));
				
			}
		
	
	}

	

function status_transacao($st)
		{
			$status;
			switch($st)
			{
				case "0": $status = "Criada";break;
				case "1": $status = "Em andamento";break;
				case "2": $status = "Autenticada";break;
				case "3": $status = "Não autenticada";break;
				case "4": $status = "Autorizada";break;
				case "5": $status = "Não autorizada";break;
				case "6": $status = "Capturada";break;
				case "8": $status = "Não capturada";break;
				case "9": $status = "Cancelada";break;
				case "10": $status = "Em autenticação";break;
				default: $status = "n/a";break;
			}
			return $status;
		}
	   	

	

	}