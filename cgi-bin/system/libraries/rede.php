<?php

class Rede {

    private $ci;
    private $nos_ids;
    private $cache_no_alterar;
    private $ids_rede = array();
    private $cache_no_inserir = 0;

    function __construct() {
        //ini_set('xdebug.max_nesting_level', 10000);
        $this->ci = & get_instance();
    }

    private function caminho_distribuidor($patrocinador) {

        $dis = $this->ci->db
                        ->select(array('di_id'))
                        ->where('di_direita', $patrocinador)
                        ->or_where('di_esquerda', $patrocinador)
                        ->get('distribuidores')->row();

        if (count($dis) > 0) {
            $this->nos_ids[] = $dis->di_id;
            $this->caminho_distribuidor($dis->di_id);
        }
    }

    private function inserir_no_patrocinador($patrocinador, $lado_rede) {
        $no = new stdClass;
        $no->id = $patrocinador->di_id;
        $no->lado = $lado_rede;

        //Se for para inserir na perna menor
        if ($lado_rede == false) {

            //Se lado direito e lado esquerdo for vazio
            //Insere sempre na perna de derramamento
            if ($patrocinador->di_esquerda == 0 && $patrocinador->di_direita == 0) {

                $ladoDerramamento = $this->ci->db
                                ->where('di_esquerda', $patrocinador->di_id)
                                ->or_where('di_direita', $patrocinador->di_id)
                                ->get('distribuidores')->row();

                if (count($ladoDerramamento) > 0) {


                    if ($ladoDerramamento->di_esquerda == $patrocinador->di_id) {
                        $no->lado = 'e';
                    } else {
                        $no->lado = 'd';
                    }

                    //Se lado esquerdo ou lado direito for diferente de zero
                    //Insere do lado que estiver vazio
                } else {

                    if ($patrocinador->di_esquerda == 0) {
                        $no->lado = 'e';
                    } else {
                        $no->lado = 'd';
                    }
                }
            } else {

                if ($patrocinador->di_esquerda == 0) {
                    $no->lado = 'e';
                } else {
                    $no->lado = 'd';
                }
            }
        }

        //Desativando o código de derramento
        if (ConfigSingleton::getValue('ativar_ou_destivar_codigo_promocional') == 0) {

            /**
             * Injeção de regra de lado  de rede.
             */
            $pontos = new Pontos($patrocinador);
            $plano = PlanosModel::getPlanoDistribuidor($patrocinador->di_id);

            //Se o patrocinador tem incluído o primeiro ou  segundo
            $sql = "select (select IF(!isnull(di_ni_patrocinador),1,0) from distribuidores where di_esquerda = {$patrocinador->di_id})  as esquerda,"
                    . "(select IF(!isnull(di_ni_patrocinador),1,0) from distribuidores where di_direita= {$patrocinador->di_id})  as direita";

            $di_lado_patrocinador = $this->ci->db->query($sql)->row();

            if ($di_lado_patrocinador->direita == 1) {
                if ((int) $pontos->get_pontos_direita_diretos() < (int) $plano->pa_pontos) {
                    $no->lado = 'd';
                }
            }
            if ($di_lado_patrocinador->esquerda == 1) {
                if ((int) $pontos->get_pontos_esquerda_diretos() < (int) $plano->pa_pontos) {
                    $no->lado = 'e';
                }
            }
        }

        self::ultimo_no_extremidade($patrocinador, $no->lado);
        $no->id = $this->cache_no_inserir->di_id;

        return $no;
    }

    private function inserir_na_rede($dis, $lado) {

        $no = new stdClass;
        $no->id = 0;
        $no->lado = $lado;

        //Desativando o código de derramento
        if (ConfigSingleton::getValue('ativar_ou_destivar_codigo_promocional') == 0) {
            /**
             * Injeção de regra de lado  de rede.
             */
            $pontos = new Pontos($dis);
            $plano = PlanosModel::getPlanoDistribuidor($dis->di_id);

            //Se o patrocinador tem incluído o primeiro ou  segundo
            $sql = "select (select IF(!isnull(di_ni_patrocinador),1,0) from distribuidores where di_esquerda = {$dis->di_id})  as esquerda,"
                    . "(select IF(!isnull(di_ni_patrocinador),1,0) from distribuidores where di_direita= {$dis->di_id})  as direita";

            $di_lado_patrocinador = $this->ci->db->query($sql)->row();

            if ($di_lado_patrocinador->direita == 1) {
                if ((int) $pontos->get_pontos_direita_diretos() < (int) $plano->pa_pontos) {
                    $no->lado = 'd';
                }
            }
            if ($di_lado_patrocinador->esquerda == 1) {
                if ((int) $pontos->get_pontos_esquerda_diretos() < (int) $plano->pa_pontos) {
                    $no->lado = 'e';
                }
            }
        }
        
        self::ultimo_no_extremidade($dis, $no->lado);
        $no->id = $this->cache_no_inserir->di_id;


        return $no;
    }

    public function ultimo_no_extremidade($no_parameter, $lado) {
        $fild_lado = $lado == 'e' ? 'di_esquerda' : 'di_direita';

        $no = $this->ci->db
                        ->where('di_id', $no_parameter->{$fild_lado})
                        ->select(array('di_id', 'di_esquerda', 'di_direita'))
                        ->get('distribuidores')->row();

        if (count($no) > 0) {
            self::ultimo_no_extremidade($no, $lado);
        } else {

            $this->cache_no_inserir = 0;
            $this->cache_no_inserir = $no_parameter;
        }
    }

    function perna_menor($dis_id, $esquerda, $direita) {

        $qtd_esquerda = $this->ci->db->query("
		 SELECT count(*) as qtd FROM `distribuidor_ligacao` 
		 WHERE `li_no` =  {$esquerda}
		")->row();

        $qtd_esquerda = $qtd_esquerda->qtd;


        $qtd_direita = $this->ci->db->query("
		 SELECT count(*) as qtd FROM `distribuidor_ligacao` 
		 WHERE `li_no` = {$direita}
		")->row();
        $qtd_direita = $qtd_direita->qtd;



        if ($qtd_esquerda <= $qtd_direita) {
            return 'e';
        } else {
            return 'd';
        }
    }

    public function realocar($di_id, $patrocinadorNovo) {



        //Distribuidor
        $distribuidor = $this->ci->db
                        ->join('cidades', 'ci_id = di_cidade')
                        ->where('di_id', $di_id)
                        ->get('distribuidores')->row();

        //é o patrocinador do usuário que está cadastrando
        $patAntigo = $this->ci->db
                        ->join('cidades', 'ci_id = di_cidade')
                        ->where('di_id', $distribuidor->di_ni_patrocinador)
                        ->get('distribuidores')->row();

        //Pegando o novo patrocinador do que vai receber o usuáriuo
        $pat = $this->ci->db
                        ->join('cidades', 'ci_id = di_cidade')
                        ->where('di_id', $patrocinadorNovo)
                        ->get('distribuidores')->row();

        //Pega quem ta abaixo e troca de possição com o usuario atual.
//        $this->subirUltimoNo($di_id);
        //recolocando todo mundo;

        if (count($pat) > 0) {

            if ($distribuidor->di_preferencia_indicador != 0) {
                $pat->di_lado_preferencial = $distribuidor->di_preferencia_indicador;
            }


            $no = false;
            //Se perna preferencial for a esquerda e a esquerda do patrocinador tiver vazia
            if ($pat->di_lado_preferencial == 1 && $pat->di_esquerda == 0) {
                $no = self::inserir_no_patrocinador($pat, 'e');
            }

            //Se perna preferencial for a direita e a direita do patrocinador tiver vazia
            if ($pat->di_lado_preferencial == 2 && $pat->di_direita == 0 && $no == false) {
                $no = self::inserir_no_patrocinador($pat, 'd');
            }

            //Se perna preferencial for a menor e a esquerda ou direita do patrocinador tiver vazia
            if ($pat->di_lado_preferencial == 3 && ($pat->di_esquerda == 0 || $pat->di_direita == 0) && $no == false) {
                $no = self::inserir_no_patrocinador($pat, false);
            }

            if ($pat->di_lado_preferencial == 1 && $pat->di_esquerda != 0 && $no == false) {
                $no = self::inserir_na_rede($pat, 'e');
            }

            if ($pat->di_lado_preferencial == 2 && $pat->di_direita != 0 && $no == false) {
                $no = self::inserir_na_rede($pat, 'd');
            }

            if ($pat->di_lado_preferencial == 3 && $pat->di_direita != 0 && $pat->di_esquerda != 0 && $no == false) {
                //Verificar perna menor
                $lado_menor = self::perna_menor($pat->di_id, $pat->di_esquerda, $pat->di_direita);

                $no = self::inserir_na_rede($pat, $lado_menor);
            }



            //Atualizo o nó
            if ($no) {

                if ($no->lado == 'e') {
                    $this->ci->db->select(array('di_id', 'di_esquerda', 'di_direita'))->where('di_id', $no->id)
                            ->update('distribuidores', array(
                                'di_esquerda' => $distribuidor->di_id
                    ));
                } elseif ($no->lado == 'd') {
                    $this->ci->db->select(array('di_id', 'di_esquerda', 'di_direita'))->where('di_id', $no->id)
                            ->update('distribuidores', array(
                                'di_direita' => $distribuidor->di_id
                    ));
                }
            }


            //notificacao_cadastro($distribuidor[0],$patrocinador[0]);

            $this->nos_ids = array($distribuidor->di_id);
            self::caminho_distribuidor($distribuidor->di_id);
            $caminho = array_reverse($this->nos_ids);




            foreach ($caminho as $k => $c) {
                $this->ci->db->insert('distribuidor_ligacao', array(
                    'li_id_distribuidor' => $di_id,
                    'li_posicao' => ($k + 1),
                    'li_no' => $c
                ));
            }
        }
    }

    private function subirUltimoNo($di_id) {
        //Pega toda rede abaido da rede informada.
        $redAntiga = $this->ci->db->where("li_no >= {$di_id} order by li_no")
                        ->get('distribuidor_ligacao')->result();

        $patrocinadorAntigo = $this->ci->db->where('di_id', $di_id)->get('distribuidores')->row();

        //Verifica a outra qual é aproxima rede.
        $patrocinadorProximaRed = 0;
        $patrocinadorProximaPosicao = 0;


        foreach ($redAntiga as $redAn) {
            if ($redAn->li_no == $di_id) {
                continue;
            }

            $patrocinadorProximaRed = $redAn->li_no;
            $patrocinadorProximaPosicao = $redAn->li_posicao;
            break;
        }

        //Alterando a possição de quem ta abaixo.
        $this->ci->db->where('li_no', $patrocinadorProximaRed)
                ->update('distribuidor_ligacao', array('li_posicao' => $patrocinadorProximaPosicao - 1));

        //Alterar o patrocinador subindo o nivel de quem ta embixo.
        $this->ci->db->where('di_id', $patrocinadorProximaRed)->update('distribuidores', array(
            'di_ni_patrocinador' => $patrocinadorAntigo->di_ni_patrocinador,
            'di_usuario_patrocinador' => $patrocinadorAntigo->di_usuario_patrocinador
        ));

        //Alterando o na li_ligação_distribuidor
        $this->ci->db->where('li_id_distribuidor', $patrocinadorProximaRed)
                ->where('li_no !=li_id_distribuidor')
                ->order_by('li_no', 'desc')
                ->limit(1)
                ->update('distribuidor_ligacao', array('li_no' => $patrocinadorAntigo->di_ni_patrocinador)
        );

        //Pegando todo mundo que ta abaixo do distribuidor para realocar.
        $cache_no_alterar = $this->ci->db->where('li_no', $di_id)
                        ->get('distribuidor_ligacao')->result();

        if (count($cache_no_alterar) > 0) {
            foreach ($cache_no_alterar as $key => $cache_no_alterar_value) {
                $this->cache_no_alterar[] = $cache_no_alterar_value;
            }
        }

        return $this->cache_no_alterar;
    }

    function alocar($di_id) {

        //Verificar se o distribuidor já foi alocado
        $alocado = $this->ci->db
                        ->select('di_id')
                        ->where('di_direita', $di_id)
                        ->or_where('di_esquerda', $di_id)
                        ->get('distribuidores')->row();

        $na_rede = $this->ci->db->where('li_id_distribuidor', $di_id)
                        ->get('distribuidor_ligacao')->num_rows;


        if (count($alocado) == 0 && $na_rede == 0) {

            //Distribuidor
            $distribuidor = $this->ci->db
                            ->join('cidades', 'ci_id = di_cidade')
                            ->where('di_id', $di_id)
                            ->get('distribuidores')->row();

            //é o patrocinador do usuário que está cadastrando
            $pat = $this->ci->db
                            ->join('cidades', 'ci_id = di_cidade')
                            ->where('di_id', $distribuidor->di_ni_patrocinador)
                            ->get('distribuidores')->row();

            if (count($pat) > 0) {

                if ($distribuidor->di_preferencia_indicador != 0) {
                    $pat->di_lado_preferencial = $distribuidor->di_preferencia_indicador;
                }


                $no = false;
                //Se perna preferencial for a esquerda e a esquerda do patrocinador tiver vazia
                if ($pat->di_lado_preferencial == 1 && $pat->di_esquerda == 0) {
                    $no = self::inserir_no_patrocinador($pat, 'e');
                }

                //Se perna preferencial for a direita e a direita do patrocinador tiver vazia
                if ($pat->di_lado_preferencial == 2 && $pat->di_direita == 0 && $no == false) {
                    $no = self::inserir_no_patrocinador($pat, 'd');
                }

                //Se perna preferencial for a menor e a esquerda ou direita do patrocinador tiver vazia
                if ($pat->di_lado_preferencial == 3 && ($pat->di_esquerda == 0 || $pat->di_direita == 0) && $no == false) {
                    $no = self::inserir_no_patrocinador($pat, false);
                }

                if ($pat->di_lado_preferencial == 1 && $pat->di_esquerda != 0 && $no == false) {
                    $no = self::inserir_na_rede($pat, 'e');
                }

                if ($pat->di_lado_preferencial == 2 && $pat->di_direita != 0 && $no == false) {
                    $no = self::inserir_na_rede($pat, 'd');
                }

                if ($pat->di_lado_preferencial == 3 && $pat->di_direita != 0 && $pat->di_esquerda != 0 && $no == false) {
                    //Verificar perna menor
                    $lado_menor = self::perna_menor($pat->di_id, $pat->di_esquerda, $pat->di_direita);

                    $no = self::inserir_na_rede($pat, $lado_menor);
                }


                //Atualizo o nó
                if ($no) {

                    if ($no->lado == 'e') {
                        $this->ci->db->select(array('di_id', 'di_esquerda', 'di_direita'))->where('di_id', $no->id)
                                ->update('distribuidores', array(
                                    'di_esquerda' => $distribuidor->di_id
                        ));
                    } elseif ($no->lado == 'd') {

                        $this->ci->db->select(array('di_id', 'di_esquerda', 'di_direita'))->where('di_id', $no->id)
                                ->update('distribuidores', array(
                                    'di_direita' => $distribuidor->di_id
                        ));
                    }
                }


                //notificacao_cadastro($distribuidor[0],$patrocinador[0]);

                $this->nos_ids = array($distribuidor->di_id);
                self::caminho_distribuidor($distribuidor->di_id);
                $caminho = array_reverse($this->nos_ids);




                foreach ($caminho as $k => $c) {
                    $this->ci->db->insert('distribuidor_ligacao', array(
                        'li_id_distribuidor' => $di_id,
                        'li_posicao' => ($k + 1),
                        'li_no' => $c
                    ));
                }
            }
        }
    }

}
