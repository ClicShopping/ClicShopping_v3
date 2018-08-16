<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *
 *
 */

  namespace ClicShopping\Apps\Orders\Orders\Sites\ClicShoppingAdmin\Pages\Home\Actions;

  use ClicShopping\OM\Registry;

  class PackingSlip extends \ClicShopping\OM\PagesActionsAbstract {
    public function execute() {
      $CLICSHOPPING_Orders = Registry::get('Orders');

      $this->page->setUseSiteTemplate(false); //don't display Header / Footer
      $this->page->setFile('packingslip.php');

      $CLICSHOPPING_Orders->loadDefinitions('Sites/ClicShoppingAdmin/packingslip');
    }
  }