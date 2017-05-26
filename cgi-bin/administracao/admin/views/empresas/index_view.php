<?php
$empresa = funcoesdb::arrayToObject(array('ep_nome'=>'','ep_status'=>'','ep_id'=>''));
$url=base_url('index.php/empresas/add');

if($this->input->get('ep')){
    $empresa = $this->db->where('ep_id',$this->input->get('ep'))
                        ->get('empresas')->row();
    $url=base_url('index.php/empresas/atualizar');
}

?>
<div class="box-content min-height">
    <div class="box-content-header">Vendas</div>
    <div class="box-content-body">
        <form name="empresa" id="empresa" action="<?php echo $url;?>" method="POST">
            <input type="hidden"  name="ep_id"  id="ep_id" value="<?php echo $empresa->ep_id;?>"/>
            <div class="row">
                <div class="span3">
                    <label for="ep_nome">Nome da Empresa:</label>    
                    <input type="text" name="ep_nome"  id="ep_nome" class="span3" value="<?php echo $empresa->ep_nome;?>">
                </div>
                <div class="span3">
                    <label for="ep_status">Situação Empresa:</label>    
                    <select name="ep_status" id="ep_status" class="span3">
                        <option <?php echo $empresa->ep_status==1?'selected':'';?> value="1">Ativa</option>
                        <option <?php echo $empresa->ep_status==0?'selected':'';?>value="0">Bloqueada</option>
                    </select>
                </div>
            </div>

            <button  class="btn btn-success">Salvar</button>
        </form>
        <table class="table table-bordered">
            <tr>
                <th style="width: 8px;"></th>
                <th>Código</th>
                <th>Nome</th>
                <th>Situcao</th>
            </tr>
            <?php
            $empresas = $this->db->get('empresas')->result();
            foreach ($empresas as $key => $empresa_value) {
                ?>
                <tr>
                    <td>
                        <div class="btn-toolbar" style="margin: 0;">
                            <div class="btn-group">
                                <button type="button" class="btn" onclick="window.open('<?php echo base_url('index.php/empresas?ep='.$empresa_value->ep_id ) ?>', '_self');"> <i class="icon-pencil"></i> Editar</button>
                                <button style="padding: 8px;" class="btn dropdown-toggle " data-toggle="dropdown"><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo base_url('index.php/empresas/remover?remove='.$empresa_value->ep_id ); ?>" target="_self"><i class="icon-trash"></i> Remover</a></li>
                                </ul>
                            </div><!-- /btn-group -->
                        </div>

                    </td>
                    <td><?php echo $empresa_value->ep_id ?></td>
                    <td><?php echo $empresa_value->ep_nome ?></td>
                    <td><?php echo $empresa_value->ep_status == 1 ? 'Ativo' : 'Bloquado'; ?></td>
                </tr>
<?php } ?>
        </table>

    </div>
</div>
