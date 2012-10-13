<div id="loading" class="ui-helper-hidden ui-layout-ignore">Loading content...</div>
<div id="logged_out_dialog" class="ui-helper-hidden ui-layout-ignore"></div>
<div id="addRaidDialog" title="Raid Date" class="ui-helper-hidden ui-layout-ignore">
  <form id="addRaidFrm">
    <fieldset class="ui-helper-reset">
      <label for="new_raid_date">
        Date:
      </label>
      <input type="text" name="new_raid_date" id="new_raid_date" value="" class="ui-widget-content ui-corner-all" />
    </fieldset>
  </form>
</div>
<div id="multiUserSelectorDialog" title="Raid Members Selector" class="ui-helper-hidden ui-layout-ignore">
  <form id="multiUserSelectorDialogFrm">
    <fieldset class="ui-helper-reset">
      <label for="new_raid_date">
        Users:
      </label>
      <input type="text" name="multiUserSelector" id="multiUserSelector" value="" class="ui-widget-content ui-corner-all" />
    </fieldset>
  </form>
</div>

<div id="raid_tabs" class="ui-widget-header">
  <ul></ul>
</div>

<div id="raid_tabs_content" class="ui-layout-content ui-widget-content no-padding"></div>

<div id="raids_footer" class="ui-widget-content add-padding" style="height: 40px;">
  <div id="raid_footer_l" style="clear: left; float: left;">
    <button id="selectAllUsers" class="selectAllUsers">Select: [All]</button>
    <button id="selectUsersByName" class="selectUsersByName">[By name]</button>
    
  </div>
  <div id="raid_footer_r" style="clear: right; float: right;">
    <button id="addRaidBtn">Create Raid</button>
  </div>
</div>
