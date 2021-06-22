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
        if(isset($_SESSION['loggedin']) && !empty($_SESSION['loggedin']) 
                && isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }

    function isAdmin() {
        if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
            return true;
        }
        return false;
    }

    function isMod() {
        if (isset($_SESSION['moderator']) && !empty($_SESSION['moderator'])) {
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
        $stmt = $pdo->prepare("SELECT count(id) as nc FROM $table WHERE parent_id = $parent"); // nc -> Number of Children
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
    
    /* Return country name, insert ID */
    // function get_country_name($id){
    //     $pdo = pdo_connect_mysql();
    //     $stmt = $pdo -> prepare("SELECT country FROM country WHERE id = ?");
    //     $stmt->execute([$id]);
    //     $result = $stmt->fetch();
    //     return $result['country'];
    // }

    function categoryTree($parent_id = 0, $sub_mark = '') {
        $pdo = pdo_connect_mysql();
        if ($parent_id != 0) {
            $stmt = $pdo->query("SELECT * FROM category WHERE parent_id = $parent_id ORDER BY category ASC");
        } else {
            $stmt = $pdo->query("SELECT * FROM category WHERE parent_id IS NULL ORDER BY category ASC");
        }
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $item) {
                echo "<option value=".$item['id'].">".$sub_mark.$item['category']."</option>";
                categoryTree($item['id'], $sub_mark.'-');
            }
        }
    }    

?>