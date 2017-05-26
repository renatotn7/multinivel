<?php 

class Paginacao{
	 private $por_pagina;
	 private $total_row;
	 private $inicio;
	 private $query_string;
	 private $name_var = 'page_show';
	 
	 
	 public function __construct(){
		  $this->config_inicio();
		  $this->por_pagina = 10;
		  $this->query_string('&'.$_SERVER['QUERY_STRING']);
		 }


	 public function name_var($name_var){
		  $this->name_var = $name_var;
		  return $this;
		 }
	 
	 public function query_string($query_string){
		       $url = explode('&',$query_string);
			   $newstr = '';
			   foreach($url as $u){
				   if($u!=''&&!strpos('pre-'.$u,$this->name_var)){
				    $newstr .= "&".$u;
				   }
				   }
		       $this->query_string = $newstr;
			 
		 }
		 
	 public function inicio($inicio){
		  $this->inicio = $inicio;
		  return $this;
		 }
	 
	 private function config_inicio(){
		  if(isset($_GET[$this->name_var])&&$_GET[$this->name_var]!=1&&$_GET[$this->name_var]!=''){
			   $this->inicio = ($_GET[$this->name_var]-1)*$this->por_pagina;
			  }else{
				  $this->inicio = 0;
				  }
		 }
	 
	 public function por_pagina($por_pagina){
		  $this->por_pagina = $por_pagina;
		  return $this;
		 }
		 
	 private function total_row($total){
		  $this->total_row = $total;
		 }
	
	private function limit_rows(){
		if(($this->inicio+$this->por_pagina)>=$this->total_row){
			return $this->total_row;
		}else{
			return ($this->inicio+$this->por_pagina);
			}
		}
	
	
	public function rows($resultSet){
		 $this->config_inicio();
		 $this->total_row(count($resultSet));
		 $rows_atual = array();
		 $stop_for = $this->limit_rows();
		 for($i=$this->inicio;$i < $stop_for;$i++){
			  $rows_atual[] = $resultSet[$i]; 
			 }
		 return $rows_atual; 
		}	 
	 
	 
	 
	 public function pagina_atual(){
		  if(isset($_GET[$this->name_var])&&$_GET[$this->name_var]!=''&&$_GET[$this->name_var]!=1)
		       {
			   return $_GET[$this->name_var];
			  }else{
				  return 1;
				  }
		 }
	 
	 
	 public function links(){
		  if($this->total_row==0 || $this->por_pagina==0){
			  return '';
			  }
		  
		  $num_pages = ceil($this->total_row / $this->por_pagina);
		  
		  if ($num_pages == 1)
			{
				return '';
			}
		 	
		  $htmlLiks = "";	
			
		  $htmlLiks .= "\n<div class='page-paginacao'>";
		  $htmlLiks .=  "\n<table width='100%' border='0'>";
		  $htmlLiks .=  "\n<tr>";
		  $htmlLiks .=  "\n<td>";
		  
		  if($num_pages>2){
		  $htmlLiks .=  "<a href='".current_url()."?{$this->name_var}";
		  $htmlLiks .=  "=1{$this->query_string}'>primeira</a> ";
		  }
		  $page_count = 1;
		  for($i=0;$i<$this->total_row;$i=$i+$this->por_pagina){
			 
			 if($page_count == $this->pagina_atual()){ 
			 
			 $htmlLiks .= " <span class='page-atual' href='".current_url()."?{$this->name_var}";
			 $htmlLiks .= "={$page_count}{$this->query_string}'> ";
			 $htmlLiks .=  $page_count;
			 $htmlLiks .=  "</span>\n";
			 
			 }else{
				 $htmlLiks .= " <a href='".current_url()."?{$this->name_var}";
				 $htmlLiks .= "={$page_count}{$this->query_string}'> ";
				 $htmlLiks .=  $page_count;
				 $htmlLiks .=  "</a>\n";
				 }
			 
			 $page_count++;
			}
		  if($num_pages>2){	  
		  	$htmlLiks .=  "\n<a href='".current_url()."?{$this->name_var}";
		  	$htmlLiks .=  "=".$num_pages."{$this->query_string}'>última</a>";
		  }
		  $htmlLiks .=  "\n</td>";
		  
		  $htmlLiks .=  "\n<td>";
		  $htmlLiks .=  "\n<div class='page-info'>Registros ".($this->inicio+1);
		  $htmlLiks .=  "\n - ".$this->limit_rows()." de ".$this->total_row;
		  $htmlLiks .=  "\n em ".$num_pages." páginas</div>";
		  $htmlLiks .=  "\n</td>";
		  
		  $htmlLiks .=  "</tr>";	  
		  $htmlLiks .=  "</table>";	  
		  $htmlLiks .=  "</div>";	
		  	
		  	return $htmlLiks;  
		 }
	 	 		 
	} 