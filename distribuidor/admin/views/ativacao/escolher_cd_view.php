<h2>Escolha o CD par retirar o produto</h2>

<table id="table-listagem" width="100%" border="0" cellspacing="0" cellpadding="5">
  <thead>
  <tr>
    <td width="8%"><strong>Código</strong></td>
    <td width="38%"><strong>Produto</strong></td>
    <td width="16%"><strong>Pontos</strong></td>
    <td width="16%"></td>
  </tr>
  </thead>
  <tbody>
  <?php 
  $cds = $this->db->where('cd_ativo',1)
  ->join('cidades','ci_id=cd_cidade')
  ->get('cd')->result();
	   
  foreach($cds  as $c){	   
  ?>
  
  <tr>
    <td><?php echo $c->cd_nome?></td>
    <td><?php echo $c->ci_nome."-".$c->ci_uf?></td>
    <td><?php echo $c->cd_fone1?></td>
    <td>
    <a class="botao" onClick="return confirm('Escolher o cd: <?php echo $c->cd_nome?> ?')" href="<?php echo base_url('index.php/ativacao/cd_escolhido?cd='.$c->cd_id)?>">Escolher este produto</a></td>
  </tr>  
  <?php }?>
  
  </tbody>
  </table>