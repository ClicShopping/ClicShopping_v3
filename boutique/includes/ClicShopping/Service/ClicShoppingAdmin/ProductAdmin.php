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
  namespace ClicShopping\Service\ClicShoppingAdmin;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Catalog\Products\Classes\ClicShoppingAdmin\ProductsAdmin as ProductAdminClass;

  class ProductAdmin implements \ClicShopping\OM\ServiceInterface {

    public static function start() {
      Registry::set('ProductsAdmin', new ProductAdminClass());

      return true;
    }

    public static function stop() {
      return true;
    }
  }
