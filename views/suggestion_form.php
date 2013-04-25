<?php
session_start();
/* * ******************************************
 * Suggestion Form View
 * ****************************************** */

if (isset($_SESSION['suggestion_message'])) {
    $error = $_SESSION['suggestion_message'];
    unset($_SESSION['suggestion_message']);
}
if (isset($_SESSION['suggestion_array'])) {
    $suggestion_array = $_SESSION['suggestion_array'];
    unset($_SESSION['suggestion_array']);
}
if (isset($_SESSION['s_array'])) {
    $s_array = $_SESSION['s_array'];
    $t_title = $s_array[0];
    $t_desc = $s_array[1];
    unset($_SESSION['s_array']);
    unset($s_array);
}

if (!empty($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
}

$page_heading = 'SUGGESTIONS';
$page_icon = 'icon-th-list';

$totalviews = $_SESSION['totalviews'];
$total = $_SESSION['total_suggestions'];

$type = 'suggestion';

?>

<?php require_once $current_dir.'/modules/header.php'; ?>

    <!-- RIGHT COL (suggestions/comments) -->
    <div class="span10 offset1" >

      <div class="page-header"><h2>Tutorial Suggestions</h2></div>

      <?php if($error){ echo "<p class='alert alert-error' >$error</p>"; unset($error); } ?>

      <div id="suggestion_div">
      <?php
      foreach ($suggestion_array as $suggestion) {
        if ($_SESSION['rights'] == 1) {
        echo "<form action='.' method='post' class='form-horizontal' id='edit_suggestion_form'>
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
              echo "<button class='btn btn-info' onclick='like(&quot;suggestion&quot;,$suggestion[suggestion_id])' type='button' ><i class='icon-thumbs-up icon-white'></i> LIKE <span id='like_count'>$suggestion[like_count]</span> </button>";
        } else {
          echo "<dl>
                  <dt class='suggestion_title' >$suggestion[suggestion_title]</dt>
                  <dd class='suggestion_desc' >$suggestion[suggestion_desc]</dd>
                  <dd class='suggestion_info'>
                    Created on ".date("M j, Y g:ia", strtotime($suggestion['creation_date']))."</dd>
                </dl>";
          echo "<button class='btn btn-info' onclick='like(&quot;suggestion&quot;,$suggestion[suggestion_id])' type='button' ><i class='icon-thumbs-up icon-white'></i> LIKE <span id='like_count'>$suggestion[like_count]</span> </button>";
        }


      } // end loop
      unset($suggestion_array);
      if ($totalviews != 0) {
      ?>


<!-- PAGINATION -->
    <div class='pagination pagination-centered' >
    <ul>
      <li><a href="#" onclick="suggestion_pagination(1)" >&laquo;</a></li>
    <?php
      for ($i=1;$i<=$totalviews;$i++) {
        if ($i == 1) {
          echo "<li class='active' ><a href='#' onclick='suggestion_pagination($i)' >$i</a></li>";
        } else {
          echo "<li><a href='#' onclick='suggestion_pagination($i)' >$i</a></li>";
        }
      } // end loop
    ?>
      <li><a href="#" onclick="suggestion_pagination(<?php echo $totalviews; ?>)" >&raquo;</a></li>
    </ul>
  </div>
<?php } ?>
  </div>
  <!-- END SUGGESTION DIV -->

<!-- ADD SUGGESTION FORM -->

      <div class="page-header"> <h2>Add Suggestion</h2> </div>
<?php if(isset($_SESSION['rights'])) { ?>
      <form method="post" action=".">
          <label class="control-label" for="tutorial_title">Tutorial Title: </label>
          <input class="input-block-level" type="text" name="tutorial_title" id="tutorial_title" size="20" value="<?php echo $t_title; ?>" required /><br />
          <label class="control-label" for="tutorial_desc" >Tutorial Description: </label>
          <textarea class="input-block-level" type="text" name="tutorial_desc" id="tutorial_desc" rows="5" required><?php echo $t_desc; ?></textarea><br />
          <input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit" />
          <input type="hidden" name="action" value="tutorial_form" />
      </form>
<?php } else {

  echo "<p class='alert alert-info' >To add a suggestion, please login with a valid BYUI email address.</p>";
}
  ?>
</div>
<!-- END RIGHT COL -->

<?php require_once $current_dir.'/modules/footer.php'; ?>