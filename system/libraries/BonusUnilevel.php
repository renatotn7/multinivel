<?php

class BonusUnilevel {

    private $ci;
    private $db;
    private $dataPl;
    private $limiteDiario;

    function __construct() {
        $this->ci = & get_instance();
        $this->db = $this->ci->db;
        $limit = $this->db->where('field', 'valor_maximo_diario')->get('config')->row();
        $this->limiteDiario = (float) $limit->valor;
    }

    /**
     * Verifica ser o Binário ta ativo, apto a receber o bônus.
     * na tabela Regitro ativação
     */
    public function binario_ativo($di_id){
         
        if($this->estaAtivoBinario($di_id)==false){
             return false;
         }
         
    	$seisMesesAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 180, date('Y')));
    	$dis = $this->db
    	->where('at_distribuidor',$di_id)
    	->where('at_data > "'.$seisMesesAtras.'"')->get('registro_ativacao')->row();
    	if(count($dis)>0)
    		return true;
    	else
    		return false;
    }
    
    
    public function pagar_bonus($compra) {
        $this->pagar_geracoes($compra);
    }

    //Essa função será chamada se o Administrador optar por 
    //pagar o bônus em gerações.
    //Configuração na tabela config
    public function pagar_geracoes($compra) {
        
        $AtivacaoMensal = new AtivacaoMensal();
        
        $objDistribuidor = $this->getPatrocinador($compra->co_id_distribuidor);

        $objPatrocinadorAtual = $this->getPatrocinador($objDistribuidor->di_ni_patrocinador);

        //echo "<p>Patrocinador Atual: ". $objPatrocinadorAtual->di_id."</p>";

        $maximoGeracoes = $this->db->select('max(pa_bonus_unilevel_geracoes) as geracao_maxima')->get('planos')->row();
        $geracaoMaxima = isset($maximoGeracoes->geracao_maxima) ? $maximoGeracoes->geracao_maxima : 0;

        $geracao = 1;
        while ($geracao <= $geracaoMaxima) {

            if(count($objPatrocinadorAtual) == 0){
                break;
            }
            
           
            
            $AtivacaoMensal->setDistribuidor($objPatrocinadorAtual);
           //Verifica se o patrocinador ta ativo no periodo de 6 meses
           if($this->binario_ativo($objPatrocinadorAtual->di_id) && $AtivacaoMensal->checarAtivacao()==true)
           {
            //Verifica para quem vai pagar o Bônus, distribuidor ou industria
            if (count($objPatrocinadorAtual) > 0) {

                $planoAtual = DistribuidorDAO::getPlano($objPatrocinadorAtual->di_id);


                //Geração maxima que o distribuidor recebe
                $geracaoMaximaDistribuidor = $planoAtual->pa_bonus_unilevel_geracoes;

                
                //Se a geração atual for maior que a geração que o distribuidor pode receber
                //o bônus ele não recebe
                if ($geracao <= $geracaoMaximaDistribuidor) {

                	//Verifica o limite de ganho Por CPF.
                    $valor = $planoAtual->pa_bonus_unilevel_valor;
                    $valorBonusAPagar = LimiteGanho::paraCPF($objPatrocinadorAtual->di_cpf, $valor, $this->limiteDiario, $this->dataPl);

                    if ($valor != $valorBonusAPagar) {
                    	$descricaoBonus = '
										   Bônus Unilevel <b>' . date('d/m/Y', strtotime($this->dataPl)) . '</b><br>
										   Você atingiu o limite de <b>US$ ' . number_format($this->limiteDiario, 2, ',', '.') . '</b> de bonificação diária.
										  ';
                    } else {
                    	$descricaoBonus =  'Bônus Unilevel <b>' . $objDistribuidor->di_usuario . '</b>';
                    }
                    
                    
                    if ($valorBonusAPagar > 0) {


                        //Registrando o pagamento
                        $this->db->insert('registro_bonus_unilevel', array(
                            'rb_distribuidor' => $objDistribuidor->di_id,
                            'rb_receptor' => $objPatrocinadorAtual->di_id,
                            'rb_compra' => $compra->co_id,
                            'rb_posicao' => $geracao,
                            'rb_valor' => $valorBonusAPagar,
                            'rb_data' => date('Y-m-d H:i:s')
                        ));


                        $pais = $this->db->where('es_id', $objPatrocinadorAtual->di_uf)->get('estados')->row();

                        //Se o distribuidor estiver ativo, recebe o 
                        //bonus diretamente em sua conta
                        if (BonusPerdido::receberBonus($pais->es_pais, $objPatrocinadorAtual->di_conta_verificada)) {

                            //Registrando o bônus na conta do distribuidor  
                            $this->db->insert('conta_bonus', array(
                                'cb_distribuidor' => $objPatrocinadorAtual->di_id,
                                'cb_descricao' =>$descricaoBonus,
                                'cb_credito' => $valorBonusAPagar,
                                'cb_tipo' => 80
                            ));

                            // Se tiver inativo, acomula o bônus
                            // para quando se ativar receber  
                        } else {

                            //Inserindo bônus de indicação para patrocinador
                            $this->db->insert('conta_bonus_perdido', array(
                                'cb_distribuidor' => $objPatrocinadorAtual->di_id,
                                'cb_descricao' => 'Bônus Unilevel <b>' . $objDistribuidor->di_usuario . '</b>',
                                'cb_credito' => $valorBonusAPagar,
                                'cb_tipo' => 80
                            ));
                            
                        }
                    }//Valor maior que zero
                    //Incrementando a geração
                }else{
                  //echo "<p>Geracao Error: G: $geracao P:". $objPatrocinadorAtual->di_ni_patrocinador."</p>";  
                }

                $geracao++;
            }
            
           }

            //O proximo patrocinador atual
            //Muda o patrocinador atual
            if (count($objPatrocinadorAtual) > 0) {
                //echo "<p>Buscando Pat: ". $objPatrocinadorAtual->di_ni_patrocinador."</p>";
                $objPatrocinadorAtual = $this->getPatrocinador($objPatrocinadorAtual->di_ni_patrocinador);
            } else {
                 //echo "<p>Nenhum pat</p>";
               
                $geracao = ($geracaoMaxima + 1);
            }
        }
    }


    private function getPatrocinador($idPatrocinador) {

        return $this->ci->db
                        ->select(array('di_id','di_uf','di_data_cad','di_cpf','di_conta_verificada','di_usuario', 'di_ni_patrocinador', '
			 (SELECT COUNT(*) 
			 FROM registro_ativacao 
			 WHERE at_distribuidor = di_id 
			 AND at_data LIKE \'%' . date('Y-m-') . '%\')
			 as ativacao
			'))
                        ->where('di_id', $idPatrocinador)
                        ->get('distribuidores')->row();
    }

    
    public function estaAtivoBinario($idDistribuidor){
        $binario = $this->db->where('db_distribuidor',$idDistribuidor)->get('registro_distribuidor_binario')->row();
        return count($binario)>0;
    }
    
}
