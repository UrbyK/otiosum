<?php
    include_once './dbh.inc.php';
    $pdo = pdo_connect_mysql();

    if (isset($_POST['sid']) && !empty($_POST['sid'])) {
        $sid = $_POST['sid'];
        try {
            $pdo->beginTransaction();

            $sql = "DELETE FROM product_sale WHERE sale_id = $sid";
            $pdo->prepare($sql)->execute();

            $sql = "DELETE FROM sale WHERE id = $sid";
            $pdo->prepare($sql)->execute();
             
            $pdo->commit();
            echo "OK";
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo $e->getMessage();
            exit();
        }
        exit();
    }
    exit();
?>