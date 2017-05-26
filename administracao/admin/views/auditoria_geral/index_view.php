<table class="table table-bordered">
    <thead>
        <tr>
            <th>Usuário</th>
            <th>Ação</th>
            <th>Local</th>
            <th>Descrição</th>
            <th>Alterações</th>
            <th>Sistema</th>
            <th>Data</th>
        </tr>
    </thead>
    <?php foreach ($auditorias as $auditoria) { ?>
        <tr>
            <td><?php echo $auditoria->ag_id_responsavel; ?></td>
            <td><?php echo $auditoria->ag_acao_realizada; ?></td>
            <td><?php echo $auditoria->ag_tabela; ?></td>
            <td><?php echo $auditoria->ag_descricao; ?></td>
            <td><?php //auditoriaGeral::ler_dados_josn($auditoria->ag_dados,$auditoria->ag_acao_realizada); ?></td>
            <td><?php echo $auditoria->ag_sistema; ?></td>
            <td><?php echo date('d/m/Y H:i:s',  strtotime($auditoria->ag_data)); ?></td>
        </tr>
    <?php } ?>
</table>
