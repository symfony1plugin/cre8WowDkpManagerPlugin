<?php

class cre8WowDkpManagerRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function addRoutes(sfEvent $event)
  {
    $r = $event->getSubject();

    $r->prependRoute('dkp_manager_suggestRaidUsers', new sfRoute('/suggestRaidUsers', array(
      'module'              => 'dkp_manager',
      'action'              => 'suggestRaidUsers'
    )));

  }
}