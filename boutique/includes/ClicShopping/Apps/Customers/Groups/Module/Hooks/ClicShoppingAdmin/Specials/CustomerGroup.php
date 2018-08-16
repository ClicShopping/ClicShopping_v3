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

  namespace ClicShopping\Apps\Customers\Groups\Module\Hooks\ClicShoppingAdmin\Specials;

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Customers\Groups\Classes\ClicShoppingAdmin\GroupsB2BAdmin;

  use ClicShopping\Apps\Customers\Groups\Groups as GroupsApp;

  class CustomerGroup implements \ClicShopping\OM\Modules\HooksInterface {
    protected $app;

    public function __construct() {
      if (!Registry::exists('Groups')) {
        Registry::set('Groups', new GroupsApp());
      }

      $this->app = Registry::get('Groups');
    }

    public function display() {
      global $sInfo;

      if (!defined('CLICSHOPPING_APP_CUSTOMERS_GROUPS_GR_STATUS') || CLICSHOPPING_APP_CUSTOMERS_GROUPS_GR_STATUS == 'False') {
        return false;
      }

      $this->app->loadDefinitions('Module/Hooks/ClicShoppingAdmin/CustomerGroup/customer_group');

      $output = '';

      if (CLICSHOPPING_APP_CUSTOMERS_GROUPS_GR_STATUS == 'True' &&  !empty(CLICSHOPPING_APP_CUSTOMERS_GROUPS_GR_STATUS)) {
        if (MODE_B2B_B2C == 'true') {

          $content = '<div class="row">';
          $content .= '<div class="col-md-5">';
          $content .= '<div class="form-group row">';
          $content .= '<label for="'  . $this->app->getDef('text_customer_group') .'" class="col-5 col-form-label">' . $this->app->getDef('text_customer_group') . '</label>';
          $content .= '<div class="col-md-5">';
          $content .=  HTML::selectMenu('customers_group', GroupsB2BAdmin::getAllGroups(), (isset($sInfo->customers_group_id)? $sInfo->customers_group_id:''));
          $content .= '</div>';
          $content .= '</div>';
          $content .= '</div>';
          $content .= '</div>';

          $title = $this->app->getDef('text_group');

          $output = <<<EOD
<!-- ######################## -->
<!--  Start CustomersGroup      -->
<!-- ######################## -->
        <div class="separator"></div>
        <div class="mainTitle">
          <span class="col-md-10">{$title}</span>
        </div>
        <div class="adminformTitle">
           {$content}
        </div>
<!-- ######################## -->
<!--  Start CustomersGroup      -->
<!-- ######################## -->
EOD;
          return $output;
        }
      }
    }
  }
