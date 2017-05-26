<div class="box-content min-height">
 <div class="box-content-header">Notícias</div>
 <div class="box-content-body">

<form name="form1" id="validar_formulario" method="post" action="<?php echo base_url('index.php/'.$modulo."/salvar_novo")?>">

<div class="content">
<b>Adicionar: <?php echo $module_descricao?></b>
</div>
<div class="content">

<p>
Titulo:<br />
<input type="text" class="input-xlarge" name="titulo" />
</p>


<p>
Descrição:<br />
<textarea name="texto" style="width:500px; height:150px;"></textarea>
</p>

<p>
Situação:<br />
<select name="status">
 <option value="1">Habilitado</option>
 <option value="0">Desabilitado</option>
</select>
</p>

Estilo:<br />

<table width="400px" border="0" cellspacing="0" cellpadding="0">
  <tr>

    <td>
    <div class="alert alert-success" style="width:100px;">
     <input style="margin:0; padding:0;" checked="checked" type="radio" name="cor" value="alert-success" /> Geral Verde
    </div>
    </td>

    <td>
    
    <div class="alert alert-error" style="width:100px;">
     <input style="margin:0; padding:0;" type="radio" name="cor" value="alert-error" /> Importante
    </div>
    </td>
    
    <td>
    <div class="alert alert-info" style="width:100px;">
     <input style="margin:0; padding:0;" type="radio" name="cor" value="alert-info" /> Informação
    </div>
    </td>
    <td>
    
    <div class="alert" style="width:100px;">
     <input style="margin:0; padding:0;" type="radio" name="cor" value="alert" /> Alerta
    </div>
    
    </td>
  </tr>
</table>











<p>
    <input value="Salvar" class="btn btn-primary" type="submit">
  <a class="btn" href="<?php echo base_url('index.php/'.$modulo.'/listar')?>">Fechar</a>
</p>
 
 </div>
</form>

</div>
</div>

<script>
function aplicar(){
	$("#validar_formulario").attr('action','<?php echo base_url('index.php/'.$modulo."/salvar_novo".'?aplicar=sim')?>');
	$("#validar_formulario").submit();
	}
</script>
