
<div class="box-content min-height">
 <div class="box-content-header">Monte seu relatório</div>
 <div class="box-content-body">
 
<form name="formulario" target="_blank" action="<?php echo base_url('index.php/relatorios/relatorio_vendas/')?>" 
method="get">


<p>
Venda para:<br>
<select name="tipo">
 <option value="1">CD</option>
 <option value="2">Distribuidores</option>
</select>
</p>

<p>
Data:
</p>
<p>
de:  
<input type="text" class="mdata" style="width:90px;" size="10" value="<?php echo date('01/m/Y')?>" name="de" />
até: <input type="text" class="mdata" style="width:90px;" size="10" value="<?php echo date('d/m/Y')?>" name="ate" />
</p>


<p>
NI distribuidor:<br>
<input type="text" size="10" name="ni" />
</p>



<p>
Forma de Pagamento:<br>
<select name="forma_pagamento">
<option value="">--Todos--</option>
 <option value="8">AstroPay</option>
 <option value="9">Deposito Empresarial Identificado</option>
 <option value="1">Boleto Bancário</option>
 <option value="4">Cartão de crédito</option>
 <option value="2">Dinheiro, Transferência</option>
 <option value="3">Bônus</option>
 <option value="4">Cartão de crédito</option>
</select>
</p>

<p>
Agrupar vendas por cliente: 
<select name="agrupar" style="width:70px;">
 <option value="">Não</option>
 <option value="1">Sim</option>
</select>
</p>

<p>
Mostrar por página:<br>
<select name="por_pagina">
 <option value="40">40 registros</option>
 <option value="70">70 registros</option>
 <option value="100">100 registros</option>
</select>
</p>

<input type="submit" class="btn btn-info" value="Gerar Relatório" />
</form>

</div>
</div>