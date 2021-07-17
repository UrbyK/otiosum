<?php

    require_once './session.php';
    include_once './image-upload.inc.php';

    if (!isLogin()) {
        header("Location: ../../login");
        exit();
    } elseif (!isAdmin() && !isMod()) {
        header("Location: ../../index");
        exit();
    }

    if (!isset($_POST['submit'])) {
        header("Location: ../../brand-add");
        exit();
    } else {
        // include db connection
        include_once './dbh.inc.php';
        $pdo = pdo_connect_mysql();

        include_once './xss_cleaner.inc.php';
        $brand = xss_cleaner($_POST['brand']);
        $img = $_FILES['img'];
        print_r($img);

        // check if brand value is given and not empty
        if (isset($brand) && !empty($brand)) {
            // check if image is given and not empty
            if (isset($img) && !empty($img['name'])) {
                // check if brand is unique
                $stmt = $pdo->prepare("SELECT * FROM brand WHERE title LIKE ?");
                $stmt->execute([$brand]);
                if ($stmt->rowCount() == 0) {
                    // careful at writing upload_image query, keep in mind that relative path is set in the
                    // the function and not here, keep an exstra '?' to execute 'data'
                    $query = "INSERT INTO brand (title, image) VALUES (?,?)";
                    $stmt = $pdo->prepare($query);
                    $uploadStatus = upload_image($brand, $img);
                    
                    // check if image upload is not error
                    if (explode("=", $uploadStatus)[0] != "error") {
                        // check if execute was successful
                        if($stmt->execute([$brand, $uploadStatus])) {
                            header("Location: ../../brand-add?status=success");
                            exit();
                        } else {
                            header("Location: ../../brand-add?error=err");
                            exit();
                        }

                    } else {
                    header("Location: ../../brand-add?$uploadStatus");
                    exit();
                    }
                    
                } else {
                    header("Location: ../../brand-add?error=brand-exists");
                    exit();                   
                }

            } else {
                header("Location: ../../brand-add?error=image-empty");
                exit();
            }

        } else {
            header("Location: ../../brand-add?error=brand-empty");
            exit();
        }
        
    }

?>