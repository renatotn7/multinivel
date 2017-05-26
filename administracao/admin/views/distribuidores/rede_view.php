<div class="box-content min-height">
 <div class="box-content-header">Empreendedores</div>
 <div class="box-content-body">

<link rel="stylesheet" href="<?php echo base_url("public/script/tree")?>/css/jquery.treeview.css" />

<script src="<?php echo base_url("public/script/tree")?>/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo base_url("public/script/tree")?>/js/jquery.treeview.js" type="text/javascript"></script>

<script type="text/javascript">
		$(function() {
			$("#tree").treeview({
				collapsed: true,
				animated: "medium",
				control:"#sidetreecontrol"
			});
		})
		
	</script>
<style>
.rede-pa{
	font-weight:bold;
	padding:5px;
	padding-left:8px;
	color:#06C;
	}
</style>
<?php 
$ni_patrocinador = isset($_GET['ni'])?$_GET['ni']:00000;
function tree_distribuidor($ni_pai,$start=0){
	$ci =& get_instance();
	$dis = $ci->db->select(array('di_id','di_nome','di_ativo'))
	->where('di_ni_patrocinador',$ni_pai)->get('distribuidores')->result();
	 
	 if(count($dis)){
		  echo $start==0?"<ul id='tree' class='filetree'>":"<ul>";
		  $start++;
		  
		  foreach($dis as $d){
			  echo "<li><span class='folder'>
			  <a onClick='mostra_info(\"$d->di_id\")' class='".(($d->di_ativo==1)?"ativo":"inativo")."' href='javascript:void(0)'>{$d->di_nome} [{$d->di_id}]</a></span>";
			  tree_distribuidor($d->di_id,$start);
			  echo "</li>";
			  }
			  
		  echo "</ul>";
		 }
	
	}
?>


<h1>
<a href="<?php echo base_url()?>">Principal</a> >> 
<a href="<?php echo base_url('index.php/distribuidores')?>">Rede</a> >>
Distribuidores</h1>

<div class="painel">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" valign="top" style="border-right:3px solid #f3f3f3;">
    <?php if($ni_patrocinador!=0){
		$pa = $this->db->where('di_id',$ni_patrocinador)->get('distribuidores')->result();
		?>
       <div class="rede-pa">Rede de <?php echo $pa[0]->di_nome?> / <?php echo $pa[0]->di_id?></div>
       <?php }?>
        <div id="arvore-distribuidor">
		<?php
        tree_distribuidor($ni_patrocinador);
        ?>
        </div>

    </td>
    <td valign="top">
    <div id="arvore-info-distribuidor">
    </div>
    </td>
  </tr>
</table>



</div>
<style>
 .ativo{
	 color:#090 !important;
	 }
</style>
<script type="text/javascript">
 function mostra_info(id){
	  $("#arvore-info-distribuidor").html("<img src='<?php echo base_url("public/script/tree/css/images/ajax-loader.gif")?>' />"); 
	  $.ajax({
		    url:"<?php echo base_url("index.php/distribuidores/distribuidor_info_ajax")?>",
			type:"POST",
			data:{ni:id},
			dataType:"json",
			success:function(dataJson){
				
			   var txt = "<h2>"+dataJson.di_nome+" / "+dataJson.di_id+"</h2>";
			
				txt += "<table width='100%' border='0' cellspacing='0' cellpadding='4'>";
				txt += "<tr><td align='right'><strong>Patrocinador:</strong></td><td>"+dataJson.di_ni_patrocinador+"</td></tr>";
				txt += "<tr><td  width='100px' align='right'><strong>Cidade/Uf:</strong></td><td>"+dataJson.ci_nome+"-"+dataJson.ci_uf+"</td></tr>";
				txt += "<tr><td align='right'><strong>Telefone:</strong></td><td>"+dataJson.di_fone1+"</td></tr>";
                txt += "<tr><td align='right'><strong>E-mail:</strong></td><td>"+dataJson.di_email+"</td></tr>";
				txt += "<tr><td align='right'><strong>Situação:</strong></td><td>"+(dataJson.di_ativo==0?"Inativo":"Ativo")+"</td></tr>";
                txt += "<tr><td align='right'><strong>Dt Cad.:</strong></td><td>"+dataJson.di_data_cad+"</td></tr>";
				txt += "<tr><td align='right'><strong>GRADUAÇÃO:</strong></td><td>"+dataJson.dq_descricao+"</td></tr>";					
				txt += "<tr><td align='right'></td></tr>";
			txt += "<tr><td align='right'><strong>V.PP:</strong></td><td>"+dataJson.pp+"</td></tr>";
			txt += "<tr><td align='right'><strong>V.PG:</strong></td><td>"+dataJson.pg+"</td></tr>";
			txt += "<tr><td align='right'><strong>V.FR:</strong></td><td>R$ "+dataJson.vfr+"</td></tr>";					
			
				txt += "</table>";
				
				$("#arvore-info-distribuidor").html(txt); 
				},
			error:function(erro){
				$("#arvore-info-distribuidor").html("<div>Desculpe ocorreu um erro, tente novamente.</div>");
				}	
		  });
	 }
</script>


</div>
</div>
