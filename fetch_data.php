<?php
    //include_once './src/inc/dbh.inc.php';
    include_once './functions.php';
    if(isset($_POST['action'])) {
        $date = date('Y-m-d');
        $select = "SELECT * FROM product ";
        $where = " WHERE date_published <= CURDATE()";
        $join = "";
        $groupBy = "";
        $orderBy = "";
        $data=[];

        // join with categories
        if(isset($_POST['category']) && !empty($_POST['category'])){
            $join .= " INNER JOIN product_category ON product.id = product_category.product_id";
            $category_filter = implode(",", $_POST["category"]);
            $where .= " AND product_category.category_id IN ($category_filter)";
            $groupBy .= " GROUP BY product.id";
        }

        // filter by in berween price
        if(isset($_POST["minPrice"], $_POST["maxPrice"]) && !empty($_POST["minPrice"]) && !empty($_POST["maxPrice"])) {
            $minPrice = $_POST['minPrice'];
            $maxPrice = $_POST['maxPrice'];
            $where .= " AND price BETWEEN $minPrice AND $maxPrice";
        }

        // filter by search input
        if(isset($_POST['search']) && !empty($_POST['search'])) {
            $search = $_POST['search'];
            $where .= " AND title LIKE '%$search%'";
        }

        // filter by brands
        if(isset($_POST['brand']) && !empty($_POST['brand'])){
            $brand_filter = implode(",", $_POST["brand"]);
            $where .= " AND brand_id IN ($brand_filter)";
        }
        $query = $select . $join . $where . $groupBy . $orderBy;
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalRow = $stmt->rowCount();
        $output = "";

        if($totalRow > 0) {
            foreach($result as $product) {
                $output .= productCard($product);
            }
        } else {
            $output = "<h3>Nismo našli izdelka</h3>";
        }
        echo $output;
    }
?>