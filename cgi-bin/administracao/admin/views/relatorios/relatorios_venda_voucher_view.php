<div class="box-content min-height">
	<div class="box-content-header">Relatório de indicações</div>
	<div class="box-content-body">
		<form name="form" method="get" action="">
			<div class="row">
				<div class="span2">
					<label> Usuário:</label>
				    <?php echo CHtml::textInput('di_usuario',array('class'=>'span2'));?>
				</div>
				<div class="span2">
					<label> CPF:</label>
				    <?php echo CHtml::textInput('di_cpf',array('class'=>'span2'));?>
				</div>
				<div class="span1">
				<label> De:</label>
				    <?php echo CHtml::textInput('de',array('class'=>'span1'));?>
				    </div>
				   <div class="span1">
				 <label> Até:</label>
				    <?php echo CHtml::textInput('ate',array('class'=>'span1'));?>
				</div>
				<div class="span3">
					<label>Situação da comprar:</label>
				 <?php echo CHtml::dropdow('co_pago',
							array(0=>'NãoPagos',1=>'Pagos'),
							 array('empty'=>'--Selecione--'))?>
				</div>
				<div class="span3">
					<label>Situação do e-voucher:</label>
				 <?php echo CHtml::dropdow('status',
							array(0=>'Disponível',1=>'Usado'),
							 array('empty'=>'--Selecione--',"class"=>'span3'))?>
				</div>
			</div>
			<div class="row">
			  <div class="span2">
			   <button type="submit" class="btn btn-primary">Buscar <i class="icon-search  icon-white"></i></button>
			  </div>
			</div>
		</form>

				<table class="table table-hover table-bordered" width="100%">
					<thead>
						<tr>
							<th>Usuário</th>
							<th>CPF</th>
							<th>Valor</th>
							<th>Situacao da comprar</th>
							<th>Situaçao do Voucher</th>
							<th>Data</th>
						</tr>
					</thead>
					<?php foreach ($vouchers as $voucher){?>
					<tr>
						<td><?php echo $voucher->di_usuario;?></td>
						<td><?php echo $voucher->di_cpf;?></td>
						<td><?php echo $voucher->co_total_valor;?></td>
						<td><?php echo $voucher->co_pago==0?"Pendente (Aguardando Pagamento)":'Sem pendência';?></td>
						<td><?php echo $voucher->status==0?"Disponpível":'Usado';?></td>
						<td><?php echo date('d/m/Y H:i:s',strtotime( $voucher->co_data_compra));?></td>
					</tr>
					<?php }?>
				</table>
	</div>
</div>