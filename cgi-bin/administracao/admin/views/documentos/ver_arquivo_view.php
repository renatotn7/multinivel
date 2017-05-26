<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="50%" valign="top">
            <div class="box">
                <strong>Dados Pessoais</strong>
                <div><strong>Nome:</strong> <?php echo $distribuidor->di_nome ?></div>
                <div><strong>CPF:</strong> <?php echo $distribuidor->di_cpf ?></div>
                <div><strong>RG:</strong> <?php echo $distribuidor->di_rg ?></div>
                <div><strong>Dt. Nasc:</strong> <?php echo date('d/m/Y', strtotime($distribuidor->di_data_nascimento)) ?></div>
            </div>

            <div class="box">
                <strong>Endereço</strong>
                <div><strong>Endereço:</strong> <?php echo $distribuidor->di_endereco ?></div>
                <div><strong>Complemento:</strong> <?php echo $distribuidor->di_complemento ?></div>
                <div><strong>Nº:</strong> <?php echo $distribuidor->di_numero ?></div>
                <div><strong>CEP:</strong> <?php echo $distribuidor->di_cep ?></div>
                <div><strong>Cidade:</strong> <?php echo $distribuidor->ci_nome ?></div>
                <div><strong>UF:</strong> <?php echo $distribuidor->ci_uf ?></div>
            </div>

            <div class="box">
                <strong>Dados Bancários</strong>
                <h5>Conta Bancária 1</h5>
                <div><strong>Banco:</strong> <?php echo $distribuidor->di_conta_banco ?></div>
                <div><strong>Variação:</strong> <?php echo $distribuidor->di_conta_variacao ?></div>
                <div><strong>Número:</strong> <?php echo $distribuidor->di_conta_numero ?></div>
                <div><strong>Agência:</strong> <?php echo $distribuidor->di_conta_agencia ?></div>
                <div><strong>Nome:</strong> <?php echo $distribuidor->di_conta_nome ?></div>
                <div><strong>CPF:</strong> <?php echo $distribuidor->di_conta_cpf ?></div>
                <?php
                if (isset($distribuidor->di_conta_banco2)) {
                    ?>
                    <h5>Conta Bancária 2</h5>
                    <div><strong>Banco:</strong> <?php echo $distribuidor->di_conta_banco2 ?></div>
                    <div><strong>Variação:</strong> <?php echo $distribuidor->di_conta_variacao2 ?></div>
                    <div><strong>Número:</strong> <?php echo $distribuidor->di_conta_numero2 ?></div>
                    <div><strong>Agência:</strong> <?php echo $distribuidor->di_conta_agencia2 ?></div>
                    <div><strong>Nome:</strong> <?php echo $distribuidor->di_conta_nome2 ?></div>
                    <div><strong>CPF:</strong> <?php echo $distribuidor->di_conta_cpf2 ?></div>
                    <?php
                }
                ?>

            </div>
        </td>
        <td valign="top">
            <iframe frameborder="0" src='<?php echo base_url($file) ?>' style="width:662px; height:414px"></iframe>
            <a target="_blank" href="<?php echo base_url($file) ?>">Ver Tela Cheia</a>
        </td>
    </tr>
</table>

<style>
    .box{
        background:#f9f9f9;
        margin:3px;
        border:1px solid #d9d9d9;
        padding:3px;
    }
</style>
