<div class="box-content">
    <div class="box-content-header">Detalhes de Rede Linear</div>
    <table class="table table-bordered">
        <tr>
            <?php for ($geracao = 1; $geracao <= 10; $geracao++) { ?>
                <th bgcolor="#f3f3f3"><?php echo $geracao ?>ª geração</th>
            <?php } ?>
        </tr>
        <tr>
            <?php for ($geracao = 1; $geracao <= 10; $geracao++) { ?>
            <td style="padding: 0;"> 
                    <?php foreach ($redeLinear[$geracao] as $k=> $distribuidor) { ?>
                <div class="user-g">
                     
                    <div class="user-g-modal">
                    <div><?php echo $distribuidor->di_usuario ?></div>
                    <div style='color:#3f256f;font-size:9px;'><?php echo DistribuidorDAO::getPlano($distribuidor->di_id)->pa_descricao?></div>
                    <?php 
                    $bonusPago = BonusVendaVolume::getRegistroBonusPago(get_user()->di_id, $distribuidor->di_usuario);
                    if(count($bonusPago) > 0){
                        echo "<div style='color:green;font-size:10px;'>BU: R$ {$bonusPago->cb_credito}</div>";
                    }
                     $bonusPerdido = BonusVendaVolume::getRegistroBonusPerdido(get_user()->di_id, $distribuidor->di_usuario);
                   
                    if(count($bonusPerdido) > 0){
                          echo "<div style='color:red; font-size:10px;'>BU: R$ {$bonusPerdido->cb_credito}</div>";  
                    }
                    ?>
                    </div>
                </div>
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
    </table>

</div>

<style>
    .user-g{
        padding: 2px 4px;
        border-bottom: 1px solid #efefef;
        background: #a6d5f5;
        font-size: 12px;
        position: relative;
        width: 100px;
        height: 55px;
    }
    .user-g:hover .user-g-modal{
        position: absolute;
        top: 0;
        left: 0;
        font-size: 20px;
        background: #fff;
        padding: 10px;
        min-width: 100px;
        z-index: 100;
    }
</style>