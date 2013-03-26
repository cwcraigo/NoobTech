<?php
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

?>

<?php require_once $current_dir.'/modules/header.php'; ?>

    <!-- LEFT COL (form/videos) -->
    <div class="span3">

      <div class="page-header"> <h3>Add Suggestion</h3> </div>

      <form method="post" action=".">
          <label class="control-label" for="tutorial_title">Tutorial Title: </label>
          <input class="input-block-level" type="text" name="tutorial_title" id="tutorial_title" size="20" value="<?php echo $t_title; ?>" required /><br />
          <label class="control-label" for="tutorial_desc" >Tutorial Description: </label>
          <textarea class="input-block-level" type="text" name="tutorial_desc" id="tutorial_desc" rows="5" required><?php echo $t_desc; ?></textarea><br />
          <input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit" />
          <input type="hidden" name="action" value="tutorial_form" />
      </form>

    </div>
    <!-- END LEFT COL -->

    <!-- RIGHT COL (suggestions/comments) -->
    <div class="span9" >

      <div class="page-header"><h3>Tutorial Suggestions</h3></div>

      <p class='text-error' > <?php if($error){ echo $error; unset($error); } ?> </p>

      <dl>
      <?php
      foreach ($suggestion_array as $suggestion) {

        echo "<dt class='suggestion_title' >$suggestion[suggestion_title]</dt>
              <dd class='suggestion_desc' >$suggestion[suggestion_desc]</dd>
              <dd class='suggestion_info'>
              Created on ".date("M j, Y g:ia", strtotime($suggestion['creation_date']))."</dd>";
      }
      unset($suggestion_array);
      ?>
      </dl>
</div>
<!-- END RIGHT COL -->

<?php require_once $current_dir.'/modules/footer.php'; ?>