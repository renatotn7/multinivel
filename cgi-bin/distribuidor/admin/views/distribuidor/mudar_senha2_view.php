<?php 
$this->lang->load ( 'distribuidor/distribuidor/mudar_senha2_view');
?>
<form name="formulario" method="post" action="">
  <div class="alert alert-info">
  <?php echo  $this->lang->line('notification_nota');?>
  </div>
    
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="334" align="right"><strong><?php echo $this->lang->line('label_senha_atual');?>:</strong></td>
    <td width="860"><input type="password" name="senha" /></td>
  </tr>
  <tr>
    <td align="right"><strong><?php echo $this->lang->line('label_senha_nova');?>:</strong></td>
    <td><input type="password" id="senha" class="validate[required,minSize[6]]" name="new_senha" /></td>
  </tr>
  <tr>
    <td align="right"><strong><?php echo $this->lang->line('label_repetir_senha');?>:</strong></td>
    <td><input type="password" class="validate[required,equals[senha],minSize[6]]" /></td>
  </tr>
  
  <tr>
    <td></td>
    <td><input type="submit" class="btn btn-success" value="<?php echo $this->lang->line('label_salvar');?>" />
    </td>
  </tr>  

  <tr>
    <td align="right"></td>
    <td><a href="<?php echo base_url('index.php/distribuidor/pedir_senha_seguranca')?>"><?php echo $this->lang->line('label_esqueceu_senha');?></a></td>
  </tr>  
  
</table>


</form>
