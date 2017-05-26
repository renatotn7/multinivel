<div class="box-content" style="min-height:1px; padding:10px; width:93%; margin-bottom:5px;">
  <?php
  	//traz apenas os planos superiores ao plano atual do usuario
	$planos = $this->db->where('pa_id >', get_user()->distribuidor->getPlano()->getId(), false)
  						->join('produtos', 'pr_id=pa_kit', 'left')->get('planos')->result();

	if (isset($_GET['cad']) && $_GET['cad'] == 'sim') {
?>
  <div class="alert alert-success" style="font-size:24px; margin:10px 0;"> <strong>Parabéns
    <?php
    echo get_user()->di_nome;
?>
    , bem vindo ao futuro melhor.</strong> </div>
  <h4> Bem vindo ao seu escritório virtual.<br />
  </h4>
  <?php
}
?>
</div>
<div class="box-content">
  <div class="box-content-header">Upgrade de conta</div>
  <div class="box-content-body">
    <?php if (count($planos) > 0) {?>
    <center>
      <h3>Escolha um dos planos disponiveis para upgrade:</h3>
    </center>
    <form id="form1" name="form1" method="post" action="<?php
echo base_url('index.php/loja/upgrade_plano_finaliza');
?>">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <?php  
    foreach ($planos as $k => $p) {
        $produtosSelecionados = $this->db->join('produtos', 'pr_id=pn_produto')->where('pn_plano', $p->pa_id)->get('produtos_padrao_plano')->result();
?>
          <td valign="top"><div class="well" style="text-align:center; background:#B5D8F8;">
              <h4><?php echo $p->pa_descricao;?></h4>
              <div>R$ <?php echo number_format(($p->pa_valor), 2, ',', '.');?> </div>
              <input <?php
        echo $k == 0 ? 'checked' : '';
?> type="radio" onclick="show_escolher_combo(<?php
        echo $p->pa_id;
?>)" name="plano" value="<?php
        echo $p->pa_primes;
?>"  />
	</div>
            <?php
    }
?>
        </tr>
      </table>
      <input type="submit" class="btn btn-success btn-large" value="Proximo" />
    </form>
    <?php
    }else{
	?>
    <div class="alert alert-warning">
      <h4>Não existem planos disponiveis</h4>
      Atualmente não existem planos superiores ao seu plano atual! </div>
    <?php
	}			
	?>
  </div>
</div>
