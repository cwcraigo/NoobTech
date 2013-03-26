<?php
session_start();

// var_dump($_SESSION['rights']);

?>

<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
    <div class='row-fluid'>

      <div class="navbar navbar-inverse">
        <div class="navbar-inner">
          <div class="container">

            <!-- TITLE -->
            <a class='brand' id="main_title" href="#">NoobTech</a>

            <!-- NAVIGATION -->
            <ul class='nav'>
              <li><a href="/index.php?action=home">HOME</a></li>
              <li><a href="/index.php?action=suggestions">TUTORIAL SUGGESTIONS</a></li>
              <li><a href="/index.php?action=tutorials">VIDEO TUTORIALS</a></li>
            </ul>

            <form action='.' method='post' class='navbar-search'>
                <input class='search-query span12' name='video_tag' placeholder='Search for a Video' />
                <input type='hidden' name='action' value='search_videos'>
            </form>

            <?php if($_SESSION['rights'] == 1) { ?>
              <a href="/index.php?action=logout"><button class='btn btn-inverse btn-mini pull-right' >Logout</button></a>
            <?php } else { ?>
              <button class='btn btn-inverse btn-mini pull-right' onclick='login_form()' >Login</button>
            <?php } ?>

          </div>
        </div>
      </div>
    </div>
    <!-- END HEADER ROW -->

    <div id='loginDiv'>
      <form action='.' method='post' class='form-inline'>
        <div class='controls controls-row'>
          <input class='input-medium' type='email' name='email' placeholder='Email' required />
          <input class='input-medium' type="password" name='password' placeholder='Password' required />
          <button class='btn btn-medium btn-info' type='submit' >Login</button>
          <input type='hidden' name='action' value='login'>
        </div>
      </form>
    </div>

    <!-- CONTENT ROW -->
    <div class="row-fluid">