<div class="box-content min-height">
    <div class="box-content-header">Histórico Dos Pagamento Plataforma de Pagamento</div>
    <div class="box-content-body">
        <div class="row">
            <div class="span">
                <a class="btn btn-success" href="<?php echo base_url('index.php/historico_transacao/atualizar/' . $this->uri->segment(3)); ?>">Sicronizar Histórico</a>
            </div>
        </div>
        <br/>
        <table class="table table-bordered">
            <tr>
                <th>Nº</th>
                <th>Nº da Compra</th>
                <th>Descricao da Compra</th>
                <th>Mensagem</th>
                <th>status</th>
                <th>Protocolo</th>
            </tr>
            <?php if (count($historico) == 0) { ?>
                <tr>
                    <td colspan="6" style="text-align: center">Nenhum registro econtrado.</td>
                </tr>
                <?php
            } else {
                foreach ($historico as $key => $his_value) {?>
                    <tr>
                        <td><?php echo $his_value->sa_numero; ?> </td>
                        <td><?php echo $his_value->co_id; ?> </td>
                        <td><ul><?php echo str_replace(',','',$his_value->produto); ?></ul> </td>
                        <td><?php echo empty($his_value->sa_status) && (int)$his_value->sa_status !=0?'Não Sicronizado':$his_value->sa_mensagem; ?> </td>
                        <td><?php echo empty($his_value->sa_status) && (int)$his_value->sa_status !=0?'Não Sicronizado':$his_value->sa_status; ?> </td>
                        <td><?php echo empty($his_value->sa_status) && (int)$his_value->sa_status !=0?'Não Sicronizado':$his_value->sa_protocolo; ?> </td>
                    </tr>
                    <?php
                }
            }
            ?>

        </table>
    </div>
</div>
