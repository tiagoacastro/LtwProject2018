<?php
  include_once('../includes/session.php');
  include_once('../database/db_story.php');
  include_once('../database/db_user.php');

  // Verify if user is logged in
  if (!isset($_SESSION['username'])) {
      die(header('Location: ../pages/login.php'));
  }

  $story_id = $_POST['story_id'];
  $story_op = $_POST['story_op'];
  $type = $_POST['type'];
  $username = $_SESSION['username'];

  if($story_op == $username)
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));

    $lastVote = lastStoryVote($username, $story_id);
    if(strcmp($type,"upvote")===0){
        if($lastVote == 1){
            remStoryVote($story_id, $username);
            remPoint($story_op);
        }
        else {
            if($lastVote == -1){
                remStoryVote($story_id, $username);
                addPoint($story_op);
            }

            addStoryVote($story_id, $username, 1);
            addPoint($story_op);
        }
    }
    else{
        if($lastVote == -1){
            remStoryVote($story_id, $username);
            addPoint($story_op);
        }
        else {
            if($lastVote == 1){
                remStoryVote($story_id, $username);
                remPoint($story_op);
            }

            addStoryVote($story_id, $username, -1);
            remPoint($story_op);
        }
    }

   header('Location: ' . $_SERVER['HTTP_REFERER']);