<?php

session_start();

$current_dir = $_SERVER['DOCUMENT_ROOT'];

if(file_exists("$current_dir/lib/model.php")) {
  include_once $current_dir.'/lib/model.php';
} else {
	echo 'movie_search - model 404 Error!';
}

$per_page = 5;

$total = get_suggestion_count();

$totalviews = ceil($total / $per_page);

$page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

$start = ($page - 1) * $per_page;

$suggestion_array = get_suggestions($start, $per_page);

$str = "";

foreach ($suggestion_array as $suggestion) {
	if ($_SESSION['rights'] == 1) {
		$str .= "<form action='.' method='post' class='form-horizontal' id='edit_suggestion_form'>
	        	<div class='control-group'>
	          	<label class='control-label' for='suggestion_title' >Title:</label>
	          	<div class='controls'>
	            	<input class='input-xlarge' type='text' name='suggestion_title' value='$suggestion[suggestion_title]' />
	          	</div>
	        	</div>
		        <div class='control-group'>
		          <label class='control-label' for='suggestion_desc' >Suggestion:</label>
		          <div class='controls'>
		            <input class='input-block-level' type='text' name='suggestion_desc' value='$suggestion[suggestion_desc]' />
		          </div>
		        </div>
		        <div class='control-group'>
		          <label class='control-label' for='creation_date' >Creation Date:</label>
		          <div class='controls'>
		            <input class='input-medium' type='text' name='creation_date' value='".date("M j, Y g:ia", strtotime($suggestion['creation_date']))."' disabled />
		            <input type='hidden' name='action' value='edit_suggestion' />
		            <input type='hidden' name='suggestion_id' value='$suggestion[suggestion_id]' />
		            <br><br>
		            <button type='submit' class='btn btn-danger btn-mini' ><i class='icon-pencil icon-white'></i>Edit</button>
		            <a class='btn btn-danger btn-mini' href='/index.php?action=delete_suggestion&suggestion_id=$suggestion[suggestion_id]' ><i class='icon-remove-circle icon-white'></i>Delete</a>
		          </div>
		        </div>
		      </form>";
		$str .= "<button class='btn btn-info' onclick='like(&quot;suggestion&quot;,$suggestion[suggestion_id])' type='button' ><i class='icon-thumbs-up icon-white'></i> LIKE <span id='like_count'>$suggestion[like_count]</span> </button>";
	} else {
		$str .= "<dl>
		        <dt class='suggestion_title' >$suggestion[suggestion_title]</dt>
		        <dd class='suggestion_desc' >$suggestion[suggestion_desc]</dd>
		        <dd class='suggestion_info'>
		          Created on ".date("M j, Y g:ia", strtotime($suggestion['creation_date']))."</dd>
		      </dl>";
		$str .= "<button class='btn btn-info' onclick='like(&quot;suggestion&quot;,$suggestion[suggestion_id])' type='button' ><i class='icon-thumbs-up icon-white'></i> LIKE <span id='like_count'>$suggestion[like_count]</span> </button>";
  }

} // end foreach

// <!-- PAGINATION -->
if ($totalviews != 0) {
$str .= "<div class='pagination pagination-centered' >
				<ul>
    			<li>
    				<a href='#' onclick='suggestion_pagination(1)' >&laquo;</a>
    			</li>";

for ($i=1;$i<=$totalviews;$i++) {
	if ($page == $i) {
		$str .= "<li class='active' >
	  					<a href='#' onclick='suggestion_pagination($i)' >$i</a>
	  				</li>";
	}	else {
		$str .= "<li>
							<a href='#' onclick='suggestion_pagination($i)' >$i</a>
						</li>";
	}

} // end loop

$str .= "<li>
					<a href='#' onclick='suggestion_pagination($totalviews)' >&raquo;</a>
				</li>
			</ul>
			</div>";
}

echo $str;

?>