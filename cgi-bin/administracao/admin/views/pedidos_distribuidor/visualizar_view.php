<?php 
if(count($c) == 0){
	echo "Nenhum pedido encontrado";
	exit;
	}
 $d = $this->db
           ->where('es_id', $c->di_uf_rg)
		   ->get('estados')->row();
		   
 if(isset($d->es_uf)){
   $c->di_uf_rg = $d->es_uf;
 }else{	 
   $c->di_uf_rg = '';
 }		   
 
 
 list($d,$m,$y) = explode('-',$c->di_data_nascimento);
 $c->di_data_nascimento =  "$y/$m/$d";
 
 $produtos = $this->db->where('pm_id_compra', $this->uri->segment(3))
            ->join('produtos_comprados', 'pm_id_produto=pr_id')->get('produtos')->result();
 // var_dump($produto);exit();
 
 $patrocinador = $this->db->where('di_id', $c->di_ni_patrocinador)->get('distribuidores')->row();
 
 
 $estado = $this->db
           ->where('es_id', $c->di_uf)
		   ->get('estados')->row();
		   
 $cidade = $this->db
           ->where('ci_id', $c->di_cidade)
		   ->get('cidades')->row();
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style>
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
  text-align:center;
  text-transform:uppercase;
}

.h1 {
  color:#CCCCCC;
  font-size:19px;
  font-weight:normal;
  margin-bottom:5px;
  margin-top:0;
  padding-bottom:3px;
  text-align:left;
  text-transform:uppercase;
  border:none;
}
</style>

<div class="box-content min-height">
 <h1>Relatório venda para distribuidor</h1>
 <div class="box-content-body">
<style>
.title td {
  background-color:#E7EFEF;
  font-size:15px;
  color:#666;
  background-position:initial initial;
  background-repeat:initial initial;
  padding:5px;
}

.title2 td {
  background-color:#f4f4f4;
  font-size:15px;
  background-position:initial initial;
  background-repeat:initial initial;
  padding:5px;
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
<div class="well">

<h1 class="h1">Dados distribuidor</h1>
<table width="80%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr class="title">    
    <td width="20%" align="left"><strong style="color:#666;">NI</strong></td>
    <td width="75%" align="left"><?php echo $c->di_id?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Distribuidor</strong></td>
    <td width="75%" align="left"><?php echo $c->di_nome?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Data de Nascimento</strong></td>
    <td width="75%" align="left"><?php echo $c->di_data_nascimento?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">CPF</strong></td>
    <td width="75%" align="left"><?php echo $c->di_cpf?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">RG</strong></td>
    <td width="75%" align="left"><?php echo $c->di_rg?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Órgão Expeditor</strong></td>
    <td width="75%" align="left"><?php echo $c->di_ssp_rg?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">UF</strong></td>
    <td width="75%" align="left"><?php echo $c->di_uf_rg?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">E-mail</strong></td>
    <td width="75%" align="left"><?php echo $c->di_email?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Endereço</strong></td>
    <td width="75%" align="left"><?php echo $c->di_endereco?></td>
  </tr>

  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Número</strong></td>
    <td width="75%" align="left"><?php echo $c->di_numero?></td>
  </tr>
  
   <tr class="title">    
    <td width="20%"><strong style="color:#666;">CEP</strong></td>
    <td width="75%" align="left"><?php echo $c->di_cep?></td>
  </tr>
  
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Cidade - UF</strong></td>
    <td width="75%" align="left"><?php echo $cidade->ci_nome." - ".$estado->es_uf?></td>
  </tr>

  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Telefone</strong></td>
    <td width="75%" align="left"><?php echo $c->di_fone1?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Celular</strong></td>
    <td width="75%" align="left"><?php echo $c->di_fone2?></td>
  </tr>
 
</thead>
</table>
<br/>

	<br />
<?php
$Pagamento_terceiros = $this->db->where ( 'rc_compra', $c->co_id )->join ( 'distribuidores', 'di_id=rc_pagante' )->get ( 'registro_pagamento_compra_terceiro' )->row ();
if (count ( $Pagamento_terceiros ) != 0) {
	?>
<table width="100%" class="table" border="0" cellspacing="0"
		cellpadding="0">
		<tr class="title">
			<td colspan="2"><strong>Detalhes do Pagamento</strong></td>
		</tr>
		<tr>
			<td><strong>Paga com Bônus Por: </strong><?php echo $Pagamento_terceiros->di_nome?> - (<?php echo $Pagamento_terceiros->di_usuario?>)</td>
			<td><strong>Data Hora: </strong><?php echo date('d/m/Y H:m:s',strtotime($Pagamento_terceiros->rc_data));?></td>
		</tr>
	</table>
<?php }?>

<br/>
<h1 class="h1">Dados Compra</h1>

<table width="80%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr class="title">    
    <td width="20%" align="left"><strong style="color:#666;">N°</strong></td>
    <td width="75%" align="left"><?php echo $c->co_id?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Produtos</strong></td>
    <td width="75%" align="left">
      <?php
          foreach ($produtos as $produto) {
               echo $produto->pr_nome .' <strong>$ '.$produto->pr_valor.' Uni | Qtd Prd: ('. $produto->pm_quantidade .')</strong><br>' ;
          }
      ?>
    </td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Situação</strong></td>
    <td width="75%" align="left"><?php echo $c->st_descricao?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Forma de Pagamento</strong></td>
    <td width="75%" align="left"><?php echo $c->fp_descricao?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Valor total da compra</strong></td>
    <td width="75%" align="left">US$ <?php echo number_format($c->co_total_valor,2,',','.');?></td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Data da compra</strong></td>
    <td width="75%" align="left">
     <?php 
	  $data = explode(' ', $c->co_data_compra);
	  $dataCompra = explode('-',$data[0]);
	  echo $dataCompra[2]."/".$dataCompra[1]."/".$dataCompra[0];	  
	 ?>
    </td>
  </tr>
  <tr class="title">    
    <td width="20%"><strong style="color:#666;">Horário da compra</strong></td>
    <td width="75%" align="left">	 <?php 
	  $data = explode(' ', $c->co_data_compra);
	  echo  $data[1];	  
	 ?> </td>
  </tr>
 
</thead>
</table>

 <a  class="btn btn-primary" href="javascript:window.print()">Imprimir</a> <br> 

</div>
</div>