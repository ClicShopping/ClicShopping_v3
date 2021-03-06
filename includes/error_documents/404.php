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

  use ClicShopping\OM\HTTP;

  http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Error - Page Not Found</title>
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

  <script src="https://kit.fontawesome.com/89fdf54890.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="text-center" style="padding-top:150px;">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="text-center" style="padding-top:50px; padding-bottom:20px;">
          <h1><?php echo HTTP::redirect(HTTP::getShopUrlDomain() . 'index.php'); ?></h1></div>
        <div class="error-template">
          <h1>Oops!</h1>
          <h2>404 Not Found</h2>
          <div class="error-details">
            Sorry, an error has occured, Requested page not found!
            <h1>This Page is Missing</h1>
            <p>It looks like this page is missing. Please continue back to our website and try again.</p>
            <p style="margin-top: 40px;">
              <php echo HTML::button(
              'Return to website', null, CLICSHOPPING::link(), null, 'primary'); ?>
            </p>

          </div>
          <div class="error-actions">
            <br/><br/>
            <i class="fas fa-home"></i> <span style="text-decoration:none;"><a
                href="<?php echo HTTP::redirect(HTTP::getShopUrlDomain() . 'index.php'); ?>">Go to the web store</a></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script></body>
</html>
