<div class="box-content min-height">
 <div class="box-content-header">Depósitos</div>
 <div class="box-content-body">

<a href="<?php echo base_url('index.php/relatorios/relatorio_folha_pagamento') ?>">Relatório Folha de Pagamento Pendente</a>
<p class="label label-info">
  Data:
</p>

<form action="<?php echo base_url('index.php/relatorios/depositos'); ?>" name="form1" id="form1">
    de: <input style="margin:0; width:90px;"
               type="text" 
               class="mdata" 
               size="10" value="<?php echo get_parameter('de')?>" name="de" 
               />
    
    até: <input style="margin:0;width:90px;"
                type="text" 
                class="mdata" 
                size="10" 
                value="<?php echo get_parameter('ate')?>" 
                name="ate" />
    
    <input type="submit" class="btn btn-info" value="Filtrar" />
    <a class="btn btn-primary" href="<?php echo base_url('index.php/solicitacoes_saques/gerarcsv?'.  http_build_query(array_filter($_REQUEST)));?>">Exportar CSV</a>
</form>
<table border="0" class="table table-bordered" width="100%" class="relatorios" cellpadding="0" cellspacing="0">
 <thead>
  <tr>
   <td width="5%">Nº </td>
   <td width="33%">Distribuidor</td>
   <td width="33%">Usuário</td>
   <td width="10%">Data</td>
   <td width="22%">Situação</td>
   <td width="14%">Valor</td>
   <td width="33%"></td>
  </tr>
 </thead>
 <tbody>
 <?php 
 
 if(get_parameter('de')){
		$this->db->where('cdp_data >=',data_usa(get_parameter('de'))." 00:00:00");
}
if(get_parameter('ate')){
		$this->db->where('cdp_data <=',data_usa(get_parameter('ate'))." 23:59:59");
}
 
 $compras = $this->db
				 ->where('cdp_id NOT IN(select ex_id_conta_bonus from conta_extorno)')
				 ->join('distribuidores','di_id=cdp_distribuidor')
				 ->order_by('cdp_status','ASC')
				 ->order_by('cdp_data','DESC')
 				 ->get('conta_deposito')->result();
 $total = 0;
 $total_pago = 0;
 $total_pendente = 0;
 foreach( $compras as $key=> $c){
 $c->cdp_status==1?$total_pago+=$c->cdp_valor:$total_pendente+=$c->cdp_valor;
 ?>
 
   <tr <?php echo $c->cdp_status==1?'class="marca"':''?>>
   <td><?php echo anchor('relatorios/deposito_unico/'.$c->cdp_id,$c->cdp_id,'target="_blank"')?></td>
   <td><?php echo anchor('relatorios/deposito_unico/'.$c->cdp_id,$c->di_nome,'target="_blank"')?></td>
   <td><?php echo $c->di_usuario; ?></td>
   <td><?php echo date('d/m/Y',strtotime($c->cdp_data))?></td>
   <td><?php echo $c->cdp_status==1?'Depósito efetuado':'Aguardando depósito'?> </td>
   <td>US$ <?php echo number_format($c->cdp_valor,2,',','.')?></td>
    <td>
        <?PHP IF($c->cdp_status ==0){?>
        <form action="<?php echo base_url('index.php/deposito/extorno');?>" onsubmit="return confirm('deseja estornar esse valor?');" method="POST" name="form-modal-<?php echo $key;?>">
             <input type="hidden" name="cdp_id" id="cdp_id" value="<?php echo $c->cdp_id;?>">
             <button type="submit" class="btn btn-danger" data-toggle="button" >Extornar</button>
        </form>
        <?PHP }?>
    </td>
  </tr>
  <?php }?>


   <tr>
   <td align="right">&nbsp;</td>
   <td align="right">&nbsp;</td>
   <td align="right">&nbsp;</td>
   <td align="right"><strong>Pendentes</strong></td>
   <td style="color:#C30; font-size:14px;">US$ <?php echo number_format($total_pendente,2,',','.')?></td>
  </tr> 
  
  
   <tr>
   <td align="right">&nbsp;</td>
   <td align="right">&nbsp;</td>
   <td align="right">&nbsp;</td>
   <td align="right"><strong>Depositados</strong></td>
   <td style="font-size:14px;">US$ <?php echo number_format($total_pago,2,',','.')?></td>
  </tr>

   <tr>
   <td align="right">&nbsp;</td>
   <td align="right">&nbsp;</td>
   <td align="right">&nbsp;</td>
   <td align="right"><strong>Total</strong></td>
   <td style="font-size:14px;">US$ <?php echo number_format($total_pago+$total_pendente,2,',','.')?></td>
  </tr> 
  
 </tbody>
</table>

</div>
</div>

<div id="myModal" class="modal hide fade">
    <form action="<?php echo base_url('index.php/deposito/extorno');?>" method="post"  name="form-modal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Confirmação de Estorno</h3>
  </div>
  <div class="modal-body">
     <div class="alert" style="margin: 0;">
    <p>
    Ao realizar um Extorno, todo o valor debitado da conta do usuário é devolvido.
    Para realiazar o Extorno é necessário a confirmação da senha.
     <input type="password" name="senha" id="senha" class="span2 validate[required]" placeholder="Senha" value="">
     <input type="hidden" name="cdp_id" id="cdp_id" value="">
       </p>
     </div>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" onclick="closeModal();">Cancela</a>
    <button class="btn btn-primary" type="submit">Confirmar</button>
</div>
</form>
</div>
<style>

.relatorios a{
	color:#09C;
	}

.marca td,.marca td a {
	color:#090 !important;
	}
</style>
<script type="text/javascript">

 function modal(id){
	 $('#myModal').modal('show');
	 $('#cdp_id').val(id);
 }
 function closeModal()
 {
	 $('#myModal').modal('hide');
	 $('#cdp_id').val('');
	 $('#senha').val('');
 }

</script>