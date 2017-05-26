<style>
.mail-header{
 background-image:-webkit-gradient(linear, 0 0%, 0 100%, from(#FFFFFF), to(#F0F0F0));
  background-position:initial initial;
  background-repeat:initial initial;
  border-bottom-color:#DFDFDF;
  border-bottom-style:solid;
  border-bottom-width:1px;
  padding:3px 0;
  position:relative;
  padding:5px;
  border:1px solid #d9d9d9;
  border-bottom:none;
 }
 .mail-header h2{
	 margin:2px;
	 font-size:15px;
	 }
  .mail-header div{
	  font-size:11px;
	  padding:3px 0;
	   }	 
 .mais-body{
	 border:1px solid #d9d9d9;
	 min-height:100px;
	 padding:10px;
	 font-size:13px;
	 }
</style>

<?php 
$m = $this->db->where('me_id',$this->uri->segment(3))
->join('distribuidores','di_id = me_emissor')
->get('mensagem')->result();
$m = $m[0];
?>

<div class="mail-controles">
 <a href="<?php echo base_url('index.php/mensagem/')?>"><img src="<?php echo base_url()?>public/imagem/voltar-mail.png" /></a>
 <a href="<?php echo base_url('index.php/mensagem/escrever?responder='.$m->me_id)?>"><img src="<?php echo base_url()?>public/imagem/mais-responder.png" /></a>
</div>

<div class="mail-header">
<h2><?php echo $m->me_assunto?></h2>
<div>De: <strong><?php echo $m->di_nome?></strong></div>
<div>Data: <strong><?php echo date('d/m/Y H:i:s',strtotime($m->me_data))?></strong></div>
</div>
<div class="mais-body">
<?php echo $m->me_mensagem?>
</div>

<?php 
$this->db->where('me_id',$this->uri->segment(3))->update('mensagem',array('me_lido'=>1));
?>