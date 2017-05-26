
<style>
#mail{
	border:1px solid #ccc;
	border-radius:5px 5px 0 0;
	}

#mail .title td{
	background-image:-webkit-gradient(linear, 0 0%, 100% 0%, from(#E3F2F6), color-stop(0.08, #D6EAF3), to(#D6EAF3));
  background-position:initial initial;
  background-repeat:initial initial;
  border-left-color:#BBD3DA;
  border-left-style:solid;
  border-left-width:1px;
  color:#69939E;
  font-size:11px;
  font-weight:bold;
  overflow:hidden;
  padding:8px 7px;
  text-overflow:ellipsis;
	}
#mail tbody td{
	padding:8px 7px;
	border-bottom:1px solid #ccc;
	color:#333333;
	font-size:13px;
	border-left:1px solid #BBD3DA;
	cursor:pointer;
	}
.nao-lida td{
	font-weight:bold !important;
	}
.page-paginacao{
  background-image:-webkit-gradient(linear, 0 0%, 0 100%, from(#EBEBEB), to(#C6C6C6));
  background-position:initial initial;
  background-repeat:initial initial;
  border-bottom-left-radius:4px;
  border-bottom-right-radius:4px;
  border-top-color:#DDDDDD;
  border-top-left-radius:0;
  border-top-right-radius:0;
  border-top-style:solid;
  border-top-width:1px;
  bottom:0;
  height:22px;
  padding:4px 8px;
  font-size:12px;
	}	
.mail-controles{
	padding:4px 3px;
	}				
</style>
<div class="mail-controles">
 <a href="<?php echo base_url('index.php/mensagem/escolher_distribuidor')?>"><img src="<?php echo base_url()?>public/imagem/mail-nova.png" /></a>
</div>

<table width="100%" id="mail" border="0" cellspacing="0" cellpadding="0">
  <tr class="title">
    <td>Assunto</td>
    <td>de</td>
    <td>data</td>
  </tr>
<tbody> 

<?php 
foreach($msg as $m){
?>  
 <tr onClick="ver(<?php echo $m->me_id?>)" class="<?php echo $m->me_lido==0?'nao-lida':'' ?>">
    <td><?php echo $m->me_assunto?></td>
    <td><?php echo $m->di_nome?></td>
    <td><?php echo date('d/m/Y H:i:s',strtotime($m->me_data))?></td>
 </tr>
<?php }?>  
  
</tbody>
  
</table>
<?php echo $links?>
<script>
function ver($id){
	location = "<?php echo base_url('index.php/mensagem/ver/')?>/"+$id;
	}
</script>

