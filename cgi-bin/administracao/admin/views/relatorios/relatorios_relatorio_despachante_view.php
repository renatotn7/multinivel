<?php error_reporting(E_ALL); ?>
<style type="text/css">

    /* tables */
    table.tablesorter {
        font-family:arial;
        background-color: #CDCDCD;
        margin:10px 0pt 15px;
        /*font-size: 8pt;*/
        width: 100%;
        text-align: left;
    }
    table.tablesorter thead tr th, table.tablesorter tfoot tr th {
        background-color: #e6EEEE;
        border: 1px solid #FFF;
        /*font-size: 10pt;*/
        padding: 4px;
    }
    table.tablesorter thead tr .header-despacho {
        background-image: url(<?php echo base_url('public/imagem/bg.gif'); ?>) !important;  
        background-repeat: no-repeat;
        background-position: center right;
        cursor: pointer;
    }
    table.tablesorter tbody td {
        color: #3D3D3D;
        padding: 4px;
        background-color: #FFF;
        vertical-align: top;
    }
    table.tablesorter tbody tr.odd td {
        background-color:#F0F0F6;
    }
    table.tablesorter thead tr .headerSortUp {
        background-image: url(<?php echo base_url('public/imagem/asc.gif'); ?>) !important; 
    }
    table.tablesorter thead tr .headerSortDown {
        background-image: url(<?php echo base_url('public/imagem/asc.gif'); ?>) !important; 
    }
    table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
        background-color: #8dbdd8;
    }

    .bloqueado > td{
        background-color: #FFD7CA !important;
    }
    .laranja select{
        border-color: orange;
        color: orange;
    }
</style>
<div class="box-content min-height">
    <div class="box-content-header">Relatório Transações</div>
    <div class="box-content-body">

        <form id="form-relatorio-despacho" name="form-relatorio-despacho" action="<?php echo base_url('index.php/relatorios/relatorio_despachante'); ?>" method="get">

            <div class="row">
                <div class="span2">
                    <label for="co_id"><strong> Nº pedido:</strong></label>
                    <input class="span2" onblur="limpar_data();" onkeypress="limpar_data();"  name="co_id" id="co_id" type="text"  value=""/>
                </div>
                <div class="span3">
                    <label for="di_usuario"><strong> Usuário:</strong></label>
                    <input placeholder="Usuário"  onblur="limpar_data();" onkeypress="limpar_data();"  name="di_usuario" id="di_usuario" type="text"  value=""/>
                </div>

                <div class="span3">
                    <label for="pr_id"><strong>Produto:</strong></label>
                    <select  name="pr_id" id="pr_id" >
                        <option value="">--Selecione--</option> 
                        <?php
                        $produtos = $this->db->get('produtos')->result();
                        foreach ($produtos as $key => $produto) {
                            ?>
                            <option <?php echo (isset($_GET['pr_id']) && $_GET['pr_id'] == $produto->pr_id) ? 'selected' : '' ?> value="<?php echo $produto->pr_id; ?>"><?php echo $produto->pr_nome; ?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class="span3">
                    <label for="pa_id"><strong>Agências:</strong></label>
                    <select  name="pa_id" id="pa_id" >
                        <option value="">--Selecione--</option> 
                        <?php
                        $produtos = $this->db
                                        ->where('pa_id !=104')
                                        ->get('planos')->result();
                        foreach ($produtos as $key => $produto) {
                            ?>
                            <option <?php echo (isset($_GET['pa_id']) && $_GET['pa_id'] == $produto->pa_id) ? 'selected' : '' ?> value="<?php echo $produto->pa_id; ?>"><?php echo $produto->pa_descricao; ?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class="span3">
                    <label for="totalpagina"><strong> total de registro por página:</strong></label>
                    <select id="totalpagina" name="totalpagina">
                        <option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina'] == 20) ? 'selected' : '' ?> value="20">20</option>
                        <option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina'] == 50) ? 'selected' : '' ?> value="50">50</option>
                        <option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina'] == 100) ? 'selected' : ''; ?> value="100">100</option>
                        <option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina'] == 'todos') ? 'selected' : ''; ?> value="todos">Todos</option>
                    </select>
                </div>
            </div>
            <!--filtro de data-->
            <div class="row">
                <div class="span1" style="width: 77px">
                    <label for="de"><strong> De:</strong></label>
                    <input name="de" id="de" class='date-filtro' type="text" value="<?php echo $de; ?>" class="span1" style="width: 77px"/>
                </div>
                <div class="span1" style="width: 77px">
                    <label for="ate"><strong> Até:</strong></label>
                    <input name="ate" id="ate" class="date-filtro" type="text" value="<?php echo $ate; ?>" class="span1" style="width: 77px"/>
                </div>

                <div class="span3">
                    <label for="co_situacao"><strong> Situação:</strong></label>
                    <select id="co_situacao" name="co_situacao">
                        <option value="">--Indiferente--</option>
                        <option value="7">pendente</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 9) ? 'selected' : ''; ?> value="9">Auditoria</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 10) ? 'selected' : ''; ?> value="10">Dados errados</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 11) ? 'selected' : ''; ?> value="11">Retornou</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 12) ? 'selected' : ''; ?> value="12">Pendências de Parcelas</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 8) ? 'selected' : ''; ?> value="8">Enviado</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 15) ? 'selected' : ''; ?> value="15">Virtual Gift-Card</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 16) ? 'selected' : ''; ?> value="16">Em Separção</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 17) ? 'selected' : ''; ?> value="17">Trocou por token</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 18) ? 'selected' : ''; ?> value="18">Dados incompartivel</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 19) ? 'selected' : ''; ?> value="19">Despacho em andamento</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 20) ? 'selected' : ''; ?> value="20">Confirmado recebimento</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 21) ? 'selected' : ''; ?> value="21">Conta de teste</option>
                        <option <?php echo (isset($_GET['co_situacao']) && $_GET['co_situacao'] == 22) ? 'selected' : ''; ?> value="22">Conta da Empresa</option>

                    </select>
                </div>
                <div class="span3">
                    <label for="co_id_produto_escolha_entrega"><strong> Produto Escolhido:</strong></label>
                    <select id="co_id_produto_escolha_entrega" name="co_id_produto_escolha_entrega">
                        <option  value="">--Indiferente--</option>
                        <option <?php echo (isset($_GET['co_id_produto_escolha_entrega']) && $_GET['co_id_produto_escolha_entrega'] == 1) ? 'selected' : '';
                        ?> value="1">Pendente</option>
                        <option <?php echo (isset($_GET['co_id_produto_escolha_entrega']) && $_GET['co_id_produto_escolha_entrega'] == 2) ? 'selected' : '';
                        ?> value="2">Envio da Jóia</option>
                        <option <?php echo (isset($_GET['co_id_produto_escolha_entrega']) && $_GET['co_id_produto_escolha_entrega'] == 3) ? 'selected' : '';
                        ?> value="3">Voucher EWC na empresa</option>
                        <option <?php echo (isset($_GET['co_id_produto_escolha_entrega']) && $_GET['co_id_produto_escolha_entrega'] == 4) ? 'selected' : '';
                        ?> value="4">Virtual Gift Card</option>
                    </select>
                </div>
            
                <div class="span3">
                    <label for="empresa">Empresas</label>
                    <select name="empresa" id="empresa">
                        <option value="">--Selecione--</option>
                        <?php
                        $empresas = $this->db->get('empresas')
                                ->result();
                        foreach ($empresas as $key => $empresa_value) {
                            ?>
                            <option value="<?php echo $empresa_value->ep_id; ?>">
                                <?php echo $empresa_value->ep_nome; ?>
                            </option>                      
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <span class="span">
                    <button type="submit" class="btn">Enviar</button>
                    <a href="javascript:void(0);" onclick="gerarXml();" class="btn btn-primary" >Baixar em Arquivo Excel a consulta</a>

                </span>
            </div>
        </form>
        <!-- tabela do relatorio de despacho-->
        <table class="table table-bordered table-hover " width="100%" style="resize:both; overflow:auto;">
            <thead>
                <tr>
                    <th width='10%' nowrap="nowrap">Nº Pedido</th>
                    <th width='3%' nowrap="nowrap">Logistica</th>
                    <th width='5%' nowrap="nowrap">Situação</th>
                    <th width='5%' nowrap="nowrap">Escolha</th>
                    <th width='5%' nowrap="nowrap">Produto</th>
                    <th width='5%' nowrap="nowrap">Bloqueado</th>
                    <th width='5%' nowrap="nowrap">Tipo de pessoa</th>
                    <th width='5%' nowrap="nowrap">Data Venda</th>
                    <th width='5%' nowrap="nowrap">Produto</th>
                    <th width='5%' nowrap="nowrap">Agência</th>
                    <th width='5%' nowrap="nowrap">Niv</th>
                    <th width='3%' nowrap="nowrap">Tokem do produto</th>
                    <th width='5%' nowrap="nowrap">Email</th>
                    <th width='5%' nowrap="nowrap">Forma de Pagamento</th>
                    <th width='5%' nowrap="nowrap">Status Login</th>
                    <th width='5%' nowrap="nowrap">Motivo</th>
                    <th width='5%' nowrap="nowrap">Usuário</th>
                    <th width='8%' nowrap="nowrap">Nome</th>
                    <th width='8%' nowrap="nowrap">Sobre nome</th>
                    <th width='3%' nowrap="nowrap">Tipo de Documento</th>
                    <th width='3%' nowrap="nowrap">Número do Documento</th>

                    <th width='3%' nowrap="nowrap">Data Nascimento</th>
                    <th width='3%' nowrap="nowrap">Cidade de Nascimento</th>
                    <th width='3%' nowrap="nowrap">País do nascimento</th>
                    <th width='3%' nowrap="nowrap">Sexo</th>
                    <th width='3%' nowrap="nowrap">Telefone fixo</th>
                    <th width='3%' nowrap="nowrap">Telefone celular</th>

                    <th width='3%' nowrap="nowrap">País</th>
                    <th width='3%' nowrap="nowrap">Estado</th>

                    <th width='3%' nowrap="nowrap">Bairro</th>
                    <th width='4%' nowrap="nowrap">Cidade</th>
                    <th width='6%' nowrap="nowrap">Rua</th>
                    <th width='8%' nowrap="nowrap">Número</th>
                    <th width='10%' nowrap="nowrap">Complemento</th>
                    <th width='5%' nowrap="nowrap">Código Postal</th>
                    <th width='5%' nowrap="nowrap">Nome da Empresa</th>
                    <th width='5%' nowrap="nowrap">Tx Identification Number</th>
                    <th width='5%' nowrap="nowrap">Diregente Responsável Jurídico</th>
                    <th width='5%' nowrap="nowrap">Diretor Jurídico</th>
                    <th width='5%' nowrap="nowrap">Endereço Jurídico</th>
                </tr>
            </thead>   
            <?php
            foreach ($responta_relatorio as $key => $relatorio) {

                $plano = DistribuidorDAO::getPlano($relatorio->di_id);
                if (count($plano) > 0) {

                    $situacao = '';
                    $select_situcao = '';

                    switch ($relatorio->co_situacao) {
                        case 7:
                            $situacao = 'label-info';
                            $select_situcao = 'info';
                            break;
                        case 6:
                            $situacao = 'label-info';
                            $select_situcao = 'info';
                            break;
                        case 3:
                            $situacao = 'label-info';
                            $select_situcao = 'info';
                            break;
                        case 3:
                            $situacao = 'label-important';
                            $select_situcao = 'laranja';
                            break;
                        case 5:
                            $situacao = 'label-important';
                            $select_situcao = 'laranja';
                            break;
                        case 1:
                            $situacao = 'label-important';
                            $select_situcao = 'success';
                            break;
                        case 8:
                            $situacao = 'label-success';
                            $select_situcao = 'success';
                            break;
                        case 9:
                            $select_situcao = 'warning';
                            $situacao = 'label-warning';
                            break;
                        case 10:
                            $select_situcao = 'error';
                            $situacao = 'label-important';
                            break;
                        case 11:
                            $select_situcao = 'laranja';
                            $situacao = 'label-warning';
                            break;
                        case 2:
                            $situacao = 'label-success';
                            $select_situcao = '';
                            break;
                        case 14:
                            $situacao = 'label-success';
                            $select_situcao = 'success';
                            break;
                        case 15:
                            $situacao = 'label-success';
                            $select_situcao = 'laranja';
                            break;
                    }

                    $descricao_cartao = "";
                    if ($relatorio->co_id_cartao != 0) {
                        $cartao = $this->db->where('cm_id', $relatorio->co_id_cartao)
                                        ->get('cartoes_membership')->row();

                        $descricao_cartao = " e Cartão: " . $cartao->cm_nome;
                    }
                    ?>


                    <tr class="<?php echo usuario_bloqueado($relatorio->di_usuario) ? ' bloqueado' : ''; ?>">
                        <td><strong class="label <?php echo $situacao ?>"><?php echo $relatorio->co_id; ?> </strong></td>
                        <td>
                            <form name="custon-produto" method="post" action="<?php echo base_url('index.php/relatorios/salvar_logistica/' . $relatorio->co_id . '' . (count($_REQUEST) > 0 ? '?' . http_build_query(array_filter($_REQUEST)) : '')); ?>" >  
                                <div id="myModal_<?php echo $relatorio->di_id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-header">
                                        <button type="button" class="close"  onclick="hideModal(<?php echo $relatorio->di_id ?>);" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h3 id="myModalLabel">Dados logistica</h3>
                                    </div>
                                    <div class="modal-body">

                                        <div class="row">
                                            <div class="span2">
                                                <label for="co_frete_codigo"><strong>Código de rastreio</strong></label>
                                                <input class="span2" type="text" name="co_frete_codigo" id="co_frete_codigo" value="<?php echo $relatorio->co_frete_codigo; ?>"/>
                                            </div>
                                            <div class="span4">
                                                <label for="co_frete_transportadora"><strong>Transportadora</strong></label>
                                                <input class="span4"  type="text" name="co_frete_transportadora" id="co_frete_transportadora" value="<?php echo $relatorio->co_frete_transportadora; ?>"/>
                                            </div>
                                        </div>     
                                        <div class="row">
                                            <div class="span6">
                                                <label for="co_frete_link_transportadora"><strong>Link Transportadora</strong></label>
                                                <input class="span6"  type="text" name="co_frete_link_transportadora" id="co_frete_link_transportadora" placeholder="http://" value="<?php echo $relatorio->co_frete_link_transportadora; ?>"/>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <a href="javascript:void();" onclick="hideModal(<?php echo $relatorio->di_id ?>);" class="btn">Cancelar</a>
                                        <button <?php echo!empty($relatorio->co_frete_codigo) ? "onclick=\"return confirm('Deseja Alterar essas Informações?');\"" : ""; ?>  type="submit" class="btn btn-primary">Salvar</button>
                                    </div>

                                </div>
                            </form>

                            <a href="javascript:void();" onclick="showModal(<?php echo $relatorio->di_id ?>);"><?php echo!empty($relatorio->co_frete_codigo) ? $relatorio->co_frete_codigo : 'Informar dados de envio'; ?></a></td>
                        <td>

                            <form action="<?php echo base_url('index.php/relatorios/alterar_situacao_relatorio_despacho/' . $relatorio->co_id); ?>" name="form-situacao-<?php echo $key; ?>"  id="form-situacao-<?php echo $key; ?>" method="post">
                                <div class="control-group <?php echo $select_situcao; ?>">
                                    <div class="controls">
                                        <input type="hidden" name="nome_responsavel" id="nome_responsavel-<?php echo $key; ?>" value=""/>
                                        <select  name="situacao" id="situacao-<?php echo $key; ?>" onchange="enviar_situacao(<?php echo $key; ?>);">
                                            <?php if (count(ComprasModel::parcelas_pendentes($relatorio)) == 0) { ?>
                                                <option  <?php echo $relatorio->co_situacao == 7 ? 'selected' : ''; ?>       value="7" selected="true" >Pendente</option>
                                                <option <?php echo $relatorio->co_situacao == 9 ? 'selected' : ''; ?>    value="9">Auditoria</option>
                                                <option <?php echo $relatorio->co_situacao == 10 ? 'selected' : ''; ?>   value="10">Dados cartão</option>
                                                <option <?php echo $relatorio->co_situacao == 11 ? 'selected' : ''; ?>   value="11">Retornou</option>
                                                <option  <?php echo $relatorio->co_situacao == 8 ? 'selected' : ''; ?>   value="8">Enviado</option>
                                                <option <?php echo $relatorio->co_situacao == 14 ? 'selected' : ''; ?>   value="14">EWC Voucher</option>
                                                <option <?php echo $relatorio->co_situacao == 15 ? 'selected' : ''; ?>   value="15">Virtual Gift-Card</option>
                                                <option <?php echo $relatorio->co_situacao == 16 ? 'selected' : ''; ?>   value="16">Em Separção</option>
                                                <option <?php echo $relatorio->co_situacao == 17 ? 'selected' : ''; ?>   value="17">Trocou por token</option>
                                                <option <?php echo $relatorio->co_situacao == 18 ? 'selected' : ''; ?>   value="18">dados incompartivel</option>
                                                <option <?php echo $relatorio->co_situacao == 19 ? 'selected' : ''; ?>   value="19">Despacho em andamento</option>
                                                <option <?php echo $relatorio->co_situacao == 20 ? 'selected' : ''; ?>   value="20">Confirmado recebimento</option>
                                                <option <?php echo $relatorio->co_situacao == 21 ? 'selected' : ''; ?>   value="21">Conta de teste</option>
                                                <option <?php echo $relatorio->co_situacao == 22 ? 'selected' : ''; ?>   value="22">Conta da Empresa</option>
                                            <?php } else { ?>
                                                <option <?php echo $relatorio->co_situacao == 12 ? 'selected' : ''; ?>   value="12">Pendências de Parcelas</option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>
                            </form>
                            <?php
                            $responsavel = $this->db->where('csr_id_compra', $relatorio->co_id)
                                            ->order_by('csr_id', 'desc')
                                            ->get('compras_situacao_reponsavel')->row();

                            if (count($responsavel) > 0) {
                                echo "Responsável: " . $responsavel->csr_nome_responsavel;
                            }
                            ?>

                        </td>
                        <td><?php echo DistribuidorDAO::getProdutoEscolhido($relatorio->co_id_produto_escolha_entrega); ?> </td>
                        <td>
                            <?php  $products = $this->db->where('pm_id_compra', $relatorio->co_id)
                                    ->join('produtos_comprados', 'pm_id_produto=pr_id')->get('produtos')->result();

                                  foreach ($products as $product) {
                                       echo $product->pr_nome .' <strong>Qtd Prd: ('. $product->pm_quantidade .')</strong> <br>';
                                  }
                             ?> 
                        </td>
                        <td><?php echo usuario_bloqueado($relatorio->di_usuario) ? 'Bloqueado' : ''; ?> </td>
                        <td><?php echo e_pessoa_juridica($relatorio->di_id) ? ' Juridica' : 'Fisíca'; ?> </td>
                        <td><?php echo date('d/m/y', strtotime($relatorio->co_data_compra)); ?> </td>
                        <td>
                            <?php
                            if ($relatorio->pr_kit_tipo = 1) {
                                $produtos = kitModel::getProdutoskits($relatorio->pr_kit);

                                foreach ($produtos as $produtos) {
                                    echo $produtos->pr_nome . '<br/>';
                                }
                            } else {
                                  foreach ($products as $product) {
                                       echo $product->pr_nome.'  '.$descricao_cartao.' <strong>Qtd Prd: ('. $product->pm_quantidade .')</strong> <br>';
                                  } 
                            }
                            ?>
                        </td>
                        <td><?php echo $plano->pa_descricao; ?></td>
                        <td><?php echo $relatorio->di_niv; ?></td>
                        <td><?php
                            $produtoToken = ComprasModel::getTokenProduto($relatorio->co_id, $relatorio->pr_id);
                            if (count($produtoToken) > 0) {
                                echo $produtoToken->prk_token;
                            }
                            ?>
                        </td>
                        <td><?php echo $relatorio->di_email; ?></td>
                        <td><?php echo $relatorio->co_forma_pgt_txt; ?></td>
                        <td><?php echo $relatorio->di_login_status == 1 ? 'Ativo' : 'Bloqueado'; ?></td>
                        <td><?php
                            $motivo = $this->db->where('rdb_id_distribuidor', $relatorio->di_id)
                                    ->order_by('rdb_id', 'desc')
                                    ->get('registro_distribuidor_bloqueio', 1)
                                    ->row();

                            if (count($motivo) > 0 && $relatorio->di_login_status == 0) {
                                echo $motivo->rdb_mensagem;
                            }
                            ?></td>
                        <td><?php echo $relatorio->di_usuario; ?></td>
                        <td><?php echo $relatorio->di_nome; ?></td>
                        <td><?php echo $relatorio->di_ultimo_nome; ?></td>
                        <td><?php echo empty($relatorio->di_tipo_documento) ? 'CPF' : $relatorio->di_tipo_documento; ?></td>
                        <td><?php echo empty($relatorio->di_rg) ? $relatorio->di_cpf : $relatorio->di_rg; ?></td>


                        <td><?php echo date('d/m/Y', strtotime($relatorio->di_data_nascimento)); ?></td>
                        <td><?php echo $relatorio->di_cidade_nascimento; ?></td>
                        <td><?php
                            $pais = DistribuidorDAO::getPais($relatorio->di_pais_nascimento);

                            if (count($pais) > 0) {
                                echo $pais->ps_nome;
                            }
                            ?></td>
                        <td><?php echo $relatorio->di_sexo == 'M' ? 'Masculino' : 'Feminino'; ?></td>
                        <td><?php echo $relatorio->di_fone1; ?></td>
                        <td><?php echo $relatorio->di_fone2; ?></td>


                        <td><?php echo DistribuidorDAO::getPais($relatorio->di_cidade)->ps_nome; ?></td>
                        <td><?php echo DistribuidorDAO::getEstado($relatorio->di_uf)->es_nome; ?></td>
                        <td><?php echo $relatorio->di_bairro; ?></td>
                        <td><?php echo DistribuidorDAO::getCidade($relatorio->di_cidade)->ci_nome; ?></td>
                        <td><?php echo $relatorio->di_endereco; ?></td>
                        <td><?php echo $relatorio->di_numero; ?></td>
                        <td><?php echo $relatorio->di_complemento; ?></td>
                        <td><?php echo $relatorio->di_cep; ?></td>
                        <td><?php echo $relatorio->dpj_nome_empresa; ?></td>
                        <td><?php echo $relatorio->dpj_tx_identificacao; ?></td>
                        <td><?php echo $relatorio->dpj_diregente_responsavel; ?></td>
                        <td><?php echo $relatorio->dpj_diretor; ?></td>
                        <td><?php echo $relatorio->dpj_endereco; ?></td>
                    </tr>

                    <?php
                }
            }
            ?>
        </table>
        <div id="pager" class="pager" style="top: 1061px; position: absolute;">

        </div>

        <div class="row">
            <div class="span">
                <?php echo $paginacao; ?>
                </br>
                <i>Total registro: <?php echo $total_registro; ?></i>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $("table").tablesorter({
            'cssHeader': 'header-despacho',
            debug: false});
    });

    function enviar_situacao(id)
    {
        if (confirm('Deseja realmente altera a situação da compra?'))
        {
            //            if ($('#situacao-' + id).val() == 8) {
            var nome_responsavel = prompt('Digite o nome do responsável');
            $('#nome_responsavel-' + id).val(nome_responsavel);
            $('#form-situacao-' + id).submit();
//            } else {
            //                $('#form-situacao-' + id).submit();
//            }
        }

    }
</script>
<script type="text/javascript">
    function limpar_data() {
        $('.date-filtro').val('');
    }

    function showModal(id) {
        $('#myModal_' + id).modal('show');
    }

    function hideModal(id) {
        $('#myModal_' + id).modal('hide');
    }
    function gerarXml() {
        var campos = $('#form-relatorio-despacho').serialize();
        document.location = '<?php echo base_url("index.php/relatorios/xlsRelatorioDespacho?"); ?>' + campos;
    }

</script>