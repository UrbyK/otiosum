<?php
    include './src/inc/dbh.inc.php';
    include './src/inc/xss_cleaner.inc.php';

    /* Template header */
    // function template_header($title){
    //     include_once('./header.php');
    // }

    // /* Template footer */
    // function template_footer(){
    //     include_once('./footer.php');
    // }


    function user_login_status() {
        if(isset($_SESSION['login']) && !empty($_SESSION['login']) 
                && isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }

    /* Get all main menu navigation items */
    function main_menu_navigation($table){
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE parent_id IS NULL");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /* Get all children of the navigation */
    function main_menu_navigation_sub($table, $parent){
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE parent_id = ?");
        $stmt->execute([$parent]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /* Checks if an item has children */
    function has_children($table, $parent){
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare("SELECT count(id) as nc FROM $table WHERE parent_id = $parent");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result["nc"];
    }

    /* Return all countries */
    function countries(){
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare("SELECT * FROM country");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

?>