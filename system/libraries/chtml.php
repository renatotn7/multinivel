<?php
/**
 * 06/02/2014
 * Desenvolvindo em momentos de lazer.
 * @author Helber Lucio de Paula
 * @license uso livre para ser usado pela objeto no marketing multe nivel
 *
 */

class CHtml{
	
	private $name;
        private static $time_exec;


        static public function dropdow($name='',$data=array(),$optionHtml=array()){

		$html="<select name='{$name}'
		".self::attr($optionHtml)."		
			 >";
		
			if(isset($optionHtml['empty'])){
			$html.= "<option value='0'>".$optionHtml['empty']."</option>";
			}
			
		 foreach ($data as $key=>$value){
		 $html.="<option 
		 		".(isset($optionHtml['selected']) && $optionHtml['selected']==$key?'selected="selected"':'')."
		 		value='".$key."'>".$value."</option>";	
		 }
		 $html.="</select>";
		 
		 if(isset($optionHtml['ajax']) && count($optionHtml['ajax'])>0){
		 	$html.=self::ajax($optionHtml['ajax']);
		 }

		 return $html;
	}
	
	static public function textInput($name='',$optionHtml=array()){
		$html="<input type='text' name='{$name}'"
				           .self::attr($optionHtml,$name)."/>";
		return $html;
	}
        
	static public function textHidden($name='',$optionHtml=array()){
		$html="<input type='hidden' name='{$name}'"
				           .self::attr($optionHtml,$name)."/>";
		return $html;
	}
	
	/**
	 * Cria um array para ser usado no DropDow
	 * @param unknown $data
	 * @param string $value_key
	 * @param string $name
	 * @return multitype:unknown
	 */
	static public function arrayDataOption($data=array(),$value_key='',$name=''){
	    $objeto=array();
	    
	    foreach ($data as $key=>$value){
	        $data_array=get_object_vars($value);
	    	$objeto[$data_array[$value_key]]=$data_array[$name]; 
	    }
	    return $objeto;
	}
        /**
         * Calcular o tempo de execução de pagina php
         * colocar no início
         */
        static public function berginTime(){
            // Iniciamos o "contador"
            list($usec, $sec) = explode(' ', microtime());
            self::$time_exec =( (float) $sec + (float) $usec);
        }
        /**
         * Calcular o tempo de execução de pagina php
         * colocar no fim
         */
        static public function endTime($type=''){
            list($usec, $sec) = explode(' ', microtime());
            $script_end = (float) $sec + (float) $usec;
            $elapsed_time = round($script_end -  self::$time_exec, 5);
            $hours = (int)($elapsed_time/60/60);
            $minutes = (int)($elapsed_time/60)-$hours*60;
            $seconds = (int)$elapsed_time-$hours*60*60-$minutes*60;
            
            if(empty($type)){
            echo 'Elapsed time: ',$seconds, ' segundos. Memory usage: ', 
                 round(((memory_get_peak_usage(true) / 1024) / 1024), 2),'Mb';
            }
            
            if($type=='session'){
                 set_notificacao(1,'Elapsed time: '.$seconds.' segundos. Memory usage: '. 
                 round(((memory_get_peak_usage(true) / 1024) / 1024), 2).'Mb');
            }
            
            
        }
        
        /** 
         * Logo de excecução 
         */
        static public function logexec($nome='',$conteudo='',$pasta='')
        {
             $caminho =realpath(dirname(dirname(dirname(dirname(__FILE__))))).'/log_bonus/';
             
             //removendo html e colocando no formanto de txt.
//             $conteudo = str_replace('</p>',"\n",str_replace('<br>',"\n",str_replace("<br/>", "\n",$conteudo)));
//             $conteudo = strip_tags($conteudo);
             
             if(!file_exists($caminho)){
                 mkdir($caminho);   
              }
              
              if(!empty($pasta)){
                  $caminho.=$pasta.'/';
                  
                  if(!file_exists($caminho)){
                       mkdir($caminho); 
                  }
              }
             
            $arquivo = $caminho.$nome.'log.txt'; //Nome do arquivo que será gravado ou criado
            $texto = (empty($conteudo)?$nome:$conteudo); 
            $recurso = fopen($arquivo,"a+"); //Comando para criar/abrir o arquivo para gravação
            fwrite($recurso,$texto); //Funçao para escrever no arquivo
            fclose($recurso); //Função para fechar o arquivo
        }


        /**
	 * Cria atributos html na dom
	 * @param unknown $optionHtml
	 * @return string
	 */
	private function attr($optionHtml=array(),$name=''){
		$html="";
         if(isset($optionHtml['selected'])){
         	unset($optionHtml['selected']);
         }
//         if(isset($optionHtml['value'])){
//         	unset($optionHtml['value']);
//         }
         if(isset($optionHtml['disabled']) && empty($optionHtml['disabled'])){
         	unset($optionHtml['disabled']);
         }
         
         if(isset($optionHtml['empty'])){
         	unset($optionHtml['empty']);
         }
         
         if(isset($optionHtml['ajax'])){
         	unset($optionHtml['ajax']);
         }
         if(isset($optionHtml['value']) && empty($optionHtml['value']) || !isset($optionHtml['value'])){
         	$optionHtml['value']=self::getValue($name);
         }

		if(count($optionHtml)>0){
		foreach ($optionHtml as $key =>$value){
		  $html.=$key."='".$value."' ";	
		 }	
		}
		return $html;
	}
	
	private function getValue($name='')
	{
		return isset( $_REQUEST[$name]) && !empty( $_REQUEST[$name])? $_REQUEST[$name]:'';
	}
	
	private function ajax($option=array()){
	$ajax="<script>\n/*FUNCTION AJAX*/$(function(){";

		  
		$ajax.=" });/n/*FUNCTION AJAX*/</script> "; 
		 return $ajax;
	}
       
}