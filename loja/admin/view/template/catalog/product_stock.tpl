<?php echo $header; ?>


  <div class="box">

    <div class="heading">

      <h1><img src="view/image/product.png" alt="" /> Movimentar Estoque</h1>
      </div>
       <div class="content">
          
             <div class="breadcrumb">

    <?php foreach ($breadcrumbs as $breadcrumb) { ?>

    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>

    <?php } ?>

  </div>
           
           
                 <table class="list">
                    <thead>
                    <tr>
                        <td>Produto</td>
                        <td>Estoque Atual</td>
                        <td></td>
                    </tr>
                    </thead>
                     <tr>
                        <td><?php echo $product['name']?></td>
                        <td><?php echo $product['quantity']?></td>
                        <td></td>
                    </tr>
                 </table>
           
           <form method="post" action="<?php echo $this->url->link('catalog/product/add_stock','token='.$token.'&product_id='.$product_id)?>">
               <div>Adicionar produto ao estoque:</div>
               Operação: <select name='type'>
                   <option value='1'>Entrada no estoque</option>
                   <option value='2'>Estorno no estoque</option>
               </select>
               Quantidade: <input type="text" name='qtd' />
               <input type="submit" value="Adicionar" />
           </form>   
           
      <table class="list">
          <thead>
          <tr>
              <td>Pedido</td>
              <td>Movimentação</td>
              <td>Data</td>
          </tr>
          </thead>
          <?php
          foreach($movimentacoes as $movimentacao){
          ?>
          <tr>
              <td><?php echo $movimentacao['order_id'] > 0 ?$movimentacao['order_id']:'-'?></td>
              <td><?php echo $movimentacao['input']>0?'+'.$movimentacao['input']:'-'.$movimentacao['output']; ?></td>
              <td><?php echo date('d/m/Y H:i',strtotime($movimentacao['date']))?></td>
          </tr>
          <?php }?>
      </table>
          
      </div>
    
  </div>

<?php echo $footer; ?>