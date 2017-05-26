

 
 <div class="box-content min-height">
 <div class="box-content-header">Pedido de Produtos</div>
 <div class="box-content-body">   
   
    
  <p>Escolha um Centro de Distribuição(CD) <br />para realizar o pedido:</p>

 <form method="post" class="painel" name="formulario" action="">
 
  <div class="input-append">
   <select name="cd_escolhido" class="validate[required]">
   <option value="">--Selecione--</option>
    <?php 
	$cds = $this->db->where('cd_ativo',1)->join('cidades','ci_id=cd_cidade')->get('cd')->result();
	foreach($cds as $c){
	?>
     <option value="<?php echo $c->cd_id?>"><?php echo $c->cd_nome?> - <?php echo $c->ci_nome?>/<?php echo $c->ci_uf?></option>
    <?php }?>
   </select>
   <button type="submit" class="btn btn-primary">Continuar</button>
   </div>
 </form>   
   
   <div class="buttons">
 <a class="btn" href="javascript:history.go(-1)">Voltar</a>
</div> 
    
    </div>
    </div>


 


