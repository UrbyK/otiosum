<?php
    include_once './dbh.inc.php';
    $pdo = pdo_connect_mysql();

    if (isset($_POST['sid']) && !empty($_POST['sid'])) {
        $sid = $_POST['sid'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $discount = $_POST['discount'];
        try {
            $pdo->beginTransaction();

            $sql = "UPDATE sale SET date_start = :startDate, date_end = :endDate, discount = :discount WHERE id = $sid";
            $pdo->prepare($sql)->execute(["startDate"=>$startDate, "endDate"=>$endDate, "discount"=>$discount]);

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