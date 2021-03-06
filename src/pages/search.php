<?php
    include_once('../includes/session.php');
    include_once('../templates/tpl_common.php');
    include_once('../templates/tpl_stories.php');
    include_once('../templates/tpl_channels.php');
    include_once('../templates/tpl_search.php');
    include_once('../database/db_channel.php');
    include_once('../database/db_comment.php');
    include_once('../database/db_story.php');
    include_once('../database/db_post.php');
    include_once('../database/db_user.php');

    // Verify if user is logged in
    if (!isset($_SESSION['username'])) {
        die(header('Location: login.php'));
    }

    draw_header($_SESSION['username']);
    draw_search_form();

    ?>
    <section id="search">
    <?php

    if (isset($_GET['submit']) && preg_match("/[A-Z  | a-z]+/", $_GET['search_text'])) {
        $search_text = $_GET['search_text'];
        $search_type = $_GET['search_type'];

        if ($search_type == 'stories') {

            if(strstr($search_text, '#')){
                $tok = strtok($search_text," \n");

                $tags = array();
                while ($tok !== false) {
                    array_push($tags, substr($tok, 1));
                    $tok = strtok(" ");
                }

                $stories = searchStoriesByTags($tags);
            }
            else{
                $stories = searchStories($search_text);
            }
            
            if($stories == null)
                echo 'Ups... Didn\'t find anything!';
            else
                draw_stories($stories, -1);
        } else if ($search_type == 'comments') {
            $comments = searchComments($search_text);

            if($comments == null)
                echo 'Ups... Didn\'t find anything!';
            else
                draw_comments($comments, false);
        }
        else if ($search_type == 'channels') {
            $channels = searchChannels($search_text);

            if($channels == null)
                echo 'Ups... Didn\'t find anything!';
            else
                draw_channels($channels);
        }
        else {
            die(header('Location: ../pages/home.php'));
        }
    }

    ?>
    </section>
    <?php
    
    draw_footer();