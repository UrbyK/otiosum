<?php
    include_once './dbh.inc.php';
    include_once './xss_cleaner.inc.php';
    $pdo = pdo_connect_mysql();

    if(isset($_POST['pid']) && !empty($_POST['pid'])) {
        if(isset($_POST['aid']) && !empty($_POST['aid'])) {
            if(isset($_POST['comment']) && !empty($_POST['comment'])) {
                $pid = $_POST['pid'];
                $aid = $_POST['aid'];
                $comment = xss_cleaner($_POST['comment']);
                if(!empty($_POST['rating'])){
                    $rating = $_POST['rating'];
                } else {
                    $rating = null;
                }

                try {
                    $pdo->beginTransaction();
                    $sql = "INSERT INTO review(product_id, account_id, rating, comment) VALUES(?,?,?,?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$pid, $aid, $rating, $comment]);

                    $pdo->commit();
                    echo "OK";
                    exit();
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    echo $e->getMessage();
                    exit();
                }

            } else {
                exit();
            }
        } else {
            exit();
        }
    } else {
        exit();
    }
?>