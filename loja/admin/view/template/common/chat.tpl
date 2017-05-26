<?php echo $header; ?>

<div id="content">


<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <td class="left">Nome</td>
    <td class="left">E-mail</td>
    <td class="left">Conversa</td>
    <td class="left">Horário</td>
    <td></td>
  </tr>
 </thead>
 <tbody>
 <?php foreach($conversas as $c){?> 
  <tr>
    <td class="left"><?php echo $c['ch_nome']?></td>
    <td class="left"><?php echo $c['ch_email']?></td>
    <td class="left"><?php echo $c['ch_conversa']?></td>
    <td class="left"><?php echo $c['ch_data']?></td>
    <td>
    <?php if($c['ch_status']==1){?>
    <a onClick="window.open('<?php echo $this->url->link('common/chat/conversa', 'ch_id='.$c['ch_id'].'&token=' . $this->session->data['token'], 'SSL');?>','jan','width=900,height=400')">Atender</a>
    <?php }else{?>
    <a href="">Offline</a>
    <?php }?>
    </td>
  </tr>
  <?php }?>
  </tbody>
</table>


</div>

<?php echo $footer; ?>