<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />


<div id="content">
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <td colspan="6" class="left"> <a style="float:right" class="button" href="index.php?route=chat/chat/logout_chat&token=<?php echo $this->session->data['token']?>">Sair do chat</a>
</td>
    </tr>
 </thead>
 
 <thead>
  <tr>
    <td width="2%" class="left">Nº</td>
    <td width="20%" class="left">Nome</td>
    <td width="28%" class="left">Assunto</td>
    <td width="22%" class="left">E-mail</td>
    <td width="15%" class="left">Horário</td>
    <td width="13%"></td>
  </tr>
 </thead>
 
 <tbody>
 <?php foreach($conversas as $c){?> 
  <tr <?php echo $c['ch_status']==1?"class='ativo'":""?>>
    <td class="left"><?php echo $c['ch_id']?></td>
    <td class="left"><?php echo $c['ch_nome']?></td>
    <td class="left"><?php echo $c['ch_assunto']?></td>
    <td class="left"><?php echo $c['ch_email']?></td>
    <td class="left"><?php echo date('d/m/Y H:i:s',strtotime($c['ch_data']))?></td>
    <td>
    <?php if($c['ch_status']==1){?>
    <a onClick="window.open('<?php echo $this->url->link('chat/chat/conversa', 'ch_id='.$c['ch_id'].'&token=' . $this->session->data['token'], 'SSL');?>','jan','width=600,height=400')">Atender</a>
    <?php }else{?>
    <a onClick="window.open('<?php echo $this->url->link('chat/chat/conversa', 'ch_id='.$c['ch_id'].'&token=' . $this->session->data['token'], 'SSL');?>','jan','width=600,height=400')">Offline</a>
    <?php }?>
    </td>
  </tr>
  <?php }?>
  </tbody>
 
 <thead>
  <tr>
    <td colspan="6" class="left"> <a style="float:right" class="button" href="index.php?route=chat/chat/logout_chat&token=<?php echo $this->session->data['token']?>">Sair do chat</a>
</td>
    </tr>
 </thead> 
  
</table>


</div>

<style>
#content{
	min-height:300px;
	}
.atendido,.atendente{
	border-bottom:1px solid #e9e9e9;
	font-size:13px;
	padding:2px 0;
	}	
	
.atendido strong{
	color:#606;
	font-size:14px;
	}
.atendente strong{
	color:#F60;
	font-size:14px;
	}
.ativo td{
	color:#060;
	font-weight:bold;
	font-size:13px;
	}			
</style>

<?php echo $footer; ?>

<script type="application/javascript">
  $(function(){	  
	   setInterval('location = ""',10000);
	  });
</script>