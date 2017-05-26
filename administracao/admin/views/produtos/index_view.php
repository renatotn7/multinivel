<style>
    #myModalImagem > .modal-footer{
        background: #FFF !important;
    }
    #myModalImagem > .modal-title{
        box-sizing: border-box;
        color: rgb(51, 51, 51);
        direction: ltr;
        display: block;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 18px;
        font-weight: 500;
        height: 25px;
        line-height: 25.7142868041992px;
        margin-bottom: 0px;
        margin-left: 0px;
        margin-right: 0px;
        margin-top: 0px;
        text-align: center;
        width: 733.125px;
    }
    #myModalImagem{

    }
    .preload{
        margin-top: 29px;
        margin-left: 247px;
        width: 43px; display: none;
    }

    .thumbnails > li {
        float: left;
        margin-bottom: 20px;
        margin-left: 24px !important;
    }
</style>

<div class="box-content min-height">
    <div class="box-content-header">Produtos</div>
    <div class="box-content-body">

        <?php if (permissao('produtos', 'adicionar', get_user())) { ?>
            <div class="menu-interno">
                <a style="float:right;" class="btn btn-success" href="<?php echo base_url('index.php/produtos/adicionar') ?>">Adicionar produto</a>
            </div>
        <?php } ?>

        <div class="">
            <form method="get"  name="formulario">
                Nome: <input style="margin:0;" value="<?php echo get_parameter('nome') ?>" name="nome" type="text" size="30" />
                Categoria: 
                <select name="cat" style="margin:0;">
                    <option value="">----</option>
                    <?php
                    categoria_option($this->db, 0);
                    ?>
                </select>

                <input class="btn btn-info" type="submit" value="Buscar" />
            </form>

            <table id="table-listagem" width="100%" border="0" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                        <td width="7%" bgcolor="#f7f7f7"></td>
                        <td width="7%" bgcolor="#f7f7f7"><strong>Código</strong></td>
                        <td width="10%" bgcolor="#f7f7f7"><strong>Código - Alfa Nº</strong></td>
                        <td width="25%" bgcolor="#f7f7f7"><strong>Produto</strong></td>
                        <td width="17%" bgcolor="#f7f7f7"><strong>Categoria</strong></td>
                        <td width="17%" bgcolor="#f7f7f7"><strong>Valor</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($produtos as $p) {
                        ?>

                        <tr>
                            <td>
                                <div class="btn-toolbar" style="margin: 0;">
                                    <div class="btn-group">
                                        <?php if (permissao('produtos', 'editar', get_user())) { ?>
                                            <button type="button" class="btn"  onclick="window.open('<?php echo base_url('index.php/produtos/editar_produto/' . $p->pr_id) ?>', '_self');" >Editar</button>
                                        <?php } else { ?>
                                            <button type="button" class="btn" >Ação</button>
                                        <?php } ?>
                                        <button style="padding: 8px;" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <?php if (permissao('produtos', 'excluir', get_user())) { ?>
                                                <li>
                                                    <a onclick="return confirm('Deseja excluir esse produto?');" href="<?php echo base_url("index.php/produtos/remover_protudo/{$p->pr_id}"); ?>" target="_self"><i class="icon-trash"></i> Remover esse produto</a>
                                                </li>
                                            <?php } ?>
                                            <?php if (permissao('produtos', 'excluir', get_user())) { ?>
                                                <li>
                                                    <a rel="<?php echo $p->pr_id; ?>" href="javascript:void(0);" target="_self" class="addProduto"><i class="icon-camera"></i> Enviar Imagem</a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div><!-- /btn-group -->
                                </div>
                            </td>
                            <td><?php echo $p->pr_id ?></td>
                            <td><?php echo $p->pr_codigo ?></td>
                            <td><?php echo $p->pr_nome ?></td>
                            <td><?php echo $p->ca_descricao ?></td>
                            <td>US$ <?php echo number_format($p->pr_valor, 2, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
                </tbody> 
            </table>
        </div>
        <?php echo $links ?>


    </div>
</div>


<script type="text/javascript">
    $(document).on('click', '.addProduto', function() {
        $('#pr_id').val($(this).attr('rel'));
        $('#myModalImagem').modal('hide');
        $('#myModal').modal('show');
        $('.preload-1').css('display', 'block');
        $('.thumbnails').load('<?php echo base_url('index.php/produtos/get_imagens?pr_id='); ?>' + $(this).attr('rel'),
                function() {
                    $('.preload-1').css('display', 'none');
                }
        );
    });

    $(document).on('click', '.viewProduto', function() {

        $('#myModalImagem').modal('show');
        $('#myModal').modal('hide');
        $('.sair').attr('rel', $('#pr_id').val());
        $('#img_id').val($(this).attr('rel'));

        //CHAMANDO A IMAGEM.
        $('.preload-2').css('display', 'block');
        $('.imagem-contend').load('<?php echo base_url('index.php/produtos/get_imagem?img_id='); ?>' + $(this).attr('rel'),
                function() {
                    $('.preload-2').css('display', 'none');

                }
        );
    });

    $(document).on('click', '#arquivo', function() {
        $('.bar').css('width', '0%');
    });
//upload de arquivos 
    $(document).on('click', '#btnEnviar', function() {
        $('.bar').css('width', '0%');
        $('#formUpload').ajaxForm({
            uploadProgress: function(event, position, total, percentComplete) {
                $('.bar').css('width', percentComplete + '%');
                $('.bar').html(percentComplete + '%');
            },
            success: function(data) {
                $('.bar').css('width', '100%');
                $('.bar').html('100%');
                if (data.sucesso == true) {
                    $('.thumbnails').append('<li><img rel="' + data.img_id + '"class="viewProduto" style="cursor: pointer;" data-src="holder.js/160x120" alt="160x120" src="' + data.msg + '" /></li>');
                }
                else {
                    $('.error_upload').html(data.msg);
                }
            },
            error: function() {
                $('.error_upload').html('Erro ao enviar requisição!!!');
            },
            dataType: 'json',
            url: '<?php echo base_url('index.php/enviar_arquivo'); ?>',
            resetForm: true
        }).submit();
    });

</script>
<!--modal da escolha do produtos-->
<div id="myModal" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Envio de imagem</h3>
    </div>
    <form name="formUpload" id="formUpload" method="post">
        <input type="hidden" name="pr_id" id="pr_id" value="">
        <div class="modal-body">
            <label for="arquivo">Selecione o arquivo:</label>
            <input type="file" name="arquivo" id="arquivo" size="45" />
            <div class="progress progress-success progress-striped active">
                <div class="bar" style="width: 0%"></div>
            </div>
            <div style="display: none;" class="alert error_upload">

            </div>
            <img class="preload preload-1" src="<?php echo base_url("/public/imagem/loading.gif"); ?>" />
            <!--miniaturas enviadas--> 
            <ul class="thumbnails">

            </ul>
        </div>
        <div class="modal-footer">
            <a href="<?php echo base_url('/index.php/produtos/'); ?>" class="btn">Cancela</a>
            <button  id="btnEnviar" class="btn btn-primary" type="button" > Fazer Upload</button>
        </div>
    </form>
</div>

<!--modal da escolha do produtos-->
<div id="myModalImagem" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Visualizar</h4>
    </div>
    <div class="modal-body" >
        <img class="preload preload-2" src="<?php echo base_url("/public/imagem/loading.gif"); ?>" />
        <span class="imagem-contend"></span>
    </div>
    <div class="modal-footer">
         <form name="excluir-imagem" method="post"  action="<?php echo base_url('index.php/produtos/remover_imagem'); ?>">
        <button  class="btn addProduto sair" type="button"> Voltar</button>
       
            <input type="hidden" name="img_id" id="img_id" value=''/>
            <button class="btn btn-danger" type="submit"><i class="icon-trash icon-white"></i> Excluir</button>
        </form>
        <!--<a href="#" class="btn btn-primary"><i class=" icon-chevron-right icon-white"></i> Próximo</a>-->

    </div>
</form>
</div>

<?php

function categoria_option($db, $pai, $traco = 0) {
    $cat = $db->where('ca_pai', $pai)->get('categorias_produtos')->result();
    if (count($cat)) {
        foreach ($cat as $c) {
            echo "<option value='" . $c->ca_id . "'>" . str_repeat('-', $traco) . "{$c->ca_descricao}</option>";
            if ($db->where('ca_pai', $c->ca_id)->get('categorias_produtos')->num_rows > 0) {
                categoria_option($db, $c->ca_id, $traco++);
            }
        }
    }
}
?>

