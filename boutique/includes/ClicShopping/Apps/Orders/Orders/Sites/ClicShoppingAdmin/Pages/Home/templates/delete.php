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

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;


  $CLICSHOPPING_Orders = Registry::get('Orders');
  $CLICSHOPPING_Template = Registry::get('TemplateAdmin');
  $CLICSHOPPING_MessageStack = Registry::get('MessageStack');

  if ($CLICSHOPPING_MessageStack->exists('Orders')) {
    echo $CLICSHOPPING_MessageStack->get('Orders');
  }
?>

<div class="contentBody">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-block headerCard">
        <div class="row">
          <span class="col-md-1"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . '/categories/orders.gif', $CLICSHOPPING_Orders->getDef('heading_title'), '40', '40'); ?></span>
          <span class="col-md-2 pageHeading"><?php echo '&nbsp;' . $CLICSHOPPING_Orders->getDef('heading_title') . ' #' .  (int)$_GET['oID']; ?></span>
          <span class="col-md-9 text-md-right"><?php echo HTML::button($CLICSHOPPING_Orders->getDef('button_back'), null,  $CLICSHOPPING_Orders->link('Orders'), 'primary'); ?></span>
        </div>
      </div>
    </div>
  </div>
  <div class="separator"></div>


  <div class="col-md-12 mainTitle"><strong><?php echo $CLICSHOPPING_Orders->getDef('text_info_heading_delete_order'); ?></strong></div>
  <?php  echo HTML::form('orders', $CLICSHOPPING_Orders->link('Orders&DeleteConfirm&oID=' . (int)$_GET['oID'])); ?>
  <div class="adminformTitle">
    <div class="row">
      <div class="separator"></div>
      <div class="col-md-12"><?php echo $CLICSHOPPING_Orders->getDef('text_info_delete_info'); ?><br/><br/><?php echo $oInfo->customers_name; ?></div>
      <div class="separator"></div>
      <div class="col-md-12"><br/><?php echo HTML::checkboxField('restock') . ' ' . $CLICSHOPPING_Orders->getDef('text_info_restock_product_quantity'); ?><br/><br/></div>
      <div class="separator"></div>
      <div class="col-md-12 text-md-center">
        <span><br /><?php echo HTML::button($CLICSHOPPING_Orders->getDef('button_delete'), null, null, 'danger', null, 'sm') . '&nbsp;</span><span>' .  HTML::button($CLICSHOPPING_Orders->getDef('button_cancel'), null, $CLICSHOPPING_Orders->link('Edit&oID=' . $oInfo->orders_id), 'warning', null, 'sm'); ?></span>
      </div>
    </div>
  </div>
  </form>
</div>