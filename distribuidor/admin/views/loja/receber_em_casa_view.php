<div class="box-content">
 <div class="box-content-header">
 <a href="<?php echo base_url('index.php/loja/')?>">Loja</a> &raquo;
Endreço de entrega
 </div>
 <div class="box-content-body">


<h2>O pedido será enviado para o endereço de cadastro</h2>

<div class='painel'>

<table width="700px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong><?php echo get_compra()->co_entrega_logradouro?>, <?php echo get_compra()->co_entrega_numero?><br />
<?php echo get_compra()->co_entrega_bairro?> - <?php echo get_compra()->co_entrega_cep?><br />
<?php echo get_compra()->ci_nome?>-<?php echo get_compra()->ci_uf?></strong></td>
    <td>
</td>
  </tr>
</table>

<br />



<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <thead>
  <tr><td></td></tr>
 </thead>
 <tbody>
 <tr><td>

<p style="color:#f00; font-size:15px;">Após a confirmação do pagamento do pedido, a expedição tem o prazo de até 72 horas em dias úteis
<br>
para postá-lo, após a postagem deve se contar os prazos fixos de cada modalidade de envio<br><br>

 
</p>


<a class="btn btn-success" href="<?php echo base_url('index.php/loja/carrinho/')?>">CONTINUAR</a>

 <a class="btn" href="javascript:history.go(-1)">Voltar</a> 
 
 </td></tr>
 </tbody>
</table>


<br />




</div>


</div>
</div>









<script type="text/javascript">
$(function(){
	if($("input[type='radio']").size()>=1){
		$($("input[type='radio']")[0]).attr('checked','checked');
		}
	});
</script>
