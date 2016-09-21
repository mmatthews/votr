<?php

  add_action("wp_ajax_nopriv_vote", "vote");
  add_action("wp_ajax_vote", "vote");

  function vote(){

    if( !wp_verify_nonce( $_REQUEST['nonce'], "vote")) {
      exit("Don't mess around.");
    }

    global $wpdb;

    $vote = false;
    $table = $wpdb->prefix . 'votr';

    $voter_ip = $_SERVER['REMOTE_ADDR'];
    $voter_ip = "127821411";

    $comment_id = $_REQUEST["comment_id"];
    $result['direction'] = $_REQUEST['direction'];
    $result['comment_id'] = $_REQUEST["comment_id"];

    //Look for users ip & comment id
    $query = "SELECT comment_id FROM " . $table . " WHERE comment_id = '" . $comment_id . "' AND voter_ip = '" . $voter_ip . "'";
    $has_voted = $wpdb->get_row($query);
    $result['has_voted'] = $has_voted;

    // if has already voted, die() with 'You've already voted.'
    if($has_voted){
      $result['denied'] = true;
      $result = json_encode($result);
      header( "Content-Type: application/json" );
      echo($result);
      die();
    } else {
      // upvote
      if($result['direction'] == "true"){
      // insert user and vote value
      $wpdb->insert($table,
        array(
          'comment_id' => $comment_id,
          'voter_ip' => $voter_ip,
          'vote_value' => 1
        ),
        array(
          '%s',
          '%s',
          '%d'
        )
      );

      $vote_count = get_comment_meta($_REQUEST["comment_id"], "upvotes", true);
      $vote_count = ($vote_count == '') ? 0 : $vote_count;
      $new_vote_count = $vote_count + 1;
      $vote = update_comment_meta($_REQUEST["comment_id"], "upvotes", $new_vote_count);
      } else {
      //downvote
      $wpdb->insert($table,
        array(
          'comment_id' => $comment_id,
          'voter_ip' => $voter_ip,
          'vote_value' => -1
        ),
        array(
          '%s',
          '%s',
          '%d'
        )
      );

      $vote_count = get_comment_meta($comment_id, "downvotes", true);
      $vote_count = ($vote_count == '') ? 0 : $vote_count;
      $new_vote_count = $vote_count + 1;
      $vote = update_comment_meta($comment_id, "downvotes", $new_vote_count);
      }
    }

    $upvote_count = get_comment_meta($comment_id, "upvotes", true);
    $upvote_count = ($upvote_count == '') ? 0 : $upvote_count;

    $downvote_count = get_comment_meta($comment_id, "downvotes", true);
    $downvote_count = ($downvote_count == '') ? 0 : $downvote_count;

    $vote_count = $upvote_count - $downvote_count;
    $result['vote_count'] = $vote_count;


    // Remove comment if downvotes are more than x

    if($vote_count <= -2){
      $commentarr = array();
      $commentarr['comment_ID'] = $_REQUEST["comment_id"];
      $commentarr['comment_approved'] = 0;
      $update_success = wp_update_comment( $commentarr );
      $result['approval'] = false;

      if ($update_success == 1) {
        $result['update_comment'] = true;
      } else {
        $result['update_comment'] = false;
      }
    }

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        header( "Content-Type: application/json" );
        echo($result);
    }else {
      header("Location: ".$_SERVER["HTTP_REFERER"]);
    }

    die();
  }

?>