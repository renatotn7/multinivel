<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

/**
 * Bônus Binário.
 * @author helber
 *
 */
class Bonus_indireto extends CI_Controller {

    private $array_ids;
    private $m;
    private $d;
    private $y;
    private $mes;
    private $limiteDiario;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '128M');

        $data_pag = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));

        $this->m = date('m', $data_pag);
        $this->y = date('Y', $data_pag);
        $this->d = date('d', $data_pag);
        $this->mes = $this->y . '-' . $this->m . '-' . $this->d;

        $limit = $this->db->where('field', 'valor_maximo_diario')->get('config')->row();
        $this->limiteDiario = (float) $limit->valor;
    }

    public function index() {
        ob_start();
        CHtml::berginTime();

        $config = $this->db->where('field', 'token_seguranca')->get('config')->row();

        /*
         * Para receber o bonus bionário.
         * 1 - Ter um plano e esta no registro de binário
         */

        //Join binário
        $this->db->query("update distribuidores set di_uf = 9 where di_uf=0;");
        $this->db->query("update distribuidores set di_cidade = 39505 where di_cidade=0;");

        $dis = $this->db->query("
		 SELECT di_cpf,di_id,di_usuario,di_direita,di_esquerda, es_pais, di_conta_verificada
		 FROM distribuidores
                    JOIN estados on es_id = di_uf
                    JOIN registro_planos_distribuidor ON ps_distribuidor = di_id
                    join registro_distribuidor_binario on db_distribuidor= di_id
		")->result();

        foreach ($dis as $k => $d) {
            $obj_pontos = new PontosBonusBinario();
            $obj_pontos->setDistribuidor($d);
            $pontos_a_pagar = $obj_pontos->pontosAPagar();

            if ($pontos_a_pagar > 0) {

                echo "<p>D:{$d->di_usuario} => {$d->di_id} P: {$pontos_a_pagar}</p>";
                //Verifica se ele ta no perido de ativação que é de 6 meses
                if ($this->binario_ativo($d->di_id)) {
                    //SE FOR BRASILEIRO E A CONTA NAO TIVER VERIFICADA
                    if (!BonusPerdido::receberBonus($d->es_pais, $d->di_conta_verificada)) {
                        self::salvar_pontos_perdidos($d->di_id, $pontos_a_pagar, $d->di_cpf);
                    } else {
                        self::salvar_pontos($d->di_id, $pontos_a_pagar, $d->di_cpf);
                    }
                }
            }
        }
        CHtml::endTime();
        $registro = ob_get_contents();
        ob_end_clean();
        echo $registro;
        CHtml::logexec('Bonus_binario_data_' . date('H_i_s'),$registro.' em '.date('d_m_Y_H_s_i'), 'bonus_binario');
    }

    public function salvar_pontos($di_id, $pontos, $cpf) {
        ECHO('############### GANHOU ##################');
        //BUSCAMOS O PLANO DO DISTRIBUIDOR
        $plano = DistribuidorDAO::getPlano($di_id);

        //IMPLEMENTAÇÃO DAS MUDANÇAS COM A ENTRADA DOS PLANOS
        $valorEmPontos = ($pontos * $plano->pa_binario ) / 100;


        $ontemDateTime = date('Y-m-d 23:59:59', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        $ontemDate = date('Y-m-d', strtotime($ontemDateTime));

        //Limite Diário de ganho por CPF
        $percentual = LimiteGanho::paraCPF($cpf, $valorEmPontos, $this->limiteDiario, $ontemDate);
        $pontosPagos = $pontos * $percentual / $valorEmPontos;

        if ($percentual != $valorEmPontos) {
            $descricaoBonus = "Bônus Binário: <b>" . number_format($pontosPagos, 0, ',', '.') . "</b> pontos<br>
					Você atingiu o limite de <b>US$ " . number_format($this->limiteDiario, 2, ',', '.') . "</b> de bonificação diária.
					";
        } else {
            $descricaoBonus = "Bônus Binário: <b>" . number_format($pontosPagos, 0, ',', '.') . "</b> pontos<br>";
        }



        echo "<p>Pagar para $di_id: {$pontos} a {$plano->pa_binario}%</p>";
        echo "<p>Total em pontos {$valorEmPontos}</p>";
        echo "<p>Total em pontos depois dos calculos de limite {$pontosPagos}</p>";
        echo "<p>Valor depositado {$percentual}</p>";
        echo "<div>-------------------------------</div>";


        $this->db->insert('registro_bonus_indireto_pagos', array(
            'pg_distribuidor' => $di_id,
            'pg_pontos' => $pontos,
            'pg_data' => $ontemDate,
            'pg_atualizacao' => $ontemDateTime
        ));


        $this->db->insert('conta_bonus', array(
            'cb_distribuidor' => $di_id,
            'cb_descricao' => $descricaoBonus,
            'cb_credito' => $percentual,
            'cb_tipo' => 2,
            'cb_data_hora' => $ontemDateTime
        ));

        $idContaBonus = $this->db->insert_id();

        if ($percentual != $valorEmPontos) {

            $this->db->insert('registro_ganho_limite_diario', array(
                'gl_distribuidor' => $di_id,
                'gl_id_conta_bonus' => $idContaBonus,
                'gl_descricao' => "Ganhos de Bônus Binário excedente.",
                'gl_valor' => ($valorEmPontos - $percentual),
                'gl_tipo' => 2,
                'gl_data' => date('Y-m-d H:i:s')
            ));
        }
    }

    public function salvar_pontos_perdidos($di_id, $pontos, $cpf) {

        //BUSCAMOS O PLANO DO DISTRIBUIDOR
        $plano = $this->db
                        ->join('planos', 'ps_plano = pa_id')
                        ->where('ps_distribuidor', $di_id)
                        ->order_by('ps_plano', 'desc')
                        ->limit(1)
                        ->get('registro_planos_distribuidor')->row();

        //IMPLEMENTAÇÃO DAS MUDANÇAS COM A ENTRADA DOS PLANOS
        $valorEmPontos = ($pontos * ($plano->pa_binario / 100));

        $ontemDateTime = date('Y-m-d 23:59:59', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        $ontemDate = date('Y-m-d', strtotime($ontemDateTime));

        $percentual = LimiteGanho::paraCPF($cpf, $valorEmPontos, $this->limiteDiario, $ontemDate);
        $pontosPagos = $pontos * $percentual / $valorEmPontos;

        if ($percentual != $valorEmPontos) {
            $descricaoBonus = "
					Bônus Binário: <b>" . number_format($pontosPagos, 0, ',', '.') . "</b> pontos<br>
					Você atingiu o limite de <b>US$ " . number_format($this->limiteDiario, 2, ',', '.') . "</b> de bonificação diária.
					";
        } else {
            $descricaoBonus = "Bônus Binário: <b>" . number_format($pontosPagos, 0, ',', '.') . "</b> pontos<br>";
        }

        $this->db->insert('conta_bonus_perdido', array(
            'cb_distribuidor' => $di_id,
            'cb_descricao' => $descricaoBonus,
            'cb_credito' => $percentual,
            'cb_tipo' => 2,
            'cb_data_hora' => $ontemDateTime
        ));
    }

    /**
     * Verifica ser o Binário ta ativo, apto a receber o bônus.
     * na tabela Regitro ativação
     */
    public function binario_ativo($di_id) {
        $seisMesesAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 180, date('Y')));
        $dis = $this->db
                        ->where('at_distribuidor', $di_id)
                        ->where('at_data > "' . $seisMesesAtras . '"')->get('registro_ativacao')->row();
        if (count($dis) > 0)
            return true;
        else
            return false;
    }

}
