<style>
#autocomplete {
	position: absolute;
	width: 425px;
	margin: -9px 0px;
	border: 1px solid #CCC;
	background: #FFF;
	z-index: 9999;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	display: none;
	}
	
	#autocomplete ul{
	list-style: none;
	}
	
	#autocomplete ul li{
	margin-left: -25px;
	padding-left: 6px;
	}
	
	#autocomplete ul li:hover{
	background: #ddd;
	}


</style>
<div class="box-content min-height">
	<div class="box-content-header">Relatório Transações</div>
	<div class="box-content-body">
		<form action="<?php echo base_url('index.php/relatorios/transacoes');?>" method="get">
			<div class="row">
                            <div class="span2">
                                Mostrar da rede:
                                <input type="checkbox" name="di_usuario"  <?php echo isset($_GET['di_usuario'])?'checked':'';?>  id="di_usuario" value="1"/>
                            </div>
				<div class ="span6">
				Nome do Distribuidor:
				<input type="hidden" name="di_id" id="di_id" value="<?php echo isset($_GET['di_id'])?$_GET['di_id']:'';?>">
				<input type="text" class="input-xxlarge" name="name" id="name" autocomplete="off" style="width: 413px" value="<?php echo isset($_GET['name'])?$_GET['name']:'';?>">
				<div id="autocomplete" >
				    <ul>
				      
					</ul>
				</div>
				</div>
				<div class="span3">
				    Buscar por Tipo:
	      <select id="cb_tipo" name="cb_tipo">
                    <option value="">Todos</option>
					<?php
					if($tipos){
					foreach ($tipos as $tipo){?>
					 <option <?php echo (isset($_GET['cb_tipo']) && $_GET['cb_tipo']==$tipo->cbt_id)?'selected':''?> value="<?php echo $tipo->cbt_id;?>"><?php echo $tipo->cbt_descricao;?></option>
					 <?php }
						 }
					 ?>
					</select>
			   </div>
			
			</div>
			<div  class="row">
				<div class="span3">
					 De:<br>
					 <div class=" input-append">
					   <input class="span2 data" id="de" name="de" type="text" value="<?php echo date('d/m/Y',strtotime($de));?>">
					   <span class="add-on"><i class=" icon-calendar"></i></span>
				     </div>
		        </div>
				
				<div class="span3">
					Ate:<br>
					 <div class="input-append">
					  <input class="span2 data" id="ate" name="ate" type="text" value="<?php echo date('d/m/Y',strtotime($ate));?>">
					   <span class="add-on"><i class=" icon-calendar"></i></span>
					</div>
			     </div>
			        <div class="span3">
			        total de registro por página:<br/>
			        <select id="totalpagina" name="totalpagina">
			        	<option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina']==20)?'selected':''?> value="20">20</option>
		        	    <option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina']==50)?'selected':''?> value="50">50</option>
			        	<option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina']==100)? 'selected':'';?> value="100">100</option>
			        	<option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina']=='todos')? 'selected':'';?> value="todos">Todos</option>
			        </select>
			        </div>
			     <div class="span3">
			     <br>
			     <button class="btn btn-info"  type="submit">Buscar</button>
			     </div>
			    
			</div>

		</form>
			<table class="table talbe-hover table-bordered">
			<thead>
				<tr>
					<th>Nº:</th>
                    <th>Usuario:</th>
					<th>
                                          Data:
				  		<a href="<?php echo $ordenarDescData; ?>" class="btn pop" data-toggle="popover" data-placement="top" title="" data-original-title="Ordena em decrescente" ><i class="icon-arrow-up <?php echo  $activDescData;?>"></i><a>
				  		<a href="<?php echo $ordenarAscData; ?>" class="btn pop" data-toggle="popover" data-placement="top" title="" data-original-title="Ordena em decrescente" ><i class="icon-arrow-down <?php echo  $activAscData;?>"></i><a>
					
					</th>
					<th>
                                          Data de Cadastro:
                                                <a href="<?php echo $ordenarDescDataCad; ?>" class="btn pop" data-toggle="popover" data-placement="top" title="" data-original-title="Ordena em decrescente" ><i class="icon-arrow-up <?php echo  $activDescDataCad;?>"></i><a>
                                                <a href="<?php echo $ordenarAscDataCad; ?>" class="btn pop" data-toggle="popover" data-placement="top" title="" data-original-title="Ordena em decrescente" ><i class="icon-arrow-down <?php echo  $activAscDataCad;?>"></i><a>
					</th>
					<th>Descrição:</th>
					<th>Valor: 
					<a href="<?php echo $ordenarDescValor; ?>" class="btn pop" data-toggle="popover" data-placement="top" title="" data-original-title="Ordena em decrescente" ><i class="icon-arrow-up <?php echo  $activDescValor;?>"></i><a>
				    <a href="<?php echo $ordenarAscValor; ?>" class="btn pop" data-toggle="popover" data-placement="top" title="" data-original-title="Ordena em decrescente" ><i class="icon-arrow-down <?php echo  $activAscValor;?>"></i><a>
				</tr>
			</thead>
			<?php   foreach ($contaBonus as $contaB){?>
			<tr>
				<td><?php echo $contaB->cb_id; ?></td>
                <td><?php echo $contaB->di_usuario; ?></td>
				<td><?php echo date('d/m/Y H:s:i',strtotime( $contaB->cb_data_hora)); ?></td>
				<td><?php echo date('d/m/Y H:s:i',strtotime( $contaB->di_data_cad)); ?></td>
				<td><?php echo $contaB->cb_descricao; ?></td>
				<td><span class="<?php echo $contaB->class;?>"><?php echo  $contaB->R.number_format( $contaB->valor, 2, ',', '.');?></span></td>
			</tr>
			<?php }?>
		</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>Registros</strong><br /><?php echo $total_Registro;?></td>
    <td><strong>Total Crédito</strong><br />US$ <?php echo number_format($totalCreditos->valor,2,',','.')?></td>
    <td><strong>Total Debito</strong><br />US$ <?php echo number_format($totalDebitos->valor,2,',','.')?></td>
    <td>&nbsp;</td>
  </tr>
</table>

		<div class="row">
         <div></div>
		<div class="pagination pagination-centered"><?php echo $paginacao;?> </div>
		</div>
	</div>
</div>
<script>
(function($){

 $('#name').keyup(function(){ 
	var html="";
	
	   if($(this).val().length > 3)
	     { 	
			$.ajax({
				   url:'<?php echo base_url("index.php/autocomplete/autocompleteDistribuidores");?>',
				   dataType:'json',
				   data:{nome:$(this).val()},
				   success:function(data)
				   {
			
					  $("#autocomplete").css('display','block');
					  if(data.error==0){
					   for(var x in data.data)
						 {
					      html+="<li rel='"+data.data[x].di_usuario+"'>"+data.data[x].di_usuario+"</li>";
						 }
					  }else{
					    html+="<li>Nenhum resultado</li>";
					  }
						 
		
					   $("#autocomplete").find('ul').html(html);
				   }

				});
	     }

       });
	     
	   $('#autocomplete').on('click','li',function(){
			   $('#name').val($(this).attr('rel'));
			   $("#autocomplete").css('display','none');
		   });

	   //share link
	    $('#share-link').on('click',function(){
	    	$('#link').show();
	    	$('#link').val('Gerando url aguarde...');
		   $.ajax({
				   url:'<?php echo base_url("index.php/encurtar_url/migre");?>',
				   dataType:'json',
				   type:'post',
				   data:{url:'<?php echo base_url("index.php/relatorios/transacoes").'?'.($link?$link:'');?>'},
				   success:function(data)
				   {
					  if(data.error==""){
						 $('#link').val(data.url); 
					  }
				   }
	
				});

			});

	    $(".data").mask('99/99/9999');
		  
		   
})(jQuery);

</script>