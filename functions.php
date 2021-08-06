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
    function rootCategories(){
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare("SELECT * FROM category WHERE parent_id IS NULL");
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

    // select all categories
    function categories() {
        $pdo = pdo_connect_mysql();
        $query = "SELECT * FROM category ORDER BY parent_id";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // select all data from brands
    function brands() {
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->query("SELECT * FROM brand");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // number of products per brand
    function numItemsPerBrand($bid, $cid=0) {
        $pdo = pdo_connect_mysql();
        if($cid==0) {
            return $pdo->query("SELECT id FROM product WHERE brand_id = $bid")->rowCount();
        } else {
            return $pdo->query("SELECT id FROM product p INNER JOIN product_category pc ON p.id = pc.product_id WHERE brand_id = $bid AND pc.category_id = $cid")->rowCount();
        }
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

    // return disocunt data for a product
    function discountData($pid) {
        $pdo = pdo_connect_mysql();
        $query = "SELECT s.id, s.date_start, s.date_end, s.discount FROM sale s INNER JOIN product_sale ps ON s.id = ps.sale_id WHERE ps.product_id = $pid AND CURDATE() BETWEEN date_start and date_end ORDER BY date_start ASC, date_end ASC, discount ASC";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
    // calculates retail price if there are sales
    function retailPrice($price, $discount) {
        return round($price - ($price*($discount/100)), 2);
    }

    // get all measurmetns for a product
    function measurement($pid) {
        $pdo = pdo_connect_mysql();
        $query = "SELECT * FROM measurement WHERE product_id = $pid";
        return $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
    }

    // get user data 
    function user($aid){
        $pdo = pdo_connect_mysql();
        return $pdo->query("SELECT * FROM account WHERE id = $aid")->fetch(PDO::FETCH_ASSOC);
    }

    // get all reviews for a product newest to oldest
    function productReviews($pid) {
        $pdo = pdo_connect_mysql();
        $query = "SELECT * FROM review WHERE product_id = $pid ORDER BY id DESC";
        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }


    /* Returns average rating for an item */
    function average_rating($pid) {
        $pdo = pdo_connect_mysql();
        $query  = "SELECT rating FROM review WHERE product_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$pid]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i=0;
        $sum = 0;
        $average = 0;
        foreach($items as $item) {
            if($item['rating'] > 0){
                
                $i++;
                $sum += $item['rating'];
            }        
        }
        if($i!=0) $average = $sum / $i;
        return $average;
    }

    // number of ratings for a product
    function number_of_ratings($pid) {
        $pdo = pdo_connect_mysql();
        $query = "SELECT rating FROM review WHERE product_id = $pid";
        $stmt = $pdo->query($query);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i=0;
        foreach($items as $item) {
            if($item['rating'] > 0){
                $i++;
            }        
        }

        return $i;
    }

    // products card file import
    function productCard($product) {
        ob_start();
            include './products-card.php';
        return ob_get_clean();
    }

    function ajaxPagination($page, $totalPages) {
        ob_start();
            include './products-card-pagination.php';
        return ob_get_clean();
    }

    function reviewThemplate($review, $user) {
        ob_start();
            include './review.php';
        return ob_get_clean();
    }

    // retunrr 1D array of childs for a parent
    function fetch_recursive($src_arr, $currentid, $parentfound = false, $cats = array()) {
        foreach($src_arr as $row)
        {
            if((!$parentfound && $row['id'] == $currentid) || $row['parent_id'] == $currentid)
            {
                $rowdata = array();
                foreach($row as $k => $v)
                    $rowdata[$k] = $v;
                $cats[] = $rowdata;
                if($row['parent_id'] == $currentid)
                    $cats = array_merge($cats, fetch_recursive($src_arr, $row['id'], true));
            }
        }
        return $cats;
    }

    // check for valid brands for a category
    function validBrands($cid=0) {
        $pdo = pdo_connect_mysql();        
        if($cid==0) {
            $query = "SELECT distinct(b.id), b.title FROM brand b INNER JOIN product p ON b.id = p.brand_id INNER JOIN product_category pc ON p.id = pc.product_id";
        } else {
            $query = "SELECT distinct(b.id), b.title FROM brand b INNER JOIN product p ON b.id = p.brand_id INNER JOIN product_category pc ON p.id = pc.product_id WHERE pc.category_id = $cid";
        }
        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // returns min and max price for selected main category
    function minMaxPrice($cid) {
        $pdo = pdo_connect_mysql();        
        if($cid==0) {
            $query = "SELECT MIN(price) as minPrice, MAX(price) as maxPrice FROM product p INNER JOIN product_category pc ON p.id = pc.product_id WHERE p.date_published <= CURDATE()";
        } else {
            $query = "SELECT MIN(price) as minPrice, MAX(price) as maxPrice FROM product p INNER JOIN product_category pc ON p.id = pc.product_id WHERE p.date_published <= CURDATE() AND pc.category_id = $cid";
        }
        return $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
    }

?>