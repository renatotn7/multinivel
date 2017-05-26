<?php
class Url {
	private $url;
	private $ssl;
	private $rewrite = array();
	
        // adicionados para definir os links da url amigavel
        private $urlCliente;
        private $urlFriendly;
        private $incustomer='';
        
        private $urlamiga = true;
        
        
	public function __construct($url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
	}
		
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}
		
	public function link($route, $args = '', $connection = 'NONSSL') {
		if ($connection ==  'NONSSL') {
			$url = $this->url;	
		} else {
			$url = $this->ssl;	
		}
		
		$url .= 'index.php?route=' . $route;
			
		if ($args) {
			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&')); 
		}
		
		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
				
		return $url;
	}
	
	
	
	
##Prepara string
  function inserir_slug($db,$str,$query){
	 
	 if($query==''){
		 echo "Ocorreu um erro ao inserir a url amigavel, nehuma url informada";
		 exit;
		 }
	 
	 if($str==''){
		 $str = $query;
		 }
	  
	 $acentos = array(
				'?','*','&','$','@','\'','/','.',
				'À','Á','Ã','Â', 'à','á','ã','â',
				'Ê', 'É',
				'Í', 'í', 
				'Ó','Õ','Ô', 'ó', 'õ', 'ô',
				'Ú','Ü',
				'Ç', 'ç',
				'é','ê', 
				'ú','ü',
				'(',')',
				'[',']',
				'$','%','&','*','@','!',
				);
			$remove_acentos = array(
				'','','','','','','-','',
				'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
				'e', 'e',
				'i', 'i',
				'o', 'o','o', 'o', 'o','o',
				'u', 'u',
				'c', 'c',
				'e', 'e',
				'u', 'u',
				'', '',
				'[', ']',
				'','','','','','',
				);
				
			$slung =  str_replace($acentos, $remove_acentos, urldecode($str));
			$slung = strtolower(str_replace(",","",$slung));
			$slung =  strtolower(str_replace(" ","-",$slung));
			
			$db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '{$query}'");
			$ja_existe =  $db->query("SELECT * FROM ".DB_PREFIX."url_alias WHERE keyword = '$slung'");
				
		 if($ja_existe->num_rows!=0){
			 $slung = $slung."_".time();
			 }
				
		 $db->query("INSERT INTO ".DB_PREFIX."url_alias(query,keyword) VALUES('".trim($query)."','$slung')");	
			
	}
	
	/**
         * Para enviar o caminho direto da url amigavel
         * @param String $route
         * @param String $connection
         * @return String
         */
	public function linkAmigavel($route, $connection = 'NONSSL') {
		if ($connection ==  'NONSSL') {
			$url = $this->url;	
		} else {
			$url = $this->ssl;	
		}
		
		$url .= $route;
		
		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
				
		return $url;
	}
	/**
         * Para url amigavel
         * @param String $cliente
         * @return String
         */
        private function carregaUrlCliente($cliente = ''){
         return array(
            'common/home' => $cliente.'home',
            'checkout/cart' => $cliente.'carrinho',
            'account/register' => $cliente.'cadastre-se',
            'account/wishlist' => $cliente.'lista-de-desejo',
            'checkout/checkout' => $cliente.'checkout',
            'account/logout' => $cliente.'sair',
            'account/login' => $cliente.'login',
            'product/product' => $cliente.'produto',
            'product/special' => $cliente.'especial',
            'affiliate/account' => $cliente.'afiliado',
            'checkout/voucher' => $cliente.'vale-presente',
            'product/manufacturer' => $cliente.'fabricante',
            'account/newsletter' => $cliente.'newsletter',
            'account/order' => $cliente.'meus-pedidos',
            'account/account' => $cliente.'minha-conta',
            'information/contact' => $cliente.'contato',
            'information/information'=> $cliente.'institucional',
            'information/sitemap' => $cliente.'mapa-do-site',
            'account/forgotten' => $cliente.'lembrar-senha',
            'account/download' => $cliente.'meus-downloads',
            'account/return' => $cliente.'minhas-devolucoes',
            'account/transaction' => $cliente.'minhas-indicacoes',
            'account/password' => $cliente.'alterar-senha',
            'account/edit' => $cliente.'alterar-informacoes',
            'account/address' => $cliente.'alterar-enderecos',
            'account/reward' => $cliente.'pontos-de-fidelidade',
        );
    }
    //para adicionar o customer
    public function setNomeParaUrl($nomeUrl=''){
        $this->incustomer = $nomeUrl;
    }
    
    /**
     * Ajuda a montar a url amigavel
     * @param String $_route
     * @return boolean
     */
    public function getKeyFriendly($_route) {
        if (count($this->urlFriendly) > 0) {
            $key = array_search($_route, $this->urlFriendly);
            if ($key && in_array($_route, $this->urlFriendly)) {
                return $key;
            }
        }
        return false;
    }

    /**
     * Ajuda a montar url amigavel
     * @param String $route
     * @return boolean
     */
    public function getValueFriendly($route) {
        if (count($this->urlFriendly) > 0) {
            if (in_array($route, array_keys($this->urlFriendly))) {
                return $this->urlFriendly[$route];
            }
        }
        return false;
    }
}
?>