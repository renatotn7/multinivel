<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle">
    <?php if ($description) { ?>
    <div style="margin-bottom: 15px;"><?php echo $description; ?></div>
    <?php } ?>
    <?php if ($categories) { ?>
    <table class="list">
      <?php for ($i = 0; $i < sizeof($categories); $i = $i + 4) { ?>
      <tr>
        <?php for ($j = $i; $j < ($i + 4); $j++) { ?>
        <td width="25%"><?php if (isset($categories[$j])) { ?>
          <a href="<?php echo $categories[$j]['href']; ?>"><img src="<?php echo $categories[$j]['thumb']; ?>" title="<?php echo $categories[$j]['name']; ?>" alt="<?php echo $categories[$j]['name']; ?>" style="margin-bottom: 3px;" /></a><br />
          <a href="<?php echo $categories[$j]['href']; ?>"><?php echo $categories[$j]['name']; ?></a>
          <?php } ?></td>
        <?php } ?>
      </tr>
      <?php } ?>
    </table>
    <?php } ?>
    <?php if ($informations) { ?>
    <div class="sort">
      <div class="div1">
        <select name="sort" onchange="location = this.value">
          <?php foreach ($sorts as $sorts) { ?>
          <?php if (($sort . '-' . $order) == $sorts['value']) { ?>
          <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
          <?php } else { ?>
          <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
          <?php } ?>
          <?php } ?>
        </select>
      </div>
      <div class="div2"><?php echo $text_sort; ?></div>
    </div>
    <div class="list">
      <?php $count = sizeof($informations); for ($i = 0; $i < $count; $i++) { ?>
          <h2><a href="<?php echo $informations[$i]['href']; ?>"><?php echo $informations[$i]['title']; ?></a></h2>
					<p><?php echo $informations[$i]['description']; ?></p>
      <?php } ?>
    </div>
    <div class="pagination"><?php echo $pagination; ?></div>
    <?php } ?>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 