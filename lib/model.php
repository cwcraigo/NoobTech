<?php
session_start();

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
function edit_video($video_id, $video_title, $video_url, $video_desc, $video_length) {
  global $myConn, $db, $current_dir;

  $sql = "UPDATE video
            SET video_title = ?
            ,   video_url  = ?
            ,   video_desc  = ?
            ,   video_length = ?
            ,   last_updated_by = ?
            ,   last_update_date = UTC_DATE()
            WHERE video_id = ?";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('ssssii', $video_title, $video_url, $video_desc, $video_length, $_SESSION['user_id'], $video_id);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! U-VIDEO";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if ($rowschanged == 1) {
    return TRUE;
  } else {
    return FALSE;
  }

} // end function

/* *********************
* edit_suggestion()
************************ */
function edit_suggestion($suggestion_id, $suggestion_title, $suggestion_desc) {
  global $myConn, $db, $current_dir;

  $sql = "UPDATE suggestion
            SET suggestion_title = ?
            ,   suggestion_desc  = ?
            ,   last_updated_by = ?
            ,   last_update_date = UTC_DATE()
            WHERE suggestion_id = ?";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('ssii', $suggestion_title, $suggestion_desc, $_SESSION['user_id'], $suggestion_id);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! U-SUGGESTION";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if ($rowschanged == 1) {
    return TRUE;
  } else {
    return FALSE;
  }

} // end function

/* *********************
* delete_suggestion()
************************ */
function delete_suggestion($suggestion_id) {
  global $myConn, $db, $current_dir;

  $sql = "UPDATE suggestion
            SET active_flag = 'N'
            ,   last_updated_by = ?
            ,   last_update_date = UTC_DATE()
            WHERE suggestion_id = ?";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('ii', $_SESSION['user_id'], $suggestion_id);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! D-SUGGESTION";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if ($rowschanged == 1) {
    return TRUE;
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
    $stmt->bind_param('ii', $_SESSION['user_id'], $video_id);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! U-VIDEO";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if ($rowschanged == 1) {
    return TRUE;
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
    echo "500 Error! LOGIN";
    // include_once $current_dir.'/views/errorDocs/500.php';
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
      // include_once $current_dir.'/views/errorDocs/500.php';
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
    echo "500 Error! S-VIDID";
    // include_once $current_dir.'/views/errorDocs/500.php';
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
          WHERE v.active_flag = 'Y'
          AND   v.video_title LIKE CONCAT('%',UPPER('$tag'),'%')
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
    echo "500 Error! S-VIDTAG";
    // include_once $current_dir.'/views/errorDocs/500.php';
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
function get_videos($low = 1, $high = 5) {
  global $myConn, $db, $current_dir;

  $video = array();
  $video_array = array();

  $sql = "SELECT video_id
               , video_url
               , video_title
               , video_desc
               , video_length
               , creation_date
          FROM $db.video
          WHERE active_flag = 'Y'
          LIMIT $low, $high";

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
    echo "500 Error! S-VID";
    // include_once $current_dir.'/views/errorDocs/500.php';
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
          , ?, NOW(), NULL, NULL)";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('ssi',$tutorial_title, $tutorial_desc, $_SESSION['user_id']);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! I-SUGG";
    // include_once $current_dir.'/views/errorDocs/500.php';
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
function get_suggestions($low = 1, $high = 5) {
  global $myConn, $db, $current_dir;

  $sug_array = array();
  $suggestion_array = array();

  $sql = "SELECT s.suggestion_id
               , s.suggestion_title
               , s.suggestion_desc
               , s.creation_date
               , SUM( l.like_count )
               FROM suggestion s LEFT JOIN cwcraigo_intro2techdb.like l
               ON l.id = s.suggestion_id
               AND l.like_type = 'suggestion'
               WHERE s.active_flag =  'Y'
               GROUP BY s.suggestion_id
                      , s.suggestion_title
                      , s.suggestion_desc
                      , s.creation_date
               ORDER BY s.creation_date DESC
               LIMIT 1 , 5";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($suggestion_id,$suggestion_title,$suggestion_desc,$creation_date,$like_count);
    while ($stmt->fetch()) {
      $sug_array['suggestion_id']     = $suggestion_id;
      $sug_array['suggestion_title']  = $suggestion_title;
      $sug_array['suggestion_desc']   = $suggestion_desc;
      $sug_array['creation_date']     = $creation_date;
      $sug_array['like_count']        = $like_count;
      $suggestion_array[]             = $sug_array;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! S-SUGG";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($suggestion_array)) {
    return $suggestion_array;
  } else {
    return FALSE;
  }
} // end function

/* *********************
* get_suggestion_count()
************************ */
function get_suggestion_count() {
  global $myConn, $db, $current_dir;

  $sug_array = array();
  $suggestion_array = array();

  $sql = "SELECT COUNT(*)
          FROM $db.suggestion
          WHERE active_flag = 'Y'";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($count);
    while ($stmt->fetch()) {
      $count = $count;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! SUGG-#";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($count)) {
    return $count;
  } else {
    return 0;
  }
} // end function

/* *********************
* get_video_count()
************************ */
function get_video_count() {
  global $myConn, $db, $current_dir;

  $sql = "SELECT COUNT(*)
          FROM $db.video
          WHERE active_flag = 'Y'";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($count);
    while ($stmt->fetch()) {
      $count = $count;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! VID-#";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($count)) {
    return $count;
  } else {
    return 0;
  }
} // end function


/* *********************
* get_featured_video()
************************ */
function get_featured_video($video_id) {

  $result = get_video_by_id($video_id);

  while ($result == FALSE){
    $video_id++;
    $result = get_video_by_id($video_id);
  }

  return $result;

} // end function


/* *********************
* get_comments()
************************ */
function get_comments($low = 1, $high = 5, $video_id) {
  global $myConn, $db, $current_dir;

  $comment_array = array();

  $sql = "SELECT c.comment_id
               , c.video_id
               , c.comment_text
               , u.email
               , c.creation_date
          FROM comment c INNER JOIN user u
          ON c.created_by = u.user_id
          WHERE c.video_id = $video_id
          LIMIT $low, $high";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($comment_id,$video_id,$comment_text,$email,$creation_date);
    while ($stmt->fetch()) {
      $comment['comment_id']    = $comment_id;
      $comment['video_id']      = $video_id;
      $comment['comment_text']  = $comment_text;
      $comment['email']         = $email;
      $comment['creation_date'] = $creation_date;
      $comment_array[]          = $comment;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! S-COMMENTS";
    include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($comment_array)) {
    return $comment_array;
  } else {
    return FALSE;
  }
} // end function

/* *********************
* get_comment_count()
************************ */
function get_comment_count($video_id) {
  global $myConn, $db, $current_dir;

  $sql = "SELECT COUNT(*)
          FROM comment
          WHERE video_id = $video_id";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($count);
    while ($stmt->fetch()) {
      $count = $count;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! COMM-#";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($count)) {
    return $count;
  } else {
    return 0;
  }
} // end function


/* *********************
* insert_comment()
************************ */
function insert_comment($comment_text, $video_id) {
  global $myConn, $db, $current_dir;

  $sql = "INSERT INTO comment
          ( comment_text
          , video_id
          , created_by)
          VALUES
          ( ?, ?, ?)";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('sii',$comment_text, $video_id, $_SESSION['user_id']);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! I-COMM";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if ($rowschanged == 1) {
    return TRUE;
  } else {
    return FALSE;
  }
} //end function


/* *********************
* insert_like()
************************ */
function insert_like($type,$id) {
  global $myConn, $db, $current_dir;

  $sql = "INSERT INTO $db.like
          ( like_type
          , id
          , created_by)
          VALUES
          ( ?, ?, ?)";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('sii',$type, $id, $_SESSION['user_id']);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! I-LIKE";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if ($rowschanged == 1) {
    return TRUE;
  } else {
    return FALSE;
  }
} //end function

/* *********************
* get_like_count()
************************ */
function get_like_count($type,$id) {
  global $myConn, $db, $current_dir;
// echo "$type,$id"; exit;
  $sql = "SELECT SUM(like_count)
          FROM $db.like
          WHERE like_type = ?
          AND id = ?";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('si',$type, $id);
    $stmt->execute();
    $stmt->bind_result($count);
    while ($stmt->fetch()) {
      $count = $count;
    }
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! LIKE-#";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if (!empty($count)) {
    return $count;
  } else {
    return 0;
  }
} // end function

/* *********************
* insert_video()
************************ */
function insert_video($video_length,$video_title,$video_desc,$video_url) {
  global $myConn, $db, $current_dir;

  $sql = "INSERT INTO video
          ( video_length
          , video_title
          , video_desc
          , video_url
          , active_flag
          , created_by
          , creation_date)
          VALUES
          ( ?
          , ?
          , ?
          , ?
          , 'Y'
          , ?
          , NOW())";

  $stmt = $myConn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('ssssi',$video_length,$video_title,$video_desc,$video_url,$_SESSION['user_id']);
    $stmt->execute();
    $rowschanged = $stmt->affected_rows;
    $stmt->close();
  } else {
    // $_SESSION['error'] = __FILE__." ".__FUNCTION__." stmt did not prepare.";
    echo "500 Error! I-VIDEO";
    // include_once $current_dir.'/views/errorDocs/500.php';
    exit;
  } //end prepared stmt

  if ($rowschanged == 1) {
    return TRUE;
  } else {
    return FALSE;
  }
} //end function

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




INSERT INTO video
( video_length
, video_title
, video_desc
, video_url
, active_flag
, created_by
, creation_date)
VALUES
( '11:51'
, 'Brian cyberDuck'
, 'Brian'
, '../movies/cyberDuck(revised).mov'
, 'Y'
, 3
, NOW()),
( '09:19'
, 'Brian outlook2010toBYUI'
, 'Brian'
, '../movies/outlook2010toBYUI.mov'
, 'Y'
, 3
, NOW()),
( '03:26'
, 'Brian xCodeDownload'
, 'Brian'
, '../movies/xCodeDownload(revised).mov'
, 'Y'
, 3
, NOW()),
( '10:34'
, 'Brian cyberDuck'
, 'Brian'
, '../movies/BYUIwirelessPrintingMac.mov'
, 'Y'
, 3
, NOW());





*/

?>