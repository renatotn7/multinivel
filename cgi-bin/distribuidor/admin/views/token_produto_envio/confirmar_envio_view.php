<?php
$this->lang->load('distribuidor/confirmar_envio/confirmar_envio');
$produtos = produtoModel::getProduto($token->prk_produto);
?>

<div class="box-content min-height">
    <div class="box-content-header">Confirmar Envio de Produto</div>
    <div class="box-content-body">
        <div class="panel">
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td>
                                <strong>Dados do vendedor</strong>:<br>
                                <strong><?php echo $token->di_nome ?> (<?php echo $token->di_usuario ?>)</strong><br />
                                <?php echo $token->di_endereco ?><br />
                                <?php echo $token->di_bairro ?>, <?php echo $this->lang->line('label_cep');?>: <?php echo $token->di_cep ?><br />
                                <?php echo DistribuidorDAO::getCidade($token->di_cidade)->ci_nome; ?> - <?php echo DistribuidorDAO::getCidade($token->di_cidade)->ci_uf ?><br /> 
                            </td>
                            
                            <td width="30%">
                                <strong> Dados do Produto</strong>:<br>
                                <b>produto:</b><?php echo $produtos->pr_nome;?><br />
                                <b>valor:</b>US$ <?php echo $produtos->pr_valor;?><br />
                            </td>
                        </tr>
                    </table>
                    <br>
                    <br>
                   
                    <form name="form1" method="post" action="<?php echo  base_url('index.php/distribuidor/enviar_token_produto');?>">
                        <input type="hidden" id="token"  name="token" value="<?php echo $token->prk_token;?>"/>
                        <input type="hidden" name="c" value="<?php echo $token->co_id ?>">

                       <?php echo $this->lang->line('label_senha_seguranca');?>:<br>
                        <input type="password" name="senha">
                        <br>
                        <button class="btn btn-large btn-success" type="submit"> 
                            <?php echo $this->lang->line('label_confirmar_pagamento');?>
                        </button>
                        <a class="btn" href="<?php echo base_url() ?>">
                            <?php echo $this->lang->line('label_cancelar');?>
                        </a>
                    </form>
        </div> 
    </div>
</div> 