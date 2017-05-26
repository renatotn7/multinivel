
<div class="box-content min-height">
 <div class="box-content-header">Editar dados</div>
 <div class="box-content-body">

<?php if(get_user()->rf_tipo==1){?>
<div class="well">
 <a class="btn btn-info" href="<?php echo base_url('index.php/fabrica/editar_fabrica')?>">Editar Dados Fábrica</a>
</div>
<?php }?>


<form name="formulario" method="post" action="">
<p>
 <label>Nome:</label>
 <input type="text" name="rf_nome" class='validate[required]' size="50" value="<?php echo get_user()->rf_nome?>" />
</p> 

<p>
 <label>E-mail:</label>
 <input type="text" name="rf_email" class='validate[required,custom[email]]' size="50" value="<?php echo get_user()->rf_email?>" />
</p> 

<p>
 <label>Nova Senha:</label>
 <input type="password" name="rf_pw" id="pw1" size="20" value="" />
</p> 


<p>
<button class="btn btn-primary" type="submit">Salvar dados</button>
</p>
</form>

</div>
</div>
