<?php 

$dataBinarioDIREITA = $this->db->query("
    SELECT di_id, di_usuario, di_nome, co_data_compra FROM compras 
	JOIN distribuidores ON di_id = co_id_distribuidor
	JOIN  distribuidor_ligacao ON li_id_distribuidor = di_id 
	WHERE 
	li_no = ".get_user()->di_direita."
	AND co_pago = 1
	ORDER BY co_data_compra ASC
   ")->row();
 


   $dataBinarioESQUERDA = $this->db->query("
    SELECT di_id, di_usuario, di_nome, co_data_compra FROM compras 
	JOIN distribuidores ON di_id = co_id_distribuidor
	JOIN  distribuidor_ligacao ON li_id_distribuidor = di_id 
	WHERE 
	li_no = ".get_user()->di_esquerda."
	AND co_pago = 1
	ORDER BY co_data_compra ASC
   ")->row();
   

  
   
  
  $pontos  = new Pontos(get_user());
  $dataBinario = $pontos->get_data_binario();
  $pontosEsquerda = $pontos->get_pontos_esquerda();
  
  $pontosDireita = $pontos->get_pontos_direita();
			
  $pontosPagos = $pontos->total_pontos_pagos_positivo();
  $pernaMenor = $pontosEsquerda < $pontosDireita?$pontosEsquerda:$pontosDireita;
  
  $pontosTotalEsquerda = $pontos->carrega_esquerda_total();
  $pontosTotalDireita = $pontos->carrega_direita_total();
  $EsquerdaAntes = $pontos->carrega_esquerda_antes_qualificacao();
  $DireitaAntes = $pontos->carrega_direita_antes_qualificacao();
  
?>



<div class="box-content min-height">
 <div class="box-content-header">
  <a href="<?php echo base_url()?>">Principal</a> &raquo; Detalhes do Binário
 </div>
 <div class="box-content-body">
 
 <table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#f9f9f9">Data de Indicação Esquerda</td>
    <td>
    <?php if(isset($dataBinarioESQUERDA->di_nome)){?>
	<strong><?php echo $dataBinarioESQUERDA->di_nome?>(<?php echo $dataBinarioESQUERDA->di_usuario?>)</strong><br>
	<?php echo date('d/m/Y H:i:s',strtotime($dataBinarioESQUERDA->co_data_compra))?>
    <?php }else{?>
     Nenhum indicado na direita
    <?php }?>
    </td>
  </tr> 
  <tr>
    <td width="250px" bgcolor="#f9f9f9">Data de Indicação Direita</td>
    <td>
    <?php if(isset($dataBinarioDIREITA->di_nome)){?>
	<strong><?php echo $dataBinarioDIREITA->di_nome?>(<?php echo $dataBinarioDIREITA->di_usuario?>)</strong><br>
	<?php echo date('d/m/Y H:i:s',strtotime($dataBinarioDIREITA->co_data_compra))?>
    <?php }else{?>
     Nenhum indicado na direita
    <?php }?>
    </td>
  </tr>
  <?php if(isset($dataBinarioDIREITA->di_nome) && isset($dataBinarioESQUERDA->di_nome)){?>
  <tr>
    <td bgcolor="#f9f9f9">Data da sua qualificação</td>
    <td><?php echo date('d/m/Y',strtotime($dataBinario))?></td>
  </tr>    
  <?php }?>
 </table>


 <table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td bgcolor="#f9f9f9">Pontos Total Esquerda</td>
    <td>
	<label class="label label-info"> <?php echo number_format($pontosTotalEsquerda,0,',','.')?></label>
    </td>
  </tr> 
  <tr>
    <td width="250px" bgcolor="#f9f9f9">Pontos Total Direita</td>
    <td>
	  <label class="label label-info"><?php echo number_format($pontosTotalDireita,0,',','.')?></label>
    </td>
  </tr> 

  <tr>
    <td bgcolor="#f9f9f9">Pontos Esquerda(Antes da qualificação)</td>
    <td>
	<label class="label label-important"><?php echo number_format($EsquerdaAntes,0,',','.')?></label>
    </td>
  </tr> 
  <tr>
    <td width="250px" bgcolor="#f9f9f9">Pontos Direita(Antes qualificação)</td>
    <td>
	 <label class="label label-important"><?php echo number_format($DireitaAntes,0,',','.')?></label>
    </td>
  </tr> 
 
  <tr>
    <td bgcolor="#f9f9f9">Pontos Esquerda(Após a qualificação)</td>
    <td>
    <label class="label label-info"><?php echo number_format($pontosTotalEsquerda,0,',','.')?></label> - 
	<label class="label label-important"><?php echo number_format($EsquerdaAntes,0,',','.')?></label> =
    <label class="label label-success"><?php echo number_format($pontosEsquerda,0,',','.')?></label>
    </td>
  </tr> 
  <tr>
    <td width="250px" bgcolor="#f9f9f9">Pontos Direita(Após a qualificação)</td>
    <td>
       <label class="label label-info"><?php echo number_format($pontosTotalDireita,0,',','.')?></label> - 
	<label class="label label-important"><?php echo number_format($DireitaAntes,0,',','.')?></label> =
	 <label class="label label-success"><?php echo number_format($pontosDireita,0,',','.')?></label>
    </td>
  </tr> 
   <tr>
    <td width="250px" bgcolor="#f9f9f9">Perna Menor</td>
    <td>
	 <?php echo number_format($pernaMenor,0,',','.')?>
    </td>
  </tr>  
  <tr>
    <td width="250px" bgcolor="#f9f9f9">Total de Pontos Pagos</td>
    <td>
	 <?php echo number_format($pontosPagos,0,',','.')?>
    </td>
  </tr> 
  <tr>
    <td width="250px" bgcolor="#f9f9f9">Bônus Pago Individo</td>
    <td>
	 <?php echo number_format($pernaMenor-$pontosPagos,0,',','.')?>
    </td>
  </tr>        
 </table>
 
 
 </div>
</div>