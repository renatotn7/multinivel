<?php 
$compraPaga = $this->db->where('co_pago',1)->where('co_id_distribuidor',get_user()->di_id)->get('compras')->row();

if(count($compraPaga) > 0){


if(get_parameter('info_user')){
	  $pai = (int)base64_decode(get_parameter('info_user'));
	  }else{
		  $pai = get_user()->di_id;
		  }

		$atual = $this->db->query("
		SELECT `di_id`, `di_nome`, `di_ni_patrocinador`, `di_data_cad`, `di_usuario`, 
		`di_esquerda`, `di_direita`
		FROM (`distribuidores`)  
		WHERE `di_id` = {$pai}
		AND di_id IN(
		SELECT li_id_distribuidor FROM distribuidor_ligacao WHERE li_no = ".get_user()->di_id."
		)
		")->row();



if($atual->di_id==''){
	
	 $pai = get_user()->di_id;
	 
	 $atual = $this->db
	 ->select(array('di_id','di_nome','di_ni_patrocinador','di_data_cad','di_usuario','di_esquerda','di_direita'))
	 ->where('di_id',get_user()->di_id)
	 ->get('distribuidores')->row();
	 	
	}
	
$esta_alocado = $this->db->select('di_id')
->where('di_esquerda',$atual->di_id)
->or_where('di_direita',$atual->di_id)
->get('distribuidores')->row();	
	
?>

<div style="background:none;border:none;" class="box-content min-height">
 <div class="box-content-header" style="color:#EEEEEE;">Pontos Diários</div>
 <div class="box-content-body">
 
<table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#EEEEEE;">
  <tr>
    <td width="60%" valign="top">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td width="34%"><strong>Nome:</strong></td>
    <td width="66%"><?php echo $atual->di_nome?>(<?php echo $atual->di_usuario?>)</td>
    </tr>
  <tr>
    <td><strong>Cadastrado:</strong></td>
    <td><?php echo date('d/m/Y',strtotime($atual->di_data_cad))?></td>
 </tr>
  
</table>
    
    </td>
    <td>
    
    <form method="get" action="<?php echo base_url('index.php/distribuidor/buscar_na_rede')?>" style="margin:0;">
      <div class="input-append">
      <input type="text" name="chave_user" placeholder='Informe o usuário ou Nome'>
      <button class="btn btn-info" type="submit">Buscar</button>
    </div>
    </form>
<?php 
if($atual->di_id != get_user()->di_id){?>
     <a class="btn" href="<?php echo current_url()?>"><i class="icon-home"></i>INICIO DA REDE</a>
    <?php }?>
    <br />
<br />

    
    </td>
  </tr>
</table>


<div class="rede-binaria">
  
  <?php 
  $dis1 = get_no($pai,'dis1');
  $dis1_1 = get_no($dis1->di_esquerda,'dis1-1');
  $dis1_2 = get_no($dis1->di_direita,'dis1-2');
  $dis2_1 = get_no($dis1_1->di_esquerda,'dis2-1');
  $dis2_2 = get_no($dis1_1->di_direita,'dis2-2');
  $dis2_3 = get_no($dis1_2->di_esquerda,'dis2-3');
  $dis2_4 = get_no($dis1_2->di_direita,'dis2-4');
  
  
  $dis3_1 = get_no($dis2_1->di_esquerda,'dis3-1');
  $dis3_2 = get_no($dis2_1->di_direita,'dis3-2'); 
  $dis3_3 = get_no($dis2_2->di_esquerda,'dis3-3');  
  $dis3_4 = get_no($dis2_2->di_direita,'dis3-4');
  $dis3_5 = get_no($dis2_3->di_esquerda,'dis3-5');
  $dis3_6 = get_no($dis2_3->di_direita,'dis3-6'); 
  $dis3_7 = get_no($dis2_4->di_esquerda,'dis3-7'); 
  $dis3_8 = get_no($dis2_4->di_direita,'dis3-8');
  
 
   
  ?>

  
</div>

<div class="legenda">
</div>

</div>
</div>




<?php 
}

function get_no($di_id,$id_css){
	
	if($di_id==0){
		$dis_vazio = new stdClass;
		$dis_vazio->di_esquerda = 0;
		$dis_vazio->di_direita = 0;
		return $dis_vazio;
		}
	
	$ci =& get_instance();
	
	
	 $dis = $ci->db
	 ->select(array('di_id','di_ni_patrocinador','di_binario','di_usuario_patrocinador','di_esquerda','di_usuario','di_direita'))
     ->where('di_id',$di_id)
	 ->join('compras','co_id_distribuidor=di_id')
	 ->get('distribuidores')->row();
	

	
	if(count($dis)>0){
		     
			$pat = $ci->db->select(array('di_nome','di_usuario'))
			->where('di_id',$dis->di_ni_patrocinador)
			->get('distribuidores')->row(); 
		
		 
		 $ativou_binario = $dis->di_binario==1?true:false;  
		?>	  
		  
          <div class="dis <?php echo $id_css?>">
          <a href="<?php echo current_url().'?info_user='.base64_encode($dis->di_id)?>">
          <?php 
		  $imagem = "binario-ativo.png";	    
		  ?>
              
              
           <img src="<?php echo base_url()?>public/imagem/<?php echo $imagem;?>" />
          </a> 
           <div class="info-user">
             <div><b>Usuário: </b> <?php echo $dis->di_usuario?> </div>
             <div><b>Nome: </b> <?php echo $dis->di_nome?> </div>
             <?php if(count($pat)>0){?>
              <div><b>Patrocinador: </b> <?php echo $pat->di_nome?>(<?php echo $pat->di_usuario?>) </div>
             <?php }?>
           </div>
          </div>
          
		 <?php
		 return $dis;
		}else{
			return false;
			}	
	}

  
	
?>
