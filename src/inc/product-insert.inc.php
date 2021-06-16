<?php
 
    if (!isset($_POST['submit'])) {
        header("Location: ../../product-add.php");
    } else {
        // impoprt DB conenction
        include_once "./dbh.inc.php";
        $pdo = pdo_connect_mysql();

        // import xss_cleaner function
        include_once "./xss_cleaner.inc.php";

        $title = xss_cleaner($_POST['title']);
        $summary = xss_cleaner($_POST['summary']);
        $description = xss_cleaner($_POST['description']);
        $sku = xss_cleaner($_POST['sku']);
        $quantity = xss_cleaner($_POST['quantity']);
        $price = xss_cleaner($_POST['price']);
        $data = "";
        // check if categories were selected and save to new array
        if (isset($_POST['category'])) {
            $data = xss_cleaner($_POST['category']);
        }

        // insert into product
        $stmt = $pdo->prepare("INSERT INTO product (title, summary, description, sku, quantity, price) VALUES(?,?,?,?,?,?)");
        if ($stmt->execute([$title, $summary, $description, $sku, $quantity, $price])) {

            $product_id = $pdo->lastInsertId();
            echo "uspeh ".$product_id."<br>";
            
            foreach ($data as $category_id) {
                $stmt = $pdo->prepare("INSERT INTO product_category (product_id, category_id) VALUES(?,?)");
                   if ($stmt->execute([$product_id,$category_id])) {
                       echo "poglej category";
                   } else {
                       echo "napaka pri category";
                   }
            }

        } else {
            echo "prišlo do napake";
        }
        echo $title." ".$summary." ".$description." ".$sku." ".$quantity." ".$price."<br>";
        print_r($data);


    }


    // if (isset($_POST['category'])): 
    //     $data = $_POST['category']; 
    //     print_r($data);
    //     // foreach ($data as $item): 
    //     //     echo $item."<br>"; 
    //     // endforeach;
    // else:
    //     echo "Ni podatkov";
    // endif;
?>