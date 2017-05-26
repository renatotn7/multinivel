<div class="box-content min-height">
 <div class="box-content-header">Administrar valor da PL</div>
 <div class="box-content-body">
 

<?php if(permissao('valor_pl','adicionar',get_user())){?>
 <a class="btn btn-success" style="display:none;" href="<?php echo base_url('index.php/valor_pl/novo_pl')?>">Adicionar Valor PL</a>
<br />
<br />
<?php }?>


 <table class="table table-hover table-bordered" width="100%" border="0" cellspacing="0" cellpadding="5">
 <thead>
  <tr>
    <th width="20%"><strong>Descrição</strong></th>
    <th width="15%"><strong>% Percentual PL</strong></th>
    
    <th width="20%"></th>
   
  </tr>
  </thead>
  
  <tr>
    <th width="30%"><span>Persentual Bônus PL</span></th>
    <th width="15%"><span><?php echo $pl->valor?>%</span></th>
    <td align="center">
     <a href="<?php echo base_url('index.php/valor_pl/novo_pl/'.$pl->field)?>">editar</a>
    </td>
     
  </tr>

</table>

</div>
</div>

