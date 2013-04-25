<?php

session_start();

$current_dir = $_SERVER['DOCUMENT_ROOT'];

if(file_exists("$current_dir/lib/model.php")) {
  include_once $current_dir.'/lib/model.php';
} else {
	echo 'movie_search - model 404 Error!';
}

$per_page = 5;

$total = get_video_count();

$totalviews = ceil($total / $per_page);

$page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

$start = ($page - 1) * $per_page;

$video_array = get_videos($start, $per_page);

$str = "";

foreach ($video_array as $video) {
	if ($_SESSION['rights'] == 1) {
		$str .= "<form action='.' method='post' class='form-horizontal' id='edit_tutorial_form'>
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
              </form><br><br>";
	} else {
	$str .= "<ul class='media-list'>
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
	    }
} // end foreach

// <!-- PAGINATION -->
if ($totalviews != 0) {
$str .= "<div class='pagination pagination-centered' >
				<ul>
    			<li>
    				<a href='#' onclick='video_pagination(1)' >&laquo;</a>
    			</li>";

for ($i=1;$i<=$totalviews;$i++) {
	if ($page == $i) {
		$str .= "<li class='active' >
	  					<a href='#' onclick='video_pagination($i)' >$i</a>
	  				</li>";
	}	else {
		$str .= "<li>
							<a href='#' onclick='video_pagination($i)' >$i</a>
						</li>";
	}

} // end loop

$str .= "<li>
					<a href='#' onclick='video_pagination($totalviews)' >&raquo;</a>
				</li>
			</ul>
			</div>";
}

echo $str;

?>