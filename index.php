<?php
/* ************************************************************************
* AUTHOR:  Craig W. Christensen
* DATE:
* DESCRIPTION:
************************************************************************ */
// STARTING SESSION
session_start();
$_SESSION['session_id'] = session_id();

// GLOBAL DECLARATIONS
$current_dir = $_SERVER['DOCUMENT_ROOT']; // gets current directory name

// ----------------------------------------------------------------------------
// INCLUDE MODEL AND CLASS
if(file_exists("$current_dir/lib/model.php")) {
  include_once $current_dir.'/lib/model.php';
} else {
	echo 'Controller - model 404 Error!';
  include_once $current_dir.'/views/errorDocs/404.php';
  exit;
}
if(file_exists("$current_dir/lib/library.php")) {
	include_once $current_dir.'/lib/library.php';
} else {
	echo 'Controller - library 404 Error!';
  include_once $current_dir.'/views/errorDocs/404.php';
  exit;
}

// ----------------------------------------------------------------------------
/* ****************************************************************************
* GET ACTION POST/GET
**************************************************************************** */
if (!empty($_POST['action'])) {
	$action = $_POST['action'];
} else if (!empty($_GET['action'])) {
	$action = $_GET['action'];
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* HOME BUTTON
**************************************************************************** */
if ($action == 'home') {

	include $current_dir.'/views/home.php';
	exit;
}

// ----------------------------------------------------------------------------
/* ****************************************************************************
* EDIT SUGGESTION INFO
**************************************************************************** */
else if ($action == 'edit_suggestion') {

	$suggestion_id = string_check($_POST['suggestion_id']);
	$suggestion_title = string_check($_POST['suggestion_title']);
	$suggestion_desc = string_check($_POST['suggestion_desc']);

	$result = edit_suggestion($suggestion_id, $suggestion_title, $suggestion_desc);

	if ($result == TRUE) {
		$_SESSION['error'] = 'Edit Success!';
	} else {
		$_SESSION['error'] = 'Edit Failed. Please try again.';
	}

	$suggestion_array = array();
	$suggestion_array = get_suggestions();

	if ($suggestion_array == FALSE) {
		$_SESSION['error'] = 'Could not retrieve videos.';
	} else {
		$_SESSION['suggestion_array'] = $suggestion_array;
	}
// var_dump($result); exit;

	include $current_dir.'/views/suggestion_form.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------


// ----------------------------------------------------------------------------
/* ****************************************************************************
* COMMENT GOES HERE
**************************************************************************** */
else if ($action == 'comment_form') {

	$video_id = string_check($_POST['video_id']);
	$comment_text = string_check($_POST['comment_text']);

	$result = insert_comment($comment_text, $video_id);

	if ($result == FALSE) {
		$_SESSION['error'] = 'Could not insert comment. Please try again.';
	} else {
		$_SESSION['error'] = 'Insert Success!';
	}

	$video = array();
	$comment_array = array();
	$video = get_video_by_id($video_id);
	$comment_array = get_comments(1,5,$video_id);
	$_SESSION['video'] = $video;
	$_SESSION['comment_array'] = $comment_array;
	$like_count = get_like_count('video',$video_id);
	$_SESSION['like_count'] = $like_count;

	$per_page = 5;

	$total = get_comment_count($video_id);

	$totalviews = ceil($total / $per_page);

	$_SESSION['total_videos'] = $total;
	$_SESSION['totalviews'] = $totalviews;

	include $current_dir.'/views/video_view.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------



// ----------------------------------------------------------------------------
/* ****************************************************************************
* COMMENT GOES HERE
**************************************************************************** */
else if ($action == 'delete_suggestion') {

	$suggestion_id = string_check($_GET['suggestion_id']);

	$result = delete_suggestion($suggestion_id);

	if ($result == TRUE) {
		$_SESSION['error'] = 'Delete Success!';
	} else {
		$_SESSION['error'] = 'Delete Failed. Please try again.';
	}

	$suggestion_array = array();
	$suggestion_array = get_suggestions();

	if ($suggestion_array == FALSE) {
		$_SESSION['error'] = 'Could not retrieve videos.';
	} else {
		$_SESSION['suggestion_array'] = $suggestion_array;
	}

	include $current_dir.'/views/suggestion_form.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------


// ----------------------------------------------------------------------------
/* ****************************************************************************
* COMMENT GOES HERE
**************************************************************************** */
else if ($action == 'delete_video') {

	$video_id = string_check($_GET['video_id']);

	$result = delete_video($video_id);

	if ($result == TRUE) {
		$_SESSION['error'] = 'Delete Success!';
	} else {
		$_SESSION['error'] = 'Delete Failed. Please try again.';
	}

	$video_array = array();
	$video_array = get_videos();

	if ($video_array == FALSE) {
		$_SESSION['video_message'] = 'Could not retrieve videos.';
	} else {
		$_SESSION['video_array'] = $video_array;
	}

	include $current_dir.'/views/video_tutorial.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* EDIT VIDEO INFO
**************************************************************************** */
else if ($action == 'edit_video') {

	$video_id = $_POST['video_id'];
	$video_title = $_POST['video_title'];
	$video_url = $_POST['video_url'];
	$video_desc = $_POST['video_desc'];
	$video_length = $_POST['video_length'];

	$result = edit_video($video_id, $video_title, $video_url, $video_desc, $video_length);

	if ($result == TRUE) {
		$_SESSION['error'] = 'Edit Success!';
	} else {
		$_SESSION['error'] = 'Edit Failed. Please try again.';
	}

	$video_array = array();
	$video_array = get_videos();

	if ($video_array == FALSE) {
		$_SESSION['video_message'] = 'Could not retrieve videos.';
	} else {
		$_SESSION['video_array'] = $video_array;
	}
// var_dump($result); exit;

	include $current_dir.'/views/video_tutorial.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* LOGOUT
**************************************************************************** */
else if ($action == 'logout') {

	unset($_SESSION['rights']);
	$_SESSION['user_id'] = -1;

	$_SESSION['error'] = 'Logout Success!';

	include $current_dir.'/views/home.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* LOGIN
**************************************************************************** */
else if ($action == 'login') {

	$email = string_check($_POST['email']);
	$password = string_check($_POST['password']);

	$user_rights = login($email, $password);

	if ($user_rights === FALSE) {
		$_SESSION['rights'] = 0;
		$_SESSION['user_id'] = -1;
		$_SESSION['error'] = 'Login Failed. Please try again. OR Please enter valid byui email address.';
	} else {
		$_SESSION['rights'] = $user_rights['user_rights'];
		$_SESSION['user_id'] = $user_rights['user_id'];
		$_SESSION['error'] = 'Login Success!';
	}

	include $current_dir.'/views/home.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* SUGGESTION LINK
**************************************************************************** */
else if ($action == 'suggestions') {

	$suggestion_array = get_suggestions();
	$per_page = 5;

	$total = get_suggestion_count();

	$totalviews = ceil($total / $per_page);

	$_SESSION['total_suggestions'] = $total;
	$_SESSION['totalviews'] = $totalviews;

	if ($suggestion_array == FALSE) {
		$_SESSION['suggestion_message'] = 'Could not retrieve suggestions.';
	} else {
		$_SESSION['suggestion_array'] = $suggestion_array;
	}

	include $current_dir.'/views/suggestion_form.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* VIDEO LINK
**************************************************************************** */
else if ($action == 'tutorial_form') {

	$tutorial_title = string_check($_POST['tutorial_title']);
	$tutorial_desc = string_check($_POST['tutorial_desc']);

	$result = insert_suggestion($tutorial_title, $tutorial_desc);

	if ($result == FALSE) {
		$_SESSION['suggestion_message'] = 'Failed to add suggestion. Please try again.';
		$s_array = array($tutorial_title, $tutorial_desc);
		$_SESSION['s_array'] = $s_array;
	} else {
		$suggestion_array = get_suggestions();
		if ($suggestion_array == FALSE) {
			$_SESSION['suggestion_message'] = 'Suggestion added! However, could not retrieve suggestions.';
		} else {
			$_SESSION['suggestion_message'] = 'Suggestion added!';
		}
		$_SESSION['suggestion_array'] = $suggestion_array;
	}

	include $current_dir.'/views/suggestion_form.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* TUTORIAL LINK
**************************************************************************** */
else if ($action == 'tutorials') {

	$video_array = array();
	$video_array = get_videos();

	$per_page = 5;

	$total = get_video_count();

	$totalviews = ceil($total / $per_page);

	$_SESSION['total_videos'] = $total;
	$_SESSION['totalviews'] = $totalviews;

	if ($video_array == FALSE) {
		$_SESSION['video_message'] = 'Could not retrieve videos.';
	} else {
		$_SESSION['video_array'] = $video_array;
	}

	include $current_dir.'/views/video_tutorial.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* VIDEO SEARCH
**************************************************************************** */
else if ($action == 'search_videos') {

	$tag = string_check($_POST['video_tag']);

	$video_array = array();
	$video_array = get_videos_by_tag($tag);

	if ($video_array == FALSE) {
		$_SESSION['video_message'] = 'Could not retrieve videos.';
	} else {
		$_SESSION['video_array'] = $video_array;
	}

	include $current_dir.'/views/video_tutorial.php';
	exit;

}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* VIDEO LINK
**************************************************************************** */
else if ($action == 'video_link') {

	$video_id = string_check($_GET['video_id']);

	$_SESSION['video_id'] = $video_id;

	$video = array();
	$comment_array = array();
	$video = get_video_by_id($video_id);
	$comment_array = get_comments(1,5,$video_id);

	$like_count = get_like_count('video',$video_id);
	$_SESSION['like_count'] = $like_count;

	$per_page = 5;

	$total = get_comment_count($video_id);

	$totalviews = ceil($total / $per_page);

	$_SESSION['total_videos'] = $total;
	$_SESSION['totalviews'] = $totalviews;

	if ($video == FALSE && $comment_array == FALSE) {
		$_SESSION['video_message'] = 'Could not retrieve video.';
		include $current_dir.'/views/video_tutorial.php';
		exit;
	} else {
		$_SESSION['video'] = $video;
		$_SESSION['comment_array'] = $comment_array;
		include $current_dir.'/views/video_view.php';
		exit;
	}


}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* DEFAULT HOME
**************************************************************************** */
else {

	$_SESSION['user_id'] = -1;

	$total = get_video_count();

	$video_id = rand(0,$total);

	$video = get_featured_video($video_id);

	$_SESSION['featured_video'] = $video;

	include $current_dir.'/views/home.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
?>