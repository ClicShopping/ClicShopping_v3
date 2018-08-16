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

  namespace ClicShopping\Apps\Configuration\TaxClass\Sites\ClicShoppingAdmin\Pages\Home\Actions;

  use ClicShopping\OM\Registry;

  class Edit extends \ClicShopping\OM\PagesActionsAbstract {
    public function execute() {
      $CLICSHOPPING_TaxClass = Registry::get('TaxClass');

      $this->page->setFile('edit.php');
      $this->page->data['action'] = 'Update';

      $CLICSHOPPING_TaxClass->loadDefinitions('Sites/ClicShoppingAdmin/TaxClass');
    }
  }