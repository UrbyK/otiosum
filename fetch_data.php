<?php
    //include_once './src/inc/dbh.inc.php';
    include_once './functions.php';

    if (isset($_POST['action']) && $_POST['action'] == "fetch_data") {
        $select = "SELECT p.* FROM product p ";
        $where = " WHERE date_published <= CURDATE()";
        $join = "";
        $groupBy = " GROUP BY p.id";
        $orderBy = "";
        $limit ="";

        // join with categories
        if (isset($_POST['category']) && !empty($_POST['category'])){
            $join .= " INNER JOIN product_category ON p.id = product_category.product_id";
            $category_filter = implode(",", $_POST["category"]);
            $where .= " AND product_category.category_id IN ($category_filter)";
        }

        // filter by in berween price
        if (isset($_POST["minPrice"], $_POST["maxPrice"]) && !empty($_POST["minPrice"]) && !empty($_POST["maxPrice"])) {
            $minPrice = $_POST['minPrice'];
            $maxPrice = $_POST['maxPrice'];
            $where .= " AND price BETWEEN $minPrice AND $maxPrice";
        }

        // filter by search input
        if (isset($_POST['search']) && !empty($_POST['search'])) {
            $search = $_POST['search'];
            $where .= " AND title LIKE '%$search%'";
        }

        // filter by brands
        if (isset($_POST['brand']) && !empty($_POST['brand'])){
            $brand_filter = implode(",", $_POST["brand"]);
            $where .= " AND brand_id IN ($brand_filter)";
        }

        // limit per page
        if (isset($_POST['limit']) && !empty($_POST['limit'])) {
            $lim = $_POST['limit'];
            if (isset($_POST['page']) >= 1) {
                $start = (($_POST['page'])-1) * $lim;
                $page = ($_POST['page']);
            } else {
                $start = 0;
            }
            $limit = " LIMIT $start, $lim";

        }

        // sort order
        if (isset($_POST['sortBy']) && !empty($_POST['sortBy'])) {
            $sort = $_POST['sortBy'];
            if ($sort == "minPrice") {
                $orderBy .= " ORDER BY price ASC";
            } elseif ($sort == "maxPrice") {
                $orderBy .= " ORDER BY price DESC";
            } elseif ($sort == "rating") {
                $join.= " LEFT JOIN review r ON p.id = r.product_id";
                $orderBy .= " ORDER BY avg(rating) DESC";

            }
        }

        // get all data
        $query = $select . $join . $where . $groupBy . $orderBy . $limit;
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalRow = $stmt->rowCount();
        

        //pagination data
        $stmt = $pdo->prepare($select.$join.$where.$groupBy.$orderBy);
        $stmt->execute();
        $total_products = $stmt->rowCount();

        $total_pages = ceil($total_products/(int)$lim);

        // output
        $output = "";
        $pagination ="";
        if ($totalRow > 0) {
            foreach($result as $product) {
                $output .= productCard($product);
            }
            $pagination = ajaxPagination($page,$total_pages);
        } else {
            $output = "<h3>Nismo našli izdelka</h3>";
        }

        $return = ['output' => $output, 'pagination'=> $pagination];

        $response = json_encode($return);
        echo $response;
        exit();
    }
?>