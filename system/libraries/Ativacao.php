<?php

class Ativacao {

    private $ci;

    /*
     * Contrutor inicializa a variavel ci
     * @return void
     */

    function __construct($distribuidor = NULL) {
        $this->ci = & get_instance();
    }

    /*
     * Verifica se essa compra vai ativar o distribuidor.
     * @return boolean
     */

    public function esta_ativo($idDistribuidor) {
        return self::tem_ativacao($idDistribuidor);
    }

    public static function tem_ativacao($diDistribuidor) {
        $AtivacaoMensal = new AtivacaoMensal();
        $AtivacaoMensal->setDistribuidorPorId($diDistribuidor);
        return $AtivacaoMensal->checarAtivacao();
    }

    /*
     * Verifica se essa compra vai ativar o distribuidor.
     * @return boolean
     */

    private function verificar_se_deve_ativar($compra) {

        if ($compra->co_eativo == 1) {
            return true;
        }

        if ($this->esta_ativo($compra->co_id_distribuidor)) {
            return false;
        }

        if ($compra->co_eplano == 1) {
            return true;
        }

        $seisMesesAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - 6, date('d'), date('Y')));

        if (!ComprasModel::compraAtivacaoMensal($compra->co_id, get_user(), true)) {
            $jaEstaAtivo = $this->ci->db->where('at_data >', $seisMesesAtras)
                    ->where('at_distribuidor', $compra->co_id_distribuidor)
                    ->get('registro_ativacao', 1)
                    ->row();

            if (count($jaEstaAtivo) > 0) {
                return false;
            }
        }

        return true;
    }

    /*
     * Lancar a ativação, essa função é chamada sempre que uma compra é paga
     * se for ativação, registra no banco
     * return void
     */

    public function lancar_ativacao($compra) {

        if ($this->verificar_se_deve_ativar($compra)) {
            
            //Não pode ser ativação mensal.
            ComprasModel::codigoPromocionalDerramamento($compra->co_id_distribuidor,true);
            
            $this->ci->db->insert('registro_ativacao', array(
                'at_distribuidor' => $compra->co_id_distribuidor,
                'at_compra' => $compra->co_id,
                'at_tipo' => ($compra->co_eativo == 1 ? 2 : 1),
                'at_data' => date('Y-m-d H:i:s')
            ));

            if ($compra->co_eplano == 1 && $compra->co_eupgrade == 0) {
                $rede = new Rede();
                $rede->alocar($compra->co_id_distribuidor);

                $BonusVendaVolume = new BonusVendaVolume();
                $BonusVendaVolume->pagar($compra);
            }

            if ($compra->co_eativo == 1) {
                //SO RECEBE BONUS ACUMULADO SE TIVER ATIVO NO MES PASSADO
                $bonus_unilevel = new BonusUnilevel();
                $bonus_unilevel->pagar_bonus($compra);
            }

            $distribuidor = $this->get_distribuidor($compra);
            //Enviado email no momento da ativação.
            if ($this->qual_pais($distribuidor) == 1 && $compra->co_eativo == 0) {

                $msg = "<br/><strong>Nome: </strong>" . $distribuidor->di_nome;
                $msg.= "<br/><strong>Usuário: </strong>" . $distribuidor->di_usuario;
                $msg.= "<br/><strong>CPF: </strong>" . $distribuidor->di_cpf;
                $msg.= "<br/><strong>Plano: </strong>" . $this->get_plano_selecionado($compra);
                $msg.= "<br/><strong>Endereço: </strong>" . $distribuidor->di_endereco;
                $msg.= "<br/><strong>Bairro: </strong>" . $distribuidor->di_bairro;
                $msg.= " <strong>CEP: </strong>" . $distribuidor->di_cep;
                $msg.= "<br><strong>Cidade: </strong>" . $this->get_cidade($distribuidor->di_cidade);
                $msg.= "" . $this->get_estado($distribuidor->di_cidade);
                $msg.= ",<strong> Brasil</strong>";

                /**
                 * 1 joias ou arterfatos de ouro
                 * 2 voucher de compras.
                 */
                if ($compra->co_tipo_plano == 2) {
                    $msg.= "<br/><h2>E-voucher : " . $this->get_voucher($distribuidor->di_id, $compra->co_id) . "</h2>";
                    foreach (explode(';', $this->get_config('email_brasil_cadastro_voucher')) as $email_brasil_cadastro_voucher) {
                        $this->envia_email($msg, $email_brasil_cadastro_voucher);
                    }
                }
                foreach (explode(';', $this->get_config('email_todos_cadastro_brasil')) as $email_todos_cadastro_brasil) {
                    $this->envia_email($msg, $email_todos_cadastro_brasil);
                }
            } else if ($this->qual_pais($distribuidor) == 2) {

                $msg = "<br/><strong>Nome: </strong>" . $distribuidor->di_nome;
                $msg.= "<br/><strong>Usuário: </strong>" . $distribuidor->di_usuario;
                $msg.= "<br/><strong>CPF: </strong>" . $distribuidor->di_cpf;
                $msg.= "<br/><strong>Plano: </strong>" . $this->get_plano_selecionado($compra);
                $msg.= "<br/><strong>Endereço: </strong>" . $distribuidor->di_endereco;
                $msg.= "<br/><strong>Bairro: </strong>" . $distribuidor->di_bairro;
                $msg.= " <strong>CEP: </strong>" . $distribuidor->di_cep;
                $msg.= "<br><strong>Cidade: </strong>" . $this->get_cidade($distribuidor->di_cidade);
                $msg.= "" . $this->get_estado($distribuidor->di_cidade);
                $msg.= ",<strong> Estados Unidos</strong>";

                foreach (explode(';', $this->get_config('email_todos_cadastro_usa')) as $email_todos_cadastro_usa) {
                    $this->envia_email($msg, $email_todos_cadastro_usa);
                }
            }
        }
    }

    /**
     * Retornar o numero do voucher.
     * @param unknown $di_id
     * @param unknown $co_id
     */
    private function get_voucher($di_id, $co_id) {
        if (!empty($co_id)) {
            $voucher = $this->ci->db
                            ->where('vo_id_compra', $co_id)
                            ->where('vo_id_distribuidor', $di_id)
                            ->get('compras_voucher')->row();
            return $voucher->vo_codigo;
        }
    }

    private function get_plano_selecionado($compra) {

        $plano = $this->ci->db->where('co_id_plano', $compra->co_id_plano)
                        ->join('planos', 'pa_id=co_id_plano')
                        ->get('compras')->row();
        if (count($plano) > 0) {
            return $plano->pa_descricao;
        } else {
            return '';
        }
    }

    private function get_estado($es_di) {
        $estado = $this->ci->db->where('es_id', $es_di)->get('estados')->row();
        if (count($estado) > 0) {
            return $estado->es_nome;
        } else {
            return '';
        }
    }

    private function get_cidade($ci_id) {
        $cidade = $this->ci->db->where('ci_id', $ci_id)->get('cidades')->row();
        if (count($cidade) > 0) {
            return $cidade->ci_nome;
        } else {
            return '';
        }
    }

    /**
     * Envia email de acordo com a ativação do 
     * do cadastro do usuário. 
     * emails vide tabela config
     * @param objeto $distribuidor
     * 
     */
    public function envia_email($msg = '', $to = '') {



        $headers = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-type: text/plain; charset=utf-8";
        $headers[] = "From: <{$to}>";
        $headers[] = "X-Mailer: PHP/" . phpversion();
        $headers[] = "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
        $headers[] = "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
        @header('Content-Type: text/html; charset=utf-8');
        @mail($to, utf8_decode("Ativação de cadastro"), utf8_decode($msg), implode("\r\n", $headers));
    }

    /**
     * Retorna o distribuidor  da compra passada.
     * @param unknown $compra
     */
    private function get_distribuidor($compra) {
        if (count($compra) > 0) {
            $distribuidor = $this->ci->db
                            ->select(array('distribuidores.*'), false)
                            ->where('di_id', $compra->co_id_distribuidor)
                            ->join('distribuidores', 'co_id_distribuidor=di_id')
                            ->get('compras')->row();

            return $distribuidor;
        }
    }

    /**
     * Verifica qual pais é o distribuidor que ta se ativando.
     * @param objeto $distribuidor
     */
    private function qual_pais($distribuidor) {
        $pais = $this->ci->db
                        ->where('ci_id', $distribuidor->di_cidade)
                        ->join('cidades', 'ci_pais= ps_id')->get('pais')->row();
        return $pais->ps_id;
    }

    /**
     * 
     */
    private function get_config($field = '') {
        if (!empty($field)) {
            $config = $this->ci->db->where('field', $field)->get('config')->row();

            if (count($config) > 0) {
                return $config->valor;
            }
        }
    }
    
    public static function estavaAtivo($idDistribuidor,$data){
        return get_instance()
                ->db
                ->where('at_distribuidor',$idDistribuidor)
                ->where('at_data >=',date('Y-m-01 00:00:00',  strtotime($data)))
                ->where('at_data <=',$data)
                ->get('registro_ativacao')->num_rows > 0;
    }

}
