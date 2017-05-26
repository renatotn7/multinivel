<?php $this->lang->load('distribuidor/distribuidor/pendentes_view');?>
<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3><?php echo $this->lang->line('label_cadastro_pendentes'); ?></h3>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<table class="table table-bordered table-hover">
						<thead>
							<tr bgcolor="#f7f7f7">
								<th><?php echo $this->lang->line('label_nome');?></th>
								<th><?php echo $this->lang->line('label_perna');?></th>
								<th><?php echo $this->lang->line('label_email');?></th>
								<th><?php echo $this->lang->line('label_telefone');?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($dados as $d){ ?>
							<tr>
								<td><?php echo $d->di_nome?> (<?php echo $d->di_usuario?>)</td>
								<td>
									<form action="<?php echo base_url('index.php/distribuidor/perna_inserir_pendentes')?>" method="post" class="form-inline">
										<input type="hidden" name="di_id" value="<?php echo $d->di_id?>" />
										<select onchange="show_senha_seguranca()" name="perna" class="form-control">
											<option <?php echo $d->di_preferencia_indicador==0?'selected':''?> value="0"><?php echo $this->lang->line('label_preferencial');?></option>
											<option <?php echo $d->di_preferencia_indicador==1?'selected':''?> value="1"><?php echo $this->lang->line('label_esquerda');?></option>
											<option <?php echo $d->di_preferencia_indicador==2?'selected':''?> value="2"><?php echo $this->lang->line('label_direita');?></option>
											<option <?php echo $d->di_preferencia_indicador==3?'selected':''?> value="3"><?php echo $this->lang->line('label_nenor');?></option>
										</select>
										<input type="password" name="senha_segurancao" placeholder="<?php echo $this->lang->line('label_senha_seguranca');?>" class="form-control"/>
										<input type="hidden" name="url" value="<?php echo current_url() ?>" />
										<button class="btn" type="submit"><?php echo $this->lang->line('label_salvar');?></button>
									</form>
								</td>
								<td><?php echo $d->di_email?></td>
								<td><?php echo $d->di_fone1?></td>
							</tr>
							<?php } ?>
						</tbody>
						<?php if(count($dados)==0){ ?>
						<tfoot>
							<tr>
								<td colspan="100%"><?php echo $this->lang->line('label_nenhum_cadastro_pendentes');?></td>
							</tr>
						</tfoot>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>