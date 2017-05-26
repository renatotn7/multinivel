
<div class="box-content min-height">
    <div class="box-content-header">Editar dados fábrica</div>
    <div class="box-content-body">
        <form name="formulario" action="<?php echo base_url('index.php/fabrica/salvar_fabrica'); ?>" method="post">
            <?php
            $fabrica = $this->db
                            ->join('cidades', 'ci_id=fa_cidade','left')
                            ->where('fa_id', get_user()->fa_id)->get('fabricas')->row();

            ?>
            <input type="hidden" name="fa_id" id="fa_id" value="<?php echo $fabrica->fa_id; ?>" />
            <table width="100%" border="0" cellspacing="0" cellpadding="7">
                <tr>
                    <td width="150px"><label>Nome:</label></td>
                    <td><input type="text" name="fa_nome" class="validate[required]" value="<?php echo $fabrica->fa_nome ?>" size="50"  /></td>
                </tr>
                <tr>
                    <td><label>País:</label></td>
                    <td>
                        <select name="fa_pais"  class="ajax-pais validate[required]">
                            <?php
                            $pa = $this->db->get('pais')->result();
                            foreach ($pa as $p) {
                                ?>
                                <option <?php echo $fabrica->ci_pais == $p->ps_id ? "selected" : "" ?> value="<?php echo $p->ps_id ?>"><?php echo $p->ps_nome; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label>Estado:</label></td>
                    <td>
                        <select name="fa_estado"  class="recebe-uf validate[required]">
                            <?php
                            $es = $this->db->get('estados')->result();
                            foreach ($es as $e) {
                                ?>
                                <option <?php echo $fabrica->ci_estado == $e->es_id ? "selected" : "" ?> value="<?php echo $e->es_id ?>"><?php echo $e->es_nome ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label>Cidade:</label></td>
                    <td>
                        <input type="text" name="fa_cidade" id="fa_cidade" value="<?php echo $fabrica->fa_cidade;?>"/>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>Bairro:</label>
                    </td>
                    <td>
                        <input type="text" class="validate[required]" value="<?php echo $fabrica->fa_bairro ?>" name="fa_bairro" size="50" />
                    </td>
                </tr>

                <tr>
                    <td><label>Endereço:</label></td>
                    <td><input type="text" class="validate[required]" value="<?php echo $fabrica->fa_endereco ?>" name="fa_endereco" size="50" /></td>
                </tr>
                <tr>
                    <td><label>Cep:</label><span>Para calcular frete</span></td>
                    <td><input type="text" class="validate[required]" value="<?php echo $fabrica->fa_cep ?>" name="fa_cep" size="50" /></td>
                </tr>
                <tr>
                    <td>
                        <label>Telefone:</label>
                    </td>
                    <td>
                        <input type="text" class="validate[required]" value="<?php echo $fabrica->fa_telefone ?>" name="fa_telefone" size="50" />
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>E-mail:</label>
                    </td>
                    <td>
                        <input type="text" class="validate[required]" value="<?php echo $fabrica->fa_email ?>" name="fa_email" size="50" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Senha do Email <i>Usada para realizar envio autenticado.</i>:</label>
                    </td>
                    <td>
                        <input type="text" class="validate[required]" value="<?php echo $fabrica->fa_senha_email ?>" name="fa_senha_email" size="50" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Servidor de Envio</label>
                    </td>
                     <td>
                        <input type="text" class="validate[required]" value="<?php echo $fabrica->fa_server_email ?>" name="fa_server_email" size="50" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Porta de Envio</label>
                    </td>
                    <td>
                        <input type="text" class="validate[required]" value="<?php echo $fabrica->fa_porta_email ?>" name="fa_porta_email" size="50" />
                    </td>
                </tr>
                <tr style="display:none;">
                    <td><label>Imposto de renda:</label><span>Em percentual(%)</span></td>
                    <td><input type="text" class="validate[required]" value="<?php echo $fabrica->fa_imposto_renda ?>" name="fa_imposto_renda" size="50" /></td>
                </tr>
                <tr style="display:none;">
                    <td><label>INSS:</label><span>Em percentual(%)</span></td>
                    <td><input type="text" class="validate[required]" value="<?php echo $fabrica->fa_imposto_inss ?>" name="fa_imposto_inss" size="50" /></td>
                </tr>


            </table>

            <button type="submit" class="btn btn-primary">Salvar dados</button>


        </form>

    </div>
</div>