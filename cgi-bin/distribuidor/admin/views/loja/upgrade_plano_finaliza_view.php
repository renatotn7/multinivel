<div class="box-content" style="min-height:1px; padding:10px; width:93%; margin-bottom:5px;">
  <?php		 
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

    <center><h3>Requisitos para o upgrade</h3></center>
    <form id="form1" name="form1" method="post" action="<?php
echo base_url('index.php/loja/prepara_upgrade_servico/');
?>">
      <table class="table" style="margin:0 auto;">
      	<tr>
        	<td><b>Adquira um Plano</b></td>
            <td><b>Valor</b></td>
            <td><b>Primes</b></td>
            <td><b>Combos</b></td>
        </tr>
		<?php 
			$totalValor = 0;	
			$totalPrimes = 0;	
	  		foreach($arrayRequisitos as $requisito){
				$produtosSelecionados = $this->db->join('produtos', 'pr_id=pn_produto')->where('pn_plano', $requisito->pa_id)->get('produtos_padrao_plano')->result();
		?>
        <tr>
        	<td><input type="hidden" value="<?php echo($requisito->pa_id)?>" name="planos[]"/><?php echo($requisito->pa_descricao);?></td>
            <td>R$ <?php echo($requisito->pa_valor);?></td>
            <td><?php echo($requisito->pa_primes);?></td>
            <td><div style="min-height:100px;">
                <?php
        if ($produtosSelecionados) {
?>
                <strong>Combo Padrão
                <?php
            echo $requisito->pa_descricao;
?>
                </strong>
                <?php
            foreach ($produtosSelecionados as $produtoSelecionado) {
?>
                <div style="font-size:11px;">&not;
                  <?php
                echo $produtoSelecionado->pr_nome;
?>
                </div>
                <?php
            }
?>
                <?php
        }else{
?>
                <?php 
					$data['idPlano'] = $requisito->pa_id;
					$this->load->view("loja/opcoes_combo_upgrade_view", $data);?>
                <?php
				
		}
?>
              </div></td>
        </tr>
		<?php
		$totalValor = $totalValor + $requisito->pa_valor;
		$totalPrimes = $totalPrimes + $requisito->pa_primes;
			}
		?>
        <tr>
        	<td style="border-top:1px gray solid"><b>Total:</b></td>
            <td style="border-top:1px gray solid"><b>R$ <?php echo(number_format(str_ireplace(',','.',$totalValor),2,'.',''));?></b></td>
            <td style="border-top:1px gray solid"><b><?php echo($totalPrimes);?></b></td>
            <td style="border-top:1px gray solid"></td>
        </tr>
      </table>
      <input type="submit" class="btn btn-success btn-large" value="Solicitar Upgrade" />
    </form>
  </div>
</div>