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

  namespace ClicShopping\Apps\Configuration\TaxRates\Sites\ClicShoppingAdmin\Pages\Home\Actions\Configure;

  use ClicShopping\OM\Registry;

  use ClicShopping\OM\Cache;

  class Install extends \ClicShopping\OM\PagesActionsAbstract {

    public function execute() {

      $CLICSHOPPING_MessageStack = Registry::get('MessageStack');
      $CLICSHOPPING_TaxRates = Registry::get('TaxRates');

      $current_module = $this->page->data['current_module'];

      $CLICSHOPPING_TaxRates->loadDefinitions('Sites/ClicShoppingAdmin/install');

      $m = Registry::get('TaxRatesAdminConfig' . $current_module);
      $m->install();

      static::installDbMenuAdministration();
      static::installProductsTaxRatesDb();

      $CLICSHOPPING_MessageStack->add($CLICSHOPPING_TaxRates->getDef('alert_module_install_success'), 'success', 'TaxRates');

      $CLICSHOPPING_TaxRates->redirect('Configure&module=' . $current_module);
    }

    private static function installDbMenuAdministration() {
      $CLICSHOPPING_Db = Registry::get('Db');
      $CLICSHOPPING_TaxRates = Registry::get('TaxRates');
      $CLICSHOPPING_Language = Registry::get('Language');

      $Qcheck = $CLICSHOPPING_Db->get('administrator_menu', 'app_code', ['app_code' => 'app_configuration_tax_rates']);

      if ($Qcheck->fetch() === false) {

        $sql_data_array = ['sort_order' => 4,
                           'link' => 'index.php?A&Configuration\TaxRates&TaxRates',
                           'image' => 'tax_rates.gif',
                           'b2b_menu' => 0,
                           'access' => 0,
                           'app_code' => 'app_configuration_tax_rates'
                          ];

        $insert_sql_data = ['parent_id' => 19];

        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

        $CLICSHOPPING_Db->save('administrator_menu', $sql_data_array);

        $id = $CLICSHOPPING_Db->lastInsertId();

        $languages = $CLICSHOPPING_Language->getLanguages();

        for ($i=0, $n=count($languages); $i<$n; $i++) {

          $language_id = $languages[$i]['id'];

          $sql_data_array = ['label' => $CLICSHOPPING_TaxRates->getDef('title_menu')];

          $insert_sql_data = ['id' => (int)$id,
                              'language_id' => (int)$language_id
                             ];

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          $CLICSHOPPING_Db->save('administrator_menu_description', $sql_data_array );

        }

        Cache::clear('menu-administrator');
      }
    }


    private function installProductsTaxRatesDb() {
      $CLICSHOPPING_Db = Registry::get('Db');

      $Qcheck = $CLICSHOPPING_Db->query('show tables like ":table_tax_rates"');

      if ($Qcheck->fetch() === false) {
$sql = <<<EOD
CREATE TABLE :table_tax_rates (
  tax_rates_id int not_null auto_increment,
  tax_zone_id int not_null,
  tax_class_id int not_null,
  tax_priority int(5) default(1)
  tax_rate decimal(7,4) not_null,
  tax_description varchar(255) not_null,
  last_modified datetime,
  date_added datetime not_null,
  code_tax_odoo varchar(15) null
  PRIMARY KEY tax_rates_id
) CHARSET=utf8 COLLATE=utf8_unicode_ci;
EOD;
        $CLICSHOPPING_Db->exec($sql);
      }
    }
  }
