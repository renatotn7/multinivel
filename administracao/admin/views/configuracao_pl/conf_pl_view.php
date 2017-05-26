<div class="box-content min-height">
    <div class="box-content-header">Configuração de País da PL</div>
    <div class="box-content-body">
        <form action="<?php echo base_url('index.php/configuracao_pl/adcionar');?>" method="post" name="form1" id="form1">
            <div class="row">
                <div class="span">
                   <h4>Adcionar Valor para PL </h4>
                </div>
            </div>
            <div class="row">
             
                <div class="span3">
                    <label class="control-label" for="inputError">País</label>
                    <div class="controls">
                        <?php
                        $pais_ = $this->db->get('pais')->result();
                        echo CHtml::dropdow('pais', CHtml::arrayDataOption($pais_, 'ps_id', 'ps_nome'), array(
                            'empty' => '--Selecione--'
                        ));
                        ?>
                    </div>
                </div>
                <div class="span3">
                    <label class="control-label" for="inputError">Valor PL (US$)</label>
                    <div class="controls">
                        <?php echo CHtml::textInput('valor_pl', array('class' => 'moeda')); ?>
                    </div>
                </div>
            </div>

            <button type="submit"class="btn btn-primary">Adcionar</button>
        </form>
        <hr>
        <form action="<?php echo base_url('index.php/configuracao_pl/salvar');?>" method="post" name="form2" id="form2">
            <?php
            $paises = $this->db
                            ->join('pais', 'ps_id=bpl_id_pais')
                            ->get('bonus_pl_por_pais')->result();
            if (count($paises) > 0) {
                ?>
                <?php foreach ($paises as $key => $pais) { ?>
                    <?php echo CHtml::textHidden('bpl_id[]', array('value' => $pais->bpl_id)); ?>
                    <div class="row">
                        <div class="span3">
                            <label class="control-label" >País</label>
                            <div class="controls">
                              <?php  echo CHtml::dropdow('pais_' . $key, CHtml::arrayDataOption($pais_, 'ps_id', 'ps_nome'), array(
                                'empty' => '--Selecione--',
                                 'selected'=>$pais->ps_id
                               ));
                              ?>
                            </div>
                        </div>
                        <div class="span2">
                            <label class="control-label" for="inputError">Valor PL USR$</label>
                            <div class="controls">
                                <?php echo CHtml::textInput('valor_pl_' . $key, array('value' => $pais->bpl_valor,'class'=>'moeda span2')); ?>
                            </div>
                        </div>
                        <div class="span2">
                            <br>
                            <div class="controls">
                              <a href="<?php echo base_url('index.php/configuracao_pl/remover?cod='.$pais->bpl_id)?>"  target="_SELF" class="btn btn-danger" type="submit" >Remover</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
        </form>
    </div>
</div>