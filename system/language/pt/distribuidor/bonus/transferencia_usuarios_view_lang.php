<?php
$lang ['label_transferencia'] = "Regras para Transferência";
$lang ['label_limit_transferencia'] = "Limite minimo de transferência  US$";
$lang ['label_saldo_atual'] = "Seu saldo atual";
$lang ['label_us$'] = "US$";
$lang ['label_usuario_receber_bonus'] = " Usuário que irá receber o bônus";
$lang ['label_verificar'] = "Verificar";
$lang ['label_valor_transferencia'] = "Valor da transferência";
$lang ['label_senha_seguranca'] = "Senha de Segurança";
$lang ['label_salvar_dados'] = "Salvar dados";
$lang ['label_transferencia_entre_usuarios'] = "Transferências entre usuários serão feitas direto na plataforma ". ConfigSingleton::getValue('name_plataforma_pagamento');
$lang ['___________________'] = "___________________";

// NOTIFICAÇÕES
$lang ['notification_usuario_nao_cadastrado'] = "Esse usuário não está cadastrado no sistema!";
$lang ['notification_usuario_selecionado_sucesso'] = "Usuário selecionado com sucesso!";
$lang ['notification_nao_tem_vinculo_titularidade'] = "O usuário não tem vinculo de titularidade com você.";
$lang ['notificaton_transferencia'] = "
- O cliente que irá receber a Transferência deve ter no Mínimo US$300 de saldo<br>
- O limite de transferência diárias é {numero}<br>
- A taxa de transferência entre contas é de {taxa} debitado do seu saldo do backoffice.<br>
- Seu saldo mínimo que ficará em seu backoffice não deve ser menor que US$59,95 + os {taxa} do valor da transação.<br>";

?>