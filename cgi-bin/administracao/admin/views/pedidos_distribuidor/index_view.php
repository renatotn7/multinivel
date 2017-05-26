<div class="box-content min-height">
    <div class="box-content-header">Vendas para Distribuidores</div>
    <div class="box-content-body">




        <form name="formulario" method="get" action="">
            Nº: <input  type="text" value="<?php echo get_parameter('id') ?>" name="id" style="margin:0;width:50px;"/>
            Usuário: <input  type="text" value="<?php echo get_parameter('usuario') ?>" name="usuario" style="margin:0;width:100px;"/>
            CPF: <input  type="text" name="cpf" value="<?php echo get_parameter('cpf') ?>" style="margin:0;width:150px;" />
            Nome: <input type="text" name="nome" value="<?php echo get_parameter('nome') ?>" style="margin:0;width:200px;" />
            Situação: 
            <select name="pago" style="margin:0;width:150px;">
                <option  <?php echo get_parameter('pago') =='false'?'selected':'';?> value="false" >--Indiferente--</option>
                <option value="1" <?php echo get_parameter('pago')==1?'selected':'';?>>Pago</option>
                <option value="0" <?php echo get_parameter('pago')===0?'selected':'';?>>Não Pago</option>    
            </select>
            <div class="row">
                <!-- <div class="span3">
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
                </div> -->
            </div>
            <input type="submit" class="btn btn-info" value="Filtrar" style="margin-top:8px;" />
        </form>
        <table width="100%" align="left" border="0" cellpadding="0" cellspacing="0">

            <tr><td height="10px"></td></tr>

        </table>

        <table id="table-listagem" class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="5">
            <thead>

                <tr>
                    <td width="6%" bgcolor="#f7f7f7"><strong>Nº</strong></td>
                    <td width="23%" bgcolor="#f7f7f7"><strong>Distribuidor/usuario</strong><br /></td>
                    <td width="10%" bgcolor="#f7f7f7"><strong>Data</strong></td>
                    <td width="16%" valign="top" bgcolor="#f7f7f7"><strong>Cidade-UF</strong></td>
                    <td width="11%" valign="top" bgcolor="#f7f7f7"><strong>Código de Rastreio</strong></td>
                    <!-- <td width="13%" valign="top" bgcolor="#f7f7f7"><strong>Produto Escolhido</strong></td> -->
                    <td width="13%" valign="top" bgcolor="#f7f7f7"><strong>Valor</strong></td>
                    <td width="18%" valign="bottom" bgcolor="#f7f7f7">Ações</td>
                </tr>
            </thead>
            <tbody>
                <?php                
                foreach ($pedidos as $key => $c) {
                    $hoje = date('Y-m-d', strtotime($c->co_data_compra)) == date('Y-m-d');
                    
                    ?>
                    <tr>
                        <td>
                            <?php if ($c->co_pago == 0) { ?>
                                <span class="label label-important"><?php echo $c->co_id ?></span>
                            <?php } ?>

                            <?php if ($c->co_pago == 1) { ?>
                                <span class="label label-success"><?php echo $c->co_id ?></span>
                            <?php } ?>
                        </td>
                        <td><?php echo $c->di_nome . " (" . $c->di_usuario ?>)</td>

                        <td><?php echo $hoje ? "Hoje" : date('d/m/Y', strtotime($c->co_data_compra)); ?></td>
                        <td><?php echo $c->ci_nome . "-" . $c->ci_uf ?></td>
                        <td><?php echo $c->co_frete_codigo ?></td>
                       
                        <td>US$ <?php echo number_format($c->co_frete_valor + $c->co_total_valor, 2, ',', '.') ?></td>
                        <td align="center">

                            <?php if( permissao('relacao_pedidos', 'editar', get_user(), false) ): ?>
                            <a  class="btn btn-info" href="<?php echo base_url("index.php/pedidos_distribuidor/editar_pedido/$c->co_id") ?>">Alterar</a>  
                            <?php endif; ?>

                            <a  class="btn btn-primary" target="_blank" href="<?php echo base_url("index.php/pedidos_distribuidor/visualizar_pedido/$c->co_id") ?>">Detalhes</a>  

                        </td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>

        <?php //var_dump($produtos); exit(); ?>
        <?php echo $links ?>

    </div>
</div>