<?php $this->lang->load('distribuidor/distribuidor/layout_distribuidor_view'); ?>
<div class="box-content min-height">
 <div class="box-content-header"><?php echo $this->lang->line('title'); ?></div>
 <div class="box-content-body">
 
 
<div class="panel">

<?php 

$dados = isset($dados)?$dados:'meus_dados';
$this->load->view('distribuidor/menu_usuario_view',array('active'=>$dados))?>


<?php 


	$this->load->view("distribuidor/{$dados}_view");
	
?>

</div>
</div>
</div>



<script>

$(function(){
	 
	 /*ESTADOS AJAX*/
	 $(".ajax-uf").change(function(){
		  
		  var uf_sel_id = $(this).val();
		  $(".recebe-cidade").html("<option value=''>Aguarde...</option>");
		  $.ajax({
			  url:'<?php echo base_url('index.php/distribuidor/cidades')?>',
			  type:'POST',
			  data:{es_id:uf_sel_id},
			  dataType:'json',
			  success:function(cidadesJson){
				  var txt_cidades = "<option value=''>--Selecione a cidade--</option>";
				    $.each(cidadesJson,function(index, cidade){
						txt_cidades += "<option value='"+cidade.ci_id+"'>"+cidade.ci_nome+"</option>";
						});
				  $(".recebe-cidade").html(txt_cidades);
				  $(".recebe-cidade").removeAttr("disabled");		
				  }
			  });
		  
		 });
		 /*ESTADOS AJAX*/ 
	
	
	/*MASCARAS*/
	 $(".mtel").mask("(99)9999-9999?9");
   $(".mcep").mask("99999-999");
   $(".mcpf").mask("99999999999");
    $(".mcpf_number").mask("99999999999");
   $(".mdata").mask("99/99/9999");
   $(".mcnpj").mask("99.999.999/9999-99");
   $(".mhora").mask("99:99:99");
   $(".mdata_metade").mask("99/9999");
	 
	  
	});	
//Validação no ATM de usuario
$(document).ready(function(){
 $('.validar-atm').click(function(){
     $('.semcadastro').addClass('hide').removeClass('in');
 });

 $('.validar-atm').blur(function(){
     $('.status').show();
     $('#di_niv').attr('readonly',true);
     $.ajax({
     url:'<?php echo base_url('index.php/distribuidor/validar_conta_empresa');?>',
     type:'post',
     data:{'di_email_atm':$(this).val()},
     dataType:'json',
     success:function(data){
           $('.status').hide();
           $('#di_niv').attr('readonly',false);
           
        //Usuario não cadastrado.   
        if(data.status == "1"){  
            //Cadastro não exite na ATM
            $('.semcadastro').addClass('in').removeClass('hide');
        }
       
        //Usuário cadastrado. 
        if(data.status == "2"){
             $('#di_niv').val(data.niv);
        }
        
    }
 });
     
 });
});


	
</script>

