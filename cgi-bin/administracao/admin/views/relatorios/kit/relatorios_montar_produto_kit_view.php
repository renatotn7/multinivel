<h1>Monte seu relatório</h1>

<form name="formulario" target="_blank" action="<?php echo base_url('index.php/relatorios/produto_kit/')?>" 
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
<input type="text" class="mdata" size="10" value="<?php echo date('01/m/Y')?>" name="de" />
até: <input type="text" class="mdata" size="10" value="<?php echo date('d/m/Y')?>" name="ate" />
</p>


<p>
Mostrar por página:<br>
<select name="por_pagina">
 <option value="10">10 registros</option>
 <option value="20">20 registros</option>
 <option value="50">50 registros</option>
 <option value="100">100 registros</option>
</select>
</p>

<p>
Ordem:<br>
<select name="ordem">
 <option value="DESC">Mais Vendidos</option>
 <option value="ASC">Menos Vendidos</option>
</select>
</p>

<input type="submit" class="botao" value="Gerar Relatório" />
<a class="botao" href="<?php echo base_url('index.php/relatorios/')?>">Voltar</a>
</form>