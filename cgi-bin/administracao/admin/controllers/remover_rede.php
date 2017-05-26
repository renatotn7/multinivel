<?php

/**
 * Description of remover_rede
 *
 * @author Ronyldo12
 */
class remover_rede extends CI_Controller {

    public function __construct() {
        parent::__construct();
        autenticar();
        set_time_limit(0);
    }

    public function index() {
        $data['pagina'] = 'remover_rede/remover_rede';
        $this->load->view('home/index_view', $data);
    }

    public function informar_usuario() {

        $data['pagina'] = 'remover_rede/informar_usuario';
        $this->load->view('home/index_view', $data);
    }

    public function confirmar_usuario() {

        if (!$this->uri->segment(3)) {
            set_notificacao(2, 'Informe o tipo de operação.');
            redirect(base_url('index.php/remover_rede/index'));
            exit;
        }

        $tipoOperacao = $this->uri->segment(3) == 1 ? 'Excluir usuário' : 'Excluir rede do usuário';

        $distribuidor = $this->db
                ->where('di_usuario', $_POST['di_usuario'])
                ->get('distribuidores')->row();
        if (count($distribuidor) == 0) {
            set_notificacao(2, 'Usuário não encontrado');
            redirect(base_url('index.php/remover_rede/informar_usuario/' . $this->uri->segment(3)));
            exit;
        }

        $distribuidoresSeraoExcluidos = $this->getDistribuidoresSeraoExcluidos($distribuidor->di_id);

      

        $data['id'] = uniqid();
        $_SESSION['id_exclusao'] = $data['id'];
        $data['distribuidoresSeraoExcluidos'] = $distribuidoresSeraoExcluidos;
        $data['diretosNaRede'] = $this->getDiretosNaRede($distribuidor->di_id);
        $data['diretos'] = $this->getDiretos($distribuidor->di_id);
        $data['estaNaRede'] = $this->estaNaRede($distribuidor->di_id);
        $data['comprasPagas'] = $this->getComprasPagas($distribuidor->di_id);
        $data['distribuidor'] = $distribuidor;
        $data['tipoOperacao'] = $tipoOperacao;
        $data['tipoOperacaoInt'] = $this->uri->segment(3);
        $data['pagina'] = 'remover_rede/confirmar_usuario';
        $this->load->view('home/index_view', $data);
    }

    public function remover_usuario() {
        //Valida se o administrador passou pela tela de cofnirmar usuário
        if (!isset($_SESSION['id_exclusao']) && !$this->input->get('id')) {
            set_notificacao(2, 'Acesso negado. Tente novamente.');
            redirect(base_url('index.php/remover_rede/'));
            exit;
        }

        //Valida se o administrador passou pela tela de cofnirmar usuário
        if ($_SESSION['id_exclusao'] != $this->input->get('id')) {
            set_notificacao(2, 'Acesso negado. Tente novamente.');
            redirect(base_url('index.php/remover_rede/'));
            exit;
        }
        $distribuidor = $this->db->where('di_id', $this->uri->segment(3))->get('distribuidores')->row();
        if (count($distribuidor) == 0) {
            set_notificacao(2, 'Usuário não encontrado');
            redirect(base_url('index.php/remover_rede/'));
            exit;
        }
        
        $this->excluirUsuario($distribuidor->di_id);
        
        set_notificacao(1, '<h4>Rede Excluida com sucesso</h4>');
        redirect(base_url('index.php/remover_rede/'));
        exit;
    }

    public function remover_rede_usuario() {

        //Valida se o administrador passou pela tela de cofnirmar usuário
        if (!isset($_SESSION['id_exclusao']) && !$this->input->get('id')) {
            set_notificacao(2, 'Acesso negado. Tente novamente.');
            redirect(base_url('index.php/remover_rede/'));
            exit;
        }

        //Valida se o administrador passou pela tela de cofnirmar usuário
        if ($_SESSION['id_exclusao'] != $this->input->get('id')) {
            set_notificacao(2, 'Acesso negado. Tente novamente.');
            redirect(base_url('index.php/remover_rede/'));
            exit;
        }
        
        $distribuidor = $this->db->where('di_id', $this->uri->segment(3))->get('distribuidores')->row();
        if (count($distribuidor) == 0) {
            set_notificacao(2, 'Usuário não encontrado');
            redirect(base_url('index.php/remover_rede/'));
            exit;
        }

        $distribuidoresExcluidos = $this->getDistribuidoresSeraoExcluidos($distribuidor->di_id);

        $listaExclusao = array();

        foreach ($distribuidoresExcluidos as $distribuidorExcluido) {
            $listaExclusao[$distribuidorExcluido->di_usuario] = $distribuidorExcluido->di_usuario;
        }

        while (count($listaExclusao) > 0) {

            $escolhido = $this->getProximoLista($listaExclusao);
            if (count($escolhido) == 0) {
                break;
            }

            $this->excluirUsuario($escolhido->di_id);

            echo "<p>Excluindo: {$escolhido->di_usuario} Id: {$escolhido->di_id}</p>";

            unset($listaExclusao[$escolhido->di_usuario]);
            
           
        }

        set_notificacao(1, '<h4>Rede Excluida com sucesso</h4>');
        redirect(base_url('index.php/remover_rede/'));
        exit;
    }

    private function excluirUsuario($idDistribuidor) {

        $distribuidor = $this->db->where('di_id', $idDistribuidor)->get('distribuidores')->row();
        if (count($distribuidor) == 0) {
            set_notificacao(2, 'Usuário não encontrado');
            redirect(base_url('index.php/remover_rede'));
            exit;
        }


        $dadosExclusao = array();
        //$dadosExclusao['compras'] = $this->db->where('co_id_distribuidor', $idDistribuidor)->get('compras')->result();
        //$dadosExclusao['redeLigacao'] = $this->db->where('li_id_distribuidor', $idDistribuidor)->get('distribuidor_ligacao')->result();
        //$dadosExclusao['contaBonus'] = $this->db->where('cb_distribuidor', $idDistribuidor)->get('conta_bonus')->result();
        //$dadosExclusao['registroPlano'] = $this->db->where('ps_distribuidor', $idDistribuidor)->get('registro_planos_distribuidor')->result();

        $novoUsuario = 'remove_' . uniqid();

        $dadosExclusao['de'] = array('di_usuario' => $distribuidor->di_usuario, 'cpf' => $distribuidor->di_cpf);
        $dadosExclusao['para'] = array('di_usuario' => $novoUsuario, 'cpf' => '0');

        $dadosExclusaoJson = json_encode($dadosExclusao);
        $this->db->insert('distribuidores_excluido', array(
            'di_id' => $distribuidor->di_id,
            'usuario'=>$distribuidor->di_usuario,
            'di_dados' => $dadosExclusaoJson,
            'di_data' => date('Y-m-d H:i:S')
        ));

        $this->db->where('li_id_distribuidor', $idDistribuidor)->delete('distribuidor_ligacao');
        $this->db->where('cb_distribuidor', $idDistribuidor)->delete('conta_bonus');
        $this->db->where('ps_distribuidor', $idDistribuidor)->delete('registro_planos_distribuidor');

        //Limpando a perna do no alocado
        $no = $this->getNoAlocado($idDistribuidor);
        if (count($no) > 0 && $no->di_id != 0) {
            $perna = '';
            if ($no->di_esquerda == $idDistribuidor) {
                $perna = 'di_esquerda';
            } else if ($no->di_direita == $idDistribuidor) {
                $perna = 'di_direita';
            }

            if ($perna != '') {
                $this->db->where('di_id', $no->di_id)->update('distribuidores', array(
                    $perna => '0'
                ));
            }
        }
        //FIm - Limpando a perna do no alocado

        $this->db->where('di_id', $distribuidor->di_id)->update('distribuidores', array(
            'di_excluido' => 1,
            'di_usuario' => $novoUsuario,
            'di_cpf' => '0',
            'di_rg'=>'0'
        ));

        $this->db->where('di_ni_patrocinador', $distribuidor->di_id)->update('distribuidores', array(
            'di_ni_patrocinador' => $distribuidor->di_ni_patrocinador,
            'di_usuario_patrocinador' => $distribuidor->di_usuario_patrocinador
        ));

        $this->db->where('co_id_distribuidor', $idDistribuidor)->update('compras', array(
            'co_pago' => 0,
            'co_situacao' => '-1'
        ));

        $this->db->insert('auditoria_geral', array(
            'ag_id_responsavel' => get_user()->rf_id,
            'ag_tabela' => 'alltables',
            'ag_acao_realizada' => 'excluir_distribuidor',
            'ag_descricao' => "O administrador " . get_user()->rf_nome . "(" . get_user()->rf_id . "ind) excluiu o distribuidor " . $distribuidor->di_usuario,
            'ag_data' => date('Y-m-d H:i:s')
        ));
    }

    private function getNoAlocado($idDistribuidor) {
        return $this->db->query('SELECT * FROM distribuidores 
                  WHERE di_direita ='.$idDistribuidor.' OR di_esquerda = '.$idDistribuidor)
                ->row();
    }

    private function estaNaRede($idDistribuidor) {
        $ligacao = $this->db->where('li_id_distribuidor', $idDistribuidor)->get('distribuidor_ligacao')->row();
        return count($ligacao) > 0;
    }

    private function getComprasPagas($idDistribuidor) {
        return $this->db->where('co_id_distribuidor', $idDistribuidor)->where('co_pago', 1)->get('compras')->result();
    }

    private function getDiretos($idDistribuidor) {
        return $this->db->where('di_ni_patrocinador', $idDistribuidor)->get('distribuidores')->result();
    }

    private function getDiretosNaRede($idDistribuidor) {
        return $this->db->where('di_ni_patrocinador', $idDistribuidor)
                        ->join('distribuidor_ligacao', 'di_id=li_id_distribuidor')
                        ->group_by('di_id')
                        ->get('distribuidores')->result();
    }

    private function getDistribuidoresSeraoExcluidos($idDistribuidor) {
        return $this->db->where('li_no', $idDistribuidor)
                        ->where('li_id_distribuidor !=',$idDistribuidor)
                        ->join('distribuidor_ligacao', 'di_id=li_id_distribuidor')
                        ->group_by('di_id')
                        ->get('distribuidores')->result();
    }

    private function getProximoLista($lista) {
        if (count($lista) == 0) {
            return array();
        }

        return $this->db
                        ->where('di_esquerda', 0)
                        ->where('di_direita', 0)
                        ->where_in('di_usuario', $lista)
                        ->get('distribuidores')->row();
    }

}
