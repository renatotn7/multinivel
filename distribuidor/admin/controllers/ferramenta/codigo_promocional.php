<?php

header('Content-Type: text/html; charset=utf-8');
set_time_limit(0);

class codigo_promocional extends CI_Controller {

    private $ativou_binario;
    private $pontos_necessarios;
    private $ponto_esquerda;
    private $ponto_direita;
    private $planos;

    public function index() {
        //Pegando os distribuidores.
        $distribuidores = $this->db->where('di_excluido', 0)
                        ->where('co_eplano', 1)
                        ->where('co_total_valor !=0.00')
                        ->where("co_data_compra >= '2014-10-30 00:00:00'")
                        ->join('compras', 'co_id_distribuidor = di_id')
                        ->group_by('di_id')
                        ->get('distribuidores')->result();

        foreach ($distribuidores as $distribuidore_value) {
            
            $patrocinador = $this->db->where('di_id',$distribuidore_value->di_ni_patrocinador)
                                     ->get('distribuidores')
                                     ->row();
            
            echo "<h3>Patrocinador que Recebeu:{$patrocinador->di_usuario}</h3>";
            echo "<h3>Distribuidor qeu pagou:{$distribuidore_value->di_usuario}</h3>";
            //verificarndo o lado do derramento.
         
            $lado_derramamento = self::derramamento($distribuidore_value);

            //Derramanento esquerdo.
            if ($lado_derramamento == 'e') {
                echo "Derramamento esquerda</br>";

                //Verificando se completou o lado do derramamento.
                //Vericando se o usuário já recebeu.
                $ja_recebeu_codigo = $this->db
                                ->where('prk_compra', $distribuidore_value->co_id)
                                ->where('prk_distribuidor_patrocinador', $distribuidore_value->di_ni_patrocinador)
                                ->where('prk_perna_derramamento', 1)
                                ->get('produto_token_ativacao')->row();

                if (count($ja_recebeu_codigo) == 0) {
                    if (self::pontuacao_atingida($distribuidore_value, 'e')) {
                        echo "completou derramamento<br>";

                        $diferencaData = $this->verificar_data_derramamento($distribuidore_value, 'e');
                        //Verifica se o total de dias é menor que 14 dias
                        if ($diferencaData <= 7) {
                            echo "Recebeu Tonkens por Derramamento<br/>";
                            $this->codigo_derramamento($distribuidore_value);
                        }
                    }
                }

                //Verificando se ativou o binário
                $ja_recebeu_codigo = $this->db
                                ->where('prk_compra', $distribuidore_value->co_id)
                                ->where('prk_distribuidor_patrocinador', $distribuidore_value->di_ni_patrocinador)
                                ->where('prk_perna_derramamento', 0)
                                ->get('produto_token_ativacao')->row();

                if (count($ja_recebeu_codigo) == 0) {
                    if ($this->ativou_binario) {
                        echo 'Ativou o binário</br>';

                        $diferencaData = $this->verificar_dataAtivacao_binario($distribuidore_value, 'e');
                        //Verifica se o total de dias é menor que 14 dias
                        if ($diferencaData <= 14) {
                            echo "Recebeu Tonkens por Ativacão<br/>";
                            $this->codigo_ativacao($distribuidore_value);
                        }
                    }
                }
            }

            //Derramamento direito.
            if ($lado_derramamento == 'd') {
                echo "Derramamento Direita</br>";
                
                //Vericando se o usuário já recebeu.
                $ja_recebeu_codigo = $this->db
                                ->where('prk_compra', $distribuidore_value->co_id)
                                ->where('prk_distribuidor_patrocinador', $distribuidore_value->di_ni_patrocinador)
                                ->where('prk_perna_derramamento', 1)
                                ->get('produto_token_ativacao')->row();

                if (count($ja_recebeu_codigo) == 0) {
                    //Verificando se completou o lado do derramamento.
                    if (self::pontuacao_atingida($distribuidore_value, 'd')) {
                        echo "completou derramamento<br>";

                        $diferencaData = $this->verificar_data_derramamento($distribuidore_value, 'd');
                        //Verifica se o total de dias é menor que 14 dias
                        if ($diferencaData <= 7) {
                            echo "Recebeu Tonkens por Derramamento<br/>";
                            $this->codigo_derramamento($distribuidore_value);
                        }
                    }
                }

                //Verificando se ativou o binário
                $ja_recebeu_codigo = $this->db
                                ->where('prk_compra', $distribuidore_value->co_id)
                                ->where('prk_distribuidor_patrocinador', $distribuidore_value->di_ni_patrocinador)
                                ->where('prk_perna_derramamento', 0)
                                ->get('produto_token_ativacao')->row();

                if (count($ja_recebeu_codigo) == 0) {
                    if ($this->ativou_binario) {
                        echo 'Ativou o binário</br>';
                        $totaDias = $this->verificar_dataAtivacao_binario($distribuidore_value);
                        //Verifica se o total de dias é menor que 14 dias
                        if ($diferencaData <= 14) {
                            echo "Recebeu Tonkens por Ativacão<br/>";
                            $this->codigo_ativacao($distribuidore_value);
                        }
                    }
                }
            }

            echo "Pontos Necessários:{$this->pontos_necessarios}<br/>";
            $this->ativou_binario = false;
        }
    }

    public function codigo_derramamento($distribuidor, $derramamento = false) {
        $derramamento = true;
        if ($this->planos->pa_id == 103) {
            for ($i = 0; $i < $this->planos->pa_numero_token_derramamento; $i++) {
                echo '<br/>' . ($i + 1) . ' - ' . ComprasModel::gerarTokenAtivacaoPromocional($distribuidor->co_id, $distribuidor, $derramamento,true);
            }
        }

        //RN 002
        if ($this->planos->pa_id == 102) {
            for ($i = 0; $i < $this->planos->pa_numero_token_derramamento; $i++) {
                echo '<br/>' . ($i + 1) . ' - ' . ComprasModel::gerarTokenAtivacaoPromocional($distribuidor->co_id, $distribuidor, $derramamento,true);
            }
        }

        //RN 003
        if ($this->planos->pa_id == 101) {
            for ($i = 0; $i < $this->planos->pa_numero_token_derramamento; $i++) {
                echo '<br/>' . ($i + 1) . ' - ' . ComprasModel::gerarTokenAtivacaoPromocional($distribuidor->co_id, $distribuidor, $derramamento,true);
            }
        }

        //RN 004
        if ($this->planos->pa_id == 100) {
            for ($i = 0; $i < $this->planos->pa_numero_token_derramamento; $i++) {
                echo '<br/>' . ($i + 1) . ' - ' . ComprasModel::gerarTokenAtivacaoPromocional($distribuidor->co_id, $distribuidor, $derramamento,true);
            }
        }

        //RN 005
        if ($this->planos->pa_id == 99) {
            for ($i = 0; $i < $this->planos->pa_numero_token_derramamento; $i++) {
                echo '<br/>' . ($i + 1) . ' - ' . ComprasModel::gerarTokenAtivacaoPromocional($distribuidor->co_id, $distribuidor, $derramamento,true);
            }
        }
        echo "<br/>";
    }

    public function codigo_ativacao($distribuidor = array(), $derramamento = false) {
        $derramamento = false;

        if ($this->planos->pa_id == 103) {
            for ($i = 0; $i < $this->planos->pa_numero_token_ativacao_binario; $i++) {
                echo '<br/>' . ($i + 1) . ' - ' . ComprasModel::gerarTokenAtivacaoPromocional($distribuidor->co_id, $distribuidor, $derramamento,true);
            }
        }

        //RN 002
        if ($this->planos->pa_id == 102) {
            for ($i = 0; $i < $this->planos->pa_numero_token_ativacao_binario; $i++) {
                echo '<br/>' . ($i + 1) . ' - ' . ComprasModel::gerarTokenAtivacaoPromocional($distribuidor->co_id, $distribuidor, $derramamento,true);
            }
        }

        //RN 003
        if ($this->planos->pa_id == 101) {
            for ($i = 0; $i < $this->planos->pa_numero_token_ativacao_binario; $i++) {
                echo '<br/>' . ($i + 1) . ' - ' . ComprasModel::gerarTokenAtivacaoPromocional($distribuidor->co_id, $distribuidor, $derramamento,true);
            }
        }

        //RN 004
        if ($this->planos->pa_id == 100) {
            for ($i = 0; $i < $this->planos->pa_numero_token_ativacao_binario; $i++) {
                echo '<br/>' . ($i + 1) . ' - ' . ComprasModel::gerarTokenAtivacaoPromocional($distribuidor->co_id, $distribuidor, $derramamento,true);
            }
        }

        //RN 005
        if ($this->planos->pa_id == 99) {
            for ($i = 0; $i < $this->planos->pa_numero_token_ativacao_binario; $i++) {
                echo '<br/>' . ($i + 1) . ' - ' . ComprasModel::gerarTokenAtivacaoPromocional($distribuidor->co_id, $distribuidor, $derramamento,true);
            }
        }
        echo "<br/>";
    }

    /**
     * Verificar qual lado é o derramamento do usuário
     */
    public function derramamento($distribuidores = array()) {
        $sql_ = "select (select IF(!isnull(di_ni_patrocinador),1,0) from distribuidores where di_esquerda = {$distribuidores->di_id})  as esquerda,";
        $sql_.= "(select IF(!isnull(di_ni_patrocinador),1,0) from distribuidores where di_direita= {$distribuidores->di_id})  as direita";

        $di_lado_patrocinador = $this->db->query($sql_)->row();

        if ($di_lado_patrocinador->direita == 1) {
            return 'e';
        }

        if ($di_lado_patrocinador->esquerda == 1) {
            return 'd';
        }
    }

    /**
     * Verificar se conseguir atingir a pontuação de do lado de 
     * derramamento.
     */
    public function pontuacao_atingida($distribuidor = array(), $lado) {
        $pontos = new Pontos($distribuidor);
        $plano = PlanosModel::getPlanoDistribuidor($distribuidor->di_id);
        $this->planos = $plano;
        $this->pontos_necessarios = $plano->pa_pontos;
        $this->ponto_esquerda = $pontos->get_pontos_esquerda_diretos();
        $this->ponto_direita = $pontos->get_pontos_direita_diretos();

        //Verificando se ativou o binário.
        if ($this->pernaMenor() >= $this->pontos_necessarios) {
            $this->ativou_binario = true;
        } else {
            $this->ativou_binario = false;
        }

        if ($lado == 'e') {
            if ($pontos->get_pontos_esquerda_diretos() >= $this->pontos_necessarios) {
                return true;
            } else {
                return false;
            }
        }

        if ($lado == 'd') {
            if ($pontos->get_pontos_direita_diretos() >= $this->pontos_necessarios) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function verificar_data_derramamento($distribuidor = array(), $lado) {

        $id_lado = 0;
        $total_pontos = 0;
        $patrocinador = $distribuidor;

        if ($lado == 'e') {
            $id_lado = $patrocinador->di_esquerda;
        }

        if ($lado == 'd') {
            $id_lado = $patrocinador->di_direita;
        }

        $sql_lado = "SELECT SQL_CACHE co_total_pontos,co_data_compra
                            FROM `distribuidor_ligacao` 
                            JOIN distribuidores ON di_id = `li_id_distribuidor`
                            JOIN compras ON di_id = `co_id_distribuidor`
                            WHERE `li_no` =  {$id_lado}
                             AND di_ni_patrocinador = {$patrocinador->di_id}
                             AND co_pago = 1
                             AND co_eplano=1
                             AND co_total_pontos !=0
                             AND co_total_valor !=0.00";

        $distribuidor_data = $this->db->query($sql_lado)
                ->result();

        //Somando pontos.
        foreach ($distribuidor_data as $key => $distribuidor_data_value) {
            $total_pontos+= $distribuidor_data_value->co_total_pontos;

            //Verifica ate atigir a pontuacao necessária.
            if ($total_pontos >= $this->pontos_necessarios) {

                //Pega a data da compra dos distribuidor para verificar se paga os tokens ,
                $diferencaData = funcoesdb::diffData(date('Y-m-d', strtotime($patrocinador->co_data_compra)), date('Y-m-d', strtotime($distribuidor_data_value->co_data_compra)));
                $diferencaData = abs($diferencaData);
                
                echo "Data do cadastro: {$patrocinador->co_data_compra} ===> data Completou derramamento:{$distribuidor_data_value->co_data_compra}<br/>";
                echo "tem {$diferencaData} dias<br/>";
                echo "Total pontos: {$total_pontos}<br/>";
                return $diferencaData;
                break;
            }
        }
    }

    public function verificar_dataAtivacao_binario($distribuidor) {

        $total_pontos_direito = 0;
        $total_pontos_esquerdo = 0;
        $data_direita = '';
        $data_esquerda = '';

        $registroBinario = $this->db->where('db_distribuidor', $distribuidor->di_id)
                        ->get('registro_distribuidor_binario')->row();

        $sql_lado_es = "SELECT SQL_CACHE co_total_pontos,co_data_compra
                            FROM `distribuidor_ligacao` 
                            JOIN distribuidores ON di_id = `li_id_distribuidor`
                            JOIN compras ON di_id = `co_id_distribuidor`
                            WHERE `li_no` =  {$distribuidor->di_esquerda}
                             AND di_ni_patrocinador = {$distribuidor->di_id}
                             AND co_pago = 1
                             AND co_eplano=1
                             AND co_total_pontos !=0
                             AND co_total_valor !=0.00";

        $distribuidor_data = $this->db->query($sql_lado_es)
                ->result();

        //Somando pontos.
        foreach ($distribuidor_data as $key => $distribuidor_data_value) {
            $total_pontos_esquerdo+= $distribuidor_data_value->co_total_pontos;

            //Verifica ate atigir a pontuacao necessária.
            if ($total_pontos_esquerdo >= $this->pontos_necessarios) {

                //Pega a data da compra dos distribuidor para verificar se paga os tokens ,
                $data_esquerda = (int) funcoesdb::diffData($distribuidor_data_value->co_data_compra, $registroBinario->db_data);
                break;
            }
        }

        //Olhando o lado direito.
        $sql_lado_di = "SELECT SQL_CACHE co_total_pontos,co_data_compra
                            FROM `distribuidor_ligacao` 
                            JOIN distribuidores ON di_id = `li_id_distribuidor`
                            JOIN compras ON di_id = `co_id_distribuidor`
                            WHERE `li_no` =  {$distribuidor->di_direita}
                             AND di_ni_patrocinador = {$distribuidor->di_id}
                             AND co_pago = 1
                             AND co_eplano=1
                             AND co_total_pontos !=0
                             AND co_total_valor !=0.00";

        $distribuidor_data = $this->db->query($sql_lado_di)
                ->result();

        //Somando pontos.
        foreach ($distribuidor_data as $key => $distribuidor_data_value) {
            $total_pontos_direito += $distribuidor_data_value->co_total_pontos;

            //Verifica ate atigir a pontuacao necessária.
            if ($total_pontos_direito >= $this->pontos_necessarios) {

                //Pega a data da compra dos distribuidor para verificar se paga os tokens ,
                $data_direita = (int) funcoesdb::diffData($distribuidor_data_value->co_data_compra, $registroBinario->db_data);

                break;
            }
        }

        if ($data_esquerda > $data_direita) {
            return $data_esquerda;
        } else {
            return $data_direita;
        }
    }

    public function pernaMenor() {
        if ($this->ponto_direita < $this->ponto_esquerda) {
            return $this->ponto_direita;
        } else {
            return $this->ponto_esquerda;
        }
    }

}
