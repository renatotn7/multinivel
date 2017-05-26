<?php


/**
 * Description of Distribuidor
 *
 * @author Ronildo
 */
class DistribuidorModel {
    private $id;
    private $usuario;
    private $patrocinador;
    private $esquerda;
    private $direita;
    private $ladoPreferencial;
    private $nome;
    private $sexo;
    private $tipoPessoa;
    private $rg;
    private $cpf;
    private $estadoCivil;
    private $dataNascimento;
    private $piss;
    private $dependentes;
    private $ativo;
    private $binario;
    private $email;
	
    
    public function __construct() {
        $this->id =0;
		$this->ativo = 0;
		$this->binario = 0;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getPatrocinador() {
        return $this->patrocinador;
    }

    public function setPatrocinador($patrocinador) {
        $this->patrocinador = $patrocinador;
    }

    public function getEsquerda() {
        return $this->esquerda;
    }

    public function setEsquerda($esquerda) {
        $this->esquerda = $esquerda;
    }

    public function getDireita() {
        return $this->direita;
    }

    public function setDireita($direita) {
        $this->direita = $direita;
    }

    public function getLadoPreferencial() {
        return $this->ladoPreferencial;
    }

    public function setLadoPreferencial($ladoPreferencial) {
        $this->ladoPreferencial = $ladoPreferencial;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getTipoPessoa() {
        return $this->tipoPessoa;
    }

    public function setTipoPessoa($tipoPessoa) {
        $this->tipoPessoa = $tipoPessoa;
    }

    public function getRg() {
        return $this->rg;
    }

    public function setRg($rg) {
        $this->rg = $rg;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function getEstadoCivil() {
        return $this->estadoCivil;
    }

    public function setEstadoCivil($estadoCivil) {
        $this->estadoCivil = $estadoCivil;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    public function getPiss() {
        return $this->piss;
    }

    public function setPiss($piss) {
        $this->piss = $piss;
    }

    public function getDependentes() {
        return $this->dependentes;
    }

    public function setDependentes($dependentes) {
        $this->dependentes = $dependentes;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    public function getBinario() {
        return $this->binario;
    }

    public function setBinario($binario) {
        $this->binario = $binario;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

	


    
}

?>
