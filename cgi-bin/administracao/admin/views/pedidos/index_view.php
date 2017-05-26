<div class="box-content min-height">
 <div class="box-content-header">Cadastros Pendentes</div>
 <div class="box-content-body">




<form name="formulario" method="get" action="">
 Usuário: <input type="text" name="usuario" style="margin:0;" />
  CPF: <input type="text" name="cpf" style="margin:0;" />
   Nome: <input type="text" name="nome" style="margin:0;" />
   <input type="submit" class="btn btn-info" value="Enviar" />
</form>
<table id="table-listagem" class="table table-hover table-bordered" width="100%" border="0" cellspacing="0" cellpadding="5">
  <thead>
  <tr>
    <td width="6%" bgcolor="#f7f7f7"><strong>Nº</strong></td>
    <td width="28%" bgcolor="#f7f7f7"><strong>Distribuidor/usuario</strong><br /></td>
    <td width="14%" bgcolor="#f7f7f7"><strong>Data</strong></td>
    <td width="16%" valign="top" bgcolor="#f7f7f7"><strong>Cidade-UF</strong></td>
    <td width="9%" valign="top" bgcolor="#f7f7f7"><strong>Valor</strong></td>
    <td width="27%" valign="bottom" bgcolor="#f7f7f7">&nbsp;</td>
  </tr>
  </thead>
  <tbody>
  <?php 

   foreach($pedidos as $c){ 
   $hoje = date('Y-m-d',strtotime($c->co_data_compra))==date('Y-m-d');  
   $entrouNaRede = $this->db->where('li_id_distribuidor',$c->di_id)->get('distribuidor_ligacao')->row();	   
   if(count($entrouNaRede)==0){
  ?>
  <tr>
    <td width="6%"><?php echo $c->co_id?></td>
    <td width="28%"><?php echo $c->di_nome." (".$c->di_usuario?>)</td>
    
    <td width="14%"><?php echo $hoje?"Hoje":date('d/m/Y',strtotime($c->co_data_compra));?></td>
    <td width="16%"><?php echo $c->ci_nome."-".$c->ci_uf?></td>
    <td width="9%">R$ <?php echo number_format($c->co_frete_valor+$c->co_total_valor,2,',','.')?></td>
    <td align="center">
    <?php if(permissao('cadastro_pendente','editar',get_user())){?>
    <a  class="btn btn-info" href="<?php echo base_url("index.php/pedidos/editar_pedido/$c->co_id")?>">Alterar pedido</a>
    <?php }?>
    <?php if(permissao('cadastro_pendente','excluir',get_user())){?>
    <a  class="btn btn-danger" 
    onclick="return confirm('Deseja Realmente Excluir?\nEssa operação não tem volta.')"
    href="<?php echo base_url("index.php/distribuidores/remover_distribuidor/$c->di_id")?>">Remover</a>
    <?php }?>
    
    </td>
  </tr>
  <?php }}?>
  </tbody>
 </table>
 

 <?php echo $links?>
 
 </div>
 </div>