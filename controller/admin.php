<?php

require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'log.php';
require_once MODEL_PATH . 'post.php';

class adminController extends Controller {
    
    public function panel($path,$errors=null) {
        if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']){
            $data['adminPanel']=true;
            $data['path']=$path;
            if(isset($errors)){
                $data['error'] = $errors;
            }
            switch($path){
                case 'logs':
                    $data['logs'] = $this->getAllLogs();
                    $this->render("admin/adminLogsView", $data);
                    break;
                case 'categories':
                    $post = new Post();
                    $data['categories'] = $post->getAllCategories();
                    $this->render("admin/adminCategoriesView", $data);
                    break;
                case 'users':
                default:
                    $user = New User();
                    $data['path']='users';
                    $data['users']=$user->getAllUsers();
                    $this->render("admin/adminUsersView", $data);
                    break;
            }
            return;
        }
        header('Location: '.SITE_PATH.'home');
        return;
    }
    
    public function editUser() {
        $user = new User();
    
        $id = $_POST['id'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $avatar = $_FILES['avatar'];
        $gender = $_POST['gender'];
        $login = $_POST['login'];
        $change = (isset($_POST['change'])) ? true : false;
    
        $data['update_message'] = [];
    
        if(!isset($name) || !isset($surname) || !isset($email)){
            array_push($data['update_message'], "Wypełnij wszystkie pola <br />");
        }
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($data['update_message'], "Wprowadź poprawny adres e-mail <br />");
        }
        
        if(isset($avatar) && $change){
            $emp = $user->checkFile($avatar);
            if(!empty($emp)){
                array_push($data['update_message'],$emp);
            }
        }
    
        if(!empty($data['update_message'])){
            // Tools::showObj($data['update_message']);
            // exit();
            header('Location: '.SITE_PATH.'admin/panel/users');
            return;
        }
    
        $user->editUser($id,$name,$surname,$email,$avatar,$change,$gender,true,$login);
        header('Location: '.SITE_PATH.'admin/panel/users');
        return;
    }

    public function deleteUser() {
        $id = $_POST['id'];
        $user = new User();
        $user->deleteUserAdmin($id);
        header('Location: '.SITE_PATH.'admin/panel/users');
        return;
    }

    public function deleteLog() {
        $id = $_POST['id'];
        $log = new Log();
        $log->deleteLogAdmin($id);
        header('Location: '.SITE_PATH.'admin/panel/logs');
        return;
    }

    public function deleteCategory() {
        $id = $_POST['id'];
        $post = new Post();
        $post->deleteCategoryAdmin($id);
        header('Location: '.SITE_PATH.'admin/panel/categories');
        return;
    }

    public function getAllLogs(){
        $log = new Log();
        $result = $log->getAllLogs();
        return $result;   
    }

    public function addCategory() {
        $name = $_POST['name'];
        $l = strlen($name);
        $post = new Post();
        $error = [];
        
        if($l<3){
            array_push($error, 'The name is too short. </br>');
        }

        if($l>20){
            array_push($error, 'The name is too long. </br>');
        }

        if(count($post->getCategoryByName($name))){
            array_push($error, 'The category already exists </br>');
        }

        if(!empty($error)){
            $this->panel('categories',$error);
            return;
        }

        $post->addCategoryAdmin($name);
        header('Location: '.SITE_PATH.'admin/panel/categories');
        return;
    }
}