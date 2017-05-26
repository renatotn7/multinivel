<?php

class Correios{
	
 private $ci;
 private $cep_origem;
 private $cep_destino;
 private $largura;
 private $altura;
 private $comprimento;
 private $peso;
 private $resultado;
 
 public function __construct(){
	 $this->ci =& get_instance(); 
	  $this->peso = 1;
	  $this->comprimento = 16;
	  $this->largura = 16;
	  $this->altura = 16;
	  $this->resultado = array();
	 }
	 
	 	
 private function set_cep_origem_default(){
	  $fabrica = $this->ci->db->get('fabricas',1)->result();
	  $this->cep_origem = $fabrica[0]->fa_cep;
	 }	
	 
 public function calcular_frete($cep_destino,$peso=1,$cep_origem=0){
	  $this->resultado = array();
	  $this->cep_destino = $cep_destino;
	  $this->peso = $peso;
	  
	  if($cep_origem==0)
	   {
	   $this->set_cep_origem_default();
	   }else{
	   $this->cep_origem = $cep_origem;
	   }
	   
	   $this->tratar_cep();
	   
	   
	  
	   
	   //Calcula valor PAC
	   $pac = @simplexml_load_file($this->get_url(41106));
	   
	   
	   if($pac && $pac->cServico->Erro==0){
		   $this->resultado['pac'] = array(
		   'valor'=>$pac->cServico->Valor[0],
		   'prazo'=>$pac->cServico->PrazoEntrega[0],
		   );
	   }
	   
	   
	   $sedex =  @simplexml_load_file($this->get_url(40010));
	    if($pac && $sedex->cServico->Erro==0){
		   $this->resultado['sedex'] = array(
		   'valor'=>$sedex->cServico->Valor,
		   'prazo'=>$sedex->cServico->PrazoEntrega,
		   );
	   }
	   	
		return $this->resultado;  
		
	  }
	
	
	
public function get_url($cod_servico){
	return "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$this->cep_origem."&sCepDestino=".$this->cep_destino."&nVlPeso=".$this->peso."&nCdFormato=1&nVlComprimento=".$this->comprimento."&nVlAltura=".$this->altura."&nVlLargura=".$this->largura."&sCdMaoPropria=n&nVlValorDeclarado=0&sCdAvisoRecebimento=n&nCdServico=".$cod_servico."&nVlDiametro=0&StrRetorno=xml";
	}	  

public function tratar_cep(){
	$this->cep_destino = trim(str_ireplace('-','',$this->cep_destino));
	$this->cep_origem = trim(str_ireplace('-','',$this->cep_origem));
	}	  
 	 
}