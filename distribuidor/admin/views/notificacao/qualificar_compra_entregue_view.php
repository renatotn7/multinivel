<?php
$this->lang->load('distribuidor/notificacao/qualificar_compra_entregue');
?>

<script>
    function show()
    {
        $('#formulario').removeClass('hide').addClass('in');
        $('#texto').removeClass('in').addClass('hide');

    }
    $('#data_entrega').mask('?99/99/9999');
    jQuery("#form").validationEngine();
    jQuery(function() {
        jQuery("form").validationEngine();
        //class='validate[required]'
        $(".date-filtro").datepicker({
            dateFormat: "dd/mm/yy",
            dayNamesMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
            monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]
        });
    });
</script>

<div class="row in" id="texto">
    <div class="span">
        <div class="alert" style="font-size: 18px;padding:155px 0px 155px 0px;text-align:center; ">
            <a href="javascript:show();"><?php echo $this->lang->line('mensagem_informe'); ?></a>
        </div>
    </div>
</div>

<div class="row hide" id="formulario" style="margin:20px;">
    <div class="span">
        <form name="form" id="form" method="post" action="<?php echo base_url('index.php/compra_entregue/salvar_produto_entregue'); ?>">
            <div class="row">
                <div>
                    <?php
                    $compras = get_produtosentregues();
                    ?>
                    <table>
                        <tr>
                            <td>Pedido:</td>
                            <td>
                                <select class="validate[required]" name="pedido" style="width:100px;">
                                    <option></option>
                                    <?php foreach ($compras as $compra) { ?>
                                        <option><?php echo $compra->co_id; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Data da entrega:</td>
                            <td><input class="mdata date-filtro validate[required]" type="text" name="data_entrega" style="width: 100px;"></td>
                        </tr> 
                        <tr>
                            <td>Quem entregou:</td>
                            <td><input type="text" name="quem_entregou"></td>
                        </tr>
                        <tr>
                            <td>Satisfação:</td>
                            <td>
                                <select name="satisfacao" class="validate[required]">
                                    <option></option>
                                    <option>Ruim</option>
                                    <option>Bom</option>
                                    <option>Otimo</option>
                                    <option>Excelente</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Observação</td>
                            <td><textarea name="observacao"></textarea></td>
                        </tr>
                    </table>
                    <input type="hidden" name="ip" value="<?php echo $this->input->ip_address();?>">
                    Envie as fotos de seu produto e sua satisfação para o e-mail marketing2@Nossa Empresa.net
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <span class="span">  <button class="btn btn-info" type="submit">Salvar</button></span>
            </div>
        </form>
    </div>
</div>
