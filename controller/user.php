<?php

require_once MODEL_PATH . 'user.php';

class userController extends Controller
{

    public function showAll()
    {
        $u = new User();

        $data['users'] = $u->Query("SELECT * FROM Users");
    }

    public function register() {
        if(isset($_POST['login']) && isset($_POST['email']) && isset($_POST['pwd1']) && isset($_POST['pwd2']) && isset($_POST['name']) && isset($_POST['surname'])){
            $datea = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
            $u = new User();
            $data['form_data'] = [
                "login" => $_POST['login'],
                "email" => $_POST['email'],
                "name" => $_POST['name'],
                "surname" => $_POST['surname']
            ];
            $data['register_message'] = [];
            
            if(strlen($u->checkPassword($_POST['pwd1'],$_POST['pwd2']))){
                array_push($data['register_message'],$u->checkPassword($_POST['pwd1']));
            }

            if(count($u->getUserByLogin($_POST['login'])) || count($u->getUserByEmail($_POST['email'])) ) {
                array_push($data['register_message'],"Podany użytkownik istnieje <br />");
            }

            if(strlen($_POST['login']) < 5 || strlen($_POST['login']) > 12) {
                array_push($data['register_message'], "Wprowadź poprawny login<br />");
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                array_push($data['register_message'], "Wprowadź poprawny adres e-mail <br />");
            }

            if(!empty($data['register_message'])){
                $this->render('user/userRegisterView', $data);
                return;
            }
            
            //UDANE REJESTRACJA
            $u->Register($_POST['email'],$_POST['login'],$_POST['pwd1'],$_POST['name'],$_POST['surname'],$_POST['gender'],$datea);
            $this->render('user/userLoginView', $data);
            $data['register_message'] = '';
            return;
            // KONIEC UDANEGO REJESTRACJA
        }
        
        $data['register_message'] = "Fill all the essential inputs.";
        $this->render('user/userRegisterView', $data);
    }

    public function login(){
        $data['login_message'] = [];
        if (isset($_POST['uname1']) && isset($_POST['pwd1'])) {
            $u = new User();
            $data['login_message'] = [];
            $result = $u->Login($_POST['uname1'], $_POST['pwd1']);
            if (!strlen($result)) {
                
                $data['login_message'] = "";
                $data['user'] = $u->Query("SELECT * FROM Users WHERE Login = '" . $_POST['uname1'] . "' LIMIT 1")[0];

                $_SESSION['user_bpawlak'] = $data['user'];
                $_SESSION["isAdmin"] = $data['user']->isAdmin;

                $this->profile($_SESSION['user_bpawlak']->Login,0);
                return;
            } else {
                array_push($data['login_message'], $result);
                if($result == 'Hasło się nie zgadza. </br>') $data['login']=$_POST['uname1'];
                $this->render('user/userLoginView', $data);
                return;
            }
        }
        $this->render('user/userLoginView', $data);
    }

    public function logout() {

        session_unset();

        $this->render('user/userLoginView');
    }

    public function profile($user,$sort=null) {
        $us = new User();
        if(!isset($user) || !strlen($user)){
            if(isset($_SESSION['user_bpawlak'])){
                $user = $_SESSION['user_bpawlak']->Login;
            } else {
                header('Location: '.SITE_PATH.'home');
                return;
            }
        }

        $category = 0;
        $order = 0;
        if(isset($sort) && $sort) {
            $category = $_POST['category'];
            $order = $_POST['order'];
            $data['selected']->Category=$category;
            $data['selected']->Order=$order;
        }

        $data['profile'] = $us->getProfile($user,$category,$order);
        $tmp = $us->countLikes($user)[0]->suma;
        $data['likes'] = (isset($tmp))?$tmp:'0';

        if(!($data['profile'])) {
            header('Location: '.SITE_PATH.'home');
            return;
        }
        
        $this->render('user/userProfileView',$data);
    }

    public function settings() {
        if(!isset($_SESSION['user_bpawlak'])){
            header('Location: '.SITE_PATH.'home');
            return;
        }

        $this->render('user/userSettingsView');
    }

    public function editUser() {
        $user = new User();

        $id = $_SESSION['user_bpawlak']->Id;
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $avatar = $_FILES['avatar'];
        $gender = $_POST['gender'];
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
            $this->render('user/userSettingsView', $data);
            return;
        }

        $user->editUser($id,$name,$surname,$email,$avatar,$change,$gender);
        header('Location: '.SITE_PATH.'user/settings');
    }

    public function resetPassword(){
        $current = $_POST['current'];
        $new1 = $_POST['new1'];
        $new2 = $_POST['new2'];
        $u = new User();
        $data['reset'] = [];

        if(strlen($u->Login($_SESSION['user_bpawlak']->Login, $current))){
            array_push($data['reset'],'Twoje aktualne hasło jest niepoprawne. <br/>');
        }

        $emp = $u->checkPassword($new1,$new2);
        if(strlen($emp)){
            array_push($data['reset'],$emp);
        }

        if(empty($data['reset'])){
            echo 'srodek';
            $data['reset'] = [];
            $u->resetPassword($_SESSION['user_bpawlak']->Id,$new1);
            header('Location: '.SITE_PATH.'user/settings');
            return;
        }

        $this->render('user/userSettingsView', $data);
    }

    public function deleteUser() {
        $password = $_POST['password'];
        $user = new User();
        $data['delete'] = '';

        if(strlen($user->Login($_SESSION['user_bpawlak']->Login,$password))) {
            $data['delete']="The password is incorrect.";
            $this->render('user/userSettingsView', $data);
            return;
        }

        $user->deleteUser();
        $this->logout();
        return;
    }
}
