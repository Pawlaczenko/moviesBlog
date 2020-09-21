<?php

require_once( CORE_PATH . "TMPL.php");
require_once( MODEL_PATH . "post.php");

class User extends TMPL {

    public
            $Id = NULL,
            $Name = NULL,
            $Surname = NULL,
            $Email = NULL,
            $DateOfBirth = NULL,
            $Login = NULL,
            $Password = NULL,
            $Gender = NULL,
            $PhotoName = NULL,
            $isAdmin = 0;

    public function __construct() {
        parent::__construct();

        $this->table_name = 'Users';
    }

    function Login($user, $password){

        $result = '';
        $data = $this->Query("SELECT * FROM Users WHERE Login = '{$user}' LIMIT 1");

        if(count($data)){
            if(!Tools::PasswordCheck($password, $data[0]->Password) ){
               $result = 'Hasło się nie zgadza. </br>';
            }
            return $result;
        }
        return 'Podany użytkownik nie istnieje </br>';
    }

    function Register($email,$login,$password,$name,$surname,$gender,$DateOfBirth) {
        $this->Email = $email;
        $this->Login = $login;
        $this->Password = Tools::PasswordHash($password);
        $this->Name = $name;
        $this->Surname = $surname;
        $this->DateOfBirth = $DateOfBirth;
        $this->Gender = $gender;
        $this->PhotoName = NULL;

        $this->Save();
    }

    function getUserByLogin($login) {
        $result = $this->Query("SELECT Id, Name, Surname, Email, DateOfBirth, PhotoName, Login, Gender FROM Users WHERE Login = '{$login}' LIMIT 1");
        return $result;
    }

    function getUserById($id) {
        $result = $this->Query("SELECT * FROM Users WHERE Id = '{$id}' LIMIT 1");
        return $result;
    }

    function getUserByEmail($email) {
        $result = $this->Query("SELECT Id, Name, Surname, Email, DateOfBirth, PhotoName, Login, Gender FROM Users WHERE Email = '{$email}'");
        return $result;
    }

    function getAllUsers() {
        $result = $this->Query("SELECT * FROM Users WHERE isAdmin != 1");
        return $result;
    }

    public function checkPassword($pwd1, $pwd2) {
        $error='';
        
        if($pwd1!=$pwd2){
            $error.="Hasła się nie zgadzają <br />";
        }

        if ((strlen($pwd1) < 6)||(!preg_match("#[0-9]+#", $pwd1)) || (!preg_match("#[a-zA-Z]+#", $pwd1))) {
            $error.="Hasło nie spełnia wymagań. <br />";
        }
        return $error;
    }

    public function resetPassword($id,$pwd) {
        $this->get($id);
        $this->Password = Tools::PasswordHash($pwd);

        $this->update();
        $_SESSION['user_bpawlak'] = $this->getUserByLogin($_SESSION['user_bpawlak']->Login)[0];
        return;
    }

    public function getProfile($login,$category,$order){
        if(count($this->getUserByLogin($login))){
            $user = $this->getUserByLogin($login)[0];
        } else {
            return false;
        }
        
        $user->DateOfBirth = $this->reforgeDate($user->DateOfBirth,1);

        $post = new Post();
        $posts = $post->getPostsById($user->Id,$category,$order);
        $categories = $post->getAllCategories();

        $result = [
            "user" => $user,
            "posts" => $posts,
            "categories" => $categories
        ];

        return $result;
    }

    function editUser($_id,$_name, $_surname, $_email, $_avatar, $_change,$_gender,$isAdmin=null,$login=null) {
        $this->get($_id);
        $this->Name = $_name;
        $this->Surname = $_surname;
        $this->Email = $_email;
        $this->Gender = $_gender;
        if(isset($login)){
            $this->Login = $login;
        }

        if($_change){
            $this->deletePhoto('users/'.$this->PhotoName);
            $this->PhotoName = $this->upload($_avatar, 0, $_id);

            //Tools::showObj($this);
            //exit();
        }

        $this->update();
        if(!isset($isAdmin))$_SESSION['user_bpawlak'] = $this->getUserByLogin($_SESSION['user_bpawlak']->Login)[0];
        
        // echo $res;
        return;
    }

    function deleteUser() {
        $this->get($_SESSION['user_bpawlak']->Id);
        $this->deletePhoto('users/'.$this->PhotoName);
        $this->delete();
    }

    function deleteUserAdmin($id) {
        $this->get($id);
        // Tools::showObj($this);
        // exit();
        $this->deletePhoto('users/'.$this->PhotoName);
        $this->delete();
    }

    public function search($keyword) {
        $keywords = explode(' ',$keyword);
        $filters = '';
        foreach($keywords as $i => $value){
            $end = ($i<count($keywords)-1)?'OR':'';
            $filters.=" u.Name LIKE '%{$value}%' OR u.Surname LIKE '%{$value}%' OR u.Login LIKE '%{$value}%' {$end}";
        }
        $query = "SELECT u.Id, u.Name, u.Surname, u.PhotoName, u.Login, COUNT(p.Id) as AmountOfPosts FROM Users u LEFT JOIN Posts p ON u.Id = p.User_Id WHERE {$filters} GROUP BY u.Id ORDER BY AmountOfPosts DESC";
        return $this->Query($query);
    }

    public function countLikes($login) {
        $query = "SELECT SUM(p.AmountOfLikes) AS suma FROM Users u INNER JOIN Posts p ON u.Id = p.User_Id WHERE u.Login = '".$login."'";
        return $this->Query($query);
    }
    
    //Funkcje własne do napisania
    //Login()
    //ResetPassword()
    //Register()

    /* EXTENDS TMPL */
    //get(id)
    //getList(params)
    //save()
    //delete()
    //update()
}
