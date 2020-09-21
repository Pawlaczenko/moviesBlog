<?php
    require_once '../../config.php';
    require_once MODEL_PATH . 'user.php';

    $u = new User();
    $id = $_POST['id'];

    $result = $u->getUserById($id);

    echo json_encode($result);
?>