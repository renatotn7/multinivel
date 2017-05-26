<div class="box-content min-height">
    <div class="box-content-header">Vendas</div>
    <div class="box-content-body">

        <?php
        $dis = $this->db->select(array('di_contrato', 'di_conta_verificada', 'di_data_verificacao'))->where('di_id', $this->uri->segment(3))->get('distribuidores')->row();
        ?>

        <br />
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url() ?>">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo base_url('index.php/documentos/listar') ?>">Documentos</a> <span class="divider">/</span></li>
        </ul>

        <form name="form1" method="post" action="<?php echo base_url('index.php/documentos/salvar_dados/' . $this->uri->segment(3)); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="4">
                <tr>
                    <td width="50%">
                        <div class="alert <?php echo $dis->di_contrato == 1 ? 'alert-success' : '' ?>">
                            <strong>DOCUMENTOS PESSOAIS E ENDEREÇO</strong>
                            <div>
                                Caso todos os arquivos de documentos pessoais forem aprovados, marque essa opção como 
                                <em>Documentação aprovada</em>.<br>
                                <select name="di_contrato">
                                    <option <?php echo $dis->di_contrato == 1 ? 'selected' : '' ?> value="1">Documentação aprovada</option>
                                    <option <?php echo $dis->di_contrato == 0 ? 'selected' : '' ?> value="0">Documentação pendente/reprovada</option>
                                </select>
                                <br>
                                <input type="submit" class="btn" value="Atualizar" />
                            </div>
                        </div>
                    </td>
                    <td>


<!--                        <div class="alert <?php echo $dis->di_conta_verificada == 1 ? 'alert-success' : '' ?>">
                            <strong>DOCUMENTOS DE DADOS BANCÁRIOS</strong>
                            <div>
                                Caso todos os arquivos de documentos bancários forem aprovados, marque essa opção como 
                                <em>Documentação aprovada</em>.<br>
                                <select name="di_conta_verificada">
                                    <option <?php echo $dis->di_conta_verificada == 1 ? 'selected' : '' ?> value="1">Documentação aprovada</option>
                                    <option <?php echo $dis->di_conta_verificada == 0 ? 'selected' : '' ?> value="0">Documentação pendente/reprovada</option>
                                </select><br>
                                <input type="submit" class="btn" value="Atualizar" />
                            </div>
                        </div>-->

                    </td>
                </tr>
            </table>

        </form>

        <?php if ($dis->di_data_verificacao) { ?>
            <h4>DATA DA VERIFICAÇÃO: <?php echo date('d/m/Y', strtotime($dis->di_data_verificacao)) ?></h4>
            <br />
        <?php } ?>
        <h4>Lista de Documentos</h4>
        <table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th width="21%" bgcolor="#f7f7f7">Data</th>
                <th width="43%" bgcolor="#f7f7f7">Status</th>
                <th width="20%" bgcolor="#f7f7f7">Documento</th>
                <th width="16%" bgcolor="#f7f7f7">Arquivo</th>
            </tr>
            <?php
            $docs = $this->db->where('do_distribuidor', $this->uri->segment(3))->get('documentos')->result();
            foreach ($docs as $d) {
                ?>
                <tr>
                    <td><?php echo date('d/m/Y | H:i', strtotime($d->do_data)) ?></td>
                    <td>
                        <form style="margin:0;" method="post" action="<?php echo base_url('index.php/documentos/salvar_status/' . $this->uri->segment(3)); ?>">
                            <?php echo status_doc($d->do_status) ?>
                            <input type="hidden" name="do_id" value="<?php echo $d->do_id ?>" />
                            <input type="hidden" name="do_distribuidor" value="<?php echo $d->do_distribuidor ?>" />
                            <select id="select<?php echo $d->do_id ?>" style="width:245px; margin:0;" name="do_status">
                                <option <?php echo $d->do_status == 1 ? 'selected' : '' ?> value="1">Em análise</option>
                                <option <?php echo $d->do_status == 0 ? 'selected' : '' ?> value="0">Reprovado</option>
                                <option <?php echo $d->do_status == 2 ? 'selected' : '' ?> value="2">Aprovado</option>
                            </select>
                            <span style="display:none;" class="label label-info textarea<?php echo $d->do_id ?>">Informe o motivo:</span>
                            <textarea class="textarea<?php echo $d->do_id ?>" name="do_mensagem" style="margin: 8px 0px; width: 365px; display:none;"><?php echo $d->do_mensagem; ?></textarea>
                            <input class="btn" type="submit" value="OK"/>
                        </form>
                        <script>
                            $("#select<?php echo $d->do_id ?>").on('change', function() {
                                if ($(this).val() === "0") {
                                    $(".textarea<?php echo $d->do_id ?>").slideDown('fast');
                                } else {
                                    $(".textarea<?php echo $d->do_id ?>").slideUp('fast');
                                }
                            });
                        </script>
                    </td>
                    <td><?php echo $d->do_categoria ?></td>
                    <td><a target="_blank" onclick="grid('<?php echo base_url('index.php/documentos/ver_arquivo/' . $d->do_id) ?>', 'Ver Arquivo', '1000', '500');" href="javascript:void(0)">VER</a></td>
                </tr>
            <?php } ?> 
        </table>

        <?php

        function status_doc($s) {
            switch ($s) {
                case 0:echo "<span class='label label-important'>Reprovado</span>";
                    break;
                case 1:echo "<span class='label label-warning'>Em análise</span>";
                    break;
                case 2:echo "<span class='label label-success'>Aprovado</span>";
                    break;
            }
        }
        ?>
    </div>
</div>