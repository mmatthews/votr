<?php
function showvotes($id) {

  $upvotes = get_comment_meta($id, 'upvotes', true);
  $upvotes = ($upvotes == "") ? 0 : $upvotes;
  $downvotes = get_comment_meta($id, 'downvotes', true);
  $downvotes = ($downvotes == "") ? 0 : $downvotes;
  $vote_count = $upvotes - $downvotes;

  $nonce = wp_create_nonce("vote");

  $upvote_link = admin_url('admin-ajax.php?action=vote&comment_id=' . $id . '&nonce=' . $nonce);
  $downvote_link = admin_url('admin-ajax.php?action=vote&comment_id=' . $id . '&nonce=' . $nonce);

    // Remove comment if downvotes are more than x
    $options = get_option( 'votr_settings' );

    $dump = var_dump($global);

    echo '<script>console.log("' . $dump . '")</script>';

  echo '<div class="votr-ballot">';
    echo '<button class="user_vote downvote" title="Downvote" data-nonce="' . $nonce . '" data-comment_id="' . $id . '" data-link="' . $downvote_link . '">',
    '<span class="votr-hidden">Downvote</span><img alt="" src="' . plugins_url('img/down.png', __FILE__) .'" /></button>';
    echo "<strong id='vote_counter_" . $id . "'>" . $vote_count . "</strong>";
    echo '<button class="user_vote upvote" title="Upvote" data-nonce="' . $nonce . '" data-comment_id="' . $id . '" data-link="' . $upvote_link . '">',
    '<span class="votr-hidden">Upvote</span><img alt="" src="' . plugins_url('img/up.png', __FILE__) .'" /></button>';
  echo '</div>';

}
?>