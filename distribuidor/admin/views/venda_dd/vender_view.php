<div class="box-content min-height" style="background:#FFF;">
 <div class="box-content-header">Vender produtos DD</div>
 <div class="box-content-body">
  
  <div style="font-size:20px;">
  <h5>Comprador</h5>
   <div style="color:#069"><b>Usuário:</b> <?php echo $comprador->di_usuario?></div>
   <div style="color:#069"><b>Nome:</b> <?php echo $comprador->di_nome?></div>
   
  </div>
  
  <hr>
  
  
<form name="form1" method="post" action="<?php echo base_url('index.php/venda_dd/finalizar_venda')?>">
<input type="hidden" name="di_id" value="<?php echo $comprador->di_id?>" />
<?php 
 $produtos = $this->db
 ->select(array('pr_codigo','pr_nome','pm_id','SUM(pm_quantidade) as estoque','pm_valor','pm_pontos','pm_id_produto'))
 ->join('produtos','pr_id=pm_id_produto')
 ->join('compras','co_id=pm_id_compra')
 ->where('co_e_dd',1)
 ->where('co_pago',1)
 ->having('SUM(pm_quantidade) >',0)
 ->group_by('pm_id_produto')
 ->get('produtos_comprados')->result();
?>  
  
Escolha os produtos da venda:
<table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15%" bgcolor="#f3f3f3">Código</td>
        <td width="50%" bgcolor="#f3f3f3">Descrição</td>
        <td width="7%" bgcolor="#f3f3f3">Estoque</td>
        <td width="7%" bgcolor="#f3f3f3">Valor</td>
        <td width="28%" bgcolor="#f3f3f3">Qtd. Venda</td>
      </tr>
      <?php foreach($produtos as $p){?>
      <tr>
        <td valign="middle"><?php echo $p->pr_codigo?></td>
        <td valign="middle"><?php echo $p->pr_nome?></td>
        <td valign="middle"><?php echo $p->estoque?></td>
        <td valign="middle"><?php echo number_format($p->pm_valor,2,',','.')?></td>
        <td>
        <select name="produtos[<?php echo $p->pm_id?>]" style="margin:0; width:80px;">
          <?php for($qtd=0;$qtd<=$p->estoque;$qtd++){?>
            <option value="<?php echo $qtd?>"><?php echo $qtd?></option>
          <?php }?>
        </select>
        </td>
      </tr>
      <?php }?>
   </table>
  <?php 
   if(count($produtos)>0){
  ?>
  Informe sua senha de segurança:<br>
  <input type="password" name="senha_segurancao" value="" />
 <br>
<br>

 <input type="submit" class="btn btn-success btn-large" value="Confirmar pedido">
 <?php }?>
 
 <a class="btn" href="<?php echo base_url()?>">Cancelar pedido</a>
 
</form> 
   
 </div>
 </div>