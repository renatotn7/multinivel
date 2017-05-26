<?php 
$d = $this->db
->join('cidades','ci_id=di_cidade')
->where('di_id',get_user()->di_id)->get('distribuidores')->row();
?>

<div class="label label-info">Primeiro Titular</div>
<br />
<br />
<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="120px">Nome:</td>
    <td><input type="text" disabled="disabled" value="<?php echo $d->di_nome?>" /></td>
  </tr>
  <tr>
    <td>Sexo:</td>
    <td><input type="text" disabled="disabled" value="<?php echo $d->di_sexo=='M'?'Masculino':'Feminino'?>" /></td>
  </tr>
  <tr>
    <td>CPF:</td>
    <td><input type="text" disabled="disabled" value="<?php echo $d->di_cpf?>" /></td>
  </tr>
  <tr>
    <td>Data Nasc:</td>
    <td><input type="text" disabled="disabled" value="<?php echo date('d/m/Y',strtotime($d->di_data_nascimento))?>" /></td>
  </tr>
</table>

<div class="label label-info">Segundo Titular</div>
<br />
<br />


<form id="form1" name="form1" method="post" action="<?php echo base_url('index.php/distribuidor/salvar_info')?>">

 <input type="hidden" name="url" value="<?php echo current_url()?>" /> 
<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="120px">Nome:</td>
    <td><input type="text" name="di_titular2_nome" value="<?php echo $d->di_titular2_nome?>" /></td>
  </tr>
  <tr>
    <td>Sexo:</td>
    <td>
    <select name="di_titular2_sexo">
     <option <?php echo $d->di_titular2_sexo=='M'?'selected':''?> value="M">Masculino</option>
     <option <?php echo $d->di_titular2_sexo=='F'?'selected':''?> value="F">Feminino</option>
    </select>
    </td>
  </tr>
  <tr>
    <td>CPF:</td>
    <td><input type="text" name="di_titular2_cpf" class="mcpf validate[required]" value="<?php echo $d->di_titular2_cpf?>" /></td>
  </tr>
  <tr>
    <td>Data Nasc:</td>
    <td><input type="text" name="di_titular2_nascimento" class="mdata validate[required]" value="<?php echo date('d/m/Y',strtotime($d->di_titular2_nascimento))?>" /></td>
  </tr>

<tr>
<td colspan="2">
<div class="alert">
Para alterar seus dados<br />
Informe a senha de segurança:<br />
<input type="password" class="validate[required]" name="senha_segurancao" />
</div>
</td>
</tr>  
  
   <tr>
    <td></td>
    <td><button type="submit" class="btn btn-success">Salvar dados</button></td>
  </tr> 
</table>
</form>
