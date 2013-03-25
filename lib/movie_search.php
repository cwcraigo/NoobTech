<?php

session_start();

$current_dir = $_SERVER['DOCUMENT_ROOT'];

if(file_exists("$current_dir/lib/model.php")) {
  include_once $current_dir.'/lib/model.php';
} else {
	echo 'movie_search - model 404 Error!';
}

if ($_GET["tag"]) {

	$tag = $_GET["tag"];

	// if tag is empty
	if (empty($tag)) {

		// then notify user
		echo "<p class='text-error' >Please fill out form.</p>";

	} else {

		// if tag is not empty then get the videos
		$video_array = get_videos_by_tag($tag);

		// if video array is false
		if ($video_array == FALSE) {

			// then notify user
	    echo "<p class='text-error' >Could not retrieve videos.</p>";

		} else {

			foreach ($video_array as $video) {

				// else return the array
				echo "<dl>
			          <dt class='video_title' >
			              <a href='/index.php?action=video_link&video_id=$video[video_id]' >$video[video_title]</a></dt>
			          <dd class='video_desc' >$video[video_desc]</dd>
			          <dd class='video_length' >Video Length: $video[video_length]</dd>
			          <dd class='creation_date' >$video[creation_date]</dd>
				      </dl>";

			} // end for each loop

		} // end video_array check

	} // end tag check


}

?>