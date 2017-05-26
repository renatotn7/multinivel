
<style>
    #autocomplete {
        position: absolute;
        width: 425px;
        margin: -9px 0px;
        border: 1px solid #CCC;
        background: #FFF;
        z-index: 9999;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        display: none;
    }

    #autocomplete ul {
        list-style: none;
    }

    #autocomplete ul li {
        margin-left: -25px;
        padding-left: 6px;
    }

    #autocomplete ul li:hover {
        background: #ddd;
    }
</style>
<div class="box-content min-height">
    <div class="box-content-header">Monte seu relatório</div>
    <div class="box-content-body">
        <div class="painel">
            <h5 style="margin: 4px">Relatório de Usuário da Rede.</h5>
            <!-- inicicio  do conteúdo  -->

            <div class="row">
                <form action="<?php echo base_url('index.php/relatorios/relatorio_usuario_rede_buscar'); ?>" method="get">
                    <div class="span7">

                        <input type="hidden" name="di_id" id="di_id"
                               value="<?php echo isset($_GET['di_id']) ? $_GET['di_id'] : ''; ?>">
                        <div class="input-append">
                            <input type="text" class="input-xxlarge" name="name" id="name"
                                   autocomplete="off" style="width: 413px"
                                   value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>">
                            <button class="btn" type="submit">Buscar</button>
                        </div>
                        <div id="autocomplete">
                            <ul>

                            </ul>
                        </div>
                    </div>

                </form>
            </div>
            <?php if ($distribuidores) { ?>
                <p><?php echo count($distribuidores) ?> registros encontrados</p>
                <table class="table table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Usuário</th>
                            <th>Patrocinador</th>
                            <th>Plano</th>
                            <th>Telefones</th>
                            <th>E-mail</th>
                            <th>Saldo</th>
                            <th>País</th>
                        </tr>
                    </thead>
                    <?php
                    foreach ($distribuidores as $distribuidor) {
//                        $pontos = new Pontos($distribuidor);
                        ?>
                        <tr>
                            <td><?php echo $distribuidor->di_nome; ?></td>
                            <td><?php echo $distribuidor->di_usuario; ?></td>
                            <td><?php echo $distribuidor->di_usuario_patrocinador; ?></td>
                            <td><?php echo DistribuidorDAO::getPlano($distribuidor->di_id)->pa_descricao; ?></td>
                            <td><?php echo $distribuidor->di_fone1; ?>
                               <br /><?php echo $distribuidor->di_fone2; ?>
                            <td><?php echo $distribuidor->di_email; ?></td>
                            <td>US$ <?php echo number_format($distribuidor->di_saldo, 2, '.', ','); ?></td>
                            <td><?php 
                            $pais = $this->db->query("select sql_cache ps_nome from cidades
                                                      JOIN pais ON ps_id = ci_pais 
                                                      where ci_id= {$distribuidor->di_cidade}")->row();
                            echo $pais->ps_nome; ?></td>
                            </td>


                        </tr>
                <?php } ?>
                </table>
                <?php } ?>
            <!-- fim do conteúdo -->
        </div>
    </div>
</div>

<script>
    (function($) {

        $('#name').keyup(function() {
            var html = "";

            if ($(this).val().length > 3)
            {
                $.ajax({
                    url: '<?php echo base_url("index.php/autocomplete/autocompleteDistribuidores"); ?>',
                    dataType: 'json',
                    data: {nome: $(this).val()},
                    success: function(data)
                    {

                        $("#autocomplete").css('display', 'block');
                        if (data.error == 0) {
                            for (var x in data.data)
                            {
                                html += "<li rel='" + data.data[x].di_usuario + "'>" + data.data[x].di_usuario + "</li>";
                            }
                        } else {
                            html += "<li>Nenhum resultado</li>";
                        }


                        $("#autocomplete").find('ul').html(html);
                    }

                });
            }

        });

        $('#autocomplete').on('click', 'li', function() {
            $('#name').val($(this).attr('rel'));
            $("#autocomplete").css('display', 'none');
        });

    })(jQuery);

</script>