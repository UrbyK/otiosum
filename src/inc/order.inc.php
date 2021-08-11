<?php
    include '../../functions.php';
    if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == "insertData") {
        if(isset($_POST['paymentMethod']) && !empty($_POST['paymentMethod'])) {
            if(isset($_POST['deliveryMethod']) && !empty($_POST['deliveryMethod'])) {
                $pmID = $_POST['paymentMethod'];
                $dmID = $_POST['deliveryMethod'];
                print_r($_POST);
                $pdo = pdo_connect_mysql();
                try {
                    $pdo->beginTransaction();

                    $pdo->commit();
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    echo $e->getMessage();
                }
            }
        }
    }

?>