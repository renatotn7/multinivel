<div class="box-content min-height">
 <div class="box-content-header">Custo Frete por Estado em Dólar US$</div>
 <div class="box-content-body">
 


 <a class="btn btn-success" style="display:none;" href="<?php echo base_url('index.php/valor_pl/novo_pl')?>">Adicionar Valor PL</a>
<br />
<br />

 <table class="table table-hover table-bordered" width="50%" border="0" cellspacing="0" cellpadding="5">
 <thead>
  <tr>
    <th width="20%"><strong>Estado</strong></th>
    <th width="15%"><strong>Valor do Frete</strong></th>
    <th width="0%"><strong>Atualização de Valor em Dólar US$</strong></th>
  </tr>
  </thead>
 
 <?php foreach($custo_frete as $f){?>
   <tr>
    <td width="20%"><?php echo $f->cp_estado." - ".$f->es_nome?></td>
    <td width="15%">US$ <?php echo $f->cp_valor_frete?></td>
    <td width="0%">
     <form action="<?php echo base_url('index.php/custo_frete/atualizar_frete');?>" style="margin:0;padding:0;" method="post">
      <input type="text" class="moeda" style="width:100px;" name="cp_valor_frete">
      <input type="hidden" value="<?php echo $f->cp_id?>" name="cp_id">
      <input type="hidden" value="<?php echo $f->es_nome?>" name="estado">
      <input type="submit" class="btn" style="margin-top:-10px;" value="Atualizar">
     </form>
    </td>
  </tr>
<?php }?>

</table>

</div>
</div>

