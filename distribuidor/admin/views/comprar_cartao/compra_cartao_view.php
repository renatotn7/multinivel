<?php
$this->lang->load('distribuidor/distribuidor/cartao_view');

if (conf()->loja_manutencao == 1 && get_user()->di_usuario != 'system') {
    echo "<h3>{$this->lang->line('label_manutencao')}</h3>";
    exit;
}
?>
<style type="text/css">
    .ui-state-b-hover > img{
        border:4px dashed #000;
    }

</style>

<div class="box-content min-height">
    <div class="box-content-header"> <?php echo $this->lang->line('label_titulo'); ?> </div>
    <div class="box-content-body">
        <div class="row">
            <div class="cart"  style="text-align: center;margin-top: -50px;">
                <span  class="badge badge-important" style="
                       position: absolute;
                       right: 436px;
                       top: -18px;
                       " class="badge badge-important"><?php echo produtoModel::getTotalProdutoComprados(get_user(), 7); ?></span>
                <img  onclick="javascript:window.location = '<?php echo base_url('index.php/loja/carrinho_compra'); ?>'" style="cursor: pointer;width: 147px;" src="<?php echo base_url('public/imagem/carrinho.png'); ?>" />
                <h5><?php echo $this->lang->line('label_descricao_carrinho'); ?></h5>
            </div>
        </div>
        <form name="form-carrinho" action="<?php echo base_url('index.php/comprar_cartao'); ?>">
            <div class="row" >
                <div class="span2">

                </div>
                <!--                <div class="span3">
                                    <label>Categoria</label>
                                    <select name="categoria" onchange="submit();" >
                                        <option value="">--selecione--</option>
                <?php //foreach (categoriaModel::getCategorias() as $categoria) {  ?>
                                            <option value="<?php //echo $categoria->ca_id;      ?>"><?php // echo $categoria->ca_descricao;      ?></option>
                <?php //}  ?>
                                    </select>
                                </div>-->
                <div class="span3">
                    <label><?php echo $this->lang->line('label_produtos'); ?></label>
                    <select name="produto"  onchange="submit();" >
                        <option value="">--<?php echo $this->lang->line('label_selecione'); ?>--</option>
                        <?php foreach (produtoModel::getProdutoCategoria(7) as $produto) { ?>
                            <option value="<?php echo $produto->pr_id; ?>"><?php echo $produto->pr_nome; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="span3">
                    <label ><?php echo $this->lang->line('label_preco'); ?></label>
                    <select name="valor" onchange="submit();" >
                        <option value="">--<?php echo $this->lang->line('label_selecione'); ?>--</option>
                        <?php foreach (produtoModel::getProdutoCategoria(7) as $produto) { ?>
                            <option value="<?php echo $produto->pr_valor; ?>">US$: <?php echo $produto->pr_valor; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </form>

        <?php
        
        $categorias = categoriaModel::getCategorias(7, 'result');

        foreach ($categorias as $categoria) {
            ?>
            <h3><?php echo $categoria->ca_descricao; ?></h3>
            <hr/>
            <ul class="thumbnails">
                <?php foreach (produtoModel::getProdutoCategoria($categoria->ca_id, $this->input->get('produto'), $this->input->get('valor'),$this->uri->segment(3)) as $produto_kit) { ?>
                <li class="span3" style="height: 247px;">
                        <?php
                        $image = produtoModel::get_imagem($produto_kit->pr_id, true);
                        $image_produto = produtoModel::get_imagem_thumb($produto_kit->pr_id, true);
                        ?>
                        <a rel="<?php echo $produto_kit->pr_id; ?>"  href="<?php echo produtoModel::validar_imagem($image->url); ?>" class="zoom item">
                            <img  src="<?php echo produtoModel::validar_imagem($image_produto->url); ?>" width="<?php echo $image_produto->width; ?>"/>
                        </a>
                         <?php 
                         $produto_nome = explode('/',$produto_kit->pr_nome);
                         $key=0;
                         
                         if($_SESSION['lang']=='en'){
                             $key=1;
                         }
                         
                         if($_SESSION['lang'] == 'pt'){
                             $key=2;
                         }
                         
                         
                         ?>
                          
                        <h6 style="clear: both;">
                            <?php  echo isset($produto_nome[$key])?$produto_nome[$key]:$produto_nome[0]; ?>
                            <span style="font-size: 13px;font-weight:normal;"><?php echo str_reduce($produto_kit->pr_descricao, 70); ?></span>
                        </h6>                        
                        <span style="color: red;">US$: <?php echo $produto_kit->pr_valor; ?></span><br/>
                        <a href="<?php echo base_url('index.php/loja/comprar_produto_loja_add_carrinho?prod=' . $produto_kit->pr_id); ?>" class="btn btn-primary"><?php echo $this->lang->line('label_comprar'); ?></a>
                    </li>   
                <?php } ?>
            </ul>
        <?php } ?>
            <?php echo $links;?>
    </div>
</div>
<script>
    $(function() {

        $(document).ready(function() {
            $('.zoom').jqzoom();
        });

        $('.item').draggable({
            revert: true,
            proxy: 'clone',
            onStartDrag: function() {
                $(this).draggable('options').cursor = 'not-allowed';
                $(this).draggable('proxy').css('z-index', 10);

            },
            onStopDrag: function() {
                $(this).draggable('options').cursor = 'move';
            }, drag: function(event, ui) {
                $('.zoomPup').css('display', 'none');
                $('.zoomWindow').css('display', 'none');

            }
        });

        $('.cart').droppable({
            hoverClass: 'ui-state-b-hover',
            drop: function(i, objeto) {
                animation_cart(objeto.draggable);
                addNoCarrinho(objeto.draggable.attr('rel'));
            }
        });
        function addNoCarrinho(pr_id) {
            $.ajax({
                url: '<?php echo base_url('index.php/loja/comprar_produto_loja_add_carrinho'); ?>',
                data: {'prod': pr_id},
                type: 'get',
                success: function() {
                    $('.badge').html(parseInt($('.badge').html()) + 1);
                }
            });
        }

        //criando a animação do carrinho 
        function animation_cart(element) {
//            var elementCart = element;
//            
//            var top  = $(elementCart).offset().top;
//            var left = $(elementCart).offset().left;
//            
//            //mantendo o elemeno na possição.
//            $(elementCart).css('left',left);
//            $(elementCart).css('top',top);
        }

    });
</script>
