<?php 
 $id_distribuidor= $this->uri->segment(3);
 
 if(empty($id_distribuidor)){
     redirect(base_url('index.php/distribuidores'));
 }
 $status = $this->db->where('di_id',$id_distribuidor)
                     ->get('distribuidores')->row()->di_login_status;
 ?>
 <div class="box-content min-height">
        <div class="box-content-header">
            <a href="<?php echo base_url('index.php/distribuidores/') ?>">Distribuidores</a> 
                  &raquo; Histórico de bloqueio individual
        </div>
        <div class="box-content-body">
            <h3>Distribuidor : <i><?php echo $this->db->where('di_id',$id_distribuidor)
                    ->get('distribuidores')->row()->di_nome;?></i><br> Situação: <?php echo ($status==0?'Bloqueado':'Ativo');?></h3>
            <hr>
            <form name="bloquear-distribuidor" method="post" action="<?php echo base_url('index.php/distribuidores/alterar_status_login'); ?>" >
                <input type="hidden" name="di_id" id="di_id" value="<?php echo $id_distribuidor;?>"/>
            <div class="row">
                <div class="span">
                     <button type="submit" class="btn <?php echo ($status==0?'btn-primary':'btn-danger') ?>"><?php echo ($status==0?'Desbloquear Usuário':'Bloquear Usuário') ?></button>
                </div>
            </div>
            </form>
            
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Descrição</th>
                        <th>Data</th>
                        <th>Horas</th>
                    </tr>
                  </thead>
                    <?php
                    
                    $historicos = $this->db->where('rdb_id_distribuidor',$id_distribuidor)
                                           ->get('registro_distribuidor_bloqueio')
                                           ->result();
                
                    if(count($historicos)>0){
                      
                    foreach ($historicos as $historico) {?>
                    <tr>
                        <td><?php echo $historico->rdb_id;?></td>
                        <td><?php echo $historico->rdb_mensagem;?></td>
                        <td><?php echo date('d/m/Y',strtotime($historico->rdb_data_bloqueio));?></td>
                        <td><?php echo date('H:i:s',strtotime($historico->rdb_data_bloqueio));?></td>
                    </tr>
                    <?php }
                    
                      }else{?>
                     <tr>
                         <td colspan="4"><center><strong>Nenhum registro foi encotrado.</strong></center></td>   
                     </tr>
                    <?php } ?>
              
            </table>
        </div>
    </div>
