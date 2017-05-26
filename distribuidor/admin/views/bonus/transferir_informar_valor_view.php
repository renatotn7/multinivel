<h2>Informe o valor a ser transferido para:</h2>
<?php 

$saldo = $this->db->query("
SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
WHERE cb_distribuidor = ".get_user()->di_id."
")->result();

if($_GET['para']==1){
$dis = $this->db->where('di_id',$_GET['ni_escolhido'])->get('distribuidores')->result();
?>

<h2 style="color:#f00;"><?php echo $dis[0]->di_nome?>/<?php echo $dis[0]->di_id?></h2>
<?php 
}else if($_GET['para']==2){

$cd = $this->db->where('cd_id',get_parameter('cd_escolhido'))->get('cd')->result()
	
?>

<h2 style="color:#f00;"><?php echo $cd[0]->cd_nome?>/<?php echo $cd[0]->cd_id?></h2>

<?php }?>

<form method="post" action="<?php echo base_url('index.php/bonus/transferir_finalizar')?>" name="formulario">
<p>Saldo disponível: <strong style="color:#069">R$ <?php echo number_format($saldo[0]->saldo,2,',','.')?></strong></p>


<input type="hidden" name="ni_escolhido" value="<?php echo get_parameter('ni_escolhido')?>" />
<input type="hidden" name="cd_escolhido" value="<?php echo get_parameter('cd_escolhido')?>" />
<input type="hidden" name="para" value="<?php echo get_parameter('para')?>" />

 <input type="text" value="0.0" name="valor" class="moeda" />
 <input type="submit" class="botao" onClick="Essa operação não tem volta.\n\n Deseja realmente transferir?" value="Finalizar, Transferir valor" />
</form>
