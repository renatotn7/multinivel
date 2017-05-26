<?php 
$qualificacao = get_parameter('q')?get_parameter('q'):get_user()->di_qualificacao;

$next_qualificacao = ($qualificacao+1);

if($next_qualificacao>=6){
	 $min_perfumes = 7;
	}else{
		$min_perfumes = 5;
		}

if($next_qualificacao>=6){
	 if($next_qualificacao==6){
	  $ativos = 256;
	 }

	 if($next_qualificacao==7){
	  $ativos = 4096;
	 }

	 if($next_qualificacao==8){
	  $ativos = 32768;
	 }
	}else{
		$ativos = 2;
		}

?>


<h2 style="text-align:center">Qualificação atual: <?php echo get_user()->dq_descricao;?></h2>


<table width="1000px" align="center" border="0" style="background:#069; color:#FFF; font:bold 20px arial" cellspacing="0" cellpadding="20">
  <tr>
    <td width="220" align="center">
    <div style="padding:20px; background:#fff; color:#090;">
     <?php echo show_qualificacao($qualificacao+1)?>
     </div>
     <div style="background:#CCC; padding:4px; color:#000; font:bold 20px arial;">Nível</div>
    </td>
    <td width="700">Você precisa atingir os seguintes objetivos para se qualificar a <?php echo show_qualificacao($qualificacao+1)?>:
    </td>
  </tr>
</table>

<br />
<?php 

			  ##Distribuidores primeira geração
			  $meu_nivel = $this->db
			 ->where('li_id_distribuidor',get_user()->di_id)
			 ->where('li_no',get_user()->di_id)
			 ->select('li_posicao')
			 ->get('distribuidor_ligacao')->result();
			 
			  $ativos_geracao1 = $this->db
				->query("SELECT di_id
				FROM distribuidor_ligacao
				JOIN  `distribuidores` ON  `di_id` =  `li_id_distribuidor` 
				WHERE  `li_no` =  `li_id_distribuidor` 
				AND li_posicao = ".($meu_nivel[0]->li_posicao+1)."
				AND li_id_distribuidor IN(SELECT di_id FROM `distribuidor_ligacao` 
				JOIN distribuidores ON di_id = `li_id_distribuidor`
				WHERE di_ativo = 1 AND `li_no` = ".get_user()->di_id.")")->num_rows;
?>
<table width="1000px" align="center" style="background:#069; color:#FFF; font:bold 20px arial" border="0" cellspacing="20" cellpadding="0">
  <tr>
    <td width="33%" style="background:#CCC; color:#000; text-align:center;">
    
    
    
    <table width="100%" border="0" cellspacing="0" style="background:#FFF; color:#000;" cellpadding="10">
      <tr>
        <td>Realizados</td>
        <td>Faltam</td>
      </tr>
        <tr>
        <td style="color:#090"><?php echo $ativos_geracao1?></td>
        <td style="color:#900"><?php echo ($ativos_geracao1<$ativos?($ativos-$ativos_geracao1):0)?></td>
      </tr>
    </table>
     Ativos
    </td>
    
    <td width="33%" style="background:#CCC; color:#000; text-align:center;">
   
   <?php 

$consumido = $this->db
->select(array('SUM(pm_quantidade) as qtd'))
->join('produtos','pr_id=pm_id_produto')
->join('compras','pm_id_compra=co_id')
->where('co_pago',1)
->where('pm_valor',39.50)
->like('co_data_compra',date('Y-m-'))
->where('co_id_distribuidor',get_user()->di_id)
->where_in('pr_categoria',array(10,1,2))
->get('produtos_comprados')->row();
$consumido = $consumido->qtd+0; 
   ?> 
    
 
    <table width="100%" border="0" cellspacing="0" style="background:#FFF; color:#000;" cellpadding="10">
      <tr>
        <td>Consumidos</td>
        <td>Faltam</td>
      </tr>
        <tr>
        <td style="color:#090"><?php echo $consumido?></td>
        <td style="color:#900"><?php echo ($consumido<$min_perfumes?($min_perfumes-$consumido):0)?></td>
      </tr>
    </table>
    Perfumes Consumidos
    </td>
    <?php if($next_qualificacao!=2&&$next_qualificacao<6 ){?>
    <td style="background:#CCC; color:#000; text-align:center;">
    
    <?php 
	###Ativos em linhas diferentes
	$info_rede = ativos_linhas_diferentes(get_user()->di_id);
		//Qualificados em linhas diferentes que falta para subir de nivel
	$qali_falta = count($info_rede['qualificados']['id'])<get_qtd_qualificados($qualificacao)?get_qtd_qualificados($qualificacao)-count($info_rede['qualificados']['id']):0;
			  	  
	?>
    
     <table width="100%" border="0" cellspacing="0" style="background:#FFF; color:#000;" cellpadding="10">
      <tr>
        <td>Tempo Necessário</td>
        </tr>
        <tr>
        <td style="color:#090">
        
        <?php
		 
		if($next_qualificacao==3){
				$ql = $this->db
				->where('hi_distribuidor',get_user()->di_id)
				->order_by('hi_qualificacao','DESC')
				->where('hi_data <',date('Y-m-01'))
				->where('hi_qualificacao',2)
				->get('historico_qualificacao',1)->row();
				
		         $foi_vip_plus = isset($ql->hi_qualificacao)?true:false;
			    if(!$foi_vip_plus){
					echo "Ficar 30 dias como vip plus";
				}else{
					echo "Tempo atingido";
					}
			 }

		if($next_qualificacao==4){
				$ql = $this->db
				->where('hi_distribuidor',get_user()->di_id)
				->order_by('hi_qualificacao','DESC')
				->where('hi_data <',date('Y-m-01'))
				->where('hi_qualificacao',3)
				->get('historico_qualificacao',1)->row();
				
		         $foi_vip_plus = isset($ql->hi_qualificacao)?true:false;
			    if(!$foi_vip_plus){
					echo "Ficar 30 dias como vip star";
				}else{
					echo "Tempo atingido";
					}
			 }

		if($next_qualificacao==5){
				$ql = $this->db
				->where('hi_distribuidor',get_user()->di_id)
				->order_by('hi_qualificacao','DESC')
				->where('hi_data <',date('Y-m-01'))
				->where('hi_qualificacao',4)
				->get('historico_qualificacao',1)->row();
				
		         $foi_vip_plus = isset($ql->hi_qualificacao)?true:false;
			    if(!$foi_vip_plus){
					echo "Ficar 30 dias como vip gold";
				}else{
					echo "Tempo atingido";
					}
			 }
			 			 
			 ?>
        </td>
        </tr>
    </table>

    Tempo
    
    </td>
    <?php }?>
  </tr>
  <tr>
  <td colspan="3" style="color:#000;">
  <form method="get" name="formulario" action="">
   Simular qualificação para:
   <select name="q">
    <option <?php echo get_parameter('q')==1?'selected':''?> value="1">VIP PLUS</option>
    <option <?php echo get_parameter('q')==2?'selected':''?> value="2">VIP Star</option>
    <option <?php echo get_parameter('q')==3?'selected':''?> value="3">VIP Gold</option>
    <option <?php echo get_parameter('q')==4?'selected':''?> value="4">VIP Master</option>
    <option <?php echo get_parameter('q')==5?'selected':''?> value="5">VIP Diamante</option>
    <option <?php echo get_parameter('q')==6?'selected':''?> value="6">VIP Diamante Blue</option>
    <option <?php echo get_parameter('q')==7?'selected':''?> value="7">VIP Diamante Black</option>
   </select>
   <input type="submit" class="botao" value="Exibir"  />
  </form>
  
  </td>
  </tr>
</table>



<?php

function ativos_linhas_diferentes($di_id){
		  $ci =& get_instance();
		
		  $retorno = array();
		  $retorno['qualificados'] = array('id'=>array());
		  $retorno['vip_plus'] = array('id'=>array());
		  $retorno['vip_star'] = array('id'=>array());
		  $retorno['vip_gold'] = array('id'=>array());
		  $retorno['vip_master'] = array('id'=>array());
		  
		  
		  $minha_rede = $ci->db
		 ->select(array('di_id','di_qualificacao'))
		 ->join('distribuidores','di_id = li_id_distribuidor')
		 ->where('di_ativo',1)
		 ->where('di_qualificacao >',1)
		 ->where('di_id <>',$di_id)
		 ->where('li_no',$di_id)
		 ->get('distribuidor_ligacao')->result();
		 
		  foreach($minha_rede as $distribuidor){
			       
				   $id_dis_atual = $distribuidor->di_id;
				   $qualificacao =  $distribuidor->di_qualificacao;
			      
					#Verifica se há algum distribuidor qualificado na mesma linha
					if(count($retorno['qualificados']['id'])){
						
					$mesma_linha = $ci->db
					->where_in('li_no',$retorno['qualificados']['id'])
					->where('li_id_distribuidor',$id_dis_atual)
					->get('distribuidor_ligacao')->num_rows;
					
					}else{
						$mesma_linha =0;
						}
						
						#Adiciona ao Array de qualificados em linhas diferentes
						if($mesma_linha==0){
							
                          if($qualificacao==2){
                           $retorno['vip_plus']['id'][] =  $id_dis_atual;   
                          }
                          
                          if($qualificacao==3){
                           $retorno['vip_star']['id'][] =  $id_dis_atual;   
                          }
                          
                          if($qualificacao==4){
                           $retorno['vip_gold']['id'][] =  $id_dis_atual;   
                          }
                          
                          if($qualificacao==5){
                           $retorno['vip_master']['id'][] =  $id_dis_atual;   
                          }
                          
						  $retorno['qualificados']['id'][] = $id_dis_atual;
						}

				
			  }#fim do foreach
		 
		 
		 return $retorno; 
		}


function show_qualificacao($qualificacao){
	 switch($qualificacao){
		 case 1: return 'Distribuidor vip';break;
		 case 2: return 'VIP Plus';break;
		 case 3: return 'VIP Star';break;
		 case 4: return 'VIP Gold';break;
		 case 5: return 'VIP Master';break;
		 case 6: return 'Diamante';break;
		 case 7: return 'Diamante Blue';break;
		 case 8: return 'Diamante Black';break;
		 case 9: return 'Diamante Black';break;
		 }
	}
	

function get_pontos($qualificacao){
	 switch($qualificacao){
		 case 1: return 1900;break;//distribuidor
		 case 2: return 5900;break;//vip plus
		 case 3: return 14500;break;//vip star
		 case 4: return 29500;break;//vip gold
		 case 5: return 59500;break;//vip master
		 case 6: return 790000;break; //diamante
		 case 7: return 990000;break; //diamante blue
		 case 8: return 990000;break;//diamante blue
		 }
	}

function get_qtd_qualificados($qualificacao){
	 switch($qualificacao){
		 case 1: return 0;break;//distribuidor
		 case 2: return 2;break;//vip plus
		 case 3: return 5;break;//vip star
		 case 4: return 6;break;//vip gold
		 case 5: return 6;break;//vip master
		 case 6: return 10;break; //diamante
		 case 7: return 16;break; //diamante blue
		 case 8: return 16;break;//diamante blue
		 }
	}

?>