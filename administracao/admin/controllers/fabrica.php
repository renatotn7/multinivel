<?php

class Fabrica extends CI_Controller {

    function editar_responsavel() {

        if ($this->input->post('rf_nome')) {
            $dados = array(
                'rf_nome' => $this->input->post('rf_nome', true),
                'rf_email' => $this->input->post('rf_email', true)
            );

            $dados['rf_pw'] = $this->input->post('rf_pw', true) ? sha1($this->input->post('rf_pw', true)) : get_user()->rf_pw;


            $alterado = $this->db->where('rf_id', get_user()->rf_id)->update('responsaveis_fabrica', $dados);
        }


        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    function entrar() {
        if ($this->input->post('entrar1')) {
            $login = $this->db->where(array('rf_email' => $this->input->post('entrar1'), 'rf_pw' => sha1($this->input->post('entrar2'))))
                            ->join('responsaveis_fabrica', 'fabricas.fa_id = responsaveis_fabrica.rf_id')
                            ->join('cidades', 'fabricas.fa_cidade = cidades.ci_id')
                            ->get('fabricas')->result();


            if (count($login)) {
                set_user($login[0]);
            }
        }

        $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
    }

    function sair() {
        sair_user();
    }

    function editar_fabrica() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    function salvar_fabrica() {
        try {

            if (!$this->input->post('fa_id')) {
                throw new Exception('Erro: Não foi possível salvar fabrica.');
            }

            //Salvando cidades 
            $cidade = $this->db->where('ci_pais', $this->input->post('fa_pais'))->like('ci_nome', $this->input->post('fa_cidade'))->get('cidades')->row();
            $estado = $this->db->where('es_id', $this->input->post('fa_estado'))
                            ->get('estados')->row();

            if (count($cidade) == 0) {
                if (count($estado) > 0) {
                    $this->db->insert('cidades', array(
                        'ci_nome' => $this->input->post('fa_cidade'),
                        'ci_estado' => $estado->es_id,
                        'ci_uf' => $estado->es_uf,
                        'ci_pais' => $estado->es_pais
                    ));
                    $_POST['fa_cidade'] = $this->db->insert_id();
                }
            } else {
                $_POST['fa_cidade'] = $cidade->ci_id;
            }

            $this->db->where('fa_id', $this->input->post('fa_id'))
                    ->update('fabricas', funcoesdb::valida_fields('fabricas', $_POST));

            set_notificacao(1, "Atualizado com sucesso!");
            redirect(base_url('index.php/fabrica/editar_fabrica'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/fabrica/editar_fabrica'));
        }
    }

    /*
      | -------------------------------------------------------------------------
      | FIM DO CONTROLLER
      | -------------------------------------------------------------------------
     */
}

?>