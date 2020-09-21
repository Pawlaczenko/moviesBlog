<?php

require_once(CORE_PATH . "TMPL.php");

class Post extends TMPL {

    public
            $Id = NULL,
            $User_Id = NULL,
            $Title = NULL,
            $Content = NULL,
            $Date = NULL,
            $ImageName = NULL,
            $AmountOfLikes = 0;

    public function __construct() {
        parent::__construct();

        $this->table_name = 'Posts';
    }

    public function add($title, $content, $author, $image, $categories) {
        $this->User_Id = $author;
        $this->Title = $title;
        $this->Content = $content;        
        $this->Date = date("Y-m-d H:i:s");

        $id = $this->Save();
        $this->get($id);
        $this->ImageName = $this->upload($image, 1, $id);
        $this->Update();


        foreach ($categories as $option) {
            $query = "INSERT INTO Post_Category (Category_id, Post_id) VALUES ({$option},{$id})";
            $this->Query($query);
        }
        return $id;
    }

    public function getAllPosts($pageno, $category, $ordering) {
        
        $where = '';
        $order = '';

        $category = (!$category)?'none':$category;
        $ordering = (!$ordering)?'none':$ordering;

        switch($ordering) {
            case "alpA":
                $order = ' ORDER BY p.Title ASC';
                break;
            case "alpZ":
                $order = ' ORDER BY p.Title DESC';
                break;
            case "lik":
                $order = ' ORDER BY p.AmountOfLikes DESC';
                break;
            case "com":
                $order = ' ORDER BY AmountOfComments DESC';
                break;
            case "old":
                $order = ' ORDER BY p.Id ASC';
                break;
            case 'none':
            case "new":
            default:
                $order = ' ORDER BY p.Id DESC';
                break;
        };
        
        switch ($category) {
            case 'none':
            case "all":
                $where = '';
                break;
            default:
                $where = " INNER JOIN Post_Category pc ON pc.Post_id = p.Id WHERE pc.Category_id = {$category}";
                break;
        };

        $no_of_records_per_page = 12;
        $offset = ($pageno-1) * $no_of_records_per_page;
        $getAll = 
        $total_pages = ceil($this->getAllPages($where) / $no_of_records_per_page);
        if($pageno>$total_pages){
            $pageno = 1;
            $offset = ($pageno-1) * $no_of_records_per_page;
        }

        $query = "SELECT p.Id, p.Title, p.Date, p.ImageName, p.AmountOfLikes, u.Name, u.Surname, COUNT(c.Id) as AmountOfComments FROM Posts p INNER JOIN Users u ON p.User_Id = u.Id LEFT JOIN Post_Comment c ON p.Id = c.Post_Id{$where} GROUP BY p.Id, p.Title, p.Date, p.ImageName, p.AmountOfLikes, u.Name, u.Surname{$order} LIMIT {$offset}, {$no_of_records_per_page}";
        $posts = $this->Query($query);

        foreach ($posts as $key => $value){
            $arr2 = $this->getSpecificCategories($value->Id);
            $value->Category = (array)$arr2;
            $value->Date = $this->reforgeDate($value->Date,0);
        }

        $result = [
            "posts" => $posts,
            "total" => $total_pages
        ];
        return $result;
    }

    public function getAllPages($where) {
        $result = $this->Query("SELECT COUNT(*) as pages FROM Posts p".$where);
        $result = $result[0]->pages;
        return $result;
    }

    public function getSinglePost($id) {
        $query = 'SELECT p.Id, p.Title, p.Content, p.Date, p.ImageName, p.AmountOfLikes, u.Name, u.Surname, u.Id as User_id, u.Login FROM Posts p LEFT JOIN Users u ON p.User_Id = u.Id WHERE p.Id = '.$id.'';
        if(!$result = $this->Query($query)) {
            return 'error';
        }
        $result[0]->Date = $this->reforgeDate($result[0]->Date,1);
        $cat = $this->getSpecificCategories($id);
        $result[0]->Category = $cat;
        
        return $result;
    }

    public function getDraft($id){
        $query1 = 'SELECT * FROM Posts WHERE Id ="'.$id.'"';
        $result1=$this->Query($query1);

        $query2 = 'SELECT Category_id FROM Post_Category WHERE Post_id ="'.$id.'"';
        $result2=$this->Query($query2);

        $result3 = $this->getAllCategories();

        $result= [
            "post" => $result1,
            "categories" => $result2,
            "names"=>$result3
        ];

        return $result;
    }

    public function getPostsById($id,$category,$ordering){
        $where = '';
        $order = '';

        $category = (!$category)?'none':$category;
        $ordering = (!$ordering)?'none':$ordering;

        switch($ordering) {
            case "alpA":
                $order = ' ORDER BY p.Title ASC';
                break;
            case "alpZ":
                $order = ' ORDER BY p.Title DESC';
                break;
            case "lik":
                $order = ' ORDER BY p.AmountOfLikes DESC';
                break;
            case "com":
                $order = ' ORDER BY AmountOfComments DESC';
                break;
            case "old":
                $order = ' ORDER BY p.Id ASC';
                break;
            case 'none':
            case "new":
            default:
                $order = ' ORDER BY p.Id DESC';
                break;
        };
        
        switch ($category) {
            case 'none':
            case "all":
                $where = "WHERE p.User_Id = {$id}";
                break;
            default:
                $where = " INNER JOIN Post_Category pc ON pc.Post_id = p.Id WHERE p.User_Id ={$id} AND pc.Category_id = {$category}";
                break;
        };

        $query = "SELECT p.Id, p.Title, p.AmountOfLikes, p.Date, p.ImageName, COUNT(c.Id) as AmountOfComments FROM Posts p LEFT JOIN Post_Comment c ON p.Id = c.Post_Id {$where} GROUP BY p.Id, p.Title, p.AmountOfLikes, p.Date, p.ImageName {$order}";
        if($result = $this->Query($query)){
            foreach ($result as $key => $value){
                $arr2 = $this->getSpecificCategories($value->Id);
                $value->Category = (array)$arr2;
                $value->Date = $this->reforgeDate($value->Date,0);
            }
            return $result;
        } else {
            return false;
        }
    }

    public function checkBelonging($post,$user) {
        $query = 'SELECT p.Id FROM Posts p WHERE p.Id = "'.$post.'" AND p.User_Id = '.$user;
        $res = $this->Query($query);

        return (count($res))?true:false;
    }

    public function checkExistence($post) {
        $query = "SELECT p.Id FROM Posts p WHERE p.Id = '".$post."'";

        $res = $this->Query($query);

        return (count($res))?true:false;
    }

    public function getSpecificCategories($id) {
        $result = $this->Query("SELECT c.Name FROM Categories c NATURAL JOIN Post_Category pc WHERE Post_id=".$id." GROUP BY c.Name");
        return $result;
    }

    public function getCategoryByName($name) {
        $result = $this->Query("SELECT * FROM Categories WHERE Name = '".$name."'");
        return $result;
    }

    public function getAllCategories() {
        $result = $this->Query("SELECT c.*, COUNT(pc.Post_id) as Number FROM Categories c LEFT JOIN Post_Category pc USING(Category_id) GROUP BY c.Category_id, c.Name ORDER BY c.Name");
        return $result;
    }

    public function deleteCategoryAdmin($id) {
        $this->Query("DELETE FROM Categories WHERE Category_id = ".$id);
        return;
    }

    public function addCategoryAdmin($name) {
        $this->Query("INSERT INTO Categories (Name) VALUES ('".$name."')");
        return;
    }

    public function deletePost($id) {
        $this->get($id);
        $this->deletePhoto('post_headers/'.$this->ImageName);
        $this->delete();
    }

    public function editPost($id, $title, $content, $image, $categories){
        $this->get($id);

        $this->Title = $title;
        $this->Content = $content;        
        $this->Date = date("Y-m-d H:i:s");

        if($image){
            $this->deletePhoto('post_headers/'.$this->ImageName);
            $this->ImageName = $this->upload($image, 1, $id);
        }


        $this->Update();
        // Tools::showObj($this);
        // exit();        
        $delete = "DELETE FROM Post_Category WHERE Post_id = {$id}";
        $this->Query($delete);

        foreach ($categories as $option) {
            $query = "INSERT INTO Post_Category (Category_id, Post_id) VALUES ({$option},{$id})";
            $this->Query($query);
        }
        return;
    }

    public function likePost($post,$user) {
        $this->get($post);
        $query = "INSERT INTO Liked_Post (Post_Id, User_Id) VALUES ('".$post."','".$user."')";
        if($this->Query($query)){
            $this->AmountOfLikes+=1;
            $this->Update();
        };
        return $this->AmountOfLikes;
    }

    public function dislikePost($post,$user) {
        $this->get($post);
        $query = "DELETE FROM Liked_Post WHERE Post_Id='".$post."' AND User_Id='".$user."'";
        if($this->Query($query)){
            $this->AmountOfLikes-=1;
            $this->Update();
        };
        return $this->AmountOfLikes;
    }

    public function isLiked($id,$post) {
        $query = "SELECT * FROM Liked_Post WHERE Post_Id='".$post."' AND User_Id='".$id."'";
        return count($this->Query($query));
    }

    public function search($keyword) {
        $keywords = explode(' ',$keyword);
        $filters = '';
        foreach($keywords as $i => $value){
            $end = ($i<count($keywords)-1)?'OR':'';
            $filters.=" p.Title LIKE '%{$value}%' {$end}";
        }
        $query = "SELECT p.Id, u.Name, u.Surname, p.Title, p.Date, p.ImageName, p.AmountOfLikes FROM Posts p LEFT JOIN Users u ON p.User_Id = u.Id WHERE {$filters} ORDER BY AmountOfLikes DESC";
        $res = $this->Query($query);

        foreach($res as $value){
            $value->Date = $this->reforgeDate($value->Date,1);
        }

        return $res;
    }
}
