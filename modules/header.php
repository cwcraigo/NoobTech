<?php
session_start();

if (!empty($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
}

// var_dump($_SESSION['rights']);
// var_dump($_SESSION['user_id']);

?>

<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" type="image/png" href="pics/noobicon_gameboy.png">
      <title>NoobTech</title>
      <!-- Bootstrap -->
      <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
      <link href='http://fonts.googleapis.com/css?family=Source+Code+Pro:600|Stalinist+One|Black+Ops+One|Audiowide' rel='stylesheet' type='text/css'>
      <link href="../lib/css.css" rel="stylesheet" type="text/css" media="screen" />
      <script src="lib/bootstrap/js/bootstrap.min.js"></script>
      <script src="lib/js.js"></script>
  </head>
<body>

  <!-- CONTAINER -->
  <div class="container-fluid">

    <!-- HEADER ROW -->
    <div class='row-fluid' id="header">

      <!-- TITLE -->
      <div class="span5" id="main_title">NoobTech</div>

      <div class="navbar navbar-static-top span7" id="myNavBar">
        <div class="navbar-inner">
          <div class="container-fluid">

            <!-- <div class="row-fluid"> -->
              <!-- NAVIGATION -->
              <ul class='nav span7' id="terminal_buttons2">
                <li><a href="/index.php?action=home"><i class="icon-home"></i>HOME</a></li>
                <li><a href="/index.php?action=suggestions"><i class="icon-th-list"></i>SUGGESTIONS</a></li>
                <li><a href="/index.php?action=tutorials"><i class="icon-film"></i>TUTORIALS</a></li>
              </ul>

              <div class="nav" id="terminal_top2">
                <form action='.' method='post' class='navbar-search form-inline'>
                  <label class="control-label"><i class="icon-search"></i></label>
                  <input class='search-query input-small' name='video_tag' placeholder='Video Search' />
                  <input type='hidden' name='action' value='search_videos'>
                </form>
              </div>

              <?php if($_SESSION['rights'] == 1) { ?>
                <a class="btn btn-inverse btn-mini" href="/index.php?action=logout"><i class="icon-off icon-white"></i>Logout</a>
              <?php } else { ?>
                <button class='btn btn-inverse btn-mini' onclick='login_form()' ><i class="icon-user icon-white"></i>Login</button>
              <?php } ?>

            <!-- </div> -->

          </div>
        </div>
      </div>
    </div>
    <!-- END HEADER ROW -->

    <div id='loginDiv'>
      <form action='.' method='post' class='form-inline text-center'>
        <div class='controls controls-row'>
          <input class='input-medium' type='email' name='email' placeholder='Email' required />
          <input class='input-medium' type="password" name='password' placeholder='Password' required />
          <button class='btn btn-medium btn-info' type='submit' >Login</button>
          <input type='hidden' name='action' value='login'>
        </div>
      </form>
    </div>
<br>
    <!-- TERMINAL HEADING -->
    <div class="navbar" id="terminal_top">
        <div class="navbar-inner">
          <div class="container">
            <div class="row-fluid">

              <ul class="nav span3" id="terminal_buttons">
                <li><i class="icon-remove-sign"></i></li>
                <li>&nbsp;</li>
                <li><i class="icon-minus-sign"></i></li>
                <li>&nbsp;</li>
                <li><i class="icon-plus-sign"></i></li>
              </ul>

              <div class="brand span6" id="terminal_heading">
                <p class="text-center"><i class="<?php echo $page_icon; ?>"></i><?php echo $page_heading; ?> - NoobTech_Terminal</p>
                <!-- <p class="text-center"><i class="icon-home"></i>HOME - NoobTech_Terminal</p> -->
              </div>

              <ul class="nav pull-right" id="fullscreen">
                <li><i class="icon-resize-full"></i></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    <!-- CONTENT ROW -->
    <div class="row-fluid" id="content">

