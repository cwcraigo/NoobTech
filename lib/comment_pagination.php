<?php

session_start();

$current_dir = $_SERVER['DOCUMENT_ROOT'];

if(file_exists("$current_dir/lib/model.php")) {
  include_once $current_dir.'/lib/model.php';
} else {
	echo 'movie_search - model 404 Error!';
}

$video_id = $_GET['id'];

$per_page = 5;

$total = get_comment_count($video_id);

$totalviews = ceil($total / $per_page);

$page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

$start = ($page - 1) * $per_page;

$comment_array = get_comments($start, $per_page, $video_id);


$str = "";

foreach ($comment_array as $comment) {
        if ($_SESSION['rights'] == 1) {
        $str .= "<form action='.' method='post' class='form-horizontal' id='edit_comment_form'>
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
        $str .= "<dl>
                <dt class='suggestion_title' >$comment[comment_text]</dt>
                <dd class='suggestion_desc' >$comment[created_by]</dd>
                <dd class='suggestion_info'>
                  Created on ".date("M j, Y g:ia", strtotime($comment['creation_date']))."</dd>
              </dl>";
            }
      } // end foreach

// <!-- PAGINATION -->
if ($totalviews != 0) {

  $str .= "<div class='pagination pagination-centered' >
  				<ul>
      			<li>
      				<a href='#' onclick='comment_pagination(1,$video_id)' >&laquo;</a>
      			</li>";

  for ($i=1;$i<=$totalviews;$i++) {
  	if ($page == $i) {
  		$str .= "<li class='active' >
  	  					<a href='#' onclick='comment_pagination($i,$video_id)' >$i</a>
  	  				</li>";
  	}	else {
  		$str .= "<li>
  							<a href='#' onclick='comment_pagination($i,$video_id)' >$i</a>
  						</li>";
  	}

  } // end loop

  $str .= "<li>
  					<a href='#' onclick='video_pagination($totalviews),$video_id' >&raquo;</a>
  				</li>
  			</ul>
  			</div>";
} // end pagination

echo $str;

?>