<div class="box-content" style="min-height:1px; padding:10px; width:93%; margin-bottom:5px;">

<?php if(isset($_GET['cad'])&& $_GET['cad']=='sim'){?>
<div class="alert alert-success" style="font-size:24px; margin:10px 0;">
<strong>Parabéns <?php  echo get_user()->di_nome?> , bem vindo ao futuro melhor.</strong>
</div>

<h4>
Bem vindo ao seu escritório virtual.<br />
</h4>

Um e-mail foi enviado para <strong style="font-size:23px; color:#666;"><?php  echo get_user()->di_email?></strong> com maiores informações.
<p>Para que seu cadastro seja completado e você entre para a rede, você deve adquirir um de nossos planos.
<br />
Escolhas abaixo uma das opções.
</p>
<?php }?>

</div>

<div class="box-content">
 <div class="box-content-header">Escolha sua conta</div>
  <div class="box-content-body">
  
     <form id="form1" name="form1" method="post" action="<?php echo base_url('index.php/loja/prepara_compra_servico/')?>">

 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
      <?php 
	  $planos = $this->db
	  ->join('produtos','pr_id=pa_kit','left')
	  ->get('planos')->result();
	  foreach($planos as $k=> $p){
		  
	  $produtosSelecionados = $this->db
	   ->join('produtos','pr_id=pn_produto')
	   ->where('pn_plano',$p->pa_id)
	   ->get('produtos_padrao_plano')
	   ->result();	  
	  ?>
        <td valign="top">
         <div class="well" style="text-align:center; background:#B5D8F8; height:253px;">
         <h4><?php echo $p->pa_descricao?></h4>
          <div>R$ <?php echo number_format($p->pa_valor,2,',','.')?></div>
          <input <?php echo $k==0?'checked':''?> type="radio" onclick="show_escolher_combo(<?php echo $p->pa_id?>)" name="plano" value="<?php echo $p->pa_id?>"  />
          
          <div>
            <?php if($produtosSelecionados){?>
              <hr style="margin:3px 0;" />
             <strong>Combo Padrão <?php echo $p->pa_descricao?></strong>
			  <?php 
              foreach($produtosSelecionados as $produtoSelecionado){
              ?> 
               <div style="font-size:11px;">&not; <?php echo $produtoSelecionado->pr_nome?></div>
              <?php }?>
            <?php }?>  
          </div>
        
         </div>
        </td>
       <?php }?>
      </tr>
    </table>
     

 


  <table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
   
     <td width="40%" valign="top">
       <?php $this->load->view("loja/opcoes_combo_view");?>
     </td>
     
     <td width="20%" valign="top">

     </td>
     
     <td valign="top" width="10%">
     </td>
     
  </tr>
</table>

 
<input type="submit" class="btn btn-success btn-large" value="Iniciar Pedido" />
 
</form>


  

 </div>
 </div> 