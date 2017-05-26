<div class="box-content min-height">
 <div class="box-content-header">Notícias</div>
 <div class="box-content-body">


<div class="buttons">

 <a class="btn btn-success" href="<?php echo base_url('index.php/'.$modulo.'/add')?>"><i class="icon-plus-sign icon-white"></i> Adicionar</a>

</div>

<br />

<form id="form1" name="form1" method="post" action="<?php echo base_url($modulo.'/config_filtro')?>">

<table class="table table-hover table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
  <thead>
  <tr class="title">
  <th width="35" valign="top">Nº</th>
  <th width="693" valign="top">Titulo</th>
  <th width="117" align="center" valign="top">Situação</th>
  <th width="117" align="center" valign="top">Ordem</th>
  <th width="117" align="center" valign="top"></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($dados as $d){?> 
  <tr>
  <td><?php echo $d->{$pk}?></td>
  <td><?php echo $d->titulo?></td>
  <td>
  <div class="<?php echo $d->status==1?'label label-success':'label label-warning'?>">
  <?php echo $d->status==1?'Habilitado':'Desabilitado'?>
  </div>
  </td>
    <td>
  
   <a href="<?php echo base_url('index.php/'.$modulo.'/ordem/'.$d->{$pk}.'/?direcao='.($d->ordem+1))?>">
   + </a>
  <?php echo $d->ordem?> <a href="<?php echo base_url('index.php/'.$modulo.'/ordem/'.$d->{$pk}.'/?direcao='.($d->ordem-1))?>">
  -</a>
  </td>
  <td>
   <a class="btn btn-primary" href="<?php echo base_url('index.php/'.$modulo.'/editar/'.$d->{$pk})?>">Editar</a>
   <a class="btn btn-danger" href="<?php echo base_url('index.php/'.$modulo.'/excluir/'.$d->{$pk})?>">X</a>
  </td>
  </tr>
  <?php }?>
  
  </tbody>
</table>

</form>

<?php echo $links?>

</div>
</div>
