<?php

require_once MODEL_PATH . 'post.php';
require_once MODEL_PATH . 'comment.php';

class postController extends Controller {
    public function addPost() {
        if(isset($_SESSION['user_bpawlak'])){
            $post = new Post();
            $data['categories'] = $post->getAllCategories();
            unset($post);
            $this->render("post/postAddView",$data);
        } else {
            header('Location: '.SITE_PATH.'home');
        }
    }

    public function add() {
        $post = new Post();
        $data['post_error'] = [];
        $data['categories'] = $post->getAllCategories();
        $data['form_data'] = [
            "title" => $_POST['addPostTitle'],
            "content" => $_POST['addPostContent']
        ];

        if(isset($_POST['addPostTitle']) && isset($_POST['category']) && isset($_FILES['back_photo']) && isset($_POST['addPostContent'])){
            
            if(strlen($_POST['addPostTitle'])<8 || strlen($_POST['addPostTitle'])>90) {
                $error = 'Tytuł musi składać się z od 8 do 90 znaków.<br />';
                array_push($data['post_error'],$error);
            }

            if(count($_POST['category'])<1 && $count($_POST['category'])>3){
                $error = 'Wybierz minimum 1 i maksimum 3 kategorie.<br />';
                array_push($data['post_error'],$error);
            }

            $emp = $post->checkFile($_FILES['back_photo']);
            if(!empty($emp)){
                array_push($data['post_error'],$emp);
            }

            if(strlen($_POST['addPostContent'])<100 || strlen($_POST['addPostContent'])>10000) {
                $error = 'Artykuł musi mieć przynajmniej 100 znaków.<br />';
                array_push($data['post_error'],$error);
            }

            //Tools::showObj($data['post_error']);
            if(empty($data['post_error'])){
                $id = $post->add(addslashes($_POST['addPostTitle']), $_POST['addPostContent'], $_SESSION['user_bpawlak']->Id, $_FILES['back_photo'], $_POST['category']);
                $data['post_error'] = '';
                header('Location: '.SITE_PATH.'post/show/'.$id);
                return;
            } else {
                
            }
        } else {
            $error = 'Set all the inputs';
            // echo 'title-'.isset($_POST['addPostTitle']).'category-'.isset($_POST['category']) .'file-'. isset($_FILES['back_photo']) .'content-'. isset($_POST['addPostContent']);
            array_push($data['post_error'],$error);
        }
        $this->render('post/postAddView', $data);
        return;
    }

    public function show($id) {
        $post = new Post();
        $comment = new Comment();
        $data['comments'] = $comment->getAllComments($id);
        $data['post'] = $post->getSinglePost($id);
        if(isset($_SESSION['user_bpawlak'])){
            $data['isLiked'] = $post->isLiked($_SESSION['user_bpawlak']->Id,$id);
        }
        if($data['post']==='error') {
            
            return;
        }
        $this->render('post/postShowPostView',$data);
    }

    public function deletePost($id) {
        $post = new Post();
        $data['deletePost'] = [];
        if($post->checkBelonging($id,$_SESSION['user_bpawlak']->Id) || isset($_SESSION['isAdmin'])) {
            $post->deletePost($id);
            header('Location: '.SITE_PATH.'home');
            return;
            // header('Location: '.SITE_PATH.'user/profile/'.$_SESSION['user_bpawlak']->Login);
        }
        array_push($data['deletePost'],'Ups, coś poszło nie tak. Następnym razem nie grzeb w kodzie.');
        header('Location: '.SITE_PATH.'user/profile');
    }

    public function editPost($id,$error=null) {
        if(!isset($_SESSION['user_bpawlak'])){
            header('Location: '.SITE_PATH.'home');
            return;
        }

        $post = new Post();
        $data['editPost'] = [];
        if(isset($error)){
            $data['post_error'] = $error;
        }
        if($post->checkExistence($id) && ($post->checkBelonging($id,$_SESSION['user_bpawlak']->Id) || isset($_SESSION['isAdmin']))) {
            $data['draft'] = $post->getDraft($id);

            $this->render('post/postEditView',$data);
        } else {
            
        }
        // array_push($data['editPost'],'Ups, coś poszło nie tak. Następnym razem nie grzeb w kodzie.');
        // header('Location: '.SITE_PATH.'user/profile');
    }

    public function edit() {
        $post = new Post();
        $data['post_error'] = [];
        $post_id=$_POST['post_id'];
        $file = false;

        if(isset($_POST['addPostTitle']) && isset($_POST['category']) && isset($_POST['addPostContent'])){
            
            if(strlen($_POST['addPostTitle'])<8 || strlen($_POST['addPostTitle'])>90) {
                $error = 'Tytuł musi składać się z od 8 do 90 znaków.<br />';
                array_push($data['post_error'],$error);
            }

            if(count($_POST['category'])<1 && $count($_POST['category'])>3){
                $error = 'Wybierz minimum 1 i maksimum 3 kategorie.<br />';
                array_push($data['post_error'],$error);
            }

            if(!empty($_FILES['back_photo']['name'])){
                $emp = $post->checkFile($_FILES['back_photo']);
                if(!empty($emp)){
                    array_push($data['post_error'],$emp);
                } else {
                    $file = $_FILES['back_photo'];
                }
            }
            
            if(strlen($_POST['addPostContent'])<100 || strlen($_POST['addPostContent'])>10000) {
                $error = 'Artykuł musi mieć przynajmniej 100 znaków.<br />';
                array_push($data['post_error'],$error);
            }

            //Tools::showObj($data['post_error']);
            if(empty($data['post_error'])){
                $post->editPost($post_id,addslashes($_POST['addPostTitle']), $_POST['addPostContent'], $file, $_POST['category']);
                $data['post_error'] = '';
                header('Location: '.SITE_PATH.'post/show/'.$post_id);
                return;
            } else {
                
            }
        } else {
            $error = 'Set all the inputs';
            // echo 'title-'.isset($_POST['addPostTitle']).'category-'.isset($_POST['category']) .'file-'. isset($_FILES['back_photo']) .'content-'. isset($_POST['addPostContent']);
            array_push($data['post_error'],$error);
        }
        // $this->render('post/postAddView', $data);
        $this->editPost($post_id,$data['post_error']);
        return;
    }
}