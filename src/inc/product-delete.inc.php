<?php
    include_once './dbh.inc.php';
    $pdo = pdo_connect_mysql();
    
    if (isset($_POST['pid']) && !empty($_POST['pid'])) {
        $pid = $_POST['pid'];
    
        try {
            $pdo->beginTransaction();

            // delete all reviews for a product
            $sql = "DELETE FROM review WHERE product_id = $pid";
            $pdo->prepare($sql)->execute();

            // delete all product-category connection
            $sql = "DELETE FROM product_category WHERE product_id = $pid";
            $pdo->prepare($sql)->execute();

            // delete product measurements
            $sql = "DELETE FROM measurement WHERE product_id = $pid";
            $pdo->prepare($sql)->execute();

            // delete all product sale connection
            $sql = "DELETE FROM product_sale WHERE product_id = $pid";
            $pdo->prepare($sql)->execute();

            // delete product images
            $query = "SELECT * FROM product_image WHERE product_id = $pid";
            $stmt = $pdo->query($query);
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($images as $image) {
                // remove image from directory
                unlink("../.".$image['image']);
                
                $sql = "DELETE FROM product_image WHERE product_id = $pid";
                $pdo->prepare($sql)->execute();
            }

            // delete product
            // check if product is in order_items table
            $query = "SELECT count(*) FROM order_item WHERE product_id = $pid";
            $stmt = $pdo->query($query);
            $res = $stmt->fetchColumn();
            if($res > 0) {
                $pdo->query('SET foreign_key_checks = 0');
                $sql = "DELETE p FROM product p INNER JOIN order_item ot ON p.id = ot.product_id WHERE p.id = $pid";
                $pdo->prepare($sql)->execute();
            } else {
                $sql = "DELETE FROM product WHERE id = $pid";
                $pdo->prepare($sql)->execute();
            }
            $pdo->commit();
            echo "OK";
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo $e->getMessage();
            exit();
        }
        exit();
    }
    exit();

?>