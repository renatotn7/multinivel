<div class="box-content min-height">
 <div class="box-content-header"><a href="<?php echo base_url('index.php/valor_pl')?>">Valor da PL</a> &raquo; Adicionar valor da PL</div>
 <div class="box-content-body">

<form name="formulario" method="post" action="<?php echo base_url('index.php/valor_pl/editar_pl/'.$this->uri->segment(3));?>">

<table width="100%" border="0" cellspacing="0" cellpadding="7">

  <tr>
      <td width="153"><label>Percentual a pagar da PL:</label></td>
    <td width="1016">
    <input type="text" name="rpl_percentual" class="validate[required]" value="<?php echo $pl->valor;?>" size="50"  />    
    </td>
  </tr>

  <tr>
   <td>

    <input type="submit" value="Atualizar" class="btn btn-primary">
      
   </td>
  </tr>            
</table>

</form>

</div>
</div>
