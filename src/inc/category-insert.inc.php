<?php

    // check if it was submited with submit button
    if(!isset($_POST['submit'])) {
        header("Location: ../../category-add");
        exit();
    } else {

        // import DB connection
        include_once './dbh.inc.php';
        $pdo = pdo_connect_mysql();
        //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // include xss_cleaner
        include_once './xss_cleaner.inc.php';

        $categories = xss_cleaner($_POST['category']);
        $parents = xss_cleaner($_POST['parent_category']);
        
        try {
            $pdo->beginTransaction();
            // interate througl all categories and parent ids
            for ($i=0; $i < count($categories); $i++) {

                $category = $categories[$i];
                $parent_id = $parents[$i];

                // check if new insert is supposed to be a root category
                if (empty($parent_id) || $parent_id == 0) {

                    $query = "INSERT INTO category(category) VALUES(:category)";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':category', $category);

                } else {

                    $query = "INSERT INTO category(category, parent_id) VALUES(:category, :parent_id)";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':category', $category);
                    $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);

                }

                // execute 
                $stmt->execute();
            }
            // commit inserts
            $pdo->commit();
        // catch exceptions/errors
        } catch (PDOException $e) {
            // rollback all inserts if any insert fails
            $pdo->rollBack();
            header("Location: ../../category-add?status=error");
            exit();
        }
        $pdo = null;
        header("Location: ../../category-add?status=success");
        exit();
    }

?>

