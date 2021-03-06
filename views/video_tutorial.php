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
if (!empty($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
}

$page_heading = 'TUTORIALS';
$page_icon = 'icon-film';

$totalviews = $_SESSION['totalviews'];
$total = $_SESSION['total_videos'];

?>

<?php require_once $current_dir.'/modules/header.php'; ?>

<!-- TOP ROW (LIST OF TUTORIALS) -->
<div class="span10 offset1" >

  <!-- TITLE -->
  <div class='page-header'> <h2>Videos</h2> </div>

  <!-- ERROR/MESSAGE -->
  <?php if($error){ echo "<p class='alert alert-error' >$error</p>"; unset($error); } ?>

<div id="video_div">
<?php
    foreach ($video_array as $video) {
      if ($_SESSION['rights'] == 1) {
        echo "<form action='.' method='post' class='form-horizontal' id='edit_tutorial_form'>
                <div class='control-group'>
                  <label class='control-label' for='video_title' >Title:</label>
                  <div class='controls'>
                    <input class='input-xlarge' type='text' name='video_title' value='$video[video_title]' />
                  </div>
                </div>
                <div class='control-group'>
                  <label class='control-label' for='video_url' >Video URL:</label>
                  <div class='controls'>
                    <input class='input-xlarge' type='text' name='video_url' value='$video[video_url]' />
                    <a href='/index.php?action=video_link&video_id=$video[video_id]' >Go to video</a>
                  </div>
                </div>
                <div class='control-group'>
                  <label class='control-label' for='video_desc' >Description:</label>
                  <div class='controls'>
                    <input class='input-block-level' type='text' name='video_desc' value='$video[video_desc]' />
                  </div>
                </div>
                <div class='control-group'>
                  <label class='control-label' for='video_length' >Video Length:</label>
                  <div class='controls'>
                    <input class='input-mini' type='text' name='video_length' value='$video[video_length]' />
                  </div>
                </div>
                <div class='control-group'>
                  <label class='control-label' for='creation_date' >Creation Date:</label>
                  <div class='controls'>
                    <input class='input-medium' size='20' type='text' name='creation_date' value='".date("M j, Y g:ia", strtotime($video['creation_date']))."' disabled />
                    <input type='hidden' name='action' value='edit_video' />
                    <input type='hidden' name='video_id' value='$video[video_id]' />
                    <br><br>
                    <button type='submit' class='btn btn-danger btn-mini' ><i class='icon-pencil icon-white'></i>Edit</button>
                    <a class='btn btn-danger btn-mini' href='/index.php?action=delete_video&video_id=$video[video_id]' ><i class='icon-remove-circle icon-white'></i>Delete</a>
                  </div>
                </div>
              </form>
              <br><br>";

      } else {
        echo "<ul class='media-list'>
                <li class='media'>
                  <a class='pull-left' href='#'>
                    <object classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B'
                            codebase='http://www.apple.com/qtactivex/qtplugin.cab'
                            width='128'
                            height='100'>
                      <param name='src'        value='$video[video_url]'>
                      <param name='autoplay'   value='false'>
                      <param name='controller' value='false'>
                      <param name='loop'       value='false'>
                      <embed src='$video[video_url]'
                             pluginspage='http://www.apple.com/quicktime/download/'
                             width='128'
                             height='100'
                             scale='tofit'
                             autoplay='false'
                             controller='false'
                             loop='false'
                             bgcolor='#000000'>
                      </embed>
                    </object>
                  </a>
                  <div class='media-body'>
                    <dl>
                      <dt class='media-heading'>
                        <a href='/index.php?action=video_link&video_id=$video[video_id]' >
                          $video[video_title]
                        </a>
                      </dt>
                      <dd class='video_desc' >
                        $video[video_desc]
                      </dd>
                      <dd class='video_length' >
                        Video Length: $video[video_length]
                      </dd>
                      <dd class='creation_date' >
                        ".date("M j, Y g:ia", strtotime($video['creation_date']))."
                      </dd>
                    </dl>
                  </div>
                </li>
              </ul>";
      } // end rights check
    } // end for each loop

// PAGINATION CHECK
if ($totalviews != 0) { ?>

  <!-- PAGINATION -->
  <div class='pagination pagination-centered' >
    <ul>
      <li><a href="#" onclick="video_pagination(1)" >&laquo;</a></li>
    <?php
      for ($i=1;$i<=$totalviews;$i++) {
        if ($i == 1) {
          echo "<li class='active' ><a href='#' onclick='video_pagination($i)' >$i</a></li>";
        } else {
          echo "<li><a href='#' onclick='video_pagination($i)' >$i</a></li>";
        }
      } // end loop ?>
      <li><a href="#" onclick="video_pagination(<?php echo $totalviews; ?>)" >&raquo;</a></li>
    </ul>
  </div>
  <!-- END PAGINATION DIV -->
<?php } // end pagination check ?>
</div>
<!-- END VIDEO DIV -->

<!-- ADD VIDEO FORM -->
<?php if($_SESSION['rights'] == 1) { ?>
  <div class="page-header"> <h2>Add Video</h2> </div>
  <form method="post" action="." class='form-horizontal' id='add_video_form'>
    <div class='control-group'>
      <label class="control-label" for="video_title">Video Title: </label>
      <div class='controls'>
        <input class="input-xlarge" type="text" name="video_title" id="video_title" required /><br />
      </div>
    </div>
    <div class='control-group'>
      <label class="control-label" for="video_desc" >Video Description: </label>
      <div class='controls'>
        <textarea class="input-block-level" type="text" name="video_desc" id="video_desc" required></textarea><br />
      </div>
    </div>
    <div class='control-group'>
      <label class="control-label" for="video_length">Video Length: </label>
      <div class='controls'>
        <input class='input-small' type="text" name="video_length" id="video_length" required /><br />
      </div>
    </div>
      <input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit" />
      <input type="hidden" name="action" value="add_video_form" />
  </form>
<?php } // end rights check ?>
</div>
<!-- END RIGHT COL -->

<?php require_once $current_dir.'/modules/footer.php'; ?>