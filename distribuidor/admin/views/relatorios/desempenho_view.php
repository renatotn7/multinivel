
<link rel="stylesheet" href="<?php echo base_url("public/script/tree")?>/css/jquery.treeTable.css" />
<script src="<?php echo base_url("public/script/tree")?>/js/jquery.treeTable.js" type="text/javascript"></script>
<script src="<?php echo base_url("public/script/tree")?>/js/jquery.ui.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function()  {
		
       $(".tree-desempenho").treeTable();
	   
	   $.each($(".tree-desempenho tbody tr"),function(index,val){
		   if(index%2==0){
		   $(val).css('background','#f9f9f9');
		   }
		   });
		   
		$('.tree-desempenho tbody tr').click(function(){
			 $('.tree-desempenho tbody tr').removeClass('active');
			 $(this).addClass('active');
			});   
	   
    });
</script>


<h1>Desempenho da minha rede</h1>
<div style="overflow:auto; height:400px;">

<table class="tree-desempenho" width="3000px">
<thead>
<tr>
   <td width="146" rowspan="2">NI</td>
   <td width="374" rowspan="2">Nome</td>
   <td width="55" rowspan="2">Ativo</td>
   <td colspan="2" align="center">V.PP</td>
   <td colspan="3" align="center">Qualificação</td>
   <td colspan="2" align="center">Cad. 1º Geração</td>
   <td colspan="2" align="center">Ativos. 1º Geração</td>
   <td colspan="2" align="center">V.PG</td>
   <td colspan="2" align="center">Qua. em Linhas Diferentes</td>
   <td width="203" rowspan="2">Localização</td>
   <td width="183" rowspan="2">Ultimo acesso</td>
   <td width="51" rowspan="2">Qtd. Acesso</td>
</tr> 
<tr>
 <td width="112">Anterior</td>
 <td width="112">Atual</td>
  
  <td width="212">Anterior</td>
  <td width="158">Atual</td>
  <td width="152">Próxima</td>
  
  <td width="217">Realizados</td>
  <td width="206">Falta</td>

  <td width="176">Realizados</td>
  <td width="185">Falta</td> 

  <td width="102">Realizados</td>
  <td width="71">Falta</td> 

  <td width="160">Realizados</td>
  <td width="45">Falta</td> 
  
</tr>  
</thead>
<tbody>
<?php 
    tree_distribuidor(get_user()->di_id,1,get_user()->di_id);
?>
</tbody>
</table>



</div>
<div class="buttons">
 <a class="botao" href="javascript:history.go(-1)">Voltar</a>
</div>


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


function tree_distribuidor($ni_pai,$node,$id_inicio){
	$ci =& get_instance();
	$dis = $ci->db
	->join('cidades','ci_id=di_cidade')
	->where('di_ni_patrocinador',$ni_pai)->get('distribuidores')->result();
	 
	 if(count($dis)){
		  
		  $node++;
		  
		  foreach($dis as $d){
			  
			  $mes_atual = date('m');
			  $ano_atual = date('Y');
			  $mes_passado = date('m',mktime(0,0,0,date('m')-1,date('d'),date('Y')));
			  $ano_passado = date('Y',mktime(0,0,0,date('m')-1,date('d'),date('Y')));
			  
			  
			  
			  $img = "<img src='".base_url()."/public/imagem/".($d->di_sexo=='M'?'mas':'fem').".png'>";
			  $class_pai = $ni_pai == $id_inicio?"":"class='child-of-node-".$ni_pai."'";
			  
			  ##Pegar o ultimo login/acesso
			  $login = $ci->db->where('ha_distribuidor',$d->di_id)
			  ->order_by('ha_time','DESC')
			  ->get('historico_acesso')->result();
			  $ultimo_acesso = isset($login[0])?date('d/m/Y H:i:s',$login[0]->ha_time):'-';
			  
			  ##pegar todos os pontos P.P desse meês
			  $pontos_atual = $ci->db
			  ->where(array('pd_mes'=>$mes_atual,'pd_ano'=>$ano_atual,'pd_distribuidor'=>$d->di_id))
			  ->get('pontos_distribuidor')->result();
			  
			  $pp_atual = isset($pontos_atual[0]->pd_pontos)?$pontos_atual[0]->pd_pontos:0;
              $pg_atual = isset($pontos_atual[0]->pd_grupo)?$pontos_atual[0]->pd_grupo:0;


			  ##pegar todos os pontos P.P desse mês passado
			  $pontos_anterior = $ci->db
			  ->where(array('pd_mes'=>$mes_passado,'pd_ano'=>$ano_passado,
			  'pd_distribuidor'=>$d->di_id))
			  ->get('pontos_distribuidor')->result();

			  $pp_anterior = isset($pontos_anterior[0]->pd_pontos)?$pontos_anterior[0]->pd_pontos:'-';
              $pg_anterior = isset($pontos_anterior[0]->pd_grupo)?$pontos_anterior[0]->pd_grupo:'-';
			  
			  $pg_falta = ($pg_atual<get_pontos($d->di_qualificacao)?
			               get_pontos($d->di_qualificacao)-$pg_atual:0);			  
			  
			  ##Distribuidores primeira geração
			  $meu_nivel = $ci->db
			 ->where('li_id_distribuidor',$d->di_id)
			 ->where('li_no',$d->di_id)
			 ->select('li_posicao')
			 ->get('distribuidor_ligacao')->result();
			 
			  $ativos_geracao1 = $ci->db
				->query("SELECT di_id
				FROM distribuidor_ligacao
				JOIN  `distribuidores` ON  `di_id` =  `li_id_distribuidor` 
				WHERE  `li_no` =  `li_id_distribuidor` 
				AND li_posicao = ".($meu_nivel[0]->li_posicao+1)."
				AND li_id_distribuidor IN(SELECT di_id FROM `distribuidor_ligacao` 
				JOIN distribuidores ON di_id = `li_id_distribuidor`
				WHERE di_ativo = 1 AND `li_no` = ".$d->di_id.")")->num_rows;
			  
			  $geracao1 = $ci->db
				->query("SELECT di_id
				FROM distribuidor_ligacao
				JOIN  `distribuidores` ON  `di_id` =  `li_id_distribuidor` 
				WHERE  `li_no` =  `li_id_distribuidor` 
				AND li_posicao = ".($meu_nivel[0]->li_posicao+1)."
				AND li_id_distribuidor IN(SELECT di_id FROM `distribuidor_ligacao` 
				JOIN distribuidores ON di_id = `li_id_distribuidor`
				WHERE `li_no` = ".$d->di_id.")")->num_rows;
			  
			  ###Ativos em linhas diferentes
			  $info_rede = ativos_linhas_diferentes($d->di_id);
			  
			  //Qualificados em linhas diferentes que falta para subir de nivel
			  $qali_falta = count($info_rede['qualificados']['id'])<get_qtd_qualificados($d->di_qualificacao)?get_qtd_qualificados($d->di_qualificacao)-count($info_rede['qualificados']['id']):0;
			  
			  echo "
			    <tr id='node-".$d->di_id."' $class_pai>
                 <td>$img {$d->di_id}</td>
			     <td>{$d->di_nome}</td>
			     <td>".($d->di_ativo==1?"SIM":'NÃO')."</td>
				 <td>{$pp_anterior}</td>
				 <td>{$pp_atual}</td>
				 <td>-</td>
				 <td>".show_qualificacao($d->di_qualificacao)."</td>
				 <td>".show_qualificacao($d->di_qualificacao+1)."</td>	
				 <td>".$geracao1."</td>
				 <td>".($geracao1<5?(5-$geracao1):0)."</td>	
				 <td>".$ativos_geracao1."</td>
				 <td>".($ativos_geracao1<5?(5-$ativos_geracao1):0)."</td>	
				 <td>{$pg_atual}</td>
				 <td>".$pg_falta."</td>	
				 <td>".count($info_rede['qualificados']['id'])."</td>
				 <td>".$qali_falta."</td>
				 <td>".$d->ci_nome."-".$d->ci_uf."</td>		 
				 <td>{$ultimo_acesso}</td>
				 <td>".count($login)."</td>
                </tr>
			  ";
			  tree_distribuidor($d->di_id,$node,$id_inicio);
			  }
			  
		
		 }
	
	}
?>







<style>


/* TABLE
 * ========================================================================= */
.tree-desempenho {
  border: 1px solid #f0f0f0;
  border-collapse: collapse;
  line-height: 1;
  margin: 1em auto;
}



.tree-desempenho thead tr td {
	background:#f3f3f3;
	border:1px solid #d9d9d9;
  font-weight: normal;
  padding: 5px;
  color:#069;
  font-weight:bold;
  font-size:13px;
}

/* Body
 * ------------------------------------------------------------------------- */
 
 .tree-desempenho tbody tr:hover td, .tree-desempenho tbody tr.active td{
	 background:#BAEDFC;
	 }
 
.tree-desempenho tbody tr td {
  cursor: default;
  border:1px solid #f0f0f0;
  padding: 4px 20px;
  font-size:12px;
  cursor:pointer;
  color:#888;
}

.tree-desempenho tbody tr.even {
  background: #f3f3f3;
}

.tree-desempenho tbody tr.odd {
  background: #fff;
}



</style>

