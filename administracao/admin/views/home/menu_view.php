<ul class="menu-h" style="background:#F5F5F5;">
    <li><a class="micon-principal" href="<?php echo base_url() ?>">Página Inicial</a></li>
    <?php if (permissao('produtos', 'visualizar', get_user()) || permissao('categoria_produtos', 'visualizar', get_user())) { ?>
        <!--        <li><a class="micon-pedidos" href="#">Produtos</a>
                    <ul>
        <?php if (permissao('produtos', 'visualizar', get_user())) { ?>
                                <li><a href="<?php echo base_url('/index.php/produtos/') ?>">Produtos</a></li>
        <?php } ?>
        <?php if (permissao('categoria_produtos', 'visualizar', get_user())) { ?>
                                <li><a href="<?php echo base_url('/index.php/produtos/categorias') ?>">Categorias</a></li>
        <?php } ?>
                    </ul>
                </li>-->
    <?php } ?>

    <?php if (permissao('empreendedores', 'visualizar', get_user())) { ?>
        <li><a class="micon-redes" href="javascript:void(0)">Cadastro</a>
            <ul>
                <?php if (permissao('cadastro_pendente', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/cadastros_pendentes') ?>">Cadastros Pendentes</a></li>
                <?php } ?>
                <?php if (permissao('arede', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/distribuidores') ?>">Cadastros Ativos</a></li>
                <?php } ?>
                <?php if (permissao('verificar_conta', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/documentos/listar') ?>">Verificação dos Cadastros</a></li>
                <?php } ?>

            </ul>
        </li>
    <?php } ?>

    <?php if (permissao('vendas', 'visualizar', get_user())) { ?>
        <li><a class="micon-pedidos" href="javascript:void(0)">Estatística</a>
            <ul>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Visão geral</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Venda</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Compra</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Futuro</a></li>
            </ul> 
        </li>
    <?php } ?>
    <li>
        <a class="micon-pedidos" target="_blank" href="<?php echo base_url('index.php/acessar_loja'); ?>">Administrar Loja externa</a>
    </li>

    <?php if (permissao('vendas', 'visualizar', get_user())) { ?>
        <li><a class="micon-pedidos" href="javascript:void(0)">Filiais - CDS</a>
            <ul>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Nascionais</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Estaduais</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Regionais</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Locais</a></li>
            </ul> 
        </li>
    <?php } ?>

    <?php if (permissao('vendas', 'visualizar', get_user())) { ?>
        <li><a class="micon-pedidos" href="javascript:void(0)">Logística</a>
            <ul>
                <?php if (permissao('produtos', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('/index.php/produtos/') ?>">Produtos</a></li>
                    <li><a href="<?php echo base_url('/index.php/produto_pais') ?>">Produto por País</a></li>
                <?php } ?>
                <?php if (permissao('produtos', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('/index.php/kits/') ?>">Kits</a></li>
                <?php } ?>
                <?php if (permissao('categoria_produtos', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('/index.php/produtos/categorias') ?>">Categorias</a></li>
                    <li><a href="<?php echo base_url('/index.php/combopacotes') ?>">Combos e Pacotes</a></li>
                <?php } ?>
                <!--
                FOI SOLICITADO PARA RETIRAR TUDO QUE DIZ RESPEITO A CD DO SISTEMA
                <li><a href="<?php echo base_url('/index.php/pedidos_cd') ?>">Vendas para CD</a></li>
                -->

                <li><a href="<?php echo base_url('/index.php/pedidos_distribuidor') ?>">Relação de Pedidos</a></li>
                <li><a href="<?php echo base_url('/index.php/relatorios/relatorio_despachante') ?>">Pedidos Pagos</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Controle de Estoque</a></li>
            </ul> 
        </li>
    <?php } ?>


    <?php
    //FOI SOLICITADO PARA RETIRAR TUDO QUE DIZ RESPEITO A CD DO SISTEMA
    if (1 == 2) {
        if (permissao('cds', 'visualizar', get_user())) {
            ?>
            <li><a class="micon-dados" href="<?php echo base_url('index.php/cd') ?>">CDs</a></li>
            <?php
        }
    }
    ?>


    <?php if (permissao('financeiro', 'visualizar', get_user())) { ?>

        <li><a class="micon-bonus" href="javascript:void(0);">Financeiro</a>
            <ul>
                <li><a href="<?php echo base_url('index.php/financeiro/taxas_cambio_frete') ?>">Taxas/Câmbio/Fretes</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Auditória financeira</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Bloqueio Financeiro Geral </a></li>
                <li><a href="<?php echo base_url('/index.php/relatorio_financeiro/relatorio') ?>">Balancete</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Provisão Financeira</a></li>
                <li><a href="<?php echo base_url('/index.php/financiamento/financiamentos') ?>">Financiamentos</a></li>
                <li><a href="<?php echo base_url('index.php/distribuidores/financiados') ?>">Cadastros Financiados</a></li>
                <li><a href="<?php echo base_url('index.php/configuracao/bloqueio_upgrade_trava_sessenta'); ?>"
                       onclick="return confirm('<?php echo conf()->data_max_sessenta == 1 ? "Deseja liberar a regra dos 60 dias upgrade" : "Deseja Travar a regra dos 60 dias upgrade"; ?>');">Bloquear Upgrade tempo maior que 60 dias</a></li>
                <!--<li><a class="micon-bonus" href="<?php // echo base_url('index.php/boleto/baixar')  ?>">Boleto</a></li>-->

                <?php if (permissao('solicitacao_deposito', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/relatorios/depositos') ?>">Solicitação de Depósitos</a></li>
                <?php } ?>
            <!--<li><a class="micon-bonus" href="<?php //echo base_url('index.php/transferencia_credito/ver_transacoes')  ?>">Transferencias entre rede</a></li>-->



            </ul>

        </li>
    <?php } ?>

    <?php if (permissao('relatorios', 'visualizar', get_user())) { ?>
        <li><a class="micon-extrato" href="javascript:void(0)">Relatórios</a>
            <ul>

                <li>
                    <a href="<?php echo base_url('index.php/relatorio_financeiro/relatorio'); ?>">
                        Balancete Financeiro
                    </a> 
                </li>

                <?php if (permissao('relatorio_produtos', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/relatorios/recebimento_produto') ?>">Recebimento de Produto</a></li>
                <?php } ?> 
                <?php if (permissao('relatorio_produtos', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/relatorios/montar_vendas_produtos') ?>">Produtos Vendidos</a></li>
                <?php } ?> 
                <?php if (permissao('relatorio_vendas', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/relatorios/montar_vendas') ?>">Relatório de vendas</a></li>
                <?php } ?> 

                <?php if (permissao('relatorio_bonus', 'visualizar', get_user())) { ?>
                    <li><a target="_blank" href="<?php echo base_url('index.php/relatorios/bonus_apagar') ?>">Relátorio de bônus</a></li>
                <?php } ?>

                <?php if (permissao('relatorio_deposito', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/relatorios/depositos') ?>">Depósitos</a></li>
                <?php } ?>
                <?php if (permissao('relatorio_deposito', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/relatorios/relatorio_escolhas') ?>">Relatorio de escolha</a></li>
                <?php } ?>

                <li><a style="display:none;" href="<?php echo base_url('index.php/relatorios/qualificacoes') ?>">Relátorio de Qualificação</a>
                </li>

                <li>
                    <a target="_blank" href="<?php echo base_url('index.php/relatorios/relatorio_login') ?>">Relatório dados login</a>
                </li>

                <?php if (permissao('relatorio_celular', 'visualizar', get_user())) { ?>
                    <li><a target="_black" href="<?php echo base_url('index.php/relatorios/relatorio_celular') ?>" target="_blank">Relátorio de Celular distribuidor</a></li>
                <?php } ?>

                <?php if (permissao('relatorio_email', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/relatorios/relatorio_email') ?>" target="_blank">Relátorio de E-mail distribuidor</a></li>
                <?php } ?>  
                <?php if (permissao('relatorio_celular', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/relatorios/relatorios_distribuidor') ?>">Relatório Distribuidores</a></li>
                <?php } ?> 
                <?php if (permissao('relatorio_usuario_rede', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/relatorios/relatorio_usuario_rede') ?>">Relatório Usuário da Rede</a></li>
                <?php } ?> 
                <?php if (permissao('relatorio_usuario_rede', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/relatorios/relatorio_distribuidor_ponto_negativos') ?>">Relatório de Usuário com Pontos Negátivos</a></li>
                <?php } ?>
                <li>
                    <a target="_blank" href="<?php echo base_url('index.php/relatorios/relatorio_cadastros_ativos') ?>">Relatório de Cadastros Ativos</a>
                </li>
                <li>
                    <a target="_blank" href="<?php echo base_url('index.php/relatorios/relatorio_cadastros_inativos') ?>">Relatório de Cadastros Inativos</a>
                </li>          
                <li>
                    <a target="_blank" href="<?php echo base_url('index.php/relatorios/relatorio_plano_carreira') ?>">Relatório de Plano de Carreira</a>
                </li>                  


                <li><a class="micon-bonus" href="<?php echo base_url('index.php/relatorios/transacoes') ?>">Relatório de Transações</a></li>
                <li><a class="micon-bonus" href="<?php echo base_url('index.php/relatorios/relatorio_email_ativos'); ?>">Emails Ativos</a></li>
                <li><a class="micon-bonus" href="<?php echo base_url('index.php/relatorios/relatorio_email_pendente'); ?>">Emails Pendentes</a></li>
            </ul>
        </li>

    <?php } ?>

    <?php if (permissao('marketing', 'visualizar', get_user())) { ?>
        <li><a class="micon-extrato" href="javascript:void(0)">Marketing</a>
            <ul>
                <?php if (permissao('notificacao', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/noticias/listar') ?>">Notificações</a></li>
                <?php } ?>
                <?php if (permissao('email_marketing', 'visualizar', get_user())) { ?>
                    <li><a href="<?php echo base_url('index.php/newsletter/') ?>">E-mail Marketing</a></li>
                <?php } ?>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">clickFreind</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Rede Sociais</a></li>
                <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Analitycs</a></li>
            </ul>
        </li>
    <?php } ?>


    <li><a class="micon-extrato" href="javascript:void(0)">Pós-Venda</a> 
        <ul>
            <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Carteiras de Clientes</a></li>  
            <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Clientes Não Avaliados</a></li>  
            <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Clientes Avaliados</a></li>  
            <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Clientes Pendentes</a></li>  
            <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Clientes em Manutenção</a></li>  
            <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Clientes para Resgate</a></li>  
            <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Clientes não Resgatar</a></li>  
            <li><a href="<?php echo base_url('/index.php/implantacao') ?>">Análises e Resultados</a></li>  
        </ul>
    </li>


    <?php if (permissao('download', 'visualizar', get_user())) { ?>
        <li><a class="micon-extrato" href="#">Downloads</a>
            <ul><li><a href="<?php echo base_url('index.php/webservice/download_txt') ?>">Empreendedores(.txt)</a></li> </ul>
        </li> 
    <?php } ?>

    <?php
    if (get_user()->rf_tipo == 1) {
        ?>
        <li style="padding-bottom:0 !important;margin-bottom:0 !important;"><a class="micon-extrato" href="<?php echo base_url('index.php/usuario/') ?>">Usuários</a></li>
    <?php } ?>
    <?php if (get_user()->rf_id == 5000) { ?>
        <li><a  class="micon-pedidos" href="<?php echo base_url('index.php/empresas'); ?>">Empresas</a>
        <?php } ?>
        <?php if (permissao('configuracoes', 'visualizar', get_user())) { ?>
        <li><a  class="micon-pedidos" href="javascript:void(0)">Configurações</a>
            <ul>
                <li><a href="<?php echo base_url('/index.php/configuracao') ?>">Configuração Geral</a></li>
                <li><a href="<?php echo base_url('/index.php/custo_frete') ?>">Custo Frete por Estado</a></li>
                <li><a href="<?php echo base_url('/index.php/planos') ?>">Planos</a></li>
                <li><a href="<?php echo base_url('/index.php/carreiras') ?>">Planos de Carreira</a></li>
                <li><a href="<?php echo base_url('/index.php/configuracao_pl/conf_pl') ?>">Bônus PL Por País</a></li>
                <li><a class="micon-bonus" href="<?php echo base_url('index.php/creditar_distribuidor/inserir_credito') ?>">Creditar bônus</a></li>
                <li><a class="micon-bonus" href="<?php echo base_url('index.php/gestao_bonus/registro_dia') ?>">Gestão de Bônus</a></li>
                <!--bloquear sistema.-->
                <?php if (get_user()->rf_id == 5000 or get_user()->rf_id == 5024) { ?> 
                    <li>
                        <a href="<?php echo base_url('index.php/usuario_api/'); ?>">
                            Usuário da api
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('index.php/configuracao/alterar_status_login'); ?>">
                            <?php echo (conf()->ativar_login == 1 ? 'Bloquear login' : 'Desbloquear login'); ?>  
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('index.php/configuracao/alterar_status_cadastro'); ?>">
                            <?php echo (conf()->ativar_cadastro == 1 ? 'Bloquear Cadastro' : 'Desbloquear Cadastro'); ?>  
                        </a>
                    </li>
                <?php } ?>
                <li><a href="<?php echo base_url('index.php/atualizar_cadastro') ?>" target="_self">Atualizar Cadastros</a></li>
            </ul> 
        </li>
    <?php } ?>

</ul>

<script>
    $(".menu-h li").click(function() {
        if ($(this).find('ul').css('display') != 'undefined') {
            $(".menu-h li ul").slideUp(500);
            $(this).find('ul').slideDown(500);
        }
    });
</script>
