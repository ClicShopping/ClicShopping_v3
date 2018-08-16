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
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\DateTime;

  use ClicShopping\Sites\ClicShoppingAdmin\Tax;

  use ClicShopping\Apps\Orders\Orders\Classes\ClicShoppingAdmin\OrderAdmin;

  $CLICSHOPPING_Orders = Registry::get('Orders');
  $CLICSHOPPING_Template = Registry::get('TemplateAdmin');
  $CLICSHOPPING_Currencies = Registry::get('Currencies');
  $CLICSHOPPING_Address = Registry::get('Address');
  $CLICSHOPPING_MessageStack = Registry::get('MessageStack');

  if ($CLICSHOPPING_MessageStack->exists('Orders')) {
    echo $CLICSHOPPING_MessageStack->get('Orders');
  }

  $orders_statuses = [];
  $orders_status_array = [];

  $QordersStatus = $CLICSHOPPING_Db->prepare('select orders_status_id,
                                                    orders_status_name
                                              from :table_orders_status
                                              where language_id = :language_id
                                              ');
  $QordersStatus->bindInt(':language_id', $CLICSHOPPING_Language->getId());
  $QordersStatus->execute();

  while ($QordersStatus->fetch() !== false ) {
    $orders_statuses[] = ['id' => $QordersStatus->valueInt('orders_status_id'),
                          'text' => $QordersStatus->value('orders_status_name')
                         ];
    $orders_status_array[$QordersStatus->valueInt('orders_status_id')] = $QordersStatus->value('orders_status_name');
  }

  if (isset($_GET['oID']) && is_numeric($_GET['oID']) && ($_GET['oID'] > 0)) {

    $oID = HTML::sanitize($_GET['oID']);

    $Qorders = $CLICSHOPPING_Db->get('orders', 'orders_id', ['orders_id' => (int)$oID]);

    if ($Qorders->fetch()) {
      Registry::set('Order', new OrderAdmin($Qorders->valueInt('orders_id')));
      $order = Registry::get('Order');
    } else {
      $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('error_order_does_not_exist', ['order_id' => $oID]), 'error');
    }
  }

// orders_invoice status Dropdown
  $orders_invoice_statuses = [];
  $orders_status_invoice_array = [];

  $QordersStatusInvoice = $CLICSHOPPING_Db->prepare('select orders_status_invoice_id,
                                                            orders_status_invoice_name
                                                     from :table_orders_status_invoice
                                                     where language_id = :language_id
                                                    ');
  $QordersStatusInvoice->bindInt(':language_id',  (int)$CLICSHOPPING_Language->getId());
  $QordersStatusInvoice->execute();

  while ($QordersStatusInvoice->fetch() !== false) {
    $orders_invoice_statuses[] = ['id'   => $QordersStatusInvoice->valueInt('orders_status_invoice_id'),
                                  'text' => $QordersStatusInvoice->value('orders_status_invoice_name')
                                  ];

    $orders_status_invoice_array[$QordersStatusInvoice->valueInt('orders_status_invoice_id')] = $QordersStatusInvoice->value('orders_status_invoice_name');
  }


  // orders_support status Dropdown
  $orders_support_statuses = [];
  $orders_status_support_array = [];

  $QordersStatusSupport = $CLICSHOPPING_Db->prepare('select orders_status_support_id,
                                                           orders_status_support_name
                                                     from :table_orders_status_support
                                                     where language_id = :language_id
                                                    ');
  $QordersStatusSupport->bindInt(':language_id', $CLICSHOPPING_Language->getId());
  $QordersStatusSupport->execute();

  while ($QordersStatusSupport->fetch() !== false ) {
    $orders_support_statuses[] = ['id'   => $QordersStatusSupport->valueInt('orders_status_support_id'),
                                  'text' => $QordersStatusSupport->value('orders_status_support_name')
                                 ];
    $orders_status_support_array[$QordersStatusSupport->valueInt('orders_status_support_id')] = $QordersStatusSupport->value('orders_status_support_name');
  }


  $Qcustomers = $CLICSHOPPING_Orders->db->prepare('select c.customers_id,
                                                          o.customers_id,
                                                          o.orders_id
                                                  from :table_customers c,
                                                       :table_orders o
                                                  where c.customers_id = o.customers_id
                                                  and o.orders_id = :orders_id
                                                  limit 1
                                                  ');
  $Qcustomers->bindInt(':orders_id', $_GET['oID']);
  $Qcustomers->execute();
?>

<div class="contentBody">
  <div class="separator"></div>
  <div class="row">
    <div class="col-md-12">
      <div class="card card-block headerCard">
        <div class="row">
          <span class="col-md-1"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . '/categories/orders.gif', $CLICSHOPPING_Orders->getDef('heading_title'), '40', '40'); ?></span>
          <span class="col-md-2 pageHeading"><?php echo '&nbsp;' . $CLICSHOPPING_Orders->getDef('heading_title') . ' #' .  (int)$_GET['oID']; ?></span>
          <span class="col-md-9 text-md-right">
<?php
  if ($Qcustomers->valueInt('customers_id') != 0) {
    echo HTML::button($CLICSHOPPING_Orders->getDef('button_history'), null, $CLICSHOPPING_Orders->link('Orders&cID=' . $Qcustomers->valueInt('customers_id')), 'info', null);
    echo '&nbsp;';
  }

  echo HTML::button($CLICSHOPPING_Orders->getDef('button_invoice'), null, $CLICSHOPPING_Orders->link('Invoice&oID=' . (int)$_GET['oID']), 'success', ['newwindow' => true]);
  echo '&nbsp;';
  echo HTML::button($CLICSHOPPING_Orders->getDef('button_packingslip'), null, $CLICSHOPPING_Orders->link('PackingSlip&oID=' . (int)$_GET['oID']), 'info', ['newwindow' => true]);
  echo '&nbsp;';
  echo HTML::button($CLICSHOPPING_Orders->getDef('button_back'), null,  $CLICSHOPPING_Orders->link('Orders'), 'primary');
?>
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="separator"></div>


  <!-- pb avec autres script

  <script>
    $(function() {
      $('#orderTabs').tabs();
    });
  </script>

  -->


<!-- //###########################################//-->
<!--          Customer information tab          //-->
<!-- //###########################################//-->
  <div id="orderTabs" style="overflow: auto;">
    <ul class="nav nav-tabs flex-column flex-sm-row" role="tablist" id="myTab">
      <li class="nav-item"><?php echo '<a href="#tab1" role="tab" data-toggle="tab" class="nav-link active">' . $CLICSHOPPING_Orders->getDef('tab_general'); ?></a></li>
      <li class="nav-item"><?php echo '<a href="#tab2" role="tab" data-toggle="tab" class="nav-link">' . $CLICSHOPPING_Orders->getDef('tab_orders_details'); ?></a></li>
      <li class="nav-item"><?php echo '<a href="#tab3" role="tab" data-toggle="tab" class="nav-link">' . $CLICSHOPPING_Orders->getDef('tab_statut'); ?></a></li>
    </ul>
    <div class="tabsClicShopping">
      <div class="tab-content">

        <div class="tab-pane active" id="tab1">
          <div class="mainTitle"><?php echo $CLICSHOPPING_Orders->getDef('title_orders_adresse'); ?></div>
          <div class="adminformTitle">
            <div class="card-deck">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo $CLICSHOPPING_Orders->getDef('entry_customer'); ?></h4>
                  <p class="card-text"><strong><?php echo $CLICSHOPPING_Address->addressFormat($order->customer['format_id'], $order->customer, 1, '', '<br />'); ?></strong></p>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo $CLICSHOPPING_Orders->getDef('entry_shipping_address'); ?></h4>
                  <p class="card-text"><strong><?php echo $CLICSHOPPING_Address->addressFormat($order->delivery['format_id'], $order->delivery, 1, '', '<br />'); ?></strong></p>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo $CLICSHOPPING_Orders->getDef('entry_billing_address'); ?></h4>
                  <p class="card-text"><strong><?php echo $CLICSHOPPING_Address->addressFormat($order->billing['format_id'], $order->billing, 1, '', '<br />'); ?></strong>.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="separator"></div>
          <div class="mainTitle"><?php echo $CLICSHOPPING_Orders->getDef('title_orders_customers'); ?></div>
          <div class="adminformTitle">
            <div class="row" id="tab1ContentRow1">
<?php
  if (MODE_B2B_B2C == 'false') {
?>
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_order_siret'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->customer['siret']; ?></strong></span>
              </div>
              <div class="separator"></div>

              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_order_code_ape'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->customer['ape']; ?></strong></span>
              </div>
              <div class="separator"></div>
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_tva_intracom'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->customer['tva_intracom']; ?></strong></span>
              </div>
<?php
  }
?>
              <script src="<?php echo CLICSHOPPING::link('Shop/ext/javascript/clicshopping/ClicShoppingAdmin/modal_popup.js'); ?>"></script>
              <div class="col-md-8">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_Orders->getDef('text_condition_general_of_sales'); ?>" class="col-5 col-form-label"><?php echo $CLICSHOPPING_Orders->getDef('text_condition_general_of_sales'); ?></label>
                  <div class="col-md-5">
                    <a href="<?php echo $CLICSHOPPING_Orders->link('PageManagerOrderHistoryContract&order_id=' . (int)$_GET['oID'] . '&customer_id=' . $Qcustomers->valueInt('customers_id')); ?>"  data-toggle="modal" data-refresh="true" data-target="#myModal"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/edit.gif', $CLICSHOPPING_Orders->getDef('icon_edit')); ?></a>
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-body"><div class="te"></div></div>
                        </div> <!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                  </div>
                </div>
              </div>
              <div class="separator"></div>

              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_telephone_number'); ?></span>
                <span class="col-md-3"><strong><?php echo  $order->customer['telephone']; ?></strong></span>
                <span class="col-md-3 text-md-right"><?php echo $CLICSHOPPING_Orders->getDef('entry_customer_location'); ?></span>
                <span class="col-md-3"><a target="_blank" rel="noreferrer" href="http://maps.google.com/maps?q=<?php echo $order->delivery['street_address'], ',', $order->delivery['postcode'], ',', $order->delivery['state'], ',', $order->delivery['country']; ?>&hl=fr&um=1&ie=UTF-8&sa=N&tab=wl"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/google_map.gif', $CLICSHOPPING_Orders->getDef('entry_customer_location')); ?></a></span>
              </div>
              <div class="separator"></div>
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_cellular_phone_number'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->customer['cellular_phone']; ?></strong></span>
              </div>
              <div class="separator"></div>
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_email_address'); ?></span>
                <span class="col-md-5"><strong><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></strong></span>
              </div>
              <div class="separator"></div>
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_client_computer_ip'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->customer['client_computer_ip']; ?></strong></span>
              </div>
              <div class="separator"></div>
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_provider_name_client'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->customer['provider_name_client']; ?></strong></span>
              </div>
            </div>
          </div>
          <div class="separator"></div>
          <div class="mainTitle"><?php echo $CLICSHOPPING_Orders->getDef('title_orders_paiement'); ?></div>

          <div class="adminformTitle">
            <div class="row" id="tab1ContentRow2">
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_payment_method'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->info['payment_method']; ?></strong></span>
              </div>
<?php
  if (!is_null($order->info['cc_type']) || !is_null($order->info['cc_owner']) || !is_null($order->info['cc_number'])) {
?>
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_credit_card_type'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->info['cc_type']; ?></strong></span>
              </div>
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_credit_card_owner'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->info['cc_owner']; ?></strong></span>
              </div>
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_credit_card_number'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->info['cc_number']; ?></strong></span>
              </div>
              <div class="col-md-12">
                <span class="col-md-3"><?php echo $CLICSHOPPING_Orders->getDef('entry_credit_card_express'); ?></span>
                <span class="col-md-5"><strong><?php echo $order->info['cc_expires']; ?></strong></span>
              </div>
<?php
  }
?>
              <div class="separator"></div>
              <div class="separator"></div>
            </div>
          </div>
          <?php echo $CLICSHOPPING_Hooks->output('Orders', 'PageContentTab1', null, 'display'); ?>
        </div>
<!-- //###########################################//-->
<!--          Order informations                                                     //-->
<!-- //###########################################//-->

        <div class="tab-pane" id="tab2">
          <table width="100%" border="0" cellspacing="0" cellpadding="5" class="adminformTitle">
            <tr class="dataTableHeadingRow">
              <td></td>
              <td></td>
              <td  colspan="3" class="text-md-center"><?php echo $CLICSHOPPING_Orders->getDef('table_heading_products'); ?></td>
              <td><?php echo $CLICSHOPPING_Orders->getDef('table_heading_products_model'); ?></td>
              <td  class="text-md-right"><?php echo $CLICSHOPPING_Orders->getDef('table_heading_tax'); ?></td>
              <td  class="text-md-right"><?php echo $CLICSHOPPING_Orders->getDef('table_heading_price_excluding_tax'); ?></td>
              <td  class="text-md-right"><?php echo $CLICSHOPPING_Orders->getDef('table_heading_price_including_tax'); ?></td>
              <td  class="text-md-right"><?php echo $CLICSHOPPING_Orders->getDef('table_heading_total_excluding_tax'); ?></td>
              <td  class="text-md-right"><?php echo $CLICSHOPPING_Orders->getDef('table_heading_total_including_tax'); ?></td>
            </tr>
<?php
  for ($i=0, $n=count($order->products); $i<$n; $i++) {

    $QproductsImage = $CLICSHOPPING_Orders->db->prepare('select products_image,
                                                                products_id
                                                        from :table_products
                                                        where products_id = :products_id
                                                       ');
    $QproductsImage->bindInt(':products_id', (int)$order->products[$i]['products_id']);
    $QproductsImage->execute();

    echo '    <tr class="dataTableRow">' . "\n" .
      '      <td class="dataTableContent" valign="top"><a href="' . CLICSHOPPING::link('index.php', 'A&Catalog\Preview&Preview&pID=' . $QproductsImage->valueInt('products_id')) . '">' . HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/preview.gif', $CLICSHOPPING_Orders->getDef('text_preview')) .'</a></td>' . "\n" .
      '      <td class="dataTableContent" valign="top"><a href="' . CLICSHOPPING::link('index.php', 'A&Catalog\Products&Products&pID=' . $QproductsImage->valueInt('products_id') .'&action=new_product') . '">' . HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/edit.gif', $CLICSHOPPING_Orders->getDef('image_edit')) .'</a></td>' . "\n" .
      '      <td class="dataTableContent" valign="top"><img src=" ' . $CLICSHOPPING_Template->getDirectoryShopTemplateImages() . $QproductsImage->value('products_image') . '" width=" '. (int)SMALL_IMAGE_WIDTH_ADMIN .'" height="'. (int)SMALL_IMAGE_HEIGHT_ADMIN .'"></td>' . "\n" .
      '      <td class="dataTableContent" valign="top">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
      '      <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

    if (isset($order->products[$i]['attributes']) && (count($order->products[$i]['attributes']) > 0)) {
      for ($j = 0, $k = count($order->products[$i]['attributes']); $j < $k; $j++) {

// attributes reference
        if ($order->products[$i]['attributes'][$j]['reference'] != '' || $order->products[$i]['attributes'][$j]['reference'] !='null') {
          $attributes_reference = '<strong> ' .$order->products[$i]['attributes'][$j]['reference'] . '</strong> - ';
        }

        echo '<br /><nobr><small>&nbsp;<i> - ' . $attributes_reference  . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];

        if ($order->products[$i]['attributes'][$j]['price'] != 0) echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $CLICSHOPPING_Currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
        echo '</i></small></nobr>';
      }
    }

    echo '      </td>' . "\n" .
      '      <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
      '      <td class="text-md-right dataTableContent">' . $order->products[$i]['tax'] . '</td>' . "\n" .
      '      <td class="text-md-right dataTableContent"><strong>' . $CLICSHOPPING_Currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n" .
      '      <td class="text-md-right dataTableContent"><strong>' . $CLICSHOPPING_Currencies->format(Tax::addTax($order->products[$i]['final_price'], $order->products[$i]['tax'], true), true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n" .
      '      <td class="text-md-right dataTableContent"><strong>' . $CLICSHOPPING_Currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n" .
      '      <td class="text-md-right dataTableContent"><strong>' . $CLICSHOPPING_Currencies->format(Tax::addTax($order->products[$i]['final_price'], $order->products[$i]['tax'], true) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>' . "\n";
    echo '    </tr>' . "\n";
  }
?>
            <tr>
              <td class="text-md-right" colspan="11"><table border="0" cellspacing="0" cellpadding="2" width="100%">
<?php
  foreach ($order->totals as $value) {
    echo '      <tr>' . "\n" .
      '          <td class="text-md-right"><strong>' . $value['title'] . '</strong></td>' . "\n" .
      '          <td class="text-md-right"><strong>' . strip_tags($value['text']) . '</strong></td>' . "\n" .
      '        </tr>' . "\n";
  }
?>
                </table></td>
            </tr>
          </table>
          <div class="col-md-12" id="contentTab2"></div>
          <?php echo $CLICSHOPPING_Hooks->output('Orders', 'PageContentTab2', null, 'display'); ?>
        </div>


<!-- //###########################################//-->
<!--          ONGLET statut commande                                        //-->
<!-- //###########################################//-->
        <div class="tab-pane" id="tab3">
          <div class="mainTitle"><?php echo $CLICSHOPPING_Orders->getDef('table_heading_comments'); ?></div>
          <?php echo HTML::form('status', $CLICSHOPPING_Orders->link('Orders&Update&oID=' . $oID)); ?>
          <div class="adminformTitle" id="StatusOrder">
            <div class="row" id="tab3ContentRow1">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8"><?php echo HTML::textAreaField('comments', null, '60', '5', 'placeholder="' . $CLICSHOPPING_Orders->getDef('entry_notify_comments') .'"'); ?></div>
                  <div class="col-md-4 text-md-right"><?php echo HTML::button($CLICSHOPPING_Orders->getDef('button_update'), null, null, 'success'); ?></div>
                </div>
              </div>
              <div class="separator"></div>
              <div class="col-md-12">
                <div class="row">
                  <span class="col-md-2"><strong><?php echo $CLICSHOPPING_Orders->getDef('entry_status'); ?></strong></span>
                  <span class="col-md-4"><?php echo HTML::selectMenu('status', $orders_statuses, $order->info['orders_status']); ?></span>
                  <span class="col-md-2"><strong><?php echo $CLICSHOPPING_Orders->getDef('entry_status_invoice'); ?></strong></span>
                  <span class="col-md-4"><?php echo HTML::selectMenu('status_invoice', $orders_invoice_statuses, $order->info['orders_status_invoice']); ?></span>
                </div>
              </div>

              <div class="separator"></div>
              <div class="col-md-12" id="contentTab3"></div>

              <div class="separator"></div>
              <div class="col-md-12">
                <div class="row">
                  <span class="col-md-2"><strong><?php echo $CLICSHOPPING_Orders->getDef('entry_status_orders_support_name'); ?></strong></span>
                  <span class="col-md-4"><?php echo HTML::selectMenu('orders_status_support_id', $orders_support_statuses, $order->info['orders_status_support']); ?></span>
                </div>
              </div>
              <div class="separator"></div>
              <div class="col-md-12">
                <div class="row">
                  <span class="col-md-2"><strong><?php echo $CLICSHOPPING_Orders->getDef('entry_notify_customer'); ?></strong></span>
                  <span class="col-md-4"><?php echo HTML::checkboxField('notify', '', true); ?></span>
                  <span class="col-md-2"><strong><?php echo $CLICSHOPPING_Orders->getDef('entry_notify_comments'); ?></strong></span>
                  <span class="col-md-4"><?php echo HTML::checkboxField('notify_comments', '', true); ?></span>
                </div>
              </div>
            </div>
            <div class="separator"></div>
            <div id="ErpOrder"></div>
          </form>
          <div class="separator"></div>
          <div class="mainTitle"><?php echo $CLICSHOPPING_Orders->getDef('title_orders_history'); ?></div>
          <div class="adminformTitle">
            <table class="table table-sm table-hover">
              <thead>
              <tr>
                <td class="smallText text-md-center"><strong><?php echo $CLICSHOPPING_Orders->getDef('table_heading_date_added'); ?></strong></td>
                <td class="smallText text-md-center"><strong><?php echo $CLICSHOPPING_Orders->getDef('table_heading_customer_notified'); ?></strong></td>
                <td class="smallText text-md-center"><strong><?php echo $CLICSHOPPING_Orders->getDef('table_heading_status'); ?></strong></td>
                <td class="smallText text-md-center"><strong><?php echo $CLICSHOPPING_Orders->getDef('table_heading_comments'); ?></strong></td>
              </tr>
              </thead>
              <tbody>
<?php
  $QordersHistory = $CLICSHOPPING_Orders->db->prepare('select orders_status_id,
                                                             orders_status_invoice_id,
                                                             admin_user_name,
                                                             date_added,
                                                             customer_notified,
                                                             comments,
                                                             orders_status_tracking_id,
                                                             orders_tracking_number,
                                                             orders_status_support_id,
                                                             evidence
                                                      from :table_orders_status_history
                                                      where orders_id = :orders_id
                                                      order by date_added
                                                     ');
  $QordersHistory->bindInt(':orders_id', $oID);
  $QordersHistory->execute();

  if ($QordersHistory->rowCount() > 0) {
    while ($QordersHistory->fetch() !== false) {
      echo '      <tr>' . "\n" .
        '        <td class="text-md-center">' . DateTime::toShort($QordersHistory->value('date_added')) . '</td>' . "\n" .
        '        <td class="text-md-center">';
      if ($QordersHistory->valueInt('customer_notified') === 1) {
        echo '<i class="fas fa-check fa-lg" aria-hidden="true"></i>' . "\n";
      } else {
        echo '<i class="fas fa-times fa-lg" aria-hidden="true"></i>' . "\n";
      }
      echo '        </td>';

      $QordersTracking = $CLICSHOPPING_Orders->db->prepare('select orders_status_tracking_id,
                                                                    orders_status_tracking_link
                                                             from :table_orders_status_tracking
                                                             where orders_status_tracking_id = :orders_status_tracking_id
                                                             and language_id = :language_id
                                                            ');
      $QordersTracking->bindInt(':orders_status_tracking_id', $QordersHistory->valueInt('orders_status_tracking_id'));
      $QordersTracking->bindInt(':language_id', $CLICSHOPPING_Language->getId());
      $QordersTracking->execute();


      if (empty($QordersTracking->value('orders_status_tracking_link'))) {
        $tracking_url = ' (<a href="https://www.track-trace.com/" target="_blank" rel="noreferrer">http://www.track-trace.com/</a>)';
      } else {
        $tracking_url = ' (<a href="' . $QordersTracking->value('orders_status_tracking_link') .  $QordersHistory->value('orders_tracking_number') . '" target="_blank" rel="noreferrer">' . $QordersTracking->value('orders_status_tracking_link') .  $QordersHistory->value('orders_tracking_number') . '</a>)';;
      }

      echo '        <td class="text-md-center">' . $orders_status_array[$QordersHistory->valueInt('orders_status_id')] . '</td>' . "\n" .
           '        <td>
' . $CLICSHOPPING_Orders->getDef('entry_status_comment_invoice') . $orders_status_invoice_array[$QordersHistory->valueInt('orders_status_invoice_id')] . '<br />
' . $CLICSHOPPING_Orders->getDef('entry_status_invoice_realised') . $QordersHistory->value('admin_user_name') . '<br />
' . $CLICSHOPPING_Orders->getDef('entry_status_orders_tracking_name') . ' ' . $QordersHistory->value('orders_tracking_number') . '<br />' .  $tracking_url . '<br>
' . $CLICSHOPPING_Orders->getDef('entry_status_orders_support_name') . ' : ' . $orders_status_support_array[$QordersHistory->valueInt('orders_status_support_id')]   . '<hr>
' . $CLICSHOPPING_Orders->getDef('entry_status_invoice_note') . '<br />
' . nl2br(HTML::sanitize($QordersHistory->value('comments'))) . '<br />';

      if(!is_null($QordersHistory->value('evidence'))) {
        echo $CLICSHOPPING_Orders->getDef('entry_status_evidence') . '<br /><a href="' . CLICSHOPPING::link('../sources/Download/Evidence/' . $QordersHistory->value('evidence')) . '">' .  $QordersHistory->value('evidence') .'</a><br />';
      }

      echo    '        </td>' . "\n" .
        '      </tr>' . "\n";
    }
  } else {
    echo '      <tr>' . "\n" .
      '        <td colspan="5">' . $CLICSHOPPING_Orders->getDef('text_no_order_history') . '</td>' . "\n" .
      '      </tr>' . "\n";
  }
?>
              </tbody>
            </table>
          </div>

          <?php echo $CLICSHOPPING_Hooks->output('Orders', 'PageContentTab3', null, 'display'); ?>
        </div>
<!-- //################################################################################################################ -->
<!-- //                                              Other Tab                                                -->
<!-- //################################################################################################################ -->
<?php echo $CLICSHOPPING_Hooks->output('Orders', 'PageTab', null, 'display'); ?>
      </div>
    </div>
  </div>
</div>