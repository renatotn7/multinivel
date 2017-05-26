<?php
$api_nome = '';
$api_url = '';
$api_status = 'Ativo';
$url_action = base_url('index.php/usuario_api/addUsuario');

if ($this->input->get('indet')) {
    $usuario = usuarioAPIModel::getUsuario($this->input->get('indet'));
    if (count($usuario) > 0) {

        $api_nome = $usuario->api_nome;
        $api_url = $usuario->api_url;
        $api_status = $usuario->api_status;
        $url_action = base_url('index.php/usuario_api/AtualizarUsuario?indet=' . $this->input->get('indet'));
    } else {
        redirect(base_url('index.php/usuario_api'));
    }
}
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>

<div class="box-content min-height">
    <div class="box-content-header">
        <a href="<?php echo base_url('index.php/usuario_api') ?>">Usuários da API</a>
    </div>
    <div class="box-content-body">
        <form action="<?php echo $url_action; ?>" method="post" name="form-usuario">
            <div class="row">
                <div class="span2">
                    <label for="api_nome">Nome Usuário</label>
                    <input type="text" name="api_nome" id="api_nome" value="<?php echo $api_nome; ?>" class="span2">
                </div>

                <div class="span3">
                    <label for="api_url">URL</label>
                    <input type="text" name="api_url" id="api_nome" value="<?php echo $api_url; ?>" placeholder="http://www.exemplo.com.br" class="span3">
                </div>

                <div class="span2">
                    <label for="api_status">Status</label>
                    <select name="api_status" id="api_status" class="span2">
                        <option value="Ativo" <?php echo $api_status == 'Ativo' ? 'selected' : ''; ?>>Ativo</option>
                        <option value="Inativo" <?php echo $api_status == 'Inativo' ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                </div>

            </div>
            <br>
            <button class="btn btn-primary" type="submit">Salvar</button>
        </form>

        <table class="table table-bordered">
            <tr>
            <thead>
            <th></th>
            <th><i class="icon-user"></i> Nome</th>
            <th><i class="icon-globe"></i> URL</th>
            <th><i class="icon-lock"></i> Chave de acessos</th>
            <th><i class="icon-thumbs-up"></i> Status</th>
            </thead>
            </tr>
            <?php
            $usuarios = usuarioAPIModel::getUsuario();
            if (count($usuarios) > 0) {
                foreach ($usuarios as $usuario_value) {
                    ?>
                    <tr>
                        <td>
                            <div class="btn-group">
                                <button class="btn" onclick="window.location = '<?php echo base_url('index.php/usuario_api?indet=' . $usuario_value->api_id); ?>';">Editar</button>
                                <button class="btn dropdown-toggle" style="padding: 8px" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li> 
                                        <a href="<?php echo base_url('index.php/usuario_api/removerUsuario?indet=' . $usuario_value->api_id) ?>"><i class="icon-trash"></i> Remover</a>
                                    </li>
                                </ul>
                            </div>

                        </td>
                        <td><?php echo $usuario_value->api_nome; ?></td>
                        <td><?php echo $usuario_value->api_url; ?></td>
                        <td>
                            <ul class="unstyled">
                                <li>App ID: <strong><?php echo $usuario_value->api_app_id; ?></strong></li>
                                <li>Secret KEY: <strong><?php echo $usuario_value->api_secret_key; ?></strong></li>
                                <li>Token de acesso: <strong><?php echo $usuario_value->api_token; ?></strong></li>
                            </ul>
                        </td>
                        <td><?php echo $usuario_value->api_status; ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: center">
                            <div id="chart_div_<?PHP ECHO $usuario_value->api_app_id; ?>" style=" width: 800px; height: 150px;"></div>
                            <br/>
                            <br/>
                            <script>
                                        google.setOnLoadCallback(drawChart);
                                        function drawChart() {
                                        var data = google.visualization.arrayToDataTable([
                                                ['data', 'Acesso', 'erro no acesso'],
        <?php
        $historicos = usuarioAPIModel::getHistorioAPI($usuario_value->api_app_id);

        if (count($historicos) > 0) {
            foreach ($historicos as $historico_value) {
                ?>
                                                ['<?php echo $historico_value->ac_data; ?>',<?php echo $historico_value->total_acesso; ?>, <?php echo $historico_value->total_erros; ?>],
                <?php
            }
        }
        ?>
                                        ]);
                                                var options = {
                                                title: 'Uso da api de consulta'
                                                };
                                                var chart = new google.visualization.LineChart(document.getElementById('chart_div_<?PHP ECHO $usuario_value->api_app_id; ?>'));
                                                chart.draw(data, options);
                                        }
                            </script>
                            <a href="javascript:void(0);" onclick="show('chart_div_<?PHP ECHO $usuario_value->api_app_id; ?>');"><i id="arrow" class=" icon-chevron-down"></i> Ver Grafico de acesso</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="5" >Nenhum usuário Cadastrado.</td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<script>
function show(div){
 var div = div;
 if ($('#'+div).css('display') == 'none'){
            $('#' + div).show();
                    $("#arrow").removeClass('icon-chevron-down').addClass('icon-chevron-up');
    } else{
            $('#' + div).hide();
                    $("#arrow").removeClass('icon-chevron-up').addClass('icon-chevron-down');
    }
}
</script>