<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  namespace ClicShopping\Apps\Tools\ServiceAPP\Sites\ClicShoppingAdmin\Pages\Home\Actions;

  use ClicShopping\OM\Registry;

  class ServiceAPPAdmin extends \ClicShopping\OM\PagesActionsAbstract
  {
    public function execute()
    {
      $CLICSHOPPING_ServiceAPP = Registry::get('ServiceAPP');

      $this->page->setFile('service_admin.php');

      $CLICSHOPPING_ServiceAPP->loadDefinitions('Sites/ClicShoppingAdmin/main');
    }
  }