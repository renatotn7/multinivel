<?php

/*
-----------------VEJA UM MODELO COMPLETO-----------------

	public function <nome_do_modelo>(){
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = '<nome_do_modelo>';
		$_SESSION['modulo']['table'] = '<nome_da_tabela_do_modelo>';
		$_SESSION['modulo']['pk'] = '<nome_chave_primaria>';
		$_SESSION['modulo']['anexada'] = 'produtos';
		$_SESSION['modulo']['extensao'] = array();
		$_SESSION['modulo']['pai'] = @$_GET['pai'];

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		array(
		'<campo_chave_primaria>'=>array('type'=>'pk','label'=>'<label_ou_nome_campo>'),
		'<campo_imagem>'=>array('type'=>'img','label'=>'<label_ou_nome_campo>'),
		'<campo_varchar>'=>array('type'=>'varchar','size'=>200,'notnull'=>0,'label'=>'<label_ou_nome_campo>'),
		'<campo_texto_simples>'=>array('type'=>'text','label'=>'<label_ou_nome_campo>'),
		'<campo_texto_rico_ckeditor>'=>array('type'=>'text','ckeditor'=>1,'label'=>'<label_ou_nome_campo>'),
		'<campo_data>'=>array('type'=>'date','notnull'=>0,'label'=>'<label_ou_nome_campo>'),
		'<campo_chave_estrangeira>'=>array('type'=>'fk','table_fk'=>'<nome_tabela_estrangeira>','fk_id'=>'<id_tabela_estrangeira>','fk_text'=>'<campo_texto_tabela_estrangeira>','label'=>'<label_ou_nome_campo>'),
		);
		//Instalando o modulo
		$this->install();
		//ir para controlador
		redirect(base_admin('controle/listar'));
	}

------FIM DO EXEMPLO---------

*/

  class Modulos extends CI_Controller{

   public function __construct(){
	   parent::__construct();
	   $_SESSION['filtros'] = array();
	   }
      
    /*------------------------Banne Principal----------------------*/
           
      	public function banners(){
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'banners';
		$_SESSION['modulo']['table'] = 'website_banners';
		$_SESSION['modulo']['pk'] = 'id_banner';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();
		$_SESSION['modulo']['view_adicionar'] = 'banners/adicionar';
		$_SESSION['modulo']['view_editar'] = 'banners/editar';

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		array(
		'id_banner'=>array('type'=>'pk','label'=>'Nº'),
		'imagem'=>array('type'=>'img','label'=>'Imagem'),
		'link'=>array('type'=>'varchar','size'=>200,'label'=>'Link'),
		);
		//Instalando o modulo
		$this->install();
		//ir para controlador
		redirect(base_admin('controle/listar'));
	}
     
     /*-----------------------End----------------------*/
           
      /*--------------texto chamada home home----*/
           	public function texto_home(){
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'texto_home';
		$_SESSION['modulo']['table'] = 'website_texto_home';
		$_SESSION['modulo']['pk'] = 'id';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		
                array(
		 'id'=>array('type'=>'pk','label'=>'Nº'),
		 'titulo'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Titulo'),
		 'texto'=>array('type'=>'text','size'=>200,'label'=>'Texto Home')
		);
		
		//Instalando o modulo
		$this->install();
		//ir para controlador
		redirect(base_admin('controle/listar'));
	}
        
      /*-------end--------*/
        
      /*------------------------Institucional----------------------*/
        public function empresa(){
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'empresa';
		$_SESSION['modulo']['table'] = 'website_empresa';
		$_SESSION['modulo']['pk'] = 'id';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		array(
		'id'=>array('type'=>'pk','label'=>'Nº'),
		'titulo'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Titulo'),
		'texto'=>array('type'=>'text','ckeditor'=>1,'label'=>'Texto sobre a empresa')
		);
		
		//Instalando o modulo
		$this->install();
		//ir para controlador
		redirect(base_admin('controle/listar'));
                
	}
      /*------------------------End----------------------*/
        
      /*--------------------produtos-------------------*/
        
           	public function produtos(){
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'produtos';
		$_SESSION['modulo']['table'] = 'website_produtos';
		$_SESSION['modulo']['pk'] = 'id';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		
                array(
		 'id'=>array('type'=>'pk','label'=>'Nº'),
                 'imagem'=>array('type'=>'img','label'=>'Imagem'),
		 'titulo'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Titulo'),                
		 'resumo'=>array('type'=>'text','size'=>200,'label'=>'Resumo'),
                 'texto'=>array('type'=>'text','ckeditor'=>1,'label'=>'Texto Produto')
		);
		
		//Instalando o modulo
		$this->install();
		
                //IR para Controlador
		redirect(base_admin('controle/listar'));
                
	    }
        
    /*-----------------------end--------------------------*/

   
    /*--------------------Mais Videos Home-------------------*/
        
           	public function maisvideos(){
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'maisvideos';
		$_SESSION['modulo']['table'] = 'website_mais_videos';
		$_SESSION['modulo']['pk'] = 'id';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		
                array(
		 'id'=>array('type'=>'pk','label'=>'Nº'),
                 'imagem'=>array('type'=>'img','label'=>'Imagem'),
		 'titulo'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Titulo'),
                 'link'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Link do Video'), 
		);
		
		//Instalando o modulo
		$this->install();
		
                //IR para Controlador
		redirect(base_admin('controle/listar'));
                
	    }
        
    /*-----------------------end--------------------------*/
     
            
    /*-------------------- Últimas Notícias -------------------*/
        
        public function noticias(){
            
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'noticias';
		$_SESSION['modulo']['table'] = 'website_noticia';
		$_SESSION['modulo']['pk'] = 'id';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		
                array(
		 'id'=>array('type'=>'pk','label'=>'Nº'),
                 'imagem'=>array('type'=>'img','label'=>'Imagem'),
		 'titulo'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Titulo'),                
		 'resumo'=>array('type'=>'text','size'=>200,'label'=>'Resumo'),
                 'texto'=>array('type'=>'text','ckeditor'=>1,'label'=>'Texto da Notícia')
		);
		
		//Instalando o modulo
		$this->install();
		
                //IR para Controlador
		redirect(base_admin('controle/listar'));
                
	}
        
      /*-----------------------end--------------------------*/            
            
    /*--------------------serviços-------------------*/
        
        public function servicos(){
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'servicos';
		$_SESSION['modulo']['table'] = 'website_servicos';
		$_SESSION['modulo']['pk'] = 'id';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		
                array(
		 'id'=>array('type'=>'pk','label'=>'Nº'),
                 'imagem'=>array('type'=>'img','label'=>'Imagem'),
		 'titulo'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Titulo'),
		 'resumo'=>array('type'=>'text','size'=>200,'label'=>'Resumo'),
                 'texto'=>array('type'=>'text','ckeditor'=>1,'label'=>'Texto')
		);
		
		//Instalando o modulo
		$this->install();
		
                //IR para Controlador
		redirect(base_admin('controle/listar'));
                
        }
        
      /*-----------------------end--------------------------*/
            
    /*--------------------potfolio-------------------*/
        
        public function portfolio(){            
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'portfolio';
		$_SESSION['modulo']['table'] = 'website_portfolio';
		$_SESSION['modulo']['pk'] = 'id';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		
                array(
		 'id'=>array('type'=>'pk','label'=>'Nº'),
                 'imagem'=>array('type'=>'img','label'=>'Imagem'),
		 'titulo'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Titulo'),
                 'localizacao'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Localização'), 
                 'link'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Link'),
		 'resumo'=>array('type'=>'text','size'=>200,'label'=>'Resumo'),
                 'texto'=>array('type'=>'text','ckeditor'=>1,'label'=>'Texto Portfolio')
		);
		
		//Instalando o modulo
		$this->install();
		
                //IR para Controlador
		redirect(base_admin('controle/listar'));
                
        }
        
      /*-----------------------end--------------------------*/
 
      /*--------------------parceiros-------------------*/
        
        public function parceiros(){
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'parceiros';
		$_SESSION['modulo']['table'] = 'website_parceiros';
		$_SESSION['modulo']['pk'] = 'id';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		
                array(
		 'id'=>array('type'=>'pk','label'=>'Nº'),
                 'imagem'=>array('type'=>'img','label'=>'Imagem'),
		 'titulo'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Titulo'),
		);
		
		//Instalando o modulo
		$this->install();
		
                //IR para Controlador
		redirect(base_admin('controle/listar'));
                
        }
        
      /*-----------------------end--------------------------*/ 
        
     /*--------------------Video Youtube Home-------------------*/
        
        public function video_home(){
            
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'video_home';
		$_SESSION['modulo']['table'] = 'website_video_home';
		$_SESSION['modulo']['pk'] = 'id';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =		
                array(
		 'id'=>array('type'=>'pk','label'=>'Nº'),
		 'titulo'=>array('type'=>'varchar','size'=>200,'notnull'=>1,'label'=>'Titulo'),
                 'video'=>array('type'=>'text','size'=>200,'label'=>'Video'),
		);
		
		//Instalando o modulo
		$this->install();
		
                //IR para Controlador
		redirect(base_admin('controle/listar'));
                
        }
        
      /*-----------------------end--------------------------*/  
        
      /*----------------------- Configuração Site --------------------------*/
            
	public function configuracao(){
		$_SESSION['modulo'] = array();
		$_SESSION['modulo']['modulo']  = 'configuracao';
		$_SESSION['modulo']['table'] = 'website_configuracao';
		$_SESSION['modulo']['pk'] = 'id_configuracao';
		$_SESSION['modulo']['anexada'] = '';
		$_SESSION['modulo']['extensao'] = array();

		//Definindo os campos da tabela
		$_SESSION['modulo']['fields'] =
		array(
		'id_configuracao'=>array('type'=>'pk','label'=>'Nº'),
		'empresa'=>array('type'=>'varchar','size'=>200,'notnull'=>0,'label'=>'Nome'),
		'slogan'=>array('type'=>'varchar','size'=>200,'notnull'=>0,'label'=>'Slogan'),
		'descricao'=>array('type'=>'text','label'=>'Descrição Site'),
		'telefone'=>array('type'=>'varchar','size'=>20,'label'=>'Telefone 1'),
		'telefone2'=>array('type'=>'varchar','size'=>20,'label'=>'Telefone 2'),
		'email'=>array('type'=>'varchar','size'=>200,'label'=>'E-mail'),
		'endereco'=>array('type'=>'varchar','size'=>200,'label'=>'Endereço'),
		'maps'=>array('type'=>'text','label'=>'Google Maps'),
		'facebook'=>array('type'=>'varchar','size'=>300,'label'=>'Facebook'),
		'google'=>array('type'=>'varchar','size'=>300,'label'=>'Google+'),
		'youtube'=>array('type'=>'varchar','size'=>300,'label'=>'YouTube'),
		'tempo_banner'=>array('type'=>'varchar','size'=>10,'label'=>'Tempo Banner (em milissegundos)'),
		);
		//Instalando o modulo
		$this->install();
		//ir para controlador
		redirect(base_admin('controle/listar'));
	}  

    /*----------------------- End --------------------------*/

    /*INSTALL MODULO NÃO MEXER*/
	public function install(){

		if(!$this->db->table_exists($_SESSION['modulo']['table'])){
			$SQL_TABLE = "CREATE TABLE ".$_SESSION['modulo']['table']."(";

			foreach($_SESSION['modulo']['fields'] as $field => $f){

				//PRIMARY KEY
				if($f['type']=='pk'){
					$SQL_TABLE .= $field." integer not null auto_increment primary key,";
					}

				//VARCHAR
				if($f['type']=='varchar'){
					$null = isset($f['notnull'])?'':' not null';
					$SQL_TABLE .= $field." varchar(".$f['size'].") {$null},";
					}

				//VARCHAR
				if($f['type']=='img'){
					$SQL_TABLE .= $field." varchar(200),";
					}

				//VARCHAR
				if($f['type']=='date'){
					$null = isset($f['notnull'])?'':' not null';
					$SQL_TABLE .= $field." date $null,";
					}

				//VARCHAR
				if($f['type']=='datetime'){
					$null = isset($f['notnull'])?'':' not null';
					$SQL_TABLE .= $field." datetime $null,";
					}

				//VARCHAR
				if($f['type']=='fk'){
					$SQL_TABLE .= $field." integer default 0,";
					}

				//VARCHAR
				if($f['type']=='text'){
					$null = isset($f['notnull'])?'':' not null';
					$SQL_TABLE .= $field." text $null,";
					}
				}

			if(isset($_SESSION['modulo']['pai'])){
				$SQL_TABLE .= "id_pai integer default 0,";
				}

			$SQL_TABLE .= "ordem integer default 1,";
			$SQL_TABLE .= "insert_data datetime default '0000-00-00 00:00:00',";
			$SQL_TABLE .= "update_data datetime default '0000-00-00 00:00:00');";

			$this->db->query($SQL_TABLE);


			//echo "Tabela <b>".$_SESSION['modulo']['table']."</b> criada<br>";
			}else{
				//echo "Ja existe a tabela <b>".$_SESSION['modulo']['table']."</b><br>";
				}

		}


	}



