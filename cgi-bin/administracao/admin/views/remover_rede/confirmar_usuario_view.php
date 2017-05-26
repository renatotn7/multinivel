<div class="box-content min-height">
    <div class="box-content-header">Confirmar Usuário</div>
    <div class="box-content-body">

        <table class="table table-bordered">
            <tr>
                <td width='200px'><b>Operação:</b></td>
                <td><?php echo $tipoOperacao ?></td>
            </tr>
            <tr>
                <td><b>Nome:</b></td>
                <td><?php echo $distribuidor->di_nome ?></td>
            </tr>
            <tr>
                <td><b>Usuário:</b></td>
                <td><?php echo $distribuidor->di_usuario ?></td>
            </tr>
            <tr>
                <td><b>Compras Pagas:</b></td>
                <td><?php
                    foreach ($comprasPagas as $compraPaga) {
                        echo "<a target='blank' href='" . base_url('index.php/pedidos_distribuidor/pedido_imprimir/' . $compraPaga->co_id) . "'>#{$compraPaga->co_id}</a>, ";
                    }
                    ?></td>
            </tr> 
            <tr>
                <td><b>Está na rede:</b></td>
                <td><?php echo $estaNaRede ? 'Sim' : 'Não' ?></td>
            </tr>
            <tr>
                <td><b>Indicados Diretos:</b></td>
                <td><?php
                    foreach ($diretos as $direto) {
                        echo "{$direto->di_usuario} ,";
                    }
                    ?></td>
            </tr>
            <tr>
                <td><b>Indicados Diretos Na Rede:</b></td>
                <td><?php
                    foreach ($diretosNaRede as $diretoNarede) {
                        echo "{$diretoNarede->di_usuario} ,";
                    }
                    ?></td>
            </tr>
            <?php if ($tipoOperacaoInt == 2) { ?>
                <tr>
                    <td><b>Distribuidores que serão excluidos:</b></td>
                    <td>
                        <p><?php echo count($distribuidoresSeraoExcluidos)?> serão excluidos</p>
                        <?php
                        foreach ($distribuidoresSeraoExcluidos as $distribuidoreSeraExcluido) {
                            echo "{$distribuidoreSeraExcluido->di_usuario} ,";
                        }
                        ?></td>
                </tr>
            <?php } ?>

            <tr>
                <td><b>Situaçao: </b></td>
                <td><?php
                    $status = true;
                    if ($estaNaRede && count($distribuidoresSeraoExcluidos) > 1 && $tipoOperacaoInt == 1) {
                        $status = false;
                        echo "<div class='label label-important'>-Não é possível excluir um distribuidor com rede.</div>";
                    }
                    if ($status == true) {
                        if($tipoOperacaoInt == 1){
                        echo "<div class='label label-success'>-O distribuidor pode ser excluido</div>";
                        }else{
                          echo "<div class='label label-success'>-O distribuidor e sua rede pode ser excluido</div>";  
                        }
                    }
                    ?></td>
            </tr>
        </table>

        <?php if($status == true){?>
        <p>- ATENÇÃO: Essa operação não tem volta.</p>
        <a onclick="return confirm('Deseja realmente excluir?\nEssa operação não tem volta.')" 
           class="btn btn-danger" 
           href="<?php echo base_url('index.php/remover_rede/'.($tipoOperacaoInt==1?'remover_usuario':'remover_rede_usuario').'/'.$distribuidor->di_id.'?id='.$id)?>">Confirmar operação</a>
        <?php }?>
    </div>
</div>