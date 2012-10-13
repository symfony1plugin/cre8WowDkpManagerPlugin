<?php

/**
 * Base actions for the cre8WowDkpManagerPlugin dkp_manager module.
 * 
 * @package     cre8WowDkpManagerPlugin
 * @subpackage  dkp_manager
 * @author      Bogumil Wrona <b.wrona@cre8newmedia.com>
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class Basedkp_managerActions extends sfActions
{

  public function preExecute()
  {
    if($this->getActionName() != 'index') {
      $this->getResponse()->setHttpHeader('Content-type','application/json');
      $this->dkp = DkpQuery::create()->filterById($this->getRequestParameter('dkp', 0))->findOne();
      $this->forward404Unless($this->dkp);
    }
  }

  /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request) {
    $this->dkp = $this->getRoute()->getObject();
    $this->raids = DkpRaidQuery::create()
            ->filterByDkp($this->dkp)
            ->orderByRaidDate(Criteria::DESC)
            ->find();
  }

  public function executeDkpJs(sfWebRequest $request) {
    
  }

  public function executeAddNewRaid(sfWebRequest $request) {
    $retArray = array();

    $date = new sfDate($request->getParameter('date'));
    if($usersOrder = $request->getParameter('users_order')) {
      parse_str($usersOrder, $usersOrderHolder);
      $usersOrder = $usersOrderHolder['dwgm'];
      $retArray = array_merge($retArray, array('usersOrder' => $usersOrder));
    }
    $numOfRaids = DkpRaidQuery::create()->filterByDkp($this->dkp)->count();

    DkpRaidQuery::create()->filterByDkp($this->dkp)->update(array('IsArchived' => true));

    $dkpRaid = new DkpRaid();
    $dkpRaid->setRaidDate($date->format('Y-m-d'));
    $dkpRaid->setDkp($this->dkp);
    $dkpRaid->save();

    if(!$numOfRaids) {
      foreach($usersOrder as $wowGuildMemberId) {
        $dkpWowGuildMember = DkpWowGuildMemberQuery::create()->filterByDkp($this->dkp)->filterByWowGuildMemberId($wowGuildMemberId)->withWowCharacter()->findOne();
        if($dkpWowGuildMember) {
          $dkpRaidWowGuildMember = new DkpRaidWowGuildMember();
          $dkpRaidWowGuildMember->setDkpRaid($dkpRaid);
          $dkpRaidWowGuildMember->setWowGuildMember($dkpWowGuildMember->getWowGuildMember());
          $dkpRaidWowGuildMember->setDisplayName($dkpWowGuildMember->getWowGuildMember()->getWowCharacter()->getName());
          $dkpRaidWowGuildMember->save();
        }
      }
    } else {
      foreach(DkpRaidWowGuildMemberQuery::create()->filterByDkpRaid(DkpRaidQuery::create()->filterByDkp($this->dkp)->filterById($dkpRaid->getId(), Criteria::NOT_EQUAL)->orderByRank(Criteria::DESC)->findOne())->orderByRank()->find() as $dkpRaidWowGuildMember) {
        $newDkpRaidWowGuildMember = new DkpRaidWowGuildMember();
        $newDkpRaidWowGuildMember->setDkpRaid($dkpRaid);
        $newDkpRaidWowGuildMember->setWowGuildMemberId($dkpRaidWowGuildMember->getWowGuildMemberId());
        $newDkpRaidWowGuildMember->setDisplayName($dkpRaidWowGuildMember->getDisplayName());
        $newDkpRaidWowGuildMember->save();
      }
    }

    
    $dkpRaid = DkpRaidQuery::create()->filterById($dkpRaid->getId())->withAll()->setFormatter(ModelCriteria::FORMAT_ARRAY)->find();
    $retArray = array_merge($retArray, array('dkpRaid' => $dkpRaid->getFirst()), array('numOfRaids' => ++$numOfRaids));
    return $this->renderText(json_encode($retArray));
  }

  public function executeLoadRaid(sfWebRequest $request)
  {
    return $this->renderText(json_encode(array('dkpRaid' => DkpRaidQuery::create()->filterById($request->getParameter('rid'))->withAll()->setFormatter(ModelCriteria::FORMAT_ARRAY)->find()->getFirst())));
  }

  public function executeSuggestRaidUsers(sfWebRequest $request)
  {
    $retVal = array();
    foreach(DkpRaidWowGuildMemberQuery::create()->filterByDkpRaidId($request->getParameter('rid'))->displayNameLike($request->getParameter('q'))->find() as $dkpRWGM) {
      $retVal[] = array(
          'value' => $dkpRWGM->getId(),
          'name' => $dkpRWGM->getDisplayName(),
          'p' => $dkpRWGM->getRank()
      );
    }
    return $this->renderText(json_encode($retVal));
  }

}
