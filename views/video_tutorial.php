<?php
/* * ******************************************
 * Suggestion Form View
 * ****************************************** */
session_start();

if (isset($_SESSION['video_message'])) {
    $error = $_SESSION['video_message'];
    unset($_SESSION['video_message']);
}
if (isset($_SESSION['video_array'])) {
    $video_array = $_SESSION['video_array'];
    unset($_SESSION['video_array']);
}

$page_heading = 'TUTORIALS';
$page_icon = 'icon-film';

?>

<?php require_once $current_dir.'/modules/header.php'; ?>

<!-- LEFT COL (form/videos) -->
<!-- <div class="span4"> -->

<!-- <div class='page-header'><h2>Find A Video</h2></div> -->

<!--   <form class='form-search'>
    <div class='input-append'>
      <input class='search-query span12' name='tag' placeholder='Search for a Video' />
      <button class='btn' type='button' onclick='get_videos(tag.value)' >TEST</button>
    </div>
  </form> -->

  <!-- <form action='.' method='post' class='form-search'>
    <div class='input-append'>
      <input class='search-query span12' name='video_tag' placeholder='Search for a Video' />
      <button class='btn' type='submit' >Search</button>
      <input type='hidden' name='action' value='search_videos'>
    </div>
  </form> -->

<!-- </div> -->
<!-- END LEFT COL -->

<!-- RIGHT COL (suggestions/comments) -->
<div class="span10 offset1" >

  <div class='page-header'> <h2>Videos</h2> </div>

  <?php if($error){ echo "<p class='alert alert-error' >$error</p>"; unset($error); } ?>

  <!-- <div id='video_list' style='border:solid thin yellow;'></div> -->
  <dl>
<?php

    foreach ($video_array as $video) {

      echo "<dt class='video_title' >
                <a href='/index.php?action=video_link&video_id=$video[video_id]' >$video[video_title]</a></dt>
            <dd class='video_desc' >$video[video_desc]</dd>
            <dd class='video_length' >Video Length: $video[video_length]</dd>";

      if ($_SESSION['rights'] == 1) {
        echo "<div class='row-fluid' >
              <form action='.' method='post' class='span1'>
                <button class='btn btn-danger btn-mini' ><i class='icon-pencil icon-white'></i>Edit</button>
                <input type='hidden' name='action' value='edit_video'>
                <input type='hidden' name='video_id' value='$video[video_id]'>
              </form>
              <form action='.' method='post' class='span1'>
                <button class='btn btn-danger btn-mini' ><i class='icon-remove-circle icon-white'></i>Delete</button>
                <input type='hidden' name='action' value='delete_video'>
                <input type='hidden' name='video_id' value='$video[video_id]'>
              </form>
              </div>";
      }

      echo "<dd class='creation_date' >".date("M j, Y g:ia", strtotime($video['creation_date']))."</dd>";

    } // end for each loop

?>
  </dl>
</div>
<!-- END RIGHT COL -->

<?php require_once $current_dir.'/modules/footer.php'; ?>