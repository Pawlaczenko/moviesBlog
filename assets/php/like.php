<?php
    session_start();
    require_once '../../config.php';
    require_once MODEL_PATH . 'post.php';

    $user = $_POST['user'];
    $post = $_POST['id'];
    $func = $_POST['func'];

    $p = new Post();
    if(!$func){
        $result = $p->likePost($post,$user);
    } else {
        $result = $p->dislikePost($post,$user);
    }

    echo $result;
?>