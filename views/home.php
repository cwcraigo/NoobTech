<?php
/* ************************************************************************
* AUTHOR:  Craig W. Christensen
* DATE:
* DESCRIPTION:
************************************************************************ */
session_start();

// getting message if exists.
if (!empty($_SESSION['error'])) {
	$error = $_SESSION['error'];
	unset($_SESSION['error']);
}

// getting featured video
if (!empty($_SESSION['featured_video'])) {
  $video = $_SESSION['featured_video'];
}

$page_heading = 'HOME';
$page_icon = 'icon-home';

?>

<?php require_once $current_dir.'/modules/header.php'; ?>

<!-- TOP ROW -->
<div class="row-fluid">

  <div class="span10 offset1">
    <div class="page-header"><h2>Featured Video</h2></div>
    <?php if($error){ echo "<p class='alert alert-error' >$error</p>"; unset($error); } ?>
    <div class="row-fluid">

    <div class="span6">

      <object classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B'
              codebase='http://www.apple.com/qtactivex/qtplugin.cab'
              width="500"
              height="350">
        <param name='src'        value="<?php echo $video['video_url']; ?>">
        <param name='autoplay'   value="false">
        <param name='controller' value="true">
        <param name='loop'       value="false">
        <embed src="<?php echo $video['video_url']; ?>"
               pluginspage='http://www.apple.com/quicktime/download/'
               width="500"
               height="350"
               scale="tofit"
               autoplay="false"
               controller="true"
               loop="false"
               bgcolor="#000000">
        </embed>
      </object>

    </div>

    <div class="span6">
      <dl class="home_list" >
        <dt class='video_title'>Title:</dt>
        <dd class='video_desc'><?php echo $video['video_title']; ?></dd>
        <dt class='video_title'>Description:</dt>
        <dd class='video_desc'><?php echo $video['video_desc']; ?></dd>
        <dt class='video_title'>Length:</dt>
        <dd class='video_desc'><?php echo $video['video_length']; ?></dd>
        <dt class='video_title'>Creation Date:</dt>
        <dd ><?php echo date("M j, Y g:ia", strtotime($video['creation_date'])); ?></dd>
      </dl>
    </div>
</div>
  </div>

</div>
<!-- END TOP ROW -->

<?php require_once $current_dir.'/modules/footer.php'; ?>
