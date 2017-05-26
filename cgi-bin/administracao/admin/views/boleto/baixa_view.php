<div class="box-content min-height">
 <div class="box-content-header">Boletos</div>
 <div class="box-content-body">

<form action="<?php echo base_url('index.php/boleto/baixar_itau')?>" class="well" method="post" enctype="multipart/form-data" name="formulario" id="enviar">

  <label>

   Informe o arquivo:<br />

    <input name="baixar_boletos" type="hidden" id="baixa_boletos" value="sim">

    <input type="file" name="arquivo" id="arquivo" />

  </label>

  <input type="submit"  class="btn btn-success" value="Atulizar Boletos" />

</form>






<?php if(count($boletos_atualizados)>=1){?>

<div>Os seguintes boletos foram atualizados</div>

<table width="100%" border="0" class="table table-bordered" cellspacing="0" cellpadding="0">

  <tr>

    <th>Número do boleto:</th>

    <th>Data atualização:</th>

    <th>Valor:</th>

  </tr>

<?php 

foreach($boletos_atualizados as $b){

?>

  <tr>

    <td><?php echo $b['compra'];?></td>

    <td><?php echo $b['nome'].' / '.$b['usuario']?></td>
    <td><?php echo $b['valor']?></td>

    <td>R$ <?php echo number_format($b['valor'],2,",",".");?></td>

  </tr>





<?php }?>



</table>

<?php }?>

</div>
</div>

