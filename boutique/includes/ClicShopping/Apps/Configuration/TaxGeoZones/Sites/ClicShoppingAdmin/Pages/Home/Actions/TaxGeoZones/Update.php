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

  namespace ClicShopping\Apps\Configuration\TaxGeoZones\Sites\ClicShoppingAdmin\Pages\Home\Actions\TaxGeoZones;

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;

  class Update extends \ClicShopping\OM\PagesActionsAbstract {
    protected $app;

    public function __construct() {
      $this->app = Registry::get('TaxGeoZones');
    }

    public function execute() {

      $zID = HTML::sanitize($_GET['zID']);
      $geo_zone_name = HTML::sanitize($_POST['geo_zone_name']);
      $geo_zone_description = HTML::sanitize($_POST['geo_zone_description']);

      $this->app->db->save('geo_zones', [
                                        'geo_zone_name' => $geo_zone_name,
                                        'geo_zone_description' => $geo_zone_description,
                                        'last_modified' => 'now()'
                                        ], [
                                          'geo_zone_id' => (int)$zID
                                        ]
                          );

      $this->app->redirect('TaxGeoZones&zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID']);
    }
  }