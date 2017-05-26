<?php $this->lang->load('distribuidor/bonus/por_categoria_view');?>
<?php
$saldo = $this->db->query("
		SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
		WHERE cb_distribuidor = ".get_user()->di_id."
	")->result();

$de = data_to_usa((get_parameter('de')?get_parameter('de'):date('d/m/Y')));
$ate = data_to_usa((get_parameter('ate')?get_parameter('ate'):date('d/m/Y')));
?>
<form id="form1" name="form1" method="get" action="" class="form-inline">
	<div class="form-group">
		<label for="de"><?php echo $this->lang->line('label_de');?>:</label>
		<input type="text" class="mdata form-control" value="<?php echo date('d/m/Y',strtotime($de))?>" name="de" id="de" />
	</div>
	<div class="form-group">
		<label for="ate"><?php echo $this->lang->line('label_ate');?>:</label>
		<input type="text" class="mdata form-control" value="<?php echo date('d/m/Y',strtotime($ate))?>" name="ate" id="ate" />
	</div>
	<input type="submit" class="btn btn-default" value="<?php echo $this->lang->line('label_filtrar');?>" />
</form>

<h2>
	<div class="label label-info">
		<?php echo $this->lang->line('label_saldo_atual');?> <?php echo number_format($saldo[0]->saldo,2,',','.')?>
	</div>
</h2>

<table class="table table-bordered table-hover">
	<?php
	$valorTotal=0.0;
	$btipo = $this->db->get('bonus_tipo')->result();
	foreach($btipo as $b){
	?>
	<tr>
		<td><?php echo $b->tb_descricao?></td>
		<td><?php
		if($b->tb_id!=9){

			$bonus = $this->db
			->select('SUM(cb_credito) as pt')
			->where('cb_tipo',$b->tb_id)
			->where('cb_data_hora >=',$de.' 00:00:00')
			->where('cb_data_hora <=',$ate.' 23:59:59')
			->where('cb_distribuidor',get_user()->di_id)
			->get('conta_bonus')->row();
			echo number_format($bonus->pt,2,',','.');
			$valorTotal+=$bonus->pt;
		}else{
			echo get_user()->dq_descricao;
		}
		?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td align="right"><strong>TOTAL</strong></td>
		<td>US$ <?php echo number_format($valorTotal,2,',','.'); ?></td>
	</tr>
</table>
