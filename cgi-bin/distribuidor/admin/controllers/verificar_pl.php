<?php

class verificar_pl extends CI_Controller {

    
        //Pagar PL anteriores
    public function cron_bonus_pl() {

        set_time_limit(0);

        $data = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');
        $Pl = new bonus_pl_correcao();
        $Pl->setDiaPagamento($data);
        $Pl->init();

        $Pl->pagarPl();
    }
    
    public function bonus() {

        header("Content-Type: text/html; charset=utf-8");
// 		//Pega o distribuidor para ver qual é o plano dele
        //Distribuidores a receber Bônus PL
        $distribuidores = $this->db->query("
			SELECT di_id, di_usuario,di_direita,di_esquerda,di_cpf,co_data_compra,
                        COUNT(di_id) as quantidadePlanos
			FROM distribuidores 
                        JOIN registro_ativacao ON di_id = at_distribuidor
                        JOIN compras ON co_id_distribuidor = di_id
                        WHERE co_data_compra <= '2014-03-07 17:00:00' 
                        AND co_pago = 1
                        AND co_eplano = 1
                        GROUP BY di_id
                        ")->result();

        $total_esperado = 0;
        $total = 0;
        $ipotese_erro = 0;
        foreach ($distribuidores as $d) {

            //Qual o plano do distribuidor.

            $qtdEsquerda = $this->db->query("SELECT COUNT(*) as total FROM distribuidor_ligacao
		     		 WHERE li_no = " . $d->di_esquerda . " AND li_id_distribuidor <> " . $d->di_id . "")->row();
            $qtdDireita = $this->db->query("SELECT COUNT(*) as total FROM distribuidor_ligacao 
		     		 WHERE li_no = " . $d->di_direita . " AND li_id_distribuidor <> " . $d->di_id . "")->row();

            $binarioAtivo = $this->db->query("SELECT * FROM registro_distribuidor_binario WHERE `db_distribuidor` = " . $d->di_id)->num_rows;


            ECHO "<br>DISTRIBUIDOR:<br> " . $d->di_usuario . " -  " . $d->di_id . "";
            echo "<br>Direita:" . $qtdDireita->total;
            echo "<br>Esquerda:" . $qtdEsquerda->total;

            $bonus_pl = $this->db->where("rbpl_distribuidor", $d->di_id)->get("registro_bonus_pl")->result();
            $total_verificar = 0;

            foreach ($bonus_pl as $bonus) {
                echo "<br> Recebeu #dia: {$bonus->rbpl_data} #valor: {$bonus->rbpl_valor}";
                if ($total_esperado == 0) {
                    $total_esperado++;
                }
                $total_verificar++;
            }
            if (count($qtdDireita) == 0) {
                echo "<br>erro";
            }



            echo "<br>----------------------------------------------------";
            $total++;
        }
        echo "<br>Total: " . $total;
        echo "  Total Possibilidade Erros:" . $ipotese_erro;
    }

    public function pagar_pl_antigas() {

        $distribuidores = $distribuidores = $this->db->query("
			SELECT di_id, di_usuario,di_direita,di_esquerda,di_cpf,co_data_compra,
                        COUNT(di_id) as quantidadePlanos
			FROM distribuidores 
                        JOIN registro_ativacao ON di_id = at_distribuidor
                        JOIN compras ON co_id_distribuidor = di_id
                        WHERE co_data_compra <= '2014-03-07 17:00:00' 
                        AND co_pago = 1
                        AND co_eplano = 1
                        GROUP BY di_id
                        ")->result();


        foreach ($distribuidores as $distribuidor) {

            $registro = $this->db->query("select
					 max(t.rbpl_data) as data
					from registro_bonus_pl as t
					 where t.rbpl_tipo=1
					 and t.rbpl_distribuidor={$distribuidor->di_id}")->row();


            $inicio = DateTime::createFromFormat('y/m/d H:i:s', date('y/m/d H:i:s', strtotime($registro->data)));
            $fim = DateTime::createFromFormat('y/m/d H:i:s', date('y/m/d H:i:s'));

            $intervalo = $inicio->diff($fim);


            if (date('y-m-d', strtotime($registro->data)) < date('y-m-d')) {
                for ($i = 0; $intervalo->days > $i; $i++) {
                    $data = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - $i, date('Y')));
                    $Pl = new bonus_pl_correcao($distribuidor);
                    $Pl->setDiaPagamento($data);
                    $Pl->pagarPl();
                }
            }
        }
    }



}