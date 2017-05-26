<style type="text/css">
    .bloqueado{
        color: #FD0B0B !important;
    }
</style>
<div class="box-content min-height">
    <div class="box-content-header">Cadastros Financiados</div>
    <div class="box-content-body">

        <form name="formulario" action="<?php echo base_url('index.php/distribuidores/financiados') ?>" method="get">
            <div class="row">
                <div class="span3">
                    <label for="usuario">Usuário:</label>
                    <input type="text" name="usuario" style="width:220px;" size="10" value="<?php echo get_parameter('ni') ?>"> 
                </div>
                <div class="span3">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" value="<?php echo get_parameter('nome') ?>">
                </div>
                <div class="span2">
                    <label for="cpf">CPF:</label>
                    <input type="text" name="cpf" style="width:110px;" value="<?php echo get_parameter('cpf') ?>">
                </div>
                <div class="span2">
                    <label for="niv">Niv:</label>
                    <input type="text" name="niv" id="niv" style="width:110px;" value="<?php echo get_parameter('niv') ?>">
                </div>
                <div class="span2">
                    <label for="email">E-mail:</label>
                    <input type="text" name="email" id="email" style="width:110px;" value="<?php echo get_parameter('email') ?>">
                </div>
            </div>
            <div class="row">
                <div class="span3">
                    <label for="pais">País:</label>
                    <select name="pais" class="ajax-pais" >
                        <option value="" selected="selected">--Indiferente--</option>
                        <?php
                        $paises = $this->db->get('pais')->result();
                        foreach ($paises as $pais) {
                            ?>
                            ?>
                            <option value="<?php echo $pais->ps_id; ?>" <?php echo get_parameter('pais') == $pais->ps_id ? 'selected' : ''; ?> ><?php echo $pais->ps_nome; ?></option>
                        <?php } ?> 
                    </select>
                </div>
                <div class="span2">
                    <label for="uf">Estado:</label>
                    <select name="uf" style="width:150px;"  class="ajax-uf recebe-uf">
                        <option value="">--Indiferente--</option>
                        <?php
                        $es = $this->db->get('estados')->result();
                        foreach ($es as $e) {
                            ?>
                            <option <?php echo get_parameter('uf') == $e->es_id ? 'selected' : ''; ?> value="<?php echo $e->es_id ?>"><?php echo $e->es_uf ?></option>
                       <?php } ?>
                    </select>
                </div>
                <div class="span2">
                    <label>Situação:</label>
                    <select name="situacao" style="width:150px; margin:0;" >
                        <option value="">--Indiferente--</option>
                        <option value="1" <?php echo get_parameter('situacao')==1?'selected':'';?> >Quitados</option>
                        <option value="2" <?php echo get_parameter('situacao')==2?'selected':'';?> >Parcelas Pendentes</option>
                    </select>
                </div>

                <div class="span2">
                    <label>Total Por Página:</label>
                    <select id="totalpagina" name="totalpagina">
                        <option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina'] == 20) ? 'selected' : '' ?> value="20">20</option>
                        <option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina'] == 50) ? 'selected' : '' ?> value="50">50</option>
                        <option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina'] == 100) ? 'selected' : ''; ?> value="100">100</option>
                        <option <?php echo (isset($_GET['totalpagina']) && $_GET['totalpagina'] == 'todos') ? 'selected' : ''; ?> value="todos">Todos</option>
                    </select>
                </div>
            </div>


            <input type="submit" class="btn btn-primary" value="Buscar">
            <a href="<?php echo base_url('index.php/distribuidores/financiados');?>" class="btn btn-success">Limpar Filtros</a>
        </form>
        
<!--modal com todo resulmo do financiamento-->
 <?php  foreach ($distribuidores as $d) {?>      
<div id="myModal_<?php echo $d->di_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close"  onclick="hideModal(<?php echo $d->di_id ?>);" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 id="myModalLabel">Resulmo Financiamento: <i style="color:green;" ><?php echo $d->di_nome;?> (<?php echo $d->di_usuario;?>)</i></h4>
        </div>
        <div class="modal-body">
              <table class="table table-hover table-bordered">
                <tr>
                <thead>
                <th>Parcelas Pagas</th>
                <th>parcelas Pendêntes</th>
                </tr>
                <tr>
                    <td>
                        <?php  
                        $parcela_pagas=  ComprasModel::parcelas_pagas($d);
                        if(count($parcela_pagas)){
                        foreach ($parcela_pagas as $parc_paga) {
                             echo $parc_paga->cof_numero_parcela."º Parcela Data Venci.:".date('d/m/Y',  strtotime($parc_paga->cof_data_vencimento)).'<br/>';
                           } 
                        }?>
                    </td>
                    <td>
                          <?php  
                        $parcela_pend=  ComprasModel::parcelas_pendentes($d);
                        if(count($parcela_pend)){
                        foreach ($parcela_pend as $parc_pend) {
                            echo $parc_pend->cof_numero_parcela."º Parcela Data Venci.:".date('d/m/Y',  strtotime($parc_pend->cof_data_vencimento)).'<br/>';
                           } 
                        }?>
                        
                    </td>
                </tr>
            </table>  
        </div>
    </div>
<?php }?>

        <table width="100%" class="table table-hover table-bordered" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th width="25%">Nome (login)</th>
                    <th width="8%">Patrocinador</th>
                    <th width="16%">País</th>
                    <th width="13%">Tipo de Agência</th>
                    <th width="7%">Data cad.</th>
                    <th width="3%">Parcelas</th>
                    <th width="7%">Situção</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($distribuidores as $d) {
                $pais = DistribuidorDAO::getPais($d->di_cidade);    
                ?>
                    <tr>
                        <td><?php echo $d->di_nome ?> (<?php echo $d->di_usuario ?>)</td>
                        <td><?php echo $d->di_usuario_patrocinador ?></td>
                        <td>
                           
                           <img src="<?php echo base_url('public/imagem/flags/'.$pais->ps_sigla.'.png');?>"/> - 
                           <?php echo $pais->ps_nome;?>
                           
                        </td>
                        <td><?php echo DistribuidorDAO::getPlanoNaoPago($d->di_id)->pa_descricao; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($d->di_data_cad)) ?></td>    
                        <td> <a href="javascript:void"  onclick="showModal(<?php echo $d->di_id ?>);" data-toggle="modal">
                                <?php echo count(ComprasModel::parcelas_pagas($d)); ?>/
                                <?php echo ComprasModel::numero_de_parcelas($d); ?>
                             </a></td>
                             <td><?php echo ComprasModel::tem_parcela_em_aberto($d)?'Pendente':'Quitado';?></td>
                    </tr>
            <?php } ?>  

            </tbody>  
        </table>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><?php echo $links ?></td>
                <td>Total de <b><?php echo $num_distribuidores ?></b> distribuidores</td>
            </tr>
        </table>



    </div>
</div>
<script type="text/javascript">
    function showModal(id) {
        $('#myModal_' + id).removeClass('hide').addClass('in');
    }

    function hideModal(id) {
        $('#myModal_' + id).removeClass('in').addClass('hide');
    }
</script>
<!-- Button to trigger modal --> 
