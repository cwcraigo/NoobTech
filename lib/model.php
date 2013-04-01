<?php

/* ************************************************************************
* AUTHOR:  Craig W. Christensen
* DATE:
* DESCRIPTION:
************************************************************************ */

if(file_exists("$current_dir/lib/DBconn.php")) {
  include_once $current_dir.'/lib/DBconn.php';
} else {
  echo 'MODEL - 404 Error!';
  include_once $current_dir.'/views/errorDocs/404.php';
  exit;
}

/* *********************
* edit_video()
************************ */
function edit_video($video_id, $video_title, $video_desc, $video_length) {
  global $myConn, $db, $current_dir;

  $sql = "UPDATE video
            SET video_title = ?
            ,   video_desc  = ?
            ,   video_length = ?
            ,   last_updated_by = ?
            ,   last_update_date = UTC_DATE()
            WHERE video_id = ?";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('sssii', $video_title, $video_desc, $video_length, $video_id);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! U-VIDEO";
    include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if ($rowschanged == 1) {
    return 0;
  } else {
    return FALSE;
  }

} // end function

/* *********************
* delete_video()
************************ */
function delete_video($video_id) {
  global $myConn, $db, $current_dir;

  $sql = "UPDATE video
            SET active_flag = 'N'
            ,   last_updated_by = ?
            ,   last_update_date = UTC_DATE()
            WHERE video_id = ?";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('sssii', $video_title, $video_desc, $video_length, $video_id);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! U-VIDEO";
    include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if ($rowschanged == 1) {
    return 0;
  } else {
    return FALSE;
  }

} // end function


/* *********************
* login()
************************ */
function login($email, $password) {
  global $myConn, $db, $current_dir;

  $new_password = encrypt($email, $password);
  // $new_password = $password;
  // var_dump($new_password); exit;

  $sql = "SELECT user_rights, user_id
           FROM  user
           WHERE email = ?
           AND   password = ?";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('ss', $email, $new_password);
    $stmt->bind_result($rights,$id);
    $stmt->execute();
    if($stmt->fetch()) {
      $user_info['user_rights'] = $rights;
      $user_info['user_id'] = $id;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    // echo "500 Error!";
    include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($user_info)) {
    return $user_info;
  } else {

    $sql = "INSERT INTO user
          ( user_id
          , email
          , password
          , user_rights
          , created_by
          , creation_date
          , last_updated_by
          , last_update_date )
          VALUES
          ( NULL, ?, ?, 0, 1, NOW(), 1, NOW())";

    $stmt = $myConn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param('ss', $email, $new_password);
      $stmt->execute();
      $rowschanged = $stmt->affected_rows;
      $stmt->close();
    } else {
      // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
      echo "500 Error! I-USER";
      include_once $current_dir.'/views/errorDocs/500.php';
      exit;
    } //end prepared stmt

    if ($rowschanged == 1) {
      return 0;
    } else {
      return FALSE;
    }
  }

} // end function

/* *********************
* get_video_by_id()
************************ */
function get_video_by_id($video_id) {
  global $myConn, $db, $current_dir;

  $video = array();

  $sql = "SELECT video_id
               , video_url
               , video_title
               , video_desc
               , video_length
               , creation_date
          FROM video
          WHERE video_id = $video_id";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($video_id,$video_url,$video_title,$video_desc,$video_length,$creation_date);
    while ($stmt->fetch()) {
      $video['video_id']      = $video_id;
      $video['video_url']     = $video_url;
      $video['video_title']   = $video_title;
      $video['video_desc']    = $video_desc;
      $video['video_length']  = $video_length;
      $video['creation_date'] = $creation_date;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    // echo "500 Error!";
    include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($video)) {
    return $video;
  } else {
    return FALSE;
  }
} // end function


/* *********************
* get_videos_by_tag()
************************ */
function get_videos_by_tag($tag) {
  global $myConn, $db, $current_dir;

  $video = array();
  $video_array = array();

  $sql = "SELECT v.video_id
               , v.video_url
               , v.video_title
               , v.video_desc
               , v.video_length
               , v.creation_date
          FROM video v
          WHERE v.video_title LIKE CONCAT('%',UPPER('$tag'),'%')
          OR    v.video_desc  LIKE CONCAT('%',UPPER('$tag'),'%')";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($video_id,$video_url,$video_title,$video_desc,$video_length,$creation_date);
    while ($stmt->fetch()) {
      $video['video_id']      = $video_id;
      $video['video_url']     = $video_url;
      $video['video_title']   = $video_title;
      $video['video_desc']    = $video_desc;
      $video['video_length']  = $video_length;
      $video['creation_date'] = $creation_date;
      $video_array[]          = $video;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    // echo "500 Error!";
    include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($video_array)) {
    return $video_array;
  } else {
    return FALSE;
  }
} // end function


/* *********************
* get_videos()
************************ */
function get_videos() {
  global $myConn, $db, $current_dir;

  $video = array();
  $video_array = array();

  $sql = "SELECT video_id
               , video_url
               , video_title
               , video_desc
               , video_length
               , creation_date
          FROM $db.video";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($video_id,$video_url,$video_title,$video_desc,$video_length,$creation_date);
    while ($stmt->fetch()) {
      $video['video_id']      = $video_id;
      $video['video_url']     = $video_url;
      $video['video_title']   = $video_title;
      $video['video_desc']    = $video_desc;
      $video['video_length']  = $video_length;
      $video['creation_date'] = $creation_date;
      $video_array[]          = $video;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    // echo "500 Error!";
    include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($video_array)) {
    return $video_array;
  } else {
    return FALSE;
  }
} // end function




/* *********************
* insert_suggestion()
************************ */
function insert_suggestion($tutorial_title, $tutorial_desc) {
  global $myConn, $db, $current_dir;

  $sql = "INSERT INTO $db.suggestion
          ( suggestion_title
          , suggestion_desc
          , active_flag
          , created_by
          , creation_date
          , last_updated_by
          , last_update_date )
          VALUES
          ( ?
          , ?
          , 'Y'
          , 1, NOW(), 1, NOW())";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('ss',$tutorial_title, $tutorial_desc);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    // echo "500 Error!";
    include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if ($rowschanged == 1) {
    return TRUE;
  } else {
    return FALSE;
  }
} //end function


/* *********************
* get_suggestions()
************************ */
function get_suggestions() {
  global $myConn, $db, $current_dir;

  $sug_array = array();
  $suggestion_array = array();

  $sql = "SELECT suggestion_id
               , suggestion_title
               , suggestion_desc
               , creation_date
          FROM $db.suggestion
          ORDER BY creation_date DESC";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($suggestion_id,$suggestion_title,$suggestion_desc,$creation_date);
    while ($stmt->fetch()) {
      $sug_array['suggestion_id']     = $suggestion_id;
      $sug_array['suggestion_title']  = $suggestion_title;
      $sug_array['suggestion_desc']   = $suggestion_desc;
      $sug_array['creation_date']     = $creation_date;
      $suggestion_array[]             = $sug_array;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    // echo "500 Error!";
    include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($suggestion_array)) {
    return $suggestion_array;
  } else {
    return FALSE;
  }
} // end function


/* *********************
* encrypt()
************************ */
function encrypt($email, $password) {
  $salt = hash('sha256', 'kimclaireandi' . strtolower($email));
  $hash = $salt . $password;
  for ($i=0;$i<1000;$i++) {
    $hash = hash('sha256', $hash);
  }
  $hash = $salt . $hash;
  return $hash;
}



/*

$return['status'] = ('success' | 'failed')
$return['error']['message'] = $e->getMessage();





*/









?>