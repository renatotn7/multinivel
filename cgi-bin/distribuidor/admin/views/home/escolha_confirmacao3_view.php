<?php
$this->lang->load('distribuidor/home/index_view');
$base_url_img3 = base_url('public/imagem/') . $this->lang->line('url_opcao_img3');
$compra = ComprasModel::fez_escolha_recebimento(get_user());

$co_id = 0;
if (count($compra) > 0) {
    $co_id = $compra->co_id;
}
?>
<form name="form-escolha" method="post" action="<?php echo base_url('index.php/pedidos/salvar_escolha'); ?>">
    <input type="hidden" id="co_id" name="co_id" value="<?php echo $co_id; ?>"/>
    <input type="hidden" id="co_id_produto_escolha_entrega" name="co_id_produto_escolha_entrega" value="3"/>
    <img src="<?php echo $base_url_img3; ?>" />
    <div class='row'>
        <div style='text-align: center;width: 100%;'>
            <button class='btn btn-success btn-large ' ><?php echo $this->lang->line('label_salvar'); ?></button>
            <a class='btn btn-danger btn-large btn-escola' rel="0" href='#'><?php echo $this->lang->line('label_cancelar'); ?></a>
        </div>
    </div>
</form>