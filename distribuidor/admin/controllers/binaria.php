<style>
/*Now the CSS*/
* {margin: 0; padding: 0;}

.tree ul {
	padding-top: 20px; position: relative;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

.tree li {
	float: left; text-align: center;
	list-style-type: none;
	position: relative;
	padding: 20px 5px 0 5px;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before, .tree li::after{
	content: '';
	position: absolute; top: 0; right: 50%;
	border-top: 1px solid #ccc;
	width: 50%; height: 20px;
}
.tree li::after{
	right: auto; left: 50%;
	border-left: 1px solid #ccc;
}

/*We need to remove left-right connectors from elements without 
any siblings*/
.tree li:only-child::after, .tree li:only-child::before {
	display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child{ padding-top: 0;}

/*Remove left connector from first child and 
right connector from last child*/
.tree li:first-child::before, .tree li:last-child::after{
	border: 0 none;
}
/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before{
	border-right: 1px solid #ccc;
	border-radius: 0 5px 0 0;
	-webkit-border-radius: 0 5px 0 0;
	-moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
	border-radius: 5px 0 0 0;
	-webkit-border-radius: 5px 0 0 0;
	-moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before{
	content: '';
	position: absolute; top: 0; left: 50%;
	border-left: 1px solid #ccc;
	width: 0; height: 20px;
}

.tree li a{
	border: 1px solid #ccc;
	padding: 5px 10px;
	text-decoration: none;
	color: #666;
	font-family: arial, verdana, tahoma;
	font-size: 11px;
	display: inline-block;
	
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

/*Time for some hover effects*/
/*We will apply the hover effect the the lineage of the element also*/
.tree li a:hover, .tree li a:hover+ul li a {
	background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
}
/*Connector styles on hover*/
.tree li a:hover+ul li::after, 
.tree li a:hover+ul li::before, 
.tree li a:hover+ul::before, 
.tree li a:hover+ul ul::before{
	border-color:  #94a0b4;
}

a{font-size:9px !important;}
.ativo{
	color:#090 !important;
	}
/*Thats all. I hope you enjoyed it.
Thanks :)*/
</style>


<?php

class Binaria extends CI_Controller{
	
	public function  index(){
		}


	
	public function show_rede(){
		
		$id_start = isset($_GET['id'])?$_GET['id']:1;
		
		echo "<div style='width:10000px; height:1500px;'>";
		echo "<div class='tree'>\n\n";
		 $d = $this->db->select(array('di_id','di_esquerda','di_direita','di_ni_patrocinador','di_ativo','di_nome'))->where('di_id',$id_start)->get('distribuidores')->row();
		 echo "<ul>\n";
		 echo "<li><a href=''><img width='100px' src='".base_url('public/imagem/user.png')."' /><br />{$d->di_id}</a>\n";
		 echo self::gerar_nod($d,0);
		 echo "</li>\n";
		 echo "</ul>\n";
		echo "</div>";
		echo "</div>";
		}
	
	private function gerar_nod($pai,$line){
		
		if($line <=4){
		
		$line++;
		
		$esquerda = $this->db->select(array('di_id','di_esquerda','di_direita','di_ni_patrocinador','di_usuario','di_ativo','di_nome'))->where('di_id',$pai->di_esquerda)->get('distribuidores')->row();
		$direita = $this->db->select(array('di_id','di_esquerda','di_direita','di_ni_patrocinador','di_usuario','di_ativo','di_nome'))->where('di_id',$pai->di_direita)->get('distribuidores')->row();
		
		$esquerda = isset($esquerda->di_id)?$esquerda:false;
		$direita = isset($direita->di_id)?$direita:false;
		
	    if($esquerda&&$direita){
			
			echo "	<ul>\n";
			echo "		<li><a ".($esquerda->di_ativo==1?'class="ativo"':'')." href='".current_url().'?id='.$esquerda->di_id."'><img width='20px' src='".base_url('public/imagem/user.png')."' /><br>".texto($esquerda->di_usuario,10,'')." <br>{$esquerda->di_id}</a>\n";
			echo self::gerar_nod($esquerda,$line);
			echo "		</li>\n";
			echo "		<li><a ".($direita->di_ativo==1?'class="ativo"':'')." href='".current_url().'?id='.$direita->di_id."'><img width='20px' src='".base_url('public/imagem/user.png')."' /><br>".texto($direita->di_usuario,10,'')." <br>{$direita->di_id}</a>\n";
			echo self::gerar_nod($direita,$line);
			echo "		</li>\n";
			echo "	</ul>\n";
			}

	    if($esquerda && $direita==false){
			echo "<ul>\n";
			echo "<li><a ".($esquerda->di_ativo==1?'class="ativo"':'')." href='".current_url().'?id='.$esquerda->di_id."'><img width='20px' src='".base_url('public/imagem/user.png')."' /><br>".texto($esquerda->di_usuario,10,'')." <br>{$esquerda->di_id}</a>\n";
			echo self::gerar_nod($esquerda,$line);
			echo "</li>\n";
			echo "</ul>\n";
			}

	    if($direita && $esquerda==false){
			echo "<ul>\n";
			echo "<li><a ".($direita->di_ativo==1?'class="ativo"':'')." href='".current_url().'?id='.$direita->di_id."'><img width='20px' src='".base_url('public/imagem/user.png')."' /><br>".texto($direita->di_usuario,10,'')." <br>{$direita->di_id}</a>\n";
			echo self::gerar_nod($direita,$line);
			echo "</li>\n";
			echo "</ul>\n";
			}
		
		}
		
		}
		
			
		
	
	
		
	
	}
	
?>	



