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

  namespace ClicShopping\Apps\Tools\Upgrade\Module\ClicShoppingAdmin\Dashboard;

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Tools\Upgrade\Classes\ClicShoppingAdmin\Github;

  use ClicShopping\Apps\Tools\Upgrade\Upgrade as UpgradeApp;

  class Upgrade extends \ClicShopping\OM\Modules\AdminDashboardAbstract
  {
    protected $lang;
    protected $app;
    public $group;

    protected function init()
    {
      if (!Registry::exists('Upgrade')) {
        Registry::set('Upgrade', new UpgradeApp());
      }

      $this->app = Registry::get('Upgrade');
      $this->lang = Registry::get('Language');

      $this->app->loadDefinitions('Module/ClicShoppingAdmin/Dashboard/upgrade');

      $this->title = $this->app->getDef('module_admin_dashboard_clicshopping_update_app_title');
      $this->description = $this->app->getDef('module_admin_dashboard_clicshopping_update_app_description');

      if (\defined('MODULE_ADMIN_DASHBOARD_CLICSHOPPING_UPDATE_APP_STATUS')) {
        $this->sort_order = (int)MODULE_ADMIN_DASHBOARD_CLICSHOPPING_UPDATE_APP_SORT_ORDER;
        $this->enabled = (MODULE_ADMIN_DASHBOARD_CLICSHOPPING_UPDATE_APP_STATUS == 'True');
      }
    }

    public function getOutput()
    {

      Registry::set('Github', new Github());
      $CLICSHOPPING_Github = Registry::get('Github');

      $current_version = CLICSHOPPING::getVersion();
      preg_match('/^(\d+\.)?(\d+\.)?(\d+)$/', $current_version, $version);

      $new_version = false;

      $core_info = $CLICSHOPPING_Github->getJsonCoreInformation();

      if (is_object($core_info) && $core_info->version) {
        if ($current_version < $core_info->version) {
          $new_version = true;
        }
      }

      if ($new_version === true) {
        $content_width = (int)MODULE_ADMIN_DASHBOARD_CLICSHOPPING_UPDATE_APP_CONTENT_WIDTH;

        $output = '<div class="col-md-' . $content_width . '">';
        $output .= '<div class="row alert alert-warning" role="alert">';
        $output .= '<span class="col-md-11"><strong>' . $this->app->getDef('module_admin_dashboard_clicshopping_update_app_text_warning_upgrade') . ' : ' . $current_version . '  => ' . $core_info->version . ' - ' . $core_info->date . '<br />'. $core_info->description . '  </strong></span>';
        $output .= '<span class="col-md-1 text-end"><a href="https://github.com/ClicShopping/ClicShopping_V3/archive/master.zip" target="_blank" rel="noreferrer">' . HTML::button($this->app->getDef('module_admin_dashboard_clicshopping_update_app_button', 'primary')) . '</a></span>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="separator"></div>';

        return $output;
      }
    }

    public function Install()
    {
      $this->app->db->save('configuration', [
          'configuration_title' => 'Do you want to enable this module ?',
          'configuration_key' => 'MODULE_ADMIN_DASHBOARD_CLICSHOPPING_UPDATE_APP_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to display the latest update ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $this->app->db->save('configuration', [
          'configuration_title' => 'Select the width to display',
          'configuration_key' => 'MODULE_ADMIN_DASHBOARD_CLICSHOPPING_UPDATE_APP_CONTENT_WIDTH',
          'configuration_value' => '12',
          'configuration_description' => 'Select a number between 1 to 12',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_content_module_width_pull_down',
          'date_added' => 'now()'
        ]
      );

      $this->app->db->save('configuration', [
          'configuration_title' => 'Sort Order',
          'configuration_key' => 'MODULE_ADMIN_DASHBOARD_CLICSHOPPING_UPDATE_APP_SORT_ORDER',
          'configuration_value' => '1',
          'configuration_description' => 'Sort order of display. Lowest is displayed first',
          'configuration_group_id' => '6',
          'sort_order' => '60',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function keys()
    {
      return ['MODULE_ADMIN_DASHBOARD_CLICSHOPPING_UPDATE_APP_STATUS',
        'MODULE_ADMIN_DASHBOARD_CLICSHOPPING_UPDATE_APP_CONTENT_WIDTH',
        'MODULE_ADMIN_DASHBOARD_CLICSHOPPING_UPDATE_APP_SORT_ORDER'
      ];
    }
  }
