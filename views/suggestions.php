<?php
/* * ******************************************
 * Suggestions Form View
 * ****************************************** */

if ($_SESSION['suggestion_message']) {
    $error = $_SESSION['suggestion_message'];
    unset($_SESSION['suggestion_message']);
}

if ($_SESSION['suggestion_array']) {
    $suggestion_array = array();
    $suggestion_array = $_SESSION['suggestion_array'];
    unset($_SESSION['suggestion_array']);
}
/*
 * PAGINATION
 */

$per_page = 5;

$result = $iUserConn->query("SELECT id FROM $db.suggestion");

$total = $result->num_rows;

$totalviews = ceil($total / $per_page);

$page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

$start = ($page - 1) * $per_page;

$showResults = $iUserConn->query("SELECT *
                                  FROM $db.suggestion
                                  ORDER BY id DESC
                                  LIMIT $start, $per_page");

?>

<?php require_once $current_dir.'/modules/header.php'; ?>

<div id="content">
    <h1>Suggestions</h1>

    <?php

    foreach ($suggestion_array as $suggestion) {
        echo "
         <ul class='suggestionbox' >
             <li class='suggestion_title' >$suggestion[suggestion_title]</li>
             <li class='suggestion_desc' >$suggestion[suggestion_desc]</li>
             <li class='suggestion_info'>
             Created on ".date("M j, Y g:ia", strtotime($suggestion['creation_date']))."</li>
         </ul>";
    }

    if ($_SESSION['loggedin']) {
        require $_SERVER['DOCUMENT_ROOT'] . '/CIT336/suggestion/suggestion_form.php';
    }
    while ($comment = $showResults->fetch_assoc()) {
        echo "
        <ul class='commentbox' >
            <li class='comment_title' >$comment[title]</li>
            <li class='comment' >$comment[comment]</li>
            <li class='comment_name' >$comment[name] on ".date("M j, Y g:ia", strtotime($comment['post_date']))."</li>
        </ul>";
    }

    echo '<div class="pagination_div">
            <ol id="pagination" >
                <li>Pages: </li>';
    if ($totalviews >= 1 && $page <= $total) {
        for ($x = 1; $x <= $totalviews; $x++) {
            echo ($page == $x) ? "<li>
            <a href='/routerconfig/views/suggestion.php/?page=$x'> $x </a>
            </li>" :
            "<li>
            <a href='/routerconfig/views/suggestion.php/?page=$x'> $x </a>
            </li>";
        }
    } else {
        echo "<li>Sorry, no more to display.</li>";
    }
    echo '</ol></div>';
    ?>

</div>

<?php require_once $current_dir.'/modules/footer.php'; ?>