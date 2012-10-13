<?php

/**
 * cre8WowDkpManagerPlugin configuration.
 * 
 * @package     cre8WowDkpManagerPlugin
 * @subpackage  config
 * @author      Bogumil Wrona <b.wrona@cre8newmedia.com>
 * @version     SVN: $Id: PluginConfiguration.class.php 17207 2009-04-10 15:36:26Z Kris.Wallsmith $
 */
class cre8WowDkpManagerPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '1.0.0-DEV';

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if(in_array('dkp_manager', sfConfig::get('sf_enabled_modules', array()))) {
      $this->dispatcher->connect('routing.load_configuration', array('cre8WowDkpManagerRouting', 'addRoutes'));
    }
  }
}
