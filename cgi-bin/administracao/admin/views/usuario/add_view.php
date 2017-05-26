<div class="box-content min-height">
 <div class="box-content-header">
 <a href="<?php echo base_url('index.php/usuario')?>">
 Usuários 
 </a>
 &raquo; Criar Novo </div>
 <div class="box-content-body">

   <form method="post" action="<?php echo base_url('index.php/usuario/salvar_usuario')?>">
     Nome:<br />
     <input type="text" class="validate[required]" name="rf_nome" />
     <br />
     E-mail:<br />
     <input type="text" class="validate[required, custom[email]]" name="rf_email" />
     <br />
     Senha:<br />
     <input type="password" class="validate[required, min[8]]" name="senha" /> 
     <br />
        <div style="margin:7px 0;" class="alert alert-info"><h4>Dica de Senha</h4>No minimo 8 caracteres, não pode ser sequêncial.</div>
  
  
     
     
     <input type="submit" class="btn btn-primary" value="Salvar Usuário" />
     <a class="btn" href="<?php echo base_url('index.php/usuario')?>">Cancelar</a>
     
   </form>
 </div>
 </div>