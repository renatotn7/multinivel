<?php $this->lang->load('distribuidor/distribuidor/pendentes_view');?>
<div class="box-content min-height">
 <div class="box-content-header"><?php echo $this->lang->line('label_cadastro_pendentes');?></div>
 <div class="box-content-body"> 
 
 <table width="100%" class="table table-bordered table-hover" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="23%" bgcolor="#f7f7f7"><?php echo $this->lang->line('label_nome');?></td>
    <td width="10%" bgcolor="#f7f7f7"><?php echo $this->lang->line('label_perna');?></td>
    <td width="15%" bgcolor="#f7f7f7"><?php echo $this->lang->line('label_email');?></td>
    <td width="15%" bgcolor="#f7f7f7"><?php echo $this->lang->line('label_telefone');?></td>
  </tr>
  
  <?php   
    foreach($dados as $d){ 	  
  ?>
  
   <tr style="background:#fff">
    <td><?php echo $d->di_nome?> (<?php echo $d->di_usuario?>)</td>
    <td>
    
    <form action="<?php echo base_url('index.php/distribuidor/perna_inserir_pendentes')?>" style="margin:0;" method="post">
     <input type="hidden" name="di_id" value="<?php echo $d->di_id?>" />
     <select onchange="show_senha_seguranca()" style="margin:0; width:110px" name="perna">
      <option <?php echo $d->di_preferencia_indicador==0?'selected':''?> value="0"><?php echo $this->lang->line('label_preferencial');?></option>
      <option <?php echo $d->di_preferencia_indicador==1?'selected':''?> value="1"><?php echo $this->lang->line('label_esquerda');?></option>
      <option <?php echo $d->di_preferencia_indicador==2?'selected':''?> value="2"><?php echo $this->lang->line('label_direita');?></option>
      <option <?php echo $d->di_preferencia_indicador==3?'selected':''?> value="3"><?php echo $this->lang->line('label_nenor');?></option>
     </select>
    
     <input type="password" style="width:90px; margin:0;" name="senha_segurancao"  placeholder="<?php echo $this->lang->line('label_senha_seguranca');?>" />
     <input type="hidden" name="url" value="<?php echo current_url() ?>" /> 
     
     <button class="btn" type="submit"><?php echo $this->lang->line('label_salvar');?></button>
     
  </form>
    
    </td>
    <td> <?php echo $d->di_email ?></td>
    <td> <?php echo $d->di_fone1 ?></td>
  </tr>
  
  <?php }?>
  
  <?php if(count($dados)==0){?>
  
   <tr style="background:#fff">
     <td colspan="5"><?php echo $this->lang->line('label_nenhum_cadastro_pendentes');?></td>
   </tr>
   
  <?php }?>
  
</table>
 
 </div>
 </div>