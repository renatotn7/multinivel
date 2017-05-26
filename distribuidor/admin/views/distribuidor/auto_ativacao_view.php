
<form name="formulario" method="post" action="">
    
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="130px"><strong>Senha Atual:</strong></td>
    <td><input type="password" class="validate[required]" name="senha" /></td>
  </tr>
  <tr>
    <td><strong>Nova Senha:</strong></td>
    <td><input type="password" id="senha" class="validate[required,minSize[6]]" name="new_senha" /></td>
  </tr>
  <tr>
    <td><strong>Repetir Senha:</strong></td>
    <td><input type="password" class="validate[required,equals[senha],minSize[6]]" /></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" class="btn btn-success" value="Salvar senha" />
    </td>
  </tr>  
</table>


</form>
