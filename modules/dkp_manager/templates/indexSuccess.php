<?php echo use_dynamic_javascript(url_for('dkp_manager/dkpJs?' . http_build_query(array('pid' => time(), 'dkp' => $dkp->getId()))), 'last'); ?>

<div class="ui-layout-north no-padding">
  <?php include_partial('north', array('dkp' => $dkp, 'raids' => $raids)); ?>
</div>

<div class="ui-layout-south">
  <?php include_partial('south', array('dkp' => $dkp, 'raids' => $raids)); ?>
</div>

<div class="ui-layout-west">
  <div id="accordion-west">
    <?php include_partial('west', array('dkp' => $dkp, 'raids' => $raids)); ?>
  </div>
</div>

<div id="tabs-center" class="ui-layout-center no-padding">
  <?php include_partial('center', array('dkp' => $dkp, 'raids' => $raids)); ?>
</div>

<div id="tabs-east" class="ui-layout-east">
  <?php include_partial('east', array('dkp' => $dkp, 'raids' => $raids)); ?>
</div>