<?php

    // check if it was submited with submit button
    if(!isset($_POST['submit'])) {
        header("Location: ../../category-add");
    } else {

        // import DB connection
        include_once './dbh.inc.php';
        $pdo = pdo_connect_mysql();

        // include xss_cleaner
        include_once './xss_cleaner.inc.php';

        $categories = xss_cleaner($_POST['category']);
        $parents = xss_cleaner($_POST['parent_category']);
        for ($i=0; $i < count($categories); $i++) {
            $category = $categories[$i];
            $parent_id = $parents[$i];
            // if ($parent_id == 0) {
                $stmt = $pdo->prepare("INSERT INTO category(category) VALUES (?)");
                if($stmt->execute([$category])) {
                    echo "Success ".$i.$category.$parent_id."<br>";
                } else {
                    echo "Napaka ".$i." ".$category." ".$parent_id."<br>";
                }
            // } else {
                echo "Ne";
                // $stmt = $pdo->prepare("INSERT INTO category(category, parent_id) VALUES (:cat, :parent)");
                // $stmt->bindParam(':cat', $category, PDO::PARAM_STR);
                // $stmt->bindParam(':parent', $parent_id, PDO::PARAM_INT);
                // $stmt->bindParam(':parent', $parent_id, PDO::PARAM_INT);
                
                // if($stmt->execute()) {
                //     echo "Success ".$i.$category.$parent_id."<br>";
                // } else {
                //     echo "Napaka ".$i." ".$category." ".$parent_id."<br>";
                // }
            // }
        }
        echo "Stuff is done";
    }

?>

