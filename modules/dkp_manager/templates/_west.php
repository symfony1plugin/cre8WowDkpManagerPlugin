<h3><a href="#">Rajdy</a></h3>
<div>

  <?php if(!$raids->isEmpty()): ?>
  <div id="raids_list">
      <?php $i=0;
      foreach($raids as $raid): ?>
    <input type="radio" id="raid<?php echo $i; ?>" name="raid" value="<?php echo $raid->getId(); ?>" <?php if($i==0): ?> checked="checked"<?php endif; ?> /><label for="raid<?php echo $i; ?>"><?php echo $raid; ?></label>
        <?php $i++;
      endforeach; ?>
  </div>
  <?php else: ?>
  Your first step is to create new raid.
  <?php endif; ?>

</div>

<h3><a href="#">DKP Members</a></h3>
<div>

  <?php if($raids->isEmpty()): ?>
  <style type="text/css">
    #dkp_members_simple { list-style-type: decimal; margin: 0; padding: 0; width: 80%; }
    #dkp_members_simple li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; height: 1.5em; line-height: 1.2em; cursor: move; }
    #dkp_members_simple .ui-state-highlight { height: 1.5em; line-height: 1.2em; }
    #dkp_members_simple li span { position: absolute; margin-left: -1.3em; }

  </style>
  <script type="text/javascript">
    jQuery(function($) {
      $("#dkp_members_simple").sortable({
        placeholder: 'ui-state-highlight',
        revert: true,
        cursor: 'move',
        axis: "y",
        update: function()
        {
          var serialized = $('#dkp_members_simple').sortable('serialize');
          alert(serialized);
        }
      });
      $("#dkp_members_simple").disableSelection();


    });
  </script>


  <ul id="dkp_members_simple">
    <?php foreach(DkpWowGuildMemberQuery::create()->filterByDkp($dkp)->orderByWowCharacter()->find() as $dkpWowGuildMember): ?>
    <li class="ui-state-default" id="dwgm_<?php echo $dkpWowGuildMember->getWowGuildMember()->getId(); ?>"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $dkpWowGuildMember->getWowGuildMember()->getWowCharacter(); ?></li>
    <?php endforeach; ?>

  </ul>
  <?php endif; ?>


</div>

<h3><a href="#">Activities</a></h3>
<div>

</div>

<h3><a href="#">Section 4</a></h3>
<div>

  <p>Cras dictum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames
					ac turpis egestas.</p>
  <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
					Aenean lacinia mauris vel est.</p>
  <p>Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
					Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
</div>
