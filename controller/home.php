<?php
require_once MODEL_PATH . 'post.php';
require_once MODEL_PATH . 'user.php';

class homeController extends Controller {
    
    public function show($sort){
        $post = new Post();
        $url = $_SERVER['REQUEST_URI'];
        $page = explode('?pageno=',$url);
        $category = 0;
        $order = 0;

        if (strpos($url, '?pageno=') !== false && $sort) { //Strona + sortowanie
            $category = $_SESSION['sort']->Category;
            $order = $_SESSION['sort']->Order;
            $data['selected']->Category = $category;
            $data['selected']->Order = $order;
        } else if($sort && !isset($_SESSION['sort'])){ //nie ma sesji
            $category = $_SESSION['sort']->Category = $_POST['category'];
            $order = $_SESSION['sort']->Order = $_POST['order'];
            $data['selected']->Category = $category;
            $data['selected']->Order = $order;
        } else if($sort && isset($_SESSION['sort'])){   //jest sesja
            $_SESSION['sort']->Category = $category = $_POST['category'];
            $_SESSION['sort']->Order = $order = $_POST['order'];
            $data['selected']->Category = $category;
            $data['selected']->Order = $order;
        } else { 
            unset($_SESSION['sort']);
        }

        if(isset($page[1])) {
            $pageno = $page[1];
            if($sort){
                $category = $_SESSION['sort']->Category;
                $order = $_SESSION['sort']->Order;
            }
        } else {
            $pageno = 1;
        }

        $posts = $post->getAllPosts($pageno,$category, $order);
        $data['categories'] = $post->getAllCategories();
        $data['pages'] = [
            "pageno" => $pageno,
            "total" => $posts["total"]
        ];
        $data['posts'] = $posts["posts"];

        //Tools::showObj($data);

        $this->render("home", $data);
    }

    public function search() {
        $keyword = str_replace('"', "", str_replace("'", "", $_POST['keyword']));
        $keyword = preg_replace('/\s+/', ' ',trim($keyword));
        
        $data['keyword'] = $keyword;
        
        $data['results'] = [];

        $u = new User();
        $p = new Post();

        $data['results']['users'] = $u->search($keyword);
        $data['results']['posts'] = $p->search($keyword);
        $this->render("search",$data);
    }
}