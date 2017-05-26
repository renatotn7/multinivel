<?php 
 $fabrica = $this->db->get('fabricas')->result();
 
 //UF
  $d = $this->db
		    ->where('es_id', $c->di_uf_rg)
		    ->get('estados')->row();		   
  $c->di_uf_rg = $d->es_uf;
 //End

 
 list($d,$m,$y) = explode('-',$c->di_data_nascimento);
 $c->di_data_nascimento =  "$y/$m/$d";
 
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fabrica[0]->fa_nome;?> - Relatório Vendas Distribuidor - <?php echo date('d-m-Y')?></title>

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
  font-size:14px;
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
<body>
<h1><?php echo $fabrica[0]->fa_nome;?> - Relatório Vendas Distribuidor - <?php echo date('d/m/Y')?></h1>

<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr class="title">    
    <td width="25%" align="left"><strong style="color:#666;">NI</strong></td>
    <td width="75%" align="left"><?php echo $c->di_id?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Distribuidor</strong></td>
    <td width="75%" align="left"><?php echo $c->di_nome?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Data de Nascimento</strong></td>
    <td width="75%" align="left"><?php echo $c->di_data_nascimento?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">CPF</strong></td>
    <td width="75%" align="left"><?php echo $c->di_cpf?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">RG</strong></td>
    <td width="75%" align="left"><?php echo $c->di_rg?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Órgão Expeditor</strong></td>
    <td width="75%" align="left"><?php echo $c->di_ssp_rg?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">UF</strong></td>
    <td width="75%" align="left"><?php echo $c->di_uf_rg?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">E-mail</strong></td>
    <td width="75%" align="left"><?php echo $c->di_email?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Endereço</strong></td>
    <td width="75%" align="left"><?php echo $c->di_endereco?></td>
  </tr>

  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Número</strong></td>
    <td width="75%" align="left"><?php echo $c->di_numero?></td>
  </tr>
  
   <tr class="title">    
    <td width="25%"><strong style="color:#666;">CEP</strong></td>
    <td width="75%" align="left"><?php echo $c->di_cep?></td>
  </tr>
  
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Cidade - UF</strong></td>
    <td width="75%" align="left"><?php echo $c->ci_nome." - ".$c->es_uf?></td>
  </tr>

  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Telefone</strong></td>
    <td width="75%" align="left"><?php echo $c->di_fone1?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Celular</strong></td>
    <td width="75%" align="left"><?php echo $c->di_fone2?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Situação</strong></td>
    <td width="75%" align="left"><?php echo $c->st_descricao?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Forma de pagamento</strong></td>
    <td width="75%" align="left"><?php echo $c->fp_descricao?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Valor</strong></td>
    <td width="75%" align="left"><?php echo number_format($c->co_total_valor,2,',','.')?></td>
  </tr>
  <tr class="title">    
    <td width="25%"><strong style="color:#666;">Data</strong></td>
    <td width="75%" align="left"><?php echo date('d/m/Y',strtotime($c->co_data_compra))?></td>
  </tr>
 
</thead>
</table>

<br />
<a href="javascript:window.print()">Imprimir relatório</a>
</body>
</html>