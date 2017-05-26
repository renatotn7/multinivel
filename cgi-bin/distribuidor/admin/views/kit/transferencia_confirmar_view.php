<?php 
$dis = $this->db->where('di_id',$_GET['ni_escolhido'])->get('distribuidores')->result();
?>

<h1>Transferência de Kit - Confirmar</h1>

<div class="painel">

<h2>KIT WORK VIP #<?php echo $_GET['kit']?></h2>
     <?php	
	$kit = $this->db
	->select(array('pr_nome','pr_codigo'))
	->join('produtos','pk_produto=pr_id')
	->where('pk_kit_comprado',$_GET['kit'])->get('produtos_kit_opcoes')->result();
	foreach($kit as $op){
	?>
    <span style="font-size:10px; text-transform:uppercase;"> &not; <?php echo $op->pr_codigo?> - <?php echo $op->pr_nome?></span><br />
    <?php }?>
    
    

<h2>Para: <br /><span style="color:#f00"><?php echo $dis[0]->di_nome?> / <?php echo $dis[0]->di_id?></span></h2>

<div class="botaos">

<a onClick="return confirm('ATENÇÃO: Essa operação e inreversivel.\n\nDeseja continuar?')" 
href="<?php echo base_url()."index.php/kit/transferencia_finalizar?kit={$_GET['kit']}&ni={$_GET['ni_escolhido']}"?>">SIM, CONFIRMAR</a>
<a onClick="return confirm('Deseja refazer a operação?')" href="<?php echo base_url().'index.php/kit/transferencia_escolher_kit'?>">NÃO, REFAZER OPERAÇÃO</a>
</div>
</div>


<style>

.botaos a{
	display:inline-block;
	margin:5px;
	padding:10px 15px;
	background:#f0f0f0;
	border:1px solid #ccc;
	font-size:20px;
	text-decoration:none;
	font-weight:bold;
	}

.table {
  border-right-color:#CDDDDD;
  border-right-style:solid;
  border-right-width:1px;
  border-top-color:#CDDDDD;
  border-top-style:solid;
  border-top-width:1px;
  margin-bottom:20px;
  width:100%;
}
.title td {
  background-color:#E7EFEF;
  background-position:initial initial;
  background-repeat:initial initial;
}
.table th, .table td {
  border-bottom-color:#CDDDDD;
  border-bottom-style:solid;
  border-bottom-width:1px;
  border-left-color:#CDDDDD;
  border-left-style:solid;
  border-left-width:1px;
  padding:5px;
  vertical-align:text-bottom;
}
</style>