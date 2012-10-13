var pageLayout;
var raidTabs;
var activeRaidId = 0;


function resizeEverything () {
  // All layout 'panes'
  pageLayout.resizeAll();
  // pageLayout - East Contents
  pageLayout.sizeContent("east");

};

jQuery(document).ready(function($) {

  // OUTER/PAGE LAYOUT
  pageLayout = $("body").layout({
    west__size:				.20
  ,	east__size:				.30
  ,	east__initClosed:		  true
  ,	west__initClosed:		  true
  , applyDefaultStyles: true
  , scrollToBookmarkOnLoad: false

  });

  $.datepicker.setDefaults($.datepicker.regional['pl']);
  $.ajaxSetup({
    type: 'POST',
    data: { dkp: <?php echo $sf_params->get('dkp'); ?> },
    error: handleAjaxError
  });

  /*shows the loading div every time we have an Ajax call*/
  $("#loading").bind("ajaxSend", function(){
    $(this).show();
  }).bind("ajaxComplete", function(){
    $(this).hide();
  });

  function handleAjaxError(XMLHttpRequest, textStatus, errorThrown)
  {
    if(parseInt(XMLHttpRequest.status) == 401) {
      $('#logged_out_dialog').dialog('open');
    }
  }

  $('#logged_out_dialog').dialog({
    title: 'Please log-in again',
    autoOpen: false,
    modal: true,
    buttons: {
      'OK': function(){
        $(this).dialog('close');
      }
    },
    close: function(){
      window.location = "<?php echo url_for('dkp_manager', $dkp); ?>";
    }
  });

  raidTabs = $('#raid_tabs').tabs({
    // tabTemplate: '<li><a href="#{href}">#{label}</a> <span class="ui-icon ui-icon-close">Remove Tab</span></li>',
    select: function(event, ui){
      setSelectedRaidBtnByRaidId($(ui.panel).attr('raid_id'));
    },
    remove: function(event, ui) {
      if($('#raid_tabs').children().length == 1) {
        setSelectedRaidBtnByRaidId(0);
      }
      
    }
  });

  $("#raids_list").buttonset();
  $('#selectUsersByName').button().click(function() {
    multiUserSelectorDialog.dialog('open');
  });

  var data = {items: [
    {value: "21", name: "Mick Jagger"},
    {value: "43", name: "Johnny Storm"},
    {value: "46", name: "Richard Hatch"},
    {value: "54", name: "Kelly Slater"},
    {value: "55", name: "Rudy Hamilton"},
    {value: "79", name: "Michael Jordan"}
  ]};

  $('#multiUserSelector').autoSuggest('<?php echo url_for('@dkp_manager_suggestRaidUsers'); ?>', {
    minChars: 2,
    matchCase: true,
    selectedItemProp: "name",
    searchObjProps: "name",
    resultsHighlight: false,
    extraParams: '&rid=34&dkp=<?php echo $sf_params->get('dkp'); ?>',
    formatList: function(data, elem){
      var new_elem = elem.html(data.name + " (" + data.p + ")");
      return new_elem;
    }
  });

  $("#raid_footer_l").buttonset();

  $("#raids_list label").live('click', function() {
    loadRaid(parseInt($('#'+$(this).attr('for')).val()));
  });

  if($("#raids_list > input").length) {
    loadRaid($("#raids_list > input").val());
  }

  function loadRaid(raidID)
  {
    $.getJSON('<?php echo url_for('dkp_manager/loadRaid'); ?>',
      {
        'rid': raidID
      },
      function(json) {
        displayRaid(json.dkpRaid);
      }
    );
  }

  function displayRaid(jsonDkpRaid)
  {
    if(! $('#raid_tab_content_'+jsonDkpRaid.RaidDate).length)
    {
      $('#raid_tabs_content').append('<div id="raid_tab_content_' + jsonDkpRaid.RaidDate + '" raid_id="' + jsonDkpRaid.Id + '" raid_date="' + jsonDkpRaid.RaidDate + '" class="raid_tab_content no-padding ui-helper-hidden" >Loading...</div>');
      raidTabs.tabs('add', '#raid_tab_content_' + jsonDkpRaid.RaidDate, jsonDkpRaid.RaidDate);
      $('#raid_tab_content_' + jsonDkpRaid.RaidDate).appendTo('#raid_tabs_content');
      
    }
    setSelectedRaidTabByRaidId(jsonDkpRaid.Id);

    var rtc = $('#raid_tab_content_'+jsonDkpRaid.RaidDate); // Raid Tab Content
    rtc.empty();

    var table = $('<table id="raid_tab_table_' + jsonDkpRaid.Id + '" width="100%" cellspacing="1" cellpadding="0" border="0" class="raid_tab_table knight_table tablesorter"></table>').hide();
    var thead = $('<thead></thead>');
    var theadTr = $('<tr id="raid_tab_table_header_tr_' + jsonDkpRaid.Id + '" class="table_header blue"></tr>');
    $.each(['DKP', ' ', 'Name', 'Activity', 'Loot', 'SUM', 'Actions'], function(index, header) {
      $('<th></th>').text(header).appendTo(theadTr);
    });
    thead.append(theadTr);
    table.append(thead);

    var tbody = $('<tbody></tbody>');
    $.each(jsonDkpRaid.DkpRaidWowGuildMembers, function(index, obj) {
      var tr = $('<tr></tr>');
      var td_selection = $('<td><span class="ui-helper-hidden">0</span><input type="checkbox" class="wgmss" /></td>');
      var td_dkpPosition = $('<td></td>').text(index +1);
      var td_displayName = $('<td></td>').text(obj.DisplayName);

      var td_activity = $('<td></td>');
      var td_loot = $('<td></td>');
      var td_sum = $('<td></td>');
      var td_actions = $('<td></td>');

      var buttons = [
        { id: 'loot', icon: 'ui-icon-suitcase', title: 'Assign loot' },
        { id: 'activity', icon: 'ui-icon-transferthick-e-w', title: 'Assign activity points' }
      ];

      $.each(buttons, function(index, obj) {
        var btn = $('<button title="' + obj.title + '" class="rtc_action"></button>').addClass(obj.id).button({
          text: false,
          icons: {
            primary: obj.icon
          }
        });
        td_actions.append(btn);
      });

      tr
        .append(td_dkpPosition)
        .append(td_selection)
        .append(td_displayName)
        .append(td_activity)
        .append(td_loot)
        .append(td_sum)
        .append(td_actions)

      ;

      tbody.append(tr);
    });
    table.append(tbody);

    rtc.append(table);

    table.tablesorter({
      sortList: [[0, 0]],
      headers: {
        1: { sorter: false },
        3: { sorter: false },
        5: { sorter: false }
      }
    });

    table.show();


  }

  function setSelectedRaidTabByRaidId(raidId)
  {
    raidId = parseInt(raidId);
    // $( "#raid_tabs" ).tabs( "option", "selected", -1);
    $('#raid_tabs .raid_tab_content').each(function(index) {
      if(parseInt($(this).attr('raid_id')) == raidId) {
        $( "#raid_tabs" ).tabs( "option", "selected", index);
      }

    });
    showRaid(raidId)
  }

  function setSelectedRaidBtnByRaidId(raidId)
  {
    raidId = parseInt(raidId);
    $('#raids_list input').each(function(index) {
      $(this).next('label').removeClass('ui-state-active');
      if(parseInt($(this).val()) == raidId) {
        $(this).attr('checked', 'checked');
        $(this).next('label').addClass('ui-state-active');
      }
    });
    showRaid(raidId)
  }

  function showRaid(raidId)
  {
    $('.raid_tab_content').each(function(index, obj) {
      if($(obj).attr('raid_id') == raidId) {
        $(obj).removeClass('ui-helper-hidden');
      } else {
        $(obj).addClass('ui-helper-hidden');
      }
    });
  }

  $('#raid_tabs').find(".ui-tabs-nav").sortable({
    axis: 'x'
  });

  /*
  $('#raid_tabs span.ui-icon-close').live('click', function(){
    var index = $('li', raidTabs).index($(this).parent());
    // $('#raid_tab_content_' + $(ui.panel).attr('raid_date')).appendTo('#raid_tabs');
    raidTabs.tabs('remove', index);
    
  });
  */

  function addRaid(){
    $.getJSON('<?php echo url_for('dkp_manager/addNewRaid'); ?>',
      {
        'date': $('#new_raid_date').val(),
        'users_order': $('#dkp_members_simple').sortable('serialize')
      },
      function(json) {
        displayRaid(json.dkpRaid);
      }
    );
  }

  // modal dialog init: custom buttons and a "close" callback reseting the form inside
  var addRaidDialog = $('#addRaidDialog').dialog({
    autoOpen: false,
    modal: true,
    buttons: {
      'Add': function(){
        if ($('#new_raid_date').val().trim() != '') {
          $(this).dialog('close');
          $('#addRaidBtn').removeClass('ui-state-focus').removeClass('ui-state-hover');
          addRaid();
        }
        else {
          $('#new_raid_date').addClass('ui-state-error');
        }
      },
      'Cancel': function(){
        $(this).dialog('close');
      }
    },
    open: function(){
      $('#new_raid_date').val('');
      $('#new_raid_date').focus();
    },
    close: function(){

    }
  });


  var multiUserSelectorDialog = $('#multiUserSelectorDialog').dialog({
    autoOpen: false,
    modal: true,
    buttons: {
      'OK': function(){

      },
      'Cancel': function(){
        $(this).dialog('close');
      }
    },
    open: function(){
      
    },
    close: function(){

    }
  });

  // addTab button: just opens the dialog
  $('#addRaidBtn').button().click(function(){
    addRaidDialog.dialog('open');
  });

  $("#new_raid_date").datepicker({
    dateFormat: $.datepicker.ATOM
  }).change(function(event){
    if ($('#new_raid_date').val().trim() == '') {
      $('#new_raid_date').addClass('ui-state-error');
    }
    else {
      $('#new_raid_date').removeClass('ui-state-error');
    }
  });

  
  // THEME SWITCHER
  $('#east-toolbar').themeswitcher({
    top: '12px',
    right: '5px'
  });
  

  setTimeout( pageLayout.resizeAll, 2000 ); /* allow time for browser to re-render with new theme */
});
