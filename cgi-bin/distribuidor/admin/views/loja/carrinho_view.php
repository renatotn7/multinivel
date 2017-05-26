<div class="box-content min-height">
 <div class="box-content-header">
  <a href="<?php echo base_url()?>">Principal</a> &raquo;
 <a href="<?php echo base_url('index.php/loja/para_meu_id')?>">Loja</a> &raquo;
 Carrinho
 </div>
 <div class="box-content-body">
<div class="panel">
<table width="100%" height="150px" id="table-ger-prod" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="207" valign="top" id="cat-pordutos-car">
    <strong>Categoria</strong>
    <ul>
    <?php $cats = $this->db->where('ca_pai',0)->get('categorias_produtos')->result();
	foreach($cats as $c){
	?>
    <li>
    <a onclick='filtra_produtos(<?php echo $c->ca_id ?>,1);' href='javascript:void(0)'> 
    <?php echo $c->ca_descricao ?></a> 
    </li>
    <?php }?>
    </ul>
    </td>
    <td width="4">&nbsp;</td>
    <td width="760" valign="top" style="background:#FFF; border:1px solid #d9d9d9">
    <strong>Escolha o produtos</strong>
    
    <input type="hidden" name="produtos[]" id="recebe-produtos1" value="" />
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="488">
        
        <div style="height:100px; overflow:auto;" id="recebe-produtos">
		 <?php
		 if(get_compra()->co_id_cd==0){ 
         $prod = $this->db
		 ->select(array('pr_id','pr_descricao','pr_codigo','pr_nome'))
         ->where('pr_vender',1)
         ->where('pr_estoque >',0)
         ->where('pr_venda <>',3)
         ->get('produtos')->result();
		 }else{
			$prod = $this->db
			 ->query("
			 SELECT * FROM produtos_do_cd
			 JOIN produtos ON pr_id = pc_id_produto
			 WHERE pc_id_cd = ".get_compra()->co_id_cd."
			 GROUP BY pr_id
			 HAVING SUM(pc_entrada) - SUM(pc_saida) > 0
			 ")->result(); 
		}
		?>
        <?php if(count($prod)==0){?> <div alt="">Nenhum produto encontrado</div><?php }?>
        <?php	 
         foreach($prod as $p){
         ?>
          <div title="<?php echo $p->pr_descricao?>" 
          alt="<?php echo $p->pr_id?>"><?php echo $p->pr_codigo." - ".$p->pr_nome?></div>
         <?php }?>
        </div>
        
        </td>
        <td width="177" id="recebe-descricao" valign="top" style="padding:10px; font-size:13px; background:#f9f9f9"></td>
        
      </tr>
    </table>

  
    <form name="formulario"  style="padding:2px 5px; margin:0;">
    <div class="input-append">
     Quantidade: <input type="text" style="width:60px;" onblur="checkNumber(this.value);" maxlength="2" id="quantidade_prod" value="1" size="4" /> 
     <button class="btn btn-primary" type="button" disabled="disabled" id="add-car">Adicionar</button> 
   </div>
   </form>
    </td>
  </tr>
</table>

<p></p>

<div id="recebe-carrinho"></div>
<br />

<div class="buttons">
 <a class="btn" href="javascript:history.go(-1)">Voltar</a>
</div>

</div>
</div>
</div>

<script type="text/javascript">


$(function(){
	 
	 atualiza_carrinho();
	 desativar_compra();
	 
	 
	 $("#recebe-produtos div").live('click',function(){
		 $("#recebe-produtos div").removeClass('produto-escolhido');
		 $(this).addClass('produto-escolhido');
		 $("#recebe-descricao").html($(this).attr('title'));
		 $('#recebe-produtos1').val($(this).attr('alt'));
		 ativar_compra();
		 });
	 
	 
	 $('#add-car').click(function(){
		 var inteiro = '^d+$';
		 if($('#quantidade_prod').val()==''){
			  alert('Informe a quantidade correta');
			  return false;
			 }
		 
		 if($('#recebe-produtos1').val()&&$('#recebe-produtos1').val()!=''){
		  $.ajax({
			  url:'<?php echo base_url('index.php/loja/add_produto_car_ajax')?>',
			  data:{produtos:$('#recebe-produtos1').val(),qtd:$('#quantidade_prod').val()},
			  dataType:'html',
			  type:'POST',
			  success:function(dataHtml){
				  $('#quantidade_prod').val(1);
				  atualiza_carrinho();
				  desativar_compra();
				  $("#recebe-produtos div").removeClass('produto-escolhido');
				  $('#recebe-produtos1').val('')
				  $("#recebe-descricao").html("");
				  },
			  error:function(){
				   alert('ERRO AO INSERIR O PRODUTO NO CARRINHO. \nVerifique se inseriu a quantidade corretamente ou \ntente novamente');
				  }	  
			  });
		 }else{
			 alert('Selecione um produto');
			 }
		  
		 });
	
	});


function ativar_compra(){
	$('#add-car').removeAttr('disabled');
	$('#quantidade_prod').removeAttr('disabled');
	}

function desativar_compra(){
	$('#add-car').attr('disabled','disabled');
	$('#quantidade_prod').attr('disabled','disabled');
	}

function remover_do_carrinho(id){
	$.ajax({
		 url:'<?php echo base_url('index.php/loja/remover_carrinho_ajax')?>/'+id,
		 type:'GET',
		 success:function(){
			 atualiza_carrinho();
			 },
		 error:function(){
			 alert('Ocorreu um erro ao excluir o produto do carrinho. \nrecarregue a página.');
			 }	 
		});
	}


function atualiza_carrinho(){
	$("#recebe-carrinho").html("<img src='<?php echo base_url()?>public/script/tree/css/images/ajax-loader.gif' /> carregando seu pedido...");
	$.ajax({
	url:'<?php echo base_url('index.php/loja/get_carrinho_ajax')?>',
	type:'GET',
	dataType:'html',
	success:function(html){
		$("#recebe-carrinho").html(html);
		}
	});
	}



function filtra_produtos(id,filtrar_sub){
	desativar_compra();
	
	/**
	*
	*FILTRAR SUB CATEGORIAS
	*
	*/
	if(filtrar_sub==1){
		
	$.ajax({
		url:'<?php echo base_url('index.php/loja/sub_categoria_ajax')?>/'+id,
	    type:'GET',
		dataType:'json',
		success:function(dataSubs){
			 var txtSubCats = "<ul>";
			 
			 $.each(dataSubs,function(i, subcat){
				  txtSubCats += '<li>';
				  txtSubCats += "<a onclick='filtra_produtos("+subcat.ca_id+",0);'";
				  txtSubCats += "href='javascript:void(0)'>";
				  txtSubCats += subcat.ca_descricao;
				  txtSubCats += '</a>';
				  txtSubCats += '</li>';
				 });
			 
			 txtSubCats +='<ul>';
			 $("#recebe-sub-categoria").html(txtSubCats);
			},
		erro:function(){
			
			}	
		});
		
	}
	/**
	*
	*FILTRAR OS PRODUTOS
	*
	*/
	 $.ajax({
		 url:'<?php echo base_url('index.php/loja/produtos_ajax')?>/'+id,
		 type:'GET',
		 dataType:'json',
		 success:function(dataJson){
			
			 
			 var txt_produtos = '';
			 $.each(dataJson,function(position, produto){
				 var txt = 
				 txt_produtos += '<div title="'+produto.pr_descricao+'" alt="'+produto.pr_id+'">'+produto.pr_codigo+' - '+produto.pr_nome+'</div>';
				 });
			 dataJson.length ==0?$('#recebe-produtos').html('<div alt="">Nenhum produto nessa categoria</div>'): $('#recebe-produtos').html(txt_produtos);
			 $("#recebe-descricao").html("");
			 },
		 error:function(){
			 
			 }	 
		 });
	}

function checkNumber(valor) {
  var regra = /^[0-9]+$/;
  if (!valor.match(regra)||valor==0) {
    alert("Permitido somente número inteiro positivo maior que zero!");
	$('#quantidade_prod').val('1');
  }
};  	
	
</script>
