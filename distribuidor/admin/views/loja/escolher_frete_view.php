
<div class="box-content min-height">
 <div class="box-content-header">
  <a href="<?php echo base_url()?>">Principal</a> &raquo;
 <a href="<?php echo base_url('index.php/loja/retirar_em_cd')?>">Loja</a> &raquo;
 Frete
 </div>
 <div class="box-content-body">
<div class="panel">



<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <thead>
  <tr><td><strong style="color:#09C;">Escolha a forma de envio do pedido:</strong></td></tr>
 </thead>
 <tbody>
 <tr><td>
 <form action="" method="POST" >
 <div class="well">
 <strong>Endereço para entrega</strong>
 <div><?php echo get_user()->di_endereco?>, <?php echo get_user()->di_complemento?>, <?php echo get_user()->di_numero?></div>
 <div><?php echo get_user()->di_bairro?>, <?php echo get_user()->di_cep?></div>
 <div><?php echo get_user()->ci_nome?> - <?php echo get_user()->ci_uf?></div>
 <div style="font-size:11px;color:#F60;">DICA: Para alterar o endereço de entrega, mude seu endereço no menu Meus Dados.</div>
 </div>
 
 <?php if(false){?>
 <p>
  <input type="radio" name="frete" value="Retirar/0" /> <strong>Retirar no CD</strong> - Você retirará o pedido pessoalmente no CD
 </p>
 <?php }?>
 

 
  <input type="radio" name="frete" value="Retirar/0" />
  <b>Retirar pedido na empresa</b><br />
 
 
 <?php if(get_compra()->co_peso_total>30){?>
 <p style="color:#f00">Os correios não transporta mais de 30 Kg, sua compra está pesando <?php echo get_compra()->co_peso_total?> Kg</p>
 
  <input type="radio" name="frete" value="Transportadora/20.00" />
  Via Transportadora<br /><br />
 <?php }?>

 <?php if(isset($frete['pac'])){?>
 <p> 
 
  <input type="radio" name="frete" value="Pac/<?php echo $frete['pac']['valor']?>" />
  <strong>PAC</strong> - R$ <?php echo $frete['pac']['valor']?> - Até 30 dias úteis

 
 </p>
 <?php }?> 

 <?php if(isset($frete['pac'])){?>
 <p>
  <input type="radio" name="frete" value="Sedex/<?php echo $frete['sedex']['valor']?>" /> <strong>SEDEX</strong> - R$ <?php echo $frete['sedex']['valor']?> - Até 30 dias úteis
 </p>
 <?php }?> 
 

  
 <?php if(count($frete)==0){?>
  <p>Não foi possível calcular o frete, verifique seu CEP no cadastro e tente novamente.</p>
 <?php }else{?>
   
 <?php }?>

 
 <input type="submit" class="btn btn-primary" value="CLIQUE PARA CONTINUAR" />
 </form>
 
 </td></tr>
 </tbody>
</table>

<p style="color:#f00; font-size:15px;">Após a confirmação do pagamento do pedido, a expedição tem o prazo de até 72 horas em dias úteis
<br>
para postá-lo, após a postagem deve se contar os prazos fixos de cada modalidade de envio<br><br>

 
</p>
</div>

<div class="buttons">
 <a class="botao" href="javascript:history.go(-1)">Voltar</a>
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
