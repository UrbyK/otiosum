<?php
    include './src/inc/dbh.inc.php';
    include './src/inc/xss_cleaner.inc.php';

    // pagination script
    include './pagination.php';
    
    // Get all main menu navigation items 
    function main_menu_navigation($table){
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE parent_id IS NULL");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // Get all children of the navigation 
    function main_menu_navigation_sub($table, $parent){
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE parent_id = ?");
        $stmt->execute([$parent]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // Checks if an item has children
    function has_children($table, $parent){
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare("SELECT count(id) as nc FROM $table WHERE parent_id = $parent"); // nc -> Number of Children
        $stmt->execute();
        $result = $stmt->fetch();
        return $result["nc"];
    }

    // Return all countries
    function countries(){
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare("SELECT * FROM country");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // create a category tree 
    function categoryTree($cid=[], $parent_id = 0, $sub_mark = '') {
        $pdo = pdo_connect_mysql();
        if ($parent_id != 0) {
            $stmt = $pdo->query("SELECT * FROM category WHERE parent_id = $parent_id ORDER BY category ASC");
        } else {
            $stmt = $pdo->query("SELECT * FROM category WHERE parent_id IS NULL ORDER BY category ASC");
        }
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $item) {

                // check if given id is in arrey of active IDs and mark option as selected
                if(in_array($item['id'], $cid)) {
                    echo "<option value=".$item['id']." selected>".$sub_mark.$item['category']."</option>";                    
                } else {
                    echo "<option value=".$item['id'].">".$sub_mark.$item['category']."</option>";
                }
                categoryTree($cid, $item['id'], $sub_mark.'-');
            }
        }
    }    

    // select all data from brands
    function brands() {
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->query("SELECT * FROM brand");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // select all data from sales
    function sales() {
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->query("SELECT * FROM sale ORDER BY date_start ASC, date_end ASC, discount ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // convert DB save date format to more user frendly output
    function format_date($format, $date) {
        return date("$format", strtotime($date));
    }

    //get all data from products

    function products() {
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->query("SELECT * FROM product");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // get all images by given product id
    function productImages($pid) {
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->query("SELECT * FROM product_image WHERE product_id = $pid");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

?>