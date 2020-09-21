<?php
    session_start();
    require_once '../../config.php';
    require_once MODEL_PATH . 'comment.php';

    $c = new Comment();

    if(isset($_POST['delete'])){
        $id = $_POST['id'];
        $result = $c->deleteComment($id);
        echo $result;
    } else {
        $user = $_SESSION['user_bpawlak']->Id;
        $post = $_POST['post'];
        $content = $_POST['content'];
        
        $result = $c->addComment($content,$post,$user);
    
        echo json_encode($result);
    }

?>