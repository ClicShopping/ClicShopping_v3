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

use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTML;
?>
<div class="col-md-<?php echo $content_width; ?>">
  <div class="separator"></div>

  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-11 moduleAccountCustomersMyAccountTitle"><h3><?php echo CLICSHOPPING::getDef('module_account_customers_email_mailing_title'); ?></h3></div>
        <div class="col-md-1 text-md-right">
          <i class="fas fa-envelope fa-4x moduleAccountCustomersNotificationsIcon"></i>
        </div>
      </div>
    </div>

    <div class="card-block">
      <div class="card-text">
        <div class="moduleAccountCustomersNotificationsList">
          <div class="col-md-12">
            <div><i class="fas fa-arrow-right fa-1x moduleAccountCustomersNotificationsIconArrow"></i><?php echo HTML::link(CLICSHOPPING::link('index.php', 'Account&Newsletters'), CLICSHOPPING::getDef('module_account_customers_email_mailing_newsletters')); ?></div>
            <div><i class="fas fa-arrow-right fa-1x moduleAccountCustomersNotificationsIconArrow"></i><?php echo HTML::link(CLICSHOPPING::link('index.php', 'Account&Notifications'), CLICSHOPPING::getDef('module_account_customers_email_mailing_products')); ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>