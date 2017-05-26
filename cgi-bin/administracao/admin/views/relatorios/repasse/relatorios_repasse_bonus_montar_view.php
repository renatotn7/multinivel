<h1>Monte seu relatório</h1>

<form name="formulario" target="_blank" action="<?php echo base_url('index.php/relatorios/repasse_bonus/')?>" 
method="get">



<p>
Data:
</p>
<p>
de:  
<input type="text" class="mdata" size="10" value="<?php echo date('01/m/Y')?>" name="de" />
até: <input type="text" class="mdata" size="10" value="<?php echo date('d/m/Y')?>" name="ate" />
</p>


<input type="submit" class="botao" value="Gerar Relatório" />
<a class="botao" href="<?php echo base_url('index.php/relatorios/')?>">Voltar</a>
</form>