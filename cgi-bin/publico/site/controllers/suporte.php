<?php
class Suporte extends CI_Controller
{
	
	public function index()
	{
		redirect(base_url('index.php/suporte/formulario'));
	}
	
	public function gerar_cap()
	{
		
		$this->load->helper('captcha');
		$arrayLetras = array('a','c','h','b','1','e','7','p','m','f','x','8','4');
		
		$strWrod = '';
		for ($i = 0; $i < 8; $i++) {
			$numA = rand(0, 12);
			$strWrod .= $arrayLetras[$numA];
		}
		
		
		$_SESSION['captcha_word'] = $strWrod;
		
		$vals = array(
			'word' => $strWrod,
			'img_path' => 'public/captcha/',
			'img_url' => base_url('public/captcha/') . '/',
			'img_width' => '140',
			'img_height' => '32',
			'expiration' => 7200
		);
		
		$cap                     = create_captcha($vals);
		$_SESSION['captcha_img'] = $cap['image'];
		
		redirect(base_url('index.php/entrar/login/?msg=' . (isset($_GET['msg']) ? $_GET['msg'] : '')));
		
	}
	
	public function formulario_mensagem()
	{
		$this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
	}
	
	public function formulario()
	{
		$this->lang->load('publico/suporte/suporte');
		
		$data = array();
		if (!empty($_POST['submit-suporte'])) {
			$msg = "";
			if (empty($_POST['nome'])) {
				$msg .= $this->lang->line('msg_nome')."<br>";
			}
			if (empty($_POST['email'])) {
				$msg .= $this->lang->line('msg_email')."<br>";
			}
			if (empty($_POST['telefone'])) {
				$msg .= $this->lang->line('msg_telefone')."<br>";
			}
			if (empty($_POST['assunto'])) {
				$msg .= $this->lang->line('msg_assunto')."<br>";
			}
			if (empty($_POST['mensagem'])) {
				$msg .= $this->lang->line('msg_mensagem')."<br>";
			}
			if (empty($msg)) {
				
				$this->load->helper('my_email');
				
				$body = layout_email("

				<p><strong>".$this->lang->line('email_titulo')."</strong><br>
				  <br>
				  <strong>".$this->lang->line('email_nome')."</strong> " . $_POST['nome'] . "<br>
				  <br>
				  <strong>".$this->lang->line('email_email')."</strong> " . $_POST['email'] . "<br>
				  <br>
				  <strong>".$this->lang->line('email_telefone')."</strong> " . $_POST['telefone'] . "<br>
				  <br>
				  <strong>".$this->lang->line('email_assunto')."</strong> " . $_POST['assunto'] . "<br>
				  <br>
				  <strong>".$this->lang->line('email_mensagem')."</strong> " . $_POST['mensagem'] . "<br>
				  <br>
				");
				
				if (enviar_suporte(get_loja()->fa_email, utf8_decode($this->lang->line('email_header'). get_loja()->fa_nome), $body)) {
					redirect(base_url() . "index.php/suporte/formulario_mensagem/" . $_POST['nome']);
				} else {
					$data['error'] = $this->lang->line('email_error');
				}
			} else {
				$data['error'] = $msg;
			}
		}
		
		
		$this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view", $data);
	}
	
}