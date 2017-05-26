
<div class="box-content min-height">
	<div class="box-content-header">Depósitos</div>
	<div class="box-content-body">
	<?php count($distribuidores)?>
		<table class="table">
			<tr>
			   <th>Nº</th>
				<th>Usuário</th>
				<th>Pontos  Direita</th>
				<th>Pontos  Esquerda</th>
			</tr>
		<?php foreach ($distribuidores as $k=> $distribuidor){?>
			<tr>
			<td><?php echo  $k+1?></td>
			<td><?php echo  $distribuidor['di_usuario']?></td>
			<td><?php if($distribuidor['d'] ==1){echo $distribuidor['pontos'];}?></td>
			<td><?php if($distribuidor['e'] ==1){echo $distribuidor['pontos'];}?></td>
			</tr>
		<?php }?>
		</table>
	</div>
</div>
