<?php

  add_action("wp_ajax_nopriv_vote", "vote");
  add_action("wp_ajax_vote", "vote");

  function vote(){

    if( !wp_verify_nonce( $_REQUEST['nonce'], "vote")) {
      exit("No naughty business please");
    }
    global $wpdb;

    $vote = false;
    //$min_votes = -2;
    $table = $wpdb->prefix . 'votr';

    $voter_ip = $_SERVER['REMOTE_ADDR'];
    $voter_ip = "12345679";

    $comment_id = $_REQUEST["comment_id"];
    $result['direction'] = $_REQUEST['direction'];
    $result['comment_id'] = $_REQUEST["comment_id"];


    //Look for users ip & comment id
    $query = "SELECT comment_id FROM " . $table . " WHERE comment_id = '" . $comment_id . "' AND voter_ip = '" . $voter_ip . "'";
    $has_voted = $wpdb->get_row($query);
    $result['has_voted'] = $has_voted;

    // if has already voted, die() with 'You've already voted.'
    if($has_voted){
      $result['vote_error'] = "You've already voted";
      $result = json_encode($result);
      header( "Content-Type: application/json" );
      echo($result);
      die();
    } else {


      // if it is an upvote
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
        // if it is a downvote
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



    /* ########  IF BELOW 10, UNAPPROVE COMMENT */

/*
    if($vote_count <= $min_votes){
      $commentarr = array();
      $commentarr['comment_ID'] = $comment_id;
      $commentarr['comment_approved'] = 0;
      $unapproved = wp_update_comment( $commentarr );

      if($unapproved){
        $result['unapproved'] = true;
      } else {
        $result['unapproved'] = false;
      }
      //header('Last-Modified: '.date('D, d M Y H:i:s', time()).' GMT');
    }
*/


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