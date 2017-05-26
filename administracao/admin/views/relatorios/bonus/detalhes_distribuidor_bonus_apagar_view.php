<?php  
 $totalaPagar = $c->cb_credito-$c->cb_debito;
 
 $d = $this->db
           ->where('es_id', $c->di_uf_rg)
		   ->get('estados')->row();
 

 
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
</style>
<div class="well">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100%px">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<thead>  

 <tr class="title">
   <td width="150px" align="right">
    NI :  
   </td>
   <td><strong><?php echo $c->di_id?></strong></td>
  </tr>

 <tr class="title">
   <td width="150px" align="right">
    Distribuidor :  
   </td>
   <td><strong><?php echo $c->di_nome." (".$c->di_usuario.")"?></strong></td>
  </tr>
 
 <tr><td height="5px"></td></tr> 
  
  <tr class="title">
   <td width="150px" align="right">
    CPF :  
   </td>
   <td><strong><?php echo $c->di_cpf?></strong></td>
  </tr> 
     
  <tr><td height="5px"></td></tr>  
  
  <tr class="title">
   <td width="150px" align="right">
    RG :  
   </td>
   <td><strong><?php echo $c->di_rg?></strong></td>
  </tr> 
   
 <tr><td height="5px"></td></tr>
 
   <tr class="title">
   <td width="150px" align="right">
    Órgão Exp. :  
   </td>
   <td><strong><?php echo $c->di_ssp_rg?></strong></td>
  </tr>

 <tr><td height="5px"></td></tr>
 
 <tr class="title">
   <td width="150px" align="right">
    UF :  
   </td>
   <td><strong><?php if(isset($d->es_uf)){echo $d->es_uf;};?></strong></td>
</tr>
 
<tr><td height="5px"></td></tr>

 
<tr class="title">
   <td width="150px" align="right">
    Data Nascimento :  
   </td>
   <td><strong><?php echo $c->di_data_nascimento?></strong></td>
</tr>

<tr><td height="5px"></td></tr>

<tr class="title">
   <td width="150px" align="right">
    Telefone:  
   </td>
   <td><strong><?php echo $c->di_fone1?></strong></td>
</tr>

<tr class="title">
   <td width="150px" align="right">
    E-mail:  
   </td>
   <td><strong><?php echo $c->di_email?></strong></td>
</tr>

<tr><td height="5px"></td></tr>
  
  <tr class="title">
   <td width="150px" align="right">
    Cidade :  
   </td>
   <td><strong><?php echo $c->ci_nome." - ".$c->ci_uf?></strong></td>
   
  </tr> <tr><td height="5px"></td></tr>  <tr class="title">
   <td width="150px" align="right">
    Endereço :  
   </td>
   <td><strong><?php echo $c->di_endereco." ".$c->di_bairro." ".$c->di_cep?></strong></td>
  </tr> <tr><td height="5px"></td></tr>  
  
  <tr class="title">
   <td width="150px" align="right">
    Bônus : 
   </td>
   <td><strong><?php echo $c->tb_descricao?></strong></td>
  </tr> 
  
  <tr><td height="5px"></td></tr>  
  
  <tr class="title">  
  <td width="150px" align="right">
    Apuração:  
   </td>
   <td><strong><?php echo date('m/Y',strtotime($c->cb_data_hora))?></strong></td>
  </tr>
  
  <tr><td height="5px"></td></tr>  
  
  <tr class="title">  
   <td width="150px" align="right">
    Total a pagar: 
   </td>
   <td><strong><?php echo number_format($totalaPagar,2,',','.')?></strong></td>
  </tr> 
  
  <tr><td height="5px"></td></tr>
  
 

</thead>
</table>

 <a  class="btn btn-primary" href="javascript:window.print()">Imprimir</a> <br> 

</div>
</div>