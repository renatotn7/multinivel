<?php
$dados_editar = new stdClass();
$dados_editar->ps_id = '';
$dados_editar->cp_entrada = '';
$dados_editar->cp_numero_parcela = '';
$dados_editar->cp_juros = '';
$dados_editar->cp_id = '';

if (get_parameter('id')) {
    $dados = $this->db->where('cp_id', get_parameter('id'))
            ->join('pais', 'ps_id=cp_id_pais')
            ->get('config_pais_parcelamento')->row();
    
    if (count($dados) > 0) {
        $dados_editar = $dados;
    }
    
}

?>


<div class="box-content min-height">
    <div class="box-content-header">
        <a href="<?php echo base_url('index.php/financeiro') ?>">Financiamento</a>
    </div>
    <div class="box-content-body">
        <h5>Financiamento</h5>
        <div class="row">
            <form method="post" action="<?php echo base_url('index.php/financiamento/') . '/' . (get_parameter('id') ? 'editar' : 'salvar'); ?>" name="financiamento">
                <?php if(get_parameter('id')){?>
                <input type="hidden" name="cp_id" id="cp_id" value="<?php echo $dados_editar->cp_id;?>"/>
                <?php }?>
                <div class="span3">
                    <label for="ps_id"><strong>Páis</strong>:</label>
                    <select name="cp_id_pais" id="ps_id">
                        <option>--Selecione--</option>
                        <?php
                         if(get_parameter('id')){
                          $paises = $this->db->query('SELECT * FROM (`pais`)')->result();
                         }else{
                          $paises = $this->db->query('SELECT * FROM (`pais`) WHERE PS_ID NOT IN(select cp_id_pais from config_pais_parcelamento )')->result();
                         }
                        
                        foreach ($paises as $pais) {
                            ?>
                        <option <?php echo $dados_editar->ps_id== $pais->ps_id?'selected':'' ;?>  value="<?php echo $pais->ps_id; ?>"><?php echo $pais->ps_nome; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="span2">
                    <label for="cp_entrada"><strong>Entrada(%)</strong>:</label>
                    <input type="text" name="cp_entrada" id="cp_entrada" placeholder="0 (%)" class="span2" value="<?php echo $dados_editar->cp_entrada;?>" />
                </div>
                <div class="span2">
                    <label for="cp_numero_parcela"><strong>Nº Parcela</strong>:</label>
                    <input type="text" name="cp_numero_parcela" id="cp_numero_parcela" placeholder="0" class="span2" value="<?php echo $dados_editar->cp_numero_parcela;?>" onkeydown="validatenumber(event);"/>
                </div>
                <div class="span2">
                    <label for="cp_juros"><strong>Juros(%)</strong>:</label>
                    <input type="text" name="cp_juros" id="cp_entrada" placeholder="0 (%)" class="span2" value="<?php echo $dados_editar->cp_juros;?>" />
                </div>

        </div>
        <div class="row">
            <div class="span2">
                <button type="submit" class="btn btn-primary" ><?php echo (get_parameter('id') ? 'editar' : 'salvar'); ?></button>
            </div>
        </div>
        </form>  
        <br>
        <table class="table talbe-hover table-bordered">
            <tr>
            <thead>
            <th>Nº</th>
            <th>Pais</th>
            <th>Entrada(%)</th>
            <th>Número de Parcelas(%)</th>
            <th>Juros(%)</th>
            <th></th>
            </thead>
            </tr>
            <?php
            $parcelas = $this->db
                            ->join('pais', 'ps_id=cp_id_pais')
                            ->get('config_pais_parcelamento')->result();

            foreach ($parcelas as $parcela) {
                ?>
                <tr>
                    <td><?php echo $parcela->cp_id; ?></td>
                    <td><?php echo $parcela->ps_nome; ?></td>
                    <td><?php echo $parcela->cp_entrada; ?></td>
                    <td><?php echo $parcela->cp_numero_parcela; ?></td>
                    <td><?php echo $parcela->cp_juros; ?></td>
                    <td>
                        <a href="<?php echo base_url('index.php/financiamento/financiamentos') . '?id=' . $parcela->cp_id; ?>">Editar</a>
                        <a href="<?php echo base_url('index.php/financiamento/remover') . '?id=' . $parcela->cp_id; ?>">Remover</a>
                    </td>
                </tr>
<?php } ?>
        </table>      
    </div>
</div>
<script>
    function validatenumber(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /^[0-9\\.\b]+$/;    // allow only numbers [0-9] 
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
</script>