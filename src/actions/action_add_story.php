<?php
  include_once('../includes/session.php');
  include_once('../includes/image.php');
  include_once('../database/db_story.php');
  include_once('../database/db_image.php');
  include_once('../database/db_user.php');

  // Verify if user is logged in
  if (!isset($_SESSION['username'])) {
      die(header('Location: ../pages/login.php'));
  }

  if ($_SESSION['csrf'] !== $_POST['csrf']) {
      die(header('Location: ' . $_SERVER['HTTP_REFERER']));
  }

  $story_title = $_POST['story_title'];

  $channel_id = $_POST['channel_id'];
  
  $user_id = getUserId($_SESSION['username']);

  if (isset($_POST['tags'])) {
      $tags = $_POST['tags'];
  } else {
      $tags = null;
  }

  if (!isset($_POST['story_text'])) {
      $story_text = null;
    
      if (exif_imagetype($_FILES['image']['tmp_name']) != IMAGETYPE_JPEG) {
          $_SESSION['messages'][] = array('type' => 'error', 'content' => 'Image extension not supported!');
          die(header('Location: ' . $_SERVER['HTTP_REFERER']));
      };

      insertImage();
      $db = Database::instance()->db();
      $img_id = $db->lastInsertId();
      saveImage($img_id);
  } else {
      $story_text = $_POST['story_text'];
      $img_id = 9;
  }


  $story_id = insertStory($story_title, $story_text, $img_id, $user_id, $channel_id, $tags);


  header("Location: ../pages/story.php?id=$story_id");
