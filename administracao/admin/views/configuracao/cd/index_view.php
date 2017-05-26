<div class="box-content min-height">
 <div class="box-content-header">CD(Centro de distribuição)</div>
 <div class="box-content-body">
 

<?php if(permissao('cds','adicionar',get_user())){?>
 <a class="btn btn-success" href="<?php echo base_url('index.php/cd/adicionar_cd')?>">Adicionar CD</a>
<br />
<br />
<?php }?>


 <table class="table table-hover table-bordered" width="100%" border="0" cellspacing="0" cellpadding="5">
 <thead>
  <tr>
    <th width="7%"><strong>NI</strong></th>
    <th width="33%"><strong>Nome/Empresa</strong></th>
    <th width="18%"><strong>Responsável legal</strong></th>
    <th width="12%"><strong>Telefone</strong></th>
    <th width="13%"><strong>Cidade-UF</strong></th>
    <th width="7%"><strong>Situação</strong></th>
    <?php if(permissao('cds','excluir',get_user())){?>
    <th width="10%"></th>
    <?php }?>
  </tr>
  </thead>
  <tbody>
  <?php 
  $prods = $this->db
  ->join('cidades','ci_id = cd_cidade')
  ->get('cd')->result();
  foreach($prods as $p){
  ?>
  
  <tr>
  <td><strong><?php echo $p->cd_id.($p->cd_suporte==1?'sup':'cdp')?></strong></td>
    <td>
    <?php if(permissao('cds','excluir',get_user())){?>
    <a href="<?php echo base_url('index.php/cd/editar_cd/'.$p->cd_id)?>"><?php echo $p->cd_nome?></a>
    <?php }else{?>
    <?php echo $p->cd_nome?>
    <?php }?>
    </td>
    <td><?php echo $p->cd_responsavel_nome?></td>
    <td><?php echo $p->cd_fone1?></td>
    <td><?php echo $p->ci_nome?> - <?php echo $p->ci_uf?></td>
    <td><?php echo $p->cd_ativo?"Ativo":"Inativo"?></td>
    <?php if(permissao('cds','excluir',get_user())){?>
    <td>
    <a href="<?php echo current_url()."?ex=".$p->cd_id?>" class="remover-icon">.</a>
    </td>
    <?php }?>
  </tr>
 <?php }?>
 </tbody> 
</table>

</div>
</div>

