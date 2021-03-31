<?php
    
    session_start();
    include './functions.php';

    $pdo = pdo_connect_mysql();

    $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    template_header("Otiosum");

    $page = isset($_GET['page']) && file_exists($_GET['page']. '.php') ? $_GET['page'] : 'home';
    include $page . '.php';

    template_footer();

?>