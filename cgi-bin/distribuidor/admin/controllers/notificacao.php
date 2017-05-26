<?php
class notificacao extends CI_Controller{
	
	public function cartao_intercash()
	{
		$this->load->view('notificacao/cartao_intercash_view.php');
	}
        
	public function verificacao_conta()
	{
		$this->load->view('notificacao/verificacao_conta_view.php');
	}
        public function alteracao_senhas_iguais()
	{
		$this->load->view('notificacao/alteracao_senhas_iguais_view.php');
	}
        public function qualificar_compra_entregue()
	{
		$this->load->view('notificacao/qualificar_compra_entregue_view.php');
	}
	
	public function enviar()
	{

		$html="";
		$html.="Nome distribuidor: ".$_POST['di_nome'];
		$html.="<br/>Usuário: ".$_POST['di_usuario'];
		$html.="<br/>E-mail: ".$_POST['di_email'];
		$html.="<br/>Telefone: ".$_POST['di_fone1'];
		$html.="<br/>Telefone: ".$_POST['di_fone1'];
		$html.="<br/>Explicação: ".$_POST['di_explicacao'];
		set_notificacao(array(array('tipo'=>1,'mensagem'=>'<h4>Mensagem enviada com sucesso.</h4>')));
		send_email_verificacao('Verificação de Contas',$html);
		redirect();
	}

}