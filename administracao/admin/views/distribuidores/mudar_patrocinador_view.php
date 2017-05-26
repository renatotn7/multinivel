<?php 
$dis = $this->db->where('di_id',$this->uri->segment(3))->get('distribuidores')->result();
?>
<form class="" autocomplete="off" onSubmit="return valida()" 
action="<?php echo base_url('index.php/distribuidores/mudar_patrocinador_confirm')?>" method="POST" name="">
<h2 style="color:#F00">MUDAR PATROCINADOR: <?php echo $dis[0]->di_nome?>/<?php echo $dis[0]->di_id?></h2>
<h3>Informe o NOME ou o NI</h3>
<input type="text" style="width:400px; padding:4px; outline:none;" value="" name="di_nome" />
<input type="button" onClick="buscar()" class="botao" value="Procurar">

<input type="hidden" name="ni_escolhido" value="0" />
<input type="hidden" name="di_id" value="<?php echo $this->uri->segment(3)?>" />

<div id="recebe-busca"></div>
</form>

<script type="text/javascript">

function valida(){
	 if($("input[name='ni_escolhido']").val()==0||$("input[name='ni_escolhido']").val()==''){
		  alert('Nenhum distribuidor selecionado!\n Busque o distribuidor pelo NI ou NOME');
		  $('input[name="di_nome"]').val('');
		  $('input[name="di_nome"]').focus();
		  $("#recebe-busca").html('');
		  return false;
		 }else{
			 return true;
			 }
	}

function marcar_escolhido(id,nome){
	$('input[name="di_nome"]').val(nome);
	$('input[name="ni_escolhido"]').val(id);
	
	
	var html_confirm = "<h4>O NOVO PATRICINADOR SERÁ:</h4>";
	html_confirm += "<h2>"+nome+" / "+id+"</h2>";
	
	html_confirm +="<input class='botao btn_verde' type='submit' value='Sim, Continuar!' />";
	$("#recebe-busca").html(html_confirm);
	}




 function buscar(){
	   txt_busca = $('input[name="di_nome"]').val();
	  
		
	   $("#recebe-busca").html("<img src='<?php echo base_url()?>public/script/tree/css/images/ajax-loader.gif' /> buscando resultados...");;	 
		   
	   $.ajax({
		   url:'<?php echo base_url('index.php/distribuidores/buscar_distribuidor_ajax')?>/',
		   type:"POST",
		   data:{chave:txt_busca},
		   dataType:'json',
		   success:function(dataJson){
			   
			   if(dataJson.length==0){
				   $("#recebe-busca").html('Nenhum resultado para '+txt_busca);
				   return false;}
			   
			   var html_dis = "";
			    $.each(dataJson,function(index,dis){
					 
					html_dis += "<p onclick='marcar_escolhido("+dis.di_id+",\""+dis.di_nome+"\")'>"+dis.di_nome+"/"+dis.di_id+"</p>"; 
					 
					});
			    $("#recebe-busca").html(html_dis);		
			   }
		   });
		   
	   
	   
	 }
</script>



<style>
#menu-cd a{
	display:block;
	width:100%;
	}
#recebe-busca{
	width:410px;
	height:300px;
	overflow:auto;
	}
#recebe-busca p:hover{
	background:#f0f0f0;
	font-size:20px;
	}	
#recebe-busca p{cursor:pointer; border-bottom:1px solid #f3f3f3;
font-size:20px;
padding:2px;
margin:0;
font-weight:bold;
color:#069;
}

</style>