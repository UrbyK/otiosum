<?php
    include_once './dbh.inc.php';
    function delete_image($pid) {
        $pdo = pdo_connect_mysql();
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
    }
?>