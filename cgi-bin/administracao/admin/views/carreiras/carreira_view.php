<div class="box-content min-height">
	<div class="box-content-header">Planos de Carreira</div>
	<div class="box-content-body">
  <form action="<?php echo base_url('index.php/carreiras/salvar');?>" method="post">
		<ul id="myTab" class="nav nav-tabs">
                    <?php foreach ($planos as $key => $plano){?>
			<li <?php echo $key==0?'class="active"':'';?>><a href="#<?php echo $key;?>" data-toggle="tab"><?php echo $plano->dq_descricao;?></a></li>
	            <?php }?>
		</ul>

		<div id="myTabContent" class="tab-content">
		<?php foreach ($planos as $key => $plano){?>
		<input type="hidden" name="dq_id[]" value="<?php echo $plano->dq_id;?>"/>
			<div class="tab-pane fade in <?php echo $key==0?'active':'';?> " id="<?php echo $key?>">
                            <div class="row">
                                <div class="span6">
				 Nome da Carreira: 
			         <input type="text" name="dq_descricao_<?php echo $key;?>" value="<?php echo $plano->dq_descricao;?>" class="span6 " />
                                </div>
                        </div>
				<div class="row">
					<div class="span3">
						Quantidade de Pontos: 
						<input type="text" name="dq_pontos_<?php echo $key;?>" value="<?php echo $plano->dq_pontos;?>"
							class="span3" />
					</div>
					<div class="span3">
						Premiação(U$):
						 <input type="text"
							name="dq_premiacoes_<?php echo $key;?>" value="<?php echo $plano->dq_premiacoes;?>" class="span3 moeda" />
					</div>
					<div class="span3">
						Níveis:
						 <input type="text"
							name="dq_niveis_<?php echo $key;?>" value="<?php echo $plano->dq_niveis;?>" class="span3 " />
					</div>
			        </div>				
			</div>
			<?php }?>
						
		</div>
		
		<button class="btn" type="submit">Salvar</button>
   </form>
	</div>
</div>