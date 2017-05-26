<h1>Transferência de Kit - Escolha o KIT</h1>

<h2>Escolha o KIT</h2>
<div>
<form action="<?php echo base_url('index.php/kit/transferencia_escolher_distribuidor')?>" method="get">
   <div style="max-height:300px; overflow:auto;">
   
   <table width="100%" id="table-listagem" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="2%"></td>
        <td width="12%">Nº</td>
        <td width="32%">Nome</td>
        <td width="32%">Valor</td>
        <td width="54%">Data</td>
      </tr>
  

   
    <?php 
	$kits = $this->db
	->select(array('co_id','pm_id','co_total_valor','co_data_compra','pr_nome'))
	->join('compras','co_id=pm_id_compra')
	->join('produtos','pr_id = pm_id_produto')
	->where('pr_kit',1)
	->where('co_pago',1)
	->where('co_id_distribuidor',get_user()->di_id)
	->get('produtos_comprados')->result();
	
	if(count($kits)>1){
	
	foreach($kits as $k){
	?>
     <tr>
        <td><input type="radio" name="kit" value="<?php echo $k->pm_id?>" /></td>
        <td><?php echo $k->pm_id?></td>
        <td><?php echo $k->pr_nome?><br />
         <?php	
	$kit = $this->db
	->select(array('pr_nome','pr_codigo'))
	->join('produtos','pk_produto=pr_id')
	->where('pk_kit_comprado',$k->pm_id)->get('produtos_kit_opcoes')->result();
	foreach($kit as $op){
	?>
    <span style="font-size:10px; text-transform:uppercase;"> &not; <?php echo $op->pr_codigo?> - <?php echo $op->pr_nome?></span><br />
    <?php }?>
    
        </td>
        <td><?php echo number_format($k->co_total_valor,2,',','.')?></td>
        <td><?php echo date('d/m/Y',strtotime($k->co_data_compra))?></td>
      </tr>
    <?php }}?>
  
  </table>
   </div>
     <div class="buttons">
    <input type="submit" class="botao btn_verde" onClick="return confirm('Deseja realmente transferir o KIT selecionado?')" value="TRANFERIR KIT" />
    <a class="botao" href="javascript:history.go(-1)">Cancelar</a>
    </div> 
     
 </form>
</div>