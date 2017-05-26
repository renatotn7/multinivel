<?php 

$fr = $this->db->where('fr_apuracao',date('Y-m-01'))
	 ->where('fr_distribuidor',get_user()->di_id)
	 ->get('forma_recebimento')->result();

?>

<div class="painel">
<strong>Escolha a forma de recebimento</strong>

<form action="" method="post">
<p>
 <input type="radio" <?php echo $fr[0]->fr_forma==1?"checked":""?> name="fr_forma" value="1" /> 
 Receber em crédito escritório virtual</p>
 <p>
 <input type="radio" <?php echo $fr[0]->fr_forma==2?"checked":""?> name="fr_forma" value="2" />
 Receber em bônus via depósito</p>
 
 <input type="submit" class="botao btn_verde" value="SALVAR" />
 
 <a class="botao" href="javascript:history.go(-1)">Cancelar</a>
</form>

</div>