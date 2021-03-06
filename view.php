<?php
function showvotes($id) {

  $upvotes = get_comment_meta($id, 'upvotes', true);
  $upvotes = ($upvotes == "") ? 0 : $upvotes;
  $downvotes = get_comment_meta($id, 'downvotes', true);
  $downvotes = ($downvotes == "") ? 0 : $downvotes;
  $vote_count = $upvotes - $downvotes;

  $user_id = get_current_user_id();

  $nonce = wp_create_nonce("vote");

  $upvote_link = admin_url('admin-ajax.php?action=vote&comment_id=' . $id . '&nonce=' . $nonce);
  $downvote_link = admin_url('admin-ajax.php?action=vote&comment_id=' . $id . '&nonce=' . $nonce);

  echo '<div class="votr-ballot">';
    echo '<button class="user_vote downvote" title="Downvote" data-nonce="' . $nonce . '" data-ui="' . $user_id . '" data-comment_id="' . $id . '" data-link="' . $downvote_link . '">',
    '<span class="votr-hidden">Downvote</span><img alt="" src="' . plugins_url('img/down.png', __FILE__) .'" /></button>';
    echo "<strong id='vote_counter_" . $id . "'>" . $vote_count . "</strong>";
    echo '<button class="user_vote upvote" title="Upvote" data-nonce="' . $nonce . '" data-ui="' . $user_id . '" data-comment_id="' . $id . '" data-link="' . $upvote_link . '">',
    '<span class="votr-hidden">Upvote</span><img alt="" src="' . plugins_url('img/up.png', __FILE__) .'" /></button>';
  echo '</div>';

}
?>