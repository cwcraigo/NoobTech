<?php
/* ************************************************************************
* AUTHOR:  Craig W. Christensen
* DATE:
* DESCRIPTION:
************************************************************************ */

// getting message if exists.
if (!empty($_SESSION['error'])) {
	$error = $_SESSION['error'];
	unset($_SESSION['error']);
} elseif (!empty($_SESSION['message'])) {
	$message = $_SESSION['message'];
}

?>

<?php require_once $current_dir.'/modules/header.php'; ?>

			<!-- LEFT COL (form/videos) -->
      <!-- <div class="span4"></div> -->


      <!-- RIGHT COL (suggestions/comments) -->
      <div class="span10 offset1" >

        <div class="page-header"> <h2>Coming Soon</h2> </div>

        <p class='text-error' > <?php if($error){ echo $error; unset($error); } ?> </p>

        <dl class='home_list'> <!-- Description List -->
        	<dt>Pictures</dt> <!-- Description Title -->
        		<dd>More Pictures on homepage and NoobTech logo!</dd> <!-- Description Definition -->
        	<dt>Administrator Options</dt> <!-- Description Title -->
        		<dd>Ability to Update and Delete descriptive data for administrator users.</dd> <!-- Description Definition -->
        	<dt>Comments</dt> <!-- Description Title -->
	        	<dd>Comments next to Movie!</dd> <!-- Description Definition -->
	        <dt>Like Button</dt> <!-- Description Title -->
	        	<dd>Like button next to videos and suggestions to help speed up popular suggestions and rate current videos.</dd> <!-- Description Definition -->
	        <dt>Pagination</dt>
	        	<dd>Adding pagination for suggestions and comments.</dd>
	        <dt>Featured Video</dt>
	        	<dd>Adding a featured video on the homepage (Depending on LIKE status).</dd>
        </dl>

      </div>
      <!-- END RIGHT COL -->

<?php require_once $current_dir.'/modules/footer.php'; ?>

<!--
valid byui email for adding suggestions/comments

featured video

tutor assistance info



Remote Desktop Connection
	157.201.227.(24|25)

	Username: Administrator
	Password: MSproject2010


 -->