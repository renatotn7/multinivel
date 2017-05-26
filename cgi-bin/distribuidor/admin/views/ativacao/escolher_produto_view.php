<h2>Ecolha o produto para auto-ativação</h2>

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
  $prod = $this->db
  ->where('pr_ativacao',1)
  ->get('produtos')->result();
	   
  foreach($prod as $p){	   
  ?>
  
  <tr>
    <td><?php echo $p->pr_codigo?></td>
    <td><?php echo $p->pr_nome?></td>
    <td><?php echo $p->pr_pontos?></td>
    <td>
    <a class="botao" href="<?php echo base_url('index.php/ativacao/produto_escolhido?pr_id='.$p->pr_id)?>">Escolher este produto</a></td>
  </tr>  
  <?php }?>
  
  </tbody>
  </table>
  