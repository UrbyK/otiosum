<?php
    include_once './dbh.inc.php';
    $pdo = pdo_connect_mysql();
    
    if (isset($_POST['rid']) && !empty($_POST['rid'])) {
        $rid = $_POST['rid'];
        try {
            $pdo->beginTransaction();
            $sql = "DELETE FROM review WHERE id = $rid";
            $pdo->prepare($sql)->execute();

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