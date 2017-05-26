<?php

class Creditar_distribuidor extends CI_Controller {

    function inserir_credito() {

        permissao('creditar_distribuidor', 'creditar', get_user(), true);

        autenticar();

        $data['pagina'] = strtolower(__CLASS__) .
                "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    function checa_dados() {

        autenticar();

        if ($this->uri->segment(3)) {
            $usuario = $this->db
                            ->join('distribuidor_ligacao', ' distribuidor_ligacao.li_id_distribuidor = distribuidores.di_id')
                            ->join('cidades', ' cidades.ci_id = distribuidores.di_cidade')
                            ->join('estados', ' estados.es_id = distribuidores.di_uf')
                            ->where('di_id', $this->uri->segment(3))
                            ->get('distribuidores')->row();
        } else {
            $usuario = $this->db
                            ->join('distribuidor_ligacao', ' distribuidor_ligacao.li_id_distribuidor = distribuidores.di_id')
                            ->join('cidades', ' cidades.ci_id = distribuidores.di_cidade')
                            ->join('estados', ' estados.es_id = distribuidores.di_uf')
                            ->where('di_usuario', $_POST['di_usuario'])
                            ->get('distribuidores')->row();
        }

        if (sizeof($usuario)) {
            $data['usuario'] = $usuario;
        } else {
            set_notificacao(0, "Usuário não faz parte da rede!");
            redirect(base_url('index.php/creditar_distribuidor/inserir_credito'));
            exit;
        }
        
        $data['saldo'] = $this->db->where('cb_distribuidor',$usuario->di_id)
                ->select('sum(cb_credito) - sum(cb_debito) as saldo')
                ->get('conta_bonus')->row()->saldo;
        
        $data['pagina'] = strtolower(__CLASS__) .
                "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    function finaliza_credito() {

        autenticar();

        if ($this->uri->segment(3)) {
            $id_usuario = $this->uri->segment(3);
        } else {
            set_notificacao(0, "A indentificação do distribuidor foi perdida, tente novamente!");
            redirect(base_url('index.php/creditar_distribuidor/inserir_credito'));
            exit;
        }

        $usuario = $this->db
                        ->join('distribuidor_ligacao', ' distribuidor_ligacao.li_id_distribuidor = distribuidores.di_id')
                        ->join('cidades', ' cidades.ci_id = distribuidores.di_cidade')
                        ->join('estados', ' estados.es_id = distribuidores.di_uf')
                        ->where('di_id', $this->uri->segment(3))
                        ->get('distribuidores')->row();

        if (!sizeof($usuario)) {
            set_notificacao(0, "Usuário não faz parte da rede!");

            redirect(base_url('index.php/creditar_distribuidor/inserir_credito'));
            exit;
        }
        
        //Classe para validação do administrador
        $classValida = new ValidaSenhaAdministrador();
        $classValida->validar(get_user()->rf_id, $_POST['di_senha'], base_url('index.php/creditar_distribuidor/checa_dados/' . $usuario->di_id));

        
        
        if (!$this->isNumber($_POST['cb_credito'])) {//verifica se contém letras na var
            set_notificacao(0, "Campo Valor deve conter apenas números!");
            redirect(base_url('index.php/creditar_distribuidor/inserir_credito'));
            exit;
        }

        if (is_null($_POST['cb_credito'])) {
            set_notificacao(0, "Escolha um tipo de conta para depositar");
            redirect(base_url('index.php/creditar_distribuidor/inserir_credito'));
            exit;
        }

        if (!empty($_POST['cb_credito']) && $_POST['cb_credito'] > 0) {

            $campo = 'cb_' . $_POST['tipo'];
            $acao = $_POST['tipo'] == 'credito' ? 'Creditado' : 'Debitado';
            $cb_tipo = $_POST['tipo'] == 'credito' ? 22 : 21;
            $descricao = $_POST['descricao'];
            $bonus = array(
                'cb_distribuidor' => $usuario->di_id,
                'cb_compra' => '0',
                'cb_descricao' => $descricao ." <b>{$acao}</b>",
                 $campo => $_POST['cb_credito'],
                'cb_tipo' => $cb_tipo //Transferência de Crédito para distribuidor
            );

            $auditoria = array(
                'aurc_usuario_administrador' => get_user()->rf_id,
                'aurc_usuario_distribuidor' => $usuario->di_id,
                'aurc_id_bonus' => '0',
                'aurc_valor' => $_POST['cb_credito']
            );

            $this->db->trans_begin();


            $this->db->insert('conta_bonus', $bonus);

            $auditoria['aurc_id_bonus'] = $this->db->insert_id();

            $this->db->insert('auditoria_registro_credito', $auditoria);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                set_notificacao(0, "Houve uma falha no registro da operação em nossa base de dados, tente novamente!");
                redirect(base_url('index.php/creditar_distribuidor/checa_dados/' . $usuario->di_id));
                exit;
            } else {
                $this->db->trans_commit();
                set_notificacao(1, $acao . " executado com sucesso!");
                $data['pagina'] = 'creditar_distribuidor/inserir_credito';
            }
        } else {
            set_notificacao(0, "O valor a ser {$acao} para '" . $usuario->di_nome . ", deve ser maior que 0 (Zero)!");
            $data['pagina'] = 'creditar_distribuidor/checa_dados/' . $usuario->di_id;
        }

        $this->load->view('home/index_view', $data);
    }

    public function isNumber($var) {
        $var = str_replace(".", "", $var);
        return ctype_digit($var);
    }

}
