<div class="box-content min-height">
 <div class="box-content-header">Vendas</div>
 <div class="box-content-body">
<br />
<ul class="breadcrumb">
  <li><a href="<?php echo base_url()?>">Home</a> <span class="divider">/</span></li>
  <li class="active"><?php echo $module_descricao?></li>
</ul>

<form id="form1" name="form1" method="get" action="">

<p class="label label-info">
  Data:
</p>
<p>
de:  
<input style="margin:0; width:90px;" type="text" class="mdata" size="10" value="<?php echo get_parameter('de')?>" name="de" />
até: <input style="margin:0;width:90px;" type="text" class="mdata" size="10" value="<?php echo get_parameter('ate')?>" name="ate" />
<p>Usuário</p>
<input type="text" name="usuario"/>
<p>Nome</p>
<input type="text" name="nome"/>
<br />
<input type="submit" class="btn btn-info" value="Filtrar" />
</p>
</form>

<form id="form1" name="form1" method="post" action="<?php echo base_url($modulo.'/config_filtro')?>">

<table class="table table-hover table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
  <thead>
  <tr class="title">
  <th width="26" valign="top">Nº</th>
  <th width="388" valign="top">Titulo</th>
  <th width="229" valign="top">Cidade</th>
  <th width="229" valign="top">Data</th>
  <?php if(permissao('verificar_conta','editar',get_user())){?>
  <th width="267" align="center" valign="top"></th>
  <?php }?>
  </tr>
  </thead>
  <tbody>
  <?php foreach($dados as $d){?> 
  <tr>
  <td><?php echo $d->di_usuario?></td>
  <td><?php echo $d->di_nome?></td>
  <td><?php echo $d->ci_nome?>-<?php echo $d->ci_uf?></td>
  <td><?php echo date('d/m/Y H:i',strtotime($d->do_data));?></td>
  
  <?php if(permissao('verificar_conta','editar',get_user())){?>
  <td>
   <a class="btn btn-info" href="<?php echo base_url('index.php/'.$modulo.'/editar/'.$d->di_id)?>">Ver documentos</a>
  </td>
  <?php }?>
  
  </tr>
  <?php }?>
  
  </tbody>
</table>

</form>

<?php echo $links?>

</div>
</div>
