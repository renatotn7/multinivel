<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
$pat = $this->db->where('di_id', get_user()->di_ni_patrocinador)->get('distribuidores')->row();
?>
<style>
 .h1{font-size:19px;color:#666;padding:3px;margin-left:4px;text-align:center;}
 input{padding:5px;background:#EEEEEE;border:1px solid #ccc;width:300px;margin-left:5px;}
</style>
<form action="<?php echo base_url('index.php/comprar_cruzeiro/enviar');?>" method="post">
    <table width="550px" border="0" cellpadding="0" cellspacing="0">
     <tr><td colspan="10"><h1 class="h1">Solicitação para Compra de Cruzeiro Marítimo com Bônus</h1></td></tr>
     <tr>
      <td width="100px" valign="top" align="right">
        <p style="padding:5px;">
        Nome :
        </p>
      </td>
      <td width="300px"><input type="text" disabled name="" value="<?php echo get_user()->di_nome."(".get_user()->di_usuario?>"></td>
     </tr>
     <tr>
      <td valign="top" width="100px" align="right">
        <p style="padding:5px;">
        Patrocinador :        
        </p>
      </td>
      <td width="300px">
       <input type="text" disabled name="" value="<?php echo $pat->di_nome."(".$pat->di_usuario.")"?>"></td>
     </tr>
     <tr>
      <td valign="top" width="100px" align="right">
       <p style="padding:5px;">
        Mensagem :
       </p>
      </td>
      <td width="300px">
        <textarea cols="3" name="msg" style="width:300px;height:80px;border:1px solid #ccc;background:#EEEEEE;margin-left:5px;margin-top:10px;outline:none;">
        </textarea>
      </td>
     </tr>
     <tr>
      <td width="100px">
      </td>
      <td width="300px">
       <button style="border:none;background:none;cursor:pointer;margin:16px 8px 0 0px;outline:none;" type="submit" value="Enviar"><img src="<?php echo base_url('public/imagem/btn-comprar-dolar.png')?>" /></button>
      </td>
     </tr>
    </table>
</form>