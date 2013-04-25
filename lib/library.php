<?php
/* ************************************************************************
* AUTHOR:  Craig W. Christensen
* DATE:
* DESCRIPTION: Library functions (Ones that do not access the db).
************************************************************************ */

// Validate for strings function.
function string_check($str) {
  $str = filter_var($str, FILTER_SANITIZE_STRING);
  return $str;
}

function checkString($text) {
  return stripcslashes(htmlspecialchars($text,ENT_QUOTES));
}

// Validate for numbers function.
function number_check($num) {
  // Sanitize or takes everything but a number out.
  $num = filter_var($num, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  // Validate or makes sure it is a number.
  $num = filter_var($num, FILTER_VALIDATE_FLOAT);
  return $num;
}

// Validate email address function
function email_check($email) {
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $email = filter_var($email, FILTER_VALIDATE_EMAIL);
  return $email;
}

/**********************************************************
 * Temporary image library
 *********************************************************/

// Upload file function
function upload_file($name) {
 global $image_dir;
 global $image_dir_path;

 if (isset($_FILES[$name])) {
  $filename = strtolower($_FILES[$name]['name']);
  if (empty($filename)) {
   return;
  }
  $source = $_FILES[$name]['tmp_name'];
  $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;
  move_uploaded_file($source, $target);

  // create a second, resized version of the image -- if desired
  process_image($image_dir_path, $filename);
  $file = $image_dir . $filename;

  return $file;
 }
}

// Process image, create a newly resized version
function process_image($dir, $filename) {
  global $image_dir;
  global $description;

 // Set up the variables
 // $dir = $dir . DIRECTORY_SEPARATOR; // where the image is stored
 $i = strrpos($filename, '.'); // finds where the file name separates from extension
 $image_name = substr($filename, 0, $i); // gets the file name
 $ext = substr($filename, $i); // gets the extension

 // Set up the read path
 $image_path = $dir . DIRECTORY_SEPARATOR . $filename;

 $image_path2 = $image_dir . DIRECTORY_SEPARATOR . $filename;
 $image_path_tn2 = $image_dir . DIRECTORY_SEPARATOR . $image_name . '_tn' . $ext;

 // Set up the write path of the resized images with a new append value
 $image_path_tn = $dir . DIRECTORY_SEPARATOR . $image_name . '_tn' . $ext;
 $image_tn = $image_name . '_tn' . $ext;
 $image = $image_name . $ext;

 $add_image = pictureInsert($image_path2, $image_path_tn2, $description);
 if (!$add_image) {
     $_SESSION['message'] = 'Sorry the image could not be uploaded.';
 }

 // Resize the image to desired size or don't call if not needed
 // RE: Create a thumbnail image that's a maximum of 100x100 pixels
 resize_image($image_path, $image_path_tn, 400, 300);
}

/*********************************************
 * Resize image function -- e.g. 400x300 - Store in library
 **********************************************/

function resize_image($old_image_path, $new_image_path, $max_width, $max_height) {
 // Get image type
 $image_info = getimagesize($old_image_path);
 $image_type = $image_info[2];

 // Set up the function names
 switch ($image_type) {
  case IMAGETYPE_JPEG:
   $image_from_file = 'imagecreatefromjpeg';
   $image_to_file = 'imagejpeg';
   break;
  case IMAGETYPE_GIF:
   $image_from_file = 'imagecreatefromgif';
   $image_to_file = 'imagegif';
   break;
  case IMAGETYPE_PNG:
   $image_from_file = 'imagecreatefrompng';
   $image_to_file = 'imagepng';
   break;
  default:
   echo 'File must be a JPEG, GIF, or PNG image.';
   exit;
 }

 // Get the old image and its height and width
 $old_image = $image_from_file($old_image_path);
 $old_width = imagesx($old_image);
 $old_height = imagesy($old_image);

 // Calculate height and width ratios
 $width_ratio = $old_width / $max_width;
 $height_ratio = $old_height / $max_height;

 // If image is larger than specified ratio, create the new image
 if ($width_ratio > 1 || $height_ratio > 1) {

  // Calculate height and width for the new image
  $ratio = max($width_ratio, $height_ratio);
  $new_height = round($old_height / $ratio);
  $new_width = round($old_width / $ratio);

  // Create the new image
  $new_image = imagecreatetruecolor($new_width, $new_height);

  // Set transparency according to image type
  if ($image_type == IMAGETYPE_GIF) {
   $alpha = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
   imagecolortransparent($new_image, $alpha);
  }
  if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
   imagealphablending($new_image, false);
   imagesavealpha($new_image, true);
  }

  // Copy old image to new image - this resizes the image
  $new_x = 0;
  $new_y = 0;
  $old_x = 0;
  $old_y = 0;
  imagecopyresampled($new_image, $old_image, $new_x, $new_y, $old_x, $old_y, $new_width, $new_height, $old_width, $old_height);

  // Write the new image to a new file
  $image_to_file($new_image, $new_image_path);

  // Free any memory associated with the new image
  imagedestroy($new_image);
 } else {
  // Write the old image to a new file
  $image_to_file($old_image, $new_image_path);
 }
 // Free any memory associated with the old image
 imagedestroy($old_image);
}

?>