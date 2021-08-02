<?php
    //include_once './src/inc/dbh.inc.php';
    include_once './functions.php';
    if(isset($_POST['action'])) {
        $date = date('Y-m-d');
        $query = "SELECT * FROM product WHERE date_published <= CURDATE()";
        $data=[];
        // if(isset($_POST["minimum_price"], $_POST["maximum_price"]) && !empty($_POST["minimum_price"]) && !empty($_POST["maximum_price"])) {
        // $minPrice = $_POST['minimum_price'];
        // $maxPrice = $_POST['maximum_price'];
        //     $query .= "AND price BETWEEN $minPrice AND $maxPrice";
        // }

        if(isset($_POST['search'])) {
            $search = $_POST['search'];
            $query .= "
             AND title LIKE '%$search%'
            ";
        }

        if(isset($_POST['brand'])){
            $brand_filter = implode(",", $_POST["brand"]);
            $query .= "
             AND brand_id IN ($brand_filter);
            ";
        }
        $pdo = pdo_connect_mysql();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalRow = $stmt->rowCount();
        $output = "";

        if($totalRow > 0) {
            foreach($result as $product) {
                // $output .= '
                // <div class="col-md-3 col-lg-3 col-sm-4">
                //     <div class="card">
                //         <div class="card-body">
                //             <h3>'.$product['title'].'</h3>
                //         </div>
                //     </div>
                // </div>';

                $output .= productCard($product);

            }
        } else {
            $output = "<h3>Nismo našli izdelka</h3>";
        }
        echo $output;
    }
?>