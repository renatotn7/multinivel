<div class="box-content min-height">
    <div class="box-content-header">
        <a href="<?php echo base_url('index.php/kits') ?>">Editar Kits</a>
    </div>
    <div class="box-content-body">
        <form action="<?php echo base_url('index.php/kits/AtualizarKits/' . $kit->pr_id); ?>" name="form-kits" method="post">
            <div class="row">
                <div class="span4">
                    <label for="pr_nome">Nome do kit</label>
                    <input type="text" name="pr_nome" id="pr_nome" class="span4"  value="<?php echo $kit->pr_nome ?>"/>
                </div>
                <div class="span2">
                    <label for="pr_valor">Valor do kit</label>
                    <input type="text" name="pr_valor" id="pr_valor" class="span2 moeda"  value="<?php echo $kit->pr_valor ?>"/>
                </div>
                <br>
                <div class="span4">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>

    </div>
</div>