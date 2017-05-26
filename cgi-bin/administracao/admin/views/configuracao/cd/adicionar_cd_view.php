<div class="box-content min-height">
 <div class="box-content-header"><a href="<?php echo base_url('index.php/cd')?>"> CD</a> &raquo; CD(Centro de distribuição)</div>
 <div class="box-content-body">

<form name="formulario" method="post" action="">


<table width="100%" border="0" cellspacing="0" cellpadding="7">
  <tr>
    <td width="153"><label>Nome/Empresa:</label></td>
    <td width="1016"><input type="text" class="validate[required]" name="cd_nome" size="50"  /></td>
  </tr>
  
  <tr>
    <td width="153"><label>Representante legal:</label></td>
    <td><input type="text" class="validate[required]" name="cd_responsavel_nome" size="50"  /></td>
  </tr>  
  <tr>
    <td width="153"><label>CPF/CNPJ:</label></td>
    <td><input type="text" class="validate[required]" name="cd_documento1" size="20" maxlength="20"  /></td>
  </tr>  
  <tr>
    <td width="153"><label>RG/IE:</label></td>
    <td><input type="text" class="validate[required]" name="cd_documento2" size="20" maxlength="30"  /></td>
  </tr> 

  <tr>
    <td width="153"><label>Endereço:</label></td>
    <td><input type="text" class="validate[required]" name="cd_endereco" size="50" maxlength="200"  /></td>
  </tr>  

  <tr>
    <td width="153"><label>Bairro:</label></td>
    <td><input type="text" class="validate[required]" name="cd_bairro" size="50" maxlength="200"  /></td>
  </tr>

  <tr>
    <td width="153"><label>Número:</label></td>
    <td><input type="text" class="validate[required]" name="cd_numero" size="10" maxlength="10"  /></td>
  </tr>

  <tr>
    <td width="153"><label>CEP:</label></td>
    <td><input type="text" class="validate[required]" name="cd_cep" size="20" maxlength="9"  /></td>
  </tr>  

  <tr>
    <td><label>Estado:</label></td>
    <td>
    <select name="cd_uf"  class="ajax-uf validate[required]">
     <?php $es = $this->db->get('estados')->result();
	 foreach($es as $e){
	 ?>
     <option value="<?php echo $e->es_id?>"><?php echo $e->es_uf?></option>
     <?php }?>
    </select>
    </td>
  </tr>
  <tr>
    <td><label>Cidade:</label></td>
    <td>
    <select name="cd_cidade" disabled="disabled" class="recebe-cidade validate[required]">
    <option value="">--Selecione o estado--</option>
    
    </select>
    </td>
  </tr> 

  <tr>
    <td><label>Telefone:</label></td>
    <td><input type="text" name="cd_fone1" class="validate[required]" size="20" maxlength="14"  /></td>
  </tr> 

  <tr>
    <td><label>Telefone:</label></td>
    <td><input type="text" name="cd_fone2" size="20" maxlength="14"  /></td>
  </tr>

  <tr>
    <td><label>Situação do CD:</label></td>
    <td>
    <select name="cd_ativo">
    <option value="1">Ativo</option>
    <option value="0">Desabilitado</option>
    
    </select>
    </td>
  </tr> 


  <tr>
    <td><label>Tipo de CD:</label></td>
    <td>
    <select name="cd_suporte">
    <option value="0">CD padrão</option>
    <option value="1">CD suporte</option>
    </select>
    </td>
  </tr> 


  <tr>
    <td><label>E-mail:</label></td>
    <td><input type="text" name="cd_email" class="validate[required]" size="25" maxlength="200"  /></td>
  </tr>

  <tr>
    <td><label>Senha:</label></td>
    <td><input type="password" name="cd_pw" class="validate[required]" size="25" maxlength="14"  /></td>
  </tr> 
       
 <tr>
    <td><input type="submit" class="btn btn-success" value="Salvar dados"></td>
    <td></td>
  </tr>                 
</table>


</form>

</div>
</div>
