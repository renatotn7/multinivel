 <?php 
 $d = $this->db
 ->join('cidades','ci_id=di_cidade')
 ->where('di_id',$this->uri->segment(3))
 ->get('distribuidores')->row();
 ?> 

<div class="box-content min-height">
 <div class="box-content-header">
 <a href="<?php echo base_url('index.php/distribuidores/')?>">Distribuidores</a> &raquo; Editar distribuidor
 </div>
 <div class="box-content-body">

<strong>Excluir Usuário:</strong>
<div style="color:#f00">
<h2><?php echo $d->di_nome?> / <?php echo $d->di_usuario?></h2>
Cidade: <?php echo $d->ci_nome?> - <?php echo $d->ci_uf?><br>
</div>
<br>
<br>

<form method="post" 
onSubmit="return confirm('Tem certeza que deseja excluir?')" 
action="<?php echo base_url('index.php/excluir_distribuidor/realizar_exclusao/')?>">
  <input type="hidden" name="ni" value="<?php echo $d->di_id?>" >
  <input type="submit" class="btn btn-danger" 
  value="SIM, EXCLUIR">
  <a class="botao" href="<?php echo base_url('index.php/distribuidores/editar_distribuidor/'.$d->di_id)?>">Cancelar</a>
</form>

</div>
</div>

