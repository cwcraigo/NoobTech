<?php
/* * ******************************************
 * Suggestion Form View
 * ****************************************** */
session_start();

if (isset($_SESSION['video_message'])) {
    $error = $_SESSION['video_message'];
    unset($_SESSION['video_message']);
}
if (isset($_SESSION['video'])) {
    $video = $_SESSION['video'];
    unset($_SESSION['video']);
}

?>

<?php require_once $current_dir.'/modules/header.php'; ?>

<!-- LEFT COL (form/videos) -->
<!-- <div class="span4"></div> -->
<!-- END LEFT COL -->

<!-- RIGHT COL (suggestions/comments) -->
<div class="span10 offset1" >

  <p class='text-error' > <?php if($error){ echo $error; unset($error); } ?> </p>

  <div class='page-header'> <h3><?php echo $video[video_title]; ?></h3> </div>

  <div class="embeddedVideo">
    <object classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B' width="600" height="400"
                      codebase='http://www.apple.com/qtactivex/qtplugin.cab'>
          <param name='src' value="<?php echo $video['video_url']; ?>">
          <param name='autoplay' value="false">
          <param name='controller' value="true">
          <param name='loop' value="false">
          <embed src="<?php echo $video['video_url']; ?>" width="600" height="400" scale="tofit" autoplay="false"
                       controller="true" loop="false" bgcolor="#000000"
                       pluginspage='http://www.apple.com/quicktime/download/'>
    </embed>
    </object>
  </div>

</div>

<?php require_once $current_dir.'/modules/footer.php'; ?>