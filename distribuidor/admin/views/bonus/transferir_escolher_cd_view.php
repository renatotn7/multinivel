<h2>Escolha o Centro de Distribuição(CD)</h2>

<form name="formulario" action="<?php echo base_url('index.php/bonus/transferir_informar_valor')?>" method="get">

<input type="hidden" name="para" value="2" />

<p>Buscar por UF:
<select name="uf" onChange="filtar_cd(this.value)">
<option value="">---</option>
<?php 
$uf = $this->db->get('estados')->result();
foreach($uf as $u){
?>
<option value="<?php echo $u->es_id?>"><?php echo $u->es_uf?></option>
<?php }?>

</select>
</p>

<p>
<select class="validate[required]" style="width:300px;" multiple="multiple" name="cd_escolhido">
<?php 
$cd = $this->db->get('cd')->result();
foreach($cd as $c){
?>
 <option value="<?php echo $c->cd_id?>"><?php echo $c->cd_nome?> - <?php echo $c->cd_responsavel_nome?></option>
<?php }?> 
</select>
</p>
<input type="submit" class="botao" value="Sim, Continuar" />
</form>

<script type="text/javascript">
 function filtar_cd(uf){
	 $("select[name='cd_escolhido']").html('<option value="">Carregando...</option>');
	 $.ajax({
	 url:'<?php echo base_url('index.php/estados/filtrar_cd_ajax')?>',
	 data:{estado:uf},
	 dataType:'html',
	 type:'POST',
	 success:function(dataHtml){
		 $("select[name='cd_escolhido']").html(dataHtml);
		 }
		 
		 });
	 }
</script>