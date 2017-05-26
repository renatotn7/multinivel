<h1>Transferência de Bônus - Escolha o distribuidor</h1>

<form action="<?php echo base_url('index.php/bonus/transferir_informar_valor')?>" onSubmit="return valida()" autocomplete='off' method="GET" name="formulario">
<h2>Para quem será Transferido o Bônus?</h2>
<h2>Informe o Login(Username)</h2>
<h2>A transferência será feita do seu Saldo de Ativação para o Saldo de Ativação informado.</h2>
<h2>O Saldo de Ativação é exclusivo para ATIVAÇÃO de usuários e pagamento de upgrades.</h2>
<h2>O Valor Mínimo para transferência é de US$ 100,00.</h2>
<h2>Será cobrada taxa de US$ 5,00 para cada transferência.</h2>
<h2>Esta operação é irreversível.</h2>
<input type="text" style="width:400px;" value="" name="di_nome" />
<input type="button" onClick="buscar()" class="botao" value="Procurar">

<input type="hidden" name="ni_escolhido" value="0" />
<input type="hidden" name="para" value="1" />

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
	
	
	var html_confirm = "<h4>O Bônus será transferido para:</h4>";
	html_confirm += "<h2>"+nome+" / "+id+"</h2>";
	
	html_confirm +="<input class='botao btn_verde' type='submit' value='Sim, Continuar!' />";
	$("#recebe-busca").html(html_confirm);
	}




 function buscar(){
	   txt_busca = $('input[name="di_nome"]').val();
	  
		
	   $("#recebe-busca").html("<img src='<?php echo base_url()?>public/script/tree/css/images/ajax-loader.gif' /> buscando resultados...");;	 
		   
	   $.ajax({
		   url:'<?php echo base_url('index.php/distribuidor/buscar_distribuidor_ajax')?>/',
		   type:"POST",
		   data:{chave:txt_busca},
		   dataType:'json',
		   success:function(dataJson){
			   
			   if(dataJson.length==0){
				   $("#recebe-busca").html('Nenhum resultado para '+txt_busca);
				   return false;}
			   
			   var html_dis = "";
			    $.each(dataJson,function(index,dis){
					 
					html_dis += "<p onclick='marcar_escolhido("+dis.di_id+",\""+dis.di_usuario+"\")'>"+dis.di_usuario+"/"+dis.di_id+"</p>"; 
					 
					});
			    $("#recebe-busca").html(html_dis);		
			   }
		   });
		   
	   
	   
	 }
</script>

<style>
#recebe-busca{
	width:511px;
	height:300px;
	overflow:auto;
	}
#recebe-busca h2{
	color:#f00;
	}	
#recebe-busca p:hover{
	background:#f0f0f0;
	}	
#recebe-busca p{cursor:pointer; border-bottom:1px solid #f3f3f3;
padding:2px;
margin:0;
font-size:17px;
font-weight:bold;
color:#069;
}

</style>