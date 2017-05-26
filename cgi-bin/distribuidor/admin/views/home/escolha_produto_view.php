<?php 
    $this->lang->load('distribuidor/home/index_view');
    $base_url_img_into = base_url('public/imagem/') . $this->lang->line('url_introducao');
    $base_url_img1 = base_url('public/imagem/') . $this->lang->line('url_opcao_img1');
    $base_url_img2 = base_url('public/imagem/') . $this->lang->line('url_opcao_img2');
    $base_url_img3 = base_url('public/imagem/') . $this->lang->line('url_opcao_img3');
   
?>

<img src="<?php echo $base_url_img_into;?>" />
<img src="<?php echo $base_url_img1;?>" />
<div class='row'>
    <div style='text-align: center;width: 100%;'>
        <a class='btn btn-success btn-large btn-escola' rel="1" href='#'><?php echo $this->lang->line('label_escolha_este');?></a>
    </div>
</div>
<img src="<?php echo $base_url_img2;?>" />
<div class='row'>
    <div style='text-align: center;width: 100%;'>
        <a class='btn btn-success btn-large btn-escola'  rel="2"  href='#'><?php echo $this->lang->line('label_escolha_este');?></a>
    </div>
</div>
<img src="<?php echo $base_url_img3;?>" />
<div class='row'>
    <div style='text-align: center;width: 100%;'>
        <a class='btn btn-success btn-large btn-escola' rel="3" href='#'><?php echo $this->lang->line('label_escolha_este');?></a>
    </div>
</div>