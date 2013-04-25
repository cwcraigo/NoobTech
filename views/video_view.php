<?php
/* * ******************************************
 * Suggestion Form View
 * ****************************************** */
session_start();

if (isset($_SESSION['video'])) {
    $video = $_SESSION['video'];
}
if (isset($_SESSION['comment_array'])) {
    $comment_array = $_SESSION['comment_array'];
}
if (!empty($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
}

?>

<?php require_once $current_dir.'/modules/header.php'; ?>

<!-- MAIN ROW INSIDE 'TERMINAL' -->
<div class="span10 offset1" >

   <?php if($error){ echo "<p class='alert alert-error' >$error</p>"; unset($error); } ?>

  <div class='page-header'> <h2><?php echo $video['video_title']; ?></h2> </div>

<!-- TOP ROW -->
<div class="row-fluid">

  <!-- VIDEO -->
  <div class="span6">
  <div class="embeddedVideo">
    <object classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B'
            codebase='http://www.apple.com/qtactivex/qtplugin.cab'
            width="600"
            height="400">
     <param name='src'        value="<?php echo $video['video_url']; ?>">
     <param name='autoplay'   value="false">
     <param name='controller' value="true">
     <param name='loop'       value="false">
     <embed src="<?php echo $video['video_url']; ?>"
            pluginspage='http://www.apple.com/quicktime/download/'
            scale="tofit"
            autoplay="false"
            controller="true"
            loop="false"
            bgcolor="#000000"
            width="600"
            height="400">
    </embed>
    </object>
  </div>
  </div>

  <!-- VIDEO DETAILS -->
  <div class="span5 offset1">

    <dl class="home_list" >
      <dt class='video_title'>Description:</dt>
      <dd class='video_desc'><?php echo $video['video_desc']; ?></dd>
      <dt class='video_title'>Length:</dt>
      <dd class='video_desc'><?php echo $video['video_length']; ?></dd>
      <dt class='video_title'>Creation Date:</dt>
      <dd ><?php echo date("M j, Y g:ia", strtotime($video['creation_date'])); ?></dd>
    </dl>

<button class="btn btn-info" onclick="like('video',<?php echo $video['video_id']; ?>)" type="button" ><i class="icon-thumbs-up icon-white"></i> LIKE <span id="like_count"><?php echo $_SESSION['like_count']; ?></span> </button>

  </div>




</div>
<!-- END TOP ROW -->
<!-- ---------------------------------------------------------------------------------------------------- -->
<!-- COMMENTS ROW -->
<div class="row-fluid">

  <div class="page-header"><h2>Comments</h2></div>

  <div id="comment_div">
      <?php

      // comment_id
      // video_id
      // comment_text

      foreach ($comment_array as $comment) {
        if ($_SESSION['rights'] == 1) {
        echo "<form action='.' method='post' class='form-horizontal' id='edit_comment_form'>
                <div class='control-group'>
                  <label class='control-label' for='comment_text' >Comment:</label>
                  <div class='controls'>
                    <input class='input-block-level' type='text' name='comment_text' value='$comment[comment_text]' />
                  </div>
                </div>
                <div class='control-group'>
                  <label class='control-label' for='created_by' >Who:</label>
                  <div class='controls'>
                    <input class='input-medium' type='text' name='created_by' value='$comment[created_by]' />
                  </div>
                </div>
                <div class='control-group'>
                  <label class='control-label' for='creation_date' >When:</label>
                  <div class='controls'>
                    <input class='input-medium' type='text' name='creation_date' value='".date("M j, Y g:ia", strtotime($comment['creation_date']))."' disabled />
                    <input type='hidden' name='action' value='edit_comment' />
                    <input type='hidden' name='comment_id' value='$comment[comment_id]' />
                    <input type='hidden' name='video_id' value='$comment[video_id]' />
                    <br><br>
                    <button type='submit' class='btn btn-danger btn-mini' ><i class='icon-pencil icon-white'></i>Edit</button>
                    <a class='btn btn-danger btn-mini' href='/index.php?action=delete_comment&comment_id=$comment[comment_id]' ><i class='icon-remove-circle icon-white'></i>Delete</a>
                  </div>
                </div>
              </form>";
      } else {
        echo "<dl>
                <dt class='suggestion_title' >$comment[comment_text]</dt>
                <dd class='suggestion_desc' >$comment[created_by]</dd>
                <dd class='suggestion_info'>
                  Created on ".date("M j, Y g:ia", strtotime($comment['creation_date']))."</dd>
              </dl>";
            }
      }
      if ($totalviews != 0) {
      ?>

<!-- ---------------------------------------------------------------------------------------------------- -->
<!-- PAGINATION -->
    <div class='pagination pagination-centered' >
    <ul>
      <li><a href="#" onclick="comment_pagination(1,$_SESSION[video_id])" >&laquo;</a></li>
    <?php
      for ($i=1;$i<=$totalviews;$i++) {
        if ($i == 1) {
          echo "<li class='active' ><a href='#' onclick='comment_pagination($i,$_SESSION[video_id])' >$i</a></li>";
        } else {
          echo "<li><a href='#' onclick='comment_pagination($i,$_SESSION[video_id])' >$i</a></li>";
        }
      } // end loop
    ?>
      <li><a href="#" onclick="comment_pagination(<?php echo $totalviews; ?>,$_SESSION[video_id])" >&raquo;</a></li>
    </ul>
  </div>
<?php } ?>

  </div>
  <!-- END SUGGESTION DIV -->

</div>
<!-- END COMMENTS ROW -->
<!-- ---------------------------------------------------------------------------------------------------- -->
<!-- BOTTOM ROW -->
<div class="row-fluid">

<!-- ADD COMMENT FORM -->
<div class="page-header"><h2>Add Comment</h2></div>
<?php if(isset($_SESSION['rights'])) { ?>
      <form method="post" action=".">
          <label class="control-label" for="comment_text">Comment: </label>
          <textarea class="input-block-level" type="text" name="comment_text" id="comment_text" rows="5" required><?php echo $t_desc; ?></textarea><br />
          <input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit" />
          <input type="hidden" name="video_id" value="<?php echo $video[video_id]; ?>" />
          <input type="hidden" name="action" value="comment_form" />
      </form>
<?php } else {

  echo "<p class='alert alert-info' >To add a comment, please login with a valid BYUI email address.</p>";
}
  ?>

</div>
<!-- END BOTTOM ROW -->


</div>
<!-- END MAIN ROW INSIDE 'TERMINAL' -->

<?php require_once $current_dir.'/modules/footer.php'; ?>