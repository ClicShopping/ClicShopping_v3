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

  namespace ClicShopping\Apps\OrderTotal\Total\Module\ClicShoppingAdmin\Config\TO\Params;

  class sort_order extends \ClicShopping\Apps\OrderTotal\Total\Module\ClicShoppingAdmin\Config\ConfigParamAbstract {

//    public $sort_order = 30;
    public $default = '1500';
    public $app_configured = true;

    protected function init() {
        $this->title = $this->app->getDef('cfg_order_total_total_sort_order_title');
        $this->description = $this->app->getDef('cfg_order_total_total_sort_order_description');
    }
  }
