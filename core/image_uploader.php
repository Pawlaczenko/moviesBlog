<?php 
require_once '../config.php';
require_once CORE_PATH.'database.php';

/** 
 * Set filename 
 * If the file exists, and RENAME_F is 1, set "img_name_1" 
 * 
 * $p = dir-path, $fn=filename to check, $ex=extension $i=index to rename 
 */ 
function setFName($p, $fn, $ex, $i){ 
    if(file_exists($p .$fn .$ex)){ 
        return setFName($p, F_NAME .'_'. ($i +1), $ex, ($i +1)); 
    }else{ 
        return $fn .$ex; 
    } 
}

function uploadImage($destiny) {

    $re = '';
    // Define file upload path  TRUE : POST      FASLE : USER
    $destiny ? $upload_dir = array('img'=> 'assets/uploaded_images/posts/') : $upload_dir = array('img'=> 'assets/uploaded_images/users/');
    // Allowed image properties 
    $imgset = array( 
        'maxsize' => 2000, 
        'maxwidth' => 1024, 
        'maxheight' => 800, 
        'minwidth' => 10, 
        'minheight' => 10, 
        'type' => array('bmp', 'gif', 'jpg', 'jpeg', 'png'), 
    );

    if(isset($_FILES['upload']) && strlen($_FILES['upload']['name']) > 1) {
        $destiny ? $upload_dir = array('img'=> 'assets/uploaded_images/posts/') : $upload_dir = array('img'=> 'assets/uploaded_images/users/');
        define('F_NAME', $destiny ? preg_replace('/\.(.+?)$/i', '', basename($_FILES['upload']['tmp_name'])) : $_SESSION['user_bpawlak']->Id);

        // Get filename without extension 
        $sepext = explode('.', strtolower($_FILES['upload']['name'])); 
        $type = end($sepext);    /** gets extension **/ 

        // Upload directory 
        $upload_dir = in_array($type, $imgset['type']) ? $upload_dir['img'] : $upload_dir['audio']; 
        $upload_dir = trim($upload_dir, '/') .'/';

        if(in_array($type, $imgset['type'])){ 
            // Image width and height 
            list($width, $height) = getimagesize($_FILES['upload']['tmp_name']); 
     
            if(isset($width) && isset($height)) { 
                if($width > $imgset['maxwidth'] || $height > $imgset['maxheight']){ 
                    $re .= '\\n Width x Height = '. $width .' x '. $height .' \\n The maximum Width x Height must be: '. $imgset['maxwidth']. ' x '. $imgset['maxheight']; 
                } 
     
                if($width < $imgset['minwidth'] || $height < $imgset['minheight']){ 
                    $re .= '\\n Width x Height = '. $width .' x '. $height .'\\n The minimum Width x Height must be: '. $imgset['minwidth']. ' x '. $imgset['minheight']; 
                } 
     
                if($_FILES['upload']['size'] > $imgset['maxsize']*1000){ 
                    $re .= '\\n Maximum file size must be: '. $imgset['maxsize']. ' KB.'; 
                } 
            } 
        }else{ 
            $re .= 'The file: '. $_FILES['upload']['name']. ' has not the allowed extension type.'; 
        } 
        
        // File upload path 
        $f_name = setFName( SITE_PATH .'/'. $upload_dir, F_NAME, ".$type", 0); 
        $uploadpath = '../'.$upload_dir . $f_name; 
    }

    if($re == ''){
        $connection = new mysqli(DB_ADDRESS, DB_USER, DB_PASSWORD, DB_NAME);

        if ($connection->connect_errno) {
            return false;
        }

        if(move_uploaded_file($_FILES['upload']['tmp_name'], $uploadpath)) {
            if($destiny) {
                $CKEditorFuncNum = $_GET['CKEditorFuncNum']; 
                $url = SITE_PATH. $upload_dir . $f_name; 
                $msg = F_NAME .'.'. $type .' successfully uploaded: \\n- Size: '. number_format($_FILES['upload']['size']/1024, 2, '.', '') .' KB'; 
                $re = in_array($type, $imgset['type']) ? "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>":'<script>var cke_ob = window.parent.CKEDITOR; for(var ckid in cke_ob.instances) { if(cke_ob.instances[ckid].focusManager.hasFocus) break;} cke_ob.instances[ckid].insertHtml(\' \', \'unfiltered_html\'); alert("'. $msg .'"); var dialog = cke_ob.dialog.getCurrent();dialog.hide();</script>'; 
            } else {

            }
        }else{
            if($destiny){
                $re = '<script>alert("Unable to upload the file")</script>'; 
            } else {

            }
        }
        $connection->Close();
    }else{
        if($destiny){
            $re = '<script>alert("'. $re .'")</script>'; 
        } else {

        }
    }
    // OBSŁUGA ZAKOŃCZENIA FUNKCJI
    if($destiny){
        @header('Content-type: text/html; charset=utf-8'); 
        echo $re;
    } else {

    }
}

uploadImage($_GET['destiny']);