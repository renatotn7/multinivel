<style>
h1 {
  border-bottom-color:#CDDDDD;
  border-bottom-style:solid;
  border-bottom-width:1px;
  color:#CCCCCC;
  font-size:24px;
  font-weight:normal;
  margin-bottom:15px;
  margin-top:0;
  padding-bottom:5px;
  text-align:left;
  text-transform:uppercase;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div class="box-content min-height">
 <div class="box-content-header"></div>

 <div class="box-content-body">
  <?php foreach($celular as $c){
	  $numero = str_ireplace('(','',$c->di_fone2);
	  $numero = str_ireplace(')','',$numero);
	  $numero = str_ireplace('-','',$numero);
	  $numero = str_ireplace(' ','',$numero);
	  $numero = '0'.$numero;
	  ?>
   <span style="color:#333;font-size:15px;"><?php echo $numero;?></span><br>
  <?php }?>
 </div>
</div>
