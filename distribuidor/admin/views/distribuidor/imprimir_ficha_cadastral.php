<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ficha cadastral - <?php echo get_user()->di_nome?></title>
</head>

<body style="width:1000px; margin:0 auto;" >

<table width="100%" border="0" cellspacing="0" cellpadding="8">
  <tr>
    <td><p style="font-size:30px;"><strong>NI DO DISTRIBUIDOR</strong><br />
 <div class="field" style="width:200px;font-size:30px;"><?php echo get_user()->di_id?></div>
</p>
</td>
<td style="font-size:20px;">ACORDO PARA CLIENTE ESPECIAL DISTRIBUIDOR INDEPENDENTE Nossa Empresa!
<p style="font-size:15px">
O ABAIXO ASSINADO, POR MEIO DESTA, DESEJA TORNAR-SE UM(A) CLIENTE ESPECIAL/ DISTRIBUIDOR 
INDEPENDENTE DOS PRODUTOS Nossa Empresa!
</p>
</td>
    
    <td><img src="<?php echo base_url()?>public/imagem/logo.png" /></td>
  </tr>
</table>
<br />
<fieldset >
<legend>Dados  Pessoais:</legend>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%">
<p style="color:#004030;"><strong>Nome:</strong><br />
 <div class="field"><?php echo get_user()->di_nome?></div>
</p>
</td>
    <td>
<p><strong>NI DE QUEM INDICOU</strong><br />
 <div class="field" style="width:300px;"><?php echo get_user()->di_ni_patrocinador?></div>
</p>    
    </td>
  </tr>
</table>




<style>
body{font-size:15px;}
table{color:#004030;}
p{margin:2px 0;}
p strong{font-size:15px; display:inline-block; padding:4px 0; }
.field{border-bottom:1px solid #ccc;
 margin-right:10px;
 font-size:25px;
 font-family:Verdana, Geneva, sans-serif;
  color:#333;
 }

</style>

<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="465">
    <p>
     <strong>RG:</strong><br />
     <div class="field"><?php echo get_user()->di_rg?></div>
     </p>
    </td>
    <td width="519">
    <p>
     <strong>CPF:</strong><br />
    <div class="field"> <?php echo get_user()->di_cpf?></div>
     </p>
    </td>
  </tr>
</table>


<table width="800px" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="333">
    <p><strong>Estado civil:</strong><br />
    <div class="field"><?php echo get_user()->di_estado_civil?></div>
    </p>
</td>
<td width="451">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><p>
Data de nascimento:<br />
<div class="field"><?php echo date('d/m/Y',strtotime(get_user()->di_data_nascimento))?></div>
</p></td>
    <td><p>Sexo:<br />
     <div class="field"><?php echo get_user()->di_sexo=='M'?'Masculino':'Feminino';?></div>
     </p>
    </td>
  </tr>
</table>



</td>
</tr>
</table>

<p>
Número de dependentes:<br />
<div class="field" style="width:200px"><?php echo get_user()->di_dependentes?></div>
</p>

<p style="color:#004030;" >
PIS:<br />
<div class="field" style="width:200px"><?php echo get_user()->di_inss_pis?></div>
</p>



</fieldset>

<br />

<fieldset>
<legend>Endereço</legend>

<p style="color:#004030;">
<strong>Endereço:</strong><br />
<div class="field"><?php echo get_user()->di_endereco?></div>
</p>

<table width="800px" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="333">
    <p>
     <strong>Bairro:</strong><br />
     <div class="field"><?php echo get_user()->di_bairro?></div>
     </p>
    </td>
    <td width="451">
    <p>
     <strong>CEP:</strong><br />
     <div class="field"><?php echo get_user()->di_cep?></div>
     </p>
    </td>
  </tr>
</table>



<table width="800px" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="332">
    <p>
    <strong>Uf:</strong><br>
    <div class="field"><?php echo get_user()->ci_uf?></div>
    </p>
    </td>
    <td width="452">
    <p>
    <strong>Cidade:</strong><br>
   <div class="field"><?php echo get_user()->ci_nome?></div>
    </p>
    </td>
  </tr>
</table>

<br>


<table width="800px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="341">
    <p>
    <strong>Telefone:</strong><br>
    <div class="field"><?php echo get_user()->di_fone1?></div>
    </p>
    </td>
    <td width="459">
    <p>
    <strong>Celular:</strong><br>
    <div class="field"><?php echo get_user()->di_fone2?></div>
    </p>
    </td>
  </tr>
</table>

<p><strong>E-mail:</strong><br>
<div class="field"><?php echo get_user()->di_email?></div>
</p>

<p id="subtitulo"><strong>Comprovante de endereço</strong></p>
<p>
<div class="field"><?php echo get_user()->di_comprovante_titular==1?'Próprio':'Outros'?></div>
<?php if(get_user()->di_comprovante_titular==0){?>
 <span id="">
  <strong>Nome:</strong> <div class="field"><?php echo get_user()->di_comprovante_nome?></div>
  <strong>Motivo:</strong> <div class="field"><?php echo get_user()->di_comprovante_motivo?></div>
 </span>
 <?php }?>
 </p>


 </fieldset>
 <br>
<fieldset >
<legend>Beneficiários</legend>

<table width="800px" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="341"><p><strong>Beneficiario:</strong><br>
    <div class="field"><?php echo get_user()->di_beneficiario1?></div>
   </p></td>
    <td width="459"><p><strong>Fone:</strong><br>
    <div class="field"><?php echo get_user()->di_beneficiario_fone1?></div>
    </p></td>
  </tr>
</table>


<table width="800px" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="341"><p><strong>Beneficiario:</strong><br>
    <div class="field"><?php echo get_user()->di_beneficiario2?></div>
    </p></td>
    <td width="459"><p><strong>Fone:</strong><br>
    <div class="field"><?php echo get_user()->di_beneficiario_fone2?></div>
    </p></td>
  </tr>
</table>

<table width="800px" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="341"><p><strong>Beneficiario:</strong><br>
    <div class="field"><?php echo get_user()->di_beneficiario3?></div>
    </p></td>
    <td width="459"><p><strong>Fone:</strong><br>
    <div class="field"><?php echo get_user()->di_beneficiario_fone3?></div>
    </p></td>
  </tr>
</table>    
</fieldset>
<br />
<fieldset >
<legend>Dados  Bancário:</legend>
<table width="800px" cellpadding="0" cellspacing="0" border="0">
  <tr>
   <td  align="left">
    <p id="subtitulo">Autorizo o crédito dos valores a que tiver direito, seja feito na conta abaixo</p> 
   </td>
  </tr>
  <tr>
   <td>
    <p><strong>Banco :</strong><br />
    <div class="field"><?php echo get_user()->di_conta_banco?></div>
    </p>
    
    <p><strong>Agência:</strong> <br />
    <div class="field"><?php echo get_user()->di_conta_agencia?></div>
    <br />
     <strong>Número da conta</strong><br />
     <div class="field"><?php echo get_user()->di_conta_numero?></div>
     </p>
    <p><strong>Operação/variação </strong><br />
    <div class="field"><?php echo get_user()->di_conta_variacao?></div>
    </p>
    <br />
    <p><strong>Nome do titular da conta</strong><br />
    
    <div class="field"><?php echo get_user()->di_conta_nome?></div>
    </p>
    <p><strong>CPF do titular da conta</strong><br />
    <div class="field"><?php echo get_user()->di_conta_cpf?></div>
    </p>
   </td>
  </tr>
</table>
<br />
<br />
<p><strong>TERMOS E CONDIÇÕES</strong></p>
<p>Por intermédio deste acordo de distribuição independente e  formulário, de um lado a Nossa Empresa, e do outro lado o Distribuidor  Independente VIP, doravante denominado DISTRIBUIDOR VIP, caracterizado no  formulário deste instrumento, e têem entre si justo e contratado o seguinte:  Para torna-se um DISTRIBUIDROR INDEPENDENTE Nossa Empresa, o mesmo  deverá&nbsp;através do NI do patrocinador que o indicou ao negócio, acessar o  site e assim que preencher este questionário será criado o NI do novo Distribuidor,  e depois deste passo deverá também preencher corretamente aos quesitos neste  formulário, ser maior de idade, e adquirir o KIT EVOLUTION VIP, e comprovar o  pagamento, tendo a partir daí o direito a venda dos produtos Nossa Empresa, sem  quaisquer exclusividade, e a participação no programa de Marketing Multinível Evolution  praticado pela empresa e seus integrantes, como especificado no Plano de  Negócios Nossa Empresa, devidamente registrado.</p>
<p>1 - O Distribuidor Independente da Nossa Empresa ativo, terá  os seguintes direitos:</p>
<p>1.1 - Comprar&nbsp;os produtos da Nossa Empresa para a uso,  bem como, para revenda com os descontos praticados pela empresa em conformidade  com estes Termos e Condições, bem como, com o Plano de Negócios Evolution VIP,  que acompanha o KIT EVOLUTION VIP.</p>
<p>1.2 – Indicar&nbsp;e ou patrocinar pessoas na Nossa Empresa.</p>
<p>1.3 - Receber bônus de acordo com o Plano de Marketing Evolution  da Nossa Empresa, segundo o meu desempenho no mês em epígrafe.</p>
<p>2 - Apresentar o Plano De Marketing Evolution da VIP  Essência assim como os seus produtos, conforme estabelecido nos materiais  impressos oficiais desta empresa.</p>
<p>2.1 - O Distribuidor Independente poderá cancelar o presente  Contrato a qualquer tempo, sem justificativa alguma, através de uma  notificação, por escrito, à Nossa Empresa.</p>
<p>&nbsp;</p>
<p>2.2 – Ter acesso a um escritório virtual disponibilizado  pela Nossa Empresa, dentro de seu site, com login e senha pessoal, para  administrar sua rede.</p>
<p>3 - Concordo que, na qualidade de Distribuidor Independente  da Nossa Empresa, sou um contratado independente para todos os fins, não  configurando quaisquer relação empregatícia, sócio, representante legal ou  franquiado da Nossa Empresa. Concordo que eu serei exclusivamente responsável  pelo pagamento de todas e quaisquer despesas por mim contraídas, no processo de  vendas,&nbsp;indicações e treinamentos. </p>
<p>4 - Declaro que li e concordo em cumprir com este contrato,  o plano de negócios e Marketing da Nossa Empresa, os quais formam um todo e  passam a integrar estes Termos e Condições. Eu compreendo que devo estar em  situação regular e ativo para me tornar elegível aos bônus da Nossa Empresa,  conforme o desempenho no mês em curso. Eu compreendo que o presente contrato, o  plano de negócios e Marketing da Nossa Empresa poderão ser alterados a critério  da Nossa Empresa, e eu concordo em aderir a todas as mencionadas alterações, sem  nada reclamar. A notificação de alterações deverá ser incluída no escritório  virtual do Distribuidor VIP e ou no site da empresa. As alterações entrarão em  vigor na data de sua publicação.</p>
<p>5 - O prazo deste Contrato é por tempo indeterminado,  entretanto deverá ser confirmado no mês de janeiro de cada ano com o pagamento  da taxa de R$ 158,00 (cento e cinquenta e oito  reais), sendo que o não pagamento cancela  automaticamente este contrato. Se o meu negócio for cancelado ou rescindido por  este motivo ou outro que eu causar, eu compreendo que perderei definitivamente  todos os direitos na qualidade de um Distribuidor Independente, inclusive,  sobre a rede. A Nossa Empresa se reserva no direito de incluir, excluir,  extinguir e alterar, produtos de toda a sua linha, preços, pontos, percentuais  dos bônus e o seu marketing multinível.</p>
<p>6 – O Distribuidor Vip não poderá ceder nenhum direito ou  delegar suas atribuições nos termos deste Contrato, sem o prévio consentimento,  por escrito, da Nossa Empresa. Quaisquer tentativa de transferência ou cessão do  Contrato sem o expresso consentimento, por escrito,&nbsp;tornará o Contrato  nulo, e a critério da Nossa Empresa poderá resul­tar na rescisão do negócio sem  direito algum sobre a rede.</p>
<p>7 - Este contrato, no seu formato atual e ou conforme  alterado pela Nossa Empresa a seu critério, constitui o acordo integral entre  ela e o Distribuidor VIP. Quaisquer promessas, de­clarações, ofertas ou outras  comunicações que não estejam expressamente previstos neste contrato não tem  valor.</p>
<p>9 -. Qualquer renúncia pela Nossa Empresa de quaisquer  violação do Contrato deverá ser efetuada por escrito e assinada por um diretor  autorizado desta empresa. A renúncia pela Nossa Empresa de qualquer violação do  Contrato pelo Distribuidor não constitui novação.</p>
<p>10 – As entregas dos pedidos serão feitas a priore pelo  PAC-Correios, e o frete dos pedidos acima de R$ 500,00(quinhentos reais) será  pago pela Nossa Empresa. Já os pedidos abaixo deste valor o Distribuidor VIP  pagará o frete total. O envio dos pedidos acontecerá somente após o pagamento e  sensibilização no sistema da empresa.</p>
<p>11 – O prazo de entrega dependerá da localização, de cada  Distribuidor.</p>
<p>12 – Os pagamentos dos bônus ou créditos a que tiver direito  o Distribuidor Vip, serão descontados os impostos e taxas incidentes sobre  estes valores, bem como, as tarifas bancárias envolvidas nestes pagamento,  sendo que estas é do conhecimento do distribuidor.</p>
<p>&nbsp;</p>
<p>13 – Os funcionários da Nossa Empresa não poderão participar  do MMN da empresa, e portanto não terão a oportunidade de fazer rede.</p>
<p>14 - O Distribuidor Independente VIP, autoriza a VIP  Essência a utilizar o seu nome, fotografia, histórico pessoal e/ ou similares  em materiais de propaganda, site da empresa ou promocionais e renuncia a todas  as reivindicações por remuneração ou indenização por tal uso.</p>
<p>15 - Os bônus e demais créditos decorrentes do MMN EVOLUTION  VIP a que tiver direito o Distribuidor Vip, só serão creditados após a chegada  destes documentos em sua sede.</p>
<p>16 – A devolução de mercadoria só será aceita por defeito de  fabricação, e será feita a substituição por outra.</p>
<p>17 – O Conselho VIP é o responsável para solucionar as  dúvidas, omissões ou questões referentes a este contrato. Fica eleito o foro da  Comarca de Goiânia-GO., para dirimir quaisquer questões ou dúvidas referentes a  este contrato, e que não tenham sido solucionadas pelo Conselho VIP.</p>
<p>18 - O Distribuidor VIP deverá enviar uma cópia deste  contrato assinada juntamente com uma cópia do RG, CPF e comprov­ante de  residência no prazo máximo de 15 dias para Nossa Empresa, sitiada à Av. T – 6,  n°. 109, loja 3, Setor Bueno, CEP – 74.210-300, Goiânia-GO. Os bônus e demais  créditos decorrentes do MMN EVOLUTION VIP a que tiver direito o Distribuidor  Vip, só serão creditados após a chegada destes documentos em sua sede.</p>
<p>&nbsp;</p>
<p> _________________,________DE____________________ANO____________</p>
<p>&nbsp;</p>
<p>ASS:_________________________________________<br />
  RG:__________________________________________</p>
</fieldset>
 
</body>
</html>
