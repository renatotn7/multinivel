<div class="box-content min-height">
    <div class="box-content-header"><a href="<?php echo base_url('index.php/produtos/') ?>">&raquo; Produtos</a> &raquo; Produtos</div>
    <div class="box-content-body">

        <form name="formulario" method="post" action="">


            <table width="100%" border="0" cellspacing="0" cellpadding="7">
                <tr>
                    <td width="200px"><label>Nome do produto:</label></td>
                    <td><input type="text" class="validate[required]" name="pr_nome" size="50"  /></td>
                </tr>
                <tr>
                    <td width="200px"><label>Código:</label></td>
                    <td><input type="text" name="pr_codigo" size="20" maxlength="20"  /></td>
                </tr>  
                <tr style="display:none;">
                    <td width="200px"><label>Numeração vip:</label></td>
                    <td><input type="text" name="pr_num_vip" size="20" maxlength="20"  /></td>
                </tr>    
                <tr>
                    <td><label>Descrição:</label></td>
                    <td><textarea name="pr_descricao" cols="50" rows="3"></textarea></td>
                </tr>  
                <tr>
                    <td><label>Valor:</label>
                        <span style="font-size:12px;">Separar centavos com virgula.<br /> Ex: 1230,60</span>
                    </td>
                    <td> <input type="text" class="moeda pr_valor" name="pr_valor" size="15"  /></td>
                </tr>
                <tr>
                    <td><label>Valor Reebolso:</label>
                        <span style="font-size:12px;">Separar centavos com virgula.<br /> Ex: 1230,60</span>
                    </td>
                    <td> <input type="text" class=" moeda" name="pr_reebolso" size="15"  /></td>
                </tr>

                <tr>
                    <td><label>Estoque:</label>
                        <span style="font-size:12px;">Quantidade do produto em estoque</span>
                    </td>
                    <td> <input type="text" class="" name="pr_estoque" size="15"   /></td>
                </tr>  

                <tr>
                    <td><label>Qtd. na caixa:</label>
                        <span style="font-size:12px;">Quantidade de produto que compões a caixa</span>
                    </td>
                    <td> <input type="text" class="" name="pr_qtd_caixa" size="15"  /></td>
                </tr>   

                <tr>
                    <td><label>Peso:</label><span style="font-size:12px;">Em Kilogramas</span></td>
                    <td><input type="text" class="" name="pr_peso" size="15"  /></td>
                </tr>
                <tr>
                    <td><label>kits:</label></td>
                    <td>
                        <select name="pr_kit" onchange="verificar_valor_kits(this.value);" >
                            <option value="">--Selecione--</option>
                            <?php
                            $kits = kitModel::getKits();
                            foreach ($kits as $kit) {
                                ?>
                                <option value="<?php echo $kit->pr_id ?>"><?php echo $kit->pr_nome; ?></option>
                            <?php } ?>
                        </select> 
                    </td>
                </tr>  
                <tr>
                    <td><label>Categoria:</label></td>
                    <td>
                        <select name="pr_categoria" class="validate[required]">
                            <option value="">--Selecione--</option>
                            <?php
                            $dp = $this->db->get('categorias_produtos')->result();
                            foreach ($dp as $d) {
                                ?>
                                <option value="<?php echo $d->ca_id ?>"><?php echo $d->ca_descricao ?></option>
                            <?php } ?>
                        </select> 
                    </td>
                </tr>  

                <tr>
                    <td><label>Desconto CD:</label><span style="font-size:12px;">Desconto em venda para centro de distribuição(CD)</span></td>
                    <td><input type="text" class="moeda" value="0.0" name="pr_desconto_cd" size="15"  /></td>
                </tr> 
                <tr>
                    <td><label>Desconto para distribuidor:</label><span style="font-size:12px;">Quantos pontos ganha ao comprar o produto.</span></td>
                    <td><input type="text" class="moeda" value="0.0" name="pr_desconto_distribuidor" size="15"  /></td>
                </tr>
                <tr>
                    <td><label>Pontos:</label></td>
                    <td><input type="text" value="0" name="pr_pontos" size="15"  /></td>
                </tr>   

                <tr>
                    <td><label>Vender o produto:</label></td>
                    <td>
                        <select name="pr_vender">
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </td>

                </tr>     
                <tr style="display:none">
                    <td><label>Crédito de repasse:</label></td>
                    <td>
                        <input type="radio" name="pr_credito" checked="checked" value="0"  /> Não
                        <input type="radio" name="pr_credito" value="1" /> Sim
                    </td>
                </tr>     
                <tr style="display:none">
                    <td><label>Produto de ativação:</label></td>
                    <td>
                        <input type="radio" name="pr_ativacao" checked="checked" value="0"  /> Não
                        <input type="radio" name="pr_ativacao" value="1" /> Sim
                    </td>
                </tr>  
                <tr>
                    <td><label>Produto deve gerar token?:</label></td>
                    <td>
                        <input type="radio" name="pr_gera_token" value="0"  /> Não
                        <input type="radio" name="pr_gera_token" value="1" checked /> Sim
                    </td>
                </tr> 
                <tr>
                    <td><label>Token para Agência:</label></td>
                    <td>
                        <select  name="pr_token_agencia">
                            <option selected value="">--Selecione Agência--</option>
                            <option  value="1000">Todas as Agências</option>
                            <?php
                            $agencia = PlanosModel::getPlano();
                            foreach ($agencia as $agencia_value) {
                                ?>
                                <option value="<?php echo $agencia_value->pa_id; ?>"><?php echo $agencia_value->pa_descricao; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr> 


            <tr>
                <td><label>Produto de ativação:</label></td>
                <td>
                    <input type="radio" name="pr_ativacao" value="0"  /> Não
                    <input type="radio" name="pr_ativacao" value="1" /> Sim
                </td>
            </tr> 

            <tr>
                <td><input type="submit" class="btn btn-primary" value="Salvar produto"></td>
                <td></td>
            </tr>                 
        </table>


    </form>


</div>
</div>
<style>
    label{
        font-weight:bold;
    }
</style>
<script type="text/javascript">
    function verificar_valor_kits(id_kit) {
        var id_kit = id_kit;
        $.ajax({
            url: '<?php echo base_url("index.php/kits/get_valor_kits_ajax"); ?>',
            type: 'post',
            data: {id_kit: id_kit},
            dataType: 'json',
            success: function(data) {
                if (data.error == 0) {
                    $('.pr_valor').val(data.data);
                }
            }
        });

    }
</script>