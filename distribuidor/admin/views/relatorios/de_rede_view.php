<?php 

if(get_parameter('relatorio')=='geracao1'){
	 $geracao = 1;
	 $rs = get_my_geracao($geracao);
	}

if(get_parameter('relatorio')=='geracao2'){
	 $geracao = 2;
	 $rs = get_my_geracao($geracao);
	}

if(get_parameter('relatorio')=='geracao3'){
	 $geracao = 3;
	 $rs = get_my_geracao($geracao);
	}

if(get_parameter('relatorio')=='geracao4'){
	 $geracao = 4;
	 $rs = get_my_geracao($geracao);
	}

if(get_parameter('relatorio')=='geracao5'){
	 $geracao = 5;
	 $rs = get_my_geracao($geracao);
	}

if(get_parameter('relatorio')=='ativos'){
	 $geracao = '-';
	 $rs = $this->db->query("
	 SELECT * FROM distribuidor_ligacao 
	 JOIN distribuidores ON di_id = li_id_distribuidor
	 JOIN distribuidor_qualificacao ON dq_id=di_qualificacao
	 WHERE
	 di_ativo =1 AND
	 li_no = ".get_user()->di_id."
	 ")->result();
	}

if(get_parameter('relatorio')=='inativos'){
	 $geracao = '-';
	 $rs = $this->db->query("
	 SELECT * FROM distribuidor_ligacao 
	 JOIN distribuidores ON di_id = li_id_distribuidor
	 JOIN distribuidor_qualificacao ON dq_id=di_qualificacao
	 WHERE
	 di_ativo = 0 AND
	 li_no = ".get_user()->di_id."
	 ")->result();
	}

function get_my_geracao($geracao){
	
	$ci =& get_instance();
	
	$meu_no = $ci->db
	->where('li_no',get_user()->di_id)
	->where('li_id_distribuidor',get_user()->di_id)
	->get('distribuidor_ligacao')->result();
	
	return  $ci->db->query("
	SELECT * FROM distribuidor_ligacao
	JOIN distribuidores ON di_id  =  li_id_distribuidor
	JOIN distribuidor_qualificacao ON dq_id=di_qualificacao
	WHERE 
	li_id_distribuidor = li_no
	AND li_posicao = ".($meu_no[0]->li_posicao+$geracao)."
	AND li_id_distribuidor IN(
	SELECT li_id_distribuidor FROM distribuidor_ligacao
	WHERE li_no = ".get_user()->di_id."
	)
	")->result();
	}
?>

<h1>Relátorios de rede</h1>
<form name="formulario" >
 <select name="relatorio">
  <option value="">----</option>
  <option value="geracao1">A - 1º geração</option>
  <option value="geracao2">B - 2º geração</option>
  <option value="geracao3">C - 3º geração</option>
  <option value="geracao4">D - 4º geração</option>
  <option value="geracao5">E - 5º geração</option>
  <option value="ativos">F - Todos ativos</option>
  <option value="inativos">G - Todos os inativos</option>
 </select>
 <input type="submit" class="botao" value="Mostrar" />
</form>


<table id="table-listagem" width="100%" border="0" cellspacing="0" cellpadding="5">
  <thead>
  <tr>
    <td width="5%"><strong>NI</strong></td>
    <td width="6%"><strong>Geração</strong></td>
    <td width="31%"><strong>Nome</strong></td>
    <td width="17%"><strong>Qualificação</strong></td>
    <td width="12%"><strong>Ativo</strong></td>
    <td width="14%"><strong>Compras</strong></td>
    <td width="15%"><strong>Cadastro</strong></td>
  </tr>
  </thead>
 <tbody>
  <?php 
  if(isset($rs)){
	 foreach($rs as $r){ 
	 
	 $compras = $this->db
	 ->select_sum('co_total_valor')
	 ->where('co_pago',1)
	 ->where('co_id_distribuidor',$r->di_id)
	 ->like('co_data_compra',date('Y-m-'))
	 ->get('compras')->result();
	 $valor_compra = $compras[0]->co_total_valor==''?0:$compras[0]->co_total_valor;
  ?>
   <tr>
    <td><?php echo $r->di_id?></td>
    <td><?php echo $geracao?>ª</td>
    <td><?php echo $r->di_nome?></td>
    <td><?php echo $r->dq_descricao?></td>
    <td><?php echo $r->di_ativo==0?'Não':'Sim'?></td>
    <td><?php echo number_format($valor_compra,2,',','.')?></td>
    <td><?php echo date('d/m/Y',strtotime($r->di_data_cad))?></td>
  </tr>
 <?php }}else{
	 ?>
     <tr>
      <td colspan="7">Nenhum dado a ser exibido</td>
     </tr>
	 <?php }?> 
  </tbody>
  </table>

