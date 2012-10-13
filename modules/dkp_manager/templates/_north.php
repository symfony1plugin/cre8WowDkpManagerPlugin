<div id="raids_list_holder" class="ui-widget-content add-padding">

  <?php if(!$raids->isEmpty()): ?>
  <div id="raids_list">
      <?php $i=0; foreach($raids as $raid): ?>
    <input type="radio" name="raidbtn" id="raidbtn_<?php echo $i; ?>" value="<?php echo $raid->getId(); ?>" <?php if($i==0): ?> checked="checked"<?php endif; ?> /><label for="raidbtn_<?php echo $i; ?>"><?php echo $raid; ?></label>
      <?php $i++; endforeach; ?>
  </div>
  <?php else: ?>
  <ol>
    <li>First step is to put your "DKP Members" in order.</li>
    <li>Next step is to "Create Raid".</li>
  </ol>

  <?php endif; ?>

</div>