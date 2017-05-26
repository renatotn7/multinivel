<div class="box-content min-height">
 <div class="box-content-header">Notícias</div>
 <div class="box-content-body">

<form name="form1" id="validar_formulario" method="post" action="<?php echo base_url('index.php/'.$modulo."/salvar_update/".$this->uri->segment(3))?>">

<div class="content">
<b>Adicionar: <?php echo $module_descricao?></b>
</div>
<div class="content">

<p>
Titulo:<br />
<input type="text" class="input-xlarge" name="titulo" value="<?php echo $d->titulo?>" />
</p>


<p>
Descrição:<br />
<textarea name="texto" style="width:500px; height:150px;"><?php echo $d->texto?></textarea>
</p>

<p>
Situação:<br />
<select name="status">
 <option <?php echo $d->status==1?'selected':''?> value="1">Habilitado</option>
 <option <?php echo $d->status==0?'selected':''?> value="0">Desabilitado</option>
</select>
</p>


Estilo:<br />

<table width="400px" border="0" cellspacing="0" cellpadding="0">
  <tr>

    <td>
    <div class="alert alert-success" style="width:100px;">
     <input <?php echo $d->cor=='alert-success'?'checked':''?> style="margin:0; padding:0;" type="radio" name="cor" value="alert-success" /> Geral Verde
    </div>
    </td>

    <td>

    <div class="alert alert-error" style="width:100px;">
     <input style="margin:0; padding:0;" type="radio" name="cor" <?php echo $d->cor=='alert-danger'?'checked':''?> value="alert-danger" /> Importante
    </div>
    </td>

    <td>
    <div class="alert alert-info" style="width:100px;">
     <input style="margin:0; padding:0;" type="radio" name="cor" value="alert-info" <?php echo $d->cor=='alert-info'?'checked':''?> /> Informação
    </div>
    </td>
    <td>

    <div class="alert" style="width:100px;">
     <input style="margin:0; padding:0;" type="radio" name="cor" value="alert-warning" <?php echo $d->cor=='alert-warning'?'checked':''?> /> Alerta
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

