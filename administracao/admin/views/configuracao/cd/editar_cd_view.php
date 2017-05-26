
<div class="box-content min-height">
 <div class="box-content-header"><a href="<?php echo base_url('index.php/cd')?>"> CD</a> &raquo; CD(Centro de distribuição)</div>
 <div class="box-content-body">
 

<form name="formulario" method="post" action="">

<?php $c = $this->db
->join('cidades','ci_id = cd_cidade')
->where('cd_id',$this->uri->segment(3))->get('cd')->result();?>


<table width="100%" border="0" cellspacing="0" cellpadding="7">
  <tr>
    <td width="156"><label>Nome/Empresa:</label></td>
    <td width="1013"><input type="text" class="validate[required]" name="cd_nome" value="<?php echo $c[0]->cd_nome?>" size="50"  /></td>
  </tr>
  
  <tr>
    <td width="156"><label>Representante legal:</label></td>
    <td><input type="text" class="validate[required]" name="cd_responsavel_nome" value="<?php echo $c[0]->cd_responsavel_nome?>" size="50"  /></td>
  </tr>  
  <tr>
    <td width="156"><label>CPF/CNPJ:</label></td>
    <td><input type="text" class="validate[required]" name="cd_documento1" size="20" value="<?php echo $c[0]->cd_documento1?>" maxlength="20"  /></td>
  </tr>  
  <tr>
    <td width="156"><label>RG/IE:</label></td>
    <td><input type="text" class="validate[required]" name="cd_documento2" size="20" value="<?php echo $c[0]->cd_documento2?>" maxlength="30"  /></td>
  </tr> 

  <tr>
    <td width="156"><label>Endereço:</label></td>
    <td><input type="text" class="validate[required]" name="cd_endereco" size="50" value="<?php echo $c[0]->cd_endereco?>" maxlength="200"  /></td>
  </tr>  

  <tr>
    <td width="156"><label>Bairro:</label></td>
    <td><input type="text" class="validate[required]" name="cd_bairro" size="50" value="<?php echo $c[0]->cd_bairro?>" maxlength="200"  /></td>
  </tr>

  <tr>
    <td width="156"><label>Número:</label></td>
    <td><input type="text" class="validate[required]" name="cd_numero" value="<?php echo $c[0]->cd_numero?>" size="10" maxlength="10"  /></td>
  </tr>

  <tr>
    <td width="156"><label>CEP:</label></td>
    <td><input type="text" class="validate[required]" name="cd_cep" size="20" value="<?php echo $c[0]->cd_cep?>" maxlength="9"  /></td>
  </tr>  

  <tr>
    <td><label>Estado:</label></td>
    <td>
    <select name="cd_uf"  class="ajax-uf validate[required]">
     <?php $es = $this->db->get('estados')->result();
	 foreach($es as $e){
	 ?>
     <option <?php echo $e->es_id==$c[0]->ci_estado?"selected":""?> value="<?php echo $e->es_id?>"><?php echo $e->es_uf?></option>
     <?php }?>
    </select>
    </td>
  </tr>
  <tr>
    <td><label>Cidade:</label></td>
    <td>
    <select name="cd_cidade" disabled="disabled" class="recebe-cidade validate[required]">
    <option value="<?php echo $c[0]->ci_id?>"><?php echo $c[0]->ci_nome?></option>
    </select>
    </td>
  </tr> 

  <tr>
    <td><label>Telefone:</label></td>
    <td><input type="text" name="cd_fone1" class="validate[required]" value="<?php echo $c[0]->cd_fone1?>" size="20" maxlength="14"  /></td>
  </tr> 

  <tr>
    <td><label>Celular:</label></td>
    <td><input type="text" name="cd_fone2" size="20" maxlength="14" value="<?php echo $c[0]->cd_fone2?>"  /></td>
  </tr>

  <tr>
    <td><label>Situação do CD:</label></td>
    <td>
    <select name="cd_ativo">
    <option <?php echo $c[0]->cd_ativo==1?"selected":""?> value="1">Ativo</option>
    <option <?php echo $c[0]->cd_ativo==0?"selected":""?> value="0">Desabilitado</option>
    
    </select>
    </td>
  </tr> 

  <tr>
    <td><label>Tipo de CD:</label></td>
    <td>
    <select name="cd_suporte">
    <option <?php echo $c[0]->cd_suporte==0?"selected":""?> value="0">CD padrão</option>
    <option <?php echo $c[0]->cd_suporte==1?"selected":""?> value="1">CD suporte</option>
    </select>
    </td>
  </tr> 

  <tr>
    <td><label>E-mail:</label></td>
    <td><input type="text" name="cd_email" class="validate[required]" value="<?php echo $c[0]->cd_email?>" size="25" maxlength="200"  /></td>
  </tr>

  <tr>
    <td><label>Nova senha:</label></td>
    <td><input type="password" name="cd_pw" size="25" maxlength="14"  />
      Redefinir senha, caso queira manter a senha deixe em branco</td>
  </tr> 
       
 <tr>
    <td><input type="submit" class="btn btn-success" value="Salvar dados"></td>
    <td></td>
  </tr>                 
</table>


</form>

</div>
</div>
