<div class="box-content min-height">
    <div class="box-content-header">Gestão de Bônus</div>
    <div class="box-content-body">
        <hr>
        <ul id="myTab" class="nav nav-tabs">
            <li <?php echo $page == 1 ? ' class="active"' : ''; ?>><a href="#pl" data-toggle="tab">Bônus de Participação de Lucro (PL).</a></li>
            <li <?php echo $page == 2 ? ' class="active"' : ''; ?>><a href="#binario" data-toggle="tab" >Bônus Binário</a></li>
            <li <?php echo $page == 3 ? ' class="active"' : ''; ?>><a href="#indicacaodireta" data-toggle="tab">Bônus Indicação Direta</a></li>
            <li <?php echo $page == 4 ? ' class="active"' : ''; ?>><a href="#indicacaoindireta" data-toggle="tab">Bônus Indicação Indireta</a></li>
        </ul>

        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade  <?php echo $page == 1 ? 'in  active' : ''; ?>" id="pl">
                <form name="form" id="form" method="get"  action="<?php echo base_url('index.php/gestao_bonus/pagar_pl'); ?>" />
                <div class="alert">Obs.: A data informada será a mesma data do pagamento do bônus.
                    <br>Exemplo: Se informado <strong>16/04/2014</strong> o bônus será pago nesta data , diferente da cron que sempre paga o dia anterior.</div>
                <div class="row">
                    <div class="span2">
                        <strong> Informe a data:</strong><br>
                        <input type="text" name="data" id="data" value="" placeholder="Informe a data" class="date-filtro"/>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Liberar Bônus</button>
                </form>
            </div>
            <div class="tab-pane fade <?php echo $page == 2 ? 'in  active' : ''; ?>" id="binario">
                <h3>Em desenvolvimento! </h3>
            </div>
            <div class="tab-pane fade <?php echo $page == 3 ? 'in  active' : ''; ?>" id="indicacaodireta">
                <form name="form1" method="get" action="<?php echo base_url('index.php/gestao_bonus/verificar_bonus'); ?>">
                    <div class="row">
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label" for="inputError">Informe o Distribuidor:</label>
                                    <div class="input-append">
                                        <input type="text" name="di_usuario" id="data" value="<?php echo isset($_REQUEST['di_usuario']) && !empty($_REQUEST['di_usuario']) ? $_REQUEST['di_usuario'] : ''; ?>" placeholder="Informe o Distribuidor" class="distribuidor_cherck"/>
                                        <button class="btn" type="submit">Verificar Bônus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered table-hover">
                    <tr>
                        <th>Descricção</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                    <?php
                    if (count($registro_direto) > 0) {
                        foreach ($registro_direto as $rs) {
                            ?>  
                            <tr>
                                <td><?php echo $rs->cb_descricao; ?></td>
                                <td><?php echo $rs->cb_credito; ?></td>
                                <td><?php echo date("d/m/Y", strtotime($rs->cb_data_hora)); ?></td>                      
                                <td>
                                    <?php
                                    $usuario = get_usename($rs->cb_descricao);
                                    $di_id_indicado = $this->db->where('di_usuario', $usuario)->get('distribuidores')->row()->di_id;
                                    ?>
                                    <a href="<?php echo base_url('index.php/gestao_bonus/liberar_bonus_indicacao_direta?cb=' . $rs->cb_id . "&di_id=" . $rs->cb_distribuidor . "&indicado=" . $di_id_indicado . "&di_usuario=" . $_REQUEST['di_usuario']); ?>" class="btn btn-primary">Liberar Bônus</a>
                                </td>                      
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </table>
                <script type="text/javascript">
                    //verifica se o distribuidor existe.
                    $(document).ready(function() {

                        $('.distribuidor_cherck').click(function(e) {
                            if ($(this).parents('.control-group').length > 0) {
                                $(this).parents('.control-group').removeClass('error');
                            }
                            $(this).parent().parent().find('span').remove();
                        });

                        $('.distribuidor_cherck').blur(function(e) {
                            var input = this;

                            if ($(input).val().length > 2) {

                                $.ajax({
                                    url: '<?php echo base_url('index.php/gestao_bonus/verificar_distribuidor'); ?>',
                                    type: 'post',
                                    data: {di_usuario: $(this).val()},
                                    dataType: 'json',
                                    success: function(retorno) {
                                        if (retorno.response == 'ok') {
                                            $(input).parents('.control-group').addClass('success');
                                            $(input).parent().after('<span class="help-inline">' + retorno.response + '</span>');
                                        } else {
                                            $(input).val('');
                                            $(input).parents('.control-group').addClass('error');
                                            $(input).parent().after(' <span class="help-inline">' + retorno.response + '</span>');
                                        }
                                    }
                                });

                            }
                        });
                    });
                </script>
            </div>
            <div class="tab-pane fade <?php echo $page == 4 ? 'in  active' : ''; ?>" id="indicacaoindireta">

                <form name="form1" method="get" action="<?php echo base_url('index.php/gestao_bonus/verificar_indiretos'); ?>">
                    <div class="row">
                        <div class="span6">
                            <div class="control-group">
                                <div class="controls">
                                    <label class="control-label" for="inputError">Informe o Distribuidor:</label>
                                    <div class="input-append">
                                        <input type="text" name="di_usuario_indireto" id="data" value="<?php echo isset($_REQUEST['di_usuario_indireto']) && !empty($_REQUEST['di_usuario_indireto']) ? $_REQUEST['di_usuario_indireto'] : ''; ?>" placeholder="Informe o Distribuidor" class="distribuidor_cherck"/>
                                        <button class="btn" type="submit">Verificar Bônus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <?php   
                if (count($registro_indireto) > 0) {?>
                <form name="form1" method="post" action="<?php echo base_url('index.php/gestao_bonus/pagar_indiretos'); ?>">
                    <input type="hidden" name="di_id" id="di_id" value="<?php echo $id_distribuidor;?>"/>
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th>Geração</th>
                            <th>Nome</th>
                            <th>Usuário</th>
                            <th>Patrocinador</th>
                            <th>Data cadastro.</th>
                        </tr>
                        <?php
               
                            foreach ($registro_indireto as $rs) {
                                ?>  
                                <tr>
                                    <td><?php echo $rs['posicao']; ?>ª Geração</td>
                                    <td><?php echo $rs['distribuidor']->di_nome; ?></td>
                                    <td><?php echo $rs['distribuidor']->di_usuario; ?></td>
                                    <td><?php echo $rs['distribuidor']->di_usuario_patrocinador; ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($rs['distribuidor']->di_data_cad)); ?></td>                                    
                                </tr>
                                    <?php
                                }
                         
                            ?>

                    </table>
                    <button type="submit" class="btn btn-primary">Confirmar Pagamento</button>
                </form>
                <?php }else{
                    if(isset($_REQUEST['di_usuario_indireto'])){?>
                <table class="table table-bordered">
                    <tr>
                        <td style="text-align: center">Nenhum Registro foi encontrado.</td>
                    </tr>
                </table>
                <?php }
                }?>
            </div>
        </div>

    </div>

