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

  namespace ClicShopping\Apps\Customers\Groups\Module\Hooks\ClicShoppingAdmin\Customers;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;

  use ClicShopping\Apps\Customers\Groups\Groups as GroupsApp;

  class Update implements \ClicShopping\OM\Modules\HooksInterface {
    protected $app;

    public function __construct()   {
      if (!Registry::exists('Groups')) {
        Registry::set('Groups', new GroupsApp());
      }

      $this->app = Registry::get('Groups');
    }

    public function execute() {
      if (isset($_GET['Update'])) {

        $CLICSHOPPING_Customers = Registry::get('Customers');

        $customers_group_id = HTML::sanitize($_POST['customers_group_id']);
        $customers_id = HTML::sanitize($_POST['customers_id']);
        $customers_options_order_taxe = HTML::sanitize($_POST['customers_options_order_taxe']);

        $QmultipleGroups = $CLICSHOPPING_Customers->db->prepare('select distinct customers_group_id
                                                                 from :table_products_groups
                                                               ');

        $QmultipleGroups->execute();

        while ($QmultipleGroups->fetch() ) {
          $QmultipleCustomers = $CLICSHOPPING_Customers->db->prepare('select distinct customers_group_id
                                                                      from :table_customers_groups
                                                                      where customers_group_id = :customers_group_id
                                                                    ');
          $QmultipleCustomers->bindInt(':customers_group_id', $QmultipleGroups->valueInt('customers_group_id'));
          $QmultipleCustomers->execute();

          if (!($QmultipleCustomers->fetch())) {
            $Qdelete = $CLICSHOPPING_Customers->db->prepare('delete
                                                              from :table_products_groups
                                                              where customers_group_id = :customers_group_id
                                                             ');
            $Qdelete->bindInt(':customers_group_id', $QmultipleGroups->valueInt('customers_group_id'));

            $Qdelete->execute();
          }
        } // end while

        $sql_data_array = ['customers_group_id' => $customers_group_id];

        $this->app->db->save('customers', $sql_data_array, ['customers_id' => (int)$customers_id ]);
      }
    }
  }