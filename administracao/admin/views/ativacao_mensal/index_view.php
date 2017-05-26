<div class="box-content min-height">
    <div class="box-content-body">
        <form action="<?php echo base_url('index.php/ativacao_mensal/index'); ?>" method="get" name="form">
            <div class="row">
                <div class="span3">
                    <label for="usuario">Usuário</label>
                    <input type="text" name="usuario" id="usuario" value="" class="span3">
                </div> 

                <div class="span3">
                    <label >Data</label>
                    <input type="text" name="data_ini" id="data_ini" style="width: 73px" class="mdata date-filtro hasDatepicker" value="" />
                    <input type="text" name="data_fin" id="data_ini" style="width: 73px" class="mdata date-filtro hasDatepicker" value="" />
                   </div>

                <div class="span2">
                    <label for="plano">Plano</label>
                    <select id="plano" name="plano" class="span2">
                        <option  value="">--Selecione--</option>
                        <?php
                        $planos = $this->db->where('pa_id !=104')->get('planos')->result();
                        foreach ($planos as $key => $plano) {
                            ?>
                            <option value="<?php echo $plano->pa_id; ?>"><?php echo $plano->pa_descricao; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="span2">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="span2">
                        <option  value="">--Selecione--</option>
                        <option value="2">Ativo</option>
                        <option value="1">Pendente</option>

                    </select>
                </div>

                <div class="span3"><br/>
                    <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> Buscar</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <tr>
                <th>Usuário</th>
                <th>Data</th>
                <th>Plano</th>
                <th>Status</th>
            </tr>
            <?php
            if(count($ativacao_mensal)>0){
            foreach ($ativacao_mensal as $ativacao_mensal_Value) {
                $ativaocao = new AtivacaoMensal();
                $ativaocao->setDistribuidor($ativacao_mensal_Value);
               
                ?>
                <tr>
                    <td><?php echo $ativacao_mensal_Value->di_usuario ?></td>
                    <td><?php echo date('d/m/Y', strtotime($ativacao_mensal_Value->co_data_compra)); ?></td>
                    <td><?php echo $ativacao_mensal_Value->pa_descricao; ?></td>
                    <td><?php
                        echo $ativacao_mensal_Value->ativacao == 1 ? '<span class="label label-success">Ok</span>' : '<span class="label label-important">Pendente</span>';
                        ?></td>
                </tr>
            <?php } 
            }else{?>
                <tr>
                    <td colspan="4" style="text-align: center">Nenhum registro encontrado</td>
                </tr>
            <?php }?>
        </table>
        <?php echo $link; ?>
    </div>
</div>


