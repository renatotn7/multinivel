<?php

class Newsletter extends CI_Controller {

    public function index() {

        if ($this->input->post('lista_usuario')) {
            $array_lista = array();
            $array_lista_ = explode(';', $this->input->post('lista_usuario'));
            
            foreach ($array_lista_ as $key => $lista_value) {
                
                if(!validaEmail($lista_value)){
                    continue;
                }
                
                $array_lista[] = funcoesdb::arrayToObject(array(
                            'di_email' => $lista_value
                ));
            }
            
            if (count($array_lista) > 0) {
                $_SESSION['dis_newsletter'] = $array_lista;
            } else {
                $_SESSION['dis_newsletter'] = array();
            }
            
            redirect(base_url('index.php/Newsletter/enviando_lista'));  
            return false;
        }
        
        if ($this->input->get('processar')) {
            if (isset($_POST['usuario']) && $_POST['usuario'] != '') {
                $this->db->where('di_usuario', $_POST['usuario']);
            }

            if (isset($_POST['estado']) && $_POST['estado'] != '') {
                $this->db->where('di_uf', $_POST['estado']);
            }

            if (isset($_POST['cidade']) && $_POST['cidade'] != '') {
                $this->db->where('di_cidade', $_POST['cidade']);
            }

            if (isset($_POST['graduacao']) && $_POST['graduacao'] != '') {
                $this->db->where('di_qualificacao', $_POST['graduacao']);
            }

            if (isset($_POST['situacao_cadastro']) && !empty($_POST['situacao_cadastro'])) {
                if ($_POST['situacao_cadastro'] == 'pendente') {
                    $this->db->where('di_id NOT IN', '(SELECT li_id_distribuidor FROM distribuidor_ligacao)', false)
                            ->where('di_id NOT IN', '(SELECT co_id_distribuidor FROM compras WHERE co_pago = 1)', false);
                }

                if ($_POST['situacao_cadastro'] == 'ativos') {
                    $this->db->where('di_id IN', '(SELECT li_id_distribuidor FROM distribuidor_ligacao)', false)
                            ->where('di_id IN', '(SELECT co_id_distribuidor FROM compras WHERE co_pago = 1)', false);
                }

                if ($_POST['situacao_cadastro'] == 'finaciado') {
                    $this->db->where('co_parcelado', 1);
                }
            }

            if (isset($_POST['planos']) && $_POST['planos'] != '') {
                $this->db->where('pa_id', $_POST['planos']);
            }

            $_SESSION['dis_newsletter'] = $this->db
                            ->select(array('di_nome', 'di_email'))
                            ->where('di_excluido !=1')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->join('planos', 'co_id_plano=pa_id')
                            ->group_by('di_id')
                            ->get('distribuidores')->result();

            $_SESSION['assunto'] = isset($_POST['assunto']) ? $_POST['assunto'] : '';
            $_SESSION['mensagem'] = isset($_POST['msg']) ? $_POST['msg'] : '';

            redirect(base_url('index.php/newsletter/enviar'));
        }

        $data['pagina'] = strtolower(__CLASS__ . "/newsletter");
        $this->load->view('home/index_view', $data);
    }

    public function enviar() {
        set_time_limit(0);
        $data['pagina'] = strtolower(__CLASS__ . "/envio");
        $this->load->view('home/index_view', $data);
    }

    function enviando_lista() {
        set_time_limit(0);
        foreach ($_SESSION['dis_newsletter'] as $d) {
            $d->di_email = strtolower($d->di_email);
            $headers = "MIME-Version: 1.1\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\n";
            $headers .= "From:  nossa empresa <" .ConfigSingleton::getValue('email_ativacao_estados_unidos') . ">\r\n"; // remetente
            $envio = @mail($d->di_email, $_SESSION['assunto'], $_SESSION['mensagem'], $headers);
            if ($envio) {
                echo "<p style='color:#090'>Enviado: {$d->di_email}</p>";
            } else {
                echo "<p style='color:#f00'>Erro ao enviar:({$d->di_email})</p>";
            }

            sleep(2);
        }
        set_notificacao(1,'Processo finalizado com sucesso.');
        redirect(base_url('index.php/Newsletter'));
    }

    function enviando() {
        set_time_limit(0);
        echo '<script type="text/javascript" src="' . base_url('public/script/validar/js/jquery-1.7.2.min.js') . '"></script>';
        $total_email_existente = count($_SESSION['dis_newsletter']);
        $total_email_enviado = 1;
        $porcentual_concluido = 0;
        $total_enviado = 0;
        $total_nao_enviado = 0;

        foreach ($_SESSION['dis_newsletter'] as $d) {
            $porcentual_concluido = round(($total_email_enviado / $total_email_existente) * 100);

            $d->di_email = strtolower($d->di_email);
            $headers = "MIME-Version: 1.1\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\n";
            $headers .= "From:  nossa empresa <" . conf()->email_ativacao_estados_unidos . ">\r\n"; // remetente
            $envio = @mail($d->di_email, $_SESSION['assunto'], $_SESSION['mensagem'], $headers);
            if ($envio) {
                echo "<p style='color:#090'>Enviado: {$d->di_nome} {$d->di_email}</p>";
                $total_enviado++;
            } else {
                echo "<p style='color:#f00'>Erro ao enviar: {$d->di_nome}({$d->di_email})</p>";
                $total_nao_enviado++;
            }
            sleep(2);

            $total_email_enviado++;
            echo "<script>"
            . "$('.bar',window.parent.document).css('width','{$porcentual_concluido}%').html('{$porcentual_concluido}%');"
            . "$('.enviado',window.parent.document).html('{$total_enviado}');"
            . "$('.pendente',window.parent.document).html('{$total_nao_enviado}');"
            . "</script>";
        }
        echo "<p>Finalizado com sucesso. " . date('d/m/Y H:i:s') . "</p>";
        $_SESSION['dis_newsletter'] = array();
    }

}

?>