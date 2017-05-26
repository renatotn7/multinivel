<style>
.class-verde{
	color:#fff !important;
	background:#6FBA4E !important;
	}

.class-vermelho{
	color:#fff !important;
	background:#E85039 !important;
	}	
</style>
<?php $this->lang->load('distribuidor/distribuidor/menu_usuario_view');?>
<?php 
$d = $this->db
->select(array('di_contrato','di_conta_verificada'))
->where('di_id',get_user()->di_id)->get('distribuidores')->row();
?>
<ul class="nav nav-tabs">
    <li <?php echo $active=='meus_dados'?'class="active"':''?>><a href="<?php echo base_url('index.php/distribuidor/meus_dados')?>"><?php echo $this->lang->line('label_dados_pessoais');?></a></li>
    <li style="display:none;" <?php echo $active=='titulares'?'class="active"':''?>><a href="<?php echo base_url('index.php/distribuidor/titulares')?>"><?php echo $this->lang->line('label_titulares');?></a></li>
    <li <?php echo $active=='dados_bancarios'?'class="active"':''?>><a href="<?php echo base_url('index.php/distribuidor/dados_bancarios')?>"><?php echo $this->lang->line('label_dados_bancarios');?></a></li>
    <li <?php echo $active=='mudar_senha'?'class="active"':''?>><a href="<?php echo base_url('index.php/distribuidor/mudar_senha')?>"><?php echo $this->lang->line('label_alterar_senha_login');?></a></li>
    <li <?php echo $active=='mudar_senha2'?'class="active"':''?>><a href="<?php echo base_url('index.php/distribuidor/mudar_senha2')?>"><?php echo $this->lang->line('label_alterar_senha_seguranca_login');?></a>
    </li>  
    <?php if(DistribuidorDAO::contaverificada(get_user())){?>
    <li <?php echo $active=='verificar_conta'?'class="active"':''?>><a class='<?php echo $d->di_conta_verificada==1&&$d->di_contrato==1?'class-verde':'class-vermelho'?>' href="<?php echo base_url('index.php/distribuidor/verificar_conta')?>">
    <?php echo $this->lang->line('label_verificar_conta');?></a></li>
    <?php }?>
</ul>
