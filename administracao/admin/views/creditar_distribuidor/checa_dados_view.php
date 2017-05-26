<div class="box-content min-height">
    <div class="box-content-header">Confirme os dados</div>
    <div class="box-content-body">

        <div class="">
            <form method="post" action="<?php echo base_url('index.php/creditar_distribuidor/finaliza_credito/' . $usuario->di_id); ?>" name="formulario">
                <span style="font-size: 18px;font-weight: bold;padding: 0 6px;">Confirme os dados do distribuidor e informe o valor que deseja creditar.</span>
                <table width="100%" border="0" cellspacing="0" cellpadding="7">
                    <tr>
                        <td width="200px"><label>Distribuidor:</label></td>
                        <td><input type="text" disabled value="<?php echo $usuario->di_usuario; ?>" /></td>
                    </tr>
                    <tr>
                        <td width="200px"><label>Nome do distribuidor:</label></td>
                        <td><input type="text" disabled value="<?php echo $usuario->di_nome; ?>" /></td>
                    </tr>
                    <tr>
                        <td width="200px"><label>Email:</label></td>
                        <td><input type="text" disabled value="<?php echo $usuario->di_email; ?>" /></td>
                    </tr>  
                    <tr>
                        <td width="200px"><label>Cidade:</label></td>
                        <td><input type="text" disabled value="<?php echo $usuario->es_nome; ?>" /></td>
                    </tr>    
                    <tr>
                        <td width="200px"><label>Estado:</label></td>
                        <td><input type="text" disabled value="<?php echo $usuario->ci_nome; ?>" /></td>
                    </tr> 
                    <tr>
                        <td width="200px"><label>Saldo:</label></td>
                        <td><strong>US$: <?php echo $saldo;?></strong></td>
                    </tr> 
                    <tr>
                        <td><label>Descrição:</label>
                            <span style="font-size:12px;">Informe uma descrição para esse transação</span>
                        </td>
                        <td> <input type="text" class="validate[required]" name="descricao" size="15"  /></td>
                    </tr>  
                    <tr>
                        <td><label>Valor:</label>
                            <span style="font-size:12px;">Separar centavos com ponto.<br /> Ex: 1230.60</span>
                        </td>
                        <td> <input type="text" class="validate[required] moeda" name="cb_credito" size="15"  /></td>
                    </tr>
                    <tr>
                        <td><label>Tipo de Operação:</label>
                        </td>
                        <td>
                            <select name="tipo" class="validate[required]">
                                <option value="credito">Selecione</option>
                                <option value="credito" style='color:#066'>Creditar</option>
                                <option value="debito" style='color:#f00'>Debitar</option>
                            </select>
                        </td>
                    </tr>  
                     
                    <tr>
                        <td width="200px"><label>Senha do administrador:</label></td>
                        <td><input type="password" class="validate[required]" name="di_senha" /></td>
                    </tr>
                    <tr>
                        <td><input type="submit" class="btn btn-primary" value="Salvar Crédito"></td>
                        <td></td>
                    </tr>                 
                </table>

                <input type="hidden" name="di_id" value="<?php echo $usuario->di_id; ?>" >
            </form>
        </div>

    </div>
</div>