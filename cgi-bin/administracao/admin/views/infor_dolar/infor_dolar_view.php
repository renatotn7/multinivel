<div class="box-content min-height">
 <div class="box-content-header">Informativo valor do dólar</div>
 <div class="box-content-body">
 <?php 
   $valorDolar = $this->db->where('field', 'cotacao_dolar')->get('config')->row();
 ?>
 <br>
  <form name="form-dolar" method="post">
   <label>Valor do Dólar :</label>
   
   <input type="text" name="valor_dolar" value="<?php echo $valorDolar->valor?>" /><br>
   
   <input type="submit" value="Atulaizar" class="btn btn-success">
  </form> 
 </div>
 </div>