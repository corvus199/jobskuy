<?php

// Set default language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id'; // default language
}

// Change language if requested
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header('Location: ' . $_SERVER['PHP_SELF']); // redirect to current page
    exit;
}

// Load language file
$lang_file = 'lang_' . $_SESSION['lang'] . '.php';
if (file_exists($lang_file)) {
    $lang = include($lang_file);
} else {
    $lang = include('lang_id.php'); // fallback to default language
}

// Database connection code (assuming you have a DB connection setup here)
 // replace with your actual DB connection file
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="#">

  <title><?php echo $title; ?></title>

  <link href="<?php echo $url ?>assets/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="<?php echo $url ?>assets/bootstrap/css/datetimepicker.css" rel="stylesheet">
  <link href="<?php echo $url ?>assets/css/navbar-fixed-top.css" rel="stylesheet">
  <link href="<?php echo $url ?>assets/css/full-slider.css" rel="stylesheet">
  <link href="<?php echo $url ?>assets/css/style.css" rel="stylesheet">

  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

  <nav class="navbar navbar-default navbar-fixed-top navbar-blue">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only"><?php echo $lang['toggle_navigation']; ?></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">JOBSKUY</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?php echo $url ?>"><?php echo $lang['home']; ?></a></li>
          <li><a href="<?php echo $url ?>menu.php"><?php echo $lang['menu']; ?></a></li>
          <li><a href="<?php echo $url ?>kontak.php"><?php echo $lang['contact']; ?></a></li>
          <li><a href="<?php echo $url ?>info.php"><?php echo $lang['payment_info']; ?></a></li>
          <?php if (!empty($_SESSION['iam_user'])) { ?>
            <?php
            $user = mysql_fetch_object(mysql_query("SELECT * FROM user WHERE id='$_SESSION[iam_user]'"));
            // Ambil jumlah notifikasi untuk pembayaran
            $notification_count = mysql_result(mysql_query("SELECT COUNT(*) FROM pesanan WHERE user_id='$_SESSION[iam_user]' AND status='belum lunas'"), 0);
            // Ambil jumlah produk favorit
            $favorit_count = mysql_result(mysql_query("SELECT COUNT(*) FROM favorit WHERE user_id='$_SESSION[iam_user]'"), 0);
            ?>
            <li>
              <a href="<?php echo $url ?>pembayaran.php">
                <?php echo $lang['payment']; ?>
                <?php if ($notification_count > 0) { ?>
                  <span class="badge"><?php echo $notification_count; ?></span>
                <?php } ?>
              </a>
            </li>
            <li>
              <a href="<?php echo $url ?>favorit.php">
                <?php echo $lang['favorites']; ?>
                <?php if ($favorit_count > 0) { ?>
                  <span class="badge"><?php echo $favorit_count; ?></span>
                <?php } ?>
              </a>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <?php echo $lang['hi'] . ' ' . $user->nama; ?> <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo $url ?>profile.php"><?php echo $lang['profile']; ?></a></li>
                <li><a href="<?php echo $url ?>riwayat_pembelian.php"><?php echo $lang['purchase_history']; ?></a></li>
                <li><a href="<?php echo $url ?>logout.php"><?php echo $lang['logout']; ?></a></li>
              </ul>
            </li>
          <?php } else { ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <?php echo $lang['login_register']; ?> <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo $url ?>login.php"><?php echo $lang['login']; ?></a></li>
                <li><a href="<?php echo $url ?>register.php"><?php echo $lang['register']; ?></a></li>
              </ul>
            </li>
          <?php } ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <?php echo ucfirst($_SESSION['lang']); ?> <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="?lang=en">English</a></li>
              <li><a href="?lang=id">Bahasa Indonesia</a></li>
            </ul>
          </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav>

  <?php if ('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] == $url . 'index.php') { ?>
    <div class="container">
      <header id="myCarousel" class="carousel slide">
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
          <div class="item active">
            <div class="fill" style="background-image:url('<?php echo $url ?>assets/img/banner1.jpg');"></div>
            <div class="carousel-caption"></div>
          </div>
          <div class="item">
            <div class="fill" style="background-image:url('<?php echo $url ?>assets/img/banner.jpg');"></div>
            <div class="carousel-caption"></div>
          </div>
        </div>
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
          <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
          <span class="icon-next"></span>
        </a>
      </header>
    </div> <!-- /container -->
  <?php } ?>

  <div class="container" style="margin-top:20px;">
    <div class="row">
      <div class="col-md-3">
        <div style="background:#5bc0de; width:100%; height:auto; padding-top:3px;padding-bottom:3px; padding-left:10px;">
          <h4><?php echo $lang['categories']; ?></h4>
        </div>
        <ul class="kategori">
          <?php
          $kategori = mysql_query("SELECT * FROM kategori_produk");
          while ($data = mysql_fetch_array($kategori)) {
          ?>
            <li><a href="<?php echo $url; ?>menu.php?kategori=<?php echo $data['id'] ?>"><?php echo $data['nama']; ?> (
                <?php
                $ck = mysql_num_rows(mysql_query("SELECT * FROM produk WHERE kategori_produk_id='$data[id]'"));
                echo $ck > 0 ? $ck : 0;
                ?>
                )</a></li>
          <?php } ?>
        </ul>
        <div style="background:#5bc0de; width:100%; height:auto; padding-top:3px;padding-bottom:3px; padding-left:10px; margin-bottom:15px;">
          <h4><?php echo $lang['cart']; ?></h4>
        </div>
        <div style="width:100%; height:auto; padding-top:3px;padding-bottom:3px; padding-left:10px; margin-bottom:15px; border: 1px dashed #000;">
          <?php
          if (isset($_SESSION['cart'])) {
            $total = 0;
            $cart = unserialize($_SESSION['cart']);
            if ($cart == '') {
              $cart = [];
            }
            foreach ($cart as $id => $qty) {
              $product = mysql_fetch_array(mysql_query("SELECT * FROM produk WHERE id='$id'"));
              if (isset($product)) {
                $t = $qty * $product['harga'];
                $total += $t;
              }
            }
            echo '<h4 style="color:#f00;">Rp ' . number_format($total, 2, ',', '.') . '</h4>';
          } else {
            echo '<h4 style="color:#f00;">Rp 0,00</h4>';
          }
          ?>
          <a href="<?php echo $url; ?>keranjang.php"><?php echo $lang['view_details']; ?></a>
        </div>
        <div style="background:#5bc0de; width:100%; height:auto; padding-top:3px;padding-bottom:3px; padding-left:10px;">
          <h4><?php echo $lang['search']; ?></h4>
        </div>
        <form action="<?php echo $url; ?>search.php" method="get">
          <input type="text" name="query" class="form-control" placeholder="<?php echo $lang['search_placeholder']; ?>" required>
          <button type="submit" class="btn btn-info btn-block" style="margin-top:10px;"><?php echo $lang['search']; ?></button>
        </form>
        <div class="row col-md-12">
          <hr>
          <img src="<?php echo $url . 'assets/img/bar1.jpg'; ?>" width="100%"><br><br>
          <img src="<?php echo $url . 'assets/img/bar2.jpg'; ?>" width="100%">
        </div>
      </div>
