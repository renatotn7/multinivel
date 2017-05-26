<h1>Monte seu relatório</h1>

<form name="formulario" target="_blank" action="<?php echo base_url('index.php/relatorios/ativacao/')?>" 
method="get">



<p>
Escolha o mês e o ano:
</p>
<p>
<select name="mes">
<option <?php echo date('m')==01?'selected':''?> value="01">01</option>
<option <?php echo date('m')==02?'selected':''?> value="02">02</option>
<option <?php echo date('m')==03?'selected':''?> value="03">03</option>
<option <?php echo date('m')==04?'selected':''?> value="04">04</option>
<option <?php echo date('m')==05?'selected':''?> value="05">05</option>
<option <?php echo date('m')==06?'selected':''?> value="06">06</option>
<option <?php echo date('m')==07?'selected':''?> value="07">07</option>
<option <?php echo date('m')==08?'selected':''?> value="08">08</option>
<option <?php echo date('m')==09?'selected':''?> value="09">09</option>
<option <?php echo date('m')==10?'selected':''?> value="10">10</option>
<option <?php echo date('m')==11?'selected':''?> value="11">11</option>
<option <?php echo date('m')==12?'selected':''?> value="12">12</option>
</select>
/

<input type="text" size="4" name="ano" value="<?php echo date('Y')?>" />

</p>


<input type="submit" class="botao" value="Gerar Relatório" />
<a class="botao" href="<?php echo base_url('index.php/relatorios/')?>">Voltar</a>
</form>