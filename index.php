<?php
/* ************************************************************************
* AUTHOR:  Craig W. Christensen
* DATE:
* DESCRIPTION:
************************************************************************ */
// STARTING SESSION
session_start();
$_SESSION['session_id'] = session_id();

// if (!isset($_SESSION['rights'])) {
// 	$_SESSION['rights'] = 0;
// }


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
* COMMENT GOES HERE
**************************************************************************** */
if ($action == 'home') {

	include $current_dir.'/views/home.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* COMMENT GOES HERE
**************************************************************************** */
else if ($action == 'logout') {

	unset($_SESSION['rights']);

	$_SESSION['error'] = 'Logout Success!';

	include $current_dir.'/views/home.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* COMMENT GOES HERE
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
* COMMENT GOES HERE
**************************************************************************** */
else if ($action == 'suggestions') {

	$suggestion_array = get_suggestions();

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
* COMMENT GOES HERE
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
* COMMENT GOES HERE
**************************************************************************** */
else if ($action == 'tutorials') {

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
* COMMENT GOES HERE
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
* COMMENT GOES HERE
**************************************************************************** */
else if ($action == 'video_link') {

	$video_id = string_check($_GET['video_id']);

	$video = array();
	$video = get_video_by_id($video_id);

	if ($video == FALSE) {
		$_SESSION['video_message'] = 'Could not retrieve video.';
		include $current_dir.'/views/video_tutorial.php';
		exit;
	} else {
		$_SESSION['video'] = $video;
		include $current_dir.'/views/video_view.php';
		exit;
	}


}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
/* ****************************************************************************
* COMMENT GOES HERE
**************************************************************************** */
else {
	// $_SESSION['error'] = 'No Action performed.';
	include $current_dir.'/views/home.php';
	exit;
}
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------








?>






