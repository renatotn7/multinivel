<?php
function INSS($valor)
{
    $inss = 0;

    if ($valor < 1247.70) {
        $inss = $valor * (8 / 100);
    } else if ($valor >= 1247.71 && $valor <= 2079.50) {
        $inss = $valor * (9 / 100);
    } else if ($valor >= 2079.51) {

        $new_valor = $valor > 4159.00 ? 4159.00 : $valor;
        $inss      = $new_valor * (11 / 100);
    }

    return $inss;
}

function inposto_renda($valor, $inss, $dependentes)
{
    $base_calculo = ($valor - $inss) - ($dependentes * 171.97);

    $imposto = 0;

    if ($base_calculo >= 1710.79 && $base_calculo <= 2563.91) {
        $imposto = ($base_calculo * (7.5 / 100)) - 128.31;
    } else if ($base_calculo >= 2563.92 && $base_calculo <= 3418.59) {
        $imposto = ($base_calculo * (15 / 100)) - 320.60;
    } else if ($base_calculo >= 3418.60 && $base_calculo <= 4271.59) {
        $imposto = ($base_calculo * (22.5 / 100)) - 577.00;
    } else if ($base_calculo >= 4271.59) {
        $imposto = ($base_calculo * (27.5 / 100)) - 790.58;
    }

    return $imposto;
}

$fabrica = $this->db->get('fabricas')->result();
$dep     = $this->db
    ->join('distribuidores', 'di_id=cdp_distribuidor')
    ->join('cidades', 'di_cidade=ci_id')
    ->where('cdp_id', $this->uri->segment(3))
    ->get('conta_deposito')->result();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Depósito Nº  <?php echo $dep[0]->cdp_id; ?> -  <?php echo $dep[0]->di_nome . '/' . $dep[0]->di_id; ?></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<style>
		body{
			color:#000000;
			font-family:Verdana, Arial, Helvetica, sans-serif;
			font-size:12px;
		}
		h1 {
			border-bottom-color:#CDDDDD;
			border-bottom-style:solid;
			border-bottom-width:1px;
			color:#CCCCCC;
			font-size:24px;
			font-weight:normal;
			margin-bottom:15px;
			margin-top:0;
			padding-bottom:5px;
			text-align:right;
			text-transform:uppercase;
		}

		.table {
			border-right-color:#CDDDDD;
			border-right-style:solid;
			border-right-width:1px;
			border-top-color:#CDDDDD;
			border-top-style:solid;
			border-top-width:1px;
			margin-bottom:20px;
			width:100%;
		}
		.title td {
			background-color:#E7EFEF;
			background-position:initial initial;
			background-repeat:initial initial;
		}
		.table th, .table td {
			border-bottom-color:#CDDDDD;
			border-bottom-style:solid;
			border-bottom-width:1px;
			border-left-color:#CDDDDD;
			border-left-style:solid;
			border-left-width:1px;
			padding:5px;
			vertical-align:text-bottom;
		}
	</style>
</head>
<body class="container">
	<h1><?php echo $fabrica[0]->fa_nome; ?> - Depósito Nº  <?php echo $dep[0]->cdp_id; ?> -  <?php echo $dep[0]->di_nome . '/' . $dep[0]->di_id; ?> </h1>
	<table class="table table-bordered table-hover" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<strong><?php echo $dep[0]->di_nome." ".$dep[0]->di_ultimo_nome ?></strong><br />
				<strong><?php echo $dep[0]->di_tipo_documento . ": " . $dep[0]->di_rg ?></strong><br />
				<?php echo $dep[0]->di_endereco ?><br />
				<?php echo $dep[0]->ci_nome ?>-<?php echo $dep[0]->ci_uf ?><br />
				Fone: <?php echo $dep[0]->di_fone1 ?><br />
				<?php echo $dep[0]->di_email ?>
			</td>
			<td>
				<b>Data:</b> <?php echo date('d/m/Y H:i:s', strtotime($dep[0]->cdp_data)) ?><br />
				<b>Nº depósito:</b> <?php echo $dep[0]->cdp_id; ?><br />
				<b>Situação:</b> <?php echo $dep[0]->cdp_status == 1 ? 'Depositado' : 'Aguardando depósito' ?><br />
				<b>Ação:</b> <?php echo $dep[0]->cdp_status == 1 ? '<a href="' . base_url("index.php/deposito/status?cdp={$dep[0]->cdp_id}&s=0") . '">Aguardando depósito</a>' : '<a href="' . base_url("index.php/deposito/status?cdp={$dep[0]->cdp_id}&s=1") . '">Depositado</a>'?>
			</td>
			<td>
				<strong>Conta para depósito</strong>
				<b><?php echo $dep[0]->di_conta_banco ?></b><br />
				Conta <?php echo $dep[0]->di_conta_tipo == 1 ? 'Corrente' : 'Poupança' ?>: <?php echo $dep[0]->di_conta_numero ?><br />
				Agência: <?php echo $dep[0]->di_conta_agencia ?><br />
				Variação: <?php echo $dep[0]->di_conta_variacao ?><br />
				<?php echo $dep[0]->di_conta_nome ?> - <?php echo $dep[0]->di_conta_cpf ?><br />
			</td>
		</tr>
	</table>
	<br />
	<table class="table table-bordered table-hover" width="100%" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr class="title">
				<td width="6%"><strong>Nº</strong></td>
				<td width="35%"><strong>Mês apuração</strong></td>
				<td width="35%"><strong>Data</strong></td>
				<td width="9%"><strong>Valor</strong></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="6%"><?php echo $dep[0]->cdp_id ?></td>
				<td width="35%"><?php echo date('m/Y', strtotime($dep[0]->cdp_apuracao)) ?></td>
				<td width="35%"><?php echo date('d/m/Y', strtotime($dep[0]->cdp_data)) ?></td>
				<td width="9%">US$ <?php echo number_format($dep[0]->cdp_valor, 2, ',', '.') ?></td>
			</tr>
			<?php
			$inss         = INSS($dep[0]->cdp_valor);
			$inpost_renda = (inposto_renda($dep[0]->cdp_valor, $inss, $dep[0]->di_dependentes))
			?>
		</tbody>
	</table>
	<p>
		<a class="btn btn-primary text-center" href="javascript:window.print()">Imprimir</a>
	</p>
</body>
</html>