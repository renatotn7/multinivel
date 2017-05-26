<?php 
	$registroAtivacao = $this->db->where('distribuidor_auto',get_user()->di_id)->get('registro_auto_ativacao')->row();
	$quant = count($registroAtivacao);
	?>
<?php if($quant == 0){?>
<div class="box-content min-height">
	<div class="box-content-header">Auto Ativação</div>
	<div class="alert alert-warning">
		<h4>O recurso de auto-ativação esta desabilitado</h4>
		Ative agora mesmo o recurso de <a href="#">auto-ativação</a> 
	</div>
	<div class="alert alert-info">
		<p style="max-width:80%; text-align:center;">A auto-ativação lhe permite a comodidade de se iniciar todos os meses já ativo,	de forma automática todo dia 01 do mês seguinte ao pagamento do bônus 
	</div>
	<div class="box-content-body">
		<b>Requisitos para auto-ativação</b> <br />
		<br />
		<ul class="lista_com_efeito">
			<li>Escolher a opção de <a href="#">auto-ativação</a></li>
			<li>Escolher os produtos que irão compor sua auto-ativação</li>
			<li>Ter em bônus a quantia necessaria dos produtos</li>
		</ul>
		<a id="ativar_auto" class="btn btn-large btn-success">Ativar a Auto-Ativação</a>
		<div id="recebe_load" style="display:none">
			<?php
				$data['registroAtivacao'] = $registroAtivacao;
				$this->load->view("ativacao/opcoes_combo_auto_ativacao", $data);
				?>
		</div>
	</div>
</div>
</div>
</div>
<?php }elseif($quant = 1){?>
<div class="box-content min-height">
	<div class="box-content-header">Auto Ativação</div>
	<div class="box-content-body">
		<div class="alert alert-success">
			<h4>O recurso de auto-ativação esta habilitado</h4>
			Confira abaixo o status de sua auto-ativação 
		</div>
		<div class="alert alert-info">
			<p style="max-width:80%; text-align:center;">A auto-ativação lhe permite a comodidade de se iniciar todos os meses já ativo,	de forma automática todo dia 01 do mês seguinte ao pagamento do bônus </p>
		</div>
		<div style="width:100%; height:50px;">
			<form method="post" action="<?php echo base_url('index.php/ativacao/desativar_auto_ativacao/')?>">
				<input id="auto_atv_id" name="registroAtivacao" type="hidden" value="<?php echo ($registroAtivacao->auto_atv_id);?>"/>
				<button type="submit" class="btn btn-large btn-danger">Desativar a Auto-Ativação</button>
			</form>
		</div>
		<?php 
			$data['registroAtivacao'] = $registroAtivacao;
			$this->load->view("ativacao/opcoes_combo_auto_ativacao", $data);
			?>
	</div>
</div>
<?php }?>