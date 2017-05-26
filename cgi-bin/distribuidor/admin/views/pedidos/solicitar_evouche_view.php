<div class="box-content min-height border-radios">
	<div class="box-content-header">
		<a href="<?php echo base_url() ?>"><?php echo $this->lang->line('label_titulo'); ?></a>

	</div>
	<div class="box-content-body">
	<div class="alert">
	 <h4>e-voucher</h4>
	 <p>E-voucher é um vale compra que após ser adquirido pode ser usado no site da Nossa Empresa.</p>
	</div>
		<form name="form" method="post" action="<?php echo base_url("index.php/pedidos/finalizar_comprar_voucher");?>">
			<div class="row">
				<div class="span4" style="margin-left: 31px;">
				 <label><strong>US$:</strong>
					<select name="evoucher" id="evoucher">
					 <option value='0.00'>Selecione um valor</option>
					 <option value="100.00">100.00</option>
					 <option value="200.00">200.00</option>
					 <option value="400.00">400.00</option>
					 <option value="800.00">800.00</option>
					 <option value="1600.00">1600.00</option>
					</select>
						</label>
				</div>
			</div>
			<div class="row">
				<div class="span4">
				 <label class="alert">Senha de segurança:
					<input type="password" name="senha" id="senha"
						placeholder="senha de segurança" class="span3">
						</label>
				</div>
			</div>
			<div class="row">
				<div class="span2">
			   
					<button type="submit" class="btn btn-success">Realizar compra.</button>
					
				</div>
			</div>
		</form>
	</div>
	<table class="table table-bordered table-hover">
	<thead>
 	   <tr>
 	    
 	     <th>Valor</th>
 	     <th>Situação</th>
 	     <th>e-Voucher</th>
 	     <th>Data</th>
 	     <th></th>
 	   </tr>
    </thead>
    <?php
  $evoucheres = $this->db
				  ->where('co_evoucher',1)
				  ->where('co_id_distribuidor',get_user()->di_id)
				  ->join('compras_voucher','vo_id_compra=co_id','left')
				  ->get('compras')->result();
  
    foreach ($evoucheres as $evoucher){?>
    <tr>
    <td><?php echo $evoucher->co_total_valor;?></td>
    <td>
    <?php
		    if( $evoucher->co_pago==1){
		       echo !empty($evoucher->status) && $evoucher->status==1?"Não Disponível" :'Disponível';
		    }
    ?>
    </td>
    <td style="text-align: center"><?php echo !empty($evoucher->vo_codigo)?$evoucher->vo_codigo :'<img src="'.base_url().'/public/imagem/e-voucher-pendente.jpg"/>';?></td>

    <td><?php echo date('d/m/Y  H:i:s',strtotime($evoucher->co_data_insert));?></td>
    <td>
    <?php if( $evoucher->co_pago==0){?>
    <a class="btn btn-primary" href="<?php echo base_url("index.php/pedidos/confirmar_pagamento?id_pedido=".$evoucher->co_id);?>">Pagar</a> </td>
    <?php }?>
    </tr>
    <?php }?>
	</table>
</div>
<script type="text/javascript">
$(function(){
	 $(".moeda").maskMoney({symbol:"R$",decimal:",",thousands:"."});
})(jQuery);
</script>