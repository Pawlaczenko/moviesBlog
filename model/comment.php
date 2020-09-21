<?php

require_once( CORE_PATH . "TMPL.php");
require_once( MODEL_PATH . "post.php");

class Comment extends TMPL {

    public
            $Post_Id = NULL,
            $Id = NULL,
            $User_Id = NULL,
            $Comment = NULL,
            $Date = NULL;

    public function __construct() {
        parent::__construct();

        $this->table_name = 'Post_Comment';
    }

    public function addComment($comment,$post,$user) {
        $this->Post_Id = $post;
        $this->User_Id = $user;
        $this->Comment = addslashes($comment);
        $this->Date = date("Y-m-d H:i:s");

        
        $id = $this->Save();
        $result = $this->getSingleComment($id);
        return $result;
    }

    public function deleteComment($id) {
        $this->get($id);
        return $this->delete();
    }

    public function getAllComments($post) {
        $query = "SELECT c.*, u.Id as user, u.Name, u.Surname, u.Login, u.PhotoName FROM Post_Comment c INNER JOIN Users u ON c.User_Id = u.Id WHERE c.Post_Id='".$post."' ORDER BY c.Id DESC";
        // echo $query;
        // exit();
        $result = $this->Query($query);

        foreach ($result as $key => $value){
            $value->Date = $this->reforgeDate($value->Date,1);
        }

        return $result;
    }
    
    public function getSingleComment($id) {
        $query = "SELECT c.*, u.Id as user, u.Name, u.Surname, u.Login, u.PhotoName FROM Post_Comment c INNER JOIN Users u ON c.User_Id = u.Id WHERE c.Id='".$id."' LIMIT 1";
        // echo $query;
        // exit();
        $result = $this->Query($query);
        $result[0]->Date = $this->reforgeDate($result[0]->Date,1);
        return $result;
    }

    public function countComments($id) {
        $query = "SELECT COUNT(c.Id) FROM Post_Comment c WHERE c.Post_Id='".$id."'";
        // echo $query;
        // exit();
        $result = $this->Query($query);
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
