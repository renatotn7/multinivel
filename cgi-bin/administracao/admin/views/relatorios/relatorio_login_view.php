<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/bootstrap/css/bootstrap.css" />
<div class="box-content min-height">
    <div class="box-content-body">
<h1>Relatório Dados Login</h1>

<form name="formulario" action="" method="get">
    Data: <input type="text" id="campo" style="width:120px; margin:0;text-align:center;" class="mdata date-filtro" value="<?php echo date('d/m/Y') ?>" name="data" />
<!--    Situação :
    <select name="pendente">
     <option value="1" <?php //echo get_parameter("pendente")==1?"selected='selected'":''?>>Pendentes</option>
     <option value="2" <?php //echo get_parameter("pendente")==2?"selected='selected'":''?>>Qualificados</option>
    </select>-->
    <input type="submit" class="btn btn-primary" value="Filtrar">
</form>

</div>
</div>

<table width="95%" class="table table-bordered table-hover" class="table" cellspacing="0" cellpadding="0">
    <tr>
        <td>Código</td>
        <td>Data Cadastro</td>
        <td>Hora Cadastro</td>
        <td>Nome</td>
        <td>Sobrenome</td>
        <td>Usuário</td>
        <td>Login do Patrocinador</td>
        <td>Documento</td>
        
        
    </tr>
    
   <?php foreach ($distribuidores as $distribuidor) {?>
    <tr>
        <td><?php echo $distribuidor->di_id;?></td>
        <td><?php echo date('d/m/Y',  strtotime($distribuidor->di_data_cad));?></td>
        <td><?php echo date('H:i:s',  strtotime($distribuidor->di_data_cad));?></td>
        <td><?php echo $distribuidor->di_nome;?></td>
        <td><?php echo $distribuidor->di_ultimo_nome;?></td>
        <td><?php echo $distribuidor->di_usuario;?></td>
        <td><?php echo $distribuidor->di_usuario_patrocinador;?></td>
        <td>
            <?php echo empty($distribuidor->di_tipo_documento)?'CPF ':$distribuidor->di_tipo_documento;?>: 
            <?php echo empty( $distribuidor->di_rg)? $distribuidor->di_cpf: $distribuidor->di_rg;?>
        </td>
    </tr>

    <tr>
        <td colspan="8">
            <table>
                <tr>
                    <td>Endereço</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
      <tr>
          <td  colspan="8"><hr></td>
    </tr>
   <?php }?>
</table>